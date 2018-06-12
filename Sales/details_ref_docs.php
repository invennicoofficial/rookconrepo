<!-- Reference Documents -->
<script type="text/javascript">
function addRowDoc() {
    var clone = $('.additional_doc').clone();
    clone.find('.form-control').val('');
    clone.removeClass("additional_doc");
    $('.additional_doc').last().after(clone);
    return false;
}
function addRowLink() {
    var clone = $('.additional_link').clone();
    clone.find('.form-control').val('');
    clone.removeClass("additional_link");
    $('.additional_link').last().after(clone);
    return false;
}
</script>

<div class="accordion-block-details padded" id="refdocs">
    <div class="accordion-block-details-heading"><h4>Reference Documents</h4></div>
    
    <div class="row"><?php
        if ( !empty($salesid) ) {
            $result = mysqli_query($dbc, "SELECT * FROM `sales_document` WHERE `salesid`='{$salesid}' AND (`document_type`='Reference Documents' OR `document_type`='') ORDER BY `salesdocid` DESC");
            if ( $result->num_rows > 0 ) {
                echo '
                    <table>
                        <tr class="hidden-xs hidden-sm">
                            <th>Document/Link</th>
                            <th>Date</th>
                            <th>Uploaded By</th>
                        </tr>';
                
                while ( $row=mysqli_fetch_array($result) ) {
                    echo '<tr>';
                        $by = $row['created_by'];
                        if ( $row['document'] != '' ) {
                            echo '<td data-title="Schedule"><a href="download/'.$row['document'].'" target="_blank">'.(empty($row['label']) ? $row['document'] : $row['label']).'</a></td>';
                        } else {
                            echo '<td data-title="Schedule"><a target="_blank" href=\''.$row['link'].'\'">'.(empty($row['label']) ? 'Link' : $row['label']).'</a></td>';
                        }
                        echo '<td data-title="Schedule">'.$row['created_date'].'</td>';
                        echo '<td data-title="Schedule">'.get_staff($dbc, $by).'</td>';
                        //echo '<td data-title="Schedule"><a href=\'delete_restore.php?action=delete&ticketdocid='.$row['ticketdocid'].'&salesid='.$row['salesid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
                    echo '</tr>';
                }
                
                echo '</table><br /><br />';
            }
        } ?>
    </div><!-- .row -->
    
    <div class="row">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">
            <span class="popover-examples list-inline"><a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a></span>
            <b>Upload Document(s):</b>
        </div>
        
        <div class="col-xs-12 col-sm-8 gap-md-left-15 gap-top">
            <div class="additional_doc">
                <div class="col-sm-7"><input name="document_label[]" type="text" placeholder="Reference Document Label" class="form-control" /></div>
                <div class="col-sm-4"><input name="upload_document[]" multiple type="file" data-filename-placement="inside" class="form-control" /></div>
                <div class="col-sm-1 pull-right"><a href="#" id="add_row_doc" onclick="addRowDoc(); return false;" class=""><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a></div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div><!-- .row -->
    
    <div class="row triple-gap-top">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">
            <span class="popover-examples list-inline"><a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a></span>
            <b>Link(s):</b> <em>(eg. https://www.google.com)</em>
        </div>
        
        <div class="col-xs-12 col-sm-8 gap-md-left-15 gap-top">
            <div class="additional_link">
                <div class="col-sm-7"><input name="link_label[]" type="text" placeholder="Link Label" class="form-control" /></div>
                <div class="col-sm-4"><input name="support_link[]" type="text" placeholder="Link" class="form-control" /></div>
                <div class="col-sm-1 pull-right"><a href="#" id="add_row_link" onclick="addRowLink(); return false;" class=""><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a></div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div><!-- .row -->
    
</div><!-- .accordion-block-details -->