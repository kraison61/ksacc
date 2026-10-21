@props([
    'items' => [
        [
            'question' => 'ต้องเตรียมเอกสารอะไรบ้าง?',
            'answer' => 'โดยทั่วไปคือใบกำกับภาษีซื้อ–ขาย ใบเสร็จรับเงิน รายการเดินบัญชีธนาคาร และข้อมูลเงินเดือน (ถ้ามี) เราจะส่งรายการเอกสารให้ตามลักษณะกิจการของคุณ',
        ],
        [
            'question' => 'อยู่ไกลสำนักงาน ใช้บริการได้ไหม?',
            'answer' => 'ได้ครับ ส่งเอกสารออนไลน์ทาง LINE หรืออีเมลได้ทั้งหมด ไม่ต้องเดินทางมาที่สำนักงาน',
        ],
        [
            'question' => 'ค่าบริการคิดจากอะไร?',
            'answer' => 'คิดตามปริมาณเอกสารและประเภทภาษีที่ต้องยื่นในแต่ละเดือน เราแจ้งราคาให้ชัดเจนก่อนเริ่มงานทุกครั้ง',
        ],
        [
            'question' => 'ค่าผู้สอบบัญชี (CPA) รวมในราคาหรือไม่?',
            'answer' => 'ยังไม่รวม แต่เราประสานงานกับผู้สอบบัญชีรับอนุญาตให้ครบจบ และแจ้งค่าใช้จ่ายให้ทราบล่วงหน้า',
        ],
    ],
])

<section id="faq" class="px-6 py-24">
    <x-frontend.container class="flex flex-col gap-10">
        <x-frontend.section-heading
            eyebrow="คำถามพบบ่อย"
            title="สิ่งที่เจ้าของกิจการมักถามเรา"
        />

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            @foreach ($items as $item)
                <article class="flex flex-col gap-2 rounded-2xl border border-line bg-white px-7 py-6">
                    <h3 class="m-0 text-[19px] font-semibold text-ink">{{ $item['question'] }}</h3>
                    <p class="m-0 text-ink-muted">{{ $item['answer'] }}</p>
                </article>
            @endforeach
        </div>
    </x-frontend.container>
</section>
