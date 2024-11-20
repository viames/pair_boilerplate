-- Sample comment for the migration
ALTER TABLE `users` 
  CHANGE COLUMN `name` `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';