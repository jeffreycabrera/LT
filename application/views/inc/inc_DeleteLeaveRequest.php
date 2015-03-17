<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title text-uppercase">Delete Leave request</h3>
    </div>
    <div class="modal-body">
        <form name="leaveForm" id="leaveForm" class="form-horizontal" role="form">
        <input type="hidden" name="action" i="action" value="<?=$action?>">
        <input type="hidden" id="table_id" name="table_id" value="<?=$table_id?>">
        <div class="row">
            <div class="col-sm-12">
                <p>Are you sure to cancel this leave request?</p>
            </div>
        </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="submit">Yes</button>
    </div>
</div>
</div>