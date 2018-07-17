<?php
/*
Newsbaord
FFM
*/
if ( isset ( $_GET[ 'id'] ) ) {
	$nid = intval ( trim ( $_GET[ 'id' ] ) );
    $is_sw = isset($_GET['sw']) ? true : false;
} else {
	header( 'Location: newsboard.php' );
	exit;
}
include_once ('include.php');
if ($is_sw) {
    include ('database_connection_htg.php');
}
?>
</head>
<body>

<?php include ('navigation.php'); ?>

<div class="container"><?php
	$query = '
		SELECT DISTINCT
			news.newsboardid AS nID, news.title AS title, news.description AS description,
			img.document_link AS image
		FROM
			newsboard AS news
		JOIN
			newsboard_uploads AS img ON (news.newsboardid = img.newsboardid)
		WHERE
			deleted = 0 AND news.newsboardid = ' . $nid . '
		GROUP BY
			news.newsboardid DESC';
	
	$results = $is_sw ? mysqli_query($dbc_htg, $query) : mysqli_query($dbc, $query);
	
	if ( $results->num_rows > 0 ) { ?>
		<div class="rows grid"><?php
			while ( $row = mysqli_fetch_assoc ( $results ) ) { ?>
				<div class="col-sm-12 col-md-12">
					<div class="nb-title">
						<h1><?= str_replace(['Rook Connect', 'Precision Work Flow'], ['ROOK Connect', 'Precision Workflow'], $row[ 'title' ]); ?></h1>
						<div class="gap-left gap-top double-gap-bottom"><a href="newsboard.php" class="btn config-btn">Back to Dashboard</a></div>
					</div>
					<div class="nb-img nb-img-single"><img src="https://ffm.rookconnect.com/News Board/download/<?= $row[ 'image' ]; ?>" alt="<?= $row[ 'title' ]; ?>" /></div>
					<div class="nb-desc"><?= html_entity_decode ( str_replace(['Rook Connect', 'Precision Work Flow'], ['ROOK Connect', 'Precision Workflow'], $row[ 'description' ]) ); ?></div>
					<div class="nb-more triple-gap-bottom"><a href="newsboard.php" class="btn brand-btn btn-lg mobile-block">Back</a></div>
				</div><?php
			}
			
			$software_url = WEBSITE_URL; ?>
			
			<div class="clearfix"></div>
			
			<!-- DISQUS -->
			<div class="notice double-gap-bottom popover-examples">
			<div class="col-sm-1 notice-icon"><img src="img/info.png" class="wiggle-me" width="25px"></div>
			<div class="col-sm-16"><span class="notice-name">NOTE:</span>
				Please login/register on Disqus first to leave a comment. You can register/login with your Disqus, Facebook, Twitter or Google accounts.</div>
				<div class="clearfix"></div>
			</div>
			
			<div id="disqus_thread"></div>
			<script>
				/**
				 *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
				 *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
				 */
				/*
				var disqus_config = function () {
					this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
					this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
				};
				*/
				(function() {  // DON'T EDIT BELOW THIS LINE
					var d = document, s = d.createElement('script');
					
					s.src = '//freshfocusmedia.disqus.com/embed.js';
					
					s.setAttribute('data-timestamp', +new Date());
					(d.head || d.body).appendChild(s);
				})();
			</script>
			<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
			<!-- DISQUS -->
			
		</div><!-- .row .grid --><?php
	} ?>
</div>
<?php include ('footer.php'); ?>