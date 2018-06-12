<!-- Coach Roster -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.add_coach').on( 'click', function () {
            addCoach();
        });

        $('#coach_upload_csv').on('change', function() {
            var file = $('#coach_upload_csv')[0].files[0];
            var fd = new FormData();
            fd.append('csv_file', file);
            $.ajax({
                url: 'ajax.php?fill=uploadCsv&category=coach',
                data: fd,
                type: 'POST',
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    for (var i = 0; i < response.length; i++) {
                        var counter = parseInt($('#coach_counter').val());
                        var curr = counter - 1;
                        var clone = $('.coach_roster').first().clone();
                        clone.find('.form-control').val('');

                        clone.find('.coach_first_name').attr('name', 'coach_first_name['+counter+']').val(response[i].first_name);
                        clone.find('.coach_last_name').attr('name', 'coach_last_name['+counter+']').val(response[i].last_name);
                        clone.find('.coach_email').attr('name', 'coach_email['+counter+']').val(response[i].email_address);
                        clone.find('.use_email_username').attr('name', 'use_email_username['+counter+']');
                        if(response[i].user_name_use_email == 'yes') {
                            clone.find('.use_email_username').attr('checked', 'checked');
                        }
                        clone.find('.coach_username').attr('name', 'coach_username['+counter+']').val(response[i].user_name);
                        clone.find('.auto_password').attr('name', 'auto_password['+counter+']');
                        if(response[i].password_auto_generate == 'yes') {
                            clone.find('.auto_password').attr('checked', 'checked');
                        }
                        clone.find('.coach_password').attr('name', 'coach_password['+counter+']').val(response[i].password);
                        clone.find('.email_login').attr('name', 'email_login['+counter+']');
                        if(response[i].email_login_credentials == 'yes') {
                            clone.find('.email_login').attr('checked', 'checked');
                        }

                        clone.find('.add_coach').on('click', function() {
                            addCoach();
                        });

                        $('.coach_roster').last().after(clone);

                        $('#coach_counter').val(counter+1);
                    }
                }
            });
        });
    });

    function addCoach() {
        var counter = parseInt($('#coach_counter').val());
        var curr = counter - 1;
        var clone = $('.coach_roster').first().clone();
        clone.find('.form-control').val('');

        clone.find('.coach_first_name').attr('name', 'coach_first_name['+counter+']');
        clone.find('.coach_last_name').attr('name', 'coach_last_name['+counter+']');
        clone.find('.coach_email').attr('name', 'coach_email['+counter+']');
        clone.find('.use_email_username').attr('name', 'use_email_username['+counter+']');
        clone.find('.coach_username').attr('name', 'coach_username['+counter+']');
        clone.find('.auto_password').attr('name', 'auto_password['+counter+']');
        clone.find('.coach_password').attr('name', 'coach_password['+counter+']');
        clone.find('.email_login').attr('name', 'email_login['+counter+']');

        clone.find('.add_coach').on('click', function() {
            addCoach();
        });

        $('.coach_roster').last().after(clone);

        $('#coach_counter').val(counter+1);

        return false;
    }
    
    function deleteCoach(sel) {
        if ($('.coach_roster').length <= 1) {
            addCoach();
        }
        $(sel).closest('.coach_roster').remove();
    }

    function deleteCoachId(sel, contactid) {
        var contactid = contactid;
        $.ajax({
            url: 'ajax.php?fill=deleteContactId&contactid='+contactid,
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $(sel).closest('.coach_roster_existing').remove();
            }
        });
    }

    function useEmailCoach(sel) {
        if($(sel).is(':checked')) {
            var email_address = $(sel).closest('.coach_roster').find('.coach_email').val();
            $(sel).closest('.coach_roster').find('.coach_username').val(email_address);
            $(sel).closest('.coach_roster').find('.coach_username').prop('readonly', true);
        } else {
            $(sel).closest('.coach_roster').find('.coach_username').prop('readonly', false);
        }
    }

    function updateUsernameEmailCoach(input) {
        if($(input).closest('.coach_roster').find('.use_email_username').is(':checked')) {
            $(input).closest('.coach_roster').find('.coach_username').val(input.value);
        }
    }

    function autoGeneratePasswordCoach(sel) {
        if($(sel).is(':checked')) {
            var alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var password = '';
            for (var i = 0; i < 8; i++) {
                var rng = Math.floor(Math.random() * alphabet.length);
                password += alphabet.substring((rng - 1), rng);
            }
            $(sel).closest('.coach_roster').find('.coach_password').val(password);
            $(sel).closest('.coach_roster').find('.coach_password').prop('readonly', true);
        } else {
            $(sel).closest('.coach_roster').find('.coach_password').prop('readonly', false);
        }
    }
