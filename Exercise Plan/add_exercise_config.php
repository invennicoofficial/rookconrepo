<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('exercise_library');
error_reporting(0);

if((!empty($_GET['action'])) && ($_GET['action'] == 'delete')) {
    $exlibraryuploadid = $_GET['exlibraryuploadid'];
    $query = mysqli_query($dbc,"DELETE FROM exercise_library_upload WHERE exlibraryuploadid='$exlibraryuploadid'");

    $exerciseid = $_GET['exerciseid'];
    echo '<script type="text/javascript"> window.location.replace("add_exercise_config.php?exerciseid='.$exerciseid.'"); </script>';
}

if (isset($_POST['submit'])) {

    if($_POST['category'] == 'Other') {
        $category = filter_var($_POST['category_name'],FILTER_SANITIZE_STRING);
    } else {
        $category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
    }

	$title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
	$weblink = $_POST['weblink'];
    $type = $_POST['type'];
    if($type == 'My Library') {
        $type = $_SESSION['contactid'];
    }

	if(!file_exists('Download')) {
		mkdir('Download');
	}
	$description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
    if(empty($_POST['exerciseid'])) {
	    $query_insert_tq = "INSERT INTO `exercise_config` (`category`, `type`, `title`, `weblink`, `upload_document`, `description`) VALUES ('$category', '$type', '$title', '$weblink', '$upload_document', '$description')";
		$result_insert_tq = mysqli_query($dbc, $query_insert_tq);
        $exerciseid = mysqli_insert_id($dbc);
        $url = 'Added';
	} else {
		$exerciseid = $_POST['exerciseid'];
        if($upload_document != '') {
            $ud = '#$#'.$upload_document;
        }
		$query_update_tq = "UPDATE `exercise_config` SET `type` = '$type', `category` = '$category', `title` = '$title', `weblink` = '$weblink', `upload_document` = concat(upload_document, '$ud'), `description` = '$description' WHERE `exerciseid` = '$exerciseid'";
		$result_update_tq = mysqli_query($dbc, $query_update_tq);
        $url = 'Updated';
	}

    $document = htmlspecialchars($_FILES["document"]["name"], ENT_QUOTES);
    for($i = 0; $i < count($_FILES['document']['name']); $i++) {
        if($document[$i] != '') {
            move_uploaded_file($_FILES["document"]["tmp_name"][$i], "Download/" . $_FILES["document"]["name"][$i]) ;
            $query_insert_upload = "INSERT INTO `exercise_library_upload` (`exerciseid`, `type`, `upload`) VALUES ('$exerciseid', 'document', '$document[$i]')";
            $result_insert_upload = mysqli_query($dbc, $query_insert_upload);
        }
    }

    $link = $_POST['link'];
    for($i = 0; $i < count($_POST['link']); $i++) {
        if($link[$i] != '') {
            $query_insert_upload = "INSERT INTO `exercise_library_upload` (`exerciseid`, `type`, `upload`) VALUES ('$exerciseid', 'link', '$link[$i]')";
            $result_insert_upload = mysqli_query($dbc, $query_insert_upload);
        }
    }

    $video = htmlspecialchars($_FILES["video"]["name"], ENT_QUOTES);
    for($i = 0; $i < count($_FILES['video']['name']); $i++) {
        if($video[$i] != '') {
            move_uploaded_file($_FILES["video"]["tmp_name"][$i], "Download/" . $_FILES["video"]["name"][$i]) ;

            $query_insert_upload = "INSERT INTO `exercise_library_upload` (`exerciseid`, `type`, `upload`) VALUES ('$exerciseid', 'video', '$video[$i]')";
            $result_insert_upload = mysqli_query($dbc, $query_insert_upload);
        }
    }

	$type = ($type == 'Common' ? 'master' : 'private');
    echo '<script type="text/javascript"> window.location.replace("exercise_config.php?view='.$type.'"); </script>';
}

