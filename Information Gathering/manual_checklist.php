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
function manual_checklist($dbc, $td_height, $img_height, $img_width, $category) {
    ?>

    <table class="tbl-orient">
    <?php
    $contactid = $_SESSION['contactid'];

    $result = mysqli_query($dbc, "SELECT * FROM infogathering WHERE deleted = 0 AND category='$category' ORDER BY category, lpad(heading_number, 100, 0), lpad(sub_heading_number, 100, 0)");

    $status_1 = '';
    $status_2 = '';
    $test = 0;
    $loop = 0;
    while($row = mysqli_fetch_array($result)) {
        $infogatheringid = $row['infogatheringid'];

        $status = '';
        $deadline = $row['deadline'];
        $today = date('Y-m-d');

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

        <?php if($row['third_heading_number'] == '' && $row['third_heading'] == '') {

        ?>
        <td height="<?php echo $td_height;?>" width="20%">
            <?php
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='add_manual.php?infogatheringid=".$infogatheringid."&action=view'>".$row['sub_heading_number'].'&nbsp;&nbsp;'.$row['sub_heading']."</a>";
            ?>&nbsp;&nbsp;
        </td>
        <?php } else { ?>
        <td height="<?php echo $td_height;?>" width="20%">
            <?php
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='add_manual.php?infogatheringid=".$infogatheringid."&action==view'>".$row['third_heading_number'].'&nbsp;&nbsp;'.$row['third_heading']."</a>";
            ?>&nbsp;&nbsp;
        </td>
        <?php } ?>
        <td height="<?php echo $td_height;?>" width="8%">
            <?php
                echo $status;
            ?>&nbsp;&nbsp;
        </td>
        <td height="<?php echo $td_height;?>" width="10%">
            <?php
                echo 'Revised '.$row['last_edited'];
            ?>&nbsp;&nbsp;
        </td>
        <td height="<?php echo $td_height;?>" width="7%">
            <?php
                echo "<a href='add_manual.php?infogatheringid=".$infogatheringid."&action=config'>Configure/Edit</a> | <a href='add_manual.php?infogatheringid=".$infogatheringid."&action=delete'>Archive</a> ";
            ?>&nbsp;&nbsp;
        </td>
    </tr>
    <?php }
    if(($loop == 1) || ($test == 2) || ($test == 1)) {
        echo '</table>';
    }
    ?>

<?php }
?>