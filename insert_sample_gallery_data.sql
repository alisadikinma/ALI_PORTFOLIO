-- Insert sample data untuk testing gallery
-- File: insert_sample_gallery_data.sql

-- 1. Insert sample gallery jika belum ada
INSERT IGNORE INTO galeri (id_galeri, nama_galeri, company, period, deskripsi_galeri, thumbnail, sequence, status) 
VALUES 
(1, '1st Place Winner Nextdev Startup Competition', 'TechCorp', '2024', 'Award gallery showcasing startup competition achievements', 'sample_thumb.jpg', 1, 'Active'),
(2, 'AI Innovation Project', 'AI Solutions Inc', '2024', 'Portfolio of AI-driven innovation projects', NULL, 2, 'Active');

-- 2. Insert sample gallery items untuk gallery ID 1
INSERT IGNORE INTO gallery_items (id_gallery_item, id_galeri, type, file_name, youtube_url, id_award, sequence, status) 
VALUES 
(1, 1, 'image', 'award_1.jpg', NULL, NULL, 1, 'Active'),
(2, 1, 'image', 'award_2.jpg', NULL, NULL, 2, 'Active'),
(3, 1, 'youtube', NULL, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, 3, 'Active');

-- 3. Insert sample gallery items untuk gallery ID 2
INSERT IGNORE INTO gallery_items (id_gallery_item, id_galeri, type, file_name, youtube_url, id_award, sequence, status) 
VALUES 
(4, 2, 'image', 'project_1.jpg', NULL, NULL, 1, 'Active'),
(5, 2, 'image', 'project_2.jpg', NULL, NULL, 2, 'Active');

-- 4. Update status jika ada yang NULL
UPDATE galeri SET status = 'Active' WHERE status IS NULL OR status = '';
UPDATE gallery_items SET status = 'Active' WHERE status IS NULL OR status = '';

-- 5. Verify data
SELECT 'VERIFICATION - GALERI TABLE:' as info;
SELECT * FROM galeri ORDER BY id_galeri;

SELECT 'VERIFICATION - GALLERY_ITEMS TABLE:' as info;
SELECT gi.*, g.nama_galeri 
FROM gallery_items gi 
LEFT JOIN galeri g ON gi.id_galeri = g.id_galeri 
ORDER BY gi.id_galeri, gi.sequence;
