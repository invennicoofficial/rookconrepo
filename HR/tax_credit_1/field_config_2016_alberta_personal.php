<script type="text/javascript">
	$(document).ready(function(){
		var inc = 1;
        $('#add_row_hazard').on( 'click', function () {
            $(".hide_show_service").show();
            var clone = $('.additional_hazard').clone();
            clone.find('.task_list').val('');
            clone.removeClass("additional_hazard");
            $('#add_here_new_hazard').append(clone);
            inc++;
            return false;
        });
    });
</script>
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

<div class="form-group">
    <label for="site_name" class="col-sm-4 control-label">Company Name:</label>
    <div class="col-sm-8">
        <input name="config_extra_fields[]" value="<?php echo $config_extra_fields[0]; ?>" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="site_name" class="col-sm-4 control-label">Company Address:</label>
    <div class="col-sm-8">
        <input name="config_extra_fields[]" value="<?php echo $config_extra_fields[1]; ?>" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="site_name" class="col-sm-4 control-label">Company Phone:</label>
    <div class="col-sm-8">
        <input name="config_extra_fields[]" value="<?php echo $config_extra_fields[2]; ?>" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="site_name" class="col-sm-4 control-label">Description:<br><em>Add Tags like.<br>[Company Name], [Company Address], [Employee Name], [Employee Position], [Joining Date], [Today Date], [Amount], [Work Type], [Company Phone]</em></label>
    <div class="col-sm-8">
      <textarea name="hr_description" rows="5" cols="50" class="form-control"><?php echo $hr_description; ?></textarea>
    </div>
</div>