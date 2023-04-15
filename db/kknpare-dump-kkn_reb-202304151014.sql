-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: localhost    Database: kkn_reb
-- ------------------------------------------------------
-- Server version	8.0.32-0ubuntu0.22.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idhakakses` int DEFAULT NULL,
  `iduser` int DEFAULT NULL,
  `idgrup` int DEFAULT NULL,
  `aktivasi` enum('y','n') DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grup_FK` (`owned`) USING BTREE,
  KEY `grup_FK_1` (`iduser_update`) USING BTREE,
  KEY `pembimbing_App` (`iduser`) USING BTREE,
  KEY `admin_FK` (`idgrup`),
  KEY `admin_FK_1` (`idhakakses`),
  CONSTRAINT `admin_FK` FOREIGN KEY (`idgrup`) REFERENCES `grup` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `admin_fk1` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `admin_fk2` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `admin_fk3` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `admin_FK_1` FOREIGN KEY (`idhakakses`) REFERENCES `hakakses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `administrasi`
--

DROP TABLE IF EXISTS `administrasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administrasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idkkn` int DEFAULT NULL,
  `namaadministrasi` varchar(200) DEFAULT NULL,
  `upload_file` enum('y','n') DEFAULT NULL,
  `upload_type` enum('pdf','gambar') DEFAULT NULL,
  `upload_size` int DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  `aktif` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`id`),
  KEY `administrasi_FK` (`idkkn`),
  KEY `administrasi_FK_1` (`owned`),
  KEY `administrasi_FK_2` (`iduser_update`),
  CONSTRAINT `administrasi_FK` FOREIGN KEY (`idkkn`) REFERENCES `kkn` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `administrasi_FK_1` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `administrasi_FK_2` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aksesgrup`
--

DROP TABLE IF EXISTS `aksesgrup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aksesgrup` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idgrup` int NOT NULL,
  `idmodule` int DEFAULT NULL,
  `module` varchar(100) DEFAULT '',
  `c` enum('y','n') DEFAULT 'n',
  `r` enum('n','y') DEFAULT 'y',
  `u` enum('y','n') DEFAULT 'n',
  `d` enum('y','n') DEFAULT 'n',
  `f` enum('n','y') DEFAULT 'n',
  `ket` text,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aksesgrup_UN` (`idgrup`,`idmodule`),
  KEY `fk_idgrup` (`idgrup`) USING BTREE,
  KEY `aksesgrup_FK_2` (`idmodule`),
  KEY `aksesgrup_FK_3` (`owned`),
  KEY `aksesgrup_FK_4` (`iduser_update`),
  CONSTRAINT `aksesgrup_FK_1` FOREIGN KEY (`idgrup`) REFERENCES `grup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `aksesgrup_FK_2` FOREIGN KEY (`idmodule`) REFERENCES `module` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aksesgrup_FK_3` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aksesgrup_FK_4` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aksesuser`
--