</script>

<div class="accordion-block-details padded" id="coach_roster">
    <div class="accordion-block-details-heading">
        <h4 class="col-sm-7">Coach Roster</h4>
        <div class="col-sm-5 text-right">
            <a href="sales_order_contacts.csv"><label for="coach_download_csv" class="custom-file-upload default-background">Download CSV</label></a>
            <label for="coach_upload_csv" class="custom-file-upload default-background" style="display: inline;">Upload a CSV</label>
            <input type="file" name="coach_upload_csv" id="coach_upload_csv" class="file-upload" value="" />
        </div>
        <div class="clearfix"></div>
    </div>
    <?php
    if ($businessid > 0) {
        $coach_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `businessid` = '$businessid' AND `category` = 'Coach' AND `deleted` = 0".$classification_query),MYSQLI_ASSOC));
        foreach ($coach_list as $coachid) { ?>
            <div class="coach_roster_existing">
                <div class="row set-row-height gap-top">
                    <div class="col-sm-3 gap-md-left-15">First Name:</div>
                    <div class="col-sm-7"><input type="text" class="form-control coach_first_name" value="<?= get_contact($dbc, $coachid, 'first_name') ?>" readonly /></div>
                </div>
                <div class="row set-row-height">
                    <div class="col-sm-3 gap-md-left-15">Last Name:</div>
                    <div class="col-sm-7"><input type="text" class="form-control coach_last_name" value="<?= get_contact($dbc, $coachid, 'last_name') ?>" readonly /></div>
                </div>
                <div class="row set-row-height">
                    <div class="col-sm-3 gap-md-left-15">Email:</div>
                    <div class="col-sm-7"><input type="text" class="form-control coach_email" value="<?= get_contact($dbc, $coachid, 'email_address') ?>" readonly /></div>
                </div>
                <div class="clearfix"></div>
    
                <div class="row set-row-height">
                    <div class="col-sm-12 text-right">
                    <a href="#" onclick="deleteCoach(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a></div>
                </div>
            </div>
        <?php }
    } ?>
    <?php $coach_counter = 0; ?>
    <div class="coach_roster">
        <div class="row set-row-height gap-top">
            <div class="col-sm-3 gap-md-left-15">First Name:</div>
            <div class="col-sm-7"><input type="text" name="coach_first_name[<?= $coach_counter ?>]" class="form-control coach_first_name" value="" /></div>
        </div>
        <div class="row set-row-height">
            <div class="col-sm-3 gap-md-left-15">Last Name:</div>
            <div class="col-sm-7"><input type="text" name="coach_last_name[<?= $coach_counter ?>]" class="form-control coach_last_name" value="" /></div>
        </div>
        <div class="row set-row-height">
            <div class="col-sm-3 gap-md-left-15">Email:</div>
            <div class="col-sm-7"><input type="text" name="coach_email[<?= $coach_counter ?>]" class="form-control coach_email" value="" onkeyup="updateUsernameEmailCoach(this);" onblur="updateUsernameEmailCoach(this);" /></div>
        </div>
        <div class="row set-row-height">
            <div class="col-sm-3 gap-md-left-15">Username:</div>
            <div class="col-sm-3"><input type="checkbox" name="use_email_username[<?= $coach_counter ?>]" value="" class="use_email_username" onchange="useEmailCoach(this);"/> <small>Use Email</small></div>
            <div class="col-sm-4"><input type="text" name="coach_username[<?= $coach_counter ?>]" class="form-control coach_username" value="" /></div>
        </div>
        <div class="row set-row-height">
            <div class="col-sm-3 gap-md-left-15">Password:</div>
            <div class="col-sm-3"><input type="checkbox" name="auto_password[<?= $coach_counter ?>]" value="" class="auto_password" onchange="autoGeneratePasswordCoach(this);" /> <small>Auto Generate</small></div>
            <div class="col-sm-4"><input type="text" name="coach_password[<?= $coach_counter ?>]" class="form-control coach_password" value="" /></div>
        </div>
        <div class="row set-row-height">
            <div class="col-sm-3 gap-md-left-15">Email Login Credentials:</div>
            <div class="col-sm-2"><input type="checkbox" name="email_login[<?= $coach_counter ?>]" value="" class="email_login" /></div>
        </div>
        <div class="clearfix"></div>
    
        <div class="row set-row-height">
            <div class="col-sm-12 text-right">
            <a href="#" onclick="deleteCoach(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>&nbsp;&nbsp;<a href="#" class="add_coach"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a></div>
        </div>
    </div><!-- .coach_roster -->
    <?php $coach_counter++; ?>

    <input type="hidden" id="coach_counter" name="coach_counter" value="<?= $coach_counter ?>">
</div>