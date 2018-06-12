<?php
    $page_query = $_GET;
    unset($page_query['tab_name']);
    unset($page_query['safetyinspectid']);
    unset($page_query['endofday']);

    $tab_name = $_GET['tab_name'];
?>
<style>
table td {
    background-color: white;
}
</style>
<script type="text/javascript">
$(document).ready(function () {
    $(window).resize(function() {
        var available_height_main = window.innerHeight - $(footer).outerHeight() - $('.main-screen').offset().top - 14;
        var available_height_sidebar = $('.sidebar').outerHeight();
        available_height = available_height_main > available_height_sidebar ? available_height_main : available_height_sidebar;
        $('.main-screen').outerHeight('auto').css('min-height', available_height);
    }).resize();

    if ($(window).width() <= 767) {
        $('html,body').animate({scrollTop: $('#scrollHere').offset().top}, 50);
    }

    var last_timer_value = $("#last_timer_value").val();
    var active_status = $('#active_status').val();
    if(window.location.hash || active_status != '') {
        var type = window.location.hash.substr(1);
        if (!type) {
            type = $('#active_status').val();
        }
        $('#'+type).timer({
            seconds: last_timer_value //Specify start time in seconds
        });
        $('#'+type).show();
        $('.end_of_day').show();

        $('.dl_comment').hide();

        if(type == 'off_duty_timer') {
            $('#dl_sleep_comment_i').hide();
            $('#dl_driving_comment_i').hide();
            $('#dl_on_comment_i').hide();

            $('#driving_timer').parent('text').hide();
            $('#sleeper_berth_timer').parent('text').hide();
            $('#on_duty_timer').parent('text').hide();

            $('#dl_off_comment').show();
            $('#dl_off_comment_i').show();
            setActiveTimer('.off_duty');
        }

        if(type == 'driving_timer') {
            $('#dl_off_comment_i').hide();
            $('#dl_sleep_comment_i').hide();
            $('#dl_on_comment_i').hide();

            $('#off_duty_timer').parent('text').hide();
            $('#sleeper_berth_timer').parent('text').hide();
            $('#on_duty_timer').parent('text').hide();
            $('#dl_driving_comment').show();
            $('#dl_driving_comment_i').show();
            setActiveTimer('.driving');
        }
        if(type == 'sleeper_berth_timer') {
            $('#dl_off_comment_i').hide();
            $('#dl_driving_comment_i').hide();
            $('#dl_on_comment_i').hide();

            $('#off_duty_timer').parent('text').hide();
            $('#driving_timer').parent('text').hide();
            $('#on_duty_timer').parent('text').hide();
            $('#dl_sleep_comment').show();
            $('#dl_sleep_comment_i').show();
            setActiveTimer('.sleeper_berth');
        }
        if(type == 'on_duty_timer') {
            $('#dl_off_comment_i').hide();
            $('#dl_sleep_comment_i').hide();
            $('#dl_driving_comment_i').hide();

            $('#off_duty_timer').parent('text').hide();
            $('#driving_timer').parent('text').hide();
            $('#sleeper_berth_timer').parent('text').hide();
            $('#dl_on_comment').show();
            $('#dl_on_comment_i').show();
            setActiveTimer('.on_duty');
        }
    } else {
        $('#off_duty_timer').parent('text').hide();
        $('#driving_timer').parent('text').hide();
        $('#sleeper_berth_timer').parent('text').hide();
        $('#on_duty_timer').parent('text').hide();
        $('.end_of_day').hide();
        $('.dl_comment').hide();
    }

    //$('#off_duty_timer').hide();
    //$('#driving_timer').hide();
    //$('#sleeper_berth_timer').hide();
    //$('#on_duty_timer').hide();

    $('.amendments').hide();
    //$('.amendments_data').hide();

    var hasTimer = false;
    // Init timer start

    function setActiveTimer(timer){
        $(timer).find('li').addClass('active blue');
    }

    $('.off_duty').on('click', function() {
        var dl_comment = '';
        var url_hash = '#'+$('#active_status').val();
        var activeTimer = url_hash;

        if(activeTimer == '#off_duty_timer'){
            if($('#dl_off_comment').val() != '') {
                dl_comment = $('#dl_off_comment').val();
            } else {
                dl_comment = 'Off-duty time';
            }
        }
        else if(activeTimer == '#on_duty_timer'){
            if($('#dl_on_comment').val() != '') {
                dl_comment = $('#dl_on_comment').val();
            } else {
                dl_comment = 'On-duty time';
            }
        }
        else if(activeTimer == '#sleeper_berth_timer'){
            if($('#dl_sleep_comment').val() != '') {
                dl_comment = $('#dl_sleep_comment').val();
            } else {
                dl_comment = 'Sleeper time';
            }
        }
        else if(activeTimer == '#driving_timer'){
            if($('#dl_driving_comment').val() != '') {
                dl_comment = $('#dl_driving_comment').val();
            } else {
                dl_comment = 'Driving time';
            }
        }
        $('.off_duty').find('li').addClass('active blue');

        $('.dl_comment').hide();
        $('#dl_off_comment').show();
        $('#dl_off_comment_i').show();
        $('.off_duty').parent('div').attr('style', 'pointer-events: none');
        $('.sleeper_berth').parent('div').removeAttr('style');
        $('.driving').parent('div').removeAttr('style');
        $('.on_duty').parent('div').removeAttr('style');

        $('.sleeper_berth').find('li').removeClass('active blue');
        $('.driving').find('li').removeClass('active blue');
        $('.on_duty').find('li').removeClass('active blue');

        $('.end_of_day').show();
        $('.amendments').show();
        var url_hash = '#'+$('#active_status').val();
        parent.location.hash = '';

        document.location.hash = "off_duty_timer";
        $('#off_duty_timer').parent('text').show();
        $(url_hash).parent('text').hide();

        hasTimer = true;
        var timer_value = $(url_hash).text();
        $(url_hash).timer('remove');
        //$('#off_duty_timer').timer('resume');
        $('#off_duty_timer').timer({
            seconds: 0 //Specify start time in seconds
        });

        var timer_name = url_hash.substring(1, url_hash.length);
        var drivinglogid = $('#drivinglogid').val();
        dl_comment = encodeURIComponent(dl_comment);

        $.ajax({    //create an ajax request to load_page.php
            type: "GET",
            url: "driving_log_ajax_all.php?fill=drivinglog&drivinglogid="+drivinglogid+"&timer_name="+timer_name+"&time="+timer_value+"&current_timer=off_duty_timer&dl_comment="+dl_comment,
            dataType: "html",   //expect html to be returned
            success: function(response) {
                $('.dl_comment').val('');
                $('#active_status').val('off_duty_timer');
                window.location.href = '?<?= http_build_query($page_query) ?>';
            }
        });

        return false;

    });

    $('.sleeper_berth').on('click', function() {
        var dl_comment = '';
        var url_hash = '#'+$('#active_status').val();
        var activeTimer = url_hash;

        if(activeTimer == '#off_duty_timer'){
            if($('#dl_off_comment').val() != '') {
                dl_comment = $('#dl_off_comment').val();
            } else {
                dl_comment = 'Off-duty time';
            }
        }
        else if(activeTimer == '#on_duty_timer'){
            if($('#dl_on_comment').val() != '') {
                dl_comment = $('#dl_on_comment').val();
            } else {
                dl_comment = 'On-duty time';
            }
        }
        else if(activeTimer == '#sleeper_berth_timer'){
            if($('#dl_sleep_comment').val() != '') {
                dl_comment = $('#dl_sleep_comment').val();
            } else {
                dl_comment = 'Sleeper time';
            }
        }
        else if(activeTimer == '#driving_timer'){
            if($('#dl_driving_comment').val() != '') {
                dl_comment = $('#dl_driving_comment').val();
            } else {
                dl_comment = 'Driving time';
            }
        }

        if(dl_comment == '') {
            'Sleeper time';
        }
        $('.sleeper_berth').find('li').addClass('active blue');

        $('.dl_comment').hide();
        $('#dl_sleep_comment').show();
        $('#dl_sleep_comment_i').show();
        $('.off_duty').parent('div').removeAttr('style');
        $('.sleeper_berth').parent('div').attr('style', 'pointer-events: none');
        $('.driving').parent('div').removeAttr('style');
        $('.on_duty').parent('div').removeAttr('style');

        $('.off_duty').find('li').removeClass('active blue');
        $('.driving').find('li').removeClass('active blue');
        $('.on_duty').find('li').removeClass('active blue');

        $('.end_of_day').show();
        $('.amendments').show();
        var url_hash = '#'+$('#active_status').val();
        parent.location.hash = '';

        document.location.hash = "sleeper_berth_timer";
        $('#sleeper_berth_timer').parent('text').show();
        $(url_hash).parent('text').hide();

        hasTimer = true;

        var timer_value = $(url_hash).text();
        $(url_hash).timer('remove');
        //$('#sleeper_berth_timer').timer('resume');
        $('#sleeper_berth_timer').timer({
            seconds: 0 //Specify start time in seconds
        });
        var timer_name = url_hash.substring(1, url_hash.length);
        var drivinglogid = $('#drivinglogid').val();

        dl_comment = encodeURIComponent(dl_comment);

        $.ajax({    //create an ajax request to load_page.php
            type: "GET",
            url: "driving_log_ajax_all.php?fill=drivinglog&drivinglogid="+drivinglogid+"&timer_name="+timer_name+"&time="+timer_value+"&current_timer=sleeper_berth_timer&dl_comment="+dl_comment,
            dataType: "html",   //expect html to be returned
            success: function(response) {
                $('.dl_comment').val('');
                $('#active_status').val('sleeper_berth_timer');
                window.location.href = '?<?= http_build_query($page_query) ?>';
            }
        });

        return false;
    });

    $('.on_duty').on('click', function() {
        var dl_comment = '';
        var url_hash = '#'+$('#active_status').val();
        var activeTimer = url_hash;

        if(activeTimer == '#off_duty_timer'){
            if($('#dl_off_comment').val() != '') {
                dl_comment = $('#dl_off_comment').val();
            } else {
                dl_comment = 'Off-duty time';
            }
        }
        else if(activeTimer == '#on_duty_timer'){
            if($('#dl_on_comment').val() != '') {
                dl_comment = $('#dl_on_comment').val();
            } else {
                dl_comment = 'On-duty time';
            }
        }
        else if(activeTimer == '#sleeper_berth_timer'){
            if($('#dl_sleep_comment').val() != '') {
                dl_comment = $('#dl_sleep_comment').val();
            } else {
                dl_comment = 'Sleeper time';
            }
        }
        else if(activeTimer == '#driving_timer'){
            if($('#dl_driving_comment').val() != '') {
                dl_comment = $('#dl_driving_comment').val();
            } else {
                dl_comment = 'Driving time';
            }
        }

        if(dl_comment == '') {
            'On-duty time';
        }
        $('.on_duty').find('li').addClass('active blue');

        $('.dl_comment').hide();
        $('#dl_on_comment').show();
        $('#dl_on_comment_i').show();
        $('.off_duty').parent('div').removeAttr('style');
        $('.sleeper_berth').parent('div').removeAttr('style');
        $('.driving').parent('div').removeAttr('style');
        $('.on_duty').parent('div').attr('style', 'pointer-events: none');

        $('.off_duty').find('li').removeClass('active blue');
        $('.sleeper_berth').find('li').removeClass('active blue');
        $('.driving').find('li').removeClass('active blue');

        $('.end_of_day').show();
        $('.amendments').show();
        var url_hash = '#'+$('#active_status').val();
        parent.location.hash = '';

        document.location.hash = "on_duty_timer";
        $('#on_duty_timer').parent('text').show();
        $(url_hash).parent('text').hide();

        hasTimer = true;

        var timer_value = $(url_hash).text();
        $(url_hash).timer('remove');
        //$('#on_duty_timer').timer('resume');
        $('#on_duty_timer').timer({
            seconds: 0 //Specify start time in seconds
        });
        var timer_name = url_hash.substring(1, url_hash.length);
        var drivinglogid = $('#drivinglogid').val();
        dl_comment = encodeURIComponent(dl_comment);
        $.ajax({    //create an ajax request to load_page.php
            type: "GET",
            url: "driving_log_ajax_all.php?fill=drivinglog&drivinglogid="+drivinglogid+"&timer_name="+timer_name+"&time="+timer_value+"&current_timer=on_duty_timer&dl_comment="+dl_comment,
            dataType: "html",   //expect html to be returned
            success: function(response) {
                $('.dl_comment').val('');
                $('#active_status').val('on_duty_timer');
                window.location.href = '?<?= http_build_query($page_query) ?>';
            }
        });

        return false;
    });

    $('.end_of_day').on('click', function() {
        var drivinglogid = $('#drivinglogid').val();
        var vehicle_status = $("#vehicle_status").val();
        if (vehicle_status != 'Done') {
            alert('Please fill out Post-Trip Checklist before ending your day.');
            window.location.href = "add_driving_log.php?tab_name=checklist&endofday=on&drivinglogid="+drivinglogid;
        } else if(confirm('Are you sure you want to end your day?')){
            var url_hash = '#'+$('#active_status').val();
            var activeTimer = url_hash;
            var dl_comment = '';

            if(activeTimer == '#off_duty_timer'){
                if($('#dl_off_comment').val() != '') {
                    dl_comment = $('#dl_off_comment').val();
                } else {
                    dl_comment = 'Off-duty time';
                }
            }
            else if(activeTimer == '#on_duty_timer'){
                if($('#dl_on_comment').val() != '') {
                    dl_comment = $('#dl_on_comment').val();
                } else {
                    dl_comment = 'On-duty time';
                }
            }
            else if(activeTimer == '#sleeper_berth_timer'){
                if($('#dl_sleep_comment').val() != '') {
                    dl_comment = $('#dl_sleep_comment').val();
                } else {
                    dl_comment = 'Sleeper time';
                }
            }
            else if(activeTimer == '#driving_timer'){
                if($('#dl_driving_comment').val() != '') {
                    dl_comment = $('#dl_driving_comment').val();
                } else {
                    dl_comment = 'Driving time';
                }
            }

            if($('.hide_over_24hours_check').val() !== 'over') {
                if(dl_comment == '') {
                    dl_comment = 'End of day';
                }
                var timer_value = $(url_hash).text();
            } else {
                dl_comment = 'Over 24 hours';
                var timer_value = '00:00:00';
            }

            parent.location.hash = '';

            $(url_hash).hide();

            var timer_value = $(url_hash).text();
            $(url_hash).timer('remove');
            var timer_name = url_hash.substring(1, url_hash.length);
            dl_comment = encodeURIComponent(dl_comment);
            $.ajax({    //create an ajax request to load_page.php
                type: "GET",
                url: "driving_log_ajax_all.php?fill=drivinglog&end=end&drivinglogid="+drivinglogid+"&timer_name="+timer_name+"&time="+timer_value+"&dl_comment="+dl_comment,
                dataType: "html",   //expect html to be returned
                success: function(response) {
                    $('.dl_comment').val('');
                    window.location.href = "amendments.php?graph=off&drivinglogid="+drivinglogid;
                }
            });
        }

        return false;
    });

    $('.amendments').on('click', function() {
        $('.amendments_data').show();
        return false;
    });
});
function loadInspectionGraph() {
    var url_hash = '#'+$('#active_status').val();
    var timer_value = $(url_hash).text();
    //$('#off_duty_timer').timer('resume');

    var timer_name = url_hash.substring(1, url_hash.length);
    var drivinglogid = $('#drivinglogid').val();
    console.log("driving_log_ajax_all.php?view_log_info=1&timer_valu="+timer_value+"&timer_names="+timer_name+"&drivinglogid="+drivinglogid);

    //window.location.href = 'add_driving_log.php?<?php echo $_SERVER['QUERY_STRING']; ?>&view_log_info=1'+window.location.hash;
    $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "driving_log_ajax_all.php?view_log_info=1&timer_valu="+timer_value+"&timer_names="+timer_name+"&drivinglogid="+drivinglogid,
        dataType: "html",   //expect html to be returned
        success: function(response){
        }
    });
}
</script>
<div class="col-sm-3" style="padding: 0;">
    <ul class="sidebar sidebar-block">
        <a href="driving_log_tiles.php" class="allow_view_only">Back to Dashboard</a>
        <h3 style="padding: 0; margin: 0.2em 0 0.2em 0"><a href="?<?php echo http_build_query($page_query); ?>" class="active" onclick="">Status</a></h3>
        <ul>
            <div <?= $timer_name[0] == 'off_duty_timer' ? 'style="pointer-events: none;"' : '' ?>><a href="" class="off_duty"><li class="<?= $timer_name[0] == 'off_duty_timer' ? 'active blue' : '' ?>">Off-Duty</li></a></div>
            <div <?= $timer_name[0] == 'sleeper_berth_timer' ? 'style="pointer-events: none;"' : '' ?>><a href="" class="sleeper_berth"><li class="<?= $timer_name[0] == 'sleeper_berth_timer' ? 'active blue' : '' ?>">Sleeper</li></a></div>
            <div <?= $timer_name[0] == 'driving_timer' ? 'style="pointer-events: none;"' : '' ?>><a href="?<?php $page_query['tab_name'] = 'checklist'; echo http_build_query($page_query); unset($page_query['tab_name']); ?>" class="driving"><li class="<?= $timer_name[0] == 'driving_timer' ? 'active blue' : '' ?>">Driving</li></a></div>
            <div <?= $timer_name[0] == 'on_duty_timer' ? 'style="pointer-events: none;"' : '' ?>><a href="" class="on_duty"><li class="<?= $timer_name[0] == 'on_duty_timer' ? 'active blue' : '' ?>">On-Duty</li></a></div>
            <div style="padding-top: 0.2em;">
                <input type="text" id="dl_off_comment" name="dl_off_comment" placeholder="Include comment with Status" <?= $timer_name[0] == 'off_duty_timer' ? '' : 'style="display: none;"' ?> class="form-control dl_comment">
                <input type="text" id="dl_sleep_comment" name="dl_sleep_comment" placeholder="Include comment with Status" <?= $timer_name[0] == 'sleeper_berth_timer' ? '' : 'style="display: none;"' ?> class="form-control dl_comment">
                <input type="text" id="dl_driving_comment" name="dl_driving_comment" placeholder="Include comment with Status" <?= $timer_name[0] == 'driving_timer' ? '' : 'style="display: none;"' ?> class="form-control dl_comment">
                <input type="text" id="dl_on_comment" name="dl_on_comment" placeholder="Include comment with Status" <?= $timer_name[0] == 'on_duty_timer' ? '' : 'style="display: none;"' ?> class="form-control dl_comment">
            </div>
        </ul>
        <a href="?<?php $page_query['tab_name'] = 'cycle'; echo http_build_query($page_query); ?>"><li id="cycle_time_link" <?= $tab_name == 'cycle' ? 'class="active blue"' : '' ?>>Cycle Time</li></a>
        <a href="?<?php $page_query['tab_name'] = 'inspection'; echo http_build_query($page_query); ?>" onclick="loadInspectionGraph();"><li id="inspection_mode_link" <?= $tab_name == 'inspection' || $_GET['safetyinspectid'] ? 'class="active blue"' : '' ?>>Inspection Mode</li></a>
        <ul>
            <a href="?<?php $page_query['tab_name'] = 'inspection'; echo http_build_query($page_query); ?>" onclick="loadInspectionGraph();"><li <?= $tab_name == 'inspection' ? 'class="active blue"' : '' ?>>Amendments/Graph</li></a>
            <?php
                $query_checklists = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `driving_log_safety_inspect` WHERE `drivinglogid` = '$drivinglogid' ORDER BY `safetyinspectid` DESC"),MYSQLI_ASSOC);
                foreach ($query_checklists as $checklist) {
                    $page_query['tab_name'] = 'checklist';
                    $page_query['safetyinspectid'] = $checklist['safetyinspectid'];
                    $checklist_name = ' (';
                    if (!empty($checklist['safety_inspect_vehicleid'])) {
                        $checklist_name .= 'Vehicle #'.get_equipment_field($dbc, $checklist['safety_inspect_vehicleid'], 'unit_number');
                    }
                    if (!empty($checklist['safety_inspect_vehicleid']) && !empty($checklist['safety_inspect_trailerid'])) {
                        $checklist_name .= ', ';
                    }
                    if (!empty($checklist['safety_inspect_trailerid'])) {
                        $checklist_name .= 'Trailer #'.get_equipment_field($dbc, $checklist['safety_inspect_trailerid'], 'unit_number');
                    }
                    $checklist_name .= ')';
                    echo '<a href="?'.http_build_query($page_query).'"><li '.($_GET['safetyinspectid'] == $checklist['safetyinspectid'] ? 'class="active blue"' : '').'>Safety Checklist'.($checklist_name != ' ()' ? $checklist_name : '').'</li></a>';
                }
                unset($page_query['safetyinspectid']);
            ?>
        </ul>
        <a href="?<?php $page_query['tab_name'] = 'notices'; echo http_build_query($page_query); ?>"><li id="notices_link" <?= $tab_name == 'notices' ? 'class="active blue"' : '' ?>>Notices</li></a>
        <a href="?<?php $page_query['tab_name'] = 'hours_of_service'; echo http_build_query($page_query); ?>"><li id="hours_link" <?= $tab_name == 'hours_of_service' ? 'class="active blue"' : '' ?>>Hours-of-Services Rules</li></a>
    </ul>
