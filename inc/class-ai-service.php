<?php
/**
 * AI Service for Photo to Elementor plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Photo_To_Elementor_AI_Service {

    private $api_key;
    private $model;
    private $provider;

    public function __construct() {
        $this->api_key = get_option('photo_to_elementor_api_key');
        $this->model = get_option('photo_to_elementor_model', 'gpt-4o');
        $this->provider = get_option('photo_to_elementor_provider', 'openai');
    }

    /**
     * Analyze image and generate layout structure
     */
    public function analyze_image($image_data, $additional_prompt = '') {
        if ($this->provider === 'openai') {
            return $this->analyze_with_openai($image_data, $additional_prompt);
        } elseif ($this->provider === 'anthropic') {
            return $this->analyze_with_anthropic($image_data, $additional_prompt);
        }

        return new WP_Error('invalid_provider', 'Invalid AI provider configured');
    }

<?php
/**
 * AI Service for Photo to Elementor plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Photo_To_Elementor_AI_Service {

    private $api_key;
    private $model;
    private $provider;

    public function __construct() {
        $this->api_key = get_option('photo_to_elementor_api_key');
        $this->model = get_option('photo_to_elementor_model', 'gpt-4o');
        $this->provider = get_option('photo_to_elementor_provider', 'openai');
    }

    /**
     * Analyze image and generate layout structure
     */
    public function analyze_image($image_data, $additional_prompt = '') {
        if ($this->provider === 'openai') {
            return $this->analyze_with_openai($image_data, $additional_prompt);
        } elseif ($this->provider === 'anthropic') {
            return $this->analyze_with_anthropic($image_data, $additional_prompt);
        }

        return new WP_Error('invalid_provider', 'Invalid AI provider configured');
    }

    /**
     * Generate layout from image using OpenAI Vision API
     */
    private function analyze_with_openai($image_data, $additional_prompt) {
        $system_prompt = $this->get_system_prompt($additional_prompt);

        $messages = [
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $system_prompt
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => 'data:image/jpeg;base64,' . $image_data
                        ]
                    ]
                ]
            ]
        ];

        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json',
            ],
            'body' => wp_json_encode([
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => 4000,
                'temperature' => 0.1,
                'response_format' => ['type' => 'json_object']
            ]),
            'timeout' => 60,
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['error'])) {
            return new WP_Error('openai_error', $data['error']['message']);
        }

        $content = $data['choices'][0]['message']['content'] ?? '';

        // Parse the JSON response
        $parsed = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $parsed;
        }

        return new WP_Error('invalid_response', 'Could not parse AI response as JSON');
    }

    /**
     * Analyze image using Anthropic Claude (placeholder - would need vision API)
     */
    private function analyze_with_anthropic($image_data, $additional_prompt) {
        // Note: Anthropic doesn't have vision capabilities in their API yet
        // This is a placeholder for when they add it
        return new WP_Error('anthropic_not_supported', 'Anthropic vision API not yet available');
    }

    /**
     * Get the system prompt for AI
     */
    private function get_system_prompt($additional_prompt = '') {
        $base_prompt = 'You are an Elementor Expert. Analyze the provided image and convert it into a structured JSON layout.
Use only these widget types: \'heading\', \'text-editor\', \'button\', \'image\', \'icon\', \'spacer\'.
Structure the response as a \'container\' with \'children\'.
For each element, identify:
- colors (hex)
- alignment
- content string
- spacing (padding/margin)

Return ONLY valid JSON. Structure example:
{
  "elements": [
    { "type": "container", "settings": {"background": "#ffffff"}, "children": [...] }
  ]
}' . ($additional_prompt ? ' ' . $additional_prompt : '');

        return $base_prompt;
    }
}

    /**
     * Analyze image using Anthropic Claude (placeholder - would need vision API)
     */
    private function analyze_with_anthropic($image_data, $additional_prompt) {
        // Note: Anthropic doesn't have vision capabilities in their API yet
        // This is a placeholder for when they add it
        return new WP_Error('anthropic_not_supported', 'Anthropic vision API not yet available');
    }

    /**
     * Get the system prompt for AI
     */
    private function get_system_prompt($additional_prompt = '') {
        $base_prompt = 'You are an expert Elementor page builder assistant. Your task is to analyze images of websites or UI designs and convert them into structured JSON that represents an Elementor layout.

IMPORTANT: You must output ONLY valid JSON. No explanations, no markdown, just pure JSON.

The JSON structure should follow this simplified Elementor schema:

{
  "type": "container",
  "settings": {
    "flex_direction": "column",
    "padding": {"unit": "px", "top": "20", "right": "20", "bottom": "20", "left": "20"},
    "background_color": "#ffffff"
  },
  "elements": [
    {
      "type": "widget",
      "widgetType": "heading",
      "settings": {
        "title": "Your Heading Text",
        "size": "h2",
        "align": "center"
      }
    },
    {
      "type": "container",
      "settings": {"flex_direction": "row"},
      "elements": [
        // nested elements
      ]
    }
  ]
}

Key guidelines:
1. Use "container" as the main building block (Elementor\'s modern Flexbox system)
2. For widgets, use proper Elementor widget types: heading, text-editor, button, image, spacer, divider
3. Extract colors as hex codes, sizes as approximate values
4. Focus on visual hierarchy: headers, sections, columns
5. Make layouts responsive by default
6. Use semantic spacing and alignment

' . $additional_prompt;

        return $base_prompt;
    }
}