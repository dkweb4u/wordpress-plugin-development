jQuery(function($){

loadEmpdata();

 $('#wpEmpForm').validate(); 
//  form submit
 $('#wpEmpForm').on('submit',function(e){

    e.preventDefault();

        if (!$(this).valid()) {
        return; // stop AJAX if form is invalid
    }

    var formdata = new FormData(this);

    $.ajax({
        url: EMP.ajax_url,
        method:'POST',
        data: formdata,
        dataType:'json',
        contentType: false,
        processData: false,
        success: function(res){
               if(res.status == 1){
                alert(res.message);
                 $('#wpEmpForm')[0].reset();
                 loadEmpdata();
               }
            },
        error: function(err){
                console.log(err);
            }
    });

 });

 function loadEmpdata(){
    $.ajax({
        url:EMP.ajax_url,
        data:{
            action: 'get_emp_full_data'  // make this to wp_ajax_
        },
        dataType: 'json',
        method: 'GET',
        success: function(res){
            console.log(res);
            var emp = '';
           $.each(res.data, function(i,data){

            if(data.profile_img){
                   var img = "<img src='" + data.profile_img +"'/>";
            }
            else{
                var img = "No Image";
            }


                emp += `<tr><td>${i+1}</td><td>${data.name}</td><td>${data.email}</td><td>${data.designation}</td><td>${img}</td>
                <td>
                <button data-id='${data.id}' class="edit-btn">Edit</button>
                <button data-id='${data.id}'  class="delete-btn">Delete</button>
                 </td>
                </tr>`;
            });

            $("#table_emp_data").html(emp);
        },
        complete: function(){
          $('.edit-btn').each(function(){
              $(this).on('click',function(){
                editEmpfunction($(this).data('id'));
                 $('.edit_emp_form').removeClass('d-none');
              });
          });
        }
    });
 }

//  edit function
function editEmpfunction(id){

     $.ajax({
        url: EMP.ajax_url,
        data: {
            action: 'fetch_single_emp',
            emp_id: id
        },
        method: 'GET',
        dataType: 'json',
        success: function(res){
           console.log($('.edit_emp_form #name'));
           $('.edit_emp_form #name').val(res.data.name);
           $('.edit_emp_form #email').val(res.data.email);
           $('.edit_emp_form #designation').val(res.data.designation);
           $('.edit_emp_form #profile').val(res.data.profile);
           $('.edit_emp_form #emp_id').val(res.data.id);

        }
     });
}

// delete function
 $(document).on('click','.delete-btn',function(){
    if(confirm("Are You Sure To Delete")){

        var empID = $(this).data('id');

     $.ajax({
        url: EMP.ajax_url,
        data: {
            action: 'emp_delete_data',
            emp_id: empID
        },
        method: 'GET',
        dataType: 'json',
        success: function(res){
            alert(res.message);
             loadEmpdata();
        }
     });
    }
 });

// update emp data
 $('#editEmpForm').validate(); 
//  form submit
 $('#editEmpForm').on('submit',function(e){

    e.preventDefault();

        if (!$(this).valid()) {
        return; // stop AJAX if form is invalid
    }

    var formdata = new FormData(this);

    $.ajax({
        url: EMP.ajax_url,
        method:'POST',
        data: formdata,
        dataType:'json',
        contentType: false,
        processData: false,
        success: function(res){
               if(res.status == 1){
                alert(res.message);
                 $('#wpEmpForm')[0].reset();
                 loadEmpdata();
               }
            },
        error: function(err){
                console.log(err);
            }
    });

 });


//  trigger btn
$('.add_new_emp_btn').on('click',function(){
$('.add_emp_form').toggleClass('d-none');
if($('.add_new_emp_btn').text() == 'Close'){
    $('.add_new_emp_btn').text('Add New');
}
else{
$('.add_new_emp_btn').text('Close');
}

});


});