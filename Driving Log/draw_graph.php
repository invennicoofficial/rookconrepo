<?php 

/**
 * File Name : draw_graph.php
 * 	
 * Long description for file (if any)...
 *
 * @author Fresh Focus Media
*/

?>

<script type="text/javascript">
window.onload = function () {

    var graph_data = $('#graph_data').val();
	var numbersArray = graph_data.split(',,');
    var sritems = [];
	
    for (var i = 0; i < numbersArray.length; i += 3) {
      var j = i;
      var k = j + 1;
      var m = k + 1;
	  
      sritems.push({
        "x": parseInt(numbersArray[j], 10),
        "y": JSON.parse(numbersArray[k]),
        "label": numbersArray[m].trim().slice(1, -1) // Remove surrounding quotes
      });
    }
        CanvasJS.addColorSet("greenShades",
                [//colorSet Array

                "transparent"
                             
                ]);
    //alert(JSON.stringify(sritems, 2));

    var chart = new CanvasJS.Chart("chartContainer",
	{
        backgroundColor: "",
		colorSet: "greenShades",
		title:{
			text: " Driving Log",
			fontColor: "transparent"
		},
		exportEnabled: true,
		axisY: {
			includeZero:false,
			title: " ",
			interval: 1,
            maximum: 24,
			 tickLength: 0,
			lineThickness: 1,
			lineColor: "transparent",
			gridThickness: 0,
			labelFontColor: "transparent",
            minimum: 0,
			valueFormatString: ""
		},
		axisX: {
			interval:10,
			title: " ",
			lineThickness: 0,
			 tickLength: 0,
			lineColor: "black",
			labelFontColor: "transparent",
            gridColor: "lightblack" ,
            gridThickness: 0,
			valueFormatString: ""
		},
		      legend:{
        fontColor: "transparent"
 		},
		dataPointMaxWidth: 6,
		data: [
		{
			type: "rangeBar",
			showInLegend: true,
			yValueFormatString: "#0.##",
			legendText: "Duty Wise Time",
			dataPoints:
            sritems
		}
		]
	});
	chart.render();
}

</script>
<style type="text/css" media="screen">
    /*canvas, img { display:block; margin:1em auto; border:1px solid black; }
    canvas { background:url(img/timer_ruler.png) }
    */
	
  </style>
</head>
<body>
<?php
$drivinglogid = $_GET['drivinglogid'];
$result_graph = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM driving_log_graph WHERE drivinglogid='$drivinglogid'"));
$graph_data =$result_graph['graph_data'];
?>
<img id="canvasImg" />

