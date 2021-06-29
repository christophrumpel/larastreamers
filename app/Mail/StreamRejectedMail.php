<?php

namespace App\Mail;

use App\Models\Stream;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StreamRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Stream $stream
    ) {
    }

    public function build(): StreamRejectedMail
    {
        return $this
            ->subject("The stream you've submitted was rejected")
            ->markdown('mail.rejected');
    }
}
