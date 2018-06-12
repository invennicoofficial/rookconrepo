<?php

// $Id$


// We need to construct a standard query string that can be passed to the *.js.php
// pages.  That's because some of the settings they use are area dependent.
// We also want the area to be in the queey string so that the JavaScript page is
// cached if the area is the same and not otherwise.
$standard_query_string = "area=$area";



// We are using the jQuery UI library, which has been custom built to include the following components:
//   - Core
//   - Widget
//   - Mouse
//   - Position
//   - Draggable
//   - Resizable
//   - Autocomplete
//   - Button
//   - Datepicker
//   - Dialog
//   - Menu
//   - Tabs
//   - Sunny theme
// If you need any other components you will need to rebuild the custom UI library
// See http://jqueryui.com/
?>

<!--[if (!IE)|(gt IE 8)]><!-->
  <script type="text/javascript" src="jquery/jquery-2.1.0.min.js"></script>
<!--<![endif]-->

<!--[if lte IE 8]>
  <script type="text/javascript" src="jquery/jquery-1.11.0.min.js"></script>
<![endif]-->

<?php
// We need the migration script now that we are running jQuery 1.9+, or at least
// until we are confident that our JavaScript is not using any features that have
// been removed.   The uncompressed version will output warnings about removed and
// deprecated features to the console
if (DEBUG)
{
  ?>
  <script type="text/javascript" src="jquery/jquery-migrate-1.2.1.js"></script>
  <?php
}
else
{
  ?>
  <script type="text/javascript" src="jquery/jquery-migrate-1.2.1.min.js"></script>
  <?php
}

if (DEBUG)
{
  ?>
  <!--<script type="text/javascript" src="jquery/ui/jquery-sortable.js"></script>
  <script type="text/javascript" src="jquery/ui/jquery-ui.js"></script>-->
  <script type="text/javascript" src="../js/jquery-ui-1.9.2.custom.js"></script>
  <?php
}
else
{
  ?>
  <!--<script type="text/javascript" src="jquery/ui/jquery-sortable.js"></script>
  <script type="text/javascript" src="jquery/ui/jquery-ui.min.js"></script>-->
  <script type="text/javascript" src="../js/jquery-ui-1.9.2.custom.js"></script>
  <?php
}

if (NULL !== ($datepicker_lang_file = get_datepicker_lang_file('jquery/ui/i18n')))
{
  echo "<script type=\"text/javascript\" src=\"${datepicker_lang_file}\"></script>";
}
?>

<?php // Work out if we're using IE6 or below ?>
<script type="text/javascript">
  //<![CDATA[
  var lteIE6 = false;
  //]]>
</script>
<!--[if lte IE 6]>
<script type="text/javascript">
  //<![CDATA[
  var lteIE6 = true;
  //]]>
</script>
<![endif]-->

<?php // Work out if we're using IE7 or below ?>
<script type="text/javascript">
  //<![CDATA[
  var lteIE7 = false;
  //]]>
</script>
<!--[if lte IE 7]>
<script type="text/javascript">
  //<![CDATA[
  var lteIE7 = true;
  //]]>
</script>
<![endif]-->

<?php // Work out if we're using IE8 or below ?>
<script type="text/javascript">
  //<![CDATA[
  var lteIE8 = false;
  //]]>
</script>
<!--[if lte IE 8]>
<script type="text/javascript">
  //<![CDATA[
  var lteIE8 = true;
  //]]>
</script>
<![endif]-->

<script type="text/javascript">
  //<![CDATA[
  <?php
  // Create an empty init() function.   This will get "extended" if necessary
  // by the page specific JavaScript files in the js directory
  ?>
  function init(params)
  {
  }
  //]]>
</script>

<?php
// All pages
?>
<script type="text/javascript" src="js/functions.js.php?<?php echo $standard_query_string ?>"></script>
<script type="text/javascript" src="js/datepicker.js.php?<?php echo $standard_query_string ?>"></script>
<script type="text/javascript" src="js/general.js.php?<?php echo $standard_query_string ?>"></script>
<script type="text/javascript" src="../js/bootstrap.min.js?<?php echo $standard_query_string ?>"></script>
<script>
	$(document).ready(function(){
		$(".popover-examples a").tooltip({
			trigger: 'hover'
		});
	});
</script>


<?php
// dataTables initialisation
if (in_array($page, array('admin', 'edit_users', 'pending', 'report', 'search')))
{
  // Include the JavaScript for those pages that use dataTables
  if (DEBUG)
  {
    ?>
    <script type="text/javascript" src="jquery/datatables/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="jquery/datatables/js/dataTables.buttons.js"></script>
    <script type="text/javascript" src="jquery/datatables/js/buttons.colVis.js"></script>
    <script type="text/javascript" src="jquery/datatables/js/dataTables.fixedColumns.js"></script>
    <script type="text/javascript" src="jquery/datatables/js/dataTables.colReorder.js"></script>
    <?php
  }
  else
  {
    ?>
    <script type="text/javascript" src="jquery/datatables/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="jquery/datatables/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="jquery/datatables/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="jquery/datatables/js/dataTables.fixedColumns.min.js"></script>
    <script type="text/javascript" src="jquery/datatables/js/dataTables.colReorder.min.js"></script>
    <?php
  }
  ?>

  <script type="text/javascript" src="jquery/datatables/js/plugins.js"></script>

  <script type="text/javascript" src="js/datatables.js.php?<?php echo $standard_query_string ?>"></script>

  <?php
}


