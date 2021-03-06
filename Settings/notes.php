<?php
error_reporting(1);
 ?>
<script>
function tileConfig(sel) {
	var type = sel.type;
	var name = sel.name;
	var tile_value = sel.value;
	var final_value = '*';
	var contactid = $('.contacterid').val();

	if($("#"+name+"_turn_on").is(":checked")) {
		final_value += 'turn_on*';
	}
	if($("#"+name+"_turn_off").is(":checked")) {
		final_value += 'turn_off*';
	}

	var isTurnOff = $("#"+name+"_turn_off").is(':checked');
	if(isTurnOff) {
	   var turnoff = name;
	} else {
		var turnoff = '';
	}

	var isTurnOn = $("#"+name+"_turn_on").is(':checked');
	if(isTurnOn) {
	   var turnOn = name;
	} else {
		var turnOn = '';
	}

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../ajax_all.php?fill=tile_config&name="+name+"&value="+final_value+"&turnoff="+turnoff+"&turnOn="+turnOn+"&contactid="+contactid,
		dataType: "html",   //expect html to be returned
		success: function(response){
			response = response.split('#*#');
			console.log(response[0]);
			$(sel).closest('tr').find('td[data-title=Status]').html(response[1]);
			if(response[2] == '1') {
				$(sel).closest('tr').find('input[value=turn_on]').attr('checked','checked');
			} else {
				$(sel).closest('tr').find('input[value=turn_off]').attr('checked','checked');
			}
		}
	});
}
$(document).ready(function() {
	$('.live-search-box2').focus();
    $('.live-search-list2 tr').each(function(){
        var text = $(this).text() + ' ' + $(this).prevAll().andSelf().find('th').last().text();
        text = text.replace(/ Show in Security Tile/g, '');
        searchtext = $(this).find("td:first-child").text();
        $(this).attr('data-search-term', searchtext.toLowerCase());
    });

    $('.live-search-box2').on('keyup', function(){
        var searchTerm = $(this).val().toLowerCase();

        $('.live-search-list2 tr').each(function(){
            if ($(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 || searchTerm.length < 1) {
                $(this).show();
                if (!$(this).hasClass('dont-hide')) {
                    $(this).attr("class","search-found");
                }
            } else if(!$(this).hasClass('dont-hide')) {
                $(this).hide();
                $(this).attr("class","search-not-found");
            }
        });

        $('div .panel-default').each(function(){
            if ($(this).find('.search-found').length > 0) {
                $(this).show();
            } else {
                $(this).hide();
            }
        })
    });

	$('.iframe_open').click(function(){
		var tile = $(this).data('option');
		var title = $(this).parents('tr').children(':first').text();
		$('#iframe_instead_of_window').attr('src', 'tile_history.php?tile_name='+tile+'&title='+title);
		$('.iframe_title').text('Tile Status History');
		$('.iframe_holder').show();
		$('.hide_on_iframe').hide();
		$('#iframe_instead_of_window').on('load', function() {
			$(this).height($(this).get(0).contentWindow.document.body.scrollHeight);
		});
	});

	$('.close_iframe').click(function(){
		$('.iframe_holder').hide();
		$('.hide_on_iframe').show();
	});

	$('iframe').load(function() {
		this.contentWindow.document.body.style.overflow = 'hidden';
		this.contentWindow.document.body.style.minHeight = '0';
		this.contentWindow.document.body.style.paddingBottom = '5em';
		this.style.height = (this.contentWindow.document.body.offsetHeight + 80) + 'px';
	});
});
</script>
<div class='iframe_holder' style='display:none;'>
	<img src='<?= WEBSITE_URL; ?>/img/icons/close.png' class='close_iframe' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
	<span class='iframe_title' style='color:white; font-weight:bold; position: relative;top:58px; left: 20px; font-size: 30px;'></span>
	<iframe id="iframe_instead_of_window" style='width: 100%; overflow: hidden;' height="200px; border:0;" src=""></iframe>
</div>
<div class="row hide_on_iframe">
	<div class="col-md-12">
        <div id=""><?php
            if($_POST['submit']) {
                $data = $_POST;

                foreach($data as $key => $value) {
                    $value = (empty($value)) ? '' : htmlspecialchars($value);
                    
                    if($key != 'submit') {
                        $subtab = $key;
                        $tile_part = explode("_", $subtab);
                        $tile = $tile_part[0];
                        $check_existence = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) AS row_count, note, notesid FROM notes_setting WHERE tile='$tile' AND subtab='$subtab'"));
                        
                        if($check_existence['row_count'] != 0) {
                            $note_exist = $check_existence['note'];
                            $notesid = $check_existence['notesid'];
                            if($value != $note_exist) {
                                mysqli_query($dbc, 'UPDATE notes_setting SET note="'. $value .'" WHERE notesid='. $notesid);
                            }
                        } else {
                            mysqli_query($dbc, 'INSERT INTO notes_setting(`tile`,`subtab`,`note`) VALUES("'. $tile .'","'. $subtab .'","'. $value .'")');
                        }
                    }
                }
            }

            $get_config_result = mysqli_query($dbc,"SELECT * FROM notes_setting");

            while($row = mysqli_fetch_assoc($get_config_result)) {
                $result_array[$row['subtab']] = $row['note'];
            } ?>

            <center><input type='text' name='x' class=' form-control live-search-box2' placeholder='Search for a tile...' style='max-width:300px; margin-bottom:20px;'></center>
            <!-- Added in each Accordion -->
            <!--<table class='table table-bordered live-search-list2'>
                <tr class='hidden-sm dont-hide'>
                    <th>Available Software Tiles & Functionality</th>
                    <th><span class="popover-examples list-inline">
                        <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Turning on a tile applies the tile to active view. The tile still must be configured, click the tile to configure."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                    </span>Turn On Tile</th>
                    <th><span class="popover-examples list-inline">
                        <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Turning off a tile removes the tile from the active view. Tiles turned off are not deleted, merely removed from the active view."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                    </span>Turn Off Tile</th>
                    <th>History</th>
                    <th>Status</th>
                </tr>
            </table>-->
            <!-- Software Settings -->
            <form action="" method="post">
                <div class="panel-group" id="accordion2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field3" >
                                    Software Settings<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_field3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span>Notes</th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Styling</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_styling']; ?>" name="setting_styling"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Formatting</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_formatting']; ?>" name="setting_formatting"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Menu Formatting</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_menu_formatting']; ?>" name="setting_menu_formatting"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Tile Sort Order</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_tile_sort_order']; ?>" name="setting_tile_sort_order"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">My Dashboards</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_my_dashboard']; ?>" name="setting_my_dashboard"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Dashboards</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_dashboard']; ?>" name="setting_dashboard"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Software Identity</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_software_identity']; ?>" name="setting_software_identity"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Software Login Page</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_software_login_page']; ?>" name="setting_software_login_page"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Social Media Links</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_social_media_links']; ?>" name="setting_social_media_links"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">URL Favicon</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_url_favicon']; ?>" name="setting_url_favicon"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Logo</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_logo']; ?>" name="setting_logo"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Display Preferences</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_contact_sort_order']; ?>" name="setting_contact_sort_order"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Font Setting</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_font_setting']; ?>" name="setting_font_setting"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Data Usage</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_data_usage']; ?>" name="setting_data_usage"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%"><?= TICKET_NOUN ?> Slider View</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['setting_ticket_slider']; ?>" name="setting_ticket_slider"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field4" >
                                    Security<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_field4" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span>Notes</th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Software Functionality</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['security_software_functionality']; ?>" name="security_software_functionality"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Security Levels & Groups</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['security_security_level_n_group']; ?>" name="security_security_level_n_group"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Set Security Privileges</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['security_set_security_previleges']; ?>" name="security_set_security_previleges"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Assign Privileges - Active Users</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['security_assign_previleges_active']; ?>" name="security_assign_previleges_active"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Assign Privileges - Suspended Users</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['security_assign_previleges_suspended']; ?>" name="security_assign_previleges_suspended"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Password Reset</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['security_password_reset']; ?>" name="security_password_reset"></td>
                                        </tr>
                                        <!--
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Reporting</td>
                                            <td><input type="text" class="form-control" value="<?php //echo $result_array['security_reporting']; ?>" name="security_reporting"></td>
                                        </tr>
                                        -->
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Reporting - Activated Security Levels & Groups</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['security_activated_security_level_n_group']; ?>" name="security_activated_security_level_n_group"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Reporting - Account User Reports</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['security_account_user_reports']; ?>" name="security_account_user_reports"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field5" >
                                    Information Gathering<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_field5" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span>Notes</th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Information Gathering</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['ig_information_gathering']; ?>" name="ig_information_gathering"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Reporting</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['ig_reporting']; ?>" name="ig_reporting"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field6" >
                                    Agendas & Meetings<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_field6" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span>Notes</th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Agendas</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['am_agenda']; ?>" name="am_agenda"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Meetings</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['am_meeting']; ?>" name="am_meeting"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field7" >
                                    Sales<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_field7" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span>Notes</th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Sales</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['sales_sales']; ?>" name="sales_sales"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Reports</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['sales_reports']; ?>" name="sales_reports"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field8" >
                                    Marketing Material<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_field8" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span>Notes</th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Marketing Material</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['mm_mm']; ?>" name="mm_mm"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field9" >
                                    Internal Documents<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_field9" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span>Notes</th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Internal Documents</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['id_id']; ?>" name="id_id"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field10" >
                                    HR<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_field10" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span>Notes</th>
                                        </tr>
                                        <!--
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Form</td>
                                            <td><input type="text" class="form-control" value="<?php //echo $result_array['hr_form']; ?>" name="hr_form"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Manual</td>
                                            <td><input type="text" class="form-control" value="<?php //echo $result_array['hr_manual']; ?>" name="hr_manual"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">On Boarding</td>
                                            <td><input type="text" class="form-control" value="<?php //echo $result_array['hr_on_boarding']; ?>" name="hr_on_boarding"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Orientation</td>
                                            <td><input type="text" class="form-control" value="<?php //echo $result_array['hr_orientation']; ?>" name="hr_orientation"></td>
                                        </tr>
                                        -->
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Reporting</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['hr_reporting']; ?>" name="hr_reporting"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field11" >
                                    Policies & Procedures<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_field11" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span>Notes</th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Manuals</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['pp_manuals']; ?>" name="pp_manuals"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Follow Up</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['pp_follow_up']; ?>" name="pp_follow_up"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Reporting</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['pp_reporting']; ?>" name="pp_reporting"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field12" >
                                    Cold Call<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_field12" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span>Notes</th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Cold Call Pipeline - Not Scheduled</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['cc_not_scheduled']; ?>" name="cc_not_scheduled"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Cold Call Pipeline - Scheduled</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['cc_scheduled']; ?>" name="cc_scheduled"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Cold Call Pipeline - Missed Call</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['cc_missed_call']; ?>" name="cc_missed_call"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Cold Call Pipeline - Past Due</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['cc_past_due']; ?>" name="cc_past_due"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Schedule</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['cc_schedule']; ?>" name="cc_schedule"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Lead Bank - Available Leads</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['cc_available']; ?>" name="cc_available"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Lead Bank - Abondoned Leads</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['cc_abondoned']; ?>" name="cc_abondoned"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Lost / Archive</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['cc_lost_archive']; ?>" name="cc_lost_archive"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Goals</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['cc_goals']; ?>" name="cc_goals"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Reporting</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['cc_reporting']; ?>" name="cc_reporting"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field13" >
                                    Checklist<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_field13" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span>Notes</th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Checklist</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['checklist_checklist']; ?>" name="checklist_checklist"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tasks" >
                                    Tasks<span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_tasks" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span>Notes</th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Summary</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['tasks_summary']; ?>" name="tasks_summary"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">My Tasks</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['tasks_my']; ?>" name="tasks_my"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Private Tasks</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['tasks_private']; ?>" name="tasks_private"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Shared Tasks</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['tasks_company']; ?>" name="tasks_company"></td>
                                        </tr>
                                        <!--
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Business Tasks</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['tasks_business']; ?>" name="tasks_business"></td>
                                        </tr>
                                        -->
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Project Tasks</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['tasks_project']; ?>" name="tasks_project"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%"><?= (substr(CONTACTS_TILE, -1)=='s' && substr(CONTACTS_TILE, -2) !='ss') ? rtrim(CONTACTS_TILE, 's') : CONTACTS_TILE; ?> Tasks</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['tasks_client']; ?>" name="tasks_client"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Reporting</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['tasks_reporting']; ?>" name="tasks_reporting"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_contacts" >
                                    <?= CONTACTS_TILE ?><span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_contacts" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span><?= CONTACTS_TILE ?></th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Active</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['contacts_active']; ?>" name="contacts_active"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Inactive</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['contacts_inactive']; ?>" name="contacts_inactive"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Archived</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['contacts_archived']; ?>" name="contacts_archived"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_projects" >
                                    <?= PROJECT_TILE ?><span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_projects" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span><?= PROJECT_TILE ?></th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Favourite</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['projects_favourite']; ?>" name="projects_favourite"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Pending</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['projects_pending']; ?>" name="projects_pending"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Planner</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['projects_planner']; ?>" name="projects_planner"></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Scrum</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['projects_scrum']; ?>" name="projects_scrum"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tickets" >
                                    <?= TICKET_TILE ?><span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_tickets" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div id='no-more-tables'>
                                    <table border='2' cellpadding='10' class='table table-bordered live-search-list2'>
                                        <tr class='hidden-sm dont-hide'>
                                            <th>Sub Tab</th>
                                            <th><span class="popover-examples list-inline">
                                                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Add Notes which should be visible on the particular page of the tile."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="20"></a>
                                            </span><?= TICKET_TILE ?></th>
                                        </tr>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Summary</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['tickets_summary']; ?>" name="tickets_summary"></td>
                                        </tr><?php
                                        $ticket_tabs = get_config($dbc, 'ticket_tabs');
                                        foreach (explode(',', $ticket_tabs) as $ticket_tab) {
                                            $tab_lower = 'tickets_'.strtolower(strtoupper(str_replace(' ', '_', $ticket_tab))); ?>
                                            <tr>
                                                <td data-title="Comment" style="width:25%"><?= $ticket_tab ?></td>
                                                <td><input type="text" class="form-control" value="<?= $result_array[$tab_lower]; ?>" name="<?= $tab_lower ?>"></td>
                                            </tr><?php
                                        } ?>
                                        <tr>
                                            <td data-title="Comment" style="width:25%">Import/Export Tickets</td>
                                            <td><input type="text" class="form-control" value="<?= $result_array['tickets_import_export']; ?>" name="tickets_import_export"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <input type="submit" value="Submit" name="submit" class="btn config-btn btn-lg	pull-right">
            </form>

            <input type='hidden' value='<?= $_SESSION['contactid']; ?>' class='contacterid'>
        </div>
	</div>
</div>