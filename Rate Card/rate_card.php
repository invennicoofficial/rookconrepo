<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('rate_card');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);

// Rate Card Page Config
$tab_result = get_config($dbc, 'rate_card_tabs');
$tab_list = explode(',',trim($tab_result,','));

$tab_status = [];
$tab_status['card'] = (empty($_GET['card']) ? ($tab_list[0] == 'universal' ? $tab_list[1] : $tab_list[0]) : $_GET['card']);

$back_url = WEBSITE_URL.'/home.php';
if(!in_array($tab_status['card'], $tab_list)) {
	$tab_status['card'] = $tab_list[0];
}
$default = ($tab_status['card'] == 'customer' ? 'active' : 'current');
switch($tab_status['card']) {
	case 'universal': $tab_status['title'] = 'Universal'; break;
	case 'customer': $tab_status['title'] = 'Customer'; break;
	case 'position': $tab_status['title'] = 'Position'; break;
	case 'staff': $tab_status['title'] = 'Staff'; break;
	case 'equipment': $tab_status['title'] = 'Equipment'; break;
	case 'category': $tab_status['title'] = 'Equipment Category'; break;
	case 'services': $tab_status['title'] = 'Services'; break;
	case 'labour': $tab_status['title'] = 'Labour'; break;
	default: $tab_status['title'] = 'Company'; break;
}
switch(isset($_GET['status']) ? $_GET['status'] : $default) {
	case 'active':
		$tab_status['status'] = 'active';
		$tab_status['title'] = 'Active '.$tab_status['title'].' Rate Cards';
		break;
	case 'current':
		$tab_status['status'] = 'current';
		$tab_status['title'] = ($default == 'current' ? '' : 'Current ').$tab_status['title'].' Rate Cards';
		break;
	case 'add':
		$tab_status['status'] = 'add';
		$tab_status['title'] = (isset($_GET['id']) ? 'Edit a ' : 'Add a ').$tab_status['title'].' Rate Card';
		$back_url = WEBSITE_URL.'/Rate Card/rate_card.php?card='.$tab_status['card'];
		break;
	case 'history':
		$tab_status['status'] = 'history'; $tab_status['title'] = $tab_status['title'].' Rate Card History';
		break;
	case 'show':
		$tab_status['status'] = 'show'; $tab_status['title'] = 'View '.$tab_status['title'].' Rate Card';
		$back_url = WEBSITE_URL.'/Rate Card/rate_card.php?card='.$tab_status['card'];
		break;
	case 'import':
		$tab_status['status'] = 'import'; $tab_status['title'] = 'Import '.$tab_status['title'].' Rate Card';
		$_GET['category'] = empty($_GET['category']) ? 'import' : $_GET['category'];
		$back_url = WEBSITE_URL.'/Rate Card/rate_card.php?card='.$tab_status['card'];
		break;
	default:
		$tab_status['status'] = $default;
		$tab_status['title'] = ($default == 'active' ? 'Active ' : '').$tab_status['title'].' Rate Cards';
		break;
}

if(!isset($_GET['action'])) :
?>
<script type="text/javascript">
</script>
<style type='text/css'>
.control-label {
	padding-top: 7px;
}
</style>

