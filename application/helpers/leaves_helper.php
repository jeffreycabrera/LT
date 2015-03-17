<?php

define('WEEKS_IN_A_YEAR', 52);
define('DAYS_INTERVAL', 14);

function get_total_leaves() {
	return WEEKS_IN_A_YEAR / 2;
}

function get_earnings($total) {
	return $total / get_total_leaves();
}

function compute_pto_earnings($earning, $balance, $pto_earned) {
	/**
	1. For every 14 days, employee is entitled X leave
		X = 20 / 26
	2. Get the TOTAL SUM of all the X leaves.
	Dont add X to the TOTAL SUM only if the user used his LEAVE
		- check the DB if the user used his leave for the current week
	*/
	list($year, $month, $day) = explode("-", date("Y-m-d"));

	$time = strtotime('1 January '. $year, time());
    $time += (1-date('d', $time))*24*3600;

    $currentDate_intime = strtotime($day ." ". date('F', mktime(0, 0, 0, $month, 10)) ." ". $year, time());
	$currentDate_intime += 0*24*3600;

	for ($wk = 1; $wk <= get_total_leaves(); $wk++) {
		if ($wk > 1) {
			$time += 1*24*3600;
		}

	    $sdate_intime = $time;
	    $time += 13*24*3600;
    	$edate_intime = $time;

    	if ($currentDate_intime >= $edate_intime){
    		$pto_earned = $pto_earned + $earning;
    	}
	}

	return round($pto_earned, 2);
}

function getPTO_earned($PTO, $PTOBalance){
	$earning = get_earnings($PTO);

	$earned = compute_pto_earnings($earning, $PTOBalance, 0);
	return $earned;
}

function getLeaveRequest($leaves){
	$leavesCount = COUNT($leaves);
	$leavesRequest = 0;

	for($x = 0; $x < $leavesCount; $x++) {
		$startDate = $leaves[$x]['StartDate'];
		$endDate = $leaves[$x]['EndDate'];

		if ($leaves[$x]['HalfDay']==1 && $startDate==$endDate){
			$LeaveRequest = 0.50;
		} else if ($leaves[$x]['LWOP']==1) {
			$LeaveRequest = 0;
		} else {
			$LeaveRequest = date_diff(date_create($startDate), date_create($endDate));
			$LeaveRequest = $LeaveRequest->format('%a')+1;
		}

		$leavesRequest += $LeaveRequest;
	}

	return $leavesRequest;
}
?>