?>
<script type="text/javascript">
$(document).ready(function(){
    $("#form1").submit(function( event ) {
        var title = $("input[name=title]").val();
        var type = $("#type").val();
        if (title == '' || type == '') {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });
	$("#category").change(function() {
		if($( "#category option:selected" ).text() == 'Other') {
				$( "#category_name" ).show();
		} else {
			$( "#category_name" ).hide();
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
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">
        <h1 class="double-pad-bottom">Exercise Plans</h1>

        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

		<?php

        $category = '';
		$title =	'';
        $weblink = '';
        $upload_document = '';
        $description = '';
        $type = ($_GET['type'] == 'master' ? 'Common' : ($_GET['type'] == 'private' ? 'My Library' : ''));

		if(!empty($_GET['exerciseid']))	{
			$exerciseid = $_GET['exerciseid'];
			$get_treatment =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	exercise_config WHERE	exerciseid='$exerciseid'"));
            $category = $get_treatment['category'];
            $title =	$get_treatment['title'];
            $description =	$get_treatment['description'];
            $type = $get_treatment['type'];
            if($type != 'Common') {
                $type = 'My Library';
            }

		?>
		<input type="hidden" id="exerciseid"	name="exerciseid" value="<?php echo $exerciseid ?>" />
		<?php	}	   ?>

           <div class="form-group">
            <label for="travel_task" class="col-sm-4 control-label">Type<span class="empire-red">*</span>:</label>
            <div class="col-sm-8">
              <select name="type" class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
                <option value = 'Common' <?php if ($type == "Common") { echo " selected"; } ?>>Company</option>
                <option value = 'My Library' <?php if ($type == "My Library") { echo " selected"; } ?>>My Library</option>
              </select>
            </div>
          </div>

           <div class="form-group">
            <label for="travel_task" class="col-sm-4 control-label">Category:</label>
            <div class="col-sm-8">
              <select id="category" name="category" class="chosen-select-deselect form-control" width="380">
              <option value=''></option>
                  <?php
                    $result = mysqli_query($dbc, "SELECT distinct(category) FROM exercise_config");
                    while($row = mysqli_fetch_assoc($result)) {
                        if ($category == $row['category']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value = '".$row['category']."'>".$row['category']."</option>";
                    }
                  ?>
                  <option value = 'Other'>Other</option>
              </select>
            </div>
          </div>

           <div class="form-group">
            <label for="travel_task" class="col-sm-4 control-label"></label>
            <div class="col-sm-8">
                <input name="category_name" id="category_name" type="text" class="form-control" style="display: none;"/>
            </div>
          </div>

		  <div class="form-group">
			<label for="client_name" class="col-sm-4 control-label">Title<span class="empire-red">*</span>:</label>
			<div class="col-sm-8">
			  <input name="title" value="<?php echo $title; ?>" type="text" class="form-control">
			</div>
		  </div>

            <div class="form-group">
                <label for="additional_note" class="col-sm-4 control-label">Upload Document(s):
                        <span class="popover-examples list-inline">&nbsp;
                        <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                        </span>
                </label>
                <div class="col-sm-8">

                <?php
                    $get_doc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(exlibraryuploadid) AS total_id FROM exercise_library_upload WHERE type='document' AND exerciseid='$exerciseid'"));

                    if((!empty($_GET['exerciseid'])) && ($get_doc['total_id'] > 0)) {
                        $result = mysqli_query($dbc, "SELECT upload, exlibraryuploadid FROM exercise_library_upload WHERE type='document' AND exerciseid='$exerciseid'");

                        echo '<ul>';
                        $i=0;
                        while($row = mysqli_fetch_array($result)) {
                            $document = $row['upload'];
                            if($document != '') {
                                echo '<li><a href="Download/'.$document.'" target="_blank">'.$document.'</a> - <a href="add_exercise_config.php?action=delete&exlibraryuploadid='.$row['exlibraryuploadid'].'&exerciseid='.$exerciseid.'&type='.$type.'" onclick="return confirm(\'Are you sure?\')">Delete</a></li>';
                            }
                        }
                        echo '</ul>';
                    }
                ?>
                    <div class="enter_cost additional_doc clearfix">
                        <div class="clearfix"></div>

                        <div class="form-group clearfix">
                            <div class="col-sm-5">
                                <input name="document[]" multiple type="file" data-filename-placement="inside" class="form-control" />
                            </div>
                        </div>

                    </div>

                    <div id="add_here_new_doc"></div>

                    <div class="form-group triple-gapped clearfix">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button id="add_row_doc" class="btn brand-btn pull-left">Add Another Document</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="additional_note" class="col-sm-4 control-label">Add Link(s):<br><em>(Ex : https://www.google.com)</em>
                </label>
                <div class="col-sm-8">

                <?php
                    $get_doc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(exlibraryuploadid) AS total_id FROM exercise_library_upload WHERE type='link' AND exerciseid='$exerciseid'"));

                    if((!empty($_GET['exerciseid'])) && ($get_doc['total_id'] > 0)) {
                        $result = mysqli_query($dbc, "SELECT upload, exlibraryuploadid FROM exercise_library_upload WHERE type='link' AND exerciseid='$exerciseid'");

                        echo '<ul>';
                        $i=0;
                        while($row = mysqli_fetch_array($result)) {
                            $link = $row['upload'];
                            if($link != '') {
                                echo '<li><a href="'.$link.'" target="_blank">'.$link.'</a> - <a href="add_exercise_config.php?action=delete&exlibraryuploadid='.$row['exlibraryuploadid'].'&exerciseid='.$exerciseid.'&type='.$type.'" onclick="return confirm(\'Are you sure?\')">Delete</a></li>';
                            }
                        }
                        echo '</ul>';
                    }
                ?>

                    <div class="enter_cost additional_link clearfix">
                        <div class="clearfix"></div>

                        <div class="form-group clearfix">
                            <div class="col-sm-5">
                                <input name="link[]" type="text" class="form-control"/>
                            </div>
                        </div>

                    </div>

                    <div id="add_here_new_link"></div>

                    <div class="form-group triple-gapped clearfix">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button id="add_row_link" class="btn brand-btn pull-left">Add Another Link</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="additional_note" class="col-sm-4 control-label">Upload Video(s):
                        <span class="popover-examples list-inline">&nbsp;
                        <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                        </span>
                </label>
                <div class="col-sm-8">

                <?php
                    $get_doc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(exlibraryuploadid) AS total_id FROM exercise_library_upload WHERE type='video' AND exerciseid='$exerciseid'"));

                    if((!empty($_GET['exerciseid'])) && ($get_doc['total_id'] > 0)) {
                        $result = mysqli_query($dbc, "SELECT upload, exlibraryuploadid FROM exercise_library_upload WHERE type='video' AND exerciseid='$exerciseid'");

                        echo '<ul>';
                        $i=0;
                        while($row = mysqli_fetch_array($result)) {
                            $video = $row['upload'];
                            if($video != '') {
                                echo '<li><a href="Download/'.$video.'" target="_blank">'.$video.'</a> - <a href="add_exercise_config.php?action=delete&exlibraryuploadid='.$row['exlibraryuploadid'].'&exerciseid='.$exerciseid.'&type='.$type.'" onclick="return confirm(\'Are you sure?\')">Delete</a></li>';
                            }
                        }
                        echo '</ul>';
                    }
                ?>
                    <div class="enter_cost additional_videos clearfix">
                        <div class="clearfix"></div>

                        <div class="form-group clearfix">
                            <div class="col-sm-5">
                                <input name="video[]" multiple type="file" data-filename-placement="inside" class="form-control" />
                            </div>
                        </div>

                    </div>

                    <div id="add_here_new_videos"></div>

                    <div class="form-group triple-gapped clearfix">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button id="add_row_videos" class="btn brand-btn pull-left">Add Another Video</button>
                        </div>
                    </div>
                </div>
            </div>


		  <div class="form-group">
			<label for="fax_number"	class="col-sm-4	control-label">Description:</label>
			<div class="col-sm-8">
				<textarea name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea>
			</div>
		  </div>

			<div class="form-group">
				<div class="col-sm-4 clearfix">
					<a href="exercise_config.php?view=master" class="btn brand-btn pull-right">Back</a>
				</div>
				<div class="col-sm-8">
					<button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
				</div>
			</div>

        

    </form>


	</div>
</div>

<?php include ('../footer.php'); ?>