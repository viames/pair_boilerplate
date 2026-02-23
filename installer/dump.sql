/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acl`
--

CREATE TABLE IF NOT EXISTS `acl` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`rule_id` int unsigned NOT NULL,
	`group_id` int unsigned DEFAULT NULL,
	`is_default` tinyint unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE KEY `rule_id` (`rule_id`,`group_id`),
	KEY `group_id` (`group_id`) USING BTREE,
	CONSTRAINT `fk_acl_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `fk_acl_rules` FOREIGN KEY (`rule_id`) REFERENCES `rules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `acl`
--

LOCK TABLES `acl` WRITE;
/*!40000 ALTER TABLE `acl` DISABLE KEYS */;

INSERT INTO `acl` (`id`, `rule_id`, `group_id`, `is_default`)
VALUES
	(1,1,1,0),
	(2,2,1,0),
	(3,3,1,0),
	(4,4,1,0),
	(5,5,1,0),
	(6,6,1,0),
	(7,7,1,0),
	(8,8,1,0),
	(9,9,1,0),
	(10,10,1,1),
	(11,11,1,0),
	(12,12,1,0),
	(13,13,1,0),
	(14,14,1,0),
	(15,15,1,0),
	(16,16,1,0),
	(17,17,1,0),
	(18,18,1,0),
	(19,19,1,0),
	(20,20,1,0);

/*!40000 ALTER TABLE `acl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit`
--

CREATE TABLE IF NOT EXISTS `audit` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`user_id` int unsigned DEFAULT NULL,
	`event` enum('password_changed','login_failed','login_successful','logout','session_expired','remember_me_login','user_created','user_deleted','user_changed','permissions_changed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`created_at` timestamp NOT NULL,
	`details` json DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `user_id` (`user_id`),
	KEY `created_at` (`created_at`),
	CONSTRAINT `fk_audit_access_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
	`id` smallint unsigned NOT NULL AUTO_INCREMENT,
	`code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`native_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
	`english_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	PRIMARY KEY (`id`),
	UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;

INSERT INTO `countries` (`id`, `code`, `native_name`, `english_name`)
VALUES
	(1,'AD','Andorra','Andorra'),
	(2,'AE','دولة الإمارات العربية المتحدة‎','United Arab Emirates'),
	(3,'AF','جمھوری اسلامی افغانستان','Afghanistan'),
	(4,'AG','Antigua and Barbuda','Antigua and Barbuda'),
	(5,'AI','Anguilla','Anguilla'),
	(6,'AL','Shqipëria','Albania'),
	(7,'AM','Հայաստանի Հանրապետություն','Armenia'),
	(8,'AN','Nederlandse Antillen','Netherlands Antilles'),
	(9,'AO','Angola','Angola'),
	(10,'AR','Argentina','Argentina'),
	(11,'AT','Österreich','Austria'),
	(12,'AU','Australia','Australia'),
	(13,'AW','Aruba','Aruba'),
	(14,'AZ','Azərbaycan','Azerbaijan'),
	(15,'BA','Босна и Херцеговина','Bosnia and Herzegovina'),
	(16,'BB','Barbados','Barbados'),
	(17,'BD','গণপ্রজাতন্ত্রী বাংলাদেশ','Bangladesh'),
	(18,'BE','België','Belgium'),
	(19,'BF','Burkina Faso','Burkina Faso'),
	(20,'BG','България','Bulgaria'),
	(21,'BH','البحرين‎','Bahrain'),
	(22,'BI','Uburundi','Burundi'),
	(23,'BJ','Bénin','Benin'),
	(24,'BM','Bermuda','Bermuda'),
	(25,'BN','Brunei','Brunei'),
	(26,'BO','Bolivia','Bolivia'),
	(27,'BR','Brasil','Brazil'),
	(28,'BS','Bahamas','Bahamas'),
	(29,'BT','འབྲུག་རྒྱལ་ཁབ་','Bhutan'),
	(30,'BW','Botswana','Botswana'),
	(31,'BY','Беларусь','Belarus'),
	(32,'BZ','Belize','Belize'),
	(33,'CA','Canada','Canada'),
	(34,'CC','Pulu Kokos (Keeling)','Cocos (Keeling) Islands'),
	(35,'CD','République démocratique du Congo','Democratic Republic of the Congo'),
	(36,'CF','Ködörösêse tî Bêafrîka','Central African Republic'),
	(37,'CG','Congo','Congo'),
	(38,'CH','Schweiz','Switzerland'),
	(39,'CI','Côte d\'Ivoire','Ivory Coast'),
	(40,'CK','Cook Islands','Cook Islands'),
	(41,'CL','Chile','Chile'),
	(42,'CM','Cameroun','Cameroon'),
	(43,'CN','中华人民共和国','China'),
	(44,'CO','Colombia','Colombia'),
	(45,'CR','Costa Rica','Costa Rica'),
	(46,'CU','Cuba','Cuba'),
	(47,'CV','Cabo Verde','Cape Verde'),
	(48,'CX','Christmas Island','Christmas Island'),
	(49,'CY','Κύπρος','Cyprus'),
	(50,'CZ','Česká republika','Czech Republic'),
	(51,'DE','Deutschland','Germany'),
	(52,'DJ','Jamhuuriyadda Jabuuti','Djibouti'),
	(53,'DK','Danmark','Denmark'),
	(54,'DM','Dominica','Dominica'),
	(55,'DO','República Dominicana','Dominican Republic'),
	(56,'DZ','الجزائر‎','Algeria'),
	(57,'EC','Ecuador','Ecuador'),
	(58,'EE','Eesti','Estonia'),
	(59,'EG','مَصر‎','Egypt'),
	(60,'EH','الصحراء الغربية‎‎','Western Sahara'),
	(61,'ER','ኤርትራ','Eritrea'),
	(62,'ES','España','Spain'),
	(63,'ET','ኢትዮጵያ','Ethiopia'),
	(64,'FI','Suomi','Finland'),
	(65,'FJ','Viti','Fiji'),
	(66,'FK','Falkland Islands','Falkland Islands'),
	(67,'FM','Federated States of Micronesia','Federated States of Micronesia'),
	(68,'FO','Føroyar','Faroe Islands'),
	(69,'FR','France','France'),
	(70,'GA','Gabon','Gabon'),
	(71,'GB','United Kingdom','United Kingdom'),
	(72,'GD','Grenada','Grenada'),
	(73,'GE','საქართველო','Georgia'),
	(74,'GF','Guyane française','French Guiana'),
	(75,'GG','Guernsey','Guernsey'),
	(76,'GH','Ghana','Ghana'),
	(77,'GI','Gibraltar','Gibraltar'),
	(78,'GL','Kalaallit Nunaat','Greenland'),
	(79,'GM','Gambia','Gambia'),
	(80,'GN','Guinée','Guinea'),
	(81,'GP','Guadeloupe','Guadeloupe'),
	(82,'GQ','Guinea Ecuatorial','Equatorial Guinea'),
	(83,'GR','Ελλάδα','Greece'),
	(84,'GS','S. Georgia and S. Sandwich Islands','S. Georgia and S. Sandwich Islands'),
	(85,'GT','Guatemala','Guatemala'),
	(86,'GW','Guiné-Bissau','Guinea-Bissau'),
	(87,'GY','Guyana','Guyana'),
	(88,'HK','香港','Hong Kong'),
	(89,'HN','Honduras','Honduras'),
	(90,'HR','Hrvatska','Croatia'),
	(91,'HT','Haïti','Haiti'),
	(92,'HU','Magyarország','Hungary'),
	(93,'ID','Indonesia','Indonesia'),
	(94,'IE','Éire','Ireland'),
	(95,'IL','יִשְׂרָאֵל','Israel'),
	(96,'IN','Bhārat','India'),
	(97,'IQ','العراق','Iraq'),
	(98,'IR','Irān','Iran'),
	(99,'IS','Ísland','Iceland'),
	(100,'IT','Italia','Italy'),
	(101,'JM','Jamaica','Jamaica'),
	(102,'JO','الْأُرْدُنّ','Jordan'),
	(103,'JP','Nippon','Japan'),
	(104,'KE','Kenya','Kenya'),
	(105,'KG','Кыргызстан Kyrgyzstan','Kyrgyzstan'),
	(106,'KH','ព្រះរាជាណាចក្រកម្ពុជា','Cambodia'),
	(107,'KI','Kiribati','Kiribati'),
	(108,'KM','Komori','Comoros'),
	(109,'KN','Saint Kitts and Nevis','Saint Kitts and Nevis'),
	(110,'KP','북한','North Korea'),
	(111,'KR','대한민국','South Korea'),
	(112,'KW','الكويت‎','Kuwait'),
	(113,'KY','Cayman Islands','Cayman Islands'),
	(114,'KZ','Қазақстан','Kazakhstan'),
	(115,'LA','ລາວ','Laos'),
	(116,'LB','لبنان‎','Lebanon'),
	(117,'LC','Sainte-Lucie','Saint Lucia'),
	(118,'LI','Liechtenstein','Liechtenstein'),
	(119,'LK','ශ්‍රී ලංකා','Sri Lanka'),
	(120,'LR','Liberia','Liberia'),
	(121,'LS','Lesotho','Lesotho'),
	(122,'LT','Lietuva','Lithuania'),
	(123,'LU','Lëtzebuerg','Luxembourg'),
	(124,'LV','Latvija','Latvia'),
	(125,'LY','ليبيا‎','Libya'),
	(126,'MA','المغرب‎','Morocco'),
	(127,'MC','Monaco','Monaco'),
	(128,'MD','Moldova','Moldova'),
	(129,'MG','Madagasikara','Madagascar'),
	(130,'MH','Aolepān Aorōkin M̧ajeļ','Marshall Islands'),
	(131,'MK','Македонија','Macedonia'),
	(132,'ML','Mali','Mali'),
	(133,'MM','ပြည်ထောင်စုသမ္မတ မြန်မာနိုင်ငံတော်','Myanmar'),
	(134,'MN','Monggol Ulus','Mongolia'),
	(135,'MO','澳門','Macao'),
	(136,'MP','Sankattan Siha Na Islas Mariånas','Northern Mariana Islands'),
	(137,'MQ','Martinique','Martinique'),
	(138,'MR','موريتانيا','Mauritania'),
	(139,'MS','Montserrat','Montserrat'),
	(140,'MT','Malta','Malta'),
	(141,'MU','Maurice','Mauritius'),
	(142,'MV','ދިވެހިރާއްޖެ','Maldives'),
	(143,'MW','Malaŵi','Malawi'),
	(144,'MX','México','Mexico'),
	(145,'MY','مليسيا','Malaysia'),
	(146,'MZ','Moçambique','Mozambique'),
	(147,'NA','Namibia','Namibia'),
	(148,'NC','Nouvelle-Calédonie','New Caledonia'),
	(149,'NE','Niger','Niger'),
	(150,'NF','Norf\'k Ailen','Norfolk Island'),
	(151,'NG','Nigeria','Nigeria'),
	(152,'NI','Nicaragua','Nicaragua'),
	(153,'NL','Nederlanden','Netherlands'),
	(154,'NO','Norge','Norway'),
	(155,'NP','नेपाल','Nepal'),
	(156,'NR','Naoero','Nauru'),
	(157,'NU','Niuē','Niue'),
	(158,'NZ','Aotearoa','New Zealand'),
	(159,'OM','عمان','Oman'),
	(160,'PA','Panamá','Panama'),
	(161,'PE','Perú','Peru'),
	(162,'PF','Polynésie française','French Polynesia'),
	(163,'PG','Papua Niu Gini','Papua New Guinea'),
	(164,'PH','Pilipinas','Philippines'),
	(165,'PK','پاکِستان‬‎','Pakistan'),
	(166,'PL','Polska','Poland'),
	(167,'PM','Saint-Pierre-et-Miquelon','Saint Pierre and Miquelon'),
	(168,'PN','Pitkern Ailen','Pitcairn Islands'),
	(169,'PS','دولة فلسطين','Palestine'),
	(170,'PT','Portugal','Portugal'),
	(171,'PW','Belau','Palau'),
	(172,'PY','Paraguái','Paraguay'),
	(173,'QA','قطر','Qatar'),
	(174,'RE','La Réunion','Réunion'),
	(175,'RO','România','Romania'),
	(176,'RU','Росси́я','Russia'),
	(177,'RW','U Rwanda','Rwanda'),
	(178,'SA','السعودية‎','Saudi Arabia'),
	(179,'SB','Solomon Islands','Solomon Islands'),
	(180,'SC','Seychelles','Seychelles'),
	(181,'SD','السودان‎','Sudan'),
	(182,'SE','Sverige','Sweden'),
	(183,'SG','Singapura','Singapore'),
	(184,'SH','Saint Helena','Saint Helena'),
	(185,'SI','Slovenija','Slovenia'),
	(186,'SJ','Svalbard og Jan Mayen','Svalbard and Jan Mayen'),
	(187,'SK','Slovensko','Slovakia'),
	(188,'SL','Sierra Leone','Sierra Leone'),
	(189,'SM','San Marino','San Marino'),
	(190,'SN','Sénégal','Senegal'),
	(191,'SO','Soomaaliya','Somalia'),
	(192,'SR','Suriname','Suriname'),
	(193,'ST','São Tomé and Príncipe','São Tomé and Príncipe'),
	(194,'SV','El Salvador','El Salvador'),
	(195,'SY','سوريا‎','Syria'),
	(196,'SZ','eSwatini','Swaziland'),
	(197,'TC','Turks and Caicos Islands','Turks and Caicos Islands'),
	(198,'TD','جمهورية تشاد','Chad'),
	(199,'TF','Terres australes et antarctiques françaises','French Southern Territories'),
	(200,'TG','Togo','Togo'),
	(201,'TH','ประเทศไทย','Thailand'),
	(202,'TJ','Тоҷикистон','Tajikistan'),
	(203,'TK','Tokelau','Tokelau'),
	(204,'TM','Türkmenistan','Turkmenistan'),
	(205,'TN','تونس','Tunisia'),
	(206,'TO','Tonga','Tonga'),
	(207,'TR','Türkiye','Turkey'),
	(208,'TT','Trinidad and Tobago','Trinidad and Tobago'),
	(209,'TV','Tuvalu','Tuvalu'),
	(210,'TW','中華民國','Taiwan'),
	(211,'TZ','Tanzania','Tanzania'),
	(212,'UA','Україна','Ukraine'),
	(213,'UG','Uganda','Uganda'),
	(214,'UY','Uruguay','Uruguay'),
	(215,'UZ','Oʻzbekiston','Uzbekistan'),
	(216,'VC','Saint Vincent and the Grenadines','Saint Vincent and the Grenadines'),
	(217,'VE','Venezuela','Venezuela'),
	(218,'VG','British Virgin Islands','British Virgin Islands'),
	(219,'VI','United States Virgin Islands','United States Virgin Islands'),
	(220,'VN','Việt Nam','Vietnam'),
	(221,'VU','Vanuatu','Vanuatu'),
	(222,'WF','Wallis-et-Futuna','Wallis and Futuna'),
	(223,'WS','Sāmoa','Samoa'),
	(224,'YE','ٱلْيَمَن','Yemen'),
	(225,'YT','Mayotte','Mayotte'),
	(226,'ZA','Afrika','South Africa'),
	(227,'ZM','Zambia','Zambia'),
	(228,'ZR','Zaïre','Zaire'),
	(229,'ZW','Zimbabwe','Zimbabwe'),
	(230,'US','United States','United States'),
	(231,'XK','Kosova','Kosovo'),
	(232,'AX','Åland','Åland Islands'),
	(233,'AS','Amerika Sāmoa','American Samoa'),
	(234,'AQ','Antarctic','Antarctica'),
	(235,'BQ','Caribisch Nederland','Caribbean Netherlands'),
	(236,'BV','Bouvetøya','Bouvet Island'),
	(237,'IO','British Indian Ocean Territory','British Indian Ocean Territory'),
	(238,'CW','Curaçao','Curaçao'),
	(239,'GU','Guåhån','Guam'),
	(240,'HM','Heard Island and McDonald Islands','Heard Island and McDonald Islands'),
	(241,'VA','Santa Sede','Holy See'),
	(242,'IM','Ellan Vannin','Isle of Man'),
	(243,'JE','Jersey','Jersey'),
	(244,'ME','Црна Гора','Montenegro'),
	(245,'BL','Saint-Barthélemy','Saint Barthélemy'),
	(246,'MF','Saint-Martin','Saint Martin'),
	(247,'RS','Србија','Serbia'),
	(248,'SX','Sint Maarten','Sint Maarten'),
	(249,'SS','South Sudan','South Sudan'),
	(250,'TL','Timór Lorosa\'e','East Timor'),
	(251,'UM','United States Minor Outlying Islands','United States Minor Outlying Islands');

/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `error_logs`
--

CREATE TABLE IF NOT EXISTS `error_logs` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`level` int unsigned DEFAULT '8',
	`user_id` int unsigned DEFAULT NULL,
	`path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
	`get_data` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
	`post_data` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
	`files_data` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
	`cookie_data` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
	`description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
	`user_messages` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
	`referer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
	`created_at` timestamp NOT NULL,
	PRIMARY KEY (`id`),
	KEY `user_id` (`user_id`),
	KEY `created_at` (`created_at`),
	CONSTRAINT `fk_error_logs_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`is_default` int unsigned NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;

INSERT INTO `groups` (`id`, `name`, `is_default`)
VALUES
	(1,'Default',1);

/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
	`id` smallint unsigned NOT NULL AUTO_INCREMENT,
	`code` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`native_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`english_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	PRIMARY KEY (`id`),
	UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;

INSERT INTO `languages` (`id`, `code`, `native_name`, `english_name`)
VALUES
	(1,'aa','Afaraf','Afar'),
	(2,'ab','аҧсуа бызшәа','Abkhazian'),
	(3,'af','Afrikaans','Afrikaans'),
	(4,'am','አማርኛ','Amharic'),
	(5,'ar','العَرَبِيَّة‎','Arabic'),
	(6,'as','অসমীয়া','Assamese'),
	(7,'ay','aymar aru','Aymara'),
	(8,'az','azərbaycan dili','Azerbaijani'),
	(9,'ba','башҡорт теле','Bashkir'),
	(10,'be','беларуская мова','Belarusian'),
	(11,'bg','български език','Bulgarian'),
	(12,'bh','भोजपुरी','Bihari'),
	(13,'bi','Bislama','Bislama'),
	(14,'bn','বাংলা','Bengali'),
	(15,'bo','བོད་ཡིག','Tibetan'),
	(16,'br','brezhoneg','Breton'),
	(17,'ca','Català','Catalan'),
	(18,'co','corsu','Corsican'),
	(19,'cs','čeština','Czech'),
	(20,'cy','Cymraeg','Welsh'),
	(21,'da','dansk','Danish'),
	(22,'de','Deutsch','German'),
	(23,'dz','རྫོང་ཁ','Dzongkha'),
	(24,'el','ελληνικά','Greek'),
	(25,'en','English','English'),
	(26,'eo','Esperanto','Esperanto'),
	(27,'es','Español','Spanish'),
	(28,'et','eesti','Estonian'),
	(29,'eu','euskara','Basque'),
	(30,'fa','فارسی','Persian'),
	(31,'fi','suomi','Finnish'),
	(32,'fj','vosa Vakaviti','Fijian'),
	(33,'fo','føroyskt','Faeroese'),
	(34,'fr','français','French'),
	(35,'fy','Frysk','Western Frisian'),
	(36,'ga','Gaeilge','Irish'),
	(37,'gd','Gàidhlig','Gaelic'),
	(38,'gl','Galego','Galician'),
	(39,'gn','Avañe\'ẽ','Guaraní'),
	(40,'gu','ગુજરાતી','Gujarati'),
	(41,'ha','هَوُسَ','Hausa'),
	(42,'hi','हिन्दी','Hindi'),
	(43,'hr','hrvatski','Croatian'),
	(44,'hu','magyar','Hungarian'),
	(45,'hy','Հայերեն','Armenian'),
	(46,'ia','Interlingua','Interlingua'),
	(47,'ie','Interlingue','Interlingue'),
	(48,'ik','Iñupiaq','Inupiaq'),
	(49,'id','Bahasa Indonesia','Indonesian'),
	(50,'is','Íslenska','Icelandic'),
	(51,'it','Italiano','Italian'),
	(52,'he','עברית','Hebrew'),
	(53,'ja','日本語','Japanese'),
	(54,'yi','ייִדיש','Yiddish'),
	(55,'jv','ꦧꦱꦗꦮ','Javanese'),
	(56,'ka','ქართული','Georgian'),
	(57,'kk','қазақ тілі','Kazakh'),
	(58,'kl','kalaallisut','Greenlandic'),
	(59,'km','ខ្មែរ, ខេមរភាសា, ភាសាខ្មែរ','Central Khmer'),
	(60,'kn','ಕನ್ನಡ','Kannada'),
	(61,'ko','한국어','Korean'),
	(62,'ks','कश्मीरी','Kashmiri'),
	(63,'ku','Kurdî','Kurdish'),
	(64,'ky','Кыргызча','Kirghiz'),
	(65,'la','latine','Latin'),
	(66,'ln','Lingála','Lingala'),
	(67,'lo','ພາສາລາວ','Lao'),
	(68,'lt','lietuvių kalba','Lithuanian'),
	(69,'lv','latviešu valoda','Latvian'),
	(70,'mg','fiteny malagasy','Malagasy'),
	(71,'mi','te reo Māori','Maori'),
	(72,'mk','македонски јазик','Macedonian'),
	(73,'ml','മലയാളം','Malayalam'),
	(74,'mn','Монгол хэл','Mongolian'),
	(75,'ae','avesta','Avestan'),
	(76,'mr','मराठी','Marathi'),
	(77,'ms','Bahasa Melayu','Malay'),
	(78,'mt','Malti','Maltese'),
	(79,'my','ဗမာစာ','Burmese'),
	(80,'na','dorerin Naoero','Nauruan'),
	(81,'ne','नेपाली','Nepali'),
	(82,'nl','Nederlands','Dutch'),
	(83,'no','Norsk','Norwegian'),
	(84,'oc','occitan','Occitan'),
	(85,'om','Afaan Oromoo','Oromo'),
	(86,'pa','ਪੰਜਾਬੀ','Panjabi'),
	(87,'pl','język polski','Polish'),
	(88,'ps','پښتو','Pashto'),
	(89,'pt','Português','Portuguese'),
	(90,'qu','Runa Simi','Quechua'),
	(91,'rm','Rumantsch Grischun','Romansh'),
	(92,'rn','Ikirundi','Rundi'),
	(93,'ro','Română','Romanian'),
	(94,'ru','русский','Russian'),
	(95,'rw','Ikinyarwanda','Kinyarwanda'),
	(96,'sa','संस्कृतम्','Sanskrit'),
	(97,'sd','सिन्धी','Sindhi'),
	(98,'sg','yângâ tî sängö','Sango'),
	(99,'lb','Lëtzebuergesch','Luxembourgish'),
	(100,'si','සිංහල','Sinhalese'),
	(101,'sk','Slovenčina','Slovak'),
	(102,'sl','Slovenski Jezik','Slovene'),
	(103,'sm','gagana fa\'a Samoa','Samoan'),
	(104,'sn','chiShona','Shona'),
	(105,'so','Soomaaliga','Somali'),
	(106,'sq','Shqip','Albanian'),
	(107,'sr','српски језик','Serbian'),
	(108,'ss','SiSwati','Swazi'),
	(109,'st','Sesotho','Southern Sotho'),
	(110,'su','Basa Sunda','Sundanese'),
	(111,'sv','Svenska','Swedish'),
	(112,'sw','Kiswahili','Swahili'),
	(113,'ta','தமிழ்','Tamil'),
	(114,'te','తెలుగు','Telugu'),
	(115,'tg','тоҷикӣ','Tajik'),
	(116,'th','ไทย','Thai'),
	(117,'ti','ትግርኛ','Tigrinya'),
	(118,'tk','Türkmen','Turkmen'),
	(119,'tl','Wikang Tagalog','Tagalog'),
	(120,'tn','Setswana','Tswana'),
	(121,'to','Faka Tonga','Tongan'),
	(122,'tr','Türkçe','Turkish'),
	(123,'ts','Xitsonga','Tsonga'),
	(124,'tt','татар теле','Tatar'),
	(125,'tw','Twi','Twi'),
	(126,'uk','Українська','Ukrainian'),
	(127,'ur','اردو','Urdu'),
	(128,'uz','Oʻzbek','Uzbek'),
	(129,'vi','Tiếng Việt','Vietnamese'),
	(130,'vo','Volapük','Volapük'),
	(131,'wo','Wollof','Wolof'),
	(132,'xh','isiXhosa','Xhosa'),
	(133,'yo','Yorùbá','Yoruba'),
	(134,'zh','中文','Chinese'),
	(135,'zu','isiZulu','Zulu'),
	(136,'or','ଓଡ଼ିଆ','Oriya'),
	(137,'mh','Kajin M̧ajeļ','Marshallese'),
	(138,'ak','Akan','Akan'),
	(139,'an','aragonés','Aragonese'),
	(140,'av','авар мацӀ','Avaric'),
	(141,'bm','bamanankan','Bambara'),
	(142,'bs','bosanski jezik','Bosnian'),
	(143,'ch','Chamoru','Chamorro'),
	(144,'ce','нохчийн мотт','Chechen'),
	(145,'ny','chiCheŵa','Chichewa'),
	(146,'cu','ѩзыкъ словѣньскъ','Church Slavic'),
	(147,'cv','чӑваш чӗлхи','Chuvash'),
	(148,'kw','Kernewek','Cornish'),
	(149,'cr','ᓀᐦᐃᔭᐍᐏᐣ','Cree'),
	(150,'dv','ދިވެހި','Maldivian'),
	(151,'ee','Eʋegbe','Ewe'),
	(152,'ff','Fulfulde','Fulah'),
	(153,'ki','Gĩkũyũ','Kikuyu'),
	(154,'lg','Luganda','Ganda'),
	(155,'ht','Kreyòl ayisyen','Haitian'),
	(156,'hz','Otjiherero','Herero'),
	(157,'ho','Police Motu','Hiri Motu'),
	(158,'io','Ido','Ido'),
	(159,'ig','Asụsụ Igbo','Igbo'),
	(160,'iu','ᐃᓄᒃᑎᑐᑦ','Inuktitut'),
	(161,'kr','Kanuri','Kanuri'),
	(162,'gv','Gaelg','Manx'),
	(163,'kv','коми кыв','Komi'),
	(164,'kg','Kikongo','Kongo'),
	(165,'kj','Kuanyama','Kuanyama'),
	(166,'li','Limburgs','Limburgan'),
	(167,'lu','Kiluba','Luba-Katanga'),
	(168,'sc','sardu','Sardinian'),
	(169,'nv','Diné bizaad','Navajo'),
	(170,'ng','Owambo','Ndonga'),
	(171,'nd','isiNdebele','North Ndebele'),
	(172,'se','Davvisámegiella','Northern Sami'),
	(173,'nb','Norsk Bokmål','Norwegian Bokmål'),
	(174,'nn','Norsk Nynorsk','Norwegian Nynorsk'),
	(175,'oj','ᐊᓂᔑᓈᐯᒧᐎᓐ','Ojibwa'),
	(176,'os','ирон æвзаг','Ossetian'),
	(177,'pi','पाऴि','Pali'),
	(178,'ii','ꆈꌠ꒿','Sichuan Yi'),
	(179,'mo','лимба молдовеняскэ','Moldovan'),
	(180,'nr','isiNdebele','Southern Ndebele'),
	(181,'ty','Reo Tahiti','Tahitian'),
	(182,'ug','ئۇيغۇرچە‎','Uighur'),
	(183,'ve','Tshivenḓa','Venda'),
	(184,'wa','Walon','Walloon'),
	(185,'za','Saɯ cueŋƅ','Zhuang');

/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locales`
--

