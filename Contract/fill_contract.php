<?php include_once('../include.php');
checkAuthorised('contracts');

if(isset($_POST['add_manual'])) {
	$contractid = $_POST['contractid'];
	if($contractid > 0) {
		include('user_forms.php');
	}
}
?>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form" <?= $user_form_layout == 'Sidebar' ? 'style="padding: 0; margin: 0;"' : '' ?>>
	<input type="hidden" name="contractid" value="<?= $contractid ?>">
	<input type="hidden" name="form_id" value="<?= $user_form_id ?>">

	<?php $contract = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contracts` WHERE `contractid` = '$contractid'"));
	$value_config = ','.$contract['fields'].',';
    $user_form_id = $contract['user_form_id'];
    $sub_heading = $contract['sub_heading'];
    $user_form = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM `user_forms` WHERE `form_id` = '$user_form_id'"))['name'];

    if($user_form_layout == 'Sidebar') {
        include('user_forms_sidebar.php');
    } ?>

	<div class="scale-to-fill has-main-screen" id="user_form_mainscreen" style="padding: 0; background-color: #fff;">
	    <div class="main-screen standard-body default_screen form-horizontal">
	    	<?php if($user_form_layout == 'Sidebar') { ?>
		        <div class="standard-body-title">
		            <h3><?= $sub_heading ?></h3>
		        </div>
		    <?php } ?>
	        <div class="standard-body-content double-pad-left double-pad-right pad-top">
	        	<?php if($user_form_layout != 'Sidebar') { ?>
			    	<h3><?= $sub_heading ?></h3>
			    	<div class="gap-top double-gap-bottom"><a href="?tab=<?= $_GET['tab'] ?>" class="btn config-btn">Back to Dashboard</a></div>
	        	<?php } ?>

			    <?php if(strpos($value_config, ',Business,') !== FALSE) { ?>
			    	<div class="form-group">
			    		<label class="col-sm-4 control-label">Business:</label>
			    		<div class="col-sm-8">
			    			<select name="contract_businessid" data-placeholder="Select a Business" class="chosen-select-deselect">
			    				<option></option>
			    				<?php $contract_businesses = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` = 'Business' AND `deleted` = 0 AND `status` = 1 AND `show_hide_user` = 1"),MYSQLI_ASSOC));
			    				foreach ($contract_businesses as $contract_business) { ?>
			    					<option value="<?= $contract_business ?>"><?= get_client($dbc, $contract_business) ?></option>
			    				<?php } ?>
			    			</select>
			    		</div>
			    	</div>
			    <?php } ?>

			    <?php include('../Contract/user_forms.php'); ?>

			    <div class="form-group">
			    	<div class="col-sm-4">
			    		<p><span class="hp-red pull-right"><em>Required Fields *</em></span></p>
			    	</div>
			    	<div class="col-sm-8"></div>
			    </div>

			    <div class="form-group">
			    	<div class="col-sm-4 clearfix">
			    		<a href="?tab=<?= $_GET['tab'] ?>" class="btn brand-btn pull-right">Back</a>
			    	</div>
			    	<div class="col-sm-8">
			    		<button type="submit" name="add_manual" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
			    	</div>
			    </div>
            </div>
        </div>
    </div>
</form>