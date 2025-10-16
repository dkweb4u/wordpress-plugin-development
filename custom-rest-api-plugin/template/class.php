<?php
/*
Plugin Name: Rest API Plugin (Class Version)
Description: Plugin for creating own API with CRUD operations
Version: 2.0
Author: DkD
*/

if (!defined('WPINC')) {
    exit;
}

class Students_API_Plugin {

    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'apitable';

        // Activation hook to create table
        register_activation_hook(__FILE__, array($this, 'create_table'));

        // REST API init
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    // -----------------------
    // Create Table
    // -----------------------
    public function create_table() {
        global $wpdb;

        $collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table_name}` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(30) NOT NULL,
            `email` varchar(30) NOT NULL,
            `phone` varchar(30) NOT NULL,
            PRIMARY KEY (`id`)
        ) $collate;";

        include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // -----------------------
    // Register REST API Routes
    // -----------------------
    public function register_routes() {

        // GET all students
        register_rest_route('students/v1', 'students', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_students'),
        ));

        // GET one student
        register_rest_route('students/v1', 'student/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_one_student'),
        ));

        // POST - add student
        register_rest_route('students/v1', 'student', array(
            'methods' => 'POST',
            'callback' => array($this, 'insert_student'),
            'args' => array(
                'name' => array('type' => 'string', 'required' => true),
                'email' => array('type' => 'string', 'required' => true),
                'phone' => array('type' => 'string', 'required' => true)
            )
        ));

        // PUT - update student
        register_rest_route('students/v1', 'student/(?P<id>\d+)', array(
            'methods' => 'PUT',
            'callback' => array($this, 'update_student'),
            'args' => array(
                'name' => array('type' => 'string', 'required' => true),
                'email' => array('type' => 'string', 'required' => true),
                'phone' => array('type' => 'string', 'required' => true)
            )
        ));

        // DELETE - delete student
        register_rest_route('students/v1', 'student/(?P<id>\d+)', array(
            'methods' => 'DELETE',
            'callback' => array($this, 'delete_student'),
        ));
    }

    // -----------------------
    // CRUD Methods
    // -----------------------

    // GET all students
    public function get_students() {
        global $wpdb;
        $data = $wpdb->get_results("SELECT * FROM {$this->table_name}", ARRAY_A);

        return wp_send_json(array(
            'status' => 1,
            'message' => 'Students List',
            'data' => $data
        ));
    }

    // GET one student
    public function get_one_student($request) {
        global $wpdb;
        $id = intval($request->get_param('id'));
        $data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id), ARRAY_A);

        return wp_send_json(array(
            'status' => 1,
            'message' => 'Student Data',
            'data' => $data
        ));
    }

    // POST - insert student
    public function insert_student($request) {
        global $wpdb;
        $wpdb->insert($this->table_name, array(
            'name' => $request->get_param('name'),
            'email' => $request->get_param('email'),
            'phone' => $request->get_param('phone')
        ));

        return wp_send_json(array(
            'status' => 1,
            'message' => 'Student added',
            'data' => $wpdb->insert_id
        ));
    }

    // PUT - update student
    public function update_student($request) {
        global $wpdb;
        $id = intval($request->get_param('id'));

        $wpdb->update($this->table_name, array(
            'name' => $request->get_param('name'),
            'email' => $request->get_param('email'),
            'phone' => $request->get_param('phone')
        ), array('id' => $id));

        return wp_send_json(array(
            'status' => 1,
            'message' => 'Student updated'
        ));
    }

    // DELETE - remove student
    public function delete_student($request) {
        global $wpdb;
        $id = intval($request->get_param('id'));

        $wpdb->delete($this->table_name, array('id' => $id));

        return wp_send_json(array(
            'status' => 1,
            'message' => 'Student deleted'
        ));
    }
}

// Initialize the plugin
new Students_API_Plugin();
