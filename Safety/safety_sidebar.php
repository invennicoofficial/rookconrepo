<div class="tile-sidebar sidebar standard-collapsible hide-on-mobile">
	<ul class='hide-titles-mob'>
		<?php foreach($categories as $cat_id => $label) {
			if('driving_log' == $cat_id) {
				$logs = mysqli_query($dbc, "SELECT `log_id` FROM `site_work_driving_log` WHERE `staff`='".$_SESSION['contactid']."' AND IFNULL(`end_drive_time`,'')=''");
				if($logs->num_rows > 0) {
					$label = 'End Driving Log';
					$tab_id = '&log_id='.$logs->fetch_assoc()['log_id'];
				}
			}
			else if($tab == $cat_id) {
				$tab_cat = $label;
			}
			$cat_name = ($cat_id == 'manuals' ? 'manual' : $cat_id);
			$tab_cat_list = $dbc->query("SELECT `category` FROM `safety` WHERE `deleted`=0 AND `tab`='$cat_name' AND IFNULL(`category`,'') != '' GROUP BY `category` ORDER BY `category`");
			if(check_subtab_persmission($dbc, 'safety', ROLE, $cat_id) && $cat_id != '') {
				$form_count = $dbc->query("SELECT `safetyid` FROM `safety` WHERE `tab`='$label' AND `deleted`=0");
				if($tab_cat_list->num_rows > 0) {
					echo '<li class="sidebar-higher-level"><b><a class="cursor-hand '.($cat_id == $tab ? '' : 'collapsed').' '.($cat_id == $tab ? 'active blue' : '').'" data-toggle="collapse" data-target="#'.config_safe_str($cat_id).'_tabs">'.$label.'<span class="arrow"></span></a></b>
						<ul id="'.config_safe_str($cat_id).'_tabs" class="collapse '.($cat_id == $tab ? 'in' : '').'">';
							while($tab_row = $tab_cat_list->fetch_assoc()) { ?>
								<a href="?tab=<?= $cat_id ?>&cat_name=<?= $tab_row['category'] ?>"><li class="<?= $cat_id == $tab && $tab_cat_name == $tab_row['category'] ? 'active blue' : '' ?>"><?= $tab_row['category'] ?></li></a>
							<?php }
					echo '</ul></li>';
				} else if(in_array($label,$bypass_cat) && $form_count->num_rows == 1) {
					$form_count = $form_count->fetch_assoc(); ?>
					<a href="?safetyid=<?= $form_count['safetyid'] ?>&action=view"><li class="<?= $_GET['safety'] == $form_count['safetyid'] && $site == '' ? 'active blue' : '' ?>"><?= $label ?></li></a>
				<?php } else { ?>
					<a href="?tab=<?= $cat_id.$tab_id ?>"><li class="<?= $cat_id == $tab && $site == '' ? 'active blue' : '' ?>"><?= $label ?></li></a>
				<?php }
			}
		}
		foreach($site_list as $siteid => $site_name) {
			if(!empty($site_name)) {
				echo '<li class="sidebar-higher-level"><b><a class="cursor-hand '.($site_name == $site ? '' : 'collapsed').' '.($site_name == $site ? 'active blue' : '').'" data-toggle="collapse" data-target="#'.config_safe_str($site_name).'_tabs">'.$site_name.'<span class="arrow"></span></a></b>
					<ul id="'.config_safe_str($site_name).'_tabs" class="collapse '.($site_name == $site ? 'in' : '').'">';
					$site_tabs = $dbc->query("SELECT `tab`, MAX(`safetyid`) `id`, COUNT(*) `count` FROM `safety` WHERE (`assign_sites` IN ('',',,',',',',ALL,') OR CONCAT('%,',`assign_sites`,',%') LIKE ',$site_name,') AND `deleted`=0 GROUP BY `tab` ORDER BY `tab`");
					if($site_tabs->num_rows > 0) {
						while($site_tab = $site_tabs->fetch_assoc()) {
							if(check_subtab_persmission($dbc, 'safety', ROLE, $cat_id) && $cat_id != '') {
								if(in_array($site_tab['tab'],$bypass_cat) && $site_tab['count'] == 1 && $site_tab['id'] > 0) { ?>
									<a href="?safetyid=<?= $site_tab['id'] ?>&action=view&site=<?= $site_name ?>&siteid=<?= $siteid ?>"><li class="<?= $_GET['safety'] == $site_tab['id'] && $site == $site_name ? 'active blue' : '' ?>"><?= $site_tab['tab'] ?></li></a>
								<?php } else { ?>
									<a href="?site=<?= $site_name ?>&tab=<?= $site_tab['tab'] ?>&siteid=<?= $siteid ?>"><li class="<?= $site_name == $site && $site_tab['tab'] == $tab ? 'active blue' : '' ?>"><?= $site_tab['tab'] ?></li></a>
								<?php }
							}
						}
					} else {
						echo 'No Safety Forms Found for '.$site_name;
					}
				echo '</ul></li>';
			}
		} ?>
	</ul>
</div>
<div class="show-on-mob">
	<?php if(count($site_list) > 0) { ?>
		<a class="btn brand-btn <?= $site == '' ? 'active_tab' : '' ?>" href="?">All Sites</a>
	<?php } ?>
	<?php foreach($site_list as $siteid => $site_name) { ?>
		<a class="btn brand-btn <?= $site_name == $site ? 'active_tab' : '' ?>" href="?site=<?= $site_name ?>&siteid=<?= $siteid ?>"><?= $site_name ?></a>
	<?php } ?>
</div>