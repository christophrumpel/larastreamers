<?php

namespace App\Mail;

use App\Models\Stream;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StreamSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Stream $stream
    ) {}

    public function build(): self
    {
        return $this
            ->from('noreply@larastreamers.com', 'Larastreamers')
            ->subject('Stream submitted on Larastreamers')
            ->markdown('mail.submitted');
    }
}
