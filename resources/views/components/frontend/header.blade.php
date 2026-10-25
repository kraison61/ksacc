@php
    $lineUrl = config('company.line_url') ?: '#contact';
    $tracksLine = filled(config('company.line_url'));
    $navLinks = [
        ['href' => route('home').'#services', 'label' => 'บริการ'],
        ['href' => route('home').'#pricing', 'label' => 'ราคา'],
        ['href' => route('home').'#steps', 'label' => 'ขั้นตอน'],
        ['href' => route('home').'#faq', 'label' => 'คำถามพบบ่อย'],
    ];
@endphp

<header class="fixed inset-x-0 top-0 z-50" data-site-header>
    <button
        type="button"
        class="pointer-events-none invisible fixed inset-0 z-40 bg-ink/45 opacity-0 transition-opacity duration-200 data-open:pointer-events-auto data-open:visible data-open:opacity-100 md:hidden"
        data-nav-backdrop
        aria-label="ปิดเมนู"
        tabindex="-1"
    ></button>

    <div class="relative z-50 h-[4.5rem] border-b border-line bg-white/95 shadow-[0_8px_24px_rgba(20,36,58,0.06)] backdrop-blur-md">
        <x-frontend.container class="flex h-full items-center gap-3">
            <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3 text-navy no-underline" aria-label="{{ config('company.name') }}">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-[10px] bg-navy text-accent">
                    <x-frontend.icon name="book" :size="24" />
                </span>
                <span class="hidden min-w-0 flex-col leading-tight md:flex">
                    <span class="truncate text-base font-bold sm:text-lg">{{ config('company.name') }}</span>
                    <span class="truncate text-[13px] text-ink-muted">สำนักงานบัญชี · {{ config('company.tagline') }}</span>
                </span>
            </a>

            <div class="ml-auto flex items-center gap-2 md:hidden">
                <x-frontend.button
                    :href="$lineUrl"
                    variant="pill"
                    icon="line"
                    :icon-size="18"
                    class="shrink-0"
                    :data-contact-track="$tracksLine ? 'line' : null"
                    :target="$tracksLine ? '_blank' : null"
                    :rel="$tracksLine ? 'noopener noreferrer' : null"
                >
                    แอด LINE
                </x-frontend.button>

                <button
                    type="button"
                    class="inline-flex size-11 shrink-0 items-center justify-center rounded-[10px] border border-line bg-white text-navy"
                    data-nav-toggle
                    aria-controls="mobile-nav"
                    aria-expanded="false"
                    aria-label="เปิดเมนู"
                >
                    <x-frontend.icon name="menu" :size="22" data-nav-icon-open />
                    <x-frontend.icon name="close" :size="22" class="hidden" data-nav-icon-close />
                </button>
            </div>

            <nav aria-label="เมนูหลัก" class="ml-auto hidden items-center gap-x-6 text-base md:flex">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}" class="py-2.5 text-navy no-underline hover:text-navy-hover">{{ $link['label'] }}</a>
                @endforeach
                <x-frontend.button
                    :href="$lineUrl"
                    variant="pill"
                    icon="line"
                    :icon-size="18"
                    :data-contact-track="$tracksLine ? 'line' : null"
                    :target="$tracksLine ? '_blank' : null"
                    :rel="$tracksLine ? 'noopener noreferrer' : null"
                >
                    แอด LINE
                </x-frontend.button>
            </nav>
        </x-frontend.container>

        <nav
            id="mobile-nav"
            aria-label="เมนูมือถือ"
            class="pointer-events-none invisible absolute inset-x-0 top-full z-50 px-4 pt-3 opacity-0 transition-[opacity,transform] duration-200 ease-out -translate-y-1 data-open:visible data-open:translate-y-0 data-open:opacity-100 md:hidden"
            data-mobile-nav
            inert
        >
            <div class="pointer-events-auto overflow-hidden rounded-2xl border border-line bg-white shadow-[0_18px_48px_rgba(20,36,58,0.16)]">
                <div class="h-1 bg-accent"></div>
                <p class="px-5 pt-3.5 text-[13px] font-semibold tracking-wide text-accent-text">เมนู</p>
                <div class="flex flex-col gap-1 p-2">
                    @foreach ($navLinks as $link)
                        <a
                            href="{{ $link['href'] }}"
                            class="flex items-center justify-between gap-3 rounded-xl px-3 py-3.5 text-base font-semibold text-navy no-underline transition-colors hover:bg-navy-soft"
                            data-nav-link
                        >
                            <span>{{ $link['label'] }}</span>
                            <x-frontend.icon name="chevron" :size="18" class="text-ink-muted" />
                        </a>
                    @endforeach
                </div>
            </div>
        </nav>
    </div>
</header>

<div class="h-[4.5rem]" aria-hidden="true"></div>
