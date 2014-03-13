-- MySQL dump 10.13  Distrib 5.5.34, for FreeBSD9.2 (amd64)
--
-- Host: localhost    Database: dxpad
-- ------------------------------------------------------
-- Server version	5.5.34-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bands`
--

DROP TABLE IF EXISTS `bands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bands` (
  `band_id` int(4) NOT NULL AUTO_INCREMENT,
  `band_name` varchar(20) NOT NULL,
  `band_start` int(20) NOT NULL,
  `band_end` int(20) NOT NULL,
  PRIMARY KEY (`band_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `log_id` int(10) NOT NULL AUTO_INCREMENT,
  `log_call` varchar(20) NOT NULL,
  `log_freq` varchar(20) NOT NULL,
  `log_time` int(10) NOT NULL,
  `log_rst_rx_0` int(1) DEFAULT NULL,
  `log_rst_rx_1` int(1) DEFAULT NULL,
  `log_rst_rx_2` int(1) DEFAULT NULL,
  `log_rst_tx_0` int(1) DEFAULT NULL,
  `log_rst_tx_1` int(1) DEFAULT NULL,
  `log_rst_tx_2` int(1) DEFAULT NULL,
  `log_dok` varchar(10) DEFAULT NULL,
  `log_loc` varchar(10) DEFAULT NULL,
  `log_qth` varchar(30) DEFAULT NULL,
  `log_name` varchar(30) DEFAULT NULL,
  `log_notes` varchar(200) DEFAULT NULL,
  `log_manager` varchar(200) DEFAULT NULL,
  `project_id` int(4) NOT NULL,
  `operator_id` int(4) NOT NULL,
  `mode_id` int(4) NOT NULL,
  `band_id` int(4) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=268 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modes`
--

DROP TABLE IF EXISTS `modes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modes` (
  `mode_id` int(4) NOT NULL AUTO_INCREMENT,
  `mode_name` varchar(8) DEFAULT NULL,
  `mode_digital` int(1) DEFAULT NULL,
  PRIMARY KEY (`mode_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `operators`
--

DROP TABLE IF EXISTS `operators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operators` (
  `operator_call` varchar(10) NOT NULL,
  `operator_name` varchar(20) DEFAULT NULL,
  `operator_id` int(4) NOT NULL AUTO_INCREMENT,
  `operator_mail` varchar(20) DEFAULT NULL,
  `operator_pass` varchar(40) DEFAULT NULL,
  `operator_role` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`operator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `project_id` int(4) NOT NULL AUTO_INCREMENT,
  `project_short_name` varchar(50) NOT NULL,
  `project_long_name` varchar(200) DEFAULT NULL,
  `project_qrz_user` varchar(100) DEFAULT NULL,
  `project_qrz_pass` varchar(100) DEFAULT NULL,
  `project_qrz_sess` varchar(100) DEFAULT NULL,
  `project_qrz_sess_valid_until` varchar(10) DEFAULT NULL,
  `project_qrz_sess_created` int(10) DEFAULT NULL,
  `project_locator` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_bands_projects`
--

DROP TABLE IF EXISTS `rel_bands_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rel_bands_projects` (
  `project_id` int(4) NOT NULL,
  `band_id` int(4) NOT NULL,
  `id` int(4) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=347 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_modes_projects`
--

DROP TABLE IF EXISTS `rel_modes_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rel_modes_projects` (
  `project_id` int(4) NOT NULL,
  `mode_id` int(4) NOT NULL,
  `id` int(4) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=294 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_operators_projects`
--

DROP TABLE IF EXISTS `rel_operators_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rel_operators_projects` (
  `project_id` int(4) NOT NULL,
  `operator_id` int(4) NOT NULL,
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `setting_log_time_auto` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-03-12 17:10:08
