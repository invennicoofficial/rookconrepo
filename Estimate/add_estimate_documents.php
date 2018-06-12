<script type="text/javascript">
$(document).ready(function() {
    $('#add_row_doc').on( 'click', function () {
        var clone = $('.additional_doc').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_doc");
        $('#add_here_new_doc').append(clone);
        return false;
    });
});
</script>

<?php
if(!empty($_GET['estimateid'])) {
    $estimateid = $_GET['estimateid'];

    $get_doc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(uploadid) AS total_id FROM estimate_document WHERE estimateid='$estimateid'"));

    if($get_doc['total_id'] > 0) {
        $result = mysqli_query($dbc, "SELECT upload, uploadid FROM estimate_document WHERE  estimateid='$estimateid'");

        echo '<ul>';
        $i=0;
        while($row = mysqli_fetch_array($result)) {
            $document = $row['upload'];
            if($document != '') {
                echo '<li><a href="download/'.$document.'" target="_blank">'.$document.'</a> - <a href="'.WEBSITE_URL.'/delete_restore.php?action=delete&uploadid='.$row['uploadid'].'&estimateid='.$estimateid.'&type='.$type.'" onclick="return confirm(\'Are you sure?\')">Delete</a></li>';
            }
        }
        echo '</ul>';
    }
}

?>
<div class="enter_cost additional_doc clearfix">
    <div class="clearfix"></div>

    <div class="form-group clearfix">
        <div class="col-sm-5">
            <input name="document[]" multiple type="file" data-filename-placement="inside" class="form-control" />
        </div>
    </div>

</div>

<div id="add_here_new_doc"></div>

<div class="form-group triple-gapped clearfix">
    <div class="col-sm-offset-4 col-sm-8">
        <button id="add_row_doc" class="btn brand-btn pull-left">Add Another Document</button>
    </div>
</div>