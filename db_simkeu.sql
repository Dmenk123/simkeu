/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100129
 Source Host           : localhost:3306
 Source Schema         : db_simkeu

 Target Server Type    : MySQL
 Target Server Version : 100129
 File Encoding         : 65001

 Date: 14/10/2019 16:38:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tbl_hak_akses
-- ----------------------------
DROP TABLE IF EXISTS `tbl_hak_akses`;
CREATE TABLE `tbl_hak_akses`  (
  `id_menu` int(11) NOT NULL,
  `id_level_user` int(11) NOT NULL,
  `add_button` int(1) NULL DEFAULT NULL,
  `edit_button` int(1) NULL DEFAULT NULL,
  `delete_button` int(1) NULL DEFAULT NULL,
  INDEX `f_level_user`(`id_level_user`) USING BTREE,
  INDEX `id_menu`(`id_menu`) USING BTREE,
  CONSTRAINT `f_level_user` FOREIGN KEY (`id_level_user`) REFERENCES `tbl_level_user` (`id_level_user`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tbl_hak_akses_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `tbl_menu` (`id_menu`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_hak_akses
-- ----------------------------
INSERT INTO `tbl_hak_akses` VALUES (1, 3, 0, 0, 0);
INSERT INTO `tbl_hak_akses` VALUES (1, 4, 0, 0, 0);
INSERT INTO `tbl_hak_akses` VALUES (1, 1, 0, 0, 0);
INSERT INTO `tbl_hak_akses` VALUES (100, 1, 0, 0, 0);
INSERT INTO `tbl_hak_akses` VALUES (101, 1, 1, 1, 1);
INSERT INTO `tbl_hak_akses` VALUES (102, 1, 1, 1, 1);
INSERT INTO `tbl_hak_akses` VALUES (99, 1, 0, 0, 0);
INSERT INTO `tbl_hak_akses` VALUES (98, 1, 1, 1, 1);
INSERT INTO `tbl_hak_akses` VALUES (97, 1, 1, 1, 1);

-- ----------------------------
-- Table structure for tbl_level_user
-- ----------------------------
DROP TABLE IF EXISTS `tbl_level_user`;
CREATE TABLE `tbl_level_user`  (
  `id_level_user` int(11) NOT NULL AUTO_INCREMENT,
  `nama_level_user` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `keterangan_level_user` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
  `aktif` int(1) NULL DEFAULT 1,
  PRIMARY KEY (`id_level_user`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_level_user
-- ----------------------------
INSERT INTO `tbl_level_user` VALUES (1, 'administrator', 'Untuk Administor (Super User)', 1);
INSERT INTO `tbl_level_user` VALUES (2, 'tu', 'Untuk Tata Usaha', 1);
INSERT INTO `tbl_level_user` VALUES (3, 'keuangan', 'Untuk Keuangan', 1);
INSERT INTO `tbl_level_user` VALUES (4, 'kepsek', 'Untuk Kepala Sekolah', 1);
INSERT INTO `tbl_level_user` VALUES (5, 'guru', 'Untuk Guru', 1);

-- ----------------------------
-- Table structure for tbl_master_kode_akun
-- ----------------------------
DROP TABLE IF EXISTS `tbl_master_kode_akun`;
CREATE TABLE `tbl_master_kode_akun`  (
  `nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tipe` int(1) NULL DEFAULT NULL,
  `kode` int(3) NULL DEFAULT NULL,
  `sub_1` int(3) NULL DEFAULT NULL,
  `sub_2` int(3) NULL DEFAULT NULL,
  `kode_in_text` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  INDEX `tipe`(`tipe`) USING BTREE,
  INDEX `kode`(`kode`) USING BTREE,
  INDEX `sub_1`(`sub_1`) USING BTREE,
  INDEX `sub_2`(`sub_2`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_master_kode_akun
-- ----------------------------
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengembangan kompetensi sekolah', 1, 1, NULL, NULL, '1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan Kompetensi Ketuntasan Minimal', 1, 1, 1, NULL, '1.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan kreteria kenaikan kelas', 1, 1, 2, NULL, '1.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pelaksanaan Uji Coba UASBN/UN TK.LP MA\'ARIF', 1, 1, 3, NULL, '1.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pelaksanaan Uji Coba UASBN/UN TK.Kota', 1, 1, 4, NULL, '1.4');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengembangan standar isi', 1, 2, NULL, NULL, '2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan pembagian tugas guru dan jadwal pelajaran', 1, 2, 1, NULL, '2.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan program tahunan', 1, 2, 2, NULL, '2.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan Program Semester', 1, 2, 3, NULL, '2.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan silabus', 1, 2, 4, NULL, '2.4');
INSERT INTO `tbl_master_kode_akun` VALUES ('Kegiatan MGMP dan diskusi jum\'at LP Ma\'arif', 1, 2, 5, NULL, '2.5');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan RPP', 1, 2, 6, NULL, '2.6');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengembangan standar Proses', 1, 3, NULL, NULL, '3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Kegiatan Pengelolaan Kegiatan Belajar Mengajar', 1, 3, 1, NULL, '3.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengadaan sarana penunjang KBM(ATK KBM)', 1, 3, 1, 1, '3.1.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengembangan Alat Pembelajaran(seluruh mapel termasuk OR)', 1, 3, 1, 2, '3.1.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Program Kesiswaan', 1, 3, 2, NULL, '3.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pelaksanaan program perlombaan 17 Agustus', 1, 3, 2, 1, '3.2.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pelaksanaan Pendaftaran Peserta Didik Baru(PPDB)', 1, 3, 2, 2, '3.2.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pelaksanaan kegiatan los', 1, 3, 2, 3, '3.2.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pelaksanaan pertandingan futsal', 1, 3, 2, 4, '3.2.4');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pelaksanaan Penyembelihan hewan qurban', 1, 3, 2, 5, '3.2.5');
INSERT INTO `tbl_master_kode_akun` VALUES ('Program Ekstrakulikuler', 1, 3, 3, NULL, '3.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan program kesiswaan', 1, 3, 3, 1, '3.3.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('pelaksanaan ekstrakulikuler kepramukaan', 1, 3, 3, 2, '3.3.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengembangan Pendidik dan Tenaga Kependidikan', 1, 4, NULL, NULL, '4');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pembinaan Guru di Gugus', 1, 4, 1, NULL, '4.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Peningkatan kualitas guru kelas,mata pelajaran', 1, 4, 1, 1, '4.1.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Peningkatan Kompetensi Kepala Sekolah', 1, 4, 1, 2, '4.1.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pembinaan Tenaga Kependidikan :', 1, 4, 2, NULL, '4.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pembinaan Tenaga Ketatausahaan', 1, 4, 2, 1, '4.2.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pembinaan Kepsek,WK.Kesiswaan,BK,Osis', 1, 4, 2, 2, '4.2.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengembangan sarana dan prasarana sekolah', 1, 5, NULL, NULL, '5');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengadaan,pemeliharaan dan perawatan alat kantor/inventaris kantor', 1, 5, 1, NULL, '5.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('service printer', 1, 5, 1, 1, '5.1.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pembelian bola sepak', 1, 5, 1, 2, '5.1.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('batrei spiker,corong toa,ampli targa', 1, 5, 1, 3, '5.1.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('eccosp panasonik,print raport,modem,bor ,mata bor', 1, 5, 1, 4, '5.1.4');
INSERT INTO `tbl_master_kode_akun` VALUES ('kipas angin dan hexos,alat kebersihan', 1, 5, 1, 5, '5.1.5');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pemeliharaan dan perbaikan gudang :', 1, 5, 2, NULL, '5.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengelasan pagar besi', 1, 5, 2, 1, '5.2.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pemasangan Keramik ruang kelas', 1, 5, 2, 2, '5.2.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pembuatan Papan tulis whaiteboard', 1, 5, 2, 3, '5.2.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengadaan dan perawatan Meubelair :', 1, 5, 3, NULL, '5.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Meja kursi murid', 1, 5, 3, 1, '5.3.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Meja kursi guru', 1, 5, 3, 2, '5.3.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengembangan standar pengelolaan', 1, 6, NULL, NULL, '6');
INSERT INTO `tbl_master_kode_akun` VALUES ('Kegiatan pengembangan manajemen sekolah', 1, 6, 1, NULL, '6.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan Visi dan Misi', 1, 6, 1, 1, '6.1.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan Profil Sekolah', 1, 6, 1, 2, '6.1.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Kegiatan pengelolaan perkantoran', 1, 6, 2, NULL, '6.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan program ketatausahaan', 1, 6, 2, 1, '6.2.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengadaan sarana pendukung perkantoran', 1, 6, 2, 2, '6.2.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Kegiatan supervisi,Monitoring dan Evaluasi', 1, 6, 3, NULL, '6.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan Program supervisi,Monitoring dan Evaluasi', 1, 6, 3, 1, '6.3.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('supervisi akademik', 1, 6, 3, 2, '6.3.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Kegiatan Hubungan Masyarakat', 1, 6, 4, NULL, '6,4');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengembangan sistem informasi manajemen', 1, 6, 4, 1, '6.4.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan Leafleat', 1, 6, 4, 2, '6.4.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengembangan standar pembiayaan', 1, 7, NULL, NULL, '7');
INSERT INTO `tbl_master_kode_akun` VALUES ('Kegiatan rumah tangga sekolah,daya dan jasa', 1, 7, 1, NULL, '7.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Konsumsi Rapat Dinas', 1, 7, 1, 1, '7.1.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Konsumsi 1 Muharam', 1, 7, 1, 2, '7.1.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Konsumsi Jalan sehat 10 November', 1, 7, 1, 3, '7.1.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Biaya Transportasi kegiatan guru', 1, 7, 1, 4, '7.1.4');
INSERT INTO `tbl_master_kode_akun` VALUES ('Biaya Transportasi kegiatan siswa', 1, 7, 1, 5, '7.1.5');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pengembangan dan implementasi sistem penilaian', 1, 8, NULL, NULL, '8');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan kisi-kisi :', 1, 8, 1, NULL, '8.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Ulangan harian', 1, 8, 1, 1, '8.1.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Ulangan Tengah semester', 1, 8, 1, 2, '8.1.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Ulangan Akhir semester', 1, 8, 1, 3, '8.1.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Ujian praktek Agama', 1, 8, 1, 4, '8.1.4');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penyusunan soal', 1, 8, 2, NULL, '8.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Ulangan harian', 1, 8, 2, 1, '8.2.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Ulangan Tengah semester', 1, 8, 2, 2, '8.2.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Ulangan Akhir semester', 1, 8, 2, 3, '8.2.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pelaksanaan Penilaian', 1, 8, 3, NULL, '8.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Ulangan harian', 1, 8, 3, 1, '8.3.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Ulangan Tengah semester', 1, 8, 3, 2, '8.3.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Ulangan Akhir semester', 1, 8, 3, 3, '8.3.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Ulangan kenaikan kelas', 1, 8, 3, 4, '8.3.4');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pelaksanaan ujian praktek', 1, 8, 4, NULL, '8.4');
INSERT INTO `tbl_master_kode_akun` VALUES ('tindak lanjut hasil penilaian', 1, 8, 5, NULL, '8.5');
INSERT INTO `tbl_master_kode_akun` VALUES ('analisis', 1, 8, 5, 1, '8.5.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('remedial', 1, 8, 5, 2, '8.5.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('pengayaan', 1, 8, 5, 3, '8.5.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penilaian lainnya', 1, 8, 6, NULL, '8.6');
INSERT INTO `tbl_master_kode_akun` VALUES ('Portofolio', 1, 8, 6, 1, '8.6.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Proyek', 1, 8, 6, 2, '8.6.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penugasan', 1, 8, 6, 3, '8.6.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Inovasi model penilaian', 1, 8, 7, NULL, '8.7');
INSERT INTO `tbl_master_kode_akun` VALUES ('workshop', 1, 8, 7, 1, '8.7.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('IHT', 1, 8, 7, 2, '8.7.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Pelatihan', 1, 8, 7, 3, '8.7.3');
INSERT INTO `tbl_master_kode_akun` VALUES ('Study banding', 1, 8, 7, 4, '8.7.4');
INSERT INTO `tbl_master_kode_akun` VALUES ('Penggunaan Dana Lainnya', 2, 2, NULL, NULL, '2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Belanja Alat tulis kantor', 2, 2, 1, NULL, '2.1');
INSERT INTO `tbl_master_kode_akun` VALUES ('Belanja Bahan dan alat habis pakai', 2, 2, 2, NULL, '2.2');
INSERT INTO `tbl_master_kode_akun` VALUES ('Belanja Pegawai', 2, 2, 3, NULL, '2.3');

-- ----------------------------
-- Table structure for tbl_master_kode_akun_internal
-- ----------------------------
DROP TABLE IF EXISTS `tbl_master_kode_akun_internal`;
CREATE TABLE `tbl_master_kode_akun_internal`  (
  `nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `kode` int(3) NULL DEFAULT NULL,
  `sub_1` int(3) NULL DEFAULT NULL,
  `sub_2` int(3) NULL DEFAULT NULL,
  `tipe_bos` int(3) NULL DEFAULT NULL,
  `kode_bos` int(3) NULL DEFAULT NULL,
  `kode_bos_sub1` int(3) NULL DEFAULT NULL,
  `kode_bos_sub2` int(3) NULL DEFAULT NULL,
  `kode_in_text` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  INDEX `kode_bos`(`kode_bos`) USING BTREE,
  INDEX `kode_bos_sub1`(`kode_bos_sub1`) USING BTREE,
  INDEX `kode_bos_sub2`(`kode_bos_sub2`) USING BTREE,
  INDEX `tbl_master_kode_akun_internal_ibfk_1`(`tipe_bos`) USING BTREE,
  CONSTRAINT `tbl_master_kode_akun_internal_ibfk_1` FOREIGN KEY (`tipe_bos`) REFERENCES `tbl_master_kode_akun` (`tipe`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tbl_master_kode_akun_internal_ibfk_2` FOREIGN KEY (`kode_bos`) REFERENCES `tbl_master_kode_akun` (`kode`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tbl_master_kode_akun_internal_ibfk_3` FOREIGN KEY (`kode_bos_sub1`) REFERENCES `tbl_master_kode_akun` (`sub_1`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tbl_master_kode_akun_internal_ibfk_4` FOREIGN KEY (`kode_bos_sub2`) REFERENCES `tbl_master_kode_akun` (`sub_2`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_master_kode_akun_internal
-- ----------------------------
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Alat Tulis Sekolah', 1, NULL, NULL, 2, 2, 1, NULL, '1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('spidol boardmarker', 1, 1, NULL, NULL, NULL, NULL, NULL, '1.1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('bolpoin', 1, 2, NULL, NULL, NULL, NULL, NULL, '1.2');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Penghapus Whaite board', 1, 3, NULL, NULL, NULL, NULL, NULL, '1.3');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('FD Kingston 16 GB', 1, 4, NULL, NULL, NULL, NULL, NULL, '1.4');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('FD Kingston 8 GB', 1, 5, NULL, NULL, NULL, NULL, NULL, '1.5');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Mouse', 1, 6, NULL, NULL, NULL, NULL, NULL, '1.6');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Pensil', 1, 7, NULL, NULL, NULL, NULL, NULL, '1.7');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Penghapus pensil', 1, 8, NULL, NULL, NULL, NULL, NULL, '1.8');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Stipo', 1, 9, NULL, NULL, NULL, NULL, NULL, '1.9');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('CD-R', 1, 10, NULL, NULL, NULL, NULL, NULL, '1.10');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('CD-RW', 1, 11, NULL, NULL, NULL, NULL, NULL, '1.11');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Keplek', 1, 12, NULL, NULL, NULL, NULL, NULL, '1.12');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Biaya Bahan dan Alat Habis Pakai', 2, NULL, NULL, 2, 2, 2, NULL, '2');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Kertas HVS', 2, 1, NULL, NULL, NULL, NULL, NULL, '2.1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('foto copy', 2, 2, NULL, NULL, NULL, NULL, NULL, '2.2');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('kertas cd', 2, 3, NULL, NULL, NULL, NULL, NULL, '2.3');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('map snel', 2, 4, NULL, NULL, NULL, NULL, NULL, '2.4');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Minum', 2, 5, NULL, NULL, NULL, NULL, NULL, '2.5');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('matrei 6000', 2, 6, NULL, NULL, NULL, NULL, NULL, '2.6');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Matrei 3000', 2, 7, NULL, NULL, NULL, NULL, NULL, '2.6');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('tinta print hitam', 2, 8, NULL, NULL, NULL, NULL, NULL, '2.7');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('tinta print warna', 2, 9, NULL, NULL, NULL, NULL, NULL, '2.7');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('service komputer dan printer', 2, 10, NULL, NULL, NULL, NULL, NULL, '2.8');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('kertas kop', 2, 11, NULL, NULL, NULL, NULL, NULL, '2.9');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('amplop kop', 2, 12, NULL, NULL, NULL, NULL, NULL, '2.10');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Isi spidol boardmarker', 2, 13, NULL, NULL, NULL, NULL, NULL, '2.11');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Isi Steples kecil', 2, 14, NULL, NULL, NULL, NULL, NULL, '2.12');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('isolasi besar putih', 2, 15, NULL, NULL, NULL, NULL, NULL, '2.13');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Isolasi besar hitam', 2, 16, NULL, NULL, NULL, NULL, NULL, '2.14');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Kapur tulis', 2, 17, NULL, NULL, NULL, NULL, NULL, '2.15');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Service Kipas angin', 2, 18, NULL, NULL, NULL, NULL, NULL, '2.16');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Pemeliharaan dan perbaikan ringan', 3, NULL, NULL, 1, 5, NULL, NULL, '3');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Perbaikan Kelas', 3, 1, NULL, NULL, NULL, NULL, NULL, '3.1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Pengecatan Ruang kelas', 3, 2, NULL, NULL, NULL, NULL, NULL, '3.2');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Pengecatan Ruang kantor', 3, 3, NULL, NULL, NULL, NULL, NULL, '3.4');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Perbaikan meja dan kursi', 3, 4, NULL, NULL, NULL, NULL, NULL, '3.5');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Biaya Transport', 4, NULL, NULL, 1, 7, NULL, NULL, '4');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Perjalanan Dinas Luar kota', 4, 1, NULL, NULL, NULL, NULL, NULL, '4.1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Perjalanan Dalam Kota', 4, 2, NULL, NULL, NULL, NULL, NULL, '4.2');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Biaya Konsumsi', 5, NULL, NULL, 1, 7, NULL, NULL, '5');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Konsumsi Rapat UTS dan UAS ', 5, 1, NULL, NULL, NULL, NULL, NULL, '5.1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Konsumsi Kegiatan Keagamaan', 5, 2, NULL, NULL, NULL, NULL, NULL, '5.2');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Konsumsi Ujian nasional', 5, 3, NULL, NULL, NULL, NULL, NULL, '5.3');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Konsumsi Kegiatan LDKS dan KTS', 5, 4, NULL, NULL, NULL, NULL, NULL, '5.4');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Biaya Pembinaan siswa/Ekstrakulikuler', 6, NULL, NULL, 1, 3, NULL, NULL, '6');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('LDKS', 6, 1, NULL, NULL, NULL, NULL, NULL, '6.1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('PMB', 6, 2, NULL, NULL, NULL, NULL, NULL, '6.2');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('MOS', 6, 3, NULL, NULL, NULL, NULL, NULL, '6.3');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Kegiatan Keagamaan', 6, 4, NULL, NULL, NULL, NULL, NULL, '6.4');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('KTS', 6, 5, NULL, NULL, NULL, NULL, NULL, '6.5');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Biaya uji kompetensi', 7, NULL, NULL, 1, 1, NULL, NULL, '7');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Biaya Praktek kerja industri', 8, NULL, NULL, 1, 6, NULL, NULL, '8');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Biaya Pelaporan', 9, NULL, NULL, 1, 2, NULL, NULL, '9');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Pelaporan Proposal BOS ', 9, 1, NULL, NULL, NULL, NULL, NULL, '9.1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Pelaporan SPJ Bopda', 9, 2, NULL, NULL, NULL, NULL, NULL, '9.2');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Peningkatan Mutu Pendidik dan Tenaga Kependidikan', 10, NULL, NULL, 1, 4, NULL, NULL, '10');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Penyusunan RPP', 10, 1, NULL, NULL, NULL, NULL, NULL, '10.1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('MGMP Ma\'arif', 10, 2, NULL, NULL, NULL, NULL, NULL, '10.2');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Iuran MGMP MKKS', 10, 3, NULL, NULL, NULL, NULL, NULL, '10.3');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Biaya Transportasi MGMP MKKS', 10, 4, NULL, NULL, NULL, NULL, NULL, '10.4');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Work shop', 10, 5, NULL, NULL, NULL, NULL, NULL, '10.5');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Rapat kerja kepala sekolah', 10, 6, NULL, NULL, NULL, NULL, NULL, '10.6');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Pengembangan Kurikulum', 11, NULL, NULL, 1, 8, NULL, NULL, '11');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('UTS Semester I dan II', 11, 1, NULL, NULL, NULL, NULL, NULL, '11.1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('UAS Semester I dan II', 11, 2, NULL, NULL, NULL, NULL, NULL, '11.2');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Ujian Ma\'arif', 11, 3, NULL, NULL, NULL, NULL, NULL, '11.3');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Ujian Sekolah', 11, 4, NULL, NULL, NULL, NULL, NULL, '11.4');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Ujian Praktek', 11, 5, NULL, NULL, NULL, NULL, NULL, '11.5');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Tri Out', 11, 6, NULL, NULL, NULL, NULL, NULL, '11.6');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Ujian Nasional', 11, 7, NULL, NULL, NULL, NULL, NULL, '11.7');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Ulangan Harian sem.I dan II', 11, 8, NULL, NULL, NULL, NULL, NULL, '11.8');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Pembelian/Pengadaan sarana dan prasarana  Pemb', 12, NULL, NULL, 1, 5, NULL, NULL, '12');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Pembelian sarana Olah raga', 12, 1, NULL, NULL, NULL, NULL, NULL, '12.1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Pembelian sarana dan bahan kebersihan', 12, 2, NULL, NULL, NULL, NULL, NULL, '12.2');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Pembelian Perlengkapan kelas', 12, 3, NULL, NULL, NULL, NULL, NULL, '12.3');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Pengadaan buku ', 12, 4, NULL, NULL, NULL, NULL, NULL, '12.4');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Biaya Daya dan Jasa', 13, NULL, NULL, 1, 7, NULL, NULL, '13');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Rekening telepon', 13, 1, NULL, NULL, NULL, NULL, NULL, '13.1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Rekening listrik', 13, 2, NULL, NULL, NULL, NULL, NULL, '13.2');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Biaya Upah /Gaji /Honorarium tenaga pendidik dan tenaga kependidikan', 14, NULL, NULL, 2, 2, 3, NULL, '14');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Honorarium Guru dan karyawan', 14, 1, NULL, NULL, NULL, NULL, NULL, '14.1');
INSERT INTO `tbl_master_kode_akun_internal` VALUES ('Honorarium Pemb.ekstra,satpam,pak bon', 14, 2, NULL, NULL, NULL, NULL, NULL, '14.2');

