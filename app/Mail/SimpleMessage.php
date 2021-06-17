<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimpleMessage extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected string $body;

    /**
     * Create a new message instance.
     *
     * @param  string  $body
     */
    public function __construct(string $body)
    {
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown(
            'emails.simple_message',
            [
                'body' => $this->body,
            ]
        );
    }
}
