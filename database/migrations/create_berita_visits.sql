-- Create berita_visits table for tracking visitor geo location
CREATE TABLE IF NOT EXISTS `berita_visits` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `article_id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_visits_article_id_created_at_index` (`article_id`,`created_at`),
  KEY `article_visits_ip_address_index` (`ip_address`),
  KEY `article_visits_country_index` (`country`),
  KEY `article_visits_created_at_index` (`created_at`),
  CONSTRAINT `article_visits_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `berita` (`id_berita`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add indexes if not exists
ALTER TABLE `berita_visits` ADD INDEX IF NOT EXISTS `idx_article_date` (`article_id`, `created_at`);
ALTER TABLE `berita_visits` ADD INDEX IF NOT EXISTS `idx_ip` (`ip_address`);
ALTER TABLE `berita_visits` ADD INDEX IF NOT EXISTS `idx_country` (`country`);