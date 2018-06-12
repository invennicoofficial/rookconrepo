<?php
/*
Dashboard
FFM
*/
include ('database_connection.php');
include ('global.php');
include ('header.php');
include ('function.php');
include ('phpmailer.php');
?>
</head>
<body>

<?php include ('navigation.php');
?>

<?php
mysqli_query($dbc, "INSERT INTO `field_config` (`fieldconfigid`, `package`, `package_dashboard`, `promotion`, `promotion_dashboard`, `services`, `services_dashboard`, `sred`, `sred_dashboard`, `labour`, `labour_dashboard`, `custom`, `custom_dashboard`, `asset`, `asset_dashboard`, `equipment`, `equipment_dashboard`, `servicerecord`, `servicerecord_dashboard`, `expense`, `expense_dashboard`, `tickets`, `tickets_dashboard`, `workorder`, `workorder_dashboard`, `pos`, `pos_dashboard`, `material`, `material_dashboard`, `insurers`, `insurers_dashboard`, `certificate`, `certificate_dashboard`, `marketing_material`, `marketing_material_dashboard`, `internal_documents`, `internal_documents_dashboard`, `client_documents`, `client_documents_dashboard`, `agenda_meeting`, `medication`, `medication_dashboard`, `sales`, `sales_dashboard`, `products`, `products_dashboard`, `passwords`, `passwords_dashboard`, `task`, `task_dashboard`, `purchase_order`, `purchase_order_dashboard`, `time_tracking`, `time_tracking_dashboard`, `sales_order`, `sales_order_dashboard`) VALUES
(1, 'Service Type,Category,Heading,Cost,Description,Final Retail Price,Assign Services,Assign Clients,Assign Customer,Assign Vendor,Assign Inventory,Assign Equipment,Assign Staff,Assign Contractor', 'Service Type,Category,Heading,Final Retail Price', 'Service Type,Category,Heading,Cost,Description,Final Retail Price,Assign Services,Assign Clients,Assign Customer,Assign Vendor,Assign Inventory,Assign Equipment,Assign Staff,Assign Contractor', 'Service Type,Category,Heading,Cost,Final Retail Price', 'Service Type,Category,Heading,Description,Estimated Hours,Service Code,Fee', 'Service Type,Category,Heading,Estimated Hours,Service Code,Fee', 'SR&ED Type,Category,Heading,Cost,Description,Final Retail Price,SR&ED Code,Name', 'SR&ED Type,Category,Heading,Final Retail Price,SR&ED Code,Name', 'Labour Type,Category,Heading,Cost,Description', 'Labour Type,Category,Heading,Cost', 'Service Type,Category,Heading,Cost,Description,Assign Services,Assign Clients,Assign Customer,Assign Vendor,Assign Inventory,Assign Equipment,Assign Staff,Assign Contractor', 'Service Type,Category,Heading,Cost', 'Category,Product Name,Code,Description,Min Bin,Current Stock,Sell Price,Final Retail Price', 'Category,Product Name,Code,Min Bin,Current Stock,Final Retail Price', 'Unit Number,Serial Number,Category,Type,Description,Year Purchased,Hourly Rate,Monthly Rate,Daily Rate,Truck Lease,Insurance,Nickname,VIN Number,Color,Licence Plate,Last Oil Filter Change,Next Oil Filter Change,Ownership Status', 'Unit Number,Serial Number,Category,Type,Make,Model,Cost,Hourly Rate,Monthly Rate,Daily Rate,Nickname,VIN Number,Color,Licence Plate,Ownership Status', 'Equipment,Service Date,Advised Service Date,Description of Job,Completed,Staff,Service Record Cost,Service Type,Kilometers,Recommended Next Service Mileage,Receipt/Document', 'Unit Number,Type,Model,Service Date,Completed,Staff,Service Type', 'Expense For,Expense Date,Receipt,Expense Heading,Flight,Hotel,Breakfast,Lunch,Dinner,Drink,Transportation,Entertainment,GAS,Misc,Signature,Amount,Description,Staff,Budget,GST', 'Expense For,Expense Date,Receipt,Expense Heading,Flight,Hotel,Breakfast,Lunch,Dinner,Drink,Transportation,Entertainment,GAS,Misc,Amount,Description,Staff,Budget,GST', 'Information,Deliverables,Description,Documents,Timer,Notes,Checklist Items,Tasks,Path & Milestone', '', 'Information,Deliverables,Description,Documents,Timer,Notes,Checklist Items,Tasks', '', 'Invoice Date,Customer,Product Pricing,Discount,Delivery,Assembly,Tax,Total Price,Payment Type,Comment,Tax Exemption,Created/Sold By,Ship Date,Products,Misc Item,Category,Part#,Name,Price,Quantity,servCategory,servHeading,servPrice,servQuantity,prodCategory,prodHeading,prodPrice,prodQuantity,Admin Price,Final Retail Price', 'Invoice #,Invoice Date,Customer,Total Price,Payment Type,Invoice PDF,Status,Send to Client,Delivery/Shipping Type', 'Category,Material Name,Code,Description,Width,Length,Units,Unit Weight,Weight Per Feet,Quantity,Price', 'Category,Material Name,Code,Width,Length,Units,Unit Weight,Weight Per Feet,Quantity,Price', NULL, NULL, 'Certificate Type,Title,Category,Heading,Description,Uploader,Link,Staff,Issue Date,Reminder Date,Expiry Date', 'Certificate Type,Title,Category,Heading,Link,Staff,Issue Date,Reminder Date,Expiry Date', 'Marketing Material Type,Category,Title,Heading,Description,Uploader', 'Marketing Material Type,Category,Title,Heading', 'Internal Documents Type,Category,Title,Description,Uploader', 'Internal Documents Type,Category,Title,', 'Client Documents Type,Category,Title,Uploader,Client', 'Client Documents Type,Category,Title,Client', 'Business,Contact,Date of Meeting,Time of Meeting,End Time of Meeting,Location,Meeting Requested by,Meeting Objective,Documents,Items to Bring,Company Attendees,Contact Attendees,Add New Contact,Project,Agenda Topic,Meeting Topic,Service,Agenda Notes,Tickets Waiting for QA,Email to all Company Attendees,Email to all Contact Attendees,Meeting Notes,Client Deliverables,Company Deliverables,Add Ticket,Add Task', 'Medication Type,Category,Title,', 'Medication Type,Category,Title,', 'Today,This Week,This Month,Custom,Staff Information,Lead Information,Service,Products,Lead Source,Reference Documents,Marketing Material,Information Gathering,Estimate,Quote,Next Action,Lead Notes,Lead Status,Services Service Type,Services Category,Services Heading,Products Product Type,Products Category,Products Heading,Marketing Material Material Type,Marketing Material Category,Marketing Material Heading', 'Business/Contact,Phone/Email,Next Action,Reminder,Status', 'Product Type,Category,Heading,Cost,Description,Final Retail Price', 'Product Type,Category,Heading,Cost,Final Retail Price', 'Password Type,Category,Heading,Description,Business', 'Password Type,Category,Heading,Business', 'Business,Scrum Board,Heading,Task,Staff,To Do Date,Work Time,Status', 'Business,Heading,Task', 'Invoice Date,Customer,Product Pricing,Send Outbound Invoice,Discount,Delivery,Assembly,Tax,Total Price,Payment Type,Comment,Tax Exemption,Created/Sold By,Ship Date,prodProducts,vplProducts,Deposit Paid,Due Date,Category,Part#,Name,Price,Quantity,servCategory,servHeading,servPrice,servQuantity,prodCategory,prodHeading,prodPrice,prodQuantity,vplCategory,vplPart#,vplName,vplPrice,vplQuantity', 'Invoice #,Invoice Date,Customer,Total Price,Payment Type,Invoice PDF,Comment,Status,Send to Client,Delivery/Shipping Type,Send to Anyone', 'Business,Contact,Location,Job number,AFE number,Work performed,Short description,Job description,Labour,Position,REG Hours,REG Rate,OT Hours,OT Rate', 'Business,Contact,Location,Job number,AFE number,Labour', 'Invoice Date,Customer,Product Pricing,Send Outbound Invoice,Discount,Delivery,Assembly,Tax,Total Price,Payment Type,Comment,Tax Exemption,Created/Sold By,Ship Date,prodProducts,Deposit Paid,Due Date,Pricing by Line Item,Category,Part#,Name,Price,Quantity,servCategory,servHeading,servPrice,servQuantity,prodCategory,prodHeading,prodPrice,prodQuantity,vplCategory,vplPart#,vplName,vplPrice,vplQuantity,Admin Price,Final Retail Price', 'Invoice #,Invoice Date,Customer,Total Price,Payment Type,Invoice PDF,Status,Send to Client,Delivery/Shipping Type,Send to Anyone')");

mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) VALUES
('send_quote_client_subject', 'Your Quote is Here'),
('send_quote_client_body', '&lt;p&gt;Hello&amp;nbsp;[Client Name],&lt;/p&gt;\n&lt;p&gt;Please see attachment for your quote.&lt;/p&gt;'),
('policy_pro_max_section', '30'),
('policy_pro_max_subsection', '30'),
('policy_pro_max_thirdsection', '30'),
('quote_payment_term', 'EOM - End of month'),
('quote_due_period', '15 days'),
('pos_tax', 'GST**5**11111**No*#*PST**7**22222**No'),
('invoice_footer', 'Fresh Focus Media'),
('pos_logo', 'ffm-logo-support.png'),
('reports_dashboard', 'Validation,Sales Summary,Receivables,Profit-Loss,Time Tracking'),
('operations_max_section', '30'),
('operations_max_subsection', '30'),
('operations_max_thirdsection', '30'),
('emp_handbook_max_section', '30'),
('emp_handbook_max_subsection', '30'),
('emp_handbook_max_thirdsection', '30'),
('guide_max_section', '30'),
('guide_max_subsection', '30'),
('guide_max_thirdsection', '30'),
('quote_tax', 'GST**5**123'),
('inventory_tabs', 'Company Inventory'),
('pos_design', '3'),
('expense_types', 'IT,Business'),
('project_tile_name', 'Project'),
('next_step_after_project', 'Ticket/Work Order'),
('ticket_heading', 'Software, Website, R&D'),
('workorder_status', 'To Do, Doing, Done'),
('asset_tabs', 'Company Asset'),
('contacts_tabs', 'Business,Staff'),
('quote_term_condition', 'Total Amount of all individual items.'),
('ticket_status', 'Sales,Last Minute Priority,Information Gathering,To Be Scheduled,To Do,Doing,Internal QA,Client QA,Waiting On Client,Done,Archived'),
('task_tab', 'Company Internal'),
('sales_lead_source', 'Software,Friend'),
('invoice_payment_types', 'Pay Now,Net 30,Net 60,Net 90,Net 120,Mastercard,Visa,Debit Card,Net 30 Days'),
('equipment_tabs', 'Company Equipment'),
('estimate_service_price_or_hours', 'Estimated Total Hours'),
('estimate_service_qty_cost', 'Cost Per Hours'),
('project_service_price_or_hours', 'Actual Hours'),
('project_service_qty_cost', 'Hourly Rate'),
('ticket_tab', 'Company Internal'),
('password_category', 'Internal,Client'),
('task_status', 'Last Minute Priority,Information Gathering,To Do,Doing,Done,Archived'),
('task_ticket', 'Task,Ticket'),
('purchase_order_logo', 'ffm-logo-support.png'),
('purchase_order_tax', 'GST**5**1222**No'),
('purchase_order_company_address', '&lt;p&gt;7220&amp;nbsp;Fairmount&amp;nbsp;Drive SE&lt;/p&gt;\r\n&lt;p&gt;Calgary AB&lt;/p&gt;\r\n&lt;p&gt;403 904 8746&lt;/p&gt;\r\n&lt;p&gt;info@freshfocusmedia.com&lt;/p&gt;'),
('purchase_order_footer', 'All Prices is in CAD'),
('contacts_classification', 'Long Term,Short Term'),
('pos_promotion', '5**6**wholesale_price*#*1**2**admin_price*#*22**3**client_price*#*4**2**wholesale_price'),
('software_styler_choice', 'swr'),
('company_name', 'FFM'),
('vpl_tabs', 'Internal PL'),
('purchase_order_design', '3'),
('purchaseorder_tile_titler', 'Purchase Order'),
('po_invoice_payment_types', 'Pay Now,Net 30,Net 60,Net 90,Net 120,'),
('po_invoice_footer', 'Fresh Focus Media'),
('safety_main_site_tabs', 'Site 1,Site 2'),
('sales_order_logo', 'ffm-logo-support.png'),
('sales_order_design', '3'),
('sales_order_tax', 'GST**5**12333**No'),
('sales_order_tile_titler', 'Sales Order'),
('sales_order_invoice_payment_types', 'Pay Now,Net 30,Net 60,Net 90,Net 120,'),
('sales_order_invoice_footer', 'Fresh Focus Media'),
('pos_tile_titler', 'Point of Sale')");

