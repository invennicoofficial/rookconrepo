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
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2">Patient Name<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3">Date<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4">Pain Scale<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5">PAIN INTENSITY<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6">PERSONAL CARE<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">LIFTING<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8">READING<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9">HEADACHES<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10">CONCENTRATION<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11">WORK<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">DRIVING<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13">SLEEPING<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields14,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields14">RECREATION<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields15,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields15">Total Score<br>
<input type="checkbox" <?php if (strpos(','.$fields.',', ',fields16,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields16">Signature<br>
