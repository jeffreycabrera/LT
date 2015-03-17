<div class="leave-tracker-template">
    <div class="row createApprover">
        <div class="col-md-8"><h1 class="page-header"><?= $pageTitle; ?></h1></div>
        <div class="col-md-4" style="text-align: right;"><button id="createApprover_<?=$buttonValue?>" class="btn btn-primary pop-event">Add Approver</button></div>
    </div>
    <div class="row reload">
        <div class="col-md-12">
            <?php echo $tableData; ?>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade"></div>