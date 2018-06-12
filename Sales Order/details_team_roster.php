<!-- Team Roster -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.add_team').on( 'click', function () {
            addTeam();
        });

        $('#team_upload_csv').on('change', function() {
            var file = $('#team_upload_csv')[0].files[0];
            var fd = new FormData();
            fd.append('csv_file', file);
            $.ajax({
                url: 'ajax.php?fill=uploadCsv&category=team',
                data: fd,
                type: 'POST',
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    for (var i = 0; i < response.length; i++) {
                        var counter = parseInt($('#team_counter').val());
                        var curr = counter - 1;
                        var clone = $('.team_roster').first().clone();
                        clone.find('.form-control').val('');

                        clone.find('.team_first_name').attr('name', 'team_first_name['+counter+']').val(response[i].first_name);
                        clone.find('.team_last_name').attr('name', 'team_last_name['+counter+']').val(response[i].last_name);
                        clone.find('.team_email').attr('name', 'team_email['+counter+']').val(response[i].email_address);
                        clone.find('.team_number').attr('name', 'team_number['+counter+']').val(response[i].player_number);
                        clone.find('.use_email_username').attr('name', 'use_email_username_team['+counter+']');
                        if(response[i].user_name_use_email == 'yes') {
                            clone.find('.use_email_username').attr('checked', 'checked');
                        }
                        clone.find('.team_username').attr('name', 'team_username['+counter+']').val(response[i].user_name);
                        clone.find('.auto_password').attr('name', 'auto_password_team['+counter+']');
                        if(response[i].password_auto_generate == 'yes') {
                            clone.find('.auto_password').attr('checked', 'checked');
                        }
                        clone.find('.team_password').attr('name', 'team_password['+counter+']').val(response[i].password);
                        clone.find('.email_login').attr('name', 'email_login_team['+counter+']');
                        if(response[i].email_login_credentials == 'yes') {
                            clone.find('.email_login').attr('checked', 'checked');
                        }

                        clone.find('.add_team').on('click', function() {
                            addTeam();
                        });

                        $('.team_roster').last().after(clone);

                        $('#team_counter').val(counter+1);
                    }
                }
            });
        });
    });

    function addTeam() {
        var counter = parseInt($('#team_counter').val());
        var curr = counter - 1;
        var clone = $('.team_roster').first().clone();
        clone.find('.form-control').val('');

        clone.find('.team_first_name').attr('name', 'team_first_name['+counter+']');
        clone.find('.team_last_name').attr('name', 'team_last_name['+counter+']');
        clone.find('.team_email').attr('name', 'team_email['+counter+']');
        clone.find('.team_number').attr('name', 'team_number['+counter+']');
        clone.find('.use_email_username').attr('name', 'use_email_username_team['+counter+']');
        clone.find('.team_username').attr('name', 'team_username['+counter+']');
        clone.find('.auto_password').attr('name', 'auto_password_team['+counter+']');
        clone.find('.team_password').attr('name', 'team_password['+counter+']');
        clone.find('.email_login').attr('name', 'email_login_team['+counter+']');

        clone.find('.add_team').on('click', function() {
            addToach();
        });

        $('.team_roster').last().after(clone);

        $('#team_counter').val(counter+1);

        return false;
    }
    
    function deleteTeam(sel) {
        if ($('.team_roster').length <= 1) {
            addTeam();
        }
        $(sel).closest('.team_roster').remove();
    }

    function deleteTeamId(sel, contactid) {
        var contactid = contactid;
        $.ajax({
            url: 'ajax.php?fill=deleteContactId&contactid='+contactid,
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $(sel).closest('.team_roster_existing').remove();
            }
        });
    }

    function useEmail(sel) {
        if($(sel).is(':checked')) {
            var email_address = $(sel).closest('.team_roster').find('.team_email').val();
            $(sel).closest('.team_roster').find('.team_username').val(email_address);
            $(sel).closest('.team_roster').find('.team_username').prop('readonly', true);
        } else {
            $(sel).closest('.team_roster').find('.team_username').prop('readonly', false);
        }
    }

    function updateUsernameEmail(input) {
        if($(input).closest('.team_roster').find('.use_email_username').is(':checked')) {
            $(input).closest('.team_roster').find('.team_username').val(input.value);
        }
    }

    function autoGeneratePassword(sel) {
        if($(sel).is(':checked')) {
            var alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var password = '';
            for (var i = 0; i < 8; i++) {
                var rng = Math.floor(Math.random() * alphabet.length);
                password += alphabet.substring((rng - 1), rng);
            }
            $(sel).closest('.team_roster').find('.team_password').val(password);
            $(sel).closest('.team_roster').find('.team_password').prop('readonly', true);
        } else {
            $(sel).closest('.team_roster').find('.team_password').prop('readonly', false);
        }
    }
</script>

