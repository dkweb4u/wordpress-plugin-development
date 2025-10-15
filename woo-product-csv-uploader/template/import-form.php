
<h2>WooCommerce CSV Importer</h2>
<form action="" method="POST" enctype="multipart/form-data">
    <p>
        <label for="">Upload CSV File</label>
        
    </p>
    <p>
        <input type="file" name="product_csv_data">
    </p>
    <?php
    wp_nonce_field('wcsv_product_uploader_action','wcsv_nonce');
    submit_button('Upload','primary','csv_file_uploader_woocommerce');
    
    ?>
</form>