mysqli_query($dbc, "INSERT INTO `field_config_asset` (`tab`, `accordion`, `order`, `asset`, `asset_dashboard`) VALUES
('Company Asset', 'Description', 1, 'Description,Category,Name,Type', NULL),
('Company Asset', 'Pricing', 2, 'Category,Final Retail Price', NULL),
('Company Asset', 'Stock', 3, 'Category,Current Stock', NULL),
('Company Asset', 'Alerts', 4, 'Category,Min Bin', NULL),
('Company Asset', 'Status', 5, 'Category,Status', NULL),
('Company Asset', 'General', 6, 'Category,Notes', NULL)");

mysqli_query($dbc, "INSERT INTO `field_config_contacts` (`tab`, `accordion`, `contacts`, `order`, `contacts_dashboard`) VALUES
('Business', 'Business Information', 'Category,Name,Office Phone,Fax,Email Address,Website', 1, NULL),
('Business', 'Business Address', 'Category,Business Address,Postal Code,City,Province,State,Country', 2, NULL),
('Staff', 'Business Information', 'Category,Business', 1, NULL),
('Staff', 'Staff Information', 'Category,First Name,Last Name,Office Phone,Cell Phone,Home Phone,Fax,Email Address,Website', 2, NULL),
('Staff', 'Staff Address', 'Category,Mailing Address,Postal Code,City,Province,State,Country', 3, NULL),
('Staff', 'Login Information', 'Category,Role,User Name,Password', 4, NULL),
('Business', NULL, NULL, NULL, 'Category,Name,Office Phone,Email Address'),
('Staff', NULL, NULL, NULL, 'Category,Business,First Name,Last Name,Role,Email Address')");

mysqli_query($dbc, "INSERT INTO `field_config_equipment` (`tab`, `accordion`, `order`, `equipment`, `equipment_dashboard`) VALUES
('Company Equipment', 'Description', 1, 'Description,Category,Type,Make,Model', 'Category,Type,Make,Model,Cost'),
('Company Equipment', 'Product Cost', 2, 'Cost', 'Category,Type,Make,Model,Cost'),
('Company Equipment', 'Status', 3, 'Status', 'Category,Type,Make,Model,Cost'),
('Company Equipment', 'General', 4, 'Notes', 'Category,Type,Make,Model,Cost'),
('Company Equipment', NULL, NULL, NULL, 'Category,Type,Make,Model,Cost'),
('service_request', NULL, NULL, 'Equipment,Service Record,Defects,Comment', 'Equipment,Service Record,Defects'),
('service_record', NULL, NULL, 'Service Date,Advised Service Date,Equipment,Inventory,Description of Job,Service Record Mileage,Hours,Completed,Staff,Vendor,Service Record Cost,Service Type,Kilometers,Recommended Next Service Mileage,Receipt/Document', 'Service Date,Advised Service Date,Equipment,Inventory,Staff,Vendor,Service Record Cost')");

mysqli_query($dbc, "INSERT INTO `field_config_estimate` (`fieldconfigestimateid`, `logo`, `quote_pdf_footer_logo`, `config_fields`, `config_fields_dashboard`, `config_fields_quote`, `config_fields_quote_dashboard`, `quote_pdf_header`, `quote_pdf_footer`) VALUES
(1, 'fresh-focus-logo-dark.png', 'letterhead_footer.png', 'Package Heading,Promotion Heading,Custom Heading,Material Code,Services Heading,Products Heading,SRED Heading,Staff Contact Person,Contractor Contact Person,Clients Client Name,Clients Contact Person,Vendor Pricelist Vendor,Vendor Pricelist Price List,Vendor Pricelist Category,Vendor Pricelist Product,Customer Customer Name,Customer Contact Person,Inventory Product Name,Equipment Unit/Serial Number,Labour Heading,Expenses Type,Expenses Category,Other Detail,Details Issue,Details GAP,Details Work,Package,Package Category,Promotion,Custom,Material,Services,Services Service Type,Services Category,Products,Products Product Type,Products Category,Staff,Contractor,Clients,Vendor Pricelist,Customer,Inventory,Equipment,Labour', 'Estimate#,Business,Estimate Name,Total Cost,Notes,Financial Summary,Review Quote,Status,History', 'Package,Package Service Type,Package Category,Package Heading,Package Description,Promotion Service Type,Promotion Category,Promotion Heading,Promotion Description,Custom Service Type,Custom Category,Custom Heading,Custom Description,Material Code,Material Category,Material Material Name,Material Description,Services,Services Service Type,Services Category,Services Heading,Services Description,Products Product Type,Products Category,Products Heading,Products Description,Staff Contact Person,Staff Description,Contractor Contact Person,Contractor Description,Clients Client Name,Clients Contact Person,Clients Description,Vendor Pricelist Vendor,Vendor Pricelist Price List,Vendor Pricelist Category,Vendor Pricelist Product,Vendor Pricelist Code,Vendor Pricelist Description,Customer Customer Name,Customer Contact Person,Customer Description,Inventory,Inventory Category,Inventory Product Name,Inventory Code,Inventory Sub-Category,Inventory Description,Equipment Category,Equipment Unit Number,Equipment Serial Number,Equipment Type,Equipment Description,Labour Type,Labour Heading,Labour Category,Labour Description', 'Quote#,Client,Quote Name,Total Cost,Quote,Follow up Date,Status,Send To Client,History', '&lt;p&gt;7220&amp;nbsp;Fairmount&amp;nbsp;Drive SE&lt;/p&gt;\r\n&lt;p&gt;Calgary AB&lt;/p&gt;\r\n&lt;p&gt;403 904 8746&lt;/p&gt;\r\n&lt;p&gt;info@freshfocusmedia.com&lt;/p&gt;', '')");

mysqli_query($dbc, "INSERT INTO `field_config_incident_report` (`fieldconfigid`, `incident_report`, `incident_report_dashboard`) VALUES
(1, 'Type,Staff,Description,Record Equipment Or Property Damage,Record Cause Of Accident,Corrective Action,Witness Statement,Supervisor Statement & Signoff,Taken Care,Initial Actions Required,Record Of Injury Involved,Interview Witness(s),Check Background Info,Timing,Determine Causes,Supply Pictures,Follow Up,Managers Review Signature', 'Type,Staff,Follow Up,Date Created,PDF')");

mysqli_query($dbc, "INSERT INTO `field_config_inventory` (`tab`, `accordion`, `inventory`, `order`, `inventory_dashboard`, `receive_shipment`) VALUES
('Company Inventory', 'Description', 'Description,Category,Name', 1, NULL, NULL),
('Company Inventory', 'Product Cost', 'Cost', 2, NULL, NULL),
('Company Inventory', 'Pricing', 'Final Retail Price', 3, NULL, NULL),
('Company Inventory', 'Stock', 'Current Stock', 4, NULL, NULL),
('Company Inventory', 'Alerts', 'Min Bin', 5, NULL, NULL),
('Company Inventory', 'Status', 'Status', 6, NULL, NULL),
('Company Inventory', 'General', 'Notes', 7, NULL, NULL),
('Company Inventory', NULL, NULL, NULL, 'Category,Name,Cost,Current Stock,Min Bin,Status', NULL)");

mysqli_query($dbc, "INSERT INTO `field_config_manuals` (`manualsid`, `manual`, `policy_procedures`, `operations_manual`, `emp_handbook`, `guide`, `safety`) VALUES
(1, 'Policies & Procedures,Operations Manual,Employee Handbook,How to Guide,Safety', 'Topic (Sub Tab),Detail,Signature box,Comments,Staff,Section #,Section Heading,Sub Section #,Sub Section Heading', 'Topic (Sub Tab),Detail,Signature box,Comments,Staff,Section #,Section Heading,Sub Section #,Sub Section Heading', 'Topic (Sub Tab),Detail,Signature box,Comments,Staff,Section #,Section Heading,Sub Section #,Sub Section Heading', 'Topic (Sub Tab),Detail,Signature box,Comments,Staff,Section #,Section Heading,Sub Section #,Sub Section Heading', NULL)");

mysqli_query($dbc, "INSERT INTO `field_config_ratecard` (`fieldconfigratecardid`, `config_fields`) VALUES
(1, 'Package Heading,Promotion Heading,Custom Heading,Material Code,Services Heading,Products Heading,SRED Heading,Staff Contact Person,Contractor Contact Person,Clients Client Name,Clients Contact Person,Vendor Pricelist Vendor,Vendor Pricelist Price List,Vendor Pricelist Category,Vendor Pricelist Product,Customer Customer Name,Customer Contact Person,Inventory Product Name,Equipment Unit/Serial Number,Labour Heading,Expenses Type,Expenses Category,Other Detail,Package,Package Service Type,Package Category,Promotion,Promotion Service Type,Promotion Category,Custom,Custom Service Type,Custom Category,Material,Services,Services Service Type,Services Category,Products,Products Product Type,Products Category,SRED,SRED SRED Type,SRED Category,Staff,Contractor,Clients,Vendor Pricelist,Customer,Inventory,Inventory Category,Equipment,Equipment Category,Labour,Labour Type,Expenses,Other')");

mysqli_query($dbc, "INSERT INTO `field_config_vpl` (`tab`, `accordion`, `inventory`, `order`, `inventory_dashboard`, `receive_shipment`) VALUES
('Company Inventory', 'Description', 'Description,Category,Name', 1, NULL, NULL),
('Company Inventory', 'Product Cost', 'Cost', 2, NULL, NULL),
('Company Inventory', 'Pricing', 'Final Retail Price', 3, NULL, NULL),
('Company Inventory', 'Stock', 'Current Stock', 4, NULL, NULL),
('Company Inventory', 'Alerts', 'Min Bin', 5, NULL, NULL),
('Company Inventory', 'Status', 'Status', 6, NULL, NULL),
('Company Inventory', 'General', 'Notes', 7, NULL, NULL),
('Company Inventory', NULL, NULL, NULL, 'Category,Name,Cost,Current Stock,Min Bin,Status', NULL)");

mysqli_query($dbc, "INSERT INTO `field_config_project` (`type`, `config_fields`, `config_fields_quote`) VALUES
('Client', 'Package Heading,Promotion Heading,Custom Heading,Material Code,Services Heading,Products Heading,SRED Heading,Staff Contact Person,Contractor Contact Person,Clients Client Name,Clients Contact Person,Vendor Pricelist Vendor,Vendor Pricelist Price List,Vendor Pricelist Category,Vendor Pricelist Product,Customer Customer Name,Customer Contact Person,Inventory Product Name,Equipment Unit/Serial Number,Labour Heading,Expenses Type,Expenses Category,Other Detail,Details Base Knowledge,Details Do,Details Sources,Details Plan,Details Next Steps,Package,Package Service Type,Package Category,Material,Services,Services Service Type,Services Category,Products,Products Product Type,Products Category,SRED,SRED SRED Type,SRED Category,Staff,Contractor,Clients,Vendor Pricelist,Inventory,Inventory Category,Equipment,Equipment Category,Labour,Labour Type', NULL),
('Internal', 'Package Heading,Promotion Heading,Custom Heading,Material Code,Services Heading,Products Heading,SRED Heading,Staff Contact Person,Contractor Contact Person,Clients Client Name,Clients Contact Person,Vendor Pricelist Vendor,Vendor Pricelist Price List,Vendor Pricelist Category,Vendor Pricelist Product,Customer Customer Name,Customer Contact Person,Inventory Product Name,Equipment Unit/Serial Number,Labour Heading,Expenses Type,Expenses Category,Other Detail,Details Base Knowledge,Details Do,Details Sources,Details Plan,Details Next Steps,Package,Package Service Type,Package Category,Material,Services,Services Service Type,Services Category,Products,Products Product Type,Products Category,SRED,SRED SRED Type,SRED Category,Staff,Contractor,Clients,Vendor Pricelist,Inventory,Inventory Category,Equipment,Equipment Category,Labour,Labour Type', NULL)");

mysqli_query($dbc, "INSERT INTO `newsboard` (`newsboard_type`, `title`, `description`) VALUES ('Fresh Focus Media', 'Sample News Item', '&lt;p&gt;This is a sample news item. You can read more by clicking on the Read More button.&amp;nbsp;This is a sample news item. You can read more by clicking on the Read More button.&amp;nbsp;This is a sample news item. You can read more by clicking on the Read More button.&amp;nbsp;This is a sample news item. You can read more by clicking on the Read More button.&amp;nbsp;This is a sample news item. You can read more by clicking on the Read More button.&amp;nbsp;This is a sample news item. You can read more by clicking on the Read More button.&amp;nbsp;This is a sample news item. You can read more by clicking on the Read More button.&lt;/p&gt;
&lt;p&gt;This is a sample news item. You can read more by clicking on the Read More button.&amp;nbsp;This is a sample news item. You can read more by clicking on the Read More button.&amp;nbsp;This is a sample news item. You can read more by clicking on the Read More button.&amp;nbsp;This is a sample news item. You can read more by clicking on the Read More button.&amp;nbsp;This is a sample news item. You can read more by clicking on the Read More button.&amp;nbsp;This is a sample news item. You can read more by clicking on the Read More button.&amp;nbsp;This is a sample news item. You can read more by clicking on the Read More button.&lt;/p&gt;')");
$newsboardid = mysqli_insert_id($dbc);

mysqli_query($dbc, "INSERT INTO `newsboard_uploads` (`newsboardid`, `type`, `document_link`) VALUES ('$newsboardid', 'Document', 'http://www.freshfocusmedia.com/wp-content/uploads/software-newsboards/sample.jpg')");

echo 'Basic Config Done';
?>

<?php include ('footer.php'); ?>