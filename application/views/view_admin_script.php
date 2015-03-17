// <script>
//     var isSet;

//     function popConfirmDialog(title) {
//         $('#confirmModal').dialog({
//             dialogClass: "no-close",
//             modal: true,
//             title: title,
//             width: 400,
//             buttons: [
//                 {
//                     text: "Delete",
//                     "class": "btn btn-primary",
//                     click: function () {
//                         $.ajax({
//                             type: 'POST',
//                             url: '/index.php/admin/delete',
//                             data: {'ID': $('#hidUserID').val()},
//                             dataType: 'text',
//                             success: function (data) {
//                                 $('#formModal').dialog('close');
//                                 window.location.href = '/admin';
//                             }
//                         });
//                         $(this).dialog("close");
//                     }
//                 },
//                 {
//                     text: "Cancel",
//                     "class": "btn btn-primary",
//                     click: function () {
//                         $(this).dialog("close");
//                     }
//                 }
//             ]
//         });
//     }

//     function popModal(title) {
//         $('#formModal').dialog({
//             dialogClass: "no-close",
//             width: 620,
//             resizable: false,
//             modal: true,
//             title: title,
//             buttons: [
//                 {
//                     text: "Save",
//                     "class": "btn btn-primary",
//                     click: function () {
//                         var url = isSet === 'add' ? "/adduser" : "/edit";
//                         var requestData = $('#leaveForm').serialize();
//                         $.ajax({
//                             type: "POST",
//                             url: "/index.php/admin" + url,
//                             data: requestData,
//                             dataType: "JSON",
//                             success: function (data) {
//                                 if (data.succes==true) {
//                                     $("#formModal").dialog("close");
//                                     //reload the datatable.
//                                     window.location.href = '/admin';
//                                 } else {
//                                     $("#errorModal").html('<div class="col-sm-12">'+ data.err_msg +'</div>');
//                                     errorAction();
//                                 }
//                             }
//                         });
//                     }
//                 },
//                 {
//                     text: "Cancel",
//                     "class": "btn btn-primary",
//                     click: function () {
//                         $(this).dialog("close");
//                     }
//                 }
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

//     function clearForm() {
//         $("#leaveForm").find("input[type=text], textarea, hidden").val("");
//         $("#leaveForm").find("select").removeAttr('selected');
//         $('#isApprover').prop('checked', false);
//     }

//     $(document).ready(function () {
//         $('#leaves').dataTable({
//             responsive: true,
//             "bLengthChange": false,
//             "sPaginationType": "full_numbers",
//             "aoColumnDefs": [{'bSortable': false, 'aTargets': [2, 3, 4, 5]}]
//         });

//         $('#addUser').click(function () {
//             clearForm();
//             $('#btnDelete').hide();
//             $('#btnReset').hide();
//             isSet = 'add';
//             popModal('Add User');
//         });

//         $('.btns').click(function () {
//             $('#btnDelete').val($(this).val());
//             $('#btnDelete').show();
//             $('#btnReset').show();

//             isSet = 'edit';
//             var curID = $(this).attr("id");

//             popModal("Edit User");

//             $.get("/index.php/admin/users/" + curID, function (data) {
//                 var user = JSON.parse(data);
//                 var approvers = user.approvers;

//                 $('#approver1 option[selected="selected"]').removeAttr('selected');
//                 $('#approver2 option[selected="selected"]').removeAttr('selected');
                
//                 $('#hidUserID').val(user.UserID);
//                 $('#lastName').val(user.LastName);
//                 $('#firstName').val(user.FirstName);
//                 $('#middleName').val(user.MiddleName);
//                 $('#emailAddress').val(user.Email);
//                 $('#PTO').val(user.PTO);
//                 $('#isApprover').prop('checked', user.IsApprover == 1 ? true : false);

//                 if (approvers.hasOwnProperty(1) ){
//                     $('#approver1 option[value='+ approvers[1].ApproverID +']').attr('selected','selected');
//                 }else{
//                     $('#approver1 option[value=0').attr('selected','selected');
//                 }

//                 if (approvers.hasOwnProperty(2)){
//                     $('#approver2 option[value='+ approvers[2].ApproverID +']').attr('selected','selected');
//                 }else{
//                     $('#approver1 option[value=0').attr('selected','selected');
//                 }
//             });
//         });

//         $('#btnDelete').click(function () {
//             popConfirmDialog('Delete User');
//         });
//     });
// </script>