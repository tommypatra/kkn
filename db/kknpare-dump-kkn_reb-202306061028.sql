-- evaluasi definition

CREATE TABLE `evaluasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idkkn` int(11) DEFAULT NULL,
  `judul` varchar(200) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `tujuan` enum('DPL','PESERTA','EKSTERNAL','MASYARAKAT','PANITIA','UMUM','PEMDA') DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `owned` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int(11) DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluasi_FK` (`idkkn`),
  KEY `evaluasi_FK_1` (`owned`),
  KEY `evaluasi_FK_2` (`iduser_update`),
  CONSTRAINT `evaluasi_FK` FOREIGN KEY (`idkkn`) REFERENCES `kkn` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `evaluasi_FK_1` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evaluasi_FK_2` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- testimoni definition

CREATE TABLE `testimoni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idkelompok` int(11) DEFAULT NULL,
  `judul` varchar(250) DEFAULT NULL,
  `link` varchar(250) DEFAULT NULL,
  `thumbnail` varchar(250) DEFAULT NULL,
  `owned` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `iduser_update` int(11) DEFAULT NULL,
  `update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `testimoni_UN` (`link`),
  KEY `idkkn` (`idkelompok`) USING BTREE,
  KEY `nilai_FK_2` (`owned`) USING BTREE,
  KEY `nilai_FK_3` (`iduser_update`) USING BTREE,
  CONSTRAINT `nilai_FK_2_copy` FOREIGN KEY (`owned`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `nilai_FK_3_copy` FOREIGN KEY (`iduser_update`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `testimoni_FK` FOREIGN KEY (`idkelompok`) REFERENCES `kelompok` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;