</head>
<body>
<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">
		<h1><?php echo $tab_status['title']; ?>

		<?php if($tab_status['status'] != 'history'): ?>
			<?php if(config_visible_function($dbc, 'rate_card') == 1): ?>
				<a href="field_config_rate_card.php?tab=<?php echo $tab_status['card']; ?>" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>
			<?php endif; ?></h1>
            <div class="notice double-gap-bottom popover-examples">
                <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                The Rate Card tile is where you assign price points of all services being offered by your business. In the Active Rate Card section you can review all current rates being offered by your business.
                Click Edit to add end dates for current rates, and click Add Rate Card to add new price points for services. In this section you can add multiple rates for each service by selected start and end dates, ensuring of course that prices don't overlap. You must assign an effective start date for a rate in order to have an active rate for a service.<br>
                In this section you can review all Inactive and Expired rates that your company has offered. Once a rate for a service expires, it's logged here for your records and ongoing review.</div>
                <div class="clearfix"></div>
            </div>
			<?php if(count($tab_list) > 1): ?>
				<p>
					<?php if(in_array('universal', $tab_list)): ?>
						<?php if ( check_subtab_persmission($dbc, 'rate_card', ROLE, 'universal') === TRUE ) { ?>
							<a href='rate_card.php?card=universal'><button type="button" class="btn brand-btn mobile-block<?php echo ($tab_status['card'] == 'universal' ? ' active_tab' : ''); ?>" >Universal Rates</button></a><?php
						} else { ?>
							<a href='rate_card.php?card=universal'><button type="button" class="btn disabled-btn mobile-block<?php echo ($tab_status['card'] == 'universal' ? ' active_tab' : ''); ?>" >Universal Rates</button></a><?php
						} ?>
					<?php endif; ?>

					<?php if(in_array('company', $tab_list)): ?>
						<?php if ( check_subtab_persmission($dbc, 'rate_card', ROLE, 'company') === TRUE ) { ?>
							<a href='rate_card.php?card=company'><button type="button" class="btn brand-btn mobile-block<?php echo ($tab_status['card'] == 'company' ? ' active_tab' : ''); ?>" >My Company</button></a><?php
						} else { ?>
							<a href='rate_card.php?card=company'><button type="button" class="btn disabled-btn mobile-block<?php echo ($tab_status['card'] == 'company' ? ' active_tab' : ''); ?>" >My Company</button></a><?php
						} ?>
					<?php endif; ?>

					<?php if(in_array('customer', $tab_list)): ?>
						<?php if ( check_subtab_persmission($dbc, 'rate_card', ROLE, 'customer') === TRUE ) { ?>
							<a href='rate_card.php?card=customer'><button type="button" class="btn brand-btn mobile-block<?php echo ($tab_status['card'] == 'customer' ? ' active_tab' : ''); ?>">Customer Specific</button></a><?php
						} else { ?>
							<a href='rate_card.php?card=customer'><button type="button" class="btn disabled-btn mobile-block<?php echo ($tab_status['card'] == 'customer' ? ' active_tab' : ''); ?>">Customer Specific</button></a><?php
						} ?>
					<?php endif; ?>

					<?php if(tile_enabled($dbc, 'estimate')): ?>
						<?php if ( check_subtab_persmission($dbc, 'rate_card', ROLE, 'estimate') === TRUE ) { ?>
							<a href='ratecards.php?type=estimate'><button type="button" class="btn brand-btn mobile-block" >Scope Templates</button></a><?php
						} else { ?>
							<button type="button" class="btn disabled-btn mobile-block" >Scope Templates</button><?php
						} ?>
					<?php endif; ?>

					<?php if(in_array('position', $tab_list)): ?>
						<?php if ( check_subtab_persmission($dbc, 'rate_card', ROLE, 'position') === TRUE ) { ?>
							<a href='rate_card.php?card=position'><button type="button" class="btn brand-btn mobile-block<?php echo ($tab_status['card'] == 'position' ? ' active_tab' : ''); ?>" >Position</button></a><?php
						} else { ?>
							<a href='rate_card.php?card=position'><button type="button" class="btn disabled-btn mobile-block<?php echo ($tab_status['card'] == 'position' ? ' active_tab' : ''); ?>" >Position</button></a><?php
						} ?>
					<?php endif; ?>

					<?php if(in_array('staff', $tab_list)): ?>
						<?php if ( check_subtab_persmission($dbc, 'rate_card', ROLE, 'staff') === TRUE ) { ?>
							<a href='rate_card.php?card=staff'><button type="button" class="btn brand-btn mobile-block<?php echo ($tab_status['card'] == 'staff' ? ' active_tab' : ''); ?>" >Staff</button></a><?php
						} else { ?>
							<a href='rate_card.php?card=staff'><button type="button" class="btn disabled-btn mobile-block<?php echo ($tab_status['card'] == 'staff' ? ' active_tab' : ''); ?>" >Staff</button></a><?php
						} ?>
					<?php endif; ?>

					<?php if(in_array('equipment', $tab_list)): ?>
						<?php if ( check_subtab_persmission($dbc, 'rate_card', ROLE, 'equipment') === TRUE ) { ?>
							<a href='rate_card.php?card=equipment'><button type="button" class="btn brand-btn mobile-block<?php echo ($tab_status['card'] == 'equipment' ? ' active_tab' : ''); ?>" >Equipment</button></a><?php
						} else { ?>
							<a href='rate_card.php?card=equipment'><button type="button" class="btn disabled-btn mobile-block<?php echo ($tab_status['card'] == 'equipment' ? ' active_tab' : ''); ?>" >Equipment</button></a><?php
						} ?>
					<?php endif; ?>

					<?php if(in_array('category', $tab_list)): ?>
						<?php if ( check_subtab_persmission($dbc, 'rate_card', ROLE, 'category') === TRUE ) { ?>
							<a href='rate_card.php?card=category'><button type="button" class="btn brand-btn mobile-block<?php echo ($tab_status['card'] == 'category' ? ' active_tab' : ''); ?>" >Equipment Category</button></a><?php
						} else { ?>
							<a href='rate_card.php?card=category'><button type="button" class="btn disabled-btn mobile-block<?php echo ($tab_status['card'] == 'category' ? ' active_tab' : ''); ?>" >Equipment Category</button></a><?php
						} ?>
					<?php endif; ?>

					<?php if(in_array('services', $tab_list)): ?>
						<?php if ( check_subtab_persmission($dbc, 'rate_card', ROLE, 'services') === TRUE ) { ?>
							<a href='rate_card.php?card=services'><button type="button" class="btn brand-btn mobile-block<?php echo ($tab_status['card'] == 'services' ? ' active_tab' : ''); ?>" >Services</button></a><?php
						} else { ?>
							<a href='rate_card.php?card=services'><button type="button" class="btn disabled-btn mobile-block<?php echo ($tab_status['card'] == 'services' ? ' active_tab' : ''); ?>" >Services</button></a><?php
						} ?>
					<?php endif; ?>

					<?php if(in_array('labour', $tab_list)): ?>
						<?php if ( check_subtab_persmission($dbc, 'rate_card', ROLE, 'labour') === TRUE ) { ?>
							<a href='rate_card.php?card=labour'><button type="button" class="btn brand-btn mobile-block<?php echo ($tab_status['card'] == 'labour' ? ' active_tab' : ''); ?>" >Labour</button></a><?php
						} else { ?>
							<a href='rate_card.php?card=labour'><button type="button" class="btn disabled-btn mobile-block<?php echo ($tab_status['card'] == 'labour' ? ' active_tab' : ''); ?>" >Labour</button></a><?php
						} ?>
					<?php endif; ?>
				</p>
			<?php endif; ?>
			<a href="rate_card.php?card=<?php echo ($tab_status['card'] == 'universal' ? 'company' : $tab_status['card']); ?>&status=import" class="<?= $_GET['status'] == 'import' ? 'active_tab' : '' ?> btn brand-btn pull-right">Import Rates</a>

			<p>
				<?php if($tab_status['card'] == 'customer'): ?>
					<?php $contact_category = '';
					foreach(explode(',',get_config($dbc, 'customer_rate_card_contact_categories')) as $rate_contact_category) {
						$category_str = config_safe_str($rate_contact_category);
						if(empty($_GET['category'])) {
							$_GET['category'] = $category_str;
						}
						if($_GET['category'] == $category_str) {
							$contact_category = $rate_contact_category;
						} ?>
						<a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=active&category=<?= $category_str ?>'><button type="button" class="btn brand-btn mobile-block<?php echo ($tab_status['status'] == 'active' && $_GET['category'] == $category_str ? ' active_tab' : ''); ?>" ><?= $rate_contact_category == '' ? 'Active' : $rate_contact_category ?> Rate Cards</button></a>
					<?php } ?>
				<?php endif; ?>
				<?php if($tab_status['card'] != 'universal' && $tab_status['card'] != 'company' && $tab_status['card'] != 'services' && $tab_status['card'] != 'labour' && $tab_status['card'] != 'equipment'): ?>
					<a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=current'><button type="button" class="btn brand-btn mobile-block<?php echo ($tab_status['status'] == 'current' ? ' active_tab' : ''); ?>" ><?php echo ($tab_status['card'] == 'customer') ? 'Current ' : ''; ?>Rate Card Status</button></a>
				<?php elseif($tab_status['card'] == 'company'): ?>
					<?php if(strpos(get_config($dbc, 'company_db_rate_fields'),',category,')!==false): ?>
						<a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=current&category='><button type="button" class="btn brand-btn mobile-block<?php echo ($_GET['category'] == '' ? ' active_tab' : ''); ?>" >All Rate Cards</button></a>
						<?php $category_list = mysqli_query($dbc, "SELECT DISTINCT `rate_categories` FROM `company_rate_card` WHERE `rate_categories` != '' ORDER BY `rate_categories`");
						while($category = mysqli_fetch_array($category_list)['rate_categories']) { ?>
							<a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=current&category=<?php echo $category; ?>'><button type="button" class="btn brand-btn mobile-block<?php echo ($_GET['category'] == $category ? ' active_tab' : ''); ?>" ><?php echo $category; ?> Rate Cards</button></a>
						<?php }
					endif; ?>
				<?php elseif($tab_status['card'] == 'services'):
					if(empty($_GET['category'])) {
						$_GET['category'] = 'active';
					} ?>
						<a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=current&category=active'><button type="button" class="btn brand-btn mobile-block<?php echo ($_GET['category'] == 'active' ? ' active_tab' : ''); ?>" >Active</button></a>

						<a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=current&category=inactive'><button type="button" class="btn brand-btn mobile-block<?php echo ($_GET['category'] == 'inactive' ? ' active_tab' : ''); ?>" >Inactive/Expired</button></a>

                        <br><br>

						<?php
                        if(!empty($_GET['category'])) {
                            $category_list = mysqli_query($dbc, "SELECT `category` FROM `services` WHERE `category`!='' AND `deleted`=0 GROUP BY `category` ORDER BY `category`");
                            while($scat = mysqli_fetch_array($category_list)) {
								$_GET['t'] = empty($_GET['t']) ? $scat['category'] : $_GET['t']; ?>
                                <a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=current&category=<?php echo $_GET['category']; ?>&t=<?php echo $scat['category'];?>'><button type="button" class="btn brand-btn mobile-block<?php echo ($_GET['t'] == $scat['category'] ? ' active_tab' : ''); ?>" ><?php echo $scat['category']; ?></button></a>
                            <?php }
                        } ?>
				<?php elseif($tab_status['card'] == 'labour'):
					if(empty($_GET['category'])) {
						$_GET['category'] = 'active';
					} ?>
						<a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=current&category=active'><button type="button" class="btn brand-btn mobile-block<?php echo ($_GET['category'] == 'active' ? ' active_tab' : ''); ?>" >Active</button></a>

						<a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=current&category=inactive'><button type="button" class="btn brand-btn mobile-block<?php echo ($_GET['category'] == 'inactive' ? ' active_tab' : ''); ?>" >Inactive/Expired</button></a>

                        <br><br>

						<?php $each_tab = mysqli_query($dbc, "SELECT `labour_type` FROM `labour` WHERE `deleted` = 0 GROUP BY `labour_type` ORDER BY `labour_type`");
						while($scat = $each_tab->fetch_assoc()) {
							$_GET['t'] = empty($_GET['t']) ? $scat['labour_type'] : $_GET['t']; ?>
                            <a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=current&category=<?php echo $_GET['category']; ?>&t=<?php echo $scat['labour_type'];?>'><button type="button" class="btn brand-btn mobile-block<?php echo ($_GET['t'] == $scat['labour_type'] ? ' active_tab' : ''); ?>" ><?php echo $scat['labour_type']; ?></button></a>
                        <?php } ?>
				<?php elseif($tab_status['card'] == 'equipment'): ?>
					<?php $each_tab = mysqli_query($dbc, "SELECT `category` FROM `equipment` WHERE `deleted` = 0 GROUP BY `category` ORDER BY `category`");
					while($scat = $each_tab->fetch_assoc()) {
						$_GET['t'] = empty($_GET['t']) ? $scat['category'] : $_GET['t']; ?>
						<a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=current&category=<?php echo $_GET['category']; ?>&t=<?php echo $scat['category'];?>'><button type="button" class="btn brand-btn mobile-block<?php echo ($_GET['t'] == $scat['category'] ? ' active_tab' : ''); ?>" ><?php echo $scat['category']; ?></button></a>
					<?php } ?>
				<?php endif; ?>
			</p>
			<p>
				<?php if($tab_status['card'] != 'universal' && vuaed_visible_function($dbc, 'rate_card') == 1): ?>
					<a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=add' class="btn brand-btn pull-right<?php echo (($tab_status['status'] == 'add' || $tab_status['card'] == 'universal') ? ' active_tab' : ''); ?>"><?php echo (isset($_GET['id']) ? 'Edit' : 'Add'); ?> Rate Card</a>
				<?php endif; ?>
				<!-- <a href="<?php //echo $back_url; ?>" class="btn brand-btn pull-left">Back</a> -->
			</p>
			<div class="clearfix"></div>
		<?php else:
			echo "</h1>";
		endif;
	if($tab_status['status'] == 'add'): ?>
		<a href="rate_card.php?card=<?= ($tab_status['card'] == 'universal' ? 'company' : $tab_status['card']).($tab_status['card'] == 'services' || $tab_status['card'] == 'labour' ? '&t='.$_GET['t'] : '') ?>" class="btn brand-btn">Back to Dashboard</a>
	<?php endif;
