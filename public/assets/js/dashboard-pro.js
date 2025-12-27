/* ============================================
   PROFESSIONAL DASHBOARD JAVASCRIPT
   Animations & Interactions
   ============================================ */

document.addEventListener('DOMContentLoaded', function() {
    
    // Counter Animation
    function animateCounter() {
        const counters = document.querySelectorAll('.counter');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            
            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };
            
            // Intersection Observer - only animate when visible
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    updateCounter();
                    observer.disconnect();
                }
            }, { threshold: 0.5 });
            
            observer.observe(counter);
        });
    }
    
    // Fade in animation for cards
    function fadeInCards() {
        const cards = document.querySelectorAll('.metric-card, .cta-panel, .dashboard-section, .insights-card');
        
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }
    
    // Progress bar animation
    function animateProgressBars() {
        const progressBars = document.querySelectorAll('.score-fill, .progress-fill');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const bar = entry.target;
                    const width = bar.style.width;
                    bar.style.width = '0';
                    
                    setTimeout(() => {
                        bar.style.transition = 'width 1s ease-out';
                        bar.style.width = width;
                    }, 300);
                    
                    observer.unobserve(bar);
                }
            });
        }, { threshold: 0.5 });
        
        progressBars.forEach(bar => observer.observe(bar));
    }
    
    // Table row click to expand (optional enhancement)
    function handleTableRows() {
        const rows = document.querySelectorAll('.job-row');
        
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Don't trigger if clicking on action buttons
                if (!e.target.closest('.action-buttons')) {
                    this.style.backgroundColor = 'var(--gray-50)';
                    setTimeout(() => {
                        this.style.backgroundColor = '';
                    }, 300);
                }
            });
        });
    }
    
    // Tooltip for action buttons
    function initTooltips() {
        const buttons = document.querySelectorAll('[title]');
        
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function(e) {
                const title = this.getAttribute('title');
                if (!title) return;
                
                const tooltip = document.createElement('div');
                tooltip.className = 'custom-tooltip';
                tooltip.textContent = title;
                tooltip.style.cssText = `
                    position: absolute;
                    background: #1f2937;
                    color: white;
                    padding: 0.5rem 0.75rem;
                    border-radius: 6px;
                    font-size: 0.875rem;
                    pointer-events: none;
                    z-index: 1000;
                    white-space: nowrap;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                `;
                
                document.body.appendChild(tooltip);
                
                const rect = this.getBoundingClientRect();
                tooltip.style.top = (rect.top - tooltip.offsetHeight - 8) + 'px';
                tooltip.style.left = (rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)) + 'px';
                
                this.addEventListener('mouseleave', function() {
                    tooltip.remove();
                }, { once: true });
            });
        });
    }
    
    // Smooth scroll for anchor links
    function smoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
    
    // CTA Button ripple effect
    function addRippleEffect() {
        const buttons = document.querySelectorAll('.btn-cta, .btn-primary-empty');
        
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.className = 'ripple';
                
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.6);
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    animation: ripple-animation 0.6s ease-out;
                    pointer-events: none;
                `;
                
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });
        
        // Add ripple animation to CSS
        if (!document.querySelector('#ripple-style')) {
            const style = document.createElement('style');
            style.id = 'ripple-style';
            style.textContent = `
                @keyframes ripple-animation {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    // Initialize all functions
    animateCounter();
    fadeInCards();
    animateProgressBars();
    handleTableRows();
    initTooltips();
    smoothScroll();
    addRippleEffect();
    
    // Add notification badge pulse animation
    function pulseNotifications() {
        const badges = document.querySelectorAll('.metric-trend');
        
        badges.forEach(badge => {
            setInterval(() => {
                badge.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    badge.style.transform = 'scale(1)';
                }, 200);
            }, 3000);
        });
    }
    
    pulseNotifications();
    
    // Console greeting
    console.log('%c🚀 Professional Dashboard Loaded', 'color: #667eea; font-size: 16px; font-weight: bold;');
    console.log('%cBuilt with ❤️ for modern hiring', 'color: #6b7280; font-size: 12px;');
});

// Optional: Auto-refresh data every 30 seconds
setInterval(() => {
    // Add your AJAX refresh logic here
    console.log('Data refresh check...');
}, 30000);
