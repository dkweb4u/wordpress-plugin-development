<?php

add_action('add_meta_boxes', function () {
    add_meta_box(
        'post_gallery',
        'Post Gallery',
        'post_gallery_callback',
        'post',
        'normal',
        'high'
    );
});

function post_gallery_callback($post)
{
    $gallery = get_post_meta($post->ID, '_post_gallery', true);
    $gallery = is_array($gallery) ? $gallery : [];

    wp_nonce_field('save_post_gallery', 'post_gallery_nonce');
    ?>
    
    <button type="button" class="button" id="add-gallery-images">
        Add Images
    </button>

    <ul id="gallery-container" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:15px;">
        <?php foreach ($gallery as $id) : ?>
            <li data-id="<?php echo $id; ?>">
                <img src="<?php echo wp_get_attachment_image_url($id, 'thumbnail'); ?>" width="100">
                <a href="#" class="remove-image">Delete</a>
                <input type="hidden" name="post_gallery[]" value="<?php echo $id; ?>">
            </li>
        <?php endforeach; ?>
    </ul>

    <?php
}

add_action('save_post', function ($post_id) {

    if (!isset($_POST['post_gallery_nonce']) ||
        !wp_verify_nonce($_POST['post_gallery_nonce'], 'save_post_gallery')) {
        return;
    }

    $gallery = isset($_POST['post_gallery'])
        ? array_map('intval', $_POST['post_gallery'])
        : [];

    update_post_meta($post_id, '_post_gallery', $gallery);

});

add_action('admin_enqueue_scripts', function ($hook) {

    if (!in_array($hook, ['post.php', 'post-new.php'])) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-sortable');

    wp_add_inline_script('jquery-ui-sortable', "

    jQuery(function($){

        $('#gallery-container').sortable();

        $('#add-gallery-images').on('click', function(e){

            e.preventDefault();

            var frame = wp.media({
                title: 'Select Images',
                multiple: true
            });

            frame.on('select', function(){

                var attachments = frame.state()
                    .get('selection')
                    .toJSON();

                attachments.forEach(function(item){

                    $('#gallery-container').append(
                        '<li data-id=\"'+item.id+'\">' +
                        '<img src=\"'+item.sizes.thumbnail.url+'\" width=\"100\">' +
                        '<br>' +
                        '<a href=\"#\" class=\"remove-image\">Delete</a>' +
                        '<input type=\"hidden\" name=\"post_gallery[]\" value=\"'+item.id+'\">' +
                        '</li>'
                    );

                });

            });

            frame.open();

        });

        $(document).on('click','.remove-image',function(e){
            e.preventDefault();
            $(this).closest('li').remove();
        });

    });

    ");
});


// frontend

$gallery = get_post_meta(get_the_ID(), '_post_gallery', true);

if (!empty($gallery)) {

    echo '<div class="gallery">';

    foreach ($gallery as $image_id) {

        echo wp_get_attachment_image(
            $image_id,
            'large'
        );

    }

    echo '</div>';
}
