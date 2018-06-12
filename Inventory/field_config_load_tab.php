<?php include_once('../include.php'); ?>
<script type="text/javascript" src="field_config.js"></script>

<?php $type = $_GET['type'];

include('../Inventory/field_config_'.$type.'.php');
if ($type != 'order_list') { ?>
    <div class="pull-right gap-top gap-right gap-bottom">
        <a href="inventory.php" class="btn brand-btn">Cancel</a>
        <button type="submit" id="submit" name="submit" value="Submit" class="btn brand-btn">Submit</button>
    </div>
<?php } ?>