<?php
	$search_month = date('n');
	$search_year = date('Y');
	$to_search_month = date('n');
	if(isset($_POST['search_user_submit'])) {
		$search_month = $_POST['search_month'];
		$to_search_month = $_POST['to_search_month'];
		$search_year = $_POST['search_year'];
	}
	if (isset($_POST['display_all_inventory'])) {
		$search_month = date('n');
		$to_search_month = date('n');
		$search_year = date('Y');
	}
?>
	<form name="form_sites_1" method="post" action="" class="form-inline" role="form">
		<center>
		<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
		  <label for="site_name" class="control-label">From Month:</label>
		</div>
		<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
			<select data-placeholder="Pick a Month" name="search_month" class="form-control" width="380">
				<option value=""></option>
				<option <?php if ($search_month == "1") { echo " selected"; } ?>  value="1">January</option>
				<option <?php if ($search_month == "2") { echo " selected"; } ?> value="2">February</option>
				<option <?php if ($search_month == "3") { echo " selected"; } ?> value="3">March</option>
				<option <?php if ($search_month == "4") { echo " selected"; } ?> value="4">April</option>
				<option <?php if ($search_month == "5") { echo " selected"; } ?> value="5">May</option>
				<option <?php if ($search_month == "6") { echo " selected"; } ?> value="6">June</option>
				<option <?php if ($search_month == "7") { echo " selected"; } ?> value="7">July</option>
				<option <?php if ($search_month == "8") { echo " selected"; } ?> value="8">August</option>
				<option <?php if ($search_month == "9") { echo " selected"; } ?> value="9">September</option>
				<option <?php if ($search_month == "10") { echo " selected"; } ?> value="10">October</option>
				<option <?php if ($search_month == "11") { echo " selected"; } ?> value="11">November</option>
				<option <?php if ($search_month == "12") { echo " selected"; } ?> value="12">December</option>
			</select>
		</div>
		<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
		  <label for="site_name" class="control-label">To Month:</label>
		</div>
		  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
			  <select data-placeholder="Pick a Month" name="to_search_month" class="form-control" width="380">
				<option value=""></option>
				<option <?php if ($to_search_month == "1") { echo " selected"; } ?>  value="1">January</option>
				<option <?php if ($to_search_month == "2") { echo " selected"; } ?> value="2">February</option>
				<option <?php if ($to_search_month == "3") { echo " selected"; } ?> value="3">March</option>
				<option <?php if ($to_search_month == "4") { echo " selected"; } ?> value="4">April</option>
				<option <?php if ($to_search_month == "5") { echo " selected"; } ?> value="5">May</option>
				<option <?php if ($to_search_month == "6") { echo " selected"; } ?> value="6">June</option>
				<option <?php if ($to_search_month == "7") { echo " selected"; } ?> value="7">July</option>
				<option <?php if ($to_search_month == "8") { echo " selected"; } ?> value="8">August</option>
				<option <?php if ($to_search_month == "9") { echo " selected"; } ?> value="9">September</option>
				<option <?php if ($to_search_month == "10") { echo " selected"; } ?> value="10">October</option>
				<option <?php if ($to_search_month == "11") { echo " selected"; } ?> value="11">November</option>
				<option <?php if ($to_search_month == "12") { echo " selected"; } ?> value="12">December</option>
			</select>
		  </div>
		  <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">&nbsp;</div>
		  <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
			  <label for="site_name" class="control-label">Year:</label>
		  </div>
		  <div class="col-lg-4 col-md-5 col-sm-8 col-xs-8">
			  <select data-placeholder="Pick a Client" name="search_year" class="form-control" width="380">
			  <option value=""></option>
			  <option <?php if ($search_year == "2015") { echo " selected"; } ?>  value="2015">2015</option>
			  <option <?php if ($search_year == "2016") { echo " selected"; } ?>  value="2016">2016</option>
			  <option <?php if ($search_year == "2017") { echo " selected"; } ?>  value="2017">2017</option>
			</select>
		  </div>
		  <div class="col-lg-10 col-md-3 col-sm-4 col-xs-4">&nbsp;</div>
		<div class="form-group">
		<br>
			<button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
			<button type="submit" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Current Month</button>
		</div>
		</center>
	</form>
