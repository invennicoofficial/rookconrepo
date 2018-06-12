<style>
@media (max-width:1599px) {
.tbl-orient input {
			position:absolute;
			left:20px;
}
}
.tbl-orient a {color:black;
			Font-weight:bold;
}
.tbl-orient a:hover {
	text-shadow:none;
	color:black;
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
function manual_checklist($dbc, $contactid, $td_height, $img_height, $img_width, $type) {
    ?>
        <span class="pull-right">
            <img src="<?php echo WEBSITE_URL;?>/img/block/red.png" width="32" height="32" border="0" alt=""> Deadline Gone
            <img src="<?php echo WEBSITE_URL;?>/img/block/green.png" width="32" height="32" border="0" alt=""> Deadline Today
        </span><br><br>

    <table class="tbl-orient">
    <?php
    $result = mysqli_query($dbc, "SELECT * FROM manuals WHERE deleted = 0 AND assign_staff  LIKE '%," . $contactid . ",%' AND manual_type='$type' ORDER BY category, heading_number, sub_heading_number");
    $status_loop = '';

    //$cat =  mysqli_fetch_assoc($result);
    //echo '<h3>'.$cat['category'].'</h3>';
    while($row = mysqli_fetch_array($result)) {

        if($row['heading'] != $status_loop) {
            echo '<table class="tbl-orient">';
            echo '<br><h4 class="tbl-orient" style="height:40px; border:3px solid blue;">' . $row['heading_number'] .' - '.$row['heading']. '</h4>';
            $status_loop = $row['heading'];
        }

        $manualtypeid = $row['manualtypeid'];

        $deadline = $row['deadline'];
        $today = date('Y-m-d');
        $color = '';

        if($today > $deadline) {
            $color = 'style="background-color: lightcoral;"';
        }
        if($today == $deadline) {
            $color = 'style="background-color: lightgreen;"';
        }
        $get_orientation = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM manuals_staff WHERE staffid='$contactid' AND manualtypeid='$manualtypeid' ORDER by manualstaffid DESC"));
        $checked = $get_orientation['done']==1 ? 'checked' : '';
    ?>
    <tr <?php echo $color;?> >
        <td height="<?php echo $td_height;?>" width="40">
        <?php
            if($checked == 'checked') {
                echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="">';
            }
        ?>
        </td>
        <td height="<?php echo $td_height;?>" width="30">
            <?php
                echo "<a href='add_manual.php?manualtypeid=".$manualtypeid."&type=".$type."&action=view'>".$row['sub_heading_number']."</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td height="<?php echo $td_height;?>">
            <?php
                echo "<a href='add_manual.php?manualtypeid=".$manualtypeid."&type=".$type."&action=view'>".$row['sub_heading']."</a>";
            ?>&nbsp;&nbsp;
        </td>
        <td height="<?php echo $td_height;?>" width="50">
            <?php
                echo "<a href='add_manual.php?manualtypeid=".$manualtypeid."&type=".$type."&action=config'>Configuresss/Edit</a>";
            ?>&nbsp;&nbsp;
        </td>
    </tr>
    <?php } ?>

</table>
<?php } ?>