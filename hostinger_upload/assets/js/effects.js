// Interactive Effects for Jake Barton Website
// Includes: Particles Background, Flowing Menu, Scroll Reveal

// ======================
// UTILITY: Debounce Function
// ======================
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ======================
// 1. PARTICLES BACKGROUND
// ======================
class ParticlesBackground {
    constructor() {
        this.canvas = null;
        this.ctx = null;
        this.particles = [];
        this.particleCount = 80; // Number of particles
        this.init();
    }

    init() {
        // Create canvas for particles
        this.canvas = document.createElement('canvas');
        this.canvas.id = 'particles-canvas';
        this.canvas.style.position = 'fixed';
        this.canvas.style.top = '0';
        this.canvas.style.left = '0';
        this.canvas.style.width = '100%';
        this.canvas.style.height = '100%';
        this.canvas.style.zIndex = '-1';
        this.canvas.style.pointerEvents = 'none';

        // Insert before animated-bg
        const animatedBg = document.querySelector('.animated-bg');
        if (animatedBg) {
            animatedBg.parentNode.insertBefore(this.canvas, animatedBg);
        } else {
            document.body.insertBefore(this.canvas, document.body.firstChild);
        }

        this.ctx = this.canvas.getContext('2d');
        this.resize();
        this.createParticles();
        this.animate();

        // Handle resize with debouncing
        const debouncedResize = debounce(() => this.resize(), 250);
        window.addEventListener('resize', debouncedResize);
    }

    resize() {
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;
    }

    createParticles() {
        this.particles = [];
        for (let i = 0; i < this.particleCount; i++) {
            this.particles.push({
                x: Math.random() * this.canvas.width,
                y: Math.random() * this.canvas.height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                size: Math.random() * 2 + 1,
                opacity: Math.random() * 0.5 + 0.2
            });
        }
    }

    animate() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        // Update and draw particles
        this.particles.forEach((particle, i) => {
            // Move particle
            particle.x += particle.vx;
            particle.y += particle.vy;

            // Wrap around screen
            if (particle.x < 0) particle.x = this.canvas.width;
            if (particle.x > this.canvas.width) particle.x = 0;
            if (particle.y < 0) particle.y = this.canvas.height;
            if (particle.y > this.canvas.height) particle.y = 0;

            // Draw particle
            this.ctx.fillStyle = `rgba(255, 255, 255, ${particle.opacity})`;
            this.ctx.beginPath();
            this.ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
            this.ctx.fill();

            // Draw connections
            for (let j = i + 1; j < this.particles.length; j++) {
                const p2 = this.particles[j];
                const dx = particle.x - p2.x;
                const dy = particle.y - p2.y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < 120) {
                    const opacity = (1 - distance / 120) * 0.2;
                    this.ctx.strokeStyle = `rgba(255, 255, 255, ${opacity})`;
                    this.ctx.lineWidth = 0.5;
                    this.ctx.beginPath();
                    this.ctx.moveTo(particle.x, particle.y);
                    this.ctx.lineTo(p2.x, p2.y);
                    this.ctx.stroke();
                }
            }
        });

        requestAnimationFrame(() => this.animate());
    }
}

// ======================
// 2. FLOWING MENU
// ======================
class FlowingMenu {
    constructor() {
        this.nav = document.querySelector('nav ul');
        this.links = Array.from(document.querySelectorAll('nav ul li a'));
        this.indicator = null;
        
        if (!this.nav || this.links.length === 0) return;
        
        this.init();
    }

    init() {
        // Create flowing indicator
        this.indicator = document.createElement('div');
        this.indicator.className = 'nav-indicator';
        this.indicator.style.width = '0px';
        this.indicator.style.opacity = '1';
        this.nav.appendChild(this.indicator);

        // Track active link
        this.links.forEach((link, index) => {
            link.addEventListener('mouseenter', () => this.moveIndicator(link));
            link.addEventListener('click', (e) => {
                this.setActive(link);
            });
        });

        // Set initial position
        const activeLink = document.querySelector('nav ul li a.active') || this.links[0];
        if (activeLink) {
            setTimeout(() => {
                this.setActive(activeLink);
            }, 50);
        }

        // Handle mouse leave
        this.nav.addEventListener('mouseleave', () => {
            const activeLink = document.querySelector('nav ul li a.active') || this.links[0];
            if (activeLink) {
                this.moveIndicator(activeLink);
            }
        });
    }

