<?php
/**
 * Elementor Bridge for Photo to Elementor plugin
 * Converts AI-generated simplified JSON to full Elementor JSON structure
 */

if (!defined('ABSPATH')) {
    exit;
}

class Photo_To_Elementor_Elementor_Bridge {

<?php
/**
 * Elementor Bridge for Photo to Elementor plugin
 * Converts AI-generated simplified JSON to full Elementor JSON structure
 */

if (!defined('ABSPATH')) {
    exit;
}

class Photo_To_Elementor_Elementor_Bridge {

    /**
     * Convert AI JSON to Elementor format
     */
    public function convert_to_elementor_json($ai_json) {
        if (!is_array($ai_json) || !isset($ai_json['elements'])) {
            return new WP_Error('invalid_ai_data', 'Invalid AI response structure');
        }

        $elementor_data = [];

        foreach ($ai_json['elements'] as $element) {
            $elementor_data[] = $this->map_element($element);
        }

        return $elementor_data;
    }

    /**
     * Map individual element to Elementor format
     */
    private function map_element($el) {
        $id = substr(md5(uniqid()), 0, 7); // Elementor uses 7-char IDs

        // Base structure for an Elementor Container (Flexbox)
        $mapped = [
            'id' => $id,
            'elType' => ($el['type'] === 'container') ? 'container' : 'widget',
            'isInner' => false,
            'settings' => $this->map_settings($el),
            'elements' => []
        ];

        if ($mapped['elType'] === 'widget') {
            $mapped['widgetType'] = $el['type'];
        }

        if (!empty($el['children'])) {
            foreach ($el['children'] as $child) {
                $mapped['elements'][] = $this->map_element($child);
            }
        }

        return $mapped;
    }

    /**
     * Map settings from AI format to Elementor format
     */
    private function map_settings($el) {
        $settings = [];

        // Handle content for widgets
        if (isset($el['content'])) {
            $settings['title'] = $el['content']; // for headings
        }

        // Handle settings object
        if (isset($el['settings'])) {
            $el_settings = $el['settings'];

            // Background color
            if (isset($el_settings['background'])) {
                $settings['_background_background'] = 'classic';
                $settings['_background_color'] = $el_settings['background'];
            }

            // Padding
            if (isset($el_settings['padding'])) {
                $settings['_padding'] = $this->normalize_dimensions($el_settings['padding']);
            }

            // Margin
            if (isset($el_settings['margin'])) {
                $settings['_margin'] = $this->normalize_dimensions($el_settings['margin']);
            }

            // Flex direction
            if (isset($el_settings['flex_direction'])) {
                $settings['flex_direction'] = $el_settings['flex_direction'];
            }

            // Alignment
            if (isset($el_settings['align'])) {
                $settings['justify_content'] = $this->map_alignment($el_settings['align']);
            }
        }

        // Add default fallback styles for better appearance
        $settings = array_merge($this->get_default_styles(), $settings);

        return $settings;
    }

    /**
     * Get default fallback styles for clean appearance
     */
    private function get_default_styles() {
        return [
            '_typography_font_family' => 'Inter',
            '_typography_font_weight' => '600',
            '_typography_font_size' => [
                'unit' => 'px',
                'size' => 16
            ],
            '_padding' => [
                'unit' => 'px',
                'top' => '20',
                'right' => '20',
                'bottom' => '20',
                'left' => '20',
                'isLinked' => true
            ]
        ];
    }

    /**
     * Normalize dimension values (padding, margin)
     */
    private function normalize_dimensions($dimensions) {
        if (is_array($dimensions)) {
            return [
                'unit' => $dimensions['unit'] ?? 'px',
                'top' => $dimensions['top'] ?? '0',
                'right' => $dimensions['right'] ?? '0',
                'bottom' => $dimensions['bottom'] ?? '0',
                'left' => $dimensions['left'] ?? '0',
                'isLinked' => false,
            ];
        } elseif (is_string($dimensions)) {
            // Handle simple string values like "20px"
            $value = str_replace('px', '', $dimensions);
            return [
                'unit' => 'px',
                'top' => $value,
                'right' => $value,
                'bottom' => $value,
                'left' => $value,
                'isLinked' => true,
            ];
        }

        return [
            'unit' => 'px',
            'top' => '0',
            'right' => '0',
            'bottom' => '0',
            'left' => '0',
            'isLinked' => true,
        ];
    }

    /**
     * Map alignment values
     */
    private function map_alignment($align) {
        $mapping = [
            'left' => 'flex-start',
            'center' => 'center',
            'right' => 'flex-end',
            'justify' => 'space-between',
        ];

        return $mapping[$align] ?? 'flex-start';
    }
}
}