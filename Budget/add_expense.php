<?php
/*
Add Expense
*/
include ('../include.php');
checkAuthorised('budget');
$from_url = (empty($_GET['from_url']) ? 'budget.php?maintype=expense_tracking' : $_GET['from_url']);

if (isset($_POST['expense_submit'])) {

    $budgetid = $_POST['budgetid'];
    $submit_staff = $_POST['staff'];
	$category = $_POST['category'];
	$budget_categoryid = $_POST['budget_heading'];
    $expense_heading = filter_var($_POST['title'],FILTER_SANITIZE_STRING);

    $ex_file = $_FILES["ex_file"]["name"];

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

    $N = count($_POST['amount']);
    for($i=0; $i < $N; $i++) {

        move_uploaded_file($_FILES["ex_file"]["tmp_name"][$i], "download/" . $_FILES["ex_file"]["name"][$i]) ;
		$ex_file[$i] = htmlspecialchars($ex_file[$i], ENT_QUOTES);
        $expense_date = filter_var($_POST['ex_date'][$i],FILTER_SANITIZE_STRING);
        $actual_amount = filter_var($_POST['amount'][$i],FILTER_SANITIZE_STRING);
        $tax = filter_var($_POST['gst'][$i],FILTER_SANITIZE_STRING);
        $total = filter_var($_POST['total'][$i],FILTER_SANITIZE_STRING);

        $query_insert_expense = "INSERT INTO `budget_expense` (`budget_categoryid`, `expense_heading`, `expense_date`, `submit_staff`, `reciept`,
		`actual_amount`, `tax`, `total`) VALUES ('$budget_categoryid', '$expense_heading', '$expense_date', '$submit_staff', 
		'$ex_file[$i]', '$actual_amount', '$tax', '$total')";
        $result_insert_expense = mysqli_query($dbc, $query_insert_expense);
    }

    echo '<script type="text/javascript"> window.location.replace("'.$from_url.'"); </script>';
    //mysqli_close($dbc);//Close the DB Connection
}
?>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        //var projectclientid = $("#projectclientid").val();
        var budget_name = $("input[name=budget_name]").val();
        var category = $("#category").val();
		var budget_heading = $("#budget_heading").val();
		var staff = $("#staff").val();
		var expense_heading = $("#expense_heading").val();
		
        if (expense_heading == '' || budget_name == '' || staff == '' || category == '' || budget_heading == '') {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });
});
</script>
<script type="text/javascript">
var contacts = '';
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

		contacts=$('[name=contact] option');
		$('[name=expense_for]').change(function() {
			$('[name=contact]').empty();
			contacts.each(function() {
				var type = $(this).data('type');
				var category = $('[name=expense_for]').val();
				if(type == '' || type == category || category == '') {
					$('[name=contact]').append($(this));
				}
				$('[name=contact]').val('').trigger('change.select2');
			});
		});
    });
    $(document).on('change', 'select[name="budgetid"]', function() { selectCategory(this); });
    $(document).on('change', 'select[name="category"]', function() { selectHeading(this); });

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
	
	function selectCategory(sel) {
		var stage = encodeURIComponent(sel.value);
		var typeId = sel.id;
		var arr = typeId.split('_');

		$.ajax({
			type: "GET",
			url: "budget_ajax_all.php?fill=budget_cat_config&value="+stage,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$('#input_budgetid').val(stage);
				$("#category").html(response);
				$("#category").trigger("change.select2");
			}
		});
	}
	
	function selectHeading(sel) {
		var stage = encodeURIComponent(sel.value);
		var typeId = sel.id;
		var arr = typeId.split('_');
		var budgetid = $('#input_budgetid').val();
		$.ajax({
			type: "GET",
			url: "budget_ajax_all.php?fill=budget_heading_config&value="+stage+"&budgetid="+budgetid,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$("#budget_heading").html(response);
				$("#budget_heading").trigger("change.select2");
			}
		});
	}
</script>
</head>

