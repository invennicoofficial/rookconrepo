<!-- Tile Sidebar -->
<?php if($_GET['settings'] == 'config') {
    $settings_type = 'user';
    if(!empty($_GET['settings_type'])) {
        $settings_type = $_GET['settings_type'];
    } ?>
    <ul class="sidebar">
        <a href="?settings=config&settings_type=user"><li <?= ($settings_type == 'user' ? 'class="active"' : '') ?>>User Settings</li></a>
        <?php if (config_visible_function($dbc, 'profile') == 1) { ?><a href="?settings=config&settings_type=software"><li <?= ($settings_type == 'software' ? 'class="active"' : '') ?>>Software Default Settings</li></a><?php } else { ?><li <?= ($settings_type == 'software' ? 'class="active"' : '') ?>>Software Default Settings</li><?php } ?>
    </ul>
<?php } else {
    $field_tabs = ','.get_config($dbc, 'staff_field_subtabs').',';
    if(strpos($field_tabs,',Software ID,') === FALSE) {
        $field_tabs .= ',Software ID';
    }
    $sidebar_fields = [
        'staff_information' => ['Staff Information','Staff Information','staff_information'],
        'staff_address' => ['Staff Address','Staff Address','staff_address'],
        'employee_information' => ['Employee Information','Employee Information','employee_information'],
        'driver_information' => ['Driver Information','Driver Information','driver_information'],
        'direct_deposit_information' => ['Direct Deposit Information','Direct Deposit Information','direct_deposit_information'],
        'software_id' => ['','Software ID'],
        'social_media' => ['Social Media','Social Media','social_media'],
        'emergency' => ['Emergency','Emergency','emergency'],
        'health' => ['Health','Health & Safety','health'],
        'schedule' => ['Schedule','Staff Schedule','schedule'],
        'hr' => ['HR','HR Record','hr'],
        'staff_docs' => ['Staff Documents','Staff Documents','staff_docs'],
        'incident_reports' => ['Incident Reports',INC_REP_TILE,'incident_reports'],
        'time_off' => ['Time Off','Time Off Request Form','hr'],
        'time_off_requests' => ['Time Off','Time Off Requests','hr'],
        'certificates' => ['Certificates','Certificates & Certifications','certificates'],
        'goals' => ['Goals and Objectives','Goals & Objectives','goals']
    ];

    if(empty($subtab)) {
        $subtab = $_POST['subtab'];
    }
    ?>
    <script type="text/javascript">
    function submitButton(subtab) {
		$('input[required]').prop('checked',true);
        $('[name="subtab"]').val(subtab);
        $('[name="contactid"]').click();
    }
    </script>
    <button type="submit" name="contactid" value="<?= $contactid ?>" style="display: none;"></button>
    <ul class="sidebar">
        <!-- <a href="" onclick="submitButton('daysheet'); return false;"><li <?= ($subtab == 'daysheet' ? 'class="active"' : '') ?>>Planner</li></a> -->
        <a href="" onclick="submitButton('id_card'); return false;"><li <?= ($subtab == 'id_card' ? 'class="active"' : '') ?>>ID Card</li></a>
        <?php foreach ($sidebar_fields as $key => $sidebar_field) {
            if ((strpos($field_tabs,','.$sidebar_field[0].',') !== FALSE || empty($sidebar_field[0])) && (check_subtab_persmission($dbc, 'profile', ROLE, $sidebar_field[2]) === TRUE || empty($sidebar_field[2]))) {
                if(empty($subtab)) {
                    $subtab = $key;
                }
				?><a href="" onclick="submitButton('<?= $key ?>'); return false;"><li <?= ($subtab == $key ? 'class="active"' : '') ?>><?= $sidebar_field[1] ?></li></a>
			<?php }
        } ?>
        
        <?php if ($subtab == 'goals') {
            echo '<ul style="position: relative; left: 2em; width: calc(100% - 2.5em);">';

            $each_tab = array('Daily', 'Weekly', 'Bi-Monthly', 'Monthly', 'Quarterly', 'Semi-Annually', 'Yearly');
            $statusCount = 0;
            foreach ($each_tab as $cat_tab) {
                if ((!empty($_GET['status'])) && ($_GET['status'] == $cat_tab))
                    $statusCount++;
            }

            $totalCount = 0;
            foreach ($each_tab as $cat_tab) {
                if(empty($_GET['status']) || ($statusCount == 0 && $totalCount == 0)) {
                    $cat_tab = 'Daily';
                    $_GET['status'] = 'Daily';
                }

                if ((!empty($_GET['status'])) && ($_GET['status'] == $cat_tab)) {
                    $active_to_be = ' class="sidebar-lower-level active"';
                }
                else {
                    $active_to_be = '';
                }

                echo "<a href='gao_goal.php?maintype=my&status=".$cat_tab."'><li".$active_to_be.">".$cat_tab."</li></a>";
                $totalCount++;

            }

            echo '</ul>';
        } ?>
    </ul>
    <input type="hidden" name="subtab" value="<?= $subtab ?>">
<?php } ?>