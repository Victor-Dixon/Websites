<?php
/**
 * Add custom rewrite rules for blog pagination
 * 
 * This ensures /blog/page/2/ works correctly with page templates
 */
function freerideinvestor_add_blog_rewrite_rules() {
    // Add rewrite rule for blog pagination
    add_rewrite_rule(
        '^blog/page/([0-9]+)/?$',
        'index.php?pagename=blog&paged=$matches[1]',
        'top'
    );
}
add_action('init', 'freerideinvestor_add_blog_rewrite_rules');

/**
 * Flush rewrite rules on theme activation
 */
function freerideinvestor_flush_rewrite_rules() {
    freerideinvestor_add_blog_rewrite_rules();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'freerideinvestor_flush_rewrite_rules');
