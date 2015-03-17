<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 class="modal-title text-uppercase">Leave Form</h3>
        </div>
        <div class="modal-body">
            <form method="post" name="leaveForm" id="leaveForm" class="form-horizontal" role="form">
                <div class="row">
                    <input type="hidden" name="action" value="<?=$action?>">
                    <input type="hidden" name="table_id" value="<?php echo (isset($tableID) ? $tableID : ''); ?>">
                    <div class="col-sm-4">
                        Date Start : <br />
                        <input required type="text" class="form-control" name="startDate" id="startDate" placeholder="yy-mm-dd" value="<?php echo set_value('startDate', isset($StartDate) ? $StartDate : ''); ?>">
                        Date End : <br />
                        <input required type="text" class="form-control" name="endDate" id="endDate" placeholder="yy-mm-dd" value="<?php echo set_value('endDate', isset($EndDate) ? $EndDate : ''); ?>">
                    </div>

                    <div class="col-sm-4"></div>
                    <div class="col-sm-4">
                        <input name="halfday" id="halfday" value="1" type="checkbox" <?php if (isset($HalfDay)) { if ($HalfDay == 1) { echo 'checked'; }}  ?> disabled> Half Day<br>
                        <input name="LWOP" id="LWOP" value="1" type="checkbox" <?php if (isset($LWOP)) { if ($LWOP == 1) { echo 'checked'; }}  ?>> Leave w/o Pay
                    </div>

                    <div class="col-sm-12">
                        <br />Reason : <br />
                        <textarea required name="reason" class="form-control" id="reason" rows="3"><?php echo set_value('reason', isset($Purpose) ? $Purpose : ''); ?></textarea>
                        <input name="approverEmail" type="hidden" value="<?=$user_approver_email?>">
                        <input name="approverName" type="hidden" value="<?=$user_approver_name?>">
                        <input name="fullName" type="hidden" value="<?=$fullName?>">

                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="submit"><?=$btn_name;?></button>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#halfday').change(function() {
            if(this.checked) {
                $('#endDate').val( $('#startDate').val() );
            }
        });

        $("#startDate").datepicker({
            dateFormat: "yy-mm-dd",
            beforeShowDay: $.datepicker.noWeekends,
            autoSize: true,
            numberOfMonths: 1,
            //minDate: "<?php echo date('Y-m-d') ?>",
            onSelect: function(){
                $('#halfday').removeAttr('disabled');
            },
            onClose: function (selectedDate) {
                $("#endDate").datepicker("option", "minDate", selectedDate);
            }
        });

        $("#endDate").datepicker({
            dateFormat: "yy-mm-dd",
            beforeShowDay: $.datepicker.noWeekends,
            autoSize: true,
            numberOfMonths: 1,
            onClose: function (selectedDate) {
                $("#startDate").datepicker("option", "maxDate", selectedDate);
            }
        });
    });

$("input[name='startDate']").on('click', function() {
    $(this).css('border-color', '');
});

$("input[name='endDate']").on('click', function() {
    $(this).css('border-color', '');
});

$("textarea[name='reason']").on('click', function() {
    $(this).css('border-color', '');
});
</script>