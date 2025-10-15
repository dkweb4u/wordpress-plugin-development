jQuery(document).ready(function($){
    $('#wcp_plugin_product_image').on('click',function(){
     
        // media access for button
        var fileInfo = wp.media({
            title: "Select Product Image",
            multiple: false    // if multiple files add true 
        }).open().on('select',function(){

        //   single images  ---------------------------------------------------------------------
            
          var uploadFile = fileInfo.state().get('selection').first();  // get only one image

           var fileObj = uploadFile.toJSON();

        //    console.log(fileObj);


            $('#wcp_product_image_input').val(fileObj.id);  // id pass the woo product


          $('#wcp_plugin_product_image').parent().append(`<img src ="${fileObj.url}" alt="${fileObj.alt}" style="height: 100px; width: 100px;"/>`);

       

        //   multiple images  ---------------------------------------------------------------------
/*

          var uploadFile = fileInfo.state().get('selection');  // multiple or all images

           var fileObj = uploadFile.toJSON();

           console.log(fileObj);


           $.each(fileObj, function(i,img){
                $('#wcp_plugin_product_image').parent().append(`<img src ="${img.url}" alt="${img.alt}" style="height: 100px; width: 100px;"/>`);
            });

*/




        })

    });
});