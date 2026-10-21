<section aria-label="ตัวเลขของเรา" class="border-b border-line bg-white">
    <x-frontend.container class="grid grid-cols-1 gap-6 py-10 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['value' => '10+ ปี', 'label' => 'ประสบการณ์งานบัญชีและภาษี'],
            ['value' => '200+ ราย', 'label' => 'กิจการที่เราดูแลแล้ว'],
            ['value' => '0 ครั้ง', 'label' => 'ไม่เคยโดนค่าปรับยื่นล่าช้า'],
            ['value' => '1 ชม.', 'label' => 'ตอบ LINE ในเวลาทำการ'],
        ] as $stat)
            <div class="flex flex-col gap-0.5 pl-1">
                <span class="text-4xl leading-tight font-bold text-navy">{{ $stat['value'] }}</span>
                <span class="text-base text-ink-muted">{{ $stat['label'] }}</span>
            </div>
        @endforeach
    </x-frontend.container>
</section>
