<?php

include_once('../database_connection.php');

$appdate = explode(' ', $_GET['appdate']);
$endappdate = explode(' ', $_GET['endappdate']);
$therapistsid = $_GET['therapistid'];
$apptime = $appdate[1] . ' ' . $appdate[2];
$endapptime = $endappdate[1] . ' ' . $endappdate[2];

$appupdatetime = date("H:i:s", strtotime($apptime));
$endappupdatetime = date("H:i:s", strtotime($endapptime));

$appupdatedatetime = $appdate[0] . ' ' . $appupdatetime;
$endappupdatedatetime = $endappdate[0] . ' ' . $endappupdatetime;

$get_booking = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT bookingid FROM booking WHERE 
                                                therapistsid = " . $therapistsid ." AND ((appoint_date >= '" . $appupdatedatetime . "' AND appoint_date <'" . $endappupdatedatetime
                                                . "' AND end_appoint_date >= '" . $appupdatedatetime . "' AND end_appoint_date >='" . $endappupdatedatetime . "') 
                                                OR (appoint_date < '" . $appupdatedatetime . "' AND appoint_date <'" . $endappupdatedatetime
                                                . "' AND end_appoint_date >= '" . $appupdatedatetime . "' AND end_appoint_date <'" . $endappupdatedatetime . "')
                                                OR (appoint_date < '" . $appupdatedatetime . "' AND appoint_date <'" . $endappupdatedatetime
                                                . "' AND end_appoint_date > '" . $appupdatedatetime . "' AND end_appoint_date >'" . $endappupdatedatetime . "')
                                                OR (appoint_date > '" . $appupdatedatetime . "' AND appoint_date >'" . $endappupdatedatetime
                                                . "' AND end_appoint_date < '" . $appupdatedatetime . "' AND end_appoint_date <'" . $endappupdatedatetime . "')
                                                OR (appoint_date >= '" . $appupdatedatetime . "' AND appoint_date <'" . $endappupdatedatetime
                                                . "' AND end_appoint_date >= '" . $appupdatedatetime . "' AND end_appoint_date <'" . $endappupdatedatetime . "'))"
                                                
                                              ));
                                        
if(isset($get_booking) && count($get_booking) > 0)
    echo 1;
else
    echo 0;
