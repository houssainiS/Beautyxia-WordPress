# Face Analysis & Beauty Tips WordPress Plugin

A fully functional WordPress plugin that provides AI-powered face analysis with personalized beauty tips. This plugin converts your Shopify extension into a WordPress-compatible tool.

## Features

- **Face Analysis**: Upload or capture images for real-time facial analysis
- **Skin Type Detection**: Identifies dry, normal, or oily skin types
- **Eye Color Recognition**: Detects left and right eye colors
- **Acne Analysis**: Assesses acne severity levels (0-3)
- **Personalized Tips**: Generates beauty tips based on analysis results
- **Multilingual Support**: English, French, and Arabic translations
- **Webcam Support**: Capture photos directly from your device
- **GDPR Compliant**: Includes consent management
- **Responsive Design**: Works on desktop, tablet, and mobile devices

## Installation

1. Download the plugin folder
2. Upload to `/wp-content/plugins/` directory
3. Activate the plugin from WordPress admin panel
4. Use the shortcode `[face_analysis]` on any page or post

## Usage

### Basic Shortcode
\`\`\`
[face_analysis]
\`\`\`

### With Custom Title and Description
\`\`\`
[face_analysis title="My Face Analysis" description="Analyze your face for personalized beauty tips"]
\`\`\`

## Configuration

The plugin uses the following API endpoints:
- **Analysis API**: `https://beautyai.duckdns.org/upload/`
- **Feedback API**: `https://beautyai.duckdns.org/submit-feedback/`

You can modify these endpoints in the `class-face-analysis.php` file if needed.

## File Structure

\`\`\`
face-analysis-plugin/
├── face-analysis-plugin.php       # Main plugin file
├── includes/
│   ├── class-face-analysis.php    # Main plugin class
│   └── class-tips-generator.php   # Tips generation logic
├── templates/
│   └── face-analysis-template.php # UI template
├── assets/
│   ├── css/
│   │   └── style.css              # Plugin styles
│   └── js/
│       ├── tips.js                # Tips generator script
│       └── main.js                # Main plugin script
└── README.md                       # This file
\`\`\`

## Supported Languages

- English (en)
- French (fr)
- Arabic (ar)

Users can switch languages using the language selector in the plugin interface.

## Requirements

- WordPress 5.0+
- PHP 7.2+
- Modern browser with JavaScript enabled
- Camera access (for webcam capture feature)

## API Integration

The plugin communicates with your backend API for:
1. **Face Analysis**: Sends image to `/upload/` endpoint
2. **Feedback**: Sends user feedback to `/submit-feedback/` endpoint

Both endpoints should accept POST requests with appropriate data.

## Customization

### Modifying Tips

Edit the `SKIN_TYPE_TIPS` and `ACNE_SEVERITY_TIPS` objects in `assets/js/tips.js` to customize beauty tips.

### Styling

All styles are in `assets/css/style.css`. Modify colors, fonts, and layouts as needed.

### API Endpoints

Update the endpoints in `includes/class-face-analysis.php`:
\`\`\`php
private $api_endpoint = 'https://your-api.com/upload/';
private $feedback_endpoint = 'https://your-api.com/submit-feedback/';
\`\`\`

## Support

For issues or questions, please contact your development team.

## License

GPL v2 or later
