@php
    $lineUrl = config('company.line_url') ?: '#contact';
    $lineId = config('company.line_id');
    $phone = config('company.phone');
    $phoneDisplay = config('company.phone_display') ?: $phone;
    $telHref = $phone ? 'tel:'.$phone : '#contact';
@endphp

<section id="contact" class="bg-navy px-6 pt-22 pb-10 text-white">
    <x-frontend.container class="flex flex-col gap-16">
        <div class="flex flex-col items-center gap-5 text-center">
            <h2 class="m-0 text-[clamp(1.75rem,4vw,2.625rem)] leading-snug font-bold">
                ให้เราดูแลบัญชี <span class="text-accent">คุณดูแลธุรกิจ</span>
            </h2>
            <p class="m-0 max-w-[600px] text-lg text-navy-mist">
                ปรึกษาฟรี ไม่มีข้อผูกมัด ทักมาเล่าลักษณะกิจการ เราประเมินราคาให้ภายในวันเดียว
            </p>
            <div class="flex flex-wrap justify-center gap-3 pt-2">
                <x-frontend.button :href="$lineUrl" variant="primary" icon="line">
                    แอด LINE {{ $lineId ? '@'.$lineId : 'ปรึกษาฟรี' }}
                </x-frontend.button>
                <x-frontend.button :href="$telHref" variant="secondary" icon="phone">
                    โทร {{ $phoneDisplay ?: 'สอบถาม' }}
                </x-frontend.button>
            </div>
        </div>
    </x-frontend.container>
</section>
