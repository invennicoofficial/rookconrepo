<?php
/*
Add Expense
*/
include ('../include.php');
checkAuthorised('fund_development');

if(isset($_GET['action']) && $_GET['action'] == 'delete') {
    $fundingid = $_GET['fundingid'];
    $query = mysqli_query($dbc,"DELETE FROM fund_development_funding WHERE fundingid='$fundingid'");

    echo '<script type="text/javascript"> window.location.replace("funding.php"); </script>';
}

if (isset($_POST['funding_submit'])) {

    $funding_for = $_POST['funding_for'];
    $staff = $_POST['staff'];

    $contact = filter_var($_POST['contact'],FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);

    $ex_file = htmlspecialchars($_FILES["ex_file"]["name"], ENT_QUOTES);

    if (!file_exists('funding_download')) {
        mkdir('funding_download', 0777, true);
    }

    $N = count($_POST['amount']);
    for($i=0; $i < $N; $i++) {
        move_uploaded_file($_FILES["ex_file"]["tmp_name"][$i], "funding_download/" . $_FILES["ex_file"]["name"][$i]) ;

        $ex_date = filter_var($_POST['ex_date'][$i],FILTER_SANITIZE_STRING);
        $type = filter_var($_POST['type'][$i],FILTER_SANITIZE_STRING);
        $day_funding = filter_var($_POST['day_funding'][$i],FILTER_SANITIZE_STRING);
        $amount = filter_var($_POST['amount'][$i],FILTER_SANITIZE_STRING);
        $balance = filter_var($_POST['balance'][$i],FILTER_SANITIZE_STRING);
        $gst = filter_var($_POST['gst'][$i],FILTER_SANITIZE_STRING);
        $total = filter_var($_POST['total'][$i],FILTER_SANITIZE_STRING);

        $query_insert = "INSERT INTO `fund_development_funding` (`funding_for`, `contact`, `staff`, `title`, `description`, `ex_date`, `ex_file`, `type`, `day_funding`, `amount`, `balance`, `gst`, `total`) VALUES ('$funding_for', '$contact', '$staff', '$title', '$description', '$ex_date', '$ex_file[$i]', '$type', '$day_funding', '$amount', '$balance', '$gst', '$total')";

        $result_insert = mysqli_query($dbc, $query_insert);
    }

    echo '<script type="text/javascript"> window.location.replace("funding.php"); </script>';
    //mysqli_close($dbc);//Close the DB Connection
}
?>
<script type="text/javascript">

    $(document).ready(function() {
        var add_new_s = 1;
        $('#add_row_exp').on( 'click', function () {
            var clone = $('.additional_exp').clone();
            clone.find('.form-control').val('');
            clone.find('.set_pre').val(0);

            clone.find('.datepicker').val('');

            clone.find('#amount_0').attr('id', 'amount_'+add_new_s);
            clone.find('#gst_0').attr('id', 'gst_'+add_new_s);
            clone.find('#total_0').attr('id', 'total_'+add_new_s);

            clone.removeClass("additional_exp");

            $('#add_here_new_exp').append(clone);
            clone.find('.datepicker').each(function() {
                $(this).removeAttr('id').removeClass('hasDatepicker');
                $('.datepicker').datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true, yearRange: '1920:2025'});
            });

            add_new_s++;
            return false;
        });

    });

  	function numericFilter(txb) {
	   txb.value = txb.value.replace(/[^\0-9]/ig, "");
	}

    function countTotal(sel) {
        var end = sel.value;
        var typeId = sel.id;
        var arr = typeId.split('_');

        var amount = $("#amount_"+arr[1]).val();
        var gst = $("#gst_"+arr[1]).val();

        var total = parseFloat(amount) + parseFloat(gst);
        //alert('ethy');

        $("#total_"+arr[1]).val((total).toFixed(2));

    }
</script>
</head>

