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
INSERT INTO `category` VALUES (1,'Memorial Funeral','งานศพที่ระลึก','0468240c3272445df247c7fddf33f873.jpeg','2018-04-06 15:42:00','2019-03-16 01:19:00',1,5),(2,'Wedding Gift','ของขวัญแต่งงาน','982d52820f4298b447c99e15fcf34acd.jpeg','2018-04-06 15:43:00','2019-03-16 01:19:00',1,8),(3,'Baby Registry','Registry เด็ก','928d1048b9afef1bea6b97ae1f0c052f.jpeg','2019-03-13 14:02:00','2019-03-16 01:19:00',1,4),(4,'Birthday Gift','ของขวัญวันเกิด','d7785b93247ae4f428f43e3d39b82c65.jpeg','2019-03-13 19:34:00','2019-03-16 01:19:00',1,7),(5,'Share travel expenses','ค่าใช้จ่ายในการเดินทางแบ่งปัน','2913d047b0623efb9e0563d9e1edab09.jpeg','2019-03-13 19:39:00','2019-03-16 01:19:00',1,3),(6,'Group gift','ของที่ระลึกของกลุ่ม','1beca09a926d9671ddcbea52cfaa0c0f.jpeg','2019-03-13 19:40:00','2019-03-16 01:19:00',1,6),(7,'Farewell','การอำลา','440db15061b2a9b70fc882c389dc1c3c.jpeg','2019-03-13 19:41:00','2019-03-16 01:19:00',1,1),(8,'Project & Causes','โครงการและxxxx','','2019-03-13 19:43:00','2019-03-16 01:19:00',1,2);
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_invite`
--

DROP TABLE IF EXISTS `email_invite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_invite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `pot_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdate` datetime NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `remind` tinyint(2) NOT NULL DEFAULT '0',
  `invite_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_invite`
--

