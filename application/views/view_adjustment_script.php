<script>
$(document).ready(function() {
    // build dataTable:
    var table = $('#employeeList').DataTable({
        responsive: true,
        "bLengthChange": false,
        "sPaginationType": "full_numbers",
        "aoColumnDefs": [{'bSortable': false, 'aTargets': [1]}]
    });
});

$('#employeeList, .details').on('click','.pop-event', function() {
// $("body").on("click", ".pop-event", function(event){
    var data_item = $(this).attr('id'),
        data_item = data_item.split("_");


    var action=data_item[0],
        leave_id=data_item[1];

    var URL = '<?php echo base_url() ?>index.php/adjustment/load_view';
  

    $.ajax({
        url: URL, 
        type: 'POST',
        dataType:'json',
        data: {'action': action, 'user_id': leave_id},
        success: function(response) {
            $('#myModal').html(response.html_view);
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            })
        }
    });
}); 

</script>