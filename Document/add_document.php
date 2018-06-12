<?php
/*
Upload Docs
*/
include ('../include.php');
checkAuthorised('documents');
error_reporting(0);

if (isset($_POST['submit'])) {

	if($_POST['new_tile'] != '') {
		$tile_name = $_POST['new_tile'];
	} else {
		$tile_name = $_POST['tile_name'];
	}

	if($_POST['new_sub_tile'] != '') {
		$sub_tile_name = $_POST['new_sub_tile'];
	} else {
		$sub_tile_name = $_POST['sub_tile_name'];
	}

	if($sub_tile_name == '') {
		$sub_tile_name = $tile_name;
	}

	if($_POST['new_tile_heading'] != '') {
		$tile_heading = $_POST['new_tile_heading'];
	} else {
		$tile_heading = $_POST['tile_heading'];
	}

    $document = htmlspecialchars($_FILES["file"]["name"], ENT_QUOTES);

	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}

	$document = htmlspecialchars($_FILES["file"]["name"], ENT_QUOTES);

    for($i = 0; $i < count($_FILES['file']['name']); $i++) {

        move_uploaded_file($_FILES["file"]["tmp_name"][$i], "download/" . $_FILES["file"]["name"][$i]) ;

        $query_insert_client_doc = "INSERT INTO `documents` (`tile_name`, `sub_tile_name`, `tile_heading`, `document`) VALUES ('$tile_name', '$sub_tile_name', '$tile_heading', '$document[$i]')";

        $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);

    }

    echo '<script type="text/javascript"> window.location.replace("documents.php?type=view_document&tile_name='.$tile_name.'&sub_tile_name='.$sub_tile_name.'"); </script>';

}

