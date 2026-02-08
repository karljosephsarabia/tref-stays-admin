<?php

namespace App\Channels\Messages;

class TwilioSmsMessage
{
    public $content;

    /**
     * @param string $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }
}