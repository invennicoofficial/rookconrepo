<?php
include_once('../tcpdf/tcpdf.php');
require_once('../phpsign/signature-to-image.php');
error_reporting(0);
?>

<?php
$safety_contactid = $_SESSION['contactid'];

$type = '';
$contactid = '';
$clientid = '';
$other_names = '';
$location = '';
$workerid = '';

$ir1 = '';
$ir2 = '';
$ir3 = '';
$ir4 = '';
$ir5 = '';
$ir6 = '';
$ir7 = '';
$ir8 = '';
$ir9 = '';
$ir10 = '';
$ir11 = '';
$ir12 = '';
$ir13 = '';
$ir14 = '';
$ir15 = '';
    
$equipmentid = '';
$other_driver_name = '';
$other_driver_address = '';
$other_driver_licence = '';
$other_driver_ins_company = '';
$other_driver_ins_policy = '';
$other_owner_name = '';
$other_owner_address = '';
$witness_names = '';
$assign_followup = '';
$assign_corrective = '';

$upload_document = '';
$action_taken = '';
$follow_up_name = '#*##*#';
$follow_up_title = 'Parent/Guardian#*#Doctor#*#Other';
$follow_up_date = '#*##*#';
$follow_up_who = '#*##*#';
$recommendations = '';
$sign = '';
$today_date = date('Y-m-d');
$reported_by = $_SESSION['contactid'];
$comments = '';
$supervisor = '';
$supervisor_sign = '';
$coordinator = '';
$coordinator_sign = '';
$coordinator_comments = '';
$funder_name = '';
$funder_contacted = '';
$incident_date = '';
$director = '';
$director_sign = '';

if(!empty($form)) {
    $type = $form;
}

if(!empty($_GET['formid'])) {
    $incidentreportid = $_GET['formid'];
    $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM incident_report WHERE incidentreportid='$incidentreportid'"));

    $type = $get_contact['type'];
    $contactid = $get_contact['contactid'];
    $clientid = $get_contact['clientid'];
    $other_names = $get_contact['other_names'];
    $location = $get_contact['location'];
    $workerid = $get_contact['workerid'];
    
    $ir1 = $get_contact['ir1'];
    $ir2 = $get_contact['ir2'];
    $ir3 = $get_contact['ir3'];
    $ir4 = $get_contact['ir4'];
    $ir5 = $get_contact['ir5'];
    $ir6 = $get_contact['ir6'];
    $ir7 = $get_contact['ir7'];
    $ir8 = $get_contact['ir8'];
    $ir9 = $get_contact['ir9'];
    $ir10 = $get_contact['ir10'];
    $ir11 = $get_contact['ir11'];
    $ir12 = $get_contact['ir12'];
    $ir13 = $get_contact['ir13'];
    $ir14 = $get_contact['ir14'];
    $ir15 = $get_contact['ir15'];
    
    $equipmentid = $get_contact['equipmentid'];
    $other_driver_name = $get_contact['other_driver_name'];
    $other_driver_address = $get_contact['other_driver_address'];
    $other_driver_licence = $get_contact['other_driver_licence'];
    $other_driver_ins_company = $get_contact['other_driver_ins_company'];
    $other_driver_ins_policy = $get_contact['other_driver_ins_policy'];
    $other_owner_name = $get_contact['other_owner'];
    $other_owner_address = $get_contact['other_address'];
    $witness_names = $get_contact['witness_names'];
    $assign_followup = $get_contact['assign_followup'];
    $assign_corrective = $get_contact['assign_corrective'];
    
    $action_taken = $get_contact['action_taken'];
    $follow_up_name = $get_contact['followup_contact_name'];
    $follow_up_title = $get_contact['followup_contact_title'];
    $follow_up_date = $get_contact['followup_contact_date'];
    $follow_up_who = $get_contact['followup_contact_who'];
    $recommendations = $get_contact['recommendations'];
    $sign = $get_contact['sign'];
    $today_date = $get_contact['today_date'];
    $reported_by = $get_contact['reported_by'];
    $comments = $get_contact['comments'];
    $supervisor = $get_contact['supervisor'];
    $supervisor_sign = $get_contact['supervisor_sign'];
    $coordinator = $get_contact['coordinator'];
    $coordinator_sign = $get_contact['coordinator_sign'];
    $coordinator_comments = $get_contact['coordinator_comments'];
    $funder_name = $get_contact['funder_name'];
    $funder_contacted = $get_contact['funder_contacted'];
    $incident_date = strtotime($get_contact['incident_date']);
    $director = $get_contact['director'];
    $director_sign = $get_contact['director_sign'];
    $incident_date_date = date('Y-m-d', $incident_date);
    $incident_date_time = date('h:i a', $incident_date);
    $upload_document = $get_contact['upload_document'];
}

