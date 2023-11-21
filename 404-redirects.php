<?php
/**
 * Plugin Name: 404 Redirect
 * Description: Redirect users to a specified page when encountering a 404 error.
 * Version: 1.0
 * Author: PlainSurf Solutions
 * Author URI: https://plainsurf.com/
 * Requires PHP at least: 7.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'redirect_activate');
register_deactivation_hook(__FILE__, 'redirect_deactivate');

// Activation function
function redirect_activate() {
    // No activation tasks for this simple example
}

// Deactivation function
function redirect_deactivate() {
    // No deactivation tasks for this simple example
}

// Add custom redirect for 404 errors
function redirect_on_404() {
    if (is_404()) {
        $redirect_url = get_option('404_redirect_url', home_url('/')); // Default to home page if no redirect is set

        wp_redirect($redirect_url, 301);
        exit();
    }
}
add_action('template_redirect', 'redirect_on_404');

// Add settings page to set custom redirect URL
function redirect_settings_menu() {
    add_menu_page(
        '404 Redirect Settings',
        '404 Redirect',
        'manage_options',
        '404-redirect-settings',
        'redirect_settings_page'
    );
}
add_action('admin_menu', 'redirect_settings_menu');

// Settings page callback
function redirect_settings_page() {
    ?>
    <div class="wrap">
        <h2>404 Redirect Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('redirect_settings_group'); ?>
            <?php do_settings_sections('redirect_settings_group'); ?>
            <label for="404_redirect_url">Redirect URL:</label>
            <input type="text" name="404_redirect_url" id="404_redirect_url" value="<?php echo esc_url(get_option('404_redirect_url', home_url('/'))); ?>">
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register and sanitize settings
function redirect_settings() {
    register_setting('redirect_settings_group', '404_redirect_url', 'esc_url');
    add_settings_section('redirect_settings_section', 'Custom 404 Redirect', 'redirect_settings_section_callback', 'redirect_settings_group');
}
add_action('admin_init', 'redirect_settings');

// Settings section callback
function redirect_settings_section_callback() {
    echo 'Set a custom URL to redirect users when a 404 error occurs.';
}

