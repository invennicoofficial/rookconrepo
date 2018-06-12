<!-- Daysheet My Projects-->
<?php
    $projects_list = mysqli_query($dbc, "SELECT * FROM `project` WHERE `project_lead` = '".$contactid."' AND `deleted` = 0 AND `status` != 'Archive' AND `status` != 'Pending' ORDER BY `projectid` DESC");
    $project_tabs = ['favourite'=>'Favourite','pending'=>'Pending'];
    foreach(explode(',',get_config($dbc, "project_tabs")) as $type_name) {
        $project_tabs[config_safe_str($type_name)] = $type_name;
    }
    $num_rows = mysqli_num_rows($projects_list);
?>
    <div class="col-xs-12">
      <!--  <div class="weekly-div" style="overflow-y: hidden;"> -->
            <?php if($num_rows > 0) {
                while($project = mysqli_fetch_array($projects_list)) {
                    $projectid = $project['projectid'];
                    $project_type = $project['projecttype'];
                    $value_config = array_filter(array_unique(array_merge(explode(',',mysqli_fetch_array(mysqli_query($dbc,"SELECT `config_fields` FROM field_config_project WHERE type='$project_type'"))[0]),explode(',',mysqli_fetch_array(mysqli_query($dbc,"SELECT `config_fields` FROM field_config_project WHERE type='ALL'"))[0]))));
                    $action = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `project_actions` WHERE `projectid`='$projectid' AND `deleted`=0 AND `completed`=0 ORDER BY `due_date` ASC"));
                    $invoices = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM (SELECT `paid` FROM `invoice` WHERE `projectid`='$projectid' UNION SELECT 'Yes') `invoices` ORDER BY `paid`='Yes'")); ?>


                    <div class="dashboard-item " data-id="<?= $project['projectid'] ?>">
                        <h4>
                            <a href="../Project/projects.php?edit=<?= $project['projectid'] ?>">#<?= $project['projectid'] ?> <?= $project['project_name'] ?> - <?= get_client($dbc, $project['businessid']) ?>
                                <span class="small">(<?= $project_tabs[$project['projecttype']] ?>)
                                    <?php if(in_array('DB Review',$value_config) || !in_array_any(['DB Review','DB Status','DB Billing','DB Type','DB Follow Up','DB Assign'],$value_config)) { ?>
                                        <span class="review_date">Last Reviewed: <?= $project['reviewer_id'] > 0 ? date('Y-m-d', strtotime($project['review_date'])).' by '.get_contact($dbc, $project['reviewer_id']) : 'Never' ?></span>
                                    <?php } ?>
                                </span>
                            </a>
                            <div  class="clearfix"></div>
                        </h4>
                        <?php if(in_array('DB Business',$value_config) || !in_array_any(['DB Review','DB Status','DB Business','DB Contact','DB Billing','DB Type','DB Follow Up','DB Assign'],$value_config)) { ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-4"><?= BUSINESS_CAT ?>:</label>
                                    <div class="col-sm-8">
                                        <?= get_client($dbc, $project['businessid']) ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(in_array('DB Contact',$value_config) || !in_array_any(['DB Review','DB Status','DB Business','DB Contact','DB Billing','DB Type','DB Follow Up','DB Assign'],$value_config)) { ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-4">Contact:</label>
                                    <div class="col-sm-8">
                                        <?= get_contact($dbc, $project['clientid']) ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(in_array('DB Status',$value_config) || !in_array_any(['DB Review','DB Status','DB Business','DB Contact','DB Billing','DB Type','DB Follow Up','DB Assign'],$value_config)) { ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-4">Status:</label>
                                    <div class="col-sm-8">
                                            <?= $project['status'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(in_array('DB Billing',$value_config) || !in_array_any(['DB Review','DB Status','DB Business','DB Contact','DB Billing','DB Type','DB Follow Up','DB Assign'],$value_config)) { ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-4">Paid:</label>
                                    <div class="toggle-switch form-group col-sm-8"><input type="hidden" name="paid" value="<?= $invoices['paid'] ?>" data-table="invoice" data-identifier="projectid" data-id="<?= $project['projectid'] ?>">
                                        <img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" style="height: 2em; <?= $invoices['paid'] == 'Yes' ? 'display: none;' : '' ?>">
                                        <img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" style="height: 2em; <?= $invoices['paid'] == 'Yes' ? '' : 'display: none;' ?>">
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(in_array('DB Type',$value_config) || !in_array_any(['DB Review','DB Status','DB Business','DB Contact','DB Billing','DB Type','DB Follow Up','DB Assign'],$value_config)) { ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-4"><?= PROJECT_NOUN ?> Type:</label>
                                    <div class="col-sm-8">
                                            <?php foreach($project_tabs as $value => $type) {
                                                if($type != '' && $value != 'favourite' && $value != 'pending' && $value == $project['projecttype']) {
                                                    echo $type;
                                                }
                                            } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(in_array('DB Follow Up',$value_config) || !in_array_any(['DB Review','DB Status','DB Business','DB Contact','DB Billing','DB Type','DB Follow Up','DB Assign'],$value_config)) { ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-4">Follow Up Date:</label>
                                    <div class="col-sm-8">
                                        <?= $project['followup'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(in_array('DB Assign',$value_config) || !in_array_any(['DB Review','DB Status','DB Business','DB Contact','DB Billing','DB Type','DB Follow Up','DB Assign'],$value_config)) { ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-4"><?= PROJECT_NOUN ?> Lead:</label>
                                    <div class="col-sm-8">
                                        <?= get_contact($dbc, $project['project_lead']) ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if(in_array('DB Milestones',$value_config)) { ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-sm-4"><?= PROJECT_NOUN ?> Milestones:</label>
                                    <div class="col-sm-8">
                                        <?php $milestones = $dbc->query("SELECT `miles`.`milestone`, `label`, `pathid`, SUM(IF(`list`.`id` > 0,1,0)) `count` FROM `project_path_custom_milestones` `miles` LEFT JOIN (SELECT `ticketid` `id`, `projectid`, `milestone_timeline` `milestone` FROM `tickets` WHERE `status` NOT IN ('Archive','Archived','Done') AND `deleted`=0 UNION SELECT `tasklistid` `id`, `projectid`, `project_milestone` `milestone` FROM `tasklist` WHERE `status` != 'Done' AND `deleted`=0) `list` ON `miles`.`milestone`=`list`.`milestone` AND `miles`.`projectid`=`list`.`projectid` WHERE `miles`.`projectid`='".$project['projectid']."' AND `miles`.`deleted`=0 GROUP BY `miles`.`projectid`, `miles`.`milestone` ORDER BY `miles`.`sort`,`miles`.`id`");
                                        while($milestone = $milestones->fetch_assoc()) {
                                            ?>
                                            <!-- <a href="?edit=<?= $projectid ?>&tab=path_<?= config_safe_str($milestone['milestone']) ?>&pathid=I|<?= $milestone['pathid'] ?>"><?= $milestone['label'] ?> (<?= $milestone['count'] ?>)</a> -->
                                            <?= $milestone['label'] ?> (<?= $milestone['count'] ?>)
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="clearfix"></div>
                    </div>
                <?php }
            } else {
                echo "<h2>No Record Found.</h2>";
            } ?>
    <!--    </div>  -->
    </div>