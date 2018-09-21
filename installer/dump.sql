/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acl`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `acl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rule_id` int(3) unsigned NOT NULL,
  `group_id` int(3) unsigned DEFAULT NULL,
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rule_id` (`rule_id`,`group_id`),
  KEY `group_id` (`group_id`) USING BTREE,
  CONSTRAINT `fk_acl_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_acl_rules` FOREIGN KEY (`rule_id`) REFERENCES `rules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acl`
--

LOCK TABLES `acl` WRITE;
/*!40000 ALTER TABLE `acl` DISABLE KEYS */;
INSERT INTO `acl` VALUES (2,2,1,0),(3,3,1,0),(4,4,1,0),(5,5,1,0),(6,6,1,0),(7,7,1,0),(8,8,1,0),(9,9,1,0),(10,10,1,1),(11,1,1,0);
/*!40000 ALTER TABLE `acl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `native_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `english_name` varchar(34) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'AD','Andorra','Andorra'),(2,'AE','دولة الإمارات العربية المتحدة‎','United Arab Emirates'),(3,'AF','جمھوری اسلامی افغانستان','Afghanistan'),(4,'AG','Antigua and Barbuda','Antigua and Barbuda'),(5,'AI','Anguilla','Anguilla'),(6,'AL','Shqipëria','Albania'),(7,'AM','Հայաստանի Հանրապետություն','Armenia'),(8,'AN','Nederlandse Antillen','Netherlands Antilles'),(9,'AO','Angola','Angola'),(10,'AR','Argentina','Argentina'),(11,'AT','Österreich','Austria'),(12,'AU','Australia','Australia'),(13,'AW','Aruba','Aruba'),(14,'AZ','Azərbaycan','Azerbaijan'),(15,'BA','Босна и Херцеговина','Bosnia and Herzegovina'),(16,'BB','Barbados','Barbados'),(17,'BD','গণপ্রজাতন্ত্রী বাংলাদেশ','Bangladesh'),(18,'BE','België','Belgium'),(19,'BF',NULL,'Burkina Faso'),(20,'BG',NULL,'Bulgaria'),(21,'BH','البحرين‎','Bahrain'),(22,'BI',NULL,'Burundi'),(23,'BJ',NULL,'Benin'),(24,'BM',NULL,'Bermuda'),(25,'BN',NULL,'Brunei Darussalam'),(26,'BO',NULL,'Bolivia'),(27,'BR',NULL,'Brazil'),(28,'BS','Bahamas','Bahamas'),(29,'BT',NULL,'Bhutan'),(30,'BW',NULL,'Botswana'),(31,'BY','Беларусь','Belarus'),(32,'BZ',NULL,'Belize'),(33,'CA','Canada','Canada'),(34,'CC',NULL,'Cocos (Keeling) Islands'),(35,'CD',NULL,'Democratic Republic of the Congo'),(36,'CF',NULL,'Central African Republic'),(37,'CG',NULL,'Congo'),(38,'CH',NULL,'Switzerland'),(39,'CI',NULL,'Cote D\'Ivoire (Ivory Coast)'),(40,'CK',NULL,'Cook Islands'),(41,'CL',NULL,'Chile'),(42,'CM',NULL,'Cameroon'),(43,'CN','中华人民共和国','China'),(44,'CO',NULL,'Colombia'),(45,'CR',NULL,'Costa Rica'),(46,'CU',NULL,'Cuba'),(47,'CV',NULL,'Cape Verde'),(48,'CX',NULL,'Christmas Island'),(49,'CY',NULL,'Cyprus'),(50,'CZ',NULL,'Czech Republic'),(51,'DE',NULL,'Germany'),(52,'DJ',NULL,'Djibouti'),(53,'DK',NULL,'Denmark'),(54,'DM',NULL,'Dominica'),(55,'DO',NULL,'Dominican Republic'),(56,'DZ','الجزائر‎','Algeria'),(57,'EC',NULL,'Ecuador'),(58,'EE',NULL,'Estonia'),(59,'EG',NULL,'Egypt'),(60,'EH',NULL,'Western Sahara'),(61,'ER',NULL,'Eritrea'),(62,'ES',NULL,'Spain'),(63,'ET',NULL,'Ethiopia'),(64,'FI',NULL,'Finland'),(65,'FJ',NULL,'Fiji'),(66,'FK',NULL,'Falkland Islands (Malvinas)'),(67,'FM',NULL,'Federated States of Micronesia'),(68,'FO',NULL,'Faroe Islands'),(69,'FR','France','France'),(70,'GA',NULL,'Gabon'),(71,'GB',NULL,'Great Britain (UK)'),(72,'GD',NULL,'Grenada'),(73,'GE',NULL,'Georgia'),(74,'GF',NULL,'French Guiana'),(75,'GG',NULL,'Guernsey'),(76,'GH',NULL,'Ghana'),(77,'GI',NULL,'Gibraltar'),(78,'GL',NULL,'Greenland'),(79,'GM',NULL,'Gambia'),(80,'GN',NULL,'Guinea'),(81,'GP',NULL,'Guadeloupe'),(82,'GQ',NULL,'Equatorial Guinea'),(83,'GR',NULL,'Greece'),(84,'GS',NULL,'S. Georgia and S. Sandwich Islands'),(85,'GT',NULL,'Guatemala'),(86,'GW',NULL,'Guinea-Bissau'),(87,'GY',NULL,'Guyana'),(88,'HK',NULL,'Hong Kong'),(89,'HN',NULL,'Honduras'),(90,'HR',NULL,'Croatia (Hrvatska)'),(91,'HT',NULL,'Haiti'),(92,'HU',NULL,'Hungary'),(93,'ID',NULL,'Indonesia'),(94,'IE',NULL,'Ireland'),(95,'IL',NULL,'Israel'),(96,'IN',NULL,'India'),(97,'IQ',NULL,'Iraq'),(98,'IR',NULL,'Iran'),(99,'IS',NULL,'Iceland'),(100,'IT','Italia','Italy'),(101,'JM',NULL,'Jamaica'),(102,'JO',NULL,'Jordan'),(103,'JP',NULL,'Japan'),(104,'KE',NULL,'Kenya'),(105,'KG',NULL,'Kyrgyzstan'),(106,'KH',NULL,'Cambodia'),(107,'KI',NULL,'Kiribati'),(108,'KM',NULL,'Comoros'),(109,'KN',NULL,'Saint Kitts and Nevis'),(110,'KP',NULL,'Korea (North)'),(111,'KR',NULL,'Korea (South)'),(112,'KW',NULL,'Kuwait'),(113,'KY',NULL,'Cayman Islands'),(114,'KZ',NULL,'Kazakhstan'),(115,'LA',NULL,'Laos'),(116,'LB',NULL,'Lebanon'),(117,'LC',NULL,'Saint Lucia'),(118,'LI',NULL,'Liechtenstein'),(119,'LK',NULL,'Sri Lanka'),(120,'LR',NULL,'Liberia'),(121,'LS',NULL,'Lesotho'),(122,'LT',NULL,'Lithuania'),(123,'LU',NULL,'Luxembourg'),(124,'LV',NULL,'Latvia'),(125,'LY',NULL,'Libya'),(126,'MA',NULL,'Morocco'),(127,'MC',NULL,'Monaco'),(128,'MD',NULL,'Moldova'),(129,'MG',NULL,'Madagascar'),(130,'MH',NULL,'Marshall Islands'),(131,'MK',NULL,'Macedonia'),(132,'ML',NULL,'Mali'),(133,'MM',NULL,'Myanmar'),(134,'MN',NULL,'Mongolia'),(135,'MO',NULL,'Macao'),(136,'MP',NULL,'Northern Mariana Islands'),(137,'MQ',NULL,'Martinique'),(138,'MR',NULL,'Mauritania'),(139,'MS',NULL,'Montserrat'),(140,'MT',NULL,'Malta'),(141,'MU',NULL,'Mauritius'),(142,'MV',NULL,'Maldives'),(143,'MW',NULL,'Malawi'),(144,'MX',NULL,'Mexico'),(145,'MY',NULL,'Malaysia'),(146,'MZ',NULL,'Mozambique'),(147,'NA',NULL,'Namibia'),(148,'NC',NULL,'New Caledonia'),(149,'NE',NULL,'Niger'),(150,'NF',NULL,'Norfolk Island'),(151,'NG',NULL,'Nigeria'),(152,'NI',NULL,'Nicaragua'),(153,'NL',NULL,'Netherlands'),(154,'NO',NULL,'Norway'),(155,'NP',NULL,'Nepal'),(156,'NR',NULL,'Nauru'),(157,'NU',NULL,'Niue'),(158,'NZ',NULL,'New Zealand (Aotearoa)'),(159,'OM',NULL,'Oman'),(160,'PA',NULL,'Panama'),(161,'PE',NULL,'Peru'),(162,'PF',NULL,'French Polynesia'),(163,'PG',NULL,'Papua New Guinea'),(164,'PH',NULL,'Philippines'),(165,'PK',NULL,'Pakistan'),(166,'PL',NULL,'Poland'),(167,'PM',NULL,'Saint Pierre and Miquelon'),(168,'PN',NULL,'Pitcairn'),(169,'PS',NULL,'Palestinian Territory'),(170,'PT','Portugal','Portugal'),(171,'PW',NULL,'Palau'),(172,'PY',NULL,'Paraguay'),(173,'QA',NULL,'Qatar'),(174,'RE',NULL,'Reunion'),(175,'RO',NULL,'Romania'),(176,'RU',NULL,'Russian Federation'),(177,'RW',NULL,'Rwanda'),(178,'SA','المملكة العربية السعودية','Saudi Arabia'),(179,'SB',NULL,'Solomon Islands'),(180,'SC',NULL,'Seychelles'),(181,'SD',NULL,'Sudan'),(182,'SE',NULL,'Sweden'),(183,'SG',NULL,'Singapore'),(184,'SH',NULL,'Saint Helena'),(185,'SI',NULL,'Slovenia'),(186,'SJ',NULL,'Svalbard and Jan Mayen'),(187,'SK',NULL,'Slovakia'),(188,'SL',NULL,'Sierra Leone'),(189,'SM',NULL,'San Marino'),(190,'SN',NULL,'Senegal'),(191,'SO',NULL,'Somalia'),(192,'SR',NULL,'Suriname'),(193,'ST',NULL,'Sao Tome and Principe'),(194,'SV',NULL,'El Salvador'),(195,'SY',NULL,'Syria'),(196,'SZ',NULL,'Swaziland'),(197,'TC',NULL,'Turks and Caicos Islands'),(198,'TD',NULL,'Chad'),(199,'TF',NULL,'French Southern Territories'),(200,'TG',NULL,'Togo'),(201,'TH',NULL,'Thailand'),(202,'TJ',NULL,'Tajikistan'),(203,'TK',NULL,'Tokelau'),(204,'TM',NULL,'Turkmenistan'),(205,'TN',NULL,'Tunisia'),(206,'TO',NULL,'Tonga'),(207,'TR',NULL,'Turkey'),(208,'TT',NULL,'Trinidad and Tobago'),(209,'TV',NULL,'Tuvalu'),(210,'TW',NULL,'Taiwan'),(211,'TZ',NULL,'Tanzania'),(212,'UA',NULL,'Ukraine'),(213,'UG',NULL,'Uganda'),(214,'UY',NULL,'Uruguay'),(215,'UZ',NULL,'Uzbekistan'),(216,'VC',NULL,'Saint Vincent and the Grenadines'),(217,'VE',NULL,'Venezuela'),(218,'VG',NULL,'Virgin Islands (British)'),(219,'VI',NULL,'Virgin Islands (U.S.)'),(220,'VN',NULL,'Viet Nam'),(221,'VU',NULL,'Vanuatu'),(222,'WF',NULL,'Wallis and Futuna'),(223,'WS',NULL,'Samoa'),(224,'YE',NULL,'Yemen'),(225,'YT',NULL,'Mayotte'),(226,'ZA',NULL,'South Africa'),(227,'ZM',NULL,'Zambia'),(228,'ZR',NULL,'Zaire (former)'),(229,'ZW',NULL,'Zimbabwe'),(230,'US',NULL,'United States of America');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `error_logs`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `error_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_time` datetime NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `module` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `get_data` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_data` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `cookie_data` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_messages` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `referer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `created_time` (`created_time`),
  CONSTRAINT `error_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `error_logs`
--

LOCK TABLES `error_logs` WRITE;
/*!40000 ALTER TABLE `error_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `error_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `is_default` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'Default',1);
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `languages` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `native_name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `english_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'aa',NULL,'Afar'),(2,'ab',NULL,'Abkhazian'),(3,'af','Afrikaans','Afrikaans'),(4,'am',NULL,'Amharic'),(5,'ar','العَرَبِيَّة‎','Arabic'),(6,'as',NULL,'Assamese'),(7,'ay',NULL,'Aymara'),(8,'az',NULL,'Azerbaijani'),(9,'ba',NULL,'Bashkir'),(10,'be',NULL,'Belarusian'),(11,'bg',NULL,'Bulgarian'),(12,'bh',NULL,'Bihari'),(13,'bi',NULL,'Bislama'),(14,'bn','বাংলা','Bengali/Bangla'),(15,'bo','བོད་སྐད་','Tibetan'),(16,'br',NULL,'Breton'),(17,'ca','Català','Catalan'),(18,'co',NULL,'Corsican'),(19,'cs',NULL,'Czech'),(20,'cy',NULL,'Welsh'),(21,'da',NULL,'Danish'),(22,'de','Deutsch','German'),(23,'dz',NULL,'Bhutani'),(24,'el','ελληνικά','Greek'),(25,'en','English','English'),(26,'eo',NULL,'Esperanto'),(27,'es',NULL,'Spanish'),(28,'et',NULL,'Estonian'),(29,'eu',NULL,'Basque'),(30,'fa',NULL,'Persian'),(31,'fi',NULL,'Finnish'),(32,'fj',NULL,'Fiji'),(33,'fo',NULL,'Faeroese'),(34,'fr','Français','French'),(35,'fy',NULL,'Frisian'),(36,'ga',NULL,'Irish'),(37,'gd',NULL,'Scots/Gaelic'),(38,'gl',NULL,'Galician'),(39,'gn',NULL,'Guarani'),(40,'gu',NULL,'Gujarati'),(41,'ha',NULL,'Hausa'),(42,'hi',NULL,'Hindi'),(43,'hr',NULL,'Croatian'),(44,'hu',NULL,'Hungarian'),(45,'hy',NULL,'Armenian'),(46,'ia',NULL,'Interlingua'),(47,'ie',NULL,'Interlingue'),(48,'ik',NULL,'Inupiak'),(49,'in',NULL,'Indonesian'),(50,'is',NULL,'Icelandic'),(51,'it','Italiano','Italian'),(52,'iw',NULL,'Hebrew'),(53,'ja','日本語','Japanese'),(54,'ji','יידיש','Yiddish'),(55,'jw',NULL,'Javanese'),(56,'ka',NULL,'Georgian'),(57,'kk',NULL,'Kazakh'),(58,'kl',NULL,'Greenlandic'),(59,'km',NULL,'Cambodian'),(60,'kn',NULL,'Kannada'),(61,'ko',NULL,'Korean'),(62,'ks',NULL,'Kashmiri'),(63,'ku',NULL,'Kurdish'),(64,'ky',NULL,'Kirghiz'),(65,'la',NULL,'Latin'),(66,'ln',NULL,'Lingala'),(67,'lo',NULL,'Laothian'),(68,'lt',NULL,'Lithuanian'),(69,'lv',NULL,'Latvian/Lettish'),(70,'mg',NULL,'Malagasy'),(71,'mi',NULL,'Maori'),(72,'mk',NULL,'Macedonian'),(73,'ml',NULL,'Malayalam'),(74,'mn',NULL,'Mongolian'),(75,'mo',NULL,'Moldavian'),(76,'mr',NULL,'Marathi'),(77,'ms',NULL,'Malay'),(78,'mt',NULL,'Maltese'),(79,'my',NULL,'Burmese'),(80,'na',NULL,'Nauru'),(81,'ne',NULL,'Nepali'),(82,'nl','Nederlands','Dutch'),(83,'no',NULL,'Norwegian'),(84,'oc',NULL,'Occitan'),(85,'om',NULL,'(Afan)/Oromoor/Oriya'),(86,'pa',NULL,'Punjabi'),(87,'pl',NULL,'Polish'),(88,'ps',NULL,'Pashto/Pushto'),(89,'pt','Português','Portuguese'),(90,'qu',NULL,'Quechua'),(91,'rm',NULL,'Rhaeto-Romance'),(92,'rn',NULL,'Kirundi'),(93,'ro',NULL,'Romanian'),(94,'ru','русский язык','Russian'),(95,'rw',NULL,'Kinyarwanda'),(96,'sa',NULL,'Sanskrit'),(97,'sd',NULL,'Sindhi'),(98,'sg',NULL,'Sangro'),(99,'sh',NULL,'Serbo-Croatian'),(100,'si',NULL,'Singhalese'),(101,'sk',NULL,'Slovak'),(102,'sl',NULL,'Slovenian'),(103,'sm',NULL,'Samoan'),(104,'sn',NULL,'Shona'),(105,'so',NULL,'Somali'),(106,'sq','Shqip','Albanian'),(107,'sr',NULL,'Serbian'),(108,'ss',NULL,'Siswati'),(109,'st',NULL,'Sesotho'),(110,'su',NULL,'Sundanese'),(111,'sv',NULL,'Swedish'),(112,'sw',NULL,'Swahili'),(113,'ta',NULL,'Tamil'),(114,'te',NULL,'Telugu'),(115,'tg',NULL,'Tajik'),(116,'th','ภาษาไทย','Thai'),(117,'ti',NULL,'Tigrinya'),(118,'tk',NULL,'Turkmen'),(119,'tl',NULL,'Tagalog'),(120,'tn',NULL,'Setswana'),(121,'to',NULL,'Tonga'),(122,'tr','Türkçe','Turkish'),(123,'ts',NULL,'Tsonga'),(124,'tt',NULL,'Tatar'),(125,'tw',NULL,'Twi'),(126,'uk',NULL,'Ukrainian'),(127,'ur',NULL,'Urdu'),(128,'uz',NULL,'Uzbek'),(129,'vi','tiếng Việt','Vietnamese'),(130,'vo',NULL,'Volapuk'),(131,'wo',NULL,'Wolof'),(132,'xh','isiXhosa','Xhosa'),(133,'yo','Èdè Yorùbá','Yoruba'),(134,'zh','汉语/漢語','Chinese'),(135,'zu','isiZulu','Zulu');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locales`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locales` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` smallint(3) unsigned NOT NULL,
  `country_id` smallint(3) unsigned NOT NULL,
  `official_language` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `default_country` tinyint(1) unsigned DEFAULT NULL,
  `app_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `country_id` (`country_id`),
  KEY `official_language` (`official_language`),
  KEY `default_country` (`default_country`),
  KEY `app_default` (`app_default`),
  CONSTRAINT `fk_locales_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_locales_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=342 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locales`
--

LOCK TABLES `locales` WRITE;
/*!40000 ALTER TABLE `locales` DISABLE KEYS */;
INSERT INTO `locales` VALUES (1,3,147,0,0,0),(2,3,226,1,0,0),(3,4,63,1,0,0),(4,5,2,0,0,0),(5,5,21,0,0,0),(6,5,52,0,0,0),(7,5,56,0,0,0),(8,5,59,0,0,0),(9,5,60,0,0,0),(10,5,61,0,0,0),(11,5,95,0,0,0),(12,5,97,0,0,0),(13,5,102,0,0,0),(14,5,108,0,0,0),(15,5,112,0,0,0),(16,5,116,0,0,0),(17,5,125,0,0,0),(18,5,126,0,0,0),(19,5,138,0,0,0),(20,5,159,0,0,0),(21,5,169,0,0,0),(22,5,173,0,0,0),(23,5,178,1,0,0),(24,5,181,0,0,0),(25,5,191,0,0,0),(26,5,195,0,0,0),(27,5,198,0,0,0),(28,5,205,0,0,0),(29,5,224,0,0,0),(30,6,96,1,0,0),(31,10,31,1,0,0),(32,11,20,1,0,0),(33,14,17,1,0,0),(34,14,96,0,0,0),(35,15,43,0,0,0),(36,15,96,0,0,0),(37,16,69,1,0,0),(38,17,1,0,0,0),(39,17,62,0,0,0),(40,17,69,0,0,0),(41,17,100,0,0,0),(42,19,50,1,0,0),(43,20,71,1,0,0),(44,21,53,0,0,0),(45,21,78,0,0,0),(46,22,11,0,0,0),(47,22,18,0,0,0),(48,22,38,0,0,0),(49,22,51,0,0,0),(50,22,100,0,0,0),(51,22,118,0,0,0),(52,22,123,0,0,0),(53,23,29,1,0,0),(54,24,49,0,0,0),(55,24,83,0,0,0),(56,25,4,0,0,0),(57,25,5,0,0,0),(58,25,11,0,0,0),(59,25,12,0,0,0),(60,25,16,0,0,0),(61,25,18,0,0,0),(62,25,22,0,0,0),(63,25,24,0,0,0),(64,25,28,0,0,0),(65,25,30,0,0,0),(66,25,32,0,0,0),(67,25,33,0,0,0),(68,25,34,0,0,0),(69,25,38,0,0,0),(70,25,40,0,0,0),(71,25,42,0,0,0),(72,25,48,0,0,0),(73,25,49,0,0,0),(74,25,51,0,0,0),(75,25,53,0,0,0),(76,25,54,0,0,0),(77,25,61,0,0,0),(78,25,64,0,0,0),(79,25,65,0,0,0),(80,25,66,0,0,0),(81,25,67,0,0,0),(82,25,71,1,1,1),(83,25,72,0,0,0),(84,25,75,0,0,0),(85,25,76,0,0,0),(86,25,77,0,0,0),(87,25,79,0,0,0),(88,25,87,0,0,0),(89,25,88,0,0,0),(90,25,94,0,0,0),(91,25,95,0,0,0),(92,25,96,0,0,0),(93,25,101,0,0,0),(94,25,104,0,0,0),(95,25,107,0,0,0),(96,25,109,0,0,0),(97,25,113,0,0,0),(98,25,117,0,0,0),(99,25,120,0,0,0),(100,25,121,0,0,0),(101,25,129,0,0,0),(102,25,130,0,0,0),(103,25,135,0,0,0),(104,25,136,0,0,0),(105,25,139,0,0,0),(106,25,140,0,0,0),(107,25,141,0,0,0),(108,25,143,0,0,0),(109,25,145,0,0,0),(110,25,147,0,0,0),(111,25,150,0,0,0),(112,25,151,0,0,0),(113,25,153,0,0,0),(114,25,156,0,0,0),(115,25,157,0,0,0),(116,25,158,0,0,0),(117,25,163,0,0,0),(118,25,164,0,0,0),(119,25,165,0,0,0),(120,25,168,0,0,0),(121,25,171,0,0,0),(122,25,177,0,0,0),(123,25,179,0,0,0),(124,25,180,0,0,0),(125,25,181,0,0,0),(126,25,182,0,0,0),(127,25,183,0,0,0),(128,25,184,0,0,0),(129,25,185,0,0,0),(130,25,188,0,0,0),(131,25,196,0,0,0),(132,25,197,0,0,0),(133,25,203,0,0,0),(134,25,206,0,0,0),(135,25,208,0,0,0),(136,25,209,0,0,0),(137,25,211,0,0,0),(138,25,213,0,0,0),(139,25,230,1,0,0),(140,25,216,0,0,0),(141,25,218,0,0,0),(142,25,219,0,0,0),(143,25,221,0,0,0),(144,25,223,0,0,0),(145,25,226,0,0,0),(146,25,227,0,0,0),(147,25,229,0,0,0),(148,27,10,0,0,0),(149,27,26,0,0,0),(150,27,27,0,0,0),(151,27,32,0,0,0),(152,27,41,0,0,0),(153,27,44,0,0,0),(154,27,45,0,0,0),(155,27,46,0,0,0),(156,27,55,0,0,0),(157,27,57,0,0,0),(158,27,62,0,0,0),(159,27,82,0,0,0),(160,27,85,0,0,0),(161,27,89,0,0,0),(162,27,144,0,0,0),(163,27,152,0,0,0),(164,27,160,0,0,0),(165,27,161,0,0,0),(166,27,164,0,0,0),(167,27,172,0,0,0),(168,27,194,0,0,0),(169,27,230,0,0,0),(170,27,214,0,0,0),(171,27,217,0,0,0),(172,28,58,1,0,0),(173,29,62,1,0,0),(174,30,3,0,0,0),(175,30,98,0,0,0),(176,31,64,1,0,0),(177,33,53,0,0,0),(178,33,68,0,0,0),(179,34,18,0,0,0),(180,34,19,0,0,0),(181,34,22,0,0,0),(182,34,23,0,0,0),(183,34,33,0,0,0),(184,34,35,0,0,0),(185,34,36,0,0,0),(186,34,37,0,0,0),(187,34,38,0,0,0),(188,34,39,0,0,0),(189,34,42,0,0,0),(190,34,52,0,0,0),(191,34,56,0,0,0),(192,34,69,1,1,0),(193,34,70,0,0,0),(194,34,74,0,0,0),(195,34,80,0,0,0),(196,34,81,0,0,0),(197,34,82,0,0,0),(198,34,91,0,0,0),(199,34,108,0,0,0),(200,34,123,0,0,0),(201,34,126,0,0,0),(202,34,127,0,0,0),(203,34,129,0,0,0),(204,34,132,0,0,0),(205,34,137,0,0,0),(206,34,138,0,0,0),(207,34,141,0,0,0),(208,34,148,0,0,0),(209,34,149,0,0,0),(210,34,162,0,0,0),(211,34,167,0,0,0),(212,34,174,0,0,0),(213,34,177,0,0,0),(214,34,180,0,0,0),(215,34,190,0,0,0),(216,34,195,0,0,0),(217,34,198,0,0,0),(218,34,200,0,0,0),(219,34,205,0,0,0),(220,34,221,0,0,0),(221,34,222,0,0,0),(222,34,225,0,0,0),(223,35,153,1,0,0),(224,36,94,1,0,0),(225,37,71,1,0,0),(226,38,62,1,0,0),(227,40,96,1,0,0),(228,41,76,0,0,0),(229,41,149,0,0,0),(230,41,151,0,0,0),(231,42,96,1,0,0),(232,43,15,0,0,0),(233,43,90,0,0,0),(234,44,92,1,0,0),(235,45,7,1,0,0),(236,50,99,1,0,0),(237,51,38,0,0,0),(238,51,100,1,1,0),(239,51,189,1,0,0),(240,53,103,1,0,0),(241,56,73,1,0,0),(242,57,114,1,0,0),(243,58,78,1,0,0),(244,59,106,1,0,0),(245,60,96,1,0,0),(246,61,110,0,0,0),(247,61,111,0,0,0),(248,62,96,1,0,0),(249,64,105,1,0,0),(250,66,9,0,0,0),(251,66,35,0,0,0),(252,66,36,0,0,0),(253,66,37,0,0,0),(254,67,115,1,0,0),(255,68,122,1,0,0),(256,69,124,1,0,0),(257,70,129,1,0,0),(258,72,131,1,0,0),(259,73,96,1,0,0),(260,74,134,1,0,0),(261,76,96,1,0,0),(262,77,25,0,0,0),(263,77,145,0,0,0),(264,77,183,0,0,0),(265,78,140,1,0,0),(266,79,133,1,0,0),(267,81,96,0,0,0),(268,81,155,0,0,0),(269,82,13,0,0,0),(270,82,18,0,0,0),(271,82,153,0,0,0),(272,82,192,0,0,0),(273,85,63,0,0,0),(274,85,104,0,0,0),(275,87,166,1,0,0),(276,88,3,1,0,0),(277,89,9,0,0,0),(278,89,27,0,0,0),(279,89,38,0,0,0),(280,89,47,0,0,0),(281,89,82,0,0,0),(282,89,86,0,0,0),(283,89,123,0,0,0),(284,89,135,0,0,0),(285,89,146,0,0,0),(286,89,170,1,0,0),(287,89,193,0,0,0),(288,90,26,0,0,0),(289,90,57,0,0,0),(290,90,161,0,0,0),(291,91,38,1,0,0),(292,92,22,1,0,0),(293,93,128,0,0,0),(294,93,175,0,0,0),(295,94,31,0,0,0),(296,94,105,0,0,0),(297,94,114,0,0,0),(298,94,128,0,0,0),(299,94,176,0,0,0),(300,94,212,0,0,0),(301,95,177,1,0,0),(302,97,165,1,0,0),(303,98,36,1,0,0),(304,100,119,1,0,0),(305,101,187,1,0,0),(306,102,185,1,0,0),(307,104,229,1,0,0),(308,105,52,0,0,0),(309,105,63,0,0,0),(310,105,104,0,0,0),(311,105,191,0,0,0),(312,106,6,0,0,0),(313,106,131,0,0,0),(314,111,64,0,0,0),(315,111,182,0,0,0),(316,112,35,0,0,0),(317,112,104,0,0,0),(318,112,211,0,0,0),(319,112,213,0,0,0),(320,113,96,0,0,0),(321,113,119,0,0,0),(322,113,145,0,0,0),(323,113,183,0,0,0),(324,114,96,1,0,0),(325,115,202,1,0,0),(326,116,201,1,0,0),(327,117,61,0,0,0),(328,117,63,0,0,0),(329,118,204,1,0,0),(330,121,206,1,0,0),(331,122,49,0,0,0),(332,122,207,0,0,0),(333,124,176,1,0,0),(334,126,212,1,0,0),(335,127,96,0,0,0),(336,127,165,0,0,0),(337,129,220,1,0,0),(338,131,190,1,0,0),(339,133,23,0,0,0),(340,133,151,0,0,0),(341,135,226,1,0,0);
/*!40000 ALTER TABLE `locales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `version` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `date_released` datetime NOT NULL,
  `app_version` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `installed_by` int(4) unsigned NOT NULL,
  `date_installed` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `installed_by` (`installed_by`),
  KEY `date_installed` (`date_installed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'api','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(2,'developer','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(3,'languages','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(4,'modules','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(5,'options','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(6,'rules','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(7,'selftest','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(8,'templates','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(9,'user','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(10,'users','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(11,'tools','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(12,'countries','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(13,'locales','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00'),(14,'translator','1.0','2017-01-01 00:00:00','1.0',1,'2017-01-01 00:00:00');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `options`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `options` (
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `label` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `type` enum('text','textarea','bool','int','list','custom') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `value` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `list_options` text COLLATE utf8mb4_unicode_ci,
  `group` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`name`),
  KEY `group` (`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options`
--

LOCK TABLES `options` WRITE;
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
INSERT INTO `options` VALUES ('development','DEVELOPMENT','bool','1',NULL,'debug'),('pagination_pages','ITEMS_PER_PAGE','int','12',NULL,'site'),('session_time','SESSION_TIME','int','120',NULL,'site'),('show_log','SHOW_LOG','bool','1',NULL,'debug'),('webservice_timeout','WEBSERVICE_TIMEOUT','int','8',NULL,'services'),('admin_emails','ADMIN_EMAILS','text','em@il.address',NULL,'site');
/*!40000 ALTER TABLE `options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rules`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `rules` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_only` tinyint(1) NOT NULL DEFAULT '0',
  `module_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_action` (`module_id`,`action`) USING BTREE,
  CONSTRAINT `fk_rules_modules` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rules`
--

LOCK TABLES `rules` WRITE;
/*!40000 ALTER TABLE `rules` DISABLE KEYS */;
INSERT INTO `rules` VALUES (1,NULL,0,1),(2,NULL,1,2),(4,NULL,0,4),(5,NULL,1,5),(6,NULL,1,6),(7,NULL,1,7),(8,NULL,0,8),(9,NULL,0,9),(10,NULL,0,10),(11,NULL,1,11),(12,NULL,1,12),(13,NULL,1,13),(14,NULL,1,14);
/*!40000 ALTER TABLE `rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `id_user` int(4) unsigned DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `timezone_offset` decimal(2,1) DEFAULT NULL,
  `timezone_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`id_user`,`start_time`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `templates`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `version` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `date_released` datetime NOT NULL,
  `app_version` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1.0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `installed_by` int(4) unsigned NOT NULL,
  `date_installed` datetime NOT NULL,
  `derived` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `palette` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `installed_by` (`installed_by`),
  KEY `date_installed` (`date_installed`),
  CONSTRAINT `fk_templates_users` FOREIGN KEY (`installed_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `templates`
--

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
INSERT INTO `templates` VALUES (1,'Basic','1.0','2017-01-07 12:00:00','1.0',1,1,'2017-01-07 12:00:00',0,'#1AB394,#1C84C6,#9C9C9C,#636363');
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(3) unsigned NOT NULL,
  `locale_id` smallint(3) unsigned NOT NULL,
  `ldap_user` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `hash` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` int(1) unsigned NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `faults` int(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `group_id` (`group_id`),
  KEY `admin` (`admin`),
  KEY `locale_id` (`locale_id`),
  KEY `ldap_user` (`ldap_user`),
  CONSTRAINT `fk_users_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `fk_users_locales` FOREIGN KEY (`locale_id`) REFERENCES `locales` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
