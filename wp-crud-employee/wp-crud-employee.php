<?php
// Plugin Name: WP Employee Plugin
// Description:  WP Employee Plugin with CRUD operation
// Version: 1.0
// Author: DkD
// Author URI: getmysite.in 
// Plugin URI: test.com 


if(!defined('ABSPATH')){
    exit;
}

define('WEMP_PATH', plugin_dir_path(__FILE__));
define('WEMP_URL', plugin_dir_url(__FILE__));


include_once(WEMP_PATH . 'MyEmployees.php');

$empObject = new MyEmployees;

// create db table
register_activation_hook(__FILE__,[$empObject , 'createEmpTable']);

// register_activation_hook(run file, if class means [classobject, method name] );
register_deactivation_hook(__FILE__,[$empObject , 'dropEmpTable']);

// add short code emp form
add_shortcode('wp_emp_form', [$empObject, 'short_code_emp_form']);

add_action('wp_enqueue_scripts', [$empObject, 'wp_plugin_add_assets']);

// process of ajex request
add_action('wp_ajax_wp_emp_plugin_action',[$empObject, 'handle_form_emp_add']);

// load or fetch emp data
add_action('wp_ajax_get_emp_full_data',[$empObject,'fetchEmpData']);

// Delete emp data
add_action('wp_ajax_emp_delete_data',[$empObject,'deleteEmpData']);

// Edit emp data
add_action('wp_ajax_fetch_single_emp',[$empObject,'editEmpData']);

// update emp data
add_action('wp_ajax_wp_emp_plugin_edit_action',[$empObject,'updateEmpData']);
