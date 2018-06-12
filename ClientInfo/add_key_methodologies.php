<?php
include ('config_ss_functions.php');

$value = $config['settings']['Choose Fields for Key Methodologies'];

if(isset($_GET['action']) && $_GET['action'] == 'delete') {
    mysqli_query($dbc, "DELETE FROM key_methodologies WHERE keymethodologiesid=".$_GET['keymethodologiesid']);

    echo '<script type="text/javascript"> window.location.replace("'.$_GET['from_url'].'"); </script>';
}
?>
<script type="text/javascript">
function selectContactCategory(sel) {
    var stage = sel.value;
    var typeId = sel.id;
    var arr = typeId.split('_');
    $.ajax({
        type: "GET",
        url: "../Social Story/isp_ajax_all.php?fill=contact_category&category="+stage,
        dataType: "html",   //expect html to be returned
        success: function(response){
            $("#contact_"+arr[2]).html(response);
            $("#contact_"+arr[2]).trigger("change.select2");
        }
    });
}
</script>
    <?php

        $inputs = get_all_inputs_social($value['data']);

        foreach($inputs as $input) {
            $$input = '';
        }

        if(!empty($_GET['contactid']) && !empty($_GET['category'])) {
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM key_methodologies WHERE support_contact='".$_GET['contactid']."'"));

            foreach($inputs as $input) {
                $$input = $get_contact[$input];
            }
            $keymethodologiesid = $get_contact['keymethodologiesid'];
            $support_contact = $_GET['contactid'];
            $support_contact_category = $_GET['category'];

        ?>
        <input type="hidden" id="keymethodologiesid" name="keymethodologiesid" value="<?php echo $keymethodologiesid ?>" />
        <input type="hidden" id="support_contact_category" name="support_contact_category" value="<?php echo $support_contact_category; ?>" />
        <input type="hidden" id="support_contact" name="support_contact" value="<?php echo $support_contact; ?>" />
        <?php   }      ?>
        <input type="hidden" id="submit_type" name="submit_type" value="key_methodologies" />



<?php
$k=0;

if(isset($value['config_field'])) {
    $get_field_config = @mysqli_fetch_assoc(mysqli_query($dbc,"SELECT ".$value['config_field']." FROM field_config"));
    $value_config = ','.$get_field_config[$value['config_field']].',';
    foreach($value['data'] as $tab_name => $tabs) {
		$display = false;
		foreach($tabs as $info) {
			$display = ($display == true || strpos($value_config, ','.$info[2].',') !== FALSE);
		}
		if($display) { ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field<?php echo $k; ?>" >
							<?php echo $tab_name; ?><span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>
				<div id="collapse_field<?php echo $k; ?>" class="panel-collapse collapse">
					<div class="panel-body">
							<?php
								foreach($tabs as $field) {
									if (strpos($value_config, ','.$field[2].',') !== FALSE) {
										if($field[2] == 'support_contact') {
											echo get_field_social($field, $$field[2], $dbc, $support_contact, $support_contact_category, 'disabled="true"');
										} else if($field[2] == 'support_contact_category') {
                                            echo get_field_social($field, $$field[2], $dbc, $support_contact, '', 'disabled="true"');
                                        } else {
											echo get_field_social($field, $$field[2], $dbc, $support_contact);
										}
									}
								}
							?>
						</ul>
					</div>
				</div>
			</div>
		<?php }
       $k++;
    }
}
?>