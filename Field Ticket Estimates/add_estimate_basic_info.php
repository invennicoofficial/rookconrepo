<div class="form-group clearfix completion_date">
    <label for="first_name" class="col-sm-4 control-label text-right">Business<span class="brand-color">*</span>:</label>
    <div class="col-sm-8">
        <select name="businessid" <?php echo $disable_business; ?> id="businessid" data-placeholder="Choose an Option..." class="chosen-select-deselect form-control" width="380">
            <option value=''></option>
            <?php
            $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Business' AND deleted=0 ORDER BY category");
            while($row = mysqli_fetch_array($query)) {
                if ($businessid== $row['contactid']) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
                echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['name']).'</option>';
            }
            ?>
        </select>
    </div>
</div>

<div class="form-group clearfix completion_date">
    <label for="first_name" class="col-sm-4 control-label text-right">Contact:</label>
    <div class="col-sm-8">
        <select name="estimateclientid" <?php echo $disable_client; ?> id="estimateclientid" data-placeholder="Choose an Option..." class="chosen-select-deselect form-control" width="380">
            <option value=''></option>
            <?php
            $cat = '';
            $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name, category FROM contacts WHERE businessid='$businessid' order by first_name");
            while($row = mysqli_fetch_array($query)) {
                if ($clientid== $row['contactid']) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
                if($cat != $row['category']) {
                    echo '<optgroup label="'.$row['category'].'">';
                    $cat = $row['category'];
                }
                echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
            }
            ?>
        </select>
    </div>
</div>

<div class="form-group clearfix">
    <label for="first_name" class="col-sm-4 control-label text-right">My Company Rate Card:</label>
    <div class="col-sm-8">
        <select name="companyrcid" <?php echo $disable_rc; ?> data-placeholder="Choose a Rate Card..." class="chosen-select-deselect form-control" width="380">
            <option value=''></option>
            <?php
            $query = mysqli_query($dbc,"SELECT companyrcid, rate_card_name FROM company_rate_card GROUP BY rate_card_name");
            while($row = mysqli_fetch_array($query)) {
                if ($companyrcid == $row['companyrcid']) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
                echo "<option ".$selected." value='". $row['companyrcid']."'>".$row['rate_card_name'].'</option>';
            }
            ?>
        </select>
    </div>
</div>

<div class="form-group clearfix">
    <label for="first_name" class="col-sm-4 control-label text-right">Cutomer Specific Rate Card:</label>
    <div class="col-sm-8">
        <select name="ratecardid" <?php echo $disable_rc; ?> id="ratecardid" data-placeholder="Choose a Rate Card..." class="chosen-select-deselect form-control" width="380">
            <option value=''></option>
            <?php
            $query = mysqli_query($dbc,"SELECT ratecardid, rate_card_name FROM rate_card WHERE on_off=1 ORDER BY rate_card_name");
            while($row = mysqli_fetch_array($query)) {
                if ($ratecardid == $row['ratecardid']) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
                echo "<option ".$selected." value='". $row['ratecardid']."'>".$row['rate_card_name'].'</option>';
            }
            ?>
        </select>
    </div>
</div>
<!-- Hide this if WASHTECH is using ESTIMATES -->
<?php if(!isset($washtech_software_checker)) { ?>


<div class="form-group clearfix completion_date">
    <label for="first_name" class="col-sm-4 control-label text-right">Type<span class="brand-color">*</span>:</label>
    <div class="col-sm-8">
        <select name="estimatetype" <?php echo $disable_type; ?> id="estimatetype" data-placeholder="Choose an Option..." class="chosen-select-deselect form-control" width="380">
            <option value=''></option>
            <option <?php if ($estimatetype == "Client") { echo " selected"; } ?> value='Client'>Client</option>
            <?php if(tile_visible($dbc, 'sred') == 1) { ?>
            <option <?php if ($estimatetype == "SRED") { echo " selected"; } ?> value='SRED'>SR&ED</option>
            <?php } ?>
            <?php if(tile_visible($dbc, 'internal') == 1) { ?>
            <option <?php if ($estimatetype == "Internal") { echo " selected"; } ?> value='Internal'>Internal</option>
            <?php } ?>
            <?php if(tile_visible($dbc, 'rd') == 1) { ?>
            <option <?php if ($estimatetype == "RD") { echo " selected"; } ?> value='RD'>R&D</option>
            <?php } ?>
            <?php if(tile_visible($dbc, 'business_development') == 1) { ?>
            <option <?php if ($estimatetype == "Business Development") { echo " selected"; } ?>
            value='Business Development'>Business Development</option>
            <?php } ?>
            <?php if(tile_visible($dbc, 'process_development') == 1) { ?>
            <option <?php if ($estimatetype == "Process Development") { echo " selected"; } ?> value='Process Development'>Process Development</option>
            <?php } ?>
            <?php if(tile_visible($dbc, 'addendum') == 1) { ?>
            <option <?php if ($estimatetype == "Addendum") { echo " selected"; } ?> value='Addendum'>Addendum</option>
            <?php } ?>
            <?php if(tile_visible($dbc, 'addition') == 1) { ?>
            <option <?php if ($estimatetype == "Addition") { echo " selected"; } ?> value='Addition'>Addition</option>
            <?php } ?>
            <?php if(tile_visible($dbc, 'marketing') == 1) { ?>
            <option <?php if ($estimatetype == "Marketing") { echo " selected"; } ?> value='Marketing'>Marketing</option>
            <?php } ?>
            <?php if(tile_visible($dbc, 'manufacturing') == 1) { ?>
            <option <?php if ($estimatetype == "Manufacturing") { echo " selected"; } ?> value='Manufacturing'>Manufacturing</option>
            <?php } ?>
            <?php if(tile_visible($dbc, 'assembly') == 1) { ?>
            <option <?php if ($estimatetype == "Assembly") { echo " selected"; } ?> value='Assembly'>Assembly</option>
            <?php } ?>
        </select>
    </div>
</div>
<?php } ?>
<div class="form-group">
    <label for="first_name" class="col-sm-4 control-label text-right">Short Name<span class="brand-color">*</span>:</label>
    <div class="col-sm-8">
        <input name="estimate_name" value="<?php echo $estimate_name; ?>" id="estimate_name" type="text" class="form-control"></p>
    </div>
</div>
