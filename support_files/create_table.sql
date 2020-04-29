CREATE DATABASE nyc_open_data;

Use nyc_open_data;

CREATE TABLE `permits` (
  `EventID` int(11) NOT NULL,
  `EventType` text,
  `StartDateTime` datetime DEFAULT NULL,
  `EndDateTime` datetime DEFAULT NULL,
  `EnteredOn` datetime DEFAULT NULL,
  `EventAgency` text,
  `ParkingHeld` text,
  `Borough` varchar(100) DEFAULT NULL,
  `CommunityBoard(s)` text,
  `PolicePrecinct(s)` text,
  `Category` varchar(100) DEFAULT NULL,
  `SubCategoryName` varchar(100) DEFAULT NULL,
  `Country` text,
  `ZipCode(s)` text,
  `Zip` varchar(20) NOT NULL, 
  UNIQUE KEY `idx_permits_EventID_Zip` (`EventID`,`Zip`),
  KEY `idx_permits_StartDateTime` (`StartDateTime`),
  KEY `idx_permits_Borough` (`Borough`),
  KEY `idx_permits_SubCategoryName` (`SubCategoryName`)
);
