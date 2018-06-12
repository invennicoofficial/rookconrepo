<?php
/*
Customer Listing
*/
include ('../include.php');

if((!empty($_GET['projectmanageid'])) && (!empty($_GET['ar_type']))) {
	if($_GET['ar_type']=='delete')
	{
		$type = $_GET['ar_type'];
		$tile = $_GET['tile'];
		$tab = $_GET['tab'];
		$projectmanageid = $_GET['projectmanageid'];

		$query_delete_projectmanage="DELETE FROM project_manage WHERE projectmanageid = '$projectmanageid'";
		$result_delete_projectmanage = mysqli_query($dbc, $query_delete_projectmanage);

		$query_delete_projectmanagedetail="DELETE FROM project_manage_detail WHERE projectmanageid = '$projectmanageid'";
		$result_delete_projectmanagedetail = mysqli_query($dbc, $query_delete_projectmanagedetail);

		$message="Project Deleted";
		echo '<script type="text/javascript"> alert("'.$message.'"); window.location.replace("project_workflow_dashboard.php?tab='.$tab.'&tile='.$tile.'"); </script>';
	}else{
        $type = $_GET['ar_type'];
        $tile = $_GET['tile'];
        $tab = $_GET['tab'];
        $projectmanageid = $_GET['projectmanageid'];
		echo "Approving Work Order...";
        $query_update_report = "UPDATE `project_manage` SET `status` = '$type' WHERE `projectmanageid` = '$projectmanageid'";
        $result_update_report = mysqli_query($dbc, $query_update_report);
		echo "Done.<br />\n";
		if($type == 'Approved' && $tile == 'Shop Work Orders') {
			$tab = 'Shop Work Order';
			echo "Generating PDFs...<br />\n";
			exit("<script>$.ajax({
				method: 'GET',
				url: 'generate_pdf.php?projectmanageid=".$projectmanageid."&tile=".$tile."&tab=".$tab."'
			}).done(function() {
				window.location.replace('project_workflow_dashboard.php?tab=".$tab."&tile=".$tile."');
			});</script>");
		}
		else {
			exit('<script> window.location.replace("project_workflow_dashboard.php?tab='.$tab.'&tile='.$tile.'"); </script>');
		}
	}
}

if (isset($_POST['send_wo_approve'])) {
	$projectmanageid = $_POST['send_wo_approve'];
	$email_list = $_POST['getemailsapproval'];
	$tile_get = $_POST['tile_get'];
	$tab_get = $_POST['tab_get'];
    if ($email_list != '') {
        $emails_arr = explode( ',', $email_list );
        foreach( $emails_arr as $email )
        {
            if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL) === false) {
            } else {
                 echo '<script type="text/javascript"> alert("One or more of the email addresses you have provided is not a proper email address.");
                        window.location.replace("project_workflow_dashboard.php?tile='.$tile_get.'&tab='.$tab_get.'"); </script>';
                        exit();
            }
        }
        $to_email = $email_list;
        $to = explode(',', $to_email);
        $subject ="Pending Work Order";
        $message = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name'])." requested to approve Work Order #".$projectmanageid;
        send_email('', $to, '', '', $subject, $message, '');
        echo '<script type="text/javascript"> alert("Work Order #'.$projectmanageid.' approval sent to '.$email_list.'.");
        window.location.replace("project_workflow_dashboard.php?tile='.$tile_get.'&tab='.$tab_get.'"); </script>';
	} else {
	    echo '<script type="text/javascript"> alert("Please enter at least 1 email address."); window.location.replace("project_workflow_dashboard.php?tile='.$tile_get.'&tab='.$tab_get.'");
	    </script>';
        exit();
	}
}

?>
<script type="text/javascript">
function tileConfig(sel) {
    var type = sel.type;
    var name = sel.name;
    var tile_value = sel.value;
    var final_value = '*';

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
        url: "../ajax_all.php?fill=tile_config&name="+name+"&value="+final_value+"&turnoff="+turnoff+"&turnOn="+turnOn,
        dataType: "html",   //expect html to be returned
        success: function(response){
            //location.reload();
        }
    });
}

