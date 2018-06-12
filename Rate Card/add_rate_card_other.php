<script>
$(document).ready(function() {
	//Expenses
    $('#add_row_other_detail').on( 'click', function () {
        var clone = $('.additional_other_detail').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_other_detail");
        $('#add_here_new_other_detail').append(clone);
        return false;
    });
});
</script>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix">
            <label class="col-sm-4 text-center">Detail</label>
            <label class="col-sm-1 text-center">Rate Card Price</label>
        </div>

        <div class="additional_other_detail clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix">
                <div class="col-sm-4" >
                    <input name="other_detail[]" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="otherfinalprice[]" type="text" class="form-control" />
                </div>
            </div>

        </div>

        <div id="add_here_new_other_detail"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_other_detail" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>