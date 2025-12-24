<?php
/**
 * About Page Template (slug: about)
 *
 * This template intentionally ignores the page editor content and renders
 * a simple "Space" layout with a working WordPress comments form.
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
            <article id="post-<?php the_ID(); ?>" <?php post_class('about-card'); ?>>
                <header class="about-hero">
                    <div class="about-hero__inner">
                        <p class="about-eyebrow"><?php echo esc_html(get_bloginfo('name')); ?></p>
                        <h1 class="about-title"><?php the_title(); ?></h1>
                        <?php $tagline = get_bloginfo('description'); ?>
                        <?php if (!empty($tagline)) : ?>
                            <p class="about-tagline"><?php echo esc_html($tagline); ?></p>
                        <?php endif; ?>
                    </div>
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
                                <?php echo esc_html__('Contact', 'ariajet'); ?>
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
.page-about-template {
    padding: 110px 1.25rem 60px;
    min-height: 80vh;
}
.page-about-template .container {
    max-width: 1100px;
}
.page-about-template .about-card {
    border-radius: 24px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.12);
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.12) 0%, rgba(118, 75, 162, 0.10) 100%);
    backdrop-filter: blur(10px);
}
.page-about-template .about-hero {
    padding: 56px 32px 40px;
    text-align: center;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.55) 0%, rgba(118, 75, 162, 0.55) 100%);
    color: #fff;
}
.page-about-template .about-eyebrow {
    margin: 0 0 10px;
    opacity: 0.9;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    font-weight: 600;
    font-size: 0.85rem;
}
.page-about-template .about-title {
    margin: 0;
    font-size: 2.6rem;
    line-height: 1.12;
    color: #fff;
}
.page-about-template .about-tagline {
    margin: 14px auto 0;
    max-width: 60ch;
    font-size: 1.1rem;
    opacity: 0.92;
}
.page-about-template .about-body {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 28px;
    padding: 34px 32px 38px;
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
    border: 1px solid rgba(255, 255, 255, 0.18);
    box-shadow: 0 20px 45px rgba(0,0,0,0.22);
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
    justify-content: flex-start;
    gap: 12px;
    padding-top: 8px;
}
.page-about-template .about-button {
    display: inline-block;
    padding: 0.9rem 1.25rem;
    border-radius: 999px;
    text-decoration: none;
    font-weight: 600;
    background: #ffffff;
    color: #3b4cca;
    border: 1px solid rgba(0,0,0,0.06);
}
.page-about-template .about-button:hover {
    transform: translateY(-1px);
}

@media (max-width: 900px) {
    .page-about-template .about-body {
        grid-template-columns: 1fr;
        padding: 26px 18px 30px;
    }
    .page-about-template .about-hero {
        padding: 44px 18px 32px;
    }
    .page-about-template .about-title {
        font-size: 2.1rem;
    }
    .page-about-template .about-cta {
        justify-content: center;
    }
}
</style>

<?php
get_footer();

