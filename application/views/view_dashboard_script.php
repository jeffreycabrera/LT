<script type="text/javascript">
function resetPassword(url){
    $("#otherPopModal").html('<div class="col-sm-12"><img src="<?php echo base_url()."assets/images/ajax-loader.gif";?>" /></div>');
    $('#otherPopModal').dialog({
        dialogClass: "no-close",
        modal: true,
        width: 280,
        title: 'Email Sending',
        buttons:false
    });

    $.ajax({
        type:"POST",
        url:"/index.php/admin_emailing/"+ url,
        data: $('#userForm').serialize(),
        dataType: "JSON",
        success: function(data){
            // if (data.success==true){
            //     $("#otherPopModal").dialog("close");
            //     $("#formModal").dialog("close");
            //     // window.location.href = '/admin_dashboard';
            // }else{
            //     $("#otherPopModal").dialog("close");
            //     $("#otherPopModal").html('<div class="col-sm-12">Email Sending Failed</div>');
            //     errorAction("Email Sending");
            // }
        }
    });
}

// function errorAction(title) {
//     $('#otherPopModal').dialog({
//         dialogClass: "no-close",
//         modal: true,
//         title: title,
//         width: 400,
//         buttons: [
//         {
//             text: "OK",
//             "class": "btn btn-danger",
//             click: function () {
//                 $(this).dialog("close");
//             }
//         }
//         ]
//     });
// }

$(document).ready(function() {
	// build dataTable:
    $('#employeeList').dataTable({
        responsive: true,
        "bLengthChange": false,
        "sPaginationType": "full_numbers",
        "aoColumnDefs": [{'bSortable': false, 'aTargets': [2, 3, 4, 5]}]
    });

    // onClick events:
//     $('.pop-event').click(function () {
//         var data_item = $(this).attr('id'),
//             data_item = data_item.split("_");

//         var action=data_item[0],
//             leave_id=data_item[1];

//         if (action==="createUser" || action==="edit"){
//             var URL = '<?php echo base_url() ?>index.php/employees/load_view';
//         }
//         else if (action==="delete-user") {
//             var URL = '<?php echo base_url() ?>index.php/employees/delete_view';
//         }

//         $.ajax({
//             url: URL, 
//             type: 'POST',
//             dataType:'json',
//             data: {'action': action, 'user_id': leave_id},
//             success: function(response) {
//                 $('#myModal').html(response.html_view);
//                 $('#myModal .modal-title').html(action +' User');
//                 $('#myModal').modal({
//                     backdrop: 'static',
//                     keyboard: false
//                 })
//             }
//         });
//     });
// });

// $("body").on("click", "#submit", function(event){
//     var form_Data = $('#userForm').serializeArray(),
//         form_action = JSON.parse(JSON.stringify(form_Data));

//     if (form_Data[0]['value'] == 'delete-user') {
//         var URL = "/index.php/employees/delete_user";
//     } else {
//         var URL = "/index.php/employees/user_detailManager";
//     }

//     $.ajax({
//         type: "POST",
//         url: URL,
//         data: form_Data,
//         dataType: "JSON",
//         success: function (data) {
//             if (data.succes) {
//                 if (data.act=='create'){
//                     resetPassword('send_email');
//                     $("#myModal").modal('hide');
//                 }else{
//                     $("#myModal").modal('hide');
//                 }
                
//                 //reload the datatable.
//             } else {
//                 $("#otherPopModal").html('<div class="col-sm-12">'+ data.description +'</div>');
//                 errorAction(data.title);
//             }
//         }
//     });
// });
</script>