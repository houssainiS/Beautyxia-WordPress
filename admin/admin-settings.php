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

    /**
     * Helper to fetch real-time quota and plan from Django
     */
    private function get_quota_data($api_key, $site_url) {
        if (!$api_key) return null;

        // Note: You should ensure your Django urls.py has a 'status' endpoint 
        // that returns plan and usage details.
        $status_url = "http://127.0.0.1:8000/wordpress/status/";
        $response = wp_remote_get(add_query_arg(array(
            'shop_url' => $site_url,
            'api_key'  => $api_key
        ), $status_url));

        if (is_wp_error($response)) return null;

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    public function render_settings_page() {
        // --- 1. HANDLE INCOMING CONNECTION SUCCESS ---
        if (isset($_GET['api_key']) && isset($_GET['status']) && $_GET['status'] === 'success') {
            update_option('fa_api_key', sanitize_text_field($_GET['api_key']));
            echo '<script>window.location.href="' . admin_url('admin.php?page=face-analyzer&connection=success') . '";</script>';
            exit;
        }

        $api_key = get_option('fa_api_key');
        $site_url = get_site_url();
        $admin_email = get_option('admin_email');

        // --- 2. HANDLE DISCONNECT REQUEST ---
        if (isset($_POST['fa_disconnect'])) {
            if ($api_key) {
                wp_remote_post("http://127.0.0.1:8000/wordpress/deactivate/", array(
                    'body' => array(
                        'shop_url' => $site_url,
                        'api_key'  => $api_key,
                    ),
                ));
            }
            delete_option('fa_api_key');
            $api_key = false;
        }

        // Fetch Quota Info
        $quota = $this->get_quota_data($api_key, $site_url);
        $plan_name = $quota['plan'] ?? 'Free';
        $used = $quota['usage']['used'] ?? 0;
        $limit = $quota['usage']['limit'] ?? 100;
        $remaining = max(0, $limit - $used);

        $django_connect_url = "http://127.0.0.1:8000/wordpress/connect/?shop_url=" . urlencode($site_url) . "&admin_email=" . urlencode($admin_email);

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
                                <p>Your WordPress site is not yet connected. Click below to establish a connection.</p>
                            </div>
                            <a href="<?php echo esc_url($django_connect_url); ?>" class="fa-button fa-button-primary">Connect to MakeupAI Backend</a>
                        <?php else: ?>
                            <div class="fa-info-table">
                                <div class="fa-info-row">
                                    <span class="fa-info-label">API Key:</span>
                                    <code class="fa-api-key"><?php echo esc_html($api_key); ?></code>
                                </div>
                                <div class="fa-info-row">
                                    <span class="fa-info-label">Status:</span>
                                    <span class="fa-status-active">Connected ✓</span>
                                </div>
                            </div>
                            <div class="fa-button-group">
                                <a href="<?php echo esc_url($django_connect_url); ?>" class="fa-button fa-button-secondary">Change Account</a>
                                <form method="post" action="" style="display: inline;">
                                    <button type="submit" name="fa_disconnect" class="fa-button fa-button-danger" onclick="return confirm('Are you sure?');">Disconnect</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($api_key): ?>
                <div class="fa-card fa-quota-card">
                    <div class="fa-card-header">
                        <h2 class="fa-card-title">📊 Plan & Usage</h2>
                    </div>
                    <div class="fa-card-body">
                        <div class="fa-quota-info">
                            <p><strong>Current Plan:</strong> <span class="fa-plan-badge"><?php echo esc_html(ucfirst($plan_name)); ?></span></p>
                            <div class="fa-quota-main">
                                <span class="fa-remaining-qty"><?php echo number_format($remaining); ?></span>
                                <span class="fa-remaining-label">analyses left this month</span>
                            </div>
                            <div class="fa-progress-container">
                                <?php $progress = ($limit > 0) ? ($used / $limit) * 100 : 0; ?>
                                <div class="fa-progress-bar" style="width: <?php echo esc_attr($progress); ?>%;"></div>
                            </div>
                            <p class="fa-usage-subtext"><?php echo esc_html($used); ?> / <?php echo esc_html($limit); ?> used</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="fa-card fa-tags-card">
                    <div class="fa-card-header">
                        <h2 class="fa-card-title">🏷️ Product Recommendation Tags</h2>
                    </div>
                    <div class="fa-card-body">
                        <div class="fa-tag-categories">
                            <div class="fa-tag-category">
                                <h3 class="fa-category-title">Skin Types</h3>
                                <div class="fa-tag-list">
                                    <?php foreach ($skin_types as $tag): ?>
                                        <span class="fa-tag fa-tag-skin-type"><?php echo esc_html($tag); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="fa-tag-category">
                                <h3 class="fa-category-title">Skin Concerns</h3>
                                <div class="fa-tag-list">
                                    <?php foreach ($skin_concerns as $tag): ?>
                                        <span class="fa-tag fa-tag-skin-concern"><?php echo esc_html(str_replace('_', ' ', $tag)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fa-card">
                    <div class="fa-card-header">
                        <h2 class="fa-card-title">📌 Display Face Analyzer</h2>
                    </div>
                    <div class="fa-card-body">
                        <div class="fa-shortcode-box">
                            <code>[face_analyzer]</code>
                            <button class="fa-copy-btn" data-copy="[face_analyzer]">📋</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fa-footer">
                <p>Beautyxia v1.0 | Powered by Webixia</p>
            </div>
        </div>
        <?php
    }
}

new FA_Admin_Settings();