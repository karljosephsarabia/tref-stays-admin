<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class TwilioSmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        try {
            $message = $notification->toTwilioSms($notifiable);

            $to = $notifiable->routeNotificationFor('Sms');
            $from = config('twilio_whitepages.client.sms_twilio_from');
            $sid = config('twilio_whitepages.client.auth.sms_twilio_sid');
            $token = config('twilio_whitepages.client.auth.sms_twilio_token');

            $twilio = new Client($sid, $token);

            return $twilio->messages->create($to, [
                'from' => $from,
                'body' => $message->content
            ]);
        } catch (\Exception $ex) {
            return null;
        }
    }
}