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
<input type="checkbox" class="selecctall"/> Select All<br><br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields1,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields1">Fax #<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2">Insurance Company<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3">Policy Number<br>

<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4">Date of Accident<br>
<h4>Claimant Information</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5">Patient Info<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6">Date of Accident<br>
<h4>Claimant's Authorized Representative</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">Name<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8">Address<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9">Relationship with Claimant<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10">Telephone Number<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11">Fax Number<br>
<h4>Therapy Status Report</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">Diagnosis<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13">Key Subjective/Physical Examination Findings<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14">C/O<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15">O/E<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16">Diagnosis<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields17">ICD-10-CA Injury Code*<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields18,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields18">Outcome measures<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields19,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields19">Is the claimant employed or engaged in training activities?<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields20,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields20">Functional Goals<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields21,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields21">Comments<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields22,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields22">Expected Number of Visits<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields23,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields23">Date of expected treatment discharge<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields24,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields24">Do you expect these visits to be sufficient to meet functional goals:<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields25,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields25">Do you expect to reassess within three weeks due to alerting factors?<br>
<h4>Treatment</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields26,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields26">Treatment Provided<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields27,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields27">Do you expect the claimant to return to normal and essential activities?<br>
<h4>Primary Health Care Practitioner Information</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields28,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields28">Name of Primary Health Care Practitioner<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields29,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields29">Profession<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields30,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields30">Address<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields31,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields31">Telephone Number<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields32,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields32">Fax Number<br>
<h4>Signature of Primary Health Care Practitioner</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields33,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields33">Name<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields34,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields34">Signature<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields35,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields35">Date<br>
<h4>Choice in Following Diagnostic and Treatment Protocols</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields36,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields36">Name<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields37,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields37">Signature<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields38,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields38">Date<br>