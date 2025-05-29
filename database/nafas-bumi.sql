-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Bulan Mei 2025 pada 14.35
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
-- Database: `nafas-bumi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `author_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `content`
--

INSERT INTO `content` (`id`, `title`, `content`, `image`, `created_at`, `updated_at`, `author_id`) VALUES
(8, 'Sampah Plastik Ancam Lautan Indonesia', 'Jakarta, 28 Mei 2025 — Pencemaran sampah plastik di Indonesia semakin mengkhawatirkan. Data KLHK menyebutkan sekitar 620 ribu ton sampah plastik masuk ke laut setiap tahun. Hal ini berdampak serius pada ekosistem laut dan kesehatan manusia.\r\n\r\nPenelitian di perairan Sulawesi menemukan mikroplastik dalam tubuh lebih dari 50% ikan yang diteliti. Pakar lingkungan menilai solusi harus melibatkan pemerintah, industri, dan masyarakat untuk mengurangi penggunaan plastik sekali pakai dan meningkatkan sistem daur ulang.\r\n\r\nMeski ada kebijakan pengurangan kantong plastik sejak 2020, implementasinya masih lemah. Komunitas lingkungan terus mendorong edukasi publik agar sadar pentingnya menjaga lingkungan.', 'sampah.jpeg', '2025-05-28 14:48:35', '2025-05-29 05:35:14', 2),
(9, 'Kebakaran Hutan Kembali Landa Kalimantan, Asap Cemari Udara', 'Palangka Raya, 28 Mei 2025 — Kebakaran hutan kembali terjadi di wilayah Kalimantan Tengah akibat musim kemarau panjang dan pembukaan lahan secara ilegal. Dalam sepekan terakhir, lebih dari 1.000 hektare hutan dilaporkan terbakar.\r\n\r\nAsap pekat mulai menyelimuti beberapa kota, mengganggu aktivitas warga dan meningkatkan kasus gangguan pernapasan. Pemerintah daerah telah menetapkan status siaga darurat dan mengerahkan tim pemadam gabungan.\r\n\r\nAktivis lingkungan mendesak penegakan hukum terhadap pelaku pembakaran lahan dan meminta pemerintah memperkuat upaya pencegahan kebakaran secara berkelanjutan.\r\n\r\n', 'kebakaran.jpeg', '2025-05-28 14:49:56', '2025-05-29 05:34:56', 2),
(13, 'Perbaiki Kualitas Udara, Polres Metro Tangerang Kota Tanam 1.000 Pohon', 'Tangerang - Polres Metro Tangerang Kota menanam seribu pohon di 12 polsek untuk membantu memperbaiki kualitas udara di wilayahnya. Gerakan penghijauan itu bagian dari perbaikan kualitas udara di wilayah Jabodetabek (Jakarta, Bogor, Depok, Tangerang dan Bekasi).\r\n\r\nKapolres Metro Tangerang Kota Komisaris Besar Polisi Zain Dwi Nugroho mengatakan kegiatan polisi peduli penghijauan dilakukan secara serentak di seluruh wilayah hukum Polda Metro Jaya.', 'tanam.jpg', '2025-05-29 10:43:49', '2025-05-29 10:43:49', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `event_date` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `location`, `created_at`, `updated_at`) VALUES
(1, 'Bersih-Bersih Pantai', 'Kegiatan bersih-bersih pantai untuk menjaga kebersihan lingkungan.', '2027-11-03 09:00:00', 'Pantai Kuta', '2025-05-27 10:12:42', '2025-05-27 10:23:51'),
(2, 'Workshop Daur Ulang', 'Workshop tentang cara mendaur ulang sampah plastik menjadi barang berguna.', '2026-12-10 10:00:00', 'Gedung Serbaguna', '2025-05-27 10:12:42', '2025-05-29 05:40:59'),
(3, 'Penanaman Pohon', 'Ayo ikut serta dalam kegiatan penanaman pohon di taman kota.', '2026-12-16 08:00:00', 'Taman Kota', '2025-05-27 10:12:42', '2025-05-29 05:41:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `join_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `members`
--

INSERT INTO `members` (`id`, `user_id`, `phone`, `address`, `join_date`) VALUES
(1, 2, '082128335374', 'krajan', '2025-05-24 12:23:21'),
(2, 3, '123', 'asd', '2025-05-24 12:58:03'),
(3, 4, '082128335374', 'Tanjunganom\r\nJatinom', '2025-05-27 13:59:53'),
(4, 5, '0920930927', 'jogja', '2025-05-28 09:39:48'),
(5, 6, '7890', 'lmknjbh', '2025-05-29 08:41:25'),
(6, 7, '12', 'qwe', '2025-05-29 10:29:37'),
(7, 8, '123', 'qwe', '2025-05-29 12:19:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `is_read`) VALUES
(4, 'YB', 'YB@gamersganteng.idaman', 'apresiasi', 'ngerinyoooo', '2025-05-29 05:57:11', 1),
(6, 'Ahmad Santosa', 'wqe@o.o', 'adf', 'weqweqw', '2025-05-29 10:17:45', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','member') DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$q6mLhAKzw//zyvVjfZedb.maGr8Ij8/V1Wi1Vq17J5.bwwXat6fAy', 'info@nafasbumi.com', 'Administrator', 'admin', '2025-05-29 12:32:19'),
(2, 'admin21', '$2y$10$q6mLhAKzw//zyvVjfZedb.maGr8Ij8/V1Wi1Vq17J5.bwwXat6fAy', 'h@gmail.com', 'ahmad santosa', 'admin', '2025-05-24 12:23:21'),
(3, 'prim', '$2y$10$S.8XMW1f9OcMZEejKIf8yeU05L0dyAHpnkDSeytQGmg3AMu8ibN9G', 'p@g.b', 'prim', 'member', '2025-05-24 12:58:03'),
(4, 'admin1', '$2y$10$MPhN99B8DXxDJcrWuuNDH.9WcZDgvCKRkKTIClF93lkne4wcV.B.u', 'santosa@students.amikom.ac.id', 'santos', 'member', '2025-05-27 13:59:53'),
(5, 'laksana', '$2y$10$hVVsNGWzSmt3qwQuWE11i.snGE3eO6QXNxX4cLt6kDi.RdudPTfoq', 'laksana@gmail.com', 'Laksana', 'member', '2025-05-28 09:39:48'),
(6, 'zxv', '$2y$10$BfVpQUkWv2xAeg6kXlHnT..zC3GXzEt4I3xUSSx8atLpRy18Tv/uy', 'hallo0509@asd.com', 'zxc', 'member', '2025-05-29 08:41:25'),
(7, 'yb', '$2y$10$AnXFb0A5B38NHaj5pUwD9uIgd7SueW5HHzXd61iyeJ15LxMLshR/6', 'YB@gamersganteng.idaman', 'opop', 'member', '2025-05-29 10:29:37'),
(8, 'gtr', '$2y$10$1Q9em/haPRfw9K1lnivtq.C5lMarJRBuopwbCjfZWTxIF39er70im', 'qe@f.fa', 'ds', 'member', '2025-05-29 12:19:20');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indeks untuk tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `content_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
