<?php
/*
Add Vendor
*/
include ('../include.php');
error_reporting(0);
$config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_contracts`"));

if((!empty($_GET['contractid'])) && (!empty($_GET['action'])) && ($_GET['action'] == 'delete')) {
    $contractid = $_GET['contractid'];
	$tab = get_contract($dbc, $contractid, 'category');
    $query = mysqli_query($dbc,"DELETE FROM `contracts` WHERE contractid='$contractid'");
    echo '<script type="text/javascript"> window.location.replace("contracts.php?tab=$tab"); </script>';
}

if((!empty($_GET['action'])) && ($_GET['action'] == 'delete')) {
    $uploadid = $_GET['uploadid'];
	$tab = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category` FROM `contracts_upload` LEFT JOIN `contracts` ON `contracts_upload`.`contractid`=`contracts`.`contractid` WHERE `uploadid`='$uploadid'"),MYSQLI_NUM)[0];
    $query = mysqli_query($dbc,"DELETE FROM `contracts_upload` WHERE uploadid='$uploadid'");
    echo '<script type="text/javascript"> window.location.replace("contracts.php?tab=$tab"); </script>';
}

if (isset($_POST['add_contract'])) {
	$contractid = $_POST['add_contract'];
	if($_POST['new_category'] != '') {
		$category = filter_var($_POST['new_category'],FILTER_SANITIZE_STRING);
	} else {
		$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
	}
	if($_POST['new_heading'] != '') {
		$heading = filter_var($_POST['new_heading'],FILTER_SANITIZE_STRING);
	} else {
		$heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
	}

	if($_POST['new_heading_number'] != '') {
		$heading_number = filter_var($_POST['new_heading_number'],FILTER_SANITIZE_STRING);
	} else {
		$heading_number = filter_var($_POST['heading_number'],FILTER_SANITIZE_STRING);
	}

    //$heading_number = filter_var($_POST['heading_number'],FILTER_SANITIZE_STRING);
    $sub_heading_number	= filter_var($_POST['sub_heading_number'],FILTER_SANITIZE_STRING);
    $sub_heading		= filter_var($_POST['sub_heading'],FILTER_SANITIZE_STRING);

    $third_heading			= filter_var($_POST['third_heading'],FILTER_SANITIZE_STRING);
    $third_heading_number	= filter_var($_POST['third_heading_number'],FILTER_SANITIZE_STRING);

	$contract_name = filter_var($_POST['contract_name'],FILTER_SANITIZE_STRING);
	$contract_text = filter_var(htmlentities($_POST['contract_text']),FILTER_SANITIZE_STRING);
	$field_list = filter_var(implode('#*#',$_POST['field_list']),FILTER_SANITIZE_STRING);
	$default_list = filter_var(implode('#*#',$_POST['default_list']),FILTER_SANITIZE_STRING);
	
    $last_edited = date('Y-m-d');
    if(empty($contractid)) {
        $query_insert_vendor = "INSERT INTO `contracts` (`category`, `heading_number`, `heading`, `sub_heading_number`, `sub_heading`, `third_heading`, `third_heading_number`, `contract_name`, `contract_text`, `field_list`, `default_list`)
			VALUES ('$category', '$heading_number', '$heading', '$sub_heading_number', '$sub_heading', '$third_heading', '$third_heading_number', '$contract_name', '$contract_text', '$field_list', '$default_list')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $manualtypeid = mysqli_insert_id($dbc);

        $url = 'Added';
    } else {
        $query_update_vendor = "UPDATE `contracts` SET `category`='$category', `heading_number`='$heading_number', `heading`='$heading', `sub_heading_number`='$sub_heading_number', `sub_heading`='$sub_heading', `third_heading`='$third_heading', `third_heading_number`='$third_heading_number', `contract_name`='$contract_name', `contract_text`='$contract_text', `field_list`='$field_list', `default_list`='$default_list' WHERE `contractid`='$contractid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    echo '<script type="text/javascript"> window.location.replace("contracts.php?tab='.$category.'"); </script>';
}

?>
<script type="text/javascript">
$(document).ready(function() {

    $("#category").change(function() {
        if($("#category option:selected").text() == 'New Topic (Sub Tab)') {
                $( "#new_category" ).show();
        } else {
            $( "#new_category" ).hide();
        }
    });

    $("#heading").change(function() {
        if($("#heading option:selected").text() == 'New Heading') {
                $( "#new_heading" ).show();
        } else {
            $( "#new_heading" ).hide();
        }
    });

    $("#heading_number").change(function() {
        if($("#heading_number option:selected").text() == 'New Heading Number') {
                $("#new_heading_number").show();
        } else {
            $( "#new_heading_number" ).hide();
        }
    });

    $('#add_row_doc').on( 'click', function () {
        var clone = $('.additional_doc').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_doc");
        $('#add_here_new_doc').append(clone);
        return false;
    });

    $('#add_row_link').on( 'click', function () {
        var clone = $('.additional_link').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_link");
        $('#add_here_new_link').append(clone);
        return false;
    });

    $('#add_row_videos').on( 'click', function () {
        var clone = $('.additional_videos').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_videos");
        $('#add_here_new_videos').append(clone);
        return false;
    });

} );
function selectSection(sel) {
    var category = $('#category').val();
    var type = $('#type').val();
	var heading_number = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "contracts_ajax.php?fill=section&heading_number="+heading_number+"&category="+category,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#heading").val(response);
			$("#heading").trigger("change.select2");
		}
	});
	if(heading_number > 0) {
		$.ajax({    //create an ajax request to load_page.php
			type: "POST",
			url: "contracts_ajax.php?fill=sub_heading_number",
			data: { heading_number: heading_number, sub_heading: $('#sub_heading_value').val(), category: category, type: type, max_section: $('#max_subsection').val() },
			dataType: "html",   //expect html to be returned
			success: function(response){
				$("#sub_heading_number").empty().html(response).trigger("change.select2");
			}
		});
	}
	else {
		$("#sub_heading_number").empty().trigger("change.select2");
	}
}
function selectSubSection(sel) {
    var category = $('#category').val();
    var type = $('#type').val();
	var sub_heading_number = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "contracts_ajax.php?fill=subsection&sub_heading_number="+sub_heading_number+"&category="+category,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#sub_heading").val(response);
		}
	});
	if(sub_heading_number > 0) {
		$.ajax({    //create an ajax request to load_page.php
			type: "POST",
			url: "contracts_ajax.php?fill=third_heading_number",
			data: { sub_heading_number: sub_heading_number, third_heading: $('#third_heading_value').val(), category: category, type: type, max_section: $('#max_subsection').val() },
			dataType: "html",   //expect html to be returned
			success: function(response){
				$("#third_heading_number").empty().html(response).trigger("change.select2");
			}
		});
	}
	else {
		$("#third_heading_number").empty().trigger("change.select2");
	}
}
function add_field_row(btn) {
	var row = $('.field_row').last().clone();
	row.find('input').val('');
	$(btn).before(row);
}
</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('contracts');
?>
<div class="container">
  <div class="row">

    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php $sections = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(DISTINCT `heading_number`) headings, COUNT(DISTINCT `sub_heading_number`) sub_headings, COUNT(DISTINCT `third_heading_number`) third_headings FROM `contracts` WHERE `category`='$category'"));
		$max_section = $sections['headings'] + 5;
		$max_subsection = $sections['sub_headings'] + 5;
		$max_thirdsection = $sections['third_headings'] + 5;

        $category = $_GET['tab'];
        $heading = '';
        $sub_heading = '';
        $heading_number = '';
        $sub_heading_number = '';
        $third_heading_number = '';
        $third_heading = '';
        $contract_name = '';
		$contract_text = '';
		$field_list = '';
		$defaults_list = '';

        if(!empty($_GET['contractid'])) {
            $contractid = $_GET['contractid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM contracts WHERE contractid='$contractid'"));

			$category = $get_contact['category'];
			$heading = $get_contact['heading'];
			$sub_heading = $get_contact['sub_heading'];
			$heading_number = $get_contact['heading_number'];
			$sub_heading_number = $get_contact['sub_heading_number'];
			$third_heading_number = $get_contact['third_heading_number'];
			$third_heading = $get_contact['third_heading'];
			$contract_name = $get_contact['contract_name'];
			$contract_text = $get_contact['contract_text'];
			$field_list = $get_contact['field_list'];
			$defaults_list = $get_contact['default_list'];
		} ?>
        <input type="hidden" id="type" name="type" value="<?php echo $type; ?>" />
        <input type="hidden" id="max_subsection" name="max_subsection" value="<?php echo $max_subsection ?>" />
        <input type="hidden" id="sub_heading_value" name="sub_heading_value" value="<?php echo $sub_heading_number ?>" />
        <input type="hidden" id="third_heading_value" name="third_heading_value" value="<?php echo $third_heading_number ?>" />

        <h1><?= (empty($contractid) ? 'Add ' : 'Edit ').$category ?> Contract</h1>

		<div class="gap-top triple-gap-bottom"><a href="contracts.php?tab=<?= $category ?>" class="btn config-btn">Back to Dashboard</a></div>

        <div class="panel-group" id="accordion2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_list" >Headings<span class="glyphicon glyphicon-plus"></span></a>
                    </h4>
                </div>

                <div id="collapse_list" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php include ('add_basic_fields.php'); ?>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_contract" >Contract Information<span class="glyphicon glyphicon-plus"></span></a>
                    </h4>
                </div>

                <div id="collapse_contract" class="panel-collapse collapse">
                    <div class="panel-body">
						<div class="form-group">
							<label class="col-sm-4 control-label">Contract Name:</label>
							<div class="col-sm-8">
								<input type="text" name="contract_name" class="form-control" value="<?= $contract_name ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Contract Text:<br />
								<em>Enter the text of the contract as you want it to appear in the PDF. It will use the formatting
								and spacing as you set it here. To add fields, enter the name of the field, with a default value if
								you wish, in the fields below. Then enclose the name of the field in double square brackets, for
								example [[FIELDNAME]].</em></label>
							<div class="col-sm-8">
								<textarea name="contract_text" class="form-control"><?= $contract_text ?></textarea>
							</div>
						</div>
						Enter the fields below. The Contract Field should exactly match whatever is in the double square brackets in the contract above, and must be unique. The default value will be filled into the form when you fill in the contract, with the following exceptions:
						<ul><li>A default value of TODAY will be replaced with today's date.</li>
						<li>A default value of CUSTOMER will be replaced with the customer's name.</li>
						<li>A default value of SIGNATURE will be replaced with a place to digitally sign the contract.</li></ul>
						<div class="col-sm-5 text-center">Contract Field</div><div class="col-sm-5 text-center">Default Value</div>
						<?php $defaults = explode('#*#', $defaults_list);
						foreach(explode('#*#', $field_list) as $row => $field) {
							$default = $defaults[$row]; ?>
							<div class="form-group field_row">
								<div class="col-sm-5"><input type="text" class="form-control" name="field_list[]" value="<?= $field ?>"></div>
								<div class="col-sm-5"><input type="text" class="form-control" name="default_list[]" value="<?= $default ?>"></div>
								<div class="col-sm-2"><button onclick="$(this).closest('.form-group').remove(); return false;" class="btn brand-btn">Delete</button></div>
							</div>
						<?php } ?>
						<button class="btn brand-btn pull-right" onclick="add_field_row(this); return false;">Add Field</button>
                    </div>
                </div>
            </div>

        </div>

            <div class="form-group">
				<p><span class="hp-red"><em>Required Fields *</em></span></p>
            </div>

            <div class="form-group">
                <div class="col-sm-6">
					<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here to discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                    <a href="contracts.php?tab=<?= $category ?>" class="btn brand-btn btn-lg">Back</a>
                </div>
				<div class="col-sm-6">
					<button type="submit" name="add_contract" value="<?= $contractid ?>" class="btn brand-btn btn-lg pull-right">Submit</button>
					<span class="popover-examples list-inline pull-right" style="margin:15px 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to save your contract."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				</div>
            </div>

			</div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
