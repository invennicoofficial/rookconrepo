<?php
/*
Dashboard
*/
include_once ('../include.php');
checkAuthorised('sales');

if (isset($_POST['submit'])) {
    set_config($dbc, 'sales_dashboard_users', implode(',',$_POST['dashboard_users']));

    echo '<script type="text/javascript"> window.location.replace("field_config.php?tab=dashboards"); </script>';
}
?>
<script>
$(document).ready(function(){
    $("#selectall").change(function(){
      $(".all_check").prop('checked', $(this).prop("checked"));
    });
});
</script>

<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="gap-top">
    	<?php $dashboard_users = array_filter(explode(',',get_config($dbc, 'sales_dashboard_users'))); ?>

        <div class="form-group">
    		<label class="col-sm-4">Users:<br /><em>Select the Users that should have dashboards.</em></label>
    		<div class="col-sm-8">
				<?php foreach(sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `last_name`, `first_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status`>0")) as $contact) { ?>
					<label class="form-checkbox"><input name="dashboard_users[]" <?= in_array($contact['contactid'],$dashboard_users) || empty($dashboard_users) ? 'checked' : '' ?> type="checkbox" value="<?= $contact['contactid'] ?>"><?= $contact['full_name'] ?></label>
				<?php } ?>
    		</div>
    	</div>

        <div class="pull-right gap-top gap-bottom">
            <a href="index.php" class="btn brand-btn">Back</a>
            <button	type="submit" name="submit"	value="Submit" class="btn brand-btn">Submit</button>
        </div>
    </div>
</form>