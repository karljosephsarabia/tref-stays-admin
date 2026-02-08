<?php

namespace App\Console\Commands;

use DOMDocument;
use Exception;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Wav;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use SMD\Common\Models\Recording;
use SMD\Common\ReservationSystem\Models\RsHebrewAudio;

class GenerateHebrewAudios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hebaudio:generate
                            {--t|truncate_table : Truncate rs_hebrew_audios table before generating new audio files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Hebrew audio files';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $truncate_table = $this->option('truncate_table');

        if($truncate_table){
            Schema::disableForeignKeyConstraints();
            DB::statement('truncate table `rs_hebrew_audios`;');
            Schema::enableForeignKeyConstraints();

            //insert english recordings for hebrew audio tool
            $recordings = Recording::where('system_name', 'like', 'rsapp%')
                ->where('name', 'not like', '% Text%')
                ->whereNotNull('tts_text')
                ->where('tts_text', 'not like', '%,, ,%')
                ->where('type', 'system')
                ->orderBy('id', 'asc')->get();

            foreach($recordings as $recording){
                $rs_hebrew_audio = new RsHebrewAudio();
                $rs_hebrew_audio->recording_id = $recording->id;
                $rs_hebrew_audio->name = $recording->name;
                $rs_hebrew_audio->system_name = $recording->system_name;
                $rs_hebrew_audio->tts_text = $recording->tts_text;
                $rs_hebrew_audio->hebrew_system_name = $recording->system_name . '-hebrew';
                $rs_hebrew_audio->tts_update = true;
                $rs_hebrew_audio->save();
            }
        }
        //
        $log_file_path =  __DIR__ . '/../../../tmp/hebrew_audio_creation_log.txt';
        $log_file = fopen($log_file_path,'a');
        fwrite($log_file, "\n");

        $this->info('Generating Hebrew audio files...');

        //load the records to be processed
        $rs_heb_audio = RsHebrewAudio::where('tts_update', true)->get();

        //process each record
        $requests = 0;
        foreach ($rs_heb_audio as $item) {
            $this->info('Processing record: ' . $item->id);
            fwrite($log_file, 'Processing record: ' . $item->id);
            try {
                $result = $this->translateEnglishTextToHebrew($item->tts_text);
                $res = $this->generateAudio($result, $item->hebrew_system_name);
                if($res == true) {
                    $item->tts_update = false;
                    $item->save();
                    fwrite($log_file, " created: $item->id => $item->hebrew_system_name" . "\n");
                } else {
                    fwrite($log_file, " failed: $item->id => $item->hebrew_system_name" . "\n");
                }
            } catch (Exception $e) {
                fwrite($log_file, " error: $item->id => $item->hebrew_system_name => " . $e->getMessage()."\n");
                $this->error('Error: ' . $e->getMessage());
            }

            $requests++;
            if($requests >= 20) {
                $requests = 0;
                $this->info('Waiting for 60 seconds...');
                fwrite($log_file, 'Waiting for 60 seconds...'."\n");
                sleep(60);
            }
        }

        fclose($log_file);
        return 0;
    }

    private function translateEnglishTextToHebrew($text)
    {
        $apiKey = 'd8d57fd2c20c475eaae6af24f5567705';
        $host = 'https://api.cognitive.microsofttranslator.com';
        $path = "/translate?api-version=3.0";
        $params = "&from=en&to=he";
        $location = 'eastus';
        $requestBody = array (
            array (
                'Text' => $text,
            ),
        );
        $content = json_encode($requestBody);

        $headers = "Content-type: application/json; charset=UTF-8\r\n" .
            "Content-length: " . strlen($content) . "\r\n" .
            "Ocp-Apim-Subscription-Key: $apiKey\r\n" .
            "Ocp-Apim-Subscription-Region: ". $location."\r\n";

        $options = array (
            'http' => array (
                'header' => $headers,
                'method' => 'POST',
                'content' => $content
            )
        );

        $context  = stream_context_create ($options);

        $result = file_get_contents ($host . $path . $params, false, $context);
        //GeneralHelper::userLog('RsApp Asterisk hebrew translate raw result', null, $result);
        $json = json_encode(json_decode($result), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        //GeneralHelper::userLog('RsApp Asterisk hebrew translate json', null, $json);
        $json = json_decode($result, true);
        //GeneralHelper::userLog('RsApp Asterisk hebrew translate decoded json', null, $json);
        return $json[0]['translations'][0]['text'];
    }

    private function generateAudio($text, $systemName)
    {
        $region = 'eastus'; //env('AZURE_REGION');
        $accessTokenUri = 'https://' . $region . '.api.cognitive.microsoft.com/sts/v1.0/issuetoken';
        $apiKey = '65fefdd6b10c47279c75f08d5261fb0e'; //env('AZURE_API_KEY');

        $options = array(
            'http' => array(
                'header'  => "Ocp-Apim-Subscription-Key: ".$apiKey."\r\n" .
                    "content-length: 0\r\n",
                'method'  => 'POST',
            ),
        );

        $context  = stream_context_create($options);

        //get the Access Token
        $access_token = file_get_contents($accessTokenUri, false, $context);

        if (!$access_token) {
            throw new Exception("Problem with token $accessTokenUri, ***[$php_errormsg]***");
        }
        //GeneralHelper::userLog('RsApp Asterisk hebrew audio', null, 'access token: ' . $access_token);
        //=========================================================
        $ttsServiceUri = "https://".$region.".tts.speech.microsoft.com/cognitiveservices/v1";

        $doc = new DOMDocument();

        $root = $doc->createElement( "speak" );
        $root->setAttribute( "version" , "1.0" );
        $root->setAttribute( "xml:lang" , "en-US" );

        /*
        $voice = $doc->createElement( "voice" );
        $voice->setAttribute( "xml:lang" , "en-us" );
        $voice->setAttribute( "xml:gender" , "Male" );
        $voice->setAttribute( "name" , "en-US-GuyNeural"); // Short name for "Microsoft Server Speech Text to Speech Voice (en-US, Guy24KRUS)"
        */

        $voice = $doc->createElement( "voice" );
        $voice->setAttribute( "xml:lang" , "he-IL" );
        $voice->setAttribute( "xml:gender" , "Male" );
        $voice->setAttribute( "name" , "he-IL-AvriNeural"); // Short name for "Microsoft Server Speech Text to Speech Voice (en-US, Guy24KRUS)"

        $text2 = $doc->createTextNode($text);

        $voice->appendChild( $text2 );
        $root->appendChild( $voice );
        $doc->appendChild( $root );
        $data = $doc->saveXML();

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/ssml+xml\r\n" .
                    "X-Microsoft-OutputFormat: audio-16khz-32kbitrate-mono-mp3\r\n" .
                    "Authorization: "."Bearer ".$access_token."\r\n" .
                    "X-Search-AppId: 07D3234E49CE426DAA29772419F436CA\r\n" .
                    "X-Search-ClientID: 1ECFAE91408841A480F00935DC390960\r\n" .
                    "User-Agent: TTSPHP\r\n" .
                    "content-length: ".strlen($data)."\r\n",
                'method'  => 'POST',
                'content' => $data,
            ),
        );

        $context  = stream_context_create($options);

        // get the wave data
        $result = file_get_contents($ttsServiceUri, false, $context);
        if (!$result) {
            throw new Exception("Problem with $ttsServiceUri, ***[$php_errormsg]***");
        }
        else{
            //echo "Wave data length: ". strlen($result);
            $file = __DIR__ . '/../../../tmp/hebrew_' . str_random(20) . '_tmp_audio.mp3';
            file_put_contents($file, $result);

            $sounds_dir = env('ASTERISK_SOUND_DIR') . DIRECTORY_SEPARATOR;
            $fileDest = $systemName . '.wav';
            //$ret = $this->saveFile($file, $sounds_dir . $fileDest);
            $ret = true;
            file_put_contents($sounds_dir . $fileDest, $result);
            unlink($file);
            return $ret;
        }
    }

    private function saveFile($from, $to)
    {
        try {
            $ffmpeg = FFMpeg::create(array(
                'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                'ffprobe.binaries' => '/usr/bin/ffprobe',
                'timeout' => 3600, // The timeout for the underlying process
                'ffmpeg.threads' => 12,
            ));

            $audio = $ffmpeg->open($from);
            $audio->filters()->resample(8000);

            $format = new Wav();
            $format->setAudioChannels(1);
            $format->setAudioKiloBitrate(16);

            $audio->save($format, $to);
            return true;
        } catch (\Exception $e) {
            $this->info('RsApp Asterisk hebrew audio saveFile error : ['. $to .'] ' . $e->getMessage());
            return false;
        }
    }
}
