<?php
/*
 * Add Milestones
 * Called From:
 *  - tasks_dashbaord.php
 */
include ('../include.php');
checkAuthorised('tasks');
error_reporting(0);

if (isset($_POST['add_tab'])) {
    $taskboardid = preg_replace('/[^0-9]/', '', $_POST['taskboardid']);
    $new_milestones = $_POST['milestone'];
    foreach($new_milestones as $new_milestone) {
        mysqli_query($dbc, "INSERT INTO task_additional_milestones (task_board_id, milestone) VALUES ('$taskboardid', '$new_milestone')");
    }
    echo '<script type="text/javascript">window.location.replace("?category='.$taskboardid.'&tab='.$board_security.'");</script>';
} ?>

<script type="text/javascript">
$(document).ready(function() {
	/* $("#project_path").change(function() {
		var project_path = $("#project_path").val();
		$.ajax({
			type: "GET",
			url: "task_ajax_all.php?fill=project_path_milestone&project_path="+project_path,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$('#milestone_timeline').html(response);
				$("#milestone_timeline").trigger("change.select2");
			}
		});
	}); */
    
    $('.add').on('click', cloneMilestone);
    $('.delete').on('click', removeMilestone);
    
    $('form').submit(function(){
        /* $.ajax({
			type: "GET",
			url: "task_ajax_all.php?fill=project_path_milestone&project_path="+project_path,
			dataType: "html",   //expect html to be returned
			success: function(response){
				$('#milestone_timeline').html(response);
				$("#milestone_timeline").trigger("change.select2");
			}
		}); */
    });
});

var mcount = $('.milestone').length;

function cloneMilestone() {
    var clone = $(this).parents('.milestone').clone();
    $('.new_milestones').append(clone);
    clone.find('.milestone').attr('id', 'milestone_'+mcount);
    clone.find('[name^=milestone]').val('');
    clone.find('[name^=milestone]').focus();
    clone.on('click', '.add', cloneMilestone);
    clone.on('click', '.delete', removeMilestone);
    mcount++;
}

function removeMilestone() {
    $(this).parents('.milestone').remove();
}
</script>

<div class="container">
	<div class="row">
        <h3>Add Milestones</h3>
        <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form"><?php
            if(!empty($_GET['task_board'])) {
                $taskboardid = preg_replace('/[^0-9]/', '', $_GET['task_board']); ?>
                <input type="hidden" id="taskboardid" name="taskboardid" value="<?= $taskboardid ?>" /><?php
            } ?>
            
            <div class="form-group">
                <label class="col-sm-4 control-label">New Milestone(s):</label>
            </div>
            <div class="form-group clearfix milestone" id="milestone_0">
                <div class="col-xs-9"><input name="milestone[]" type="text" class="form-control" value="" /></div>
                <div class="col-xs-3 m-top-mbl">
                    <img src="../img/remove.png" class="inline-img cursor-hand delete" alt="Delete" />
                    <img src="../img/icons/ROOK-add-icon.png" class="inline-img cursor-hand add" alt="Add Milestone" />
                </div>
            </div>
            
            <div class="clearfix new_milestones"></div>

            <div class="form-group pull-right double-gap-top"><?php
                $back_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>
                <a href="<?= $back_url ?>" class="btn brand-btn pull-left">Cancel</a>
                <button	type="submit" name="add_tab" value="add_tab" class="btn brand-btn pull-right">Submit</button>
                <div class="clearfix"></div>
            </div>
        </form>
    </div><!-- .row -->
</div><!-- .container -->