$(document).ready(function() {

	$('.iframe_open').click(function(){
			var id = $(this).attr('id');
			var name="<?php echo $_GET['tab']; ?>";
		   $('#iframe_instead_of_window').attr('src', 'project_workflow_history.php?projectid='+id+'&projectname='+name);
		   $('.iframe_title').text('Project History');
		   $('.iframe_holder').show();
		   $('.hide_on_iframe').hide();
	});

	$('.iframe_open_description').click(function(){
			var id = $(this).attr('id');
			var name="<?php echo $_GET['tab']; ?>";
		   $('#iframe_instead_of_window').attr('src', 'project_workflow_description.php?projectid='+id+'&projectname='+name);
		   $('.iframe_title').text('Project History');
		   $('.iframe_holder').show();
		   $('.hide_on_iframe').hide();
	});

	$('iframe').load(function() {
		this.contentWindow.document.body.style.overflow = 'hidden';
		this.contentWindow.document.body.style.minHeight = '0';
		this.contentWindow.document.body.style.paddingBottom = '5em';
		this.style.height = (this.contentWindow.document.body.offsetHeight + 80) + 'px';
	});

	$('.getemailsapproval2').focusout(
        function() {
			$('.getemailsapproval').val($(this).val());
    });

    $('.close_iframer').click(function(){
		$('.iframe_holder').hide();
		$('.hide_on_iframe').show();
    });

	$('.send_cancel').click(
        function() {
			var id = $(this).val();
			$('.approve-box-'+id).hide();
			$('.getemailsapprove').val('');
    });

});

function approvebutton(sel) {
	var status = sel.id;
	$(".approve-box-"+status).show();
	return false;
}

</script>
<style>
.selectbutton {
	cursor: pointer;
	text-decoration: underline;
}
@media (min-width: 801px) {
	.sel2 {
		display:none;
	}
}
.approve-box {
    display: none;
    position: fixed;
    width: 500px;
	height:250px;
	top:50%;
	margin-top:-125px;
    left: 50%;
    background: lightgrey;
    color: black;
    border: 10px outset grey;
    border-radius: 15px;
    margin-left: -250px;
    text-align: center;
	z-index:99;
    padding: 20px;
}
@media (max-width:530px) {
.approve-box {
	width:100%;
	z-index:99;
	left:0px;
	margin-left:0px;
	overflow:auto;
}
}
.open-approval { cursor:pointer; text-decoration:underline; }
.open-approval:hover { cursor:pointer; text-decoration:underline; font-style: italic; }
	</style>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('project_workflow');
?>
<div class='iframe_holder' style='display:none;'>
	<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right; cursor:pointer;'>
	<span class='iframe_title' style='color:white; font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
	<iframe id="iframe_instead_of_window" style="width: 100%; border:0; margin-top:-70px;" src=""></iframe>
