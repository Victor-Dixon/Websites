<?php
/**
 * About Page Template (slug: about)
 *
 * This template intentionally ignores the page editor content and renders
 * a simple "Space" layout with a working WordPress comments form.
 *
 * @package AriaJet_Studio
 */

get_header();
?>

<main id="main" class="site-main page-about-template">
    <section class="section">
        <div class="container container--narrow">
            <?php
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
                    <header class="page-header reveal">
                        <h1 class="page-title">
                            <?php echo esc_html(get_bloginfo('name')) . '&#039;s Space'; ?>
                        </h1>
                    </header>

                    <div class="entry-content reveal">
                        <?php the_content(); ?>
                    </div>

                </article>
                <?php
            endwhile;
            ?>
        </div>
    </section>
</main>

<style>
.page-about-template .entry-content {
    max-width: 900px;
    margin: 0 auto;
}
</style>

<?php
get_footer();

