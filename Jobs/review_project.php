<?php
/*
Dashboard
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
require('../gantti-master/lib/gantti.php');
error_reporting(0);

if (isset($_POST['printpdf'])) {
    include_once ('print_project_tickets.php');
}

?>
<script type="text/javascript" src="project_path.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#scrum_tickets').each(function() { DoubleScroll(this); });

	$('.close_iframer').click(function(){
		$('.iframe_holder').hide();
		$('.hide_on_iframe').show();
	});
});

function exportExcel(id)
{
	var projectid=id;
	var testid = $("#testid").val();
	window.location="review_project_gantt_chart_excel.php?projectid="+projectid;
	$('.close').click();
}

function DoubleScroll(element) {
        var scrollbar= document.createElement('div');
        scrollbar.appendChild(document.createElement('div'));
        scrollbar.style.overflow= 'auto';
        scrollbar.style.overflowY= 'hidden';
        scrollbar.style.width= '';
        scrollbar.firstChild.style.width= element.scrollWidth+'px';
        scrollbar.firstChild.style.height= '0px';
        scrollbar.firstChild.style.paddingTop= '1px';
        scrollbar.firstChild.appendChild(document.createTextNode('\xA0'));
        scrollbar.onscroll= function() {
            element.scrollLeft= scrollbar.scrollLeft;
        };
        element.onscroll= function() {
            scrollbar.scrollLeft= element.scrollLeft;
        };
        element.parentNode.insertBefore(scrollbar, element);
}
</script>
<style>
.active_tab {
    color: #000000;background: #ffffff;background: -webkit-linear-gradient(top, #FFF, #9A9A9A);
    background: linear-gradient(to bottom, #FFF, #9A9A9A);
    border: 2px solid #FFFFFF;cursor:default;
}
.ui-state-disabled  { pointer-events: none !important; }
</style>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="iframe_holder" style="display:none;">
		<img src="<?php echo WEBSITE_URL; ?>/img/icons/close.png" class="close_iframer" width="45px" style="position:relative; right:10px; float:right; top:58px; cursor:pointer;">
		<span class="iframe_title" style="color:white; font-weight:bold; position:relative; top:58px; left:20px; font-size:30px;"></span>
		<iframe id="iframe_instead_of_window" style="width:100%; overflow:hidden; height:200px; border:0;" src=""></iframe>
	</div>
	<div class="row hide_on_iframe">
        <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

        <?php
            $projectid = $_GET['projectid'];
			$project_name = get_project($dbc, $projectid, 'project_name');
            $businessid = get_project($dbc, $projectid, 'businessid');
            $b_name = get_client($dbc, $businessid);
			$current_tab = '';
            $pp = '';
            $ec = '';
            $detail = '';
            $pl = '';
            $sales = '';
            $ticket = '';
            $meeting = '';
            $deli = '';
            $docs = '';
            $po = '';
            $add = '';
            $comm = '';
            $reminders = '';
            $history = '';
            $gantt = '';
            $checklist = '';
            $project_time = '';
            $type = $_GET['type'];
            if($_GET['type'] == 'project_path') {
                $pp = 'active_tab';
				$current_tab = 'Project Path';
            }
            if($_GET['type'] == 'gantt_chart') {
                $gantt = 'active_tab';
				$current_tab = 'Gantt Chart';
            }
            if($_GET['type'] == 'email_comm') {
                $ec = 'active_tab';
				$current_tab = 'Email Communication';
            }
            if($_GET['type'] == 'detail') {
                $detail = 'active_tab';
				$current_tab = 'Details';
            }
            if($_GET['type'] == 'pl') {
                $pl = 'active_tab';
				$current_tab = 'Profit/Loss';
            }
            if($_GET['type'] == 'sales') {
                $sales = 'active_tab';
				$current_tab = 'Sales';
            }
            if($_GET['type'] == 'ticket') {
                $ticket = 'active_tab';
				$current_tab = 'Tickets';
            }
            if($_GET['type'] == 'meeting') {
                $meeting = 'active_tab';
				$current_tab = 'Meetings';
            }
            if($_GET['type'] == 'deli') {
                $deli = 'active_tab';
				$current_tab = 'Dates';
            }
            if($_GET['type'] == 'docs') {
                $docs = 'active_tab';
				$current_tab = 'Documents';
            }
            if($_GET['type'] == 'po') {
                $po = 'active_tab';
				$current_tab = 'PO';
            }
            if($_GET['type'] == 'add') {
                $add = 'active_tab';
				$current_tab = 'Additions';
            }
            if($_GET['type'] == 'comm') {
                $comm = 'active_tab';
				$current_tab = 'Notes';
            }
            if($_GET['type'] == 'history') {
                $history = 'active_tab';
				$current_tab = 'History';
            }
            if($_GET['type'] == 'reminders') {
                $reminders = 'active_tab';
				$current_tab = 'Reminders';
            }
            if($_GET['type'] == 'checklist' || $_GET['type'] == 'company_checklist') {
                $checklist = 'active_tab';
				$current_tab = 'Checklist';
            }
            if($_GET['type'] == 'project_time') {
                $project_time = 'active_tab';
				$current_tab = 'Total Time Tracked';
            }
            if($_GET['type'] == 'certificates') {
                $certificates = 'active_tab';
				$current_tab = 'Certificates';
            }

			if(empty($_GET['from_url'])) {
				$_GET['from_url'] = WEBSITE_URL."/Project/project.php?type=".get_project($dbc,$projectid,'projecttype');
			}
        ?>
		<h1>Project #<?php echo $projectid.' : '.$b_name.' : '.$project_name; ?></h1>
		<div class="double-gap-top"><a href="<?= $_GET['from_url']; ?>" class="btn config-btn">Back to Dashboard</a></div>
		<script>
		$(document).ready(function() {
			document.title = "Project #<?php echo $projectid.': '.$project_name.($current_tab != '' ? ' | '.$current_tab : ''); ?>";
		});
		</script>
            <input type="hidden" name="projectid" value="<?php echo $projectid; ?>">

        <br><br>
		<div class='mobile-100-container'>

			<a href='review_project.php?type=project_path&projectid=<?php echo $projectid; ?>&from_url=<?php echo urlencode($_GET['from_url']); ?>'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $pp; ?>" >Project Path</button></a>&nbsp;&nbsp;
			<a href='review_project.php?type=gantt_chart&projectid=<?php echo $projectid; ?>&from_url=<?php echo urlencode($_GET['from_url']); ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $gantt; ?>" >Gantt Chart</button></a>&nbsp;&nbsp;
			<a href='review_project.php?type=checklist&projectid=<?php echo $projectid; ?>&from_url=<?php echo urlencode($_GET['from_url']); ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $checklist; ?>" >Checklist</button></a>&nbsp;&nbsp;
			<a href='review_project.php?type=ticket&projectid=<?php echo $projectid; ?>&category=Active&from_url=<?php echo urlencode($_GET['from_url']); ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $ticket; ?>" >Tickets</button></a>&nbsp;&nbsp;
			<a href='review_project.php?type=email_comm&projectid=<?php echo $projectid; ?>&from_url=<?php echo urlencode($_GET['from_url']); ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $ec; ?>" >Email Communication</button></a>&nbsp;&nbsp;
			<a href='review_project.php?type=detail&projectid=<?php echo $projectid; ?>&from_url=<?php echo urlencode($_GET['from_url']); ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $detail; ?>" >Details</button></a>&nbsp;&nbsp;

            <!--
            <a href='review_project.php?type=pl&projectid=<?php echo $projectid; ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $pl; ?>" >Profit/Loss</button></a>&nbsp;&nbsp;
			<a href='review_project.php?type=sales&projectid=<?php echo $projectid; ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $sales; ?>" >Sales</button></a>&nbsp;&nbsp;
            -->
			<a href='review_project.php?type=meeting&projectid=<?php echo $projectid; ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $meeting; ?>" >Agendas & Meetings</button></a>&nbsp;&nbsp;

			<a href='review_project.php?type=deli&projectid=<?php echo $projectid; ?>&from_url=<?php echo urlencode($_GET['from_url']); ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $deli; ?>" >Dates</button></a>&nbsp;&nbsp;
			<a href='review_project.php?type=docs&projectid=<?php echo $projectid; ?>&from_url=<?php echo urlencode($_GET['from_url']); ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $docs; ?>" >Documents</button></a>&nbsp;&nbsp;

            <!--
            <a href='review_project.php?type=po&projectid=<?php echo $projectid; ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $po; ?>" >PO</button></a>&nbsp;&nbsp;
			<a href='review_project.php?type=add&projectid=<?php echo $projectid; ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $add; ?>" >Additions</button></a>&nbsp;&nbsp;
			<a href='review_project.php?type=comm&projectid=<?php echo $projectid; ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $comm; ?>" >Notes</button></a>&nbsp;&nbsp;
            -->
			<a href='review_project.php?type=reminders&projectid=<?php echo $projectid; ?>&from_url=<?php echo urlencode($_GET['from_url']); ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $reminders; ?>" >Reminders</button></a>&nbsp;&nbsp;
			<a href='review_project.php?type=project_time&projectid=<?php echo $projectid; ?>&from_url=<?php echo urlencode($_GET['from_url']); ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $project_time; ?>" >Total Time Tracked</button></a>&nbsp;&nbsp;
			<?php if(strpos(','.mysqli_fetch_assoc(mysqli_query($dbc,"SELECT certificate FROM field_config"))['certificate'].',',',Client Project,') !== FALSE) { ?>
				<a href='review_project.php?type=certificates&projectid=<?php echo $projectid; ?>&from_url=<?php echo urlencode($_GET['from_url']); ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $certificates; ?>" >Certificates</button></a>&nbsp;&nbsp;
			<?php } ?>
			<a href='review_project.php?type=history&projectid=<?php echo $projectid; ?>&from_url=<?php echo urlencode($_GET['from_url']); ?>'><button type="button" class="btn brand-btn mobile-block mobile-100  <?php echo $history; ?>" >History</button></a>&nbsp;&nbsp;

		</div>
        <br><br>
        <?php
            if($_GET['type'] == 'project_path') {
                include ('review_project_path.php');
            }
            if($_GET['type'] == 'gantt_chart') {
                include ('review_project_gantt_chart.php');
            }
            if($_GET['type'] == 'checklist') {
                //include ('review_project_checklist.php');
                include ('review_project_ticket_checklist.php');
            }
            if($_GET['type'] == 'company_checklist') {
                include ('review_project_company_checklist.php');
            }
            if($_GET['type'] == 'email_comm') {
                include ('review_project_email_communication.php');
            }
            if($_GET['type'] == 'detail') {
                include ('review_jobs_detail.php');
            }
            if($_GET['type'] == 'pl') {
                $pl = 'active_tab';
            }
            if($_GET['type'] == 'sales') {
                $sales = 'active_tab';
            }
            if($_GET['type'] == 'ticket') {
                $ticket = 'active_tab';
                include ('review_project_ticket.php');
            }
            if($_GET['type'] == 'meeting') {
                $meeting = 'active_tab';
				include ('review_project_agendas_meetings.php');
            }
            if($_GET['type'] == 'deli') {
                $deli = 'active_tab';
                include ('add_project_dates.php');
            }
            if($_GET['type'] == 'docs') {
                $docs = 'active_tab';
                include ('add_project_documents.php');
            }
            if($_GET['type'] == 'po') {
                $po = 'active_tab';
            }
            if($_GET['type'] == 'add') {
                $add = 'active_tab';
            }
            if($_GET['type'] == 'comm') {
                $comm = 'active_tab';
            }
            if($_GET['type'] == 'reminders') {
                $reminders = 'active_tab';
                include ('review_reminders.php');
            }
            if($_GET['type'] == 'project_time') {
                $project_time = 'active_tab';
                include ('review_project_time.php');
            }
            if($_GET['type'] == 'history') {
                $history = 'active_tab';
                include ('project_history.php');
            }
            if($_GET['type'] == 'certificates') {
                $certificates = 'active_tab';
                include ('review_project_certificates.php');
            }
        ?>
        </form>
	</div>
</div>
<?php include ('../footer.php'); ?>
<script type='text/javascript'>
$(function(){
  var gantt_width = jQuery(".gantt-items").width();
  $(".div1").css("width", gantt_width);
  $(".gantt-labels").css("margin-top", "79px");
  $(".wrapper1").scroll(function(){
    $(".gantt-data").scrollLeft($(".wrapper1").scrollLeft());
  });
  $(".gantt-data").scroll(function(){
    $(".wrapper1").scrollLeft($(".gantt-data").scrollLeft());
  });
});
</script>