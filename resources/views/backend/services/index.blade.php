<x-layouts.backend heading="บริการ">
    <x-slot:actions>
        <x-backend.button :href="route('admin.services.create')">เพิ่มบริการ</x-backend.button>
    </x-slot:actions>

    <div class="overflow-hidden rounded-2xl border border-line bg-white">
        @if ($services->isEmpty())
            <p class="m-0 p-6 text-ink-muted">
                ยังไม่มีบริการ
                <a href="{{ route('admin.services.create') }}" class="font-semibold text-navy no-underline hover:text-navy-hover">เพิ่มบริการแรก</a>
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[40rem] border-collapse text-left text-sm">
                    <thead class="border-b border-line bg-page text-ink-muted">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium">ลำดับ</th>
                            <th scope="col" class="px-4 py-3 font-medium">ชื่อ</th>
                            <th scope="col" class="px-4 py-3 font-medium">slug</th>
                            <th scope="col" class="px-4 py-3 font-medium">สถานะ</th>
                            <th scope="col" class="px-4 py-3 font-medium"><span class="sr-only">จัดการ</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr class="border-b border-line last:border-b-0">
                                <td class="px-4 py-3 text-ink-muted">{{ $service->sort_order }}</td>
                                <td class="px-4 py-3 font-medium text-navy">{{ $service->title }}</td>
                                <td class="px-4 py-3 text-ink-muted">{{ $service->slug }}</td>
                                <td class="px-4 py-3">
                                    @if ($service->is_published)
                                        <span class="rounded-full bg-navy-soft px-2.5 py-1 text-xs font-semibold text-navy">เผยแพร่</span>
                                    @else
                                        <span class="rounded-full bg-accent-band px-2.5 py-1 text-xs font-semibold text-accent-band-text">ฉบับร่าง</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-3">
                                        @if ($service->is_published)
                                            <a href="{{ route('services.show', $service) }}" class="font-medium text-navy no-underline hover:text-navy-hover" target="_blank" rel="noopener">ดู</a>
                                        @endif
                                        <a href="{{ route('admin.services.edit', $service) }}" class="font-medium text-navy no-underline hover:text-navy-hover">แก้ไข</a>
                                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('ลบบริการนี้หรือไม่?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-700 hover:text-red-800">ลบ</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @if ($services->hasPages())
        <div>{{ $services->links() }}</div>
    @endif
</x-layouts.backend>
