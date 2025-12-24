/**
 * Digital Dreamscape Theme JavaScript
 * Handles mobile menu toggle and basic interactions
 */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Remove extra menu items that aren't part of the main ul list
        var navigation = document.querySelector('.main-navigation');
        if (navigation) {
            var navChildren = Array.from(navigation.children);
            navChildren.forEach(function (child) {
                // Keep only ul elements
                if (child.tagName !== 'UL') {
                    child.remove();
                } else {
                    // Also clean up any links inside ul that contain "Watch Live" or "Read Episodes"
                    var links = child.querySelectorAll('a');
                    links.forEach(function (link) {
                        var linkText = link.textContent.trim().toLowerCase();
                        if (linkText.includes('watch live') ||
                            (linkText.includes('read epi') && !linkText.includes('blog'))) {
                            var listItem = link.closest('li');
                            if (listItem) {
                                listItem.remove();
                            }
                        }
                    });
                }
            });
        }

        // Mobile menu toggle
        var menuToggle = document.querySelector('.mobile-menu-toggle');

        if (menuToggle && navigation) {
            menuToggle.addEventListener('click', function () {
                navigation.classList.toggle('active');
                var isExpanded = navigation.classList.contains('active');
                menuToggle.setAttribute('aria-expanded', isExpanded);
            });

            // Close menu when clicking on a link
            var navLinks = navigation.querySelectorAll('a');
            navLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    navigation.classList.remove('active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                });
            });
        }

        // Smooth scroll for anchor links
        var anchorLinks = document.querySelectorAll('a[href^="#"]');
        anchorLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                var href = link.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    var target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
    });
})();

