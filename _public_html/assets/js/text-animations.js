// Text Animations - Rotating Text Effect
// Inspired by modern web animation libraries
// Pure vanilla JavaScript implementation

class RotatingText {
    constructor(element, options = {}) {
        this.element = element;
        this.words = options.words || [];
        this.duration = options.duration || 2000;
        this.pause = options.pause || 1000;
        this.currentIndex = 0;
        this.isAnimating = false;
        
        if (this.words.length === 0) return;
        
        this.init();
    }
    
    init() {
        // Set up the container
        this.element.style.display = 'inline-block';
        this.element.style.position = 'relative';
        this.element.style.verticalAlign = 'top';
        
        // Create word elements
        this.wordElements = this.words.map((word, index) => {
            const wordEl = document.createElement('span');
            wordEl.textContent = word;
            wordEl.style.position = 'absolute';
            wordEl.style.left = '0';
            wordEl.style.top = '0';
            wordEl.style.width = '100%';
            wordEl.style.opacity = index === 0 ? '1' : '0';
            wordEl.style.transform = index === 0 ? 'translateY(0)' : 'translateY(20px)';
            wordEl.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
            wordEl.className = 'rotating-word';
            return wordEl;
        });
        
        // Add first word to get proper width
        this.element.innerHTML = '';
        this.wordElements.forEach(el => this.element.appendChild(el));
        
        // Start rotation
        this.startRotation();
    }
    
    startRotation() {
        setInterval(() => {
            this.rotate();
        }, this.duration + this.pause);
    }
    
    rotate() {
        if (this.isAnimating) return;
        this.isAnimating = true;
        
        const currentEl = this.wordElements[this.currentIndex];
        const nextIndex = (this.currentIndex + 1) % this.words.length;
        const nextEl = this.wordElements[nextIndex];
        
        // Animate out current word
        currentEl.style.opacity = '0';
        currentEl.style.transform = 'translateY(-20px)';
        
        // Animate in next word
        setTimeout(() => {
            nextEl.style.opacity = '1';
            nextEl.style.transform = 'translateY(0)';
            
            this.currentIndex = nextIndex;
            this.isAnimating = false;
        }, 500);
    }
}

// Typing Text Effect
class TypingText {
    constructor(element, options = {}) {
        this.element = element;
        this.words = options.words || [];
        this.typingSpeed = options.typingSpeed || 100;
        this.deletingSpeed = options.deletingSpeed || 50;
        this.pause = options.pause || 2000;
        this.currentWordIndex = 0;
        this.currentText = '';
        this.isDeleting = false;
        
        if (this.words.length === 0) return;
        
        this.init();
    }
    
    init() {
        this.element.style.display = 'inline-block';
        this.element.style.borderRight = '3px solid var(--accent-white)';
        this.element.style.paddingRight = '5px';
        this.element.style.animation = 'blink 0.7s step-end infinite';
        
        // Add blink animation
        if (!document.getElementById('typing-blink-style')) {
            const style = document.createElement('style');
            style.id = 'typing-blink-style';
            style.textContent = `
                @keyframes blink {
                    0%, 50% { border-color: var(--accent-white); }
                    51%, 100% { border-color: transparent; }
                }
            `;
            document.head.appendChild(style);
        }
        
        this.type();
    }
    
    type() {
        const currentWord = this.words[this.currentWordIndex];
        
        if (this.isDeleting) {
            this.currentText = currentWord.substring(0, this.currentText.length - 1);
        } else {
            this.currentText = currentWord.substring(0, this.currentText.length + 1);
        }
        
        this.element.textContent = this.currentText;
        
        let speed = this.isDeleting ? this.deletingSpeed : this.typingSpeed;
        
        if (!this.isDeleting && this.currentText === currentWord) {
            speed = this.pause;
            this.isDeleting = true;
        } else if (this.isDeleting && this.currentText === '') {
            this.isDeleting = false;
            this.currentWordIndex = (this.currentWordIndex + 1) % this.words.length;
            speed = 500;
        }
        
        setTimeout(() => this.type(), speed);
    }
}

// Glitch Text Effect
class GlitchText {
    constructor(element, options = {}) {
        this.element = element;
        this.text = element.textContent;
        this.intensity = options.intensity || 3;
        this.speed = options.speed || 50;
        
        this.init();
    }
    
    init() {
        this.element.style.position = 'relative';
        this.element.style.display = 'inline-block';
        
        this.element.addEventListener('mouseenter', () => this.startGlitch());
    }
    
    startGlitch() {
        const chars = '!<>-_\\/[]{}—=+*^?#________';
        let iterations = 0;
        const maxIterations = this.text.length;
        
        const interval = setInterval(() => {
            this.element.textContent = this.text
                .split('')
                .map((char, index) => {
                    if (index < iterations) {
                        return this.text[index];
                    }
                    return chars[Math.floor(Math.random() * chars.length)];
                })
                .join('');
            
            iterations += 1/this.intensity;
            
            if (iterations >= maxIterations) {
                clearInterval(interval);
                this.element.textContent = this.text;
            }
        }, this.speed);
    }
}

