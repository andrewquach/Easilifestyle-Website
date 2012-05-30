-- phpMyAdmin SQL Dump
-- version 3.1.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 12, 2009 at 02:12 PM
-- Server version: 5.1.32
-- PHP Version: 5.2.9-1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `usezshopper`
--

-- --------------------------------------------------------

--
-- Table structure for table `usez_commissions`
--

CREATE TABLE IF NOT EXISTS `usez_commissions` (
  `commissions_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `remark` int(11) NOT NULL,
  `commission_time` date NOT NULL,
  `created_time` date NOT NULL,
  `update_time` datetime NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`commissions_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `usez_commissions`
--

INSERT INTO `usez_commissions` (`commissions_id`, `customer_id`, `remark`, `commission_time`, `created_time`, `update_time`, `amount`, `status`, `type`, `description`) VALUES
(2, 5, 123, '2009-11-20', '2009-11-11', '2009-11-11 00:00:00', 123, 'available', 'type1', '123'),
(3, 3, 222, '2009-11-11', '2009-11-11', '2009-11-11 06:14:09', 2222, 'available', 'type1', '222222');


--
-- Database: `usezshopper`
--

-- --------------------------------------------------------

--
-- Table structure for table `usez_company_pool`
--

CREATE TABLE IF NOT EXISTS `usez_company_pool` (
  `companypool_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_time` date NOT NULL,
  `company_real_amount` decimal(12,0) NOT NULL,
  `company_edit_amount` decimal(12,0) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`companypool_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `usez_company_pool`
--

INSERT INTO `usez_company_pool` (`companypool_id`, `company_time`, `company_real_amount`, `company_edit_amount`, `notes`) VALUES
(1, '2009-11-09', '4460', '50', 'Company Pool'),
(2, '2009-11-11', '2650', '0', 'Company Pool date :2009-11');


--
-- Database: `usezshopper`
--

-- --------------------------------------------------------

--
-- Table structure for table `usez_orphan_pool`
--

CREATE TABLE IF NOT EXISTS `usez_orphan_pool` (
  `orphanpool_id` int(11) NOT NULL AUTO_INCREMENT,
  `orphan_time` date NOT NULL,
  `orphan_real_amount` decimal(12,0) NOT NULL,
  `orphan_edit_amount` decimal(12,0) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`orphanpool_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `usez_orphan_pool`
--

INSERT INTO `usez_orphan_pool` (`orphanpool_id`, `orphan_time`, `orphan_real_amount`, `orphan_edit_amount`, `notes`) VALUES
(1, '2009-11-06', '500', '400', 'Orphan Pool 123123');
