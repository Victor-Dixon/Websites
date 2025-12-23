<?php
function dd_safe_avatar( $user_id = 0, $size = 64 ): string {
  $user_id = (int) $user_id;
  if ($user_id <= 0) {
    $current = wp_get_current_user();
    $user_id = $current ? (int) $current->ID : 0;
  }

  $html = get_avatar($user_id, $size);
  return is_string($html) ? $html : '';
}

/**
 * Add Digital Dreamscape meta description to posts
 */
function digitaldreamscape_post_meta_description() {
    if (is_single()) {
        $description = 'Digital Dreamscape is a living, narrative-driven AI world where real actions become story, and story feeds back into execution. This episode is part of the persistent simulation of self + system.';
        echo '<meta name="description" content="' . esc_attr($description) . '">';
    }
}
add_action('wp_head', 'digitaldreamscape_post_meta_description');
