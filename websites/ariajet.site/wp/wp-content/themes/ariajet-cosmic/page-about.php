<?php
/**
 * About Page Template (slug: about)
 *
 * This template intentionally ignores the page editor content and renders
 * a simple "Space" layout with a working WordPress comments form.
 *
 * @package AriaJet_Cosmic
 */

get_header();
?>

<main id="main" class="site-main page-template page-about-template">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('page-content cosmic-card'); ?>>
                <header class="entry-header">
                    <h1 class="entry-title page-title">
                        <?php echo esc_html(get_bloginfo('name')) . '&#039;s Space'; ?>
                    </h1>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

            </article>
            <?php
        endwhile;
        ?>
    </div>
</main>

<style>
.page-about-template .page-content {
    padding: var(--space-12);
}
.page-about-template .entry-content {
    max-width: 900px;
    margin: 0 auto;
}
</style>

<?php
get_footer();

