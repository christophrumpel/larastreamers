<?php

namespace App\Mail;

use App\Models\Stream;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StreamApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Stream $stream
    ) {}

    public function build(): self
    {
        return $this
            ->from('noreply@larastreamers.com', 'Larastreamers')
            ->subject("The stream you've submitted has been approved")
            ->markdown('mail.approved');
    }
}
