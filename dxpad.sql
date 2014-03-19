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
) ENGINE=InnoDB AUTO_INCREMENT=11841 DEFAULT CHARSET=latin1;
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
INSERT INTO `projects` VALUES (1,'OZ2014',NULL,'do7ale','Egowaloho988','d2bd221f97e3f4c548c3aa154d9e5f2f','1392495943',1395147761,'jo51is'),(2,'testprojekt',NULL,'','1234','',NULL,1394538797,'');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=365 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_bands_projects`
--

LOCK TABLES `rel_bands_projects` WRITE;
/*!40000 ALTER TABLE `rel_bands_projects` DISABLE KEYS */;
INSERT INTO `rel_bands_projects` VALUES (2,2,115),(0,1,291),(0,2,292),(0,3,293),(0,4,294),(0,5,295),(0,6,296),(0,7,297),(1,1,355),(1,2,356),(1,3,357),(1,4,358),(1,5,359),(1,6,360),(1,7,361),(1,8,362),(1,9,363),(1,10,364);
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
) ENGINE=InnoDB AUTO_INCREMENT=302 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_modes_projects`
--

LOCK TABLES `rel_modes_projects` WRITE;
/*!40000 ALTER TABLE `rel_modes_projects` DISABLE KEYS */;
INSERT INTO `rel_modes_projects` VALUES (2,1,158),(2,2,159),(2,3,160),(2,4,161),(0,1,262),(0,2,263),(0,3,264),(0,4,265),(1,1,298),(1,2,299),(1,3,300),(1,4,301);
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rel_operators_projects`
--

LOCK TABLES `rel_operators_projects` WRITE;
/*!40000 ALTER TABLE `rel_operators_projects` DISABLE KEYS */;
INSERT INTO `rel_operators_projects` VALUES (2,5,39,NULL),(0,1,113,NULL),(0,3,114,NULL),(0,4,115,NULL),(0,6,116,NULL),(1,1,149,1),(1,3,150,1),(1,4,151,1),(1,6,152,NULL);
/*!40000 ALTER TABLE `rel_operators_projects` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `qrz_cache`;
CREATE TABLE `qrz_cache` (
  `call` varchar(20) NOT NULL,
  `timestamp` int(10) NOT NULL,
  `fname` varchar(20),
  `addr1` varchar(40),
  `addr2` varchar(40),
  `url` varchar(40),
  `grid` varchar(6),
  `qslmgr` varchar(60),
  PRIMARY KEY (`call`)
);

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-03-18 14:09:11
