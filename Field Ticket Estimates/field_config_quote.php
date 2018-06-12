<?php
/*
Configuration - Choose which functionality you want for your software. Config email subject and body part for each functionality. Config Email Send Before days/month for patient treatment/booking confirmation and reminder.
*/
include ('../include.php');
checkAuthorised('field_ticket_estimates');
error_reporting(0);

if (isset($_POST['submit'])) {
    //Logo
	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}
    $logo = htmlspecialchars($_FILES["logo"]["name"], ENT_QUOTES);
    $quote_pdf_footer_logo = htmlspecialchars($_FILES["quote_pdf_footer_logo"]["name"], ENT_QUOTES);
    $quote_pdf_header = filter_var(htmlentities($_POST['quote_pdf_header']),FILTER_SANITIZE_STRING);
    $quote_pdf_footer = filter_var(htmlentities($_POST['quote_pdf_footer']),FILTER_SANITIZE_STRING);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigestimateid) AS fieldconfigestimateid FROM field_config_bid"));
    if($get_field_config['fieldconfigestimateid'] > 0) {
		if($logo == '') {
			$logo_update = $_POST['logo_file'];
		} else {
			$logo_update = $logo;
		}
		move_uploaded_file($_FILES["logo"]["tmp_name"],"download/" . $logo_update);
		if($quote_pdf_footer_logo == '') {
			$quote_pdf_footer_logo_update = $_POST['quote_pdf_footer_logo_file'];
		} else {
			$quote_pdf_footer_logo_update = $quote_pdf_footer_logo;
		}
		move_uploaded_file($_FILES["quote_pdf_footer_logo"]["tmp_name"],"download/" . $quote_pdf_footer_logo_update);
        $query_update_employee = "UPDATE `field_config_bid` SET logo = '$logo_update', quote_pdf_header = '$quote_pdf_header', quote_pdf_footer = '$quote_pdf_footer', quote_pdf_footer_logo = '$quote_pdf_footer_logo_update' WHERE `fieldconfigestimateid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
		move_uploaded_file($_FILES["logo"]["tmp_name"], "download/" . $_FILES["logo"]["name"]) ;
		move_uploaded_file($_FILES["quote_pdf_footer_logo"]["tmp_name"], "download/" . $_FILES["quote_pdf_footer_logo"]["name"]) ;
        $query_insert_config = "INSERT INTO `field_config_bid` (`logo`, `quote_pdf_header`, `quote_pdf_footer`, `quote_pdf_footer_logo`) VALUES ('$logo', '$quote_pdf_header', '$quote_pdf_footer', '$quote_pdf_footer_logo')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Logo

    $send_quote_client_subject = filter_var($_POST['send_quote_client_subject'],FILTER_SANITIZE_STRING);
    $survey = htmlentities($_POST['send_quote_client_body']);
    $send_quote_client_body = filter_var($survey,FILTER_SANITIZE_STRING);

    //Survey Email
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='send_quote_client_subject'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$send_quote_client_subject' WHERE name='send_quote_client_subject'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('send_quote_client_subject', '$send_quote_client_subject')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='send_quote_client_body'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$send_quote_client_body' WHERE name='send_quote_client_body'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('send_quote_client_body', '$send_quote_client_body')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Survey Email


    //PAyment Term
    $quote_payment_term = filter_var($_POST['quote_payment_term'],FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='quote_payment_term'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$quote_payment_term' WHERE name='quote_payment_term'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('quote_payment_term', '$quote_payment_term')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    //PAyment Term

    //Terms & Condition
    $quote_term_condition = filter_var($_POST['quote_term_condition'],FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='quote_term_condition'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$quote_term_condition' WHERE name='quote_term_condition'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('quote_term_condition', '$quote_term_condition')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Terms & Condition

    //quote_due_period
    $quote_due_period = filter_var($_POST['quote_due_period'],FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='quote_due_period'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$quote_due_period' WHERE name='quote_due_period'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('quote_due_period', '$quote_due_period')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    //quote_due_period

    //Tax
    $quote_tax = '';
    for($i = 0; $i < count($_POST['quote_tax_name']); $i++) {
        if($_POST['quote_tax_name'][$i] != '') {
            $quote_tax .= filter_var($_POST['quote_tax_name'][$i],FILTER_SANITIZE_STRING).'**'.$_POST['quote_tax_rate'][$i].'**'.$_POST['quote_tax_number'][$i].'**'.$_POST['quote_tax_exemption_'.$i].'*#*';
        }
    }

    $quote_tax = rtrim($quote_tax, "*#*'");
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='quote_tax'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$quote_tax' WHERE name='quote_tax'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('quote_tax', '$quote_tax')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $config_fields_quote = implode(',',$_POST['config_fields']);
    $config_fields_quote_dashboard = implode(',',$_POST['config_fields_quote_dashboard']);

    $query_update_employee = "UPDATE `field_config_bid` SET config_fields_quote = '$config_fields_quote', config_fields_quote_dashboard = '$config_fields_quote_dashboard' WHERE `fieldconfigestimateid` = 1";
    $result_update_employee = mysqli_query($dbc, $query_update_employee);

    $from=$_POST['from'];
    echo '<script type="text/javascript"> window.location.replace("field_config_quote.php"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
    $('#add_tax_button').on( 'click', function () {
        var clone = $('.additional_tax').clone();

        var numItems = $('.tax_exemption_div').length;
        clone.find('.tax_exemption').attr("name", "quote_tax_exemption_"+numItems);
        clone.find('.form-control').val('');
        clone.find('.rate').val('0');
        clone.removeClass("additional_tax");
        $('#add_here_new_tax').append(clone);
        return false;
    });
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Estimate</h1>
<!--<a href="estimate.php" class="btn config-btn">Back</a>-->
<div class="gap-top double-gap-bottom"><a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back to Dashboard</a></div>

<a href='field_config_estimate.php'><button type="button" class="btn brand-btn mobile-block" >Bid Config</button></a>
<a href='field_config_quote.php'><button type="button" class="btn brand-btn mobile-block active_tab" >Cost Estimate Config</button></a>

<br><br>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="panel-group" id="accordion2">

    <?php
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_bid"));
    $logo = $get_field_config['logo'];
    $config_fields_quote_dashboard = ','.$get_field_config['config_fields_quote_dashboard'].',';
    $quote_pdf_header = $get_field_config['quote_pdf_header'];
    $quote_pdf_footer = $get_field_config['quote_pdf_footer'];
    $quote_pdf_footer_logo = $get_field_config['quote_pdf_footer_logo'];
    ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dash" >
                        Cost Estimate Dashboard Config<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_dash" class="panel-collapse collapse">
                <div class="panel-body">
                     <input type="checkbox" <?php if (strpos($config_fields_quote_dashboard, ','."Quote#".',') !== FALSE) { echo " checked"; } ?> value="Quote#" style="height: 20px; width: 20px;" name="config_fields_quote_dashboard[]">&nbsp;&nbsp;Cost Estimate#&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($config_fields_quote_dashboard, ','."Client".',') !== FALSE) { echo " checked"; } ?> value="Client" style="height: 20px; width: 20px;" name="config_fields_quote_dashboard[]">&nbsp;&nbsp;Client&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($config_fields_quote_dashboard, ','."Quote Name".',') !== FALSE) { echo " checked"; } ?> value="Quote Name" style="height: 20px; width: 20px;" name="config_fields_quote_dashboard[]">&nbsp;&nbsp;Cost Estimate Name&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($config_fields_quote_dashboard, ','."Total Cost".',') !== FALSE) { echo " checked"; } ?> value="Total Cost" style="height: 20px; width: 20px;" name="config_fields_quote_dashboard[]">&nbsp;&nbsp;Total Cost&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($config_fields_quote_dashboard, ','."Quote".',') !== FALSE) { echo " checked"; } ?> value="Quote" style="height: 20px; width: 20px;" name="config_fields_quote_dashboard[]">&nbsp;&nbsp;Cost Estimate&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($config_fields_quote_dashboard, ','."Follow up Date".',') !== FALSE) { echo " checked"; } ?> value="Follow up Date" style="height: 20px; width: 20px;" name="config_fields_quote_dashboard[]">&nbsp;&nbsp;Follow up Date&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($config_fields_quote_dashboard, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="config_fields_quote_dashboard[]">&nbsp;&nbsp;Status&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($config_fields_quote_dashboard, ','."Send To Client".',') !== FALSE) { echo " checked"; } ?> value="Send To Client" style="height: 20px; width: 20px;" name="config_fields_quote_dashboard[]">&nbsp;&nbsp;Send To Client&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($config_fields_quote_dashboard, ','."History".',') !== FALSE) { echo " checked"; } ?> value="History" style="height: 20px; width: 20px;" name="config_fields_quote_dashboard[]">&nbsp;&nbsp;History&nbsp;&nbsp;
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                        Logo for Cost Estimate PDF<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_field" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="form-group">
                    <label for="file[]" class="col-sm-4 control-label">Header Logo
                    <span class="popover-examples list-inline">&nbsp;
                    <a  data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
                    </span>
                    :</label>
                    <div class="col-sm-8">
                    <?php if($logo != '') {
                        echo '<a href="download/'.$logo.'" target="_blank">View</a>';
                        ?>
                        <input type="hidden" name="logo_file" value="<?php echo $logo; ?>" />
                        <input name="logo" type="file" data-filename-placement="inside" class="form-control" />
                      <?php } else { ?>
                      <input name="logo" type="file" data-filename-placement="inside" class="form-control" />
                      <?php } ?>
                    </div>
                    </div>

                    <div class="form-group">
                    <label for="file[]" class="col-sm-4 control-label">Footer Logo
                    <span class="popover-examples list-inline">&nbsp;
                    <a  data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
                    </span>
                    :</label>
                    <div class="col-sm-8">
                    <?php if($quote_pdf_footer_logo != '') {
                        echo '<a href="download/'.$quote_pdf_footer_logo.'" target="_blank">View</a>';
                        ?>
                        <input type="hidden" name="quote_pdf_footer_logo_file" value="<?php echo $quote_pdf_footer_logo; ?>" />
                        <input name="quote_pdf_footer_logo" type="file" data-filename-placement="inside" class="form-control" />
                      <?php } else { ?>
                      <input name="quote_pdf_footer_logo" type="file" data-filename-placement="inside" class="form-control" />
                      <?php } ?>
                    </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <!--<a href="estimate.php" class="btn config-btn pull-right">Back</a>-->
							<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>
                        </div>
                        <div class="col-sm-8">
                            <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Header" >
                        Header & Footer for Cost Estimate PDF<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Header" class="panel-collapse collapse">
                <div class="panel-body">

                    <div class="form-group">
                        <label for="office_country" class="col-sm-4 control-label">Header Info:<br><em>(Ex: Company Address, Phone, Email etc)</em></label>
                        <div class="col-sm-8">
                            <textarea name="quote_pdf_header" rows="3" cols="50" class="form-control"><?php echo $quote_pdf_header; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="office_country" class="col-sm-4 control-label">Footer Info:<br><em>(e.g. - company name, address, phone, etc.)</em></label>
                        <div class="col-sm-8">
                            <textarea name="quote_pdf_footer" rows="3" cols="50" class="form-control"><?php echo $quote_pdf_footer; ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <!--<a href="estimate.php" class="btn config-btn pull-right">Back</a>-->
							<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>
                        </div>
                        <div class="col-sm-8">
                            <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_survey" >
                        Email Cost Estimate To Client<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_survey" class="panel-collapse collapse">
                <div class="panel-body">

                   <?php
                    $send_quote_client_body = html_entity_decode(get_config($dbc, 'send_quote_client_body'));
                    $send_quote_client_subject = get_config($dbc, 'send_quote_client_subject');
                   ?>

                  <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Email Subject:</label>
                    <div class="col-sm-8">
                        <input name="send_quote_client_subject" type="text" value = "<?php echo $send_quote_client_subject; ?>" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Email Body:<br>Use tag for Client Name: [Client Name]</label>
                    <div class="col-sm-8">
                        <textarea name="send_quote_client_body" rows="5" cols="50" class="form-control"><?php echo $send_quote_client_body; ?></textarea>
                    </div>
                  </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <!--<a href="estimate.php" class="btn config-btn pull-right">Back</a>-->
							<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>
                        </div>
                        <div class="col-sm-8">
                            <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_term" >
                        Payment Breakdown<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_term" class="panel-collapse collapse">
                <div class="panel-body">

                   <?php
                    $quote_payment_term = get_config($dbc, 'quote_payment_term');
                   ?>

                  <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Payment Breakdown:<br>(e.g. - retainer, deposit, midpoint, completion &nbsp;% amounts)</label>
                    <div class="col-sm-8">
                        <input name="quote_payment_term" type="text" value = "<?php echo $quote_payment_term; ?>" class="form-control">
                    </div>
                  </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <!--<a href="estimate.php" class="btn config-btn pull-right">Back</a>-->
							<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>
                        </div>
                        <div class="col-sm-8">
                            <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!--
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_termcond" >
                        Terms & Condition<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_termcond" class="panel-collapse collapse">
                <div class="panel-body">

                   <?php
                    $quote_term_condition = get_config($dbc, 'quote_term_condition');
                   ?>

                  <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Payment Term:</label>
                    <div class="col-sm-8">
                        <input name="quote_term_condition" type="text" value = "<?php //echo $quote_term_condition; ?>" class="form-control">
                    </div>
                  </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <a href="estimate.php" class="btn config-btn pull-right">Back</a>
                        </div>
                        <div class="col-sm-8">
                            <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_due" >
                       Payment Due Period<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_due" class="panel-collapse collapse">
                <div class="panel-body">

                   <?php
                    $quote_due_period = get_config($dbc, 'quote_due_period');
                   ?>

                  <div class="form-group">
                    <label for="fax_number"	class="col-sm-4	control-label">Payment Due Within:</label>
                    <div class="col-sm-8">
                        <input name="quote_due_period" type="text" value = "<?php echo $quote_due_period; ?>" class="form-control">
                    </div>
                  </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <!--<a href="estimate.php" class="btn config-btn pull-right">Back</a>-->
							<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>
                        </div>
                        <div class="col-sm-8">
                            <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tax" >
                        Set Tax Names & Rates<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_tax" class="panel-collapse collapse">
                <div class="panel-body">

                    <?php
                    $value_config = get_config($dbc, 'quote_tax');
                    ?>

                    <div class="form-group clearfix  hide-titles-mob">
                        <label class="col-sm-2 text-center">Name</label>
                        <label class="col-sm-2 text-center">Rate(%)<br><em>(add number without % sign)</em></label>
                        <label class="col-sm-2 text-center">Tax Number</label>
                    </div>

                    <?php
                    $quote_tax = explode('*#*',$value_config);

                    $total_count = mb_substr_count($value_config,'*#*');
                    for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
                        $quote_tax_name_rate = explode('**',$quote_tax[$eq_loop]);
                    ?>
                        <div class="clearfix"></div>
                        <div class="form-group clearfix">
                          <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Name:</label>
                                <input name="quote_tax_name[]" value="<?php echo $quote_tax_name_rate[0];?>" type="text" class="form-control quantity" />
                            </div>
                            <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate (%) (add number without % sign):</label>
                                <input name="quote_tax_rate[]" value="<?php echo $quote_tax_name_rate[1]; ?>" type="text" class="form-control category" />
                            </div>
                            <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Tax Number:</label>
                                <input name="quote_tax_number[]" value="<?php echo $quote_tax_name_rate[2]; ?>" type="text" class="form-control category" />
                            </div>
                        </div>
                    <?php } ?>

                    <div class="additional_tax">
                    <div class="clearfix"></div>
                    <div class="form-group clearfix" width="100%">
                        <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Name:</label>
                            <input name="quote_tax_name[]" type="text" class="form-control price" />
                        </div>
                        <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate (%) (add number without % sign):</label>
                            <input name="quote_tax_rate[]" value="0" type="text" class="form-control rate" />
                        </div>
                        <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Tax Number:</label>
                            <input name="quote_tax_number[]" type="text" class="form-control" />
                        </div>
                    </div>

                    </div>

                    <div id="add_here_new_tax"></div>

                    <div class="col-sm-8 col-sm-offset-4 triple-gap-bottom">
                        <button id="add_tax_button" class="btn brand-btn mobile-block">Add</button>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <!--<a href="estimate.php" class="btn config-btn pull-right">Back</a>-->
							<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>
                        </div>
                        <div class="col-sm-8">
                            <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_bid"));
        $value_config = ','.$get_field_config['config_fields_quote'].',';
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Package" >
                        Package<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Package" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Package".',') !== FALSE) { echo " checked"; } ?> value="Package" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Package

                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Package Service Type".',') !== FALSE) { echo " checked"; } ?> value="Package Service Type" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Service Type&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Package Category".',') !== FALSE) { echo " checked"; } ?> value="Package Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Category&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Package Heading".',') !== FALSE) { echo " checked"; } ?> value="Package Heading" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Package Description".',') !== FALSE) { echo " checked"; } ?> value="Package Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Package Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Package Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description&nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Promotion" >
                        Promotion<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Promotion" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Promotion".',') !== FALSE) { echo " checked"; } ?> value="Promotion" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Promotion
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Promotion Service Type".',') !== FALSE) { echo " checked"; } ?> value="Promotion Service Type" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Service Type&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Promotion Category".',') !== FALSE) { echo " checked"; } ?> value="Promotion Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Category&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Promotion Heading".',') !== FALSE) { echo " checked"; } ?> value="Promotion Heading" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Promotion Description".',') !== FALSE) { echo " checked"; } ?> value="Promotion Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Promotion Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Promotion Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description&nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Custom" >
                        Custom<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Custom" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Custom".',') !== FALSE) { echo " checked"; } ?> value="Custom" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Custom
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Custom Service Type".',') !== FALSE) { echo " checked"; } ?> value="Custom Service Type" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Service Type&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Custom Category".',') !== FALSE) { echo " checked"; } ?> value="Custom Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Category&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Custom Heading".',') !== FALSE) { echo " checked"; } ?> value="Custom Heading" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Custom Description".',') !== FALSE) { echo " checked"; } ?> value="Custom Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Custom Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Custom Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description&nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Material" >
                        Material<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Material" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Material".',') !== FALSE) { echo " checked"; } ?> value="Material" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Material
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Material Code".',') !== FALSE) { echo " checked"; } ?> value="Material Code" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Code&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Material Category".',') !== FALSE) { echo " checked"; } ?> value="Material Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Material Sub-Category".',') !== FALSE) { echo " checked"; } ?> value="Material Sub-Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Sub-Category&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Material Material Name".',') !== FALSE) { echo " checked"; } ?> value="Material Material Name" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Material Name&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Material Description".',') !== FALSE) { echo " checked"; } ?> value="Material Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Material Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Material Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Material Width".',') !== FALSE) { echo " checked"; } ?> value="Material Width" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Width&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Material Length".',') !== FALSE) { echo " checked"; } ?> value="Material Length" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Length&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Material Units".',') !== FALSE) { echo " checked"; } ?> value="Material Units" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Units&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Material Unit Weight".',') !== FALSE) { echo " checked"; } ?> value="Material Unit Weight" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Unit Weight&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Material Weight Per Foot".',') !== FALSE) { echo " checked"; } ?> value="Material Weight Per Foot" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Weight Per Foot&nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Services" >
                        Services<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Services" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Services".',') !== FALSE) { echo " checked"; } ?> value="Services" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Services
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Services Service Type".',') !== FALSE) { echo " checked"; } ?> value="Services Service Type" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Service Type&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Services Category".',') !== FALSE) { echo " checked"; } ?> value="Services Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Services Heading".',') !== FALSE) { echo " checked"; } ?> value="Services Heading" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Services Description".',') !== FALSE) { echo " checked"; } ?> value="Services Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Services Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Services Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description&nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Products" >
                        Products<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Products" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Products".',') !== FALSE) { echo " checked"; } ?> value="Products" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Products
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Products Product Type".',') !== FALSE) { echo " checked"; } ?> value="Products Product Type" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Product Type&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Products Category".',') !== FALSE) { echo " checked"; } ?> value="Products Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Products Heading".',') !== FALSE) { echo " checked"; } ?> value="Products Heading" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Products Description".',') !== FALSE) { echo " checked"; } ?> value="Products Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Products Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Products Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description&nbsp;&nbsp;

                 </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sred" >
                        SR&ED<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_sred" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."SRED".',') !== FALSE) { echo " checked"; } ?> value="SRED" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;SR&ED
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."SRED SRED Type".',') !== FALSE) { echo " checked"; } ?> value="SRED SRED Type" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;SR&ED Type&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."SRED Category".',') !== FALSE) { echo " checked"; } ?> value="SRED Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."SRED Heading".',') !== FALSE) { echo " checked"; } ?> value="SRED Heading" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."SRED Description".',') !== FALSE) { echo " checked"; } ?> value="SRED Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."SRED Quote Description".',') !== FALSE) { echo " checked"; } ?> value="SRED Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description&nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Staff" >
                        Staff<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Staff" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { echo " checked"; } ?> value="Staff" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Staff
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Staff Contact Person".',') !== FALSE) { echo " checked"; } ?> value="Staff Contact Person" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Contact Person&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Staff Description".',') !== FALSE) { echo " checked"; } ?> value="Staff Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Staff Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Staff Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description &nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Contractor" >
                        Contractor<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Contractor" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Contractor".',') !== FALSE) { echo " checked"; } ?> value="Contractor" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Contractor
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Contractor Contact Person".',') !== FALSE) { echo " checked"; } ?> value="Contractor Contact Person" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Contact Person&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Contractor Description".',') !== FALSE) { echo " checked"; } ?> value="Contractor Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Contractor Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Contractor Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description &nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Clients" >
                        Clients<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Clients" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Clients".',') !== FALSE) { echo " checked"; } ?> value="Clients" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Clients
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Clients Client Name".',') !== FALSE) { echo " checked"; } ?> value="Clients Client Name" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Client Name&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Clients Contact Person".',') !== FALSE) { echo " checked"; } ?> value="Clients Contact Person" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Contact Person&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Clients Description".',') !== FALSE) { echo " checked"; } ?> value="Clients Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Clients Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Clients Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description &nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pl" >
                        Vendor Pricelist<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_pl" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Vendor Pricelist
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist Vendor".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Vendor" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Vendor&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist Price List".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Price List" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Price List&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist Category".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Category&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist Product".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Product" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Product&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist Code".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Code" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Code&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist Sub-Category".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Sub-Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Sub-Category&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist Description".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist Size".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Size" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Size&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist Type".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Type" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Type&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist Part No".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Part No" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Part No&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Vendor Pricelist Variance".',') !== FALSE) { echo " checked"; } ?> value="Vendor Pricelist Variance" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Variance&nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Customer" >
                        Customer<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Customer" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Customer".',') !== FALSE) { echo " checked"; } ?> value="Customer" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Customer
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Customer Customer Name".',') !== FALSE) { echo " checked"; } ?> value="Customer Customer Name" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Customer Name&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Customer Contact Person".',') !== FALSE) { echo " checked"; } ?> value="Customer Contact Person" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Contact Person&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Customer Description".',') !== FALSE) { echo " checked"; } ?> value="Customer Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Customer Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Customer Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description &nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Inventory" >
                        Inventory<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Inventory" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory".',') !== FALSE) { echo " checked"; } ?> value="Inventory" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Inventory
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Category".',') !== FALSE) { echo " checked"; } ?> value="Inventory Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Category&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Product Name".',') !== FALSE) { echo " checked"; } ?> value="Inventory Product Name" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Product Name&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Code".',') !== FALSE) { echo " checked"; } ?> value="Inventory Code" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Code&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Sub-Category".',') !== FALSE) { echo " checked"; } ?> value="Inventory Sub-Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Sub-Category&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Description".',') !== FALSE) { echo " checked"; } ?> value="Inventory Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Inventory Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Size".',') !== FALSE) { echo " checked"; } ?> value="Inventory Size" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Size &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Type".',') !== FALSE) { echo " checked"; } ?> value="Inventory Type" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Type &nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Part No".',') !== FALSE) { echo " checked"; } ?> value="Inventory Part No" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Part No &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Location".',') !== FALSE) { echo " checked"; } ?> value="Inventory Location" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Location &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Variance".',') !== FALSE) { echo " checked"; } ?> value="Inventory Variance" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Variance &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Weight".',') !== FALSE) { echo " checked"; } ?> value="Inventory Weight" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Weight &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory ID Number".',') !== FALSE) { echo " checked"; } ?> value="Inventory ID Number" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;ID Number &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Operator".',') !== FALSE) { echo " checked"; } ?> value="Inventory Operator" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Operator &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory LSD".',') !== FALSE) { echo " checked"; } ?> value="Inventory LSD" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;LSD &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Comments".',') !== FALSE) { echo " checked"; } ?> value="Inventory Comments" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Comments &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Questions".',') !== FALSE) { echo " checked"; } ?> value="Inventory Questions" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Questions &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Inventory Requests".',') !== FALSE) { echo " checked"; } ?> value="Inventory Requests" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Requests &nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Equipment" >
                        Equipment<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Equipment" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment".',') !== FALSE) { echo " checked"; } ?> value="Equipment" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Equipment
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Category".',') !== FALSE) { echo " checked"; } ?> value="Equipment Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Category&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Unit Number".',') !== FALSE) { echo " checked"; } ?> value="Equipment Unit Number" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Unit Number&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Serial Number".',') !== FALSE) { echo " checked"; } ?> value="Equipment Serial Number" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Serial Number&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Type".',') !== FALSE) { echo " checked"; } ?> value="Equipment Type" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Type&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Make".',') !== FALSE) { echo " checked"; } ?> value="Equipment Make" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Make&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Model".',') !== FALSE) { echo " checked"; } ?> value="Equipment Model" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Model&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Model Year".',') !== FALSE) { echo " checked"; } ?> value="Equipment Model Year" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Model Year&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Description".',') !== FALSE) { echo " checked"; } ?> value="Equipment Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Equipment Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Year Purchased".',') !== FALSE) { echo " checked"; } ?> value="Equipment Year Purchased" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Year Purchased&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Mileage".',') !== FALSE) { echo " checked"; } ?> value="Equipment Mileage" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Mileage&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Hours Operated".',') !== FALSE) { echo " checked"; } ?> value="Equipment Hours Operated" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Hours Operated&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Notes".',') !== FALSE) { echo " checked"; } ?> value="Equipment Notes" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Notes&nbsp;&nbsp;

                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Nickname".',') !== FALSE) { echo " checked"; } ?> value="Equipment Nickname" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Nickname&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment VIN Number".',') !== FALSE) { echo " checked"; } ?> value="Equipment VIN Number" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;VIN Number&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Color".',') !== FALSE) { echo " checked"; } ?> value="Equipment Color" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Color&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Licence Plate".',') !== FALSE) { echo " checked"; } ?> value="Equipment Licence Plate" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Licence Plate&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Equipment Ownership Status".',') !== FALSE) { echo " checked"; } ?> value="Equipment Ownership Status" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Ownership Status&nbsp;&nbsp;

                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Labour" >
                        Labour<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_Labour" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($value_config, ','."Labour".',') !== FALSE) { echo " checked"; } ?> value="Labour" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Labour
                    <br><br>

                    <input type="checkbox" <?php if (strpos($value_config, ','."Labour Type".',') !== FALSE) { echo " checked"; } ?> value="Labour Type" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Type&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Labour Heading".',') !== FALSE) { echo " checked"; } ?> value="Labour Heading" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Heading&nbsp;&nbsp;


                    <input type="checkbox" <?php if (strpos($value_config, ','."Labour Category".',') !== FALSE) { echo " checked"; } ?> value="Labour Category" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Category &nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Labour Description".',') !== FALSE) { echo " checked"; } ?> value="Labour Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Labour Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Labour Quote Description" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Quote Description&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Labour Labour Code".',') !== FALSE) { echo " checked"; } ?> value="Labour Labour Code" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Labour Code&nbsp;&nbsp;
                    <input type="checkbox" <?php if (strpos($value_config, ','."Labour Name".',') !== FALSE) { echo " checked"; } ?> value="Labour Name" style="height: 20px; width: 20px;" name="config_fields[]">&nbsp;&nbsp;Name&nbsp;&nbsp;

                </div>
            </div>
        </div>

        <br>
        <div class="form-group">
            <div class="col-sm-6">
                <!--<a href="estimate.php" class="btn config-btn pull-right">Back</a>-->
				<a href="#" class="btn config-btn btn-lg" onclick="history.go(-1);return false;">Back</a>
            </div>
            <div class="col-sm-6">
                <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
            </div>
			<div class="clearfix"></div>
        </div>

    </div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>