<?php
/**
 * Created by PhpStorm.
 * User: jerdg
 * Date: 6/11/2018
 * Time: 10:21 AM
 */

namespace App\Audio;


use Folour\Flavy\Flavy;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\File;

class AudioHelper
{

    /**
     * Now we convert all audio to Wav PCM 16bit little endian with 8k sample rate.
     * @param string $audio_path
     * @return array
     */
    public static function parseAudio($audio_path) {
        try {
            $audio_info = Flavy::info($audio_path);
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
                $tmp_file_name = storage_path() . '/' . Uuid::uuid4() . '_ca.wav';
                Flavy::from($audio_path)
                    ->to($tmp_file_name)
                    ->aCodec('pcm_s16le')
                    ->channels(1)
                    ->sampleRate(8000)
                    ->run();
                $contents = File::get($tmp_file_name);
                $size = File::size($tmp_file_name);
                unlink($tmp_file_name);
            } else {
                $contents = file_get_contents($audio_path);
                $size = strlen($contents);
            }
            return [
                'contents' => $contents,
                'size' => $size,
                'extension' => $extension,
                'mime' => $mime_type,
            ];
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }


}
