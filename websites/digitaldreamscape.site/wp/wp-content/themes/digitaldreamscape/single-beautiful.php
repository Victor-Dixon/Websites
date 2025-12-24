<?php
/**
 * Beautiful Single Post Template
 * 
 * Modern, elegant single post design matching blog template
 * 
 * @package DigitalDreamscape
 * @since 2.0.0
 */

get_header(); ?>

<main class="site-main beautiful-single-main">
    <div class="beautiful-single-container">
        <?php
        while (have_posts()) : the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('beautiful-single-article'); ?>>
                <!-- Episode Header -->
                <header class="beautiful-single-header">
                    <div class="beautiful-single-badges">
                        <span class="beautiful-single-badge episode">[EPISODE]</span>
                        <?php
                        $categories = get_the_category();
                        if (!empty($categories)) {
                            $category = $categories[0];
                            echo '<span class="beautiful-single-badge questline">[QUESTLINE] ' . esc_html($category->name) . '</span>';
                        }
                        ?>
                    </div>
                    
                    <h1 class="beautiful-single-title"><?php the_title(); ?></h1>
                    
                    <div class="beautiful-single-meta">
                        <div class="beautiful-single-author">
                            <?php echo get_avatar(get_the_author_meta('ID'), 48); ?>
                            <div class="beautiful-single-author-info">
                                <span class="beautiful-single-author-name"><?php the_author(); ?></span>
                                <span class="beautiful-single-author-role">[Shadow Sovereign]</span>
                            </div>
                        </div>
                        
                        <div class="beautiful-single-date">
                            <span class="beautiful-single-badge timeline">[TIMELINE]</span>
                            <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('F j, Y'); ?></time>
                        </div>
                    </div>
                    
                    <div class="beautiful-single-stats">
                        <span class="beautiful-single-stat">
                            <span class="beautiful-single-stat-icon">⏱</span>
                            <?php
                            $content = get_the_content();
                            $word_count = str_word_count(strip_tags($content));
                            $reading_time = ceil($word_count / 200);
                            echo $reading_time . ' min';
                            ?>
                        </span>
                        <span class="beautiful-single-stat">
                            <span class="beautiful-single-stat-icon">📖</span>
                            <?php echo $word_count; ?> words
                        </span>
                        <span class="beautiful-single-stat">
                            <span class="beautiful-single-stat-icon">🎯</span>
                            <span class="beautiful-single-badge canon">[CANON]</span>
                        </span>
                    </div>
                </header>
                
                <!-- Narrative Context -->
                <div class="beautiful-single-context">
                    <div class="beautiful-single-context-card">
                        <div class="beautiful-single-context-badge">[WORLD-STATE]</div>
                        <p class="beautiful-single-context-text">This episode becomes part of the persistent narrative</p>
                    </div>
                    <div class="beautiful-single-context-card">
                        <div class="beautiful-single-context-badge">[NARRATIVE MODE: ACTIVE]</div>
                        <p class="beautiful-single-context-text"><strong>Digital Dreamscape</strong> is a living, narrative-driven AI world where real actions become story, and story feeds back into execution. This post is part of the persistent simulation of self + system.</p>
                    </div>
                </div>
                
                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="beautiful-single-featured-image">
                        <?php the_post_thumbnail('large', array('class' => 'beautiful-single-image')); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Post Content -->
                <div class="beautiful-single-content">
                    <?php the_content(); ?>
                </div>
                
                <!-- Episode Footer -->
                <footer class="beautiful-single-footer">
                    <div class="beautiful-single-episode-complete">
                        <div class="beautiful-single-episode-badge">[EPISODE COMPLETE]</div>
                        <p class="beautiful-single-episode-text">This episode has been logged to memory. Identity state updated. Questline progression recorded.</p>
                    </div>
                    
                    <!-- Share Buttons -->
                    <div class="beautiful-single-share">
                        <div class="beautiful-single-share-label">[SHARE EPISODE]</div>
                        <div class="beautiful-single-share-buttons">
                            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>" 
                               target="_blank" 
                               rel="noopener" 
                               class="beautiful-single-share-button twitter">
                                <span>𝕏</span> Twitter
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                               target="_blank" 
                               rel="noopener" 
                               class="beautiful-single-share-button facebook">
                                <span>📘</span> Facebook
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" 
                               target="_blank" 
                               rel="noopener" 
                               class="beautiful-single-share-button linkedin">
                                <span>💼</span> LinkedIn
                            </a>
                        </div>
                    </div>
                    
                    <!-- Author Bio -->
                    <div class="beautiful-single-author-bio">
                        <div class="beautiful-single-author-bio-label">[AUTHOR]</div>
                        <div class="beautiful-single-author-bio-content">
                            <?php echo get_avatar(get_the_author_meta('ID'), 64); ?>
                            <div class="beautiful-single-author-bio-text">
                                <h3 class="beautiful-single-author-bio-name"><?php the_author(); ?></h3>
                                <p class="beautiful-single-author-bio-description">Building Digital Dreamscape in public. One episode at a time.</p>
                                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="beautiful-single-author-bio-link">
                                    View all posts by <?php the_author(); ?> →
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="beautiful-single-navigation">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        ?>
                        <?php if ($prev_post) : ?>
                            <div class="beautiful-single-nav-prev">
                                <span class="beautiful-single-nav-label">← Previous Episode</span>
                                <a href="<?php echo get_permalink($prev_post); ?>" class="beautiful-single-nav-link">
                                    <?php echo get_the_title($prev_post); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($next_post) : ?>
                            <div class="beautiful-single-nav-next">
                                <span class="beautiful-single-nav-label">Next Episode →</span>
                                <a href="<?php echo get_permalink($next_post); ?>" class="beautiful-single-nav-link">
                                    <?php echo get_the_title($next_post); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </nav>
                </footer>
            </article>
            <?php
        endwhile;
        ?>
    </div>
</main>

<?php get_footer(); ?>

