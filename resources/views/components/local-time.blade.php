<span
    x-data="{
        formatLocalTimeZone: function (element, timestamp) {
            const timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            const date = moment.unix(timestamp).tz(timeZone);

            element.innerHTML = date.format('{{ $format }}');
        }
    }"
    x-init="formatLocalTimeZone($el, {{ $date->timestamp }})"
    title="{{ $date->diffForHumans() }}"
    {{ $attributes }}
>
    {{ $date->format('Y-m-d H:i') }}
</span>
