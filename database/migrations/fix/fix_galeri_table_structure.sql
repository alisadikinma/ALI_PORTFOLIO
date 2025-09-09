-- FIX: Update galeri table structure to match Laravel model expectations
-- Run this SQL script in phpMyAdmin or MySQL command line

-- Step 1: Check current table structure
DESCRIBE galeri;

-- Step 2: Add missing columns that don't exist yet
ALTER TABLE galeri 
ADD COLUMN sequence INT DEFAULT 0 AFTER video_galeri,
ADD COLUMN status ENUM('Active', 'Inactive') DEFAULT 'Active' AFTER sequence;

-- Step 3: Rename columns to match model expectations
ALTER TABLE galeri 
CHANGE COLUMN keterangan_galeri deskripsi_galeri TEXT,
CHANGE COLUMN gambar_galeri thumbnail VARCHAR(255);

-- Step 4: Remove old columns that are no longer needed
ALTER TABLE galeri 
DROP COLUMN jenis_galeri,
DROP COLUMN gambar_galeri1,
DROP COLUMN gambar_galeri2, 
DROP COLUMN gambar_galeri3,
DROP COLUMN video_galeri;

-- Step 5: Verify the new structure
DESCRIBE galeri;

-- Step 6: Check updated data
SELECT id_galeri, nama_galeri, deskripsi_galeri, thumbnail, sequence, status FROM galeri LIMIT 5;