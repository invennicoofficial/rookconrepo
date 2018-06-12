<?php // Contacts View
error_reporting(0);
include_once('../include.php'); ?>
</head>
<body>
<?php 
$security_folder = FOLDER_NAME;
$folder_label = FOLDER_URL;
if($security_folder == 'clientinfo') {
	$security_folder = 'client_info';
	$folder_label = 'Client Information';
} else if($security_folder == 'contactsrolodex') {
	$security_folder = 'contacts_rolodex';
	$folder_label = 'Contacts Rolodex';
} else if($security_folder == 'contacts') {
	$security_folder = 'contacts_inbox';
}
checkAuthorised($security_folder);
$edit_access = vuaed_visible_function($dbc, $security_folder);
$config_access = config_visible_function($dbc, $security_folder);
$tab = $_GET['tab'];
$_GET['category'] = 'Members';

$mandatory_config = [];
switch($tab) {
include('../Contacts/edit_contact.php');