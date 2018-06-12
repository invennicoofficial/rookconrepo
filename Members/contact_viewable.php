<?php // Contacts View
error_reporting(0);
include_once('../include.php'); ?>
<script>
$(document).ready(function() {
	$('.main-screen .scale-to-fill,.tile-content,.main-screen-white,body').first().css('width','100%').removeClass('double-pad-top').height($('.main-screen-white,body').first().get(0).scrollHeight);
});
</script>
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
	case 'profile': $mandatory_config = ['Business','First Name','Last Name','Preferred Name','Home Phone','Cell Phone','Email Address','Date of Birth','Display Age Value','School','FSCD Number','Preferred Pronoun','Health Care Number']; break;
	case 'guardians': $mandatory_config = ['Guardians Self','Guardians First Name','Guardians Last Name','Guardians Relationship','Guardians Work Phone','Guardians Home Phone','Guardians Cell Phone','Guardians Email Address','Guardians Siblings']; break;
	case 'emergency': $mandatory_config = ['Emergency Contact First Name','Emergency Contact Last Name','Emergency Contact Contact Number','Emergency Contact Relationship']; break;
	case 'medical': $mandatory_config = ['Medical Details Diagnosis','Medical Details Allergies','Medical Details Seizure','Medication Details']; break;
	case 'methodologies': $mandatory_config = ['Client Key Methodologies Social Story']; break;
	case 'log_notes': $mandatory_config = ['Client Top Daily Log Notes']; break;
}
include('../Contacts/edit_contact.php');