// Get any page specific JavaScript
if (file_exists("js/$page.js"))
{
  ?>
  <script type="text/javascript" src="js/<?php echo $page?>.js"></script>
  <?php
}
if (file_exists("js/$page.js.php"))
{
  ?>
  <script type="text/javascript" src="js/<?php echo $page?>.js.php?<?php echo $standard_query_string ?>"></script>
  <?php
}


// We need the resizable booking, multiple bookings and refresh code for the day and week pages
if (in_array($page, array('day', 'week')))
{
  ?>
  <script type="text/javascript" src="js/refresh.js.php?<?php echo $standard_query_string ?>"></script>
  <script type="text/javascript" src="js/resizable.js.php?<?php echo $standard_query_string ?>"></script>
  <script type="text/javascript" src="js/multiple.js.php?<?php echo $standard_query_string ?>"></script>
  <?php
}

if (in_array($page, array('day', 'week', 'month')))
{
  ?>
  <script type="text/javascript" src="js/cell_click.js.php?<?php echo $standard_query_string ?>"></script>
  <?php
}
?>

<script type="text/javascript">

//<![CDATA[


$(window).on('load', function() {

  var args = {day: '<?php echo $day?>',
              month: '<?php echo $month?>',
              year: '<?php echo $year?>',
              area: '<?php echo $area?>',
              room: '<?php echo $room?>',
              page: '<?php echo $page?>'};
  <?php
  // We need $timetohighlight for the day and week pages
  $timetohighlight = get_form_var('timetohighlight', 'int');
  if (isset($timetohighlight))
  {
    ?>
    args.timetohighlight = '<?php echo $timetohighlight?>';
    <?php
  }
  ?>
  init(args);

});

//]]>

$(document).ready(function() {

    $('.I a').append('Holiday');
    $('.E a').append('Break');
    $('.Q a').prepend('No Book Days');
    $('.R a').prepend('Vacation');

    $('.A a, .C a, .H a, .N a, .F a').prepend('<img style="float:right; height:15px; margin-right: 20px; margin-top: 2px;" src="../img/filled_star.png" />');
    /*
    $('.A a').prepend('Patient : ');
    $('.F a').prepend('Patient : ');
    $('.B a').prepend('Patient : ');
    $('.C a').prepend('Patient : ');
    $('.D a').prepend('Patient : ');
    $('.G a').prepend('Patient : ');
    $('.H a').prepend('Patient : ');
    $('.J a').prepend('Patient : ');
    $('.K a').prepend('Patient : ');
    $('.L a').prepend('Patient : ');
    $('.M a').prepend('Patient : ');
    $('.N a').prepend('Patient : ');
    $('.O a').prepend('Patient : ');
    $('.S a').prepend('Patient : ');
    $('.T a').prepend('Patient : ');
    */

    $('#edit_entry_submit_save .submit').on('click', function() {
        var type = $("#type").val();
        var total_waitlist = $("#total_waitlist").val();
        var patientstatus = $("#f_patientstatus").val();

        if(type == 'P') {
            alert("Please make sure you have filled Type of Appointment.");
            return false;
        }

        if(patientstatus == 'Cancelled' && total_waitlist >= 1) {
            alert('Attention: There are patients on the waitlist who can fill this timeslot.');
        }

        if(type != 'I' && type != 'E' && type != 'Q' && type != 'R') {
            var f_patient = $("#f_patient").val();
            var f_injury = $("#f_injury").val();
            if (f_patient == '' || f_injury == 'Please select' || f_injury == '') {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
        }
    });
});

function selectPatient(sel) {

    var status = sel.value;

    /*
    $.ajax({
		type: "GET",
		url: "../ajax_all.php?fill=mrbs&patient="+status,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#f_patient").replaceWith(response);
		}
	});
    */

    setGetParameter('patient',status);
}

function setGetParameter(paramName, paramValue)
{
    var url = window.location.href;
    var hash = location.hash;
    url = url.replace(hash, '');
    if (url.indexOf(paramName + "=") >= 0)
    {
        var prefix = url.substring(0, url.indexOf(paramName));
        var suffix = url.substring(url.indexOf(paramName));
        suffix = suffix.substring(suffix.indexOf("=") + 1);
        suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
        url = prefix + paramName + "=" + paramValue + suffix;
    }
    else
    {
    if (url.indexOf("?") < 0)
        url += "?" + paramName + "=" + paramValue;
    else
        url += "&" + paramName + "=" + paramValue;
    }
    window.location.href = url + hash;
}

</script>