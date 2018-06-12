<?php
/*
Edit Docs
*/
include ('../include.php');
checkAuthorised('documents');
error_reporting(0);

if (isset($_POST['submit'])) {

    $old_tile_name = $_POST['old_tile_name'];
    $old_sub_tile_name = $_POST['old_sub_tile_name'];

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

    $query_update_tile = "UPDATE `documents` SET tile_name = '$tile_name' WHERE tile_name = '$old_tile_name'";
    $result_update_tile = mysqli_query($dbc, $query_update_tile);

    $query_update_subtile = "UPDATE `documents` SET sub_tile_name = '$sub_tile_name' WHERE sub_tile_name = '$old_sub_tile_name'";
    $result_update_subtile = mysqli_query($dbc, $query_update_subtile);

    echo '<script type="text/javascript"> window.location.replace("documents.php?type=view_document&tile_name='.$tile_name.'&sub_tile_name='.$sub_tile_name.'"); </script>';
}

?>
<script type="text/javascript">
$(document).ready(function() {

    $("#form1").submit(function( event ) {
        var new_tile = $("input[name=new_tile]").val();
        var new_sub_tile = $("input[name=new_sub_tile]").val();

        var tile_name = $("#tile_name").val();
        var sub_tile_name = $("#sub_tile_name").val();

        if (tile_name == '' || sub_tile_name == '') {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
        if(((tile_name == 'Other') && (new_tile == '')) || ((sub_tile_name == 'Other') && (new_sub_tile == ''))) {
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
        <h1>Edit Tile Title</h1>
		<div class="gap-top double-gap-bottom"><a href="documents.php" class="btn config-btn">Back to Dashboard</a></div>

        <form action="edit_document_title.php" id="form1" method="post" class="form-horizontal" enctype="multipart/form-data" role="form">

                <?php
                    $tile_name = $_GET['tile_name'];
					echo '<input type="hidden" name="old_tile_name" value="'.$tile_name.'">';
                    $sub_tile_name = $_GET['sub_tile_name'];
					echo '<input type="hidden" name="old_sub_tile_name" value="'.$sub_tile_name.'">';
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

              <div class="form-group double-gap-top clearfix">
				<p><span class="text-red"><em>Required Fields *</em></span></p>
              </div>

            <div class="form-group">
                <div class="col-sm-6">
                    <!--<a href="documents.php" class="btn brand-btn mobile-block pull-right">Back</a>-->
					<a href="#" class="btn brand-btn mobile-block btn-lg" onclick="history.go(-1);return false;">Back</a>
                </div>
                <div class="col-sm-6">
                    <button type="submit" name="submit" value="Submit" class="btn brand-btn mobile-block btn-lg pull-right">Submit</button>
                </div>
            </div>

        </form>
		</div>
	</div>
</div>

<?php include ('../footer.php'); ?>