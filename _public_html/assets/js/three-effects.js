// Three.js Enhanced Effects for Jake Barton Website
// Portfolio Cards with 3D Tilt Effect (Lightweight Version)

// ======================
// 3D PORTFOLIO CARDS (Mouse-Tracking Tilt)
// ======================
class PortfolioCards3D {
    constructor() {
        this.cards = document.querySelectorAll('.carousel-card, .portfolio-card');
        if (this.cards.length === 0) return;
        
        this.init();
    }

    init() {
        this.cards.forEach(card => {
            card.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
            card.style.transformStyle = 'preserve-3d';

            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05, 1.05, 1.05)`;
                card.style.boxShadow = '0 20px 60px rgba(255, 255, 255, 0.2)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
                card.style.boxShadow = '0 8px 24px rgba(0, 0, 0, 0.6)';
            });
        });
    }
}

// ======================
// INITIALIZE EFFECTS
// ======================
function initializeThreeJS() {
    console.log('Initializing 3D card effects...');

    // Portfolio Card 3D Effects
    try {
        setTimeout(() => {
            new PortfolioCards3D();
            console.log('✓ Portfolio Cards 3D initialized');
        }, 500);
    } catch (e) {
        console.error('Portfolio Cards error:', e);
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeThreeJS);
} else {
    initializeThreeJS();
}

export { PortfolioCards3D };
