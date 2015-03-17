<div class="leave-tracker-template">
    <div class="row requestLeave">
        <div class="col-md-8"><h2><small>PTO summary</small></h2></div>
        <div class="col-md-4" style="text-align: right;"><button id="requestLeave_" class="btn btn-primary pop-event">Request For A Leave</button></div>
    </div>
        
    <div class="row">
        <div class="col-md-4">
        <table class="table col-md-4" cellspacing="0">
        <tr>
            <td>Total PTO</td>
            <td>: <?php echo number_format($user_summary['PTO'], 0); if (number_format($user_summary['PTO'], 2) > 1) { echo " days"; } else { echo " day"; } ?></td>
        </tr>
        <tr>
            <td>Number of PTO Earned</td>
            <td>: <?php echo number_format($user_summary['LeavesEarned'], 2); if (number_format($user_summary['LeavesEarned'], 2) > 1) { echo " days"; } else { echo " day"; } ?></td>
        </tr>
        
        
        <tr>
            <td>Year End Balance</td>
            <td>: <?php echo number_format($user_summary['PTOBalance'], 2); if (number_format($user_summary['PTOBalance'], 2) > 1) { echo " days"; } else { echo " day"; }?></td>
        </tr>
		
        </table>
        </div>
        <div class="col-md-4">
            <table class="table col-md-4" cellspacing="0">
                <tr>
                    <td>Number of PTO Requested</td>
                    <td>: <?php echo number_format($user_summary['LeavesRequest'], 2); if (number_format($user_summary['LeavesRequest'], 2) > 1) { echo " days"; } else { echo " day"; } ?></td>
                </tr>
                <tr>
                    <td>Number of PTO Taken</td>
                    <td>: <?php echo number_format($user_summary['LeavesTaken'], 2); if (number_format($user_summary['LeavesTaken'], 2) > 1) { echo " days"; } else { echo " day"; } ?></td>
                </tr>
                <tr>
                    <td>Number of LWOP Taken</td>
                    <td>: <?php echo number_format($user_summary['LWOP'], 2); if (number_format($user_summary['LWOP'], 2) > 1) { echo " days"; } else { echo " day"; } ?></td>
                </tr>
                <tr style="font-size:15px;">
                    <td><strong>Available Balance</strong></td>
                    <td><strong>: <?php echo number_format($user_summary['AvailBal'], 2); if (number_format($user_summary['AvailBal'], 2) > 1) { echo " days"; } else { echo " day"; }?></strong></td>
                </tr>
            </table>
        </div>
        <div class="col-md-4">
            <table class="table col-md-4" cellspacing="0">
                <tr>
                    <td>Approvers</td>
                    <td>
                        <?php 
                            $approverscount = count($user_approvers);
                            for($x = 0; $x < $approverscount; $x++) {
                                echo ": ". $user_approvers[$x]['ApproverName'] ."<br>";
                            }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <table id="leaves" class="table table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Date Filed</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Reason</th>
            <th>LWOP</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
        $leavescount = count($leave_list);

        for($x = 0; $x < $leavescount; $x++) {
        ?>
        
        <tr>
            <td><?php echo dateformat($leave_list[$x]['DateFiled']) ?></td>
            <td><?php echo dateformat($leave_list[$x]['StartDate']) ?></td>
            <td><?php echo dateformat($leave_list[$x]['EndDate']) ?></td>
            <td><?php echo summary($leave_list[$x]['Purpose']) ?></td>
            <td>
            <?php
                if ($leave_list[$x]['LWOP'] == '1'){
                    echo "Yes";
                }
                else{
                    echo "No";
                }
            ?>
            </td>
            <?php
                $current_twoDayAhead = date('Y-m-d', strtotime("+2 days"));
                $current_oneDayAhead = date('Y-m-d', strtotime("+1 days"));

                if (is_null($leave_list[$x]['Status'])){
                    if (strtotime($leave_list[$x]['StartDate']) == strtotime($current_twoDayAhead) || strtotime($current_oneDayAhead) == strtotime($leave_list[$x]['StartDate'])){
                        echo "<td class=\"warning\">Follow-up</td>";
                    }else{
                        echo "<td class=\"warning\">Pending</td>";
                    }
                }elseif ($leave_list[$x]['Status'] == "1") {
                    echo "<td class=\"warning\">Processing</td>";
                }elseif ($leave_list[$x]['Status'] == "0"){
                    echo "<td class=\"danger\">Declined</td>";
                }elseif ($leave_list[$x]['Status'] == "2"){
                    echo "<td class=\"success\">Accepted</td>";
                }
            ?>
            <td style="text-align:center;">
                <?php 
                    if (!is_null($leave_list[$x]['Status'])){ 
                        echo '<button type="button" class="btn btn-link pop-event" id="detail_'. $leave_list[$x]['LeaveTableID'] .'">Detail</button> ';
                    }else{ 
                        echo '<button type="button" class="btn btn-primary pop-event" id="edit_'. $leave_list[$x]['LeaveTableID'] .'">Edit</button> ';
                    }

                    if ((strtotime($leave_list[$x]['StartDate']) >= strtotime(date("Y-m-d")) && $leave_list[$x]['Status'] != '0')){
                        echo '<button type="button" class="btn btn-danger pop-event" id="delete_'. $leave_list[$x]['LeaveTableID'] .'">Delete</button> ';
                    }
                ?>
            </td>
        </tr>

        <?php
        }
    ?>
    </tbody>
    </table>
</div>

<div id="myModal" class="modal fade"></div>

    