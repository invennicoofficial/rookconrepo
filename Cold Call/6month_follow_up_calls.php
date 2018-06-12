<?php
/*
EIS
*/
include ('../include.php');
checkAuthorised('calllog');

if (!empty($_GET['followupcallid'])) {
    $followupcallid = $_GET['followupcallid'];
	$query_update_es = "UPDATE `follow_up_calls` SET `call_today` = 1 WHERE `followupcallid` = '$followupcallid'";
	$result_update_es = mysqli_query($dbc, $query_update_es);
}
?>
<script type="text/javascript">
$(document).on('change', 'select[name="follow_up_status[]"]', function() { selectStatus(this); });
function selectStatus(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=follow_up_status&id="+arr[1]+'&name='+status,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
</script>
</head>
<body>

<?php include ('../navigation.php');

?>

<div class="container">
	<div class="row">
        <div class="table-responsive">

        <h1 class="double-pad-bottom">6 Monthss Follow Up From Last Appointment Date</h1>
        <form name="form_sites" method="post" action="" class="form-inline" role="form">

        <a href='confirmation_call.php?status=Confirmed'><button type="button" class="btn brand-btn mobile-block" >Confirmed Appointments</button></a>&nbsp;&nbsp;
        <a href='confirmation_call.php?status=Cancelled'><button type="button" class="btn brand-btn mobile-block" >Cancelled Appointments</button></a>&nbsp;&nbsp;
        <a href='confirmation_call.php?status=Leftmessage'><button type="button" class="btn brand-btn mobile-block" >Call Again Left Message</button></a>&nbsp;&nbsp;
        <a href='confirmation_call.php?status=Nomessage'><button type="button" class="btn brand-btn mobile-block" >Call Again No Message</button></a>&nbsp;&nbsp;
        <a href='confirmation_call.php?status=Rebooked'><button type="button" class="btn brand-btn mobile-block" >Rebooked</button></a>

        <a href='follow_up_calls.php?status=follow_up'><button type="button" class="btn brand-btn mobile-block active_tab" >Follow Up Calls</button></a>
        <br><br>

        <a href='follow_up_calls.php'><button type="button" class="btn brand-btn mobile-block" >3 Months</button></a>
        <button type="button" class="btn brand-btn mobile-block active_tab" >6 Monthss</button>
        <a href='1year_follow_up_calls.php'><button type="button" class="btn brand-btn mobile-block" >1 Year</button></a>
        <br><br>

        <?php
        $follow_up_query = "SELECT * FROM follow_up_calls WHERE follow_up_date = CURDATE() - INTERVAL 6 MONTH";

        $result = mysqli_query($dbc, $follow_up_query);
        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo "<table border='2' cellpadding='10' class='table'>";
            echo "<tr>
            <th>Patient Last Appt Date</th>
            <th>Patient</th>
            <th>Phone</th>
            <th>Therapist</th>
            <th>Follow Up Status</th>
            <th>Call Today</th>
            ";
            echo "</tr>";
        } else {
            echo "<h2>No Record Found.</h2>";
        }

        while($row = mysqli_fetch_array( $result ))
        {
            echo '<tr>';
            echo '<td>' . $row['follow_up_date'] . '</td>';
            echo '<td>' . get_contact($dbc, $row['patientid']) . '</td>';
            echo '<td>' . get_contact_phone($dbc, $row['patientid']) . '</td>';
            echo '<td>' . get_contact($dbc, $row['therapistsid']) . '</td>';
            ?>
            <td data-title="Status">
                <select data-placeholder="Choose a Status..." name="follow_up_status[]" id="status_<?php echo $row['followupcallid']; ?>" class="chosen-select-deselect form-control input-sm">
                    <option value=""></option>
                    <option value="Appointment Made" <?php if ($row['follow_up_status'] == "Appointment Made") { echo " selected"; } ?> >Appointment Made</option>
                    <option value="Booked Follow Up Call" <?php if ($row['follow_up_status'] == "Booked Follow Up Call") { echo " selected"; } ?> >Booked Follow Up Call</option>
                    <option value="Do not Call Again" <?php if ($row['follow_up_status'] == "Do not Call Again") { echo " selected"; } ?> >Do not Call Again</option>
                    <option value="Call Again Left Message" <?php if ($row['follow_up_status'] == "Call Again Left Message") { echo " selected"; } ?> >Call Again Left Message</option>
                    <option value="Call Again No Message" <?php if ($row['follow_up_status'] == "Call Again No Message") { echo " selected"; } ?> >Call Again No Message</option>
                </select>
            </td>
            <?php
            echo '<td>';
            if($row['call_today'] == 0) {
                echo '<a href="follow_up_calls.php?followupcallid='.$row['followupcallid'].'"><img src="'.WEBSITE_URL.'/img/blank_star.png" onclick="return confirm(\'Are you sure?\')" width="32" height="32" border="0" alt=""></a>';
            } else {
                echo '<img src="'.WEBSITE_URL.'/img/filled_star.png" width="32" height="32" border="0" alt="">';
            }
            echo '</td>';
            echo "</tr>";
        }

        echo '</table>';

        ?>
        <a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn">Back</a>

        </div>
	</div>
</div>

<?php include ('../footer.php'); ?>