<?php
include ('../include.php');
include 'config.php';

$value = $config['settings']['Choose Fields for Activities'];

if(isset($_GET['action']) && $_GET['action'] == 'delete') {
    mysqli_query($dbc, "DELETE FROM social_story_activities WHERE activities_id=".$_GET['activities_id']);

    echo '<script type="text/javascript"> window.location.replace("'.$_GET['from_url'].'"); </script>';
}

if (isset($_POST['submit'])) {

    $inputs = get_post_inputs_social($value['data']);
    $files = get_post_uploads_social($value['data']);
    move_files_social($files);

    if(empty($_POST['activities_id'])) {
        $query_insert_vendor = prepare_insert_social($inputs, 'social_story_activities');
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $activities_id = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $activities_id = $_POST['activities_id'];
        $query_update_vendor = prepare_update_social($inputs, 'social_story_activities', 'activities_id', $activities_id);
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

    echo '<script type="text/javascript"> window.location.replace("'.$_GET['from_url'].'"); </script>';

}
?>
<script type="text/javascript">

function selectContactCategory(sel) {
    var stage = sel.value;
    var typeId = sel.id;
    var arr = typeId.split('_');
    $.ajax({
        type: "GET",
        url: "isp_ajax_all.php?fill=contact_category&category="+stage,
        dataType: "html",   //expect html to be returned
        success: function(response){
            $("#contact_"+arr[2]).html(response);
            $("#contact_"+arr[2]).trigger("change.select2");
        }
    });
}
</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('social_story');
?>
<div class="container">
  <div class="row">

    <h1>Activities</h1>
	<div class="pad-left gap-top double-gap-bottom"><a href="<?= $_GET['from_url'] ?>" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php

        $inputs = get_all_inputs_social($value['data']);

        foreach($inputs as $input) {
            $$input = '';
        }

        if(!empty($_GET['activities_id'])) {

            $activities_id = $_GET['activities_id'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM social_story_activities WHERE activities_id='$activities_id'"));

            foreach($inputs as $input) {
                if($input == 'incident_widget') {
                    $$input = unserialize($get_contact[$input]);
                } else {
                    $$input = $get_contact[$input];
                }
            }

        ?>
        <input type="hidden" id="activities_id" name="activities_id" value="<?php echo $activities_id ?>" />
        <?php   }      ?>



<div class="panel-group" id="accordion2">
<?php
$k=0;
if(isset($value['config_field'])) {
    $get_field_config = @mysqli_fetch_assoc(mysqli_query($dbc,"SELECT ".$value['config_field']." FROM field_config"));
    $value_config = ','.$get_field_config[$value['config_field']].',';
    foreach($value['data'] as $tab_name => $tabs) {
		$display = false;
		foreach($tabs as $info) {
			$display = ($display == true || strpos($value_config, ','.$info[2].',') !== FALSE);
		}
		if($display) { ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field<?php echo $k; ?>" >
							<?php echo $tab_name; ?><span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>
				<div id="collapse_field<?php echo $k; ?>" class="panel-collapse collapse">
					<div class="panel-body">
							<?php
								foreach($tabs as $field) {
									if (strpos($value_config, ','.$field[2].',') !== FALSE) {
										if($field[2] == 'support_contact') {
											echo get_field_social($field, @$$field[2], $dbc, $support_contact, $support_contact_category);
										} else {
											echo get_field_social($field, @$$field[2], $dbc, $support_contact);
										}
									}
								}
							?>
						</ul>
					</div>
				</div>
			</div>
		<?php }
		$k++;
    }
}


?>
</div>

        <div class="form-group">
			<p><span class="hp-red"><em>Required Fields *</em></span></p>
        </div>

        <div class="form-group">
            <div class="col-sm-6"><a href="<?= $_GET['from_url'] ?>" class="btn brand-btn btn-lg">Back</a></div>
			<div class="col-sm-6"><button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button></div>
			<div class="clearfix"></div>
        </div>

        

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
