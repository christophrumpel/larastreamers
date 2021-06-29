@component('mail::message')
Hi,

A new stream was submitted:
* title: "[{{ $stream->title }}]({{ $stream->url() }})"
* by: {{ $stream->submitted_by_email }}
* language: {{ $stream->language_code }}

🟢 [Approve]({{ $stream->approveUrl() }})

🔴 [Reject]({{ $stream->rejectUrl() }})

Greets,

Larastreamers
@endcomponent
