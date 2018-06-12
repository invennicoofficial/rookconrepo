<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('newsboard');
error_reporting(0);

if (isset($_POST['submit'])) {
    $newsboard = implode(',',$_POST['newsboard']);
    $newsboard_dashboard = implode(',',$_POST['newsboard_dashboard']);

    if (strpos(','.$newsboard.',',','.'News Board Type,Title'.',') === false) {
        $newsboard = 'News Board Type,Title,'.$newsboard;
    }
    if (strpos(','.$newsboard_dashboard.',',','.'News Board Type,Title'.',') === false) {
        $newsboard_dashboard = 'News Board Type,Title,'.$newsboard_dashboard;
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET newsboard = '$newsboard', newsboard_dashboard = '$newsboard_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`newsboard`, `newsboard_dashboard`) VALUES ('$newsboard', '$newsboard_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_newsboard.php"); </script>';

}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>News Board</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="newsboard.php" class="btn config-btn">Back to Dashboard</a></div>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add or remove fields. These are the fields that will appear when someone adds a News Board."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for News Board<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT newsboard FROM field_config"));
                $value_config = ','.$get_field_config['newsboard'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ',News Board Type,') !== false) { echo " checked"; } ?> value="News Board Type" name="newsboard[]">&nbsp;&nbsp;News Board Type
                        </td>
						<td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ',Title,') !== false) { echo " checked"; } ?> value="Title" name="newsboard[]">&nbsp;&nbsp;Title
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ',Uploader,') !== false) { echo " checked"; } ?> value="Uploader" name="newsboard[]">&nbsp;&nbsp;Header Image
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ',Expiry Date,') !== false) { echo " checked"; } ?> value="Expiry Date" name="newsboard[]">&nbsp;&nbsp;Expiry Date
                        </td>
						<td>
                            <input type="checkbox" <?php if (strpos($value_config, ',Description,') !== false) { echo " checked"; } ?> value="Description" name="newsboard[]">&nbsp;&nbsp;Description
                        </td>
					</tr>
                </table>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add or remove fields. These are the fields that will appear on your News Board Dashboard - on the main page of this software."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dashboard" >
                    Choose Fields for News Board Dashboard<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dashboard" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT newsboard_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['newsboard_dashboard'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ',News Board Type,') !== false) { echo " checked"; } ?> value="News Board Type" name="newsboard_dashboard[]">&nbsp;&nbsp;News Board Type
                        </td>
						<td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ',Title,') !== false) { echo " checked"; } ?> value="Title" name="newsboard_dashboard[]">&nbsp;&nbsp;Title
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ',Uploader,') !== false) { echo " checked"; } ?> value="Uploader" name="newsboard_dashboard[]">&nbsp;&nbsp;Header Image
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ',Expiry Date,') !== false) { echo " checked"; } ?> value="Expiry Date" name="newsboard_dashboard[]">&nbsp;&nbsp;Expiry Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ',Description,') !== false) { echo " checked"; } ?> value="Description" name="newsboard_dashboard[]">&nbsp;&nbsp;Description
                        </td>
					</tr>
                </table>
            </div>
        </div>
    </div>

</div>
<div class="pull-left">
    <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
    <a href="newsboard.php" class="btn brand-btn btn-lg">Back</a>
</div>
<div class="pull-right">
    <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to submit your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
    <button	type="submit" name="submit"	value="Submit" class="btn brand-btn btn-lg">Submit</button>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>