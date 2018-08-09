<!-- Information Gathering -->
<script type="text/javascript">
$(document).ready(function() {
    $('.add_row_infodoc').on( 'click', add_info_doc);
});
function add_info_doc() {
    var clone = $('.additional_infodoc').clone();
    clone.find('.form-control').val('');
    clone.removeClass("additional_infodoc");
    $('#add_here_new_infodoc').append(clone);
    $('.add_row_infodoc').off('click',add_info_doc).on( 'click', add_info_doc);
    return false;
}
</script>

<div class="accordion-block-details padded" id="infogathering">
    <div class="accordion-block-details-heading"><h4>Information Gathering</h4></div>
    
    <div class="row">
        <div class="col-xs-12 col-sm-11 gap-md-left-15"><?php
            if ( !empty($salesid) ) {
                $result = mysqli_query($dbc, "SELECT * FROM `sales_document` WHERE `salesid`='{$salesid}' AND `document_type`='Information Gathering' ORDER BY `salesdocid` DESC");
                if ( $result->num_rows > 0 ) {
                    echo '
                        <br />
                        <table>
                            <tr class="hidden-xs hidden-sm">
                                <th>Document</th>
                                <th>Date</th>
                                <th>Uploaded By</th>
                            </tr>';
                    
                    while ( $row=mysqli_fetch_array($result) ) {
                        echo '<tr>';
                            $by = $row['created_by'];
                            if ( !empty($row['document']) ) {
                                echo '<td data-title="Document"><a href="download/'.$row['document'].'" target="_blank">'.(empty($row['label']) ? $row['document'] : $row['label']).'</a></td>';
                            }
                            echo '<td data-title="Date">'.$row['created_date'].'</td>';
                            echo '<td data-title="Uploaded By">'.get_staff($dbc, $by).'</td>';
                            //echo '<td data-title="Schedule"><a href=\'delete_restore.php?action=delete&ticketdocid='.$row['ticketdocid'].'&salesid='.$row['salesid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
                        echo '</tr>';
                    }
                    
                    echo '</table><br /><br />';
                }
            } ?>
        </div>
    </div><!-- .row -->
    
    <div class="row">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">
            <span class="popover-examples list-inline"><a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a></span>
            <b>Upload Document(s):</b>
        </div>
        
        <div class="col-xs-12 col-sm-8 gap-md-left-15 gap-top">
            <div class="additional_infodoc">
                <div class="col-sm-7"><input name="infodoc_label[]" type="text" placeholder="Information Gathering Document Label" class="form-control" /></div>
                <div class="col-sm-4"><input name="upload_infodoc[]" multiple type="file" data-filename-placement="inside" class="form-control" /></div>
                <a href="#" class="add_row_infodoc"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
                <div class="clearfix"></div>
            </div>

            <div id="add_here_new_infodoc"></div>

            <div class="col-sm-12 gap-md-left-10 gap-top">
            </div>
        </div>
        <div class="clearfix"></div>
    </div><!-- .row -->
    
    <div class="row double-gap-top">
        <div class="col-sm-12 gap-md-left-15 set-row-height"><a href="<?= WEBSITE_URL; ?>/Information Gathering/infogathering.php?from=<?= urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']); ?>" target="_blank">Click to View/Add Information Gathering</a></div>
        <div class="clearfix"></div>
    </div><!-- .row -->
</div>