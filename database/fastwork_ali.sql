-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Agu 2025 pada 02.57
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fastwork_ali`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `award`
--

CREATE TABLE `award` (
  `id_award` int(10) UNSIGNED NOT NULL,
  `nama_award` varchar(255) NOT NULL,
  `gambar_award` varchar(255) NOT NULL,
  `keterangan_award` text NOT NULL,
  `slug_award` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `award`
--

INSERT INTO `award` (`id_award`, `nama_award`, `gambar_award`, `keterangan_award`, `slug_award`, `created_at`, `updated_at`) VALUES
(4, 'Google For Startups', 'award20250817085640.png', '<p>Google For Startups</p>', 'google-for-startups', '2025-08-17 12:56:40', '2025-08-17 12:56:40'),
(5, 'Google For Startups 001', 'award20250817085822.png', '<p>Google For Startups 001</p>', 'google-for-startups-001', '2025-08-17 12:58:22', '2025-08-17 12:58:22'),
(6, 'Google For Startups 002', 'award20250817085905.png', '<p>Google For Startups 002</p>', 'google-for-startups-002', '2025-08-17 12:59:06', '2025-08-17 12:59:06'),
(7, 'Google For Startups XXX', 'award20250818105559.png', '<p>Google For Startups XXX</p>', 'google-for-startups-xxx', '2025-08-18 02:55:59', '2025-08-18 02:55:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita`
--

