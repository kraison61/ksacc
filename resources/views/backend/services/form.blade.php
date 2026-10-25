<x-layouts.backend :heading="$service->exists ? 'แก้ไขบริการ' : 'เพิ่มบริการ'">
    <div class="flex max-w-3xl flex-col gap-4">
    <form method="POST" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}" class="flex flex-col gap-6 rounded-2xl border border-line bg-white p-6">
        @csrf
        @if ($service->exists)
            @method('PUT')
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                กรุณาตรวจสอบข้อมูลที่กรอก
            </div>
        @endif

        <x-backend.field label="ชื่อบริการ" name="title">
            <x-backend.input name="title" :value="old('title', $service->title)" required />
        </x-backend.field>

        <x-backend.field label="slug" name="slug" hint="ใช้ในที่อยู่หน้าบริการ เช่น monthly-bookkeeping ใช้ตัวพิมพ์เล็ก ตัวเลข และขีดกลาง">
            <x-backend.input name="slug" :value="old('slug', $service->slug)" required pattern="[a-z0-9]+(?:-[a-z0-9]+)*" />
        </x-backend.field>

        <x-backend.field label="คำอธิบาย" name="description">
            <x-backend.textarea name="description" :value="old('description', $service->description)" required />
        </x-backend.field>

        <div class="grid gap-4 sm:grid-cols-2">
            <x-backend.field label="ไอคอน" name="icon">
                <x-backend.select
                    name="icon"
                    :options="\App\Models\Service::ICONS"
                    :selected="old('icon', $service->icon)"
                    required
                />
            </x-backend.field>

            <x-backend.field label="ลำดับการแสดงผล" name="sort_order" hint="เลขน้อยแสดงก่อน">
                <x-backend.input name="sort_order" type="number" min="0" max="9999" :value="old('sort_order', $service->sort_order)" required />
            </x-backend.field>
        </div>

        <x-backend.checkbox name="is_published" label="เผยแพร่บนหน้าเว็บ" :checked="old('is_published', $service->is_published)" />

        <div class="flex flex-wrap items-center justify-end gap-2 border-t border-line pt-4">
            <x-backend.button :href="route('admin.services.index')" variant="secondary">ยกเลิก</x-backend.button>
            <x-backend.button>บันทึก</x-backend.button>
        </div>
    </form>

    @if ($service->exists)
        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('ลบบริการนี้หรือไม่?')">
            @csrf
            @method('DELETE')
            <x-backend.button variant="danger">ลบบริการ</x-backend.button>
        </form>
    @endif
    </div>
</x-layouts.backend>