LOCK TABLES `email_invite` WRITE;
/*!40000 ALTER TABLE `email_invite` DISABLE KEYS */;
INSERT INTO `email_invite` VALUES (13,'b1audio2018@gmail.com','Test 222',1,8,'2019-03-26 20:08:00','2019-03-27 22:16:00','1',1,'2019-03-27 22:16:58'),(14,'boy@gpsn.co.th','Test 222',1,8,'2019-03-26 20:08:00','2019-03-27 22:16:00','1',1,'2019-03-27 22:16:59'),(20,'boyatomic32@gmail.com','Test 555ssss',1,8,'2019-03-27 22:03:00','2019-03-27 22:16:00','1',0,'2019-03-27 22:17:00'),(22,'sylvain@wezenit.com','Looking forward seeing you at the wedding :)',7,5,'2019-03-28 19:04:00','2019-03-28 19:08:00','1',1,'2019-03-28 19:08:47'),(23,'svanou@gmail.com','Help us get a gift for tony',18,5,'2019-04-03 15:17:00','2019-04-03 15:17:00','1',1,'2019-04-03 15:17:00'),(24,'sylvain@geomail.io','Help us get a gift for tony',18,5,'2019-04-03 15:17:00','2019-04-03 15:17:00','1',1,'2019-04-03 15:17:00');
/*!40000 ALTER TABLE `email_invite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `page` varchar(20) NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` VALUES (6,8,'0e3ece330aa6903ca584e11593d356c5.jpg','general','2019-04-05 19:59:00'),(7,8,'86e9eac2c056b172c60bf1057f31b442.jpg','general','2019-04-05 20:02:00'),(8,8,'5ee8313054f382a54f5974d2756cf455.jpg','general','2019-04-05 20:03:00');
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `moneypot`
--

DROP TABLE IF EXISTS `moneypot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moneypot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `organizer` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `description` text NOT NULL,
  `image` varchar(50) NOT NULL,
  `thumb` varchar(50) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `subcate_id` int(11) NOT NULL,
  `setting_type_participation` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1= Open, 2= Suggested, 3 =  Fixed',
  `setting_total_funds` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 =Show,  2= Hide',
  `setting_deadline` date NOT NULL COMMENT 'Choose a deadline',
  `setting_goal` varchar(20) NOT NULL DEFAULT '0' COMMENT 'Set a goal',
  `setting_open_invite` enum('0','1') NOT NULL DEFAULT '0',
  `setting_hide_participarts_list` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1= active, 2= close',
  `total` varchar(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `moneypot`
--

LOCK TABLES `moneypot` WRITE;
/*!40000 ALTER TABLE `moneypot` DISABLE KEYS */;
INSERT INTO `moneypot` VALUES (11,'Salami is born','Salami',3,'2019-03-29','<p>Our baby is born on the 28th march :)&nbsp;</p>','1f08c4b9f0c6254336d402981646d4ce.png','359bad1033e24836428a92d9dc452232.png','2019-03-28 21:28:00','2019-03-29 16:56:00',5,0,'1','1','0000-00-00','20000','0','0','1','0'),(4,'Test','Sylvain',4,'2019-03-31','<p>Goooo</p>','b53c77d0b5c3319721616dd35b931f73.jpg','','2019-03-23 14:09:00','2019-03-23 14:09:00',5,0,'1','1','0000-00-00','0','0','0','1','0'),(13,'Celebrate Loy Kratong','Pi Krapom',5,'2019-04-18','<p>Let\'s split the bill guys :)&nbsp;</p>','28cc3baf6daee81f9e788587a68ef71a.png','d1086aed73153200830b1f88b3159676.png','2019-03-29 21:22:00','2019-03-30 13:56:00',5,0,'1','1','0000-00-00','0','0','0','1','0'),(10,'Test','Boy ',8,'2019-04-30','<p>Please help !&nbsp;</p>','7534e0bfeccc8955da5e0a9a14f96335.png','8aa0f64bdcebafdd26fd2aaf6b88eacf.png','2019-03-28 19:49:00','2019-03-28 21:05:00',5,4,'1','1','0000-00-00','0','0','1','1','0'),(5,'Help me walk',' Tylian baudelot',8,'2019-04-30','<p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">Tylian est un petit garçon né à 7mois de grossesse suite à sa naissance, il est malheureusement atteint de paralysie cérébrale ou imc&nbsp; qui lui provoque une tétraparesie spastique en gros c est comme si nous avions en permanence des crampes. Tylian a maintenant 5ans et demi c est un petit garçon souriant coquin qui adore faire des blagues mais il ne marche pas plus que quelques pas, il peux faire 1h de deambulateur sans risquer d avoir des crampes dans la soirée la majeure partie du temps il est en fauteuil roulant .&nbsp;</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">Après plusieurs traitements qui malheureusement n\'ont qu\'un effet provisoire tylian doit rencontrer un &nbsp;neurochirurgien en allemagne pour voir si il serait éligible à une opération nommée rhizotomie dorsale sélective qui pourrait justement l aider,&nbsp;lui éviter les déformations liées à la maladie et améliorer sa condition de vie .</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">&nbsp;</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">Édit. Nous avons rencontrer les chirurgiens&nbsp; et malheureusement se ne sera pas 1 mais 3 opérations que tylian devra avoir car la spasticité à déjà commencé à déformer ses jambes .</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">1- achilleoténotomie incision du tendon d achille et au niveau du genou</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">2- ils placeront une vis dans les 2 genoux afin de bloquer sa croissance et de redresser son tibia qui se déforme .6mois après ablation des vis</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">3- la rhizotomie dorsale sélective qui consiste à ouvrir la colonne sur 1 vertèbre puis à&nbsp;separer les nerfs moteurs et sensitif et sélectionnés les nerfs sensitif qui sont atteint de spasticité pour les coupés.&nbsp;</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">&nbsp;</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">Tous ceci coûtera environ 50000e logement compris puis il y aura tout ce qui est soins .</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">En attendant nous avons reçu la première date d opération de tylian se sera le 7 mai cela approche a grand pas et il faut avoir payé avant le 7 avril , il nous reste donc mois de 2mois pour récolter 15000e ( comprenant logement et operation) plus 5000e de réserve car il est stipulé qu il pourrait nous demander plus si il rencontrait un soucis soit 20000e pour les 2 premières operations s en suivra l ablation du matériel 6mois plus tard et 6 mois après la grosse intervention qui durera plus de 4h30&nbsp; .</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">&nbsp;</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">Tylian et nous même comptons sur vous pour l aider à avoir une vie meilleure .</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">C est un petit loulou qui a la joie de vivre.</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">&nbsp;</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">&nbsp;</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">Nous faisons donc appel à votre générosité afin de pouvoir financer ces premieres opérations pour notre loulou.</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">Merci beaucoup.</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">&nbsp;</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">Ici vous pouvez directement et en un clic, participer à cette cagnotte.</p><ul style=\"outline: 0px; margin-right: 10px; margin-bottom: 10px; margin-left: 10px; padding-left: 0px; list-style-position: inside; list-style-image: initial; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\"><li style=\"outline: 0px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px;\">Chacun participe du montant qu\'il souhaite.</li><li style=\"outline: 0px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px;\">Tous les paiements sont sécurisés.</li></ul><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">Envie de nous aider à récolter plus de dons ? Partagez cette cagnotte !</p><p style=\"outline: 0px; margin-right: 10px; margin-bottom: 0px; margin-left: 10px; line-height: 20px; text-shadow: rgba(255, 255, 255, 0.76) 0px 1px 0px; max-width: 100%; overflow-wrap: break-word; color: rgb(85, 85, 85); font-family: &quot;PT Sans&quot;, Arial, Helvetica, sans-serif;\">Merci à tous !</p>','3fc1b5bd3e9919d7559f35c1a4939c22.jpg','','2019-03-26 19:20:00','2019-03-28 19:03:00',5,2,'1','1','0000-00-00','0','0','0','1','0'),(14,'Find the bear','Sylvain',8,'2019-04-30','<p>Wanna be</p>','11457a6b5e9785c7e8fc49d4e093ab67.png','e4f55d79b92c8a2706eda99bec44f4bf.png','2019-03-30 14:09:00','2019-03-30 14:09:00',5,2,'1','1','0000-00-00','0','0','0','1','0'),(8,'ทดสอบ test ทดสอบ','ทดสอบ',8,'2019-03-30','<p>fasff fa</p><p>afa fa faff</p><p>fafaf faf faff</p><p>fafdasscac</p><p>dsafasff . faffaf</p><p>faffa faffaaf faffaf</p><p>faffaff faffaff faff</p><p>fafafffa faffar ergfd</p><p>fdhgfjghmjh</p><p>,jhhllhllh</p>','26891dc2de9d981084cfdde61bc2746c.png','6c3ba81becdb3d4c7bc5afd1ee3c8c95.png','2019-03-28 16:42:00','2019-03-29 21:44:00',8,5,'1','1','0000-00-00','0','0','0','1','0'),(9,'dadadas 55555 ','ทดสอบ 5555',8,'2019-05-30','<p>af fasf กดหเกหเ .&nbsp; 555555 55555</p><p>หเกเหกเ เหเหกเเเ 66666 666666 . 66666</p><p>กหดกหหฟก</p><p>กหฟกกหฟ ดกดหดหก</p><p>ดหกดดหด</p><p>ดหดดหด . า่้สาา่ส่า</p><p>่วาสวาวาสวา</p><p>าวสวาสววาฝฝนว</p><p>ำพไๆพหดหกดห</p><p>นรนาส่าสาส่สสสสส่มา่ส่</p><p>าม่าส่สา่้้า้า้่า้่า กฟดฟดดฟ</p><p>กฟหกกฟ ดไำดำไกกอหดหกด</p>','8fedaecc03c525fd69f81c0c5c0887f3.png','6557bac94fe734cedc69ed084472dbd0.png','2019-03-28 16:46:00','2019-04-03 09:17:00',8,4,'1','1','2019-05-31','1000000','0','0','1','5299'),(15,'Find the bear','Sylvain',8,'2019-04-30','<p>Wanna be</p>','2e4d877f5abccead12bd18c931433bd6.png','c7e346e36496d56f95948cdb5bb44da4.png','2019-03-30 14:09:00','2019-03-30 14:09:00',5,2,'1','1','0000-00-00','0','0','0','1','0'),(16,'555','test',8,'2019-04-30','<p>ฟหฟด&nbsp;</p><p>&nbsp;ดฟด ดฟ&nbsp;</p><p>ด ฟดฟ&nbsp;</p><p>&nbsp;ดฟ ฟด ดฟ ดฟ&nbsp;</p><p>ดฟ ดดหดก&nbsp;</p><p>ด หเหเ้ำหดหเ</p>','f5d4573fdbcb35b36a48425c9ce5074e.png','5798db716231510b2b047e0fb5aaa2a2.png','2019-03-30 17:25:00','2019-04-02 17:23:00',1,6,'1','1','0000-00-00','0','0','0','1','1000'),(17,'มูลนิธิสงเคราะห์เด็กอ่อนพญาไท','มูลนิธิสงเคราะห์เด็กอ่อนพญาไท',8,'2019-06-30','<div style=\"color: rgb(51, 51, 51); font-family: Arial, Thonburi; font-size: 20px;\"><span style=\"text-decoration: none; font-size: 14px; margin-right: 100px; text-overflow: ellipsis; display: block; overflow: hidden; white-space: normal; margin-bottom: 10px; word-break: break-word; color: rgb(51, 51, 51); text-indent: 30px;\">ส่งเสริมการปฏิบัติงานสถานสงเคราะห์และคุ้มครองเด็กอ่อน ให้มีสวัสดิภาพทั้งร่างกายและจิตใจ, เผยแพร่ให้ประชาชนได้ทราบและให้ความร่วมมือในการสงเคราะห์ คุ้มครอง พัฒนาเด็กอ่อน ที่ขาดบิดา/มารดา เป็นกำพร้า ถูกทอดทิ้ง, ให้ความร่วมมือกับองค์กรภาคเอกชนอื่น ๆ ที่ทำงานเกี่ยวกับการสงเคราะห์และพัฒนาเด็ก และไม่ดำเนินการเกี่ยวข้องกับการเมือง</span><font color=\"#0079c1\"><span style=\"text-decoration-style: initial; text-decoration-color: initial; font-size: 16px; margin-right: 100px; text-overflow: ellipsis; display: block; overflow: hidden; white-space: nowrap; margin-bottom: 10px; word-break: break-word;\">โครงการบ้านเด็กอ่อน</span></font><a href=\"https://youtu.be/fDph4UWipg4\" target=\"_blank\" class=\"icon video-blue project_vdo\" style=\"text-decoration: none; color: rgb(51, 51, 51); display: inline-block; background-repeat: no-repeat; border: none; background-image: url(&quot;../img/foundation/videoblue.png&quot;); background-size: cover; width: 30px; height: 30px; right: 5px; position: absolute; top: 0px; font-size: 1.4rem;\"></a><a href=\"http://www.phayathaibabieshome.go.th/project/\" target=\"_blank\" class=\"icon web-blue project_web\" style=\"text-decoration: none; color: rgb(51, 51, 51); display: inline-block; background-repeat: no-repeat; border: none; background-image: url(&quot;../img/foundation/webblue.png&quot;); background-size: cover; width: 30px; height: 30px; right: 5rem; position: absolute; top: 0px; font-size: 1.4rem;\"></a></div><div id=\"project-item-detail\" class=\"project-item-detail\" style=\"overflow: hidden; margin-right: 20px;\"><div class=\"text-responsive-ellipsis\" style=\"color: rgb(51, 51, 51); font-family: Arial, Thonburi; font-size: 14px; display: -webkit-box; max-width: 100%; height: 113px; margin: 0px auto; line-height: 1.4; -webkit-line-clamp: 6; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; overflow-wrap: break-word; word-break: break-word;\">ส่งเสริมการปฏิบัติงานสถานสงเคราะห์และคุ้มครองเด็กอ่อน ให้มีสวัสดิภาพทั้งร่างกาย และจิตใจเผยแพร่ให้ประชาชนได้ทราบ และให้ความร่วมมือในการสงเคราะห์ คุ้มครอง พัฒนาเด็กอ่อน ที่ขาดบิดามารดา เป็นกำพร้า ถูกทอดทิ้งให้ความร่วมมือกับองค์กรภาคเอกชนอื่น ๆ ที่ทำงานเกี่ยวกับการสงเคราะห์และพัฒนาเด็ก และไม่ดำเนินการเกี่ยวข้องกับการเมือง</div><div class=\"text-responsive-ellipsis\" style=\"display: -webkit-box; max-width: 100%; height: 113px; margin: 0px auto; line-height: 1.4; -webkit-line-clamp: 6; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; overflow-wrap: break-word; word-break: break-word;\"><font face=\"Arial, Thonburi\"><span style=\"font-size: 14px;\">มูลนิธิสงเคราะห์เด็กอ่อนพญาไท เป็นองค์กรสาธารณกุศลตามประกาศกระทรวงการคลัง&nbsp;</span></font><span style=\"font-size: 14px; font-family: Arial, Thonburi;\">เงินบริจาคสามารถหักลดหย่อนภาษีได้ 02-584-7254,02-584-7255 Fax.02-584-7255 Phayathaibaby@dcy.go.th เลขที่ ๗๘ / ๒๔ หมู่ ๑ ถนนภูมิเวท ตำบลบางตลาด อำเภอปากเกร็ด จังหวัดนนทบุรี ๑๑๑๒๐</span></div></div>','d46f660e26114d4b02e3d8e853a6483b.png','f19bfc2fcad90014bb4577a889d14f01.png','2019-03-30 18:02:00','2019-04-03 14:03:00',8,7,'1','1','0000-00-00','0','0','0','1','658'),(18,'Birthday gift for Tony ','Tony Williams ',4,'2019-04-30','<p>please help us to contribute for tony\'s birthday !</p>','b24e6a787c0374b094e73495882be71c.png','0a841650c89728e99e30d252b4a79123.png','2019-04-01 16:12:00','2019-04-02 21:12:00',5,0,'1','1','0000-00-00','0','0','0','1','0'),(19,'บอย ทดสอบ','ทดสอบ',8,'2019-04-30','test test','0dec77cb85c81006635fc77aee9eef2e.png','726bab088b29e99967d46884b555d184.png','2019-04-03 19:26:00','2019-04-04 11:26:00',8,4,'1','1','0000-00-00','0','0','0','1','5972'),(20,'It\'s a boy ! ','Eric Williams',3,'2019-04-30','<p>Hi,&nbsp;</p><p>Our little boy is born :)&nbsp;</p><p>If you wish to partipate you can do here :)&nbsp;</p>','b3064f5a539c476492dff23c458e8099.png','4acedeb4d038fc127e266fa198536644.png','2019-04-04 17:38:00','2019-04-05 20:12:00',5,0,'1','1','0000-00-00','0','0','0','1','2999'),(21,'ลำโพง orion','ิboy',8,'2019-05-31','<p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\"><strong>ORION</strong>&nbsp;ค่ายเครื่องเสียงระดับแชมพ์ ที่ประสบความสำเร็จมาแล้วมากมาย โดยเฉพาะรายการแข่งขันพลังเสียงระดับโลกต่างๆ ที่เคยคว้าแชมพ์มาครอง ล่าสุดค่ายนี้พร้อมบุกตลาดบ้านเราอีกครั้ง ทั้งสำหรับฟังเน้นเนื้อหา หรือเน้นพลังแบบเปิดโชว์ รวมถึงระดับแข่งขัน อย่างล่าสุดกับซับวูเฟอร์ขนาด&nbsp;10″ ที่ทีมงานนำมาทดสอบกัน</p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\">&nbsp;</p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\"><strong>ลักษณะเด่น</strong></p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\">C10D4 เป็นซับวูเฟอร์ขนาด 10″ แบบวอยศ์คอยล์คู่ มีกำลังขับ 400 วัตต์ ออกแบบมาเพื่อขับเล่นได้หลากหลาย โดยสามารถนำมาต่อร่วมกันหลายตัว ได้ทั้งแบบขนาน และอนุกรม เพื่อรีดเค้นขุมพลังเบสส์ตามต้องการ ค่ายนี้เน้นโครงสร้างที่น้ำหนักเบา แข็งแกร่ง ด้วยโลหะปั๊มขึ้นรูป เจาะช่องถี่ๆ เพื่อให้ได้ขายึดโครงมากๆ มีพับขอบเพิ่มความแข็งแรง ขั้วต่อสายแบบเสียบหรือเชื่อมตะกั่วตามต้องการ ซึ่งในชุดต่อมีสายเชื่อมมาให้ด้วย หากต้องการขับเล่นตัวเดียวในแบบขนาน</p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\">กรวยผลิตจากกระดาษขึ้นรูป ซึ่งจะให้เนื้อหารายละเอียดเสียงเบสส์ดีกว่าแบบปั๊มขึ้นรูป สังเกตได้จากผิวด้านใต้ที่เป็นรอยตะปุ่มตะป่ำรูปทรงอิสระตามธรรมชาติ ส่วนด้านล่างเป็นลายเม็ดสกรีน ดัสต์แคพไฟเบอร์ขนาดใหญ่ มีโลโกตัวนูน เซอร์ราวน์ดยางโดนัท ช่วยให้ช่วงชักลึกมากขึ้น สไปเดอร์ลอนขนาดกลางแข็ง วิศวกรค่ายนี้เน้นรายละเอียดในการเดินสายลำโพงไปยังวอยศ์คอยล์โดยเดินแนบไปใต้กรวยซึ่งช่วงจากขั้วต่อสายถึงกลางกรวยมีแผ่นฟองน้ำบางๆ รอง เพื่อป้องกันเอฟเฟคท์ต่างๆ ที่อาจจะทำให้<br>เกิดเสียงรบกวนขณะกรวยกระพือขึ้น</p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\">แม่เหล็กมีขนาดใหญ่ ด้านท้ายมีทีโยค ป้องกันแม่เหล็ก พร้อมรูระบายความร้อนเข้าไปถึงแกนวอยศ์คอยล์ รุ่นนี้ออกแบบมาให้ติดตั้งได้หลายรูปแบบทั้งตู้สูตรแบบปิด (SEALED) ตู้เปิด (PORTED) ตู้แบนด์พาสส์ (BANDPASS) และแบบแขวนลอย (FREEAIR) ตามต้องการ</p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\">&nbsp;</p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\"><strong>คุณภาพเสียง</strong></p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\">ทีมงานได้ใช้เพาเวอร์แอมพ์ CLASS D ขนาดกำลังขับ 500 วัตต์ ขับซับวูเฟอร์รุ่นนี้ โดยต่อเล่นแบบขนาน คือ ขั้วบวกต่อกับขั้วบวก เข้าเอาท์พุทบวก และขั้วลบต่อกับขั้วลบ เข้าเอาท์พุทลบ ซึ่งจะทำให้เค้นวัตต์ที่ความต้านทาน 2 โอห์ม ติดตั้งในตู้ปิดปริมาตร 1.0 ลูกบาศก์ฟุต อัดใยแก้วมากเป็นพิเศษ ใช้อีเลคทรอนิคส์ ครอสส์โอเวอร์ในเพาเวอร์แอมพ์ ตัดความถี่โลว์พาสส์ที่ความถี่ 250 HZ ในการทดลองฟังเพิ่มเพาเวอร์แอมพ์ 4 แชนแนล ใช้แชนแนลที่ 1/2 ขับชุดลำโพงกลาง/แหลม 1 คู่ พาสสีฟ ครอสส์โอเวอร์ตัดกลาง/แหลมที่ 4,000 HZ</p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\">จากกราฟทดสอบ มีการตอบสนองความถี่ได้ในช่วง 25-250 HZ โดยจุดกราฟมีความต่อเนื่องกันดี เริ่มจากเสียงเบสส์ที่ลงได้ลึก 25 HZ ระดับเสียง 87 ดีบี และต่อเนื่องกันถึง 31.5 HZ กับมวลเบสส์ขนาดใหญ่ที่นุ่มลึก และหาฟังยาก จะมีให้ได้ยินกัน ที่ความถี่ 40-50 HZ ระดับเสียง 90 ดีบี ได้เสียงเบสส์ที่มีทั้งนุ่มลึก และพลังออกมาตามต้นฉบับ ที่ความถี่ 63-100 HZ ระดับเสียง 93 ดีบี เป็นเสียงเบสส์ที่หนักหน่วงมีพลัง จะออกมาโดดเด่นชัดนำ</p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\">ที่ความถี่ 125 HZ ระดับเสียง 96 ดีบี ได้ความโดดเด่นกับเบสส์มีพลังที่กระชับรวดเร็ว และที่ความถี่&nbsp;160-200 HZ ระดับเสียง 93 ดีบี และ 90 ดีบี ตามลำดับ ก่อนลงมาที่จุดตัด 250 HZ ระดับเสียง 87 ดีบี&nbsp;จึงได้เสียงเบสส์ที่เป็นลูกชัดเจนหยุดสนิท ก่อนขึ้นเสียงกลาง เมื่อทดลองฟังดนตรี&nbsp;JAZZ/ROCK/DANCE ได้เนื้อหาเบสส์น่าฟัง มีความกลมกลืนกับชิ้นดนตรีกลาง/แหลม ลูกนุ่มลึกเค้น<br>ได้ไม่ยาก พลังเกินตัว เสียงตบเบสส์เนียนชัดหยุดสนิท จึงเหมาะกับเสียงเบสส์ทุกรูปแบบ</p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\">&nbsp;</p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\"><strong>ความคุ้มค่า</strong></p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\">รุ่นนี้เป็นซับวูเฟอร์ที่ออกแบบมาให้ต่อเล่นได้หลากหลาย ด้านพละกำลังมีให้เกินตัว สามารถเค้นเสียงเบสส์ได้หลากหลายรูปแบบ กับแนวดนตรีต่างๆ ซึ่งเพียงข้างเดียวก็ต่อเล่นได้ทั้งแบบขนาน และอนุกรมหรือหากเพิ่มจำนวนขึ้นอีก ก็จะเค้นพลังในระดับแข่งขันได้อย่างสบาย</p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\">&nbsp;<img src=\"/images/general/86e9eac2c056b172c60bf1057f31b442.jpg\" style=\"width: 100%;\"></p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\"><iframe frameborder=\"0\" src=\"//www.youtube.com/embed/t-UZuZUiA2M\" width=\"640\" height=\"360\" class=\"note-video-clip\"></iframe><br></p><p style=\"margin-bottom: 8.5px; color: rgb(51, 51, 51); font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: 14.4px;\"><img src=\"/images/general/5ee8313054f382a54f5974d2756cf455.jpg\" style=\"width: 100%;\"><br></p>','87db7619132e64c3c4722a4c38831b6e.png','de8f318b62e091fd684665772b647dfc.png','2019-04-04 19:09:00','2019-04-05 20:04:00',8,6,'1','1','0000-00-00','0','0','0','1','4873');
/*!40000 ALTER TABLE `moneypot` ENABLE KEYS */;
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
INSERT INTO `subcategory` VALUES (1,8,'Organization','องค์กร','8d4545ade83ef67508ad6db2e0151028.jpeg','2018-04-23 18:34:00','2019-03-17 19:56:00',1,3),(2,8,'Emergency','กรณีฉุกเฉิน','1b7b4e5028fc2a6d8591e76f6b244f23.jpeg','2019-03-13 20:02:00','2019-03-17 19:56:00',1,6),(3,8,'Sport','กีฬา','86075573c099702999205200b9f8f887.jpeg','2019-03-13 20:03:00','2019-03-17 19:56:00',1,8),(4,8,'Education','การศึกษา','f1aefc8781fb4b5c0df3276c142eab1e.jpeg','2019-03-13 20:05:00','2019-03-17 19:56:00',1,7),(5,8,'Animals','สัตว์','d907778211a09c23a15b2b521edcbcac.jpeg','2019-03-13 20:05:00','2019-03-17 19:56:00',1,5),(6,8,'Art & Culture','ศิลปะและวัฒนธรรม','7696c2b8eb573bcef793c2cd59d8d037.jpeg','2019-03-13 20:10:00','2019-03-17 19:56:00',1,4),(7,8,'Community','ชุมชน','25e9394b5b1e462a7c6084774c44930f.jpeg','2019-03-13 20:12:00','2019-03-17 19:56:00',1,2),(8,8,'Environment','สิ่งแวดล้อม','b0ce0b873f3024edd6caed308cc9c41a.jpeg','2019-03-13 20:13:00','2019-03-17 19:56:00',1,1);
/*!40000 ALTER TABLE `subcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `amount` varchar(20) NOT NULL,
  `type` tinyint(2) NOT NULL,
  `charge_id` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `fee` varchar(20) NOT NULL,
  `amount_total` varchar(20) NOT NULL,
  `pot_id` int(11) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdate` datetime NOT NULL,
  `message` text NOT NULL,
  `reference` varchar(100) NOT NULL,
  `transaction` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,8,'เสรี ใสเกื้อ','boyatomic32@gmail.com','1000',1,'chrg_test_5fecsvq0q9kck5j0mzd','successful','39.06','960.94',9,'2019-03-29 21:26:00','2019-03-29 21:26:00','','paym_test_5fecsvq55hde0yn204l','trxn_test_5fecsvze8qh8emyjf8e'),(2,8,'เสรี ใสเกื้อ','boyatomic32@gmail.com','4000',1,'chrg_test_5fect5dm02vgezbbwpe','successful','156.22','3843.78',9,'2019-03-29 21:27:00','2019-03-29 21:27:00','','paym_test_5fect5dqdm2tp5iqv87','trxn_test_5fect5m0shhpzxccl7h'),(3,8,'boy gpsn','boy@gpsn.co.th','999',1,'chrg_test_5fecwpivyp7z468cmgr','successful','39.01','959.99',13,'2019-03-29 21:37:00','2019-03-29 21:37:00','boy test','paym_test_5fecwpj1fjoqa2lszsl','trxn_test_5fecwpqiaxdfff8oi5c'),(4,8,'เสรี ใสเกื้อ','boyatomic32@gmail.com','4500',1,'chrg_test_5fecyjnzbtffmakrujr','successful','175.75','4324.25',8,'2019-03-29 21:42:00','2019-03-29 21:42:00','','paym_test_5fecyjo3q8abvxdtmgw','trxn_test_5fecyjw7h9s9vkph686'),(5,8,'เสรี ใสเกื้อ','boyatomic32@gmail.com','4300',2,'chrg_test_5fen3yac1rzwqe1fwex','successful','167.94','4132.06',9,'2019-03-30 15:00:00','2019-03-30 15:00:00','','ofsp_test_5fen3yag4qhv42x54ax','trxn_test_5fen3z5h7vlrg4xuqev'),(6,5,'Sylvain De Muynck','svanou@gmail.com','2000',1,'chrg_test_5fffzeu5huf06ddeztz','successful','78.11','1921.89',18,'2019-04-01 16:13:00','2019-04-01 16:14:00','wef','paym_test_5fffzeuc3nqfzfrssrk','trxn_test_5fffzfe4uz32qqtpsxy'),(7,1,'seree saikuea','boysere32@gmail.com','5500',1,'chrg_test_5ffu3l2ipsa7hwdndbn','successful','214.8','5285.2',16,'2019-04-02 16:17:00','2019-04-02 16:17:00','','paym_test_5ffu3l2n4nibcx3bmck','trxn_test_5ffu3lessw1tzv4wfu4'),(8,1,'seree saikuea','boysere32@gmail.com','4500',1,'chrg_test_5ffu9wpiifrf6m05nn9','successful','175.75','4324.25',16,'2019-04-02 16:35:00','2019-04-02 16:35:00','','paym_test_5ffu9wpnp3lsgrkqx6u','trxn_test_5ffu9x5b0wl19hxlynl'),(9,1,'seree saikuea','boysere32@gmail.com','4300',1,'chrg_test_5ffubp9ptt37a5978j1','successful','167.94','4132.06',16,'2019-04-02 16:40:00','2019-04-02 16:40:00','','paym_test_5ffubp9w4hkhnm99k2e','trxn_test_5ffubpjk05al8suyogb'),(10,1,'seree saikuea','boysere32@gmail.com','4300',1,'chrg_test_5ffudcvg4hkpoz9xc1v','successful','167.94','4132.06',16,'2019-04-02 16:45:00','2019-04-02 16:45:00','','paym_test_5ffudcvm4w7nidfhrvi','trxn_test_5ffudd4nu7j75pfwztu'),(11,1,'seree saikuea','boysere32@gmail.com','800',1,'chrg_test_5ffunlbf11wmlb7gvu1','successful','31.24','768.76',16,'2019-04-02 17:14:00','2019-04-02 17:14:00','','paym_test_5ffunlbna5j1f93z0ky','trxn_test_5ffunlkklhw1a1g4i2s'),(12,1,'seree saikuea','boysere32@gmail.com','200',1,'chrg_test_5ffuqveeb1wo2blazqp','successful','7.81','192.19',16,'2019-04-02 17:23:00','2019-04-02 17:23:00','','paym_test_5ffuqvejt2h51bd8xp4','trxn_test_5ffuqvn54x4d5pjq5cv'),(13,0,'seree saikuea','b1audio2018@gmail.com','999',2,'chrg_test_5fg42t0c1o9dnxh0gp7','successful','39.01','959.99',9,'2019-04-03 09:17:00','2019-04-03 09:17:00','','ofsp_test_5fg42t0hihwjqtnx7th','trxn_test_5fg42tr6ce6qi0b3vrz'),(14,9,'บอย โอเน็กซ์','saboy55@hotmail.com','658.00',1,'chrg_test_5fg6v8vtzju0nuqtmf9','successful','25.7','632.3',17,'2019-04-03 14:03:00','2019-04-03 14:03:00','','paym_test_5fg6v8vyecd0j674epm','trxn_test_5fg6v94zakv7s0hgg15'),(15,8,'เสรี ใสเกื้อ','boyatomic32@gmail.com','999',1,'chrg_test_5fga5q0we3qsiscfzj5','successful','39.01','959.99',19,'2019-04-03 19:39:00','2019-04-03 19:39:00','','paym_test_5fga5q12xwnzdp0231s','trxn_test_5fga5qj9pmm0u8g5db3'),(16,0,'เสรี ใสสุขสม','boyatomic32@gmail.com','658.00',1,'chrg_test_5fgbd9rtf1bsiqcas5k','successful','25.7','632.3',19,'2019-04-03 21:43:00','2019-04-03 21:43:00','','paym_test_5fgbd9rzhbrmoits2m6','trxn_test_5fgbda1ip2xm0yki46e'),(17,0,'เสรี ใสสุขสม','boyatomic32@gmail.com','658.00',2,'chrg_test_5fgbe08tvsobwaka27d','pending','0','0',19,'2019-04-03 21:45:00','2019-04-03 21:45:00','','ofsp_test_5fgbe08yjr7uwoep1l0',''),(18,8,'เสรี ใสเกื้อ','boyatomic32@gmail.com','658.00',2,'chrg_test_5fgbg3w1q24z0se5k3i','successful','25.7','632.3',19,'2019-04-03 21:51:00','2019-04-03 21:51:00','','ofsp_test_5fgbg3w63rwbe98davr','trxn_test_5fgbg4r1g4wkui36wjn'),(19,8,'เสรี ใสเกื้อ','boyatomic32@gmail.com','658.00',2,'chrg_test_5fgbndscsugtusbxgrc','successful','25.7','632.3',19,'2019-04-03 22:12:00','2019-04-03 22:12:00','','ofsp_test_5fgbndsgvinayxjqu13','trxn_test_5fgbnepxcjaqei0h9mq'),(20,0,'boy gpsn','boy@gpsn.co.th','2999',2,'chrg_test_5fgjf5thqbomgjebdzc','successful','117.12','2881.88',19,'2019-04-04 11:26:00','2019-04-04 11:26:00','','ofsp_test_5fgjf5tkt6f1kbp1nm2','trxn_test_5fgjf6lu93nno4cr3vn'),(21,10,'Sylvain SpeakeasyStreets','sylvain@speakeasystreets.com','2000',1,'chrg_test_5fgn81ssgojln80ucx5','successful','78.11','1921.89',20,'2019-04-04 17:55:00','2019-04-04 17:56:00','Boom! looking forward meeting him ! ','paym_test_5fgn81t0deabuyermte','trxn_test_5fgn826xvoc9wwhbwxg'),(22,8,'เสรี ใสเกื้อ','boyatomic32@gmail.com','999',2,'chrg_test_5fgoud48xtfk83u2rvo','successful','39.01','959.99',21,'2019-04-04 20:41:00','2019-04-04 20:43:00','รวมตัว ร่วมใจ ยิ่งใหญ่ Orion club team thailand 2019','ofsp_test_5fgoud4d1qyg2sx96sa','trxn_test_5fgoueawlj6xf4zb9zs'),(23,0,'Atomic Formula-x','b1audio2018@gmail.com','2955',2,'chrg_test_5fgowfpyqyh0vuvs6wl','successful','115.41','2839.59',21,'2019-04-04 20:47:00','2019-04-04 20:47:00','วัยรุ่นต้องคะนอง','ofsp_test_5fgowfq33xlo4h5sds5','trxn_test_5fgowgkph4en0ib2b8m'),(24,9,'บอย โอเน็กซ์','saboy55@hotmail.com','919',2,'chrg_test_5fgylty7rpt453fwf6q','successful','35.89','883.11',21,'2019-04-05 13:19:00','2019-04-05 13:19:00','','ofsp_test_5fgyltycxh4ebiztes5','trxn_test_5fgyluqmoi2djknyi5r'),(25,8,'seree saikuea(boy)','boyatomic32@gmail.com','999',2,'chrg_test_5fh2n2h84h565f02r08','successful','39.01','959.99',20,'2019-04-05 20:12:00','2019-04-05 20:13:00','good job','ofsp_test_5fh2n2hdlbycckr3om0','trxn_test_5fh2n3kda0ulqpnv8cf');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transferfund`
--

DROP TABLE IF EXISTS `transferfund`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transferfund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `bank_acc_name` varchar(10) NOT NULL,
  `bank_acc_country` varchar(5) NOT NULL,
  `bank_acc_brand` varchar(10) NOT NULL,
  `bank_acc_number` varchar(20) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdate` datetime NOT NULL,
  `status` enum('1','2','3','0') NOT NULL DEFAULT '1' COMMENT '1= pending, 2=Send, 3 =  Paid, 0= Fail',
  `amount` varchar(20) NOT NULL DEFAULT '0',
  `omise_recipient_id` varchar(50) NOT NULL,
  `omise_transfer_id` varchar(50) NOT NULL,
  `pot_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transfer_amount` varchar(20) NOT NULL,
  `transfer_fee` varchar(20) NOT NULL,
  `bank_verified` varchar(20) NOT NULL DEFAULT 'Pending',
  `service_charge` varchar(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transferfund`
--

LOCK TABLES `transferfund` WRITE;
/*!40000 ALTER TABLE `transferfund` DISABLE KEYS */;
INSERT INTO `transferfund` VALUES (1,'seree saikuea','เสรี ใสสุข','th','scb','123456789','2019-03-29 21:33:00','2019-03-29 21:50:00','3','5000','recp_test_5fecv44ksorctrpec9y','trsf_test_5fecv473xbks11y1cas',9,8,'4650','30','Verified','350'),(2,'seree saikuea','เสรี ใสสุข','th','scb','123456789','2019-03-29 21:44:00','2019-03-29 23:30:00','3','4500','recp_test_5fecz5mr7n7ns7zaz23','trsf_test_5fecz5pfcevwg9alelv',8,8,'4185','30','Verified','315'),(3,'Sylvain De Muynck','Sylvain de','th','kbank','0101110052','2019-03-30 13:56:00','2019-03-30 14:20:00','3','999','recp_test_5femhh0vo5j4ajpkj9t','trsf_test_5femhh3tbpghs6gk81k',13,5,'929.07','30','Verified','69.93'),(4,'เสรี ใสเกื้อ','เสรี ใสสุข','th','scb','1234567890','2019-04-02 16:32:00','2019-04-02 16:50:00','3','5500','recp_test_5ffu8u36qkhaz6vty1j','trsf_test_5ffu8u6do8i1e9j9zxc',16,1,'5145','30','Verified','385'),(5,'seree saikuea','เสรี ใสสุข','th','scb','123456789','2019-04-02 16:37:00','2019-04-02 16:50:00','3','4500','recp_test_5ffuauiowwh5bqv764t','trsf_test_5ffuaul055mrww6vgfv',16,1,'4215','30','Verified','315'),(6,'seree saikuea','seree saik','th','kbank','1234567890','2019-04-02 16:41:00','2019-04-02 16:50:00','3','4300','recp_test_5ffuc3lp369q8i35br5','trsf_test_5ffuc3onlsei8w8u9is',16,1,'4029','30','Verified','301'),(7,'seree saikuea','เสรี ใสสุข','th','tmb','1234567890','2019-04-02 17:13:00','2019-04-02 17:13:00','1','4300','recp_test_5ffunentah468cbpdr4','trsf_test_5ffuneq9eadgzq6dvfy',16,1,'4029','30','Pending','301'),(8,'Tony williams','Tony Willa','th','kbank','12312313','2019-04-02 21:12:00','2019-04-02 21:12:00','1','2000','recp_test_5ffwzduwgu8mvfyuq8u','trsf_test_5ffwzdxe6zco8wuv309',18,5,'1890','30','Pending','140');
/*!40000 ALTER TABLE `transferfund` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_en` varchar(100) NOT NULL,
  `name_th` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `createdate` datetime NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `facebook_id` varchar(20) NOT NULL DEFAULT '0',
  `token` varchar(255) NOT NULL,
  `image` varchar(50) NOT NULL,
  `omise_customer_id` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (5,'Sylvain ','ซิลวา','svanou@gmail.com','d104dd81ab79533a097300fd36caf9d2','2019-03-12 16:34:00','2019-04-05 11:53:36','1','10156513613983506','','b41de075f5f1fcb58515df8dce7caba5.jpg',''),(4,'Sylvian','ซิลวา','kkk@gmail.com','be0c1c7982c6b22842927cbbeeaa6b32','2019-03-12 16:24:00','2019-04-05 11:53:30','1','','','',''),(3,'boy gpsn','บอย จีพีเอสเอ็น','boy@gpsn.co.th','eacbd3b3ef1b67db9e13813376fc5a2d','2019-03-12 14:45:00','2019-04-05 11:43:52','1','','MjAxOS0wMy0yMyAwNjoxODozOSZib3lAZ3Bzbi5jby50aAlungkhanlungkhan','',''),(1,'seree saikuea','เสรี ใสเกื้อ','boysere32@gmail.com','330019c86222a5de884f91e6c7626c66','2019-03-12 13:50:00','2019-04-05 11:43:22','1','','','',''),(7,'Sylvain','ซิลวา','sylvain@gpsn.co.th','d728f03157a2b36a46801fcd6e25014a','2019-03-13 21:49:00','2019-04-05 11:53:46','1','','MjAxOS0wMy0xNCAwOTo1NDowMyZzeWx2YWluQGdwc24uY28udGglungkhan','',''),(8,'seree saikuea(boy)','เสรี ใสเกื้อ(บอย)','boyatomic32@gmail.com','72f1c6e20c0e25358b061016688d4549','2019-03-23 11:19:00','2019-04-05 12:04:50','1','1520619608069841','','a0e3999b1684b7d0ab682cf921e98227.jpg','cust_test_5fbudiza72eo6qgl6di'),(9,'Boy Onex','บอย โอเน็กซ์','saboy55@hotmail.com','','2019-04-03 13:55:00','2019-04-05 12:13:01','1','10210605598335818','','',''),(10,'Sylvain Speakeasy Streets','Sylvain Speakeasy Streets','sylvain@speakeasystreets.com','','2019-04-04 17:54:00','2019-04-05 11:15:54','1','392122481371022','','','');
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

-- Dump completed on 2019-04-05 13:31:35
