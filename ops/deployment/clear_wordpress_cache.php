#!/usr/bin/env php
<?php
/**
 * WordPress Cache Clearing Script
 * 
 * Clears various WordPress caches including:
 * - Object cache
 * - Transients
 * - LiteSpeed Cache (if installed)
 * - Rewrite rules
 * 
 * Usage: php clear_wordpress_cache.php
 */

// Load WordPress
require_once(__DIR__ . '/../../websites/freerideinvestor.com/wp/wp-load.php');

echo "🧹 Clearing WordPress Cache...\n\n";

// Clear transients
global $wpdb;
$transients_deleted = $wpdb->query(
    "DELETE FROM {$wpdb->options} 
     WHERE option_name LIKE '_transient_%' 
     OR option_name LIKE '_site_transient_%'"
);
echo "✅ Cleared transients: {$transients_deleted} entries\n";

// Clear object cache
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "✅ Object cache flushed\n";
} else {
    echo "⚠️  Object cache not available\n";
}

// Clear LiteSpeed Cache (if installed)
if (class_exists('LiteSpeed_Cache')) {
    if (method_exists('LiteSpeed_Cache', 'purge_all')) {
        LiteSpeed_Cache::purge_all();
        echo "✅ LiteSpeed Cache purged\n";
    }
} else {
    echo "ℹ️  LiteSpeed Cache not installed\n";
}

// Clear rewrite rules
flush_rewrite_rules();
echo "✅ Rewrite rules flushed\n";

// Clear any other cache plugins
if (function_exists('rocket_clean_domain')) {
    rocket_clean_domain();
    echo "✅ WP Rocket cache cleared\n";
}

if (function_exists('w3tc_flush_all')) {
    w3tc_flush_all();
    echo "✅ W3 Total Cache flushed\n";
}

if (function_exists('wp_cache_clean_cache')) {
    wp_cache_clean_cache();
    echo "✅ WP Super Cache cleared\n";
}

echo "\n✅ Cache clearing complete!\n";

