<?php
/**
 * About Page Template (slug: about)
 *
 * Renders a real About page layout. If the page has editor content, we show it;
 * otherwise we fall back to a sensible default.
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
                    <h1 class="entry-title page-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content">
                    <hr class="about-divider" />

                    <?php
                    $raw_content = trim((string) get_the_content());
                    if ($raw_content !== '') :
                        the_content();
                    else :
                        $playlists_page = get_page_by_path('playlists');
                        $playlists_url = $playlists_page ? get_permalink($playlists_page) : home_url('/playlists/');
                        ?>
                        <p class="about-lede">
                            <?php echo esc_html(get_bloginfo('name')); ?> is a little launchpad for creative work—games, prototypes, and the music that powers it.
                        </p>

                        <div class="about-grid">
                            <section class="about-card">
                                <h2 class="about-heading"><?php _e('Orbit map', 'ariajet-cosmic'); ?></h2>
                                <ul class="about-list">
                                    <li><?php _e('Play the latest 2D games and prototypes', 'ariajet-cosmic'); ?></li>
                                    <li><?php _e('Browse projects and experiments', 'ariajet-cosmic'); ?></li>
                                    <li><?php _e('Listen to playlists and music', 'ariajet-cosmic'); ?></li>
                                </ul>
                            </section>

                            <section class="about-card">
                                <h2 class="about-heading"><?php _e('Explore', 'ariajet-cosmic'); ?></h2>
                                <p><?php _e('Start with the games, then wander through projects and playlists. New things land here over time.', 'ariajet-cosmic'); ?></p>
                                <p class="about-links">
                                    <a class="about-link" href="<?php echo esc_url(get_post_type_archive_link('game')); ?>"><?php _e('Games', 'ariajet-cosmic'); ?></a>
                                    <span class="about-link-sep" aria-hidden="true">•</span>
                                    <a class="about-link" href="<?php echo esc_url($playlists_url); ?>"><?php _e('Music', 'ariajet-cosmic'); ?></a>
                                </p>
                            </section>
                        </div>
                        <?php
                    endif;
                    ?>

                    <p class="about-comments-intro">
                        <strong><?php _e('Leave a comment below!', 'ariajet-cosmic'); ?></strong>
                    </p>
                </div>

                <div class="about-comments">
                    <?php
                    // We show comments + form (the theme also forces comments open for slug "about").
                    comments_template();
                    ?>
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
    max-width: 900px;
    margin: 0 auto;
}
.page-about-template .entry-content p {
    max-width: 70ch;
}
.page-about-template .about-lede {
    font-size: 1.15rem;
    margin: 0 0 var(--space-8);
    opacity: 0.95;
}
.page-about-template .about-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: var(--space-8);
    margin: var(--space-8) 0 var(--space-10);
}
.page-about-template .about-card {
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 18px;
    padding: var(--space-8);
    background: rgba(255, 255, 255, 0.04);
}
.page-about-template .about-links {
    margin-top: var(--space-6);
}
.page-about-template .about-link {
    text-decoration: none;
    font-weight: 650;
}
.page-about-template .about-link-sep {
    margin: 0 var(--space-3);
    opacity: 0.7;
}
.page-about-template .about-divider {
    border: 0;
    height: 1px;
    background: rgba(255, 255, 255, 0.12);
    margin: var(--space-6) 0 var(--space-8);
}
.page-about-template .about-list {
    margin: var(--space-4) 0 var(--space-8);
    padding-left: var(--space-8);
}
.page-about-template .about-comments {
    margin-top: var(--space-10);
}
</style>

<?php
get_footer();

