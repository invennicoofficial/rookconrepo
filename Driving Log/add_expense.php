<?php
/*
Add Expense
*/
include ('../include.php');
checkAuthorised('driving_log');

if (isset($_POST['expense_submit'])) {

    $driverid = $_SESSION['staffid'];
    $fill_date = $_POST['fill_date'];
    $per_dium = filter_var($_POST['per_dium'],FILTER_SANITIZE_STRING);
    $upload_document = htmlspecialchars($_FILES["file"]["name"], ENT_QUOTES);

    $final_total = $_POST['final_total'];

    if (!file_exists('download/expense')) {
        mkdir('download/expense', 0777, true);
    }
	move_uploaded_file($_FILES["file"]["tmp_name"],	"download/expense/" . $upload_document);

    $query_insert_expense = "INSERT INTO `driving_log_expense` (`driverid`, `fill_date`, `per_dium`, `upload_document`, `final_total`) VALUES ('$driverid', '$fill_date', '$per_dium', '$upload_document', '$final_total')";
    $result_insert_expense = mysqli_query($dbc, $query_insert_expense);

    $expenseid = mysqli_insert_id($dbc);

    // Expense Report

        $get_emp = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT first_name, last_name FROM staff WHERE staffid='$driverid'"));
        $emp_name = $get_emp['first_name'].' '.$get_emp['last_name'];

        $file_name = 'expense_'.$expenseid.'.doc';

        if (!file_exists('download/expense/doc')) {
            mkdir('download/expense/doc', 0777, true);
        }

        $fp = fopen("download/expense/doc/".$file_name, 'w+');

        $html = '<h1>Expense Report #'.$expenseid.'</h1>'.
            $fill_date.'<hr><br>
            <b>Driver Name:</b> '.$emp_name.'

        <br><hr><br>

        <table border=2>
        <tr>
            <th>Date</th>
            <th>Country</th>
            <th>Total Amount on Invoice</th>
            <th>Invoice Amount</th>
            <th>Tips</th>
            <th>GST Amount</th>
            <th>GL Account Coding</th>
            <th>Description and Customer Purpose</th>
            <th>Total</th>
        </tr>
        ';

        $N = count($_POST['expense_date']);
        for($i=0; $i < $N; $i++) {
            $html .= '<tr>
                <td>'.$_POST['expense_date'][$i].'</td>
                <td>'.$_POST['country'][$i].'</td>
                <td>'.$_POST['amount_on_invoice'][$i].'</td>
                <td>'.$_POST['invoice_amount'][$i].'</td>
                <td>'.$_POST['tips'][$i].'</td>
                <td>'.$_POST['gst_amount'][$i].'</td>
                <td>'.$_POST['gl'][$i].'</td>
                <td>'.$_POST['desc'][$i].'</td>
                <td>'.$_POST['total'][$i].'</td>
            </tr>';
        }

        $html .= '</table>';
        $html .= '<h3>Total Amount</h3>
            '.$final_total.'<br><hr><br>';


        $html .= '<table width=100%>
        <tr>
            <td>Driver Signature</td>
            <td>Title:</td>
            <td>Date:</td>
        </tr>
        <tr>
            <td>Approval Signature</td>
            <td>Title:</td>
            <td>Date:</td>
        </tr>
        </table>';

        fwrite($fp, $html);
        fclose($fp);

    // Expense Report


    echo '<script type="text/javascript"> window.location.replace("expenses.php"); </script>';

    mysqli_close($dbc);//Close the DB Connection
}

