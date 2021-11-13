@props([
    'datetime',
])
<time
    x-data="{
        datetime: moment('{{ $datetime->toIso8601String() }}'),
    }"
    x-text="datetime.isSameOrBefore(moment()) ? datetime.fromNow() : datetime.toNow()"
    datetime="{{ $datetime->toIso8601String() }}"
    {{ $attributes }}
>
    {{ $datetime->diffForHumans() }}
</time>
