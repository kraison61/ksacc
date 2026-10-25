@props([
    'title' => null,
    'heading' => 'แดชบอร์ด',
])

@php
    $pageTitle = $title ?? $heading.' — '.config('company.name');
    $links = [
        ['href' => route('admin.dashboard'), 'label' => 'แดชบอร์ด', 'active' => request()->routeIs('admin.dashboard')],
        ['href' => route('admin.contact-clicks.index'), 'label' => 'การคลิก', 'active' => request()->routeIs('admin.contact-clicks.*')],
        ['href' => route('admin.services.index'), 'label' => 'บริการ', 'active' => request()->routeIs('admin.services.*')],
        ['href' => route('admin.pricing-plans.index'), 'label' => 'แพ็กเกจราคา', 'active' => request()->routeIs('admin.pricing-plans.*')],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $pageTitle }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-page font-sans text-ink antialiased">
    <div class="flex min-h-screen">
        <aside class="sticky top-0 hidden h-screen w-64 shrink-0 flex-col border-r border-line bg-white md:flex">
            <a href="{{ route('admin.dashboard') }}" class="border-b border-line px-5 py-4 no-underline">
                <p class="m-0 text-sm font-semibold text-navy">{{ config('company.name') }}</p>
                <p class="m-0 text-xs text-ink-muted">ระบบจัดการหลังบ้าน</p>
            </a>

            <nav class="flex flex-1 flex-col gap-1 p-3" aria-label="เมนูแอดมิน">
                @foreach ($links as $link)
                    <x-backend.nav-link :href="$link['href']" :active="$link['active']">
                        {{ $link['label'] }}
                    </x-backend.nav-link>
                @endforeach
            </nav>

            <div class="flex flex-col gap-3 border-t border-line p-4">
                <div class="min-w-0">
                    <p class="m-0 truncate text-sm font-medium text-navy">{{ auth()->user()->name }}</p>
                    <p class="m-0 truncate text-xs text-ink-muted">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('home') }}" class="text-sm font-medium text-navy no-underline hover:text-navy-hover" target="_blank" rel="noopener">ดูหน้าเว็บ</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <x-backend.button variant="secondary" class="w-full">ออกจากระบบ</x-backend.button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex flex-col gap-3 border-b border-line bg-white px-4 py-4 sm:px-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h1 class="m-0 text-lg font-semibold text-navy">{{ $heading }}</h1>
                    <div class="flex flex-wrap items-center gap-2">
                        @isset($actions)
                            {{ $actions }}
                        @endisset
                        <form method="POST" action="{{ route('admin.logout') }}" class="md:hidden">
                            @csrf
                            <x-backend.button variant="secondary">ออกจากระบบ</x-backend.button>
                        </form>
                    </div>
                </div>

                <nav class="flex gap-2 overflow-x-auto md:hidden" aria-label="เมนูแอดมิน">
                    @foreach ($links as $link)
                        <x-backend.nav-link :href="$link['href']" :active="$link['active']" class="shrink-0">
                            {{ $link['label'] }}
                        </x-backend.nav-link>
                    @endforeach
                </nav>
            </header>

            <main class="flex flex-1 flex-col gap-4 p-4 sm:p-6">
                @if (session('status'))
                    <div class="rounded-xl border border-navy-mist bg-navy-soft px-4 py-3 text-sm text-navy" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
