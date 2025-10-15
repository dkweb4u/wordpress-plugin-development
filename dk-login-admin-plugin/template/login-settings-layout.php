<form method="post" action="options.php">
    <?php
        settings_fields('dk_login_login_page_settings_field_group'); 
        do_settings_sections('dk-admin-custom'); 
        submit_button();
    ?>
</form>