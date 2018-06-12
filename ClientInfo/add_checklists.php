<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion2" href="#collapse1" >
                View Checklist<span class="glyphicon glyphicon-plus"></span>
            </a>
        </h4>
    </div>

	<?php
		$checklist_add_class = 'in';
		$checklist_class = '';
		if($_GET['update_type'] == 'edit' || $_GET['update_type'] == 'add')
			$checklist_add_class = 'in';
		else
			$checklist_class = 'in';
	?>
    <div id="collapse1" class="panel-collapse collapse <?php echo $checklist_class; ?>">
        <div class="panel-body">			
			<div class="form-group clearfix">
			<?php
				include('checklist.php'); 
			?>
			</div>
        </div>
    </div>
</div>
<?php if($_GET['update_type'] == 'edit' || $_GET['update_type'] == 'add'): ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion2" href="#collapse3" >
				<?php if($_GET['update_type'] == 'Edit'): ?>
					Edit
				<?php else: ?>
					Add
				<?php endif; ?>
					 Checklist<span class="glyphicon glyphicon-plus"></span>
				
            </a>
        </h4>
    </div>

    <div id="collapse3" class="panel-collapse collapse <?php echo $checklist_add_class; ?>">
        <div class="panel-body">
			<div class="form-group clearfix">
			<?php
				include('add_checklist.php'); 
			?>
			</div>
        </div>
    </div>
</div>
<?php endif; ?>