$get_type_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT `incident_report`, `hide_fields`, `report_info` FROM `field_config_incident_report` WHERE `row_type`='$type' AND '$type'!='' UNION SELECT GROUP_CONCAT(`incident_report`), '', '' FROM `field_config_incident_report` WHERE IFNULL(`incident_report`,'') != ''"));
$value_config = ','.$get_type_config['incident_report'].',';
$hide_config_list = explode('#*#',$get_type_config['hide_fields']);
$hide_config = ',';
foreach($hide_config_list as $list_fields) {
    $list_fields = explode(':|',$list_fields);
    foreach(explode(',',ROLE) as $mylevel) {
        if($mylevel == $list_fields[0]) {
            $hide_config .= ','.$list_fields[1].',';
        }
    }
}
$report_info = $get_type_config['report_info'];
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_incident_report WHERE row_type=''"));

?>
<?php if(!empty($report_info)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_report_info" >
                    Incident Report Information<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_report_info" class="panel-collapse collapse">
            <div class="panel-body">
                <?= html_entity_decode($report_info) ?>
            </div>
        </div>
    </div>
<?php } ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info" >
                Information<span class="glyphicon glyphicon-plus"></span>
            </a>
        </h4>
    </div>

    <div id="collapse_info" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="form-group">
                <label for="business_street" class="col-sm-4 control-label">Date</label>
                <div class="col-sm-8">
                    <input type="text" name="safety_today_date" value="<?php echo $today_date; ?>" class="form-control" />
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ('../Incident Report/add_incident_report_fields.php'); ?>

<?php if(!empty($_GET['formid'])) {
    $sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$incidentreportid' AND safetyid='$safetyid'");
    $sa_inc=  0;
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_sa = $row_sa['assign_staff'];
        $assign_staff_id = $row_sa['safetyattid'];
        $assign_staff_done = $row_sa['done'];
        ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sa<?php echo $sa_inc;?>" >
                    <?php echo $assign_staff_sa; ?><span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_sa<?php echo $sa_inc;?>" class="panel-collapse collapse">
            <div class="panel-body">

                <?php
                if($assign_staff_done == 0) {
                    $output_name = 'sign_'.$assign_staff_id; ?>
                <?php include ('../phpsign/sign_multiple.php'); ?>

                <?php if (strpos($assign_staff_sa, 'Extra') !== false) { ?>
                   <div class="form-group">
                    <label for="business_street" class="col-sm-4 control-label">Name:</label>
                    <div class="col-sm-8">
                        <input name="assign_staff_<?php echo $assign_staff_id;?>" type="text" class="form-control" />
                    </div>
                  </div>
                <?php } ?>

                <?php } ?>

            </div>
        </div>
    </div>
    <?php $sa_inc++;
    }
}

echo '<input type="hidden" name="safety_contactid" value="'.$safety_contactid.'">';
echo '<input type="hidden" id="sign" name="sign">';
if(!empty($_GET['formid'])) {
    echo '<input type="hidden" name="incidentreportid" value="'.$_GET['formid'].'" />';
}
if(!empty($form)) {
    echo '<input type="hidden" name="type" value="'.$form.'" />';
} ?>

<script type="text/javascript">
    $(document).ready(function(){
        $('#category').prop("disabled", true);
        $('#category').trigger("change.select2");
    });
</script>