?>
<script type="text/javascript">
$(document).ready(function() {

    $("#form1").submit(function( event ) {
        var new_tile = $("input[name=new_tile]").val();
        var new_sub_tile = $("input[name=new_sub_tile]").val();
        var new_tile_heading = $("input[name=new_tile_heading]").val();

        var tile_name = $("#tile_name").val();
        var sub_tile_name = $("#sub_tile_name").val();
        var tile_heading = $("#tile_heading").val();

        if (tile_name == '' || sub_tile_name == '' || tile_heading == '') {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
        if(((tile_name == 'Other') && (new_tile == '')) || ((sub_tile_name == 'Other') && (new_sub_tile == ''))  || ((tile_heading == 'Other') && (new_tile_heading == ''))) {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });

    $("#tile_name").change(function() {
        if($("#tile_name option:selected").text() == 'New Tile') {
                $( "#new_tile" ).show();
        } else {
            $( "#new_tile" ).hide();
        }
    });

    $("#sub_tile_name").change(function() {
        if($("#sub_tile_name option:selected").text() == 'New Sub Tile') {
                $( "#new_sub_tile" ).show();
        } else {
            $( "#new_sub_tile" ).hide();
        }
    });

    $("#tile_heading").change(function() {
        if($("#tile_heading option:selected").text() == 'New Tile Heading') {
                $("#new_tile_heading").show();
        } else {
            $("#new_tile_heading").hide();
        }
    });

});
</script>
</head>
<body>

<?php include_once ('../navigation.php');

?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
        <h1>Upload Documents</h1>
		<div class="pad-left gap-top double-gap-bottom"><a href="documents.php" class="btn config-btn">Back to Dashboard</a></div>

        <form action="add_document.php" id="form1" method="post" class="form-horizontal" enctype="multipart/form-data" role="form">

            <?php
                $tile_name = '';
                if(!empty($_GET['tile_name'])) {
                    $tile_name = $_GET['tile_name'];
					echo '<input type="hidden" name="tile_name" value="'.$tile_name.'">';
				}

				?>

			  <div class="form-group">
				<label for="position[]" class="col-sm-4 control-label">Tile Name<span class="brand-color">*</span>:</label>
				<div class="col-sm-8">
						<select data-placeholder="Choose a Tile..." id="tile_name" name="tile_name" class="chosen-select-deselect form-control" width="380">
						  <option value=""></option>
						  <?php
							$query = mysqli_query($dbc,"SELECT distinct(tile_name) FROM documents");
							while($row = mysqli_fetch_array($query)) {
								if ($_GET['tile_name'] == $row['tile_name']) {
									$selected = 'selected="selected"';
								} else {
									$selected = '';
								}
								echo "<option ".$selected." value='". $row['tile_name']."'>".$row['tile_name'].'</option>';
							}
							echo "<option value = 'Other'>New Tile</option>";
						  ?>
						</select>
				</div>
			  </div>

			   <div class="form-group" id="new_tile" style="display: none;">
				<label for="travel_task" class="col-sm-4 control-label">
                <span class="popover-examples list-inline">&nbsp;
                <a  data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations, '&' symbols, or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                </span>
                </label>
				<div class="col-sm-8">
					<input name="new_tile" type="text" class="form-control"/>
				</div>
			  </div>

			  <div class="form-group">
				<label for="position[]" class="col-sm-4 control-label">Sub Tile Name<span class="brand-color">*</span>:</label>
				<div class="col-sm-8">
						<select data-placeholder="Choose a Tile..." id="sub_tile_name" name="sub_tile_name" class="chosen-select-deselect form-control" width="380">
						  <option value=""></option>
						  <?php
							$query = mysqli_query($dbc,"SELECT distinct(sub_tile_name) FROM documents");
							while($row = mysqli_fetch_array($query)) {
								if ($_GET['sub_tile_name'] == $row['sub_tile_name']) {
									$selected = 'selected="selected"';
								} else {
									$selected = '';
								}
								echo "<option ".$selected." value='". $row['sub_tile_name']."'>".$row['sub_tile_name'].'</option>';
							}
							echo "<option value = 'Other'>New Sub Tile</option>";
						  ?>
						</select>
				</div>
			  </div>

			   <div class="form-group" id="new_sub_tile" style="display: none;">
				<label for="travel_task" class="col-sm-4 control-label">
                <span class="popover-examples list-inline">&nbsp;
                <a  data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations, '&' symbols, or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                </span>
                </label>
				<div class="col-sm-8">
					<input name="new_sub_tile" type="text" class="form-control"/>
				</div>
			  </div>

			  <div class="form-group">
				<label for="position[]" class="col-sm-4 control-label">Tile Heading<span class="brand-color">*</span>:</label>
				<div class="col-sm-8">
						<select data-placeholder="Choose a Tile Heading..." id="tile_heading" name="tile_heading" class="chosen-select-deselect form-control" width="380">
						  <option value=""></option>
						  <?php
							$query = mysqli_query($dbc,"SELECT distinct(tile_heading) FROM documents");
							while($row = mysqli_fetch_array($query)) {
								echo "<option value='". $row['tile_heading']."'>".$row['tile_heading'].'</option>';
							}
							echo "<option value = 'Other'>New Tile Heading</option>";
						  ?>
						</select>
				</div>
			  </div>

			   <div class="form-group" id="new_tile_heading" style="display: none;">
				<label for="travel_task" class="col-sm-4 control-label">
                <span class="popover-examples list-inline">&nbsp;
                <a  data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations, '&' symbols, or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                </span>
                </label>
				<div class="col-sm-8">
					<input name="new_tile_heading" type="text" class="form-control" />
				</div>
			  </div>

              <div class="form-group">
                <label for="file[]" class="col-sm-4 control-label">Upload Document(s):
                <span class="popover-examples list-inline">&nbsp;
                <a  data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                </span><br><span class="pull-right text-red"><em>(upload a maximum of 20 documents at a time)</em></span>
                </label>
                <div class="col-sm-8">
                  <input name="file[]" required multiple type="file" id="file" data-filename-placement="inside" class="form-control" />
                </div>
              </div>

              <div class="form-group triple-gap-top">
				<p><span class="text-red"><em>Required Fields *</em></span></p>
              </div>

            <div class="form-group">
                <div class="col-sm-6">
                    <a href="documents.php" class="btn brand-btn mobile-block btn-lg">Back</a>
					<!--<a href="#" class="btn brand-btn mobile-block pull-right" onclick="history.go(-1);return false;">Back</a>-->
                </div>
                <div class="col-sm-6">
                    <button type="submit" name="submit" value="Submit" class="btn brand-btn mobile-block btn-lg pull-right">Submit</button>
                </div>
				
				<div class="clearfix"></div>
            </div>

        </form>
		</div>
	</div>
</div>

<?php include ('../footer.php'); ?>