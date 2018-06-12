<?php // Profit / Loss
include('../include.php');
error_reporting(0);

if(empty($_GET['tab'])) {
	$_GET['tab'] = 'revenue';
}
switch($_GET['tab']) {
	case 'receivables': $tab = 'receivables';
		$title = 'Outstanding Receivables';
		break;
	case 'compensation': $tab = 'compensation';
		$title = 'Staff & Compensation';
		break;
	case 'expenses': $tab = 'expenses';
		$title = 'Expense Report';
		break;
	case 'inventory': $tab = 'inventory';
		$title = 'Cost of Inventory';
		break;
	case 'summary': $tab = 'summary';
		$title = 'Profit & Loss';
		break;
	default: $tab = 'revenue';
		$title = 'Revenue Items';
		break;
}

$invoice_years = mysqli_fetch_array(mysqli_query($dbc, "SELECT DISTINCT(LEFT(`invoice_date`,4)) year FROM `point_of_sell` ORDER BY `invoice_date`"));
$min_year = $invoice_years['year'];
$search_start = date('Y-m-01');
$search_end = date('Y-m-t');
if(!empty($_POST['search_start'])) {
	$search_start = $_POST['search_start'];
} else if (!empty($_GET['search_start'])) {
    $search_start = $_GET['search_start'];
}
if(!empty($_POST['search_end'])) {
    $search_end = $_POST['search_end'];
} else if (!empty($_GET['search_end'])) {
    $search_end = $_GET['search_end'];
}
$startyear = intval(explode('-', $search_start)[0]);
$endyear = intval(explode('-', $search_end)[0]);

?>
<script type="text/javascript">
$(document).ready(function() {
	<?php if ( check_subtab_persmission($dbc, 'profit_loss', ROLE, $tab) === FALSE ) {
		echo "$('.tab-container button:first').click();";
	} ?>

    $("form a").click (function() {
        var href = $(this).attr('href');
        if (href.indexOf("search_start") == -1) {
            href += "&search_start=" + $("[name='search_start']").val();
        }
        if (href.indexOf("search_end") == -1) {
            href += "&search_end=" + $("[name='search_end']").val();
        }
        $(this).attr('href', href);
    });
});
</script>
</head>
<body>
<?php include('../navigation.php');
checkAuthorised('profit_loss'); ?>

<div class="container triple-pad-bottom">
	<div id="no-more-tables" class="row">
		<h1><?php echo $title; ?></h1>
		<div class="tab-container"><?php
			$tab_info = [ 'revenue' => 'Displays revenue between two selected dates.',
				'receivables' => 'Displays receivables between two selected dates.',
				'compensation' => 'Displays compensation per staff between two selected dates.',
				'expenses' => 'Displays expenses between two selected dates.',
				'inventory' => 'Displays inventory costs between two selected dates.',
				'summary' => 'Displays a summary of profit & loss between two selected dates.' ];
			$tab_name = [ 'revenue' => 'Revenue',
				'receivables' => 'Receivables',
				'compensation' => 'Staff & Compensation',
				'expenses' => 'Expenses',
				'inventory' => 'Costs',
				'summary' => 'Summary' ];

			foreach ($tab_name as $tab_id => $tab_label) {
				$subtabs_nav = "<span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='". $tab_info[$tab_id] ."'><img src='" . WEBSITE_URL . "/img/info.png' width='20'></a></span>";
				if ( check_subtab_persmission($dbc, 'profit_loss', ROLE, $tab_id) === TRUE ) {
					$subtabs_nav .= "<a href='profit_loss.php?tab=" . $tab_id . "'><button type='button' class='btn brand-btn mobile-100 mobile-block ". ($tab == $tab_id ? 'active_tab' : '') ."' >". $tab_label ."</button></a>";
				}

			
				
				echo "<div class='pull-left tab'>" . $subtabs_nav . "</div>";
			} 
			
			$tab_title = $_GET['tab'];
			echo "<br><br><span class='pull-right'><a href='redirect.php?tab=".$tab_title."'><button type='button' class='btn brand-btn mobile-100 mobile-block' >Print PDF</button></a></span>";
			
			?>
			<div class="clearfix"></div>
		</div>

		<form action="" method="POST">
            <?php
            if($tab == 'revenue') {
                echo '<div class="notice double-gap-bottom popover-examples">
                <div class="col-sm-1 notice-icon"><img src="'.WEBSITE_URL.'/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                Displays revenue between two selected dates, broken out by revenue Items.</div>
                <div class="clearfix"></div>
                </div>';
            } else if($tab == 'receivables') {
                echo '<div class="notice double-gap-bottom popover-examples">
                <div class="col-sm-1 notice-icon"><img src="'.WEBSITE_URL.'/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                Displays receivables between two selected dates, broken out by receivable Items.</div>
                <div class="clearfix"></div>
                </div>';
            }  else if($tab == 'compensation') {
                echo '<div class="notice double-gap-bottom popover-examples">
                <div class="col-sm-1 notice-icon"><img src="'.WEBSITE_URL.'/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                Displays compensation per staff between two selected dates.</div>
                <div class="clearfix"></div>
                </div>';
            }  else if($tab == 'expenses') {
                echo '<div class="notice double-gap-bottom popover-examples">
                <div class="col-sm-1 notice-icon"><img src="'.WEBSITE_URL.'/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                Displays expenses between two selected dates, broken out by expense category.</div>
                <div class="clearfix"></div>
                </div>';
            }  else if($tab == 'inventory') {
                echo '<div class="notice double-gap-bottom popover-examples">
                <div class="col-sm-1 notice-icon"><img src="'.WEBSITE_URL.'/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                Displays inventory costs between two selected dates, broken out by inventory Items.</div>
                <div class="clearfix"></div>
                </div>';
            }  else if($tab == 'summary') {
                echo '<div class="notice double-gap-bottom popover-examples">
                <div class="col-sm-1 notice-icon"><img src="'.WEBSITE_URL.'/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                Displays a summary of Profit & Loss between two selected dates. It is an overview of every other report listed previously for the selected date range.</div>
                <div class="clearfix"></div>
                </div>';
            }?>

            <center><div class="form-group">
                From: <input name="search_start" type="text" class="datepicker" value="<?php echo $search_start; ?>">
                &nbsp;&nbsp;&nbsp;
                Until: <input name="search_end" type="text" class="datepicker" value="<?php echo $search_end; ?>">
                &nbsp;&nbsp;&nbsp;
				<button type="submit" name="search_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
            </div>

            <!-- 
			<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
				<label for="search_year" class="control-label">Year:</label>
			</div>
			<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
				<select data-placeholder="Pick a Year" name="search_year" class="form-control chosen-select-deselect" width="380">
					<option value=""></option>
					<?php /* 
                        for($i = $min_year; $i <= date('Y'); $i++) {
						  echo "<option ".($search_year == $i ? 'selected ' : '')."value='$i'>$i</option>\n";
					    } */
                    ?>
				</select>
			</div> -->

			<!--<div class="form-group">
				<button type="submit" name="search_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
				<a href="" class="btn brand-btn mobile-block">Current Year</a>
			</div></center>-->
		</form>

		<?php include($tab.'.php'); ?>
	</div>
</div>

<?php include('../footer.php'); ?>