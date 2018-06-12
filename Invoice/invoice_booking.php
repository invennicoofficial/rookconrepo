<?php
// For Booking //
$next_appointment = $_POST['next_appointment'];

$get_roomid = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT id FROM mrbs_room WHERE room_name='$staff'"));

$patientstatus = 'Booked Unconfirmed';
$room_id = $get_roomid['id'];
$timestamp = date('Y-m-d H:i:s');
$created_by = $_SESSION['first_name'].' '.$_SESSION['last_name'];

$injury_data = get_all_from_injury($dbc, $injuryid, 'injury_name').' : '.get_all_from_injury($dbc, $injuryid, 'injury_type'). ' - '.get_all_from_injury($dbc, $injuryid, 'injury_date');

for($i = 0; $i < count($_POST['block_appoint_date']); $i++) {
    $block_appoint_date = $_POST['block_appoint_date'][$i];
    $block_end_appoint_date = $_POST['block_end_appoint_date'][$i];
    $appointtype = $_POST['appointtype'][$i];

    $block_start_time = strtotime($block_appoint_date);
    $block_end_time = strtotime($block_end_appoint_date);

    if($block_appoint_date != '') {
        $block_booking = 1;

        $query_insert_cal = "INSERT INTO `mrbs_entry` (`patient`, `injury`, `patientstatus`, `start_time`, `end_time`, `room_id`, `timestamp`, `type`, `create_by`) VALUES ('$patients', '$injury_data', '$patientstatus', '$block_start_time', '$block_end_time', '$room_id', '$timestamp', '$appointtype', '$created_by')";
        $result_insert_cal = mysqli_query($dbc, $query_insert_cal);
        $calid = mysqli_insert_id($dbc);
        $query_insert_booking = "INSERT INTO `booking` (`today_date`, `patientid`, `injuryid`, `therapistsid`, `appoint_date`, `end_appoint_date`, `calid`, `type`, `create_by`) VALUES ('$today_date', '$patientid', '$injuryid', '$therapistsid', '$block_appoint_date', '$block_end_appoint_date', '$calid', '$appointtype', '$created_by')";
        $result_insert_booking = mysqli_query($dbc, $query_insert_booking);
    }
}
// For Booking //