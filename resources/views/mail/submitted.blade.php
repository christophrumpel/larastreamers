@component('mail::message')
    Hi,

    A stream titled "[{{ $stream->title }}]({{ $stream->url() }})" was submitted by {{ $stream->submitted_by_email }}.

    [Approve]({{ $stream->approveUrl() }})

    [Reject]({{ $stream->rejectUrl() }})

    Kr,




    Larastreamers
@endcomponent
