<?php
/**
 * Template Name: Contact
 * @package Freerideinvestor-Modern
 * 
 * This template loads the proper contact page template
 */
get_header();

// Load the proper contact page template
$template_path = get_template_directory() . '/page-templates/page-contact.php';
if (file_exists($template_path)) {
    include($template_path);
} else {
    // Fallback: Display basic contact info
    ?>
    <div class="container">
        <div class="content-area">
            <div class="main-content">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                        </header>
                        <div class="entry-content">
                            <?php the_content(); ?>
                            <p>Email us at <a href="mailto:support@freerideinvestor.com">support@freerideinvestor.com</a> or join our Discord community.</p>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php
}

get_footer();
?>