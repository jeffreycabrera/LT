<script type="text/javascript">
$(document).ready(function() {
	// build dataTable:
    var table = $('#approversList').DataTable({
        responsive: true,
        "bLengthChange": false,
        "sPaginationType": "full_numbers",
        "aoColumnDefs": [{'bSortable': false, 'aTargets': [2]}]
    });
    
    $('body').on('click', '#submitApprover', function(event){

        var form_Data = $('#approverForm').serializeArray(),
            form_action = JSON.parse(JSON.stringify(form_Data));
            
        if (form_Data[0]['value']=='delete-approver') {
            var URL = "/approvers/delete_approver";
        } else {
            var URL = "/approvers/add_approver";
        }
       
        $.ajax({    
            url: URL,
            type: "POST",
            dataType: "JSON",
            data: form_action,
            success: function (data) {
                if (form_Data[0]['value']=='delete-approver') {
                    $("#myModal").modal('hide'); 
                    table
                    .row($('.selected1').parents('tr'))
                    .remove()
                    .draw(false);
                } else {
                    if (data.success == false) {
                        $("input[name='tags']").css('border-color', 'red');
                        return false;
                    }
                    $("#myModal").modal('hide'); 
                    table.row.add( [
                        form_action[1]['value'],
                        "",
                        "<button id='delete-approver_"+form_Data[0]['value']+"' class='btn btn-danger pop-event' type='button'><i class='fa fa-trash fa-2'></i> remove</button>"
                    ] ).draw();  
                }
            }
        });
    });
});

$('#approversList, .createApprover').on('click', '.pop-event', function () {
    var data_item = $(this).attr('id'),
        data_item = data_item.split("_");

    $(this).addClass('selected1');
    var action=data_item[0],
        leave_id=data_item[1];

    if (action == "createApprover") {
        var URL = '<?php echo base_url() ?>index.php/approvers/load_view';
    } else if (action == "delete-approver") {
        var URL = '<?php echo base_url() ?>index.php/approvers/delete_view';
    }

    $.ajax({
        url: URL, 
        type: 'POST',
        dataType:'json',
        data: {'action': action, 'user_id': leave_id},
        success: function(response) {
            $('#myModal').html(response.html_view);
            // $('#myModal .modal-title').html(action +' Approver');
            
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            })
        }
    });
});

</script>