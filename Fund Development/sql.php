ALTER TABLE `field_config`  ADD `fund_development_funders` TEXT NULL  AFTER `external_communication_dashboard`,  ADD `fund_development_funding` TEXT NULL  AFTER `fund_development_funders`;

CREATE TABLE `fund_development_funder` (
  `fundersid` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `client_id` varchar(255) DEFAULT NULL,
  `aish` varchar(255) DEFAULT NULL,
  `work_phone` varchar(255) DEFAULT NULL,
  `cell_phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postal_zip_code` varchar(255) DEFAULT NULL,
  `city_town` varchar(255) DEFAULT NULL,
  `province_state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `fund_development_funder`
  ADD PRIMARY KEY (`fundersid`);

ALTER TABLE `fund_development_funder`
  MODIFY `fundersid` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `fund_development_funding` (
  `fundingid` int(10) NOT NULL,
  `funding_for` varchar(500) DEFAULT NULL,
  `contact` varchar(500) DEFAULT NULL,
  `staff` varchar(200) DEFAULT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `description` text,
  `ex_date` date DEFAULT NULL,
  `ex_file` varchar(1000) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `day_funding` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `gst` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `deleted` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `fund_development_funding`
  ADD PRIMARY KEY (`fundingid`);

ALTER TABLE `fund_development_funding`
  MODIFY `fundingid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;