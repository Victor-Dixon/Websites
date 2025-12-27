<?php
/**
 * Swarm Theme Functions
 *
 * @package Swarm
 * @since 1.0.0
 */

/**
 * Theme Setup
 */
function swarm_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('custom-logo');
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'swarm'),
        'footer' => __('Footer Menu', 'swarm'),
    ));
}
add_action('after_setup_theme', 'swarm_setup');

/**
 * Enqueue Styles and Scripts
 */
function swarm_scripts() {
    wp_enqueue_style('swarm-style', get_stylesheet_uri(), array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'swarm_scripts');


