<?php
/**
 * REST API endpoint for Photo to Elementor plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Photo_To_Elementor_API_Endpoint {

    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Register REST API routes
     */
    public function register_routes() {
        register_rest_route('photo-to-elementor/v1', '/generate-layout', [
            'methods' => 'POST',
            'callback' => [$this, 'generate_layout'],
            'permission_callback' => [$this, 'check_permissions'],
            'args' => [
                'image' => [
                    'required' => true,
                    'description' => 'Base64 encoded image data',
                    'type' => 'string',
                ],
                'prompt' => [
                    'required' => false,
                    'description' => 'Additional prompt instructions',
                    'type' => 'string',
                    'default' => '',
                ],
            ],
        ]);
    }

    /**
     * Check if user has permission to use the API
     */
    public function check_permissions() {
        return current_user_can('edit_posts') && wp_verify_nonce(
            isset($_SERVER['HTTP_X_WP_NONCE']) ? $_SERVER['HTTP_X_WP_NONCE'] : '',
            'wp_rest'
        );
    }

    /**
     * Generate layout from image
     */
    public function generate_layout($request) {
        try {
            $image_data = $request->get_param('image');
            $additional_prompt = $request->get_param('prompt');

            // Validate image data
            if (empty($image_data)) {
                return new WP_Error('missing_image', 'Image data is required', ['status' => 400]);
            }

            // Check if API key is configured
            $api_key = get_option('photo_to_elementor_api_key');
            if (empty($api_key)) {
                return new WP_Error('api_key_missing', 'API key not configured', ['status' => 500]);
            }

            // Process the image with AI
            $ai_service = new Photo_To_Elementor_AI_Service();
            $ai_response = $ai_service->analyze_image($image_data, $additional_prompt);

            if (is_wp_error($ai_response)) {
                return $ai_response;
            }

            // Convert AI response to Elementor JSON
            $bridge = new Photo_To_Elementor_Elementor_Bridge();
            $elementor_data = $bridge->convert_to_elementor_json($ai_response);

            return new WP_REST_Response([
                'success' => true,
                'data' => $elementor_data,
            ], 200);

        } catch (Exception $e) {
            return new WP_Error('generation_failed', $e->getMessage(), ['status' => 500]);
        }
    }
}