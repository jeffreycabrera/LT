<script>

function errorAction() {
    $('#errorModal').dialog({
        dialogClass: "no-close",
        modal: true,
        title: "Error!",
        width: 400,
        buttons: [
        {
            text: "OK",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }
        ]
    });
}

$(document).ready(function () {
    var table = $('#leaves').DataTable({
        responsive: true,
        "bLengthChange": false,
        "sPaginationType": "full_numbers",
        "aoColumnDefs": [{'bSortable': false, 'aTargets': [5, 6]}]
    });

    // $('#summaryList').dataTable({
    //     responsive: true,
    //     "bLengthChange": false,
    //     "sPaginationType": "full_numbers"
    // });
$('body').on('click', '#submitPending', function(event){

    var form_Data = $('#pendingForm').serializeArray(),
        form_action = JSON.parse(JSON.stringify(form_Data));
        
    var URL = "/pending/action";
    
    $.ajax({    
        url: URL,
        type: "POST",
        data: form_action,
        dataType:'json',
        success: function (data) {
            if (data.succes == false) {
                if (data.field == 'comment') {
                    $('#comment').css('border-color', 'red');
                    return;
                } else {
                    $('#required').show();
                    return;
                }
                return;
            } 
            table
                .row($('.selected1').parents('tr'))
                .remove()
                .draw(false);
            $("#myModal").modal('hide');    
        }
    });
});

});

// $('body').on('click', '.pop-event', function(){
$('#leaves').on('click', '.pop-event', function () {
    var data_item = $(this).attr('id');

    $(this).addClass('selected1');

    $.ajax({
        url: '<?php echo base_url() ?>index.php/pending/pending_view', 
        type: 'POST',
        dataType:'json',
        data: {'leave_id': data_item},
        success: function(response) {
            $('#myModal').html(response.html_view);
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            })
        }
    });
});











    // function popModal(title) {
    //     $('#formModal').dialog({
    //         dialogClass: "no-close",
    //         modal: true,
    //         title: title,
    //         width: 620,
    //         buttons: [
    //             {
    //                 text: "Save",
    //                 "class": "btn btn-primary",
    //                 click: function () {
    //                     var details = $("#leaveForm").serialize();
                        
    //                     $.ajax({
    //                         type: 'POST',
    //                         url: '/index.php/pending/update',
    //                         data: details,
    //                         dataType: "JSON",
    //                         success: function (data) {
    //                             if (data.succes==true) {
    //                                 $('#formModal').dialog('close');
    //                                 //reload the datatable.
    //                                 window.location.href = '/index.php/pending';
    //                             } else {
    //                                 $("#errorModal").html('<div class="col-sm-12">'+ data.description +'</div>');
    //                                 errorAction();
    //                             }
    //                         }
    //                     });
    //                 }
    //             },
    //             {
    //                 text: "Cancel",
    //                 "class": "btn btn-primary",
    //                 click: function () {
    //                     $(this).dialog("close");
    //                 }
    //             }
    //         ]
    //     });
    // }

    

</script>
