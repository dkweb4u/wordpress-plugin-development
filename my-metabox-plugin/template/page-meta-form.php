  <?php

    $post_id = isset($post->ID) ? $post->ID : '';
    $matatitle = get_post_meta($post_id,'pmeta-title',true); 
    $matades = get_post_meta($post_id,'pmeta-decription',true);
    $matakey = get_post_meta($post_id,'pmeta-keywords',true);
       // true means single otherwise its provide array

  ?>

  
  
  
  <p><label for="pmeta-title">Meta Title</label>
   <input type="text" name="pmeta-title"  class="widefat" value="<?php echo   $matatitle ?>">   
    </p>
        <p><label for="pmeta-decription">Meta Description</label>
   <input type="text" name="pmeta-decription"  class="widefat" value="<?php echo   $matades ?>">   
    </p>
        <p><label for="pmeta-keywords">Meta Keywords</label>
   <input type="text" name="pmeta-keywords"  class="widefat" value="<?php echo  $matakey ?>">   
    </p>

    <?php 
//  nonce field for avoid csrf

   wp_nonce_field('mmp_save_metabox_data_in_page', 'mmp_pmetabox_form');
// wp_nonce_field('which function verify that function name', 'unique field name');

?>