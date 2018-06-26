<script>
$(document).ready(function() {
	//Position
    $('.staff_pos .delete').last().hide();
    $('#add_row_pos').on( 'click', function () {
		$('.staff_pos .delete').show();
		destroyInputs($('#collapse_staffpos'));
		
		var clone = $('.staff_pos').last().clone();
		clone.find('select,input').val('');
		$('.staff_pos').last().after(clone);

		initInputs('#collapse_staffpos');
		$('.staff_pos .delete').show().last().hide();
        return false;
    });
});
function remRow(btn) {
	$(btn).closest('.staff_pos').remove();
	$('.staff_pos .delete').show().last().hide();
}
</script>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <label class="col-sm-3 text-center">Position</label>
            <label class="col-sm-1 text-center">UoM</label>
            <label class="col-sm-1 text-center">Rate Card Price</label>
        </div>

		<?php $positions = explode('**',$staff_position);
		$position_list = $dbc->query("SELECT `position_id`,`name` FROM `positions` WHERE `deleted`=0 ORDER BY `name`")->fetch_all(MYSQLI_ASSOC);
		foreach(explode('**',$staff_position) as $position) {
			$position = explode('#',$position); ?>
			<div class="form-group clearfix staff_pos">
				<div class="col-sm-3"><label class="show-on-mob">Position:</label>
					<select data-placeholder="Select Position" class="chosen-select-deselect" name="staff_pos[]"><option />
						<?php foreach($position_list as $position_row) { ?>
							<option <?= $position[0] == $position_row['position_id'] ? 'selected' : '' ?> value="<?= $position_row['position_id'] ?>"><?= $position_row['name'] ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-1"><label class="show-on-mob">Unit of Measure:</label>
					<select data-placeholder="Select Position" class="chosen-select-deselect" name="staff_pos_unit[]"><option />
						<option <?= $position[2] > 0 ? '' : 'selected' ?> value="Hourly">Hourly</option>
						<option <?= $position[2] > 0 ? 'selected' : '' ?> value="Daily">Daily</option>
					</select>
				</div>
				<div class="col-sm-1"><label class="show-on-mob">Rate:</label>
					<input type="number" min=0 class="form-control" name="staff_pos_rate[]" value="<?= $position[2] > 0 ? $position[2] : $position[1] ?>">
				</div>
				<div class="col-sm-1"><button class="delete btn brand-btn" onclick="remRow(this); return false;">Delete</button></div>
			</div>
		<?php } ?>
        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_pos" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>