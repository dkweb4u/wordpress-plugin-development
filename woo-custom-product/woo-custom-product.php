<?php
// Plugin Name: Woocommerce custom product
// Description:  Woocommerce custom product
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
add_action('admin_menu','wordpres_custom_product_menu');
function wordpres_custom_product_menu(){
   add_menu_page('WooCommece Custom Product', 'Woo Custom Product','manage_options','woo-custom-product-page','woo_custom_product_page','dashicons-cart',2);
}

// plugin page
function woo_custom_product_page(){

   ob_start();

   include_once(plugin_dir_path(__FILE__).'template/product-form.php');

   $template = ob_get_contents();

   ob_get_clean();

   echo $template;


}

// assets of plugin 
add_action('admin_enqueue_scripts','add_custom_product_assets');
function add_custom_product_assets(){
   // css
   wp_enqueue_style('wcp_plugin_style',plugin_dir_url(__FILE__).'assets/style.css' );
   
   // wordpress media access
    wp_enqueue_media(); 

   // js

   wp_enqueue_script('wcp_plugin_script',plugin_dir_url(__FILE__).'assets/script.js',array('jquery'),1.0,true);

}

// form submit function

add_action('admin_init','wcp_form_submit_action');

function wcp_form_submit_action(){
   if(isset($_POST['wcp_form_submit_btn']) && isset($_POST['wcp_plugin_nonce']) && wp_verify_nonce($_POST['wcp_plugin_nonce'], 'wcp_form_submit_action' )){
       if(class_exists('WC_Product_Simple')){

         // woocommerce simple product class 

         $simpleProductObj = new WC_Product_Simple();

          $simpleProductObj->set_name($_POST['wcp_name']);
          $simpleProductObj->set_sku($_POST['wcp_sku']);
          $simpleProductObj->set_regular_price($_POST['wcp_regular_price']);
          $simpleProductObj->set_sale_price($_POST['wcp_sale_price']);
          $simpleProductObj->set_description($_POST['wcp_description']);
          $simpleProductObj->set_short_description($_POST['wcp_short_description']);
          $simpleProductObj->set_status('publish');
          $simpleProductObj->set_image_id($_POST['wcp_product_image']);
           
         $product_id =  $simpleProductObj->save();

         add_action('admin_notices',function() use ($product_id){
         echo '<div class="notice notice-success is-dismissible"><p>'.$product_id.'Product Added Successfully</p></div>';
         });
       }
   }
}