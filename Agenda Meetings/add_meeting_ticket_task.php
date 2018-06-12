<?php if (strpos($value_config, ','."Add Ticket".',') !== FALSE) { ?>
<?php
echo '<td>';
$from = urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']);
echo '<a class="btn-lg brand-btn mobile-block" href="'.WEBSITE_URL.'/Ticket/index.php?edit=0&from='.$from.'">New '.TICKET_NOUN.'</a></td>';
//echo '<a href="#"  class="btn brand-btn mobile-block btn-lg" onclick="wwindow.open(\'../Ticket/add_tickets.php\', \'newwindow\', \'width=900, height=900\'); return false;">Create Ticket</a></td>';
?>
<?php } ?>
<?php if (strpos($value_config, ','."Add Task".',') !== FALSE) { ?>
<?php //include ('../Ticket/add_view_ticket_tasklist.php'); ?>
<?php } ?>