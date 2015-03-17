<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h3 class="modal-title text-uppercase">Details</h3>
    </div>
    <div class="modal-body">
        <form name="pendingForm" id="pendingForm" class="form-horizontal" role="form">
        <div class="row">
            <input type="hidden" id="approverTableID" name="approverTableID" value="<?=$approverTableID?>">
            <div class="col-md-12"><h3><small id="emp-name"></small></h3></div>
            <div class="col-sm-6">                              
                <input type="hidden" id="heirarchy" name="heirarchy" value="<?=$Heirarchy?>"/>
                <input type="hidden" id="leaveID" name="leaveID" value="<?=$leaveID?>"/>
                Start End : <span id="startDate"><?=$StartDate?></span><br />
                Date End : <span id="dateEnd"><?=$EndDate?></span><br />
                Status : <br />
                   <!--  <input type="radio" name="status" id="status" value="1"><label for="">Accept</label><br>
                    <input type="radio" name="status" id="status" value="2"><label for="">Decline</label> -->

                    <div class="btn-group" id="statusRadio" data-toggle="buttons">
                        <label class="btn btn-success status" data-toggle="tooltip" data-placement="top" data-original-title="Default tooltip">
                          <input type="radio" autocomplete="off" name="status" id="status" value="1" > Accept
                        </label>
                        <label class="btn btn-default status">
                          <input type="radio" autocomplete="off" name="status" id="status" value="2"> Decline
                        </label>
                        <small id="required" style="color:red; display:none;"><i>* required</i></small>
                    </div>

                <!-- <select id="status" name="status" class="form-control">
                    <option value="0" disabled selected>-- Select Status --</option>
                    <option value="1">Accept</option>
                    <option value="2">Decline</option>
                </select> -->
            </div>
            <div class="col-sm-6">
                Other Details<br>
                <input type="checkbox" id="chkHalf" disabled> Half Day<br />
                <input type="checkbox" id="chkLWOP" name="LWOP" value="1" <?php if ($LWOP == 1) { echo 'checked'; } ?>> Leave w/o Pay
            </div>

            <div class="col-sm-12">
                <br />Reason : <br />
                <p id="reason"><?=$Purpose?></p>
            </div>
            <?php if ($comment): ?>
            <div class="col-sm-12">
                <br />Approver 1's comment : <br />
                <p id="comment1"><?=$comment?></p>
            </div>
            <?php endif; ?>
            <div class="col-sm-12">
                <br />Approver's Comment : <br />
                <textarea name="comment" class="form-control comment" id="comment" rows="3"></textarea>
            </div>
        </div>
    </form>
    </div>
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
        <button id="submitPending" class="btn btn-primary" type="button">Save changes</button>
    </div>
</div>
</div>

<script type="text/javascript">
    $('.status').on('click',function() {
        $('#required').hide();
    });

    $('#comment').on('click',function() {
        $('#comment').removeAttr('style');
        // $('#comment').css('border-color', '#555');
    });


</script>