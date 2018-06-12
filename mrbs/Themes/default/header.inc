<?php

// $Id$


// Print the page header
function print_theme_header($day, $month, $year, $area, $room)
{
  global $mrbs_company, $mrbs_company_logo, $mrbs_company_url, $mrbs_company_more_info,
         $search_str, $locale_warning;
  global $tbl_entry, $tbl_room, $tbl_area;
  global $PHP_SELF, $HTTP_HOST, $QUERY_STRING;
  global $view_week_number, $weekstarts, $times_along_top, $periods, $enable_periods;
  global $auth, $max_level;
  global $default_language_tokens, $disable_automatic_language_changing, $override_locale;
  global $select_options;
  global $ajax_refresh_rate;
  global $main_table_cell_border_width, $main_cell_height;
  global $timetohighlight;

  $page = basename($PHP_SELF, ".php");
  $user = getUserName();
  $is_admin = (authGetUserLevel($user) >= $max_level);

  // Need to set the timezone before we can use date()
  get_area_settings($area);

  // If we dont know the right date then make it up
  if (!$day)
  {
    $day   = date("d");
  }
  if (!$month)
  {
    $month = date("m");
  }
  if (!$year)
  {
    $year  = date("Y");
  }
  if (!isset($search_str))
  {
    $search_str = "";
  }

  http_headers();
  echo DOCTYPE;
?>

<html>
  <head>

  <meta charset="<?php echo get_charset(); ?>">
  <title><?php echo get_vocab("mrbs") ?></title>

  <?php
  require_once "style.inc";
  require_once "js.inc";
  ?>

  </head>

  <?php
  // Put the filename in as a class to aid styling.
  // (Use a class rather than id to avoid specificity problems)
  echo "<body class=\"non_js ".htmlspecialchars($page)."\">\n";

    // Add a class of "js" so that we know if we're using JavaScript or not
    // and remove the non_js class (it's sometimes useful to know that we're
    // not running JavaScript)
  ?>
    <script type="text/javascript">
      //<![CDATA[
      $('body').addClass('js').removeClass('non_js');
      //]]>
    </script>

    <div class="screenonly">

<?php
  if (!empty($locale_warning))
  {
    echo "[Warning: ".$locale_warning."]";
  }
?>

  <table id="banner">
    <tr>
      <td id="company">
        <div>
          <?php
          echo "<div id=\"logo\">\n";
          if (!empty($mrbs_company_url))
          {
            echo "<a href=\"$mrbs_company_url\">\n";
          }
          if (empty($mrbs_company_logo))
          {
            echo "<span>$mrbs_company</span>\n";
          }
          else
          {
            // Suppress error messages in case the logo is a URL and allow_url_fopen
            // is not enabled in php.ini
            $logo_size = @getimagesize($mrbs_company_logo);
            echo "<a href='index.php'><img src=\"$mrbs_company_logo\" " . $logo_size[3] . " alt=\"$mrbs_company\"></a>\n";
          }
          if (!empty($mrbs_company_url))
          {
            echo "</a>\n";
          }
          echo "</div>\n";
          if (!empty($mrbs_company_more_info))
          {
            echo "<div id=\"more_info\">$mrbs_company_more_info</div>\n";
          }
          ?>
          <div id="mrbs">
            <a href="index.php"><?php echo get_vocab("mrbs") ?></a>
          </div>
        </div>
      </td>
      <td>
        <form action="day.php" method="get" id="Form1">
          <div>
            <?php
            // Give the form id as the optional fifth parameter because we want
            // the form to be automatically submitted when the datepicker is closed
            genDateSelector("", $day, $month, $year, "Form1");
            if (!empty($area))
            {
              echo "<input type=\"hidden\" name=\"area\" value=\"$area\">\n";
            }
            if (!empty($room))
            {
              echo "<input type=\"hidden\" name=\"room\" value=\"$room\">\n";
            }
            // Although the datepicker will automatically go to the new date when
            // the date is changed, we still need a submit button because there
            // are occasions when you want to go to the date without changing it -
            // for example when you've been on a Search or Report page
            echo "<input type=\"submit\" value=\"" . get_vocab("goto") . "\">";
            ?>

            <span class="icon_summary" style="width:540px;">
				<span class="popover-examples list-inline" style="float:left; display:inline-block; height:80px; min-width:50px;"><a data-toggle="tooltip" data-placement="right" title="These symbols represent the statuses of the appointments in the calendar."><img style="padding-top:20px;" src="../img/info.png" width="20"></a></span>
				<img src="images/status_icon/unconfirmed.png" width="25" height="25" border="0" alt="" style="vertical-align:middle">Booked Unconfirmed&nbsp;&nbsp;
				<img src="images/status_icon/confirmed.png" width="25" height="25" border="0" alt="" style="vertical-align:middle">Booked Confirmed&nbsp;&nbsp;
				<img src="images/status_icon/arrived.png" width="25" height="25" border="0" alt="" style="vertical-align:middle">&nbsp;Arrived&nbsp;&nbsp;
				<img src="images/status_icon/invoiced.png" width="20" height="20" border="0" alt="" style="vertical-align:middle">&nbsp;Invoiced<br><br>
				<img src="images/status_icon/paid.png" width="25" height="25" border="0" alt="" style="vertical-align:middle">Paid&nbsp;&nbsp;
				<img src="images/status_icon/rescheduled.png" width="30" height="25" border="0" alt="" style="vertical-align:middle">&nbsp;Rescheduled&nbsp;&nbsp;
				<img src="images/status_icon/noshow.png" width="23" height="23" border="0" alt="" style="vertical-align:middle">Late Cancellation / No-Show&nbsp;&nbsp;
				<img src="images/status_icon/cancel.png" width="20" height="20" border="0" alt="" style="vertical-align:middle">&nbsp;Cancelled
            </span>

           </div>

        </form>
        <?php


        // Provide a link to the list of bookings awaiting approval
        // (if there are any enabled areas where we require bookings to be approved)

        $approval_somewhere = some_area('approval_enabled', TRUE);
        if ($approval_somewhere && (authGetUserLevel($user) >= 1))
        {
          $sql_approval_enabled = some_area_predicate('approval_enabled');
          // Find out how many bookings are awaiting approval
          // (but only for areas where approval is required)
          $sql = "SELECT COUNT(*)
                    FROM $tbl_entry E, $tbl_room R, $tbl_area A
                   WHERE (status&" . STATUS_AWAITING_APPROVAL . " != 0)
                     AND E.room_id = R.id
                     AND R.area_id = A.id
                     AND R.disabled = 0
                     AND A.disabled = 0
                     AND $sql_approval_enabled";
          if (!$is_admin)
          {
            // Ordinary users can only see their own
            $sql .= " AND create_by='" . sql_escape($user) . "'";
          }
          $n_outstanding = sql_query1($sql);
          if ($n_outstanding < 0)
          {
            trigger_error(sql_error(), E_USER_WARNING);
            fatal_error(FALSE, get_vocab("fatal_db_error"));
          }
          echo "<div id=\"n_outstanding\"" .
               (($n_outstanding > 0) ? " class=\"outstanding\"" : '') .
               ">\n";
          echo "<a href=\"pending.php?day=$day&amp;month=$month&amp;year=$year&amp;area=$area" .
               ((!empty($room)) ? "&amp;room=$room" : "") .
               "\">$n_outstanding " . get_vocab("outstanding") . "</a>\n";
          echo "</div>\n";
        }
        ?>
      </td>
      <?php
      $query_str = "day=$day&amp;month=$month&amp;year=$year";
      if (!empty($area))
      {
        $query_str .= "&amp;area=$area";
      }
      if (!empty($room))
      {
        $query_str .= "&amp;room=$room";
      }

      //echo "<td>\n";
      //echo "<a href=\"help.php?$query_str\"><img src='images/help-icon.png' alt='Help'/></br>" . get_vocab("help") . "</a>\n";
      //echo "</td>\n";

      echo "<td>\n";
	  echo '<a class="btn brand-btn pull-right" href="#" onclick=" window.open(\''."http://" . $_SERVER['SERVER_NAME'].'/Booking/waitlist.php?contactid=0\', \'newwindow\', \'width=900, height=900\'); return false;"><img src="images/therapists-icon.png" alt="Help"/></a><br /><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="This will create a popup window that shows each therapist\'s patients that have been placed in the waitlist."><img src="../img/info.png" width="20"></a></span>Waitlist';
      echo "</td>\n";

      //echo "<td>\n";
      //echo "<a href=\"admin.php?$query_str\"><img src='images/therapists-icon.png' alt='Therapists'/></br>" . get_vocab("rooms") . "</a>\n";
      //echo "</td>\n";

      echo "<td>\n";
	  echo "<a href=\"report.php?$query_str\"><img src='images/reports-icon.png' alt='Reports'/></a></br><span class='popover-examples list-inline'><a data-toggle='tooltip' data-placement='top' title='View a customizable report by clicking here and filling out the required fields.'><img src='../img/info.png' width='20'></a></span>" . get_vocab("report") . "\n";
      echo "</td>\n";
      ?>

      <td>
        <form id="header_search" method="get" action="search.php">
          <div>
            <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="left" title="Search for past and future appointments here."><img src="../img/info.png" width="20"></a></span>
			<a href="search.php?advanced=1"><?php echo get_vocab("search") ?>:</a>
            <input type="search" name="search_str" value="<?php echo htmlspecialchars($search_str) ?>" required>
            <input type="hidden" name="day"        value="<?php echo $day        ?>">
            <input type="hidden" name="month"      value="<?php echo $month      ?>">
            <input type="hidden" name="year"       value="<?php echo $year       ?>">
            <?php
            if (!empty($area))
            {
              echo "<input type=\"hidden\" name=\"area\" value=\"$area\">\n";
            }
            if (!empty($room))
            {
              echo "<input type=\"hidden\" name=\"room\" value=\"$room\">\n";
            }
            ?>
          </div>
        </form>
      </td>
      <?php
      // For session protocols that define their own logon box...
      if (function_exists('PrintLogonBox'))
      {
        echo "<td>\n";
        echo "<div id=\"logon_box\">\n";
        PrintLogonBox();
        echo "</div>\n";
        echo "</td>\n";
      }
      ?>
    </tr>
  </table>
</div>
<div class='iframe_holder' style='display:none; width:80%;margin:auto;'>
		<img src='http://<?php echo $_SERVER['SERVER_NAME']; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:93px; cursor:pointer;'>
		<span class='iframe_title' style='font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
</div>
<div id="contents" class="hide_on_iframe">
<?php

} // end of print_theme_header()