    moveIndicator(link) {
        if (!this.indicator || !link) return;
        
        const linkRect = link.getBoundingClientRect();
        const navRect = this.nav.getBoundingClientRect();
        
        const width = linkRect.width;
        const left = linkRect.left - navRect.left;
        
        this.indicator.style.width = `${width}px`;
        this.indicator.style.left = `${left}px`;
    }

    setActive(link) {
        this.links.forEach(l => l.classList.remove('active'));
        link.classList.add('active');
        this.moveIndicator(link);
    }
}

// ======================
// 3. SCROLL REVEAL ANIMATIONS
// ======================
class ScrollReveal {
    constructor() {
        this.elements = [];
        this.init();
    }

    init() {
        // Find all sections to reveal
        this.elements = document.querySelectorAll('.content-section, .showcase-item, .skill-tag');
        
        // Create observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        // Observe all elements
        this.elements.forEach(el => {
            el.classList.add('reveal-item');
            observer.observe(el);
        });
    }
}

// ======================
// INITIALIZE ALL EFFECTS
// ======================
function initializeEffects() {
    try {
        new ParticlesBackground();
    } catch (e) {
        // Silently fail in production
    }

    try {
        new FlowingMenu();
    } catch (e) {
        // Silently fail in production
    }

    try {
        new ScrollReveal();
    } catch (e) {
        // Silently fail in production
    }

    // Mobile navigation
    try {
        new MobileNav();
    } catch (e) {}
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeEffects);
} else {
    initializeEffects();
}

// ======================
// 4. MOBILE NAVIGATION TOGGLE
// ======================
class MobileNav {
    constructor() {
        this.button = document.querySelector('.nav-toggle');
        this.menu = document.querySelector('#primary-menu') || document.querySelector('nav ul');
        if (!this.button || !this.menu) return;
        this.expanded = false;
        this.outsideHandler = (e) => {
            if (!this.expanded) return;
            // Check if click is on button or its children (the bars)
            if (!this.menu.contains(e.target) && !this.button.contains(e.target)) {
                this.toggle(false);
            }
        };
        this.bindEvents();
        // Ensure menu starts closed
        this.menu.classList.remove('open');
        this.button.setAttribute('aria-expanded','false');
        // Add drag scroll functionality
        this.addDragScroll();
    }
    
    addDragScroll() {
        let isDown = false;
        let startX;
        let scrollLeft;
        let hasMoved = false;

        this.menu.addEventListener('mousedown', (e) => {
            // Don't interfere with scrollbar clicks
            if (e.target === this.menu) {
                isDown = true;
                hasMoved = false;
                this.menu.style.cursor = 'grabbing';
                startX = e.pageX - this.menu.offsetLeft;
                scrollLeft = this.menu.scrollLeft;
            }
        });

        this.menu.addEventListener('mouseleave', () => {
            isDown = false;
            if (this.expanded) this.menu.style.cursor = 'grab';
        });

        this.menu.addEventListener('mouseup', () => {
            isDown = false;
            if (this.expanded) this.menu.style.cursor = 'grab';
        });

        this.menu.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            hasMoved = true;
            const x = e.pageX - this.menu.offsetLeft;
            const walk = (x - startX) * 2;
            this.menu.scrollLeft = scrollLeft - walk;
        });

        // Touch events for mobile (native scroll works better)
        let touchStartX = 0;
        let touchHasMoved = false;
        
        this.menu.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].pageX;
            touchHasMoved = false;
        }, { passive: true });

        this.menu.addEventListener('touchmove', (e) => {
            const touchMoveX = e.touches[0].pageX;
            if (Math.abs(touchMoveX - touchStartX) > 10) {
                touchHasMoved = true;
            }
        }, { passive: true });

        // Store hasMoved state for click handler
        this.menu._dragState = { hasMoved: () => hasMoved || touchHasMoved };
        
        this.menu.addEventListener('touchend', () => {
            setTimeout(() => { touchHasMoved = false; }, 100);
        });
    }
    bindEvents() {
        this.button.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggle();
        });
        // Handle link clicks through event delegation on the ul
        this.menu.addEventListener('click', (e) => {
            // Don't navigate if user was dragging
            if (this.menu._dragState && this.menu._dragState.hasMoved()) {
                return;
            }
            
            const link = e.target.closest('a');
            if (link && link.href) {
                e.preventDefault();
                if (this.expanded) this.toggle();
                // Navigate after a brief delay to allow menu to close
                setTimeout(() => {
                    if (link.hasAttribute('download')) {
                        // Handle download links
                        const tempLink = document.createElement('a');
                        tempLink.href = link.href;
                        tempLink.download = link.getAttribute('download') || '';
                        document.body.appendChild(tempLink);
                        tempLink.click();
                        document.body.removeChild(tempLink);
                    } else {
                        window.location.href = link.href;
                    }
                }, 150);
            }
        });
        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.expanded) this.toggle(false);
        });
        // Close on outside click
        document.addEventListener('click', this.outsideHandler);
    }
    toggle(force) {
        this.expanded = typeof force === 'boolean' ? force : !this.expanded;
        this.button.setAttribute('aria-expanded', this.expanded);
        if (this.expanded) {
            this.menu.classList.add('open');
            this.button.setAttribute('aria-label', 'Close menu');
            // Focus first link for accessibility
            const firstLink = this.menu.querySelector('a');
            if (firstLink) firstLink.focus();
            // Rotate bars via aria-expanded attribute already styled in CSS
        } else {
            this.menu.classList.remove('open');
            this.button.setAttribute('aria-label', 'Open menu');
        }
    }
}

