<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header container">
  <nav class="main-nav">
    <!-- Mobile Menu Toggle Button -->
    <button class="menu-toggle" id="mobile-menu-toggle" aria-label="Toggle navigation menu">
      <span>☰ Menu</span>
    </button>
    
    <?php 
      wp_nav_menu([
        'theme_location' => 'primary',
        'container' => '',
        'menu_class' => 'nav-list',
        'menu_id' => 'primary-menu'
      ]); 
    ?>
  </nav>
</header>
