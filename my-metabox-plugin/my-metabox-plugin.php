<?php
// Plugin Name: My Metabox Plugin
// Description:  My Metabox Plugin for block theme editor
// Version: 1.0
// Author: DkD
// Author URI: getmysite.in 
// Plugin URI: test.com 


if(!defined('ABSPATH')){
    exit;
}
// Register meta boxes in pages
add_action('add_meta_boxes','mmp_metabox_register');

function mmp_metabox_register(){

    add_meta_box(
        'mmp_metabox_id',                   // Unique ID
        'Metabox For SEO',                 // Box Title
        'mmp_create_page_metabox',         // Callback function to render HTML
        'page', // or ['post', 'page'],   // Post type (post, page, product, or custom)
        'normal',                         // Context (normal, side, advanced)
        'high'                            // Priority
    );

}

// create layout for metabox in page (Extra options in add new page)
function mmp_create_page_metabox($post){

    ob_start();

    include_once(plugin_dir_path(__FILE__).'template/page-meta-form.php');

    $template = ob_get_contents();

    ob_end_clean();

    echo $template;

}


// save options data in pages include in wordpress

add_action("save_post",'mmp_save_metabox_data_in_page');

function mmp_save_metabox_data_in_page($post_id){

    // check nonce verify

    // wp_verify_nonce($_POST['that field name of nonce'], 'current running function')

    if(!wp_verify_nonce($_POST['mmp_pmetabox_form'], 'mmp_save_metabox_data_in_page')){
         return;
    }

    // avoid auto save data
      if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
        return;
      }

    // ------------------------------------------------


    if(isset($_POST['pmeta-title'])){

        update_post_meta($post_id,'pmeta-title',$_POST['pmeta-title']);
    }

    
    if(isset($_POST['pmeta-decription'])){

        update_post_meta($post_id,'pmeta-decription',$_POST['pmeta-decription']);
    }

    
    if(isset($_POST['pmeta-keywords'])){

        update_post_meta($post_id,'pmeta-keywords',$_POST['pmeta-keywords']);
    }
    
}

add_action('wp_head', 'add_tags_on_header',1);

function add_tags_on_header(){
    if(is_page()){
        global $post;

        $post_id = $post->ID;

    $matatitle = get_post_meta($post_id,'pmeta-title',true); 
    $matades = get_post_meta($post_id,'pmeta-decription',true);
    $matakey = get_post_meta($post_id,'pmeta-keywords',true);

      ?>
       <meta name="description" content="<?php echo $matades;  ?>">
       <title><?php echo $matades;  ?></title>
       <meta name="keywords" content="<?php echo $matakey;  ?>">

      <?php

    }
}