<div class="main-screen standard-body override-main-screen form-horizontal">
	<div class="standard-body-title">
		<h3>Execute Macros</h3>
	</div>
	<div class="standard-body-content pad-top">
		<div class="col-sm-12">
			<?php if(empty($_GET['macro'])) {
				echo '<h3>Please Select a Macro</h3>';
			} else {
				$file_list = array_filter(scandir('macros'), function($filename) { return strpos($filename,'.php') !== FALSE; });
				$macro_name = '';
				foreach($file_list as $macro_name) {
					if(config_safe_str($macro_name) == $_GET['macro']) {
						break;
					}
				}
				include_once('macros/'.$macro_name);
			} ?>
		</div>
	</div>
</div>