<body>
<?php include_once ('../navigation.php');
?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
    <h1>Add Funding</h1>
	<div class="pad-left gap-top double-gap-bottom"><a href="funding.php" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post" action="add_funding.php" enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php
    $get_value_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT fund_development_funding FROM field_config"));
    $value_config = ','.$get_value_config['fund_development_funding'].',';
    ?>

    <div class="panel-group" id="accordion2">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_1" >
                        Information<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_1" class="panel-collapse collapse">
                <div class="panel-body">

                <?php if (strpos($value_config, ','."Funding For".',') !== FALSE) { ?>
                    <div class="form-group">
                      <label for="site_name" class="col-sm-4 control-label">Funding For<span class="text-red">*</span>:</label>
                      <div class="col-sm-8">
                        <select data-placeholder="Choose a Funding For..." name="funding_for" class="chosen-select-deselect form-control" width="380">
                          <option value=""></option>
                          <option value="Staff">Staff</option>
                          <option value="Contact">Contact</option>
                          <option value="Business">Business</option>
                          <option value="Client">Client</option>
                          <option value="Customer">Customer</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="site_name" class="col-sm-4 control-label">Contact<span class="text-red">*</span>:</label>
                      <div class="col-sm-8">
                        <select data-placeholder="Choose a Contact..." name="contact" class="chosen-select-deselect form-control" width="380">
                          <option value=""></option>
                          <?php
                            $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE (category='Customer' OR category='Client') AND deleted=0");
                            while($row = mysqli_fetch_array($query)) {
                                echo "<option value='". decryptIt($row['name'])."'>".decryptIt($row['name']).'</option>';
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { ?>
                    <div class="form-group">
                      <label for="site_name" class="col-sm-4 control-label">Staff<span class="text-red">*</span>:</label>
                      <div class="col-sm-8">
                        <select data-placeholder="Choose a Staff Member..." name="staff" class="chosen-select-deselect form-control" width="380">
                          <option value=""></option>
                          <?php
                            $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0");
                            while($row = mysqli_fetch_array($query)) {
                                echo "<option value='". decryptIt($row['first_name']).' '.decryptIt($row['last_name'])."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."Funding Heading".',') !== FALSE) { ?>
                  <div class="form-group">
                    <label for="office_zip" class="col-sm-4 control-label">Funding Heading:</label>
                    <div class="col-sm-8">
                      <input name="title" type="text" class="form-control office_zip" />
                    </div>
                  </div>
                <?php } ?>

                <?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
                  <div class="form-group">
                    <label for="first_name[]" class="col-sm-4 control-label">Description:</label>
                    <div class="col-sm-8">
                      <textarea name="description" rows="5" cols="50" class="form-control"></textarea>
                    </div>
                  </div>
                <?php } ?>


                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                        Funding Info<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_2" class="panel-collapse collapse">
                <div class="panel-body">

                <div class="form-group clearfix  hide-titles-mob">
                    <?php if (strpos($value_config, ','."Funding Date".',') !== FALSE) { ?>
                    <label class="col-sm-2 text-center">Date</label>
                    <?php } ?>
                    <?php if (strpos($value_config, ','."Receipt".',') !== FALSE) { ?>
                    <label class="col-sm-2 text-center">Receipt</label>
                    <?php } ?>
                    <label class="col-sm-2 text-center">Type</label>
                    <?php if (strpos($value_config, ','."Day Funding".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center">Day Funding</label>
                    <?php } ?>
                    <?php if (strpos($value_config, ','."Amount".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center">Amount</label>
                    <?php } ?>
                    <?php if (strpos($value_config, ','."GST".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center">GST</label>
                    <?php } ?>
                    <?php if (strpos($value_config, ','."Amount".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center">Total</label>
                    <?php } ?>
                    <?php if (strpos($value_config, ','."Budget".',') !== FALSE) { ?>
                    <label class="col-sm-1 text-center">Budget</label>
                    <?php } ?>

                </div>

                <div class="additional_exp clearfix">
                    <div class="clearfix"></div>

                    <div class="form-group clearfix">
                        <?php if (strpos($value_config, ','."Funding Date".',') !== FALSE) { ?>
                        <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Funding Date:</label>
                            <input name="ex_date[]" type="text" class="datepicker form-control">
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Receipt".',') !== FALSE) { ?>
                        <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Receipt:</label>
                            <input name="ex_file[]" type="file" data-filename-placement="inside" class="form-control" />
                        </div>
                        <?php } ?>
                        <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Type:</label>
                            <select data-placeholder="Choose a User..." name="type[]" class="chosen-select-deselect1 form-control" width="380">
                              <option value=""></option>
                              <?php if (strpos($value_config, ','."Flight".',') !== FALSE) { ?>
                              <option value="Flight">Flight</option>
                              <?php } ?>
                              <?php if (strpos($value_config, ','."Hotel".',') !== FALSE) { ?>
                              <option value="Hotel">Hotel</option>
                              <?php } ?>
                              <?php if (strpos($value_config, ','."Breakfast".',') !== FALSE) { ?>
                              <option value="Breakfast">Breakfast</option>
                              <?php } ?>
                              <?php if (strpos($value_config, ','."Lunch".',') !== FALSE) { ?>
                              <option value="Lunch">Lunch</option>
                              <?php } ?>
                              <?php if (strpos($value_config, ','."Dinner".',') !== FALSE) { ?>
                              <option value="Dinner">Dinner</option>
                              <?php } ?>
                              <?php if (strpos($value_config, ','."Beverages".',') !== FALSE) { ?>
                              <option value="Beverages">Beverages</option>
                              <?php } ?>
                              <?php if (strpos($value_config, ','."Transportation".',') !== FALSE) { ?>
                              <option value="Transportation">Transportation</option>
                              <?php } ?>
                              <?php if (strpos($value_config, ','."Transportation".',') !== FALSE) { ?>
                              <option value="Transportation">Entertainment</option>
                              <?php } ?>
                              <?php if (strpos($value_config, ','."GAS".',') !== FALSE) { ?>
                              <option value="GAS">Gas</option>
                              <?php } ?>
                              <?php if (strpos($value_config, ','."Misc".',') !== FALSE) { ?>
                              <option value="Misc">Misc</option>
                              <?php } ?>
                            </select>
                        </div>
                        <?php if (strpos($value_config, ','."Day Funding".',') !== FALSE) { ?>
                        <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Day Funding:</label>
                            <input name="day_funding[]" type="text" class="form-control" />
                        </div>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Amount".',') !== FALSE) { ?>
                        <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Amount:</label>
                            <input name="amount[]" value= 0 onchange="countTotal(this)" id="amount_0" type="text" class="form-control set_pre" />
                        </div>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."GST".',') !== FALSE) { ?>
                        <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">GST:</label>
                            <input name="gst[]" value= 0 onchange="countTotal(this)" id="gst_0" type="text" class="form-control set_pre" />
                        </div>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Amount".',') !== FALSE) { ?>
                        <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Total:</label>
                            <input name="total[]" value= 0 id="total_0" type="text" class="form-control set_pre" />
                        </div>
                        <?php } ?>
                        <?php if (strpos($value_config, ','."Budget".',') !== FALSE) { ?>
                        <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Budget:</label>
                            <input name="balance[]" value= 0 type="text" class="form-control set_pre" />
                        </div>
                        <?php } ?>
                    </div>

                </div>

                <div id="add_here_new_exp"></div>

                <div class="form-group triple-gapped clearfix">
                    <div class="col-sm-offset-4 col-sm-8">
                        <button id="add_row_exp" class="btn brand-btn pull-left">Add Row</button>
                    </div>
                </div>

                </div>
            </div>
        </div>

    </div>

      <div class="form-group">
		<p><span class="text-red"><em>Required Fields *</em></span></p>
      </div>

        <div class="form-group">
            <div class="col-sm-6">
                <a href="funding.php" class="btn brand-btn mobile-block btn-lg">Back</a>
				<!--<a href="#" class="btn brand-btn mobile-block pull-right" onclick="history.go(-1);return false;">Back</a>-->
            </div>
            <div class="col-sm-6">
                <button type="submit" name="funding_submit" value="Submit" class="btn brand-btn mobile-block btn-lg pull-right">Submit</button>
            </div>
			<div class="clearfix"></div>
      </div>

    </form>
    </div>
  </div>
</div>
<?php include ('../footer.php'); ?>