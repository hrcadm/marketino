<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendQuote extends Mailable {
    use Queueable, SerializesModels;

    private $quote;
    private $attachment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Quote $quote, string $localFile = null) {
        $this->quote = $quote;
        $this->attachment = $localFile;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): Mailable {
        $out = $this->markdown('mail.quote', ['quote' => $this->quote])->subject(__('mail.quote_subject',['number' => $this->quote->document_number]))->attach($this->attachment, [
            'as'   => $this->quote->getPdfFileName(),
            'mime' => 'application/pdf',
        ]);

        return $out;
    }

}
