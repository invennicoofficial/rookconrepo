<?php
$software_url = 'ffm.rookconnect.com';
require_once($software_url."/include.php");

//include ('../include.php');

$query = mysqli_query($dbc,"SELECT contactid, first_name, last_name, email_address FROM contacts WHERE deleted=0 AND category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." ORDER BY last_name");

while($row = mysqli_fetch_array($query)) {
    $search_user = $row['contactid'];

    $get_am = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(agendameetingid) AS total_am FROM agenda_meeting WHERE companycontactid LIKE '%," . $search_user . ",%' AND DATE(NOW()) = DATE(date_of_meeting)"));
    $total_am = $get_am['total_am'];

    $get_ticket = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(ticketid) AS total_ticket FROM tickets WHERE DATE(NOW()) BETWEEN to_do_date AND to_do_end_date AND status != 'Archived' AND contactid LIKE '%," . $search_user . ",%' ORDER BY ticketid DESC"));
    $total_ticket = $get_ticket['total_ticket'];

    $get_task = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(tasklistid) AS total_task FROM tasklist WHERE contactid='$search_user' AND status!='Archived'"));
    $total_task = $get_task['total_task'];

    $get_quote = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(estimateid) AS total_quote FROM estimate WHERE assign_staffid LIKE '%," . $search_user . ",%' AND DATE(follow_up_date) = DATE(NOW()) AND (status!='Saved' AND status!='Submitted' AND status!='Approved Quote')"));
    $total_quote = $get_quote['total_quote'];

    $message = 'Please find below your activity for today.<br><br><a href="'.$software_url.'/Quote/quotes.php">Quotes ('.$total_quote.')</a><br><br>
    <a href="'.$software_url.'/Agenda Meetings/meeting.php">Meetings ('.$total_am.')</a><br><br>
    <a href="'.$software_url.'/Daysheet/ticket_daysheet.php">'.TICKET_TILE.' ('.$total_ticket.')</a><br><br>
    <a href="'.$software_url.'/Daysheet/ticket_daysheet.php">Tasks ('.$total_task.')</a>';

    $email_address = get_email($dbc, $row['contactid']);
    //$email_address =  'dayanapatel@freshfocusmedia.com';
    if($email_address != '') {
        send_email('', $email_address, '', '', 'Your Daily Activity', $message, '');
    }
}

echo 'Email Sent';
?>
