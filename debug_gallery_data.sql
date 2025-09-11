-- Debug query untuk cek data gallery
-- Jalankan di phpMyAdmin atau MySQL client

-- 1. Cek struktur dan data tabel galeri
SELECT 'GALERI TABLE DATA:' as info;
SELECT * FROM galeri ORDER BY id_galeri;

-- 2. Cek struktur dan data tabel gallery_items
SELECT 'GALLERY_ITEMS TABLE DATA:' as info;
SELECT * FROM gallery_items ORDER BY id_galeri, sequence;

-- 3. Cek relasi data
SELECT 'GALERI WITH ITEMS COUNT:' as info;
SELECT 
    g.id_galeri,
    g.nama_galeri,
    g.status as gallery_status,
    COUNT(gi.id_gallery_item) as items_count,
    GROUP_CONCAT(CONCAT(gi.type, ':', gi.status)) as items_info
FROM galeri g
LEFT JOIN gallery_items gi ON g.id_galeri = gi.id_galeri
GROUP BY g.id_galeri, g.nama_galeri, g.status
ORDER BY g.id_galeri;

-- 4. Cek data untuk gallery ID 1 specifically
SELECT 'GALLERY ID 1 DETAILS:' as info;
SELECT 
    gi.*,
    g.nama_galeri,
    g.status as gallery_status
FROM gallery_items gi
JOIN galeri g ON gi.id_galeri = g.id_galeri
WHERE gi.id_galeri = 1
ORDER BY gi.sequence;
