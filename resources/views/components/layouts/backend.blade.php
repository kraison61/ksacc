@props([
    'title' => null,
    'heading' => 'แดชบอร์ด',
])

@php
    $pageTitle = $title ?? 'แอดมิน — '.config('company.name');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-page font-sans text-ink antialiased">
    <div class="flex min-h-screen">
        <aside class="hidden w-64 shrink-0 border-r border-line bg-white md:block">
            <div class="border-b border-line px-5 py-4">
                <p class="text-sm font-semibold text-navy">{{ config('company.name') }}</p>
                <p class="text-xs text-ink-muted">ระบบจัดการหลังบ้าน</p>
            </div>
            <nav class="flex flex-col gap-1 p-3 text-sm" aria-label="เมนูแอดมิน">
                <a href="{{ url('/admin') }}" class="rounded-lg px-3 py-2 font-medium text-navy hover:bg-navy-soft">แดชบอร์ด</a>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="border-b border-line bg-white px-6 py-4">
                <h1 class="text-lg font-semibold text-navy">{{ $heading }}</h1>
            </header>
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
