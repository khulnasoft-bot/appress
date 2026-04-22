<?php
/**
 * Plugin Name: Photo to Elementor
 * Plugin URI: https://example.com/photo-to-elementor
 * Description: Convert photos to Elementor layouts using AI vision
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v2 or later
 * Text Domain: photo-to-elementor
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PHOTO_TO_ELEMENTOR_VERSION', '1.0.0');
define('PHOTO_TO_ELEMENTOR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PHOTO_TO_ELEMENTOR_PLUGIN_URL', plugin_dir_url(__FILE__));

// Check requirements
function photo_to_elementor_check_requirements() {
    if (!class_exists('Elementor\Plugin')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Photo to Elementor requires Elementor to be installed and activated.</p></div>';
        });
        return false;
    }
    return true;
}

// Initialize the plugin
function photo_to_elementor_init() {
    if (!photo_to_elementor_check_requirements()) {
        return;
    }

    // Load plugin files
    require_once PHOTO_TO_ELEMENTOR_PLUGIN_DIR . 'inc/class-settings.php';
    require_once PHOTO_TO_ELEMENTOR_PLUGIN_DIR . 'inc/class-api-endpoint.php';
    require_once PHOTO_TO_ELEMENTOR_PLUGIN_DIR . 'inc/class-ai-service.php';
    require_once PHOTO_TO_ELEMENTOR_PLUGIN_DIR . 'inc/class-elementor-bridge.php';

    // Initialize classes
    new Photo_To_Elementor_Settings();
    new Photo_To_Elementor_API_Endpoint();
    new Photo_To_Elementor_AI_Service();
    new Photo_To_Elementor_Elementor_Bridge();
}
add_action('plugins_loaded', 'photo_to_elementor_init');

// Enqueue scripts for Elementor editor
function photo_to_elementor_enqueue_editor_scripts() {
    wp_enqueue_script(
        'photo-to-elementor-editor',
        PHOTO_TO_ELEMENTOR_PLUGIN_URL . 'assets/js/editor.js',
        ['jquery'],
        PHOTO_TO_ELEMENTOR_VERSION,
        true
    );

    wp_enqueue_style(
        'photo-to-elementor-editor',
        PHOTO_TO_ELEMENTOR_PLUGIN_URL . 'assets/css/editor.css',
        [],
        PHOTO_TO_ELEMENTOR_VERSION
    );

    // Localize script with necessary data
    wp_localize_script('photo-to-elementor-editor', 'photoToElementor', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('photo_to_elementor_nonce'),
        'apiEndpoint' => rest_url('photo-to-elementor/v1/generate-layout')
    ]);
}
add_action('elementor/editor/after_enqueue_scripts', 'photo_to_elementor_enqueue_editor_scripts');

// Activation hook
function photo_to_elementor_activate() {
    // Create necessary database tables or options if needed
    add_option('photo_to_elementor_api_key', '');
    add_option('photo_to_elementor_model', 'gpt-4o');
}
register_activation_hook(__FILE__, 'photo_to_elementor_activate');

// Deactivation hook
function photo_to_elementor_deactivate() {
    // Cleanup if needed
}
register_deactivation_hook(__FILE__, 'photo_to_elementor_deactivate');