</div>
<div class="container triple-pad-bottom hide_on_iframe">
    <div class="row">
		<div class="col-md-12">

            <h1 class=""><?php echo $_GET['tile']; ?> Dashboard<?php
                $tab_url = '';
                $tile = '';
                if(!isset($_GET['tab'])) {
                    $_GET['tab'] = 'Shop Work Order';
                }
                $tab_url = $_GET['tab'];
                if(!empty($_GET['tile'])) {
                    $tile = $_GET['tile'];
                }
                //if(config_visible_function($dbc, 'project') == 1) {
                    if($tab_url != '') {
                        echo '<a href="field_config_project_manage.php?tab='.$tab_url.'&tile='.$tile.'" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
                    }
                //} ?>
            </h1>

            <form name="form_sites" method="post" action="" class="form-inline" role="form">
                <input type='hidden' class='' value='<?php echo $_GET['tile']; ?>' name='tile_get'>
                <input type='hidden' class='' value='<?php echo $_GET['tab']; ?>' name='tab_get'><?php

                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT project_manage_dashboard, dashboard_view, tile_data, tile_employee FROM field_config_project_manage WHERE tile='$tile' AND tab='$tab_url' AND project_manage_dashboard IS NOT NULL"));
                $value_config = ','.$get_field_config['project_manage_dashboard'].',';
                $dashboard_view = $get_field_config['dashboard_view'];
                $tile_data = $get_field_config['tile_data'];
                $tile_employee = $get_field_config['tile_employee'];

                $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT project_path FROM project_workflow WHERE tile_name='$tile'"));

                $project_path = $get_config['project_path'];

                $to = explode(',', $project_path);
                foreach($to as $tab)  {
                    if($tab_url == $tab) {
                        $active = 'active_tab';
                    } else {
                        $active = '';
                    }
                    if(check_subtab_persmission( $dbc, 'shop_work_orders', ROLE, strtolower(str_replace(' ','_',$tab)) ) === true) {
                        echo "<a href='project_workflow_dashboard.php?tile=".$tile."&tab=".$tab."'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active."' >".$tab."</button></a>&nbsp;&nbsp;";
                    }
                }
                
                if(!empty($_GET['tab'])){ ?>
                    <div class="pad-top pad-bottom"><?php
                        $dropdownstaff='';
                        $dropdownworkorder='';
                        $dropdownworkdate='';
                        $dropdownworkenddate = '';

                        $tile=$_GET['tile'];
                        $tab=$_GET['tab'];

                        if(isset($_POST['search_shopworkorder_submit'])) {
                            if (isset($_POST['search_staff'])) {
                                $dropdownstaff = $_POST['search_staff'];
                            }
                            if (isset($_POST['search_workorder'])) {
                                $dropdownworkorder = $_POST['search_workorder'];
                            }
                            if (isset($_POST['search_date'])) {
                                $dropdownworkdate = $_POST['search_date'];
                            }
                            if (isset($_POST['end_search_date'])) {
                                $dropdownworkenddate = $_POST['end_search_date'];
                            }
                        }
                        if (isset($_POST['display_all_shopworkorder'])) {
                            $dropdownstaff='';
                            $dropdownworkorder='';
                            $dropdownworkdate='';
                            $dropdownworkenddate = '';
                        }
                        
                        if($_GET['tab']=='Shop Time Sheets' || $_GET['tab']=='Payroll') {

                            if (strpos($value_config, ','."Search By Staff".',') !== FALSE) { ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                                    <label for="search_staff" class="control-label">Search By Staff:</label>
                                </div>
                                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                                    <select data-placeholder="Pick a Client" name="search_staff" class="form-control">
                                        <option value="">Select</option><?php
                                        $query_search_staff = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT DISTINCT c.first_name, c.last_name, c.contactid FROM contacts c, project_manage_assign_to_timer p WHERE c.contactid = p.created_by AND c.`deleted`=0 AND c.`status`=1"),MYSQLI_ASSOC));
                                        foreach($query_search_staff as $id) {
                                            //while($row_search_staff = mysqli_fetch_array($query_search_staff)) { ?>
                                            <option <?php if($id==$dropdownstaff){ echo "selected"; } ?> value='<?php echo $id; ?>' ><?php echo get_contact($dbc, $id); ?></option><?php
                                        } ?>
                                    </select>
                                </div><?php
                            }
                            
                            if (strpos($value_config, ','."Search By Work Order".',') !== FALSE) { ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                                    <label for="search_workorder" class="control-label">Search By Work Orders:</label>
                                </div>
                                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                                    <input type="text" name="search_workorder" class="form-control" />
                                    <!--
                                    <select data-placeholder="Pick a Client" name="search_workorder" class="form-control">
                                        <option value="">Select</option><?php /*
                                        $query_search_workorder = mysqli_query($dbc,"SELECT DISTINCT timer_task FROM project_manage_assign_to_timer WHERE timer_task!='' AND timer_task!='0' AND `timer_type`='Work'");
                                        while($row_search_workorder = mysqli_fetch_array($query_search_workorder)) { ?>
                                            <option <?php if($row_search_workorder['timer_task']==$dropdownworkorder){ echo "selected"; } ?> value='<?php echo $row_search_workorder['timer_task']; ?>' ><?php echo $row_search_workorder['timer_task']; ?></option><?php
                                        } */ ?>
                                    </select>
                                    -->
                                </div><?php
                            }
                            
                            if (strpos($value_config, ','."Search By Date".',') !== FALSE) { ?>
                                <br /><br />
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                                    <label for="search_date" class="control-label">Search From Date:</label>
                                </div>
                                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                                    <?php if(isset($_POST['search_shopworkorder_submit'])) { ?>
                                        <input type="text" name="search_date" class="datepicker form-control" value="<?php echo $_POST['search_date']?>" style="width:100%;">
                                    <?php } else { ?>
                                        <input type="text" name="search_date" class="datepicker form-control" style="width:100%;">
                                    <?php } ?>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                                    <label for="search_date" class="control-label">Search Until Date:</label>
                                </div>
                                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                                    <?php if(isset($_POST['search_shopworkorder_submit'])) { ?>
                                        <input type="text" name="end_search_date" class="datepicker form-control" value="<?php echo $_POST['end_search_date']?>" style="width:100%;">
                                    <?php } else { ?>
                                        <input type="text" name="end_search_date" class="datepicker form-control" style="width:100%;">
                                    <?php } ?>
                                </div><?php
                            }
                            
                            if ((strpos($value_config, ','."Search By Staff".',') !== FALSE) || (strpos($value_config, ','."Search By Work Order".',') !== FALSE) || (strpos($value_config, ','."Search By Date".',') !== FALSE)) { ?>
                                <div class="clearfix"></div>
                                <div class='mobile-100-container clearfix double-gap-top'>
                                    <div class="form-group gap-right">
                                        <button type="submit" name="search_shopworkorder_submit" value="Search" class="btn brand-btn mobile-block mobile-100">Search</button>
                                    </div>
                                    <div class="form-group gap-right">
                                        <button type="submit" name="display_all_shopworkorder" value="Display All" class="btn brand-btn mobile-block mobile-100">Display All</button>
                                    </div>
                                </div><?php
                            }
                        }
                        
                        if($_GET['tab'] !='Shop Time Sheets' && $_GET['tab'] != 'Payroll') {
                            if (strpos($value_config, ','."Search By Staff".',') !== FALSE) { ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                                    <label for="search_staff" class="control-label">Search By Staff:</label>
                                </div>
                                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                                    <select data-placeholder="Pick a Client" name="search_staff" class="form-control">
                                        <option value="">Select</option><?php
                                        $query_search_staff = mysqli_query($dbc,"SELECT DISTINCT c.first_name, c.last_name, c.contactid FROM contacts c, project_manage p WHERE c.contactid = p.contactid AND c.businessid=p.businessid AND p.tile='$tile' AND p.tab='$tab'");
                                        while($row_search_staff = mysqli_fetch_array($query_search_staff)) { ?>
                                            <option <?php if($row_search_staff['contactid']==$dropdownstaff){ echo "selected"; } ?> value='<?php echo $row_search_staff['contactid']; ?>' ><?php echo $row_search_staff['first_name'].' '.$row_search_staff['last_name']; ?></option><?php
                                        } ?>
                                    </select>
                                </div><?php
                            }
                            
                            if (strpos($value_config, ','."Search By Work Order".',') !== FALSE) { ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                                    <label for="search_staff" class="control-label">Search By Work Orders:</label>
                                </div>
                                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                                    <input type="text" name="search_workorder" class="form-control" />
                                    <!--
                                    <select data-placeholder="Pick a Client" name="search_workorder" class="form-control">
                                        <option value="">Select</option><?php /*
                                        $query_search_workorder = mysqli_query($dbc,"SELECT DISTINCT unique_id FROM project_manage WHERE  tile='$tile' AND tab='$tab'");
                                        while($row_search_workorder = mysqli_fetch_array($query_search_workorder)) { ?>
                                            <option <?php if($row_search_workorder['unique_id']==$dropdownworkorder){ echo "selected"; } ?> value='<?php echo $row_search_workorder['unique_id']; ?>' ><?php echo $row_search_workorder['unique_id']; ?></option><?php
                                        } */ ?>
                                    </select>
                                    -->
                                </div><?php
                            }
                            
                            if (strpos($value_config, ','."Search By Date".',') !== FALSE) { ?>
                                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                                    <label for="search_staff" class="control-label">Search by Date:</label>
                                </div>
                                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                                    <?php if(isset($_POST['search_shopworkorder_submit'])) { ?>
                                        <input type="text" name="search_date" class="datepicker" value="<?php echo $_POST['search_date']?>">
                                    <?php } else { ?>
                                        <input type="text" name="search_date" class="datepicker">
                                    <?php } ?>
                                </div><?php
                            }
                            
                            if ((strpos($value_config, ','."Search By Staff".',') !== FALSE) || (strpos($value_config, ','."Search By Work Order".',') !== FALSE) || (strpos($value_config, ','."Search By Date".',') !== FALSE)) { ?>
                                <div class="clearfix"></div>
                                <div class='mobile-100-container clearfix double-gap-top'>
                                    <div class="form-group gap-right">
                                        <button type="submit" name="search_shopworkorder_submit" value="Search" class="btn brand-btn mobile-block mobile-100">Search</button>
                                    </div>
                                    <div class="form-group gap-right">
                                        <button type="submit" name="display_all_shopworkorder" value="Display All" class="btn brand-btn mobile-block mobile-100">Display All</button>
                                    </div>
                                </div><?php
                            }
                        } ?>
                    </div><?php
                } ?>
            
                <div id="no-more-tables"><?php
                    if($dashboard_view == 'tile_view') {
                        include ('tile_view_dashboard.php');
                    } else {
                        include ('table_view_dashboard.php');
                    } ?>
                </div>

            </form>
        
        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
