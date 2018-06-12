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
</style>
<?php
function manual_checklist_pp($dbc, $td_height, $img_height, $img_width, $type, $category) {
    ?>

    <table class="tbl-orient">
    <?php
    $contactid = $_SESSION['contactid'];
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT $type FROM field_config_manuals"));
    $value_config = ','.$get_field_config[$type].',';
    if (empty($get_field_config)) {
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `field_config_hr_manuals` WHERE `tab` = '$type' AND `category` = '$category'"));
        $value_config = ','.$get_field_config['fields'].',';
    }

    //echo "SELECT * FROM manuals WHERE deleted = 0 AND manual_type='$type' AND category='$category' ORDER BY category, heading_number, sub_heading_number";

    //$result = mysqli_query($dbc, "SELECT * FROM manuals WHERE deleted = 0 AND manual_type='$type' AND category='$category' ORDER BY category, LENGTH(heading_number), LENGTH(sub_heading_number)");

    $result = mysqli_query($dbc, "SELECT * FROM manuals WHERE deleted = 0 AND manual_type='$type' AND category='$category' ORDER BY category, INET_ATON(heading_number), INET_ATON(sub_heading_number)");

    $status_1 = '';
    $status_2 = '';
    $test = 0;
    $loop = 0;
    while($row = mysqli_fetch_array($result)) {
        $manualtypeid = $row['manualtypeid'];

        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM manuals_staff WHERE manualtypeid='$manualtypeid' AND staffid='$contactid' ORDER by manualstaffid DESC"));
        $staff_status = $get_staff['manualstaffid'];

        $checked = $get_staff['done']==1 ? 'checked' : '';

        $status = '';
        $deadline = $row['deadline'];
        $today = date('Y-m-d');

        if($staff_status == '') {
            $status = '<span style="color:blue;">New</span>';
        }

        if(($staff_status == '') && ($today > $deadline)) {
            $status = '<span style="color:red;">Review Needed</span>';
        }

        if(($staff_status != '') && ($today > $deadline) && ($get_staff['done'] == 0)) {
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
            $loop = 0;
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
    ?>

    <?php

    if($row['third_heading_number'] != '' || $row['third_heading'] != '') {
        if($row['sub_heading_number'] != $status_2) {
            if(($test == 2) || ($test == 1)) {
                echo '</table>';
            }

            echo '<h4 class="tbl-orient" style="height:40px; border-bottom: 2px solid black; padding-top: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;' . $row['sub_heading_number'] .' - '.$row['sub_heading']. '</h4>';
            $status_2 = $row['sub_heading_number'];
            echo '<table class="tbl-orient">';
            $test = 1;
            $loop++;
        } else {
            $test = 2;
        }
    } ?>
    <tr>
        <?php 
		$base_url=WEBSITE_URL;
		if($row['third_heading_number'] == '' && $row['third_heading'] == '') { ?>
        <td height="<?php echo $td_height;?>" width="">
            <?php		
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$base_url."/Manuals/add_manual.php?source=hr&manualtypeid=".$manualtypeid."&type=".$type."&action=view&category_pnp=".$category."'>".$row['sub_heading_number'].'&nbsp;&nbsp;'.$row['sub_heading']."</a>";
            ?>&nbsp;&nbsp;
        </td>
        <?php } else { ?>
        <td height="<?php echo $td_height;?>" width="">
            <?php
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$base_url."add_manual.php?manualtypeid=".$manualtypeid."&type=".$type."&action=view'>".$row['third_heading_number'].'&nbsp;&nbsp;'.$row['third_heading']."</a>";
            ?>&nbsp;&nbsp;
        </td>
        <?php } ?>
        <?php if (strpos($value_config, ','."Status".',') !== FALSE) { ?>
        <td height="<?php echo $td_height;?>" width="15%">
            <?php
                echo $status;
            ?>&nbsp;&nbsp;
        </td>
        <td height="<?php echo $td_height;?>" width="17%">
            <?php
                echo 'Revised '.$row['last_edited'];
            ?>&nbsp;&nbsp;
        </td>
        <?php } ?>
        <td height="<?php echo $td_height;?>" width="2%">
            <?php
                echo "<a href='".WEBSITE_URL."/Manuals/add_manual.php?manualtypeid=".$manualtypeid."&type=".$type."&action=config'>Configure/Edit</a> | <a href=\"".WEBSITE_URL."/delete_restore.php?action=delete&type=".$type."&category=".$_GET['category']."&manualtypeid=".$manualtypeid."\" onclick=\"return confirm('Are you sure?')\">Archive</a>";
            ?>&nbsp;&nbsp;
        </td>
    </tr>
    <?php }
    if(($loop == 1) || ($test == 2) || ($test == 1)) {
        echo '</table>';
    }
    ?>

<?php } ?>

<?php
function manual_checklist_m($dbc, $td_height, $img_height, $img_width, $type, $category) {
    ?>

    <table class="tbl-orient">
    <?php
    $contactid = $_SESSION['contactid'];
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT $type FROM field_config_manuals"));
    $value_config = ','.$get_field_config[$type].',';
    if (empty($get_field_config)) {
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `field_config_hr_manuals` WHERE `tab` = '$type' AND `category` = '$category'"));
        $value_config = ','.$get_field_config['fields'].',';
    }

    //echo "SELECT * FROM manuals WHERE deleted = 0 AND manual_type='$type' AND category='$category' ORDER BY category, heading_number, sub_heading_number";

    //$result = mysqli_query($dbc, "SELECT * FROM manuals WHERE deleted = 0 AND manual_type='$type' AND category='$category' ORDER BY category, LENGTH(heading_number), LENGTH(sub_heading_number)");

    $result = mysqli_query($dbc, "SELECT * FROM manuals WHERE deleted = 0 AND manual_type='$type' AND category='$category' ORDER BY category, INET_ATON(heading_number), INET_ATON(sub_heading_number)");

    $status_1 = '';
    $status_2 = '';
    $test = 0;
    $loop = 0;
    while($row = mysqli_fetch_array($result)) {
        $manualtypeid = $row['manualtypeid'];

        $get_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM manuals_staff WHERE manualtypeid='$manualtypeid' AND staffid='$contactid' ORDER by manualstaffid DESC"));
        $staff_status = $get_staff['manualstaffid'];

        $checked = $get_staff['done']==1 ? 'checked' : '';

        $status = '';
        $deadline = $row['deadline'];
        $today = date('Y-m-d');

        if($staff_status == '') {
            $status = '<span style="color:blue;">New</span>';
        }

        if(($staff_status == '') && ($today > $deadline)) {
            $status = '<span style="color:red;">Review Needed</span>';
        }

        if(($staff_status != '') && ($today > $deadline) && ($get_staff['done'] == 0)) {
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
            $loop = 0;
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
    ?>

    <?php

    if($row['third_heading_number'] != '' || $row['third_heading'] != '') {
        if($row['sub_heading_number'] != $status_2) {
            if(($test == 2) || ($test == 1)) {
                echo '</table>';
            }

            echo '<h4 class="tbl-orient" style="height:40px; border-bottom: 2px solid black; padding-top: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;' . $row['sub_heading_number'] .' - '.$row['sub_heading']. '</h4>';
            $status_2 = $row['sub_heading_number'];
            echo '<table class="tbl-orient">';
            $test = 1;
            $loop++;
        } else {
            $test = 2;
        }
    } ?>
    <tr>
        <?php 
        $base_url=WEBSITE_URL;
        if($row['third_heading_number'] == '' && $row['third_heading'] == '') { ?>
        <td height="<?php echo $td_height;?>" width="">
            <?php       
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$base_url."/HR/add_hr_manual.php?manualtypeid=".$manualtypeid."&type=".$type."&action=view&category=".$category."'>".$row['sub_heading_number'].'&nbsp;&nbsp;'.$row['sub_heading']."</a>";
            ?>&nbsp;&nbsp;
        </td>
        <?php } else { ?>
        <td height="<?php echo $td_height;?>" width="">
            <?php
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='".$base_url."/HR/add_hr_manual.php?manualtypeid=".$manualtypeid."&type=".$type."&action=view'>".$row['third_heading_number'].'&nbsp;&nbsp;'.$row['third_heading']."</a>";
            ?>&nbsp;&nbsp;
        </td>
        <?php } ?>
        <?php if (strpos($value_config, ','."Status".',') !== FALSE) { ?>
        <td height="<?php echo $td_height;?>" width="15%">
            <?php
                echo $status;
            ?>&nbsp;&nbsp;
        </td>
        <td height="<?php echo $td_height;?>" width="17%">
            <?php
                echo 'Revised '.$row['last_edited'];
            ?>&nbsp;&nbsp;
        </td>
        <?php } ?>
        <td height="<?php echo $td_height;?>" width="2%">
            <?php
                echo "<a href='".$base_url."/HR/add_hr_manual.php?manualtypeid=".$manualtypeid."&type=".$type."&category=".$category."&action=config'>Configure/Edit</a> | <a href=\"".WEBSITE_URL."/delete_restore.php?action=delete&type=".$type."&category=".$_GET['category']."&manualtypeid=".$manualtypeid."\" onclick=\"return confirm('Are you sure?')\">Archive</a>";
            ?>&nbsp;&nbsp;
        </td>
    </tr>
    <?php }
    if(($loop == 1) || ($test == 2) || ($test == 1)) {
        echo '</table>';
    }
    ?>

<?php } ?>