<?php

namespace App\Http\Controllers;

use DOMDocument;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use SMD\Common\Models\Recording;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsHebrewAudio;
use Yajra\Datatables\Facades\Datatables;

class HebrewAudioController extends AppBaseController
{
    //
    public function __construct(){
        $this->middleware('ajax.json')->only(['update','delete']);
    }

    public function show()
    {
        return view('hebrew-audio');
    }

    private function audios()
    {
        return RsHebrewAudio::query();
    }

    public function datatable()
    {
        return Datatables::of($this->audios())
            ->addIndexColumn()
            ->setRowId('id')
            ->make(true);
    }

    public function edit(Request $request, $id = null)
    {
        try{
            $change = 0;
            $audio = $id ? RsHebrewAudio::findOrFail($id) : new RsHebrewAudio();

            if($request->input('tts_text') != $audio->tts_text){
                $audio->tts_text = $request->input('tts_text');
                $change++;
            }

            if($change > 0) {
                $audio->tts_update = true;
                try {
                    $result = $this->translateEnglishTextToHebrew($audio->tts_text);
                    $this->generateAudio($result, $audio->hebrew_system_name);
                } catch (Exception $ex) {
                    GeneralHelper::userLog('RsApp HebrewAudioController edit', null, $ex->getMessage());
                }

                $audio->save();
                return $this->jsonSuccessResponse();
            } else {
                return $this->jsonNoChangeRequiredResponse();
            }

        }  catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse('edit hebrew audio' . '::' . $e->getMessage());
        }
    }

    public function english_play($id = null)
    {
        try {
            $recording = Recording::findOrFail($id);
            return response($recording->contents)->withHeaders([
                'Content-Type' => $recording->mime_type,
                'Content-Transfer-Encoding' => 'Binary',
                'Content-Length' => $recording->size,
                'Content-disposition' => 'attachment; filename="' . $recording->id . '.' . $recording->extension . '"',
            ]);

        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('recording.file_download_error') . ': ' . $e->getMessage());
        }
    }

    public function hebrew_play($id = null) {
        try {
            $audio = RsHebrewAudio::findOrFail($id);

            //check if the file exists
            //$file_path = env('ASTERISK_SOUND_DIR') . DIRECTORY_SEPARATOR . $audio->hebrew_system_name . '.wav';
            $file_path = __DIR__ . '/../../../tmp' . DIRECTORY_SEPARATOR . $audio->hebrew_system_name . '.wav';
            if(file_exists($file_path)) {
                //load the file
                $contents = file_get_contents($file_path);

                return response($contents)->withHeaders([
                    'Content-Type' => 'audio/wav',
                    'Content-Transfer-Encoding' => 'Binary',
                    'Content-Length' => strlen($contents),
                    'Content-disposition' => 'attachment; filename="'. $id .'.wav"',
                ]);
            } else {
                $file_path = env('ASTERISK_SOUND_DIR') . DIRECTORY_SEPARATOR . $audio->hebrew_system_name . '.wav';

                if(file_exists($file_path)) {
                    $contents = file_get_contents($file_path);

                    return response($contents)->withHeaders([
                        'Content-Type' => 'audio/wav',
                        'Content-Transfer-Encoding' => 'Binary',
                        'Content-Length' => strlen($contents),
                        'Content-disposition' => 'attachment; filename="' . $id . '.wav"',
                    ]);
                }
            }
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('recording.file_download_error').': '.$e->getMessage());
        }
    }

    public function hebrew_play2($id = null) {
        try {
            $audio = RsHebrewAudio::findOrFail($id);

            //check if the file exists
            $file_path = env('ASTERISK_SOUND_DIR') . DIRECTORY_SEPARATOR . $audio->hebrew_system_name . '.wav';
            if(file_exists($file_path)) {
                //load the file
                $contents = file_get_contents($file_path);

                return response($contents)->withHeaders([
                    'Content-Type' => 'audio/wav',
                    'Content-Transfer-Encoding' => 'Binary',
                    'Content-Length' => strlen($contents),
                    'Content-disposition' => 'attachment; filename="'. $id .'.wav"',
                ]);
            }
        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('recording.file_download_error').': '.$e->getMessage());
        }
    }

    public function hebrew_play3($id = null) {
        try {
            $audio = RsHebrewAudio::findOrFail($id);

            //load the file
            $contents = $audio->contents;

            return response($contents)->withHeaders([
                'Content-Type' => 'audio/wav',
                'Content-Transfer-Encoding' => 'Binary',
                'Content-Length' => strlen($contents),
                'Content-disposition' => 'attachment; filename="' . $id . '.wav"',
            ]);

        } catch(ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('recording.file_download_error').': '.$e->getMessage());
        }
    }

    private function translateEnglishTextToHebrew($text)
    {
        $apiKey = env('AZURE_TEXT_API_KEY');
        $host = 'https://api.cognitive.microsofttranslator.com';
        $path = "/translate?api-version=3.0";
        $params = "&from=en&to=he";
        $location = env('AZURE_REGION');
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
        $region = env('AZURE_REGION');
        $accessTokenUri = 'https://' . $region . '.api.cognitive.microsoft.com/sts/v1.0/issuetoken';
        $apiKey = env('AZURE_API_KEY');

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

            //$sounds_dir = config('asterisk.sounds_dir', '/var/lib/asterisk/sounds');
            //$sounds_dir .= DIRECTORY_SEPARATOR . config('asterisk.ivrmanager_sounds_dir', 'ivrmanager') . DIRECTORY_SEPARATOR;
            $sounds_dir = __DIR__ . '/../../../tmp/';
            $fileDest = $systemName . '.wav';
            //$this->saveFile($file, $sounds_dir . $fileDest);
            file_put_contents($sounds_dir . $fileDest, $result);
            unlink($file);
        }
    }
}
