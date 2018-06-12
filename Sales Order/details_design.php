<script type="text/javascript">
    function displayFileName(sel) {
        var div = $(sel).closest('div');
        var filename = $(sel).val().split('\\');
        filename = filename[filename.length - 1];
        div.find('.hidden_until_upload').show();
        div.find('span.file_name').text(filename);
    }
    function addDesign() {
        var count = parseInt($('#upload_count').val());
        var design_block = $('.custom_design').first();
        var clone = design_block.clone();

        clone.find('input').val('');
        clone.find('.custom-file-upload').prop('for', 'so_designs_'+count);
        clone.find('[name="custom_design[]"]').prop('id', 'so_designs_'+count);
        clone.find('.hidden_until_upload').hide();
        design_block.after(clone);

        count += 1;
        $('#upload_count').val(count);
    }
    function deleteDesign(sel) {
        if ($('.custom_design').length <= 1) {
            addDesign();
        }
        $(sel).closest('.custom_design').remove();
    }
    function deleteExistingDesign(sel) {
        if (confirm('Are you sure you want to delete this design?')) {
            var main_sotid = $('#sotid').val();
            var sotid = $(sel).data('id');
            $.ajax({
                type: 'GET',
                url: 'ajax.php?fill=deleteDesign&main_sotid='+main_sotid+'&sotid='+sotid,
                dataType: 'html',
                success: function(response) {
                    $(sel).closest('tr').remove();
                }
            });
        }
    }
</script>
<!-- Upload Design -->
<div class="accordion-block-details padded" id="upload_design">
    <input type="hidden" name="upload_count" id="upload_count" value="1">
    <div class="accordion-block-details-heading"><h4>Custom Designs</h4></div>
    <?php $design_list = mysqli_query($dbc, "SELECT * FROM `sales_order_upload_temp` WHERE `parentsotid` = '$sotid'");
    $num_rows = mysqli_num_rows($design_list);
    if ($num_rows > 0) { ?>
        <div class="existing_designs">
                <table class="table table-bordered">
                <tr class="hidden-xs">
                    <th>Design Name</th>
                    <th>Design</th>
                    <th>Function</th>
                </tr>
                <?php foreach ($design_list as $design) { ?>
                    <tr>
                        <td data-title="Design Name"><?= $design['name'] ?></td>
                        <td data-title="View"><a href="download/<?= $design['file'] ?>" target="_blank"><img src="download/<?= $design['file'] ?>" style="max-height: 100px; width: auto;"></a></td>
                        <td data-title="Function"><a href="" onclick="deleteExistingDesign(this); return false;" data-id="<?= $design['sotid'] ?>">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php } ?>
    <div class="row custom_design">
        <div class="col-sm-12 gap-md-left-15 set-row-height">
            <div class="col-sm-3 pad-5">Design File:</div>
            <div class="col-sm-8">
                <label for="so_designs_0" class="custom-file-upload default-background">Click here to upload a design</label>
                <input type="file" name="custom_design[]" id="so_designs_0" class="file-upload" value="" onchange="displayFileName(this);" />
                <div class="hidden_until_upload inline" style="display: none;">
                    <span class="file_name"></span>
                    <input type="text" name="custom_design_name[]" class="form-control inline" placeholder="Enter a name">
                </div>
            </div>
            <div class="col-sm-1 pull-right">
            <a href="#" onclick="deleteDesign(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>&nbsp;&nbsp;<a href="#" class="add_design" onclick="addDesign(); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a></div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>