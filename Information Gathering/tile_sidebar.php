<!-- Tile Sidebar -->
<?php
/* Pagination Counting */
$rowsPerPage = 25;
$pageNum = 1;

if(isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}

$offset = ($pageNum - 1) * $rowsPerPage;
$tabs = mysqli_fetch_all(mysqli_query($dbc, "SELECT distinct(category) FROM infogathering WHERE deleted=0 LIMIT $offset, $rowsPerPage"),MYSQLI_ASSOC);
if(empty($_GET['category'])) {
	$_GET['category'] = $tabs[0]['category'];
}
?>
<ul class="sidebar">
<?php foreach($tabs as $row) { ?>
<a href="infogathering.php?category=<?= $row['category'] ?>"><li <?= ($_GET['category'] == $row['category'] ? 'class="active"' : '') ?>><?= $row['category'] ?></li></a>
<?php } ?>
</ul>