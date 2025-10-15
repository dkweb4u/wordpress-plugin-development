<?php
// Plugin Name: Dk login admin plugin
// Description:  Dk login admin plugin for wordpress wp-admin
// Version: 2.0
// Author: DkD
// Author URI: getmysite.in 
// Plugin URI: test.com 

if(!defined('ABSPATH')){
    exit;
}
add_action('admin_menu', 'dk_admin_menu_custom');
function dk_admin_menu_custom(){
    add_submenu_page('tools.php', 'DK Admin Customiser', 'Dk Admin custom', 'manage_options', 'dk-admin-custom', 'dk_admin_custom_page');
}

function dk_admin_custom_page(){
    ?>
    <div class="wrap">
        <h1>Login Page Customiser</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields('dk_login_login_page_settings_field_group');
                do_settings_sections('dk-admin-custom');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_init', 'dk_admin_login_page_field_register');
function dk_admin_login_page_field_register(){
    register_setting('dk_login_login_page_settings_field_group', 'dk_admin_text_color');
    register_setting('dk_login_login_page_settings_field_group', 'dk_admin_background_color');
    register_setting('dk_login_login_page_settings_field_group', 'dk_admin_login_logo');

    add_settings_section('dk_admin_login_page_settings_id', 'Login Page Customiser', null, 'dk-admin-custom');

    add_settings_field('dk_admin_text_color', 'Page Text Color', 'dk_admin_text_color_layout', 'dk-admin-custom', 'dk_admin_login_page_settings_id');
    add_settings_field('dk_admin_background_color', 'Page Background Color', 'dk_admin_background_color_layout', 'dk-admin-custom', 'dk_admin_login_page_settings_id');
    add_settings_field('dk_admin_login_logo', 'Page Logo', 'dk_admin_logo_layout', 'dk-admin-custom', 'dk_admin_login_page_settings_id');
}

function dk_admin_text_color_layout(){
    $value = get_option('dk_admin_text_color');
    echo "<input type='text' name='dk_admin_text_color' value='" . esc_attr($value) . "'>";
}

function dk_admin_background_color_layout(){
    $value = get_option('dk_admin_background_color');
    echo "<input type='text' name='dk_admin_background_color' value='" . esc_attr($value) . "'>";
}

function dk_admin_logo_layout(){
    $value = get_option('dk_admin_login_logo');
    echo "<input type='text' name='dk_admin_login_logo' value='" . esc_attr($value) . "'>";
}


// custom login setting page

add_action('login_enqueue_scripts','dk_login_custom_update');

function dk_login_custom_update(){
      $text_color = get_option('dk_admin_text_color');
      $bg_color = get_option('dk_admin_background_color');
      $logo = get_option('dk_admin_login_logo');

      ?>
<style>
.login h1 a{
        background-image: none, url('<?=$logo?>') !important;
        background-size: contain !important;
    }
</style>
      <?php

}