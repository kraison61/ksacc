@props([
    'title' => null,
    'jsonLd' => null,
])

@php
    $pageTitle = $title ?? config('company.name').' — '.config('company.tagline');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>

    @if ($jsonLd)
        <x-frontend.json-ld :data="$jsonLd" />
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-page font-sans text-[17px] leading-relaxed text-ink antialiased">
    <x-frontend.header />

    <main>
        {{ $slot }}
    </main>

    <x-frontend.contact-cta />
    <x-frontend.footer />
</body>
</html>
