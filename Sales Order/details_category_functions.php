<script type="text/javascript">
/** ORDER FUNCTIONS **/
//Delete
function deleteRow(sel, hide) {
    var typeId = sel.id;
    var arr    = typeId.split('_');
    var sotid  = arr[1];
    $('#'+hide+arr[1]).hide();
    $.ajax({
        type: "GET",
        url: "ajax.php?fill=removeItem&sotid="+sotid,
        dataType: "html",
        success: function(response) {
        }
    });
}

//Edit Heading
function editHeading(sel) {
    var row = $(sel).closest('.heading_row');
    row.find('.heading_row_text').hide();
    row.find('.heading_row_edit').show();
}

//Edit Heading Display Quantity
function displayMandatoryQuantity(sel) {
    if ($(sel).is(':checked')) {
        $(sel).closest('.heading_row_edit').find('.mandatory_quantity').show();
    } else {
        $(sel).closest('.heading_row_edit').find('.mandatory_quantity').hide();
    }
}

//Save Heading
function saveHeading(sel) {
    var sotid = $('#sotid').val();
    var item_type = $(sel).data('category');
    var contact_category = $(sel).data('contact-category');
    var old_heading_name = $(sel).data('heading-name');
    var row = $(sel).closest('.heading_details');
    var heading_name = row.find('[name="heading_name"]').val();
    var mandatory_checkbox = 0;
    if (row.find('[name="mandatory_checkbox"]').is(':checked')) {
        mandatory_checkbox = 1;
    }
    var mandatory_quantity = row.find('[name="mandatory_quantity"]').val();
    $.ajax({
        type: "POST",
        url: "ajax.php?from_type=sot&fill=updateHeading&sotid="+sotid+"&item_type="+item_type+"&contact_category="+contact_category+"&old_heading_name="+old_heading_name+"&mandatory_checkbox="+mandatory_checkbox+"&mandatory_quantity="+mandatory_quantity+"&heading_name="+heading_name,
        dataType: "html",
        success: function(response) {
            $(sel).attr('data-heading-name', heading_name);
            $(sel).closest('.heading_row').find('.heading_row_text').show().find('b').text(response);
            $(sel).closest('.heading_row').find('.heading_row_edit').hide();
        }
    })
}

//Edit Price
function editPrice(sel) {
    var row = $(sel).closest('.row');
    row.find('.price_text').hide();
    row.find('.price_input').show().find('input').focus();
}

//Update Price
function updatePrice(sel) {
    if(parseFloat($(sel).val()) < 0) {
        $(sel).val(0.00);
    }
    var row = $(sel).closest('.row');
    var sotid = row.attr('id').split('_')[1];
    var price = $(sel).val();
    $.ajax({
        async: false,
        url: 'ajax.php?fill=updateProductPrice&sotid='+sotid+'&price='+price,
        type: "GET",
        dataType: "html",
        success: function(response) {
            row.find('.price_text_number').text(response);
            $(sel).val(response);
            row.find('.price_text').show();
            row.find('.price_input').hide();
        }
    });
}

//Update Price
function updateTime(sel) {
    var row = $(sel).closest('.row');
    var sotid = row.attr('id').split('_')[1];
    var time = $(sel).val();
    var previous_time = $(sel).data('initial');
    $.ajax({
        async: false,
        url: 'ajax.php?fill=updateProductTime&sotid='+sotid+'&time_estimate='+time,
        type: "GET",
        dataType: "html",
        success: function(response) {
            $(sel).data('initial', $(sel).val())

            var price = row.find('[name="item_price_input"]').val();
            var minutes = time.split(':');
            minutes = (parseInt(minutes[0])*60) + parseInt(minutes[1]);
            var previous_minutes = previous_time.split(':');
            previous_minutes = (parseInt(previous_minutes[0])*60) + parseInt(previous_minutes[1]);

            if(previous_minutes > 0 && minutes > 0 && previous_minutes != minutes) {
                price = minutes / previous_minutes * price;
                row.find('[name="item_price_input"]').val(price);
                updatePrice(row.find('[name="item_price_input"]'));
            }
        }
    });
}

//Sortable items
function sortableItems() {
    $('.sortable_heading').sortable({
        items: '.sortable_row',
        handle: '.sortable_handle',
        stop: function(event, ui) {
            reorderItems();
        }
    });
    $('.item_div_block').sortable({
        items: '.sortable_heading',
        handle: '.heading_handle',
        stop: function(event, ui) {
            reorderItems();
        }
    });
}

//Sort order
function reorderItems() {
    var sotid = $('#sotid').val();
    $('.contact_type_div').each(function() {
        var contact_category = $(this).data('contact-category');
        $(this).find('.item_div_block').each(function() {
            var item_type = $(this).data('item-type');
            var heading_sortorder = 1;
            $(this).find('.sortable_heading').each(function() {
                var heading_name = $(this).find('[name="heading_name"]').val();
                var items = [];
                $(this).find('.sortable_row').each(function() {
                    items.push($(this).data('id'));
                });
                $.ajax({
                    url: '../Sales Order/ajax.php?fill=reoderItems',
                    method: 'POST',
                    data: { sotid: sotid, contact_category: contact_category, item_type: item_type, items: items, heading_sortorder: heading_sortorder },
                    success: function(response) {
                    }
                });
                heading_sortorder++;
            });
        });
    });
}
/** ORDER FUNCTIONS END **/

