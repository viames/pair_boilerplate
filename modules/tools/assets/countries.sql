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
-- Table structure for table `countries`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `native_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `english_name` varchar(34) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=231 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'AD','Andorra','Andorra'),(2,'AE','دولة الإمارات العربية المتحدة‎','United Arab Emirates'),(3,'AF','جمھوری اسلامی افغانستان','Afghanistan'),(4,'AG','Antigua and Barbuda','Antigua and Barbuda'),(5,'AI','Anguilla','Anguilla'),(6,'AL','Shqipëria','Albania'),(7,'AM','Հայաստանի Հանրապետություն','Armenia'),(8,'AN','Nederlandse Antillen','Netherlands Antilles'),(9,'AO','Angola','Angola'),(10,'AR','Argentina','Argentina'),(11,'AT','Österreich','Austria'),(12,'AU','Australia','Australia'),(13,'AW','Aruba','Aruba'),(14,'AZ','Azərbaycan','Azerbaijan'),(15,'BA','Босна и Херцеговина','Bosnia and Herzegovina'),(16,'BB','Barbados','Barbados'),(17,'BD','গণপ্রজাতন্ত্রী বাংলাদেশ','Bangladesh'),(18,'BE','België','Belgium'),(19,'BF',NULL,'Burkina Faso'),(20,'BG',NULL,'Bulgaria'),(21,'BH','البحرين‎','Bahrain'),(22,'BI',NULL,'Burundi'),(23,'BJ',NULL,'Benin'),(24,'BM',NULL,'Bermuda'),(25,'BN',NULL,'Brunei Darussalam'),(26,'BO',NULL,'Bolivia'),(27,'BR',NULL,'Brazil'),(28,'BS','Bahamas','Bahamas'),(29,'BT',NULL,'Bhutan'),(30,'BW',NULL,'Botswana'),(31,'BY','Беларусь','Belarus'),(32,'BZ',NULL,'Belize'),(33,'CA','Canada','Canada'),(34,'CC',NULL,'Cocos (Keeling) Islands'),(35,'CD',NULL,'Democratic Republic of the Congo'),(36,'CF',NULL,'Central African Republic'),(37,'CG',NULL,'Congo'),(38,'CH',NULL,'Switzerland'),(39,'CI',NULL,'Cote D\'Ivoire (Ivory Coast)'),(40,'CK',NULL,'Cook Islands'),(41,'CL',NULL,'Chile'),(42,'CM',NULL,'Cameroon'),(43,'CN','中华人民共和国','China'),(44,'CO',NULL,'Colombia'),(45,'CR',NULL,'Costa Rica'),(46,'CU',NULL,'Cuba'),(47,'CV',NULL,'Cape Verde'),(48,'CX',NULL,'Christmas Island'),(49,'CY',NULL,'Cyprus'),(50,'CZ',NULL,'Czech Republic'),(51,'DE',NULL,'Germany'),(52,'DJ',NULL,'Djibouti'),(53,'DK',NULL,'Denmark'),(54,'DM',NULL,'Dominica'),(55,'DO',NULL,'Dominican Republic'),(56,'DZ','الجزائر‎','Algeria'),(57,'EC',NULL,'Ecuador'),(58,'EE',NULL,'Estonia'),(59,'EG',NULL,'Egypt'),(60,'EH',NULL,'Western Sahara'),(61,'ER',NULL,'Eritrea'),(62,'ES',NULL,'Spain'),(63,'ET',NULL,'Ethiopia'),(64,'FI',NULL,'Finland'),(65,'FJ',NULL,'Fiji'),(66,'FK',NULL,'Falkland Islands (Malvinas)'),(67,'FM',NULL,'Federated States of Micronesia'),(68,'FO',NULL,'Faroe Islands'),(69,'FR','France','France'),(70,'GA',NULL,'Gabon'),(71,'GB',NULL,'Great Britain (UK)'),(72,'GD',NULL,'Grenada'),(73,'GE',NULL,'Georgia'),(74,'GF',NULL,'French Guiana'),(75,'GG',NULL,'Guernsey'),(76,'GH',NULL,'Ghana'),(77,'GI',NULL,'Gibraltar'),(78,'GL',NULL,'Greenland'),(79,'GM',NULL,'Gambia'),(80,'GN',NULL,'Guinea'),(81,'GP',NULL,'Guadeloupe'),(82,'GQ',NULL,'Equatorial Guinea'),(83,'GR',NULL,'Greece'),(84,'GS',NULL,'S. Georgia and S. Sandwich Islands'),(85,'GT',NULL,'Guatemala'),(86,'GW',NULL,'Guinea-Bissau'),(87,'GY',NULL,'Guyana'),(88,'HK',NULL,'Hong Kong'),(89,'HN',NULL,'Honduras'),(90,'HR',NULL,'Croatia (Hrvatska)'),(91,'HT',NULL,'Haiti'),(92,'HU',NULL,'Hungary'),(93,'ID',NULL,'Indonesia'),(94,'IE',NULL,'Ireland'),(95,'IL',NULL,'Israel'),(96,'IN',NULL,'India'),(97,'IQ',NULL,'Iraq'),(98,'IR',NULL,'Iran'),(99,'IS',NULL,'Iceland'),(100,'IT','Italia','Italy'),(101,'JM',NULL,'Jamaica'),(102,'JO',NULL,'Jordan'),(103,'JP',NULL,'Japan'),(104,'KE',NULL,'Kenya'),(105,'KG',NULL,'Kyrgyzstan'),(106,'KH',NULL,'Cambodia'),(107,'KI',NULL,'Kiribati'),(108,'KM',NULL,'Comoros'),(109,'KN',NULL,'Saint Kitts and Nevis'),(110,'KP',NULL,'Korea (North)'),(111,'KR',NULL,'Korea (South)'),(112,'KW',NULL,'Kuwait'),(113,'KY',NULL,'Cayman Islands'),(114,'KZ',NULL,'Kazakhstan'),(115,'LA',NULL,'Laos'),(116,'LB',NULL,'Lebanon'),(117,'LC',NULL,'Saint Lucia'),(118,'LI',NULL,'Liechtenstein'),(119,'LK',NULL,'Sri Lanka'),(120,'LR',NULL,'Liberia'),(121,'LS',NULL,'Lesotho'),(122,'LT',NULL,'Lithuania'),(123,'LU',NULL,'Luxembourg'),(124,'LV',NULL,'Latvia'),(125,'LY',NULL,'Libya'),(126,'MA',NULL,'Morocco'),(127,'MC',NULL,'Monaco'),(128,'MD',NULL,'Moldova'),(129,'MG',NULL,'Madagascar'),(130,'MH',NULL,'Marshall Islands'),(131,'MK',NULL,'Macedonia'),(132,'ML',NULL,'Mali'),(133,'MM',NULL,'Myanmar'),(134,'MN',NULL,'Mongolia'),(135,'MO',NULL,'Macao'),(136,'MP',NULL,'Northern Mariana Islands'),(137,'MQ',NULL,'Martinique'),(138,'MR',NULL,'Mauritania'),(139,'MS',NULL,'Montserrat'),(140,'MT',NULL,'Malta'),(141,'MU',NULL,'Mauritius'),(142,'MV',NULL,'Maldives'),(143,'MW',NULL,'Malawi'),(144,'MX',NULL,'Mexico'),(145,'MY',NULL,'Malaysia'),(146,'MZ',NULL,'Mozambique'),(147,'NA',NULL,'Namibia'),(148,'NC',NULL,'New Caledonia'),(149,'NE',NULL,'Niger'),(150,'NF',NULL,'Norfolk Island'),(151,'NG',NULL,'Nigeria'),(152,'NI',NULL,'Nicaragua'),(153,'NL',NULL,'Netherlands'),(154,'NO',NULL,'Norway'),(155,'NP',NULL,'Nepal'),(156,'NR',NULL,'Nauru'),(157,'NU',NULL,'Niue'),(158,'NZ',NULL,'New Zealand (Aotearoa)'),(159,'OM',NULL,'Oman'),(160,'PA',NULL,'Panama'),(161,'PE',NULL,'Peru'),(162,'PF',NULL,'French Polynesia'),(163,'PG',NULL,'Papua New Guinea'),(164,'PH',NULL,'Philippines'),(165,'PK',NULL,'Pakistan'),(166,'PL',NULL,'Poland'),(167,'PM',NULL,'Saint Pierre and Miquelon'),(168,'PN',NULL,'Pitcairn'),(169,'PS',NULL,'Palestinian Territory'),(170,'PT','Portugal','Portugal'),(171,'PW',NULL,'Palau'),(172,'PY',NULL,'Paraguay'),(173,'QA',NULL,'Qatar'),(174,'RE',NULL,'Reunion'),(175,'RO',NULL,'Romania'),(176,'RU',NULL,'Russian Federation'),(177,'RW',NULL,'Rwanda'),(178,'SA','المملكة العربية السعودية','Saudi Arabia'),(179,'SB',NULL,'Solomon Islands'),(180,'SC',NULL,'Seychelles'),(181,'SD',NULL,'Sudan'),(182,'SE',NULL,'Sweden'),(183,'SG',NULL,'Singapore'),(184,'SH',NULL,'Saint Helena'),(185,'SI',NULL,'Slovenia'),(186,'SJ',NULL,'Svalbard and Jan Mayen'),(187,'SK',NULL,'Slovakia'),(188,'SL',NULL,'Sierra Leone'),(189,'SM',NULL,'San Marino'),(190,'SN',NULL,'Senegal'),(191,'SO',NULL,'Somalia'),(192,'SR',NULL,'Suriname'),(193,'ST',NULL,'Sao Tome and Principe'),(194,'SV',NULL,'El Salvador'),(195,'SY',NULL,'Syria'),(196,'SZ',NULL,'Swaziland'),(197,'TC',NULL,'Turks and Caicos Islands'),(198,'TD',NULL,'Chad'),(199,'TF',NULL,'French Southern Territories'),(200,'TG',NULL,'Togo'),(201,'TH',NULL,'Thailand'),(202,'TJ',NULL,'Tajikistan'),(203,'TK',NULL,'Tokelau'),(204,'TM',NULL,'Turkmenistan'),(205,'TN',NULL,'Tunisia'),(206,'TO',NULL,'Tonga'),(207,'TR',NULL,'Turkey'),(208,'TT',NULL,'Trinidad and Tobago'),(209,'TV',NULL,'Tuvalu'),(210,'TW',NULL,'Taiwan'),(211,'TZ',NULL,'Tanzania'),(212,'UA',NULL,'Ukraine'),(213,'UG',NULL,'Uganda'),(214,'UY',NULL,'Uruguay'),(215,'UZ',NULL,'Uzbekistan'),(216,'VC',NULL,'Saint Vincent and the Grenadines'),(217,'VE',NULL,'Venezuela'),(218,'VG',NULL,'Virgin Islands (British)'),(219,'VI',NULL,'Virgin Islands (U.S.)'),(220,'VN',NULL,'Viet Nam'),(221,'VU',NULL,'Vanuatu'),(222,'WF',NULL,'Wallis and Futuna'),(223,'WS',NULL,'Samoa'),(224,'YE',NULL,'Yemen'),(225,'YT',NULL,'Mayotte'),(226,'ZA',NULL,'South Africa'),(227,'ZM',NULL,'Zambia'),(228,'ZR',NULL,'Zaire (former)'),(229,'ZW',NULL,'Zimbabwe'),(230,'US',NULL,'United States of America');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;