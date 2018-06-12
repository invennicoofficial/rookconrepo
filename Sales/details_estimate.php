<!-- Estimate -->
<script type="text/javascript">
$(document).ready(function() {
    $('#add_row_estimate').on( 'click', function () {
        var clone = $('.additional_estimate').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_estimate");
        $('#add_here_new_estimate').append(clone);
        return false;
    });
});
</script>

<div class="accordion-block-details padded" id="estimate">
    <div class="accordion-block-details-heading"><h4>Estimate</h4></div>
    
    <div class="row">
        <div class="col-xs-12 col-sm-11 gap-md-left-15"><?php
            if ( !empty($salesid) ) {
                $result = mysqli_query($dbc, "SELECT * FROM `sales_document` WHERE `salesid`='{$salesid}' AND `document_type`='Estimate' ORDER BY `salesdocid` DESC");
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
            <span class="popover-examples list-inline"><a href="#" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a></span>
            <b>Upload Estimate(s):</b>
        </div>
        
        <div class="col-xs-12 col-sm-8 gap-md-left-15 gap-top">
            <div class="additional_estimate">
                <div class="col-sm-7"><input name="estimate_label[]" type="text" placeholder="Estimate Label" class="form-control" /></div>
                <div class="col-sm-5"><input name="upload_estimate[]" multiple type="file" data-filename-placement="inside" class="form-control" /></div>
                <div class="clearfix"></div>
            </div>

            <div id="add_here_new_estimate"></div>

            <div class="col-sm-12 gap-md-left-10 gap-top">
                <a href="#" id="add_row_estimate" class=""><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
            </div>
        </div>
        <div class="clearfix"></div>
    </div><!-- .row -->
    
    <div class="row double-gap-top">
        <div class="col-sm-12 gap-md-left-15 set-row-height"><?php
            $get_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(`estimateid`) AS `total_id` FROM `estimate` WHERE `businessid`='$businessid'"));
            if ($get_config['total_id'] > 0) {
                echo '<a target="_blank" href="'.WEBSITE_URL.'/Estimate/estimate.php?businessid='.$businessid.'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" id="'.$businessid.'">Click to View/Add Estimate</a>';
            } else {
                echo '<a target="_blank" href="'.WEBSITE_URL.'/Estimate/add_estimate.php?from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'">Click to Add Estimate</a>';
            } ?>
        </div>
        <div class="clearfix"></div>
    </div><!-- .row -->
</div>