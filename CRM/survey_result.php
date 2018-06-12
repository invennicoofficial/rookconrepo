<?php
/*
Survey Result
*/
include ('../include.php');
checkAuthorised('crm');
error_reporting(0);

?>
<script type="text/javascript">
$(document).ready(function() {

	$('.iframe_open').click(function(){
			var id = $(this).attr('id');
			var arr = id.split('_');
		    $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/CRM/feedback_survey.php?surveyid='+arr[0]+'&surveyresultid='+arr[1]);
		    $('.iframe_title').text('View Survey');

			$('.iframe_holder').show(1000);
			$('.hide_on_iframe').hide(1000);
	});

	$('.close_iframer').click(function(){
		var result = confirm("Are you sure you want to close this window?");
		if (result) {
			$('.iframe_holder').hide(1000);
			$('.hide_on_iframe').show(1000);
			location.reload();
		}
	});

});
    function surveyConfig(sel) {
        var name = sel.name;
        var value = sel.value;
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=survey&name="+name+"&value="+value,
			dataType: "html",   //expect html to be returned
			success: function(response){
                location.reload();
			}
		});
    }
</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class='iframe_holder' style='display:none;'>

		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
	<div class="row hide_on_iframe">
        <div class="col-md-12">

        <h1 class="single-pad-bottom">CRM Dashboard
        <?php
            echo '<a href="config_crm.php?category=survey" class="mobile-block pull-right"><img style="width:50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a><br><br>';
        ?>
        </h1>

        <?php
        $value_config = ','.get_config($dbc, 'crm_dashboard').',';
        ?>

        <?php if (strpos($value_config, ','."Referrals".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Track the Referrals you receive."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='referral.php'><button type="button" class="btn brand-btn mobile-block">Referrals</button></a>
		</span>
        <?php } ?>

        <?php if (strpos($value_config, ','."Recommendations".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Track the Referrals you receive."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='recommendations.php'><button type="button" class="btn brand-btn mobile-block">Recommendations</button></a>
		</span>
        <?php } ?>

        <?php if (strpos($value_config, ','."Surveys".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Send out Surveys to customers."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='survey.php'><button type="button" class="btn brand-btn mobile-block active_tab">Surveys</button></a>
		</span>
        <?php } ?>

        <?php if (strpos($value_config, ','."Testimonials".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Record and track Testimonials."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='testimonials.php'><button type="button" class="btn brand-btn mobile-block">Testimonials</button></a>
		</span>
        <?php } ?>

        <?php if (strpos($value_config, ','."Birthday & Promotion".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Track Birthdays and General Promotions."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='birthday_promo.php'><button type="button" class="btn brand-btn mobile-block">Birthdays &amp; Promotions</button></a>
		</span>
        <?php } ?>

        <?php if (strpos($value_config, ','."6 Month Follow Up Email".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Check in on/follow up with customers after 6 months to see how they are doing and potentially book future appointments."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='6month_follow_up_email.php'><button type="button" class="btn brand-btn mobile-block gap_left">6 Month Follow Up Email</button></a>
		</span>
        <?php } ?>

        <!--<?php if (strpos($value_config, ','."Confirmation Email".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Send and track confirmation emails one month ahead."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='confirmation_email.php'><button type="button" class="btn brand-btn mobile-block">Confirmation Email</button></a>
		</span>
        <?php } ?>

        <?php if (strpos($value_config, ','."Reminder Email".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Send and track appointment reminder emails."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='reminder_email.php'><button type="button" class="btn brand-btn mobile-block">Reminder Email</button></a>
		</span>
        <?php } ?>-->

        <br><br>
        <a href='survey.php'><button type="button" class="btn brand-btn mobile-block" >Surveys</button></a>
        <a href='survey_result.php'><button type="button" class="btn brand-btn mobile-block active_tab" >Result</button></a>

        <?php
            echo '<a href="add_survey.php" class="btn brand-btn pull-right">Create Survey</a><br><br>';
        ?>
        <form name="form_clients" method="post" action="" class="form-inline" role="form">

            <?php

            /* Pagination Counting */
            $rowsPerPage = 25;
            $pageNum = 1;

            if(isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

            $offset = ($pageNum - 1) * $rowsPerPage;

            $query_check_credentials = "SELECT * FROM crm_feedback_survey_result ORDER BY surveyresultid DESC LIMIT $offset, $rowsPerPage";
            $pageQuery = "SELECT count(*) as numrows FROM crm_feedback_survey_result ORDER BY surveyresultid DESC";

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                // Added Pagination //
                echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
                // Pagination Finish //

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
                <th>Survey ID#</th>
                <th>Customer</th>
                <th>Staff</th>
                <th>View</th>";
                echo "</tr>";
            } else {
            	echo "<h2>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array($result))
            {
                echo "<tr>";
                echo '<td data-title="Survey ID#">'.$row['surveyid'].'</td>';
                echo '<td data-title="Customer">'.get_contact($dbc, $row['patientid']).'</td>';
                echo '<td data-title="Staff">'.get_contact($dbc, $row['therapistid']).'</td>';
                echo '<td><a id="'.$row['surveyid'].'_'.$row['surveyresultid'].'" class="iframe_open">View</a></td>';
				//echo '<td><a href="#" onclick=" window.open(\''.WEBSITE_URL.'/CRM/feedback_survey.php?surveyid='.$row['surveyid'].'&surveyresultid='.$row['surveyresultid'].'\', \'newwindow\', \'width=900, height=900\'); return false;">View</a></td>';

                echo "</tr>";
            }

            echo '</table>';
            // Added Pagination //
            echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
            // Pagination Finish //

            ?>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