<form name="form_sites" method="post" action="" class="form-inline" role="form">
	<?php
		if(vuaed_visible_function($dbc, 'interactive_calendar') == 1) {
			echo '<a href="add_interactive_calendar.php" class="btn brand-btn mobile-block gap-bottom pull-right double-gap-top">Add Activity</a>';
		}
	?>
</form>

<?php
for($i=$search_month; $i<=$to_search_month; $i++) {
	$dateObj   = DateTime::createFromFormat('!m', $i);
	$monthName = $dateObj->format('F');
	echo '<h2>'.$monthName.' '.$search_year.'</h2>';
	echo draw_calendar($dbc, $i,$search_year,$status_array, $search_user);
}
?>

<?php
function draw_calendar($dbc, $month,$year,$search_user,$search_client,$search_ticket,$search_status,$status_array){

	/* draw table */

    //class="calendar"

	$calendar = '<table cellpadding="0" cellspacing="0" class="table table-bordered">';

	/* table headings */
	$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	$calendar.= '<tr class="calendar-row"><td style="width:14.29%" class="calendar-day-head">'.implode('</td><td style="width:14.29%" class="calendar-day-head">',$headings).'</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td style="width:14.29%" class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

    $sunday = date( 'Y-m-d', strtotime( 'sunday last week' ) );
    $monday = date( 'Y-m-d', strtotime( 'monday this week' ) );
    $tuesday = date( 'Y-m-d', strtotime( 'tuesday this week' ) );
    $wednesday = date( 'Y-m-d', strtotime( 'wednesday this week' ) );
    $thursday = date( 'Y-m-d', strtotime( 'thursday this week' ) );
    $friday = date( 'Y-m-d', strtotime( 'friday this week' ) );
    $saturday = date( 'Y-m-d', strtotime( 'saturday this week' ) );

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):

        $today_date = $list_day.'-'.$month.'-'.$year;
        $new_today_date = date_format(date_create_from_format('j-n-Y', $today_date), 'Y-m-d');

            $calendar.= '<td style="width:14.29%" class="calendar-day connectedSortable '.$new_today_date.'">';
            /* add in the day number */
            $class = '';
            if($new_today_date == date('Y-m-d')) {
                $class = 'today-btn';
            }
            $calendar.= '<div class="btn brand-btn pull-right ui-disabled '.$class.'">'.$list_day.'</div>';

            $result = mysqli_query($dbc,"SELECT * FROM interactive_calendar WHERE deleted=0 AND activity_date='$new_today_date'");

            while($row_tasklist = mysqli_fetch_array( $result )) {
				if($row_tasklist['activity_name'] != '') {
					$intid = $row_tasklist['intercalendarid'];
					$calendar .= '<br><a style="display:block; white-space: nowrap; text-overflow: ellipsis; overflow:hidden;" href="'.WEBSITE_URL.'/Interactive Calendar/add_interactive_calendar.php?intercalendarid='.$intid.'" id="activity_'.$intid.'" title="'.$row_tasklist['activity_name'].'">';
					if($row_tasklist['activity_name'] != '') {
						$calendar .= '<span style="display:block; padding: 5px;color:black;
							border-radius: 10px; background-color: #FF1493;">'.$row_tasklist['activity_name']. '</span></a>';
					}

					$morning_image = '';
					$lunch_image = '';
					$afternoon_image = '';
					$dinner_image = '';
					$evening_image = '';
					$morning_margin = '';
					$lunch_margin = '';
					$afternoon_margin = '';
					$evening_margin = '';
					$dinner_margin = '';
						if($row_tasklist['morning_image'] != null) {
							$morning_margin = 'margin-bottom:20px';
							$morning_image = 'images/'.$row_tasklist['morning_image'];
						}
						if($row_tasklist['lunch_image'] != null) {
							$lunch_margin = 'margin-bottom:20px';
							$lunch_image = 'images/'.$row_tasklist['lunch_image'];
						}
						if($row_tasklist['afternoon_image'] != null) {
							$afternoon_margin = 'margin-bottom:20px';
							$afternoon_image = 'images/'.$row_tasklist['afternoon_image'];
						}
						if($row_tasklist['dinner_image'] != null) {
							$dinner_margin = 'margin-bottom:20px';
							$dinner_image = 'images/'.$row_tasklist['dinner_image'];
						}
						if($row_tasklist['evening_image'] != null) {
							$evening_margin = 'margin-bottom:20px';
							$evening_image = 'images/'.$row_tasklist['evening_image'];
						}

					$calendar.= '<div class="ui-disabled pull-left '.$class.'">Morning</div><br><div><div class="drop-area" ondragenter="dragent(this)" ondragover="dragov(event)" ondrop="dropof(this,event)" name="'.$intid.'" style="'.$morning_margin.'" id="drop-area-morning-'.$intid.'">
					<span class="drop-text">Drag and Drop Images Here</span></div>';
					if($morning_image != '')
						$calendar.= '<img id="img-'.$intid.'" src="'.$morning_image.'" width="250" height="250" hspace=15 />';
					$calendar.= '</div><br>';
					$calendar.= '<div class="ui-disabled pull-left '.$class.'">Lunch</div><br><div><div class="drop-area" ondragenter="dragent(this)" ondragover="dragov(event)" ondrop="dropof(this,event)" style="'.$lunch_margin.'" name="'.$intid.'" id="drop-area-lunch-'.$intid.'"><span class="drop-text">Drag and Drop Images Here</span></div>';
					if($lunch_image != '')
						$calendar.= '<img id="img-'.$intid.'" src="'.$lunch_image.'" width="250" height="250" hspace=15 />';
					$calendar.= '</div><br>';
					$calendar.= '<div class="ui-disabled pull-left '.$class.'">Afternoon</div><br><div><div class="drop-area" ondragenter="dragent(this)" ondragover="dragov(event)" ondrop="dropof(this,event)" style="'.$afternoon_margin.'" name="'.$intid.'" id="drop-area-afternoon-'.$intid.'"><span class="drop-text">Drag and Drop Images Here</span></div>';
					if($afternoon_image != '')
						$calendar.= '<img id="img-'.$intid.'" src="'.$afternoon_image.'" width="250" height="250" hspace=15 />';
					$calendar.= '</div><br>';
					$calendar.= '<div class="ui-disabled pull-left '.$class.'">Dinner</div><br><div><div class="drop-area" ondragenter="dragent(this)" ondragover="dragov(event)" ondrop="dropof(this,event)" name="'.$intid.'" style="'.$dinner_margin.'" id="drop-area-dinner-'.$intid.'"><span class="drop-text">Drag and Drop Images Here</span></div>';
					if($dinner_image != '')
						$calendar.= '<img id="img-'.$intid.'" src="'.$dinner_image.'" width="250" height="250" hspace=15 />';
					$calendar.= '</div><br>';
					$calendar.= '<div class="ui-disabled pull-left '.$class.'">Evening</div><br><div><div class="drop-area" ondragenter="dragent(this)" ondragover="dragov(event)" ondrop="dropof(this,event)" style="'.$evening_margin.'" name="'.$intid.'" id="drop-area-evening-'.$intid.'"><span class="drop-text">Drag and Drop Images Here</span></div>';
					if($evening_image != '')
						$calendar.= '<img id="img-'.$intid.'" src="'.$evening_image.'" width="250" height="250" hspace=15 />';
					$calendar.= '</div>';
				}
                /*if($row_tasklist['activity_image'] != '') {
                    $calendar .= '<br><img width="250" height="250" src="download/'.$row_tasklist['activity_image'].'"></a>';
                }*/
            }

            /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
            $calendar.= str_repeat('<p> </p>',2);

            $calendar.= '</td>';

		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td style="width:14.29%" class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';

	/* all done, return result */
	return $calendar;
}
?>
