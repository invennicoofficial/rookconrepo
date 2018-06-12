<?php
/*
Payment/Invoice Listing SEA
*/

// IMPORTANT NOTE FOR CROSS SOFTWARE FUNCTIONALITY:

// **** IMPORTANT NOTE: $number_of_connections variable is set only in the database_connection.php file. You must put this variable in manually for this to work. Please see (sea.freshfocussoftware.com) SEA's database_connection.php files in order to see how these variables are set up. If you are trying to copy this cross-software functionality, it is advised that you use the exact same format/variable names that SEA's database_connection.php file contains. *****

// DONE IMPORTANT NOTE FOR CROSS SOFTWARE FUNCTIONALITY //
include ('../include.php');
include_once('../tcpdf/tcpdf.php');

?><style>.selectbutton {
	cursor: pointer;
	text-decoration: underline;
}
@media (min-width: 801px) {
	.sel2 {
		display:none;
	}
}
.approve-box {
    display: none;
    position: fixed;
    width: 500px;
	height:250px;
	top:50%;
	margin-top:-125px;
    left: 50%;
    background: lightgrey;
    color: black;
    border: 10px outset grey;
    border-radius: 15px;
    margin-left: -250px;
    text-align: center;
	z-index:99;
    padding: 20px;
}
@media (max-width:530px) {
.approve-box {
	width:100%;
	z-index:99;
	left:0px;
	margin-left:0px;
	overflow:auto;
}
}
.open-approval { cursor:pointer; }
.open-approval:hover { cursor:pointer; text-decoration:underline; }
	</style>
	<?php
$get_invoice =	mysqli_query($dbc,"SELECT posid FROM purchase_orders WHERE `invoice_date` + INTERVAL 30 DAY < NOW() AND status!='Completed'");
$num_rows = mysqli_num_rows($get_invoice);
if($num_rows > 0) {
    while($row = mysqli_fetch_array( $get_invoice )) {
        $posid = $row['posid'];
		//$query_update_project = "UPDATE `purchase_orders` SET status = 'Posted Past Due' WHERE `posid` = '$posid'";
	//	$result_update_project = mysqli_query($dbc, $query_update_project);
    }
}

if((!empty($_GET['type'])) && ($_GET['type'] == 'send_email')) {
    $type = $_GET['type'];
    $posid = $_GET['id'];


}
?>
<script type="text/javascript">
function disapprove_button(sel) {
	if (confirm('Are you sure you want to disapprove this article?')) {
		var status = sel.id;
		var arr = status.split('_');
		var id = arr[0];
		var dbc = arr[1];
		var message = '';
		$.ajax({    //create an ajax request to load_page.php
					type: "GET",
					url: "news_ajax_all.php?fill=cross_software_approval&dbc="+dbc+"&disapprove=true&name="+message+'&status='+id,
					dataType: "html",   //expect html to be returned
					success: function(response){
						//alert("You have successfully disapproved this article.");
						location.reload();
				}
		});
	}
}

function approvebutton(sel) {
	if (confirm('Are you sure you want to approve this article?')) {
		var status = sel.id;
		var arr = status.split('_');
		var id = arr[0];
		var dbc = arr[1];
		$.ajax({    //create an ajax request to load_page.php
					type: "GET",
					url: "news_ajax_all.php?fill=cross_software_approval&dbc="+dbc+"&status="+id,
					dataType: "html",   //expect html to be returned
					success: function(response){
						//alert("You have successfully approved this article.");
						location.reload();
				}
		});
	}
}
</script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('newsboard');


?>