?>
<script type="text/javascript">

    $(document).ready(function() {
        var i=2;
        $('#add_row').on( 'click', function () {
                var clone = $('.additional_detail').clone();
                clone.find('.expense').val('');
                clone.find('.set_value').val(0);
                clone.find('.change_title').html("<hr/>Additional Expense "+i);
                clone.find('#invoice_1').attr('id', 'invoice_'+i);
                clone.find('#tips_1').attr('id', 'tips_'+i);
                clone.find('#gst_1').attr('id', 'gst_'+i);
                clone.find('#total_1').attr('id', 'total_'+i);
                i++;
                clone.removeClass("additional_detail");
                $('#add_here_new_detail').append(clone);
                return false;
        });

        $("#form1").submit(function( event ) {

            var driver = $("input[name=driver]").val();
            var per_dium = $("input[name=per_dium]").val();
            var final_total = $("input[name=final_total]").val();

            if (driver == '' || per_dium == '' || final_total == '0' || (!$("#accept_policy").is(":checked"))) {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }

        });

    });

    function sum_cost(txb) {
        var sum = 0;

        var get_id = txb.id;

        var split_id = get_id.split('_');

        var inv = parseFloat($('#invoice_'+split_id[1]).val());
        var tips = parseFloat($('#tips_'+split_id[1]).val());
        var gst = parseFloat($('#gst_'+split_id[1]).val());

        var total = parseFloat(inv+tips+gst);

        $('#total_'+split_id[1]).val(total);

        $('input[name="total[]"]').each(function(){
            sum += parseFloat($(this).val());
        });

        $('#final_total').val(sum);
    }

  	function numericFilter(txb) {
	   txb.value = txb.value.replace(/[^\0-9]/ig, "");
	}
</script>
</head>

<body>
<?php include_once ('navigation.php');

