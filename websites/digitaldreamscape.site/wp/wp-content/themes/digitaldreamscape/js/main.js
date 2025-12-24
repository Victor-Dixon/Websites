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
        // Also check for any generic divs that might contain these links
        var navCtaDivs = navigation.querySelectorAll('.nav-cta, div.nav-cta, div:not(.menu):not(#primary-menu)');
        navCtaDivs.forEach(function (div) {
            // Check if this div contains "Watch Live" or "Read Episodes"
            var divText = div.textContent.trim().toLowerCase();
            if (divText.includes('watch live') || 
                (divText.includes('read epi') && !divText.includes('read the blog'))) {
                // Immediately hide
                div.style.setProperty('display', 'none', 'important');
                div.style.setProperty('visibility', 'hidden', 'important');
                div.style.setProperty('height', '0', 'important');
                div.style.setProperty('width', '0', 'important');
                div.style.setProperty('overflow', 'hidden', 'important');
                div.style.setProperty('position', 'absolute', 'important');
                div.style.setProperty('left', '-9999px', 'important');
                div.style.setProperty('opacity', '0', 'important');
                div.style.setProperty('pointer-events', 'none', 'important');
                
                // Force remove
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
                }, 10);
            }
        });

        // Also remove links containing "Watch Live" or "Read Episodes" as backup
        var allLinks = navigation.querySelectorAll('a');
        allLinks.forEach(function (link) {
            var linkText = link.textContent.trim().toLowerCase();
            if (linkText.includes('watch live') ||
                linkText.includes('watch live →') ||
                (linkText.includes('read epi') && !linkText.includes('read the blog'))) {
                var parent = link.parentElement;
                // Remove regardless of parent - these should not appear in nav
                if (parent) {
                    // First hide immediately
                    parent.style.display = 'none';
                    parent.style.visibility = 'hidden';
                    parent.style.height = '0';
                    parent.style.width = '0';
                    parent.style.overflow = 'hidden';
                    parent.style.opacity = '0';
                    parent.style.pointerEvents = 'none';

                    // Also hide the link itself
                    link.style.display = 'none';
                    link.style.visibility = 'hidden';

                    setTimeout(function () {
                        try {
                            if (parent.parentNode) {
                                parent.parentNode.removeChild(parent);
                            } else {
                                parent.remove();
                            }
                        } catch (e) {
                            // If parent removal fails, try removing link
                            try {
                                if (link.parentNode) {
                                    link.parentNode.removeChild(link);
                                } else {
                                    link.remove();
                                }
                            } catch (e2) {
                                // Ignore if already removed
                            }
                        }
                    }, 50);
                }
            }
        });

        // Also check for text nodes containing "Watch Live" or "Read Episodes"
        // This handles cases where they might appear as plain text
        var walker = document.createTreeWalker(
            navigation,
            NodeFilter.SHOW_TEXT,
            null,
            false
        );

        var textNode;
        var nodesToRemove = [];
        while (textNode = walker.nextNode()) {
            var text = textNode.textContent.trim().toLowerCase();
            if (text.includes('watch live') ||
                (text.includes('read epi') && !text.includes('read the blog'))) {
                var parent = textNode.parentNode;
                // Remove ANY parent that contains this text, unless it's a valid menu item
                if (parent && parent.tagName !== 'LI' && 
                    !parent.classList.contains('menu-item') && 
                    !parent.classList.contains('menu') &&
                    parent.id !== 'primary-menu') {
                    nodesToRemove.push(parent);
                }
            }
        }

        nodesToRemove.forEach(function (node) {
            // Immediately hide with important flags
            node.style.setProperty('display', 'none', 'important');
            node.style.setProperty('visibility', 'hidden', 'important');
            node.style.setProperty('height', '0', 'important');
            node.style.setProperty('width', '0', 'important');
            node.style.setProperty('opacity', '0', 'important');
            node.style.setProperty('font-size', '0', 'important');
            node.style.setProperty('line-height', '0', 'important');
            node.style.setProperty('padding', '0', 'important');
            node.style.setProperty('margin', '0', 'important');
            
            // Force remove immediately
            setTimeout(function () {
                try {
                    if (node.parentNode) {
                        node.parentNode.removeChild(node);
                    } else {
                        node.remove();
                    }
                } catch (e) {
                    // If removal fails, empty the content
                    try {
                        node.innerHTML = '';
                        node.textContent = '';
                    } catch (e2) {
                        // Ignore if already removed
                    }
                }
            }, 10);
        });
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

        // Run cleanup multiple times to catch all variations - MORE FREQUENT
        setTimeout(cleanupMenuItems, 50);
        setTimeout(cleanupMenuItems, 100);
        setTimeout(cleanupMenuItems, 200);
        setTimeout(cleanupMenuItems, 300);
        setTimeout(cleanupMenuItems, 500);
        setTimeout(cleanupMenuItems, 750);
        setTimeout(cleanupMenuItems, 1000);
        setTimeout(cleanupMenuItems, 1500);
        setTimeout(cleanupMenuItems, 2000);
        setTimeout(cleanupMenuItems, 3000);

        // Also run on window load
        window.addEventListener('load', function () {
            cleanupMenuItems();
            setTimeout(cleanupMenuItems, 500);
            setTimeout(cleanupMenuItems, 1000);
        });
    }

    // Run cleanup immediately if DOM is already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMenuCleanup);
    } else {
        initMenuCleanup();
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

