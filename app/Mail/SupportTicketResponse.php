<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketResponse extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $supportTicketReply;

    /**
     * Create a new message instance.
     *
     * @param  string  $subject
     * @param  string  $message
     */
    public function __construct(string $subject, string $message)
    {
        $this->supportTicketReply = $message;
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
                    ->markdown('mail.support_ticket_response');
    }
}
