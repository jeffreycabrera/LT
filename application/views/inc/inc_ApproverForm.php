<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h3 class="modal-title text-uppercase">Add Approver</h3>
    </div>
    <div class="modal-body">
        <form role="form" class="form-horizontal" id="approverForm" name="approverForm">
            <div class="row">
                <input type="hidden" value="" name="hidUserID" id="hidUserID">
                <input type="hidden" value="" name="fullName" id="fullName">                      
                <div class="ui-widget col-md-12">
                    <label for="tags">Users: </label>
                    <input type="text" id="tags" class="col-md-12 form-control" placeholder="Search User..." autocomplete="off" name="tags" />
                </div>

                <div class="col-md-12">
                <br>
                <!-- <div class="panel panel-default">
                    <div><h3><span class="panel-heading" id="approver-name"></span></h3></div>
                    <div class="panel-body">
                        <div id="radio">
                        </div>
                    </div>
                </div> -->
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
        <button id="submitApprover" class="btn btn-primary" type="button">Save changes</button>
    </div>
</div>
</div>

<script type="text/javascript">

    function displayResult(item) {
        $('#hidUserID').val(item.value);
        $('#fullName').val(item.text);
    }

    $('#tags').typeahead({
        ajax : '<?php echo base_url()?>index.php/approvers/autoCompleteAjax',
         displayField : 'name',
         onSelect : displayResult
    });

    $('#tags').keyup(function() {
        if($(this).val() == "") {
            $('#approver-name').html('');
            $('#hidUserID').val('');
        }
    });

    $('#tags').on('click', function() {
        $(this).css('border-color', '');
    });
    
</script>