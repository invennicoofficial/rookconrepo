<div class="form-group">
<label for="file[]" class="col-sm-4 control-label">Upload Logo
<span class="popover-examples list-inline">&nbsp;
<a href="#job_file" data-toggle="tooltip" data-placement="top" title="Remove Single/Double Quote from file name"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
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
    <label for="office_country" class="col-sm-4 control-label">Header Info:<br><em>(Ex: Company Address, Phone, Email etc)</em></label>
    <div class="col-sm-8">
        <textarea name="pdf_header" rows="3" cols="50" class="form-control"><?php echo $pdf_header; ?></textarea>
    </div>
</div>
<div class="form-group">
    <label for="office_country" class="col-sm-4 control-label">Footer Info:<br><em>(Ex: Company name, Address, Phone etc)</em></label>
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
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2">Home phone</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3">Cell Phone</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4">Address</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5">D.O.B.</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6">Vehicle Make</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">Model</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8">Year</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9">Color</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10">Driver's License #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11">License Plate #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">Province</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13">VIN #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14">Damage Estimate</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15">Description of Damage</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16">Insurance Company</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields17">Policy #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields18,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields18">Expiry Date</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields19,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields19">Occurrence Date (dd/mm/yy)</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields20,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields20">Time</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields21,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields21">Date Reported (dd/mm/yy)</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields22,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields22">Road Conditions</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields23,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields23">Weather Conditions</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields24,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields24">Location of incident</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields25,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields25">Police/RCMP Collision Report #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields26,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields26">Collision involved</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields27,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields27">Any injuries as a result?</li>

	</ul>

<p>Other Driver</p>

	<ul style="list-style-type: none;">

	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields28,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields28">Name</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields29,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields29">Home phone</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields30,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields30">Cell Phone</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields31,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields31">Address</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields32,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields32">D.O.B. (dd/mm/yyyy)</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields33,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields33">Vehicle Make</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields34,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields34">Model</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields35,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields35">Year</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields36,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields36">Color</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields37,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields37">Driver's License #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields38,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields38">License Plate #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields39,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields39">Province</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields40,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields40">VIN #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields41,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields41">Damage Estimate</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields42,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields42">Description of Damage</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields43,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields43">Insurance Company</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields44,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields44">Policy #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields45,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields45">Expiry Date</li>

	</ul>

<p>Witnesses</p>

	<ul style="list-style-type: none;">

	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields46,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields46">Name</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields47,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields47">Address</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields48,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields48">Phone</li>

	</ul>

	<ul style="list-style-type: none;">
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields47,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields47">Description of events leading up to accident</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields48,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields48">My Identity</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields49,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields49">Diagram of Accident scene</li>

	</ul>
</div>