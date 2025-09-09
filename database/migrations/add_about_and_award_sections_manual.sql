-- Menambahkan kolom baru untuk About Section dan Award Section
-- Jalankan query ini di database MySQL

ALTER TABLE `setting` 
ADD COLUMN `about_section_title` VARCHAR(255) NULL AFTER `success_rate`,
ADD COLUMN `about_section_subtitle` VARCHAR(255) NULL AFTER `about_section_title`,
ADD COLUMN `about_section_description` TEXT NULL AFTER `about_section_subtitle`,
ADD COLUMN `about_section_image` VARCHAR(255) NULL AFTER `about_section_description`,
ADD COLUMN `award_section_title` VARCHAR(255) NULL AFTER `about_section_image`,
ADD COLUMN `award_section_subtitle` VARCHAR(255) NULL AFTER `award_section_title`;

-- Isi data default (opsional)
UPDATE `setting` 
SET 
  `about_section_title` = 'With over 16+ years of experience in manufacturing and technology',
  `about_section_subtitle` = 'Bridging Traditional Manufacturing with AI Innovation',
  `about_section_description` = 'I\'ve dedicated my career to bridging the gap between traditional manufacturing and cutting-edge AI solutions.<br><br>From my early days as a Production Engineer to becoming an AI Generalist, I\'ve consistently focused on delivering measurable business impact through innovative technology solutions.',
  `award_section_title` = 'Awards & Recognition',
  `award_section_subtitle` = 'Innovative solutions that drive real business impact and transformation'
WHERE `id_setting` = 1;
