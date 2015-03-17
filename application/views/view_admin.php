<div class="leave-tracker-template">
    <div class="row">
        <div class="col-md-8"><h1 class="page-header"><?= $pageTitle; ?></h1></div>
        <div class="col-md-4" style="text-align: right;"><button id="create<?=$buttonValue?>_" class="btn btn-primary pop-event">Add <?=$buttonValue?></button></div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo $tableData; ?>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade"></div>