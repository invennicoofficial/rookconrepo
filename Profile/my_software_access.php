<?php
$contactid = $_SESSION['contactid'];
$get_contact =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	contacts WHERE	contactid='$contactid'"));

$user_name = $get_contact['user_name'];
$password = decryptIt($get_contact['password']);
$role = $get_contact['role'];

$subtab = 'software_access';
if (!empty($_POST['subtab'])) {
    $subtab = $_POST['subtab'];
}
?>
<input type="hidden" name="edit_software_access" value="1">
<h4>Software Access</h4>
<?php
$value_config = ',Role,User Name,Password,';
$edit_config = ',User Name,Password,'; ?>
<?php
include ('../Contacts/add_contacts_basic_info.php');
?>