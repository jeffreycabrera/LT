
<?php
echo validation_errors();
?>
<form name="leaveForm" id="leaveForm" class="form-horizontal" role="form">

    <input type="hidden" id="hidUserID" name="userID">
    <div class="col-sm-12" style="text-align:right;"><button type="button" id="btnDelete" class="btn btn-danger">Delete User</button>&nbsp;&nbsp;&nbsp;&nbsp;</div>
    <div class="col-sm-12">
        </br>
        <div class="col-sm-4">
            Lastname
            <input type="text" class="form-control" id="txtLN" name="LastName">
        </div>

        <div class="col-sm-4">
            Firstname
            <input type="text" class="form-control" id="txtFN" name="FirstName">
        </div>
        <div class="col-sm-4">
            Middlename
            <input type="text" class="form-control" id="txtMN" name="MiddleName">
            <br>
        </div>

    </div>
    <div class="col-sm-12">
        <div class="col-sm-12">
            Email
            <input type="text" class="form-control" id="txtEmail" name="Email"> 
        </div>
    </div>
    <div class="col-sm-12">
        <br>
        <div class="col-sm-6">

            Approver 1 :
            <select id='approver1'  class='frm form-control' placeholder='-Select Status-'>
                <?php
                echo "<option value='0'></option>";
                foreach ($approversList as $approver) {
                    echo "<option value='" . $approver['approverID'] . "'>" . $approver['ApproverName'] . "</option>";
                }
                ?>  
            </select>
            <br>
        </div>
        <div class="col-sm-6">
            Approver 2 :
            <select id='approver2'  class='frm form-control' placeholder='-Select Status-'>
                <?php
                echo "<option value='0'></option>";
                foreach ($approversList as $approver) {
                    echo "<option value='" . $approver['approverID'] . "'>" . $approver['ApproverName'] . "</option>";
                }
                ?>  
            </select>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-6">
            Number of PTO per Year :
            <input type="text" class="form-control" id="txtPTO"  name='txtPTO'>
        </div>
        <div class="col-sm-6" style="text-align:right;">
            <input id="isApprover" name='isApprover' type="checkbox"> Assign as Approver
        </div>
    </div>    
</form>