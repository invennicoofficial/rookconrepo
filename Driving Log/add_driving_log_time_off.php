<?php
/*
Add Driving Log Time Off
*/
include ('../include.php');
checkAuthorised('driving_log');
error_reporting(0);

$view_only_mode = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `driving_log_view_only_mode` WHERE `contactid` = '".$_SESSION['contactid']."'"))['view_only_mode'];
include_once('view_only_mode.php');

if (isset($_POST['submit_time_off'])) {
    $start_date = filter_var($_POST['start_date'],FILTER_SANITIZE_STRING);
    $start_time = filter_var($_POST['start_time'],FILTER_SANITIZE_STRING);
    $end_date = filter_var($_POST['end_date'],FILTER_SANITIZE_STRING);
    $end_time = filter_var($_POST['end_time'],FILTER_SANITIZE_STRING);
    $main_office_addy = filter_var($_POST['main_office_addy'],FILTER_SANITIZE_STRING);
    $home_terminal_addy = filter_var($_POST['home_terminal_addy'],FILTER_SANITIZE_STRING);
    $driverid = filter_var($_POST['driverid'],FILTER_SANITIZE_STRING);
    $codriverid = filter_var($_POST['codriverid'],FILTER_SANITIZE_STRING);
    $clientid = filter_var($_POST['clientid'],FILTER_SANITIZE_STRING);

    if (!empty($_POST['timeoffid'])) {
        $timeoffid = $_POST['timeoffid'];
        $query_update = "UPDATE `driving_log_time_off` SET `start_date` = '$start_date', `start_time` = '$start_time', `end_date` = '$end_date', `end_time` = '$end_time', `main_office_addy` = '$main_office_addy', `home_terminal_addy` = '$home_terminal_addy', `driverid` = '$driverid', `codriverid` = '$codriverid', `clientid` = '$clientid' WHERE `timeoffid` = '$timeoffid'";
        $result_update = mysqli_query($dbc, $query_update);
    } else {
        $query_insert = "INSERT INTO `driving_log_time_off` (`start_date`, `start_time`, `end_date`, `end_time`, `main_office_addy`, `home_terminal_addy` , `driverid`, `codriverid`, `clientid`) VALUES ('$start_date', '$start_time', '$end_date', '$end_time', '$main_office_addy', '$home_terminal_addy', '$driverid', '$codriverid', '$clientid')";
        $result_insert = mysqli_query($dbc, $query_insert);
        $timeoffid = mysqli_insert_id($dbc);
    }

    $url = 'driving_log_tiles.php';
    if ($_GET['from_url']) {
        $url = $_GET['from_url'];
    }
    echo '<script type="text/javascript">window.location.href = "'.$url.'";</script>';
}

?>
<script type="text/javascript">
$(document).ready(function() {
    <?php if ($view_only_mode == 1) { ?>
        $('div.container form').find('input,select,button,a,.select2,.chosen-container,ul div').not('.allow_view_only').each(function() {
            $(this).css('pointer-events', 'none');
            if ($(this)[0].tagName == 'TEXTAREA') {
                $(this).parent('div').css('pointer-events', 'none');
            }
        });
    <?php } ?>
    $('[name="submit_time_off"]').on('click', function() {
        if ($("#driverid").val() == '') {
            alert("Please make sure you have selected a Driver.");
            return false;
        }
    });
    $("#driverid").change(function() {
        $.ajax({    //create an ajax request to load_page.php
            type: "GET",
            url: "driving_log_ajax_all.php?fill=startdl&driverid="+this.value,
            dataType: "html",   //expect html to be returned
            success: function(response){
                $('#codriverid').html(response);
                $("#codriverid").trigger("change.select2");
            }
        });
    });
});
</script>

</head>