// Reveal Text on Scroll
class RevealText {
    constructor(element, options = {}) {
        this.element = element;
        this.direction = options.direction || 'up'; // up, down, left, right
        this.duration = options.duration || 0.8;
        this.delay = options.delay || 0;
        
        this.init();
    }
    
    init() {
        // Set initial state
        const transforms = {
            up: 'translateY(30px)',
            down: 'translateY(-30px)',
            left: 'translateX(30px)',
            right: 'translateX(-30px)'
        };
        
        this.element.style.opacity = '0';
        this.element.style.transform = transforms[this.direction];
        this.element.style.transition = `all ${this.duration}s cubic-bezier(0.4, 0, 0.2, 1) ${this.delay}s`;
        
        // Create observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.reveal();
                    observer.unobserve(this.element);
                }
            });
        }, { threshold: 0.1 });
        
        observer.observe(this.element);
    }
    
    reveal() {
        this.element.style.opacity = '1';
        this.element.style.transform = 'translate(0, 0)';
    }
}

// Split Text Animation (letters appear one by one)
class SplitText {
    constructor(element, options = {}) {
        this.element = element;
        this.text = element.textContent;
        this.animation = options.animation || 'fade'; // fade, slide, rotate
        this.stagger = options.stagger || 0.03;
        this.autoPlay = options.autoPlay !== false;
        
        this.init();
    }
    
    init() {
        this.element.innerHTML = '';
        this.element.style.display = 'inline-block';
        
        this.letters = this.text.split('').map((char, index) => {
            const span = document.createElement('span');
            span.textContent = char === ' ' ? '\u00A0' : char;
            span.style.display = 'inline-block';
            span.style.opacity = '0';
            span.style.transition = `all 0.5s cubic-bezier(0.4, 0, 0.2, 1) ${index * this.stagger}s`;
            
            if (this.animation === 'slide') {
                span.style.transform = 'translateY(20px)';
            } else if (this.animation === 'rotate') {
                span.style.transform = 'rotateX(90deg)';
                span.style.transformOrigin = 'bottom';
            }
            
            this.element.appendChild(span);
            return span;
        });
        
        if (this.autoPlay) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.animate();
                        observer.unobserve(this.element);
                    }
                });
            }, { threshold: 0.1 });
            
            observer.observe(this.element);
        }
    }
    
    animate() {
        this.letters.forEach(letter => {
            letter.style.opacity = '1';
            letter.style.transform = 'translate(0, 0) rotate(0)';
        });
    }
}

// Gradient Text Animation
class GradientText {
    constructor(element, options = {}) {
        this.element = element;
        this.colors = options.colors || ['#ffffff', '#999999', '#ffffff'];
        this.duration = options.duration || 3;
        
        this.init();
    }
    
    init() {
        const gradient = `linear-gradient(90deg, ${this.colors.join(', ')})`;
        this.element.style.background = gradient;
        this.element.style.backgroundSize = '200% 100%';
        this.element.style.webkitBackgroundClip = 'text';
        this.element.style.backgroundClip = 'text';
        this.element.style.webkitTextFillColor = 'transparent';
        this.element.style.animation = `gradientShift ${this.duration}s ease infinite`;
        
        // Add gradient animation
        if (!document.getElementById('gradient-shift-style')) {
            const style = document.createElement('style');
            style.id = 'gradient-shift-style';
            style.textContent = `
                @keyframes gradientShift {
                    0%, 100% { background-position: 0% 50%; }
                    50% { background-position: 100% 50%; }
                }
            `;
            document.head.appendChild(style);
        }
    }
}

// Auto-initialize based on data attributes
function initTextAnimations() {
    // Rotating text
    document.querySelectorAll('[data-rotating-text]').forEach(element => {
        const words = element.getAttribute('data-rotating-text').split(',').map(w => w.trim());
        const duration = parseInt(element.getAttribute('data-duration')) || 2000;
        new RotatingText(element, { words, duration });
    });
    
    // Typing text
    document.querySelectorAll('[data-typing-text]').forEach(element => {
        const words = element.getAttribute('data-typing-text').split(',').map(w => w.trim());
        new TypingText(element, { words });
    });
    
    // Glitch text
    document.querySelectorAll('[data-glitch-text]').forEach(element => {
        new GlitchText(element);
    });
    
    // Reveal text
    document.querySelectorAll('[data-reveal-text]').forEach(element => {
        const direction = element.getAttribute('data-reveal-direction') || 'up';
        const delay = parseFloat(element.getAttribute('data-reveal-delay')) || 0;
        new RevealText(element, { direction, delay });
    });
    
    // Split text
    document.querySelectorAll('[data-split-text]').forEach(element => {
        const animation = element.getAttribute('data-split-animation') || 'fade';
        new SplitText(element, { animation });
    });
    
    // Gradient text
    document.querySelectorAll('[data-gradient-text]').forEach(element => {
        const colors = element.getAttribute('data-gradient-text')?.split(',') || undefined;
        new GradientText(element, { colors });
    });
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTextAnimations);
} else {
    initTextAnimations();
}

// Export for manual usage
window.TextAnimations = {
    RotatingText,
    TypingText,
    GlitchText,
    RevealText,
    SplitText,
    GradientText
};
