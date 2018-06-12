<div class="tile-sidebar sidebar sidebar-override hide-titles-mob standard-collapsible">
	<ul>
		<!--<li class="standard-sidebar-searchbox"><input class="form-control search_list" placeholder="Search Rate Cards" type="text"></li>-->
		<?php if(in_array('customer',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'customer')) {
			foreach(explode(',',get_config($dbc, 'customer_rate_card_contact_categories')) as $cat_name) { ?>
				<a href="?type=customer&card=customer&status=active&category=<?= config_safe_str($cat_name) ?>"><li class="<?= $_GET['type'] == 'customer' && $_GET['category'] == config_safe_str($cat_name) ? 'active blue' : '' ?>"><?= empty($cat_name) ? 'Customer' : $cat_name ?> Specific</li></a>
			<?php }
		} ?>
		<?php if(in_array('company',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'company')) { ?><a href="?type=company"><li class="<?= $_GET['type'] == 'company' ? 'active blue' : '' ?>">My Company</li></a><?php } ?>
		<?php if(in_array('universal',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'universal')) { ?><a href="?type=universal"><li class="<?= $_GET['type'] == 'universal' ? 'active blue' : '' ?>">Universal</li></a><?php } ?>
		<?php if(in_array('position',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'position')) { ?><a href="?type=position"><li class="<?= $_GET['type'] == 'position' ? 'active blue' : '' ?>">Position</li></a><?php } ?>
		<?php if(in_array('staff',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'staff')) { ?><a href="?type=staff"><li class="<?= $_GET['type'] == 'staff' ? 'active blue' : '' ?>">Staff</li></a><?php } ?>
		<?php if(in_array('equipment',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'equipment')) { ?>
			<li class="sidebar-higher-level highest-level"><a  class="cursor-hand <?= $_GET['type'] == 'equipment' ? 'active blue' : 'collapsed' ?>" data-toggle="collapse" data-target="#equipment_cats">Equipment<span class="arrow"></span></a>
				<ul id="equipment_cats" class="collapse <?= $_GET['type'] == 'equipment' ? 'in' : '' ?>">
					<?php $equip_types = mysqli_query($dbc, "SELECT `category` FROM `equipment` WHERE `deleted` = 0 GROUP BY `category` ORDER BY `category`");
					if($equip_types->num_rows > 0) {
						while($scat = $equip_types->fetch_assoc()) {
							$_GET['t'] = empty($_GET['t']) ? $scat['category'] : $_GET['t']; ?>
							<a href='?type=equipment&status=current&t=<?php echo $scat['category'];?>'><li class="<?= ($_GET['t'] == $scat['category'] ? ' active blue' : '') ?>"><?= $scat['category'] ?></li></a>
						<?php }
					} else { ?>
						<li>No Equipment Categories Found</li>
					<?php } ?>
				</ul>
			</li>
		<?php } ?>
		<?php if(in_array('category',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'category')) { ?><a href="?type=category"><li class="<?= $_GET['type'] == 'category' ? 'active blue' : '' ?>">Equipment by Category</li></a><?php } ?>
		<?php if(in_array('services',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'services')) { ?>
			<li class="sidebar-higher-level highest-level"><a  class="cursor-hand <?= $_GET['type'] == 'services' ? 'active blue' : 'collapsed' ?>" data-toggle="collapse" data-target="#services_types">Services<span class="arrow"></span></a>
				<ul id="services_types" class="collapse <?= $_GET['type'] == 'services' ? 'in' : '' ?>">
					<li class="sidebar-higher-level highest-level"><a  class="cursor-hand <?= $_GET['type'] == 'services' && $_GET['category'] == 'active' ? 'active blue' : 'collapsed' ?>" data-toggle="collapse" data-target="#active_services">Active<span class="arrow"></span></a>
						<ul id="active_services" class="collapse <?= $_GET['type'] == 'services' && $_GET['category'] == 'active' ? 'in' : '' ?>">
							<?php $services_types = mysqli_query($dbc, "SELECT `category` FROM `services` WHERE `category`!='' AND `deleted`=0 GROUP BY `category` ORDER BY `category`");
							if($services_types->num_rows > 0) {
								while($scat = $services_types->fetch_assoc()) {
									$_GET['t'] = empty($_GET['t']) ? $scat['category'] : $_GET['t']; ?>
									<a href='?type=services&status=current&category=active&t=<?php echo $scat['category'];?>'><li class="<?= ($_GET['t'] == $scat['category'] ? ' active blue' : '') ?>"><?= $scat['category'] ?></li></a>
								<?php }
							} else { ?>
								<li>No Service Categories Found</li>
							<?php } ?>
						</ul>
					</li>
					<li class="sidebar-higher-level highest-level"><a  class="cursor-hand <?= $_GET['type'] == 'services' && $_GET['category'] == 'inactive' ? 'active blue' : 'collapsed' ?>" data-toggle="collapse" data-target="#inactive_services">Inactive / Expired<span class="arrow"></span></a>
						<ul id="inactive_services" class="collapse <?= $_GET['type'] == 'services' && $_GET['category'] == 'inactive' ? 'in' : '' ?>">
							<?php $services_types = mysqli_query($dbc, "SELECT `category` FROM `services` WHERE `category`!='' AND `deleted`=0 GROUP BY `category` ORDER BY `category`");
							if($services_types->num_rows > 0) {
								while($scat = $services_types->fetch_assoc()) {
									$_GET['t'] = empty($_GET['t']) ? $scat['category'] : $_GET['t']; ?>
									<a href='?type=services&status=current&category=inactive&t=<?php echo $scat['category'];?>'><li class="<?= ($_GET['t'] == $scat['category'] ? ' active blue' : '') ?>"><?= $scat['category'] ?></li></a>
								<?php }
							} else { ?>
								<li>No Service Categories Found</li>
							<?php } ?>
						</ul>
					</li>
				</ul>
			</li>
		<?php } ?>
		<?php if(in_array('labour',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'labour')) { ?>
			<li class="sidebar-higher-level highest-level"><a  class="cursor-hand <?= $_GET['type'] == 'labour' ? 'active blue' : 'collapsed' ?>" data-toggle="collapse" data-target="#labour_types">Labour<span class="arrow"></span></a>
				<ul id="labour_types" class="collapse <?= $_GET['type'] == 'labour' ? 'in' : '' ?>">
					<li class="sidebar-higher-level highest-level"><a  class="cursor-hand <?= $_GET['type'] == 'labour' && $_GET['category'] == 'active' ? 'active blue' : 'collapsed' ?>" data-toggle="collapse" data-target="#active_labour">Active<span class="arrow"></span></a>
						<ul id="active_labour" class="collapse <?= $_GET['type'] == 'labour' && $_GET['category'] == 'active' ? 'in' : '' ?>">
							<?php $labour_types = mysqli_query($dbc, "SELECT `labour_type` FROM `labour` WHERE `deleted` = 0 GROUP BY `labour_type` ORDER BY `labour_type`");
							if($labour_types->num_rows > 0) {
								while($scat = $labour_types->fetch_assoc()) {
									$_GET['t'] = empty($_GET['t']) ? $scat['labour_type'] : $_GET['t']; ?>
									<a href='?type=labour&status=current&category=active&t=<?php echo $scat['labour_type'];?>'><li class="<?= ($_GET['t'] == $scat['labour_type'] ? ' active blue' : '') ?>"><?= $scat['labour_type'] ?></li></a>
								<?php }
							} else { ?>
								<li>No Labour Types Found</li>
							<?php } ?>
						</ul>
					</li>
					<li class="sidebar-higher-level highest-level"><a  class="cursor-hand <?= $_GET['type'] == 'labour' && $_GET['category'] == 'inactive' ? 'active blue' : 'collapsed' ?>" data-toggle="collapse" data-target="#inactive_labour">Inactive / Expired<span class="arrow"></span></a>
						<ul id="inactive_labour" class="collapse <?= $_GET['type'] == 'labour' && $_GET['category'] == 'inactive' ? 'in' : '' ?>">
							<?php $labour_types = mysqli_query($dbc, "SELECT `labour_type` FROM `labour` WHERE `deleted` = 0 GROUP BY `labour_type` ORDER BY `labour_type`");
							if($labour_types->num_rows > 0) {
								while($scat = $labour_types->fetch_assoc()) {
									$_GET['t'] = empty($_GET['t']) ? $scat['labour_type'] : $_GET['t']; ?>
									<a href='?type=labour&status=current&category=inactive&t=<?php echo $scat['labour_type'];?>'><li class="<?= ($_GET['t'] == $scat['labour_type'] ? ' active blue' : '') ?>"><?= $scat['labour_type'] ?></li></a>
								<?php }
							} else { ?>
								<li>No Labour Types Found</li>
							<?php } ?>
						</ul>
					</li>
				</ul>
			</li>
		<?php } ?>

		<?php if(in_array('holiday',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'holiday')) { ?><a href="?type=holiday&card=holiday&status=active"><li class="<?= $_GET['type'] == 'holiday' ? 'active blue' : '' ?>">Holiday Pay</li></a><?php } ?>

		<?php if(in_array('estimate',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'estimate')) { ?>
			<li class="sidebar-higher-level highest-level"><a  class="cursor-hand <?= $_GET['type'] == 'estimate' ? 'active blue' : 'collapsed' ?>" data-toggle="collapse" data-target="#scope_rates">Scope Templates<span class="arrow"></span></a>
				<ul id="scope_rates" class="collapse <?= $_GET['type'] == 'estimate' ? 'in' : '' ?>">
					<?php $templates = mysqli_query($dbc, "SELECT `id`, `template_name` FROM `estimate_templates` WHERE `deleted`=0 ORDER BY `template_name`");
					if($templates->num_rows > 0) {
						while($template = mysqli_fetch_array($templates)) { ?>
							<li><a class="cursor-hand <?= $_GET['template'] == $template['id'] ? 'active blue' : 'collapsed' ?>" data-toggle="collapse" data-target="#scope_rate_<?= $template['id'] ?>"><?= $template['template_name'] ?><span class="arrow"></span></a>
								<ul id="scope_rate_<?= $template['id'] ?>" class="collapse <?= $_GET['template'] == $template['id'] ? 'in' : '' ?>">
									<?php $rate_cards = mysqli_query($dbc, "SELECT * FROM `rate_card_estimate_scopes` WHERE `deleted`=0 AND `template_id`='{$template['id']}'");
									while($rate = mysqli_fetch_array($rate_cards)) { ?>
										<a href="?type=estimate&template=<?= $template['id'] ?>&rate=<?= $rate['id'] ?>"><li class="<?= $rate['id'] == $_GET['rate'] ? 'active blue' : '' ?>"><?= $rate['rate_card_name'] ?></li></a>
									<?php } ?>
									<a href="?type=estimate&template=<?= $template['id'] ?>&id=new"><li>New Rate Card</li></a>
								</ul>
							</li>
						<?php }
					} else { ?>
						<li>No Templates Found</li>
					<?php } ?>
				</ul>
			</li>
		<?php } ?>
	</ul>
</div>