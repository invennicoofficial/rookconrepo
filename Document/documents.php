<?php
/*
Documents
*/
include ('../include.php');
checkAuthorised('documents');

if((!empty($_GET['documentid'])) && ($_GET['type'] == 'delete')) {

	$documentid = $_GET['documentid'];
	$doc = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM documents WHERE documentid = '$documentid'"));

	$type = $doc['type'];
	$tile_name = $doc['tile_name'];
	$sub_tile_name = $doc['sub_tile_name'];
	$document = $doc['document'];

	unlink('download/'.$document);

    $result_delete_doc = mysqli_query($dbc, "DELETE FROM `documents` WHERE `documentid` = '$documentid'");

	header('Location: documents.php?type=view_document&tile_name='.$tile_name.'&sub_tile_name='.$sub_tile_name);
}

if((!empty($_GET['tile_name'])) && (!empty($_GET['sub_tile_name'])) && ($_GET['type'] == 'delete')) {

	$sub_tile_name = $_GET['sub_tile_name'];
    $tile_name = $_GET['tile_name'];

	$doc = mysqli_query($dbc, "SELECT * FROM documents WHERE tile_name = '$tile_name' AND sub_tile_name = '$sub_tile_name'");

	while($row_doc = mysqli_fetch_array($doc)) {
		unlink('download/'.$row_doc['document']);
		$tile_name = $row_doc['tile_name'];
	}

    $result_delete_doc = mysqli_query($dbc, "DELETE FROM `documents` WHERE `sub_tile_name` = '$sub_tile_name' AND `tile_name` = '$tile_name'");

	header('Location: documents.php?type=view_sub_tile&tile_name='.$tile_name);
}
?>
</head>
<body>

<?php include_once ('../navigation.php');

