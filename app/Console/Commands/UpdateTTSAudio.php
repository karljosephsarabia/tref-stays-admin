<?php

namespace App\Console\Commands;

use App\Audio\AudioHelper;
use Carbon\Carbon;
use Folour\Flavy\Flavy;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use SMD\Amazon\Polly\AmazonPollyClient;
use SMD\Common\Models\Recording;

class UpdateTTSAudio extends Command
{

    const TTS_DIR = 'tmp_tts_audio';

    private $digits_regex = '/((\d)(\d)-?(\d)-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?(\d)?-?)+/';
    private $digits_replace = '$2 $3 $4 $5 $6 $7 $8 $9 $10 $11 $12 $13 $14 $15 $16 $17 $18 $19 $20 $21';
    private $spaces_regex = '/(\s)(\s{1,})(\s)/';
    private $spaces_replace = '$1';

    private $recordings_select = [
        'id',
        'name',
        'description',
        'type',
        'system_name',
        'extension',
        'mime_type',
        'size',
        'activated',
        'tts',
        'tts_update',
        'tts_text',
        'tts_created_at',
        'destination_id',
        'ivr_menu_id',
        'created_at',
        'updated_at',
    ];


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rs_tts:update_audios
                            {--r|recordingId= : Update only the specified Recording ID}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Reservation System TTS audio as required';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $recording_id = $this->hasOption('recordingId') ?
            (is_null_or_empty($this->option('recordingId')) ? 0 : $this->option('recordingId')) : 0;

        $cron_environment_id = env('CRON_ENVIRONMENT_ID', 20);

        $sem_key = 256325  + $cron_environment_id;
        $sem_max_acquire = 1;
        $sem_permission = 0666;
        $sem_auto_release = 1;

        if (!is_dir(Storage::path(self::TTS_DIR))) {
            Storage::makeDirectory(self::TTS_DIR);
        }

        if ($recording_id > 0) {
            $this->runUpdate($recording_id);
            $this->info('Good bye!');
            return 0;
        }

        $sem = sem_get($sem_key, $sem_max_acquire, $sem_permission, $sem_auto_release);

        if (!$sem) {
            $this->info('An error occurred while creating lock');
            return 1;
        }

        if (sem_acquire($sem, true)) {

            $this->runUpdate($recording_id);

            if (sem_release($sem)) {
                if (sem_remove($sem)) {
                    $this->info('Good bye!');
                    return 0;
                } else {
                    $this->error('An error occurred while removing lock');
                    return 1;
                }
            } else {
                $this->error('An error occurred while releasing lock');
                return 1;
            }
        } else {
            $this->info('Looks like another instance running... Will wait!');
            return 1;
        }
    }

    private function runUpdate( $recording_id) {

        $this->info('TTS Audio updater');

        if ($recording_id > 0) {
            $this->info('Will try to load recording: ' . $recording_id);
            $recordings = Recording::whereId($recording_id)
                ->where('is_hebrew', false)
                ->select($this->recordings_select)
                //->where('type', 'rs_property_address')
                ->get();
            if ($recordings->count() === 0) {
                $recording_id = -1;
            }
        } else {
            $this->info('Will try to load all Reservation System TTS recording');
            $recordings = Recording::whereTts(true)
                ->whereRaw('tts_update = true or (tts_created_at <> updated_at and tts_text IS NOT NULL and LENGTH(tts_text) > 0)')
                ->select($this->recordings_select)
                ->where('is_hebrew', false)
                ->where('type', 'rs_property_address')
                ->get();
        }

        foreach ($recordings as $recording) {
            if (!$recording->tts || $recording->is_hebrew) {
                $this->info('This is not a Reservation System TTS recording... doing nothing!');
                continue;
            }

            if ($recording->is_hebrew) {
                $this->info('This is a HEBREW recording... doing nothing!');
                continue;
            }

            if ($recording->tts_update || ($recording->tts_created_at != $recording->updated_at && !is_null_or_empty($recording->tts_text))) {
                try {
                    $this->info('We need to update this Reservation System TTS recording: ' . $recording->id);
                    $this->info('Now we try to TTS...');

                    $ac = new AmazonPollyClient(config('app.aws_access_key'), config('app.aws_secret_key'));
                    //VOICE_US_MALE_$voice;

                    $ac->setDefaultVoice(AmazonPollyClient::VOICE_US_MALE_JOEY);

                    $tts_text_audio = $recording->tts_text;
                    $tts_text_audio = preg_replace('/&/', 'and', $tts_text_audio);
                    $audio_string = $ac->synthesizeMp3At8000($tts_text_audio);
                    $tmp_name = self::TTS_DIR . DIRECTORY_SEPARATOR . time() . str_random() . $recording->id . '.mp3';
                    $this->info('Saving file tmp file...');
                    Storage::put($tmp_name, $audio_string);
                    $path = Storage::path($tmp_name);
                    //$this->info($path);
                    $this->info('Before AudioHelper');//
                    $audio_file =$this->parseAudio($path); //AudioHelper::parseAudio($path);
                    $this->info('After AudioHelper');//
                    $update_date = Carbon::now();
                    $recording->extension = $audio_file['extension'];
                    $recording->mime_type = $audio_file['mime'];
                    $recording->size = $audio_file['size'];
                    $recording->contents = $audio_file['contents'];
                    $recording->updated_at = $update_date;
                    $recording->tts_created_at = $update_date;
                    $recording->tts_update = false;
                    $recording->save();
                    Storage::delete($tmp_name);
                    $this->info('Recording updated!');
                } catch (\Exception $exception) {
                    if ($recording_id > 0) {
                        $recording_id = -1;
                    }
                    $this->error('An exception occurred while TTS-ing RS recording. Exception: ' . $exception->getMessage());
                }
            } else {
                $this->info('No need to update: ' . $recording->id);
            }

        }
    }

    private function parseAudio($audio) {
        /**
         * @var Filesystem
         */
        $fs = new Filesystem();
        $audio_info = (new \Folour\Flavy\Flavy([]))->info($audio);
        $contents = null;
        $size = 0;
        $extension = 'wav';
        $mime_type = 'audio/wav';
        if (strtolower($audio_info['format']['format_name']) != 'wav'
            || count($audio_info['streams']) > 1
            || $audio_info['streams'][0]['bits_per_sample'] != 16
            || $audio_info['streams'][0]['channels'] != 1
            || $audio_info['streams'][0]['codec_name'] != 'pcm_s16le'
            || $audio_info['streams'][0]['time_base'] != '1/8000') {
            // Let's try to convert..
            $tmp_file_name = storage_path().'/'.Uuid::uuid4().'_ca.wav';
            (new \Folour\Flavy\Flavy([]))->from($audio)
                ->to($tmp_file_name)
                ->aCodec('pcm_s16le')
                ->channels(1)
                ->sampleRate(8000)
                ->run();
            $contents = File::get($tmp_file_name);
            $size = File::size($tmp_file_name);
            unlink($tmp_file_name);
        } else {
            $size = $fs->size($audio);
            $contents = $fs->get($audio);
        }
        return [
            'contents' => $contents,
            'size' => $size,
            'extension' => $extension,
            'mime' => $mime_type,
        ];
    }

}
