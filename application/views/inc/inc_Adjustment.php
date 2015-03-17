<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <!-- <h3 class="modal-title text-uppercase">Adjustments</h3> -->
        </div>
        <div class="modal-body">
            <?php var_dump($pop_data); ?>
            <form method="post" name="adjustmentForm" id="adjustmentForm" class="form-horizontal" role="form">
                <div class="row">
                    <input type="text" name="id" >
                    <div class="col-sm-4">
                        Date Start : <br />
                        <input required type="text" class="form-control" name="startDate" id="startDate" placeholder="yy-mm-dd" value="<?php echo set_value('startDate', isset($StartDate) ? $StartDate : ''); ?>">
                        Date End : <br />
                        <input required type="text" class="form-control" name="endDate" id="endDate" placeholder="yy-mm-dd" value="<?php echo set_value('endDate', isset($EndDate) ? $EndDate : ''); ?>">
                    </div>
                    
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="submit">Save</button>
        </div>
    </div>
</div>
