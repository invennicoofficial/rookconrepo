<?php if (strpos($estimateConfigValue, ','."Estimate Info".',') !== FALSE): ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_abi" >Estimate Info<span class="glyphicon glyphicon-minus"></span></a>
			</h4>
		</div>

		<div id="collapse_abi" class="panel-collapse collapse <?php echo $info_view; ?>">
			<div class="panel-body">
				<?php
				include ('add_estimate_basic_info.php');
				?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if (strpos($estimateConfigValue, ','."Staff".',') !== FALSE): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staff2" >Staff<span class="glyphicon glyphicon-plus"></span></a>
		</h4>
	</div>

	<div id="collapse_staff2" class="panel-collapse collapse">
		<div class="panel-body">
			<?php
			include ('add_estimate_assign_staff.php');
			?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (strpos($estimateConfigValue, ','."Dates".',') !== FALSE): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_date" >Dates<span class="glyphicon glyphicon-plus"></span></a>
		</h4>
	</div>

	<div id="collapse_date" class="panel-collapse collapse">
		<div class="panel-body">
			<?php
			include ('add_estimate_dates.php');
			?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (strpos($estimateConfigValue, ','."Details".',') !== FALSE): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_detail" >Details<span class="glyphicon glyphicon-plus"></span></a>
		</h4>
	</div>

	<div id="collapse_detail" class="panel-collapse collapse">
		<div class="panel-body">
			<?php
			include ('add_estimate_detail.php');
			?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (strpos($estimateConfigValue, ','."Notes".',') !== FALSE): ?>
<?php if(!empty($_GET['estimateid'])) { ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_notes" >
			   Notes<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h4>
	</div>

	<div id="collapse_notes" class="panel-collapse collapse <?php echo $note_add_view; ?>">
		<div class="panel-body">
		 <?php include ('add_view_estimate_comment.php'); ?>
		</div>
	</div>
</div>
<?php } ?>
<?php endif; ?>

<?php if (strpos($estimateConfigValue, ','."Documents".',') !== FALSE): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_doc" >Documents<span class="glyphicon glyphicon-plus"></span></a>
		</h4>
	</div>

	<div id="collapse_doc" class="panel-collapse collapse">
		<div class="panel-body">
			<?php
			include ('add_estimate_documents.php');
			?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (strpos($estimateConfigValue, ','."Budget Info".',') !== FALSE): ?>
<!-- Hide this if WASHTECH is using ESTIMATES -->
<?php if(!isset($washtech_software_checker)) { ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_basic" >Budget Info<span class="glyphicon glyphicon-plus"></span></a>
		</h4>
	</div>

	<div id="collapse_basic" class="panel-collapse collapse">
		<div class="panel-body">

			<?php
			include ('add_estimate_budget.php');
			?>

		</div>
	</div>
</div>
<?php endif; ?>