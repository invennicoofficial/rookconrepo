<?php
include ('../database_connection.php');

if($_GET['fill'] == 'section') {
    $heading_number = $_GET['heading_number'];
    $category = $_GET['category'];

	$query = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT heading FROM patientform WHERE heading_number = '$heading_number' AND category = '$category'"));
    echo $query['heading'];
}

if($_GET['fill'] == 'subsection') {
    $sub_heading_number = $_GET['sub_heading_number'];
    $category = $_GET['category'];

	$query = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sub_heading FROM patientform WHERE sub_heading_number = '$sub_heading_number' AND category = '$category'"));
    echo $query['sub_heading'];
}
?>