<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Lead Source:</label>
    <div class="col-sm-8">
        <select data-placeholder="Choose a Lead Source..." name="lead_source" class="chosen-select-deselect form-control" width="380">
          <option value=""></option>
          <option <?php if ($lead_source == "Repeat Customer") { echo " selected"; } ?> value="Repeat Customer">Repeat Customer</option>
          <option <?php if ($lead_source == "Referral") { echo " selected"; } ?> value="Referral">Referral</option>
          <option <?php if ($lead_source == "Business Lead") { echo " selected"; } ?> value="Business Lead">Business Lead</option>
          <option <?php if ($lead_source == "Cold Call") { echo " selected"; } ?> value="Cold Call">Cold Call</option>
          <option <?php if ($lead_source == "Tradeshow") { echo " selected"; } ?> value="Tradeshow">Tradeshow</option>
          <option <?php if ($lead_source == "Website") { echo " selected"; } ?> value="Website">Website</option>
          <option <?php if ($lead_source == "Social Media") { echo " selected"; } ?> value="Social Media">Social Media</option>
          <option <?php if ($lead_source == "Print Media") { echo " selected"; } ?> value="Print Media">Print Media</option>
          <option <?php if ($lead_source == "Radio") { echo " selected"; } ?> value="Radio">Radio</option>

          <?php
            $tabs = get_config($dbc, 'sales_lead_source');
            $each_tab = explode(',', $tabs);
            foreach ($each_tab as $cat_tab) {
                if ($lead_source == $cat_tab) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
                echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
            }
          ?>
        </select>
    </div>
</div>