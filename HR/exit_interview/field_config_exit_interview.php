<div class="form-group">
<label for="file[]" class="col-sm-4 control-label">Upload Logo
<span class="popover-examples list-inline">&nbsp;
<a  data-toggle="tooltip" data-placement="top" title="Remove Single/Double Quote from file name"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
</span>
:</label>
<div class="col-sm-8">
<?php if($pdf_logo != '') {
    echo '<a href="download/'.$pdf_logo.'" target="_blank">View</a>';
    ?>
    <input type="hidden" name="logo_file" value="<?php echo $pdf_logo; ?>" />
    <input name="pdf_logo" type="file" data-filename-placement="inside" class="form-control" />
  <?php } else { ?>
  <input name="pdf_logo" type="file" data-filename-placement="inside" class="form-control" />
  <?php } ?>
</div>
</div>

<!-- Header & Footer -->
<div class="form-group">
    <label for="office_country" class="col-sm-4 control-label">Header Info:<br><em>(Company Address, Phone, Email, etc.)</em></label>
    <div class="col-sm-8">
        <textarea name="pdf_header" rows="3" cols="50" class="form-control"><?php echo $pdf_header; ?></textarea>
    </div>
</div>
<div class="form-group">
    <label for="office_country" class="col-sm-4 control-label">Footer Info:<br><em>(Company Address, Phone, Email, etc.)</em></label>
    <div class="col-sm-8">
        <textarea name="pdf_footer" rows="3" cols="50" class="form-control"><?php echo $pdf_footer; ?></textarea>
    </div>
</div>
<!-- Header & Footer -->

<input type="checkbox" class="selecctall"/> Select All
<br><br>
<div class="field_config">

	<ul style="list-style-type: none;">

	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields1,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields1">Employee Name</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2">Date of Exit</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3">What is the main reason you decided to leave your job?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4">What did you like best about your job/working for the Company?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5">What did you like least about your job/working for the Company?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6">How did you feel about the way you were managed daily/weekly/monthly? Did you feel your manager was available when you needed them?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">How did you feel about the level of supervision and feedback you received?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8">Did you feel the expectations and demands that were placed on you were fair and manageable? Were you capable of taking on more responsibility and held back in any way?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9">How did you feel about your coworkers, the office environment, and morale? Did you feel like a valued and included member of the team?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10">What could the Company do to increase morale and employee happiness to retain its best employees?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11">According to you who was a strong leader? Did the staff feel they were approachable?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">Did you feel that the management team communicated well with the staff? Were goals and objectives to be met clearly outlined?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13">Name of the person you like best and least about as leader of the company?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14">Do you feel that the company will continue to be successful in the future? Why or why not?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15">What would you suggest we do to make the company a better place to work?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16">Would you work for the company again in the future?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields17">Do you have any other comments or insights about your time at the company?</li>
	</ul>

</div>