DROP TABLE IF EXISTS `aksesuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aksesuser` (
  `id` int NOT NULL AUTO_INCREMENT,
  `iduser` int NOT NULL,
  `idmodule` int DEFAULT NULL,
  `module` varchar(100) DEFAULT NULL,
  `c` enum('y','n') DEFAULT 'n',
  `r` enum('n','y') DEFAULT 'n',
  `u` enum('y','n') DEFAULT 'n',
  `d` enum('y','n') DEFAULT 'n',
  `f` enum('n','y') DEFAULT 'n',
  `ket` text,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aksesuser_FK_1` (`owned`) USING BTREE,
  KEY `fk_idgrup` (`iduser`) USING BTREE,
  KEY `aksesuser_FK` (`idmodule`),
  KEY `aksesuser_FK_2` (`iduser_update`),
  CONSTRAINT `aksesuser_FK` FOREIGN KEY (`idmodule`) REFERENCES `module` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aksesuser_FK_1` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aksesuser_FK_2` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aksesuser_FK_3` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aktifitas`
--

DROP TABLE IF EXISTS `aktifitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aktifitas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idpenempatan` int NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `uraian` text,
  `waktu` datetime DEFAULT NULL,
  `jummhs` smallint DEFAULT '1',
  `jummasyarakat` smallint DEFAULT '0',
  `grup` enum('Non Fisik','Fisik') DEFAULT 'Non Fisik',
  `estbiaya` int DEFAULT '0',
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grup` (`grup`) USING BTREE,
  KEY `idkkn` (`idpenempatan`) USING BTREE,
  KEY `waktu` (`waktu`) USING BTREE,
  KEY `aktifitas_FK_2` (`owned`),
  KEY `aktifitas_FK_3` (`iduser_update`),
  KEY `aktifitas_FK_1` (`latitude`),
  CONSTRAINT `aktifitas_FK` FOREIGN KEY (`idpenempatan`) REFERENCES `penempatan` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_FK_2` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_FK_3` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aktifitas_dpl`
--

DROP TABLE IF EXISTS `aktifitas_dpl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aktifitas_dpl` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idkelompok` int NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `uraian` text,
  `path` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `fileinfo` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `waktu` datetime DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aktifitas_FK_1` (`latitude`) USING BTREE,
  KEY `aktifitas_FK_2` (`owned`) USING BTREE,
  KEY `aktifitas_FK_3` (`iduser_update`) USING BTREE,
  KEY `idkkn` (`idkelompok`) USING BTREE,
  KEY `waktu` (`waktu`) USING BTREE,
  CONSTRAINT `aktifitas_dpl_FK` FOREIGN KEY (`idkelompok`) REFERENCES `kelompok` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_FK_2_copy` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_FK_3_copy` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aktifitas_komentar`
--

DROP TABLE IF EXISTS `aktifitas_komentar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aktifitas_komentar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idaktifitas` int DEFAULT NULL,
  `iduser` int DEFAULT NULL,
  `komentar` text,
  `waktu` datetime DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDAKTIFITAS` (`idaktifitas`) USING BTREE,
  KEY `KOMENTATORID` (`iduser`) USING BTREE,
  KEY `aktifitas_komentar_FK_2` (`owned`),
  KEY `aktifitas_komentar_FK_3` (`iduser_update`),
  CONSTRAINT `aktifitas_komentar_FK` FOREIGN KEY (`idaktifitas`) REFERENCES `aktifitas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_komentar_FK_1` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_komentar_FK_2` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_komentar_FK_3` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aktifitas_like`
--

DROP TABLE IF EXISTS `aktifitas_like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aktifitas_like` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idaktifitas` int DEFAULT NULL,
  `iduser` int DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aktifitas_like_UN` (`idaktifitas`,`iduser`),
  KEY `IDAKTIFITAS` (`idaktifitas`) USING BTREE,
  KEY `KOMENTATORID` (`iduser`) USING BTREE,
  KEY `aktifitas_komentar_FK_2` (`owned`) USING BTREE,
  KEY `aktifitas_komentar_FK_3` (`iduser_update`) USING BTREE,
  CONSTRAINT `aktifitas_komentar_FK_1_copy` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_komentar_FK_2_copy` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_komentar_FK_3_copy` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_komentar_FK_copy` FOREIGN KEY (`idaktifitas`) REFERENCES `aktifitas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `aktifitas_upload`
--

DROP TABLE IF EXISTS `aktifitas_upload`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aktifitas_upload` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idaktifitas` int DEFAULT NULL,
  `path` varchar(250) DEFAULT NULL,
  `fileinfo` text,
  `is_image` tinyint(1) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `aktifitas_upload_UN` (`idaktifitas`,`path`),
  KEY `aktifitas_upload_FK_1` (`owned`),
  KEY `aktifitas_upload_FK_2` (`iduser_update`),
  CONSTRAINT `aktifitas_upload_FK` FOREIGN KEY (`idaktifitas`) REFERENCES `aktifitas` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_upload_FK_1` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_upload_FK_2` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `berita`
--

DROP TABLE IF EXISTS `berita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `berita` (
  `id` int NOT NULL AUTO_INCREMENT,
  `iduser` int DEFAULT NULL,
  `judul` varchar(300) DEFAULT NULL,
  `thumbnail` varchar(300) DEFAULT NULL,
  `fileinfo` text,
  `slug` varchar(100) DEFAULT NULL,
  `detail` text,
  `waktu` datetime DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `berita_UN` (`slug`),
  KEY `berita_FK` (`owned`),
  KEY `berita_FK_1` (`iduser_update`),
  KEY `berita_FK_2` (`iduser`),
  CONSTRAINT `berita_FK` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `berita_FK_1` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `berita_FK_2` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `berkas_administrasi`
--

DROP TABLE IF EXISTS `berkas_administrasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `berkas_administrasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idadministrasi` int DEFAULT NULL,
  `idpendaftar` int DEFAULT NULL,
  `status` enum('MS','TMS') DEFAULT NULL,
  `path` varchar(250) DEFAULT NULL,
  `fileinfo` text,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `verifikasi_UN` (`idadministrasi`,`idpendaftar`),
  KEY `verifikasi_Owned` (`owned`) USING BTREE,
  KEY `verifikasi_Pendaftar` (`idpendaftar`) USING BTREE,
  KEY `verifikasi_UserUpdate` (`iduser_update`) USING BTREE,
  CONSTRAINT `verifikasi_FK_copy` FOREIGN KEY (`idadministrasi`) REFERENCES `administrasi` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `verifikasi_Owned_copy` FOREIGN KEY (`owned`) REFERENCES `user` (`id`),
  CONSTRAINT `verifikasi_Pendaftar_copy` FOREIGN KEY (`idpendaftar`) REFERENCES `pendaftar` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `verifikasi_UserUpdate_copy` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grup`
--

DROP TABLE IF EXISTS `grup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grup` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_grup` varchar(80) DEFAULT '',
  `tableref` varchar(100) DEFAULT NULL,
  `ket` varchar(255) DEFAULT '',
  `self_activated` enum('y','n') DEFAULT 'n',
  `reg` enum('y','n') DEFAULT NULL,
  `aktif` enum('y','n') DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grup_FK` (`owned`) USING BTREE,
  KEY `grup_FK_1` (`iduser_update`),
  CONSTRAINT `grup_FK` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `grup_FK_1` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hakakses`
--

DROP TABLE IF EXISTS `hakakses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hakakses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `iduser` int DEFAULT NULL,
  `idgrup` int DEFAULT '4',
  `token` varchar(300) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `aktivasi` enum('1','0') DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hakakses_UN` (`iduser`,`idgrup`),
  UNIQUE KEY `hakakses_token` (`token`),
  KEY `grup_FK` (`owned`) USING BTREE,
  KEY `grup_FK_1` (`iduser_update`) USING BTREE,
  KEY `pembimbing_App` (`iduser`) USING BTREE,
  KEY `hakakses_FK_1` (`idgrup`),
  CONSTRAINT `hakakses_FK` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `hakakses_FK_1` FOREIGN KEY (`idgrup`) REFERENCES `grup` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1556 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jenis_profil`
--

DROP TABLE IF EXISTS `jenis_profil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jenis_profil` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kelompok`
--

DROP TABLE IF EXISTS `kelompok`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelompok` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idlokasi` int DEFAULT NULL,
  `idpembimbing_kkn` int DEFAULT NULL,
  `namakelompok` varchar(200) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kelompok_UN` (`idpembimbing_kkn`,`idlokasi`),
  KEY `IDKKN` (`idlokasi`) USING BTREE,
  KEY `IDLOKASI` (`idpembimbing_kkn`) USING BTREE,
  KEY `NAMAKELOMPOK` (`idlokasi`,`namakelompok`) USING BTREE,
  KEY `kelompok_FK_1` (`iduser_update`),
  KEY `kelompok_FK_3` (`owned`),
  CONSTRAINT `kelompok_FK` FOREIGN KEY (`idpembimbing_kkn`) REFERENCES `pembimbing_kkn` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `kelompok_FK_1` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `kelompok_FK_2` FOREIGN KEY (`idlokasi`) REFERENCES `lokasi` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `kelompok_FK_3` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kkn`
--

DROP TABLE IF EXISTS `kkn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kkn` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tahun` varchar(4) DEFAULT NULL,
  `semester` enum('1','2') DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `no_sk` varchar(100) DEFAULT NULL,
  `tgl_sk` date DEFAULT NULL,
  `tema` text,
  `angkatan` varchar(20) DEFAULT NULL,
  `jenis` enum('REGULER','PILIHAN') DEFAULT 'REGULER',
  `tempat` varchar(100) DEFAULT NULL,
  `daftarmulai` date DEFAULT NULL,
  `daftarselesai` date DEFAULT NULL,
  `kknmulai` date DEFAULT NULL,
  `kknselesai` date DEFAULT NULL,
  `tamulai` date DEFAULT NULL,
  `taselesai` date DEFAULT NULL,
  `nilaimulai` date DEFAULT NULL,
  `nilaiselesai` date DEFAULT NULL,
  `bagikelompok` date DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `keterangan` text,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  `aktif` enum('Y','N') DEFAULT 'Y',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tb_kkn_UN` (`slug`),
  KEY `IDTHN` (`tahun`) USING BTREE,
  KEY `kkn_FK` (`owned`),
  KEY `kkn_FK_1` (`iduser_update`),
  CONSTRAINT `kkn_FK` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `kkn_FK_1` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login_history`
--

DROP TABLE IF EXISTS `login_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `iduser` int DEFAULT NULL,
  `detail` text,
  `lastlogin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_history_FK` (`iduser`),
  CONSTRAINT `login_history_FK` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lokasi`
--

DROP TABLE IF EXISTS `lokasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lokasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idkkn` int DEFAULT NULL,
  `iddesa` int DEFAULT NULL,
  `pergi` int DEFAULT NULL,
  `pulang` int DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lokasi_UN` (`idkkn`,`iddesa`),
  KEY `IDKKN` (`idkkn`) USING BTREE,
  KEY `lokasi_FK_2` (`owned`),
  KEY `lokasi_FK_3` (`iduser_update`),
  KEY `lokasi_FK_1` (`iddesa`),
  CONSTRAINT `lokasi_FK` FOREIGN KEY (`idkkn`) REFERENCES `kkn` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `lokasi_FK_1` FOREIGN KEY (`iddesa`) REFERENCES `wilayah_desa` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `lokasi_FK_2` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `lokasi_FK_3` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mahasiswa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `iduser` int DEFAULT NULL,
  `idhakakses` int DEFAULT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `idprodi` int DEFAULT NULL,
  `path` varchar(250) DEFAULT NULL,
  `fileinfo` text,
  `idgrup` int DEFAULT '4',
  `aktivasi` enum('y','n') DEFAULT 'y',
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mahasiswa_UN` (`nim`),
  UNIQUE KEY `mahasiswa_User` (`iduser`),
  KEY `grup_FK` (`owned`) USING BTREE,
  KEY `grup_FK_1` (`iduser_update`) USING BTREE,
  KEY `pembimbing_App` (`iduser`) USING BTREE,
  KEY `mahasiswa_FK` (`idprodi`),
  KEY `mahasiswa_FK_1` (`idgrup`),
  KEY `mahasiswa_FK_2` (`idhakakses`),
  CONSTRAINT `mahasiswa_FK` FOREIGN KEY (`idprodi`) REFERENCES `mst_prodi` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_FK_1` FOREIGN KEY (`idgrup`) REFERENCES `grup` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_FK_2` FOREIGN KEY (`idhakakses`) REFERENCES `hakakses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mahasiswa_FK_3` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idparent` int DEFAULT NULL,
  `idgrup` int DEFAULT NULL,
  `idmodule` int DEFAULT NULL,
  `urut` int DEFAULT '0',
  `menu` varchar(50) DEFAULT NULL,
  `link` varchar(50) DEFAULT NULL,
  `icon_list` varchar(100) DEFAULT NULL,
  `module` varchar(100) DEFAULT NULL,
  `icon_right` varchar(100) DEFAULT NULL,
  `show` enum('y','n') DEFAULT 'y',
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_UN` (`idgrup`,`idmodule`),
  KEY `menu_module_IDX` (`module`) USING BTREE,
  KEY `menu_FK` (`owned`),
  KEY `menu_FK_1` (`iduser_update`),
  KEY `menu_FK_3` (`idparent`),
  CONSTRAINT `menu_FK` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `menu_FK_1` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `menu_FK_2` FOREIGN KEY (`idgrup`) REFERENCES `grup` (`id`),
  CONSTRAINT `menu_FK_3` FOREIGN KEY (`idparent`) REFERENCES `menu` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `module` (
  `id` int NOT NULL AUTO_INCREMENT,
  `module` varchar(100) DEFAULT NULL,
  `deskripsi` varchar(300) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_UN` (`module`),
  KEY `menu_FK` (`owned`) USING BTREE,
  KEY `menu_FK_1` (`iduser_update`) USING BTREE,
  KEY `menu_module_IDX` (`module`) USING BTREE,
  CONSTRAINT `menu_FK_1_copy` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `menu_FK_copy` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mst_fakultas`
--

DROP TABLE IF EXISTS `mst_fakultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_fakultas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fakultas` varchar(100) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mst_jabatan_UN` (`fakultas`),
  KEY `mst_jabatan_FK` (`owned`) USING BTREE,
  KEY `mst_jabatan_FK_1` (`iduser_update`) USING BTREE,
  CONSTRAINT `mst_jabatan_FK_1_copy` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mst_jabatan_FK_copy` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mst_jabatan`
--

DROP TABLE IF EXISTS `mst_jabatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_jabatan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jabatan` varchar(100) DEFAULT NULL,
  `urut` smallint DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mst_jabatan_UN` (`jabatan`),
  KEY `mst_jabatan_FK` (`owned`),
  KEY `mst_jabatan_FK_1` (`iduser_update`),
  CONSTRAINT `mst_jabatan_FK` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mst_jabatan_FK_1` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mst_output`
--

DROP TABLE IF EXISTS `mst_output`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_output` (
  `id` int NOT NULL AUTO_INCREMENT,
  `output` varchar(100) DEFAULT NULL,
  `urut` smallint DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mst_jabatan_UN` (`output`),
  KEY `mst_jabatan_FK` (`owned`) USING BTREE,
  KEY `mst_jabatan_FK_1` (`iduser_update`) USING BTREE,
  CONSTRAINT `mst_output_FK` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mst_output_FK_1` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mst_penilaian`
--

DROP TABLE IF EXISTS `mst_penilaian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_penilaian` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis` varchar(100) DEFAULT NULL,
  `urut` int DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `output_FK_1` (`owned`) USING BTREE,
  KEY `output_FK_2` (`iduser_update`) USING BTREE,
  CONSTRAINT `output_FK_1_copy` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `output_FK_2_copy` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mst_prodi`
--

DROP TABLE IF EXISTS `mst_prodi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_prodi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idfakultas` int DEFAULT NULL,
  `prodi` varchar(100) DEFAULT NULL,
  `urut` smallint DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mst_jabatan_UN` (`prodi`),
  KEY `mst_jabatan_FK` (`owned`) USING BTREE,
  KEY `mst_jabatan_FK_1` (`iduser_update`) USING BTREE,
  KEY `mst_prodi_FK` (`idfakultas`),
  CONSTRAINT `mst_jabatan_FK_1
` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mst_jabatan_FK_12` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mst_jabatan_FK_13` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mst_jabatan_FK_1_Copy
` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `mst_prodi_FK` FOREIGN KEY (`idfakultas`) REFERENCES `mst_fakultas` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nilai`
--

DROP TABLE IF EXISTS `nilai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idpenempatan` int DEFAULT NULL,
  `idmst_penilaian` int DEFAULT NULL,
  `nilai_angka` int DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nilai_UN` (`idpenempatan`,`idmst_penilaian`),
  KEY `idkkn` (`idpenempatan`) USING BTREE,
  KEY `nilai_FK_1` (`idmst_penilaian`),
  KEY `nilai_FK_2` (`owned`),
  KEY `nilai_FK_3` (`iduser_update`),
  CONSTRAINT `nilai_FK` FOREIGN KEY (`idpenempatan`) REFERENCES `penempatan` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `nilai_FK_1` FOREIGN KEY (`idmst_penilaian`) REFERENCES `mst_penilaian` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `nilai_FK_2` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `nilai_FK_3` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idaktifitas` int DEFAULT NULL,
  `iduser` int DEFAULT NULL,
  `iduser_asal` int DEFAULT NULL,
  `notif` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifikasi_FK_1` (`iduser`),
  KEY `notifikasi_FK_2` (`iduser_asal`),
  KEY `notifikasi_FK_3` (`idaktifitas`),
  CONSTRAINT `notifikasi_FK_1` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `notifikasi_FK_2` FOREIGN KEY (`iduser_asal`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `notifikasi_FK_3` FOREIGN KEY (`idaktifitas`) REFERENCES `aktifitas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `output`
--

DROP TABLE IF EXISTS `output`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `output` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idoutput` int DEFAULT NULL,
  `idkkn` int DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `output_UN` (`idoutput`,`idkkn`),
  KEY `output_FK` (`idkkn`),
  KEY `output_FK_1` (`owned`),
  KEY `output_FK_2` (`iduser_update`),
  CONSTRAINT `output_FK` FOREIGN KEY (`idkkn`) REFERENCES `kkn` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `output_FK_1` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `output_FK_2` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `output_FK_3` FOREIGN KEY (`idoutput`) REFERENCES `mst_output` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `output_penempatan`
--

DROP TABLE IF EXISTS `output_penempatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `output_penempatan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idoutput` int DEFAULT NULL,
  `idpenempatan` int DEFAULT NULL,
  `jenis` enum('upload','url') DEFAULT 'upload',
  `path` varchar(250) DEFAULT NULL,
  `fileinfo` text,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `output_penempatan_UN` (`idoutput`,`idpenempatan`),
  KEY `output_FK` (`idpenempatan`) USING BTREE,
  KEY `output_FK_1` (`owned`) USING BTREE,
  KEY `output_FK_2` (`iduser_update`) USING BTREE,
  CONSTRAINT `output_penempatan_FK` FOREIGN KEY (`idpenempatan`) REFERENCES `penempatan` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `output_penempatan_FK_1` FOREIGN KEY (`idoutput`) REFERENCES `output` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `output_penempatan_FK_2` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `output_penempatan_FK_3` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pembimbing`
--

DROP TABLE IF EXISTS `pembimbing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembimbing` (
  `id` int NOT NULL AUTO_INCREMENT,
  `iduser` int DEFAULT NULL,
  `idgrup` int DEFAULT NULL,
  `idhakakses` int DEFAULT NULL,
  `statuspeg` enum('PNS','NON PNS') DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `path` varchar(250) DEFAULT NULL,
  `fileinfo` text,
  `aktivasi` enum('y','n') DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grup_FK` (`owned`) USING BTREE,
  KEY `grup_FK_1` (`iduser_update`) USING BTREE,
  KEY `pembimbing_App` (`iduser`),
  KEY `pembimbing_grup` (`idgrup`),
  KEY `pembimbing_FK2` (`idhakakses`),
  CONSTRAINT `grup_FK_1_copy` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `grup_FK_copy` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pembimbing_FK2` FOREIGN KEY (`idhakakses`) REFERENCES `hakakses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pembimbing_grup` FOREIGN KEY (`idgrup`) REFERENCES `grup` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pembimbing_kkn`
--

DROP TABLE IF EXISTS `pembimbing_kkn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembimbing_kkn` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idsk_pembimbing` int DEFAULT NULL,
  `idpembimbing` int DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pembimbing_kkn_UN` (`idsk_pembimbing`,`idpembimbing`),
  KEY `IDLOKASI` (`idpembimbing`) USING BTREE,
  KEY `kelompok_FK_1` (`iduser_update`) USING BTREE,
  KEY `pembimbing_kkn_FK_1` (`owned`),
  CONSTRAINT `kelompok_FK_1_copy` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pembimbing_FK` FOREIGN KEY (`idsk_pembimbing`) REFERENCES `sk_pembimbing` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pembimbing_kkn_FK` FOREIGN KEY (`idpembimbing`) REFERENCES `pembimbing` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pembimbing_kkn_FK_1` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pendaftar`
--

DROP TABLE IF EXISTS `pendaftar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pendaftar` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idkkn` int DEFAULT NULL,
  `idmahasiswa` int DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pendaftar_UN` (`idkkn`,`idmahasiswa`),
  KEY `pendaftar_FK_2` (`owned`),
  KEY `pendaftar_FK_3` (`iduser_update`),
  KEY `pendaftar_FK_1` (`idmahasiswa`),
  CONSTRAINT `pendaftar_FK` FOREIGN KEY (`idkkn`) REFERENCES `kkn` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pendaftar_FK_1` FOREIGN KEY (`idmahasiswa`) REFERENCES `mahasiswa` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pendaftar_FK_2` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `pendaftar_FK_3` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `penempatan`
--

DROP TABLE IF EXISTS `penempatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penempatan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idkelompok` int DEFAULT NULL,
  `idpeserta` int DEFAULT NULL,
  `idjabatan` int DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `penempatan_UN` (`idpeserta`),
  KEY `IDDATA` (`idpeserta`) USING BTREE,
  KEY `idkkn` (`idkelompok`) USING BTREE,
  KEY `penempatan_FK_1` (`owned`),
  KEY `penempatan_FK_2` (`iduser_update`),
  KEY `penempatan_FK_4` (`idjabatan`),
  CONSTRAINT `penempatan_FK` FOREIGN KEY (`idkelompok`) REFERENCES `kelompok` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `penempatan_FK_1` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `penempatan_FK_2` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `penempatan_FK_3` FOREIGN KEY (`idpeserta`) REFERENCES `peserta` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `penempatan_FK_4` FOREIGN KEY (`idjabatan`) REFERENCES `mst_jabatan` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `penilaian`
--

DROP TABLE IF EXISTS `penilaian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penilaian` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idmst_penilaian` int DEFAULT NULL,
  `idkkn` int DEFAULT NULL,
  `persen` int DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `output_FK` (`idkkn`) USING BTREE,
  KEY `output_FK_1` (`owned`) USING BTREE,
  KEY `output_FK_2` (`iduser_update`) USING BTREE,
  CONSTRAINT `output_FK_1_copy_copy` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `output_FK_2_copy_copy` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `output_FK_copy_copy` FOREIGN KEY (`idkkn`) REFERENCES `kkn` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `peserta`
--

DROP TABLE IF EXISTS `peserta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peserta` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idpendaftar` int DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `verifikasi_UN` (`idpendaftar`),
  KEY `verifikasi_FK` (`owned`),
  KEY `verifikasi_FK_1` (`iduser_update`),
  CONSTRAINT `verifikasi_FK` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `verifikasi_FK_1` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `verifikasi_FK_2` FOREIGN KEY (`idpendaftar`) REFERENCES `pendaftar` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `posko`
--

DROP TABLE IF EXISTS `posko`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posko` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idkelompok` int DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `path` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `fileinfo` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `proker` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posko_UN` (`idkelompok`),
  KEY `posko_FK_1` (`owned`),
  KEY `posko_FK_2` (`iduser_update`),
  CONSTRAINT `posko_FK` FOREIGN KEY (`idkelompok`) REFERENCES `kelompok` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `posko_FK_1` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `posko_FK_2` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `profil`
--

DROP TABLE IF EXISTS `profil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profil` (
  `id` int NOT NULL AUTO_INCREMENT,
  `detail` text,
  `idjenis_profil` int DEFAULT NULL,
  `thumbnail` varchar(300) DEFAULT NULL,
  `fileinfo` text,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profil_UN` (`idjenis_profil`),
  KEY `output_FK` (`idjenis_profil`) USING BTREE,
  KEY `output_FK_1` (`owned`) USING BTREE,
  KEY `output_FK_2` (`iduser_update`) USING BTREE,
  CONSTRAINT `profil_FK` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `profil_FK2` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `profil_FK_1` FOREIGN KEY (`idjenis_profil`) REFERENCES `jenis_profil` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reset_password`
--

DROP TABLE IF EXISTS `reset_password`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reset_password` (
  `id` int NOT NULL AUTO_INCREMENT,
  `iduser` int DEFAULT NULL,
  `token` varchar(250) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `expired` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `aktif` enum('y','n') DEFAULT 'n',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reset_password_UN` (`token`),
  KEY `reset_password_FK` (`iduser`),
  CONSTRAINT `reset_password_FK` FOREIGN KEY (`iduser`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  `iduser` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`) USING BTREE,
  KEY `iduser` (`iduser`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sk_pembimbing`
--

DROP TABLE IF EXISTS `sk_pembimbing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sk_pembimbing` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idkkn` int DEFAULT NULL,
  `sk_no` varchar(100) DEFAULT NULL,
  `sk_tgl` varchar(100) DEFAULT NULL,
  `path` varchar(250) DEFAULT NULL,
  `fileinfo` text,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sk_pembimbing_UN` (`idkkn`),
  KEY `sk_pembimbing_FK_1` (`owned`),
  KEY `sk_pembimbing_FK_2` (`iduser_update`),
  CONSTRAINT `sk_pembimbing_FK` FOREIGN KEY (`idkkn`) REFERENCES `kkn` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `sk_pembimbing_FK_1` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `sk_pembimbing_FK_2` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `testimoni`
--

DROP TABLE IF EXISTS `testimoni`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimoni` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idpenempatan` int DEFAULT NULL,
  `link` varchar(250) DEFAULT NULL,
  `keterangan` varchar(200) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idkkn` (`idpenempatan`) USING BTREE,
  KEY `nilai_FK_2` (`owned`) USING BTREE,
  KEY `nilai_FK_3` (`iduser_update`) USING BTREE,
  CONSTRAINT `nilai_FK_2_copy` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `nilai_FK_3_copy` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `nilai_FK_copy` FOREIGN KEY (`idpenempatan`) REFERENCES `penempatan` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `upload`
--

DROP TABLE IF EXISTS `upload`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `upload` (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) DEFAULT NULL,
  `path` varchar(250) DEFAULT NULL,
  `fileinfo` text,
  `is_image` tinyint(1) DEFAULT NULL,
  `waktu` datetime DEFAULT NULL,
  `keterangan` varchar(300) DEFAULT NULL,
  `publish` enum('0','1') DEFAULT '1',
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aktifitas_upload_FK_1` (`owned`) USING BTREE,
  KEY `aktifitas_upload_FK_2` (`iduser_update`) USING BTREE,
  CONSTRAINT `aktifitas_upload_FK_1_copy` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `aktifitas_upload_FK_2_copy` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL DEFAULT '',
  `fldpass` varchar(100) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `glrdepan` varchar(30) DEFAULT '',
  `glrbelakang` varchar(30) DEFAULT '',
  `nik` varchar(16) DEFAULT NULL,
  `kel` enum('L','P') DEFAULT 'L',
  `tmplahir` varchar(50) DEFAULT NULL,
  `tgllahir` date DEFAULT NULL,
  `alamat` varchar(150) DEFAULT NULL,
  `hp` varchar(14) DEFAULT NULL,
  `institusi` varchar(200) DEFAULT NULL,
  `token` varchar(200) DEFAULT NULL,
  `path` varchar(300) DEFAULT 'assets/img/user-avatar.png',
  `fileinfo` text,
  `idprovinsi` int DEFAULT NULL,
  `idkabupaten` int DEFAULT NULL,
  `idkecamatan` int DEFAULT NULL,
  `iddesa` int DEFAULT NULL,
  `aktivasi` enum('y','n') DEFAULT 'y',
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `user_token` (`token`),
  KEY `user_FK_1` (`iddesa`),
  KEY `user_provinsi` (`idprovinsi`),
  KEY `user_kabupaten` (`idkabupaten`),
  KEY `user_kecamatan` (`idkecamatan`),
  CONSTRAINT `user_FK_1` FOREIGN KEY (`iddesa`) REFERENCES `wilayah_desa` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `user_kabupaten` FOREIGN KEY (`idkabupaten`) REFERENCES `wilayah_kab` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `user_kecamatan` FOREIGN KEY (`idkecamatan`) REFERENCES `wilayah_kec` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `user_provinsi` FOREIGN KEY (`idprovinsi`) REFERENCES `wilayah_prov` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2140 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wilayah_desa`
--

DROP TABLE IF EXISTS `wilayah_desa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wilayah_desa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idkecamatan` int DEFAULT NULL,
  `kodewilayah_prov` varchar(3) DEFAULT NULL,
  `kodewilayah_kec` varchar(3) DEFAULT NULL,
  `kodewilayah_kab` varchar(3) DEFAULT NULL,
  `kode` varchar(20) DEFAULT NULL,
  `desa` varchar(100) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wilayah_desa_UN` (`kodewilayah_prov`,`kodewilayah_kec`,`kodewilayah_kab`,`kode`),
  KEY `wilayah_desa_FK` (`kodewilayah_prov`,`kodewilayah_kab`,`kodewilayah_kec`),
  KEY `wilayah_desa_FK_1` (`idkecamatan`),
  KEY `wilayah_desa_FK_2` (`owned`),
  KEY `wilayah_desa_FK_3` (`iduser_update`),
  CONSTRAINT `wilayah_desa_FK` FOREIGN KEY (`kodewilayah_prov`, `kodewilayah_kab`, `kodewilayah_kec`) REFERENCES `wilayah_kec` (`kodewilayah_prov`, `kodewilayah_kab`, `kode`) ON UPDATE CASCADE,
  CONSTRAINT `wilayah_desa_FK_1` FOREIGN KEY (`idkecamatan`) REFERENCES `wilayah_kec` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `wilayah_desa_FK_2` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `wilayah_desa_FK_3` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83472 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wilayah_kab`
--

DROP TABLE IF EXISTS `wilayah_kab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wilayah_kab` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idprovinsi` int DEFAULT NULL,
  `kodewilayah_prov` varchar(3) DEFAULT NULL,
  `kode` varchar(2) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `idwilayah_jenis` int DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewTable_UN` (`kodewilayah_prov`,`kode`),
  KEY `wilayah_kab_FK_1` (`idprovinsi`),
  KEY `wilayah_kab_FK_2` (`owned`),
  KEY `wilayah_kab_FK_3` (`iduser_update`),
  CONSTRAINT `wilayah_kab_FK` FOREIGN KEY (`kodewilayah_prov`) REFERENCES `wilayah_prov` (`kode`) ON UPDATE CASCADE,
  CONSTRAINT `wilayah_kab_FK_1` FOREIGN KEY (`idprovinsi`) REFERENCES `wilayah_prov` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `wilayah_kab_FK_2` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `wilayah_kab_FK_3` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=524 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wilayah_kec`
--

DROP TABLE IF EXISTS `wilayah_kec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wilayah_kec` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idkabupaten` int DEFAULT NULL,
  `kodewilayah_prov` varchar(3) DEFAULT NULL,
  `kodewilayah_kab` varchar(3) DEFAULT NULL,
  `kode` varchar(3) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wilayah_kec_UN` (`kodewilayah_prov`,`kodewilayah_kab`,`kode`),
  KEY `wilayah_kec_FK_1` (`idkabupaten`),
  KEY `wilayah_kec_FK_2` (`owned`),
  KEY `wilayah_kec_FK_3` (`iduser_update`),
  CONSTRAINT `wilayah_kec_FK` FOREIGN KEY (`kodewilayah_prov`, `kodewilayah_kab`) REFERENCES `wilayah_kab` (`kodewilayah_prov`, `kode`) ON UPDATE CASCADE,
  CONSTRAINT `wilayah_kec_FK_1` FOREIGN KEY (`idkabupaten`) REFERENCES `wilayah_kab` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `wilayah_kec_FK_2` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `wilayah_kec_FK_3` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7267 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wilayah_prov`
--

DROP TABLE IF EXISTS `wilayah_prov`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wilayah_prov` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode` varchar(3) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `idwilayah_jenis` int DEFAULT NULL,
  `owned` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wilayah_prov_UN` (`kode`),
  KEY `wilayah_prov_FK` (`owned`),
  KEY `wilayah_prov_FK_1` (`iduser_update`),
  CONSTRAINT `wilayah_prov_FK` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `wilayah_prov_FK_1` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'kkn_reb'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-15 10:14:26
