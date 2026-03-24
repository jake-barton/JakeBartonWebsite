/**
 * StaggeredMenu — vanilla JS, GSAP-enhanced
 * Themed for Jake Barton's black & white portfolio.
 */
(function () {
  'use strict';

  // ── Config ────────────────────────────────────────────────────────────────
  const ITEMS = [
    { label: 'About',   link: '#about',                        num: '01' },
    { label: 'Skills',  link: '#skills',                       num: '02' },
    { label: 'Work',    link: 'portfolio/',                    num: '03' },
    { label: 'Resume',  link: 'assets/Jake_Barton_Resume.pdf', num: '04', download: true },
    { label: 'Contact', link: '#contact',                      num: '05' },
  ];

  const SOCIALS = [
    { label: 'GitHub',    link: 'https://github.com/jake-barton' },
    { label: 'Instagram', link: 'https://instagram.com/jakebarton13' },
    { label: 'LinkedIn',  link: '#contact' },
  ];

  // ── State ─────────────────────────────────────────────────────────────────
  let isOpen = false;
  let gsapReady = false;

  // ── DOM refs ──────────────────────────────────────────────────────────────
  let toggleBtn, panel, prelayerA, prelayerB, backdrop;

  // ── Build DOM ─────────────────────────────────────────────────────────────
  function buildHTML() {
    // ── Panel ──────────────────────────────────────────────────
    panel = document.createElement('aside');
    panel.id = 'sm-panel';
    panel.className = 'sm-panel';

    const inner = document.createElement('div');
    inner.className = 'sm-panel-inner';

    // Nav list
    const ul = document.createElement('ul');
    ul.className = 'sm-panel-list';
    ITEMS.forEach(function(it) {
      var li  = document.createElement('li');
      li.className = 'sm-panel-itemWrap';
      var a   = document.createElement('a');
      a.className = 'sm-panel-item';
      a.href = it.link;
      if (it.download) a.setAttribute('download', '');
      a.setAttribute('data-num', it.num);
      var lbl = document.createElement('span');
      lbl.className = 'sm-panel-label';
      lbl.textContent = it.label;
      a.appendChild(lbl);
      a.addEventListener('click', function() { if (isOpen) closeMenu(); });
      li.appendChild(a);
      ul.appendChild(li);
    });
    inner.appendChild(ul);

    // Socials
    var soc = document.createElement('div');
    soc.className = 'sm-socials';
    var socTitle = document.createElement('p');
    socTitle.className = 'sm-socials-title';
    socTitle.textContent = 'Find me on';
    soc.appendChild(socTitle);
    var socList = document.createElement('ul');
    socList.className = 'sm-socials-list';
    SOCIALS.forEach(function(s) {
      var li = document.createElement('li');
      var a  = document.createElement('a');
      a.href = s.link;
      a.className = 'sm-socials-link';
      a.textContent = s.label;
      if (s.link.indexOf('http') === 0) { a.target = '_blank'; a.rel = 'noopener noreferrer'; }
      li.appendChild(a);
      socList.appendChild(li);
    });
    soc.appendChild(socList);
    inner.appendChild(soc);
    panel.appendChild(inner);

    // ── Pre-layers ───────────────────────────────────────────────
    prelayerA = document.createElement('div');
    prelayerA.className = 'sm-prelayer sm-prelayer-a';
    prelayerB = document.createElement('div');
    prelayerB.className = 'sm-prelayer sm-prelayer-b';

    // ── Toggle button ─────────────────────────────────────────────
    toggleBtn = document.createElement('button');
    toggleBtn.className = 'sm-toggle';
    toggleBtn.type = 'button';
    toggleBtn.setAttribute('aria-label', 'Open menu');
    toggleBtn.setAttribute('aria-expanded', 'false');

    // 3 hamburger bars
    ['sm-bar sm-bar-top','sm-bar sm-bar-mid','sm-bar sm-bar-bot'].forEach(function(cls) {
      var s = document.createElement('span');
      s.className = cls;
      toggleBtn.appendChild(s);
    });

    toggleBtn.addEventListener('click', onToggleClick);

    // ── Inject into existing .site-nav ───────────────────────────
    var siteNav = document.querySelector('.site-nav');
    if (siteNav) {
      var old = siteNav.querySelector('.nav-toggle');
      if (old) old.style.display = 'none';
      siteNav.appendChild(toggleBtn);
    }

    // ── Backdrop (blur overlay behind panel) ─────────────────────
    backdrop = document.createElement('div');
    backdrop.className = 'sm-backdrop';
    backdrop.addEventListener('click', function() { if (isOpen) closeMenu(); });
    document.body.appendChild(backdrop);

    document.body.appendChild(prelayerA);
    document.body.appendChild(prelayerB);
    document.body.appendChild(panel);

    // Hide panel off-screen immediately via inline style (before GSAP ready)
    panel.style.transform     = 'translateX(100%)';
    prelayerA.style.transform = 'translateX(100%)';
    prelayerB.style.transform = 'translateX(100%)';
  }

  // ── Toggle ────────────────────────────────────────────────────────────────
  function onToggleClick() {
    isOpen ? closeMenu() : openMenu();
  }

  function openMenu() {
    isOpen = true;
    toggleBtn.setAttribute('aria-label', 'Close menu');
    toggleBtn.setAttribute('aria-expanded', 'true');
    toggleBtn.classList.add('sm-toggle--open');
    document.body.style.overflow = 'hidden';
    backdrop.classList.add('sm-backdrop--visible');

    if (gsapReady) {
      animateOpen();
    } else {
      // CSS fallback
      panel.style.transform     = 'translateX(0%)';
      prelayerA.style.transform = 'translateX(0%)';
      prelayerB.style.transform = 'translateX(0%)';
    }
  }

  function closeMenu() {
    isOpen = false;
    toggleBtn.setAttribute('aria-label', 'Open menu');
    toggleBtn.setAttribute('aria-expanded', 'false');
    toggleBtn.classList.remove('sm-toggle--open');
    document.body.style.overflow = '';
    backdrop.classList.remove('sm-backdrop--visible');

    if (gsapReady) {
      animateClose();
    } else {
      panel.style.transform     = 'translateX(100%)';
      prelayerA.style.transform = 'translateX(100%)';
      prelayerB.style.transform = 'translateX(100%)';
    }
  }

  // ── GSAP animations ───────────────────────────────────────────────────────
  function animateOpen() {
    var labels   = panel.querySelectorAll('.sm-panel-label');
    var socTitle = panel.querySelector('.sm-socials-title');
    var socLinks = panel.querySelectorAll('.sm-socials-link');

    // Clear inline styles so GSAP has full control
    panel.style.transform     = '';
    prelayerA.style.transform = '';
    prelayerB.style.transform = '';

    gsap.set(panel,     { x: '100%' });
    gsap.set(prelayerA, { x: '100%' });
    gsap.set(prelayerB, { x: '100%' });
    gsap.set(labels,    { y: '110%', rotation: 8, opacity: 0 });
    gsap.set(socTitle,  { opacity: 0 });
    gsap.set(socLinks,  { y: 20, opacity: 0 });

    var tl = gsap.timeline();

    // Pre-layer A sweeps in first and STAYS
    tl.to(prelayerA, { x: '0%', duration: 0.4, ease: 'power4.out' }, 0);
    // Pre-layer B sweeps in slightly behind and STAYS
    tl.to(prelayerB, { x: '0%', duration: 0.4, ease: 'power4.out' }, 0.07);
    // Panel slides in last, on top — pre-layers remain visible behind it
    tl.to(panel,     { x: '0%', duration: 0.55, ease: 'power4.out' }, 0.14);

    // Nav labels rise up
    tl.to(labels,   { y: '0%', rotation: 0, opacity: 1, duration: 0.65, ease: 'power3.out', stagger: 0.08 }, 0.3);
    tl.to(socTitle, { opacity: 1, duration: 0.4, ease: 'power2.out' }, 0.5);
    tl.to(socLinks, { y: 0, opacity: 1, duration: 0.4, ease: 'power3.out', stagger: 0.07 }, 0.55);
  }

  function animateClose() {
    gsap.timeline({
      onComplete: function() {
        // Re-apply inline style so it stays hidden if GSAP is ever cleared
        panel.style.transform     = 'translateX(100%)';
        prelayerA.style.transform = 'translateX(100%)';
        prelayerB.style.transform = 'translateX(100%)';
      }
    })
    // Panel slides out first, then pre-layers follow
    .to(panel,     { x: '100%', duration: 0.28, ease: 'power3.in' }, 0)
    .to(prelayerB, { x: '100%', duration: 0.28, ease: 'power3.in' }, 0.05)
    .to(prelayerA, { x: '100%', duration: 0.28, ease: 'power3.in' }, 0.1);
  }

  // ── Click-away to close (backdrop handles it; this is a keyboard/escape fallback) ──
  document.addEventListener('keydown', function(e) {
    if (isOpen && e.key === 'Escape') closeMenu();
  });

  // ── Init ──────────────────────────────────────────────────────────────────
  function init() {
    buildHTML();

    // Poll for GSAP (CDN may not have executed yet)
    function tryGsap() {
      if (typeof gsap !== 'undefined') {
        gsapReady = true;
        // GSAP takes over — it will set transform itself on first animateOpen()
        // Just leave the inline translateX(100%) in place; GSAP overrides it on open
      } else {
        setTimeout(tryGsap, 50);
      }
    }
    tryGsap();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
