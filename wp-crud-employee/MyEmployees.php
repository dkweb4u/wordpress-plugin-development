<?php

class MyEmployees
{

    private $wpdb;
    private $table_name;
    private $table_prefix;

    public function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;
        $this->table_prefix = $this->wpdb->prefix;
        $this->table_name = $this->table_prefix . "employess_table";

    }

    public function createEmpTable()
    {
        $table_collate = $this->wpdb->get_charset_collate();

        $create_command = "CREATE TABLE IF NOT EXISTS `" . $this->table_name . "` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) " . $table_collate . "";

        // run sql need below file

        require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

        dbDelta($create_command);

        $this->createPage();
    }
// drop table
    public function dropEmpTable(){
        $delete_command = "DROP TABLE IF EXISTS ". $this->table_name; 

        $this->wpdb->query($delete_command);
    }

// shortcode of emp form
public function short_code_emp_form(){

ob_start();

include_once(plugin_dir_path(__FILE__).'template/emp-form.php');
$template = ob_get_contents();
ob_end_clean();

return $template;
}

public function createPage(){
    $page_title = "My Employee CRUD operation";
    $page_content = "[wp_emp_form]"; // shortcode 

    if(!get_page_by_title($page_title)){
        wp_insert_post(array(
            'post_title' => $page_title,
            'post_content' => $page_content,
            'post_type'=> 'page',
            'post_status' => 'publish'

        ));

//         | Function                                 | Purpose                                     | Notes                                 |
// | ---------------------------------------- | ------------------------------------------- | ------------------------------------- |
// | `wp_insert_post($postarr)`               | Insert a post or page (or custom post type) | Returns the post ID on success        |
// | `wp_update_post($postarr)`               | Update an existing post                     | Needs `ID` in `$postarr`              |
// | `wp_delete_post($postid, $force_delete)` | Delete a post                               | `$force_delete = true` bypasses trash |
// | `wp_trash_post($postid)`                 | Move post to trash                          | Posts can be restored                 |
// | `wp_publish_post($postid)`               | Publish a post programmatically             | Only works if post exists             |

    }

}

public function wp_plugin_add_assets(){

wp_enqueue_script('emp_form_validate_script', WEMP_URL.'assets/validate.js', array('jquery'),'2.0.1', true);
wp_enqueue_script('emp_script', WEMP_URL.'assets/script.js', array('jquery','emp_form_validate_script'), null, true);

wp_localize_script('emp_script','EMP',array(
    'ajax_url' => admin_url('admin-ajax.php')
));

    wp_enqueue_style('emp_style',WEMP_URL.'assets/style.css');
}


public function handle_form_emp_add(){

    if(!isset($_POST['wp_emp_nonce']) || !wp_verify_nonce($_POST['wp_emp_nonce'], 'wp_emp_plugin_action')){
        wp_send_json_error(['message' => 'Invalid request']);
        wp_die();
    }

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_text_field($_POST['email']);
    $designation= sanitize_text_field($_POST['designation']);

    $profile_url = '';

    if(isset($_FILES['profile']['name'])){
        $file = wp_handle_upload($_FILES['profile'],array('test_form'=>false));

        $profile_url = $file['url'];
    }
    

   $emp_id =  $this->wpdb->insert($this->table_name,array(
        'name' => $name,
        'email' => $email,
        'designation' => $designation,
        'profile_img'=>$profile_url
    ));

    if($emp_id == 1){
   wp_send_json(array(
        'status' => 1,
        'message' => 'Employee Added!',
        'data' => $_POST
    ));
    }
    else{
           wp_send_json(array(
        'status' => 0,
        'message' => 'Something wrong',
    ));
    }
 
    die;
}

public function fetchEmpData(){
   $datas =  $this->wpdb->get_results("SELECT * FROM ". $this->table_name,  ARRAY_A) ;

   wp_send_json(array(
    'status' => 1,
    'data' =>  $datas
   ));

   wp_die();

}

public function deleteEmpData(){

   $emp =  sanitize_text_field($_GET['emp_id']);

   $empdata = $this->wpdb->get_row(
    $this->wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $emp),
    ARRAY_A);


$upload_dir = wp_upload_dir();  // gives array with 'basedir' and 'baseurl'
    $file_url = $empdata['profile_img'];
    $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $file_url);

if (file_exists($file_path)) {
    unlink($file_path);
}

   $this->wpdb->delete($this->table_name, array('id' => $emp)) ;

   wp_send_json(array(
    'status' => 1,
    'message' =>  "Deleted Successfully"
   ));

   wp_die();

}
public function editEmpData(){

   $emp =  sanitize_text_field($_GET['emp_id']);

   if($emp > 0){

   $empdata = $this->wpdb->get_row("SELECT * FROM {$this->table_name} WHERE id = {$emp}",ARRAY_A) ;

   wp_send_json(array(
    'status' => 1,
    'data' => $empdata
   ));
   }


   wp_die();

}
public function updateEmpData(){
        if(!isset($_POST['wp_emp_nonce']) || !wp_verify_nonce($_POST['wp_emp_nonce'], 'wp_emp_plugin_edit_action')){
        wp_send_json_error(['message' => 'Invalid request']);
        wp_die();
        }


    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_text_field($_POST['email']);
    $designation= sanitize_text_field($_POST['designation']);
    $id = sanitize_text_field($_POST['emp_id']);

    $profile_url = '';

    if(isset($_FILES['profile']['name'])){
        $file = wp_handle_upload($_FILES['profile'],array('test_form'=>false));

        $profile_url = $file['url'];
    }
    

   $emp_id =  $this->wpdb->update($this->table_name,array(
        'name' => $name,
        'email' => $email,
        'designation' => $designation,
        'profile_img'=>$profile_url
    ),array('id'=>$id));

    if($emp_id == 1){
   wp_send_json(array(
        'status' => 1,
        'message' => 'Employee Updated!',
        'data' => $_POST
    ));
    }
    else{
           wp_send_json(array(
        'status' => 0,
        'message' => 'Something wrong',
    ));
    }

    wp_die();

}




}