<input type="hidden" id="graph_data" name="graph_data" value="<?php echo $graph_data ?>" />
<input type="hidden" class="dataurl" name="dataurl_image" value="x" />
<input type="hidden" name="drivelogid" value="<?php echo $_GET['drivinglogid']; ?>">
<img src='../img/warning.png' width="25px"> <em>Is your graph not displaying correctly? Try <a style='text-decoration:underline;cursor:pointer;' onclick="window.location.reload(true);">reloading</a> the current page.</em><br><br>
<canvas style='background-color:white; display:none; width:100%;' id="bottleCanvas" width=1580 height=400>Please wait...</canvas>
<?php 
		$select_timers = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogid' ORDER BY timerid DESC";
		$seconds = 0;
		$minutes = 0;
		$hours = 0;
		
		$_offseconds = 0;
		$_offminutes = 0;
		$_offhours = 0;
		
		$_driveseconds = 0;
		$_driveminutes = 0;
		$_drivehours = 0;
		
		$sleepseconds = 0;
		$sleepminutes = 0;
		$sleephours = 0;
		
		$result2 = mysqli_query($dbc, $select_timers);
		$num_rows2 = mysqli_num_rows($result2);
		$is_reset = '';
		if($num_rows2 > 0) {
			while($row2 = mysqli_fetch_array($result2)) {
				if($row2['on_duty_timer'] !== '' && $row2['on_duty_timer'] !== NULL) {
					
					$reverse_explode = array_reverse(explode(':',$row2['on_duty_timer']));
					
					$i = 0;
					$len = count($reverse_explode);
					
					foreach( $reverse_explode as $time ) {
						
						
						if ($i == 0) {
							$seconds += $time;
						} else if ($i == $len - 1) {
							$hours += $time;
						} else {
							$minutes += $time;
						}
						// …
						$i++;
						
					}
				}
				if($row2['driving_timer'] !== '' && $row2['driving_timer'] !== NULL) {
					$reverse_explode = array_reverse(explode(':',$row2['driving_timer']));
					
					$i = 0;
					$len = count($reverse_explode);
					
					foreach( $reverse_explode as $time ) {
						
						
						if ($i == 0) {
							$_driveseconds += $time;
						} else if ($i == $len - 1) {
							$_drivehours += $time;
						} else {
							$_driveminutes += $time;
						}
						// …
						$i++;
						
					}
				}
				
				if($row2['off_duty_timer'] !== '' && $row2['off_duty_timer'] !== NULL) {
					$reverse_explode = array_reverse(explode(':',$row2['off_duty_timer']));
					
					$i = 0;
					$len = count($reverse_explode);
					
					foreach( $reverse_explode as $time ) {
						
						
						if ($i == 0) {
							$_offseconds += $time;
						} else if ($i == $len - 1) {
							$_offhours += $time;
						} else {
							$_offminutes += $time;
						}
						// …
						$i++;
						
					}
				}
				
				if($row2['sleeper_berth_timer'] !== '' && $row2['sleeper_berth_timer'] !== NULL) {
					$reverse_explode = array_reverse(explode(':',$row2['sleeper_berth_timer']));
					
					$i = 0;
					$len = count($reverse_explode);
					
					foreach( $reverse_explode as $time ) {
						
						
						if ($i == 0) {
							$sleepseconds += $time;
						} else if ($i == $len - 1) {
							$sleephours += $time;
						} else {
							$sleepminutes += $time;
						}
						// …
						$i++;
						
					}
				}
				
			}
		}
		
		$minute_from_seconds1 = $sleepseconds/60;
		$minute_from_seconds2 = $_offseconds/60;
		$minute_from_seconds3 = $_driveseconds/60;
		$minute_from_seconds4 = $seconds/60;
		
		$minute_from_seconds_t = $minute_from_seconds1+$minute_from_seconds2+$minute_from_seconds3+$minute_from_seconds4;
		
		$minute_add1 = floor($minute_from_seconds1);
		$minute_add2 = floor($minute_from_seconds2);
		$minute_add3 = floor($minute_from_seconds3);
		$minute_add4 = floor($minute_from_seconds4);
		$minute_addt = floor($minute_from_seconds_t);
		
		$seconds_left1 = $minute_from_seconds1 - $minute_add1;
		$seconds_left2 = $minute_from_seconds2 - $minute_add2;
		$seconds_left3 = $minute_from_seconds3 - $minute_add3;
		$seconds_left4 = $minute_from_seconds4 - $minute_add4;
		$seconds_leftt = $minute_from_seconds_t - $minute_addt;
		$seconds1 = $seconds_left1*60;
		$seconds2 = $seconds_left2*60;
		$seconds3 = $seconds_left3*60;
		$seconds4 = $seconds_left4*60;
		$secondst = $seconds_leftt*60;
		
		if(strlen($seconds1) < 2) {
			$seconds1 = '0'.$seconds1; 
		}
		if(strlen($seconds2) < 2) {
			$seconds2 = '0'.$seconds2; 
		}
		if(strlen($seconds3) < 2) {
			$seconds3 = '0'.$seconds3; 
		}
		if(strlen($seconds4) < 2) {
			$seconds4 = '0'.$seconds4; 
		}
		if(strlen($secondst) < 2) {
			$secondst = '0'.$secondst; 
		}
		
		$minutes1 = $sleepminutes + $minute_add1;
		$minutes2 = $_offminutes + $minute_add2;
		$minutes3 = $_driveminutes + $minute_add3;
		$minutes4 = $minutes + $minute_add4;
		$minutest = $sleepminutes +$_offminutes +$_driveminutes +$minutes+$minute_addt;
		
		$hours_from_minutes1 = $minutes1/60;
		$hours_from_minutes2 = $minutes2/60;
		$hours_from_minutes3 = $minutes3/60;
		$hours_from_minutes4 = $minutes4/60;
		$hours_from_minutest = $minutest/60;
		
		$hour_add1 = floor($hours_from_minutes1);
		$hour_add2 = floor($hours_from_minutes2);
		$hour_add3 = floor($hours_from_minutes3);
		$hour_add4 = floor($hours_from_minutes4);
		$hour_addt = floor($hours_from_minutest);
		
		$minutes_left1 = $hours_from_minutes1 - $hour_add1;
		$minutes_left2 = $hours_from_minutes2 - $hour_add2;
		$minutes_left3 = $hours_from_minutes3 - $hour_add3;
		$minutes_left4 = $hours_from_minutes4 - $hour_add4;
		$minutes_leftt = $hours_from_minutest - $hour_addt;
		$minutes1 = $minutes_left1*60;
		$minutes2 = $minutes_left2*60;
		$minutes3 = $minutes_left3*60;
		$minutes4 = $minutes_left4*60;
		$minutest = $minutes_leftt*60;
		
		if(strlen($minutes1) < 2) {
			$minutes1 = '0'.$minutes1; 
		}
		if(strlen($minutes2) < 2) {
			$minutes2 = '0'.$minutes2; 
		}
		if(strlen($minutes3) < 2) {
			$minutes3 = '0'.$minutes3; 
		}
		if(strlen($minutes4) < 2) {
			$minutes4 = '0'.$minutes4; 
		}
		if(strlen($minutest) < 2) {
			$minutest = '0'.$minutest; 
		}
		
		$hours1 = $sleephours+$hour_add1;
		$hours2 = $_offhours+$hour_add2;
		$hours3 = $_drivehours+$hour_add3;
		$hours4 = $hours+$hour_add4;
		$hourst = $sleephours+$_offhours+$_drivehours+$hours+$hour_addt;
		
		if(strlen($hours1) < 2) {
			$hours1 = '0'.$hours1; 
		}
		if(strlen($hours2) < 2) {
			$hours2 = '0'.$hours2; 
		}
		if(strlen($hours3) < 2) {
			$hours3 = '0'.$hours3; 
		}
		if(strlen($hours4) < 2) {
			$hours4 = '0'.$hours4; 
		}
		if(strlen($hourst) < 2) {
			$hourst = '0'.$hourst; 
		}
		
		$sleep_h_time = $hours1.':'.$minutes1;
		$off_h_time = $hours2.':'.$minutes2;
		$drive_h_time = $hours3.':'.$minutes3;
		$on_h_time = $hours4.':'.$minutes4;
		
		if($hourst >= 24 && ($minutest >0 || $secondst > 0)) {
			$total_t_time = '24:00';
		} else {
			$total_t_time = $hourst.':'.$minutest;
		}
		