-- ----------------------------
-- Table structure for tbl_menu
-- ----------------------------
DROP TABLE IF EXISTS `tbl_menu`;
CREATE TABLE `tbl_menu`  (
  `id_menu` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL,
  `nama_menu` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `judul_menu` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `link_menu` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `icon_menu` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `aktif_menu` int(1) NULL DEFAULT NULL,
  `tingkat_menu` int(11) NULL DEFAULT NULL,
  `urutan_menu` int(11) NULL DEFAULT NULL,
  `add_button` int(1) NULL DEFAULT NULL,
  `edit_button` int(1) NULL DEFAULT NULL,
  `delete_button` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id_menu`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_menu
-- ----------------------------
INSERT INTO `tbl_menu` VALUES (1, 0, 'Dashboard', 'Dashboard', 'home', 'fa fa-dashboard', 1, 1, 1, 0, 0, 0);
INSERT INTO `tbl_menu` VALUES (97, 99, 'Setting Menu', 'Setting Menu', 'set_menu_adm', NULL, 1, 2, 2, 1, 1, 1);
INSERT INTO `tbl_menu` VALUES (98, 99, 'Setting Role', 'Setting Role', 'set_role_adm', '', 1, 2, 1, 1, 1, 1);
INSERT INTO `tbl_menu` VALUES (99, 0, 'Setting (Administrator)', 'Setting', NULL, 'fa fa-gear', 1, 1, 5, 0, 0, 0);
INSERT INTO `tbl_menu` VALUES (100, 0, 'Transaksi', 'Transaksi', ' ', 'fa fa-retweet', 1, 1, 2, 0, 0, 0);
INSERT INTO `tbl_menu` VALUES (101, 100, 'Pengeluaran Harian', 'Pengeluaran Harian', 'pengeluaran', '', 1, 2, 1, 1, 1, 1);
INSERT INTO `tbl_menu` VALUES (102, 100, 'Verifikasi Pengeluaran', 'Verifikasi Pengeluaran', 'verifikasi_out', '', 1, 2, 2, 1, 1, 1);

-- ----------------------------
-- Table structure for tbl_satuan
-- ----------------------------
DROP TABLE IF EXISTS `tbl_satuan`;
CREATE TABLE `tbl_satuan`  (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_satuan
-- ----------------------------
INSERT INTO `tbl_satuan` VALUES (1, 'PCS');
INSERT INTO `tbl_satuan` VALUES (2, 'PACK');
INSERT INTO `tbl_satuan` VALUES (3, 'BOX');
INSERT INTO `tbl_satuan` VALUES (4, 'KG');
INSERT INTO `tbl_satuan` VALUES (5, 'DUZ');

-- ----------------------------
-- Table structure for tbl_trans_keluar
-- ----------------------------
DROP TABLE IF EXISTS `tbl_trans_keluar`;
CREATE TABLE `tbl_trans_keluar`  (
  `id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `user_id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pemohon` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tanggal` date NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE,
  CONSTRAINT `tbl_trans_keluar_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_trans_keluar
-- ----------------------------
INSERT INTO `tbl_trans_keluar` VALUES ('OUT101900001', 'USR00001', 'asas', '2019-10-11', 1, '2019-10-11 10:18:12', NULL);

-- ----------------------------
-- Table structure for tbl_trans_keluar_detail
-- ----------------------------
DROP TABLE IF EXISTS `tbl_trans_keluar_detail`;
CREATE TABLE `tbl_trans_keluar_detail`  (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `id_trans_keluar` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `satuan` int(4) NULL DEFAULT NULL,
  `qty` int(32) NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT 0 COMMENT '1: selesai, 0: belum selesai',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_trans_keluar`(`id_trans_keluar`) USING BTREE,
  INDEX `satuan`(`satuan`) USING BTREE,
  CONSTRAINT `tbl_trans_keluar_detail_ibfk_1` FOREIGN KEY (`id_trans_keluar`) REFERENCES `tbl_trans_keluar` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tbl_trans_keluar_detail_ibfk_2` FOREIGN KEY (`satuan`) REFERENCES `tbl_satuan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_trans_keluar_detail
-- ----------------------------
INSERT INTO `tbl_trans_keluar_detail` VALUES (1, 'OUT101900001', 'sasa', 4, 12, 0);
INSERT INTO `tbl_trans_keluar_detail` VALUES (2, 'OUT101900001', 'afas', 5, 12, 0);

-- ----------------------------
-- Table structure for tbl_user
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE `tbl_user`  (
  `id_user` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `username` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `password` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_level_user` int(11) NULL DEFAULT NULL,
  `id_pegawai` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT NULL,
  `last_login` datetime(0) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id_user`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_user
-- ----------------------------
INSERT INTO `tbl_user` VALUES ('USR00001', 'admin', '05munaqTlKafrsXZ3JyymIo=', 1, NULL, 1, '2019-10-14 13:27:16', '2019-10-05 21:34:14', '2019-10-14 13:27:16');

-- ----------------------------
-- Table structure for tbl_user_detail
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user_detail`;
CREATE TABLE `tbl_user_detail`  (
  `id_user_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_lengkap_user` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'Akun Baru',
  `alamat_user` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `tanggal_lahir_user` date NULL DEFAULT '1970-01-01',
  `jenis_kelamin_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_telp_user` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gambar_user` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'user_default.png',
  `thumb_gambar_user` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'user_default_thumb.png',
  PRIMARY KEY (`id_user_detail`) USING BTREE,
  UNIQUE INDEX `id_user`(`id_user`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tbl_user_detail
-- ----------------------------
INSERT INTO `tbl_user_detail` VALUES (2, 'USR00001', 'Rizky Yuanda', 'Jl. Ngagel Tirto IIB/6 Surabaya, Jawa Timur, Indonesia', '1991-04-03', 'Laki-Laki', '081703403473', 'img_USR00001.jpg', 'img_USR00001_thumb.jpg');
INSERT INTO `tbl_user_detail` VALUES (3, 'USR00002', 'Rizky Manajer', 'Jl. Ngagel Tirto IIB/6 Surabaya, Jawa Timur, Indonesia', '1991-04-03', 'Laki-Laki', '081703403473', 'img_USR000021.jpg', 'img_USR000021_thumb.jpg');
INSERT INTO `tbl_user_detail` VALUES (4, 'USR00004', 'Purnomo', 'rahasia', '1990-06-30', 'Laki-Laki', '', 'img_USR00004.jpg', 'img_USR00004_thumb.jpg');
INSERT INTO `tbl_user_detail` VALUES (5, 'USR00005', 'Minuk Dwi Susilowati', 'Masih dirahasiakan', '1985-02-15', 'Perempuan', '081221331214', 'img_USR000055.JPG', 'img_USR000055_thumb.JPG');
INSERT INTO `tbl_user_detail` VALUES (6, 'USR00006', 'Slamet Wardoyo', 'kepo dech', '1998-06-11', 'Laki-Laki', '081991991991', 'img_USR00006.jpg', 'img_USR00006_thumb.jpg');

-- ----------------------------
-- Table structure for tbl_verifikasi
-- ----------------------------
DROP TABLE IF EXISTS `tbl_verifikasi`;
CREATE TABLE `tbl_verifikasi`  (
  `id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_out` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_out_detail` int(32) NULL DEFAULT NULL,
  `user_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `gambar_bukti` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `harga_satuan` double(20, 2) NULL DEFAULT NULL,
  `harga_total` double(20, 2) NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_out`(`id_out`) USING BTREE,
  INDEX `id_out_detail`(`id_out_detail`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE,
  CONSTRAINT `tbl_verifikasi_ibfk_1` FOREIGN KEY (`id_out`) REFERENCES `tbl_trans_keluar` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tbl_verifikasi_ibfk_2` FOREIGN KEY (`id_out_detail`) REFERENCES `tbl_trans_keluar_detail` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tbl_verifikasi_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
