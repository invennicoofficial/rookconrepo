<?php if(str_replace(',','',$fields) == '') {
	$fields = ',fields1,fields2,fields3,fields4,fields5,fields6,fields7,fields8,fields9,fields10,fields11,fields12,fields13,fields14,fields15,fields16,fields17,fields18,fields19,fields20,fields21,fields22,fields23,Topic (Sub Tab),Section #,Section Heading,Sub Section #,Sub Section Heading,';
} ?>
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
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2">Claim Number<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3">Insurance Company<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4">Claim Representative<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5">Policy Number<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6">Date of Accident<br>

<h4>Part 1 - Claimant Information</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">Patient Info<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8">Contact Method<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9">Claim Information<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10">Employment Information<br>

<h4>Part 2 - Claimant's Authorized Representative</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11">Rep Name<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields12') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">Address<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13">Relationship with Claimant<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14">Contact Numbers<br>

<h4>Part 3 - Claimant's Accident Details</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15">Involvement<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16">Accident Information<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields17,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields17">Treatment Information<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields18,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields18">Injuries<br>

<h4>Part 4 - Information of Health Provider Providing Ongoing Treatment and Care</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields19,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields19">Health Care Provider<br>

<h4>Part 5 - Authority to Act on Claimants Behalf</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields20,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields20">Name<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields21,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields21">Authorized<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields22,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields22">Insurance Company<br>

<h4>Part 6 - Certification and Consent to Share Information</h4>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields23,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields23">Insurance Company<br>