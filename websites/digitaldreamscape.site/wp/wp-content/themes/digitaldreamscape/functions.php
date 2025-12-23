<?php
/**
 * Digital Dreamscape Theme Functions
 * 
 * Living, narrative-driven AI world theme
 * 
 * @package DigitalDreamscape
 * @since 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Prefer a human-readable author name (avoid showing emails).
 *
 * WP will display whatever the user's "Display name publicly as" is set to.
 * If that's an email, try to fall back to First+Last or Nickname when available.
 */
function digitaldreamscape_filter_author_display_name($display_name) {
    $author_id = get_the_author_meta('ID');
    if (!$author_id) {
        return $display_name;
    }

    $user = get_user_by('id', $author_id);
    if (!$user) {
        return $display_name;
    }

    $full_name = trim((string) $user->first_name . ' ' . (string) $user->last_name);
    if ($full_name !== '') {
        return $full_name;
    }

    $nickname = trim((string) $user->nickname);
    if ($nickname !== '' && strpos($nickname, '@') === false) {
        return $nickname;
    }

    return $display_name;
}
add_filter('the_author', 'digitaldreamscape_filter_author_display_name');

/**
 * Theme Setup
 */
function digitaldreamscape_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 300,
        'flex-height' => true,
        'flex-width' => true,
    ));
    add_theme_support('automatic-feed-links');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'digitaldreamscape'),
        'footer' => __('Footer Menu', 'digitaldreamscape'),
    ));
}
add_action('after_setup_theme', 'digitaldreamscape_setup');

/**
 * Enqueue Styles and Scripts
 */
function digitaldreamscape_scripts() {
    // Enqueue theme stylesheet
    wp_enqueue_style('digitaldreamscape-style', get_stylesheet_uri(), array(), '2.0.0');

    // Enqueue theme JavaScript
    wp_enqueue_script('digitaldreamscape-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '2.0.0', true);
    
    // Add Digital Dreamscape context to page
    wp_localize_script('digitaldreamscape-script', 'dreamscapeContext', array(
        'isEpisode' => is_single(),
        'isArchive' => is_archive(),
        'narrativeMode' => true,
    ));
}
add_action('wp_enqueue_scripts', 'digitaldreamscape_scripts');

/**
 * Register Widget Areas
 */
function digitaldreamscape_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'digitaldreamscape'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here.', 'digitaldreamscape'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'digitaldreamscape_widgets_init');

/**
 * Add Digital Dreamscape meta description to posts
 */
function digitaldreamscape_post_meta_description() {
    if (is_single()) {
        $description = 'Digital Dreamscape is a living, narrative-driven AI world where real actions become story, and story feeds back into execution. This episode is part of the persistent simulation of self + system.';
        echo '<meta name="description" content="' . esc_attr($description) . '">';
    }
}
add_action('wp_head', 'digitaldreamscape_post_meta_description');
