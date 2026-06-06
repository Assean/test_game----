document.addEventListener('DOMContentLoaded', () => {
    initParticles();
    handleHeaderScroll();
    animateOnScroll();
});

// Particle System for the Battle Background
function initParticles() {
    const container = document.querySelector('.particle-system');
    if (!container) return;
    const particleCount = 50;

    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';

        const size = Math.random() * 4 + 1;
        const posX = Math.random() * 100;
        const posY = Math.random() * 100;
        const delay = Math.random() * 5;
        const duration = Math.random() * 3 + 2;

        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${posX}%`;
        particle.style.top = `${posY}%`;
        particle.style.animationDelay = `${delay}s`;
        particle.style.animationDuration = `${duration}s`;

        if (Math.random() > 0.5) {
            particle.classList.add('float-up');
        } else {
            particle.classList.add('pulse');
        }

        container.appendChild(particle);
    }
}

// Header Scroll Effect
function handleHeaderScroll() {
    const header = document.querySelector('header');
    if (!header) return;
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.style.background = 'rgba(5, 5, 11, 0.9)';
            header.style.backdropFilter = 'blur(10px)';
            header.style.padding = '10px 0';
            header.style.borderBottom = '1px solid rgba(255, 255, 255, 0.1)';
        } else {
            header.style.background = 'transparent';
            header.style.backdropFilter = 'none';
            header.style.padding = '20px 0';
            header.style.borderBottom = 'none';
        }
    });
}

// Simple Intersection Observer for scroll animations
function animateOnScroll() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('opacity-100', 'translate-y-0');
                entry.target.classList.remove('opacity-0', 'translate-y-10');
            }
        });
    }, { threshold: 0.1 });

    // Observe all main sections for reveal effects
    document.querySelectorAll('section > div').forEach(el => {
        if (el.parentElement.id !== 'hero') {
            el.classList.add('opacity-0', 'translate-y-10', 'transition-all', 'duration-1000', 'ease-out');
            observer.observe(el);
        }
    });

    document.querySelectorAll('.section-heading').forEach(el => observer.observe(el));
}

// Add mouse movement parallax effect to the battle background
document.addEventListener('mousemove', (e) => {
    const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
    const moveY = (e.clientY - window.innerHeight / 2) * 0.01;

    const bg = document.querySelector('.grid-overlay');
    const characters = document.querySelector('.character-silhouettes');

    if (bg) bg.style.transform = `perspective(1000px) rotateX(60deg) translate(${moveX}px, ${moveY}px)`;
    if (characters) characters.style.transform = `translate(${-moveX * 2}px, ${-moveY * 2}px)`;
});
