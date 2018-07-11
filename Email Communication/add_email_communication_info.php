<script type="text/javascript">
$(document).ready(function() {
});
</script>

<div class="col-md-12">

       <?php if (strpos($value_config, ','."Subject".',') !== FALSE) { ?>
       <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Subject:</label>
        <div class="col-sm-8">
          <input name="subject" value="<?php echo $subject; ?>" type="text" class="form-control">
        </div>
      </div>
      <?php } ?>

      <?php if (strpos($value_config, ','."Body".',') !== FALSE) { ?>
      <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label">Body:</label>
        <div class="col-sm-8">
          <textarea name="email_body" rows="5" cols="50" class="form-control"><?php echo html_entity_decode(htmlspecialchars_decode($email_body)); ?></textarea>
        </div>
      </div>
      <?php } ?>

    <div class="form-group">
        <div class="col-sm-4">
            <a href="<?php echo $back_url; ?>" class="btn brand-btn">Back</a>
        </div>
        <div class="col-sm-8">
            <button type="submit" name="submit" value="submit" class="btn brand-btn pull-right">Submit</button>
        </div>
    </div>

</div>