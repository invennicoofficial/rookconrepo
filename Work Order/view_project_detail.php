<?php
$projectid = get_workorder($dbc, $workorderid, 'projectid');

$detail_issue = get_project_detail($dbc, $projectid, 'detail_issue');
$detail_problem = get_project_detail($dbc, $projectid, 'detail_problem');
$detail_technical_uncertainty = get_project_detail($dbc, $projectid, 'detail_technical_uncertainty');
$detail_base_knowledge = get_project_detail($dbc, $projectid, 'detail_base_knowledge');
$detail_objectives = get_project_detail($dbc, $projectid, 'detail_objectives');
$detail_already_known = get_project_detail($dbc, $projectid, 'detail_already_known');
$detail_sources = get_project_detail($dbc, $projectid, 'detail_sources');
$detail_current_designs = get_project_detail($dbc, $projectid, 'detail_current_designs');
$detail_known_techniques = get_project_detail($dbc, $projectid, 'detail_known_techniques');
$detail_review_needed = get_project_detail($dbc, $projectid, 'detail_review_needed');
$detail_looking_to_achieve = get_project_detail($dbc, $projectid, 'detail_looking_to_achieve');
$detail_plan = get_project_detail($dbc, $projectid, 'detail_plan');
$detail_next_steps = get_project_detail($dbc, $projectid, 'detail_next_steps');
$detail_learnt = get_project_detail($dbc, $projectid, 'detail_learnt');
$detail_discovered = get_project_detail($dbc, $projectid, 'detail_discovered');
$detail_tech_advancements = get_project_detail($dbc, $projectid, 'detail_tech_advancements');
$detail_work = get_project_detail($dbc, $projectid, 'detail_work');
$detail_adjustments_needed = get_project_detail($dbc, $projectid, 'detail_adjustments_needed');
$detail_future_designs = get_project_detail($dbc, $projectid, 'detail_future_designs');

?>

<?php if($detail_issue != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Issue:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_issue); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_problem != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Problem:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_problem); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_gap != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">GAP:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_gap); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_technical_uncertainty != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Technical Uncertainty:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_technical_uncertainty); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_base_knowledge != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Base Knowledge:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_base_knowledge); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_objectives != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Objectives:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_objectives); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_already_known != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Already Known:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_already_known); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_sources != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Sources:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_sources); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_current_designs != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Current Designs:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_current_designs); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_known_techniques != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Known Techniques:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_known_techniques); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_review_needed != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Review Needed:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_review_needed); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_looking_to_achieve != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Looking to Achieve:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_looking_to_achieve); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_plan != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Plan:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_plan); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_next_steps != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Next Steps:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_next_steps); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_learnt != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Learned:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_learnt); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_discovered != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Discovered:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_discovered); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_tech_advancements != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Tech Advancements:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_tech_advancements); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_work != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Work:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_work); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_adjustments_needed != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Adjustments Needed:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_adjustments_needed); ?>
    </div>
</div>
<?php } ?>

<?php if($detail_future_designs != '') { ?>
<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Future Designs:</label>
    <div class="col-sm-8">
        <?php echo html_entity_decode($detail_future_designs); ?>
    </div>
</div>
<?php } ?>