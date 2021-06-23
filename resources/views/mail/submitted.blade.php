@component('mail::message')
    Hi,

    A stream titled "[{{ $stream->title }}]({{ $stream->url() }})" was submitted by {{ $vimeo->submitted_by_email }}.

    {{ $link->text }}

    [Approve]({{ $stream->approveUrl() }})

    [Reject]({{ $stream->rejectUrl() }})

    Kr,




    Larastreamers
@endcomponent
