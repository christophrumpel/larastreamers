@component('mail::message')
Hi,

Your stream [{{ $stream->title }}]({{ $stream->url() }}) was approved.🥳 You can now find it on our [homepage]({{ route('home') }}).

Thanks,


Christoph
@endcomponent
