<script>

$(document).ready(function () {
    function sendMail() {
        $.ajax({
            type:"POST",
            url:"/index.php/admin_emailing/leaveApplication",
            data: $('#leaveForm').serialize(),
            dataType: "JSON",
            success: function(data){
                
            }
        });
    }

    var table = $('#leaves').DataTable({
        responsive: true,
        "bLengthChange": false,
        "sPaginationType": "full_numbers",
        "aoColumnDefs": [{ 'bSortable': false, 'aTargets': [6] }]
    });

    $('body').on('click', '#submit', function(event){

    var form_Data = $('#leaveForm').serializeArray(),
        form_action = JSON.parse(JSON.stringify(form_Data));
    if (form_Data[0]['value']=='delete') {
        var URL = "/personal/cancel_leave";
    } else {
        var URL = "/personal/process_leave";
    }
   
    $.ajax({    
        url: URL,
        type: "POST",
        dataType: 'JSON',
        data: form_action,
        success: function (data) {

            if (form_Data[0]['value']=='delete') {
                table
                    .row($('.selected1').parents('tr'))
                    .remove()
                    .draw(false);
                $("#myModal").modal('hide');
            } else {
                if (data.succes == false) {
                    if (form_Data[2]['value'] == "") {
                        $("input[name='startDate']").css('border-color', 'red');
                    }

                    if (form_Data[3]['value'] == "") {
                        $("input[name='endDate']").css('border-color', 'red');
                    }

                    if (form_Data[4]['value'] == "") {
                        $("textarea[name='reason']").css('border-color', 'red');
                    }
                    
                    if (form_Data[4]['value'].length < 5) {
                        $("textarea[name='reason']").css('border-color', 'red');
                    }
                    return false;
                } 

                if (form_action[0]['value'] == 'requestLeave') {
                    
                
                    if (form_action[4]['name'] == 'halfday' && form_action[5]['name'] == 'LWOP') {
                        table.row.add( [
                            data.dateFiled,
                            form_action[2]['value'],
                            form_action[3]['value'],
                            form_action[6]['value'],
                            'Yes',
                            'Pending',
                            '<button id="edit_'+data.table_id[0]['LeaveTableID']+'" class="btn btn-primary pop-event" type="button">Edit</button> <button id="delete_'+data.table_id[0]['LeaveTableID']+'" class="btn btn-danger pop-event" type="button">Delete</button>'
                        ]).draw(false);
                    } else if (form_action[4]['name'] == 'halfday') {
                        table.row.add( [
                            data.dateFiled,
                            form_action[2]['value'],
                            form_action[3]['value'],
                            form_action[5]['value'],
                            'No',
                            'Pending',
                            '<button id="edit_'+data.table_id[0]['LeaveTableID']+'" class="btn btn-primary pop-event" type="button">Edit</button> <button id="delete_'+data.table_id[0]['LeaveTableID']+'" class="btn btn-danger pop-event" type="button">Delete</button>'
                        ]).draw(false);
                    } else if (form_action[4]['name'] == 'LWOP') {
                        table.row.add( [
                            data.dateFiled,
                            form_action[2]['value'],
                            form_action[3]['value'],
                            form_action[5]['value'],
                            'Yes',
                            'Pending',
                            '<button id="edit_'+data.table_id[0]['LeaveTableID']+'" class="btn btn-primary pop-event" type="button">Edit</button> <button id="delete_'+data.table_id[0]['LeaveTableID']+'" class="btn btn-danger pop-event" type="button">Delete</button>'
                        ]).draw(false);
                    } else {
                        table.row.add( [
                            data.dateFiled,
                            form_action[2]['value'],
                            form_action[3]['value'],
                            form_action[4]['value'],
                            'No',
                            'Pending',
                            '<button id="edit_'+data.table_id[0]['LeaveTableID']+'" class="btn btn-primary pop-event" type="button">Edit</button> <button id="delete_'+data.table_id[0]['LeaveTableID']+'" class="btn btn-danger pop-event" type="button">Delete</button>'
                        ]).draw(false);
                    }  
                    sendMail();
                    $("#myModal").modal('hide');

                } else { // edit

                    if (form_action[4]['name'] == 'halfday' && form_action[5]['name'] == 'LWOP') {
                        
                        table
                            .row($('.selected1').parents('tr'))
                            .remove()
                            .draw(false);

                        table.row.add( [
                            data.dateFiled.DateFiled,
                            form_action[2]['value'],
                            form_action[3]['value'],
                            form_action[6]['value'],
                            'Yes',
                            data.dateFiled.Status,
                            '<button id="edit_'+form_action[1]['value']+'" class="btn btn-primary pop-event" type="button">Edit</button> <button id="delete_'+form_action[1]['value']+'" class="btn btn-danger pop-event" type="button">Delete</button>'
                        ]).draw(false);
                    } else if (form_action[4]['name'] == 'halfday') {
                        
                        table
                            .row($('.selected1').parents('tr'))
                            .remove()
                            .draw(false);

                        table.row.add( [
                            data.dateFiled.DateFiled,
                            form_action[2]['value'],
                            form_action[3]['value'],
                            form_action[5]['value'],
                            'No',
                            data.dateFiled.Status,
                            '<button id="edit_'+form_action[1]['value']+'" class="btn btn-primary pop-event" type="button">Edit</button> <button id="delete_'+form_action[1]['value']+'" class="btn btn-danger pop-event" type="button">Delete</button>'
                        ]).draw(false);
                    } else if (form_action[4]['name'] == 'LWOP') {
                        
                        table
                            .row($('.selected1').parents('tr'))
                            .remove()
                            .draw(false);

                        table.row.add( [
                            data.dateFiled.DateFiled,
                            form_action[2]['value'],
                            form_action[3]['value'],
                            form_action[5]['value'],
                            'Yes',
                            data.dateFiled.Status,
                            '<button id="edit_'+form_action[1]['value']+'" class="btn btn-primary pop-event" type="button">Edit</button> <button id="delete_'+form_action[1]['value']+'" class="btn btn-danger pop-event" type="button">Delete</button>'
                        ]).draw(false);
                    } else {
                        
                        table
                            .row($('.selected1').parents('tr'))
                            .remove()
                            .draw(false);

                        table.row.add( [
                            data.dateFiled.DateFiled,
                            form_action[2]['value'],
                            form_action[3]['value'],
                            form_action[4]['value'],
                            'No',
                            data.dateFiled.Status,
                            '<button id="edit_'+form_action[1]['value']+'" class="btn btn-primary pop-event" type="button">Edit</button> <button id="delete_'+form_action[1]['value']+'" class="btn btn-danger pop-event" type="button">Delete</button>'
                        ]).draw(false);
                    }  
                    sendMail();
                    $("#myModal").modal('hide');
                }
            }   
        }
    });
});


});

