<div class="leave-tracker-template">
    <div class="row">
        <div class="col-md-8"><h1 class="page-header">Employees</h1></div>
        <div class="col-md-4" style="text-align: right;"><button id="create_" class="btn btn-primary pop-event">Add User</button></div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $tableData; ?>
        </div>
    </div>
</div>

<div id="formModal" style="display:none;"></div>
<div id="otherPopModal" style="display:none;"></div>
<div id="confirmModal" style="display:none;">
    <div class="col-sm-12">
        <p>Are you sure to delete this user ?</p>
    </div>
</div>


<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-uppercase"></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submit">Save changes</button>
            </div>
        </div>
    </div>
</div>