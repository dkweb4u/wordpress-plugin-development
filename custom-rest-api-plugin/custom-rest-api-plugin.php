<?php
// Plugin Name: Rest Api Plugin
// Description: Plugin for create own api and  CURD Operation
// Version: 2.0
// Author: DkD
// Author URI: getmysite.in 
// Plugin URI: test.com 

if(!defined('WPINC')){
    exit;
}

register_activation_hook(__FILE__,'wcapi_plugin_table');

function  wcapi_plugin_table(){

    global $wpdb;

    $table_name = $wpdb->prefix . 'apitable';

    $collate = $wpdb->get_charset_collate();

    $create_table = 'CREATE TABLE IF NOT EXISTS`'.$table_name.'` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
)'.$collate.'';

include_once(ABSPATH . 'wp-admin/includes/upgrade.php');

dbDelta($create_table);

}

// Action hook of API's Registration

add_action('rest_api_init',function(){

    // Register own Route with GET Request
    register_rest_route('students/v1','students',array(
        'methods' => 'GET',  // key name is methods not method
        'callback' => 'students_api_get_routes',   // response function
/*
        // permistion check
        'permission_callback' => function() {
         return current_user_can('edit_posts'); // check notes.html
         }
*/
        
    ));

    // http://localhost/wordpress/wp-json/students/v1/students

    // --------------------------------------------------------------------

    // Register Route for Insert Data
    register_rest_route('students/v1','student',array(
     'methods' => 'POST',  // key name is methods not method
     'callback' => 'students_insert_data',
     'args' => array(
        'name' => array(
            'type' => 'string',
            'required' => true
        ) ,
        'email'  => array(
            'type' => 'string',
            'required' => true
        ),
        'phone' => array(
            'type' => 'string',
            'required' => true

        ))  // required values and condition
    ));


    // Register Route for PUT Request

   //  (?P<id>\d+)  => () dynamic, ?P parameter, <id> parameter name, \d+ any number

    register_rest_route('students/v1', 'student/(?P<id>\d+)',array(
     'methods' => 'PUT',
     'callback' => 'update_student_data',
     'args' => array(
        'name' => array(
            'type' => 'string',
            'required' => true
        ) ,
        'email'  => array(
            'type' => 'string',
            'required' => true
        ),
        'phone' => array(
            'type' => 'string',
            'required' => true

        ))
    ));


    // DELETE  student data
    register_rest_route('students/v1', 'student/(?P<id>\d+)',array(
     'methods' => 'DELETE',
     'callback' => 'delete_student_data',
    ));

    // GET ONE  student data
    register_rest_route('students/v1', 'student/(?P<id>\d+)',array(
     'methods' => 'GET',
     'callback' => 'get_one_student_data',
    ));



});
// get request response function
function students_api_get_routes(){

    global $wpdb;
    $table_name = $wpdb->prefix . 'apitable';

    $data = $wpdb->get_results('SELECT * FROM '.$table_name, ARRAY_A);


    /*

$data = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM $table_name 
         WHERE name LIKE %s OR email LIKE %s
         ORDER BY id DESC
         LIMIT %d OFFSET %d",
        '%' . $wpdb->esc_like($search) . '%',
        '%' . $wpdb->esc_like($search) . '%',
        $per_page,
        $offset
    ),
    ARRAY_A

    // -------------------------------------------------------

%s → string placeholder

%d → integer placeholder

%f → float placeholder


);
    */

    return wp_send_json(array(
     'status' => 1,
     'message' => 'Hellow DKD',
     'data' => $data
    ));

}
// add students
function students_insert_data($request){

    global $wpdb;

    $table_name = $wpdb->prefix . 'apitable';

    $data = $wpdb->insert($table_name, array(

        'name' => $request->get_param('name'),   // or     $request['name'],
        'email' => $request->get_param('email'),   // or     $request['email'],
        'phone' => $request->get_param('phone'),   // or     $request['phone'],

    ));

    return wp_send_json(array(
     'status' => 1,
     'message' => 'Student added',
     'data' => $wpdb->insert_id   // get last id of row
    ));
}

// update student data
function update_student_data($request){
     global $wpdb;

    $table_name = $wpdb->prefix . 'apitable';

    $data = $wpdb->update($table_name, array(

        'name' => $request->get_param('name'),   // or     $request['name'],
        'email' => $request->get_param('email'),   // or     $request['email'],
        'phone' => $request->get_param('phone'),   // or     $request['phone'],

    ),array('id' =>  $request->get_param('id') ));

    return wp_send_json(array(
     'status' => 1,
     'message' => 'Student updated',
     'data' =>  $data   // get last id of row
    ));
}
// update student data
function delete_student_data($request){
       global $wpdb;
    $table_name = $wpdb->prefix . 'apitable';
   $data =  $wpdb->delete($table_name,array('id' => $request->get_param('id')));

        return wp_send_json(array(
     'status' => 1,
     'message' => 'Student Deleted Successfully',
     'data' => $data
    ));
}
//  get one student

function get_one_student_data($request){
    global $wpdb;
    $table_name = $wpdb->prefix . 'apitable';
    $data = $wpdb->get_row('SELECT * FROM ' . $table_name . ' WHERE id='.$request->get_param('id'));

   return wp_send_json(array(
     'status' => 1,
     'message' => 'Student Data',
     'data' => $data
    ));
}
