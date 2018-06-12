<?php
/*
 * Get Style
 * This is in the head tag of index.php
 * It allows admins to set a software wide style for the login page.
 */
 

/* Non working old code - commented: 2017-03-20 by Jay
$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='login_style'"));

if($get_config['configid'] > 0) {
    $get_style = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM general_configuration WHERE name='login_style'"));
    $software_config = $get_style['value'];
} else {
    $software_config = '';
}
 */

include ('database_connection.php');

// Get first super user's style
$software_config    = '';
$style_sheet        = '';
$get_config         = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `software_styler_choice` FROM `contacts` WHERE `role`='super' LIMIT 1" ) );
$software_config    = $get_config['software_styler_choice'];

switch ($software_config) {
    case 'swr':             $style_sheet = '<link rel="stylesheet" href="css/style_white_red.css" type="text/css" />';      break;
    case 'bwr':             $style_sheet = '<link rel="stylesheet" href="css/style_precision.css" type="text/css" />';      break;
    case 'blw':             $style_sheet = '<link rel="stylesheet" href="css/style_original.css" type="text/css" />';       break;
    case 'bgw':             $style_sheet = '<link rel="stylesheet" href="css/style_clinic.css" type="text/css" />';         break;
    case 'silver':          $style_sheet = '<link rel="stylesheet" href="css/style_silver.css" type="text/css" />';         break;
    case 'blueorange':      $style_sheet = '<link rel="stylesheet" href="css/style_blue_orange.css" type="text/css" />';    break;
    case 'blackpurple':     $style_sheet = '<link rel="stylesheet" href="css/style_blackpurple.css" type="text/css" />';    break;
    case 'washt':           $style_sheet = '<link rel="stylesheet" href="css/style_nightshadow.css" type="text/css" />';    break;    
    case 'btb':             $style_sheet = '<link rel="stylesheet" href="css/style_neon.css" type="text/css" />';           break;
    case 'blackneonred':    $style_sheet = '<link rel="stylesheet" href="css/style_black_neon_red.css" type="text/css" />'; break;
    case 'blackneon':       $style_sheet = '<link rel="stylesheet" href="css/style_black_neon.css" type="text/css" />';     break;
    case 'ffm':             $style_sheet = '<link rel="stylesheet" href="css/style_ffm.css" type="text/css" />';            break;
    case 'garden':          $style_sheet = '<link rel="stylesheet" href="css/style_garden.css" type="text/css" />';         break;
    case 'green':           $style_sheet = '<link rel="stylesheet" href="css/style_green.css" type="text/css" />';          break;
    case 'blackorange':     $style_sheet = '<link rel="stylesheet" href="css/style_black_orange.css" type="text/css" />';   break;
    case 'navy':            $style_sheet = '<link rel="stylesheet" href="css/style_navy.css" type="text/css" />';           break;
    case 'purp':            $style_sheet = '<link rel="stylesheet" href="css/style_purple.css" type="text/css" />';         break;
    case 'turq':            $style_sheet = '<link rel="stylesheet" href="css/style_turquoise.css" type="text/css" />';      break;
    case 'leo':             $style_sheet = '<link rel="stylesheet" href="css/style_leopard.css" type="text/css" />';        break;
    case 'polka':           $style_sheet = '<link rel="stylesheet" href="css/style_polka.css" type="text/css" />';          break;
    case 'happy':           $style_sheet = '<link rel="stylesheet" href="css/style_happy.css" type="text/css" />';          break;
    case 'chrome':          $style_sheet = '<link rel="stylesheet" href="css/style_chrome.css" type="text/css" />';         break;
    case 'cosmos':          $style_sheet = '<link rel="stylesheet" href="css/style_space.css" type="text/css" />';          break;
    case 'kayla':           $style_sheet = '<link rel="stylesheet" href="css/style_kayla.css" type="text/css" />';          break;
    case 'flowers':         $style_sheet = '<link rel="stylesheet" href="css/style_flowers.css" type="text/css" />';        break;    
    case 'blackgold':       $style_sheet = '<link rel="stylesheet" href="css/style_black_gold.css" type="text/css" />';     break;
    case 'realtordark':     $style_sheet = '<link rel="stylesheet" href="css/style_realtor_dark.css" type="text/css" />';   break;
    case 'realtorlight':    $style_sheet = '<link rel="stylesheet" href="css/style_realtor_light.css" type="text/css" />';  break;
    case 'clouds':          $style_sheet = '<link rel="stylesheet" href="css/style_clouds.css" type="text/css" />';         break;
    case 'orangeblue':      $style_sheet = '<link rel="stylesheet" href="css/style_orangeblue.css" type="text/css" />';     break;
    case 'dots':            $style_sheet = '<link rel="stylesheet" href="css/style_dots.css" type="text/css" />';           break;
    case 'pinkdots':        $style_sheet = '<link rel="stylesheet" href="css/style_pinkdots.css" type="text/css" />';       break;
    case 'intuatrack':      $style_sheet = '<link rel="stylesheet" href="css/style_intuatrack.css" type="text/css" />';     break;
    default:                $style_sheet = '<link rel="stylesheet" href="css/style.css" type="text/css" />';                break;
}

/* Fallback */
if ( empty($style_sheet) ) {
    $style_sheet = '<link rel="stylesheet" href="css/style.css" type="text/css" />';
}

echo $style_sheet;
?>