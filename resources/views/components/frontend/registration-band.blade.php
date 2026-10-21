<section class="bg-white px-6 pb-24">
    <x-frontend.container>
        <div class="flex flex-wrap items-center gap-8 rounded-3xl bg-accent-band p-8 md:p-12">
            <div class="flex min-w-0 flex-[1_1_480px] flex-col gap-3">
                <span class="text-[15px] font-semibold text-accent-deep">เพิ่งเริ่มธุรกิจ?</span>
                <h2 class="m-0 text-[clamp(1.5rem,3vw,2rem)] leading-snug font-bold text-navy">
                    จดทะเบียนกับเรา แล้ววางระบบบัญชีต่อได้ทันที
                </h2>
                <p class="m-0 text-accent-band-text">
                    ไม่ต้องเริ่มนับหนึ่งใหม่กับสำนักงานบัญชีอื่น เอกสารจดทะเบียนอยู่กับเราตั้งแต่วันแรก
                </p>
            </div>

            <div class="flex min-w-0 flex-[1_1_360px] flex-wrap items-center gap-3">
                <div class="flex flex-[1_1_140px] flex-col rounded-[14px] bg-white px-5 py-4">
                    <span class="text-sm text-ink-muted">หจก. เริ่มต้น</span>
                    <span class="text-[26px] font-bold text-navy">4,599 บาท</span>
                </div>
                <div class="flex flex-[1_1_140px] flex-col rounded-[14px] bg-white px-5 py-4">
                    <span class="text-sm text-ink-muted">บจก. เริ่มต้น</span>
                    <span class="text-[26px] font-bold text-navy">7,599 บาท</span>
                </div>
                <x-frontend.button href="{{ route('home') }}#contact" variant="navy" class="flex-[1_1_100%]">
                    ปรึกษาการจดทะเบียน
                </x-frontend.button>
                <span class="text-sm text-accent-band-text">ไม่รวมค่าธรรมเนียมราชการ</span>
            </div>
        </div>
    </x-frontend.container>
</section>
