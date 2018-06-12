<?php
error_reporting(0);
include_once('../include.php');

$mdsr_child_name = '';
$mdsr_child_dob = '';
$mdsr_date_of_report = '';
$mdsr_background_info = '';
$mdsr_progress = '';
$mdsr_clinical_impacts = '';
$mdsr_proposed_goal_areas = '';
$mdsr_recommendations = '';

if ( $ticketid > 0 && strpos($value_config,',Multi-Disciplinary Summary Report,') !== false ) {
    $result_mdsr = mysqli_query($dbc, "SELECT `mdsr_child_name`, `mdsr_child_dob`, `mdsr_date_of_report`, `mdsr_background_info`, `mdsr_progress`, `mdsr_clinical_impacts`, `mdsr_proposed_goal_areas`, `mdsr_recommendations` FROM `tickets` WHERE `ticketid`='$ticketid'");
    if ( $result_mdsr->num_rows > 0 ) {
        while ( $row_mdsr=mysqli_fetch_assoc($result_mdsr) ) {
            $mdsr_child_name = $row_mdsr['mdsr_child_name'];
            $mdsr_child_dob = $row_mdsr['mdsr_child_dob'];
            $mdsr_date_of_report = $row_mdsr['mdsr_date_of_report'];
            $mdsr_background_info = $row_mdsr['mdsr_background_info'];
            $mdsr_progress = $row_mdsr['mdsr_progress'];
            $mdsr_clinical_impacts = $row_mdsr['mdsr_clinical_impacts'];
            $mdsr_proposed_goal_areas = $row_mdsr['mdsr_proposed_goal_areas'];
            $mdsr_recommendations = $row_mdsr['mdsr_recommendations'];
        }
    }
}

if(strpos($value_config,',Multi-Disciplinary Summary Report,') !== false) {
    ?><?= (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>Multi-Disciplinary Summary Report</h3>') ?>
	<input name="ticket_type" type="hidden" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" value="<?= $ticket_type; ?>" /><?php
    
    foreach ($field_sort_order as $field_sort_field) {
        if(strpos($value_config,',Child Name,') !== false && $field_sort_field == 'Child Name') { ?>
            <div class="form-group">
                <label for="mdsr_child_name" class="col-sm-4 control-label">Child's Name:</label>
                <div class="col-sm-8">
                    <input name="mdsr_child_name" type="text" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" value="<?= $mdsr_child_name; ?>" class="form-control" />
                </div>
            </div>
            <?php $pdf_contents[] = ['Child\'s Name', $mdsr_child_name];
        }
        
        if(strpos($value_config,',Date of Birth,') !== false && $field_sort_field == 'Date of Birth') { ?>
            <div class="form-group">
                <label for="mdsr_child_dob" class="col-sm-4 control-label">Date of Birth:</label>
                <div class="col-sm-8">
                    <input name="mdsr_child_dob" type="text" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" value="<?= $mdsr_child_dob; ?>" class="form-control datepicker" />
                </div>
            </div>
            <?php $pdf_contents[] = ['Child\'s Name', $mdsr_child_name];
        }
        
        if(strpos($value_config,',Date of Report,') !== false && $field_sort_field == 'Date of Report') { ?>
            <div class="form-group">
                <label for="mdsr_date_of_report" class="col-sm-4 control-label">Date of Report:</label>
                <div class="col-sm-8">
                    <input name="mdsr_date_of_report" type="text" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" value="<?= $mdsr_date_of_report; ?>" class="form-control datepicker" />
                </div>
            </div>
            <?php $pdf_contents[] = ['Date of Report', $mdsr_date_of_report];
        }
        
        if(strpos($value_config,',Background Information,') !== false && $field_sort_field == 'Background Information') { ?>
            <div class="form-group">
                <label for="mdsr_background_info" class="col-sm-4 control-label">Background Information:</label>
                <div class="col-sm-12">
                    <textarea name="mdsr_background_info" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" class="form-control"><?= html_entity_decode($mdsr_background_info); ?></textarea>
                </div>
            </div>
            <?php $pdf_contents[] = ['Background Information', html_entity_decode($mdsr_background_info)];
        }
        
        if(strpos($value_config,',Progress,') !== false && $field_sort_field == 'Progress') { ?>
            <div class="form-group">
                <label for="mdsr_progress" class="col-sm-4 control-label">Progress:</label>
                <div class="col-sm-12">
                    <textarea name="mdsr_progress" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" class="form-control"><?= html_entity_decode($mdsr_progress); ?></textarea>
                </div>
            </div>
            <?php $pdf_contents[] = ['Progress', html_entity_decode($mdsr_progress)];
        }
        
        if(strpos($value_config,',Clinical Impacts,') !== false && $field_sort_field == 'Clinical Impacts') { ?>
            <div class="form-group">
                <label for="mdsr_clinical_impacts" class="col-sm-4 control-label">Clinical Impacts:</label>
                <div class="col-sm-12">
                    <textarea name="mdsr_clinical_impacts" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" class="form-control"><?= html_entity_decode($mdsr_clinical_impacts); ?></textarea>
                </div>
            </div>
            <?php $pdf_contents[] = ['Clinical Impacts', html_entity_decode($mdsr_clinical_impacts)];
        }
        
        if(strpos($value_config,',Proposed Goal Areas,') !== false && $field_sort_field == 'Proposed Goal Areas') { ?>
            <div class="form-group">
                <label for="mdsr_proposed_goal_areas" class="col-sm-4 control-label">Proposed Goal Areas:</label>
                <div class="col-sm-12">
                    <textarea name="mdsr_proposed_goal_areas" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" class="form-control"><?= html_entity_decode($mdsr_proposed_goal_areas); ?></textarea>
                </div>
            </div>
            <?php $pdf_contents[] = ['Proposed Goal Areas', html_entity_decode($mdsr_proposed_goal_areas)];
        }
        
        if(strpos($value_config,',Recommendations,') !== false && $field_sort_field == 'Recommendations') { ?>
            <div class="form-group">
                <label for="mdsr_recommendations" class="col-sm-4 control-label">Recommendations:</label>
                <div class="col-sm-12">
                    <textarea name="mdsr_recommendations" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" class="form-control"><?= html_entity_decode($mdsr_recommendations); ?></textarea>
                </div>
            </div>
            <?php $pdf_contents[] = ['Recommendations', html_entity_decode($mdsr_recommendations)];
        }
    }
} ?>