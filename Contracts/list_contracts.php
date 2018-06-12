<style>
@media (max-width:1599px) {
.tbl-orient input {
			position:absolute;
			left:20px;
}
}
.tbl-orient a {
			Font-weight:bold;
}
.tbl-orient a:hover {
	text-shadow:none;

}
.tbl-orient {
			background-color:#EFEFEF;
			border-radius: 5px;
			position:relative;
			margin:auto;
			color:black;
			Font-weight:bold;
}
.tbl-orient td {
			border-bottom:1px solid #000146;
			padding:10px;
}
.tbl-orient .bord-right {
    border-right:1px solid #D34345;
}
</style><?php
function list_contracts($dbc, $category, $edit_access, $td_height = '35', $img_height = '20', $img_width = '20') {
    ?>

    <table class="tbl-orient">
    <?php $result = mysqli_query($dbc, "SELECT * FROM `contracts` WHERE deleted = 0 AND category='$category' ORDER BY category, INET_ATON(heading_number), INET_ATON(sub_heading_number), INET_ATON(third_heading_number)");

	$test = 0;
    $status_1 = '';
    $status_2 = '';
	if(mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_array($result)) {
			$contractid = $row['contractid'];

			$staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM contracts_staff WHERE contractid='$contractid' AND staffid='".$_SESSION['contactid']."' ORDER by contractstaffid DESC"));
			$staff_status = $staff['contractstaffid'];

			$checked = $staff['done']==1 ? 'checked' : '';

			$status = '';
			$deadline = $staff['due_date'];
			$today = date('Y-m-d');

			if($staff_status == '') {
				$status = '<span style="color:blue;">New</span>';
			}

			if(($staff_status == '') && ($today > $deadline)) {
				$status = '<span style="color:red;">Review Needed</span>';
			}

			if(($staff_status != '') && ($today > $deadline) && ($staff['done'] == 0)) {
				$status = '<span style="color:red;">Past Due</span>';
			}

			if($checked == 'checked') {
				$status = '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
			}

			if($row['heading_number'] != $status_1) {
				//if($row['third_heading_number'] == '') {
					if(($test == 2) || ($test == 1)) {
						echo '</table>';
					}
				//}
				echo '<h3 class="tbl-orient" style="height:40px; border-bottom: 3px solid black; padding-top: 4px;">&nbsp;' . $row['heading_number'] .' - '.$row['heading']. '</h3>';
				$status_1 = $row['heading_number'];
				if($row['third_heading_number'] == '') {
					echo '<table class="tbl-orient">';
					$test = 1;
				}
			} else {
				if($row['third_heading_number'] == '') {
					$test = 2;
				}
			}
			
			if($row['third_heading_number'] != '' || $row['third_heading'] != '') {
				if($row['sub_heading_number'] != $status_2) {
					if(($test == 2) || ($test == 1)) {
						echo '</table>';
					}

					echo '<h4 class="tbl-orient" style="height:40px; border-bottom: 2px solid black; padding-top: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;' . $row['sub_heading_number'] .' - '.$row['sub_heading']. '</h4>';
					$status_2 = $row['sub_heading_number'];
					echo '<table class="tbl-orient">';
					$test = 1;
				} else {
					$test = 2;
				}
			} ?>
			<tr>
				<?php if($row['third_heading_number'] == '' && $row['third_heading'] == '') { ?>
				<td height="<?php echo $td_height;?>" width="">
					<?php
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='fill_contract.php?contractid=".$contractid."'>".$row['sub_heading_number'].'&nbsp;&nbsp;'.$row['sub_heading']."</a>";
					?>&nbsp;&nbsp;
				</td>
				<?php } else { ?>
				<td height="<?php echo $td_height;?>" width="">
					<?php
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='fill_contract.php?contractid=".$contractid."'>".$row['third_heading_number'].'&nbsp;&nbsp;'.$row['third_heading']."</a>";
					?>&nbsp;&nbsp;
				</td>
				<?php } ?>
				<td height="<?php echo $td_height;?>" width="20%">
					<?php
						echo $status;
					?>&nbsp;&nbsp;
				</td>
				<td height="<?php echo $td_height;?>" width="10%">
					<a href="fill_contract.php?contractid=<?= $contractid ?>">Complete Now</a> |
					<a href="fill_contract.php?view=blank&contractid=<?= $contractid ?>">Print Blank</a>
					<?php if($edit_access == 1) {
						echo " | <a href='add_contract.php?contractid=".$contractid."'>Edit</a> | <a href='assign_contract.php?contractid=".$contractid."'>Assign</a> | <a href=\"".WEBSITE_URL."/delete_restore.php?action=delete&contractid=".$contractid."\" onclick=\"return confirm('Are you sure?')\">Archive</a>";
					} ?>&nbsp;&nbsp;
				</td>
			</tr>
		<?php }
		echo '</table>';
	} else {
		echo "<h3>No Contracts Found</h3>";
	}
} ?>