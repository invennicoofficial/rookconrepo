<!-- Tile Sidebar -->
<?php
$search_types = mysqli_fetch_all(mysqli_query($dbc, "SELECT distinct(internal_documents_type) FROM internal_documents WHERE deleted=0 order by internal_documents_type"),MYSQLI_ASSOC);
$search_categories = mysqli_fetch_all(mysqli_query($dbc, "SELECT distinct(category) FROM internal_documents WHERE deleted=0 order by category"),MYSQLI_ASSOC);
?>
<script type="text/javascript">
function submitButton(search_type, search_category) {
    $('[name="search_type"]').val(search_type);
    $('[name="search_category').val(search_category);
    $('[name="search_user_submit"]').click();
}
</script>
<input type="hidden" name="search_type" value="<?= $_POST['search_type'] ?>">
<input type="hidden" name="search_category" value="<?= $_POST['search_category'] ?>">
<ul class="sidebar">
<h4 style="padding-left: 0.5em;">Type</h4>
    <?php foreach($search_types as $row) { ?>
    <a href="" onclick="submitButton('<?= $row['internal_documents_type'] ?>', '<?= $_POST['search_category'] ?>'); return false;"><li <?= ($search_type == $row['internal_documents_type'] ? 'class="active"' : '') ?>><?= $row['internal_documents_type'] ?></li></a>
    <?php } ?>
<h4 style="padding-left: 0.5em;">Category</h4>
    <?php foreach($search_categories as $row) { ?>
    <a href="" onclick="submitButton('<?= $_POST['search_type'] ?>', '<?= $row['category'] ?>'); return false;"><li <?= ($search_category == $row['category'] ? 'class="active"' : '') ?>><?= $row['category'] ?></li></a>
    <?php } ?>
</ul>