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
    <label for="site_name" class="col-sm-4 control-label">Description:</label>
    <div class="col-sm-8">
      <textarea name="hr_description" rows="5" cols="50" class="form-control"><?php echo $hr_description; ?></textarea>
    </div>
</div>

<?php
$all_task_each = explode('**##**',$document);

$total_count = mb_substr_count($document,'**##**');
if($total_count > 0) {
    echo "<table class='table table-bordered'>";
    echo "<tr class='hidden-xs hidden-sm'>
    <th>Document Name</th>
    <th>Upload</th>";
}
for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
    $task_item = explode('**',$all_task_each[$client_loop]);
    $task = $task_item[0];
    $hazard = $task_item[1];
    if($task != '') {
        echo '<tr>';
        echo '<td data-title="Email">' . $task . '</td>';
        echo '<td data-title="Email"><a href="download/'.$hazard.'" target="_blank">' . $hazard . '</a></td>';
        echo '</tr>';
    }
}
echo '</table>';
?>
<div class="additional_hazard clearfix">
    <div class="row">
        <div class="col-md-2 col-sm-6 col-xs-6 padded">
            <p>Document Name</p>
            <input type="text" name="doc_name[]" class="task_list"/>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6 padded">
            <p>Upload</p>
            <input name="doc_upload[]" type="file" data-filename-placement="inside" class="form-control" />
        </div>
    </div>
</div>
<div id="add_here_new_hazard"></div>
<div class="form-group triple-gapped clearfix">
    <div class="col-sm-offset-4 col-sm-8">
        <button id="add_row_hazard" class="btn brand-btn pull-left">Add Hazard</button>
    </div>
</div>

