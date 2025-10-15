

<div class="wrap wcp_custom_plugin">
    <h2>Create Product</h2>
    <form id="productForm" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="wcp_name" placeholder="Enter product name" required>
        </div>

        <div class="form-group">
            <label for="regular_price">Regular Price</label>
            <input type="number" id="regular_price" name="wcp_regular_price" placeholder="0.00" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="sale_price">Sale Price</label>
            <input type="number" id="sale_price" name="wcp_sale_price" placeholder="0.00" step="0.01">
        </div>

        <div class="form-group">
            <label for="sku">SKU</label>
            <input type="text" id="sku" name="wcp_sku" placeholder="Enter SKU code">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="wcp_description" placeholder="Full product description"></textarea>
        </div>

        <div class="form-group">
            <label for="short_description">Short Description</label>
            <textarea id="short_description" name="wcp_short_description" placeholder="Brief description"></textarea>
        </div>

        <div class="form-group">
            <label for="image">Product Image</label>
           <button type="button" id="wcp_plugin_product_image">Upload</button>
           <input type="hidden" name="wcp_product_image" id="wcp_product_image_input">
        </div>

        <?php wp_nonce_field('wcp_form_submit_action','wcp_plugin_nonce') ?>

        <button type="submit" name="wcp_form_submit_btn">Submit Product</button>
    </form>
</div>
