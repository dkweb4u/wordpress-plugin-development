<?php
// Plugin Name: Backup Table 
// Description:  Backup Table  in CSV Format
// Version: 1.0
// Author: DkD
// Author URI: getmysite.in 
// Plugin URI: test.com 

// admin menu

use SimplePie\Sanitize;

add_action('admin_menu','tbcsv_page');

function tbcsv_page(){
    add_menu_page('CSV Table Backup', 'CSV Backup', 'manage_options', 'csv-table-backup', 'csv_plugin_page_backup' ,'dashicons-database-export', 8);
}
// page layout
function csv_plugin_page_backup(){

    ob_start();

    include_once(plugin_dir_path(__FILE__).'/template/form.php');
 
 
    $template = ob_get_contents(); 


    ob_end_clean();

echo  $template;
}

// click to export

add_action('admin_init','export_table_data_csv');

function export_table_data_csv(){
if(isset($_POST['tbcsv_button'])){
        global $wpdb;

    $table_name = sanitize_text_field($_POST['csv_table']);

    $data = $wpdb->get_results(
        "SELECT * FROM {$table_name}",ARRAY_A
    );

    if(empty($data)){
    // wp_redirect(admin_url('admin.php?page=csv-table-backup&csv_export=empty'));
            exit;
    }

    $filename = 'csv-table-data-'.time().'.csv';

    header("Content-Type: text/csv; charset=utf-8;");
    header('Content-Disposition: attachment; filename='.$filename);

    $output = fopen("php://output","w");

    fputcsv($output,array_keys($data[0]));

    foreach ($data as $value) {
       fputcsv($output,$value);
    }

    fclose($output);


exit;

}

}

add_action('admin_notices', function(){
    if(isset($_GET['csv_export'])){
        if($_GET['csv_export'] == 'success'){
            echo '<div class="notice notice-success is-dismissible"><p>CSV exported successfully!</p></div>';
        } elseif($_GET['csv_export'] == 'empty'){
            echo '<div class="notice notice-error is-dismissible"><p>Table is empty, no CSV exported.</p></div>';
        }
    }
});