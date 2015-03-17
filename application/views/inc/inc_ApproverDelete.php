<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title text-uppercase">Delete Approver</h3>
    </div>
    <div class="modal-body">
        <form name="approverForm" id="approverForm" class="form-horizontal" role="form">
        <?php echo form_hidden($action);?>
        <input type="hidden" id="hidUserID" name="hidUserID" value="<?php echo set_value('hidUserID', isset($userData["UserID"])?$userData["UserID"]:''); ?>">
        <div class="row">
            <div class="col-sm-12">
                <p>Are you sure to remove <strong><?= $userData["LastName"] .", ". $userData["FirstName"] ?></strong> from the Approver list ?</p>
            </div>
        </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="submitApprover">Yes</button>
    </div>
</div>
</div>