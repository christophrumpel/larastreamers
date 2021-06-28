@component('mail::message')
    Hi,

    A stream titled "[{{ $stream->title }}]({{ $stream->url() }})" was submitted by {{ $vimeo->submitted_by_email }}.

    [Approve]({{ $stream->approveUrl() }})

    [Reject]({{ $stream->rejectUrl() }})

    Kr,




    Larastreamers
@endcomponent