$('#leaves, .requestLeave').on('click', '.pop-event', function(event){

    var data_item = $(this).attr('id');
    data_item = data_item.split("_");

    $(this).addClass('selected1');

    var action = data_item[0];
    var table_id = data_item[1];

    if (action == 'requestLeave' || action == 'edit') {
       var URL = '/personal/load_leaveRequestForm'; 
    } else if (action == 'delete') {
        var URL = '/personal/load_delete';
    } else {
        var URL = '/personal/load_details';
    }

    $.ajax({
        url: URL, 
        type: 'POST',
        dataType:'json',
        data: {'action' : action, 'table_id' : table_id},
        success: function(response) {
            $('#myModal').html(response.html_view);
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    });
});



    // function resetForm(){
    //     $("#startDate").val("");
    //     $("#endDate").val("");
    //     $("#halfday").prop('checked', false);
    //     $("#reason").val("");
    //     $("#LWOP").prop('checked', false);
    // }

    // function popModal(action, id) {
    //     if (action=="edit"){
    //         action = "update";
    //         $.get("/index.php/personal/edit_leave/" + id, function (data) {
    //             var leave = JSON.parse(data);
    //             $("#startDate").val(leave.StartDate);
    //             $("#endDate").val(leave.EndDate);
    //             $('#halfday').removeAttr('disabled');
    //             $("#halfday").prop('checked', leave.HalfDay == 1 ? true : false);
    //             $("#reason").val(leave.Purpose);
    //             $("#LWOP").prop('checked', leave.LWOP == 1 ? true : false);
    //         });
    //     }else if (action=="detail"){
    //         $.get("/index.php/personal/edit_leave/" + id, function (data) {
    //             var leave = JSON.parse(data);
    //             $("#detail-startDate").html(leave.StartDate);
    //             $("#detail-dateEnd").html(leave.EndDate);

    //             if (leave.Status==2){
    //                 $("#detail-status").html("Accepted");
    //             }else if(leave.Status==1){
    //                 $("#detail-status").html("Processing..");
    //             }else{
    //                 $("#detail-status").html("Declined");
    //             }
                
    //             $("#detail-halfday").prop('checked', leave.HalfDay == 1 ? true : false);
    //             $("#detail-reason").html(leave.Purpose);
    //             $("#detail-LWOP").prop('checked', leave.LWOP == 1 ? true : false);
				// $("#detail-comment").html(leave.Comment);
    //         });
    //     }

    //     if (action=="detail"){
    //         $('#detailModal').dialog({
    //             dialogClass: "no-close",
    //             modal: true,
    //             title: "Leave Detail",
    //             width: 620,
    //             buttons: [
    //             {
    //                 text: "OK",
    //                 "class": "btn btn-primary",
    //                 click: function () {
    //                     $(this).dialog("close");
    //                 }
    //             }
    //             ]
    //         });
    //     }else{
    //         $('#formModal').dialog({
    //             dialogClass: "no-close",
    //             modal: true,
    //             title: "Leave Form",
    //             width: 620,
    //             buttons: [
    //             {
    //                 text: "Save",
    //                 id: "addleave",
    //                 "class": "btn btn-primary",
    //                 click: function () {
    //                     $('#addleave').attr('disabled','disabled').html('Saving...');
    //                     $('#cancelleave').hide();

    //                     var requestData = $("#requestLeaveForm").serialize() + '&action=' + action + '&leave_id=' + id ;
    //                     $.ajax({
    //                         type: "POST",
    //                         url: "/index.php/personal/process_leave",
    //                         data: requestData,
    //                         dataType: "JSON",
    //                         success: function (data) {
    //                             $('#addleave').removeAttr('disabled').html('Save');
    //                             $('#cancelleave').show();

    //                             if (data.succes==true) {
    //                                 $("#formModal").dialog("close");
    //                                 //reload the datatable.
    //                                 window.location.href = '/personal';
    //                             } else {
    //                                 $("#errorModal").html('<div class="col-sm-12">'+ data.err_msg +'</div>');
    //                                 errorAction();
    //                             }
    //                         }
    //                     });
    //                 }
    //             },
    //             {
    //                 text: "Cancel",
    //                 id: "cancelleave",
    //                 "class": "btn btn-primary",
    //                 click: function () {
    //                     resetForm();
    //                     $(this).dialog("close");
    //                 }
    //             }
    //             ]
    //         });
    //     }
    // }

    // function confirmAction(id) {
    //         $('#confirmModal').dialog({
    //             dialogClass: "no-close",
    //             modal: true,
    //             title: "Alert",
    //             width: 400,
    //             buttons: [
    //             {
    //                 text: "YES",
    //                 "class": "btn btn-danger",
    //                 click: function () {
    //                 $.ajax({
    //                     type: "POST",
    //                     url: "/index.php/personal/cancel_leave",
    //                     data: {leave_id:id},
    //                     dataType: "JSON",
    //                     success: function (data) {
    //                         if (data.succes==true) {
    //                             $("#confirmModal").dialog("close");
    //                             //reload the datatable.
    //                             window.location.href = '/personal';
    //                         }
    //                     }
    //                 });
    //                 }
    //             },
    //             {
    //                 text: "NO",
    //                 "class": "btn btn-default",
    //                 click: function () {
    //                     $(this).dialog("close");
    //                 }
    //             }
    //             ]
    //         });
    //     }

//     function errorAction() {
//         $('#errorModal').dialog({
//             dialogClass: "no-close",
//             modal: true,
//             title: "Error!",
//             width: 400,
//             buttons: [
//             {
//                 text: "OK",
//                 "class": "btn btn-danger",
//                 click: function () {
//                     $(this).dialog("close");
//                 }
//             }
//             ]
//         });
//     }


    // $('#requestLeave').click(function () {
    //     popModal("request",null);
    // });

    // $('.btn').click(function () {
    //     var data_item = $(this).attr('id'),
    //         data_item = data_item.split("_");

    //     var action=data_item[0],
    //         leave_id=data_item[1];

    //     if (action=="delete"){
    //         confirmAction(leave_id);
    //     }else if(action=="edit"){
    //         popModal(action, leave_id);
    //     }else if(action=="detail"){
    //         popModal(action, leave_id);
    //     }
    // });

    /*-----------/
    FORM TOOL:
    ----------*/
  //   $('#halfday').change(function() {
  //       if(this.checked) {
  //           $('#endDate').val( $('#startDate').val() );
  //       }
  //   });

  //   $("#startDate").datepicker({
  //       dateFormat: "yy-mm-dd",
		// beforeShowDay: $.datepicker.noWeekends,
  //       autoSize: true,
  //       numberOfMonths: 1,
  //       //minDate: "<?php echo date('Y-m-d') ?>",
  //       onSelect: function(){
  //           $('#halfday').removeAttr('disabled');
  //       },
  //       onClose: function (selectedDate) {
  //           $("#endDate").datepicker("option", "minDate", selectedDate);
  //       }
  //   });

  //   $("#endDate").datepicker({
  //       dateFormat: "yy-mm-dd",
		// beforeShowDay: $.datepicker.noWeekends,
  //       autoSize: true,
  //       numberOfMonths: 1,
  //       onClose: function (selectedDate) {
  //           $("#startDate").datepicker("option", "maxDate", selectedDate);
  //       }
  //   });


// });

</script>