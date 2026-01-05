<?php
if (!defined('ABSPATH')) exit;

class FA_Admin_Settings {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    }

    public function add_plugin_menu() {
        add_menu_page(
            'Beautyxia Settings',
            'Beautyxia',
            'manage_options',
            'face-analyzer',
            array($this, 'render_settings_page'),
            'dashicons-face-smiling',
            100
        );
    }

    public function enqueue_admin_styles() {
        wp_enqueue_style('fa-admin-styles', plugins_url('admin-styles.css', __FILE__));
        wp_enqueue_script('fa-admin-script', plugins_url('admin-script.js', __FILE__), array('jquery'), '1.0', true);
    }

    public function render_settings_page() {
        // --- 1. HANDLE INCOMING CONNECTION SUCCESS ---
        // This catches the API Key from the Django redirect
        if (isset($_GET['api_key']) && isset($_GET['status']) && $_GET['status'] === 'success') {
            update_option('fa_api_key', sanitize_text_field($_GET['api_key']));
            
            // Redirect to a clean URL so the API key doesn't stay in the browser bar
            echo '<script>window.location.href="' . admin_url('admin.php?page=face-analyzer&connection=success') . '";</script>';
            exit;
        }

        $api_key = get_option('fa_api_key');
        $site_url = get_site_url();
        $admin_email = get_option('admin_email');

        // --- 2. HANDLE DISCONNECT REQUEST ---
        if (isset($_POST['fa_disconnect'])) {
            if ($api_key) {
                // Notify Django to deactivate
                wp_remote_post("http://127.0.0.1:8000/wordpress/deactivate/", array(
                    'method'    => 'POST',
                    'timeout'   => 15,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking'    => true,
                    'body'        => array(
                        'shop_url' => $site_url,
                        'api_key'  => $api_key,
                    ),
                ));
            }

            delete_option('fa_api_key');
            $api_key = false;
        }

        $django_connect_url = "http://127.0.0.1:8000/wordpress/connect/";
        $django_connect_url .= "?shop_url=" . urlencode($site_url);
        $django_connect_url .= "&admin_email=" . urlencode($admin_email);

        $skin_types = array('dry', 'normal', 'oily');
        $skin_concerns = array('darkcircle', 'skinredness', 'melasma', 'vascular', 'wrinkle');
        $acne_issues = array('blackhead', 'cystic', 'folliculitis', 'keloid', 'milium', 'papular', 'purulent', 'acne_scars', 'acne', 'pimple', 'spot');
        ?>
        <div class="fa-admin-container">
            <div class="fa-header-center">
                <h1 class="fa-title-centered">Beautyxia Dashboard</h1>
                <p class="fa-subtitle-centered">Manage your AI connection and product recommendations</p>
            </div>

            <div class="fa-content">
                <div class="fa-card fa-connection-card">
                    <div class="fa-card-header">
                        <h2 class="fa-card-title">
                            <span class="fa-status-indicator <?php echo $api_key ? 'connected' : 'disconnected'; ?>"></span>
                            Connection Status
                        </h2>
                        <span class="fa-status-badge <?php echo $api_key ? 'active' : 'inactive'; ?>">
                            <?php echo $api_key ? '● Active' : '● Inactive'; ?>
                        </span>
                    </div>

                    <div class="fa-card-body">
                        <?php if (!$api_key): ?>
                            <div class="fa-status-message">
                                <p>Your WordPress site is not yet connected to MakeupAI. Click below to establish a connection and start using advanced face analysis features.</p>
                            </div>
                            <a href="<?php echo esc_url($django_connect_url); ?>" class="fa-button fa-button-primary">
                                Connect to MakeupAI Backend
                            </a>
                        <?php else: ?>
                            <div class="fa-status-message success">
                                <p>Your site is connected and ready to use MakeupAI features.</p>
                            </div>

                            <div class="fa-info-table">
                                <div class="fa-info-row">
                                    <span class="fa-info-label">API Key:</span>
                                    <code class="fa-api-key"><?php echo esc_html($api_key); ?></code>
                                </div>
                                <div class="fa-info-row">
                                    <span class="fa-info-label">Shop URL:</span>
                                    <span><?php echo esc_html($site_url); ?></span>
                                </div>
                                <div class="fa-info-row">
                                    <span class="fa-info-label">Status:</span>
                                    <span class="fa-status-active">Connected ✓</span>
                                </div>
                            </div>

                            <div class="fa-button-group">
                                <a href="<?php echo esc_url($django_connect_url); ?>" class="fa-button fa-button-secondary">
                                    Change Account / Reconnect
                                </a>
                                <form method="post" action="" style="display: inline;">
                                    <button type="submit" name="fa_disconnect" class="fa-button fa-button-danger" onclick="return confirm('Are you sure? This will deactivate all AI features.');">
                                        Disconnect
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="fa-card fa-tags-card">
                    <div class="fa-card-header">
                        <h2 class="fa-card-title">
                            <span class="fa-icon-small">🏷️</span> Product Recommendation Tags
                        </h2>
                    </div>

                    <div class="fa-card-body">
                        <p class="fa-description">
                            The recommendation system works based on product names and tags. Adding specific tags makes recommendations more accurate. You can add tags for skin types, concerns, and acne-related issues.
                        </p>

                        <div class="fa-tag-categories">
                            <div class="fa-tag-category">
                                <h3 class="fa-category-title">Skin Types</h3>
                                <div class="fa-tag-list">
                                    <?php foreach ($skin_types as $tag): ?>
                                        <span class="fa-tag fa-tag-skin-type"><?php echo esc_html($tag); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <p class="fa-category-description">Use these tags to indicate the skin type a product is suitable for.</p>
                            </div>

                            <div class="fa-tag-category">
                                <h3 class="fa-category-title">Skin Concerns</h3>
                                <div class="fa-tag-list">
                                    <?php foreach ($skin_concerns as $tag): ?>
                                        <span class="fa-tag fa-tag-skin-concern"><?php echo esc_html(str_replace('_', ' ', $tag)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <p class="fa-category-description">Use these tags to indicate what skin concerns the product addresses.</p>
                            </div>

                            <div class="fa-tag-category">
                                <h3 class="fa-category-title">Acne & Related Issues</h3>
                                <div class="fa-tag-list">
                                    <?php foreach ($acne_issues as $tag): ?>
                                        <span class="fa-tag fa-tag-acne"><?php echo esc_html(str_replace('_', ' ', $tag)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <p class="fa-category-description">Use these tags to indicate acne-related benefits and properties.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fa-card">
                    <div class="fa-card-header">
                        <h2 class="fa-card-title">
                            <span class="fa-icon-small">📌</span> Display Face Analyzer
                        </h2>
                    </div>

                    <div class="fa-card-body">
                        <p class="fa-description">Use this shortcode to display the face analyzer on any page or post:</p>
                        <div class="fa-shortcode-box">
                            <code>[face_analyzer]</code>
                            <button class="fa-copy-btn" data-copy="[face_analyzer]" title="Copy shortcode">📋</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fa-footer">
                <p>Face Analyzer v1.0 | Powered by MakeupAI</p>
            </div>
        </div>
        <?php
    }
}

// Initialize the class
new FA_Admin_Settings();