?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
        <?php

        if((empty($_GET['tile_name'])) && (empty($_GET['type']))) {

			?><h1>Documents Dashboard</h1>
			
			<div class="notice double-gap-bottom popover-examples">
				<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
				<div class="col-sm-11"><span class="notice-name">NOTE:</span>
				The documents tile provides the ability for each company to securely store and sort documents as needed. Each company can create as many tile names as they see fit, and then add headings and documents to each of those tiles. There’s a limit of 20 documents that can be uploaded at one time, but no limit as to how much room is available for organizing your documents.</div>
				<div class="clearfix"></div>
			</div>

			<div class="popover-examples list-inline pad-5"><a data-toggle="tooltip" data-placement="top" title='To edit tile titles, go into the specific tile you want to change and click on "Edit Tile Titles", change the title, then click "Submit".'><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="25"></a></div><?php
			
			echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="add_document.php" >Create Tile</a></div><br><br><br><br><br><br><br>';

			$get_client = mysqli_query($dbc,"SELECT * FROM documents GROUP BY tile_name");

            while($row_client = mysqli_fetch_array($get_client)) {
                echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="documents.php?type=view_sub_tile&tile_name='.$row_client['tile_name'].'" >'.$row_client['tile_name'].'</a></div>';
            }
        }

		if((!empty($_GET['tile_name'])) && ($_GET['type'] == 'view_sub_tile')) {
			$home = '<a href="'.WEBSITE_URL.'/home.php">Home</a>';
			$doc_root = '<a href="documents.php">Document Root</a>';
			$tile_name = $_GET['tile_name'];
			echo $home.' > '.$doc_root;
			echo '<br>';
			
			echo '<div class="double-gap-top double-gap-bottom"><a href="documents.php" class="btn config-btn">Back to Dashboard</a></div>';
			
			?><div class="popover-examples list-inline pad-5"><a data-toggle="tooltip" data-placement="top" title='To edit tile titles, go into the specific tile you want to change and click on "Edit Tile Titles", change the title, then click "Submit".'><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="25"></a></div><?php

			$tile_name = $_GET['tile_name'];
			echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="add_document.php?tile_name='.$tile_name.'" >Create Tile</a></div>';
			$result_doc_detail = mysqli_query($dbc, "SELECT * FROM documents WHERE tile_name = '$tile_name' AND sub_tile_name !='' GROUP BY sub_tile_name ORDER BY sub_tile_name");

            while($row_client = mysqli_fetch_array($result_doc_detail)) {
                echo '<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="documents.php?type=view_document&tile_name='.$tile_name.'&sub_tile_name='.$row_client['sub_tile_name'].'" >'.$row_client['sub_tile_name'].'</a></div>';
            }
			
			?>
			
			<div class="clearfix double-gap-bottom"></div>
			<div><a href="documents.php" class="btn brand-btn btn-lg">Back</a></div><?php
		}

		if((!empty($_GET['sub_tile_name'])) && ($_GET['type'] == 'view_document')) {

			$tile_name = $_GET['tile_name'];
			$sub_tile_name = $_GET['sub_tile_name'];

			$tile_name = $_GET['tile_name'];
			$sub_tile_name = $_GET['sub_tile_name'];

			$home = '<a href="'.WEBSITE_URL.'/home.php">Home</a>';
			$doc_root = '<a href="documents.php">Document Root</a>';
			$tile_name_bread = '<a href="documents.php?type=view_sub_tile&tile_name='.$tile_name.'">'.$tile_name.'</a>';

			echo $home.' > '.$doc_root.' > '.$tile_name_bread.' > '.$sub_tile_name;
			
			?><div class="double-gap-top double-gap-bottom"><a href="documents.php" class="config-btn btn">Back to Dashboard</a></div><?php

			$result_doc_detail = mysqli_query($dbc, "SELECT * FROM documents WHERE deleted = 0 AND tile_name = '$tile_name' AND sub_tile_name = '$sub_tile_name' ORDER BY tile_heading");

			$count_get_doc = mysqli_num_rows($result_doc_detail);

			if($count_get_doc == 0) {
			} else {

				$tile_heading = '';
				while($doc_detail = mysqli_fetch_array($result_doc_detail)) {

				    if($doc_detail['tile_heading'] != $tile_heading)
					{
						echo '<h3>'.$doc_detail['tile_heading'].'</h3>';
						$tile_heading = $doc_detail['tile_heading'];
					}

					//$view_document = explode("/",$doc_detail['document']);

					echo '<ul><li><a href="./download/'.$doc_detail['document'].'" target="_blank">'.$doc_detail['document'].'</a> - ';
					echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&type='.$_GET['type'].'&tile_name='.$_GET['tile_name'].'&sub_tile_name='.$_GET['sub_tile_name'].'&documentid='.$doc_detail['documentid'].'\' onclick="return confirm(\'Are you sure?\')">Archive</a>';
                    echo '</li></ul>';
				}
			}

			echo '<div class="form-group">
				<div class="col-sm-4 clearfix">
					<!-- <a href="#" class="btn brand-btn mobile-block pull-right" onclick="history.go(-1);return false;">Back</a> -->
				</div>
				<div class="col-sm-8">
					<a href="documents.php?type=delete&tile_name='.$tile_name.'&sub_tile_name='.$sub_tile_name.'" class="btn brand-btn mobile-block pull-right">Remove Tile</a>
					<a href="add_document.php?tile_name='.$tile_name.'&sub_tile_name='.$sub_tile_name.'" class="btn brand-btn mobile-block pull-right">Upload Document(s)</a>
					<a href="edit_document_title.php?tile_name='.$tile_name.'&sub_tile_name='.$sub_tile_name.'" class="btn brand-btn mobile-block pull-right">Edit Tile Titles</a>
				</div>
			</div>';
			
			?>
			<div class="clearfix"></div>
			
			<div class="double-gap-top"><a href="#" class="btn brand-btn btn-lg" onclick="history.go(-1);return false;">Back</a></div><?php
		} ?>

		</div>
	</div>
</div>

<?php include ('../footer.php'); ?>