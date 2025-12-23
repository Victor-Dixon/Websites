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
                        <?php echo esc_html__('About AriaJet.site', 'ariajet-cosmic'); ?>
                    </h1>
                </header>

                <div class="entry-content">
                    <p class="about-intro">
                        <?php echo esc_html__('AriaJet.site is the online home of Aria Jet—built to make private aviation feel simple, clear, and well-supported.', 'ariajet-cosmic'); ?>
                    </p>

                    <hr class="about-divider" />

                    <div class="about-sections">
                        <section class="about-section">
                            <h2 class="about-heading"><?php echo esc_html__('What we do', 'ariajet-cosmic'); ?></h2>
                            <ul class="about-list">
                                <li><?php echo esc_html__('Share practical guidance on private flights, aircraft, and routing.', 'ariajet-cosmic'); ?></li>
                                <li><?php echo esc_html__('Provide a straightforward way to request flight support and concierge help.', 'ariajet-cosmic'); ?></li>
                                <li><?php echo esc_html__('Focus on clear communication, realistic options, and fast follow-up.', 'ariajet-cosmic'); ?></li>
                            </ul>
                        </section>

                        <section class="about-section">
                            <h2 class="about-heading"><?php echo esc_html__('How it works', 'ariajet-cosmic'); ?></h2>
                            <ol class="about-steps">
                                <li><?php echo esc_html__('Tell us where you’re going and your preferred schedule.', 'ariajet-cosmic'); ?></li>
                                <li><?php echo esc_html__('We help you compare options and confirm what fits best.', 'ariajet-cosmic'); ?></li>
                                <li><?php echo esc_html__('You fly—supported end-to-end by a concierge-first process.', 'ariajet-cosmic'); ?></li>
                            </ol>
                        </section>

                        <section class="about-section about-cta">
                            <h2 class="about-heading"><?php echo esc_html__('Let’s plan your next flight', 'ariajet-cosmic'); ?></h2>
                            <p class="about-cta-text">
                                <?php echo esc_html__('Have a question or want to request options? Reach out and we’ll respond quickly.', 'ariajet-cosmic'); ?>
                            </p>
                            <p class="about-cta-actions">
                                <a class="about-button" href="<?php echo esc_url(home_url('/contact/')); ?>">
                                    <?php echo esc_html__('Contact', 'ariajet-cosmic'); ?>
                                </a>
                                <a class="about-link" href="mailto:<?php echo esc_attr(get_option('admin_email')); ?>">
                                    <?php echo esc_html__('Email', 'ariajet-cosmic'); ?>
                                </a>
                            </p>
                        </section>
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
    padding: var(--space-12);
    max-width: 900px;
    margin: 0 auto;
}
.page-about-template .about-intro {
    font-size: 1.05rem;
    line-height: 1.7;
    opacity: 0.92;
    max-width: 68ch;
}
.page-about-template .about-divider {
    border: 0;
    height: 1px;
    background: rgba(255, 255, 255, 0.12);
    margin: var(--space-6) 0 var(--space-8);
}
.page-about-template .about-sections {
    display: grid;
    gap: var(--space-10);
}
.page-about-template .about-heading {
    margin: 0 0 var(--space-3);
}
.page-about-template .about-list {
    margin: var(--space-3) 0 0;
    padding-left: var(--space-8);
}
.page-about-template .about-steps {
    margin: var(--space-3) 0 0;
    padding-left: var(--space-8);
}
.page-about-template .about-cta-actions {
    margin-top: var(--space-4);
    display: flex;
    gap: var(--space-4);
    flex-wrap: wrap;
    align-items: center;
}
.page-about-template .about-button {
    display: inline-block;
    padding: 0.65rem 1rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.10);
    border: 1px solid rgba(255, 255, 255, 0.14);
    color: inherit;
    text-decoration: none;
}
.page-about-template .about-button:hover,
.page-about-template .about-button:focus {
    background: rgba(255, 255, 255, 0.16);
}
.page-about-template .about-link {
    color: inherit;
    opacity: 0.9;
}
.page-about-template .about-link:hover,
.page-about-template .about-link:focus {
    opacity: 1;
}
</style>

<?php
get_footer();

