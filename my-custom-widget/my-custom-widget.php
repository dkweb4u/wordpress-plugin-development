<?php
// Plugin Name: My Custom Widget
// Description:  My Custom Widget
// Version: 1.0
// Author: DkD
// Author URI: getmysite.in 
// Plugin URI: test.com 


if(!defined('ABSPATH')){
exit;
}


add_action('widgets_init','mcw_register_widget');

include_once(plugin_dir_path(__FILE__)."/My_Custom_Widget.php");

function mcw_register_widget(){    

register_widget("My_Custom_Widget");

}
// add admin script
add_action('admin_enqueue_scripts', 'mcp_custom_script');

function mcp_custom_script() {

    wp_enqueue_style('mcp_style',plugin_dir_url(__FILE__) . 'css/style.css');

    wp_enqueue_script(
        'mcp_function_script',                            // Handle
        plugin_dir_url(__FILE__) . 'js/script.js',       // Correct path
        array('jquery')                           // Dependencies
    );
}



// =====================================================================================
//  add widget support in your theme
// Register widget area
// function mytheme_widgets_init() {
//     register_sidebar( array(
//         'name'          => __( 'Main Sidebar', 'mytheme' ),
//         'id'            => 'main-sidebar',
//         'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'mytheme' ),
//         'before_widget' => '<div id="%1$s" class="widget %2$s">', // wrapper start
//         'after_widget'  => '</div>', // wrapper end
//         'before_title'  => '<h3 class="widget-title">', // widget title start
//         'after_title'   => '</h3>', // widget title end
//     ) );
// }
// add_action( 'widgets_init', 'mytheme_widgets_init' );

//  if ( is_active_sidebar( 'main-sidebar' ) ) {
//     <aside id="sidebar" class="widget-area">
//      dynamic_sidebar( 'main-sidebar' );
//     </aside>
//  } 
// -------------------------------------------------------------------------------
// Multiple sidebars
// register_sidebar( array(
//     'name' => __( 'Footer Widget 1', 'mytheme' ),
//     'id'   => 'footer-1',
// ) );
// <?php dynamic_sidebar( 'footer-1' );
// =======================================================================================