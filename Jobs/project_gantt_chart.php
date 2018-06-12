<?php
/*
Dashboard
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
require('../gantti-master/lib/gantti.php');
error_reporting(0);
?>
<script type="text/javascript">
$(document).ready(function() {
});
</script>
<style>

</style>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row hide_on_iframe">
	    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

       <?php include ('project_header_tabs.php'); ?>

         <?php
            if(isset($_POST['search_user_submit'])) {
                $search_client = $_POST['search_client'];
            } else {
                $search_user = $contactid;
            }
			if (isset($_POST['display_all_inventory'])) {
				$search_user = $contactid;
			}
        ?>
        <div class="form-group">
		  <label for="site_name" class="col-sm-4 control-label">Search By Project Path:</label>
		  <div class="col-sm-8">
              <select data-placeholder="Pick a Project Path" name="search_client" class="chosen-select-deselect form-control" style="width: 20%;float: left;margin-right: 10px;" width="380">
              <option value=""></option>
              <?php
                $query = mysqli_query($dbc,"SELECT jobs_path_milestone, project_path FROM jobs_path_milestone");
                while($row = mysqli_fetch_array($query)) {
                ?><option <?php if ($row['jobs_path_milestone'] == $search_client) { echo " selected"; } ?> value='<?php echo  $row['jobs_path_milestone']; ?>' ><?php echo $row['project_path']; ?></option>
            <?php	} ?>
            </select>

          <label for="site_name" class=" control-label"></label>
            <button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
		  </div>
		</div>
		<div id='no-more-tables'>
        <div class="scrum_tickets" id="scrum_tickets">

        <?php
        $type = $_GET['type'];
        //if($search_client != '') {
        //    $query_check_credentials = "SELECT t.*, c.name FROM estimated_gantt_chart t, contacts c WHERE t.businessid = c.contactid AND t.businessid = '$search_client' ORDER BY estimatedganttchartid DESC";
        //} else {
            $query_check_credentials = "select * from jobs WHERE projecttype = '$type'";
        //}

        $result = mysqli_query($dbc, $query_check_credentials);
        while($row = mysqli_fetch_array( $result )) {
            $projectid = $row['projectid'];
            $businessid = $row['businessid'];
            $project_name = $row['project_name'];

            $data[] = array(
              'label' => '<a class="" href="#"  onclick="window.open(\''.WEBSITE_URL.'/Project/add_project.php?projectid='.$projectid.'&type='.$type.'\', \'newwindow\', \'width=1000, height=900\'); return false;">'.$project_name.'</a>',
			  // 'label' => '<span class="iframe_open" id="'.$projectid.'_'.$type.'" style="cursor:pointer">'.$project_name.'</span>',
              'start' => $row['start_date'],
              'end'   => $row['estimated_completed_date'],
              'class' => 'current',
            );

            $result_ticket = mysqli_query($dbc, "SELECT * FROM tickets WHERE projectid = '$projectid'");
            while($row_ticket = mysqli_fetch_array( $result_ticket )) {
                if($row_ticket['to_do_date'] != '0000-00-00') {
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
                    if($row_ticket['status'] == 'Internal QA') {
                        $class = 'class8';
                    }
                    if($row_ticket['status'] == 'Customer QA') {
                        $class = 'class9';
                    }
                    if($row_ticket['status'] == 'Waiting On Customer') {
                        $class = 'class10';
                    }
                    if($row_ticket['status'] == 'Done') {
                        $class = 'class11';
                    }
                    if($row_ticket['status'] == 'Archive') {
                        $class = 'class12';
                    }

                    $contactid = get_multiple_contact($dbc, $row_ticket['contactid']);
                    $data[] = array(
                      //'label' => '#'.$row_ticket['ticketid'].' - '.str_replace("<br>",", ",$contactid),
                        'label' => '<a class="" href="#"  onclick="window.open(\''.WEBSITE_URL.'/Ticket/add_tickets.php?ticketid='.$row_ticket['ticketid'].'\', \'newwindow\', \'width=1000, height=900\'); return false;">Ticket #'.$row_ticket['ticketid'].'</a>',
                      'start' => $row_ticket['to_do_date'],
                      'end'   => date('Y-m-d', strtotime($row_ticket['to_do_end_date'] . ' +1 day')),
                      'class' => $class,
                    );
                }
            }

            /*
            $result_wo = mysqli_query($dbc, "SELECT * FROM workorder WHERE projectid = '$projectid'");
            while($row_wo = mysqli_fetch_array( $result_wo )) {
                if($row_wo['to_do_date'] != '0000-00-00') {
                    $class = 'current';
                    if($row_wo['status'] == 'Sales') {
                        $class = 'class1';
                    }
                    if($row_wo['status'] == 'Strategy Needed') {
                        $class = 'class2';
                    }
                    if($row_wo['status'] == 'Last Minute Priority') {
                        $class = 'class3';
                    }
                    if($row_wo['status'] == 'Information Gathering') {
                        $class = 'class4';
                    }
                    if($row_wo['status'] == 'To Be Scheduled') {
                        $class = 'class5';
                    }
                    if($row_wo['status'] == 'Scheduled/To Do') {
                        $class = 'class6';
                    }
                    if($row_wo['status'] == 'Doing Today') {
                        $class = 'class7';
                    }
                    if($row_wo['status'] == 'Internal QA') {
                        $class = 'class8';
                    }
                    if($row_wo['status'] == 'Customer QA') {
                        $class = 'class9';
                    }
                    if($row_wo['status'] == 'Waiting On Customer') {
                        $class = 'class10';
                    }
                    if($row_wo['status'] == 'Done') {
                        $class = 'class11';
                    }
                    if($row_wo['status'] == 'Archive') {
                        $class = 'class12';
                    }

                    $contactid = get_multiple_contact($dbc, $row_wo['contactid']);
                    $data[] = array(
                      //'label' => '#'.$row_wo['ticketid'].' - '.str_replace("<br>",", ",$contactid),
                        'label' => '<a class="" href="#"  onclick="window.open(\''.WEBSITE_URL.'/Work Order/add_workorder.php?workorderid='.$row_wo['workorderid'].'\', \'newwindow\', \'width=1000, height=900\'); return false;">WO #'.$row_wo['workorderid'].'</a>',
                      'start' => $row_wo['to_do_date'],
                      'end'   => date('Y-m-d', strtotime($row_wo['to_do_date'] . ' +1 day')),
                      'class' => $class,
                    );
                }
            }
            */
        }

        $gantti = new Gantti($data, array(
          'title'      => $type,
          'cellwidth'  => 35,
          'cellheight' => 35,
          'today'      => true
        ));
        echo $gantti;

        ?>

        </form>
	</div>
</div>

<?php include ('../footer.php'); ?>