// ======================
// 5. 3D CAROUSEL MOUSE TRACKING
// ======================
class Carousel3DEffect {
    constructor() {
        this.carousel = document.querySelector('.carousel-wrapper');
        this.activeCard = null;
        this.mouseX = 0;
        this.mouseY = 0;
        this.currentX = 0;
        this.currentY = 0;
        this.isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        
        if (this.carousel) {
            this.init();
        }
    }
    
    init() {
        if (this.isMobile) {
            // Use gyroscope/device orientation for mobile
            if (window.DeviceOrientationEvent) {
                window.addEventListener('deviceorientation', (e) => {
                    // Beta: front-back tilt (-180 to 180)
                    // Gamma: left-right tilt (-90 to 90)
                    const beta = e.beta || 0;  // Front-back tilt
                    const gamma = e.gamma || 0; // Left-right tilt
                    
                    // Normalize to -1 to 1 range
                    // Gamma: -90 to 90 -> -1 to 1
                    this.mouseX = Math.max(-1, Math.min(1, gamma / 45));
                    // Beta: Use 30-90 range (phone held upright to tilted forward)
                    this.mouseY = Math.max(-1, Math.min(1, (beta - 60) / 30));
                });
                
                // Request permission for iOS 13+
                if (typeof DeviceOrientationEvent.requestPermission === 'function') {
                    // Will be requested when user interacts with carousel
                    this.carousel.addEventListener('click', () => {
                        DeviceOrientationEvent.requestPermission()
                            .then(permissionState => {
                                if (permissionState === 'granted') {
                                    console.log('Gyroscope permission granted');
                                }
                            })
                            .catch(console.error);
                    }, { once: true });
                }
            }
        } else {
            // Track mouse movement for desktop
            document.addEventListener('mousemove', (e) => {
                // Get mouse position relative to viewport center (-1 to 1)
                this.mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
                this.mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
            });
        }
        
        // Start animation loop
        this.animate();
    }
    
    animate() {
        // Get active card
        this.activeCard = this.carousel.querySelector('.carousel-slide.position-0');
        
        // Reset all non-active cards to prevent 3D effects
        const allSlides = this.carousel.querySelectorAll('.carousel-slide');
        allSlides.forEach(slide => {
            if (!slide.classList.contains('position-0')) {
                const card = slide.querySelector('.carousel-card');
                if (card) {
                    card.style.transform = '';
                }
            }
        });
        
        if (this.activeCard) {
            // Smooth interpolation
            this.currentX += (this.mouseX - this.currentX) * 0.1;
            this.currentY += (this.mouseY - this.currentY) * 0.1;
            
            // Calculate rotation based on mouse position
            const rotateY = this.currentX * 12; // Max 12 degrees rotation
            const rotateX = -this.currentY * 12; // Negative for natural feeling
            
            // Apply 3D transform ONLY to active card
            const card = this.activeCard.querySelector('.carousel-card');
            if (card) {
                card.style.transform = `
                    perspective(1000px)
                    rotateX(${rotateX}deg)
                    rotateY(${rotateY}deg)
                    translateZ(20px)
                    scale3d(1.02, 1.02, 1.02)
                `;
            }
        }
        
        requestAnimationFrame(() => this.animate());
    }
}

// Initialize 3D effect
try {
    new Carousel3DEffect();
} catch (e) {
    console.log('3D carousel effect not initialized');
}
