<?php
/*
* Plugin Name: Hellow World
* Description: Learning with wordpress plugin
* Author: DkD
* Version: 1.0
* Author URI: https://getmysite.in
* Plugin URI: test.com 
*/

add_action('admin_notices','hw_basic_plugin');

function hw_basic_plugin(){
   echo '<div class="notice notice-success is-dismissible"><p>Hello, Its saved successfully</p></div>';
   echo '<div class="notice notice-error is-dismissible"><p>Something wrong</p></div>';

   echo '<div class="notice notice-info is-dismissible"><p>Information notice</p></div>';

   echo '<div class="notice notice-warning is-dismissible"><p>Warning Notice</p></div>';

}

add_action('wp_dashboard_setup','hellow_world_setup');

function hellow_world_setup(){
    wp_add_dashboard_widget('hello-dk', 'DkD Widget', 'showmenowdk');
}

function showmenowdk(){
    echo "Hello Dinesh, ".date('Y')." How are You?";
}