?>
<style>
.totals_hour {
	display:inline-block;
	padding-top:20px;
	padding-bottom:20px;
	position:relative;
	top:10px;
	font-weight:bold;
}
</style>

<input type='hidden' name='xvalue_vertical_line_graph' class='xvalue_vertical_line_graph' value=''>

<div style='width:100%; overflow:auto; height:450px;' class='hidemeafterload'><table><tr><td><div id="chartContainer" style="height: 400px; width: 1580px;" style='overflow:auto;'></div></td><!--<td><div class='totals_hour'><?php //echo $off_h_time; ?></div><div class='totals_hour'><?php //echo $sleep_h_time; ?></div><div class='totals_hour'><?php //echo $drive_h_time; ?></div><div class='totals_hour'><?php //echo $on_h_time; ?></div><!--<div class='totals_hour' style='border-top: 3px solid black;'><?php //echo $total_t_time; ?></div>--><!--</td>--></tr></table></div>

<a style='display:none;' href="#" id="btn-download"  target="_blank">Download</a>
<div class='create-pdf' style='display:none;'>DONT DELETE ME</div>

<script>
$(document).ready(function() {
	setTimeout(
  function() 
  {
    $('.create-pdf').click();
  }, 1000);
	
$(".create-pdf").click(function () {
 /* var canvas = $("#chartContainer .canvasjs-chart-canvas").get(0);
    var dataURL = canvas.toDataURL('image/png');
    console.log(dataURL);
    $("#btn-download").attr("href", dataURL);
	$(".dataurl").val(dataURL);
	$(".submit-create-pdf").click();*/
	
	var img=new Image();
	img.crossOrigin='anonymous';
	img.onload=start;
	img.src="tmp/background-lines.png";
function start(){

  var bottleCanvas = $("#bottleCanvas").get(0);
 // var designCanvas = $("#chartContainer .canvasjs-chart-canvas").get(0);
  
  var ctxb=bottleCanvas.getContext('2d');
  //var ctxd=editorCanvas.getContext('2d');
  ctxb.drawImage(img,0,0);
  ctxb.beginPath();
// CREATE VERTICAL GREEN LINES THAT CONNECT THE HORIZONTAL LINES IN GRAPH.
	var graph_data = $('#graph_data').val();
	var numbersArray = graph_data.split(',,');
	console.log(graph_data+' zzz ');
	var totaltimer = 0;
	for (var i = 0; i < numbersArray.length; i += 3) {
		totaltimer++;
	}
	var timernum = 0;
	
	for (var i = 0; i < numbersArray.length; i += 3) {
		timernum++;
	  if(timernum == totaltimer) { console.log('last!'); }
      var j = i;
      var k = j + 1;
      var m = k + 1;
	  
	  var timernum2 = 0;
		for (var q = 0; q < numbersArray.length; q += 3) {
		timernum2++;
		  var g = q;
		  var s = g + 1;
		  if(timernum2 == (timernum+1)) {
			var nextxval = JSON.parse(numbersArray[s]);
			if(parseInt(numbersArray[g]) == '40') {
				var yvalue2 = 79;
				var typer = '40';
			}
			if(parseInt(numbersArray[g]) == '30') {
				var yvalue2 = 147;
				var typer = '30';
			}
			if(parseInt(numbersArray[g]) == '20') {
				var yvalue2 = 211;
				var typer = '20';
			}
			if(parseInt(numbersArray[g]) == '10') {
				var yvalue2 = 277;
				var typer = '10';
			} 
		  }
		}
		if(parseInt(numbersArray[j]) == '40') {
			var yvalue = 79;
			var typer1 = '40';
		}
		if(parseInt(numbersArray[j]) == '30') {
			var yvalue = 147;
			var typer1 = '30';
		}
		if(parseInt(numbersArray[j]) == '20') {
			var yvalue = 211;
			var typer1 = '20';
		}
		if(parseInt(numbersArray[j]) == '10') {
			var yvalue = 277;
			var typer1 = '10';
		}
		console.log(parseInt(numbersArray[j])+' xx '+JSON.parse(numbersArray[k])+' xx '+m+' xxxxxx ');
		
		var graph_data = $('#graph_data').val();
		var numbersArray = graph_data.split(',,');
		
		$('.xvalue_vertical_line_graph').val(JSON.parse(numbersArray[k]));
		var xvalue = $('.xvalue_vertical_line_graph').val();
		var xvaluearray = xvalue.split(',');
		
		// START Draw horiziontal lines of the graph //
		console.log(xvaluearray[0]+' x '+xvaluearray[1]);
		var begin_horiz_line = ((xvaluearray[0]/24)*1430)+142;
		ctxb.moveTo(begin_horiz_line,yvalue);
		var end_horiz_line = ((xvaluearray[1]/24)*1430)+142;
		ctxb.lineTo(end_horiz_line,yvalue);
		// END of Draw horiziontal lines of the graph //
		p = 0;
		for (var a in xvaluearray)
		{	
			var variable = xvaluearray[a];
			if(p == 1) {
				var xval = ((variable/24)*1430)+142;
				console.log(xval);
			}
			p++;
		}
		if(yvalue2 != yvalue && nextxval != '24,24') {
			ctxb.moveTo(xval,yvalue);
			console.log(xval + ' XVAL << >> # '+timernum+ ' ## '+yvalue+ ' ## '+typer1+ ' ## '+nextxval);
			ctxb.lineTo(xval,yvalue2);
			console.log(xval + ' XVAL << >> # '+timernum+ ' ## '+yvalue2+ ' ## '+typer+ ' ## '+nextxval);
		}
		
		
    }
	// END CREATING VERTICAL GREEN LINES IN GRAPH
	
	ctxb.lineWidth = 4; 
	ctxb.strokeStyle = '#30C73A';
	ctxb.stroke();
	// None of the commented out code below is needed anymore (for now...)
	// Get rid of green dot on graph by covering it with a white line VV 
	/*
	var imgtwo=new Image();
	imgtwo.crossOrigin='anonymous';
	imgtwo.onload=start;
	 var designCxanvas = $("#chartContainer .canvasjs-chart-canvas").get(0);
	  var ctzb=designCxanvas.getContext('2d');
	  ctzb.drawImage(imgtwo,0,0);
	  ctzb.beginPath();
	  ctzb.moveTo('1','1');
	  ctzb.lineTo('1','1');
	  ctzb.lineWidth = 50; 
	  ctzb.strokeStyle = 'transparent'; // No longer needed
	  ctzb.stroke();
	  
	  // End of getting rid of that green dot!
	  
	  // Draw the horizontal lines now:
	  
	  var imgthree=new Image();
	  imgtwo.crossOrigin='anonymous';
	  imgtwo.onload=start;
	  var designCxxanvas = $("#chartContainer .canvasjs-chart-canvas").get(0);
	  var ctdb=designCxxanvas.getContext('2d');
	  ctdb.drawImage(imgtwo,0,0);
	  ctdb.beginPath();
	  
	  
	  var graph_data = $('#graph_data').val();
		var numbersArray = graph_data.split(',,');
		var totaltimer = 0;
		for (var i = 0; i < numbersArray.length; i += 3) {
			totaltimer++;
		}
		var timernum = 0;
		
		for (var i = 0; i < numbersArray.length; i += 3) {
			timernum++;
		  if(timernum == totaltimer) { console.log('last!'); }
		  var j = i;
		  var k = j + 1;
		  var m = k + 1;
		  
			if(parseInt(numbersArray[j]) == '40') {
				var yvalue = 79;
				var typer1 = '40';
			}
			if(parseInt(numbersArray[j]) == '30') {
				var yvalue = 147;
				var typer1 = '30';
			}
			if(parseInt(numbersArray[j]) == '20') {
				var yvalue = 211;
				var typer1 = '20';
			}
			if(parseInt(numbersArray[j]) == '10') {
				var yvalue = 277;
				var typer1 = '10';
			}
			
			var graph_data = $('#graph_data').val();
			var numbersArray = graph_data.split(',,');
			
			$('.xvalue_vertical_line_graph').val(JSON.parse(numbersArray[k]));
			var xvalue = $('.xvalue_vertical_line_graph').val();
			var xvaluearray = xvalue.split(',');
			
			// START Draw horiziontal lines of the graph //
			console.log(xvaluearray[0]+' x '+xvaluearray[1]);
			var begin_horiz_line = ((xvaluearray[0]/24)*1430)+142;
			ctdb.moveTo(begin_horiz_line,yvalue);
			var end_horiz_line = ((xvaluearray[1]/24)*1430)+142;
			ctdb.lineTo(end_horiz_line,yvalue);
			// END of Draw horiziontal lines of the graph //
		}
		  ctdb.lineWidth = 5; 
		  ctdb.strokeStyle = 'transparent'; //This functionality does not appear to be working on all browsers/devices for some reason... Disabled for now, using transparent color.
		  ctdb.stroke();
	  
	  
	  // END of drawing the horizontal lines - that's a wrap, people!
	*/
	downloadCanvas();
	$('.hidemeafterload').hide();
	$('#bottleCanvas').show();
} 

function downloadCanvas() {
  var bottleCanvas = $("#bottleCanvas").get(0);
  var designCanvas = $("#chartContainer .canvasjs-chart-canvas").get(0);
	
  var bottleContext = bottleCanvas.getContext('2d');
  bottleContext.drawImage(designCanvas, 0, 0);

  var dataURL = bottleCanvas.toDataURL("image/jpeg", 0.5);
  //console.log(dataURL);
  $(".dataurl").val(dataURL);
  $("#btn-download").attr("href", dataURL);
//  $(".submit-create-pdf").click();
  
  /*
  var link = document.getElementById('btn-download');
  link.download = "bottle-design.png";
  link.href = bottleCanvas.toDataURL("image/png").replace("image/png", "image/octet-stream");*/
  
  /* var canvas = $("#chartContainer .canvasjs-chart-canvas").get(0);
    var dataURL = canvas.toDataURL('image/png');
    console.log(dataURL);*/
}
	
});
});
</script>


