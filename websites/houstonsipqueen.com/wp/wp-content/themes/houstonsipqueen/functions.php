<?php
/**
 * Houston Sip Queen Theme Functions
 * 
 * @package HoustonSipQueen
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function houstonsipqueen_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 300,
        'flex-height' => true,
        'flex-width' => true,
    ));
    add_theme_support('automatic-feed-links');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'houstonsipqueen'),
        'footer' => __('Footer Menu', 'houstonsipqueen'),
    ));
}
add_action('after_setup_theme', 'houstonsipqueen_setup');

/**
 * Enqueue Styles and Scripts
 */
function houstonsipqueen_scripts() {
    // Enqueue theme stylesheet
    wp_enqueue_style('houstonsipqueen-style', get_stylesheet_uri(), array(), '1.0.0');

    // Enqueue theme JavaScript
    wp_enqueue_script('houstonsipqueen-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'houstonsipqueen_scripts');

/**
 * Register Widget Areas
 */
function houstonsipqueen_widgets_init() {
    register_sidebar(array(
        'name' => __('Footer Widget Area', 'houstonsipqueen'),
        'id' => 'footer-1',
        'description' => __('Add widgets here to appear in your footer.', 'houstonsipqueen'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'houstonsipqueen_widgets_init');

/**
 * Create Quote Page if it doesn't exist
 */
function houstonsipqueen_create_quote_page() {
    if (get_page_by_path('quote')) {
        return;
    }
    
    $quote_page = array(
        'post_title' => 'Request a Quote',
        'post_name' => 'quote',
        'post_status' => 'publish',
        'post_type' => 'page',
        'page_template' => 'page-quote.php'
    );
    wp_insert_post($quote_page);
}
add_action('after_switch_theme', 'houstonsipqueen_create_quote_page');

/**
 * Handle Quote Form Submission
 */
function houstonsipqueen_handle_quote_form() {
    if (!is_page('quote')) {
        return;
    }

    if (!isset($_POST['quote_nonce']) || !wp_verify_nonce($_POST['quote_nonce'], 'quote_form')) {
        return;
    }

    // Honeypot spam protection
    if (!empty($_POST['website_url'])) {
        return; // Spam detected
    }

    // Sanitize and validate input
    $name = sanitize_text_field($_POST['quote_name'] ?? '');
    $email = sanitize_email($_POST['quote_email'] ?? '');
    $phone = sanitize_text_field($_POST['quote_phone'] ?? '');
    $event_date = sanitize_text_field($_POST['event_date'] ?? '');
    $event_type = sanitize_text_field($_POST['event_type'] ?? '');
    $guest_count = sanitize_text_field($_POST['guest_count'] ?? '');
    $message = sanitize_textarea_field($_POST['quote_message'] ?? '');

    // Validation
    $errors = array();
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (empty($email) || !is_email($email)) {
        $errors[] = 'Valid email is required.';
    }
    if (empty($phone)) {
        $errors[] = 'Phone number is required.';
    }

    // If validation fails, store errors in transient
    if (!empty($errors)) {
        set_transient('quote_form_errors', $errors, 30);
        return;
    }

    // Email settings
    $to = get_option('admin_email');
    $email_subject = 'New Quote Request from Houston Sip Queen';

    // Build email message
    $email_message = "New quote request from houstonsipqueen.com\n\n";
    $email_message .= "Name: {$name}\n";
    $email_message .= "Email: {$email}\n";
    $email_message .= "Phone: {$phone}\n";
    if (!empty($event_date)) {
        $email_message .= "Event Date: {$event_date}\n";
    }
    if (!empty($event_type)) {
        $email_message .= "Event Type: {$event_type}\n";
    }
    if (!empty($guest_count)) {
        $email_message .= "Guest Count: {$guest_count}\n";
    }
    if (!empty($message)) {
        $email_message .= "\nMessage:\n{$message}\n";
    }
    $email_message .= "\n---\n";
    $email_message .= "Submitted: " . date('F j, Y, g:i a') . "\n";
    $email_message .= "IP Address: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n";

    // Email headers
    $headers = array(
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
        'Content-Type: text/plain; charset=UTF-8'
    );

    // Send email
    $sent = wp_mail($to, $email_subject, $email_message, $headers);

    // Store result in transient for display
    if ($sent) {
        set_transient('quote_form_success', true, 30);
        // Optional: Send auto-reply to user
        $auto_reply_subject = 'Thank you for your quote request - Houston Sip Queen';
        $auto_reply_message = "Dear {$name},\n\n";
        $auto_reply_message .= "Thank you for requesting a quote from Houston Sip Queen. We've received your request and will get back to you within 24 hours.\n\n";
        $auto_reply_message .= "Best regards,\nHouston Sip Queen Team";
        wp_mail($email, $auto_reply_subject, $auto_reply_message);
    } else {
        set_transient('quote_form_errors', array('Failed to send message. Please try again or contact us directly.'), 30);
    }

    // Redirect to prevent form resubmission
    wp_safe_redirect(add_query_arg('submitted', $sent ? 'success' : 'error', get_permalink()));
    exit;
}
add_action('template_redirect', 'houstonsipqueen_handle_quote_form');


/**
 * Add Google Analytics 4 and Facebook Pixel tracking codes
 * Generated by batch_analytics_setup.py
 */
function add_analytics_tracking_codes() {
    // Google Analytics 4 (GA4)
        echo '<!-- Google Analytics 4 (GA4) -->\n';
        echo '<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>\n';
        echo '<script>\n';
        echo 'window.dataLayer = window.dataLayer || [];\n';
        echo 'function gtag(){dataLayer.push(arguments);}\n';
        echo 'gtag(\'js\', new Date());\n';
        echo 'gtag(\'config\', \'G-XXXXXXXXXX\', {\n';
        echo '\'page_path\': window.location.pathname,\n';
        echo '\'page_title\': document.title,\n';
        echo '});\n';
        echo '// Custom Events Tracking\n';
        echo '// Track lead_magnet_submit event\n';
        echo 'gtag("event", "lead_magnet_submit", {\n';
        echo '"event_category": "engagement",\n';
        echo '"event_label": "lead_magnet_submit"\n';
        echo '});\n';
        echo '// Track quote_form_submit event\n';
        echo 'gtag("event", "quote_form_submit", {\n';
        echo '"event_category": "engagement",\n';
        echo '"event_label": "quote_form_submit"\n';
        echo '});\n';
        echo '// Track booking_click event\n';
        echo 'gtag("event", "booking_click", {\n';
        echo '"event_category": "engagement",\n';
        echo '"event_label": "booking_click"\n';
        echo '});\n';
        echo '// Track phone_click event\n';
        echo 'gtag("event", "phone_click", {\n';
        echo '"event_category": "engagement",\n';
        echo '"event_label": "phone_click"\n';
        echo '});\n';
        echo '</script>\n';
        echo '<!-- End GA4 -->\n';
    
    // Facebook Pixel
        echo '<!-- Facebook Pixel Code -->\n';
        echo '<script>\n';
        echo '!function(f,b,e,v,n,t,s)\n';
        echo '{{if(f.fbq)return;n=f.fbq=function(){{n.callMethod?\n';
        echo 'n.callMethod.apply(n,arguments):n.queue.push(arguments)}};\n';
        echo 'if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version=\'2.0\';\n';
        echo 'n.queue=[];t=b.createElement(e);t.async=!0;\n';
        echo 't.src=v;s=b.getElementsByTagName(e)[0];\n';
        echo 's.parentNode.insertBefore(t,s)}}(window, document,\'script\',\n';
        echo '\'https://connect.facebook.net/en_US/fbevents.js\');\n';
        echo 'fbq(\'init\', \'YOUR_PIXEL_ID\');\n';
        echo 'fbq(\'track\', \'PageView\');\n';
        echo '</script>\n';
        echo '<noscript>\n';
        echo '<img height="1" width="1" style="display:none"\n';
        echo 'src="https://www.facebook.com/tr?id=YOUR_PIXEL_ID&ev=PageView&noscript=1"/>\n';
        echo '</noscript>\n';
        echo '<!-- End Facebook Pixel Code -->\n';
}
add_action('wp_head', 'add_analytics_tracking_codes', 99);
