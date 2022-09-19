-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2022 at 01:16 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `preorder_smart_canteen`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `id_penjual` int(11) DEFAULT NULL,
  `kategori` varchar(10) NOT NULL,
  `nama_menu` varchar(30) NOT NULL,
  `harga` bigint(20) NOT NULL,
  `info` varchar(50) DEFAULT NULL,
  `gambar` varchar(30) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `id_penjual`, `kategori`, `nama_menu`, `harga`, `info`, `gambar`, `stock`) VALUES
(1, 4, 'makanan', 'Mie Bakso', 10000, 'Dilengkapi bakso besar', '62234fed47d0d.jpg', 5),
(2, 4, 'makanan', 'Nasi Goreng Pedas', 12000, 'Spesial pedas dan sayur', '622350637e76b.jpeg', 5),
(3, 4, 'makanan', 'Soto Ayam', 18000, 'Kaldu ayam yang melekat', '622350cc7a6fd.jpg', 5),
(4, 4, 'minuman', 'Es Teh Lemon', 4000, 'Dengan lemon segar', '622351028ce35.jpg', 5),
(5, 4, 'minuman', 'Jus Mangga', 6000, 'Mangga segar baru dipetik hari ini', '6223513c2a59a.jpg', 5),
(6, 10, 'makanan', 'Mie Ceker', 9000, 'Dilengkapi ceker ayam', '622351dd5b0ef.jpg', 5),
(7, 10, 'makanan', 'Nasi Goreng Telur', 15000, 'Telur dengan tingkat kematangan sempurna', '62235229ae817.jpeg', 5),
(8, 10, 'makanan', 'Soto Daging', 20000, 'Daging sapi yang dijamin empuk', '6223526290260.jpg', 5),
(9, 10, 'minuman', 'Es Teh Jumbo ', 5000, 'Es teh dengan porsi ganda', '622352a13847c.jpg', 5),
(10, 11, 'makanan', 'Mie Sayur', 8000, 'Mie sehat terbuat dari sayur', '622353899f990.jpg', 5),
(11, 11, 'makanan', 'Nasi Goreng Jumbo', 15000, 'Porsi besar dengan potongan ayam', '622353d701dc0.jpg', 5),
(12, 11, 'makanan', 'Sop Sumsum', 25000, 'Sop sumsum besar segar', '62235414ac04c.jpg', 5),
(13, 11, 'minuman', 'Teh Thailand', 5000, 'Teh tradisional Thailand asli', '622354542caa4.jpg', 5),
(23, 14, 'makanan', 'Raspberry Cake', 7000, 'cake yang manis dan segar, dessert yang sempurna', '6327d34c9f709.jpg', 5),
(24, 14, 'makanan', 'Cheese Mushroom Pizza', 40000, 'keju yang meleleh dengan jamur pada pizza, cocok d', '6327d4fd1d566.jpg', 5),
(25, 14, 'makanan', 'Cobb Salad', 18000, 'salad dengan rebusan ayam, makanan sehat', '6327d7083f8ad.jpg', 5),
(26, 14, 'minuman', 'Strawberry and Lime Juice', 6000, 'jus campuran stroberi dan jeruk nipis yang segar', '6327d848e1512.jpg', 5),
(27, 14, 'minuman', 'Cherry and Lemon Juice', 6000, 'jus campuran ceri dan lemon yang segar', '6327d923687db.jpg', 5),
(28, 15, 'makanan', 'Egg Toast', 12000, 'roti bakan dengan telur, cocok untuk sarapan', '6327fcce58d1a.jpg', 5),
(29, 15, 'makanan', 'Onion Rings', 12000, 'cemilan yang crispy enak, menunda lapar', '6327fd88e84ae.jpg', 5),
(30, 15, 'makanan', 'Chicken Fingers', 15000, 'ayam crispy yang enak, dengan special sauce', '6327fe579e460.jpg', 5),
(31, 15, 'minuman', 'Black Coffee', 4000, 'kopi hitam', '6328017359d6d.jpg', 5),
(32, 15, 'minuman', 'Latte', 6000, 'kopi latte (espresso dan steamed milk)', '6328023b87d8f.jpg', 5),
(33, 15, 'minuman', 'Macchiato', 6000, 'kopi macchiato, espresso dengan busa di atasnya', '632802fa17461.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `midtrans`
--

CREATE TABLE `midtrans` (
  `id` bigint(11) NOT NULL,
  `kode_pesanan` varchar(10) NOT NULL,
  `metode_bayar` varchar(100) NOT NULL,
  `status_payment` varchar(20) NOT NULL,
  `waktu_transaksi` datetime NOT NULL,
  `waktu_terbayar` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `midtrans`
--

INSERT INTO `midtrans` (`id`, `kode_pesanan`, `metode_bayar`, `status_payment`, `waktu_transaksi`, `waktu_terbayar`) VALUES
(1, 'Q8EAKUM4LZ', 'bank_transfer', 'pending', '2022-04-15 18:03:15', '0000-00-00 00:00:00'),
(2, 'kode pesan', 'metode mencoba', 'waktu', '2022-04-25 13:41:54', '2022-04-25 13:41:54'),
(3, 'KODE ANDA', 'metode mencoba', 'waktu', '2022-04-25 13:44:02', '2022-04-25 13:44:02'),
(4, 'KODE ANDA', 'metode mencoba', 'waktu', '2022-04-25 20:54:09', '2022-04-25 13:54:09'),
(5, 'KODE ANDA', 'metode mencoba', 'waktu', '2022-04-25 20:55:01', '2022-04-25 13:55:01'),
(6, 'KODE ANDA', 'metode mencoba', 'waktu', '2022-04-25 20:56:10', '2022-04-25 13:56:10'),
(7, 'KODE ANDA', 'metode mencoba', 'waktu', '2022-04-25 14:06:11', '2022-04-25 13:56:11'),
(8, 'KODE ANDA', 'metode mencoba', 'waktu', '2022-04-25 20:58:15', '2022-04-25 13:58:15'),
(9, 'KODE ANDA', 'metode mencoba', 'waktu', '2022-04-25 14:08:16', '2022-04-25 13:58:16'),
(10, 'KODE ANDA', 'metode mencoba', 'waktu', '2022-04-25 13:57:46', '2022-04-25 13:58:16'),
(11, 'KODE ANDA', 'metode mencoba', 'waktu', '2023-04-25 14:02:50', '2022-04-25 14:02:50'),
(12, 'KODE ANDA', 'metode mencoba', 'waktu', '2022-07-25 14:02:50', '2022-04-25 14:02:50'),
(13, 'KODE ANDA', 'metode mencoba', 'waktu', '2022-04-27 14:02:51', '2022-04-25 14:02:51'),
(14, 'KODE ANDA', 'metode mencoba', 'waktu', '2023-04-25 14:04:37', '2022-04-25 14:04:37'),
(15, 'KODE ANDA', 'metode mencoba', 'waktu', '2022-01-25 14:04:37', '2022-04-25 14:04:37'),
(16, 'KODE ANDA', 'metode mencoba', 'waktu', '2022-04-27 14:04:37', '2022-04-25 14:04:37'),
(17, 'EE7LKYED8P', 'cstore', 'settlement', '2022-09-13 19:45:34', '2022-09-13 19:52:24'),
(18, 'EE7LKYED8P', 'cstore', 'settlement', '2022-09-13 19:52:04', '2022-09-13 19:52:24'),
(19, 'Q2TTEC48C9', 'bank_transfer', 'settlement', '2022-09-13 19:54:43', '2022-09-13 19:56:09'),
(20, 'ANA6HHWT5Z', 'qris', 'expire', '2022-09-13 20:19:48', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `pemicu`
--

CREATE TABLE `pemicu` (
  `id_pemicu` bigint(20) NOT NULL,
  `kode_pesanan` varchar(10) DEFAULT NULL,
  `metode_bayar` varchar(100) DEFAULT NULL,
  `status_payment` varchar(20) DEFAULT NULL,
  `waktu_terbayar` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` bigint(20) NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `id_pembeli` int(11) DEFAULT NULL,
  `id_penjual` int(11) DEFAULT NULL,
  `menu_dipesan` varchar(200) NOT NULL,
  `jumlah_pesanan` varchar(50) NOT NULL,
  `jumlah_transaksi` bigint(20) NOT NULL,
  `kode_pesanan` varchar(10) NOT NULL,
  `tipe_pesanan` varchar(30) NOT NULL,
  `status` varchar(30) NOT NULL,
  `snap_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `tanggal`, `id_pembeli`, `id_penjual`, `menu_dipesan`, `jumlah_pesanan`, `jumlah_transaksi`, `kode_pesanan`, `tipe_pesanan`, `status`, `snap_token`) VALUES
(1, '2022-04-15 17:28:04', 12, 4, 'Soto Ayam', '1', 18000, '6V64FY3X1G', 'saldo', 'terbayar', ''),
(3, '2022-04-15 17:29:39', 12, 10, 'Mie Ceker', '1', 9000, 'WIHZKCMVTB', 'cash', 'belum terbayar', ''),
(4, '2022-04-15 17:43:32', 12, 10, 'Mie Ceker', '1', 9000, '826IFHP6XZ', 'saldo', 'terbayar', ''),
(5, '2022-04-15 17:58:42', 12, 10, 'Nasi Goreng Telur', '2', 30000, 'PRN7YEIZU4', 'cash', 'belum terbayar', ''),
(6, '2022-04-15 17:59:45', 12, 10, 'Nasi Goreng Telur', '1', 15000, 'VDPXR68J2L', 'cash', 'belum terbayar', ''),
(7, '2022-04-15 18:02:39', 12, 10, 'Nasi Goreng Telur', '1', 15000, 'FFC8XJMM19', 'saldo', 'terbayar', ''),
(8, '2022-04-15 18:03:19', 12, 10, 'Nasi Goreng Telur', '1', 15000, 'Q8EAKUM4LZ', 'payment gateaway', 'pending', '3bb87a2b-5207-4875-9bfe-2836b4fdbfbf'),
(9, '2022-04-15 18:03:19', 12, 11, 'Mie Sayur', '1', 8000, 'Q8EAKUM4LZ', 'payment gateaway', 'pending', '3bb87a2b-5207-4875-9bfe-2836b4fdbfbf'),
(15, '2022-08-29 19:01:41', 16, 4, 'Soto Ayam', '2', 36000, 'T1H8V8E99U', 'saldo', 'terbayar', ''),
(17, '2022-08-29 19:02:25', 16, 11, 'Mie Sayur', '2', 16000, '1KYJBT7RRG', 'cash', 'belum terbayar', ''),
(18, '2022-08-29 19:02:25', 16, 10, 'Es Teh Jumbo ', '1', 5000, '1KYJBT7RRG', 'cash', 'belum terbayar', ''),
(19, '2022-08-29 19:03:34', 16, 14, 'Acar', '1', 3000, 'RRX6FC4X9P', 'saldo', 'terbayar', ''),
(21, '2022-08-29 19:03:35', 16, 10, 'Es Teh Jumbo , Soto Daging', '1, 1', 25000, 'RRX6FC4X9P', 'saldo', 'terbayar', ''),
(30, '2022-09-09 18:38:46', 16, 4, 'Mie Bakso', '1', 10000, 'XPT8UWLG5D', 'cash', 'belum terbayar', ''),
(31, '2022-09-13 19:43:42', 12, 11, 'Nasi Goreng Jumbo', '1', 15000, '389S1IU59U', 'payment gateaway', '', '623bbfb6-f5a2-44e0-b976-75522a3c255e'),
(32, '2022-09-13 19:43:42', 12, 14, 'Jeruk Nipis', '1', 2000, '389S1IU59U', 'payment gateaway', '', '623bbfb6-f5a2-44e0-b976-75522a3c255e'),
(33, '2022-09-13 19:46:20', 12, 10, 'Mie Ceker', '1', 9000, 'EE7LKYED8P', 'payment gateaway', 'settlement', '53a4caed-484a-430d-a4fe-46bcdda66b0a'),
(34, '2022-09-13 19:54:43', 12, 11, 'Mie Sayur', '1', 8000, 'Q2TTEC48C9', 'payment gateaway', 'settlement', 'be0d644e-d340-4c44-bf61-fcf07f95a008'),
(35, '2022-09-13 19:58:23', 12, 10, 'Mie Ceker', '1', 9000, 'S3ENXT8PN0', 'payment gateaway', '', '62e98827-9985-4b3c-9b83-753216289051'),
(36, '2022-09-13 20:00:22', 12, 10, 'Mie Ceker', '1', 9000, 'WIZ515L8EP', 'payment gateaway', '', '99e70fbd-74b1-46f7-b58e-2aad338e684c'),
(37, '2022-09-13 20:00:46', 12, 10, 'Mie Ceker', '1', 9000, '1UXWY8YCMW', 'payment gateaway', '', '033aff15-03a1-4bff-89cd-4f64536bb7ea'),
(38, '2022-09-13 20:01:10', 12, 10, 'Mie Ceker', '1', 9000, 'PKCKV4NMKT', 'payment gateaway', '', '0385dbfa-ae1a-4842-8c71-f7b077cdff1b'),
(39, '2022-09-13 20:01:33', 12, 10, 'Mie Ceker', '1', 9000, 'BU0OFKIMFF', 'payment gateaway', '', '67377b1a-35c9-41e8-a29c-c82a03829b2a'),
(40, '2022-09-13 20:15:36', 12, 10, 'Mie Ceker', '1', 9000, '42AG5C4ZCK', 'cash', 'belum terbayar', ''),
(41, '2022-09-13 20:15:36', 12, 4, 'Soto Ayam', '1', 18000, '42AG5C4ZCK', 'cash', 'belum terbayar', ''),
(42, '2022-09-13 20:16:25', 12, 4, 'Mie Bakso', '1', 10000, 'JVUMNFHABT', 'saldo', 'terbayar', ''),
(43, '2022-09-13 20:17:12', 12, 10, 'Nasi Goreng Telur', '2', 30000, 'E8I9P0QGHI', 'cash', 'belum terbayar', ''),
(44, '2022-09-13 20:17:12', 12, 4, 'Jus Mangga', '1', 6000, 'E8I9P0QGHI', 'cash', 'belum terbayar', ''),
(45, '2022-09-13 20:19:53', 12, 4, 'Jus Mangga', '1', 6000, 'ANA6HHWT5Z', 'payment gateaway', 'expire', 'bab988b5-0284-48c6-9c92-b39f5ee6a030');

-- --------------------------------------------------------

--
-- Table structure for table `request_cair`
--

CREATE TABLE `request_cair` (
  `id_cair` bigint(20) NOT NULL,
  `id_penjual` int(11) DEFAULT NULL,
  `username_penjual` varchar(20) DEFAULT NULL,
  `email_penjual` varchar(30) DEFAULT NULL,
  `nominal_request` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `request_cair`
--

INSERT INTO `request_cair` (`id_cair`, `id_penjual`, `username_penjual`, `email_penjual`, `nominal_request`) VALUES
(3, 10, 'richard123', 'richard123@gmail.com', 5000),
(6, 4, 'budi123', 'budi321@gmail.com', 50000);

-- --------------------------------------------------------

--
-- Table structure for table `request_saldo`
--

CREATE TABLE `request_saldo` (
  `id_saldo` bigint(20) NOT NULL,
  `id_pembeli` int(11) DEFAULT NULL,
  `username_pembeli` varchar(20) NOT NULL,
  `email_pembeli` varchar(30) DEFAULT NULL,
  `nominal_request` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `request_saldo`
--

INSERT INTO `request_saldo` (`id_saldo`, `id_pembeli`, `username_pembeli`, `email_pembeli`, `nominal_request`) VALUES
(6, 16, 'maulana123', 'maulana123@gmail.com', 200000),
(7, 16, 'maulana123', 'maulana123@gmail.com', 50000),
(8, 16, 'maulana123', 'maulana123@gmail.com', 200000);

-- --------------------------------------------------------

--
-- Table structure for table `riwayat`
--

CREATE TABLE `riwayat` (
  `id_riwayat` bigint(20) NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `id_pembeli` int(11) DEFAULT NULL,
  `id_penjual` int(11) DEFAULT NULL,
  `menu_dipesan` varchar(200) NOT NULL,
  `jumlah_pesanan` varchar(50) NOT NULL,
  `jumlah_transaksi` bigint(20) NOT NULL,
  `keterangan` varchar(20) NOT NULL,
  `alasan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `riwayat`
--

INSERT INTO `riwayat` (`id_riwayat`, `tanggal`, `id_pembeli`, `id_penjual`, `menu_dipesan`, `jumlah_pesanan`, `jumlah_transaksi`, `keterangan`, `alasan`) VALUES
(1, '2022-07-18 11:31:46', 16, 4, 'Mie Bakso, Nasi Goreng Pedas', '3, 2', 54000, 'berhasil', ''),
(2, '2022-07-29 11:43:42', 16, 4, 'Mie Bakso', '2', 20000, 'berhasil', ''),
(3, '2022-07-29 11:44:40', 16, 14, 'Jeruk Nipis', '2', 4000, 'gagal', 'pembeli tidak datang'),
(4, '2022-07-29 11:48:08', 16, 4, 'Mie Bakso, Nasi Goreng Pedas', '1, 1', 22000, 'berhasil', ''),
(5, '2022-08-26 06:42:48', 12, 4, 'Soto Ayam', '1', 18000, 'berhasil', ''),
(6, '2022-08-26 07:19:10', 16, 10, 'Mie Ceker', '1', 9000, 'berhasil', ''),
(7, '2022-09-09 18:28:07', 16, 4, 'Soto Ayam, Es Teh Lemon', '1, 1', 22000, 'gagal', 'pembeli kabur'),
(8, '2022-09-09 18:28:24', 16, 4, 'Mie Bakso', '1', 10000, 'gagal', 'pembeli /tidak jelas'),
(9, '2022-09-09 18:28:44', 16, 4, 'Es Teh Lemon', '2', 8000, 'gagal', 'pembeli aneh;'),
(10, '2022-09-09 18:37:20', 16, 4, 'Soto Ayam, Jus Mangga', '2, 1', 42000, 'gagal', 'fajar&lt;&gt;'),
(11, '2022-09-09 18:39:13', 16, 4, 'Soto Ayam', '1', 18000, 'gagal', 'fajar budi &lt;&gt;'),
(12, '2022-09-09 18:39:26', 16, 4, 'Mie Bakso', '1', 10000, 'gagal', 'fajar %%$$%'),
(15, '2022-09-10 08:39:12', 16, 11, 'Sop Sumsum', '2', 50000, 'berhasil', ''),
(16, '2022-09-10 08:39:16', 16, 11, 'Teh Thailand', '1', 5000, 'berhasil', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_pembeli`
--

CREATE TABLE `user_pembeli` (
  `id_pembeli` int(11) NOT NULL,
  `username_pembeli` varchar(20) NOT NULL,
  `email_pembeli` varchar(30) DEFAULT NULL,
  `no_telpon_pembeli` varchar(15) NOT NULL,
  `saldo_pembeli` bigint(20) DEFAULT NULL,
  `password_pembeli` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_pembeli`
--

INSERT INTO `user_pembeli` (`id_pembeli`, `username_pembeli`, `email_pembeli`, `no_telpon_pembeli`, `saldo_pembeli`, `password_pembeli`) VALUES
(12, 'avenue123', 'avenue123@gmail.com', '082082082082', 352000, '$2y$10$8bR4vx6Qd3aSNNbCfZtfxOQXiuCKmnGEeN4WZTc6gB6hGPNhmc.ve'),
(13, 'jamesavenue', 'avenue@gmail.com', '0808080', 0, '$2y$10$48wnL/QO34EMngLYcT2VrOUZIUendG5bNJcDFzBtafzWw6OjwuvB2'),
(16, 'maulana123', 'maulana123@gmail.com', '081990383442', 451000, '$2y$10$oYdj7T9uB/C3HyZDOg.3Hey7FkI/ReGHaVRoxtfa/z4UVbyZuoZuK'),
(21, 'mihawkeye', 'mihawkeye@gmail.com', '321321321322', 0, '$2y$10$lYM.ser9SC082Ifs6r3utuY7AR4mf0BIOPPs.Q2w17bG01oxbr1CW'),
(28, 'joni123', 'joni123@gmail.com', '123123123123', 0, '$2y$10$UBWtaxT5urmBGveoROU3yuRm2MqxtMgIwV7Lxv2IqPpyg3iPCI6Wy'),
(29, 'joju123', 'joju123@gmail.com', '081231231231', 0, '$2y$10$zznyxnlNtSKYO8woabQcdeqcoGeBK3eWnfpy2XDjdPToqC2hoEYVa'),
(31, 'alux123', 'alux123@gmail.com', '084123123123', 0, '$2y$10$YT6T2U8r1OJH87HpxnEEoeOVV/YAa2waKdU4c4pinXPDUBkpcpGX2');

-- --------------------------------------------------------

--
-- Table structure for table `user_penjual`
--

CREATE TABLE `user_penjual` (
  `id_penjual` int(11) NOT NULL,
  `username_penjual` varchar(20) NOT NULL,
  `email_penjual` varchar(30) DEFAULT NULL,
  `no_telpon_penjual` varchar(15) NOT NULL,
  `saldo_penjual` bigint(20) DEFAULT NULL,
  `password_penjual` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_penjual`
--

INSERT INTO `user_penjual` (`id_penjual`, `username_penjual`, `email_penjual`, `no_telpon_penjual`, `saldo_penjual`, `password_penjual`) VALUES
(4, 'budi123', 'budi321@gmail.com', '083083083083', 172000, '$2y$10$YqEHovUiDlRD.Na08eRjg.yq7PxVdYa86vwh/X10ZegugJ.wE18SO'),
(10, 'richard123', 'richard123@gmail.com', '021021021022', 142000, '$2y$10$6VWra2bgDkCJwuIqWiaAY.kuOaU5jG42rye91Hph6QW8d1i34C1Rm'),
(11, 'alex123', 'alex123@gmail.com', '321321321321', 104000, '$2y$10$0mtC1P1PrYJQGtlAPc5HXuFYm1bBpuEInOXMj2yyYvgLdp6NUMkdm'),
(14, 'james123', 'james123@gmail.com', '081081081081', 3000, '$2y$10$SVKPVSnnd2X.3UAvngiVauqB09Dtc0WEzeznuRMOy8GLivkPUrrGa'),
(15, 'lola123', 'lola123@gmail.com', '081332567884', 0, '$2y$10$agWp1/ADMDTCya5US9PPjec8VIA9g2h3Hiv5e5TJ7RUUglUtnrdEG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `id_penjual` (`id_penjual`);

--
-- Indexes for table `midtrans`
--
ALTER TABLE `midtrans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemicu`
--
ALTER TABLE `pemicu`
  ADD PRIMARY KEY (`id_pemicu`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pembeli` (`id_pembeli`),
  ADD KEY `id_penjual` (`id_penjual`);

--
-- Indexes for table `request_cair`
--
ALTER TABLE `request_cair`
  ADD PRIMARY KEY (`id_cair`),
  ADD KEY `id_penjual` (`id_penjual`);

--
-- Indexes for table `request_saldo`
--
ALTER TABLE `request_saldo`
  ADD PRIMARY KEY (`id_saldo`),
  ADD KEY `id_pembeli` (`id_pembeli`);

--
-- Indexes for table `riwayat`
--
ALTER TABLE `riwayat`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_pembeli` (`id_pembeli`),
  ADD KEY `id_penjual` (`id_penjual`);

--
-- Indexes for table `user_pembeli`
--
ALTER TABLE `user_pembeli`
  ADD PRIMARY KEY (`id_pembeli`);

--
-- Indexes for table `user_penjual`
--
ALTER TABLE `user_penjual`
  ADD PRIMARY KEY (`id_penjual`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `midtrans`
--
ALTER TABLE `midtrans`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pemicu`
--
ALTER TABLE `pemicu`
  MODIFY `id_pemicu` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `request_cair`
--
ALTER TABLE `request_cair`
  MODIFY `id_cair` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `request_saldo`
--
ALTER TABLE `request_saldo`
  MODIFY `id_saldo` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `riwayat`
--
ALTER TABLE `riwayat`
  MODIFY `id_riwayat` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_pembeli`
--
ALTER TABLE `user_pembeli`
  MODIFY `id_pembeli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `user_penjual`
--
ALTER TABLE `user_penjual`
  MODIFY `id_penjual` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_penjual`) REFERENCES `user_penjual` (`id_penjual`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pembeli`) REFERENCES `user_pembeli` (`id_pembeli`),
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`id_penjual`) REFERENCES `user_penjual` (`id_penjual`);

--
-- Constraints for table `request_cair`
--
ALTER TABLE `request_cair`
  ADD CONSTRAINT `request_cair_ibfk_1` FOREIGN KEY (`id_penjual`) REFERENCES `user_penjual` (`id_penjual`);

--
-- Constraints for table `request_saldo`
--
ALTER TABLE `request_saldo`
  ADD CONSTRAINT `request_saldo_ibfk_1` FOREIGN KEY (`id_pembeli`) REFERENCES `user_pembeli` (`id_pembeli`);

--
-- Constraints for table `riwayat`
--
ALTER TABLE `riwayat`
  ADD CONSTRAINT `riwayat_ibfk_1` FOREIGN KEY (`id_pembeli`) REFERENCES `user_pembeli` (`id_pembeli`),
  ADD CONSTRAINT `riwayat_ibfk_2` FOREIGN KEY (`id_penjual`) REFERENCES `user_penjual` (`id_penjual`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
