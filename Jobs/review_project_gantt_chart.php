<!--<button type="button" onclick="captureCurrentDiv()">Current Div</button>-->

<?php
//echo '<button type="submit" name="printpdf" id="printpdf" value="Print Report" class="btn brand-btn pull-right">Print</button>';

$projectid = $_GET['projectid'];

    $ticket_status = get_config($dbc, 'ticket_status');
    $each_tab = explode(',', $ticket_status);
    $i=1;
    $status_array = array();
    foreach ($each_tab as $cat_tab) {
            echo '<img src="'.WEBSITE_URL.'/img/block/s'.$i.'.png" width="10" height="10" border="0" alt="">&nbsp;'.$cat_tab.'&nbsp;&nbsp;';
            $status_array[$cat_tab] = 's'.$i.'.png';
            $i++;
    }

/*
echo '<br><br><img src="'.WEBSITE_URL.'/img/block/dark_yellow.png" width="10" height="10" border="0" alt="">&nbsp;Sales&nbsp;&nbsp;';
echo '<img src="'.WEBSITE_URL.'/img/block/brown.png" width="10" height="10" border="0" alt="">&nbsp;Strategy Needed&nbsp;&nbsp;';
echo '<img src="'.WEBSITE_URL.'/img/block/lime.png" width="10" height="10" border="0" alt="">&nbsp;Last Minute Priority&nbsp;&nbsp;';
echo '<img src="'.WEBSITE_URL.'/img/block/olive.png" width="10" height="10" border="0" alt="">&nbsp;Information Gathering&nbsp;&nbsp;';
echo '<img src="'.WEBSITE_URL.'/img/block/orange.png" width="10" height="10" border="0" alt="">&nbsp;To Be Scheduled&nbsp;&nbsp;';
echo '<img src="'.WEBSITE_URL.'/img/block/pink.png" width="10" height="10" border="0" alt="">&nbsp;To Do&nbsp;&nbsp;';
echo '<img src="'.WEBSITE_URL.'/img/block/purple.png" width="10" height="10" border="0" alt="">&nbsp;Doing&nbsp;&nbsp;';
echo '<img src="'.WEBSITE_URL.'/img/block/00eeee.png" width="10" height="10" border="0" alt="">&nbsp;Internal QA&nbsp;&nbsp;';
echo '<img src="'.WEBSITE_URL.'/img/block/bf3eff.png" width="10" height="10" border="0" alt="">&nbsp;Customer QA&nbsp;&nbsp;';
echo '<img src="'.WEBSITE_URL.'/img/block/ee9572.png" width="10" height="10" border="0" alt="">&nbsp;Waiting On Client&nbsp;&nbsp;';
echo '<img src="'.WEBSITE_URL.'/img/block/yellow.png" width="10" height="10" border="0" alt="">&nbsp;Done&nbsp;&nbsp;';
echo '<img src="'.WEBSITE_URL.'/img/block/white.png" width="10" height="10" border="0" alt="">&nbsp;Archive&nbsp;&nbsp;';
*/

echo '<br><span style="float:right;"><input type="button" class="btn brand-btn mobile-block mobile-100" value="Export To Excel" id="btnExcel" onclick="exportExcel('.$projectid.')" name="btnExcel"></span>';
echo '<br><h3>'.get_project($dbc, $projectid, 'project_name').'</h3>';

$data = array();

//$get_tt = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT start_date,estimated_completed_date,completion_date FROM project WHERE projectid='$projectid'"));

/*
$data[] = array(
  'label' => 'Project',
  'start' => $get_tt['start_date'],
  'end'   => $get_tt['estimated_completed_date'],
  'class' => 'current',
);
*/

