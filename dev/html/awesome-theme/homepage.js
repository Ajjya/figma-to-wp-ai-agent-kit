/**
 * Awesome Crypto Wallet - Homepage JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Mobile Navigation Toggle
    initMobileNav();
    
    // FAQ Accordion
    initFaqAccordion();
    
    // Smooth scroll for anchor links
    initSmoothScroll();
});

/**
 * Mobile Navigation
 */
function initMobileNav() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (!hamburger || !navMenu) return;
    
    // Track if menu has been opened at least once
    let hasInteracted = false;
    
    hamburger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Add transition class after first interaction
        if (!hasInteracted) {
            navMenu.classList.add('has-transitioned');
            hasInteracted = true;
        }
        
        const isActive = navMenu.classList.contains('active');
        
        if (isActive) {
            // Close menu
            this.classList.remove('active');
            navMenu.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            // Open menu
            this.classList.add('active');
            navMenu.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    });
    
    // Close menu when clicking a link
    const navLinks = navMenu.querySelectorAll('a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
            document.body.style.overflow = '';
        });
    });
    
    // Close menu on window resize to desktop
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 1062) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                navMenu.classList.remove('has-transitioned');
                document.body.style.overflow = '';
                hasInteracted = false;
            }
        }, 100);
    });
}

/**
 * FAQ Accordion
 */
function initFaqAccordion() {
    const faqItems = document.querySelectorAll('.faq__item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq__question');
        const answer = item.querySelector('.faq__answer');
        
        if (!question || !answer) return;
        
        question.addEventListener('click', function() {
            const isExpanded = item.classList.contains('faq__item--expanded');
            
            // Close all other items
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('faq__item--expanded');
                    const otherAnswer = otherItem.querySelector('.faq__answer');
                    if (otherAnswer) {
                        otherAnswer.classList.remove('faq__answer--visible');
                    }
                    const otherQuestion = otherItem.querySelector('.faq__question');
                    if (otherQuestion) {
                        otherQuestion.setAttribute('aria-expanded', 'false');
                    }
                }
            });
            
            // Toggle current item
            if (isExpanded) {
                item.classList.remove('faq__item--expanded');
                answer.classList.remove('faq__answer--visible');
                question.setAttribute('aria-expanded', 'false');
            } else {
                item.classList.add('faq__item--expanded');
                answer.classList.add('faq__answer--visible');
                question.setAttribute('aria-expanded', 'true');
            }
        });
    });
}

/**
 * Smooth Scroll
 */
function initSmoothScroll() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                
                const headerHeight = document.querySelector('.header')?.offsetHeight || 0;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Button hover effects
 */
document.querySelectorAll('.btn, .app-store-btn').forEach(button => {
    button.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
    });
    
    button.addEventListener('mouseleave', function() {
        this.style.transform = '';
    });
});
