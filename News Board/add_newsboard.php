<?php
/*
Add News Board
*/
include ('../include.php');
error_reporting(0);

if(!empty($_GET['certuploadid'])) {
    $certuploadid = $_GET['certuploadid'];
    $query = mysqli_query($dbc,"DELETE FROM newsboard_uploads WHERE certuploadid='$certuploadid'");
    $newsboardid = $_GET['newsboardid'];

    echo '<script type="text/javascript"> window.location.replace("add_newsboard.php?newsboardid='.$newsboardid.'"); </script>';
}

if (isset($_POST['add_newsboard'])) {
    $contactid = $_POST['contactid'];
	if($_POST['new_newsboard'] != '') {
		$newsboard_type = filter_var($_POST['new_newsboard'],FILTER_SANITIZE_STRING);
	} else {
		$newsboard_type = filter_var($_POST['newsboard_type'],FILTER_SANITIZE_STRING);
	}

	if($_POST['new_category'] != '') {
		$category = filter_var($_POST['new_category'],FILTER_SANITIZE_STRING);
	} else {
		$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
	}
    $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
    $issue_date = date('Y-m-d');
    $expiry_date = $_POST['expiry_date'];
    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);

    if(empty($_POST['newsboardid'])) {
        $software_url       = $_SERVER['SERVER_NAME'];
        $software_author    = '';
        $software_approval  = '';
        
        if($software_url == 'sea-alberta.rookconnect.com') {
            $software_author = 'SEA Alberta';
        } else if($software_url == 'sea-vancouver.rookconnect.com') {
            $software_author = 'SEA Vancouver';
        } else if($software_url == 'sea-saskatoon.rookconnect.com') {
            $software_author = 'SEA Saskatoon';
        } else if($software_url == 'sea-regina.rookconnect.com') {
            $software_author = 'SEA Regina';
        }
        
        $query_insert = "INSERT INTO `newsboard` (`contactid`, `newsboard_type`, `category`, `description`, `title`, `issue_date`, `expiry_date`, `cross_software`, `cross_software_approval`) VALUES ('$contactid', '$newsboard_type', '$category', '$description', '$title', '$issue_date', '$expiry_date', '$software_author', '$software_approval')";

        $result_insert_vendor = mysqli_query($dbc, $query_insert) or die(mysqli_error($dbc));
        $newsboardid = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $newsboardid = $_POST['newsboardid'];
        $query_update = "UPDATE `newsboard` SET `contactid` = '$contactid', `newsboard_type` = '$newsboard_type', `category` = '$category', `description` = '$description', `title` = '$title', `expiry_date` = '$expiry_date' WHERE `newsboardid` = '$newsboardid'";
        $result_update_vendor = mysqli_query($dbc, $query_update);
        $url = 'Updated';
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

    for($i = 0; $i < count($_FILES['upload_document']['name']); $i++) {
        $document = htmlspecialchars($_FILES["upload_document"]["name"][$i], ENT_QUOTES);

        move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], "download/".$_FILES["upload_document"]["name"][$i]) ;

        if($document != '') {
            $query_insert_client_doc = "INSERT INTO `newsboard_uploads` (`newsboardid`, `type`, `document_link`) VALUES ('$newsboardid', 'Document', '$document')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

    for($i = 0; $i < count($_POST['support_link']); $i++) {
        $support_link = $_POST['support_link'][$i];

        if($support_link != '') {
            $query_insert_client_doc = "INSERT INTO `newsboard_uploads` (`newsboardid`, `type`, `document_link`) VALUES ('$newsboardid', 'Link', '$support_link')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

    echo '<script type="text/javascript"> window.location.replace("newsboard.php"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}

?>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        var newsboard_type = $("#newsboard_type").val();
        var category = $("input[name=category]").val();
        var title = $("input[name=title]").val();
        if (newsboard_type == '' || category == '' || title == '' ) {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });

    $("#newsboard_type").change(function() {
        if($("#newsboard_type option:selected").text() == 'New News Board') {
                $( "#new_newsboard" ).show();
        } else {
            $( "#new_newsboard" ).hide();
        }
    });

    $("#category").change(function() {
        if($("#category option:selected").text() == 'New Category') {
                $( "#new_category" ).show();
        } else {
            $( "#new_category" ).hide();
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

    $.datepicker.setDefaults({
        onSelect: function(value) {
            if(this.id == 'expiry_date') {
                var date = new Date(value);
                date.setDate(date.getDate() - 30);
                $("#reminder_date").datepicker("setDate", date);
            }
        }
    });


});
</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('newsboard');
?>
<div class="container">
  <div class="row">

    <h1>News Board</h1>

	<div class="gap-left gap-top double-gap-bottom"><a href="newsboard.php" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post"	action="add_newsboard.php" enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT newsboard FROM field_config"));
        $value_config = ','.$get_field_config['newsboard'].',';
        $contactid = '';
        $newsboard_type = '';
        $category = '';
        $title = '';
        $expiry_date = '';
        $description = '';

        if(!empty($_GET['newsboardid'])) {
            $newsboardid = $_GET['newsboardid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM newsboard WHERE newsboardid='$newsboardid'"));
            $contactid = $get_contact['contactid'];
            $newsboard_type = $get_contact['newsboard_type'];
            $category = $get_contact['category'];
            $title = $get_contact['title'];
            $expiry_date = $get_contact['expiry_date'];
            $description = $get_contact['description'];
        ?>
        <input type="hidden" id="newsboardid" name="newsboardid" value="<?php echo $newsboardid ?>" />
        <?php } ?>

        <?php if (strpos($value_config, ','."Staff".',') !== FALSE) { ?>
        <div class="form-group">
          <label for="site_name" class="col-sm-4 control-label">
			<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Select your name from the dropdown menu so that it shows you have posted."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			Staff:
		  </label>
          <div class="col-sm-8">
            <select data-placeholder="Select a Staff Member..." name="contactid" class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE deleted=0 AND category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND status = 1 order by first_name");

                $staff_list = sort_contacts_array(mysqli_fetch_all($query,MYSQLI_ASSOC));
                foreach($staff_list as $staffid) {
                    if ($contactid == $staffid) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $staffid."'>".get_contact($dbc, $staffid).'</option>';
                }
              ?>
            </select>
          </div>
        </div>
        <?php } ?>

        <?php if (strpos($value_config, ','."News Board Type".',') !== FALSE) { ?>
       <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label"><span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Select the type from the dropdown menu. You can create a new News Board."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>News Board Type<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
            <select id="newsboard_type" name="newsboard_type" class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
                <?php
                $query = mysqli_query($dbc,"SELECT distinct(newsboard_type) FROM newsboard order by newsboard_type");
                while($row = mysqli_fetch_array($query)) {
                    if ($newsboard_type == $row['newsboard_type']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $row['newsboard_type']."'>".$row['newsboard_type'].'</option>';

                }
                echo "<option value = 'Other'>New News Board</option>";
                ?>
            </select>
        </div>
      </div>

       <div class="form-group" id="new_newsboard" style="display: none;">
        <label for="travel_task" class="col-sm-4 control-label">
			New News Board Type:
        </label>
        <div class="col-sm-8">
            <input name="new_newsboard" type="text" class="form-control" />
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Category".',') !== FALSE) { ?>

      <!-- <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Category<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="category" value="<?php echo $category; ?>" type="text" id="name" class="form-control">
        </div>
      </div>
      -->

       <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Category<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
            <select id="category" name="category" class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
                <?php
                $query = mysqli_query($dbc,"SELECT distinct(category) FROM newsboard order by category");
                while($row = mysqli_fetch_array($query)) {
                    if ($category == $row['category']) {
                        $selected = 'selected="selected"';
                    } else {
                        $selected = '';
                    }
                    echo "<option ".$selected." value='". $row['category']."'>".$row['category'].'</option>';

                }
                echo "<option value = 'Other'>New Category</option>";
                ?>
            </select>
        </div>
      </div>

       <div class="form-group" id="new_category" style="display: none;">
        <label for="travel_task" class="col-sm-4 control-label">New Category:
        </label>
        <div class="col-sm-8">
            <input name="new_category" type="text" class="form-control" />
        </div>
      </div>

      <?php } ?>

       <?php if (strpos($value_config, ','."Title".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label"><span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="This is the title of the message that will display on the New Board dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>Title<span class="hp-red">*</span>:</label>
        <div class="col-sm-8">
          <input name="title" value="<?php echo $title; ?>" type="text" id="title" class="form-control">
        </div>
      </div>
      <?php } ?>

    <?php if (strpos($value_config, ','."Uploader".',') !== FALSE) {
    ?>

    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">
			<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			Header Image:
        </label>
        <div class="col-sm-8">
            <?php
            if(!empty($_GET['newsboardid'])) {
                $query_check_credentials = "SELECT * FROM newsboard_uploads WHERE newsboardid='$newsboardid' AND type = 'Document' ORDER BY certuploadid DESC";
                $result = mysqli_query($dbc, $query_check_credentials);
                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    while($row = mysqli_fetch_array($result)) {
                        $certuploadid = $row['certuploadid'];
                        echo '<ul>';
                        echo '<li><a href="download/'.$row['document_link'].'" target="_blank">'.$row['document_link'].'</a> - <a href="add_newsboard.php?certuploadid='.$certuploadid.'&newsboardid='.$newsboardid.'"> Delete</a></li>';
                        echo '</ul>';
                    }
                }
            }
            ?>
            <div class="enter_cost additional_doc clearfix">
                <div class="clearfix"></div>

                <div class="form-group clearfix">
                    <div class="col-sm-5">
                        <input name="upload_document[]" type="file" data-filename-placement="inside" class="form-control" />
                    </div>
                </div>

            </div>

            <div id="add_here_new_doc"></div>

            <div class="clearfix">
                <div class="col-sm-offset-4 col-sm-8">
                    <button style='display:none;' id="add_row_doc" class="btn brand-btn pull-left">Add Another Document</button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (strpos($value_config, ','."Link".',') !== FALSE) {
    ?>
    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">
			Link(s):<br><em>(e.g. - https://www.google.com)</em>
        </label>
        <div class="col-sm-8">
            <?php
            if(!empty($_GET['newsboardid'])) {
                $query_check_credentials = "SELECT * FROM newsboard_uploads WHERE newsboardid='$newsboardid' AND type = 'Link' ORDER BY certuploadid DESC";
                $result = mysqli_query($dbc, $query_check_credentials);
                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    $link_no = 1;
                    while($row = mysqli_fetch_array($result)) {
                        $certuploadid = $row['certuploadid'];
                        echo '<ul>';
                        echo '<li><a target="_blank" href=\''.$row['document_link'].'\'">Link '.$link_no.'</a> - <a href="add_newsboard.php?certuploadid='.$certuploadid.'&newsboardid='.$newsboardid.'"> Delete</a></li>';
                        echo '</ul>';
                        $link_no++;
                    }
                }
            }
            ?>
            <div class="enter_cost additional_link clearfix">
                <div class="clearfix"></div>

                <div class="form-group clearfix">
                    <div class="col-sm-5">
                        <input name="support_link[]" type="text" class="form-control">
                    </div>
                </div>

            </div>

            <div id="add_here_new_link"></div>

            <div class="form-group triple-gapped clearfix">
                <div class="col-sm-offset-4 col-sm-8">
					<span class="popover-examples list-inline pull-left" style="margin:7px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click this to add as many links as you would like."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                    <button id="add_row_link" class="btn brand-btn pull-left">Add Another Link</button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

       <?php if (strpos($value_config, ','."Expiry Date".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Expiry Date:</label>
        <div class="col-sm-8">
          <input name="expiry_date" value="<?php echo $expiry_date; ?>" id="expiry_date" type="text" class="datepicker">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label"><span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="This is where the body of your message will go."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>Description:</label>
        <div class="col-sm-8">
          <textarea name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea>
        </div>
      </div>
      <?php } ?>

        <div class="form-group">
			<p><span class="hp-red"><em>Required Fields *</em></span></p>
        </div>

        <div class="double-gap-bottom">
            <div class="pull-left">
                <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <a href="newsboard.php" class="btn brand-btn btn-lg">Back</a>
            </div>
            <div class="pull-right">
                <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to submit your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <button type="submit" name="add_newsboard" value="Submit" class="btn brand-btn btn-lg">Submit</button>
            </div>
            <div class="clearfix"></div>
        </div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
