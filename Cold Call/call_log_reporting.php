<?php
    $query_check_credentials = "SELECT first_name,last_name FROM contacts WHERE contactid = " . $_SESSION['contactid'];
    $result = mysqli_fetch_assoc(mysqli_query($dbc, $query_check_credentials));
    $firstname = decryptIt($result['first_name']);
    $lastname = decryptIt($result['last_name']);
    $name = $firstname . ' ' . $lastname;

    $query_check_credentials = "SELECT * FROM calllog_goals WHERE goal_setter = " . $_SESSION['contactid'];
    $goal_result = mysqli_query($dbc, $query_check_credentials);
    $missed_call = 0;
    $passed_due = 0;
    $abandoned_lead = 0;
    $move_lead = 0;
    $new_lead = 0;
    $lost = 0;
    if(!empty($goal_result)) {
        while($row = mysqli_fetch_assoc($goal_result)) {
            $missed_call += $row['missed_call'];
            $passed_due += $row['passed_due'];
            $abandoned_lead += $row['abandoned_lead'];
            $move_lead += $row['move_lead'];
            $new_lead += $row['new_lead'];
        }
    }

    $query_check_credentials = "SELECT count(contactid) as lost_count FROM calllog_pipeline WHERE status='Lost/Archive' AND contactid = " . $_SESSION['contactid'];
    $pipeline_result = mysqli_fetch_assoc(mysqli_query($dbc, $query_check_credentials));
    if(!empty($pipeline_result))
        $lost += $pipeline_result['lost_count'];
    
    $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab='cc_reporting'"));
    $note = $notes['note'];
    
    if ( !empty($note) ) { ?>
        <div class="notice popover-examples">
            <div class="col-sm-1 notice-icon"><img src="../img/info.png" class="wiggle-me" width="25"></div>
            <div class="col-sm-11"><span class="notice-name">NOTE:</span>
            <?= $note ?></div>
            <div class="clearfix"></div>
        </div><?php
    } ?>
        
<h4># of New Businesses Entered</h4>
<table class="table table-bordered">
    <tbody><tr class="hidden-xs hidden-sm">
        <th>Sales Person</th>
        <th># of New Businesses Entered</th>
        </tr>
            <tr>
                <td data-title="Sales Person" width="50%"><?php echo $name; ?></td>
                <td data-title="Actual Lead" width="50%">0</td>
            </tr>
        </tr>
    </tbody>
</table>

<h4># of New Contacts Entered</h4>
<table class="table table-bordered">
    <tbody><tr class="hidden-xs hidden-sm">
        <th>Sales Person</th>
        <th># of New Contacts Entered</th>
        </tr>
            <tr>
                <td data-title="Sales Person" width="50%"><?php echo $name; ?></td>
                <td data-title="Actual Lead" width="50%">0</td>
            </tr>
        </tr>
    </tbody>
</table>

<h4># of Cold Calls Added to Sales Pipeline</h4>
<table class="table table-bordered">
    <tbody><tr class="hidden-xs hidden-sm">
        <th>Sales Person</th>
        <th># of Cold Calls Added to Sales Pipeline</th>
        </tr>
            <tr>
                <td data-title="Sales Person" width="50%"><?php echo $name; ?></td>
                <td data-title="Actual Lead" width="50%">0</td>
            </tr>
        </tr>
    </tbody>
</table>

<h4># of Cold Calls Transferred</h4>
<table class="table table-bordered">
    <tbody><tr class="hidden-xs hidden-sm">
        <th>Sales Person</th>
        <th># of Cold Calls Transferred</th>
        </tr>
            <tr>
                <td data-title="Sales Person" width="50%"><?php echo $name; ?></td>
                <td data-title="Actual Lead" width="50%"><?php echo $move_lead; ?></td>
            </tr>
        </tr>
    </tbody>
</table>

<h4># of Cold Calls Lost/Archived</h4>
<table class="table table-bordered">
    <tbody><tr class="hidden-xs hidden-sm">
        <th>Sales Person</th>
        <th># of Cold Calls Lost/Archived</th>
        </tr>
            <tr>
                <td data-title="Sales Person" width="50%"><?php echo $name; ?></td>
                <td data-title="Actual Lead" width="50%"><?php echo $lost; ?></td>
            </tr>
        </tr>
    </tbody>
</table>

<h4># of Calls Made</h4>
<table class="table table-bordered">
    <tbody><tr class="hidden-xs hidden-sm">
        <th>Sales Person</th>
        <th># of Calls Made</th>
        </tr>
            <tr>
                <td data-title="Sales Person" width="50%"><?php echo $name; ?></td>
                <td data-title="Actual Lead" width="50%"><?php echo $new_lead; ?></td>
            </tr>
        </tr>
    </tbody>
</table>

<h4># of Missed Cold Calls</h4>
<table class="table table-bordered">
    <tbody><tr class="hidden-xs hidden-sm">
        <th>Sales Person</th>
        <th># of Missed Cold Calls</th>
        </tr>
            <tr>
                <td data-title="Sales Person" width="50%"><?php echo $name; ?></td>
                <td data-title="Actual Lead" width="50%"><?php echo $missed_call; ?></td>
            </tr>
        </tr>
    </tbody>
</table>

<h4># of Past Due Cold Calls</h4>
<table class="table table-bordered">
    <tbody><tr class="hidden-xs hidden-sm">
        <th>Sales Person</th>
        <th># of Past Due Cold Calls</th>
        </tr>
            <tr>
                <td data-title="Sales Person" width="50%"><?php echo $name; ?></td>
                <td data-title="Actual Lead" width="50%"><?php echo $passed_due; ?></td>
            </tr>
        </tr>
    </tbody>
</table>

<h4># of Abandoned Cold Calls</h4>
<table class="table table-bordered">
    <tbody><tr class="hidden-xs hidden-sm">
        <th>Sales Person</th>
        <th># of Abandoned Cold Calls</th>
        </tr>
            <tr>
                <td data-title="Sales Person" width="50%"><?php echo $name; ?></td>
                <td data-title="Actual Lead" width="50%"><?php echo $abandoned_lead; ?></td>
            </tr>
        </tr>
    </tbody>
</table>

<h4>Total Time Tracked on Calls</h4>
<table class="table table-bordered">
    <tbody><tr class="hidden-xs hidden-sm">
        <th>Sales Person</th>
        <th>Total Time Tracked on Calls</th>
        </tr>
            <tr>
                <td data-title="Sales Person" width="50%"><?php echo $name; ?></td>
                <td data-title="Actual Lead" width="50%">0</td>
            </tr>
        </tr>
    </tbody>
</table>