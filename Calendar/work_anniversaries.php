<?php
/* 
 * Work Anniversaries
 * Display work anniversaries of staff
 * included:
 *      calendars.php
 */
?>
<?php
include ('../include.php');
error_reporting(0);
checkAuthorised('calendar_rook');
?>
</head>

<body>
<?php
    include_once ('../navigation.php');
?>
<div class="container">
	<div class="row"><?php
        $staff_with_anniversary = '';
        $today = new DateTime(date('Y-m-d'));
        
        $work_anniversaries = mysqli_query($dbc, "SELECT d.contactid, c.first_name, c.last_name, d.start_date FROM contacts c LEFT JOIN contacts_dates d ON (d.contactid=c.contactid) WHERE d.start_date > 0 AND c.category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND c.status='1' AND c.deleted='0'");
        
        if ( $work_anniversaries->num_rows > 0 ) {
            while ( $row_contact=mysqli_fetch_assoc($work_anniversaries) ) {
                if ( date('m-d')==substr($row_contact['start_date'],5,5) ) {
                    $start_date = new DateTime($row_contact['start_date']);
                    $diff = $today->diff($start_date);
                    $staff_with_anniversary .= '<div class="gap-bottom"><b>' . decryptIt($row_contact['first_name']) .' '. decryptIt($row_contact['last_name']) .':</b> '. $diff->y . ' years</div>';
                }
            }
        }
        
        echo '<h1>Staff Work Anniversaries</h1>';
        
        if ( !empty($staff_with_anniversary) ) {
            echo '<div class="gap-left">' . $staff_with_anniversary . '</div>';
        } else {
            echo '<div class="gap-left">No staff is celebrating work anniversary today.</div>';
        } ?>
    </div><!-- .row -->
</div><!-- .container -->