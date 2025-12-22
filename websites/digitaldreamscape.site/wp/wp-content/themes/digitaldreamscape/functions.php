<?php
/**
 * Digital Dreamscape Theme Functions
 * 
 * @package DigitalDreamscape
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

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
    wp_enqueue_style('digitaldreamscape-style', get_stylesheet_uri(), array(), '1.0.0');

    // Enqueue theme JavaScript
    wp_enqueue_script('digitaldreamscape-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0.0', true);
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

