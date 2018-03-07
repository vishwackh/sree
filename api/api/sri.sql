`database`: "conventionhalldb"
create database conventionhalldb;
use conventionhalldb;

CREATE TABLE `login` (
  `id` int(11) NOT NULL primary key,
  `userName` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `token` varchar(200) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `createdDate` datetime NOT NULL,
  `updatedDate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


INSERT INTO `login` (`id`, `userName`, `password`, `token`, `firstName`, `lastName`, `phone`, `email`, `type`, `active`, `createdDate`, `updatedDate`) 
VALUES (1, 'admin', 'admin', '2057564ac24451b55368bb170fe0b91d', 'admin', 'admin', '9986552521', 'admin@gmail.com', 1, 1, now(), now());

CREATE TABLE IF NOT EXISTS `booking` (
  `booking_Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customername` varchar(60) NOT NULL,
  `bridename` varchar(60) NOT NULL,
  `brideDOB` timestamp,
  `groomname` varchar(60) NOT NULL,
  `groomDOB` timestamp,
  `eventdate` timestamp,   
  `numberOfDays` int(11),
  `eventenddate` timestamp,
  `bookingType` int(11) NOT NULL DEFAULT '0', 
  `isBookingCancel` int(11) NOT NULL DEFAULT '0', 
  `phonenumber` char(15) NOT NULL,
  `emailid` varchar(60) NOT NULL,
  `addOnServices` text,
  `address` text,
  `eventname` varchar(60) NOT NULL,
  `panadharno` varchar(22) NOT NULL,
  `totalamount` double NOT NULL,
  `createdTime`datetime NOT NULL,
  `modifiedTime` timestamp NOT NULL,
  PRIMARY KEY (`booking_Id`)
);

CREATE TABLE IF NOT EXISTS `bookingCancelation` (
  `cancel_Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `booking_Id` int(11) not null,
  `paidamount` double not null,
  `refundStatus` int(11) NOT NULL DEFAULT '0', 
  `refundAmount` double not null,
  `createdTime`datetime NOT NULL,
  `modifiedTime` timestamp NOT NULL,
 PRIMARY KEY (`cancel_Id`),
  KEY(`cancel_Id`,`booking_Id`)
);
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `booking_Id` int(11) not null,
  `paidamount` double not null,
  `paymentType` varchar(20) not null,
  `chequeno` varchar(20),
  `chequeURL` varchar(120),
  `balanceamount` double not null,
  `createdTime` datetime NOT NULL,
  `modifiedTime` timestamp NOT NULL,
  PRIMARY KEY (`payment_Id`),
  KEY(`payment_Id`,`booking_Id`)
);


CREATE TABLE IF NOT EXISTS `event` (
  `event_Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `eventname` varchar(120) not null,
  `description` text,
  `eventdate` datetime NOT NULL,
  `createdTime` datetime NOT NULL,
  `modifiedTime` timestamp NOT NULL,
  PRIMARY KEY (`event_Id`)
);

CREATE TABLE IF NOT EXISTS `eventFeedback` (
  `feedback_Id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customername` varchar(120) not null,
  `feedback` text,
  `createdTime` datetime NOT NULL,
  PRIMARY KEY (`feedback_Id`)
);

