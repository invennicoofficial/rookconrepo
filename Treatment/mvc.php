<?php
$subtab = (empty($_GET['subtab']) ? 'forms' : $_GET['subtab']);
?>
<div class="tab-container mobile-100-container">
	<a href="?tab=mvc&subtab=forms"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($subtab == 'forms' ? 'active_tab' : ''); ?>">Patient Forms</button></a>
	<a href="?tab=mvc&subtab=assess"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($subtab == 'assess' ? 'active_tab' : ''); ?>">Assessment</button></a>
	<a href="?tab=mvc&subtab=treatment"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($subtab == 'treatment' ? 'active_tab' : ''); ?>">Treatment</button></a>
	<a href="?tab=mvc&subtab=discharge"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($subtab == 'discharge' ? 'active_tab' : ''); ?>">Discharge</button></a>
</div>

<?php include('output_forms.php'); ?>