/* ================================================================
   JOAQUIN ESTRADA — Portfolio JS
   ================================================================ */

(function () {
    'use strict';

    /* ── State ─────────────────────────────────────────────── */
    const state = {
        filter:  'all',
        visible: [],   // visible gallery items after filter
        lbIndex: -1,   // current lightbox item
    };

    /* ── Shorthand ──────────────────────────────────────────── */
    const $  = id  => document.getElementById(id);
    const $$ = sel => Array.from(document.querySelectorAll(sel));

    /* ── Nav: scroll background ─────────────────────────────── */
    const nav = $('nav');
    if (nav) {
        const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 20);
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll(); // run once on load
    }

    /* ── Mobile nav ─────────────────────────────────────────── */
    const toggle    = $('nav-toggle');
    const mobileNav = $('nav-mobile');

    if (toggle && mobileNav) {
        toggle.addEventListener('click', () => {
            const isOpen = mobileNav.classList.toggle('open');
            toggle.classList.toggle('open', isOpen);
            toggle.setAttribute('aria-expanded', isOpen);
            mobileNav.setAttribute('aria-hidden', !isOpen);
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });

        // Close on link click
        mobileNav.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                mobileNav.classList.remove('open');
                toggle.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
                mobileNav.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            });
        });
    }

    /* ── Gallery filter ─────────────────────────────────────── */
    function refreshVisible() {
        state.visible = $$('.gallery-item').filter(item => {
            return !item.classList.contains('hidden');
        });
    }

    function applyFilter(filter) {
        state.filter = filter;

        $$('.gallery-item').forEach(item => {
            const match = filter === 'all' || item.dataset.category === filter;
            item.classList.toggle('hidden', !match);
        });

        // Update button states
        $$('.filter-btn').forEach(btn => {
            const active = btn.dataset.filter === filter;
            btn.classList.toggle('active', active);
            btn.setAttribute('aria-selected', active);
        });

        refreshVisible();
    }

    $$('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => applyFilter(btn.dataset.filter));
    });

    // Init
    applyFilter('all');

    /* ── Lightbox ───────────────────────────────────────────── */
    const lb         = $('lightbox');
    const lbImg      = lb && lb.querySelector('.lb-image');
    const lbTitle    = lb && lb.querySelector('.lb-title');
    const lbCat      = lb && lb.querySelector('.lb-cat');
    const lbPrev     = lb && lb.querySelector('.lb-prev');
    const lbNext     = lb && lb.querySelector('.lb-next');
    const lbClose    = lb && lb.querySelector('.lb-close');
    const lbBackdrop = lb && lb.querySelector('.lb-backdrop');

    function openLb(index) {
        if (!lb || index < 0 || index >= state.visible.length) return;
        state.lbIndex = index;

        const item = state.visible[index];

        // Image load with fade
        lbImg.classList.remove('loaded');
        lbImg.onload  = () => lbImg.classList.add('loaded');
        lbImg.onerror = () => lbImg.classList.add('loaded');
        lbImg.src     = item.dataset.src;
        lbImg.alt     = item.dataset.title;

        lbTitle.textContent = item.dataset.title;
        lbCat.textContent   = item.dataset.label;

        lb.classList.add('active');
        lb.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        updateNavBtns();
    }

    function closeLb() {
        if (!lb) return;
        lb.classList.remove('active');
        lb.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        // Clear src after animation
        setTimeout(() => { if (lbImg) lbImg.src = ''; }, 310);
    }

    function navigate(dir) {
        const next = state.lbIndex + dir;
        if (next >= 0 && next < state.visible.length) openLb(next);
    }

    function updateNavBtns() {
        if (!lbPrev || !lbNext) return;
        lbPrev.classList.toggle('hidden', state.lbIndex <= 0);
        lbNext.classList.toggle('hidden', state.lbIndex >= state.visible.length - 1);
    }

    // Open on gallery click / keyboard
    document.addEventListener('click', e => {
        const item = e.target.closest('.gallery-item');
        if (item && !item.classList.contains('hidden')) {
            openLb(state.visible.indexOf(item));
        }
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            const item = document.activeElement && document.activeElement.closest('.gallery-item');
            if (item && !item.classList.contains('hidden')) {
                openLb(state.visible.indexOf(item));
            }
        }
    });

    if (lbClose)    lbClose.addEventListener('click',    closeLb);
    if (lbBackdrop) lbBackdrop.addEventListener('click', closeLb);
    if (lbPrev)     lbPrev.addEventListener('click',    () => navigate(-1));
    if (lbNext)     lbNext.addEventListener('click',    () => navigate(1));

    // Keyboard navigation inside lightbox
    document.addEventListener('keydown', e => {
        if (!lb || !lb.classList.contains('active')) return;
        if (e.key === 'Escape')     closeLb();
        if (e.key === 'ArrowLeft')  navigate(-1);
        if (e.key === 'ArrowRight') navigate(1);
    });

    // Touch swipe in lightbox
    let touchX = null;
    if (lb) {
        lb.addEventListener('touchstart', e => { touchX = e.touches[0].clientX; }, { passive: true });
        lb.addEventListener('touchend', e => {
            if (touchX === null) return;
            const dx = e.changedTouches[0].clientX - touchX;
            touchX = null;
            if (Math.abs(dx) > 40) navigate(dx < 0 ? 1 : -1);
        }, { passive: true });
    }

    /* ── Scroll reveal (Intersection Observer) ──────────────── */
    const revealObs = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    $$('.reveal').forEach(el => revealObs.observe(el));

})();
