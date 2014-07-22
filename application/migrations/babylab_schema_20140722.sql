-- phpMyAdmin SQL Dump
-- version 3.3.7deb8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 22, 2014 at 10:27 AM
-- Server version: 5.1.73
-- PHP Version: 5.3.3-7+squeeze20

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `babylab`
--

-- --------------------------------------------------------

--
-- Table structure for table `call`
--

CREATE TABLE IF NOT EXISTS `call` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `participation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nr` smallint(6) NOT NULL,
  `status` enum('call_started','no_reply','voicemail','email','confirmed','cancelled') NOT NULL DEFAULT 'call_started',
  `timestart` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timeend` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `participation_id` (`participation_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `caller`
--

CREATE TABLE IF NOT EXISTS `caller` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `experiment_id` int(11) NOT NULL,
  `user_id_caller` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `experiment_id` (`experiment_id`),
  KEY `user_id_caller` (`user_id_caller`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `participant_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` varchar(2000) NOT NULL,
  `priority` tinyint(1) NOT NULL DEFAULT '0',
  `timecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `participant_id` (`participant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=105 ;

-- --------------------------------------------------------

--
-- Table structure for table `dyslexia`
--

CREATE TABLE IF NOT EXISTS `dyslexia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `participant_id` int(11) NOT NULL,
  `gender` enum('m','f') NOT NULL,
  `statement` tinyint(1) NOT NULL,
  `emt_score` int(11) DEFAULT NULL,
  `klepel_score` int(11) DEFAULT NULL,
  `vc_score` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `participant_gender` (`participant_id`,`gender`),
  KEY `participant_id` (`participant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- Table structure for table `experiment`
--

CREATE TABLE IF NOT EXISTS `experiment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `type` varchar(100) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `duration` int(11) NOT NULL,
  `timecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timeclosed` timestamp NULL DEFAULT NULL,
  `agefrommonths` int(11) NOT NULL,
  `agefromdays` int(11) NOT NULL,
  `agetomonths` int(11) NOT NULL,
  `agetodays` int(11) NOT NULL,
  `multilingual` tinyint(1) NOT NULL,
  `dyslexic` tinyint(1) NOT NULL,
  `archived` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `location_id` (`location_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `impediment`
--

CREATE TABLE IF NOT EXISTS `impediment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `participant_id` int(11) NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `comment` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `participant_id` (`participant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `participant_id` int(11) NOT NULL,
  `language` varchar(200) NOT NULL,
  `percentage` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `participant_language` (`participant_id`,`language`),
  KEY `participant_id` (`participant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=171 ;

-- --------------------------------------------------------

--
-- Table structure for table `leader`
--

CREATE TABLE IF NOT EXISTS `leader` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `experiment_id` int(11) NOT NULL,
  `user_id_leader` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `experiment_id` (`experiment_id`),
  KEY `user_id_leader` (`user_id_leader`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `roomnumber` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempt`
--

CREATE TABLE IF NOT EXISTS `login_attempt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) NOT NULL,
  `login` varchar(50) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ncdi_check`
--

CREATE TABLE IF NOT EXISTS `ncdi_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` int(11) NOT NULL,
  `p_number` varchar(200) NOT NULL,
  `ageinmonths` int(11) NOT NULL,
  `gender` enum('m','f') NOT NULL,
  `b_score` int(11) NOT NULL,
  `p_score` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Table structure for table `participant`
--

CREATE TABLE IF NOT EXISTS `participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `gender` enum('m','f') NOT NULL,
  `dateofbirth` date NOT NULL,
  `birthweight` int(11) NOT NULL,
  `pregnancyweeks` int(11) NOT NULL,
  `pregnancydays` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `phonealt` varchar(20) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `multilingual` tinyint(1) NOT NULL,
  `dyslexicparent` enum('m','f','mf') DEFAULT NULL,
  `problemsparent` enum('m','f','mf') DEFAULT NULL,
  `parentfirstname` varchar(200) DEFAULT NULL,
  `parentlastname` varchar(200) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `origin` enum('letter','zwazat','mouth','info','other') NOT NULL DEFAULT 'other',
  `activated` tinyint(1) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=676 ;

-- --------------------------------------------------------

--
-- Table structure for table `participation`
--

CREATE TABLE IF NOT EXISTS `participation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `experiment_id` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL,
  `risk` tinyint(1) DEFAULT NULL,
  `lastcalled` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('unconfirmed','confirmed','rescheduled','cancelled','completed','no_show') NOT NULL DEFAULT 'unconfirmed',
  `nrcalls` int(11) NOT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `cancelled` tinyint(1) NOT NULL,
  `noshow` tinyint(1) NOT NULL,
  `completed` tinyint(1) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `appointment` timestamp NULL DEFAULT NULL,
  `locktime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `experiment_participant` (`experiment_id`,`participant_id`),
  KEY `experiment_id` (`experiment_id`),
  KEY `participant_id` (`participant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `percentile`
--

CREATE TABLE IF NOT EXISTS `percentile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testcat_id` int(11) NOT NULL,
  `gender` enum('m','f') DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `score` int(11) NOT NULL,
  `percentile` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `testcat_id` (`testcat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2071 ;

-- --------------------------------------------------------

--
-- Table structure for table `relation`
--

CREATE TABLE IF NOT EXISTS `relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `experiment_id` int(11) NOT NULL,
  `relation` enum('prerequisite','excludes') NOT NULL,
  `rel_exp_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `experiment_id` (`experiment_id`),
  KEY `rel_exp_id` (`rel_exp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE IF NOT EXISTS `result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `participation_id` int(11) NOT NULL,
  `phasenr` int(11) NOT NULL,
  `phase` varchar(50) NOT NULL,
  `trial` int(11) DEFAULT NULL,
  `condition` varchar(50) DEFAULT NULL,
  `lookingtime` int(11) NOT NULL,
  `nrlooks` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `participation_id` (`participation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE IF NOT EXISTS `score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testcat_id` int(11) NOT NULL,
  `testinvite_id` int(11) NOT NULL,
  `score` varchar(200) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `testcat_testinvite` (`testcat_id`,`testinvite_id`),
  KEY `testcat_id` (`testcat_id`),
  KEY `testinvite_id` (`testinvite_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2769 ;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `testcat`
--

CREATE TABLE IF NOT EXISTS `testcat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `score_type` enum('bool','int','date','string') DEFAULT NULL,
  `limesurvey_question_id` varchar(200) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `test_code` (`test_id`,`code`),
  UNIQUE KEY `test_question_id` (`test_id`,`limesurvey_question_id`),
  KEY `test_id` (`test_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=143 ;

-- --------------------------------------------------------

--
-- Table structure for table `testinvite`
--

CREATE TABLE IF NOT EXISTS `testinvite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testsurvey_id` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL,
  `token` varchar(20) NOT NULL,
  `datesent` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `datecompleted` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `testsurvey_participant_id` (`testsurvey_id`,`participant_id`),
  UNIQUE KEY `token` (`token`),
  KEY `participant_id` (`participant_id`),
  KEY `testsurvey_id` (`testsurvey_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=72 ;

-- --------------------------------------------------------

--
-- Table structure for table `testsurvey`
--

CREATE TABLE IF NOT EXISTS `testsurvey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_id` int(11) NOT NULL,
  `limesurvey_id` int(11) NOT NULL,
  `whensent` enum('participation','months') NOT NULL,
  `whennr` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `testtemplate`
--

CREATE TABLE IF NOT EXISTS `testtemplate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_id` int(11) NOT NULL,
  `language` enum('dutch','english') NOT NULL,
  `template` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `test_language` (`test_id`,`language`),
  KEY `test_id` (`test_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` enum('admin','leader','caller','system') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `preferredlanguage` varchar(2) NOT NULL,
  `activated` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `resetrequeststring` varchar(200) DEFAULT NULL,
  `resetrequesttime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `call`
--
ALTER TABLE `call`
  ADD CONSTRAINT `call_ibfk_1` FOREIGN KEY (`participation_id`) REFERENCES `participation` (`id`),
  ADD CONSTRAINT `call_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `call_ibfk_3` FOREIGN KEY (`participation_id`) REFERENCES `participation` (`id`),
  ADD CONSTRAINT `call_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `caller`
--
ALTER TABLE `caller`
  ADD CONSTRAINT `caller_ibfk_1` FOREIGN KEY (`experiment_id`) REFERENCES `experiment` (`id`),
  ADD CONSTRAINT `caller_ibfk_2` FOREIGN KEY (`user_id_caller`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `caller_ibfk_3` FOREIGN KEY (`experiment_id`) REFERENCES `experiment` (`id`),
  ADD CONSTRAINT `caller_ibfk_4` FOREIGN KEY (`user_id_caller`) REFERENCES `user` (`id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`),
  ADD CONSTRAINT `comment_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `dyslexia`
--
ALTER TABLE `dyslexia`
  ADD CONSTRAINT `dyslexia_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`),
  ADD CONSTRAINT `dyslexia_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`);

--
-- Constraints for table `experiment`
--
ALTER TABLE `experiment`
  ADD CONSTRAINT `experiment_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`),
  ADD CONSTRAINT `experiment_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`);

--
-- Constraints for table `impediment`
--
ALTER TABLE `impediment`
  ADD CONSTRAINT `impediment_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`),
  ADD CONSTRAINT `impediment_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`);

