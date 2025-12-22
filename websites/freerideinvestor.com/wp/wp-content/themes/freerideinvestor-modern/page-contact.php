<?php
/**
 * Template Name: Contact
 * @package Freerideinvestor-Modern
 * 
 * This template loads the proper contact page template
 */
get_header();
// Load the proper contact page template
include(get_template_directory() . '/page-templates/page-contact.php');
get_footer();
?>