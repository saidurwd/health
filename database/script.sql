/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  saidu
 * Created: Jan 8, 2020
 */
 -- October 18, 2022
UPDATE `os_invoice` SET `amount` = '300.000000' WHERE `id` = '75957'; 
UPDATE `os_invoice` SET `discount` = '600.000000' WHERE `id` = '75957'; 
UPDATE `os_invoice_parent` SET `total_amount` = '300.000000' WHERE `id` = '19627'; 

-- October 01, 2022
ALTER TABLE `os_invoice` ADD COLUMN `note` VARCHAR(400) NULL COMMENT 'Note' AFTER `batch`; 

--July 23, 2022
ALTER TABLE `os_service` ADD COLUMN `rate_status` ENUM('Auto','Manual') DEFAULT 'Auto' NOT NULL COMMENT 'Rate Status' AFTER `discount`;

--January 10 2022
ALTER TABLE `os_service` ADD COLUMN `ordering` INT(11) NULL COMMENT 'Ordering' AFTER `service_grade`;

-- December 14 2021
ALTER TABLE `os_patient` ADD COLUMN `admission` ENUM('Yes','No') NULL COMMENT 'Admission' AFTER `earning_source`;

-- November 16 2021
INSERT INTO `os_menu` (`parent`, `title`, `controller`, `url`, `ordering`, `group`) VALUES ('36', 'Physiotherapy Patient Contact Register', 'report', '/report/contactregister', '16', '1'); 

-- November 10 2021
ALTER TABLE `os_patient` ADD COLUMN `category_new` INT(11) NOT NULL COMMENT 'Category' AFTER `id`, CHANGE `category` `category` INT(11) NOT NULL COMMENT 'Sub Category'; 
UPDATE `os_patient` SET `category_new`=2;
ALTER TABLE `os_invoice_parent` ADD COLUMN `patient_category_new` INT(11) NULL COMMENT 'Category' AFTER `total_amount`, CHANGE `patient_category` `patient_category` INT(11) NULL COMMENT 'Sub Category'; 

-- November 06 2021
ALTER TABLE `os_patient` ADD COLUMN `village` VARCHAR(150) NULL COMMENT 'Village' AFTER `address`, ADD COLUMN `post` VARCHAR(150) NULL COMMENT 'Post' AFTER `village`; 

-- November 04 2021
ALTER TABLE `os_patient` ADD COLUMN `referred` VARCHAR(250) NULL COMMENT 'Referred' AFTER `problem`, ADD COLUMN `guardian_occupation` VARCHAR(150) NULL COMMENT 'Guardian Occupation' AFTER `referred`, ADD COLUMN `no_of_family_member` VARCHAR(50) NULL COMMENT 'Nr. of family member' AFTER `guardian_occupation`, ADD COLUMN `earning_member` VARCHAR(50) NULL COMMENT 'Earning Member' AFTER `no_of_family_member`, ADD COLUMN `earning_source` VARCHAR(150) NULL COMMENT 'Earning Source' AFTER `earning_member`; 
ALTER TABLE `os_service` ADD COLUMN `service_grade` INT NULL COMMENT 'Grade' AFTER `service_type`; 

INSERT INTO `os_menu` (`parent`, `title`, `controller`, `url`, `ordering`, `group`) VALUES ('2', 'Patient Category', 'patientCategoryNew', '/patientCategoryNew/admin', '1', '1'); 
UPDATE `os_menu` SET `title` = 'Patient Sub Category' WHERE `id` = '33'; 

CREATE TABLE `os_patient_category_new` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent` int(11) DEFAULT NULL COMMENT 'Parent',
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Category',
  `alias` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Alias',
  `path` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Path',
  `status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active' COMMENT 'Status',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*Data for the table `os_patient_category_new` */
