<?php
/**
 * Archive Template (Blog Listing)
 * 
 * @package DigitalDreamscape
 * @since 2.0.0
 */

get_header(); ?>

<main class="site-main">
    <div class="container">
        <!-- Archive Header - Digital Dreamscape Narrative -->
        <header class="archive-header dreamscape-archive-header">
            <div class="archive-badge">[EPISODE ARCHIVE]</div>
            <h1 class="archive-title dreamscape-archive-title">
                <?php
                if (is_category()) {
                    echo '[QUESTLINE] ' . single_cat_title('', false);
                } elseif (is_tag()) {
                    echo '[TAG] ' . single_tag_title('', false);
                } elseif (is_author()) {
                    echo '[IDENTITY] ' . get_the_author();
                } elseif (is_date()) {
                    if (is_year()) {
                        echo '[TIMELINE] ' . get_the_date('Y');
                    } elseif (is_month()) {
                        echo '[TIMELINE] ' . get_the_date('F Y');
                    } elseif (is_day()) {
                        echo '[TIMELINE] ' . get_the_date('F j, Y');
                    }
                } else {
                    echo 'Narrative Episodes';
                }
                ?>
            </h1>
            <?php
            $description = get_the_archive_description();
            if ($description) :
                ?>
                <div class="archive-description dreamscape-archive-desc">
                    <?php echo wp_kses_post($description); ?>
                </div>
            <?php else : ?>
                <div class="archive-description dreamscape-archive-desc">
                    <p><strong>Digital Dreamscape</strong> is a living, narrative-driven AI world where real actions become story, and story feeds back into execution. Each episode below is part of the persistent simulation of self + system.</p>
                    <p>Every conversation, decision, build, failure, and breakthrough is treated as <strong>canon</strong> and transformed into structured state.</p>
                </div>
            <?php endif; ?>
        </header>

        <!-- Blog Posts Grid -->
        <div class="blog-posts-grid">
            <?php
            // Ensure we have posts to display
            if (!have_posts()) {
                // Try to query posts directly if main query is empty
                query_posts(array('post_type' => 'post', 'posts_per_page' => 10, 'post_status' => 'publish'));
            }
            
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('blog-post-card dreamscape-episode-card'); ?>>
                        <div class="episode-marker">[EPISODE]</div>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-card-image dreamscape-episode-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium_large', array('class' => 'card-img')); ?>
                                    <div class="episode-overlay">
                                        <span class="episode-badge">[CANON]</span>
                                    </div>
                                </a>
                            </div>
                        <?php else : ?>
                            <div class="post-card-image dreamscape-episode-image dreamscape-default-episode">
                                <a href="<?php the_permalink(); ?>">
                                    <div class="episode-pattern"></div>
                                    <div class="episode-overlay">
                                        <span class="episode-badge">[CANON]</span>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-card-content dreamscape-episode-content">
                            <div class="post-card-meta dreamscape-episode-meta">
                                <time datetime="<?php echo get_the_date('c'); ?>" class="post-card-date episode-timestamp">
                                    [TIMELINE] <?php echo get_the_date('M j, Y'); ?>
                                </time>
                                <?php
                                $categories = get_the_category();
                                if (!empty($categories)) {
                                    $category = $categories[0];
                                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="post-card-category episode-questline">[QUESTLINE] ' . esc_html($category->name) . '</a>';
                                } else {
                                    echo '<span class="post-card-category episode-questline">[QUESTLINE] Uncategorized</span>';
                                }
                                ?>
                            </div>
                            
                            <h2 class="post-card-title dreamscape-episode-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <div class="post-card-excerpt dreamscape-episode-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <div class="post-card-footer dreamscape-episode-footer">
                                <div class="post-card-author episode-identity">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 40); ?>
                                    <div class="episode-identity-info">
                                        <span class="author-name"><?php the_author(); ?></span>
                                        <span class="episode-identity-title">[Shadow Sovereign]</span>
                                    </div>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="post-card-link episode-link">
                                    [ENTER EPISODE] →
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php
                endwhile;
            else :
                ?>
                <div class="no-posts">
                    <p>No posts found. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php
        if (have_posts()) :
            ?>
            <nav class="pagination">
                <?php
                echo paginate_links(array(
                    'prev_text' => '← Previous',
                    'next_text' => 'Next →',
                    'type' => 'list',
                ));
                ?>
            </nav>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>


