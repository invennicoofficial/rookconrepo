<script>
var contact_type = '';
$(document).ready(function() {
	$('.panel-heading').off('click').click(loadPanel);
	$(window).resize(function() {
		var available_height = window.innerHeight - $('footer:visible').outerHeight() - ($('.main-screen .main-screen').offset() == undefined ? 0 : $('.main-screen .main-screen').offset().top);
		if(available_height > 200) {
			$('.main-screen .main-screen').outerHeight(available_height).css({'overflow-y':'auto', 'max-width':'100%', 'margin-left':'0'});
			$('ul.sidebar').outerHeight(available_height).css('overflow-y','auto');
		}
	}).resize();

    $('.search-contact-form').keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            $('input[value="Filter"]').click();
        }
    });

});
function loadPanel() {
	var panel = $(this).closest('.panel').find('.panel-body');
	$.ajax({
		url: '../Contacts/'+panel.data('file'),
		data: { folder: '<?= FOLDER_NAME ?>', type: contact_type },
		method: 'POST',
		response: 'html',
		success: function(response) {
			panel.html(response);
			$('.pagination_links a').click(pagination_load);
		}
	});
}
function pagination_load() {
	var target = $(this).closest('.panel').find('.panel-body');
	$.ajax({
		url: this.href,
		data: { folder: '<?= FOLDER_NAME ?>', type: contact_type },
		method: 'POST',
		response: 'html',
		success: function(response) {
			target.html(response);
			$('.pagination_links a').click(pagination_load);
		}
	});
	return false;
}
function deleteContact(link) {
	if(confirm("Are you sure you want to archive this contact?")) {
		var startBlock = $(link).closest('tr').prevAll('tr').filter(function() { return $(this).find('td[colspan=2]').length > 0; }).first();
		var end = false;
		startBlock.nextAll('tr').each(function() {
			if(!end) {
				if($(this).find('td[colspan=2]').length > 0) {
					end = true;
				}
				$(this).remove();
			}
		});
		$.ajax({
			url: '../Contacts/contacts_ajax.php?action=archive&contactid='+$(link).data('contactid'),
			method: 'POST'
		});
	}
}
function statusChange(link) {
	var change_status = $(link).data('status') == "0" ? 'Activate' : 'Deactivate';
	if(confirm("Are you sure you want to "+change_status+" this contact?")) {
		$(link).text($(link).data('status') == "0" ? 'Deactivate' : 'Activate').data('status',$(link).data('status') == "0" ? '1' : '0');
		$.ajax({
			url: '../Contacts/contacts_ajax.php?action=status_change&contactid='+$(link).data('contactid')+'&new_status='+$(link).data('status'),
			method: 'POST'
		});
	}
}
</script>
<?php $lists = array_filter(explode(',',get_config($dbc, FOLDER_NAME.'_tabs')));
$staff = array_search('Staff',$lists);
if($staff !== FALSE) {
	unset($lists[$staff]);
}
if(mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) FROM `contacts` WHERE `deleted`=0 AND `tile_name`='".FOLDER_NAME."' AND `category` NOT IN ('".implode("','",$lists)."','Staff')"))[0]) {
	$lists[] = 'Uncategorized';
}
$category = empty($_GET['list']) ? $lists[0] : filter_var($_GET['list'],FILTER_SANITIZE_STRING);
$status = (empty($_GET['status']) ? 'active' : $_GET['status']); ?>
<?php $list = ''; ?>
<?php if(!isset($_POST['search_contacts_submit'])): ?>
	<?php if(empty($_GET['list'])): ?>
		<?php $list = $lists[0]; ?>
	<?php else: ?>
		<?php $list = $_GET['list']; ?>
	<?php endif; ?>
<?php else: ?>
	<?php $category = ''; ?>
<?php endif; ?>

