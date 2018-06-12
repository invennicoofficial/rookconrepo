<script>
$(document).ready(function() {
	//Expenses
    $('#add_row_exp').on( 'click', function () {
        var clone = $('.additional_exp').clone();
        clone.find('.form-control').val('');
        clone.removeClass("additional_exp");
        $('#add_here_new_exp').append(clone);
        return false;
    });
});
</script>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix">
            <label class="col-sm-2 text-center">Type of Expense</label>
            <label class="col-sm-2 text-center">Category of Expense</label>
            <label class="col-sm-1 text-center">Rate Card Price</label>
        </div>

        <div class="additional_exp clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix">
                <div class="col-sm-2">
                    <select data-placeholder="Choose a Type..." name="expensetype[]" class="chosen-select-deselect1 form-control equipmentid" width="380">
                        <option value=''></option>
                        <option value='Clients'>Clients</option>
                        <option value='Packages & Promotions'>Packages & Promotions</option>
                        <option value='Vendor'>Vendor</option>
                        <option value='Equipment'>Equipment</option>
                        <option value='Services'>Services</option>
                        <option value='Staff'>Staff</option>
                        <option value='Contractors'>Contractors</option>
                        <option value='Expenses'>Expenses</option>
                        <option value='Custom'>Custom</option>
                    </select>
                </div>

                <div class="col-sm-2">
                    <select data-placeholder="Choose a Category..." name="expensecategory[]" class="chosen-select-deselect1 form-control equipmentid" width="380">
                        <option value=''></option>
                        <option value='Hotel'>Hotel</option>
                        <option value='Breakfast'>Breakfast</option>
                        <option value='Lunch'>Lunch</option>
                        <option value='Dinner'>Dinner</option>
                        <option value='Per Deum'>Per Deum</option>
                        <option value='Tickets'>Tickets</option>
                        <option value='Transportation'>Transportation</option>
                        <option value='Entertainment'>Entertainment</option>
                        <option value='Misc'>Misc</option>
                    </select>
                </div>
                <div class="col-sm-1" >
                    <input name="expfinalprice[]" id="expfinalprice_0" type="text" class="form-control" />
                </div>
            </div>

        </div>

        <div id="add_here_new_exp"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_exp" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>