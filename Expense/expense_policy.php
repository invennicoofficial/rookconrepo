<style>
.iframe_overlay .iframe {
	width: 40em;
}
</style>
<script>
function remove_policy(id) {
	$.ajax({
		url: 'inbox_ajax.php?action=remove_policy',
		method: 'POST',
		data: { policy_id: id },
		success: function(response) {
			window.location.reload();
		}
	});
}
</script>
<div class='expense-list col-sm-6 pull-right'>
	<ul class='chained-list' style='max-width: 50em;'>
		<li style="text-align:center; line-height:2.5em;">
			<img src="<?= WEBSITE_URL ?>/img/icons/credit_cards.png" style="max-width:50%; width:8em;"><br />
			Expense Policy
        </li>
		<li class="double-padded">
            <p style="font-size:0.7em;">To the left are the rules and policies that apply to your spending.</p>
			<p style="font-size:0.7em;">You can always review these rules here. If you submit an expense that violates one of these rules, it will be flagged.</p>
        </li>
	</ul>
</div>
<div class='expense-list col-sm-6' style='text-align:center;'>
	<ul class='chained-list' style='max-width: 50em;'>
		<li>
			<div class="middle-valign col-small text-left"><img src="<?= WEBSITE_URL ?>/img/icons/ROOK-status-warning.png" style="height:1em;"></div>
            <div class="middle-valign col-large">Warning Rules</div><?php
            if($approvals == 1) { ?>
				<div class="middle-valign font-medium col-small text-right"><a href="" onclick="overlayIFrame('edit_policy.php?edit=NEW&type=Warn'); return false;">Add</a></div><?php
            } ?>
		</li>
		<?php $policy_list = mysqli_query($dbc, "SELECT * FROM `expense_policy` WHERE `deleted`=0 AND `type` IN ('Warn','')");
		while($policy = mysqli_fetch_array($policy_list)) { ?>
			<li class="text-left"><a href="" onclick="overlayIFrame('edit_policy.php?edit=<?= $policy['policy_id'] ?>'); return false;" style="font-size: 0.75em;"><?= $policy['name'] ?></a>
				<?= ($approvals == 1 ? '<a href="" onclick="remove_policy('.$policy['policy_id'].'); return false;" class="pull-right"><img src="'.WEBSITE_URL.'/img/remove.png" style="height: 1em;"></a>' : '') ?></li>
		<?php } ?>
		<li class="font-small text-left">
			<a href="" class="font-blue" onclick="$(this).next('div').toggle(); return false;">What is a warning rule?</a>
			<div class="form-group gap-top" style="display: none;">A Warning Rule will allow the user to submit the expense but will flag it for the admin who can approve or reject it at their discretion.</div>
		</li>
	</ul>
	<ul class='chained-list' style='max-width: 50em;'>
		<li>
			<div class="middle-valign col-small text-left"><img src="<?= WEBSITE_URL ?>/img/icons/ROOK-status-error.png" style="height:1em;" /></div>
            <div class="middle-valign col-large">Blocking Rules</div><?php
            if($approvals == 1) { ?>
				<div class="middle-valign font-medium col-small text-right"><a href="" onclick="overlayIFrame('edit_policy.php?edit=NEW&type=Block'); return false;">Add</a></div><?php
            } ?>
		</li>
		<?php $policy_list = mysqli_query($dbc, "SELECT * FROM `expense_policy` WHERE `deleted`=0 AND `type` IN ('Block')");
		while($policy = mysqli_fetch_array($policy_list)) { ?>
			<li class="text-left"><a href="" onclick="overlayIFrame('edit_policy.php?edit=<?= $policy['policy_id'] ?>'); return false;" style="font-size: 0.75em;"><?= $policy['name'] ?></a>
				<?= ($approvals == 1 ? '<a href="" onclick="remove_policy('.$policy['policy_id'].'); return false;" class="pull-right"><img src="'.WEBSITE_URL.'/img/remove.png" style="height: 1em;"></a>' : '') ?></li>
		<?php } ?>
		<li class="font-small text-left">
			<a href="" class="font-blue" onclick="$(this).next('div').toggle(); return false;">What is a blocking rule?</a>
			<div class="form-group gap-top" style="display: none;">A Blocking Rule, when violated, will not let the user submit the expense.</div>
		</li>
	</ul>
</div>