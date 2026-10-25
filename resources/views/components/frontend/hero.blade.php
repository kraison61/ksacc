@php
    $lineUrl = config('company.line_url') ?: '#contact';
    $tracksLine = filled(config('company.line_url'));
    $phone = config('company.phone');
    $phoneDisplay = config('company.phone_display') ?: $phone;
    $telHref = $phone ? 'tel:'.$phone : '#contact';
@endphp

<section id="top" class="bg-navy text-white">
    <x-frontend.container class="flex flex-wrap items-center gap-14 py-22">
        <div class="flex min-w-0 flex-[1_1_520px] flex-col gap-6">
            <span class="self-start rounded-full border border-accent px-3.5 py-1.5 text-sm font-semibold tracking-[0.02em] text-accent">
                สำนักงานบัญชีสำหรับ SME และร้านค้า
            </span>

            <h1 class="m-0 text-[clamp(2rem,5vw,3.25rem)] leading-tight font-bold">
                รับทำบัญชีและยื่นภาษี <span class="text-accent">ครบ จบ ตรงเวลา</span> ไม่ต้องกังวลเรื่องค่าปรับ
            </h1>

            <p class="m-0 max-w-[560px] text-lg text-navy-mist">
                ดูแลโดยผู้ทำบัญชีที่ขึ้นทะเบียนกับสภาวิชาชีพบัญชี ส่งเอกสารออนไลน์ได้ ไม่ต้องเดินทาง เริ่มต้นเพียง 1,500 บาท/เดือน
            </p>

            <div class="flex flex-wrap gap-3 pt-2">
                <x-frontend.button
                    :href="$lineUrl"
                    variant="primary"
                    icon="line"
                    :data-contact-track="$tracksLine ? 'line' : null"
                    :target="$tracksLine ? '_blank' : null"
                    :rel="$tracksLine ? 'noopener noreferrer' : null"
                >
                    แอด LINE ปรึกษาฟรี
                </x-frontend.button>
                <x-frontend.button
                    :href="$telHref"
                    variant="secondary"
                    icon="phone"
                    :data-contact-track="filled($phone) ? 'phone' : null"
                >
                    โทร {{ $phoneDisplay ?: 'สอบถาม' }}
                </x-frontend.button>
            </div>

            <div class="flex flex-wrap gap-x-6 gap-y-2.5 text-[15px] text-navy-mist">
                @foreach (['ตอบ LINE ภายใน 1 ชม.', 'ยื่นตรงเวลาทุกเดือน', 'ประสานงาน CPA ให้ครบจบ'] as $point)
                    <span class="flex items-center gap-2">
                        <x-frontend.icon name="check" :size="18" class="text-accent stroke-[2.4]" />
                        {{ $point }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="flex max-w-[460px] min-w-0 flex-[1_1_380px] flex-col gap-4 rounded-[20px] bg-white p-7 text-ink shadow-[0_24px_60px_rgba(6,18,34,0.35)]">
            <div class="flex items-baseline justify-between gap-3">
                <span class="text-lg font-bold">งานบัญชีประจำเดือนของคุณ</span>
                <span class="text-[13px] text-ink-muted">ตัวอย่าง</span>
            </div>

            <div class="flex flex-col gap-2.5">
                @foreach ([
                    ['title' => 'ภ.พ.30', 'subtitle' => 'ภาษีมูลค่าเพิ่ม', 'status' => 'ยื่นแล้ว', 'accent' => false],
                    ['title' => 'ภ.ง.ด.1, 3, 53', 'subtitle' => 'ภาษีหัก ณ ที่จ่าย', 'status' => 'ยื่นแล้ว', 'accent' => false],
                    ['title' => 'สปส.1-10', 'subtitle' => 'เงินสมทบประกันสังคม', 'status' => 'ยื่นแล้ว', 'accent' => false],
                    ['title' => 'สรุปงบรายเดือน', 'subtitle' => 'รายรับ–รายจ่าย กำไรขาดทุน', 'status' => 'ส่งทาง LINE', 'accent' => true],
                ] as $row)
                    <div class="flex items-center gap-3 rounded-xl bg-page px-3.5 py-3">
                        <span @class([
                            'flex size-8 shrink-0 items-center justify-center rounded-full',
                            'bg-accent text-ink' => $row['accent'],
                            'bg-navy text-white' => ! $row['accent'],
                        ])>
                            <x-frontend.icon :name="$row['accent'] ? 'document' : 'check'" :size="16" class="{{ $row['accent'] ? 'stroke-[2.2]' : 'stroke-[2.6]' }}" />
                        </span>
                        <span class="flex flex-1 flex-col leading-snug">
                            <span class="font-semibold">{{ $row['title'] }}</span>
                            <span class="text-sm text-ink-muted">{{ $row['subtitle'] }}</span>
                        </span>
                        <span class="text-sm font-semibold text-navy">{{ $row['status'] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-between gap-3 border-t border-dashed border-line-soft pt-3.5 text-sm text-ink-muted">
                <span>ไม่ต้องเดินทาง</span>
                <span>ไม่มีค่าปรับ</span>
                <span>ตรวจสอบได้ทุกเดือน</span>
            </div>
        </div>
    </x-frontend.container>
</section>
