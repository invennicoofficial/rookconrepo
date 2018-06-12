<?php
if (!file_exists('download')) {
	mkdir('download', 0777, true);
}

$application = htmlspecialchars($_FILES["application"]["name"], ENT_QUOTES);
$contactimage = htmlspecialchars($_FILES["contactimage"]["name"], ENT_QUOTES);
$upload_license_plate = htmlspecialchars($_FILES["upload_license_plate"]["name"], ENT_QUOTES);
$upload_property_information = htmlspecialchars($_FILES["upload_property_information"]["name"], ENT_QUOTES);
$upload_inspection = htmlspecialchars($_FILES["upload_inspection"]["name"], ENT_QUOTES);
$upload_letter_of_intent = htmlspecialchars($_FILES["upload_letter_of_intent"]["name"], ENT_QUOTES);
$upload_vendor_documents = htmlspecialchars($_FILES["upload_vendor_documents"]["name"], ENT_QUOTES);
$upload_marketing_material = htmlspecialchars($_FILES["upload_marketing_material"]["name"], ENT_QUOTES);
$upload_purchase_contract = htmlspecialchars($_FILES["upload_purchase_contract"]["name"], ENT_QUOTES);
$upload_support_contract = htmlspecialchars($_FILES["upload_support_contract"]["name"], ENT_QUOTES);
$upload_support_terms = htmlspecialchars($_FILES["upload_support_terms"]["name"], ENT_QUOTES);
$upload_rental_contract = htmlspecialchars($_FILES["upload_rental_contract"]["name"], ENT_QUOTES);
$upload_management_contract = htmlspecialchars($_FILES["upload_management_contract"]["name"], ENT_QUOTES);
$upload_articles_of_incorporation = htmlspecialchars($_FILES["upload_articles_of_incorporation"]["name"], ENT_QUOTES);
$upload_commercial_insurance = htmlspecialchars($_FILES["upload_commercial_insurance"]["name"], ENT_QUOTES);
$upload_residential_insurance = htmlspecialchars($_FILES["upload_residential_insurance"]["name"], ENT_QUOTES);
$upload_wcb = htmlspecialchars($_FILES["upload_wcb"]["name"], ENT_QUOTES);
$upload_blank_cheque = htmlspecialchars($_FILES["upload_blank_cheque"]["name"], ENT_QUOTES);
$upload_drivers_license = htmlspecialchars($_FILES["upload_drivers_license"]["name"], ENT_QUOTES);
$client_support_documents = htmlspecialchars($_FILES["client_support_documents"]["name"], ENT_QUOTES);
$transportation_support_documents = htmlspecialchars($_FILES["transportation_support_documents"]["name"], ENT_QUOTES);
$insurance_support_documents = htmlspecialchars($_FILES["insurance_support_documents"]["name"], ENT_QUOTES);
$guardians_support_documents = htmlspecialchars($_FILES["guardians_support_documents"]["name"], ENT_QUOTES);
$trustee_support_documents = htmlspecialchars($_FILES["trustee_support_documents"]["name"], ENT_QUOTES);
$family_doctor_support_documents = htmlspecialchars($_FILES["family_doctor_support_documents"]["name"], ENT_QUOTES);
$dentist_support_documents = htmlspecialchars($_FILES["dentist_support_documents"]["name"], ENT_QUOTES);
$specialists_support_documents = htmlspecialchars($_FILES["specialists_support_documents"]["name"], ENT_QUOTES);
$diagnosis_support_documents = htmlspecialchars($_FILES["diagnosis_support_documents"]["name"], ENT_QUOTES);
$allergies_support_documents = htmlspecialchars($_FILES["allergies_support_documents"]["name"], ENT_QUOTES);
$equipment_support_documents = htmlspecialchars($_FILES["equipment_support_documents"]["name"], ENT_QUOTES);
$medical_details_first_aid_cpr_documents = htmlspecialchars($_FILES["medical_details_first_aid_cpr_documents"]["name"], ENT_QUOTES);
$medical_details_support_documents = htmlspecialchars($_FILES["medical_details_support_documents"]["name"], ENT_QUOTES);
$seizure_protocol_upload = htmlspecialchars($_FILES["seizure_protocol_upload"]["name"], ENT_QUOTES);
$slip_fall_protocol_upload = htmlspecialchars($_FILES["slip_fall_protocol_upload"]["name"], ENT_QUOTES);
$transfer_protocol_upload = htmlspecialchars($_FILES["transfer_protocol_upload"]["name"], ENT_QUOTES);
$toileting_protocol_upload = htmlspecialchars($_FILES["toileting_protocol_upload"]["name"], ENT_QUOTES);
$bathing_protocol_upload = htmlspecialchars($_FILES["bathing_protocol_upload"]["name"], ENT_QUOTES);
$gtube_protocol_upload = htmlspecialchars($_FILES["gtube_protocol_upload"]["name"], ENT_QUOTES);
$oxygen_protocol_upload = htmlspecialchars($_FILES["oxygen_protocol_upload"]["name"], ENT_QUOTES);
$funding_support_documents = htmlspecialchars($_FILES["funding_support_documents"]["name"], ENT_QUOTES);