endif;

if($tab_status['card'] == 'universal') {
	if(!isset($_GET['id'])) {
		$_GET['id'] = mysqli_fetch_array(mysqli_query($dbc,"SELECT `companyrcid` FROM `company_rate_card` WHERE `rate_card_name`='' AND IFNULL(`rate_categories`,'')='".$_GET['category']."'"))['companyrcid'];
	}
	if($id = mysqli_fetch_array(mysqli_query($dbc,"SELECT `companyrcid` FROM `company_rate_card` WHERE `rate_card_name`='' AND IFNULL(`rate_categories`,'')='".$_GET['category']."'"))['companyrcid']) {
		$_GET['id'] = $id;
	}
	include_once('company_add_rate_card.php');
} else if($_GET['status'] == 'import') {
	include_once('import_rates.php');
} else {
	include_once($tab_status['card'].'_'.$tab_status['status'].'_rate_card.php');
}

if(!isset($_GET['action'])) : ?>
		<?php if($tab_status['status'] != 'history'): ?>
			<!-- <a href="<?php //echo $back_url; ?>" class="btn brand-btn pull-left">Back</a> -->
		<?php endif; ?>
		<p>
			<?php if($tab_status['card'] != 'universal' && $tab_status['card'] != 'labour' && vuaed_visible_function($dbc, 'rate_card') == 1 && $tab_status['status'] != 'add'): ?>
				<a href='rate_card.php?card=<?php echo $tab_status['card']; ?>&status=add' class="btn brand-btn pull-right<?php echo (($tab_status['status'] == 'add' || $tab_status['card'] == 'universal') ? ' active_tab' : ''); ?>"><?php echo (isset($_GET['id']) ? 'Edit' : 'Add'); ?> Rate Card</a>
			<?php endif; ?>
			<!-- <a href="<?php //echo $back_url; ?>" class="btn brand-btn pull-left">Back</a> -->
		</p>
	</div>
</div>
<?php include ('../footer.php');
endif; ?>