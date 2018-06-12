<?php
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(estimateid) AS total_id FROM estimate WHERE businessid='$businessid'"));

    if($get_config['total_id'] > 0) {
        echo '<a target="_blank" href="'.WEBSITE_URL.'/Estimate/estimate.php?businessid='.$businessid.'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" id="'.$businessid.'">Click to View Estimate</a>';
    } else {
		echo '<a target="_blank" href="'.WEBSITE_URL.'/Estimate/add_estimate.php?from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'">Click to Add Estimate</a>';
    }
?>