/** ROSTER FUNCTIONS **/
function uploadCsvCatContact(category) {
    var file = $('#'+category+'_upload_csv')[0].files[0];
    var fd = new FormData();
    fd.append('csv_file', file);
    $.ajax({
        url: 'ajax.php?fill=uploadCsv&category='+category+'',
        data: fd,
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            for (var i = 0; i < response.length; i++) {
                var counter = parseInt($('#'+category+'_counter').val());
                var curr = counter - 1;
                var clone = $('.'+category+'_roster').first().clone();
                clone.find('.form-control').val('');

                clone.find('.'+category+'_first_name').attr('name', category+'_first_name['+counter+']').val(response[i].first_name);
                clone.find('.'+category+'_last_name').attr('name', category+'_last_name['+counter+']').val(response[i].last_name);
                clone.find('.'+category+'_email').attr('name', category+'_email['+counter+']').val(response[i].email_address);
                clone.find('.team_number').attr('name', 'team_number['+counter+']').val(response[i].player_number);
                clone.find('.use_email_username').attr('name', 'use_email_username['+counter+']');
                if(response[i].user_name_use_email == 'yes') {
                    clone.find('.use_email_username').attr('checked', 'checked');
                }
                clone.find('.'+category+'_username').attr('name', category+'_username['+counter+']').val(response[i].user_name);
                clone.find('.auto_password').attr('name', 'auto_password['+counter+']');
                if(response[i].password_auto_generate == 'yes') {
                    clone.find('.auto_password').attr('checked', 'checked');
                }
                clone.find('.'+category+'_password').attr('name', category+'_password['+counter+']').val(response[i].password);
                clone.find('.'+category+'_email_login').attr('name', category+'_email_login['+counter+']');
                if(response[i].email_login_credentials == 'yes') {
                    clone.find('.'+category+'_email_login').attr('checked', 'checked');
                }

                $('.'+category+'_roster').last().after(clone);

                $('#'+category+'_counter').val(counter+1);
            }
        }
    });
}

function addCatContact(category) {
    var counter = parseInt($('#'+category+'_counter').val());
    var curr = counter - 1;
    var clone = $('.'+category+'_roster').first().clone();
    clone.find('.form-control').val('');

    clone.find('.'+category+'_first_name').attr('name', category+'_first_name['+counter+']');
    clone.find('.'+category+'_last_name').attr('name', category+'_last_name['+counter+']');
    clone.find('.'+category+'_email').attr('name', category+'_email['+counter+']');
    clone.find('.'+category+'_number').attr('name', category+'_number['+counter+']');
    clone.find('.use_email_username').attr('name', 'use_email_username['+counter+']');
    clone.find('.'+category+'_username').attr('name', category+'_username['+counter+']');
    clone.find('.auto_password').attr('name', 'auto_password['+counter+']');
    clone.find('.'+category+'_password').attr('name', category+'_password['+counter+']');
    clone.find('.'+category+'_email_login').attr('name', category+'_email_login['+counter+']');

    $('.'+category+'_roster').last().after(clone);

    $('#'+category+'_counter').val(counter+1);

    return false;
}

function deleteCatContact(sel, category) {
    if ($('.'+category+'_roster').length <= 1) {
        addCatContact(category);
    }
    $(sel).closest('.'+category+'_roster').remove();
}

function deleteCatContactId(sel, contactid, category) {
    var contactid = contactid;
    $.ajax({
        url: 'ajax.php?fill=deleteContactId&contactid='+contactid,
        type: 'GET',
        dataType: 'html',
        success: function(response) {
            $(sel).closest('.'+category+'_roster_existing').remove();
        }
    });
}

function useEmailCatContact(sel, category) {
    if($(sel).is(':checked')) {
        var email_address = $(sel).closest('.'+category+'_roster').find('.'+category+'_email').val();
        $(sel).closest('.'+category+'_roster').find('.'+category+'_username').val(email_address);
        $(sel).closest('.'+category+'_roster').find('.'+category+'_username').prop('readonly', true);
    } else {
        $(sel).closest('.'+category+'_roster').find('.'+category+'_username').prop('readonly', false);
    }
}

function updateUsernameEmail(input, category) {
    if($(input).closest('.'+category+'_roster').find('.use_email_username').is(':checked')) {
        $(input).closest('.'+category+'_roster').find('.'+category+'_username').val(input.value);
    }
}

function autoGeneratePassword(sel, category) {
    if($(sel).is(':checked')) {
        var alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var password = '';
        for (var i = 0; i < 8; i++) {
            var rng = Math.floor(Math.random() * alphabet.length);
            password += alphabet.substring((rng - 1), rng);
        }
        $(sel).closest('.'+category+'_roster').find('.'+category+'_password').val(password);
        $(sel).closest('.'+category+'_roster').find('.'+category+'_password').prop('readonly', true);
    } else {
        $(sel).closest('.'+category+'_roster').find('.'+category+'_password').prop('readonly', false);
    }
}

function downloadCsv(category) {
    $.ajax({
        url: 'ajax.php?fill=downloadCsv&category='+category,
        type: 'GET',
        dataType: 'html',
        success: function(response) {
            window.location.href = response;
        }
    });
}
/** ROSTER FUNCTIONS END **/
</script>