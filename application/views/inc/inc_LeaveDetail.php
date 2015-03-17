<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 class="modal-title text-uppercase">Leave Details</h3>
        </div>
        <div class="modal-body">
            <form name="leaveForm" class="form-horizontal" role="form">
                <div class="row">
                <div class="leaveDetails col-sm-4">
                    Date Start : <span id="detail-startDate"><?=$StartDate?></span><br />
                    Date End : <span id="detail-dateEnd"><?=$EndDate?></span><br />
                    Status : <span id="detail-status"><?=$Status?></span><br />
                </div>

                <div class="col-sm-4"></div>
                <div class="leaveDetails col-sm-4">
                    <input type="checkbox" id="detail-halfday" <?php if ($HalfDay == 1) { echo "checked"; } ?> disabled> Half Day<br />
                    <input type="checkbox" id="detail-LWOP" <?php if ($LWOP == 1) { echo "checked"; } ?> disabled> Leave w/o Pay
                </div>

                <div class="leaveDetails col-sm-12">
                    <br />Reason : <br />
                    <p id="detail-reason"><?=$Purpose?></p>
                </div>
                
                <div class="leaveDetails col-sm-12">
                    <br />Approver's Comment : <br />
                    <p id="detail-comment"><?=$Comment?></p>
                </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>