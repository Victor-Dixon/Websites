<?php
/**
 * Streaming Page Template
 * 
 * Displays streaming content in Digital Dreamscape style
 * 
 * @package DigitalDreamscape
 * @since 2.0.0
 */

get_header(); ?>

<main class="site-main">
    <div class="container">
        <!-- Streaming Header -->
        <header class="page-header dreamscape-page-header">
            <div class="page-badge">[LIVE BROADCAST]</div>
            <h1 class="page-title dreamscape-page-title">
                Streaming Hub
            </h1>
            <div class="page-description dreamscape-page-desc">
                <p>Watch live streams, catch up on past broadcasts, and join the <strong>Digital Dreamscape</strong> community in real-time.</p>
            </div>
        </header>

        <!-- Streaming Content -->
        <section class="streaming-section">
            <div class="streaming-grid">
                <!-- Live Status Card -->
                <div class="streaming-card live-status-card">
                    <div class="card-header">
                        <span class="card-badge">[STATUS]</span>
                        <span class="live-indicator offline">OFFLINE</span>
                    </div>
                    <div class="card-content">
                        <h3>Current Status</h3>
                        <p>No live stream at the moment. Check back soon or follow for notifications when we go live.</p>
                    </div>
                </div>

                <!-- Twitch Embed Card -->
                <div class="streaming-card twitch-card">
                    <div class="card-header">
                        <span class="card-badge">[TWITCH]</span>
                    </div>
                    <div class="card-content">
                        <h3>Watch on Twitch</h3>
                        <p>Join us on Twitch for live development streams, Q&A sessions, and community events.</p>
                        <a href="https://twitch.tv/digitaldreamscape" class="streaming-cta" target="_blank" rel="noopener">
                            <span>Visit Twitch Channel</span>
                            <span class="cta-arrow">→</span>
                        </a>
                    </div>
                </div>

                <!-- YouTube Card -->
                <div class="streaming-card youtube-card">
                    <div class="card-header">
                        <span class="card-badge">[YOUTUBE]</span>
                    </div>
                    <div class="card-content">
                        <h3>Watch on YouTube</h3>
                        <p>Catch up on past streams, tutorials, and exclusive content on our YouTube channel.</p>
                        <a href="https://youtube.com/@digitaldreamscape" class="streaming-cta" target="_blank" rel="noopener">
                            <span>Visit YouTube Channel</span>
                            <span class="cta-arrow">→</span>
                        </a>
                    </div>
                </div>

                <!-- Schedule Card -->
                <div class="streaming-card schedule-card">
                    <div class="card-header">
                        <span class="card-badge">[SCHEDULE]</span>
                    </div>
                    <div class="card-content">
                        <h3>Stream Schedule</h3>
                        <p>We stream regularly throughout the week. Follow our socials to get notified when we go live.</p>
                        <div class="schedule-note">
                            <em>Schedule coming soon</em>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Page Content (if any from WordPress editor) -->
        <?php while (have_posts()) : the_post(); ?>
            <?php if (get_the_content()) : ?>
                <section class="page-content">
                    <?php the_content(); ?>
                </section>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>

