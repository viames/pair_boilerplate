-- Creates the migrations table used by both Pair and application migration runners.
CREATE TABLE IF NOT EXISTS `migrations` (
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
