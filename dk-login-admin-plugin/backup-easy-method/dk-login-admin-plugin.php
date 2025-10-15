<?php
// Plugin Name: Dk login admin plugin
// Description:  Dk login admin plugin for wordpress wp-admin
// Version: 2.0
// Author: DkD
// Author URI: getmysite.in 
// Plugin URI: test.com 


// | Action | Single Site       | Multisite              | Temporary            |
// | ------ | ----------------- | ---------------------- | -------------------- |
// | Add    | `add_option()`    | `add_site_option()`    | `set_transient()`    |
// | Get    | `get_option()`    | `get_site_option()`    | `get_transient()`    |
// | Update | `update_option()` | `update_site_option()` | `set_transient()`    |
// | Delete | `delete_option()` | `delete_site_option()` | `delete_transient()` |



if(!defined('ABSPATH')){
    exit;
}

add_action('admin_menu','wlp_add_submenu_plugin');

function wlp_add_submenu_plugin(){
    add_submenu_page('tools.php','WP Login Customizer','WP Login Customizer','manage_options','wlp-admin-login-customiser','wlp_submenu_page');
}
function wlp_submenu_page() {
    // Handle POST
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wlp_nonce']) && wp_verify_nonce($_POST['wlp_nonce'], 'wlp_save_option') ) {
        $value = sanitize_text_field($_POST['working-test-max']);
        update_option('checking_my_self', $value);

        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>Settings saved successfully.</p></div>';
        });
    }

    // Load template
    ob_start();
    include_once(plugin_dir_path(__FILE__).'template/login-layout.php');
    echo ob_get_clean();
}