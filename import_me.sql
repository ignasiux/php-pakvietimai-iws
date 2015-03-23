-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 28, 2013 at 03:18 PM
-- Server version: 5.5.33-log
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `creative_pakv`
--

-- --------------------------------------------------------

--
-- Table structure for table `nariai`
--

CREATE TABLE IF NOT EXISTS `nariai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vardas` varchar(25) NOT NULL,
  `slaptazodis` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ip` varchar(16) NOT NULL,
  `pakviete` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pakviesti`
--

CREATE TABLE IF NOT EXISTS `pakviesti` (
  `pakviete` varchar(25) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `salis` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
