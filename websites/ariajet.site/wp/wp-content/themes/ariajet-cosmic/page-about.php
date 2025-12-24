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
            <article id="post-<?php the_ID(); ?>" <?php post_class('page-content cosmic-card about-card'); ?>>
                <header class="about-hero">
                    <p class="about-eyebrow"><?php echo esc_html(get_bloginfo('name')); ?></p>
                    <h1 class="about-title"><?php the_title(); ?></h1>
                    <?php $tagline = get_bloginfo('description'); ?>
                    <?php if (!empty($tagline)) : ?>
                        <p class="about-tagline"><?php echo esc_html($tagline); ?></p>
                    <?php endif; ?>
                </header>

                <div class="about-body">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="about-media">
                            <?php the_post_thumbnail('large', array('class' => 'about-avatar', 'loading' => 'lazy')); ?>
                        </div>
                    <?php endif; ?>

                    <div class="about-content entry-content">
                        <?php the_content(); ?>
                    </div>

                    <div class="about-cta">
                        <?php
                        $contact = get_page_by_path('contact');
                        if ($contact) :
                            ?>
                            <a class="about-button" href="<?php echo esc_url(get_permalink($contact)); ?>">
                                <?php echo esc_html__('Contact', 'ariajet-cosmic'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php
        endwhile;
        ?>
    </div>
</main>

<style>
.page-about-template .page-content {
    padding: 0;
}
.page-about-template .about-hero {
    padding: var(--space-12) var(--space-10) var(--space-10);
    text-align: center;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.55) 0%, rgba(118, 75, 162, 0.55) 100%);
}
.page-about-template .about-eyebrow {
    margin: 0 0 var(--space-3);
    opacity: 0.92;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    font-weight: 600;
    font-size: 0.85rem;
}
.page-about-template .about-title {
    margin: 0;
    font-size: 2.4rem;
    line-height: 1.12;
}
.page-about-template .about-tagline {
    margin: var(--space-4) auto 0;
    max-width: 60ch;
    font-size: 1.05rem;
    opacity: 0.92;
}
.page-about-template .about-body {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: var(--space-8);
    padding: var(--space-10);
}
.page-about-template .about-media {
    display: flex;
    justify-content: center;
}
.page-about-template .about-avatar {
    width: 260px;
    height: 260px;
    object-fit: cover;
    border-radius: 22px;
    border: 1px solid rgba(255, 255, 255, 0.14);
    box-shadow: 0 20px 45px rgba(0,0,0,0.25);
}
.page-about-template .about-content {
    max-width: 75ch;
}
.page-about-template .about-content > *:first-child {
    margin-top: 0;
}
.page-about-template .about-cta {
    grid-column: 1 / -1;
    display: flex;
    gap: var(--space-3);
    padding-top: var(--space-2);
}
.page-about-template .about-button {
    display: inline-block;
    padding: 0.9rem 1.25rem;
    border-radius: 999px;
    text-decoration: none;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.92);
    color: rgba(55, 61, 180, 1);
    border: 1px solid rgba(0,0,0,0.06);
}
.page-about-template .about-button:hover {
    transform: translateY(-1px);
}

@media (max-width: 900px) {
    .page-about-template .about-body {
        grid-template-columns: 1fr;
        padding: var(--space-8) var(--space-6);
    }
    .page-about-template .about-hero {
        padding: var(--space-10) var(--space-6) var(--space-8);
    }
    .page-about-template .about-title {
        font-size: 2.0rem;
    }
    .page-about-template .about-cta {
        justify-content: center;
    }
}
</style>

<?php
get_footer();

