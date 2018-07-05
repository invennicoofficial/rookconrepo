<input type="hidden" id="form_name" name="form_name" value="Manual">
<div class="form-group">
    <label for="fax_number" class="col-sm-4 control-label">Tab/Category:</label>
    <div class="col-sm-8">
        <select data-placeholder="Select Tab..." id="tab_field" name="tab_field" class="chosen-select-deselect form-control" width="380">
          <option value=""></option>
          <option <?php if ($tab == "Toolbox") { echo " selected"; } ?> value="Toolbox">Toolbox</option>
          <option <?php if ($tab == "Tailgate") { echo " selected"; } ?> value="Tailgate">Tailgate</option>
          <option <?php if ($tab == "Form") { echo " selected"; } ?> value="Form">Form</option>
          <option <?php if ($tab == "Manual") { echo " selected"; } ?> value="Manual">Manual</option>
        </select>
    </div>
</div>
<?php include ('manual_basic_field.php'); ?>