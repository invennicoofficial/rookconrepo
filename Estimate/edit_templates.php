<?php include_once('../include.php');
checkAuthorised('estimate');
error_reporting(0);
if(!isset($estimate)) {
	$estimateid = filter_var($estimateid,FILTER_SANITIZE_STRING);
	$estimate = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `estimateid`='$estimateid'"));
} ?>
<script>
profile_tab = [];
var lock_timeout;
var tile_options = [];
$(document).ready(function() {
	$('[data-table]').change(saveField);
	$('#no-more-tables').sortable({
		handle: '.scope-handle',
		items: '.sort_table',
		update: save_sort
	});
	$('#no-more-tables').sortable({
		handle: '.heading-handle',
		items: 'table',
		update: save_sort
	});
	$('.sort_table').sortable({
		connectWith: '#no-more-tables',
		handle: '.line-handle',
		items: 'tr',
		update: save_sort
	});
	$('[name=src_id]').each(function() { fill_selects($(this).closest('tr')); });
});
function save_sort() {
	set_headings();
	var i = 0;
	$('[name=sort_order]').each(function() {
		$(this).val(i++).change();
	});
}
function set_headings() {
	$('[name=heading][data-init]').each(function() {
		$(this).closest('table').find('[name=heading][data-table]').val(this.value).change();
	});
}
function set_scopes() {
	$('[name=scope_name][data-init]').each(function() {
		$(this).closest('.sort_table').find('[name=scope_name][data-table]').val(this.value).change();
	});
}
function getLock(tab_name) {
	clearTimeout(lock_timeout);
	$.ajax({
		method: 'POST',
		url: 'estimates_ajax.php?action=table_locks',
		data: {
			contactid: '<?= $_GET['edit'] ?>',
			section: tab_name,
			session_id: '<?= $_SESSION['contactid'] ?>'
		},
		reponse: 'html',
		success: function(response) {
			var locked_tabs = [];
			if(response != '#*#') {
				locked_tabs = split(',',split('#*#',response)[0]);
				console.log(split('#*#',response)[1]);
			}
			$('div[data-tab-name]').data('locked','');
			tab_name.forEach(function(tab) {
				if(!locked_tabs.includes(tab)) {
					console.log(tab+' has been locked for editing.');
					$('div[data-tab-name="'+tab+'"]').data('locked','held');
				}
			});
			lock_timeout = setTimeout(function() { releaseLock(tab_name); }, 600000);
			lockTabs();
		}
	});
}
function releaseLock(tab_name) {
	tab_name.forEach(function(tab) {
		console.log(tab+' has been released.');
		$('div[data-tab-name="'+tab+'"]').data('locked','');
		lockTabs();
		$.ajax({
			method: 'POST',
			url: 'estimates_ajax.php?action=unlock_table',
			data: {
				contactid: '<?= $_GET['edit'] ?>',
				section: tab,
				session_id: '<?= $_SESSION['contactid'] ?>'
			}
		});
	});
}
function lockTabs() {
	$('.active.blue').removeClass('active').removeClass('blue');
	$('div[data-tab-name]').each(function() {
		if($(this).data('locked') != 'held') {
			$(this).find('input,select,a').off('click').click(function() { this.blur(); return false; }).off('keyup').keyup(function() { this.blur(); return false; }).off('keypress').keypress(function() { this.blur(); return false; });
		} else {
			$(this).find('input,select,a').off('click').off('keyup').off('keypress');
			$('a[href$="status='+$(this).data('tab-name')+'"] li').addClass('active blue');
		}
	});
	if($('#view_profile').is(':visible')) {
		$('[href=#view_profile] li').addClass('active blue');
	}
}
function add_heading(scope) {
	$.ajax({
		url: 'estimates_ajax.php?action=estimate_add_heading',
		method: 'POST',
		data: {
			estimate: '<?= $estimateid ?>',
			scope: scope
		},
		success: function(response) {
			window.location.replace('?edit=<?= $estimateid ?>&status=templates');
		}
	});
}
function rem_heading(img) {
	var block = $(img).closest('table');
	block.find('[name=deleted]').val(1).change();
	block.hide();
}
function add_scope(scope) {
	$.ajax({
		url: 'estimates_ajax.php?action=estimate_add_scope',
		method: 'POST',
		data: {
			estimate: '<?= $estimateid ?>'
		},
		success: function(response) {
			window.location.replace('?edit=<?= $estimateid ?>&status=templates');
		}
	});
}
function rem_scope(img) {
	var block = $(img).closest('.sort_table');
	block.find('[name=deleted]').val(1).change();
	block.hide();
}
function saveFieldMethod() {
	if(this.value == 'CUSTOM') {
		$(this).closest('.form-group').find('div[class^=col-sm-]').show();
		$(this).closest('.col-sm-12').hide();
		$(this).closest('.form-group').find('input').focus();
		doneSaving();
	} else if($(this).is('[data-table]')) {
		var result = this.value;
		var name = this.name;
		if(name.substr(-2) == '[]') {
			result = [];
			$('[name="'+name+'"]').each(function() {
				result.push(this.value);
			});
		}
		$.ajax({
			url: 'estimates_ajax.php?action=estimate_fields',
			method: 'POST',
			dataType: 'html',
			data: {
				id: $(this).data('id'),
				id_field: $(this).data('id-field'),
				table: $(this).data('table'),
				field: name.replace('[]',''),
				value: result,
				estimate: $(this).data('estimate')
			},
			success: function(response) {
				console.log(response);
				if(name == 'quote_multiple') {
					window.location.replace('?edit=<?= $estimateid ?>&tab=scope&rate=<?= $current_rate ?>&status=quote');
				}
				doneSaving();
			}
		});
	}
}
function remove_line(img) {
	$(img).val(1).change();
	$(img).closest('tr').hide();
}
function add_line() {
	overlayIFrameSlider('estimate_scope_edit.php?estimateid=<?= $estimateid ?>&rate=<?= $_GET['rate'] ?>','75%');
}
</script>
<div class="form-horizontal col-sm-12" data-tab-name="templates">
	<h3 class="pull-left">Scope Templates</h3>
	<!--<button class="btn brand-btn" onclick="overlayIFrameSlider('estimate_scope_edit.php?estimateid=<?= $estimateid ?>&src=true', '75%', true, true, 'auto', true); return false;">Load Scope Details</button>-->
	<div class="pull-right gap-top"><a href="" onclick="overlayIFrameSlider('estimate_scope_edit.php?estimateid=<?= $estimateid ?>&src=true', '75%', true, true, 'auto', true); return false;"><img class="inline-img" src="../img/icons/ROOK-add-icon.png"></a></div>
	<div class="clearfix"></div>
    
    <div class="scale-to-fill has-main-screen hide-titles-mob"><?php
        include('../Rate Card/line_types.php');
        $estimateid = filter_var($_GET['edit'],FILTER_SANITIZE_STRING);
        $estimate = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `estimateid`='$estimateid'"));
        $config = explode(',',mysqli_fetch_array(mysqli_query($dbc,"SELECT `config_fields` FROM `field_config_estimate`"))[0]);

        $rates = [];
        $query = mysqli_query($dbc, "SELECT `rate_card` FROM `estimate_scope` WHERE `estimateid`='$estimateid' GROUP BY `rate_card`");
        if(mysqli_num_rows($query) > 0) {
            while($row = mysqli_fetch_array($query)) {
                $rates[bin2hex($row[0])] = explode(':',$row[0]);
            }
        } else {
            $rates[''] = '';
        }
        $current_rate = (!empty($_GET['rate']) ? $_GET['rate'] : key($rates));
        $_GET['rate'] = $current_rate;
        
        $scope_list = [];
        $query = mysqli_query($dbc, "SELECT `scope_name` FROM `estimate_scope` WHERE `estimateid`='$estimateid' AND `deleted`=0 GROUP BY `scope_name` ORDER BY MIN(`sort_order`)");
        $scope_name = '';
        if(mysqli_num_rows($query) > 0) {
            while($row = mysqli_fetch_array($query)) {
                $scope_list[preg_replace('/[^a-z0-9]*/','',strtolower($row[0]))] = $row[0];
            }
        } else {
            $scope_list['scope'] = 'Scope';
        } ?>
        
        <!--<div class="standard-body-title"><h3>Estimate Scope <a href="estimate_scope_edit.php?estimateid=<?= $estimateid ?>&scope=<?= $_GET['status'] ?>" onclick="overlayIFrameSlider(this.href, '75%', true, false, 'auto', true); return false;"><img class="inline-img smaller" src="../img/icons/ROOK-edit-icon.png"></a></h3></div>-->
        <div id="no-more-tables"><?php
            foreach($scope_list as $scope_id => $scope) {
                include('edit_headings.php');
            } ?>
        </div>
        <?php //include('edit_summary.php'); ?>
        
    </div>
    
    <hr />
</div>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'edit_templates.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>