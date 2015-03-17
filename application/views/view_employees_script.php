<script>
$(document).ready(function() {
    // build dataTable:
    var table = $('#employeeList').DataTable({
        responsive: true,
        "bLengthChange": false,
        "sPaginationType": "full_numbers",
        "aoColumnDefs": [{'bSortable': false, 'aTargets': [1, 2, 3, 4]}]
    });

    $("body").on("click", "#submitUser", function(event){
        var form_Data = $('#userForm').serializeArray(),
            form_action = JSON.parse(JSON.stringify(form_Data));

        if (form_Data[0]['value'] == 'delete-user') {
            var URL = "/index.php/employees/delete_user";
        } else {
            var URL = "/index.php/employees/user_detailManager";
        }

        $.ajax({
            type: "POST",
            url: URL,
            data: form_Data,
            dataType: "JSON",
            success: function (data) {
                if (form_Data[0]['value'] == 'delete-user') {
                    $("#myModal").modal('hide');
                    table
                        .row($('.selected1').parents('tr'))
                        .remove()
                        .draw(false);
                } else {


                    if (data.succes == false) {
                        if (form_action[2]['value'] == "") {
                            $("input[name='lastName']").css('border-color', 'red');
                        }

                        if (form_action[3]['value'] == "") {
                            $("input[name='firstName']").css('border-color', 'red');
                        }

                        if (form_action[4]['value'] == "") {
                            $("input[name='middleName']").css('border-color', 'red');
                        }

                        if (form_action[5]['value'] == "") {
                            $("input[name='emailAddress']").css('border-color', 'red');
                        }

                        if (check_email(form_action[5]['value']) == false) {
                            $("input[name='emailAddress']").css('border-color', 'red');
                        }

                        if (form_action[6]['value'] == 0) {
                            $("select[name='approver1']").css('border-color', 'red');
                        }

                        if (form_action[7]['value'] == 0) {
                            $("select[name='approver2']").css('border-color', 'red');
                        }

                        if (form_action[8]['value'] == "") {
                            $("input[name='PTO']").css('border-color', 'red');
                        }

                        if (isInt(form_action[8]['value']) == false) {
                            $("input[name='PTO']").css('border-color', 'red');
                        }
                        return false
                    } else {
                        if (data.act=='create'){
                            resetPassword('send_email');
                            $("#myModal").modal('hide');
                            table.row.add( [
                                form_action[2]['value'] + ", " + form_action[3]['value'],
                                form_action[8]['value'],
                                form_action[6]['value'],
                                form_action[7]['value'],
                                "<button class='btn btn-primary pop-event' id='edit_"+data.userID+"' type='button'><i class='fa fa-pencil-square-o fa-2'></i> edit</button> <button class='btn btn-danger pop-event' id='delete-user_"+data.userID+"' type='button'><i class='fa fa-trash fa-2'></i> delete</button>"
                            ]).draw(false);
                           
                        } else {
                            $("#myModal").modal('hide');

                            table
                                .row($('.selected1').parents('tr'))
                                .remove()
                                .draw(false);

                            table.row.add( [
                                form_action[2]['value'] + ", " + form_action[3]['value'],
                                form_action[8]['value'],
                                form_action[6]['value'],
                                form_action[7]['value'],
                                "<button class='btn btn-primary pop-event' id='edit_"+form_action[1]['value']+"' type='button'><i class='fa fa-pencil-square-o fa-2'></i> edit</button> <button class='btn btn-danger pop-event' id='delete-user_"+form_action[1]['value']+"' type='button'><i class='fa fa-trash fa-2'></i> delete</button>"
                            ]).draw(false);
                        }
                    }
                }  
            }
        });
    });

    function check_email(val){
        if(!val.match(/\S+@\S+\.\S+/)){ 
            return false;
        }

        if( val.indexOf(' ')!=-1 || val.indexOf('..')!=-1){
            return false;
        }
        return true;
    }

    function isInt(value) {
        return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseFloat(value))
    }

});

$('#employeeList, .createUser').on('click','.pop-event', function() {
// $("body").on("click", ".pop-event", function(event){
    var data_item = $(this).attr('id'),
        data_item = data_item.split("_");

    $(this).addClass('selected1');

    var action=data_item[0],
        leave_id=data_item[1];

    if (action==="createUser" || action==="edit"){
        var URL = '<?php echo base_url() ?>index.php/employees/load_view';
    }
    else if (action==="delete-user") {
        var URL = '<?php echo base_url() ?>index.php/employees/delete_view';
    }

    $.ajax({
        url: URL, 
        type: 'POST',
        dataType:'json',
        data: {'action': action, 'user_id': leave_id},
        success: function(response) {
            $('#myModal').html(response.html_view);
            if (action == 'createUser') {
                $('#myModal .modal-title').html('Add User');    
            } else {
                $('#myModal .modal-title').html('Edit User');
            }
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            })
        }
    });
}); 

</script>