$get_upload = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(contactuploadid) AS contactuploadid FROM contacts_upload WHERE contactid='$contactid'"));

if($get_upload['contactuploadid'] > 0) {

	if($application == '') {
		$application = $_POST['application_hidden'];
	}
	if($contactimage == '') {
		$contactimage = $_POST['contactimage_hidden'];
	}
	if($upload_license_plate == '') {
		$upload_license_plate = $_POST['upload_license_plate_hidden'];
	}
	if($upload_property_information == '') {
		$upload_property_information = $_POST['upload_property_information_hidden'];
	}
	if($upload_inspection == '') {
		$upload_inspection = $_POST['upload_inspection_hidden'];
	}
	if($upload_letter_of_intent == '') {
		$upload_letter_of_intent = $_POST['upload_letter_of_intent_hidden'];
	}
	if($upload_vendor_documents == '') {
		$upload_vendor_documents = $_POST['upload_vendor_documents_hidden'];
	}
	if($upload_marketing_material == '') {
		$upload_marketing_material = $_POST['upload_marketing_material_hidden'];
	}
	if($upload_purchase_contract == '') {
		$upload_purchase_contract = $_POST['upload_purchase_contract_hidden'];
	}
	if($upload_support_contract == '') {
		$upload_support_contract = $_POST['upload_support_contract_hidden'];
	}
	if($upload_support_terms == '') {
		$upload_support_terms = $_POST['upload_support_terms_hidden'];
	}
	if($upload_rental_contract == '') {
		$upload_rental_contract = $_POST['upload_rental_contract_hidden'];
	}
	if($upload_management_contract == '') {
		$upload_management_contract = $_POST['upload_management_contract_hidden'];
	}
	if($upload_articles_of_incorporation == '') {
		$upload_articles_of_incorporation = $_POST['upload_articles_of_incorporation_hidden'];
	}
	if($upload_commercial_insurance == '') {
		$upload_commercial_insurance = $_POST['upload_commercial_insurance_hidden'];
	}
	if($upload_residential_insurance == '') {
		$upload_residential_insurance = $_POST['upload_residential_insurance_hidden'];
	}
	if($upload_wcb == '') {
		$upload_wcb = $_POST['upload_wcb_hidden'];
	}
	if($upload_blank_cheque == '') {
		$upload_blank_cheque = $_POST['upload_blank_cheque_hidden'];
	}
	if($upload_drivers_license == '') {
		$upload_drivers_license = $_POST['upload_drivers_license_hidden'];
	}
	if($client_support_documents == '') {
		$client_support_documents = $_POST['client_support_documents_hidden'];
	}
	if($transportation_support_documents == '') {
		$transportation_support_documents = $_POST['transportation_support_documents_hidden'];
	}
	if($insurance_support_documents == '') {
		$insurance_support_documents = $_POST['insurance_support_documents_hidden'];
	}
	if($guardians_support_documents == '') {
		$guardians_support_documents = $_POST['guardians_support_documents_hidden'];
	}
	if($trustee_support_documents == '') {
		$trustee_support_documents = $_POST['trustee_support_documents_hidden'];
	}
	if($family_doctor_support_documents == '') {
		$family_doctor_support_documents = $_POST['family_doctor_support_documents_hidden'];
	}
	if($dentist_support_documents == '') {
		$dentist_support_documents = $_POST['dentist_support_documents_hidden'];
	}
	if($specialists_support_documents == '') {
		$specialists_support_documents = $_POST['specialists_support_documents_hidden'];
	}

	if($diagnosis_support_documents == '') {
		$diagnosis_support_documents = $_POST['diagnosis_support_documents_hidden'];
	}
	if($allergies_support_documents == '') {
		$allergies_support_documents = $_POST['allergies_support_documents_hidden'];
	}
	if($equipment_support_documents == '') {
		$equipment_support_documents = $_POST['equipment_support_documents_hidden'];
	}
	if($medical_details_first_aid_cpr_documents == '') {
		$medical_details_first_aid_cpr_documents = $_POST['medical_details_first_aid_cpr_documents_hidden'];
	}
	if($medical_details_support_documents == '') {
		$medical_details_support_documents = $_POST['medical_details_support_documents_hidden'];
	}

	if($seizure_protocol_upload == '') {
		$seizure_protocol_upload = $_POST['seizure_protocol_upload'];
	}
	if($slip_fall_protocol_upload == '') {
		$slip_fall_protocol_upload = $_POST['slip_fall_protocol_upload'];
	}
	if($transfer_protocol_upload == '') {
		$transfer_protocol_upload = $_POST['transfer_protocol_upload'];
	}
	if($toileting_protocol_upload == '') {
		$toileting_protocol_upload = $_POST['toileting_protocol_upload'];
	}
	if($bathing_protocol_upload == '') {
		$bathing_protocol_upload = $_POST['bathing_protocol_upload'];
	}
	if($gtube_protocol_upload == '') {
		$gtube_protocol_upload = $_POST['gtube_protocol_upload'];
	}
	if($oxygen_protocol_upload == '') {
		$oxygen_protocol_upload = $_POST['oxygen_protocol_upload'];
	}
	if($funding_support_documents == '') {
		$funding_support_documents = $_POST['funding_support_documents'];
	}
	//if($upload_drivers_license == '') {
	//	$upload_drivers_license = $_POST['upload_drivers_license'];
	//}
	if($upload_blank_cheque == '') {
		$upload_blank_cheque = $_POST['upload_blank_cheque'];
	}

	move_uploaded_file($_FILES["application"]["tmp_name"], "download/".$application) ;
	move_uploaded_file($_FILES["contactimage"]["tmp_name"], "download/".$contactimage) ;
	move_uploaded_file($_FILES["upload_license_plate"]["tmp_name"], "download/".$upload_license_plate) ;
	move_uploaded_file($_FILES["upload_property_information"]["tmp_name"], "download/".$upload_property_information) ;
	move_uploaded_file($_FILES["upload_inspection"]["tmp_name"], "download/".$upload_inspection) ;
	move_uploaded_file($_FILES["upload_letter_of_intent"]["tmp_name"], "download/".$upload_letter_of_intent) ;
	move_uploaded_file($_FILES["upload_vendor_documents"]["tmp_name"], "download/".$upload_vendor_documents) ;
	move_uploaded_file($_FILES["upload_marketing_material"]["tmp_name"], "download/".$upload_marketing_material) ;
	move_uploaded_file($_FILES["upload_purchase_contract"]["tmp_name"], "download/".$upload_purchase_contract) ;
	move_uploaded_file($_FILES["upload_support_contract"]["tmp_name"], "download/".$upload_support_contract) ;
	move_uploaded_file($_FILES["upload_support_terms"]["tmp_name"], "download/".$upload_support_terms) ;
	move_uploaded_file($_FILES["upload_rental_contract"]["tmp_name"], "download/".$upload_rental_contract) ;
	move_uploaded_file($_FILES["upload_management_contract"]["tmp_name"], "download/".$upload_management_contract) ;
	move_uploaded_file($_FILES["upload_articles_of_incorporation"]["tmp_name"], "download/".$upload_articles_of_incorporation) ;
	move_uploaded_file($_FILES["upload_commercial_insurance"]["tmp_name"], "download/".$upload_commercial_insurance) ;
	move_uploaded_file($_FILES["upload_residential_insurance"]["tmp_name"], "download/".$upload_residential_insurance) ;
	move_uploaded_file($_FILES["upload_wcb"]["tmp_name"], "download/".$upload_wcb) ;
	move_uploaded_file($_FILES["upload_blank_cheque"]["tmp_name"], "download/".$upload_blank_cheque) ;
	move_uploaded_file($_FILES["upload_drivers_license"]["tmp_name"], "download/".$upload_drivers_license) ;

	move_uploaded_file($_FILES["client_support_documents"]["tmp_name"], "download/". $client_support_documents);
	move_uploaded_file($_FILES["transportation_support_documents"]["tmp_name"], "download/". $transportation_support_documents);
	move_uploaded_file($_FILES["insurance_support_documents"]["tmp_name"], "download/". $insurance_support_documents);
	move_uploaded_file($_FILES["guardians_support_documents"]["tmp_name"], "download/". $guardians_support_documents);
	move_uploaded_file($_FILES["trustee_support_documents"]["tmp_name"], "download/". $trustee_support_documents);
	move_uploaded_file($_FILES["family_doctor_support_documents"]["tmp_name"], "download/". $family_doctor_support_documents);
	move_uploaded_file($_FILES["dentist_support_documents"]["tmp_name"], "download/". $dentist_support_documents);
	move_uploaded_file($_FILES["specialists_support_documents"]["tmp_name"], "download/". $specialists_support_documents);

	move_uploaded_file($_FILES["diagnosis_support_documents"]["tmp_name"], "download/". $diagnosis_support_documents);
	move_uploaded_file($_FILES["allergies_support_documents"]["tmp_name"], "download/". $allergies_support_documents);
	move_uploaded_file($_FILES["equipment_support_documents"]["tmp_name"], "download/". $equipment_support_documents);
	move_uploaded_file($_FILES["medical_details_first_aid_cpr_documents"]["tmp_name"], "download/". $medical_details_first_aid_cpr_documents);
	move_uploaded_file($_FILES["medical_details_support_documents"]["tmp_name"], "download/". $medical_details_support_documents);

	move_uploaded_file($_FILES["seizure_protocol_upload"]["tmp_name"], "download/". $seizure_protocol_upload);
	move_uploaded_file($_FILES["slip_fall_protocol_upload"]["tmp_name"], "download/". $slip_fall_protocol_upload);
	move_uploaded_file($_FILES["transfer_protocol_upload"]["tmp_name"], "download/". $transfer_protocol_upload);
	move_uploaded_file($_FILES["toileting_protocol_upload"]["tmp_name"], "download/". $toileting_protocol_upload);
	move_uploaded_file($_FILES["bathing_protocol_upload"]["tmp_name"], "download/". $bathing_protocol_upload);
	move_uploaded_file($_FILES["gtube_protocol_upload"]["tmp_name"], "download/". $gtube_protocol_upload);
	move_uploaded_file($_FILES["oxygen_protocol_upload"]["tmp_name"], "download/". $oxygen_protocol_upload);
	move_uploaded_file($_FILES["upload_blank_cheque"]["tmp_name"], "download/". $upload_blank_cheque);
	move_uploaded_file($_FILES["funding_support_documents"]["tmp_name"], "download/". $funding_support_documents);

	$query_update_doc = "UPDATE `contacts_upload` SET `application` = '$application', `contactimage` = '$contactimage', `upload_license_plate` = '$upload_license_plate', `upload_property_information` = '$upload_property_information', `upload_inspection` = '$upload_inspection', `upload_letter_of_intent` = '$upload_letter_of_intent', `upload_vendor_documents` = '$upload_vendor_documents', `upload_marketing_material` = '$upload_marketing_material', `upload_purchase_contract` = '$upload_purchase_contract', `upload_support_contract` = '$upload_support_contract', `upload_support_terms` = '$upload_support_terms', `upload_rental_contract` = '$upload_rental_contract', `upload_management_contract` = '$upload_management_contract', `upload_articles_of_incorporation` = '$upload_articles_of_incorporation', `upload_commercial_insurance` = '$upload_commercial_insurance', `upload_residential_insurance` = '$upload_residential_insurance', `upload_wcb` = '$upload_wcb', `upload_drivers_license` = '$upload_drivers_license', `upload_blank_cheque` = '$upload_blank_cheque', `client_support_documents` = '$client_support_documents', `transportation_support_documents` = '$transportation_support_documents', `insurance_support_documents` = '$insurance_support_documents', `guardians_support_documents` = '$guardians_support_documents', `trustee_support_documents` = '$trustee_support_documents', `family_doctor_support_documents` = '$family_doctor_support_documents', `dentist_support_documents` = '$dentist_support_documents', `specialists_support_documents` = '$specialists_support_documents', `diagnosis_support_documents` = '$diagnosis_support_documents', `allergies_support_documents` = '$allergies_support_documents', `equipment_support_documents` = '$equipment_support_documents', `medical_details_first_aid_cpr` = '$medical_details_first_aid_cpr_documents', `medical_details_support_documents` = '$medical_details_support_documents', `seizure_protocol_upload` = '$seizure_protocol_upload', `slip_fall_protocol_upload` = '$slip_fall_protocol_upload', `transfer_protocol_upload` = '$transfer_protocol_upload', `toileting_protocol_upload` = '$toileting_protocol_upload', `bathing_protocol_upload` = '$bathing_protocol_upload', `gtube_protocol_upload` = '$gtube_protocol_upload', `oxygen_protocol_upload` = '$oxygen_protocol_upload', `funding_support_documents` = '$funding_support_documents' WHERE `contactid` = '$contactid'";

	$result_update_doc	= mysqli_query($dbc, $query_update_doc);
} else {

	move_uploaded_file($_FILES["application"]["tmp_name"], "download/".$_FILES["application"]["name"]) ;
	move_uploaded_file($_FILES["contactimage"]["tmp_name"], "download/".$_FILES["contactimage"]["name"]) ;
	move_uploaded_file($_FILES["upload_license_plate"]["tmp_name"], "download/".$_FILES["upload_license_plate"]["name"]) ;
	move_uploaded_file($_FILES["upload_property_information"]["tmp_name"], "download/".$_FILES["upload_property_information"]["name"]) ;
	move_uploaded_file($_FILES["upload_inspection"]["tmp_name"], "download/".$_FILES["upload_inspection"]["name"]) ;
	move_uploaded_file($_FILES["upload_letter_of_intent"]["tmp_name"], "download/".$_FILES["upload_letter_of_intent"]["name"]) ;
	move_uploaded_file($_FILES["upload_vendor_documents"]["tmp_name"], "download/".$_FILES["upload_vendor_documents"]["name"]) ;
	move_uploaded_file($_FILES["upload_marketing_material"]["tmp_name"], "download/".$_FILES["upload_marketing_material"]["name"]) ;
	move_uploaded_file($_FILES["upload_purchase_contract"]["tmp_name"], "download/".$_FILES["upload_purchase_contract"]["name"]) ;
	move_uploaded_file($_FILES["upload_support_contract"]["tmp_name"], "download/".$_FILES["upload_support_contract"]["name"]) ;
	move_uploaded_file($_FILES["upload_support_terms"]["tmp_name"], "download/".$_FILES["upload_support_terms"]["name"]) ;
	move_uploaded_file($_FILES["upload_rental_contract"]["tmp_name"], "download/".$_FILES["upload_rental_contract"]["name"]) ;
	move_uploaded_file($_FILES["upload_management_contract"]["tmp_name"], "download/".$_FILES["upload_management_contract"]["name"]) ;
	move_uploaded_file($_FILES["upload_articles_of_incorporation"]["tmp_name"], "download/".$_FILES["upload_articles_of_incorporation"]["name"]) ;
	move_uploaded_file($_FILES["upload_commercial_insurance"]["tmp_name"], "download/".$_FILES["upload_commercial_insurance"]["name"]) ;
	move_uploaded_file($_FILES["upload_residential_insurance"]["tmp_name"], "download/".$_FILES["upload_residential_insurance"]["name"]) ;
	move_uploaded_file($_FILES["upload_wcb"]["tmp_name"], "download/".$_FILES["upload_wcb"]["name"]) ;
	move_uploaded_file($_FILES["upload_blank_cheque"]["tmp_name"], "download/".$upload_blank_cheque) ;
	move_uploaded_file($_FILES["upload_drivers_license"]["tmp_name"], "download/".$upload_drivers_license) ;

	move_uploaded_file($_FILES["client_support_documents"]["tmp_name"], "download/".$_FILES["client_support_documents"]["name"]) ;
	move_uploaded_file($_FILES["transportation_support_documents"]["tmp_name"], "download/".$_FILES["transportation_support_documents"]["name"]) ;
	move_uploaded_file($_FILES["insurance_support_documents"]["tmp_name"], "download/".$_FILES["insurance_support_documents"]["name"]) ;
	move_uploaded_file($_FILES["guardians_support_documents"]["tmp_name"], "download/".$_FILES["guardians_support_documents"]["name"]) ;
	move_uploaded_file($_FILES["trustee_support_documents"]["tmp_name"], "download/".$_FILES["trustee_support_documents"]["name"]) ;
	move_uploaded_file($_FILES["family_doctor_support_documents"]["tmp_name"], "download/".$_FILES["family_doctor_support_documents"]["name"]) ;
	move_uploaded_file($_FILES["dentist_support_documents"]["tmp_name"], "download/".$_FILES["dentist_support_documents"]["name"]) ;
	move_uploaded_file($_FILES["specialists_support_documents"]["tmp_name"], "download/".$_FILES["specialists_support_documents"]["name"]) ;


	move_uploaded_file($_FILES["diagnosis_support_documents"]["tmp_name"], "download/".$_FILES["diagnosis_support_documents"]["name"]);
	move_uploaded_file($_FILES["allergies_support_documents"]["tmp_name"], "download/".$_FILES["allergies_support_documents"]["name"]);
	move_uploaded_file($_FILES["equipment_support_documents"]["tmp_name"], "download/".$_FILES["equipment_support_documents"]["name"]);
	move_uploaded_file($_FILES["medical_details_first_aid_cpr_documents"]["tmp_name"], "download/".$_FILES["medical_details_first_aid_cpr_documents"]["name"]);
	move_uploaded_file($_FILES["medical_details_support_documents"]["tmp_name"], "download/".$_FILES["medical_details_support_documents"]["name"]);

	move_uploaded_file($_FILES["seizure_protocol_upload"]["tmp_name"], "download/".$_FILES["seizure_protocol_upload"]["name"]);
	move_uploaded_file($_FILES["slip_fall_protocol_upload"]["tmp_name"], "download/".$_FILES["slip_fall_protocol_upload"]["name"]);
	move_uploaded_file($_FILES["transfer_protocol_upload"]["tmp_name"], "download/".$_FILES["transfer_protocol_upload"]["name"]);
	move_uploaded_file($_FILES["toileting_protocol_upload"]["tmp_name"], "download/".$_FILES["toileting_protocol_upload"]["name"]);
	move_uploaded_file($_FILES["bathing_protocol_upload"]["tmp_name"], "download/".$_FILES["bathing_protocol_upload"]["name"]);
	move_uploaded_file($_FILES["gtube_protocol_upload"]["tmp_name"], "download/".$_FILES["gtube_protocol_upload"]["name"]);
	move_uploaded_file($_FILES["oxygen_protocol_upload"]["tmp_name"], "download/".$_FILES["oxygen_protocol_upload"]["name"]);
	move_uploaded_file($_FILES["funding_support_documents"]["tmp_name"], "download/".$_FILES["funding_support_documents"]["name"]);

	$query_insert_doc = "INSERT INTO `contacts_upload` (`contactid`, `application`, `contactimage`, `upload_license_plate`, `upload_property_information`, `upload_inspection`, `upload_letter_of_intent`, `upload_vendor_documents`, `upload_marketing_material`, `upload_purchase_contract`, `upload_support_contract`, `upload_support_terms`, `upload_rental_contract`, `upload_management_contract`, `upload_articles_of_incorporation`, `upload_commercial_insurance`, `upload_residential_insurance`, `upload_wcb`, `upload_drivers_license`, `upload_blank_cheque`, `client_support_documents`, `transportation_support_documents`, `insurance_support_documents`, `guardians_support_documents`, `trustee_support_documents`, `family_doctor_support_documents`, `dentist_support_documents`, `specialists_support_documents`, `diagnosis_support_documents`, `allergies_support_documents`, `equipment_support_documents`, `medical_details_first_aid_cpr`, `medical_details_support_documents`,
    `seizure_protocol_upload`, `slip_fall_protocol_upload`, `transfer_protocol_upload`, `toileting_protocol_upload`, `bathing_protocol_upload`, `gtube_protocol_upload`, `oxygen_protocol_upload`, `funding_support_documents`) VALUES ('$contactid', '$application', '$contactimage', '$upload_license_plate', '$upload_property_information', '$upload_inspection', '$upload_letter_of_intent', '$upload_vendor_documents', '$upload_marketing_material', '$upload_purchase_contract', '$upload_support_contract', '$upload_support_terms', '$upload_rental_contract', '$upload_management_contract', '$upload_articles_of_incorporation', '$upload_commercial_insurance', '$upload_residential_insurance', '$upload_wcb', '$upload_drivers_license', '$upload_blank_cheque', '$client_support_documents', '$transportation_support_documents', '$insurance_support_documents', '$guardians_support_documents', '$trustee_support_documents', '$family_doctor_support_documents', '$dentist_support_documents', '$specialists_support_documents', '$diagnosis_support_documents', '$allergies_support_documents', '$equipment_support_documents', '$medical_details_first_aid_cpr_documents', '$medical_details_support_documents', '$seizure_protocol_upload', '$slip_fall_protocol_upload', '$transfer_protocol_upload', '$toileting_protocol_upload', '$bathing_protocol_upload', '$gtube_protocol_upload', '$oxygen_protocol_upload', '$funding_support_documents')";
	$result_insert_doc= mysqli_query($dbc, $query_insert_doc);
}
