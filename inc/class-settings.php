<?php
/**
 * Settings page for Photo to Elementor plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Photo_To_Elementor_Settings {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * Add settings page to WordPress admin
     */
    public function add_settings_page() {
        add_options_page(
            'Photo to Elementor Settings',
            'Photo to Elementor',
            'manage_options',
            'photo-to-elementor',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('photo_to_elementor_settings', 'photo_to_elementor_api_key', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field'
        ]);

        register_setting('photo_to_elementor_settings', 'photo_to_elementor_model', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'gpt-4o'
        ]);

        register_setting('photo_to_elementor_settings', 'photo_to_elementor_provider', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'openai'
        ]);

        add_settings_section(
            'photo_to_elementor_main',
            'AI Configuration',
            [$this, 'settings_section_callback'],
            'photo-to-elementor'
        );

        add_settings_field(
            'photo_to_elementor_provider',
            'AI Provider',
            [$this, 'provider_field_callback'],
            'photo-to-elementor',
            'photo_to_elementor_main'
        );

        add_settings_field(
            'photo_to_elementor_api_key',
            'API Key',
            [$this, 'api_key_field_callback'],
            'photo-to-elementor',
            'photo_to_elementor_main'
        );

        add_settings_field(
            'photo_to_elementor_model',
            'Model',
            [$this, 'model_field_callback'],
            'photo-to-elementor',
            'photo_to_elementor_main'
        );
    }

    /**
     * Settings section callback
     */
    public function settings_section_callback() {
        echo '<p>Configure your AI provider settings for photo to Elementor conversion.</p>';
    }

    /**
     * Provider field callback
     */
    public function provider_field_callback() {
        $provider = get_option('photo_to_elementor_provider', 'openai');
        ?>
        <select name="photo_to_elementor_provider" id="photo_to_elementor_provider">
            <option value="openai" <?php selected($provider, 'openai'); ?>>OpenAI</option>
            <option value="anthropic" <?php selected($provider, 'anthropic'); ?>>Anthropic</option>
        </select>
        <?php
    }

    /**
     * API key field callback
     */
    public function api_key_field_callback() {
        $api_key = get_option('photo_to_elementor_api_key', '');
        ?>
        <input type="password" name="photo_to_elementor_api_key" id="photo_to_elementor_api_key"
               value="<?php echo esc_attr($api_key); ?>" class="regular-text" />
        <p class="description">Enter your API key. This will be stored securely.</p>
        <?php
    }

    /**
     * Model field callback
     */
    public function model_field_callback() {
        $model = get_option('photo_to_elementor_model', 'gpt-4o');
        $provider = get_option('photo_to_elementor_provider', 'openai');
        ?>
        <select name="photo_to_elementor_model" id="photo_to_elementor_model">
            <?php if ($provider === 'openai'): ?>
                <option value="gpt-4o" <?php selected($model, 'gpt-4o'); ?>>GPT-4o</option>
                <option value="gpt-4-vision-preview" <?php selected($model, 'gpt-4-vision-preview'); ?>>GPT-4 Vision Preview</option>
            <?php else: ?>
                <option value="claude-3-opus" <?php selected($model, 'claude-3-opus'); ?>>Claude 3 Opus</option>
                <option value="claude-3-sonnet" <?php selected($model, 'claude-3-sonnet'); ?>>Claude 3 Sonnet</option>
            <?php endif; ?>
        </select>
        <?php
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        // Handle test run
        if (isset($_POST['run_tests']) && check_admin_referer('photo_to_elementor_test')) {
            require_once PHOTO_TO_ELEMENTOR_PLUGIN_DIR . 'src/tests.php';
            photo_to_elementor_run_tests();
            echo '<hr>';
        }

        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('photo_to_elementor_settings');
                do_settings_sections('photo-to-elementor');
                submit_button('Save Settings');
                ?>
            </form>

            <hr>

            <h2>Development Tools</h2>
            <form method="post">
                <?php wp_nonce_field('photo_to_elementor_test'); ?>
                <p>Run basic tests to validate the conversion logic:</p>
                <input type="submit" name="run_tests" class="button button-secondary" value="Run Tests">
            </form>
        </div>
        <?php
    }
}