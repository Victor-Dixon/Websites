/**
 * Digital Dreamscape Theme JavaScript
 * Handles mobile menu toggle and basic interactions
 */

(function () {
    'use strict';

    // Function to clean up extra menu items - ULTRA AGGRESSIVE
    function cleanupMenuItems() {
        var navigation = document.querySelector('.main-navigation');
        if (!navigation) return;

        // Remove any direct children that aren't ul elements
        var navChildren = Array.from(navigation.childNodes);
        navChildren.forEach(function (child) {
            // Skip text nodes
            if (child.nodeType !== 1) return; // 1 = ELEMENT_NODE

            // Keep only ul elements with id="primary-menu" or class="menu"
            var isMenuList = child.tagName === 'UL' &&
                (child.id === 'primary-menu' || child.classList.contains('menu'));

            if (!isMenuList) {
                // Immediately hide with inline styles
                if (child.style) {
                    child.style.setProperty('display', 'none', 'important');
                    child.style.setProperty('visibility', 'hidden', 'important');
                    child.style.setProperty('height', '0', 'important');
                    child.style.setProperty('width', '0', 'important');
                    child.style.setProperty('overflow', 'hidden', 'important');
                    child.style.setProperty('position', 'absolute', 'important');
                    child.style.setProperty('left', '-9999px', 'important');
                    child.style.setProperty('opacity', '0', 'important');
                    child.style.setProperty('pointer-events', 'none', 'important');
                }

                // Force remove immediately
                try {
                    if (child.parentNode) {
                        child.parentNode.removeChild(child);
                    }
                } catch (e) {
                    // If remove fails, try with remove() method
                    try {
                        child.remove();
                    } catch (e2) {
                        // Last resort - set innerHTML to empty
                        if (child.innerHTML !== undefined) {
                            child.innerHTML = '';
                        }
                    }
                }
            }
        });

        // Remove nav-cta div completely (contains Watch Live and Read Episodes)
        var navCtaDivs = navigation.querySelectorAll('.nav-cta, div.nav-cta');
        navCtaDivs.forEach(function (div) {
            div.style.display = 'none';
            div.style.visibility = 'hidden';
            div.style.height = '0';
            div.style.width = '0';
            div.style.overflow = 'hidden';
            div.style.position = 'absolute';
            div.style.left = '-9999px';
            div.style.opacity = '0';
            div.style.pointerEvents = 'none';
            setTimeout(function () {
                try {
                    if (div.parentNode) {
                        div.parentNode.removeChild(div);
                    } else {
                        div.remove();
                    }
                } catch (e) {
                    // Ignore if already removed
                }
            }, 50);
        });

        // Also remove links containing "Watch Live" or "Read Episodes" as backup
        var allLinks = navigation.querySelectorAll('a');
        allLinks.forEach(function (link) {
            var linkText = link.textContent.trim().toLowerCase();
            if (linkText.includes('watch live') ||
                linkText.includes('watch live →') ||
                (linkText.includes('read epi') && !linkText.includes('read the blog'))) {
                var parent = link.parentElement;
                if (parent && !parent.classList.contains('menu') && parent.tagName !== 'LI') {
                    // Remove if parent is not a menu item
                    parent.style.display = 'none';
                    parent.style.visibility = 'hidden';
                    setTimeout(function () {
                        try {
                            if (parent.parentNode) {
                                parent.parentNode.removeChild(parent);
                            } else {
                                parent.remove();
                            }
                        } catch (e) {
                            // Ignore if already removed
                        }
                    }, 50);
                }
            }
        });
    }

    // Run cleanup immediately if DOM is already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMenuCleanup);
    } else {
        initMenuCleanup();
    }

    function initMenuCleanup() {
        // Clean up immediately
        cleanupMenuItems();

        // Use MutationObserver to catch dynamically added elements
        var navigation = document.querySelector('.main-navigation');
        if (navigation) {
            var observer = new MutationObserver(function (mutations) {
                cleanupMenuItems();
            });

            observer.observe(navigation, {
                childList: true,
                subtree: true,
                attributes: false,
                characterData: false
            });
        }

        // Run cleanup multiple times to catch all variations
        setTimeout(cleanupMenuItems, 100);
        setTimeout(cleanupMenuItems, 300);
        setTimeout(cleanupMenuItems, 500);
        setTimeout(cleanupMenuItems, 1000);
        setTimeout(cleanupMenuItems, 2000);

        // Also run on window load
        window.addEventListener('load', cleanupMenuItems);
    }

    // Original DOMContentLoaded handler for other functionality
    document.addEventListener('DOMContentLoaded', function () {
        // Mobile menu toggle
        var menuToggle = document.querySelector('.mobile-menu-toggle');
        var navigation = document.querySelector('.main-navigation');

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

