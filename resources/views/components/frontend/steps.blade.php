<section id="steps" class="border-t border-line bg-white px-6 py-24">
    <x-frontend.container class="flex flex-col gap-12">
        <x-frontend.section-heading
            eyebrow="เริ่มใช้บริการ"
            title="เริ่มง่าย ๆ ใน 4 ขั้นตอน"
            align="center"
            class="mx-auto"
        />

        <ol class="m-0 grid list-none grid-cols-1 gap-6 p-0 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['title' => 'ทัก LINE หรือโทรหาเรา', 'body' => 'เล่าลักษณะกิจการสั้น ๆ เช่น ประเภทธุรกิจ จดภาษีมูลค่าเพิ่มหรือยัง', 'accent' => false],
                ['title' => 'รับใบเสนอราคา', 'body' => 'ประเมินราคาตามปริมาณเอกสาร แจ้งชัดเจนก่อนเริ่มงาน ไม่มีค่าใช้จ่ายแอบแฝง', 'accent' => false],
                ['title' => 'ส่งเอกสารออนไลน์', 'body' => 'ส่งบิลซื้อ–ขายและรายการเดินบัญชีทาง LINE หรืออีเมลทุกเดือน', 'accent' => false],
                ['title' => 'เรายื่นให้ตรงเวลา', 'body' => 'ยื่นภาษีครบทุกแบบ พร้อมส่งสรุปรายงานให้คุณทุกเดือน', 'accent' => true],
            ] as $index => $step)
                <li @class([
                    'flex flex-col gap-3 border-t-[3px] pt-5',
                    'border-accent' => $step['accent'],
                    'border-navy' => ! $step['accent'],
                ])>
                    <span class="text-[15px] font-bold text-accent-text">ขั้นที่ {{ $index + 1 }}</span>
                    <span class="text-xl font-semibold">{{ $step['title'] }}</span>
                    <span class="text-base text-ink-muted">{{ $step['body'] }}</span>
                </li>
            @endforeach
        </ol>
    </x-frontend.container>
</section>
