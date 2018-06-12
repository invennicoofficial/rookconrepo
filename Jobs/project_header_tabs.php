<?php if(empty($_GET['nav']) || $_GET['nav'] != 'no_tabs') {
	if(config_visible_function($dbc, 'project') == 1) {
		echo '<div class="pull-right">';
			echo '<a href="field_config_project.php?type='.$type.'" class="mobile-block pull-right"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
			echo '<div class="popover-examples list-inline pull-right" style="margin:10px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></div>';
		echo '</div>';
	}
	
	echo "<div class='mobile-100-container'>";
	if(empty($_GET['from'])) { ?>
		<span class="nav-subtab">
			<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see projects that are not yet approved."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
			if ( check_subtab_persmission($dbc, 'project', ROLE, 'pending') === TRUE ) { ?>
				<a href='project.php?tab=projects&type=Pending'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_pending; ?>"><?php echo 'Pending '.JOBS_TILE; ?></button></a>&nbsp;&nbsp;<?php
			} else { ?>
				<button type="button" class="btn disabled-btn mobile-block mobile-100 <?php echo $pending; ?>">Pending Projects</button>&nbsp;&nbsp;<?php
			} ?>
		</span>
		<?php foreach($jobs_tabs as $key => $tab): ?>
			<span class="nav-subtab">
				<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see approved <?php echo $tab.' '.JOBS_TILE; ?>."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
				if ( check_subtab_persmission($dbc, 'project', ROLE, $project_vars[$key]) === TRUE ) { ?>
					<a href='project.php?tab=projects&type=<?php echo $project_vars[$key]; ?>'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ${'active_'.$project_vars[$key]}; ?>"><?php echo $tab.' '.JOBS_TILE; ?></button></a>&nbsp;&nbsp;<?php
				} else { ?>
					<button type="button" class="btn disabled-btn mobile-block mobile-100 <?php echo $pending; ?>">Pending Projects</button>&nbsp;&nbsp;<?php
				} ?>
			</span>
		<?php endforeach; ?>
		
		<br><br>
		<?php if($type != 'Pending') {
			$pp = get_jobs_path_milestone($dbc, 1, 'project_path');
			if($pp != '') {
				$main_project = '';
				$path_project = '';
				$gantt_project = '';
				if(strpos($_SERVER['REQUEST_URI'], 'project.php') !== FALSE) {
					$main_project = ' active_tab';
				}
				if(strpos($_SERVER['REQUEST_URI'], 'project_path.php') !== FALSE) {
					$path_project = ' active_tab';
				}
				if(strpos($_SERVER['REQUEST_URI'], 'project_gantt_chart.php') !== FALSE) {
					$gantt_project = ' active_tab';
				}
			}
		}
		echo '<br><br>';
	}
} ?>