<div class="accordion-block-details padded" id="team_roster">
    <div class="accordion-block-details-heading">
        <h4 class="col-sm-7">Team Roster</h4>
        <div class="col-sm-5 text-right">
            <a href="sales_order_contacts.csv"><label for="team_download_csv" class="custom-file-upload default-background">Download CSV</label></a>
            <label for="team_upload_csv" class="custom-file-upload default-background" style="display: inline;">Upload a CSV</label>
            <input type="file" name="team_upload_csv" id="team_upload_csv" class="file-upload" value="" />
        </div>
        <div class="clearfix"></div>
    </div>
    <?php
    if ($businessid > 0) {
        $team_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `businessid` = '$businessid' AND `category` = 'Team' AND `deleted` = 0".$classification_query),MYSQLI_ASSOC));
        foreach ($team_list as $teamid) { ?>
            <div class="team_roster_existing">
                <div class="row set-row-height gap-top">
                    <div class="col-sm-3 gap-md-left-15">First Name:</div>
                    <div class="col-sm-7"><input type="text" class="form-control team_first_name" value="<?= get_contact($dbc, $teamid, 'first_name') ?>" readonly /></div>
                </div>
                <div class="row set-row-height">
                    <div class="col-sm-3 gap-md-left-15">Last Name:</div>
                    <div class="col-sm-7"><input type="text" class="form-control team_last_name" value="<?= get_contact($dbc, $teamid, 'last_name') ?>" readonly /></div>
                </div>
                <div class="row set-row-height">
                    <div class="col-sm-3 gap-md-left-15">Email:</div>
                    <div class="col-sm-7"><input type="text" class="form-control team_email" value="<?= get_contact($dbc, $teamid, 'email_address') ?>" readonly /></div>
                </div>
                <div class="row set-row-height">
                    <div class="col-sm-3 gap-md-left-15">Player Number:</div>
                    <div class="col-sm-7"><input type="text" class="form-control team_number" value="<?= get_contact($dbc, $teamid, 'player_number') ?>" readonly /></div>
                </div>
                <div class="clearfix"></div>
                
                <div class="row set-row-height">
                    <div class="col-sm-12 text-right">
                        <a href="#" onclick="deleteTeam(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a></div>
                </div>
            </div>
        <?php }
    } ?>
    <?php $team_counter = 0; ?>
    <div class="team_roster">
        <div class="row set-row-height gap-top">
            <div class="col-sm-3 gap-md-left-15">First Name:</div>
            <div class="col-sm-7"><input type="text" name="team_first_name[<?= $team_counter ?>]" class="form-control team_first_name" value="" /></div>
        </div>
        <div class="row set-row-height">
            <div class="col-sm-3 gap-md-left-15">Last Name:</div>
            <div class="col-sm-7"><input type="text" name="team_last_name[<?= $team_counter ?>]" class="form-control team_last_name" value="" /></div>
        </div>
        <div class="row set-row-height">
            <div class="col-sm-3 gap-md-left-15">Email:</div>
            <div class="col-sm-7"><input type="text" name="team_email[<?= $team_counter ?>]" class="form-control team_email" value="" onkeyup="updateUsernameEmail(this);" onblur="updateUsernameEmail(this);" /></div>
        </div>
        <div class="row set-row-height">
            <div class="col-sm-3 gap-md-left-15">Player Number:</div>
            <div class="col-sm-7"><input type="text" name="team_number[<?= $team_counter ?>]" class="form-control team_number" value="" /></div>
        </div>
        <div class="row set-row-height">
            <div class="col-sm-3 gap-md-left-15">Username:</div>
            <div class="col-sm-3"><input type="checkbox" name="use_email_username_team[<?= $team_counter ?>]" value="" class="use_email_username" onchange="useEmail(this);" /> <small>Use Email</small></div>
            <div class="col-sm-4"><input type="text" name="team_username[<?= $team_counter ?>]" class="form-control team_username" value="" /></div>
        </div>
        <div class="row set-row-height">
            <div class="col-sm-3 gap-md-left-15">Password:</div>
            <div class="col-sm-3"><input type="checkbox" name="auto_password_team[<?= $team_counter ?>]" value="" class="auto_password" onchange="autoGeneratePassword(this);" /> <small>Auto Generate</small></div>
            <div class="col-sm-4"><input type="text" name="team_password[<?= $team_counter ?>]" class="form-control team_password" value="" /></div>
        </div>
        <div class="row set-row-height">
            <div class="col-sm-3 gap-md-left-15">Email Login Credentials:</div>
            <div class="col-sm-2"><input type="checkbox" name="email_login_team[<?= $team_counter ?>]" value="" class="email_login" /></div>
        </div>
        <div class="clearfix"></div>
        
        <div class="row set-row-height">
            <div class="col-sm-12 text-right">
                <a href="#" onclick="deleteTeam(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>&nbsp;&nbsp;<a href="#" class="add_team"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a></div>
        </div>
    </div><!-- .team_roster -->
    <?php $team_counter++; ?>

    <input type="hidden" id="team_counter" name="team_counter" value="<?= $team_counter ?>">
</div>