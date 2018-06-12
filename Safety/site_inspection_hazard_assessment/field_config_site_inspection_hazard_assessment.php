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

	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields1,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields1">Date/Time</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2">Project Name</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3">Employer</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4">Project Number</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5">Inspection By</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6">Purpose</li>

	</ul>

	Instruction
	<ul style="list-style-type: none;">

	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">Project Management Compliance</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8">O.H.S. Committee Minutes</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9">Supervision</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10">Safe Work Practices & Procedures</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11">Training of Workers / Records</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">Postings / Safety Manuals / OH&S Regulations</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13">Personal Protection Equipment</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14">Tool Box Meetings / Minutes</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15">Ventilation / Environmental Conditions</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16">Respiratory Protection</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields17">Lighting</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields18,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields18">Toilet, Washing, Drinking & Eating Facilities</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields19,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields19">Security / Fire Prevention</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields20,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields20">First Aid / Emergency Protocols</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields21,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields21">Equipment Condition / Maintenance</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields22,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields22">Inspections</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields23,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields23">Machine Guarding</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields24,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields24">Signs / Barricades</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields25,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields25">Handling of Loads</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields26,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields26">Access / Egress</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields27,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields27">Chemical Substances / W.H.M.I.S. / M.S.D.S. / Hazard Comm. Program</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields28,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields28">Scaffolds</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields29,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields29">Fall Protection</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields30,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields30">Noise Awareness / Protection</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields31,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields31">Work Clothing (& Accommodation if applicable)</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields32,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields32">Housekeeping</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields33,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields33">Hoisting & Rigging</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields34,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields34">Accident Reporting / Investigations</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields35,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields35">Fire Extinguishers</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields36,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields36">BBS</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields37,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields37">Orientations</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields38,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields38">Hazard Assessments</li>
	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields39,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields39">Others</li>
	</ul>

	Overall Rating

	<ul style="list-style-type: none;">

	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields40,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields40">Overall Rating</li>

	</ul>

	<ul style="list-style-type: none;">

	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields41,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields41">Additional Comments</li>

	</ul>

	<ul style="list-style-type: none;">

	<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields42,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields42">Date Completed</li>

	</ul>
</div>