<?php
// Plugin Name: Woo CSV Product Uploader
// Description:  Woocommerce CSV Product Uploader
// Version: 1.0
// Author: DkD
// Author URI: getmysite.in 
// Plugin URI: test.com 


if(!defined('ABSPATH')){
    exit;
}

// show error to woocommerce plugin required
if(!in_array('woocommerce/woocommerce.php',get_option('active_plugins'))){
    add_action('admin_notices',function(){
     echo '<div class="notice notice-error is-dismissible"><p>Woocommerce Plugin Required</p></div>';
    });       
}

// admin menu
add_action('admin_menu','wcsv_csv_uploader_menu');
function wcsv_csv_uploader_menu(){
   add_menu_page('WooCommece CSV Product uploader', 'WooCommece CSV Product uploader','manage_options','woo-csv-product-uploader-page','woo_csv_product_uploader_page','dashicons-database-export',2);
}

function woo_csv_product_uploader_page(){
   ob_start();

   include_once(plugin_dir_path(__FILE__)."template/import-form.php");

   $template = ob_get_contents();

   ob_end_clean();

   echo $template;


}

add_action('admin_init','wcsv_product_uploader_action');

function wcsv_product_uploader_action(){
    if(isset($_POST['csv_file_uploader_woocommerce'])){

        if(wp_verify_nonce($_POST['wcsv_nonce'], 'wcsv_product_uploader_action')){

            if(isset($_FILES['product_csv_data']['name']) && !empty($_FILES['product_csv_data']['name'])){

                $file = $_FILES['product_csv_data']['tmp_name'];

                $filedata = fopen($file,'r');

                $row = 0;

                while(($data = fgetcsv($filedata, 1000,',')) !== FALSE){

                    if($row == 0){
                       $row++;
                       continue;
                    }

        // woocommerce simple product class 

          $simpleProductObj = new WC_Product_Simple();

          $simpleProductObj->set_name($data[0]);
          $simpleProductObj->set_sku($data[1]);
          $simpleProductObj->set_regular_price($data[2]);
          $simpleProductObj->set_sale_price($data[3]);
          $simpleProductObj->set_description($data[4]);
          $simpleProductObj->set_short_description($data[5]);
        //   $simpleProductObj->set_image_id($data[6]);
          $simpleProductObj->set_status('publish');
           
         $product_id =  $simpleProductObj->save();

  add_action('admin_notices',function() use ($product_id){
                      echo '<div class="notice notice-success is-dismissible"><p> '.$product_id.' upload CSV data successfully</p></    div>';
                 }); 
         

                }
                    
            }
            else{
                    add_action('admin_notices',function(){
                      echo '<div class="notice notice-error is-dismissible"><p>please upload CSV File</p></    div>';
                 }); 
            }


        }


    }
}