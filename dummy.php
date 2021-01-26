<?php
$today = date("Y-m-d");

$datein = date_create("2020-3-17"); // or your date string
date_add($datein, date_interval_create_from_date_string("14 days"));// add number of days
$expire = date_format($datein, "Y-m-d"); //set date format of the result

if ($today <= $expire) {
    echo $expire . " active";
} else {
    echo $expire . " expired";
}
?>