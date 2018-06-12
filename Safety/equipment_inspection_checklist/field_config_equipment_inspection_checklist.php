<div class="form-group">
<label for="file[]" class="col-sm-4 control-label">Upload Logo
<span class="popover-examples list-inline">&nbsp;
<a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
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
    <label for="office_country" class="col-sm-4 control-label">Footer Info:<br><em>(e.g. - company name, address, phone, etc.)</em></label>
    <div class="col-sm-8">
        <textarea name="pdf_footer" rows="3" cols="50" class="form-control"><?php echo $pdf_footer; ?></textarea>
    </div>
</div>
<!-- Header & Footer -->

<input type="checkbox" class="selecctall"/> Select All
<br><br>
<div class="field_config">
	<ul style="list-style-type: none;">

	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields1,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields1">Date</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2">Make</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3">Vehicle Type</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4">Serial Number</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5">Model</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6">Kilometers</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">Inspected By</li>
	</ul>


	<ul style="list-style-type: none;">

	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8">Engine oil and filter</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9">Antifreeze</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields10') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10">Battery and tie down</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11">Battery cables</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">Fan belts and Hoses</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13">Air filter and piping</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14">Exhaust System</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15">Fuel Tank and Lines</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16">Tires, Wheels, Tracks</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields17">Service Brakes</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields18,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields18">Parking Brakes</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields19,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields19">Seat Belt</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields20,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields20">Gauges and Alarms</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields21,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields21">Heater, def., and a/c</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields22,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields22">Horn, Lights</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields23,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields23">Signal Lights</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields24,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields24">Beacon</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields25,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields25">Back-up Alarm</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields26,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields26">Glass</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields27,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields27">Window Wipers</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields28,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields28">Mirrors</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields29,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields29">Doors and Latches</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields30,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields30">Body and Frame</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields31,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields31">Steering System</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields32,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields32">Operators Manual</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields33,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields33">Grab Handles and Steps</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields34,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields34">Hood and Latches</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields35,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields35">Hydraulic Oil Levels</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields36,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields36">Hydraulic Cylinders</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields37,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields37">Hydraulic Hoses</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields38,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields38">Hydraulic Cylinder Pins</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields39,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields39">Chains, straps, tie downs</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields40,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields40">Air Leaks</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields41,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields41">Oil Leaks</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields42,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields42">Fire Extinguisher</li>

	</ul>
</div>

