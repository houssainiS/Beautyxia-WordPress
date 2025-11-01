# Installation Guide

## Step 1: Download the Plugin

Download the `face-analysis-plugin` folder to your computer.

## Step 2: Upload to WordPress

1. Go to your WordPress admin dashboard
2. Navigate to **Plugins > Add New**
3. Click **Upload Plugin**
4. Choose the `face-analysis-plugin` folder (or zip it first)
5. Click **Install Now**

## Step 3: Activate the Plugin

1. After installation, click **Activate Plugin**
2. Or go to **Plugins** and find "Face Analysis & Beauty Tips" and click **Activate**

## Step 4: Add to Your Site

### Method 1: Using Shortcode
1. Go to **Pages** or **Posts**
2. Create a new page or edit an existing one
3. Add the shortcode: `[face_analysis]`
4. Publish the page

### Method 2: Using Block Editor
1. In the block editor, search for "Face Analysis"
2. Add the block to your page
3. Publish

## Step 5: Configure (Optional)

If you need to change the API endpoints:
1. Go to the plugin folder
2. Edit `includes/class-face-analysis.php`
3. Update the `$api_endpoint` and `$feedback_endpoint` variables
4. Save the file

## Troubleshooting

### Plugin not showing up?
- Make sure the plugin folder is in `/wp-content/plugins/`
- Check that all files are properly uploaded
- Clear your browser cache

### Shortcode not working?
- Make sure the plugin is activated
- Check that you're using the correct shortcode: `[face_analysis]`
- Verify the page is published

### API errors?
- Check that your API endpoints are correct
- Verify your backend API is running
- Check browser console for error messages (F12)

### Camera not working?
- Make sure you've granted camera permissions
- Check that your browser supports WebRTC
- Try a different browser if issues persist

## Support

For additional help, contact your development team.