<body>
<?php include_once ('../navigation.php');
?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
    <h1>Add Expense</h1>
	<div class="pad-left gap-top double-gap-bottom"><a href="<?php echo $from_url; ?>" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
	<input type='hidden' name='input_budgetid' value='' id='input_budgetid' />
    <?php
    $get_value_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT expense FROM field_config"));
    $value_config = ','.$get_value_config['expense'].',';
    ?>

    <div class="panel-group" id="accordion2">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_1" >
                        Budget Information<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_1" class="panel-collapse collapse">
                <div class="panel-body">

                
                    <div class="form-group">
                      <label for="site_name" class="col-sm-4 control-label">Budget<span class="text-red">*</span>:</label>
                      <div class="col-sm-8">
                        <select data-placeholder="Select a Budget..." name="budgetid" class="chosen-select-deselect form-control" width="380">
                          <option value=""></option>
                          <?php
                            $query = mysqli_query($dbc,"SELECT budget_name, budgetid FROM budget WHERE status = 2");
                            while($row = mysqli_fetch_array($query)) {
                                echo "<option value='". $row['budgetid']."'>".$row['budget_name'].'</option>';
                            }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="site_name" class="col-sm-4 control-label">Category<span class="text-red">*</span>:</label>
                      <div class="col-sm-8">
                        <select data-placeholder="Select a Category..." name="category" id='category' class="chosen-select-deselect form-control" width="380">
                          <option value=""></option>
                        </select>
                      </div>
                    </div>
             
					<div class="form-group">
                      <label for="site_name" class="col-sm-4 control-label">Budget Heading<span class="text-red">*</span>:</label>
                      <div class="col-sm-8">
                        <select data-placeholder="Select a Budget Heading..." name="budget_heading" id='budget_heading' class="chosen-select-deselect form-control" width="380">
                          <option value=""></option>
                        </select>
                      </div>
                    </div>

                
                    <div class="form-group">
                      <label for="site_name" class="col-sm-4 control-label">Staff Submitting Expense<span class="text-red">*</span>:</label>
                      <div class="col-sm-8">
                        <select data-placeholder="Select a Staff Member..." name="staff" class="chosen-select-deselect form-control" width="380">
                          <option value=""></option>
                          <?php
                            $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
                            foreach($query as $id) {
                                echo "<option value='". $id."'>".get_contact($dbc, $id).'</option>';
                            }
                          ?>
                        </select>
                      </div>
                    </div>
               

               
                  <div class="form-group">
                    <label for="office_zip" class="col-sm-4 control-label">Expense Heading:</label>
                    <div class="col-sm-8">
                      <input name="title" type="text" class="form-control office_zip" />
                    </div>
                  </div>
                
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                        Expense Info<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_2" class="panel-collapse collapse">
                <div class="panel-body">

                <div class="form-group clearfix  hide-titles-mob">
                    <label class="col-sm-3 text-center">Expense Date</label>
                    <label class="col-sm-3 text-center">Receipt</label>
                    <label class="col-sm-2 text-center">Total Before Tax</label>
                    <label class="col-sm-1 text-center">Tax</label>
                    <label class="col-sm-1 text-center">Total</label>
                </div>

                <div class="additional_exp clearfix">
                    <div class="clearfix"></div>

                    <div class="form-group clearfix">
                        <div class="col-sm-3"><label for="company_name" class="col-sm-4 show-on-mob control-label">Expense Date:</label>
                            <input name="ex_date[]" type="text" class="datepicker form-control">
                        </div>
                        <div class="col-sm-3"><label for="company_name" class="col-sm-4 show-on-mob control-label">Receipt:</label>
                            <input name="ex_file[]" type="file" data-filename-placement="inside" class="form-control" />
                        </div>
                        <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Total Before Tax:</label>
                            <input name="amount[]" value= 0 onchange="countTotal(this)" id="amount_0" type="text" class="form-control set_pre" />
                        </div>
                        <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Tax:</label>
                            <input name="gst[]" value= 0 onchange="countTotal(this)" id="gst_0" type="text" class="form-control set_pre" />
                        </div>
                        <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Total:</label>
                            <input name="total[]" value= 0 id="total_0" type="text" class="form-control set_pre" />
                        </div>
						<div class="col-sm-1">
							<button class="btn brand-btn" onclick="$(this).closest('.form-group').remove(); return false;">Delete</button>
						</div>
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
                <a href="<?php echo $from_url; ?>" class="btn brand-btn btn-lg">Back</a>
				<!--<a href="#" class="btn brand-btn mobile-block pull-right" onclick="history.go(-1);return false;">Back</a>-->
            </div>
            <div class="col-sm-6">
                <button type="submit" name="expense_submit" value="Submit" class="btn brand-btn mobile-block btn-lg pull-right">Submit</button>
            </div>
      </div>

    </form>
    </div>
  </div>
</div>
<?php include ('../footer.php'); ?>