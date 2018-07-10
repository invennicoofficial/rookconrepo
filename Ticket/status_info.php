<?php $guest_access = true;
include('../include.php');
ob_clean();
$ticketid = filter_var($_GET['ticketid'],FILTER_SANITIZE_STRING);
$stopid = filter_var($_GET['stopid'],FILTER_SANITIZE_STRING);
$get_ticket = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticketid`='$ticketid'"));
$get_stop = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `ticket_schedule` WHERE `ticketid`='$ticketid' AND `id`='$stopid'"));
$get_ticket = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `my_ticket`.*, SUM(IF(`all_tickets`.`ticketid` > 0,1,0)) `prior_count` FROM `tickets` my_ticket LEFT JOIN `tickets` all_tickets ON `my_ticket`.`to_do_date`=`all_tickets`.`to_do_date` AND `all_tickets`.`to_do_start_time` < `my_ticket`.`to_do_start_time` AND IFNULL(`all_tickets`.`contactid`,'')=IFNULL(`my_ticket`.`contactid`,'') AND IFNULL(`all_tickets`.`equipmentid`,'')=IFNULL(`my_ticket`.`equipmentid`,'') AND `all_tickets`.`status` NOT IN ('Archived','Archive','Done') WHERE `my_ticket`.`ticketid`='$ticketid'")); ?>
<h3><?= $get_stop['type'] ?> at <?= implode(' - ',array_filter([$get_stop['location_name'], $get_stop['client_name'], $get_stop['address']])) ?></h3>
<div class="">Delivery Scheduled for <?= date('l F j, Y, g:i a',strtotime($get_stop['to_do_date'].' '.$get_stop['to_do_start_time'])) ?>.</div>
<h3>Preceding <?= TICKET_TILE ?></h3>
<div class="">There are <?= $get_ticket['prior_count'].' '.TICKET_TILE ?> ahead of your <?= TICKET_NOUN ?>.
<?php if($get_ticket['prior_count'] < 2) { ?>
	<br />Your <?= TICKET_NOUN ?> will be started soon. Please be ready when our staff arrive for them to complete the work.
<?php } ?></div>
<div class="offset-top-30 text-center">This is estimated information only, provided as a guideline for your convenience and is not a guarantee.<br />
	Status last updated <?= date('Y-m-d H:i:s') ?></div>