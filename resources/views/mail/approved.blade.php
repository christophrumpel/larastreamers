@component('mail::message')
Hi,

Your stream [{{ $stream->title }}]({{ $stream->url() }}) was approved. You can now view it on [our homepage]({{ route('home') }})

Thanks,


Christoph
@endcomponent
