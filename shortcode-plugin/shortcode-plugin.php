<?php
// Plugin Name: Shortcode Plugin
// Description: Basic Shortcode
// Version: 1.0
// Author: DkD
// Author URI: getmysite.in 
// Plugin URI: test.com 

// [sample]
add_shortcode('sample', 'codefunction');

function codefunction(){
 return  "<h2 style='color:red;'>Its Come from shortcode</h2>";
}


// [tablestudent name='' email='']
add_shortcode('tablestudent','student_table');

function student_table($atts){
    $atts = shortcode_atts(array(
        'name' => 'Dinesh',
        'email' => 'dk150198@gmail.com'
    ),$atts,'student');


    return "<table><tr><td>".$atts['name']."</td> <td>".$atts['email']."</td></tr></table>";

}

// database data

add_shortcode('dbdatapost','getdbdata');

function getdbdata(){

    global $wpdb;

    $table_prefix = $wpdb->prefix; // wp_
    $table_name = $table_prefix.'posts'; //wp_posts

   $posts =  $wpdb->get_results("SELECT post_title FROM $table_name WHERE post_type='post' AND post_status='publish'");
/*
// insert
   $wpdb->insert(
    $table_name,
    array(
        'post_title' => 'Test Post',
        'post_status' => 'publish'
    ),
    array('%s', '%s') // %s = string, %d = integer, %f = float
);

// prepare

$wpdb->query(
    $wpdb->prepare(
        "INSERT INTO $table_name (post_title, post_type, post_status, post_author, post_date)
        VALUES (%s, %s, %s, %d, %s)",
        'My Custom Post',
        'post',
        'publish',
        1,
        current_time('mysql')
    )
);


// update

$wpdb->update(
    $table_name,
    array(
        'post_title'  => 'Updated Post Title',   // Columns to update
        'post_status' => 'draft'
    ),
    array( 'ID' => 123 ),  // WHERE condition
    array( '%s', '%s' ),   // Data formats for columns
    array( '%d' )          // Data format for WHERE condition
);

// delete

$wpdb->delete(
    $table_name,
    array( 'ID' => 123 ), // WHERE condition
    array( '%d' )         // Data format
);

*/

if(count($posts) > 0){
$outputHtml = '<ul>';

foreach ($posts as $post) {
    $outputHtml .= "<li>". $post->post_title . '</li>';
}

$outputHtml .="</ul>";

return $outputHtml;

}

return "No Post Found";

}


// short code in wp query


// [list_wp_post number='5']
add_shortcode('list_wp_post','getposts_in_wp_query');

function getposts_in_wp_query($atts){
$atts = shortcode_atts(array('number' => 5), $atts, 'list_wp_post');

$query = new WP_Query(array(
'post_per_page' => $atts['number'],
'post_status' =>'publish'
));

if($query->have_posts()){
    $outputHtml = "<ul>";
    while($query->have_posts()){
        $query->the_post();
 $outputHtml .= "<li><a href='".get_the_permalink()."'>".get_the_title()."</a></li>";



    }
     $outputHtml .= "</ul>";

     return  $outputHtml ;
}

return 'No Post found';


}