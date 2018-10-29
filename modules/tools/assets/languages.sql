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
-- Table structure for table `languages`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;