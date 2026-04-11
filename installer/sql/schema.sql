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

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` enum('app','pair') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'app',
  `query_index` int DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `affected_rows` int DEFAULT NULL,
  `result` int DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `file` (`file`),
  KEY `source` (`source`),
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
	`palette` json NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `name` (`name`),
	KEY `installed_by` (`installed_by`),
	KEY `date_installed` (`date_installed`),
	CONSTRAINT `fk_templates_users` FOREIGN KEY (`installed_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
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

-- Table structure for table `user_remembers`
--

CREATE TABLE IF NOT EXISTS `user_remembers` (
	`user_id` int unsigned NOT NULL,
	`remember_me` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
	`created_at` timestamp NOT NULL,
	PRIMARY KEY (`user_id`,`remember_me`),
	KEY `remember_me` (`remember_me`),
	KEY `created_at` (`created_at`),
	CONSTRAINT `fk_user_remembers_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