$result_ticket = mysqli_query($dbc, "SELECT * FROM tickets WHERE projectid = '$projectid'");
while($row_ticket = mysqli_fetch_array( $result_ticket )) {
    $class = 'current';
    if($row_ticket['status'] == 'Sales/Estimate/RFP') {
        $class = 'class1';
    }
    if($row_ticket['status'] == 'Strategy Needed') {
        $class = 'class2';
    }
    if($row_ticket['status'] == 'Last Minute Priority') {
        $class = 'class3';
    }
    if($row_ticket['status'] == 'Information Gathering') {
        $class = 'class4';
    }
    if($row_ticket['status'] == 'To Be Scheduled') {
        $class = 'class5';
    }
    if($row_ticket['status'] == 'Scheduled/To Do') {
        $class = 'class6';
    }
    if($row_ticket['status'] == 'Doing Today') {
        $class = 'class7';
    }
    if($row_ticket['status'] == 'Done') {
        $class = 'class8';
    }
    if($row_ticket['status'] == 'Internal QA') {
        $class = 'class9';
    }
    if($row_ticket['status'] == 'Customer QA') {
        $class = 'class10';
    }
    if($row_ticket['status'] == 'Waiting On Customer') {
        $class = 'class11';
    }
    if($row_ticket['status'] == 'Done') {
        $class = 'class12';
    }
    if($row_ticket['status'] == 'Sidebar') {
        $class = 'class13';
    }
    if($row_ticket['status'] == 'Archive') {
        $class = 'class14';
    }
    if($row_ticket['to_do_date'] != '0000-00-00' && $row_ticket['to_do_date'] != '') {
        $data[] = array(
          'label' => '<a style="font-size:13px; color:white;" id="'.$row_ticket['ticketid'].'" href="'.WEBSITE_URL.'/Ticket/add_tickets.php?ticketid='.$row_ticket['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" >#'.$row_ticket['ticketid'].' : '.limit_text($row_ticket['heading'], 5 ).'</a>',
          'start' => $row_ticket['to_do_date'],
          'end'   => date('Y-m-d', strtotime($row_ticket['to_do_end_date'] . ' +1 day')),
          'class' => $class,
        );

        if($row_ticket['internal_qa_date'] != '0000-00-00' && $row_ticket['internal_qa_date'] != '') {
            $data[] = array(
              'label' => '<a style="font-size:13px; color:white;" id="'.$row_ticket['ticketid'].'" href="'.WEBSITE_URL.'/Ticket/add_tickets.php?ticketid='.$row_ticket['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" >#'.$row_ticket['ticketid'].' : Internal QA</a>',
              'start' => $row_ticket['internal_qa_date'],
              'end'   => $row_ticket['internal_qa_date'],
              'class' => $class,
            );
        } /*else {
            $data[] = array(
                'label' => '<a style="font-size:13px; color:white;" id="'.$row_ticket['ticketid'].'" href="'.WEBSITE_URL.'/Ticket/add_tickets.php?ticketid='.$row_ticket['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" >#'.$row_ticket['ticketid'].' : Internal QA</a>',
                'start' => date('Y-m-d'),
                'end'   => date('Y-m-d', time()+86400),
                'class' => $class,
            );
        }*/

        if($row_ticket['deliverable_date'] != '0000-00-00' && $row_ticket['deliverable_date'] != '') {
            $data[] = array(
              'label' => '<a style="font-size:13px; color:white;" id="'.$row_ticket['ticketid'].'" href="'.WEBSITE_URL.'/Ticket/add_tickets.php?ticketid='.$row_ticket['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" >#'.$row_ticket['ticketid'].' : Customer QA</a>',
              'start' => $row_ticket['deliverable_date'],
              'end'   => $row_ticket['deliverable_date'],
              'class' => $class,
            );
        } /*else {
            $data[] = array(
                'label' => '<a style="font-size:13px; color:white;" id="'.$row_ticket['ticketid'].'" href="'.WEBSITE_URL.'/Ticket/add_tickets.php?ticketid='.$row_ticket['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" >#'.$row_ticket['ticketid'].' : Customer QA</a>',
                'start' => date('Y-m-d'),
                'end'   => date('Y-m-d', time()+86400),
                'class' => $class,
            );
        }*/

    } else {
        $data[] = array(
          'label' => '<a style="font-size:13px; color:white;" id="'.$row_ticket['ticketid'].'" href="'.WEBSITE_URL.'/Ticket/add_tickets.php?ticketid='.$row_ticket['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" >#'.$row_ticket['ticketid'].' : '.limit_text($row_ticket['heading'], 5 ).'</a>',
          'start' => date('Y-m-d'),
          'end'   => date('Y-m-d', time()+86400),
          'class' => $class,
        );

        if($row_ticket['internal_qa_date'] != '0000-00-00' && $row_ticket['internal_qa_date'] != '') {
            $data[] = array(
              'label' => '<a style="font-size:13px; color:white;" id="'.$row_ticket['ticketid'].'" href="'.WEBSITE_URL.'/Ticket/add_tickets.php?ticketid='.$row_ticket['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" >#'.$row_ticket['ticketid'].' : Internal QA</a>',
              'start' => $row_ticket['internal_qa_date'],
              'end'   => $row_ticket['internal_qa_date'],
              'class' => $class,
            );
        } /*else {
            $data[] = array(
                'label' => '<a style="font-size:13px; color:white;" id="'.$row_ticket['ticketid'].'" href="'.WEBSITE_URL.'/Ticket/add_tickets.php?ticketid='.$row_ticket['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" >#'.$row_ticket['ticketid'].' : Internal QA</a>',
                'start' => date('Y-m-d'),
                'end'   => date('Y-m-d', time()+86400),
                'class' => $class,
            );
        }*/

        if($row_ticket['deliverable_date'] != '0000-00-00' && $row_ticket['deliverable_date'] != '') {
            $data[] = array(
              'label' => '<a style="font-size:13px; color:white;" id="'.$row_ticket['ticketid'].'" href="'.WEBSITE_URL.'/Ticket/add_tickets.php?ticketid='.$row_ticket['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" >#'.$row_ticket['ticketid'].' : Customer QA</a>',
              'start' => $row_ticket['deliverable_date'],
              'end'   => $row_ticket['deliverable_date'],
              'class' => $class,
            );
        } /*else {
            $data[] = array(
                'label' => '<a style="font-size:13px; color:white;" id="'.$row_ticket['ticketid'].'" href="'.WEBSITE_URL.'/Ticket/add_tickets.php?ticketid='.$row_ticket['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" >#'.$row_ticket['ticketid'].' : Customer QA</a>',
                'start' => date('Y-m-d'),
                'end'   => date('Y-m-d', time()+86400),
                'class' => $class,
            );
        } */
    }
}

$gantti = new Gantti($data, array(
  //'title'      => get_project($dbc, $projectid, 'project_name'),
    'title'      => '#'.$projectid,
  'cellwidth'  => 30,
  'cellheight' => 30,
  'today'      => true
));
echo $gantti;
?>