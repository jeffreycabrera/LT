<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title text-uppercase"></h3>
    </div>
    <div class="modal-body">
        <form name="userForm" id="userForm" class="form-horizontal" role="form">
        <div class="row">
            <?php echo form_hidden($action); ?>
            <input type="hidden" id="hidUserID" name="hidUserID" value="<?php echo set_value('hidUserID', isset($userData["UserID"])?$userData["UserID"]:''); ?>">
            <div class="col-sm-12 text-right">
                <span class="img-loader" hidden><img style="height:30px; width:30px; margin:0px" src="<?php echo base_url().'assets/images/ajax-loader-2.gif';?>" /></span>
                <?php echo ($action['action']=='edit')? form_button($resetbtn):""; ?><br>
            </div>
            <div class="col-md-6">
                <div class="col-sm-12">
                    <br>Lastname :
                    <input type="text" class="form-control" name="lastName" value="<?php echo set_value('lastName', isset($userData["LastName"])?$userData["LastName"]:''); ?>">
                </div>
                <div class="col-sm-12">
                    <br>Firstname :
                    <input type="text" class="form-control" name="firstName" value="<?php echo set_value('firstName', isset($userData["FirstName"])?$userData["FirstName"]:''); ?>">
                </div>
                <div class="col-sm-12">
                    <br>Middlename :
                    <input type="text" class="form-control" name="middleName" value="<?php echo set_value('middleName', isset($userData["MiddleName"])?$userData["MiddleName"]:''); ?>">
                </div>
                <div class="col-sm-12">
                    <br>Email :
                    <input type="text" class="form-control" name="emailAddress" value="<?php echo set_value('emailAddress', isset($userData["Email"])?$userData["Email"]:''); ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-sm-12">
                    <br>Approver 1 :
                    <?php 
                    $param = 'id="approver1" class="frm form-control"';
                    $approvers = (isset($userData["approvers"])? $userData["approvers"]: array());
                    $approver1 = (array_key_exists(1, $approvers))? $approvers[1]["ApproverID"] : "0";
                    
                    echo form_dropdown('approver1', $approversList, $approver1, $param); ?>
                </div>
                <div class="col-sm-12">
                    <br>Approver 2 :
                    <?php 
                    $param = 'id="approver2" class="frm form-control"';

                    $approvers = (isset($userData["approvers"])? $userData["approvers"]: array());
                    $approver1 = (array_key_exists(2, $approvers))? $approvers[2]["ApproverID"] : "0";
                    
                    echo form_dropdown('approver2', $approversList, $approver1, $param); ?>
                </div>
                <div class="col-sm-12">
                    <br>Number of PTO per Year :
                    <input type="text" class="form-control col-sm-5" name="PTO" value="<?php echo set_value('PTO', isset($userData["PTO"])?$userData["PTO"]:''); ?>">
                </div>
            </div>
        </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submitUser">Save changes</button>
    </div>
</div>
</div>

<script type="text/javascript">

$('#reset_reset').on('click', function() {
    $('.img-loader').html('<img style="height:30px; width:30px; margin:0px" src="assets/images/ajax-loader-2.gif" />');
});

$("input[name='lastName']").on('click', function() {
    $(this).css('border-color', '');
});

$("input[name='middleName']").on('click', function() {
    $(this).css('border-color', '');
});

$("input[name='firstName']").on('click', function() {
    $(this).css('border-color', '');
});

$("input[name='emailAddress']").on('click', function() {
    $(this).css('border-color', '');
});

$("select[name='approver1']").on('click', function() {
    $(this).css('border-color', '');
});

$("select[name='approver2']").on('click', function() {
    $(this).css('border-color', '');
});

$("input[name='PTO']").on('click', function() {
    $(this).css('border-color', '');
});

function resetPassword(url){
    $("#otherPopModal").html('<div class="col-sm-12"><img  src="<?php echo base_url()."assets/images/ajax-loader.gif";?>" /></div>');
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
        beforeSend: function() {
            $('.img-loader').show();
        },
        success: function(data){
            if (data.success==true){
                $('.img-loader').html('<small style="color:green">Sent!</small>');
            }else{
                $('.img-loader').html('<small style="color:red">Failed!</small>');
            }
        }
    });
}





</script>