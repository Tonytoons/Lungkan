-- MySQL dump 10.13  Distrib 5.5.57, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: c9
-- ------------------------------------------------------
-- Server version	5.5.57-0ubuntu0.14.04.1

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `level` int(1) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'seree  55','boy@gpsn.co.th','35a99229ee798125fd2126c1335f7a56','08ad70a13b9ad75a17cad68f10423688.png','1',1,'2018-03-30 14:13:44','2018-04-08 15:02:44'),(2,'tony','tony@gpsn.co.th','73afb59a95e157f94f4e5fb0303947ea','e5277ba3b6071c555ee7d210ec271ad0.png','1',1,'2018-04-08 21:54:00','2018-04-08 15:04:52');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `image` varchar(50) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'banner 1','banner 1','c93b031502910fe033cd7963ae3d5334.jpg','2018-04-08 21:46:00','2018-04-08 15:07:11',0,1),(2,'banner 2','banner 2','f3b7955f3a326dc2f6841a985283cfa6.jpg','2018-04-08 21:50:00','2018-04-08 15:07:21',0,2),(3,'banner 3','banner 3','c70a35a85ae5699501446613312c59c6.jpg','2018-04-08 22:06:00','2018-04-08 15:07:20',0,3),(4,'banner 4','banner 4','0be62f21b5f0adbaedff451880c1c8e5.jpg','2018-04-08 22:06:00','2018-04-08 15:07:21',0,4);
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_en` varchar(100) NOT NULL,
  `name_th` varchar(100) NOT NULL,
  `image` varchar(50) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'test en 1','test th 1','02cb678848af553e4a1ee9c82fed4eca.jpg','2018-04-06 15:42:00','2018-04-06 09:51:48',1,2),(2,'test en 2','test th 2','43bf4761e17427996a5f2ea23660f37f.jpg','2018-04-06 15:43:00','2018-04-06 09:52:03',1,1);
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `page` varchar(20) NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` VALUES (1,'c5c4bb7a8648a58cea052d1fe2c77e15.jpg','news','2018-04-07 06:47:00'),(2,'eb5ba47b2337ac3d03a038f2ff95e1ca.jpg','news','2018-04-07 06:56:00'),(3,'142786d27d7e61118b7ff707184101b3.jpg','news','2018-04-07 06:57:00'),(4,'9fc26cddafe8d17647c71bada2432cbf.jpg','news','2018-04-07 06:57:00'),(5,'1f4288dbfc7ee164f3aaec521165b6d4.jpg','news','2018-04-07 06:57:00');
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL,
  `name_en` varchar(100) NOT NULL,
  `name_th` varchar(100) NOT NULL,
  `keywords_en` varchar(255) NOT NULL,
  `keywords_th` varchar(255) NOT NULL,
  `description_short_en` varchar(255) NOT NULL,
  `description_short_th` varchar(255) NOT NULL,
  `description_en` text NOT NULL,
  `description_th` text NOT NULL,
  `image` varchar(50) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '1',
  `views` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,1,'news en','news th','news en','dasdas  news th','news en','dsadsad  news th','dsafasf\r\nfasfasf\r\n\r\nfsaffsaf\r\n\r\nfasfasf\r\n\r\nfsafsffadas','saassajja;\r\n\r\nsdadsda\r\nsdasad\r\n\r\nsadsa\r\n\r\nsaddasd\r\n\r\nasddaddas\r\n\r\ndasdasdd','491b34e2b101e8a56d6fb5839ea337fa.jpg','2018-04-06 17:00:00','2018-04-10 04:27:53',0,2,0),(2,2,'test en 2','test th 2','','','','','sdadasf\r\n\r\nfsafasffa\r\nfafaff\r\n\r\nfasfaf\r\n\r\nfasfaf','assaffsa\r\nfavx\r\nsa\r\nsac\r\nascsacasc\r\ncacac','73f8297771ee5ebca31d99440c31259d.jpg','2018-04-06 17:39:00','2018-04-10 04:25:55',1,1,0),(3,2,'safafa','fafaffa','fafaf','fsafsafas','gaga','fasfsag','aggaggag','agsgagag','e89243fca79e4d24f623e08b3a63b274.jpg','2018-04-08 22:08:00','2018-04-08 15:08:02',1,1,0),(4,1,'asvsgrsfg','dxdsdd','zgzggzx','vczfZdsz','gzzggg','zxvzfzdszdcz','gzgzgzg\r\ns\r\n\r\nsddsa\r\nsdadsa\r\nsaddasd\r\n\r\nsaddasdas','zvbasdcxz\r\n\r\ndsadasd\r\ndadasd\r\n\r\ndasdasd\r\n\r\ndsadasd','ed9ab481b57c2058ff01178f737ac4bf.jpg','2018-04-08 22:08:00','2018-04-10 04:25:11',1,1,0),(5,1,'saasffsa','agaggaggag','sSs','sAS','ASsS','sSas','{\"text_1\":\"fsafagagga\",\"image_2\":\"d05dc458e3e92c04dcf589ddc2da8ad9.png\",\"image_3\":\"8faf19d68fde1f2a7f1d8a705d969f92.png\",\"text_4\":\"SasADFfDafsafafas\",\"text_5\":\"dsafsagagagagag\"}','{\"image_1\":\"bb4dc14497668a4cc4f8c70e3231cfca.png\"}','892dd5dc3e8db933166395187262b5d1.jpg','2018-04-23 14:04:00','2018-04-23 07:04:55',0,1,0),(6,2,'sadsdasdsad','saxzzdazdzdzd','dadasdadsadas','zffzdfvxvfdfzc','dafasfasfafas','vzfzdfsdfdcdsds','{\"text_1\":\"sadasdasdasd\",\"image_2\":\"96629653979ad481518ea54e26bd56b7.png\"}','{\"text_1\":\"fdsfzzfsdfsdfs\",\"image_2\":\"92393f400d7766f2654b140260023067.png\"}','71e115be1a2bccac32d68e535952270f.jpg','2018-04-23 15:54:00','2018-04-23 09:29:10',0,1,0);
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategory`
--

DROP TABLE IF EXISTS `subcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL DEFAULT '1',
  `name_en` varchar(100) NOT NULL,
  `name_th` varchar(100) NOT NULL,
  `image` varchar(50) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategory`
--

LOCK TABLES `subcategory` WRITE;
/*!40000 ALTER TABLE `subcategory` DISABLE KEYS */;
INSERT INTO `subcategory` VALUES (1,2,'dasdas','dasdasd','','2018-04-23 18:34:00','2018-04-23 11:43:14',0,0);
/*!40000 ALTER TABLE `subcategory` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-27 15:33:41
