INSERT INTO `field_config_contact` (`fieldconfigid`, `contact`, `clients`, `clients_dashboard`, `customer`, `customer_dashboard`, `vendors`, `vendors_dashboard`, `vendor_pricelist`, `vendor_pricelist_dashboard`, `staff`, `staff_dashboard`, `patients`, `patients_dashboard`, `contractor`, `contractor_dashboard`) VALUES
(1, 'Staff', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'First Name,Last Name', 'First Name,Last Name', NULL, NULL, NULL, NULL);

UPDATE `field_config` SET `package` = 'Service Type,Category,Heading' WHERE `fieldconfigid` = 1;
UPDATE `field_config` SET `package_dashboard` = 'Service Type,Category,Heading' WHERE `fieldconfigid` = 1;
UPDATE `field_config` SET `promotion` = 'Service Type,Category,Heading' WHERE `fieldconfigid` = 1;
UPDATE `field_config` SET `promotion_dashboard` = 'Service Type,Category,Heading' WHERE `fieldconfigid` = 1;
UPDATE `field_config` SET `services` = 'Service Type,Category,Heading' WHERE `fieldconfigid` = 1;
UPDATE `field_config` SET `services_dashboard` = 'Service Type,Category,Heading' WHERE `fieldconfigid` = 1;
UPDATE `field_config` SET `custom` = 'Service Type,Category,Heading' WHERE `fieldconfigid` = 1;
UPDATE `field_config` SET `custom_dashboard` = 'Service Type,Category,Heading' WHERE `fieldconfigid` = 1;


UPDATE `field_config` SET `inventory` = 'Category,Product Name' WHERE `fieldconfigid` = 1;
UPDATE `field_config` SET `inventory_dashboard` = 'Category,Product Name' WHERE `fieldconfigid` = 1;

UPDATE `field_config` SET `equipment` = 'Unit Number,Serial Number,Category' WHERE `fieldconfigid` = 1;
UPDATE `field_config` SET `equipment_dashboard` = 'Unit Number,Serial Number,Category' WHERE `fieldconfigid` = 1;

UPDATE `field_config` SET `servicerecord` = 'Equipment' WHERE `fieldconfigid` = 1;
UPDATE `field_config` SET `servicerecord_dashboard` = 'Equipment' WHERE `fieldconfigid` = 1;

UPDATE `field_config` SET `asset` = 'Category,Product Name' WHERE `fieldconfigid` = 1;
UPDATE `field_config` SET `asset_dashboard` = 'Category,Product Name' WHERE `fieldconfigid` = 1;
