<?php
add_action('wp_enqueue_scripts', function () {
  $dir = get_stylesheet_directory();
  $uri = get_stylesheet_directory_uri();

  // CSS modules (order matters)
  $css = ['css/00-tokens.css','css/01-base.css','css/02-layout.css','css/03-components.css','css/04-pages.css'];
  foreach ($css as $rel) {
    $path = "$dir/$rel";
    if (!file_exists($path)) continue;
    wp_enqueue_style(
      'dd-' . sanitize_title(str_replace(['/','.css'],['-',''],$rel)),
      "$uri/$rel",
      [],
      (string) filemtime($path)
    );
  }

  // JS modules (optional)
  $js = ['js/nav.js','js/scroll.js','js/main.js'];
  foreach ($js as $rel) {
    $path = "$dir/$rel";
    if (!file_exists($path)) continue;
    wp_enqueue_script(
      'dd-' . sanitize_title(str_replace(['/','.js'],['-',''],$rel)),
      "$uri/$rel",
      [],
      (string) filemtime($path),
      true
    );
  }
  
  // Add Digital Dreamscape context to page
  wp_localize_script('dd-js-main', 'dreamscapeContext', array(
      'isEpisode' => is_single(),
      'isArchive' => is_archive(),
      'narrativeMode' => true,
  ));
});