<div class="container triple-pad-bottom">
	<div class="row hide_on_iframe">
		<h1 class="double-pad-bottom"><span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="These are News Articles created by other software."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="30"></a></span>News Posts From Other Software
        </h1>
		<?php
		$numodays = '';

		?>
		<!--<div class='mobile-100-container' >-->
		<div class="tab-container offset-left-15">
				<div class="pull-left tab"><span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="This will take you back to the News Board section."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a href='newsboard.php' ><button type="button" class="btn brand-btn mobile-block mobile-100" ><< Back</button></a></div>
		</div>

		<div class="clearfix"></div>

        <form name="invoice_table" method="post" action="pending.php" class="form-inline offset-top-20" role="form">
			<input type='hidden' class='getemailsapprove' value='' name='getemailsapprove'>
            <div class="single-pad-bottom">

				<div class="clearfix"></div>

				<?php
				$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT purchase_order_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['purchase_order_dashboard'].',';
				if (strpos($value_config, ','."Send to Anyone".',') !== FALSE) { ?>
				<!--<div class="clearfix" style='margin:10px;'></div>-->

				<?php } ?>

                <?php
                    //if (strpos(CUSTOMER_PRIVILEGES,'AE') !== false) {
                    //	echo '<a href="add_inventory.php" class="btn brand-btn pull-right">Add Product</a>';
                    //}
                ?>
                </div>
            <?php
            // Display Pager

            $rowsPerPagee = ITEMS_PER_PAGE;
            $pageNumm  = 1;

            if(isset($_GET['pagee'])) {
                $pageNumm = $_GET['pagee'];
            }

            $offsett = ($pageNumm - 1) * $rowsPerPagee;

            if (isset($_POST['display_all_invoice'])) {
                $invoice_name = '';
            }

            if (isset($_POST['search_invoice_submit'])) {
                $query_check_credentialss = "SELECT * FROM newsboard WHERE deleted = 0 AND (cross_software != '' AND cross_software IS NOT NULL) ORDER BY cross_software_approval, newsboardid";
            } else {
                $query_check_credentialss = "SELECT * FROM newsboard WHERE deleted = 0 AND (cross_software != '' AND cross_software IS NOT NULL) ORDER BY cross_software_approval, newsboardid";
            }

            // how many rows we have in database
            $queryy = "SELECT COUNT(posid) AS numrows FROM purchase_orders";

            if($invoice_name == '') {
               // echo '<h1 class="single-pad-bottom">'.display_pagination($dbc, $queryy, $pageNumm, $rowsPerPagee).'</h1>';
            }
			$num_of_rows = 0;
			if(isset($number_of_connections) && $number_of_connections > 0) {
				foreach (range(1, $number_of_connections) as $i) {
					$dbc_cross = ${'dbc_cross_'.$i};
					$resultt = mysqli_query($dbc_cross, $query_check_credentialss);
					$num_rowss = mysqli_num_rows($resultt);
					if($num_rowss > 0) {
						$num_of_rows = $num_of_rows+$num_rowss;
					}
				}
			} else {
				echo "You currently don't have any connections set up to any other software, please talk to your software administrator if you would like to set this functionality up.";
				$number_of_connections = 0;
			}
			echo 'Currently displaying '.$num_of_rows.' articles.';
            if($num_of_rows > 0) {
                echo "<div id='no-more-tables'><table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>";
						echo '<th>Author</th>';
                        echo '<th>News Board Type</th>';
                        echo '<th>Title</th>';
                        echo '<th>Header Image</th>';
                        echo '<th>Content</th>';
                        echo '<th>Approve/Disapprove</th>';

                echo "</tr>";
            } else{
                echo "<h2>No Record Found.</h2>";
            }
			if(isset($number_of_connections) && $number_of_connections > 0 && $num_of_rows > 0) {
				foreach (range(1, $number_of_connections) as $i) {
					$dbc_cross = ${'dbc_cross_'.$i};
					$software_url_get = ${'software_url_'.$i};
					$resultt = mysqli_query($dbc_cross, $query_check_credentialss);
					$numrowz = mysqli_num_rows($resultt);
					if($numrowz > 0) {
						while($roww = mysqli_fetch_array( $resultt ))
						{
							$style2 = '';
							$style = '';
							$contactid = $roww['contactid'];
							echo "<tr style='".$style.$style2."'>";
								echo '<td data-title="Author">';
									echo $roww['cross_software'];
								echo '</td>';
								echo '<td data-title="Type">';
									echo $roww['newsboard_type'];
								echo '</td>';
								echo '<td data-title="Title">'.$roww['title'].'</td>';
								echo '<td data-title="Header Image">';
								$mysqli_nummie_rows = mysqli_num_rows(mysqli_query($dbc_cross, "SELECT document_link FROM newsboard_uploads WHERE newsboardid = '".$roww['newsboardid']."'"));
								if($mysqli_nummie_rows > 0) {
									$get_header_img = mysqli_fetch_assoc(mysqli_query($dbc_cross, "SELECT document_link FROM newsboard_uploads WHERE newsboardid = '".$roww['newsboardid']."'"));
									$doc_link = $get_header_img['document_link'];
									if ($doc_link !== "" && $doc_link !== NULL) {
										echo '<a target="_blank" href="'.$software_url_get.'/News Board/download/'.$doc_link.'">'.$doc_link.'</a>';
									} else {
										echo 'No image given';
									}
								} else {
									echo 'No image given';
								}
								echo '</td>';
								echo '<td data-title="Content">'.html_entity_decode($roww['description']).'</td>';
								echo '<td data-title="Approval">';
								if ($roww['cross_software_approval'] !== "" && $roww['cross_software_approval'] !== NULL && $roww['cross_software_approval'] !== 'disapproved') {
									$approve = '<span style="color:red; font-weight:bold;">Approved</span>';
									$disapprove = 'Disapprove';
								} else if($roww['cross_software_approval'] == 'disapproved') {
									$approve = 'Approve';
									$disapprove = '<span style="color:red; font-weight:bold;">Disapproved</span>';
								} else {
									$approve = 'Approve';
									$disapprove = 'Disapprove';
								}
									echo '<span class="open-approval" onclick="approvebutton(this)" id="'.$roww['newsboardid'].'_'.$i.'"><img class="wiggle-me" src="../img/icons/like.png" width="25px"> '.$approve.'</span><br><br>';
									echo '<span class="open-approval" onclick="disapprove_button(this)" id="'.$roww['newsboardid'].'_'.$i.'"><img class="wiggle-me" src="../img/icons/dislike.png" width="25px"> '.$disapprove.'</span>';
								echo '</td>';
							echo "</tr>";
						}
					}
				}
			}

            echo '</table></div></div>';

            if($invoice_name == '') {
                //echo display_pagination($dbc, $queryy, $pageNumm, $rowsPerPagee);
            }

            ?>
        </form>

	</div>
</div>
<?php include ('../footer.php'); ?>