<div class="tile-container hide-on-mobile">
    <div class="tile-sidebar standard-collapsible hide-titles-mob double-gap-top" style="overflow-y:auto;">
        <ul>
            <li class="standard-sidebar-searchbox">
                <form action="" method="POST">
                    <?php if($_POST['search_'.$category]): ?>
                        <input name="search_<?php echo $category; ?>" type="text" value="<?php echo $_POST['search_'.$category]; ?>" class="form-control search-contact-form" />
                    <?php else: ?>
                        <input name="search_<?php echo $category; ?>" type="text" value="" placeholder="Search <?php echo $category; ?>" class="form-control search-contact-form" />
                    <?php endif; ?>
                    <input type="submit" value="Filter" class="btn brand-btn" name="search_<?= $category; ?>_submit" style="display:none;" />
                </form>
            </li>
            <?php foreach($lists as $list_name) {
                //$contact_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(`contactid`) count FROM `contacts` WHERE `deleted`=0 AND `tile_name`='".FOLDER_NAME."' AND `category`='$list_name' AND `status`=1")); ?>
                <!--
                <a href="?list=<?= $list_name ?>&status=<?= $status ?>"><li class="<?= $category == $list_name ? 'active blue' : '' ?>"><b><?= $status == 'inactive' ? 'Inactive ' : ($status == 'archive' ? 'Archived ' : '') ?><?= $list_name ?></b><span class="pull-right"><?= $contact_count['count']; ?></span></li></a>
                -->
                <li class="sidebar-higher-level highest-level">
                    <a class="<?= $_GET['list']==$list_name ? 'active blue cursor-hand' : 'collapsed' ?>" onclick="$(this).closest('li').find('ul').toggle(); $(this).find('img').toggleClass('counterclockwise');"><?= $list_name ?> <span class="arrow"></span></a>
                    <ul style="<?= $_GET['list']==$list_name ? '' : 'display:none;' ?>">
                        <?php $active_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(`contactid`) `count` FROM `contacts` WHERE `deleted`=0 AND `tile_name`='".FOLDER_NAME."' AND `category`='$list_name' AND `status`=1")); ?>
                        <a href="?list=<?= $list_name; ?>&status=active">
                            <li class="<?= ($_GET['list']==$list_name && $_GET['status']=='active') ? 'active blue' : '' ?>"><b>Active</b><span class="pull-right"><?= $active_count['count']; ?></span></li>
                        </a>
                        <?php $inactive_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(`contactid`) `count` FROM `contacts` WHERE `deleted`=0 AND `tile_name`='".FOLDER_NAME."' AND `category`='$list_name' AND `status`=0")); ?>
                        <a href="?list=<?= $list_name; ?>&status=inactive">
                            <li class="<?= ($_GET['list']==$list_name && $_GET['status']=='inactive') ? 'active blue' : '' ?>"><b>Inactive</b><span class="pull-right"><?= $inactive_count['count']; ?></span></li>
                        </a>
                    </ul>
                </li>
            <?php } ?>
            <?php $con_regions = array_filter(array_unique(explode(',', get_config($dbc, '%_region', true))));
            if(count($con_regions) > 0) { ?>
                <li class="sidebar-higher-level highest-level">
                    <a class="<?= !empty($_GET['region']) ? 'active blue cursor-hand' : 'collapsed' ?>" onclick="$(this).closest('li').find('ul').toggle(); $(this).find('img').toggleClass('counterclockwise');">Regions <span class="arrow"></span></a>
                    <ul style="<?= !empty($_GET['region']) ? '' : 'display:none;' ?>">
                        <?php foreach($con_regions as $con_region): ?>
                            <?php $active_region = explode(',', $_GET['region']);
                            if(!in_array($con_region, $active_region)) {
                                $active_region[] = $con_region;
                            } else {
                                $active_region = array_diff($active_region, [$con_region]);
                            }
                            $active_region = implode(',', $active_region);
                            $region_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(`contactid`) count FROM `contacts` WHERE `deleted`=0 AND `tile_name`='".FOLDER_NAME."' AND `category`='$list' AND `region`='$con_region' AND `status`=1")); ?>
                            <a href="?list=<?php echo $list; ?>&status=<?= $status ?>&region=<?php echo $active_region; ?>">
                                <li class="<?= (strpos($_GET['region'], $con_region) === false) ? '' : 'active blue' ?>"><b><?php echo $con_region; ?></b><span class="pull-right"><?= $region_count['count']; ?></span></li>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php } ?>

            <?php $con_locations = array_filter(explode(",", mysqli_fetch_assoc(mysqli_query($dbc,"SELECT con_locations FROM field_config_contacts where con_locations is not null AND `tile_name`='".FOLDER_NAME."'"))['con_locations']));
            if(count($con_locations) > 0) { ?>
                <li class="sidebar-higher-level highest-level">
                    <a class="<?= !empty($_GET['location']) ? 'active blue cursor-hand' : 'collapsed' ?>" onclick="$(this).closest('li').find('ul').toggle(); $(this).find('img').toggleClass('counterclockwise');">Locations <span class="arrow"></span></a>
                    <ul style="<?= !empty($_GET['location']) ? '' : 'display:none;' ?>">
                        <?php foreach($con_locations as $location): ?>
                            <?php $active_location = explode(',', $_GET['location']);
                            if(!in_array($location, $active_location)) {
                                $active_location[] = $location;
                            } else {
                                $active_location = array_diff($active_location, [$location]);
                            }
                            $active_location = implode(',', $active_location);
                            $location_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(`contactid`) count FROM `contacts` WHERE `deleted`=0 AND `tile_name`='".FOLDER_NAME."' AND `category`='$list' AND `con_locations`='$location' AND `status`=1")); ?>
                            <a href="?list=<?php echo $list; ?>&location=<?php echo $active_location; ?>">
                                <li class="<?= (strpos($_GET['location'], $location) === false) ? '' : 'active blue' ?>"><b><?php echo $location; ?></b><span class="pull-right"><?= $location_count['count']; ?></span></li>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php } ?>

            <?php $con_classifications = array_filter(explode(",", get_config($dbc, FOLDER_NAME.'_classification')));
            if(count($con_classifications) > 0) { ?>
                <li class="sidebar-higher-level highest-level">
                    <a class="<?= !empty($_GET['classification']) ? 'active blue cursor-hand' : 'collapsed' ?>" onclick="$(this).closest('li').find('ul').toggle(); $(this).find('img').toggleClass('counterclockwise');">Classifications <span class="arrow"></span></a>
                    <ul style="<?= !empty($_GET['classification']) ? '' : 'display:none;' ?>">
                        <?php foreach($con_classifications as $con_classification): ?>
                            <?php $active_classification = explode(',', $_GET['classification']);
                            if(!in_array($con_classification, $active_classification)) {
                                $active_classification[] = $con_classification;
                            } else {
                                $active_classification = array_diff($active_classification, [$con_classification]);
                            }
                            $active_classification = implode(',', $active_classification);
                            $classifications_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(`contactid`) count FROM `contacts` WHERE `deleted`=0 AND `tile_name`='".FOLDER_NAME."' AND `category`='$list' AND `classification`='$con_classification' AND `status`=1")); ?>
                            <a href="?list=<?php echo $list; ?>&classification=<?php echo $active_classification; ?>">
                                <li class="<?= (strpos($_GET['classification'], $con_classification) === false) ? '' : 'active blue' ?>"><b><?php echo $con_classification; ?></b><span class="pull-right"><?= $classifications_count['count']; ?></span></li>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php } ?>

            <?php $con_titles = array_filter(explode(",", mysqli_fetch_assoc(mysqli_query($dbc,"SELECT con_title FROM field_config_contacts where con_title is not null AND `tile_name`='".FOLDER_NAME."'"))['con_title']));
            if(count($con_titles) > 0) { ?>
                <li class="sidebar-higher-level highest-level">
                    <a class="<?= !empty($_GET['title']) ? 'active blue cursor-hand' : 'collapsed' ?>" onclick="$(this).closest('li').find('ul').toggle(); $(this).find('img').toggleClass('counterclockwise');">Titles <span class="arrow"></span></a>
                    <ul style="<?= !empty($_GET['title']) ? '' : 'display:none;' ?>">
                        <?php foreach($con_titles as $con_title): ?>
                            <?php $active_title = explode(',', $_GET['title']);
                            if(!in_array($con_title, $active_title)) {
                                $active_title[] = $con_title;
                            } else {
                                $active_title = array_diff($active_title, [$con_title]);
                            }
                            $active_title = implode(',', $active_title);
                            $title_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(`contactid`) count FROM `contacts` WHERE `deleted`=0 AND `tile_name`='".FOLDER_NAME."' AND `category`='$list' AND `title`='$con_title'")); ?>
                            <a href="?list=<?php echo $list; ?>&title=<?php echo $active_title; ?>">
                                <li class="<?= (strpos($_GET['title'], $con_title) === false) ? '' : 'active blue' ?>"><b><?php echo $con_title; ?></b><span class="pull-right"><?= $title_count['count']; ?></span></li>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </div><!-- .tile-sidebar -->

    <div class='scale-to-fill has-main-screen hide-titles-mob'>
        <div class='main-screen'>
            <?php
                $category = $list;
                include('list_common.php');
            ?>
        </div>
    </div>

    <div class="clearfix"></div>
</div><!-- .tile-container -->

<?php if ( isset($_GET['category']) ) {
    $category = filter_var($_GET['category'], FILTER_SANITIZE_STRING);
}
if(!empty($_GET['search_contacts']) || !empty($_POST['search_'.$category])) { ?>
    <div class="show-on-mob">
	<?php include('list_common.php'); ?>
    </div>
<?php } else { ?>
	<div id="type_accordions" class="gap-top gap-left show-on-mob panel-group block-panels" style="width:95%;"><?php
		$counter = 1;
		foreach($lists as $list_name) { ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#type_accordions" href="#collapse_<?= $counter; ?>">
							<?= $list_name; ?><span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_<?= $counter; ?>" class="panel-collapse collapse">
					<div class="panel-body" data-file="list_common.php?list=<?=$list_name;?>&status=<?=$status;?>&category=<?=$list_name;?>&tile_name=<?=FOLDER_NAME?>">
						Loading...
					</div>
				</div>
			</div><?php
			$counter++;
		} ?>
	</div>
<?php } ?>
