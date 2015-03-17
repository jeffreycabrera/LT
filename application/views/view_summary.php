<div class="leave-tracker-template">
    <div class="row">
    	<div class="row">
    		<div class="col-md-12"><h1 class="page-header">PTO Summary</h1></div>
            <?php if ($this->session->userdata('lt_isAdmin')) : ?>
            <div class="col-md-12"><button class="btn btn-primary" id="export">Export to excel</button></div>
    	   <?php endif; ?>
        </div>
    	<div class="row">
    		<div class="col-md-12">
            	<?php echo $summaryTable; ?>
        	</div>
    	</div> 
    </div>
    <br><br>
    <div class="row">
    	<div class="row">
    		<div class="col-md-12"><h1 class="page-header">Detailed Summary</h1></div>
    	</div>
    	<div class="row">
	    	<div class="col-md-12">
	    		<?php echo $tableData; ?>
	    	</div>
    	</div> 
    </div>
</div>


<div id="errorModal" style="display:none;"></div>