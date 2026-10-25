document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('[data-site-header]');

    if (! header) {
        return;
    }

    const toggle = header.querySelector('[data-nav-toggle]');
    const mobileNav = header.querySelector('[data-mobile-nav]');
    const backdrop = header.querySelector('[data-nav-backdrop]');
    const iconOpen = header.querySelector('[data-nav-icon-open]');
    const iconClose = header.querySelector('[data-nav-icon-close]');

    if (! toggle || ! mobileNav) {
        return;
    }

    const setOpen = (open) => {
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'ปิดเมนู' : 'เปิดเมนู');
        mobileNav.toggleAttribute('data-open', open);
        mobileNav.toggleAttribute('inert', ! open);
        backdrop?.toggleAttribute('data-open', open);
        document.body.classList.toggle('overflow-hidden', open);
        iconOpen?.classList.toggle('hidden', open);
        iconClose?.classList.toggle('hidden', ! open);
    };

    toggle.addEventListener('click', () => {
        const isOpen = toggle.getAttribute('aria-expanded') === 'true';
        setOpen(! isOpen);
    });

    backdrop?.addEventListener('click', () => setOpen(false));

    header.querySelectorAll('[data-nav-link]').forEach((link) => {
        link.addEventListener('click', () => setOpen(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
            setOpen(false);
        }
    });

    window.addEventListener('resize', () => {
        if (window.matchMedia('(min-width: 768px)').matches) {
            setOpen(false);
        }
    });
});

document.addEventListener('click', (event) => {
    const link = event.target instanceof Element
        ? event.target.closest('[data-contact-track]')
        : null;

    if (! link) {
        return;
    }

    const endpoint = document.querySelector('meta[name="contact-clicks-url"]')?.getAttribute('content');
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const channel = link.getAttribute('data-contact-track');

    if (! endpoint || ! token || (channel !== 'phone' && channel !== 'line')) {
        return;
    }

    const body = new FormData();
    body.append('_token', token);
    body.append('channel', channel);
    body.append('page_url', window.location.href);

    const sent = typeof navigator.sendBeacon === 'function' && navigator.sendBeacon(endpoint, body);

    if (! sent) {
        fetch(endpoint, {
            method: 'POST',
            body,
            credentials: 'same-origin',
            keepalive: true,
        });
    }
});
