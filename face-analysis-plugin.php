<?php
/**
 * Plugin Name: Face Analysis & Beauty Tips
 * Plugin URI: https://example.com/face-analysis
 * Description: AI-powered face analysis tool that provides personalized beauty tips based on skin type, eye color, and acne severity.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
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

// Include required files
require_once FACE_ANALYSIS_PLUGIN_DIR . 'includes/class-face-analysis.php';
require_once FACE_ANALYSIS_PLUGIN_DIR . 'includes/class-tips-generator.php';

// Initialize the plugin
function face_analysis_init() {
    $plugin = new Face_Analysis_Plugin();
    $plugin->init();
}
add_action('plugins_loaded', 'face_analysis_init');

// Activation hook
register_activation_hook(__FILE__, function() {
    // Plugin activation code if needed
    do_action('face_analysis_activated');
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    // Plugin deactivation code if needed
    do_action('face_analysis_deactivated');
});
