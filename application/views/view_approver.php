<div class="leave-tracker-template">
    <div class="col-sm-12"><h2><small>Leave Requests</small></h2></div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $tableData; ?>
        </div>
    </div>
    
    <div class="col-sm-12"><h2><small>Summary List</small></h2></div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $summaryTable; ?>
        </div>
    </div>
</div>

<div id="formModal" style="display:none;">
    <form name="leaveForm" id="leaveForm" class="form-horizontal" role="form">
        <input type="hidden" id="approverTableID" name="approverTableID">
        <div class="col-md-12"><h3><small id="emp-name"></small></h3></div>
        <div class="col-sm-4">                              
            <input type="hidden" id="heirarchy" name="heirarchy"/>
            <input type="hidden" id="leaveID" name="leaveID"/>

            Start End : <span id="startDate"> </span><br />
            Date End : <span id="dateEnd"> </span><br />
            Status : <br />
            <select id="status" name="status" class="form-control">
                <option value="0" disabled selected>-- Select Status --</option>
                <option value="1">Accept</option>
                <option value="2">Decline</option>
            </select>
        </div>

        <div class="col-sm-4"></div>

        <div class="col-sm-4">
            Approved by: <span id="approver"></span><br>
            <input type="checkbox" id="chkHalf" disabled> Half Day<br />
            <input type="checkbox" id="chkLWOP" name="LWOP" value="1" checked> Leave w/o Pay
        </div>

        <div class="col-sm-12">
            <br />Reason : <br />
            <p id="reason"></p>
        </div>

        <div class="col-sm-12"></div>
        
        <div class="col-sm-12">
            <br />Approver's Comment : <br />
            <textarea name="comment" class="form-control" id="comment" rows="3"></textarea>
        </div>
    </form>
</div>
<div id="errorModal" style="display:none;"></div>