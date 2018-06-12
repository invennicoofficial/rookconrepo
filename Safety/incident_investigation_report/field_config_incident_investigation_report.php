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
Information
<ul style="list-style-type: none;">
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields1,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields1">Date of Incident</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2">Time</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3">Address/Location</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields25,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields25">JOB #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields26,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields26">Customer</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields27,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields27">LSD #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields28,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields28">Facility / Rig Name</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields29,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields29">Date Of Occurrence</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields30,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields30">Time</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields31,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields31">Location</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields32,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields32">Date Reported</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields33,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields33">Time</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields34,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields34">Subcontractor</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields35,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields35">Person Reporting Incident</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields36,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields36">Occupation</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields37,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields37">Immediate Supervisor</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields38,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields38">Witness To Incident</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields39,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields39">Incident Type Of Incident</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields64,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields64">Employee Name</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields65,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields65">Experience</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields66,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields66">Reported to</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields67,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields67">Legal Description</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields68,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields68">Client at time of Incident</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields69,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields69">Type of work being performed</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields70,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields70">Accident Type of Incident</li>
</ul>

Investigation
<ul style="list-style-type: none;">
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4">Injury</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5">Illness</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6">Lost Time</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">Property Damage</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8">Fire</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9">Environmental Incident</li>
</ul>

<ul style="list-style-type: none;">
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10">Person In Charge</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11">Reported By</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">Reported To</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13">Date Reported</li>
</ul>

<ul style="list-style-type: none;">
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14">Description of Incident</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15">Direct Cause of Incident</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16">Contributing Factor</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields17">Person Having Control Over the Cause</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields18,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields18">Immediate Corrective Action Required</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields19,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields19">Long Term Corrective Action Required</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields20,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields20">Immediate Corrective Action Assigned To</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields21,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields21">Date Complete</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields22,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields22">Long Term Corrective Action Assigned To</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields23,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields23">Date Complete</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields24,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields24">Diagram of Scene</li>
</ul>

<ul style="list-style-type: none;">
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields40,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields40">Name Of Injured Employee</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields41,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields41">Occupation</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields42,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields42">Employee Address</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields43,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields43">Date Of Birth</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields44,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields44">Nature Of The Injury</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields45,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields45">Body Part</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields46,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields46">Did This Aggravate A Previous Injury?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields47,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields47">Nature Of The Injury</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields48,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields48">First Aid</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields49,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields49">First Aid Rendered By</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields50,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields50">Medical Treatment</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields51,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields51">Treatment Rendered By</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields52,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields52">Health Care Number</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields53,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields53">SIN #</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields54,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields54">Home Phone</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields55,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields55">Object, Equipment, Or Substance Inflicting Injury</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields56,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields56">Date Injured Worker Commenced Employment</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields57,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields57">Was A Tailgate Meeting Held Prior To The Job?</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields58,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields58">Personal Protective Equipment Worn At Time Of Injury</li>
</ul>

<ul style="list-style-type: none;">
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields59,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields59">Causal Factors</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields60,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields60">Root Cause</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields61,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields61">Prevention</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields62,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields62">Costs</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields63,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields63">Injury Classification</li>
</ul>


<ul style="list-style-type: none;">
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields71,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields71">Injury</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields72,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields72">Illness</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields73,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields73">Near Miss</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields74,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields74">Damage</li>
</ul>

</div>