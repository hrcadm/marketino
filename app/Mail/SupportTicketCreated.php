<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketCreated extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  string  $subject
     */
    public function __construct(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $replyTo = config('mail.reply-to') ?? config('mail.from.address');

        return $this->replyTo($replyTo)
                    ->markdown('mail.support_ticket_created');
    }
}
