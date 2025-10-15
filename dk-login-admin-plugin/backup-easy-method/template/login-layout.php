<form action="" method="post">
    <?php

// settings_fields('dk_login_admin_fields');

// do_settings_sections('dk_login_admin_customise');
echo "<h2>Working Well</h2>";
echo "<input type='text' name='working-test-max' value='".get_option('checking_my_self',true)."'>";
wp_nonce_field('wlp_save_option', 'wlp_nonce'); 
submit_button('Save Settings');

?>
</form>