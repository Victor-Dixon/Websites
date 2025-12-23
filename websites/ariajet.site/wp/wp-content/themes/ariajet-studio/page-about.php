<?php
/**
 * About Page Template (slug: about)
 *
 * Renders a real About page layout. If the page has editor content, we show it;
 * otherwise we fall back to a sensible default.
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
                        <h1 class="page-title"><?php the_title(); ?></h1>
                    </header>

                    <div class="entry-content reveal">
                        <hr class="about-divider" />

                        <?php
                        $raw_content = trim((string) get_the_content());
                        if ($raw_content !== '') :
                            the_content();
                        else :
                            ?>
                            <p class="about-lede">
                                <?php echo esc_html(get_bloginfo('name')); ?> is a small corner of the internet for creative work—games, prototypes, and ideas in progress.
                            </p>

                            <div class="about-grid">
                                <section class="about-card">
                                    <h2><?php _e('What I’m making', 'ariajet-studio'); ?></h2>
                                    <ul class="about-list">
                                        <li><?php _e('2D games and playful interactive pieces', 'ariajet-studio'); ?></li>
                                        <li><?php _e('Short updates, experiments, and behind-the-scenes notes', 'ariajet-studio'); ?></li>
                                        <li><?php _e('Music and playlists that set the vibe', 'ariajet-studio'); ?></li>
                                    </ul>
                                </section>

                                <section class="about-card">
                                    <h2><?php _e('Start here', 'ariajet-studio'); ?></h2>
                                    <p><?php _e('If you’re new, the best way in is to explore the latest games and projects, then come back for updates.', 'ariajet-studio'); ?></p>
                                    <p class="about-links">
                                        <a class="about-link" href="<?php echo esc_url(home_url('/')); ?>">
                                            <span class="nav-icon">🏠</span> <?php _e('Home', 'ariajet-studio'); ?>
                                        </a>
                                        <span class="about-link-sep" aria-hidden="true">•</span>
                                        <a class="about-link" href="<?php echo esc_url(get_post_type_archive_link('game')); ?>">
                                            <span class="nav-icon">🎮</span> <?php _e('Games', 'ariajet-studio'); ?>
                                        </a>
                                    </p>
                                </section>
                            </div>
                            <?php
                        endif;
                        ?>

                        <p class="about-comments-intro">
                            <strong><?php _e('Leave a comment below!', 'ariajet-studio'); ?></strong>
                        </p>
                    </div>

                    <div class="about-comments reveal">
                        <?php comments_template(); ?>
                    </div>
                </article>
                <?php
            endwhile;
            ?>
        </div>
    </section>
</main>

<style>
.page-about-template .entry-content p {
    max-width: 70ch;
}
.page-about-template .about-lede {
    font-size: 1.15rem;
    margin: 0 0 var(--space-8);
}
.page-about-template .about-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: var(--space-8);
    margin: var(--space-8) 0 var(--space-10);
}
.page-about-template .about-card {
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: var(--space-8);
    background: rgba(255, 255, 255, 0.02);
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
    background: var(--border);
    margin: var(--space-8) 0 var(--space-10);
}
.page-about-template .about-list {
    margin: var(--space-6) 0 var(--space-10);
    padding-left: var(--space-8);
}
.page-about-template .about-comments {
    margin-top: var(--space-12);
}
</style>

<?php
get_footer();

