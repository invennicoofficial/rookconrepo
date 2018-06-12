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

	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields1,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields1">Full Name</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2">Date of Birth</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3">SIN</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4">Health Care #, Province</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5">Driver&#39;s License #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6">Email</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">Trade</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8">Address, City, Province, Postal Code</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9">Phone #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10">Hire Date</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11">Certificates/ Training</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">Allergies (including food and medications)</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13">Specific Medical Problems (Diabetes, Epilepsy, Heart Condition)</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14">Regular Prescriptions</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15">Emergency Contact</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16">Family Doctor, phone #</li>
	</ul>

</div>