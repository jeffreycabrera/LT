<script>
    function popModal(title) {
        $('#formModal').dialog({
            dialogClass: "no-close",
            modal: true,
            title: title,
            width: 620,
            buttons: [
                {
                    text: "Save",
                    "class": "btn btn-primary",
                    click: function () {
                        var details = $("#leaveForm").serialize();
                        
                        $.ajax({
                            type: 'POST',
                            url: '/index.php/approver/update',
                            data: details,
                            dataType: "JSON",
                            success: function (data) {
                                if (data.succes==true) {
                                    $('#formModal').dialog('close');
                                    //reload the datatable.
                                    window.location.href = '/index.php/approver';
                                } else {
                                    $("#errorModal").html('<div class="col-sm-12">'+ data.description +'</div>');
                                    errorAction();
                                }
                            }
                        });
                    }
                },
                {
                    text: "Cancel",
                    "class": "btn btn-primary",
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ]
        });
    }
    
    function initTableButtons(){        
        $('.btns').click(function () {
            //ID of Selected USer upon click
            var leaveID = $(this).attr("id");

            $.get("/index.php/approver/pendingLeave/" + leaveID, function (data) {
                var pendingLeave = JSON.parse(data);
                $('#approverTableID').val(pendingLeave.approverTableID);
                $('#emp-name').html(pendingLeave.LastName+", "+pendingLeave.FirstName);
                $('#approver').html(pendingLeave.approver);
                $('#leaveID').val(pendingLeave.LeaveTableID);
                $('#heirarchy').val(pendingLeave.Heirarchy);
                $('#startDate').html(pendingLeave.StartDate);
                $('#dateEnd').html(pendingLeave.EndDate);
                $('#reason').html(pendingLeave.Purpose);
                $('#comment').val(pendingLeave.comment);
                $('#chkHalf').prop('checked', pendingLeave.HalfDay == 1 ? true : false);
                $('#chkLWOP').prop('checked', pendingLeave.LWOP == 1 ? true : false);
            });
            popModal("Leave Request");
        });
    }

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
        $('#summaryLeaves').dataTable({
            responsive: true,
            "bLengthChange": false,
            "sPaginationType": "full_numbers"
        });

        $('#approvedLeaves').dataTable({
            responsive: true,
            "bLengthChange": false,
            "sPaginationType": "full_numbers"
        });

        $('#export').on('click', function() {
            window.location = '/summary/ExcelExport'
        }); 
    });
</script>
