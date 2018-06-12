<?php
/*
Dashboard
*/
?>

<script type="text/javascript">
function loadFilters() {
    var contactid = $('[name="contactid"]').val();
    var patientid = $('[name="patientid"]').val();
    var heading = $('[name="heading"]').val();
    var sub_heading = $('[name="sub_heading"]').val();
    var s_start_date = $('[name="s_start_date"]').val();
    var s_end_date = $('[name="s_end_date"]').val();
    var url = '?tab=reporting&contactid='+contactid+'&patientid='+patientid+'&heading='+heading+'&sub_heading='+sub_heading+'&s_start_date='+s_start_date+'&s_end_date='+s_end_date;

    window.location.href = url;
}
</script>
<form name="form_sites" method="post" action="" class="form-inline" role="form">

    <?php
    $tab_names = ['front_desk' => 'Front Desk', 'physiotherapy' => 'Physiotherapy', 'massage' => 'Massage Therapy', 'mvc' => 'MVC/MVA', 'wcb' => 'WCB'];

    $contactid = '';
    $patientid = '';
    $category = '';
    $heading = '';
    $sub_heading = '';
    $status = '';
    $s_start_date = '';
    $s_end_date = '';
    $sub_heading = '';
    $search_query = [];

    if(!empty($_GET['contactid'])) {
        $contactid = $_GET['contactid'];
        $search_query[] = "`staffid` = '$contactid'";
    }
    if(!empty($_GET['patientid'])) {
        $patientid = $_GET['patientid'];
        $search_query[] = "`patientid` = '$patientid'";
    }
    if(!empty($_GET['heading'])) {
        $heading = $_GET['heading'];
        $search_query[] = "`heading` = '$heading'";
    }
    if(!empty($_GET['sub_heading'])) {
        $sub_heading = $_GET['sub_heading'];
        $search_query[] = "`sub_heading` = '$sub_heading'";
    }
    if(!empty($_GET['s_start_date'])) {
        $s_start_date = $_GET['s_start_date'];
        $search_query[] = "`today_date` >= '$s_start_date'";
    }
    if(!empty($_GET['s_end_date'])) {
        $s_end_date = $_GET['s_end_date'];
        $search_query[] = "`today_date` <= '$s_end_date'";
    }
    $search_query = implode(' AND ', $search_query);
    // if (isset($_POST['display_all_asset'])) {
    //     $contactid = '';
    //     $patientid = '';
    //     $category = '';
    //     $heading = '';
    //     $sub_heading = '';
    //     $status = '';
    //     $s_start_date = '';
    //     $s_end_date = '';
    //     $search_query = '';
    // }
    if(!empty($search_query)) {
        $search_query = ' AND '.$search_query;
    }
    ?>

    <div class="form-group" style="width:100%">
        <label for="ship_country" class="col-sm-2 control-label pull-left">Staff:</label>
        <div class="col-sm-4">
            <select data-placeholder="Select a Staff Member..." name="contactid" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND status > 0"),MYSQLI_ASSOC));
                foreach($query as $id) {
                    echo "<option ".($contactid == $id ? 'selected' : '')." value='". $id."'>".get_contact($dbc, $id).'</option>';
                } ?>
            </select>
        </div>
        <label for="ship_zip" class="col-sm-2 control-label">Client:</label>
        <div class="col-sm-4">
            <select data-placeholder="Select a Client..." name="patientid" class="chosen-select-deselect form-control">
              <option value=""></option>
              <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT DISTINCT(pf.`patientid`), c.* FROM `patientform_pdf` as pf LEFT JOIN `contacts` c ON pf.`patientid` = c.`contactid` WHERE pf.`patientid` IS NOT NULL AND pf.`patientid` != '' AND pf.`patientid` != 0 AND c.`contactid` IS NOT NULL AND c.`contactid` != '' AND c.`contactid` != 0"),MYSQLI_ASSOC));
                foreach($query as $id) {
                    echo "<option ".($patientid == $id ? 'selected' : '')." value='". $id."'>".get_contact($dbc, $id).'</option>';
                } ?>
            </select>
        </div>
    </div>
    <div class="form-group" style="width:100%">
        <label for="ship_country" class="col-sm-2 control-label pull-left">Heading:</label>
        <div class="col-sm-4">
            <select data-placeholder="Select a Heading..." name="heading" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT distinct(heading) FROM patientform WHERE deleted=0 ORDER BY heading");
                while($row = mysqli_fetch_array($query)) {
                    if ($heading == $row['heading']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    ?>
                    <option <?php echo $selected; ?> value='<?php echo $row['heading']; ?>' ><?php echo $row['heading']; ?></option>
                <?php }
              ?>
            </select>
        </div>
        <label for="ship_zip" class="col-sm-2 control-label">Sub Heading:</label>
        <div class="col-sm-4">
            <select data-placeholder="Select a Sub Heading..." name="sub_heading" class="chosen-select-deselect form-control">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT distinct(sub_heading) FROM patientform WHERE deleted=0 ORDER BY sub_heading");
                while($row = mysqli_fetch_array($query)) {
                    if ($sub_heading == $row['sub_heading']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    ?>
                    <option <?php echo $selected; ?> value='<?php echo $row['sub_heading']; ?>' ><?php echo $row['sub_heading']; ?></option>
                <?php }
              ?>
            </select>
        </div>
    </div>
    <div class="form-group" style="width:100%">
        <label for="site_name" class="col-sm-2 control-label">Start Date:</label>
        <div class="col-sm-4">
            <input name="s_start_date" type="text" class="datepicker form-control" value="<?php echo $s_start_date; ?>">
        </div>

        <label for="first_name" class="col-sm-2 control-label">End Date:</label>
        <div class="col-sm-4">
            <input name="s_end_date" type="text" class="datepicker form-control" value="<?php echo $s_end_date; ?>">
        </div>
    </div>
    <div class="form-group pull-right double-gap-top">
    	<button type="submit" name="reporting_client" value="Submit" onclick="loadFilters(); return false;" class="btn brand-btn mobile-block">Submit</button>
    	<a href="?tab=reporting" class="btn brand-btn mobile-block">Display All</a>
    </div>
    <div class="clearfix"></div>
    <br><br>
    <!-- <span class="pull-right">
        <img src="<?php echo WEBSITE_URL;?>/img/red.png" width="23" height="23" border="0" alt=""> Deadline Past
        <img src="<?php echo WEBSITE_URL;?>/img/green.png" width="23" height="23" border="0" alt=""> Deadline Today
    </span><br><br> -->
    <?php
    /* Pagination Counting */
    $rowsPerPage = 25;
    $pageNum = 1;

    if(isset($_GET['page'])) {
        $pageNum = $_GET['page'];
    }

    $offset = ($pageNum - 1) * $rowsPerPage;

    $query_check_credentials = "SELECT * FROM `patientform_pdf` pdf LEFT JOIN `patientform` pf ON pdf.`patientformid` = pf.`patientformid` WHERE `deleted` = 0 $search_query ORDER BY `today_date` DESC LIMIT $offset, $rowsPerPage";
    $query = "SELECT COUNT(*) numrows FROM `patientform_pdf` pdf LEFT JOIN `patientform` pf ON pdf.`patientformid` = pf.`patientformid` WHERE `deleted` = 0 $search_query ORDER BY `today_date` DESC";
    $result = mysqli_query($dbc, $query_check_credentials);

    $num_rows = mysqli_num_rows($result);
    if($num_rows > 0) {
        // Added Pagination
        echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
        // Finish Pagination

        echo "<table class='table table-bordered'>";
        echo '<tr class="hidden-xs hidden-sm">
            <th>Staff</th>
            <th>Client</th>
            <th>Topic (Sub Tab)</th>
            <th>Heading</th>
            <th>Sub Section Heading</th>
            <th>Date</th>
            <th>Function</th>
            </tr>';

        while($row = mysqli_fetch_array( $result )) {
            $patientformid = $row['patientformid'];
            $pdf_tab = $tab_names[$row['tab']];
            $pdf_heading = $row['heading'];
            $pdf_sub_heading = $row['sub_heading'];
            $fieldlevelriskid = $row['fieldlevelriskid'];
            $staffid = $row['staffid'];
            $patientid = $row['patientid'];
            $today = date('Y-m-d');
            $pdf_date = $row['today_date'];
            $pdf_url = $row['pdf_path'];

            echo "<tr>";
            echo '<td data-title="Staff">'.get_contact($dbc, $staffid).'</td>';
            echo '<td data-title="Client">'.get_contact($dbc, $patientid).'</td>';
            echo '<td data-title="Topic (Sub Tab)">'.$pdf_tab.'</td>';
            echo '<td data-title="Heading">'.$pdf_heading.'</td>';
            echo '<td data-title="Sub Heading">'.$pdf_sub_heading.'</td>';
            echo '<td data-title="Date">'.$pdf_date.'</td>';
            echo '<td data-title="Function"><a href="'.WEBSITE_URL.'/Treatment/'.$pdf_url.'" target="_blank"><img src="'.WEBSITE_URL.'/img/pdf.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt=""></a>';
            echo "</tr>";
        }

        echo '</table></div>';

        // Added Pagination
        echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
        // Finish Pagination
    } else {
        echo "<h2>No Record Found.</h2>";
    }
    ?>

</form>

<!-- <?php
function patientform_pdf($dbc, $patientformid, $fieldlevelriskid) {
    $form = get_patientform($dbc, $patientformid, 'form');

    if($form == 'Client Business Introduction') {
        $pdf_path = 'client_business_introduction/download/patientform_'.$fieldlevelriskid.'.pdf';
        return $pdf_path;
    }
    if($form == 'Branding Questionnaire') {
        $pdf_path = 'branding_questionnaire/download/patientform_'.$fieldlevelriskid.'.pdf';
        return $pdf_path;
    }
    if($form == 'Website Information Gathering') {
        $pdf_path = 'website_information_gathering_form/download/patientform_'.$fieldlevelriskid.'.pdf';
        return $pdf_path;
    }
    if($form == 'Blog') {
        $pdf_path = 'blog/download/patientform_'.$fieldlevelriskid.'.pdf';
        return $pdf_path;
    }
    if($form == 'Social Media Info Gathering') {
        $pdf_path = 'social_media_info_gathering/download/patientform_'.$fieldlevelriskid.'.pdf';
        return $pdf_path;
    }
    if($form == 'Social Media Start Up Questionnaire') {
        $pdf_path = 'social_media_start_up_questionnaire/download/patientform_'.$fieldlevelriskid.'.pdf';
        return $pdf_path;
    }

}

function patientform_edit($dbc, $patientformid, $fieldlevelriskid) {
    $form = get_patientform($dbc, $patientformid, 'form');

    //if($form == 'Client Business Introduction') {
        $edit_path = '
        <a href ="add_manual.php?patientformid='.$patientformid.'&action=view&formid='.$fieldlevelriskid.'">Edit</a>';
        return $edit_path;
   // }

}
?> -->