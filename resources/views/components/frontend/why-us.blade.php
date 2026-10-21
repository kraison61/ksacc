<section class="px-6 py-24">
    <x-frontend.container class="flex flex-wrap gap-14">
        <div class="flex min-w-0 flex-[1_1_360px] flex-col gap-4">
            <x-frontend.section-heading
                eyebrow="ทำไมต้องเรา"
                title="เจ้าของกิจการกว่า 200 รายไว้ใจให้เราดูแลบัญชี"
            >
                <p class="m-0">
                    งานบัญชีที่ดีคือคุณไม่ต้องคิดถึงมันเลย เราดูแลกำหนดการ เอกสาร และการยื่นภาษีให้ทั้งหมด คุณมีเวลาไปทำสิ่งที่ถนัดคือการทำธุรกิจ
                </p>
            </x-frontend.section-heading>
        </div>

        <div class="grid min-w-0 flex-[1.4_1_520px] grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ([
                ['icon' => 'shield', 'title' => 'ผู้ทำบัญชีขึ้นทะเบียน', 'body' => 'ขึ้นทะเบียนกับสภาวิชาชีพบัญชี ตรวจสอบได้'],
                ['icon' => 'users', 'title' => 'ประสานงาน CPA ครบจบ', 'body' => 'เราติดต่อผู้สอบบัญชีรับอนุญาตให้ คุณไม่ต้องหาเอง'],
                ['icon' => 'clock', 'title' => 'ยื่นตรงเวลา ไม่เคยโดนค่าปรับ', 'body' => 'มีระบบเตือนกำหนดการ ยื่นก่อนวันครบกำหนดทุกครั้ง'],
                ['icon' => 'line', 'title' => 'ตอบ LINE ภายใน 1 ชม.', 'body' => 'ถามได้ทุกเรื่องในเวลาทำการ ไม่ต้องรอนาน'],
                ['icon' => 'upload', 'title' => 'ส่งเอกสารออนไลน์', 'body' => 'ถ่ายรูปหรือสแกนส่งได้เลย ไม่ต้องเดินทางมาสำนักงาน'],
            ] as $item)
                <div class="flex flex-col gap-2.5 rounded-2xl border border-line bg-white p-6">
                    <x-frontend.icon :name="$item['icon']" :size="26" class="text-navy" />
                    <span class="text-lg font-semibold">{{ $item['title'] }}</span>
                    <span class="text-base text-ink-muted">{{ $item['body'] }}</span>
                </div>
            @endforeach

            <div class="flex flex-col justify-center gap-2.5 rounded-2xl bg-navy-soft p-6">
                <span class="text-lg font-semibold text-navy">10+ ปี ที่อยู่เคียงข้างธุรกิจไทย</span>
                <a href="{{ route('home') }}#contact" class="text-base font-semibold text-navy no-underline hover:text-navy-hover">
                    คุยกับเราวันนี้ →
                </a>
            </div>
        </div>
    </x-frontend.container>
</section>