insert  into `os_patient_category_new`(`id`,`parent`,`title`,`alias`,`path`,`status`) values 
(1,NULL,'General Health Service','General Health Service','0.1','Active'),
(2,1,'Medicine ','General Health Service/Medicine ','0.1.2','Active'),
(3,1,'Rishilpi Diagnostic Center','General Health Service/Rishilpi Diagnostic Center','0.1.3','Active'),
(4,NULL,'Rehabilitation Services','Rehabilitation Services','0.4','Active'),
(5,4,'Combined (PT + OT + Sp. Ed.)','Rehabilitation Services/Combined (PT + OT + Sp. Ed.)','0.4.5','Active'),
(6,4,'Orthopedic and Neurology Physiotherapy (Adult)','Rehabilitation Services/Orthopedic and Neurology Physiotherapy (Adult)','0.4.6','Active'),
(7,4,'Pediatric Physiotherapy (PT)','Rehabilitation Services/Pediatric Physiotherapy (PT)','0.4.7','Active'),
(8,4,'Occupational Therapy (OT)','Rehabilitation Services/Occupational Therapy (OT)','0.4.8','Active'),
(9,4,'Special Education','Rehabilitation Services/Special Education','0.4.9','Active'),
(10,4,'Inpatient Word (IPW)','Rehabilitation Services/Inpatient Word (IPW)','0.4.10','Active'),
(11,4,'Workshop (Assistive Device)','Rehabilitation Services/Workshop (Assistive Device)','0.4.11','Active'),
(12,4,'Hostel','Rehabilitation Services/Hostel','0.4.12','Active'),
(13,4,'Others','Rehabilitation Services/Others','0.4.13','Active');

ALTER TABLE `os_patient` CHANGE `remarks` `problem` VARCHAR(400) CHARSET utf8 COLLATE utf8_unicode_ci NULL COMMENT 'Problem'; 
ALTER TABLE `os_patient_prescription` CHANGE `problem` `admission` ENUM('No','Yes') CHARSET utf8 COLLATE utf8_unicode_ci DEFAULT 'No' NULL COMMENT 'Admission'; 
ALTER TABLE `os_patient_prescription` CHANGE `rx` `rx` TEXT CHARSET utf8 COLLATE utf8_unicode_ci NULL COMMENT 'Prescription', ADD COLUMN `problem` TEXT NULL COMMENT 'Problem' AFTER `rx`; 

-- October 23 2021
ALTER TABLE `os_patient` ADD COLUMN `patient_type` INT NULL COMMENT 'Patient Type' AFTER `emergency_contact`, ADD COLUMN `patient_grade` INT NULL COMMENT 'Patient Grade' AFTER `patient_type`, ADD COLUMN `remarks` VARCHAR(400) NULL COMMENT 'Remarks' AFTER `patient_grade`; 
CREATE TABLE `os_patient_grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Patient Grade',
  `remarks` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Remarks',
  `status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active' COMMENT 'Status',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE `os_patient_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Patient Type',
  `remarks` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Remarks',
  `status` enum('Active','Inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active' COMMENT 'Status',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT INTO `os_menu` (`parent`, `title`, `controller`, `url`, `ordering`, `group`) VALUES ('2', 'Patient Type', 'patientType', '/patientType/admin', '4', '1'); 
INSERT INTO `os_menu` (`parent`, `title`, `controller`, `url`, `ordering`, `group`) VALUES ('2', 'Patient Grade', 'patientGrade', '/patientGrade/admin', '5', '1'); 



ALTER TABLE `os_service` ADD COLUMN `service_type` ENUM('Consultation','Service') NULL COMMENT 'Service Type' AFTER `discount`; 
/* ------------Batch fix query------------- */
SELECT * FROM `os_purchase_receive` WHERE parent=47

SELECT * FROM `os_purchase_receive` 
WHERE item=130 AND batch=44

SELECT * FROM `os_stock_issue` 
WHERE item=130 AND batch=9

SELECT * FROM `os_invoice` 
WHERE item=130 AND batch=9

SELECT SUM(quantity) FROM `os_invoice` 
WHERE item=130 AND batch=9