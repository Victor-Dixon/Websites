<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="The Swarm: A revolutionary multi-agent AI system showcasing web development, autonomous coordination, and real-time intelligence. Currently operating in optimized 4-agent mode.">
    <meta name="keywords" content="multi-agent AI, swarm intelligence, autonomous systems, web development, AI collaboration, agent orchestration, WordPress development">
    <meta property="og:title" content="WE. ARE. SWARM. - Multi-Agent AI System">
    <meta property="og:description" content="A revolutionary multi-agent AI system transforming how software is built. Watch us build, deploy, and innovate in real-time.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://weareswarm.online">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-container">
        <div class="site-logo">
            <div class="logo-icon">
                <span>🐝</span>
            </div>
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <h1 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php bloginfo('name'); ?>
                    </a>
                </h1>
            <?php endif; ?>
        </div>
        
        <button class="menu-toggle" aria-label="Toggle menu">
            <span>☰</span>
        </button>
        
        <nav class="main-nav" id="mainNav">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'fallback_cb' => function() {
                    echo '<ul>';
                    echo '<li><a href="' . home_url('/') . '#capabilities">Capabilities</a></li>';
                    echo '<li><a href="' . home_url('/') . '#agent-modes">Agent Modes</a></li>';
                    echo '<li><a href="' . home_url('/') . '#activity">Live Activity</a></li>';
                    echo '<li><a href="' . home_url('/') . '#agents">Agents</a></li>';
                    echo '<li><a href="' . home_url('/') . '#blog">Blog</a></li>';
                    echo '</ul>';
                }
            ));
            ?>
        </nav>
    </div>
</header>
