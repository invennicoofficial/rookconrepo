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
function countOtherDetail() {
    var sum_fee = 0;
    $('[name="otherestimateprice[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });

    $('[name="other_detail_total"]').val(round2Fixed(sum_fee));
    $('[name="other_summary"]').val(round2Fixed(sum_fee));

    var other_budget = $('[name="other_budget"]').val();
    if(other_budget >= sum_fee) {
        $('[name="other_detail_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="other_detail_total"]').css("background-color", "#ff9999"); // Green
    }
}
</script>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix">
            <label class="col-sm-4 text-center">Detail</label>
            <label class="col-sm-1 text-center">Bid Price</label>
        </div>

        <div class="additional_other_detail clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix">
                <div class="col-sm-4" >
                    <input name="other_detail[]" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="otherestimateprice[]" onchange="countOtherDetail()" type="text" class="form-control" />
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
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Budget:</label>
    <div class="col-sm-8">
      <input name="other_budget" value="<?php echo $budget_price[12]; ?>" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Applied:</label>
    <div class="col-sm-8">
      <input name="other_detail_total" type="text" class="form-control">
    </div>
</div>