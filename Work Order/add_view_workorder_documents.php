<script type="text/javascript">
$(document).ready(function() {
    $('#add_row_doc').on( 'click', function () {
        var clone = $('.additional_doc').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_doc");
        $('#add_here_new_doc').append(clone);
        return false;
    });

    $('#add_row_doc_review').on( 'click', function () {
        var clone = $('.additional_doc_review').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_doc_review");
        $('#add_here_new_doc_review').append(clone);
        return false;
    });

    $('#add_row_link').on( 'click', function () {
        var clone = $('.additional_link').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_link");
        $('#add_here_new_link').append(clone);
        return false;
    });

    $('#add_row_review_link').on( 'click', function () {
        var clone = $('.additional_review_link').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_review_link");
        $('#add_here_new_review_link').append(clone);
        return false;
    });
});
</script>
<div class="col-md-12">
    <?php
    if(!empty($_GET['workorderid'])) {
        $query_check_credentials = "SELECT * FROM workorder_document WHERE workorderid='$workorderid' ORDER BY workorderdocid DESC";
        $result = mysqli_query($dbc, $query_check_credentials);
        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo "<table class='table table-bordered'>
            <tr class='hidden-xs hidden-sm'>
            <th>Type</th>
            <th>Document/Link</th>
            <th>Date</th>
            <th>Uploaded By</th>
            </tr>";
            while($row = mysqli_fetch_array($result)) {
                echo '<tr>';
                $by = $row['created_by'];
                echo '<td data-title="Schedule">'.$row['type'].'</td>';
                if($row['document'] != '') {
                    echo '<td data-title="Schedule"><a href="download/'.$row['document'].'" target="_blank">'.$row['document'].'</a></td>';
                } else {
                    echo '<td data-title="Schedule"><a target="_blank" href=\''.$row['link'].'\'">Link</a></td>';
                }
                echo '<td data-title="Schedule">'.$row['created_date'].'</td>';
                echo '<td data-title="Schedule">'.get_staff($dbc, $by).'</td>';
                //echo '<td data-title="Schedule"><a href=\'delete_restore.php?action=delete&workorderdocid='.$row['workorderdocid'].'&workorderid='.$row['workorderid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a></td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }
    ?>

    <h3>Support Documents</h3>
    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">Upload Document(s):
                <span class="popover-examples list-inline">&nbsp;
                <a href="#job_file" data-toggle="tooltip" title="File name cannot contain apostrophes, quotations or commas."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                </span>
        </label>
        <div class="col-sm-8">

            <div class="enter_cost additional_doc clearfix">
                <div class="clearfix"></div>

                <div class="form-group clearfix">
                    <div class="col-sm-5">
                        <input name="document" multiple type="file" data-placement="top" data-table="workorder_document" data-id="" data-id-field="workorderdocid" data-filename-placement="inside" class="form-control" />
                    </div>
                </div>

            </div>

            <div id="add_here_new_doc"></div>

            <div class="form-group triple-gapped clearfix">
                <div class="col-sm-offset-4 col-sm-8">
                    <button id="add_row_doc" class="btn brand-btn pull-left">Add Another Document</button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">Link(s):<br><em>(e.g. - https://www.google.com)</em>
        </label>
        <div class="col-sm-8">

            <div class="enter_cost additional_link clearfix">
                <div class="clearfix"></div>

                <div class="form-group clearfix">
                    <div class="col-sm-5">
                        <input name="link" type="text" data-placement="top" data-table="workorder_document" data-id="" data-id-field="workorderdocid" class="form-control">
                    </div>
                </div>

            </div>

            <div id="add_here_new_link"></div>

            <div class="form-group triple-gapped clearfix">
                <div class="col-sm-offset-4 col-sm-8">
                    <button id="add_row_link" class="btn brand-btn pull-left">Add Another Link</button>
                </div>
            </div>
        </div>
    </div>

    <h3>Review Documents</h3>
    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">Upload Document(s):
                <span class="popover-examples list-inline">&nbsp;
                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
                </span>
        </label>
        <div class="col-sm-8">

            <div class="enter_cost additional_doc_review clearfix">
                <div class="clearfix"></div>

                <div class="form-group clearfix">
                    <div class="col-sm-5">
                        <input name="review_upload_document[]" multiple type="file" data-filename-placement="inside" class="form-control" />
                    </div>
                </div>

            </div>

            <div id="add_here_new_doc_review"></div>

            <div class="form-group triple-gapped clearfix">
                <div class="col-sm-offset-4 col-sm-8">
                    <button id="add_row_doc_review" class="btn brand-btn pull-left">Add Another Document</button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="additional_note" class="col-sm-4 control-label">Link(s):<br><em>(e.g. - https://www.google.com)</em>
        </label>
        <div class="col-sm-8">

            <div class="enter_cost additional_review_link clearfix">
                <div class="clearfix"></div>

                <div class="form-group clearfix">
                    <div class="col-sm-5">
                        <input name="support_review_link[]" type="text" class="form-control">
                    </div>
                </div>

            </div>

            <div id="add_here_new_review_link"></div>

            <div class="form-group triple-gapped clearfix">
                <div class="col-sm-offset-4 col-sm-8">
                    <button id="add_row_review_link" class="btn brand-btn pull-left">Add Another Link</button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-4">
            <!--<a href="<?php //echo $back_url; ?>" class="btn brand-btn">Back</a>-->
			<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;" title="The entire form will close without submit if this back button is pressed.">Back</a>
        </div>
        <div class="col-sm-8">
            <button type="submit" name="submit" value="submit" class="btn brand-btn pull-right" title="The entire form will submit and close if this submit button is pressed.">Submit</button>
        </div>
    </div>

</div>