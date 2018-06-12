<?php //echo "sdasdas"; exit; ?>
<?php if (strpos($value_config, ','."Package".',') !== FALSE) { ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pp" >Package<span class="glyphicon glyphicon-plus"></span></a>
		</h4>
	</div>

	<div id="collapse_pp" class="panel-collapse collapse">
		<div class="panel-body">
			<?php
			include ('add_estimate_package.php');
			?>
		</div>
	</div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Promotion".',') !== FALSE) { ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_promo" >Promotion<span class="glyphicon glyphicon-plus"></span></a>
		</h4>
	</div>

	<div id="collapse_promo" class="panel-collapse collapse">
		<div class="panel-body">
			<?php
			include ('add_estimate_promotion.php');
			?>
		</div>
	</div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Custom".',') !== FALSE) { ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_cus" >Custom<span class="glyphicon glyphicon-plus"></span></a>
		</h4>
	</div>

	<div id="collapse_cus" class="panel-collapse collapse">
		<div class="panel-body">
			<?php
			include ('add_estimate_custom.php');
			?>
		</div>
	</div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Material".',') !== FALSE) { ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_material" >Material<span class="glyphicon glyphicon-plus"></span></a>
		</h4>
	</div>

	<div id="collapse_material" class="panel-collapse collapse">
		<div class="panel-body">
			<?php
			include ('add_estimate_material.php');
			?>
		</div>
	</div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Services".',') !== FALSE) { ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_service" >Services<span class="glyphicon glyphicon-plus"></span></a>
		</h4>
	</div>

	<div id="collapse_service" class="panel-collapse collapse">
		<div class="panel-body">
			<?php
			include ('add_estimate_services.php');
			?>
		</div>
	</div>
</div>
<?php } ?>

<?php if($_GET['estimatetabid']): ?>
<?php if (strpos($estimateConfigValue, ','."Products".',') !== FALSE || strpos($estimateConfigValue, ','."Product".',') !== FALSE): ?>
	<?php if (strpos($value_config, ','."Products".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_Products" >Products<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_Products" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_estimate_products.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>
<?php endif; ?>
<?php endif; ?>

<?php if (strpos($value_config, ','."SRED".',') !== FALSE) { ?>
<div class="panel panel-default">
<div class="panel-heading">
	<h4 class="panel-title">
		<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_sred" >SR&ED<span class="glyphicon glyphicon-plus"></span></a>
	</h4>
</div>

<div id="collapse_sred" class="panel-collapse collapse">
	<div class="panel-body">
		<?php
		include ('add_estimate_sred.php');
		?>
	</div>
</div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Staff".',') !== FALSE) { ?>
<div class="panel panel-default">
<div class="panel-heading">
	<h4 class="panel-title">
		<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staff" >Staff<span class="glyphicon glyphicon-plus"></span></a>
	</h4>
</div>

<div id="collapse_staff" class="panel-collapse collapse">
	<div class="panel-body">
		<?php
		include ('add_estimate_staff.php');
		?>
	</div>
</div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Contractor".',') !== FALSE) { ?>
<div class="panel panel-default">
<div class="panel-heading">
	<h4 class="panel-title">
		<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_contractor" >Contractor<span class="glyphicon glyphicon-plus"></span></a>
	</h4>
</div>

<div id="collapse_contractor" class="panel-collapse collapse">
	<div class="panel-body">
		<?php
		include ('add_estimate_contractor.php');
		?>
	</div>
</div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Clients".',') !== FALSE) { ?>
<div class="panel panel-default">
<div class="panel-heading">
	<h4 class="panel-title">
		<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_clients" >Clients<span class="glyphicon glyphicon-plus"></span></a>
	</h4>
</div>

<div id="collapse_clients" class="panel-collapse collapse">
	<div class="panel-body">
		<?php
		include ('add_estimate_clients.php');
		?>
	</div>
</div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Vendor Pricelist".',') !== FALSE) { ?>
<div class="panel panel-default">
<div class="panel-heading">
	<h4 class="panel-title">
		<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_vendor" >Vendor Pricelist<span class="glyphicon glyphicon-plus"></span></a>
	</h4>
</div>

<div id="collapse_vendor" class="panel-collapse collapse">
	<div class="panel-body">
		<!-- Hide this if WASHTECH is using ESTIMATES -->
		<?php if(!isset($washtech_software_checker)) {
			//include ('add_estimate_vendor.php');
			include ('add_estimate_vendor_order_list.php');
		} else {
			include ('add_estimate_vendor_order_list.php');
		}
		?>
	</div>
</div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Customer".',') !== FALSE) { ?>
<div class="panel panel-default">
<div class="panel-heading">
	<h4 class="panel-title">
		<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_customer" >Customer<span class="glyphicon glyphicon-plus"></span></a>
	</h4>
</div>

<div id="collapse_customer" class="panel-collapse collapse">
	<div class="panel-body">
		<?php
		include ('add_estimate_customer.php');
		?>
	</div>
</div>
</div>
<?php } ?>

<?php if($_GET['estimatetabid']): ?>
<?php if (strpos($estimateConfigValue, ','."Inventory".',') !== FALSE): ?>
	<?php if (strpos($value_config, ','."Inventory".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_inv" >Inventory<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_inv" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_estimate_inventory.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>
<?php endif; ?>
<?php endif; ?>		

<?php if($_GET['estimatetabid']): ?>
<?php if (strpos($estimateConfigValue, ','."Equipment".',') !== FALSE): ?>
	<?php if (strpos($value_config, ','."Equipment".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_equipment" >Equipment<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_equipment" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_estimate_equipment.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>
<?php endif; ?>
<?php endif; ?>			

<?php if($_GET['estimatetabid']): ?>
<?php if (strpos($estimateConfigValue, ','."Labour".',') !== FALSE): ?>
	<?php if (strpos($value_config, ','."Labour".',') !== FALSE) { ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_labour" >Labour<span class="glyphicon glyphicon-plus"></span></a>
			</h4>
		</div>

		<div id="collapse_labour" class="panel-collapse collapse">
			<div class="panel-body">
				<?php
				include ('add_estimate_labour.php');
				?>
			</div>
		</div>
	</div>
	<?php } ?>
<?php endif; ?>
<?php endif; ?>	

<?php if (strpos($value_config, ','."Expenses".',') !== FALSE) { ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_expenses" >Expenses<span class="glyphicon glyphicon-plus"></span></a>
		</h4>
	</div>

	<div id="collapse_expenses" class="panel-collapse collapse">
		<div class="panel-body">
			<?php
			include ('add_estimate_expenses.php');
			?>
		</div>
	</div>
</div>
<?php } ?>

<?php if (strpos($value_config, ','."Other".',') !== FALSE) { ?>
<div class="panel panel-default">
<div class="panel-heading">
	<h4 class="panel-title">
		<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_other" >Other<span class="glyphicon glyphicon-plus"></span></a>
	</h4>
</div>

<div id="collapse_other" class="panel-collapse collapse">
	<div class="panel-body">
		<?php
		include ('add_estimate_other.php');
		?>
	</div>
</div>
</div>
<?php } ?>

<div class="panel panel-default">
<div class="panel-heading">
	<h4 class="panel-title">
		<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_summary" >Summary<span class="glyphicon glyphicon-plus"></span></a>
	</h4>
</div>

<div id="collapse_summary" class="panel-collapse collapse">
	<div class="panel-body">
		<?php
		include ('add_estimate_summary.php');
		?>
	</div>
</div>
</div>