<?php  
function dateformat($date_param){
    $date = new DateTime($date_param);
    $date = $date->format('Y-m-d');

    return $date;
}

function dateToTime($date){
	$date = dateformat($date);
	list($year, $month, $day) = explode("-", $date);

	$time = strtotime($day ." ". date('F', mktime(0, 0, 0, $month, 10)) ." ". $year, time());
    $day = date('d', $time);
    $time += 0*24*3600;

    return $time;
}

function summary($str, $limit=30, $strip = false) {
    $str = ($strip == true)?strip_tags($str):$str;
    if (strlen($str) > $limit) {
        $str = substr($str, 0, $limit - 3);
        return (substr($str, 0, strrpos($str, ' ')).'...');
    }
    return trim($str);
}

function userID_generator($firstName, $middleName, $lastName) {
    $userID = '';

    $firstName = explode(' ', $firstName);
    foreach ($firstName as $name) {
        $userID .= strtoupper($name[0]);
    }

    $userID .= strtoupper($middleName[0]);
    $userID .= str_replace(' ', '', ucwords($lastName));

    return $userID;
}
?>