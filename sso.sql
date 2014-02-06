-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 07, 2014 at 12:11 AM
-- Server version: 5.6.11
-- PHP Version: 5.4.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sso`
--

-- --------------------------------------------------------

--
-- Table structure for table `clouds`
--

CREATE TABLE IF NOT EXISTS `clouds` (
  `cID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `type` enum('OpenStack','Hyper-V','VMware') NOT NULL,
  `name` varchar(255) NOT NULL,
  `endpoint` varchar(255) NOT NULL,
  `dashboard` varchar(255) NOT NULL,
  `admin_user` varchar(255) DEFAULT NULL,
  `admin_pass` varchar(255) DEFAULT NULL,
  `admin_token` varchar(255) DEFAULT NULL,
  `user_tenant` varchar(255) DEFAULT NULL,
  `metadata` longtext,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `clouds`
--

INSERT INTO `clouds` (`cID`, `userID`, `type`, `name`, `endpoint`, `dashboard`, `admin_user`, `admin_pass`, `admin_token`, `user_tenant`, `metadata`, `date`, `active`) VALUES
(4, 17, 'OpenStack', 'Local demo', 'http://192.168.2.128:35357/v2.0/', 'http://192.168.2.128', 'admin', 'admin', 'atoken', 'demo', NULL, '2013-10-19 10:07:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `pID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `cID` int(11) NOT NULL,
  `ucID` varchar(255) DEFAULT NULL,
  `token` text,
  `tenant` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`pID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `data` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `data`, `date`) VALUES
(1, 'facebook_app_id', '', '2013-10-12 19:10:15'),
(2, 'facebook_app_secret_key', '', '2013-10-12 19:10:33'),
(3, 'github_client_id', '', '2013-10-12 19:10:54'),
(4, 'github_client_secret_key', '', '2013-10-12 19:11:06'),
(5, 'google_client_id', '', '2013-10-12 19:11:19'),
(6, 'google_client_secret_id', '', '2013-10-12 19:11:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(4) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(80) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_type` enum('admin','user') NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `avatar` text,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `first_name`, `last_name`, `email`, `username`, `password`, `user_type`, `uid`, `service`, `avatar`) VALUES
(17, 'admin', 'admin', 'admin@test.com', 'admin', 'qdMmppuJtj/isqzel+UWrpFGwcjtm0MGTJP+/DANYyg=', 'admin', '0', 'local', 'http://images.wikia.com/despicableme/images/4/47/Minion.jpg'),
(18, 'Mike', 'Tyson', 'test@test.com', 'mike', 'qdMmppuJtj/isqzel+UWrpFGwcjtm0MGTJP+/DANYyg=', 'user', '0', 'local', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `virtualm`
--

CREATE TABLE IF NOT EXISTS `virtualm` (
  `vmID` int(11) NOT NULL AUTO_INCREMENT,
  `cID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `rvmID` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `flavor` varchar(255) DEFAULT NULL,
  `pass` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`vmID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
