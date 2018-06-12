<script type="text/javascript">
$(document).ready(function() {
    $('[name="submit_new"]').on('click', function() {
        if ($("#driverid").val() == '') {
            alert("Please make sure you have selected a Driver.");
            return false;
        } else {
            var start_date = '';
            var end_date = '';
            var driverid = $('#driverid').val();
            $.ajax({
                type: "GET",
                url: "driving_log_ajax_all.php?fill=checkoffdays&driverid="+driverid,
                dataType: "html",
                success: function(response){
                    if (response != 'good') {
                        promptLogDaysOff(response, driverid);
                    }
                }
            });
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

function promptLogDaysOff(days, driver) {
    var driverid = driver;
    var days_arr = days.split(',');
    var from_date = days_arr[0];
    var to_date = days_arr[1];
    var message = '';
    if (from_date == to_date) {
        message = 'This driver has no driving log on '+to_date+'. Would you like to log this day as time off?';
    } else {
        message = 'This driver has no driving logs from '+from_date+' to '+to_date+'. Would you like to log these days as time off?';
    }

    if (confirm(message)) {
        $.ajax({
            type: "GET",
            url: "driving_log_ajax_all.php?fill=logdaysoff&driverid="+driverid+"&startdate="+from_date+"&enddate="+to_date,
            dataType: "html",
            success: function(response){
            }
        });
    }
}
</script>

<div class="panel-group form-horizontal" id="accordion2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Fill out driver and co-driver information (if applicable)."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                    Driver/Co-Driver Info<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_2" class="panel-collapse collapse in">
            <div class="panel-body">

                <div class="form-group">
                    <label for="fax_number" class="col-sm-4 control-label">Date:</label>
                    <div class="col-sm-8">
                        <input name="dl_comment" type="text" value = "<?php echo date('Y-m-d'); ?>" disabled class="form-control"/>
                    </div>
                </div>    



                <div class="form-group">
                <label for="travel_task" class="col-sm-4 control-label">Cycle:</label>
                    <div class="col-sm-8">
                        <select name="cycle" class="chosen-select-deselect form-control" width="380">
                            <?php $driving_log_cycle_times = empty(get_config($dbc, 'driving_log_cycle_times')) ? explode(',', 'Cycle 1,Cycle 2') : explode(',', get_config($dbc, 'driving_log_cycle_times'));
                            if (in_array('Cycle 1', $driving_log_cycle_times)) { ?>
                                <option value='Cycle 1(7 days)'>Cycle 1(7 days : 70 hours)</option><?php }
                            if (in_array('Cycle 2', $driving_log_cycle_times)) { ?>
                                <option value='Cycle 2(14 days)'>Cycle 2(14 days : 120 hours)</option><?php }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fax_number" class="col-sm-4 control-label">Main Office Address:</label>
                    <div class="col-sm-8">
                        <input name="main_office_addy" type="text" value = "<?php echo get_config($dbc, 'main_office_address_dl') ?>" class="form-control"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fax_number" class="col-sm-4 control-label">Home Terminal Address:</label>
                    <div class="col-sm-8">
                        <input name="home_terminal_addy" type="text" value = "" class="form-control"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="travel_task" class="col-sm-4 control-label">Driver:<span class="brand-color">*</span></label>
                    <div class="col-sm-8">
                        <select name="driverid" id="driverid" class="chosen-select-deselect form-control" width="380">
                            <option value=''></option>
                            <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND status>0"),MYSQLI_ASSOC));
                            foreach($query as $id) {
                                $logs = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) numrows FROM driving_log WHERE driverid='".$id."' AND start_date = '".date('Y-m-d')."' ORDER BY `drivinglogid` DESC"))['numrows'];
                                echo "<option ".($driverid == $id && $logs == 0 ? 'selected' : '')." ".($logs > 0 ? 'disabled' : '')." value='". $id."'>".get_contact($dbc, $id).($logs > 0 ? ' (Already Created Log Today)' : '').'</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="travel_task" class="col-sm-4 control-label">Co-Driver:
                        <span class="popover-examples list-inline">&nbsp;
                            <a  data-toggle="tooltip" data-placement="top" title="Select person who is seated in front passenger seat."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                        </span>
                    </label>
                    <div class="col-sm-8">
                        <select id="codriverid" name="codriverid" class="chosen-select-deselect form-control" width="380">
                            <option value=''></option>
                            <?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND status>0"),MYSQLI_ASSOC));
                            foreach($query as $id) {
                                $logs = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) numrows FROM driving_log WHERE driverid='".$id."' AND start_date = '".date('Y-m-d')."' ORDER BY `drivinglogid` DESC"))['numrows'];
                                echo "<option ".($codriverid == $id ? 'selected' : '')." ".($logs > 0 ? 'disabled' : '')." value='". $id."'>".get_contact($dbc, $id).($logs > 0 ? ' (Already Created Log Today)' : '').'</option>';
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="travel_task" class="col-sm-4 control-label">Customer</span>:
                        <span class="popover-examples list-inline">&nbsp;
                            <a  data-toggle="tooltip" data-placement="top" title="Select the customer you are driving for."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
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
                <div class="form-group">
                    <label for="travel_task" class="col-sm-4 control-label">Notes:</label>
                    <div class="col-sm-8">
                        <textarea id="notes" name="notes" rows="3" cols="50" class="form-control"><?= html_entity_decode($notes) ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Signature:</label>
                    <div class="col-sm-8">
                        <?php $output_name = 'dl_start_sig';
                            echo include ('../phpsign/sign_multiple.php');
                        ?>
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
    <a href="driving_log_tiles.php" class="btn brand-btn btn-lg allow_view_only">Back</a>
    <!--<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
</div>
<div class="pull-right"><span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to submit your Driving Log."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
    <button type="submit" name="submit_new" value="Submit" class="btn brand-btn btn-lg smt">Submit</button>
</div>