CREATE TABLE IF NOT EXISTS `locales` (
 `id` smallint unsigned NOT NULL AUTO_INCREMENT,
	`language_id` smallint unsigned NOT NULL,
	`country_id` smallint unsigned NOT NULL,
	`official_language` tinyint unsigned NOT NULL DEFAULT '0',
	`default_country` tinyint unsigned DEFAULT NULL,
	`app_default` tinyint unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `language_id` (`language_id`),
	KEY `country_id` (`country_id`),
	KEY `official_language` (`official_language`),
	KEY `default_country` (`default_country`),
	KEY `app_default` (`app_default`),
	CONSTRAINT `fk_locales_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT `fk_locales_languages` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locales`
--

LOCK TABLES `locales` WRITE;
/*!40000 ALTER TABLE `locales` DISABLE KEYS */;

INSERT INTO `locales` (`id`, `language_id`, `country_id`, `official_language`, `default_country`, `app_default`)
VALUES
	(1,3,147,0,0,0),
	(2,3,226,1,0,0),
	(3,4,63,1,1,0),
	(4,5,2,1,0,0),
	(5,5,21,1,0,0),
	(6,5,52,1,0,0),
	(7,5,56,0,0,0),
	(8,5,59,1,0,0),
	(9,5,60,1,0,0),
	(10,5,61,0,0,0),
	(11,5,95,0,0,0),
	(12,5,97,1,0,0),
	(13,5,102,1,0,0),
	(14,5,108,1,0,0),
	(15,5,112,1,0,0),
	(16,5,116,1,0,0),
	(17,5,125,1,0,0),
	(18,5,126,1,0,0),
	(19,5,138,1,0,0),
	(20,5,159,1,0,0),
	(21,5,169,1,0,0),
	(22,5,173,1,0,0),
	(23,5,178,1,0,0),
	(24,5,181,1,0,0),
	(25,5,191,0,0,0),
	(26,5,195,1,0,0),
	(27,5,198,1,0,0),
	(28,5,205,1,0,0),
	(29,5,224,1,0,0),
	(30,6,96,0,0,0),
	(31,10,31,1,1,0),
	(32,11,20,1,1,0),
	(33,14,17,1,0,0),
	(34,14,96,0,0,0),
	(35,15,43,0,0,0),
	(36,15,96,0,0,0),
	(37,16,69,0,0,0),
	(38,17,1,1,0,0),
	(39,17,62,1,0,0),
	(40,17,69,0,0,0),
	(41,34,245,1,0,0),
	(42,19,50,1,1,0),
	(43,32,65,1,1,0),
	(44,21,53,1,1,0),
	(45,21,78,0,0,0),
	(46,22,11,1,0,0),
	(47,22,18,1,0,0),
	(48,22,38,1,0,0),
	(49,22,51,1,1,0),
	(50,22,100,0,0,0),
	(51,22,118,1,0,0),
	(52,22,123,1,0,0),
	(53,23,29,1,1,0),
	(54,24,49,1,0,0),
	(55,24,83,1,1,0),
	(56,25,4,1,0,0),
	(57,25,5,1,0,0),
	(58,25,11,0,0,0),
	(59,25,12,1,0,0),
	(60,25,16,1,0,0),
	(61,25,18,0,0,0),
	(62,25,22,1,0,0),
	(63,25,24,1,0,0),
	(64,25,28,1,0,0),
	(65,25,30,1,0,0),
	(66,25,32,1,0,0),
	(67,25,33,1,0,0),
	(68,25,34,1,0,0),
	(69,25,38,0,0,0),
	(70,25,40,1,0,0),
	(71,25,42,1,0,0),
	(72,25,48,0,0,0),
	(73,25,49,0,0,0),
	(74,25,51,0,0,0),
	(75,25,53,0,0,0),
	(76,25,54,1,0,0),
	(77,25,61,0,0,0),
	(78,25,64,0,0,0),
	(79,25,65,1,0,0),
	(80,25,66,1,0,0),
	(81,25,67,1,0,0),
	(82,25,71,1,1,0),
	(83,25,72,1,0,0),
	(84,25,75,1,0,0),
	(85,25,76,1,0,0),
	(86,25,77,1,0,0),
	(87,25,79,1,0,0),
	(88,25,87,1,0,0),
	(89,25,88,1,0,0),
	(90,25,94,1,0,0),
	(91,25,95,0,0,0),
	(92,25,96,1,0,0),
	(93,25,101,1,0,0),
	(94,25,104,1,0,0),
	(95,25,107,1,0,0),
	(96,25,109,1,0,0),
	(97,25,113,1,0,0),
	(98,25,117,1,0,0),
	(99,25,120,1,0,0),
	(100,25,121,1,0,0),
	(101,25,129,0,0,0),
	(102,25,130,1,0,0),
	(103,25,135,0,0,0),
	(104,25,136,1,0,0),
	(105,25,139,1,0,0),
	(106,25,140,1,0,0),
	(107,25,141,1,0,0),
	(108,25,143,0,0,0),
	(109,25,145,0,0,0),
	(110,25,147,1,0,0),
	(111,25,150,1,0,0),
	(112,25,151,1,0,0),
	(113,25,153,1,0,0),
	(114,25,156,0,0,0),
	(115,25,157,1,0,0),
	(116,25,158,1,0,0),
	(117,25,163,1,0,0),
	(118,25,164,1,0,0),
	(119,25,165,1,0,0),
	(120,25,168,1,0,0),
	(121,25,171,1,0,0),
	(122,25,177,1,0,0),
	(123,25,179,1,0,0),
	(124,25,180,1,0,0),
	(125,25,181,1,0,0),
	(126,25,182,0,0,0),
	(127,25,183,1,0,0),
	(128,25,184,1,0,0),
	(129,25,185,0,0,0),
	(130,25,188,1,0,0),
	(131,25,196,1,0,0),
	(132,25,197,1,0,0),
	(133,25,203,1,0,0),
	(134,25,206,1,0,0),
	(135,25,208,1,0,0),
	(136,25,209,1,0,0),
	(137,25,211,0,0,0),
	(138,25,213,0,0,0),
	(139,25,230,1,0,0),
	(140,25,216,1,0,0),
	(141,25,218,1,0,0),
	(142,25,219,1,0,0),
	(143,25,221,1,0,0),
	(144,25,223,1,0,0),
	(145,25,226,1,0,0),
	(146,25,227,1,0,0),
	(147,25,229,1,0,0),
	(148,27,10,1,0,0),
	(149,27,26,1,0,0),
	(150,27,27,0,0,0),
	(151,27,32,0,0,0),
	(152,27,41,1,0,0),
	(153,27,44,1,0,0),
	(154,27,45,1,0,0),
	(155,27,46,1,0,0),
	(156,27,55,1,0,0),
	(157,27,57,1,0,0),
	(158,27,62,1,1,0),
	(159,27,82,1,0,0),
	(160,27,85,1,0,0),
	(161,27,89,1,0,0),
	(162,27,144,1,0,0),
	(163,27,152,1,0,0),
	(164,27,160,1,0,0),
	(165,27,161,1,0,0),
	(166,27,164,0,0,0),
	(167,27,172,0,0,0),
	(168,27,194,1,0,0),
	(169,27,230,0,0,0),
	(170,27,214,1,0,0),
	(171,27,217,1,0,0),
	(172,28,58,1,1,0),
	(173,29,62,1,0,0),
	(174,30,3,0,0,0),
	(175,30,98,1,0,0),
	(176,31,64,1,1,0),
	(177,33,53,0,0,0),
	(178,33,68,1,1,0),
	(179,34,18,1,0,0),
	(180,34,19,1,0,0),
	(181,34,22,1,0,0),
	(182,34,23,1,0,0),
	(183,34,33,1,0,0),
	(184,34,35,1,0,0),
	(185,34,36,1,0,0),
	(186,34,37,1,0,0),
	(187,34,38,1,0,0),
	(188,34,39,1,0,0),
	(189,34,42,1,0,0),
	(190,34,52,1,0,0),
	(191,34,56,1,0,0),
	(192,34,69,1,1,0),
	(193,34,70,1,0,0),
	(194,34,74,1,0,0),
	(195,34,80,1,0,0),
	(196,34,81,1,0,0),
	(197,34,82,1,0,0),
	(198,34,91,1,0,0),
	(199,34,108,1,0,0),
	(200,34,123,1,0,0),
	(201,34,126,0,0,0),
	(202,34,127,1,0,0),
	(203,34,129,1,0,0),
	(204,34,132,1,0,0),
	(205,34,137,1,0,0),
	(206,34,138,0,0,0),
	(207,34,141,1,0,0),
	(208,34,148,1,0,0),
	(209,34,149,1,0,0),
	(210,34,162,1,0,0),
	(211,34,167,1,0,0),
	(212,34,174,1,0,0),
	(213,34,177,1,0,0),
	(214,34,180,1,0,0),
	(215,34,190,1,0,0),
	(216,34,195,0,0,0),
	(217,34,198,1,0,0),
	(218,34,200,1,0,0),
	(219,34,205,0,0,0),
	(220,34,221,1,0,0),
	(221,34,222,1,0,0),
	(222,34,225,1,0,0),
	(223,35,153,1,0,0),
	(224,36,94,1,1,0),
	(225,34,199,1,0,0),
	(226,38,62,1,0,0),
	(227,40,96,0,0,0),
	(228,41,76,0,0,0),
	(229,41,149,0,0,0),
	(230,41,151,0,0,0),
	(231,42,96,1,1,0),
	(232,43,15,1,0,0),
	(233,43,90,1,1,0),
	(234,44,92,1,1,0),
	(235,45,7,1,0,0),
	(236,50,99,1,1,0),
	(237,51,38,1,0,0),
	(238,51,100,1,1,1),
	(239,51,189,1,0,0),
	(240,53,103,1,1,0),
	(241,56,73,1,1,0),
	(242,57,114,1,1,0),
	(243,58,78,1,1,0),
	(244,59,106,1,0,0),
	(245,60,96,0,0,0),
	(246,61,110,1,0,0),
	(247,61,111,1,0,0),
	(248,62,96,0,0,0),
	(249,64,105,1,1,0),
	(250,66,9,0,0,0),
	(251,66,35,0,0,0),
	(252,66,36,0,0,0),
	(253,66,37,0,0,0),
	(254,67,115,1,1,0),
	(255,68,122,1,1,0),
	(256,69,124,1,1,0),
	(257,70,129,1,1,0),
	(258,72,131,1,1,0),
	(259,73,96,0,0,0),
	(260,74,134,1,1,0),
	(261,76,96,0,0,0),
	(262,77,25,1,0,0),
	(263,77,145,1,1,0),
	(264,77,183,1,0,0),
	(265,78,140,1,0,0),
	(266,79,133,1,1,0),
	(267,81,96,0,0,0),
	(268,81,155,1,1,0),
	(269,82,13,1,0,0),
	(270,82,18,1,0,0),
	(271,82,153,1,1,0),
	(272,82,192,1,0,0),
	(273,85,63,0,0,0),
	(274,85,104,0,0,0),
	(275,87,166,1,1,0),
	(276,88,3,1,0,0),
	(277,89,9,1,0,0),
	(278,89,27,1,0,0),
	(279,89,38,0,0,0),
	(280,89,47,1,0,0),
	(281,89,82,1,0,0),
	(282,89,86,1,0,0),
	(283,89,123,0,0,0),
	(284,89,135,1,0,0),
	(285,89,146,1,0,0),
	(286,89,170,1,1,0),
	(287,89,193,1,0,0),
	(288,90,26,0,0,0),
	(289,90,57,0,0,0),
	(290,90,161,0,0,0),
	(291,91,38,1,1,0),
	(292,92,22,1,1,0),
	(293,93,128,0,0,0),
	(294,93,175,1,1,0),
	(295,94,31,0,0,0),
	(296,94,105,1,0,0),
	(297,94,114,0,0,0),
	(298,94,128,0,0,0),
	(299,94,176,1,1,0),
	(300,94,212,0,0,0),
	(301,95,177,1,1,0),
	(302,97,165,1,0,0),
	(303,98,36,1,0,0),
	(304,100,119,1,1,0),
	(305,101,187,1,1,0),
	(306,102,185,1,1,0),
	(307,104,229,1,0,0),
	(308,105,52,1,0,0),
	(309,105,63,0,0,0),
	(310,105,104,0,0,0),
	(311,105,191,1,1,0),
	(312,106,6,1,1,0),
	(313,106,131,0,0,0),
	(314,111,64,1,0,0),
	(315,111,182,1,1,0),
	(316,112,35,0,0,0),
	(317,112,104,1,0,0),
	(318,112,211,1,0,0),
	(319,112,213,1,0,0),
	(320,113,96,0,0,0),
	(321,113,119,1,0,0),
	(322,113,145,0,0,0),
	(323,113,183,1,0,0),
	(324,114,96,0,0,0),
	(325,115,202,1,1,0),
	(326,116,201,1,1,0),
	(327,117,61,0,0,0),
	(328,117,63,0,0,0),
	(329,118,204,1,1,0),
	(330,121,206,1,1,0),
	(331,122,49,1,0,0),
	(332,122,207,1,1,0),
	(333,124,176,1,0,0),
	(334,126,212,1,1,0),
	(335,127,96,0,0,0),
	(336,127,165,1,1,0),
	(337,129,220,1,1,0),
	(338,131,190,1,1,0),
	(339,133,23,0,0,0),
	(340,133,151,0,0,0),
	(341,135,226,1,0,0),
	(342,136,96,0,0,0),
	(343,1,52,0,0,0),
	(344,1,61,0,0,0),
	(345,1,63,0,0,0),
	(346,100,61,0,0,0),
	(347,106,231,1,0,0),
	(348,107,231,1,0,0),
	(349,111,232,1,0,0),
	(350,25,233,1,0,0),
	(351,103,233,1,0,0),
	(352,8,14,1,1,0),
	(353,82,235,1,0,0),
	(354,142,15,1,1,0),
	(355,49,93,1,1,0),
	(356,25,237,1,0,0),
	(357,107,15,1,0,0),
	(358,120,30,1,0,0),
	(359,134,43,1,1,0),
	(360,74,43,0,0,0),
	(361,134,48,0,0,0),
	(362,77,48,0,0,0),
	(363,77,34,1,0,0),
	(364,112,108,1,0,0),
	(365,71,40,1,0,0),
	(366,82,238,1,0,0),
	(367,25,238,1,0,0),
	(368,108,196,1,0,0),
	(369,21,68,1,0,0),
	(370,143,239,1,0,0),
	(371,25,239,1,0,0),
	(372,155,91,1,0,0),
	(373,65,241,1,1,0),
	(374,134,88,1,0,0),
	(375,63,97,1,0,0),
	(376,162,242,1,1,0),
	(377,25,242,1,0,0),
	(378,52,95,1,1,0),
	(379,83,186,1,0,0),
	(380,25,243,1,0,0),
	(381,109,121,1,0,0),
	(382,99,123,1,1,0),
	(383,134,135,1,0,0),
	(384,145,143,1,1,0),
	(385,150,142,1,1,0),
	(386,137,130,1,1,0),
	(387,25,84,1,0,0),
	(388,112,177,1,0,0),
	(389,39,172,1,1,0),
	(390,157,163,1,1,0),
	(391,83,154,1,1,0),
	(392,179,128,1,1,0),
	(393,18,69,0,1,0),
	(394,80,156,1,1,0),
	(395,25,155,1,0,0),
	(396,71,158,1,1,0),
	(397,82,8,1,0,0),
	(398,143,136,1,0,0),
	(399,34,246,1,0,0),
	(400,103,223,1,1,0),
	(401,107,247,1,1,0),
	(402,134,183,1,0,0),
	(403,82,248,1,0,0),
	(404,25,248,1,0,0),
	(405,132,226,1,0,0),
	(406,120,226,1,0,0),
	(407,109,226,1,0,0),
	(408,123,226,1,0,0),
	(409,108,226,1,0,0),
	(410,183,226,1,0,0),
	(411,180,226,1,0,0),
	(412,25,249,1,0,0),
	(413,84,62,1,0,0),
	(414,134,210,1,0,0),
	(415,89,250,1,0,0),
	(416,112,228,1,0,0),
	(417,164,228,1,0,0),
	(418,25,251,1,0,0),
	(419,128,215,1,1,0),
	(420,94,215,1,0,0),
	(421,13,221,1,1,0),
	(422,27,60,0,0,0),
	(423,34,228,1,0,0),
	(424,66,228,1,0,0);

/*!40000 ALTER TABLE `locales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `query_index` int DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `affected_rows` int DEFAULT NULL,
  `result` int DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `file` (`file`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `version` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1.0',
  `date_released` datetime NOT NULL,
  `app_version` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1.0',
  `installed_by` int unsigned NOT NULL,
  `date_installed` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `installed_by` (`installed_by`),
  KEY `date_installed` (`date_installed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;

INSERT INTO `modules` (`id`, `name`, `version`, `date_released`, `app_version`, `installed_by`, `date_installed`)
VALUES
	(1,'api','1.0',NOW(),'1.0',1,NOW()),
	(2,'crafter','1.0',NOW(),'1.0',1,NOW()),
	(3,'languages','1.0',NOW(),'1.0',1,NOW()),
	(4,'modules','1.0',NOW(),'1.0',1,NOW()),
	(5,'options','1.0',NOW(),'1.0',1,NOW()),
	(6,'rules','1.0',NOW(),'1.0',1,NOW()),
	(7,'selftest','1.0',NOW(),'1.0',1,NOW()),
	(8,'templates','1.0',NOW(),'1.0',1,NOW()),
	(9,'user','1.0',NOW(),'1.0',1,NOW()),
	(10,'users','1.0',NOW(),'1.0',1,NOW()),
	(11,'tools','1.0',NOW(),'1.0',1,NOW()),
	(12,'countries','1.0',NOW(),'1.0',1,NOW()),
	(13,'locales','1.0',NOW(),'1.0',1,NOW()),
	(14,'translator','1.0',NOW(),'1.0',1,NOW()),
	(15,'tokens','1.0',NOW(),'1.0',1,NOW()),
	(16,'audit','1.0',NOW(),'1.0',1,NOW()),
	(17,'errorlogs','1.0',NOW(),'1.0',1,NOW()),
	(18,'oauth2','1.0',NOW(),'1.0',1,NOW()),
	(19,'migrate','1.0',NOW(),'1.0',1,NOW()),
	(20,'oauth2clients','1.0',NOW(),'1.0',1,NOW());

/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth2_clients`
--

CREATE TABLE IF NOT EXISTS `oauth2_clients` (
	`id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`secret` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`enabled` tinyint unsigned NOT NULL DEFAULT '1',
	`created_at` timestamp NULL DEFAULT NULL,
	`updated_at` timestamp NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `enabled` (`enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `oauth2_tokens`
--

CREATE TABLE IF NOT EXISTS `oauth2_tokens` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`client_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
	`created_at` timestamp NOT NULL,
	`updated_at` timestamp NOT NULL,
	PRIMARY KEY (`id`),
	KEY `client_id` (`client_id`),
	FULLTEXT KEY `token` (`token`),
	CONSTRAINT `fk_oauth2tokens_credentials` FOREIGN KEY (`client_id`) REFERENCES `oauth2_clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
	`name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`label` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`type` enum('text','textarea','bool','int','list','password') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
	`value` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`list_options` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
	`group` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	PRIMARY KEY (`name`),
	KEY `group` (`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `options`
--

LOCK TABLES `options` WRITE;
/*!40000 ALTER TABLE `options` DISABLE KEYS */;

INSERT INTO `options` (`name`, `label`, `type`, `value`, `list_options`, `group`)
VALUES
	('admin_emails','ADMIN_EMAILS','text','em@il.address',NULL,'Config'),
	('development','DEVELOPMENT','bool','1',NULL,'Config'),
	('pagination_pages','ITEMS_PER_PAGE','int','12',NULL,'Config'),
	('session_time','SESSION_TIME','int','60',NULL,'Config'),
	('show_log','SHOW_LOG','bool','1',NULL,'Config'),
	('webservice_timeout','WEBSERVICE_TIMEOUT','int','8',NULL,'Config'),
	('password_min','PASSWORD_MIN','int','8',NULL,'site');

/*!40000 ALTER TABLE `options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rules`
--

CREATE TABLE IF NOT EXISTS `rules` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`action` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`admin_only` tinyint(1) NOT NULL DEFAULT '0',
	`module_id` int unsigned NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `module_action` (`module_id`,`action`) USING BTREE,
	CONSTRAINT `fk_rules_modules` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rules`
--

LOCK TABLES `rules` WRITE;
/*!40000 ALTER TABLE `rules` DISABLE KEYS */;

INSERT INTO `rules` (`id`, `action`, `admin_only`, `module_id`)
VALUES
	(1,NULL,0,1),
	(2,NULL,1,2),
	(4,NULL,0,4),
	(5,NULL,1,5),
	(6,NULL,1,6),
	(7,NULL,1,7),
	(8,NULL,0,8),
	(9,NULL,0,9),
	(10,NULL,0,10),
	(11,NULL,1,11),
	(12,NULL,1,12),
	(13,NULL,1,13),
	(14,NULL,1,14),
	(15,NULL,1,15),
	(16,NULL,1,16),
	(17,NULL,1,17),
	(18,NULL,1,18),
	(19,NULL,1,19),
	(20,NULL,1,20);

/*!40000 ALTER TABLE `rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedule_logs`
--

CREATE TABLE `schedule_logs` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`job` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`result` tinyint unsigned NOT NULL,
	`info` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`created_at` timestamp NOT NULL,
	PRIMARY KEY (`id`),
	KEY `created_at` (`created_at`),
	KEY `job` (`job`),
	KEY `result` (`result`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
	`id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`user_id` int unsigned DEFAULT NULL,
	`start_time` datetime NOT NULL,
	`timezone_offset` decimal(2,1) DEFAULT NULL,
	`timezone_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`former_user_id` int unsigned DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `user_id` (`user_id`,`start_time`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`version` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`date_released` datetime NOT NULL,
	`app_version` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1.0',
	`is_default` tinyint(1) NOT NULL DEFAULT '0',
	`installed_by` int unsigned NOT NULL,
	`date_installed` datetime NOT NULL,
	`derived` tinyint unsigned NOT NULL DEFAULT '0',
	`palette` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	PRIMARY KEY (`id`),
	UNIQUE KEY `name` (`name`),
	KEY `installed_by` (`installed_by`),
	KEY `date_installed` (`date_installed`),
	CONSTRAINT `fk_templates_users` FOREIGN KEY (`installed_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `templates`
--

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;

INSERT INTO `templates` (`id`, `name`, `version`, `date_released`, `app_version`, `is_default`, `installed_by`, `date_installed`, `derived`, `palette`)
VALUES
		(1,'Basic','1.0',NOW(),'1.0',1,1,NOW(),0,'#1AB394,#1C84C6,#9C9C9C,#636363');

/*!40000 ALTER TABLE `templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tokens`
--

CREATE TABLE IF NOT EXISTS `tokens` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`value` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`enabled` tinyint unsigned NOT NULL DEFAULT '1',
	`created_by` int unsigned NOT NULL,
	`creation_date` datetime NOT NULL,
	`last_use` datetime DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `code` (`code`),
	KEY `token` (`value`),
	KEY `enabled` (`enabled`),
	KEY `created_by` (`created_by`),
	CONSTRAINT `fk_tokens_users` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
	`id` int unsigned NOT NULL AUTO_INCREMENT,
	`group_id` int unsigned NOT NULL,
	`locale_id` smallint unsigned NOT NULL,
	`username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`hash` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`surname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	`admin` tinyint(1) NOT NULL DEFAULT '0',
	`enabled` int unsigned NOT NULL,
	`last_login` datetime DEFAULT NULL,
	`faults` int unsigned NOT NULL DEFAULT '0',
	`pw_reset` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `username` (`username`),
	UNIQUE KEY `pw_reset` (`pw_reset`),
	KEY `group_id` (`group_id`),
	KEY `admin` (`admin`),
	KEY `locale_id` (`locale_id`),
	CONSTRAINT `fk_users_groups` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
	CONSTRAINT `fk_users_locales` FOREIGN KEY (`locale_id`) REFERENCES `locales` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `users_remembers`
--

CREATE TABLE IF NOT EXISTS `users_remembers` (
	`user_id` int unsigned NOT NULL,
	`remember_me` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`created_at` timestamp NOT NULL,
	PRIMARY KEY (`user_id`,`remember_me`),
	KEY `remember_me` (`remember_me`),
	KEY `created_at` (`created_at`),
	CONSTRAINT `fk_users_remembers_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
