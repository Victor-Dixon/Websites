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
                            <?php echo esc_html__('About AriaJet.site', 'ariajet-studio'); ?>
                        </h1>
                    </header>

                    <div class="entry-content reveal">
                        <p class="about-intro">
                            <?php echo esc_html__('AriaJet.site is the online home of Aria Jet—built to make private aviation feel simple, clear, and well-supported.', 'ariajet-studio'); ?>
                        </p>

                        <hr class="about-divider" />

                        <div class="about-sections">
                            <section class="about-section">
                                <h2 class="about-heading"><?php echo esc_html__('What we do', 'ariajet-studio'); ?></h2>
                                <ul class="about-list">
                                    <li><?php echo esc_html__('Share practical guidance on private flights, aircraft, and routing.', 'ariajet-studio'); ?></li>
                                    <li><?php echo esc_html__('Provide a straightforward way to request flight support and concierge help.', 'ariajet-studio'); ?></li>
                                    <li><?php echo esc_html__('Focus on clear communication, realistic options, and fast follow-up.', 'ariajet-studio'); ?></li>
                                </ul>
                            </section>

                            <section class="about-section">
                                <h2 class="about-heading"><?php echo esc_html__('How it works', 'ariajet-studio'); ?></h2>
                                <ol class="about-steps">
                                    <li><?php echo esc_html__('Tell us where you’re going and your preferred schedule.', 'ariajet-studio'); ?></li>
                                    <li><?php echo esc_html__('We help you compare options and confirm what fits best.', 'ariajet-studio'); ?></li>
                                    <li><?php echo esc_html__('You fly—supported end-to-end by a concierge-first process.', 'ariajet-studio'); ?></li>
                                </ol>
                            </section>

                            <section class="about-section about-cta">
                                <h2 class="about-heading"><?php echo esc_html__('Let’s plan your next flight', 'ariajet-studio'); ?></h2>
                                <p class="about-cta-text">
                                    <?php echo esc_html__('Have a question or want to request options? Reach out and we’ll respond quickly.', 'ariajet-studio'); ?>
                                </p>
                                <p class="about-cta-actions">
                                    <a class="about-button" href="<?php echo esc_url(home_url('/contact/')); ?>">
                                        <?php echo esc_html__('Contact', 'ariajet-studio'); ?>
                                    </a>
                                    <a class="about-link" href="mailto:<?php echo esc_attr(get_option('admin_email')); ?>">
                                        <?php echo esc_html__('Email', 'ariajet-studio'); ?>
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
    </section>
</main>

<style>
.page-about-template .about-intro {
    font-size: 1.05rem;
    line-height: 1.7;
    opacity: 0.92;
    max-width: 68ch;
}
.page-about-template .about-divider {
    border: 0;
    height: 1px;
    background: var(--border);
    margin: var(--space-8) 0 var(--space-10);
}
.page-about-template .about-sections {
    display: grid;
    gap: var(--space-12);
}
.page-about-template .about-heading {
    margin: 0 0 var(--space-4);
}
.page-about-template .about-list {
    margin: var(--space-4) 0 0;
    padding-left: var(--space-8);
}
.page-about-template .about-steps {
    margin: var(--space-4) 0 0;
    padding-left: var(--space-8);
}
.page-about-template .about-cta-actions {
    margin-top: var(--space-6);
    display: flex;
    gap: var(--space-4);
    flex-wrap: wrap;
    align-items: center;
}
.page-about-template .about-button {
    display: inline-block;
    padding: 0.65rem 1rem;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.10);
    color: inherit;
    text-decoration: none;
}
.page-about-template .about-button:hover,
.page-about-template .about-button:focus {
    background: rgba(255, 255, 255, 0.10);
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

