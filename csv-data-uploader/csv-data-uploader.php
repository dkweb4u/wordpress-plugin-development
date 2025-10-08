<?php
/*
Plugin Name: CSV Data Uploader
Description: Upload to extract csv file data
Author: Dinesh
Version: 1.0
Auther URI: https://ddk.netlify.app/img/cv/resume.pdf
Plugin URI: getmysite.in
*/
// create constaint
define('CSV_PLUGIN_PATH',plugin_dir_path(__FILE__));

add_shortcode('csv_file_data','csv_display_form_data');

function csv_display_form_data(){

    // start PHP Buffer
    ob_start();

    include_once(CSV_PLUGIN_PATH.'/template/csv_form.php'); // put all contents to buffer


   // read buffer
    $template = ob_get_contents(); 

    ob_end_clean(); // clean buffer

    return  $template;

}

// SQL COMMAND GENERATE BY DATABASE

// show create table table_name;

register_activation_hook(__FILE__,'csv_table_create_in_activation');

function csv_table_create_in_activation(){

    global $wpdb;

    $table_prefix = $wpdb->prefix;

    $table_name = $table_prefix . 'csv_plugin_data';

    $table_collate = $wpdb->get_charset_collate();

    $sql_command = "CREATE TABLE IF NOT EXISTS `".$table_name."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `phone` int(30) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `photo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
)". $table_collate."";

require_once(ABSPATH.'wp-admin/includes/upgrade.php');

dbDelta($sql_command);

}

add_action('wp_enqueue_scripts','csv_plugin_script');

function csv_plugin_script(){
    wp_enqueue_script('csv_plugin_script',plugin_dir_url(__FILE__).'assets/script.js',array('jquery'));
    wp_localize_script('csv_plugin_script','CSV_OBJECT',array(
        'ajax_url' => admin_url('admin-ajax.php'),
       'nonce' => wp_create_nonce('csv_safe_token') // generate token with csv_safe_token+sesstion+time
    ));
}
// Capture ajax in wordpress js to php
// wp_ajax_{action input value}
add_action('wp_ajax_csv_file_form_action','csv_form_ajax_capture'); // access only login
add_action('wp_ajax_nopriv_csv_file_form_action','csv_form_ajax_capture'); // access without login

function csv_form_ajax_capture(){

    if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'csv_safe_token')){
        wp_send_json_error(array(
            'status' => 0,
            'message' => 'Security check failed!'
        ));
    }

    // ✅ Check if file was uploaded
    if (!isset($_FILES['csv_data_file']) || $_FILES['csv_data_file']['error'] !== UPLOAD_ERR_OK) {
        wp_send_json_error(array(
            'status' => 0,
            'message' => 'No file uploaded or upload error.'
        ));
    }

    $file = $_FILES['csv_data_file']['tmp_name'];
    $handle = fopen($file, 'r');

    global $wpdb;
    $table_name = $wpdb->prefix . 'csv_plugin_data';

    if ($handle) {
        $row = 0;
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if ($row == 0) {
                $row++;
                continue;
            }

            $wpdb->insert($table_name, array(
                'name'  => $data[1],
                'phone' => $data[2],
                'email' => $data[3],
                'age'   => $data[4],
                'photo' => $data[5]
            ));

            $row++;
        }

        fclose($handle);

        wp_send_json(array(
            'status' => 1,
            'message' => 'Uploaded successfully'
        ));
    }

    wp_send_json_error(array(
        'status' => 0,
        'message' => 'File processing failed'
    ));
    exit;
}
