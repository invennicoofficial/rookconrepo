<div class="form-group">
<label for="file[]" class="col-sm-4 control-label">Upload Logo
<span class="popover-examples list-inline">&nbsp;
<a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
</span>
:</label>
<div class="col-sm-8">
<!--<?php if($pdf_logo != '') {
    echo '<a href="download/'.$pdf_logo.'" target="_blank">View</a>';
    ?>
    <input type="hidden" name="logo_file" value="<?php echo $pdf_logo; ?>" />
    <input name="pdf_logo" type="file" data-filename-placement="inside" class="form-control" />
  <?php } else { ?>
  <input name="pdf_logo" type="file" data-filename-placement="inside" class="form-control" />
  <?php } ?>-->
</div>
</div>

<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields2,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields2">Business Name</li>
<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields3,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields3">Business Services</li>
<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields4,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields4">Business Products</li>
<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields12,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields12">Business Culture</li>
<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields5,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields5">Business Vision</li>
<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields6,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields6">Business Goals</li>
<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields13,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields13">Business Tone</li>
<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields7,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields7">Target Markets</li>
<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields8,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields8">Competitors</li>
<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields9,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields9">Current Areas of Concern</li>
<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields10,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields10">Estimated Project Timeline & Budget</li>
<li><input type="checkbox" <?php if (strpos(','.$fields.',', ',fields11,') !== FALSE) { echo " checked"; } ?>  name="fields[]" value="fields11">Communication Expectations & Methods</li>