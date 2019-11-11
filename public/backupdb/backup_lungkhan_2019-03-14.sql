-- MySQL dump 10.13  Distrib 5.5.57, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: lungkhan
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'seree  55','boy@gpsn.co.th','35a99229ee798125fd2126c1335f7a56','08ad70a13b9ad75a17cad68f10423688.png','1',1,'2018-03-30 14:13:44','2018-04-08 15:02:44'),(2,'tony','tony@gpsn.co.th','73afb59a95e157f94f4e5fb0303947ea','e5277ba3b6071c555ee7d210ec271ad0.png','1',1,'2018-04-08 21:54:00','2018-04-08 15:04:52'),(3,'admin','admin@gpsn.co.th','1616a5873ac12c317d9c5c2e06b35470','6b764880902121fdaa0c02976190240f.jpg','1',1,'2019-03-13 20:16:00','2019-03-13 13:16:05');
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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Memorial Funeral','งานศพที่ระลึก','0468240c3272445df247c7fddf33f873.jpeg','2018-04-06 15:42:00','2019-03-13 20:00:00',1,5),(2,'Wedding Gift','ของขวัญแต่งงาน','982d52820f4298b447c99e15fcf34acd.jpeg','2018-04-06 15:43:00','2019-03-13 20:00:00',1,8),(3,'Baby Registry','Registry เด็ก','928d1048b9afef1bea6b97ae1f0c052f.jpeg','2019-03-13 14:02:00','2019-03-13 20:00:00',1,4),(4,'Birthday Gift','ของขวัญวันเกิด','d7785b93247ae4f428f43e3d39b82c65.jpeg','2019-03-13 19:34:00','2019-03-13 20:00:00',1,7),(5,'Share travel expenses','ค่าใช้จ่ายในการเดินทางแบ่งปัน','2913d047b0623efb9e0563d9e1edab09.jpeg','2019-03-13 19:39:00','2019-03-13 20:00:00',1,3),(6,'Group gift','ของที่ระลึกของกลุ่ม','1beca09a926d9671ddcbea52cfaa0c0f.jpeg','2019-03-13 19:40:00','2019-03-13 20:00:00',1,6),(7,'Farewell','การอำลา','440db15061b2a9b70fc882c389dc1c3c.jpeg','2019-03-13 19:41:00','2019-03-13 20:00:00',1,2),(8,'Project & Causes','โครงการและxxxx','','2019-03-13 19:43:00','2019-03-13 20:00:00',1,1);
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
-- Table structure for table `moneybox`
--

DROP TABLE IF EXISTS `moneybox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moneybox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `organizer` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `description` text NOT NULL,
  `cover` varchar(50) NOT NULL,
  `createdate` datetime NOT NULL,
  `  lastupdate` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `subcate_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `moneybox`
--

LOCK TABLES `moneybox` WRITE;
/*!40000 ALTER TABLE `moneybox` DISABLE KEYS */;
/*!40000 ALTER TABLE `moneybox` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategory`
--

LOCK TABLES `subcategory` WRITE;
/*!40000 ALTER TABLE `subcategory` DISABLE KEYS */;
INSERT INTO `subcategory` VALUES (1,8,'Organization','องค์กร','8d4545ade83ef67508ad6db2e0151028.jpeg','2018-04-23 18:34:00','2019-03-13 20:11:00',1,1),(2,8,'Emergency','กรณีฉุกเฉิน','1b7b4e5028fc2a6d8591e76f6b244f23.jpeg','2019-03-13 20:02:00','2019-03-13 20:11:00',1,6),(3,8,'Sport','กีฬา','86075573c099702999205200b9f8f887.jpeg','2019-03-13 20:03:00','2019-03-13 20:11:00',1,5),(4,8,'Education','การศึกษา','f1aefc8781fb4b5c0df3276c142eab1e.jpeg','2019-03-13 20:05:00','2019-03-13 20:11:00',1,4),(5,8,'Animals','สัตว์','d907778211a09c23a15b2b521edcbcac.jpeg','2019-03-13 20:05:00','2019-03-13 20:11:00',1,3),(6,8,'Art & Culture','ศิลปะและวัฒนธรรม','7696c2b8eb573bcef793c2cd59d8d037.jpeg','2019-03-13 20:10:00','2019-03-13 20:11:00',1,2),(7,8,'Community','ชุมชน','25e9394b5b1e462a7c6084774c44930f.jpeg','2019-03-13 20:12:00','2019-03-13 13:12:18',1,1),(8,8,'Environment','สิ่งแวดล้อม','b0ce0b873f3024edd6caed308cc9c41a.jpeg','2019-03-13 20:13:00','2019-03-13 13:13:05',1,1);
/*!40000 ALTER TABLE `subcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `facebook_id` varchar(20) NOT NULL DEFAULT '0',
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (5,'Sylvain','svanou@gmail.com','d104dd81ab79533a097300fd36caf9d2','2019-03-12 16:34:00','2019-03-12 09:34:49','0','',''),(4,'Sylvian','kkk@gmail.com','be0c1c7982c6b22842927cbbeeaa6b32','2019-03-12 16:24:00','2019-03-12 09:24:20','0','',''),(3,'boy gpsn','boy@gpsn.co.th','ec740ddb4811796b31227c13eb1cbd93','2019-03-12 14:45:00','2019-03-13 00:59:00','0','','MjAxOS0wMy0xNCAxMjo1ODo1MiZib3lAZ3Bzbi5jby50aAlungkhanlungkhan'),(2,'เสรี ใสสุขสม','boysere323@gmail.com','f784dda88c7662f5c4c2f4164418d3e9','2019-03-12 14:16:00','2019-03-12 07:16:48','0','',''),(1,'seree saikuea','boysere32@gmail.com','330019c86222a5de884f91e6c7626c66','2019-03-12 13:50:00','2019-03-12 06:50:11','0','',''),(6,'boy gpsn','boy55@gpsn.co.th','4f27ed9ed5b87eead4965b9b088d3e02','2019-03-12 22:56:00','2019-03-12 15:56:49','0','',''),(7,'Sylvain','sylvain@gpsn.co.th','d728f03157a2b36a46801fcd6e25014a','2019-03-13 21:49:00','2019-03-13 21:54:00','0','','MjAxOS0wMy0xNCAwOTo1NDowMyZzeWx2YWluQGdwc24uY28udGglungkhan'),(8,'seree saikuea','boy555@gpsn.co.th','3e897e70488cb6ea3c3b107f35954660','2019-03-14 10:48:00','2019-03-14 10:48:00','0','','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-14 10:44:10