CREATE TABLE `berita` (
  `id_berita` int(10) UNSIGNED NOT NULL,
  `judul_berita` varchar(255) NOT NULL,
  `kategori_berita` varchar(255) DEFAULT NULL,
  `related_ids` text DEFAULT NULL,
  `isi_berita` text NOT NULL,
  `gambar_berita` varchar(255) NOT NULL,
  `tanggal_berita` date NOT NULL,
  `slug_berita` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `berita`
--

INSERT INTO `berita` (`id_berita`, `judul_berita`, `kategori_berita`, `related_ids`, `isi_berita`, `gambar_berita`, `tanggal_berita`, `slug_berita`, `created_at`, `updated_at`) VALUES
(2, 'Tes Article', 'AI & Tech', '[\"3\",\"5\"]', '<p>Tes Article Doang !!!</p>', 'Berita20241122044528.jpg', '2024-11-22', 'tes-article', '2024-11-22 07:45:28', '2025-08-25 12:59:19'),
(3, 'Jual Aplikasi Web Sistem Informasi Game Online Slot & Mistery Box Berbasis Laravel', 'Hardware & Gadgets', NULL, '<p>Jual Aplikasi Web Sistem Informasi Game Online Slot &amp; Mistery Box Berbasis LaravelJual Aplikasi Web Sistem Informasi Game Online Slot &amp; Mistery Box Berbasis LaravelJual Aplikasi Web Sistem Informasi Game Online Slot &amp; Mistery Box Berbasis LaravelJual Aplikasi Web Sistem Informasi Game Online Slot &amp; Mistery Box Berbasis Laravel</p>', 'Berita20250818115437.jpg', '2025-08-18', 'jual-aplikasi-web-sistem-informasi-game-online-slot-mistery-box-berbasis-laravel', '2025-08-18 03:54:37', '2025-08-25 12:00:11'),
(4, 'Maintenance Sistem', 'AI & Tech', NULL, '<p>Maintenance SistemMaintenance SistemMaintenance SistemMaintenance SistemMaintenance SistemMaintenance SistemMaintenance SistemMaintenance SistemMaintenance SistemMaintenance Sistem</p>', 'Berita20250818015613.jpg', '2025-08-18', 'maintenance-sistem', '2025-08-18 05:56:13', '2025-08-18 05:56:13'),
(5, 'Maintenance Sistem 112sfgdfhg', 'Hardware & Gadgets', NULL, '<p>Maintenance SistemMaintenance SistemMaintenance SistemMaintenance SistemMaintenance SistemMaintenance Sistem</p>', 'Berita20250818015652.webp', '2025-08-18', 'maintenance-sistem-112sfgdfhg', '2025-08-18 05:56:52', '2025-08-18 05:56:52'),
(6, 'Tes Article dfsgdhfteygd', 'AI & Tech', NULL, '<p>Tes Article dfsgdhfteygdTes Article dfsgdhfteygdTes Article dfsgdhfteygdTes Article dfsgdhfteygdTes Article dfsgdhfteygdTes Article dfsgdhfteygdTes Article dfsgdhfteygdTes Article dfsgdhfteygdTes Article dfsgdhfteygd</p>', 'Berita20250818015732.jpg', '2025-08-18', 'tes-article-dfsgdhfteygd', '2025-08-18 05:57:32', '2025-08-18 05:57:32'),
(7, 'Maintenance Sistem', 'Hardware & Gadgets', NULL, '<p>Tess</p>', 'Berita20250825062546.png', '2025-08-25', 'maintenance-sistem', '2025-08-25 10:25:46', '2025-08-25 10:25:46'),
(9, 'Jual Aplikasi Web Sistem Informasi Game Online Slot & Mistery Box Berbasis Laravel', 'Hardware & Gadgets', '[\"6\",\"7\",\"9\"]', '<p>tess</p>', 'Berita20250825084921.png', '2025-08-25', 'jual-aplikasi-web-sistem-informasi-game-online-slot-mistery-box-berbasis-laravel', '2025-08-25 12:49:21', '2025-08-25 12:59:05'),
(10, 'tess lagi ya', 'AI & Tech', '[\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"9\"]', '<p>banyak cok</p>', 'Berita20250825085947.png', '2025-08-25', 'tess-lagi-ya', '2025-08-25 12:59:47', '2025-08-25 12:59:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `budget` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `contacts`
--

INSERT INTO `contacts` (`id`, `full_name`, `email`, `subject`, `service`, `budget`, `message`, `created_at`, `updated_at`) VALUES
(1, 'Roberth', 'pattroberth13@gmail.com', 'Loker', 'ai', 'medium', 'TesTesTesTes', '2025-08-20 13:18:55', '2025-08-20 13:18:55'),
(2, 'Roberth', 'pattroberth13@gmail.com', 'Loker', 'automation', 'high', '@if(session(\'success\'))\r\n    <div class=\"mb-4 p-4 rounded-lg bg-green-500 text-white font-medium\">\r\n        {{ session(\'success\') }}\r\n    </div>\r\n@endif', '2025-08-20 13:21:20', '2025-08-20 13:21:20'),
(3, 'Roberth', 'pattroberth13@gmail.com', 'Loker', 'ai', 'low', 'addajdbajbaddajdbajbaddajdbajbaddajdbajb', '2025-08-20 13:29:40', '2025-08-20 13:29:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `galeri`
--

CREATE TABLE `galeri` (
  `id_galeri` int(11) UNSIGNED NOT NULL,
  `nama_galeri` varchar(255) DEFAULT NULL,
  `jenis_galeri` varchar(255) DEFAULT NULL,
  `keterangan_galeri` text DEFAULT NULL,
  `gambar_galeri` varchar(255) NOT NULL,
  `gambar_galeri1` text DEFAULT NULL,
  `gambar_galeri2` text DEFAULT NULL,
  `gambar_galeri3` text DEFAULT NULL,
  `video_galeri` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `galeri`
--

INSERT INTO `galeri` (`id_galeri`, `nama_galeri`, `jenis_galeri`, `keterangan_galeri`, `gambar_galeri`, `gambar_galeri1`, `gambar_galeri2`, `gambar_galeri3`, `video_galeri`, `created_at`, `updated_at`) VALUES
(1, 'Galleri 1', 'Galeri', '<p>Galleri 1Galleri 1Galleri 1</p>', 'Galeri20250818111536.png', NULL, NULL, NULL, NULL, '2025-08-18 03:15:36', '2025-08-18 03:15:36'),
(2, 'Galleri 1232', 'Galeri', '<p>Galleri 1232Galleri 1232Galleri 1232</p>', 'Galeri20250818111608.png', NULL, NULL, NULL, NULL, '2025-08-18 03:16:08', '2025-08-18 03:16:08'),
(3, 'Platform Penjualan Busana Adat Bali Terpercaya', 'Galeri', '<p>Platform Penjualan Busana Adat Bali Terpercaya</p>', 'Galeri20250818111623.jpg', NULL, NULL, NULL, NULL, '2025-08-18 03:16:23', '2025-08-18 03:16:23'),
(4, 'Mertha Dewata Destar Store', 'Galeri', '<p>Platform Penjualan Busana Adat Bali TerpercayaPlatform Penjualan Busana Adat Bali TerpercayaPlatform Penjualan Busana Adat Bali Terpercaya</p>', 'Galeri20250818111642.jpg', NULL, NULL, NULL, NULL, '2025-08-18 03:16:42', '2025-08-18 03:16:42'),
(5, 'Foto Pra Weedingsfsdgdfg', 'Galeri', '<p>werrty</p>', 'gambar_galeri_1756112405.png', 'gambar_galeri1_1756112930.png', NULL, NULL, 'video_1756112930.mp4', '2025-08-25 10:00:05', '2025-08-25 10:08:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan`
--

CREATE TABLE `layanan` (
  `id_layanan` int(10) UNSIGNED NOT NULL,
  `nama_layanan` varchar(255) NOT NULL,
  `gambar_layanan` varchar(255) NOT NULL,
  `keterangan_layanan` text NOT NULL,
  `slug_layanan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `layanan`
--

INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `gambar_layanan`, `keterangan_layanan`, `slug_layanan`, `created_at`, `updated_at`) VALUES
(1, 'Produk Berkualitas', 'layanan20250817043448.png', 'Produk BerkualitasProduk BerkualitasProduk BerkualitasProduk Berkualitas', 'produk-berkualitas', '2025-08-17 08:34:48', '2025-08-17 08:34:48'),
(2, 'Layanan 24 Jam', 'layanan20250817051420.jpg', 'Layanan 24 Jam', 'layanan-24-jam', '2025-08-17 09:14:20', '2025-08-17 09:14:20'),
(3, 'Tes Layanan', 'layanan20250817054742.jpg', 'Tes Layanan', 'tes-layanan', '2025-08-17 09:47:42', '2025-08-17 09:47:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2024_02_20_141318_create_sessions_table', 1),
(7, '2024_02_20_145226_kategori_anggota', 2),
(8, '2025_06_16_131054_create_catatan_pakans_table', 3),
(9, '2025_06_16_134417_catatan_vitamin', 4),
(10, '2025_06_16_140842_create_catatan_kondisi_ayam_table', 5),
(11, '2025_06_16_193346_create_pengadaan_stok_table', 6),
(12, '2025_08_17_194757_add_profile_fields_to_settings_table', 7),
(13, '2025_08_17_201146_add_new_fields_to_settings_table', 8),
(14, '2025_08_20_211503_create_contacts_table', 9),
(15, '2025_08_25_201734_add_related_ids_to_berita_table', 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `project`
--

CREATE TABLE `project` (
  `id_project` int(10) UNSIGNED NOT NULL,
  `nama_project` varchar(255) NOT NULL,
  `nama_client` varchar(255) NOT NULL,
  `lokasi_client` varchar(255) NOT NULL,
  `gambar_project` varchar(255) NOT NULL,
  `gambar_project1` text NOT NULL,
  `gambar_project2` text NOT NULL,
  `keterangan_project` text NOT NULL,
  `info_project` varchar(255) NOT NULL,
  `jenis_project` varchar(255) NOT NULL,
  `url_project` varchar(255) NOT NULL,
  `slug_project` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `project`
--

INSERT INTO `project` (`id_project`, `nama_project`, `nama_client`, `lokasi_client`, `gambar_project`, `gambar_project1`, `gambar_project2`, `keterangan_project`, `info_project`, `jenis_project`, `url_project`, `slug_project`, `created_at`, `updated_at`) VALUES
(1, 'Snaphooria.id', 'Tes Portfolio 1', 'Riau, Batam', 'Project20250817043346.png', 'Project120250817043346.png', 'Project220250817043346.png', '<p>Tes Portfolio 1Tes Portfolio 1Tes Portfolio 1Tes Portfolio 1Tes Portfolio 1Tes Portfolio 1Tes Portfolio 1Tes Portfolio 1Tes Portfolio 1</p>', 'Selesai', 'Media Sosial', 'https://roberthcolln.tamaraamerta.id', 'snaphooriaid', '2025-08-17 08:33:46', '2025-08-18 03:42:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('IELEJbqxLTWfxXl567hxW7KPx7EqofzlhMQRhPmk', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSlpMSExQcVNzUk5TRkthU1R0WG9xWGN5VXNyN255Mk00dU5VS0VnMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9iZXJpdGEiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEyJFZ3SjN5QnRiR3l3SVVOMklublJrZE9hS1VBai5nYXp0TXhqZlJHenV6S0xJcU12ZTJSeC5PIjt9', 1756123187);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting`
--

CREATE TABLE `setting` (
  `id_setting` int(10) UNSIGNED NOT NULL,
  `instansi_setting` varchar(255) NOT NULL,
  `pimpinan_setting` varchar(255) NOT NULL,
  `logo_setting` varchar(255) NOT NULL,
  `favicon_setting` varchar(255) NOT NULL,
  `tentang_setting` text NOT NULL,
  `misi_setting` text DEFAULT NULL,
  `visi_setting` text DEFAULT NULL,
  `keyword_setting` varchar(255) NOT NULL,
  `alamat_setting` varchar(255) NOT NULL,
  `instagram_setting` varchar(255) NOT NULL,
  `youtube_setting` varchar(255) NOT NULL,
  `email_setting` varchar(255) NOT NULL,
  `no_hp_setting` varchar(255) NOT NULL,
  `tiktok_setting` varchar(255) DEFAULT NULL,
  `facebook_setting` varchar(255) DEFAULT NULL,
  `twitter_setting` varchar(255) DEFAULT NULL,
  `maps_setting` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_title` varchar(255) DEFAULT NULL,
  `profile_content` text DEFAULT NULL,
  `primary_button_title` varchar(255) DEFAULT NULL,
  `primary_button_link` varchar(255) DEFAULT NULL,
  `secondary_button_title` varchar(255) DEFAULT NULL,
  `secondary_button_link` varchar(255) DEFAULT NULL,
  `years_experience` varchar(255) DEFAULT NULL,
  `followers_count` varchar(255) DEFAULT NULL,
  `project_delivered` varchar(255) DEFAULT NULL,
  `cost_savings` varchar(255) DEFAULT NULL,
  `success_rate` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `setting`
--

INSERT INTO `setting` (`id_setting`, `instansi_setting`, `pimpinan_setting`, `logo_setting`, `favicon_setting`, `tentang_setting`, `misi_setting`, `visi_setting`, `keyword_setting`, `alamat_setting`, `instagram_setting`, `youtube_setting`, `email_setting`, `no_hp_setting`, `tiktok_setting`, `facebook_setting`, `twitter_setting`, `maps_setting`, `created_at`, `updated_at`, `profile_title`, `profile_content`, `primary_button_title`, `primary_button_link`, `secondary_button_title`, `secondary_button_link`, `years_experience`, `followers_count`, `project_delivered`, `cost_savings`, `success_rate`) VALUES
(2, 'AI Technology', 'Ali Sadikin', 'Screenshot (80).png', 'Screenshot (80).png', '<p><strong>Ini Tentang saya Ali Sadikin</strong>Ali SadikinAli SadikinAli SadikinAli SadikinAli SadikinAli SadikinAli SadikinAli SadikinAli SadikinAli SadikinAli SadikinAli SadikinAli SadikinAli SadikinAli Sadikin</p>', '<ol><li>Mengembangkan sistem <strong>AI Generalist</strong> yang adaptif, serbaguna, dan dapat membantu berbagai kebutuhan lintas bidang.</li><li>Mendorong lahirnya <strong>Technopreneur</strong> yang inovatif dan berdaya saing global melalui pemanfaatan teknologi mutakhir.</li><li>Menghubungkan kecerdasan buatan dengan peluang bisnis nyata untuk meningkatkan efisiensi, produktivitas, dan nilai tambah ekonomi.</li><li>Menyediakan ekosistem pembelajaran dan pengembangan yang mendukung kolaborasi antara teknologi, bisnis, dan masyarakat.</li><li>Berkontribusi pada pembangunan berkelanjutan melalui inovasi berbasis teknologi yang etis dan bertanggung jawab.</li></ol>', '<p>Menjadi pelopor dalam pemanfaatan kecerdasan buatan dan inovasi teknologi untuk menciptakan solusi cerdas, inklusif, dan berkelanjutan yang memberikan manfaat nyata bagi masyarakat dan dunia usaha.</p>', 'AI Generalist & Technopreneur', 'Riau, Batam - Indonesia', 'roberth_colln', 'AI Generalist & Technopreneur', 'office@ternakasihmandiri.id', '6282124944770', 'aliii', 'aliii', 'aliii', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d510642.143941339!2d103.72342741942022!3d0.8378388647094558!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d9bce8c054ce05%3A0x3039d80b220cbb0!2sBatam%2C%20Kota%20Batam%2C%20Kepulauan%20Riau!5e0!3m2!1sid!2sid!4v1755430870009!5m2!1sid!2sid', NULL, '2025-08-25 09:32:06', 'AI Generalist Technopreneur Content Creator', '<p>I\'m passionate about <strong>revolutionizing</strong> manufacturing with automation and robotics.</p>', 'Request Quote', 'https://www.microsoft.com', 'Downloada Rate Card', 'https://www.microsoft.com', '15+', '50k+', '100+', 'Rp. 4,3B+', '95%+');

-- --------------------------------------------------------

--
-- Struktur dari tabel `testimonial`
--

CREATE TABLE `testimonial` (
  `id_testimonial` int(10) UNSIGNED NOT NULL,
  `judul_testimonial` varchar(255) NOT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `gambar_testimonial` varchar(255) NOT NULL,
  `deskripsi_testimonial` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `testimonial`
--

INSERT INTO `testimonial` (`id_testimonial`, `judul_testimonial`, `jabatan`, `gambar_testimonial`, `deskripsi_testimonial`, `created_at`, `updated_at`) VALUES
(1, 'Testimonial', 'System Developer', 'Testimonial20241024125945.jpg', 'Mantap Pak Ali', '2024-10-23 15:59:45', '2024-10-23 15:59:45'),
(2, 'Testimonial 1', 'UI UX Design', 'Testimonial20241024010624.jpg', 'Mantap Pak Ali', '2024-10-23 16:06:24', '2024-10-23 16:06:24'),
(3, 'Testimonial 111', 'Programmer', 'Testimonial20250817055047.png', 'Mantap Pak Ali', '2025-08-17 09:50:47', '2025-08-17 09:50:47'),
(4, 'Testimonial 12121', 'Web Develope', 'Testimonial20250817055117.jpg', 'Mantap Pak Ali', '2025-08-17 09:51:17', '2025-08-18 04:42:15'),
(5, 'Colln Junifell', 'Front End Dev', 'Testimonial20250818020329.png', '<p>Mantapo kali</p>', '2025-08-18 06:03:29', '2025-08-18 06:03:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `jenis_kelamin` varchar(30) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `id_kategori_user` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `no_hp`, `alamat`, `status`, `jenis_kelamin`, `tanggal_lahir`, `tempat_lahir`, `id_kategori_user`, `foto`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$VwJ3yBtbGywIUN2InnRkdOaKUAj.gaztMxjfRGzuzKLIqMve2Rx.O', '082124944770', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'Wahyu', 'wahyu@gmail.com', NULL, '$2y$12$I1q83o4PcV4Jn8EaiygHQe8KMEY33ALjv6AU68xIM03TT5Qnv4Y1y', '08123456887', 'Desa Ayunan, Abiansemal, badung - Bali', NULL, 'Laki-laki', '2025-04-24', 'Abiansemal', 4, 'Foto20250424093126.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-24 13:31:26', '2025-04-24 13:31:26'),
(33, 'Pak Made Miasa', 'ternakasihmandiri@gmail.com', NULL, '$2y$12$7SXJoRuihAfEndahN6TX..0ZufeAjRzC3ji4ksM8xfDXUrT17P31O', '08123456887', 'Desa Ayunan, Abiansemal, badung - Bali', NULL, 'Laki-laki', '2025-04-24', 'Abiansemal', 5, 'Foto20250424093328.png', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-24 13:33:28', '2025-04-24 13:33:28');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `award`
--
ALTER TABLE `award`
  ADD PRIMARY KEY (`id_award`);

--
-- Indeks untuk tabel `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id_berita`);

--
-- Indeks untuk tabel `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id_galeri`);

--
-- Indeks untuk tabel `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id_project`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id_setting`);

--
-- Indeks untuk tabel `testimonial`
--
ALTER TABLE `testimonial`
  ADD PRIMARY KEY (`id_testimonial`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `award`
--
ALTER TABLE `award`
  MODIFY `id_award` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `berita`
--
ALTER TABLE `berita`
  MODIFY `id_berita` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id_galeri` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id_layanan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `project`
--
ALTER TABLE `project`
  MODIFY `id_project` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `setting`
--
ALTER TABLE `setting`
  MODIFY `id_setting` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `testimonial`
--
ALTER TABLE `testimonial`
  MODIFY `id_testimonial` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
