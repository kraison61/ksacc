<x-layouts.backend-guest title="เข้าสู่ระบบ">
    <div class="flex w-full max-w-md flex-col gap-6 rounded-2xl border border-line bg-white p-6 shadow-[0_16px_40px_rgba(20,36,58,0.06)] sm:p-8">
        <div class="flex flex-col gap-1">
            <p class="m-0 text-sm font-semibold text-navy">{{ config('company.name') }}</p>
            <h1 class="m-0 text-2xl font-bold text-navy">เข้าสู่ระบบแอดมิน</h1>
            <p class="m-0 text-sm text-ink-muted">สำหรับผู้ดูแลเว็บไซต์เท่านั้น</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
            @csrf

            <x-backend.field label="อีเมล" name="email">
                <x-backend.input
                    name="email"
                    type="email"
                    :value="old('email')"
                    autocomplete="username"
                    required
                    autofocus
                />
            </x-backend.field>

            <x-backend.field label="รหัสผ่าน" name="password">
                <x-backend.input
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                />
            </x-backend.field>

            <x-backend.checkbox name="remember" label="จดจำการเข้าสู่ระบบ" :checked="old('remember')" />

            <x-backend.button class="w-full">เข้าสู่ระบบ</x-backend.button>
        </form>

        <a href="{{ route('home') }}" class="text-sm font-medium text-navy no-underline hover:text-navy-hover">กลับหน้าเว็บ</a>
    </div>
</x-layouts.backend-guest>
