<div class="form-group">
    <label for="fax_number" class="col-sm-4 control-label"><span class="popover-examples list-inline"><a class="" style="margin:7px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="The Import/Export functionality allows users to export a full spreadsheet of the tile's data, as well as add or edit multiple row items at once."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>Enable Import/Export:</label>
    <div class="col-sm-8">
    <?php
    $checked = '';
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='show_impexp_inv'"));
    if($get_config['configid'] > 0) {
        $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='show_impexp_inv'"));
        if($get_config['value'] == '1') {
            $checked = 'checked';
        }
    }
    ?>
      <input type='checkbox' style='width:20px; height:20px;' <?php echo $checked; ?>  name='' class='show_impexp_inv' value='1'>
    </div>
</div>