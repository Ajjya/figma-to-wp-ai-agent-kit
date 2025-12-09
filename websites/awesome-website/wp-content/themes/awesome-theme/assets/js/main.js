/**
 * Awesome Theme - Main JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
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
        var $hamburger = $('.hamburger');
        var $navMenu = $('.nav-menu');
        
        if (!$hamburger.length || !$navMenu.length) return;
        
        // Track if menu has been opened at least once
        var hasInteracted = false;
        
        $hamburger.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Add transition class after first interaction
            if (!hasInteracted) {
                $navMenu.addClass('has-transitioned');
                hasInteracted = true;
            }
            
            var isActive = $navMenu.hasClass('active');
            
            if (isActive) {
                // Close menu
                $(this).removeClass('active');
                $navMenu.removeClass('active');
                $('body').css('overflow', '');
            } else {
                // Open menu
                $(this).addClass('active');
                $navMenu.addClass('active');
                $('body').css('overflow', 'hidden');
            }
        });
        
        // Close menu when clicking a link
        $navMenu.find('a').on('click', function() {
            $hamburger.removeClass('active');
            $navMenu.removeClass('active');
            $('body').css('overflow', '');
        });
        
        // Close menu on window resize to desktop
        var resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if ($(window).width() > 1062) {
                    $hamburger.removeClass('active');
                    $navMenu.removeClass('active').removeClass('has-transitioned');
                    $('body').css('overflow', '');
                    hasInteracted = false;
                }
            }, 100);
        });
    }

    /**
     * FAQ Accordion
     */
    function initFaqAccordion() {
        var $faqItems = $('.faq__item');
        
        $faqItems.each(function() {
            var $item = $(this);
            var $question = $item.find('.faq__question');
            var $answer = $item.find('.faq__answer');
            
            if (!$question.length || !$answer.length) return;
            
            $question.on('click', function() {
                var isExpanded = $item.hasClass('faq__item--expanded');
                
                // Close all other items
                $faqItems.not($item).each(function() {
                    $(this).removeClass('faq__item--expanded');
                    $(this).find('.faq__answer').removeClass('faq__answer--visible');
                    $(this).find('.faq__question').attr('aria-expanded', 'false');
                });
                
                // Toggle current item
                if (isExpanded) {
                    $item.removeClass('faq__item--expanded');
                    $answer.removeClass('faq__answer--visible');
                    $question.attr('aria-expanded', 'false');
                } else {
                    $item.addClass('faq__item--expanded');
                    $answer.addClass('faq__answer--visible');
                    $question.attr('aria-expanded', 'true');
                }
            });
        });
    }

    /**
     * Smooth Scroll
     */
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            var targetId = $(this).attr('href');
            
            if (targetId === '#') return;
            
            var $targetElement = $(targetId);
            
            if ($targetElement.length) {
                e.preventDefault();
                
                var headerHeight = $('.header').outerHeight() || 0;
                var targetPosition = $targetElement.offset().top - headerHeight;
                
                $('html, body').animate({
                    scrollTop: targetPosition
                }, 500);
            }
        });
    }

})(jQuery);
