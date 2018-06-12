<?php include('../Medical Charts/config.php');

foreach($config['tabs'] as $first_url) {
	$_GET['subtab'] = (empty($_GET['subtab']) ? $first_url : $_GET['subtab']);
	break;
}
$return_url = urlencode('../Client Documentation/client_documentation.php?tab=charts&subtab='.$_GET['subtab']);

foreach($config['tabs'] as $title => $url) {
	echo "<a href='?tab=charts&subtab=".$url."'><button type='button' class='btn brand-btn mobile-block ".($_GET['subtab'] == $url ? 'active_tab' : '')."' >".$title."</button></a>";
}
echo '<br><br>';
include('../Medical Charts/'.$_GET['subtab'].'_list.php');