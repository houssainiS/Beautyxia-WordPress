<?php
/**
 * Plugin Name: Beautyxia
 * Plugin URI: https://beautyai.duckdns.org/
 * Description: AI-powered face analysis tool that provides personalized beauty tips based on skin type, eye color, and acne severity.
 * Version: 1.0.0
 * Author: Beautyxia
 * Author URI: https://beautyai.duckdns.org/
 * License: GPL v2 or later
 * License URI: https://beautyai.duckdns.org/privacy-policy/
 * Text Domain: face-analysis-plugin
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FACE_ANALYSIS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FACE_ANALYSIS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FACE_ANALYSIS_PLUGIN_VERSION', '1.0.0');

// 1. Include required files
require_once FACE_ANALYSIS_PLUGIN_DIR . 'includes/class-face-analysis.php';
require_once FACE_ANALYSIS_PLUGIN_DIR . 'includes/class-tips-generator.php';

// 2. Load the Backoffice (Admin Settings)
if ( is_admin() ) {
    $admin_settings_path = FACE_ANALYSIS_PLUGIN_DIR . 'admin/admin-settings.php';
    if ( file_exists( $admin_settings_path ) ) {
        require_once $admin_settings_path;
    }
}

// 3. Initialize the plugin
function face_analysis_init() {
    $plugin = new Face_Analysis_Plugin();
    $plugin->init();
}
add_action('plugins_loaded', 'face_analysis_init');

// Activation hook
register_activation_hook(__FILE__, function() {
    do_action('face_analysis_activated');
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    do_action('face_analysis_deactivated');
});