?>
<div class="container">
  <div class="row">

    <h1 class="triple-pad-bottom">Add Expense</h1>
    <form id="form1" name="form1" method="post" action="add_expense.php" enctype="multipart/form-data" class="form-horizontal" role="form">

        <div class="panel-group" id="accordion2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_info" >
                            Information<span class="glyphicon glyphicon-minus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_info" class="panel-collapse collapse in">
                    <div class="panel-body">

                        <div class="form-group">
                          <label for="site_name" class="col-sm-4 control-label">Driver<span class="brand-color">*</span>:</label>
                          <div class="col-sm-8">
                                <?php
                                $staff = $_SESSION['first_name'].' '.$_SESSION['last_name'];
                                ?>
                                <input name="driver" type="text" value="<?php echo $staff; ?>" class="form-control"/>
                          </div>
                        </div>

                        <div class="form-group">
                            <label for="first_name" class="col-sm-4 control-label text-right">Date:</label>
                            <div class="col-sm-8">
                                <input name="fill_date" type="text" class="datepicker"></p>
                            </div>
                        </div>

					  <div class="form-group">
						<label for="fax_number"	class="col-sm-4	control-label">Number of Per Diem Days<span class="brand-color">*</span>:</label>
						<div class="col-sm-8">
						  <input name="per_dium" type="text" class="form-control"/>
						</div>
					  </div>

						<div class="form-group">
							<label for="file[]"	class="col-sm-4	control-label">Upload Document:
                            <span class="popover-examples list-inline">&nbsp;
                            <a  data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="img/info.png" width="20"></a>
                            </span>
                            </label>
							<div class="col-sm-8">
							  <input name="file" type="file" id="file" data-filename-placement="inside"	class="form-control" />
							</div>
						</div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <!--<a href="expenses.php" class="btn brand-btn pull-right">Back</a>-->
								<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button type="submit" name="expense_submit" value="Submit" class="btn brand-btn pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_desc" >
                            Expense Information<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_desc" class="panel-collapse collapse">
                    <div class="panel-body">

					    <?php $id=1; ?>
					    <div class="form-group">

							<div class="col-sm-15">
								<div class="form-group clearfix">
									<label class="col-sm-1 text-center">Date</label>
									<label class="col-sm-1 text-center">Country</label>
									<label class="col-sm-1 text-center">Total Amount on Invoice</label>
									<label class="col-sm-1 text-center">Invoice Amount</label>
									<label class="col-sm-1 text-center">Tips</label>
									<label class="col-sm-1 text-center">GST Amount</label>
									<label class="col-sm-1 text-center">GL Account Coding</label>
                                    <label class="col-sm-3 text-center">Description and Customer Purpose</label>
                                    <label class="col-sm-1 text-center">Total</label>
								</div>

								<div class="enter_cost additional_detail clearfix">
									<div class="clearfix"></div>
									<div class="form-group clearfix">
										<div class="col-sm-1">
											<input name="expense_date[]" id="custom_product_qty_<?php echo $id; ?>" type="text" class="form-control expense" />
										</div> <!-- Quantity -->
										<div class="col-sm-1">
                                            <select data-placeholder="Choose a Country..." id="type" name="country[]" class="chosen-select-deselect1 form-control" width="380">
                                              <option value=""></option>
                                              <option value="Canada">Canada</option>
                                              <option value="USA">USA</option>
                                            </select>
										</div> <!-- Quantity -->
										<div class="col-sm-1">
											<input name="amount_on_invoice[]" type="text" class="form-control expense" />
										</div> <!-- Quantity -->
										<div class="col-sm-1">
											<input name="invoice_amount[]" id="invoice_<?php echo $id; ?>" value=0 onKeyUp="numericFilter(this); sum_cost(this)" type="text" class="form-control set_value" />
										</div> <!-- Quantity -->
										<div class="col-sm-1">
											<input name="tips[]" id="tips_<?php echo $id; ?>" type="text" value=0 onKeyUp="numericFilter(this); sum_cost(this)" class="form-control set_value" />
										</div> <!-- Quantity -->
										<div class="col-sm-1">
											<input name="gst_amount[]" onKeyUp="numericFilter(this); sum_cost(this);" value=0 type="text" id="gst_<?php echo $id; ?>" class="form-control set_value" />
										</div> <!-- Quantity -->
										<div class="col-sm-1">
                                            <select data-placeholder="Choose a Coding..." id="type" name="gl[]" class="chosen-select-deselect1 form-control" width="380">
                                              <option value=""></option>
                                              <option value="Travel">Travel</option>
                                              <option value="Parking">Parking</option>
                                              <option value="Hotel">Hotel</option>
                                              <option value="Fuel">Fuel</option>
                                            </select>
										</div> <!-- Quantity -->
										<div class="col-sm-3">
											<input name="desc[]" type="text" class="form-control expense" />
										</div> <!-- Quantity -->
										<div class="col-sm-1">
											<input name="total[]" id="total_<?php echo $id; ?>" onKeyUp="numericFilter(this);" value=0 type="text" class="form-control set_value" />
										</div> <!-- Quantity -->
									</div>

								</div>

								<div id="add_here_new_detail"></div>

								<div class="form-group triple-gapped clearfix">
									<div class="col-sm-offset-4 col-sm-8">
										<button id="add_row" class="btn brand-btn pull-left">Add Row</button>
									</div>
								</div>
								<?php $id++; ?>

                                <div class="form-group">
                                    <label for="travel_task" class="col-sm-4 control-label">Total Amount<span class="brand-color">*</span>:</label>
                                    <div class="col-sm-8">
                                        <input name="final_total" id="final_total" value=0 type="text" class="form-control"/>
                                    </div>
                                </div>


							</div>
						</div>
                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <!--<a href="expenses.php" class="btn brand-btn pull-right">Back</a>-->
								<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button type="submit" name="expense_submit" value="Submit" class="btn brand-btn pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_rates" >
                            Policy<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_rates" class="panel-collapse collapse">
                    <div class="panel-body">

                    <div class="form-group">
                        <div class="col-sm-12 col-sm-offset-1">
                            <label for="site_name" style="font-size: 20px;"><input type="checkbox" style="height: 25px; width: 25px;" id="accept_policy" name="accept_policy" value=1>&nbsp; By Clicking this box I agree to the following: <br>All amounts included on this report were incurred for business purposes only; and I have attached all detailed receipts for my reimbursement claim.<span class="brand-color">*</span></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 clearfix">
                            <!--<a href="expenses.php" class="btn brand-btn pull-right">Back</a>-->
							<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>
                        </div>
                        <div class="col-sm-8">
                            <button type="submit" name="expense_submit" value="Submit" class="btn brand-btn pull-right">Submit</button>
                        </div>
                    </div>

					</div>
				</div>
			</div>
		</div>

      <div class="form-group">
          <div class="col-sm-4">
              <p><span class="text-red pull-right"><em>Required Fields *</em></span></p>
          </div>
          <div class="col-sm-8"></div>
      </div>

        <div class="form-group">
            <div class="col-sm-4 clearfix">
                <!--<a href="expenses.php" class="btn brand-btn pull-right">Back</a>-->
				<a href="#" class="btn brand-btn pull-right" onclick="history.go(-1);return false;">Back</a>
            </div>
            <div class="col-sm-8">
                <button type="submit" name="expense_submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
        </div>

    </form>

  </div>
</div>
<?php include ('footer.php'); ?>