<div class="col-md-12">

        <div class="form-group">
            <label for="first_name" class="col-sm-4 control-label text-right">Short Description:</label>
            <div class="col-sm-8">
                <input name="short_desc" value="<?php echo $short_desc; ?>" id="project_name" type="text" class="form-control"></p>
            </div>
        </div>

       <?php if (strpos($value_config, ','."Job description".',') !== FALSE) { ?>
       <div class="form-group">
        <label for="first_name[]" class="col-sm-4 control-label">Job Description:</label>
        <div class="col-sm-8">
          <textarea name="job_desc" rows="5" cols="50" class="form-control"><?php echo $job_desc; ?></textarea>
        </div>
      </div>
      <?php } ?>

        <div class="form-group">
            <div class="col-sm-4">
                <a href="time_tracking.php" class="btn brand-btn">Back</a>
				<!--<a href="<?php //echo $back_url; ?>" class="btn brand-btn">Back</a>
				<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>-->
            </div>
            <div class="col-sm-8">
                <button type="submit" name="submit" value="submit" class="btn brand-btn pull-right">Submit</button>
            </div>
        </div>

</div>