-- SQL Script untuk mengubah kolom twitter_setting menjadi linkedin_setting
-- Jalankan script ini di database MySQL/MariaDB Anda

-- Cek apakah kolom twitter_setting ada
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'setting' 
AND COLUMN_NAME = 'twitter_setting';

-- Jika ada, ubah nama kolom dari twitter_setting ke linkedin_setting
ALTER TABLE `setting` 
CHANGE COLUMN `twitter_setting` `linkedin_setting` VARCHAR(255) NULL DEFAULT NULL;

-- Verifikasi perubahan berhasil
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'setting' 
AND COLUMN_NAME = 'linkedin_setting';

-- Jika ingin memperbarui data yang sudah ada (opsional)
-- UPDATE setting SET linkedin_setting = twitter_setting WHERE twitter_setting IS NOT NULL;
