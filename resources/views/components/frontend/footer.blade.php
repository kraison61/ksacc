@php
    $phone = config('company.phone');
    $phoneDisplay = config('company.phone_display') ?: $phone;
    $lineId = config('company.line_id');
    $lineUrl = config('company.line_url');
    $address = config('company.address') ?: implode(' ', config('company.address_lines', []));
@endphp

<footer class="border-t border-white/18 bg-navy px-6 pt-8 pb-10 text-[15px] text-navy-mist">
    <x-frontend.container class="flex flex-wrap items-end justify-between gap-6">
        <div class="flex flex-col gap-1">
            <span class="text-[17px] font-bold text-white">{{ config('company.name') }}</span>
            @if ($address !== '')
                <span>{{ $address }}</span>
            @endif
        </div>

        <div class="flex flex-col gap-1">
            <span>
                @if ($phoneDisplay && $phone)
                    <a href="tel:{{ $phone }}" class="text-inherit no-underline hover:text-white" data-contact-track="phone">โทร {{ $phoneDisplay }}</a>
                @elseif ($phoneDisplay)
                    <span>โทร {{ $phoneDisplay }}</span>
                @endif
                @if ($phoneDisplay && $lineId)
                    ·
                @endif
                @if ($lineId && filled($lineUrl))
                    <a href="{{ $lineUrl }}" class="text-inherit no-underline hover:text-white" data-contact-track="line" target="_blank" rel="noopener noreferrer">LINE {{ '@'.$lineId }}</a>
                @elseif ($lineId)
                    <span>LINE {{ '@'.$lineId }}</span>
                @endif
            </span>
            @if (config('company.office_hours'))
                <span>เวลาทำการ {{ config('company.office_hours') }}</span>
            @endif
        </div>

        <span class="self-end">© {{ date('Y') }} {{ config('company.name') }}</span>
    </x-frontend.container>
</footer>
