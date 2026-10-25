@php
    $featureValue = old('features', $plan->features ?? []);

    if (is_array($featureValue)) {
        $featureValue = implode("\n", $featureValue);
    }
@endphp

<x-layouts.backend :heading="$plan->exists ? 'แก้ไขแพ็กเกจราคา' : 'เพิ่มแพ็กเกจราคา'">
    <div class="flex max-w-3xl flex-col gap-4">
    <form method="POST" action="{{ $plan->exists ? route('admin.pricing-plans.update', $plan) : route('admin.pricing-plans.store') }}" class="flex flex-col gap-6 rounded-2xl border border-line bg-white p-6">
        @csrf
        @if ($plan->exists)
            @method('PUT')
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                กรุณาตรวจสอบข้อมูลที่กรอก
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <x-backend.field label="ประเภท" name="type">
                <x-backend.select
                    name="type"
                    :options="\App\Models\PricingPlan::TYPES"
                    :selected="old('type', $plan->type)"
                    required
                />
            </x-backend.field>

            <x-backend.field label="ลำดับการแสดงผล" name="sort_order" hint="เลขน้อยแสดงก่อน">
                <x-backend.input name="sort_order" type="number" min="0" max="9999" :value="old('sort_order', $plan->sort_order)" required />
            </x-backend.field>
        </div>

        <x-backend.field label="ชื่อ" name="title">
            <x-backend.input name="title" :value="old('title', $plan->title)" required />
        </x-backend.field>

        <x-backend.field label="slug" name="slug" hint="ใช้ในที่อยู่ของรายการ เช่น monthly-basic ใช้ตัวพิมพ์เล็ก ตัวเลข และขีดกลาง">
            <x-backend.input name="slug" :value="old('slug', $plan->slug)" required pattern="[a-z0-9]+(?:-[a-z0-9]+)*" />
        </x-backend.field>

        <x-backend.field label="คำบรรยาย" name="subtitle">
            <x-backend.input name="subtitle" :value="old('subtitle', $plan->subtitle)" />
        </x-backend.field>

        <div class="grid gap-4 sm:grid-cols-3">
            <x-backend.field label="คำนำหน้าราคา" name="price_prefix" hint="เช่น เริ่มต้น">
                <x-backend.input name="price_prefix" :value="old('price_prefix', $plan->price_prefix)" />
            </x-backend.field>
            <x-backend.field label="ราคา" name="price_amount">
                <x-backend.input name="price_amount" :value="old('price_amount', $plan->price_amount)" required />
            </x-backend.field>
            <x-backend.field label="หน่วยราคา" name="price_suffix" hint="เช่น บาท/เดือน">
                <x-backend.input name="price_suffix" :value="old('price_suffix', $plan->price_suffix)" />
            </x-backend.field>
        </div>

        <x-backend.field label="คุณสมบัติ" name="features" hint="หนึ่งบรรทัดต่อหนึ่งรายการ ใช้กับแพ็กเกจ">
            <x-backend.textarea name="features" :value="$featureValue" />
        </x-backend.field>

        <x-backend.field label="ข้อความปุ่ม" name="cta_label" hint="เช่น สอบถามราคา">
            <x-backend.input name="cta_label" :value="old('cta_label', $plan->cta_label)" />
        </x-backend.field>

        <div class="flex flex-col gap-3">
            <x-backend.checkbox name="is_featured" label="แสดงเป็นแพ็กเกจแนะนำ" :checked="old('is_featured', $plan->is_featured)" />
            <x-backend.checkbox name="is_published" label="เผยแพร่บนหน้าเว็บ" :checked="old('is_published', $plan->is_published)" />
        </div>

        <div class="flex flex-wrap items-center justify-end gap-2 border-t border-line pt-4">
            <x-backend.button :href="route('admin.pricing-plans.index')" variant="secondary">ยกเลิก</x-backend.button>
            <x-backend.button>บันทึก</x-backend.button>
        </div>
    </form>

    @if ($plan->exists)
        <form method="POST" action="{{ route('admin.pricing-plans.destroy', $plan) }}" onsubmit="return confirm('ลบแพ็กเกจราคานี้หรือไม่?')">
            @csrf
            @method('DELETE')
            <x-backend.button variant="danger">ลบแพ็กเกจราคา</x-backend.button>
        </form>
    @endif
    </div>
</x-layouts.backend>
