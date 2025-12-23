<?php
/**
 * About Page Template (slug: about)
 *
 * Renders a real About page layout. If the page has editor content, we show it;
 * otherwise we fall back to a sensible default.
 *
 * @package AriaJet
 */

get_header();
?>

<main id="main" class="site-main page-about-template">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('game-showcase'); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content">
                    <hr class="about-divider" />

                    <?php
                    $raw_content = trim((string) get_the_content());
                    if ($raw_content !== '') :
                        the_content();
                    else :
                        ?>
                        <p class="about-lede">
                            <?php echo esc_html(get_bloginfo('name')); ?> is a personal studio site for creative work—games, prototypes, and experiments.
                        </p>

                        <div class="about-grid">
                            <section class="about-card">
                                <h2 class="about-heading"><?php _e('What you’ll find here', 'ariajet'); ?></h2>
                                <ul class="about-list">
                                    <li><?php _e('2D games and interactive prototypes', 'ariajet'); ?></li>
                                    <li><?php _e('Dev notes, updates, and small experiments', 'ariajet'); ?></li>
                                    <li><?php _e('Music and playlists that inspire the work', 'ariajet'); ?></li>
                                </ul>
                            </section>

                            <section class="about-card">
                                <h2 class="about-heading"><?php _e('A quick hello', 'ariajet'); ?></h2>
                                <p>
                                    <?php _e('I build things that feel playful, polished, and a little surprising. If you’re visiting from a project link, welcome—take a look around and check out the latest work.', 'ariajet'); ?>
                                </p>
                                <p class="about-links">
                                    <a class="about-link" href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Back to Home', 'ariajet'); ?></a>
                                    <span class="about-link-sep" aria-hidden="true">•</span>
                                    <a class="about-link" href="<?php echo esc_url(get_post_type_archive_link('game')); ?>"><?php _e('View Games', 'ariajet'); ?></a>
                                </p>
                            </section>
                        </div>
                        <?php
                    endif;
                    ?>

                    <p class="about-comments-intro">
                        <strong><?php _e('Leave a comment below!', 'ariajet'); ?></strong>
                    </p>
                </div>

                <div class="about-comments">
                    <?php comments_template(); ?>
                </div>
            </article>
            <?php
        endwhile;
        ?>
    </div>
</main>

<style>
.page-about-template .entry-content p {
    max-width: 70ch;
}
.page-about-template .about-lede {
    font-size: 1.15rem;
    margin: 0 0 1.5rem;
    opacity: 0.95;
}
.page-about-template .about-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.25rem;
    margin: 1.25rem 0 2rem;
}
.page-about-template .about-card {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 16px;
    padding: 1.25rem 1.25rem 1.1rem;
}
.page-about-template .about-links {
    margin-top: 1rem;
}
.page-about-template .about-link {
    text-decoration: none;
    font-weight: 600;
}
.page-about-template .about-link-sep {
    margin: 0 0.5rem;
    opacity: 0.7;
}
.page-about-template .about-divider {
    border: 0;
    height: 1px;
    background: rgba(255, 255, 255, 0.2);
    margin: 1.5rem 0 2rem;
}
.page-about-template .about-list {
    margin: 1rem 0 2rem;
    padding-left: 1.5rem;
}
.page-about-template .about-comments {
    margin-top: 2.5rem;
}
</style>

<?php
get_footer();

