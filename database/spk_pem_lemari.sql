-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Sep 2023 pada 17.45
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_pem_lemari`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama_alternatif` varchar(50) NOT NULL,
  `gambar` varchar(100) NOT NULL,
  `design` varchar(10) NOT NULL,
  `merek` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `nama_alternatif`, `gambar`, `design`, `merek`) VALUES
(1, 'Hamster LP 202', '1.jpg', 'Motif', 'Olympic'),
(2, 'JAZZ LP 301', '2.jpg', 'Warna', 'Volare'),
(3, 'SPIN SL 80', '3.jpg', 'Motif', 'Pire'),
(4, 'Primo BL 120', '4.jpg', 'Motif', 'Pire'),
(5, 'SLIDE 211', '5.jpg', 'Warna', 'Pro Design');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bobot_kriteria`
--

CREATE TABLE `bobot_kriteria` (
  `id_bobot` int(11) NOT NULL,
  `C1` float NOT NULL,
  `C2` float NOT NULL,
  `C3` float NOT NULL,
  `C4` float NOT NULL,
  `f_id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `bobot_kriteria`
--

INSERT INTO `bobot_kriteria` (`id_bobot`, `C1`, `C2`, `C3`, `C4`, `f_id_user`) VALUES
(3, 0.4, 0, 0, 0.6, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kec_alt_kriteria`
--

CREATE TABLE `kec_alt_kriteria` (
  `id_alt_kriteria` int(11) NOT NULL,
  `f_id_alternatif` int(11) NOT NULL,
  `f_id_kriteria` char(2) NOT NULL,
  `f_id_sub_kriteria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kec_alt_kriteria`
--

INSERT INTO `kec_alt_kriteria` (`id_alt_kriteria`, `f_id_alternatif`, `f_id_kriteria`, `f_id_sub_kriteria`) VALUES
(1, 1, 'C1', 2),
(2, 1, 'C2', 10),
(3, 1, 'C3', 11),
(4, 1, 'C4', 18),
(6, 2, 'C1', 3),
(7, 2, 'C2', 9),
(8, 2, 'C3', 15),
(9, 2, 'C4', 16),
(11, 3, 'C1', 1),
(12, 3, 'C2', 8),
(13, 3, 'C3', 12),
(14, 3, 'C4', 16),
(16, 4, 'C1', 1),
(17, 4, 'C2', 9),
(18, 4, 'C3', 11),
(19, 4, 'C4', 19),
(21, 5, 'C1', 4),
(22, 5, 'C2', 10),
(23, 5, 'C3', 15),
(24, 5, 'C4', 16);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` char(2) NOT NULL,
  `nama_kriteria` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`) VALUES
('C1', 'Harga'),
('C2', 'Kualitas'),
('C3', 'Volume'),
('C4', 'Kelengkapan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_kriteria`
--

CREATE TABLE `sub_kriteria` (
  `id_sub_kriteria` int(11) NOT NULL,
  `nama_sub_kriteria` varchar(150) NOT NULL,
  `bobot_sub_kriteria` int(11) NOT NULL,
  `f_id_kriteria` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `sub_kriteria`
--

INSERT INTO `sub_kriteria` (`id_sub_kriteria`, `nama_sub_kriteria`, `bobot_sub_kriteria`, `f_id_kriteria`) VALUES
(1, '500.000 ≤ Harga < 1.000.000', 4, 'C1'),
(2, '1.000.000≤ Harga <1.500.000', 3, 'C1'),
(3, '1.500.000 ≤ Harga <2 .000.000', 2, 'C1'),
(4, 'Harga ≥ 2.000.000', 1, 'C1'),
(8, 'Mudah lapuk,tidak kokoh,mudah terkena jamur', 1, 'C2'),
(9, '(Tidak mudah lapuk,Kurang kokoh,anti jamur), (Tidak mudah lapuk,kokoh,mudah terkena jamur)\r\n(mudah lapuk, kokoh, anti jamur)', 3, 'C2'),
(10, 'Kokoh, tidak mudah lapuk dan anti jamur', 5, 'C2'),
(11, '〖400.000cm〗^3≤Luas<〖700.000cm〗^3', 1, 'C3'),
(12, '〖700.000cm〗^3≤Luas<〖800.000cm〗^3', 2, 'C3'),
(13, '〖800.000cm〗^3≤Luas<〖900.000cm〗^3', 3, 'C3'),
(14, '〖900.000cm〗^3≤Luas<〖1.000.000cm〗^3', 4, 'C3'),
(15, 'Luas ≥〖1.000.000cm〗^3', 5, 'C3'),
(16, '4 kelengkapan', 4, 'C4'),
(17, '3 kelengkapan', 3, 'C4'),
(18, '2 kelengkapan', 2, 'C4'),
(19, '1 kelengkapan', 1, 'C4');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_tampung`
--

CREATE TABLE `tabel_tampung` (
  `id` int(11) NOT NULL,
  `prio1` varchar(50) NOT NULL,
  `prio2` varchar(50) NOT NULL,
  `prio3` varchar(50) NOT NULL,
  `prio4` varchar(50) NOT NULL,
  `f_id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tabel_tampung`
--

INSERT INTO `tabel_tampung` (`id`, `prio1`, `prio2`, `prio3`, `prio4`, `f_id_user`) VALUES
(2, 'Volume', 'Harga', 'Kelengkapan', 'Kualitas', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `level`) VALUES
(2, 'admin', '$2y$10$vKlD7o2zW7D0NyeRZ9gIOuq/H5cD/hjZgmjZ20.8.yRE9FHaJKqkq', 0),
(7, 'yupi', '$2y$10$ZxU.kfq5Z4OIgfdL4MF8pOvO2Xwn.tvyo.2Yk.Ngmz8AXlYKsZONG', 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indeks untuk tabel `bobot_kriteria`
--
ALTER TABLE `bobot_kriteria`
  ADD PRIMARY KEY (`id_bobot`),
  ADD KEY `id_user` (`f_id_user`);

--
-- Indeks untuk tabel `kec_alt_kriteria`
--
ALTER TABLE `kec_alt_kriteria`
  ADD PRIMARY KEY (`id_alt_kriteria`),
  ADD KEY `f_id_alternatif` (`f_id_alternatif`),
  ADD KEY `f_id_kriteria` (`f_id_kriteria`,`f_id_sub_kriteria`),
  ADD KEY `f_id_sub_kriteria` (`f_id_sub_kriteria`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indeks untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD PRIMARY KEY (`id_sub_kriteria`),
  ADD KEY `f_id_kriteria` (`f_id_kriteria`),
  ADD KEY `f_id_kriteria_2` (`f_id_kriteria`);

--
-- Indeks untuk tabel `tabel_tampung`
--
ALTER TABLE `tabel_tampung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `f_id_user` (`f_id_user`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `bobot_kriteria`
--
ALTER TABLE `bobot_kriteria`
  MODIFY `id_bobot` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kec_alt_kriteria`
--
ALTER TABLE `kec_alt_kriteria`
  MODIFY `id_alt_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  MODIFY `id_sub_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `tabel_tampung`
--
ALTER TABLE `tabel_tampung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bobot_kriteria`
--
ALTER TABLE `bobot_kriteria`
  ADD CONSTRAINT `bobot_kriteria_ibfk_1` FOREIGN KEY (`f_id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kec_alt_kriteria`
--
ALTER TABLE `kec_alt_kriteria`
  ADD CONSTRAINT `kec_alt_kriteria_ibfk_1` FOREIGN KEY (`f_id_alternatif`) REFERENCES `alternatif` (`id_alternatif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kec_alt_kriteria_ibfk_2` FOREIGN KEY (`f_id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kec_alt_kriteria_ibfk_3` FOREIGN KEY (`f_id_sub_kriteria`) REFERENCES `sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sub_kriteria`
--
ALTER TABLE `sub_kriteria`
  ADD CONSTRAINT `sub_kriteria_ibfk_1` FOREIGN KEY (`f_id_kriteria`) REFERENCES `kriteria` (`id_kriteria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tabel_tampung`
--
ALTER TABLE `tabel_tampung`
  ADD CONSTRAINT `tabel_tampung_ibfk_1` FOREIGN KEY (`f_id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
