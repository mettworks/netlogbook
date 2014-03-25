-- MySQL dump 10.13  Distrib 5.5.36, for FreeBSD9.2 (amd64)
--
-- Host: localhost    Database: dxpad
-- ------------------------------------------------------
-- Server version	5.5.36-log

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bands`
--

LOCK TABLES `bands` WRITE;
/*!40000 ALTER TABLE `bands` DISABLE KEYS */;
INSERT INTO `bands` VALUES (1,'2m',144000,146000),(2,'70cm',430000,440000),(3,'23cm',1240000,1300000),(4,'80m',3500,3800),(5,'40m',7000,7200),(6,'20m',14000,14350),(7,'15m',21000,21450),(8,'17m',18068,18168),(9,'10m',28000,29700),(10,'30m',10100,10150);
/*!40000 ALTER TABLE `bands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cronjob`
--

DROP TABLE IF EXISTS `cronjob`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cronjob` (
  `lastrun` int(10) DEFAULT NULL,
  `id` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cronjob`
--

LOCK TABLES `cronjob` WRITE;
/*!40000 ALTER TABLE `cronjob` DISABLE KEYS */;
INSERT INTO `cronjob` VALUES (1395163594,0);
/*!40000 ALTER TABLE `cronjob` ENABLE KEYS */;
UNLOCK TABLES;

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
  `time` int(10) NOT NULL,
  `typ` int(1) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13874 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modes`
--

LOCK TABLES `modes` WRITE;
/*!40000 ALTER TABLE `modes` DISABLE KEYS */;
INSERT INTO `modes` VALUES (1,'AM',0),(2,'FM',0),(3,'SSB',0),(4,'CW',1),(5,'PSK31',1),(6,'SSTV',0),(7,'RTTY',1),(8,'HELL',1);
/*!40000 ALTER TABLE `modes` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `operators`
--

LOCK TABLES `operators` WRITE;
/*!40000 ALTER TABLE `operators` DISABLE KEYS */;
INSERT INTO `operators` VALUES ('DO7ALE',NULL,1,'do7ale@do7ale.de','3e19be3bb0f081c9754a52aec3edce39','0'),('DC7VS',NULL,3,'christian@dc7vs.de','fde68a79d7ba89c4aa145190784d78c0','0'),('DG2RON',NULL,4,'dg2ron@yahoo.de','7b347eae9a00cc7c4accc2e668d0a906','0'),('TESTOP',NULL,6,'abcdefg@blacktux.de','3e19be3bb0f081c9754a52aec3edce39','0');
/*!40000 ALTER TABLE `operators` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'OZ2014',NULL,'do7ale','Egowaloho988','95998e61a918d72dffbd13254d183484','1392495943',1395759263,'jo51is'),(2,'testprojekt',NULL,'','1234','',NULL,1394538797,'');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qrz_cache`
--

DROP TABLE IF EXISTS `qrz_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qrz_cache` (
  `qrz_call` varchar(20) NOT NULL,
  `timestamp` int(10) NOT NULL,
  `fname` varchar(20) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `addr1` varchar(40) DEFAULT NULL,
  `addr2` varchar(40) DEFAULT NULL,
  `url` varchar(40) DEFAULT NULL,
  `grid` varchar(6) DEFAULT NULL,
  `qslmgr` varchar(60) DEFAULT NULL,
  `error` varchar(60) DEFAULT NULL,
  `image` varchar(25) DEFAULT NULL,
  `imageheight` int(10) DEFAULT NULL,
  `imagewidth` int(10) DEFAULT NULL,
  `imageurl` varchar(200) DEFAULT NULL,
  `imagestatus` int(1) DEFAULT NULL,
  `imagesize` int(10) DEFAULT NULL,
  `qrz_cache_id` int(12) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`qrz_cache_id`)
) ENGINE=InnoDB AUTO_INCREMENT=414 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qrz_cache`
--

LOCK TABLES `qrz_cache` WRITE;
/*!40000 ALTER TABLE `qrz_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `qrz_cache` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=435 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_bands_projects`
--

LOCK TABLES `rel_bands_projects` WRITE;
/*!40000 ALTER TABLE `rel_bands_projects` DISABLE KEYS */;
INSERT INTO `rel_bands_projects` VALUES (2,2,115),(0,1,291),(0,2,292),(0,3,293),(0,4,294),(0,5,295),(0,6,296),(0,7,297),(1,1,425),(1,2,426),(1,3,427),(1,4,428),(1,5,429),(1,6,430),(1,7,431),(1,8,432),(1,9,433),(1,10,434);
/*!40000 ALTER TABLE `rel_bands_projects` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=331 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_modes_projects`
--

LOCK TABLES `rel_modes_projects` WRITE;
/*!40000 ALTER TABLE `rel_modes_projects` DISABLE KEYS */;
INSERT INTO `rel_modes_projects` VALUES (1,1,323),(1,2,324),(1,3,325),(1,4,326),(1,5,327),(1,6,328),(1,7,329),(1,8,330);
/*!40000 ALTER TABLE `rel_modes_projects` ENABLE KEYS */;
UNLOCK TABLES;

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
  `setting_incrementell_export_complete` int(10) DEFAULT NULL,
  `setting_incrementell_export_operator` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_operators_projects`
--

LOCK TABLES `rel_operators_projects` WRITE;
/*!40000 ALTER TABLE `rel_operators_projects` DISABLE KEYS */;
INSERT INTO `rel_operators_projects` VALUES (1,1,177,NULL,NULL,NULL),(1,3,178,1,NULL,NULL),(1,4,179,1,NULL,NULL),(1,6,180,NULL,NULL,NULL);
/*!40000 ALTER TABLE `rel_operators_projects` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-03-25 16:19:03
