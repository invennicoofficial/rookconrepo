<div class="gap-top">
    <div class="form-group">
        <label for="fax_number" class="col-sm-4 control-label"><span class="popover-examples list-inline"><a class="" style="margin:7px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Digital inventory count functionality is ideal for users wanting to confirm that their actual quantity of inventory matches the quantity of inventory in their software."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>Enable Digital Inventory Count:</label>
        <div class="col-sm-8">
        <?php
        $checked = '';
        $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='show_digi_count'"));
        if($get_config['configid'] > 0) {
            $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM general_configuration WHERE name='show_digi_count'"));
            if($get_config['value'] == '1') {
                $checked = 'checked';
            }
        }
        ?>
          <input type='checkbox' style='width:20px; height:20px;' <?php echo $checked; ?>  name='' class='show_digi_count' value='1'>
        </div>
    </div>

    <div class="clearfix"></div>
</div>