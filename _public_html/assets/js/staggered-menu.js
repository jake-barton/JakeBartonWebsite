/**
 * StaggeredMenu — vanilla JS port of the React StaggeredMenu component
 * Themed for Jake Barton's black & white portfolio.
 * Depends on: GSAP (loaded via CDN before this file)
 */
(function () {
  'use strict';

  // ── Config ───────────────────────────────────────────────────────────────
  const ITEMS = [
    { label: 'About',     link: '#about',                          num: '01' },
    { label: 'Skills',    link: '#skills',                         num: '02' },
    { label: 'Work',      link: 'portfolio/',                      num: '03' },
    { label: 'Resume',    link: 'assets/Jake_Barton_Resume.pdf',   num: '04', download: true },
    { label: 'Contact',   link: '#contact',                        num: '05' },
  ];

  const SOCIALS = [
    { label: 'GitHub',    link: 'https://github.com/jake-barton' },
    { label: 'Instagram', link: 'https://instagram.com/jakebarton13' },
    { label: 'LinkedIn',  link: '#contact' },
  ];

  // Pre-layer colours: site bg → slightly lighter → panel (black)
  // Two layers create the "wipe" illusion
  const PRE_COLORS = ['#1a1a1a', '#0f0f0f'];

  // ── State ────────────────────────────────────────────────────────────────
  let isOpen   = false;
  let isBusy   = false;
  let openTl   = null;
  let closeTween = null;

  // ── DOM refs ─────────────────────────────────────────────────────────────
  let wrapper, toggleBtn, panel, preContainer, preLayers;
  let plusH, plusV, icon, textInner;

  // ── Build DOM ────────────────────────────────────────────────────────────
  function buildHTML() {
    // Wrapper replaces the old .site-nav & .stagger-menu-overlay
    // We inject it as a sibling, and keep the desktop .site-nav intact
    wrapper = document.createElement('div');
    wrapper.className = 'sm-wrapper';
    wrapper.setAttribute('data-position', 'right');

    // Pre-layers (the "wipe" layers behind the panel)
    preContainer = document.createElement('div');
    preContainer.className = 'sm-prelayers';
    preContainer.setAttribute('aria-hidden', 'true');
    PRE_COLORS.forEach(c => {
      const d = document.createElement('div');
      d.className = 'sm-prelayer';
      d.style.background = c;
      preContainer.appendChild(d);
    });
    wrapper.appendChild(preContainer);

    // Panel
    panel = document.createElement('aside');
    panel.id = 'sm-panel';
    panel.className = 'sm-panel';
    panel.setAttribute('aria-hidden', 'true');

    const inner = document.createElement('div');
    inner.className = 'sm-panel-inner';

    // Nav list
    const ul = document.createElement('ul');
    ul.className = 'sm-panel-list';
    ul.setAttribute('role', 'list');
    ul.setAttribute('data-numbering', '');
    ITEMS.forEach((it, idx) => {
      const li = document.createElement('li');
      li.className = 'sm-panel-itemWrap';

      const a = document.createElement('a');
      a.className = 'sm-panel-item';
      a.href = it.link;
      if (it.download) a.setAttribute('download', '');
      a.setAttribute('data-index', it.num);
      a.setAttribute('aria-label', it.label);

      const span = document.createElement('span');
      span.className = 'sm-panel-itemLabel';
      span.textContent = it.label;

      // Close menu on nav click
      a.addEventListener('click', () => { if (isOpen) doClose(); });

      a.appendChild(span);
      li.appendChild(a);
      ul.appendChild(li);
    });
    inner.appendChild(ul);

    // Socials
    const socialsDiv = document.createElement('div');
    socialsDiv.className = 'sm-socials';
    socialsDiv.setAttribute('aria-label', 'Social links');

    const socialsTitle = document.createElement('h3');
    socialsTitle.className = 'sm-socials-title';
    socialsTitle.textContent = 'Find me on';
    socialsDiv.appendChild(socialsTitle);

    const socialUl = document.createElement('ul');
    socialUl.className = 'sm-socials-list';
    socialUl.setAttribute('role', 'list');
    SOCIALS.forEach(s => {
      const li = document.createElement('li');
      li.className = 'sm-socials-item';
      const a = document.createElement('a');
      a.href = s.link;
      a.className = 'sm-socials-link';
      a.textContent = s.label;
      if (s.link.startsWith('http')) {
        a.target = '_blank';
        a.rel = 'noopener noreferrer';
      }
      li.appendChild(a);
      socialUl.appendChild(li);
    });
    socialsDiv.appendChild(socialUl);
    inner.appendChild(socialsDiv);

    panel.appendChild(inner);
    wrapper.appendChild(panel);

    // Toggle button (injected into the existing .site-nav)
    toggleBtn = document.createElement('button');
    toggleBtn.className = 'sm-toggle';
    toggleBtn.setAttribute('type', 'button');
    toggleBtn.setAttribute('aria-label', 'Open menu');
    toggleBtn.setAttribute('aria-expanded', 'false');
    toggleBtn.setAttribute('aria-controls', 'sm-panel');

    // Cycling text: "Menu" ↔ "Close"
    const textWrap = document.createElement('span');
    textWrap.className = 'sm-toggle-textWrap';
    textWrap.setAttribute('aria-hidden', 'true');
    textInner = document.createElement('span');
    textInner.className = 'sm-toggle-textInner';
    ['Menu', 'Close'].forEach(l => {
      const s = document.createElement('span');
      s.className = 'sm-toggle-line';
      s.textContent = l;
      textInner.appendChild(s);
    });
    textWrap.appendChild(textInner);

    // Plus / cross icon
    icon = document.createElement('span');
    icon.className = 'sm-icon';
    icon.setAttribute('aria-hidden', 'true');
    plusH = document.createElement('span');
    plusH.className = 'sm-icon-line';
    plusV = document.createElement('span');
    plusV.className = 'sm-icon-line sm-icon-line-v';
    icon.appendChild(plusH);
    icon.appendChild(plusV);

    toggleBtn.appendChild(textWrap);
    toggleBtn.appendChild(icon);
    toggleBtn.addEventListener('click', doToggle);

    // Inject into existing .site-nav (hide old .nav-toggle)
    const siteNav = document.querySelector('.site-nav');
    if (siteNav) {
      const oldToggle = siteNav.querySelector('.nav-toggle');
      if (oldToggle) oldToggle.style.display = 'none';
      siteNav.appendChild(toggleBtn);
    }

    // Inject wrapper before </body>
    document.body.appendChild(wrapper);
  }

  // ── GSAP Init ────────────────────────────────────────────────────────────
  function initGsap() {
    preLayers = Array.from(preContainer.querySelectorAll('.sm-prelayer'));

    gsap.set([panel, ...preLayers], { xPercent: 100 });
    gsap.set(plusH, { transformOrigin: '50% 50%', rotate: 0 });
    gsap.set(plusV, { transformOrigin: '50% 50%', rotate: 90 });
    gsap.set(icon,  { rotate: 0, transformOrigin: '50% 50%' });
    gsap.set(textInner, { yPercent: 0 });
  }

  // ── Toggle ───────────────────────────────────────────────────────────────
  function doToggle() {
    isOpen ? doClose() : doOpen();
  }

  function doOpen() {
    if (isBusy) return;
    isBusy  = true;
    isOpen  = true;
    panel.setAttribute('aria-hidden', 'false');
    toggleBtn.setAttribute('aria-label', 'Close menu');
    toggleBtn.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';

    playOpen();
    animateIcon(true);
    animateText(true);
  }

  function doClose() {
    isOpen = false;
    panel.setAttribute('aria-hidden', 'true');
    toggleBtn.setAttribute('aria-label', 'Open menu');
    toggleBtn.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';

    playClose();
    animateIcon(false);
    animateText(false);
  }

  // ── Open animation ───────────────────────────────────────────────────────
  function playOpen() {
    openTl?.kill();
    closeTween?.kill();
    closeTween = null;

    const itemEls      = Array.from(panel.querySelectorAll('.sm-panel-itemLabel'));
    const numberEls    = Array.from(panel.querySelectorAll('.sm-panel-list[data-numbering] .sm-panel-item'));
    const socialTitle  = panel.querySelector('.sm-socials-title');
    const socialLinks  = Array.from(panel.querySelectorAll('.sm-socials-link'));

    if (itemEls.length)   gsap.set(itemEls,   { yPercent: 140, rotate: 10 });
    if (numberEls.length) gsap.set(numberEls, { '--sm-num-opacity': 0 });
    if (socialTitle)      gsap.set(socialTitle, { opacity: 0 });
    if (socialLinks.length) gsap.set(socialLinks, { y: 25, opacity: 0 });

    const tl = gsap.timeline({
      onComplete: () => { isBusy = false; }
    });

    // Pre-layers wipe in
    preLayers.forEach((layer, i) => {
      tl.fromTo(layer,
        { xPercent: 100 },
        { xPercent: 0, duration: 0.5, ease: 'power4.out' },
        i * 0.07
      );
    });

    const panelStart = preLayers.length ? (preLayers.length - 1) * 0.07 + 0.08 : 0;

    // Panel slides in
    tl.fromTo(panel,
      { xPercent: 100 },
      { xPercent: 0, duration: 0.65, ease: 'power4.out' },
      panelStart
    );

    // Nav items rise up
    if (itemEls.length) {
      const itemsAt = panelStart + 0.65 * 0.15;
      tl.to(itemEls, {
        yPercent: 0, rotate: 0,
        duration: 1, ease: 'power4.out',
        stagger: { each: 0.1, from: 'start' }
      }, itemsAt);

      if (numberEls.length) {
        tl.to(numberEls, {
          '--sm-num-opacity': 1,
          duration: 0.6, ease: 'power2.out',
          stagger: { each: 0.08, from: 'start' }
        }, itemsAt + 0.1);
      }
    }

    // Socials fade in
    const socialsAt = panelStart + 0.65 * 0.4;
    if (socialTitle) {
      tl.to(socialTitle, { opacity: 1, duration: 0.5, ease: 'power2.out' }, socialsAt);
    }
    if (socialLinks.length) {
      tl.to(socialLinks, {
        y: 0, opacity: 1,
        duration: 0.55, ease: 'power3.out',
        stagger: { each: 0.08, from: 'start' },
        onComplete: () => gsap.set(socialLinks, { clearProps: 'opacity' })
      }, socialsAt + 0.04);
    }

    openTl = tl;
  }

  // ── Close animation ──────────────────────────────────────────────────────
  function playClose() {
    openTl?.kill();
    openTl = null;

    const all = [...preLayers, panel];
    closeTween?.kill();
    closeTween = gsap.to(all, {
      xPercent: 100,
      duration: 0.32,
      ease: 'power3.in',
      overwrite: 'auto',
      onComplete: () => {
        const itemEls = Array.from(panel.querySelectorAll('.sm-panel-itemLabel'));
        if (itemEls.length) gsap.set(itemEls, { yPercent: 140, rotate: 10 });
        const numberEls = Array.from(panel.querySelectorAll('.sm-panel-list[data-numbering] .sm-panel-item'));
        if (numberEls.length) gsap.set(numberEls, { '--sm-num-opacity': 0 });
        const socialTitle = panel.querySelector('.sm-socials-title');
        const socialLinks = Array.from(panel.querySelectorAll('.sm-socials-link'));
        if (socialTitle) gsap.set(socialTitle, { opacity: 0 });
        if (socialLinks.length) gsap.set(socialLinks, { y: 25, opacity: 0 });
        isBusy = false;
      }
    });
  }

  // ── Icon spin ────────────────────────────────────────────────────────────
  let spinTween = null;
  function animateIcon(opening) {
    spinTween?.kill();
    spinTween = gsap.to(icon, {
      rotate: opening ? 225 : 0,
      duration: opening ? 0.8 : 0.35,
      ease: opening ? 'power4.out' : 'power3.inOut',
      overwrite: 'auto'
    });
  }

  // ── Text cycle Menu ↔ Close ──────────────────────────────────────────────
  let textTween = null;
  function animateText(opening) {
    textTween?.kill();

    const current = opening ? 'Menu' : 'Close';
    const target  = opening ? 'Close' : 'Menu';
    const cycles  = 3;
    const seq     = [current];
    let last = current;
    for (let i = 0; i < cycles; i++) {
      last = last === 'Menu' ? 'Close' : 'Menu';
      seq.push(last);
    }
    if (last !== target) seq.push(target);
    seq.push(target);

    // Rebuild lines
    textInner.innerHTML = '';
    seq.forEach(l => {
      const s = document.createElement('span');
      s.className = 'sm-toggle-line';
      s.textContent = l;
      textInner.appendChild(s);
    });

    gsap.set(textInner, { yPercent: 0 });
    const finalShift = ((seq.length - 1) / seq.length) * 100;
    textTween = gsap.to(textInner, {
      yPercent: -finalShift,
      duration: 0.5 + seq.length * 0.07,
      ease: 'power4.out'
    });
  }

  // ── Click-away to close ──────────────────────────────────────────────────
  document.addEventListener('mousedown', e => {
    if (!isOpen) return;
    if (!panel.contains(e.target) && !toggleBtn.contains(e.target)) {
      doClose();
    }
  });

  // ── Boot ─────────────────────────────────────────────────────────────────
  function init() {
    buildHTML();
    // Wait for GSAP to be ready
    if (typeof gsap !== 'undefined') {
      initGsap();
    } else {
      // Fallback: poll until gsap loads
      const poll = setInterval(() => {
        if (typeof gsap !== 'undefined') {
          clearInterval(poll);
          initGsap();
        }
      }, 50);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