</div>

<?php unset($page_query['tab_name']); ?>

<div id="scrollHere" class="col-sm-9 has-main-screen" style="padding: 0;">
    <div class="main-screen">
        <div class="col-sm-4">
        <?php include('get_total_timer_times.php');
            if(isset($under_24hour_time)) { ?>
            <br /><div class='alert_noChrome' style='padding:10px;margin:15px; border:2px solid grey; border-radius:10px; width:80%; margin:auto; background-color:rgba(155,155,155,0.5); margin-bottom:10px;'>Your timers currently amount to 24 hours. You must make amendments and then <a onclick="window.location.reload(true);" style='cursor:pointer;'>refresh</a> the current page, if you would like to gain access to the timers again.</div>
        <?php } else { ?>
            <svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" xml:space="preserve" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 300 240">
                <g id="timer_circle">
                    <circle style="fill:none;stroke:#aaa;stroke-width:3;stroke-miterlimit:10;" cx="150" cy="120" r="110">
                    </circle>
                    
                    <text id="circle_off_duty" x="150" y="120" text-anchor="middle" <?= $timer_name[0] == 'off_duty_timer' ? '' : 'style="display: none;"' ?>>
                        <tspan font-size="25" id="off_duty_timer" x="150" dy="0" class="timer"></tspan><tspan x="150" dy="1.2em">Off-Duty</tspan>
                    </text>
                    <text id="circle_sleeper_berth" x="150" y="120" text-anchor="middle" <?= $timer_name[0] == 'sleeper_berth_timer' ? '' : 'style="display: none;"' ?>>
                        <tspan font-size="25" id="sleeper_berth_timer" x="150" dy="0" class="timer"></tspan><tspan x="150" dy="1.2em">Sleeper</tspan>
                    </text>
                    <text id="circle_driving" x="150" y="120" text-anchor="middle" <?= $timer_name[0] == 'driving_timer' ? '' : 'style="display: none;"' ?>>
                        <tspan font-size="25" id="driving_timer" x="150" dy="0" class="timer"></tspan><tspan x="150" dy="1.2em">Driving</tspan>
                    </text>
                    <text id="circle_on_duty" x="150" y="120" text-anchor="middle" <?= $timer_name[0] == 'on_duty_timer' ? '' : 'style="display: none;"' ?>>
                        <tspan font-size="25" id="on_duty_timer" x="150" dy="0" class="timer"></tspan><tspan x="150" dy="1.2em">On-Duty</tspan>
                    </text>
                </g>
            </svg>
        <?php } ?>
                <div style="position: relative; top: 2vw; margin-bottom: 2vw;">
                    <center>
                    <a href="?<?php echo http_build_query($page_query); ?>" id="view_statuses" class="btn brand-btn mobile-block view_statuses allow_view_only">Statuses</a>
                    <button type="submit" name="end_of_day" value="Submit" class="btn brand-btn mobile-block end_of_day">End Of Day</button>
                    </center>
                </div>
        </div>

        <div class="col-sm-8" style="padding: 0.5em 0.5em;">
            <?php if($tab_name == 'cycle') { ?>
                <a href="?<?= http_build_query($page_query) ?>" class="btn brand-btn hide-titles-mob allow_view_only">Back</a>
                <div class="clearfix"></div>
                <div id="cycle_time" style="position: relative; width:70%;">
                    <?php include('cycle_time_chart.php'); ?>
                </div>
            <?php } else if($tab_name == 'notices') { ?>
                <a href="?<?= http_build_query($page_query) ?>" class="btn brand-btn hide-titles-mob allow_view_only">Back</a>
                <div class="clearfix"></div><br />
                <div id="notices">
                    <?php include('notices.php'); ?>
                </div>
            <?php } else if($tab_name == 'inspection') { ?>
                <a href="?<?= http_build_query($page_query) ?>" class="btn brand-btn hide-titles-mob allow_view_only">Back</a>
                <div class="clearfix"></div><br />
                <div id="inspection_mode">
                    <?php include('amendments.php'); ?>
                </div>
            <?php } else if($tab_name == 'hours_of_service') { ?>
                <a href="?<?= http_build_query($page_query) ?>" class="btn brand-btn hide-titles-mob allow_view_only">Back</a>
                <div class="clearfix"></div><br />
                <div id="hours_of_service">
                    <?php include('hours_of_service.php'); ?>
                </div>
            <?php } else { ?>
                <div id="driving_log_timer" <?= $tab_name == 'checklist' ? 'style="display:none;"' : '' ?>>
                    <?php include('driving_log_timer.php'); ?>
                </div>
                <div id="driving_log_checklist" style="<?= $tab_name == 'checklist' ? '' : 'display:none;' ?>">
                    <?php include('driving_log_checklist.php'); ?>
                </div>
            <?php } ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="clearfix"></div>