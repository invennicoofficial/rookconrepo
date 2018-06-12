<?php $current_cat = $_GET['category'] ?>
<ul>
    <li class="standard-sidebar-searchbox">
        <form action="" method="POST">
        	<input name="search_vendor" type="text" value="<?= $_POST['search_vendor'] ?>" class="form-control search_vendor" placeholder="Search Labour">
        	<input type="submit" value="search_vendor_submit" name="search_vendor_submit" style="display: none;">
        </form>
    </li>
	<a href='?'><li <?= empty($current_cat) ? 'class="active"' : '' ?>>All Labour</li></a>
	<?php
	$each_tab = mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT `labour_type` FROM `labour` WHERE `deleted` = 0 ORDER BY `labour_type`"),MYSQLI_ASSOC);
    foreach ($each_tab as $cat_tab) {
		if($cat_tab['labour_type'] !== '') { ?>
			<a href='?category=<?= $cat_tab['labour_type'] ?>'><li <?= $current_cat == $cat_tab['labour_type'] ? 'class="active"' : '' ?>><?= $cat_tab['labour_type'] ?></li></a>
		<?php }
    } ?>
</ul>