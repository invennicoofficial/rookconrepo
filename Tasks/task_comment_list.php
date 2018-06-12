<?php include_once('../include.php');
checkAuthorised('tasks');
$comments_taskid = filter_var($_GET['tasklistid'],FILTER_SANITIZE_STRING);
$comments = $dbc->query("SELECT `created_by`, `created_date`, `comment` FROM `task_comments` WHERE `tasklistid`='$comments_taskid' AND `tasklistid` > 0 AND `deleted`=0 ORDER BY `taskcommid` DESC");
if($comments->num_rows > 0) { ?>
	<div class="col-sm-12 double-gap-top">
		<?php while($row_comment = $comments->fetch_assoc()) { ?>
			<div class="note_block row gap-bottom">
				<div class="col-xs-1"><?php profile_id($dbc, $row_comment['created_by']); ?></div>
				<div class="col-xs-11">
					<div><?= preg_replace_callback('/\[PROFILE ([0-9]+)\]/',profile_callback,html_entity_decode($row_comment['comment'])); ?></div>
					<div class="gap-top"><em>Added by <?= get_contact($dbc, $row_comment['created_by']); ?> on <?= $row_comment['created_date']; ?></em></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<hr />
		<?php } ?>
	</div>
	<div class="clearfix"></div><?php
}