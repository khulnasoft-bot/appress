<?php
/**
 * Basic tests for Photo to Elementor plugin
 * Run these tests to validate the conversion logic
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once 'sample-data.php';

class Photo_To_Elementor_Tests {

    public function run_tests() {
        echo "<h2>Photo to Elementor Tests</h2>";

        $this->test_hero_conversion();
        $this->test_features_conversion();

        echo "<p>Tests completed!</p>";
    }

    private function test_hero_conversion() {
        echo "<h3>Testing Hero Section Conversion</h3>";

        $ai_data = get_sample_ai_response_hero();
        $expected = get_expected_elementor_output_hero();

        $bridge = new Photo_To_Elementor_Elementor_Bridge();
        $result = $bridge->convert_to_elementor_json($ai_data);

        // Basic validation
        if (is_array($result) && count($result) > 0) {
            echo "<p style='color: green;'>✓ Hero conversion successful - basic structure valid</p>";

            // Check if main container exists
            if (isset($result[0]['elType']) && $result[0]['elType'] === 'container') {
                echo "<p style='color: green;'>✓ Main container element created</p>";
            }

            // Check if elements exist
            if (isset($result[0]['elements']) && is_array($result[0]['elements'])) {
                echo "<p style='color: green;'>✓ Child elements array created (" . count($result[0]['elements']) . " elements)</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Hero conversion failed</p>";
        }
    }

    private function test_features_conversion() {
        echo "<h3>Testing Features Section Conversion</h3>";

        $ai_data = get_sample_ai_response_features();

        $bridge = new Photo_To_Elementor_Elementor_Bridge();
        $result = $bridge->convert_to_elementor_json($ai_data);

        // Basic validation
        if (is_array($result) && count($result) > 0) {
            echo "<p style='color: green;'>✓ Features conversion successful - basic structure valid</p>";

            // Check nested containers
            $main_container = $result[0];
            if (isset($main_container['elements']) && count($main_container['elements']) > 0) {
                $heading = $main_container['elements'][0];
                if (isset($heading['widgetType']) && $heading['widgetType'] === 'heading') {
                    echo "<p style='color: green;'>✓ Heading widget created</p>";
                }

                $features_container = $main_container['elements'][1];
                if (isset($features_container['elType']) && $features_container['elType'] === 'container') {
                    echo "<p style='color: green;'>✓ Features container created</p>";
                }
            }
        } else {
            echo "<p style='color: red;'>✗ Features conversion failed</p>";
        }
    }
}

// Function to run tests (call this from admin area)
function photo_to_elementor_run_tests() {
    if (!current_user_can('manage_options')) {
        return;
    }

    require_once PHOTO_TO_ELEMENTOR_PLUGIN_DIR . 'inc/class-elementor-bridge.php';
    require_once PHOTO_TO_ELEMENTOR_PLUGIN_DIR . 'src/sample-data.php';

    $tests = new Photo_To_Elementor_Tests();
    $tests->run_tests();
}