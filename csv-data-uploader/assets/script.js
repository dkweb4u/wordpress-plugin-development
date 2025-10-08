jQuery(function($){

    $('#csv_plugin_form').submit(function(e){
      e.preventDefault();
/* syntax

      $.ajax({   
        url,
        data,
        dataType,
        method,
        processData,
        contentType,
        success : function(){}
      });

*/

    var formData = new FormData(this);

    formData.append('nonce',CSV_OBJECT.nonce);

    $.ajax(
       {
         url : CSV_OBJECT.ajax_url,
         data : formData,
         dataType: 'json',
         method: 'POST',
         processData: false,
         contentType: false,
         success : function(res){
           if(res.status){
            $('#response').html(res.message).css({color: 'green', padding: '10px',backgroundColor: '#f5f5f5'});
           }
           else{
             $('#response').html(res.message).css({color: 'red', padding: '10px',backgroundColor:'#ff000009'});
           }

            $('#csv_plugin_form')[0].reset();

         },
           error: function(xhr, status, error) {
    console.error("AJAX Error:", status, error);
    $('#response').html('Server error: ' + error)
      .css({ color: 'red', padding: '10px', backgroundColor: '#ff000009' });
  }
       }
    )


    });

});