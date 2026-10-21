@php
    $phoneDisplay = config('company.phone_display') ?: config('company.phone');
    $lineId = config('company.line_id');
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
                @if ($phoneDisplay)
                    โทร {{ $phoneDisplay }}
                @endif
                @if ($phoneDisplay && $lineId)
                    ·
                @endif
                @if ($lineId)
                    LINE {{ '@'.$lineId }}
                @endif
            </span>
            @if (config('company.office_hours'))
                <span>เวลาทำการ {{ config('company.office_hours') }}</span>
            @endif
        </div>

        <span class="self-end">© {{ date('Y') }} {{ config('company.name') }}</span>
    </x-frontend.container>
</footer>
