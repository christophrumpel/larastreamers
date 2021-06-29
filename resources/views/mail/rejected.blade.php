@component('mail::message')
Hi,

Your stream [{{ $stream->title }}]({{ $stream->url() }}) was rejected.
Maybe it wasn't a good fit, but please try it again the next time.

Thanks,

Christoph
@endcomponent
