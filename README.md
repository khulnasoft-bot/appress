# Photo to Elementor

A WordPress plugin that converts photos of websites or UI designs into Elementor page layouts using AI vision technology.

## Features

- **AI-Powered Conversion**: Uses OpenAI's GPT-4o Vision API to analyze images and generate Elementor layouts
- **Elementor Integration**: Seamlessly integrates with the Elementor page builder
- **Drag & Drop Upload**: Easy image upload interface within the Elementor editor
- **Customizable Prompts**: Add additional instructions for layout generation
- **Modern Architecture**: Uses Elementor's Flexbox Container system for responsive layouts

## Installation

1. Download the plugin files
2. Upload to your WordPress plugins directory (`/wp-content/plugins/`)
3. Activate the plugin through the WordPress admin
4. Configure your AI API settings in **Settings > Photo to Elementor**

## Configuration

### API Setup

1. Go to **Settings > Photo to Elementor** in your WordPress admin
2. Choose your AI provider (OpenAI or Anthropic)
3. Enter your API key
4. Select your preferred model

### Supported Models

- **OpenAI**: GPT-4o, GPT-4 Vision Preview
- **Anthropic**: Claude 3 Opus, Claude 3 Sonnet (when vision API becomes available)

## Usage

1. Open any page with Elementor
2. Click the **"Photo to UI"** button in the Elementor panel
3. Upload an image of a website or UI design
4. Add optional instructions (e.g., "Make it mobile-first", "Use blue theme")
5. Click **"Generate Layout"** to convert the image into an Elementor layout

## How It Works

1. **Image Optimization**: Client-side resizing reduces API costs (max 1024x1024px, 80% JPEG quality)
2. **AI Analysis**: GPT-4o Vision analyzes visual hierarchy, colors, typography, and layout structure
3. **Structured JSON**: AI outputs focused JSON with containers, widgets, and styling
4. **Elementor Mapping**: Converts to proper Elementor format with 7-char IDs and specific setting keys
5. **Skeleton Loading**: Shows v0.dev-style placeholders during processing
6. **Live Injection**: Inserts generated layout directly into Elementor with automatic cleanup

## Technical Features

### 🔒 **Security**
- WordPress nonce verification for API requests
- User capability checks (`edit_posts`)
- Secure API key storage

### 📏 **Image Optimization**
- Client-side canvas resizing
- Automatic quality compression
- Format preservation

### 🎨 **Skeleton Loading**
- Real-time loading placeholders
- Automatic cleanup on success/error
- v0.dev aesthetic integration

### 🎯 **AI Prompt Engineering**
- Elementor-specific system prompts
- Limited widget type enforcement
- Structured JSON output requirements

## Supported Elements

The plugin can detect and convert:
- Headings and text content
- Buttons and call-to-action elements
- Images and media
- Layout containers and sections
- Color schemes and spacing
- Basic responsive behavior

## Requirements

- WordPress 5.0+
- PHP 7.4+
- Elementor Page Builder (free version)
- Valid API key for OpenAI or Anthropic

## Development

### Project Structure

```
photo-to-elementor/
├── plugin.php              # Main plugin file
├── inc/
│   ├── class-settings.php      # Admin settings page
│   ├── class-api-endpoint.php  # REST API endpoint
│   ├── class-ai-service.php    # AI API communication
│   └── class-elementor-bridge.php # JSON conversion logic
├── assets/
│   ├── js/editor.js           # Elementor editor integration
│   └── css/editor.css         # Modal and button styles
└── src/                      # Future development files
```

### API Endpoints

- `POST /wp-json/photo-to-elementor/v1/generate-layout`
  - Parameters: `image` (base64), `prompt` (optional)
  - Returns: Elementor JSON structure

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

GPL v2 or later

## Support

For support and feature requests, please create an issue in the repository.

## Roadmap

- [ ] Support for more AI providers
- [ ] Iterative refinement with chat interface
- [ ] Template library integration
- [ ] Mobile-specific optimizations
- [ ] Advanced styling extraction
- [ ] Batch processing capabilities