<?php
namespace TradingRobotPlug;

/**
 * The core plugin class.
 */
class Trading_Robot_Plug {

    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        $this->plugin_name = 'tradingrobotplug';
        $this->version = TRADINGROBOTPLUG_VERSION;

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        // Load API Client
        require_once TRADINGROBOTPLUG_PLUGIN_DIR . 'includes/api-client/class-api-client.php';
        
        // Load Managers
        require_once TRADINGROBOTPLUG_PLUGIN_DIR . 'includes/user-manager/class-user-manager.php';
        require_once TRADINGROBOTPLUG_PLUGIN_DIR . 'includes/performance-tracker/class-performance-tracker.php';
        require_once TRADINGROBOTPLUG_PLUGIN_DIR . 'includes/subscription-manager/class-subscription-manager.php';
        
        // Load Admin
        if (is_admin()) {
            require_once TRADINGROBOTPLUG_PLUGIN_DIR . 'admin/class-admin.php';
        }

        // Load Public (Shortcodes)
        require_once TRADINGROBOTPLUG_PLUGIN_DIR . 'public/class-public.php';
    }

    private function define_admin_hooks() {
        if (is_admin()) {
            $plugin_admin = new Admin($this->plugin_name, $this->version);
            add_action('admin_menu', [$plugin_admin, 'add_plugin_admin_menu']);
            add_action('admin_enqueue_scripts', [$plugin_admin, 'enqueue_styles']);
            add_action('admin_enqueue_scripts', [$plugin_admin, 'enqueue_scripts']);
        }
    }

    private function define_public_hooks() {
        $plugin_public = new Public_Display($this->plugin_name, $this->version);

        add_action('wp_enqueue_scripts', [$plugin_public, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [$plugin_public, 'enqueue_scripts']);
        
        // Register Shortcodes
        add_shortcode('trading_robot_pricing', [$plugin_public, 'render_pricing_shortcode']);
        add_shortcode('trading_robot_performance', [$plugin_public, 'render_performance_shortcode']);
        add_shortcode('trading_robot_marketplace', [$plugin_public, 'render_marketplace_shortcode']);
        add_shortcode('trading_robot_dashboard', [$plugin_public, 'render_dashboard_shortcode']);
    }

    public function run() {
        // Hook execution is handled by WordPress actions/filters defined in sub-classes
    }
}
