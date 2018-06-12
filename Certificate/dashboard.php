<div class="has-dashboard form-horizontal dashboard-container dashboard-block-container">
	<?php foreach(['Complete'=>'active','Pending Completion'=>'pending','Expiry Pending'=>'expiring','Expired'=>'expired'] as $label => $group) {
		$sql = "SELECT `certificateid`, `contactid`, `projectid`, `title`, '".($group == 'pending' ? 'Issue Date' : ($group == 'active' ? 'Reminder Date' : 'Expiry Date'))."' `date_type`, `".($group == 'pending' ? 'issue_date' : ($group == 'active' ? 'reminder_date' : 'expiry_date'))."` `date` FROM `certificate` WHERE `deleted`=0";
		if($group == 'pending') {
			$sql .= " AND (`issue_date` > NOW() OR IFNULL(`issue_date`,'0000-00-00') = '0000-00-00')";
		} else if($group == 'expiring') {
			$sql .= " AND `issue_date` < NOW() AND IFNULL(`issue_date`,'0000-00-00') != '0000-00-00' AND `reminder_date` < NOW() AND `expiry_date` > NOW()";
		} else if($group == 'expired') {
			$sql .= " AND `issue_date` < NOW() AND IFNULL(`issue_date`,'0000-00-00') != '0000-00-00' AND `expiry_date` < NOW()";
		} else {
			$sql .= " AND `issue_date` < NOW() AND IFNULL(`issue_date`,'0000-00-00') != '0000-00-00' AND (`reminder_date` > NOW() OR `reminder_date`='0000-00-00') AND `expiry_date` > NOW()";
		}
		$list = mysqli_query($dbc, $sql); ?>
		<div class="dashboard-list" style="margin-bottom: -10px;">
			<div class="info-block-header"><h4><?= $label ?></h4>
			<div class="small"><?= mysqli_num_rows($list) ?></div></div></a>
			<ul class="dashboard-list">
				<?php while($row = mysqli_fetch_assoc($list)) { ?>
					<a href="?edit=<?= $row['certificateid'] ?>"><div class="dashboard-item">
						<h4><?= ($row['contactid'] > 0 ? get_contact($dbc, $row['contactid']) : PROJECT_NOUN.' #'.$row['projectid'].get_project($dbc, $row['projectid'], 'project_name')).': '.$row['title'] ?></h4>
						<em><?= $row['date_type'].': '.$row['date'] ?></em>
					</div></a>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
</div>