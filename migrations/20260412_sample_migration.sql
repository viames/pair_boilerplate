-- Sample migration that expands the users.name column length.
ALTER TABLE `users`
	CHANGE COLUMN `name` `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';
