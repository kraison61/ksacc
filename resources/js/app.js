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
