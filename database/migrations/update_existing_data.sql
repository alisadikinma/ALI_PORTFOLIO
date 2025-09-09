-- Update data lama yang belum ada sequence dan status
-- Set semua record lama ke Active dengan sequence berdasarkan ID

-- Update tabel award
UPDATE award 
SET sequence = id_award, status = 'Active' 
WHERE sequence IS NULL OR sequence = 0;

-- Update tabel layanan  
UPDATE layanan 
SET sequence = id_layanan, status = 'Active' 
WHERE sequence IS NULL OR sequence = 0;

-- Update tabel galeri
UPDATE galeri 
SET sequence = id_galeri, status = 'Active' 
WHERE sequence IS NULL OR sequence = 0;

-- Tampilkan hasil update
SELECT 'Award' as tabel, COUNT(*) as total_records FROM award WHERE status = 'Active'
UNION ALL
SELECT 'Layanan' as tabel, COUNT(*) as total_records FROM layanan WHERE status = 'Active'  
UNION ALL
SELECT 'Galeri' as tabel, COUNT(*) as total_records FROM galeri WHERE status = 'Active';
