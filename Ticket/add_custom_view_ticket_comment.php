<h3><?= get_config($dbc, 'ticket_custom_notes_heading') ?></h3>
<?php foreach(explode('#*#',get_config($dbc, 'ticket_custom_notes_type')) as $comment_type) {
	echo "<h4>$comment_type</h4>";
	$comment_type = config_safe_str($comment_type);
	include('add_view_ticket_comment.php');
	echo '<div class="clearfix"></div>';
} ?>