--
-- Constraints for table `language`
--
ALTER TABLE `language`
  ADD CONSTRAINT `language_ibfk_1` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`),
  ADD CONSTRAINT `language_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`);

--
-- Constraints for table `participation`
--
ALTER TABLE `participation`
  ADD CONSTRAINT `participation_ibfk_1` FOREIGN KEY (`experiment_id`) REFERENCES `experiment` (`id`),
  ADD CONSTRAINT `participation_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`),
  ADD CONSTRAINT `participation_ibfk_3` FOREIGN KEY (`experiment_id`) REFERENCES `experiment` (`id`),
  ADD CONSTRAINT `participation_ibfk_4` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`);

--
-- Constraints for table `percentile`
--
ALTER TABLE `percentile`
  ADD CONSTRAINT `percentile_ibfk_1` FOREIGN KEY (`testcat_id`) REFERENCES `testcat` (`id`),
  ADD CONSTRAINT `percentile_ibfk_2` FOREIGN KEY (`testcat_id`) REFERENCES `testcat` (`id`);

--
-- Constraints for table `relation`
--
ALTER TABLE `relation`
  ADD CONSTRAINT `relation_ibfk_1` FOREIGN KEY (`experiment_id`) REFERENCES `experiment` (`id`),
  ADD CONSTRAINT `relation_ibfk_2` FOREIGN KEY (`rel_exp_id`) REFERENCES `experiment` (`id`),
  ADD CONSTRAINT `relation_ibfk_3` FOREIGN KEY (`experiment_id`) REFERENCES `experiment` (`id`),
  ADD CONSTRAINT `relation_ibfk_4` FOREIGN KEY (`rel_exp_id`) REFERENCES `experiment` (`id`);

