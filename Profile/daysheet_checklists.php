<!-- Daysheet My Checklists -->
<?php
    $user_fav_checklists = get_user_settings()['checklist_fav'];
    $user_fav_checklists = "'".implode("','", array_filter(explode(',',$user_fav_checklists)))."'";
    $checklists_query = "SELECT * FROM `checklist` WHERE checklist_name != '' AND deleted = 0 AND (`assign_staff` LIKE '%,$contactid,%' OR `assign_staff`=',ALL,')";
    $checklists_result = mysqli_query($dbc, $checklists_query);
    $num_rows = mysqli_num_rows($checklists_result);
?>
    <div class="col-xs-12">
        <div class="weekly-div" style="overflow-y: hidden;">
            <?php if($num_rows > 0) {
                echo '<ul class="option-list">';
                while($checklist = mysqli_fetch_array( $checklists_result )) {
                    echo "<a href='../Checklist/checklist.php?view=".$checklist['checklistid']."&from_url=".urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI'])."' class='col-sm-6' data-projectid='{$checklist['final_projectid']}' data-project='{$checklist['final_project_name']}' data-subtab='{$checklist['name']}' data-users='$users' data-name='{$checklist['checklist_name']}'><li style='width:calc(100% - 3em);'>";
                    profile_id($dbc, $checklist['created_by']);
                    $additional = array_values(array_unique(array_filter(explode(',',str_replace(",{$checklist['created_by']},",',',','.$checklist['assign_staff'].',')))));
                    echo '<div style="display:inline; width:calc(100% - 3em);">'.(count($additional) > 0 ? ($additional[0] == 'ALL' ? '+All Staff: ' : '+'.count($additional).' ') : '').' '.$checklist['checklist_name']."</div><div class='clearfix'></div></li></a>";
                }
                echo '</ul>';
            } else {
                echo "<h2>No Record Found.</h2>";
            } ?>
        </div>
    </div>