<body>
<?php include_once ('../navigation.php');
?>
<div class="container">
    <div class="row">

        <h3 class="pull-left" style="margin-top: 0; padding: 0.25em;">Log Time Off</h3>
        <div class="pull-right">View Only: <a href="" class="view_only_button"><img src="../img/icons/switch-<?= $view_only_mode == 1 ? '7' : '6' ?>.png" style="height: 2em;"></a></div>

        <div class="clearfix"></div>
        
        <div class="gap-top triple-gap-bottom"><a href="driving_log_tiles.php" class="btn config-btn">Back to Dashboard</a></div>
        
        <div class="notice double-gap-bottom popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25px"></div>
			<div class="col-sm-16"><span class="notice-name">NOTE:</span> In this section, you can fill the fields with Log Time Off information. After filling in the field, click Submit to store the information in your software.</div>
		</div>

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <?php
            $timeoffid = '';
            $start_date = '';
            $start_time = '';
            $end_date = '';
            $end_time = '';
            $main_office_addy = '';
            $home_terminal_addy = '';
            $driverid = $_SESSION['contactid'];
            $codriverid = '';
            $clientid = '';
            if (!empty($_GET['timeoffid'])) {
                $timeoffid = $_GET['timeoffid'];
                $get_timeoff = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `driving_log_time_off` WHERE `timeoffid` = '$timeoffid'"));

                $start_date = $get_timeoff['start_date'];
                $start_time = $get_timeoff['start_time'];
                $end_date = $get_timeoff['end_date'];
                $end_time = $get_timeoff['end_time'];
                $main_office_addy = $get_timeoff['main_office_addy'];
                $home_terminal_addy = $get_timeoff['home_terminal_addy'];
                $driverid = $get_timeoff['driverid'];
                $codriverid = $get_timeoff['codriverid'];
                $clientid = $get_timeoff['clientid'];
            }
        ?>
            <input type="hidden" name="timeoffid" value="<?= $timeoffid ?>">

            <div class="panel-group" id="accordion2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Fill out driver and co-driver information (if applicable)."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                                Log Time Off Info<span class="glyphicon glyphicon-plus"></span>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_2" class="panel-collapse collapse in">
                        <div class="panel-body">

                            <div class="form-group">
                                <label for="fax_number" class="col-sm-4 control-label">Start Date:</label>
                                <div class="col-sm-8">
                                    <input name="start_date" type="text" value="<?= (!empty($start_date) ? date('Y-m-d', strtotime($start_date)) : '') ?>" class="form-control datepicker">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fax_number" class="col-sm-4 control-label">Start Time:</label>
                                <div class="col-sm-8">
                                    <input name="start_time" type="text" value="<?= (!empty($start_time) ? date('h:i a', strtotime($start_time)) : '') ?>" class="form-control datetimepicker">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fax_number" class="col-sm-4 control-label">End Date:</label>
                                <div class="col-sm-8">
                                    <input name="end_date" type="text" value="<?= (!empty($end_date) ? date('Y-m-d', strtotime($end_date)) : '') ?>" class="form-control datepicker">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fax_number" class="col-sm-4 control-label">End Time:</label>
                                <div class="col-sm-8">
                                    <input name="end_time" type="text" value="<?= (!empty($end_time) ? date('h:i a', strtotime($end_time)) : '') ?>" class="form-control datetimepicker">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fax_number" class="col-sm-4 control-label">Main Office Address:</label>
                                <div class="col-sm-8">
                                    <input name="main_office_addy" type="text" value = "<?= (!empty($main_office_addy) ? $main_office_addy : get_config($dbc, 'main_office_address_dl')) ?>" class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fax_number" class="col-sm-4 control-label">Home Terminal Address:</label>
                                <div class="col-sm-8">
                                    <input name="home_terminal_addy" type="text" value = "<?= $home_terminal_addy ?>" class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="travel_task" class="col-sm-4 control-label">Driver<span class="brand-color">*</span>:</label>
                                <div class="col-sm-8">
                                    <select name="driverid" id="driverid" class="chosen-select-deselect form-control" width="380">
                                        <option value=''></option>
                                        <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND status>0"),MYSQLI_ASSOC));
                                        foreach($query as $id) {
                                            echo "<option ".($driverid == $id ? 'selected' : '')." value='". $id."'>".get_contact($dbc, $id).'</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="travel_task" class="col-sm-4 control-label">Co-Driver:
                                    <span class="popover-examples list-inline">&nbsp;
                                        <a  data-toggle="tooltip" data-placement="top" title="Select person who is seated in front passenger seat"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                                    </span>
                                </label>
                                <div class="col-sm-8">
                                    <select id="codriverid" name="codriverid" class="chosen-select-deselect form-control" width="380">
                                        <option value=''></option>
                                        <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND status>0"),MYSQLI_ASSOC));
                                        foreach($query as $id) {
                                            echo "<option ".($codriverid == $id ? 'selected' : '')." value='". $id."'>".get_contact($dbc, $id).'</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="travel_task" class="col-sm-4 control-label">Customer</span>:
                                    <span class="popover-examples list-inline">&nbsp;
                                        <a  data-toggle="tooltip" data-placement="top" title="Select the customer you are driving for"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                                    </span>
                                </label>
                                <div class="col-sm-8">
                                    <select id="clientid" name="clientid" class="chosen-select-deselect form-control" width="380">
                                        <option value=''></option>
                                        <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category LIKE '%Customer%' OR category LIKE '%Client%' OR category LIKE '%Business%' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
                                        foreach($query as $id) {
                                            $selected = '';
                                            $selected = $id == $clientid ? 'selected = "selected"' : '';
                                            echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4">
                    <p><span class="text-red"><em>Required Fields *</em></span></p>
                </div>
                <div class="col-sm-8"></div>
            </div>
            <div class="clearfix"></div>
            <div class="pull-left"><span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Clicking here will discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <a href="<?= $_GET['from_url'] ? $_GET['from_url'] : 'driving_log_tiles.php' ?>" class="btn brand-btn btn-lg allow_view_only">Back</a>
                <!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
            </div>
            <div class="pull-right"><span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to submit your Time Off."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <button type="submit" name="submit_time_off" value="Submit" class="btn brand-btn btn-lg smt">Submit</button>
            </div>
        </form>
    </div>
</div>

<?php include('../footer.php'); ?>