--
-- Constraints for table `result`
--
ALTER TABLE `result`
  ADD CONSTRAINT `result_ibfk_1` FOREIGN KEY (`participation_id`) REFERENCES `participation` (`id`),
  ADD CONSTRAINT `result_ibfk_2` FOREIGN KEY (`participation_id`) REFERENCES `participation` (`id`);

--
-- Constraints for table `score`
--
ALTER TABLE `score`
  ADD CONSTRAINT `score_ibfk_2` FOREIGN KEY (`testcat_id`) REFERENCES `testcat` (`id`),
  ADD CONSTRAINT `score_ibfk_3` FOREIGN KEY (`testinvite_id`) REFERENCES `testinvite` (`id`),
  ADD CONSTRAINT `score_ibfk_5` FOREIGN KEY (`testcat_id`) REFERENCES `testcat` (`id`),
  ADD CONSTRAINT `score_ibfk_6` FOREIGN KEY (`testinvite_id`) REFERENCES `testinvite` (`id`);

--
-- Constraints for table `testcat`
--
ALTER TABLE `testcat`
  ADD CONSTRAINT `testcat_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`),
  ADD CONSTRAINT `testcat_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `testcat` (`id`),
  ADD CONSTRAINT `testcat_ibfk_4` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`),
  ADD CONSTRAINT `testcat_ibfk_5` FOREIGN KEY (`parent_id`) REFERENCES `testcat` (`id`);

--
-- Constraints for table `testinvite`
--
ALTER TABLE `testinvite`
  ADD CONSTRAINT `testinvite_ibfk_2` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`),
  ADD CONSTRAINT `testinvite_ibfk_3` FOREIGN KEY (`testsurvey_id`) REFERENCES `testsurvey` (`id`),
  ADD CONSTRAINT `testinvite_ibfk_4` FOREIGN KEY (`participant_id`) REFERENCES `participant` (`id`),
  ADD CONSTRAINT `testinvite_ibfk_5` FOREIGN KEY (`testsurvey_id`) REFERENCES `testsurvey` (`id`);

--
-- Constraints for table `testsurvey`
--
ALTER TABLE `testsurvey`
  ADD CONSTRAINT `testsurvey_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`),
  ADD CONSTRAINT `testsurvey_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`);

--
-- Constraints for table `testtemplate`
--
ALTER TABLE `testtemplate`
  ADD CONSTRAINT `testtemplate_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`),
  ADD CONSTRAINT `testtemplate_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`);
