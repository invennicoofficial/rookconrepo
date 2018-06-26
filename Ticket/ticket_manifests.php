<?php $siteid = filter_var($_GET['site'],FILTER_SANITIZE_STRING);
$manifest_fields = explode(',',get_config($dbc, 'ticket_manifest_fields'));
$ticket_filter = '';
if(in_array_starts('type ',$manifest_fields)) {
	$type_filters = [];
	foreach($manifest_fields as $config_field) {
		$config_field = explode(' ',$config_field);
		if($config_field[0] == 'type' && count($config_field) == 2) {
			$type_filters[] = $config_field[1];
		}
	}
	$ticket_filter = " AND `tickets`.`ticket_type` IN ('".implode("','",$type_filters)."')";
}
if($siteid == 'recent') {
	if($_GET['siteid'] > 0) {
		$manifest_filter = "AND `siteid`='".$_GET['siteid']."'";
	}
	$manifest_list = $dbc->query("SELECT `id`, `siteid`, `date`, `revision` FROM `ticket_manifests` WHERE `deleted`=0 $manifest_filter ORDER BY `id` DESC LIMIT 0,".$recent_manifests);
	if($manifest_list->num_rows > 0) { ?>
		<div id="no-more-tables">
			<table class="table table-bordered">
				<tr class="hidden-sm hidden-xs">
					<th>Date</th>
					<th>ID</th>
					<th>Site</th>
					<th>Manifest</th>
					<?php if(in_array('edit',$manifest_fields) && $tile_security['edit'] > 0) { ?><th>Function</th><?php } ?>
				</tr>
				<?php while($manifest = $manifest_list->fetch_assoc()) { ?>
					<tr>
						<td data-title="Date"><?= $manifest['date'] ?></td>
						<td data-title="ID"><?= date('y',strtotime($manifest['date'])).'-'.str_pad($manifest['id'],4,0,STR_PAD_LEFT) ?></td>
						<td data-title="Site"><?= get_contact($dbc, $manifest['siteid']) ?></td>
						<td data-title="Manifest"><?php if(file_exists('manifest/manifest_'.$manifest['id'].($manifest['revision'] > 1 ? '_'.$manifest['revision'] : '').'.pdf')) { ?><a target="_blank" href="manifest/manifest_<?= $manifest['id'] ?>.pdf">PDF <img class="inline-img" src="../img/pdf.png"></a><?php } ?></td>
						<?php if(in_array('edit',$manifest_fields) && $tile_security['edit'] > 0) { ?><td data-title="Function"><a href="?tile_name=<?= $_GET['tile_name'] ?>&tab=manifest&manifestid=<?= $manifest['id'] ?>" onclick="overlayIFrameSlider(this.href); return false;">Edit <?= $manifest['revision'] > 1 ? '(Revision '.$manifest['revision'].')' : '' ?></a></td><?php } ?>
					</tr>
				<?php } ?>
			</table>
		</div>
	<?php } else {
		echo '<h3>No Manifests Found</h3>';
	}
} else {
	if(isset($_POST['generate']) || isset($_POST['build_blank'])) {
		include_once('../tcpdf/tcpdf.php');
		DEFINE('TICKET_FOOTER',get_config($dbc, 'ticket_pdf_footer'));
		if(empty($_POST['build_blank'])) {
			$line_items = filter_var(implode(',',$_POST['include']),FILTER_SANITIZE_STRING);
			$manual_qty = [];
			foreach($_POST['include'] as $line_id) {
				foreach($_POST['line_rows'] as $i => $line_row) {
					if($line_row == $line_id) {
						$manual_qty[] = round($_POST['qty'][$i],3);
					}
				}
			}
			$qtys = implode(',',$manual_qty);
			$signature = filter_var($_POST['signature'],FILTER_SANITIZE_STRING);
			$dbc->query("INSERT INTO `ticket_manifests` (`date`,`line_items`,`qty`,`siteid`,`contactid`,`signature`,`history`) VALUES ('".date('Y-m-d')."','".$line_items."','".$qtys."','$siteid','".$_SESSION['contactid']."','$signature','Manifest created by ".get_contact($dbc, $_SESSION['contactid'])."')");
		} else {
			$_POST['include'] = [0,0,0,0,0,0,0];
			$dbc->query("INSERT INTO `ticket_manifests` (`date`,`line_items`,`siteid`,`contactid`,`signature`,`history`) VALUES ('".date('Y-m-d')."','','$siteid','".$_SESSION['contactid']."','','Blank Manifest created by ".get_contact($dbc, $_SESSION['contactid'])."')");
		}
		$manifestid = $dbc->insert_id;
		if(!empty($signature)) {
			include_once('../phpsign/signature-to-image.php');
			$signature = sigJsonToImage(html_entity_decode($signature));
			imagepng($signature, 'manifest/signature_'.$manifestid.'.png');
		}
		if(!file_exists('manifest')) {
			mkdir('manifest', 0777, true);
		}
		$manifest_ref = date('y').'-'.str_pad($manifestid,4,0,STR_PAD_LEFT);
		$manifest_date = strtoupper(date('F d/y'));
		$manifest_label = ($siteid > 0 ? strtoupper(get_contact($dbc, $siteid)) : 'UNASSIGNED');
		$logo = get_config($dbc, 'ticket_pdf_logo');
		$row_colour_1 = get_config($dbc, 'report_row_colour_1');
		$row_colour_2 = get_config($dbc, 'report_row_colour_2');
		$col_count = (in_array('file',$manifest_fields) ? 1 : 0) + (in_array('po',$manifest_fields) ? 1 : 0) + (in_array('vendor',$manifest_fields) ? 1 : 0) + (in_array('line',$manifest_fields) ? 1 : 0) + (in_array('qty',$manifest_fields) ? 1 : 0) + (in_array('manual qty',$manifest_fields) ? 1 : 0) + (in_array('site',$manifest_fields) ? 1 : 0) + (in_array('notes',$manifest_fields) ? 1 : 0);
		$html = '<table style="width:100%;border:none;">
			<tr>
				'.(file_exists('download/'.$logo) ? '<td style="width: 120px;"><img src="download/'.$logo.'" style="margin-right:20px;margin-bottom:20px;width:100px;"><br />&nbsp;</td>' : '').'
				<td><br /><br /><b><i><u>'.$manifest_label.'</b></i></u><br /><br /><b>Date:</b><br /><b>File Ref:</b></td>
				<td><br /><br /><br /><br /><b>'.$manifest_date.'</b><br /><b>'.$manifest_ref.'</b></td>
			</tr>
		</table>
		<br />
		<table style="width:100%;">
			<tr>
				'.(in_array('file',$manifest_fields) ? '<th style="border:1px solid black; text-align:center;">FILE #</th>' : '').'
				'.(in_array('po',$manifest_fields) ? '<th style="border:1px solid black; text-align:center;">PO</th>' : '').'
				'.(in_array('vendor',$manifest_fields) ? '<th style="border:1px solid black; text-align:center;">VENDOR / SHIPPER</th>' : '').'
				'.(in_array('line',$manifest_fields) ? '<th style="border:1px solid black; text-align:center;">LINE ITEM #</th>' : '').'
				'.(in_array('qty',$manifest_fields) ? '<th style="border:1px solid black; text-align:center;">LAND TRAN PIECE COUNT</th>' : '').'
				'.(in_array('manual qty',$manifest_fields) ? '<th style="border:1px solid black; text-align:center;">LAND TRAN PIECE COUNT</th>' : '').'
				'.(in_array('site',$manifest_fields) ? '<th style="border:1px solid black; text-align:center;">SITE</th>' : '').'
				'.(in_array('notes',$manifest_fields) ? '<th style="border:1px solid black; text-align:center;">NOTES</th>' : '').'
			</tr>
			<tr style="background-color:'.$row_colour_1.'"><td style="font-size:5px;" colspan="'.$col_count.'">&nbsp;</td></tr>';
			$site_notes = '';
			if($siteid > 0) {
				$site_notes = html_entity_decode($dbc->query("SELECT `notes` FROM `contacts_description` WHERE `contactid`='$siteid'")->fetch_assoc()['notes']);
			}
			foreach($_POST['include'] as $i => $line_id) {
				if($line_id > 0) {
					$row = $dbc->query("SELECT `tickets`.`ticket_label`,`ticket_attached`.`po_num`,MAX(`origin`.`vendor`) `vendor`,`ticket_attached`.`po_line`,`ticket_attached`.`notes`,`inventory`.`inventoryid`,IFNULL(`inventory`.`quantity`,`ticket_attached`.`qty`) `qty`,IFNULL(`ticket_attached`.`siteid`,`tickets`.`siteid`) `siteid` FROM `ticket_attached` LEFT JOIN `inventory` ON `ticket_attached`.`src_table`='inventory' AND `ticket_attached`.`item_id`=`inventory`.`inventoryid` LEFT JOIN `tickets` ON `ticket_attached`.`ticketid`=`tickets`.`ticketid` LEFT JOIN `ticket_schedule` `origin` ON `tickets`.`ticketid`=`origin`.`ticketid` AND `origin`.`type`='origin' AND `ticket_schedule`.`deleted`=0 WHERE `ticket_attached`.`id`='$line_id'")->fetch_assoc();
					if(!empty($manual_qty[$i]) && $row['inventoryid'] > 0) {
						$old_qty = $row['qty'];
						$new_qty = $old_qty - $manual_qty[$i];
						$dbc->query("UPDATE `inventory` SET `quantity`='$new_qty' WHERE `inventoryid`='{$row['inventoryid']}'");
						$dbc->query("INSERT INTO `inventory_change_log` (`inventoryid`,`contactid`,`old_inventory`,`changed_quantity`,`new_inventory`,`date_time`,`location_of_change`,`change_comment`) VALUES ('{$row['inventoryid']}','{$_SESSION['contactid']}','$old_qty','{$manual_qty[$i]}','$new_qty','".date('Y-m-d h:i:s')."','{$manual_qty[$i]} assigned to Manifest $manifestid')");
					} else {
						$new_qty = $manual_qty[$i] > 0 ? $manual_qty[$i] : 1;
						$dbc->query("UPDATE `ticket_attached` SET `used`=`used`+'$new_qty' WHERE `id`='$line_id'");
					}
				} else {
					$row = ['qty'=>'','siteid'=>$siteid];
				}
				$html .= '<tr style="background-color:'.($i % 2 == 0 ? $row_colour_1 : $row_colour_2).'">
					'.(in_array('file',$manifest_fields) ? '<td data-title="FILE #" style="text-align:center;">'.$row['ticket_label'].'</td>' : '').'
					'.(in_array('po',$manifest_fields) ? '<td data-title="PO" style="text-align:center;">'.$row['po_num'].'</td>' : '').'
					'.(in_array('vendor',$manifest_fields) ? '<td data-title="VENDOR / SHIPPER" style="text-align:center;">'.get_contact($dbc, $row['vendor'],'name_company').'</td>' : '').'
					'.(in_array('line',$manifest_fields) ? '<td data-title="LINE ITEM #" style="text-align:center;">'.(empty($ticket['po_line']) ? 'N/A' : $ticket['po_line']).'</td>' : '').'
					'.(in_array('qty',$manifest_fields) ? '<td data-title="LAND TRAN PIECE COUNT" style="text-align:center;">'.($row['qty'] > 0 ? round($row['qty'],3) : '').'</td>' : '').'
					'.(in_array('manual qty',$manifest_fields) ? '<td data-title="LAND TRAN PIECE COUNT" style="text-align:center;">'.$manual_qty[$i].'</td>' : '').'
					'.(in_array('site',$manifest_fields) ? '<td data-title="SITE" style="text-align:center;">'.($row['siteid'] == $siteid ? $manifest_label : ($row['siteid'] > 0 ? get_contact($dbc, $row['siteid']) : 'UNASSIGNED')).'</td>' : '').'
					'.(in_array('notes',$manifest_fields) ? '<td data-title="NOTES" style="text-align:center;">'.$row['notes'].'</td>' : '').'
				</tr>
				<tr style="background-color:'.($i % 2 == 0 ? $row_colour_1 : $row_colour_2).'"><td style="font-size:5px;" colspan="'.$col_count.'">&nbsp;</td></tr>';
			}
			$html .= '<tr>
				<td style="border-top:1px solid black; text-align:right;" colspan="'.$col_count.'">
					<br /><br /><br />
					'.(empty($signature) ? '<br /><br /><br /><br /></td></tr><tr><td colspan="'.($col_count - 2).'"></td><td colspan="2" style="border-top:1px solid black;text-align:right;">Signature' : ('<img style="width:150px;border-bottom:1px solider black;" src="manifest/signature_'.$manifestid.'.png"><br />
					Signed: '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']))).'
				</td>
			</tr>
		</table>';

		class MYPDF extends TCPDF {
			public function Header() {
			}
			public function Footer() {
				// Position at 15 mm from bottom
				// $this->SetY(-15);
				// $this->SetFont('helvetica', '', 9);
				// $footer_text = '<p style="text-align:right;">Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages().'</p>';
				// $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "R", true);
				$this->SetY(-25);
				$this->SetFont('helvetica', '', 9);
				$this->writeHTMLCell(0, 0, '', '', html_entity_decode(TICKET_FOOTER), 0, 0, false, "C", true);
			}
		}
		$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
		$pdf->setFooterData(array(0,64,0), array(0,64,128));

		$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->AddPage();
		$pdf->SetFont('helvetica', '', 9);
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output('manifest/manifest_'.$manifestid.'.pdf', 'F');
		echo "<script>
		window.open('manifest/manifest_".$manifestid.".pdf');
		window.location.replace('?tile_name=".$_GET['tile_name']."&tab=manifest&site=recent');
		</script>";
		// echo $html;
	} ?>
	<script>
	$(document).ready(function() {
		$('select[name=siteid],input[name=notes]').change(saveField);
	});
	function saveFieldMethod(field) {
		var value = '';
		if(field.name == 'siteid') {
			value_list = [];
			$(field).find('option:selected').each(function() {
				value_list.push(this.value);
			});
			value = value_list.join(',');
		} else {
			value = field.value;
		}
		$.ajax({
			url: 'ticket_ajax_all.php?action=update_fields',
			method: 'POST',
			data: {
				table: $(field).data('table'),
				field: field.name,
				value: value,
				id: $(field).data('id'),
				id_field: $(field).data('id-field'),
				ticketid: $(field).data('id')
			},
			success: function(response) {
				doneSaving();
			}
		});
	}
	</script>
	<?php $site_notes = '';
	$rowsPerPage = $_GET['pagerows'] > 0 ? $_GET['pagerows'] : 25;
	$offset = ($_GET['page'] > 0 ? $_GET['page'] - 1 : 0) * $rowsPerPage;
	$filter_inv = in_array('hide qty',$manifest_fields) ? 'AND IFNULL(`inventory`.`quantity`,`ticket_attached`.`qty`-`ticket_attached`.`used`) > 0' : '';
	$filter_proj = in_array('sort_project',$manifest_fields) && !empty($_GET['type']) ? "AND `tickets`.`projectid` IN (SELECT `projectid` FROM `project` WHERE `projecttype`='".filter_var($_GET['type'],FILTER_SANITIZE_STRING)."')" : '';
	$ticket_sql = "SELECT `tickets`.`ticketid`, `tickets`.`ticket_label`, IFNULL(IFNULL(`ticket_attached`.`siteid`,`piece`.`siteid`),`tickets`.`siteid`) `siteid`, `ticket_attached`.`id`, `ticket_attached`.`notes`, IFNULL(`inventory`.`quantity`,`ticket_attached`.`qty`) `qty`, `ticket_attached`.`po_num`, `ticket_attached`.`po_line`, MAX(`ticket_schedule`.`vendor`) `vendor` FROM `tickets` LEFT JOIN `ticket_attached` ON `tickets`.`ticketid`=`ticket_attached`.`ticketid` LEFT JOIN `inventory` ON `ticket_attached`.`item_id`=`inventory`.`inventoryid` AND `ticket_attached`.`src_table`='inventory' LEFT JOIN `ticket_attached` `piece` ON `ticket_attached`.`line_id`=`piece`.`id` LEFT JOIN `ticket_schedule` ON `tickets`.`ticketid`=`ticket_schedule`.`ticketid` AND `ticket_schedule`.`type`='origin' AND `ticket_schedule`.`deleted`=0 WHERE `tickets`.`deleted`=0 AND `ticket_attached`.`deleted`=0 AND `tickets`.`status` != 'Archive' AND `ticket_attached`.`src_table` IN ('inventory','inventory_general') AND CONCAT(',',IFNULL(NULLIF(IFNULL(IFNULL(`ticket_attached`.`siteid`,`piece`.`siteid`),`tickets`.`siteid`),0),'na'),',top_25,') LIKE '%,$siteid,%' $filter_inv $ticket_filter $filter_proj GROUP BY `ticket_attached`.`id` ORDER BY ".(in_array('ticket_sort',$manifest_fields) ? "`tickets`.`ticketid` DESC," : '')." LPAD(`ticket_attached`.`po_num`,100,0), LPAD(`ticket_attached`.`po_line`,100,0), `tickets`.`ticketid`, `ticket_attached`.`id`";
	$ticket_count = "SELECT COUNT(DISTINCT `ticket_attached`.`id`) numrows FROM `tickets` LEFT JOIN `ticket_attached` ON `tickets`.`ticketid`=`ticket_attached`.`ticketid` LEFT JOIN `inventory` ON `ticket_attached`.`item_id`=`inventory`.`inventoryid` AND `ticket_attached`.`src_table`='inventory' LEFT JOIN `ticket_attached` `piece` ON `ticket_attached`.`line_id`=`piece`.`id` LEFT JOIN `ticket_schedule` ON `tickets`.`ticketid`=`ticket_schedule`.`ticketid` AND `ticket_schedule`.`type`='origin' AND `ticket_schedule`.`deleted`=0 WHERE `tickets`.`deleted`=0 AND `ticket_attached`.`deleted`=0 AND `tickets`.`status` != 'Archive' AND `ticket_attached`.`src_table` IN ('inventory','inventory_general') AND CONCAT(',',IFNULL(NULLIF(IFNULL(IFNULL(`ticket_attached`.`siteid`,`piece`.`siteid`),`tickets`.`siteid`),0),'na'),',') LIKE '%,$siteid,%' $filter_inv $ticket_filter";
	if($siteid > 0) {
		$site_notes = html_entity_decode($dbc->query("SELECT `notes` FROM `contacts_description` WHERE `contactid`='$siteid'")->fetch_assoc()['notes']);
		$ticket_sql .= " LIMIT $offset, $rowsPerPage";
	} else if($siteid == 'top_25') {
		$ticket_sql .= ' LIMIT 0,'.$recent_inventory;
	} else {
		$ticket_sql .= " LIMIT $offset, $rowsPerPage";
	}
	$ticket_list = $dbc->query($ticket_sql);
	if($ticket_list->num_rows > 0) {
		$site_list = sort_contacts_query($dbc->query("SELECT `contactid`,`site_name`,`display_name` FROM `contacts` WHERE `category`='".SITES_CAT."' AND `deleted`=0 AND `status` > 0")); ?>
		<form class="form-horizontal" action="" method="POST">
			<?php if(!in_array('req site',$manifest_fields) || $siteid > 0) { ?>
				<button class="btn brand-btn pull-right" name="generate" value="generate" type="submit">Generate Manifest</button>
			<?php } ?>
			<button class="btn brand-btn pull-right" type="submit" name="build_blank" value="build_blank">Print Blank Manifest</button>
			<?php if($siteid != 'top_25') { display_pagination($dbc, $ticket_count, $_GET['page'], ($_GET['pagerows'] > 0 ? $_GET['pagerows'] : $rowsPerPage), true, 25); } ?>
			<table class="table table-bordered">
				<tr>
					<?php if(in_array('file',$manifest_fields)) { ?><th><?= TICKET_NOUN ?></th><?php } ?>
					<th><?= SITES_CAT ?></th>
					<?php if(in_array('po',$manifest_fields)) { ?><th>PO</th><?php } ?>
					<?php if(in_array('line',$manifest_fields)) { ?><th>Line Item</th><?php } ?>
					<?php if(in_array('vendor',$manifest_fields)) { ?><th>Vendor / Shipper</th><?php } ?>
					<?php if(in_array('manual qty',$manifest_fields)) { ?><th>Qty</th><?php } ?>
					<?php if(in_array('notes',$manifest_fields)) { ?><th>Notes</th><?php } ?>
					<?php if(!in_array('req site',$manifest_fields) || $siteid > 0) { ?><th>Add <button class="btn brand-btn pull-right" onclick="$('input[type=checkbox]').prop('checked',true); return false;">Select All<br />(from current page)</button></th><?php } ?>
				</tr>
				<?php while($ticket = $ticket_list->fetch_assoc()) { ?>
					<tr>
						<?php if(in_array('file',$manifest_fields)) { ?><td data-title="<?= TICKET_NOUN ?>"><?php if($tile_security['edit'] > 0) { ?><a href="index.php?edit=<?= $ticket['ticketid'] ?>" onclick="overlayIFrameSlider(this.href+'&calendar_view=true','auto',true,true); return false;"><?= get_ticket_label($dbc, $ticket) ?></a><?php } else { echo get_ticket_label($dbc, $ticket); } ?></td><?php } ?>
						<td data-title="<?= SITES_CAT ?>"><select name="siteid" multiple data-table="ticket_attached" data-id="<?= $ticket['id'] ?>" data-id-field="id" class="chosen-select-deselect" data-placeholder="Select <?= SITES_CAT ?>"><option />
							<?php foreach($site_list as $site) { ?>
								<option <?= in_array($site['contactid'],explode(',',$ticket['siteid'])) ? 'selected' : '' ?> value="<?= $site['contactid'] ?>"><?= $site['full_name'] ?></option>
							<?php } ?>
						</select></td>
						<?php if(in_array('po',$manifest_fields)) { ?><td data-title="PO"><a href="line_item_views.php?po=<?= $ticket['po_num'] ?>" onclick="overlayIFrameSlider(this.href,'auto',true,true); return false;"><?= $ticket['po_num'] ?></a></td><?php } ?>
						<?php if(in_array('line',$manifest_fields)) { ?><td data-title="Line Item"><?= empty($ticket['po_line']) ? 'N/A' : $ticket['po_line'] ?></td><?php } ?>
						<?php if(in_array('vendor',$manifest_fields)) { ?><td data-title="Vendor / Shipper"><?= $ticket['vendor'] > 0 ? '<a href="../Contacts/contacts_inbox.php?fields=all_fields&edit='.$ticket['vendor'].'" onclick="overlayIFrameSlider(this.href,\'auto\',true,true); return false;">'.get_contact($dbc, $ticket['vendor'], 'name_company').'</a>' : '<a href="?edit='.$ticket['ticketid'].'" onclick="overlayIFrameSlider(\'edit_ticket_tab.php?ticketid='.$ticket['ticketid'].'&tab=ticket_transport_origin\',\'auto\',true); return false;"><img src="../img/icons/ROOK-add-icon.png" class="inline-img"></a>' ?></td><?php } ?>
						<?php if(in_array('manual qty',$manifest_fields)) { ?><td data-title="Qty"><input type="number" placeholder="Available: <?= round($ticket['qty'],3) ?>" name="qty[]" class="form-control" min="0" max="<?= $ticket['qty'] ?>" value="<?= in_array('max qty', $manifest_fields) ? round($ticket['qty'],3) : '' ?>"></td><?php } ?>
						<?php if(in_array('notes',$manifest_fields)) { ?><td data-title="Notes"><?= $site_notes ?><input type="text" name="notes" data-table="ticket_attached" data-id="<?= $ticket['id'] ?>" data-id-field="id" class="form-control" value="<?= $ticket['notes'] ?>"></td><?php } ?>
						<?php if(!in_array('req site',$manifest_fields) || $siteid > 0) { ?><td data-title="Add">
							<label class="form-checkbox any-width"><input type="checkbox" name="include[]" value="<?= $ticket['id'] ?>">Include</label>
							<input type="hidden" name="line_rows[]" value="<?= $ticket['id'] ?>">
						</td><?php } ?>
					</tr>
				<?php } ?>
			</table>
			<?php if($siteid != 'top_25') { display_pagination($dbc, $ticket_count, $_GET['page'], ($_GET['pagerows'] > 0 ? $_GET['pagerows'] : $rowsPerPage), true, 25); } ?>
			<div class="form-group">
				<label class="col-sm-4">Signature:</label>
				<div class="col-sm-8">
					<?php $output_name = 'signature';
					include_once('../phpsign/sign_multiple.php'); ?>
				</div>
			</div>
			<?php if(!in_array('req site',$manifest_fields) || $siteid > 0) { ?>
				<button class="btn brand-btn pull-right" name="generate" value="generate" type="submit">Generate Manifest</button>
			<?php } ?>
		</form>
	<?php } else {
		echo '<h3>No '.TICKET_TILE.' Found</h3>';
	}
} ?>
