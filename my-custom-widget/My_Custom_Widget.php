<?php

class My_Custom_Widget extends WP_Widget{

    // construct
   public function __construct()
    {
        parent::__construct(
            "my_custom_widget", // widget unique id
            __("My Custom Widget", 'mytheme'),   // widget title
            array(
                'description' => __("My custom widget creation",'mytheme')
            )
        );
    }
    // display widget
    public function form( $instance ) {

        // print_r($instance);

?>
<p>
    <label for="<?php echo $this->get_field_id('mcw_plugin_title') ?>">Title</label>
    <input type="text" name="<?php echo $this->get_field_name('mcw_plugin_title'); ?>" id="<?php echo $this->get_field_id('mcw_plugin_title') ?>" class="widefat" value="<?php echo $instance['mcw_plugin_title'] ?>">
</p>
<p>
    <label for="<?php echo $this->get_field_id('mcw_plugin_display_type') ?>">Display Type</label>
   <select name="<?php echo $this->get_field_name('mcw_plugin_display_type') ?>" class="widefat mcp_form_select" id="<?php echo $this->get_field_id('mcw_plugin_display_type') ?>">
    <option value="0" <?php if($instance['mcw_plugin_display_type'] == 0) { echo 'selected';} ?>>Recent Posts</option>
    <option value="1" <?php if($instance['mcw_plugin_display_type'] == 1){ echo 'selected' ;} ?>>Static Message</option>
   </select>
</p>
<p class="mcw_recent_posts_widget  <?php if($instance['mcw_plugin_display_type'] != 0) { echo 'hidenow';} ?>">
    <label for="<?php echo $this->get_field_id('mcw_plugin_number') ?>">Number Of Posts</label>
    <input type="number" class="widefat" name="<?php echo $this->get_field_name('mcw_plugin_number') ?>" id="<?php echo $this->get_field_id('mcw_plugin_number') ?>" 
    value="<?php echo $instance['mcw_plugin_number'] ?>" >
</p>

<p class="mcw_static_message <?php if($instance['mcw_plugin_display_type'] == 0) { echo 'hidenow';} ?>">
    <label for="<?php echo $this->get_field_id('mcw_plugin_message') ?>">Static Message</label>
    <input type="text" name="<?php echo $this->get_field_name('mcw_plugin_message') ?>" id="<?php echo $this->get_field_id('mcw_plugin_message') ?>" value="<?php echo $instance['mcw_plugin_message'] ?>" class="widefat">
</p>

<script>
        jQuery(function($){

    $('.mcp_form_select').on('change',function(){
   
        if($(this).val() == 0){
         
          $('.mcw_recent_posts_widget').removeClass('hidenow');
          $('.mcw_static_message').addClass('hidenow');
        }
        else{
          $('.mcw_recent_posts_widget').addClass('hidenow');
          $('.mcw_static_message').removeClass('hidenow');
        }
    });


});
</script>

<?php
    }
    // save widget data
	public function update( $new_instance, $old_instance ) {

        $instance = [];

        $instance['mcw_plugin_title'] = !empty($new_instance['mcw_plugin_title']) ? strip_tags($new_instance['mcw_plugin_title']) : '';

        $instance['mcw_plugin_display_type'] = !empty($new_instance['mcw_plugin_display_type']) ? sanitize_text_field($new_instance['mcw_plugin_display_type']) : 0;

        $instance['mcw_plugin_number'] = !empty($new_instance['mcw_plugin_number']) ? sanitize_text_field($new_instance['mcw_plugin_number']) : 0;

        $instance['mcw_plugin_message'] = !empty($new_instance['mcw_plugin_message']) ? sanitize_text_field($new_instance['mcw_plugin_message']) : '';


        return $instance;


	}
    // display widget to frontend
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
        echo $args['before_title'];
        echo $instance['mcw_plugin_title'];
        echo $args['after_title'];

        if($instance['mcw_plugin_display_type'] != 0){
            echo $instance['mcw_plugin_message'];
        }
        else{

            $number = (int) $instance['mcw_plugin_number'];
            $query = new WP_Query(array(
              'post_type' => 'post',
              'posts_per_page' => $number,
              'post_status' => 'publish'
            ));

            if($query->have_posts() && $query->found_posts > 0){
                while($query->have_posts()){
                      $query->the_post();

                      echo "<p><a href=".get_the_permalink()."> ".get_the_title()."</a></p>";
                }

                wp_reset_postdata();
            }
            else{
               echo "<p>No Post found</p>";
            }


        }

		echo $args['after_widget'];

	}


}