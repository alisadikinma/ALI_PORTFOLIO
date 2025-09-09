-- SQL untuk menambahkan kolom SEO ke tabel berita
-- Jalankan query ini di phpMyAdmin atau MySQL

ALTER TABLE `berita` 
ADD COLUMN `meta_title` VARCHAR(255) NULL AFTER `judul_berita`,
ADD COLUMN `meta_description` TEXT NULL AFTER `meta_title`,
ADD COLUMN `tags` VARCHAR(500) NULL AFTER `meta_description`,
ADD COLUMN `reading_time` INT NULL AFTER `tags`,
ADD COLUMN `featured_snippet` TEXT NULL AFTER `reading_time`,
ADD COLUMN `conclusion` TEXT NULL AFTER `featured_snippet`,
ADD COLUMN `focus_keyword` VARCHAR(255) NULL AFTER `conclusion`,
ADD COLUMN `faq_data` JSON NULL AFTER `focus_keyword`,
ADD COLUMN `is_featured` BOOLEAN DEFAULT FALSE AFTER `faq_data`,
ADD COLUMN `views` INT DEFAULT 0 AFTER `is_featured`;
