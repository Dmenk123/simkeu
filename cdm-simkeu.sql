CREATE TABLE `tbl_guru` (
`id` int(12) NOT NULL AUTO_INCREMENT,
`nip` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`kode_jabatan` int(12) NULL DEFAULT NULL,
`alamat` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`tempat_lahir` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`tanggal_lahir` date NULL DEFAULT NULL,
`jenis_kelamin` varchar(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'L: Laki2, P: Perempuan',
`foto` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`is_aktif` int(1) NULL DEFAULT 1,
`is_guru` int(1) NULL DEFAULT 1,
`password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
PRIMARY KEY (`id`) ,
INDEX `kode_jabatan` (`kode_jabatan` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 6
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_hak_akses` (
`id_menu` int(11) NOT NULL,
`id_level_user` int(11) NOT NULL,
`add_button` int(1) NULL DEFAULT NULL,
`edit_button` int(1) NULL DEFAULT NULL,
`delete_button` int(1) NULL DEFAULT NULL,
INDEX `f_level_user` (`id_level_user` ASC) USING BTREE,
INDEX `id_menu` (`id_menu` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 0
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_jabatan` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`tunjangan` double(20,2) NULL DEFAULT NULL,
`is_aktif` int(1) NULL DEFAULT 1,
PRIMARY KEY (`id`) 
)
ENGINE = InnoDB
AUTO_INCREMENT = 7
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_lap_bku` (
`kode` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
`bulan` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`tahun` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`saldo_awal` double(20,2) NULL DEFAULT NULL,
`saldo_akhir` double(20,2) NULL DEFAULT NULL,
`created` datetime NULL DEFAULT NULL,
`updated` datetime NULL DEFAULT NULL,
`is_delete` int(1) NULL DEFAULT 0,
`is_kunci` int(1) NULL DEFAULT 0,
PRIMARY KEY (`kode`) 
)
ENGINE = InnoDB
AUTO_INCREMENT = 0
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_lap_bku_detail` (
`kode_header` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`bulan` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`tahun` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`tanggal` date NULL DEFAULT NULL,
`bukti` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`penerimaan` double(20,2) NULL DEFAULT NULL,
`pengeluaran` double(20,2) NULL DEFAULT NULL,
`saldo` double(20,2) NULL DEFAULT NULL,
`created_at` datetime NULL DEFAULT NULL,
`updated_at` datetime NULL DEFAULT NULL,
`is_delete` int(1) NULL DEFAULT 0,
`is_kunci` int(1) NULL DEFAULT 0,
INDEX `kode_header` (`kode_header` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 0
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_level_user` (
`id_level_user` int(11) NOT NULL AUTO_INCREMENT,
`nama_level_user` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
`keterangan_level_user` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
`aktif` int(1) NULL DEFAULT 1,
PRIMARY KEY (`id_level_user`) 
)
ENGINE = InnoDB
AUTO_INCREMENT = 6
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_log_kunci` (
`bulan` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`tahun` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`user_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`is_kunci` int(1) NULL DEFAULT 0,
`created_at` datetime NULL DEFAULT NULL,
`updated_at` datetime NULL DEFAULT NULL,
INDEX `user_id` (`user_id` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 0
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_master_kode_akun` (
`nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
`tipe` int(1) NULL DEFAULT NULL,
`kode` int(3) NULL DEFAULT NULL,
`sub_1` int(3) NULL DEFAULT NULL,
`sub_2` int(3) NULL DEFAULT NULL,
`kode_in_text` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`is_aktif` int(1) NULL DEFAULT 1,
INDEX `tipe` (`tipe` ASC) USING BTREE,
INDEX `kode` (`kode` ASC) USING BTREE,
INDEX `sub_1` (`sub_1` ASC) USING BTREE,
INDEX `sub_2` (`sub_2` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 0
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_master_kode_akun_internal` (
`nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`kode` int(3) NULL DEFAULT NULL,
`sub_1` int(3) NULL DEFAULT NULL,
`sub_2` int(3) NULL DEFAULT NULL,
`tipe_bos` int(3) NULL DEFAULT NULL,
`kode_bos` int(3) NULL DEFAULT NULL,
`kode_bos_sub1` int(3) NULL DEFAULT NULL,
`kode_bos_sub2` int(3) NULL DEFAULT NULL,
`kode_in_text` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`is_aktif` int(1) NULL DEFAULT 1,
INDEX `kode_bos` (`kode_bos` ASC) USING BTREE,
INDEX `kode_bos_sub1` (`kode_bos_sub1` ASC) USING BTREE,
INDEX `kode_bos_sub2` (`kode_bos_sub2` ASC) USING BTREE,
INDEX `tbl_master_kode_akun_internal_ibfk_1` (`tipe_bos` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 0
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_menu` (
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
PRIMARY KEY (`id_menu`) 
)
ENGINE = InnoDB
AUTO_INCREMENT = 0
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_penggajian` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_guru` int(12) NULL DEFAULT NULL,
`id_jabatan` int(12) NULL DEFAULT NULL,
`bulan` int(2) NULL DEFAULT NULL,
`tahun` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`is_guru` int(1) NULL DEFAULT 1,
`gaji_pokok` double(20,2) NULL DEFAULT NULL,
`gaji_perjam` double(20,2) NULL DEFAULT NULL,
`gaji_tunjangan_jabatan` double(20,2) NULL DEFAULT NULL,
`gaji_tunjangan_lain` double(20,2) NULL DEFAULT NULL,
`jumlah_jam_kerja` int(5) NULL DEFAULT NULL,
`potongan_lain` double(20,2) NULL DEFAULT NULL,
`total_take_home_pay` double(20,2) NULL DEFAULT NULL,
`created_at` datetime NULL DEFAULT NULL,
`is_confirm` int(1) NULL DEFAULT 0,
`is_aktif` int(1) NULL DEFAULT 1,
PRIMARY KEY (`id`) ,
INDEX `id_guru` (`id_guru` ASC) USING BTREE,
INDEX `id_jabatan` (`id_jabatan` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 10
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_rapbs` (
`id` int(14) NOT NULL AUTO_INCREMENT,
`tahun` varchar(4) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`user_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`created_at` datetime NULL DEFAULT NULL,
`updated_at` datetime NULL DEFAULT NULL,
`deleted_at` datetime NULL DEFAULT NULL,
PRIMARY KEY (`id`) ,
INDEX `user_id` (`user_id` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 6
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_rapbs_detail` (
`id_header` int(14) NULL DEFAULT NULL,
`uraian` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`qty` int(32) NULL DEFAULT NULL,
`nama_satuan` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`harga_satuan` double(20,2) NULL DEFAULT NULL,
`harga_total` double(20,2) NULL DEFAULT NULL,
`gaji_swasta` double(20,2) NULL DEFAULT NULL,
`bosnas` double(20,2) NULL DEFAULT NULL,
`hibah_bopda` double(20,2) NULL DEFAULT NULL,
`jumlah_total` double(20,2) NULL DEFAULT NULL,
`keterangan_belanja` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`is_sub` int(1) NULL DEFAULT 1,
`urut` int(32) NULL DEFAULT NULL,
`created_at` datetime NULL DEFAULT NULL,
`updated_at` datetime NULL DEFAULT NULL,
`deleted_at` datetime NULL DEFAULT NULL,
`kode` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
INDEX `id_header` (`id_header` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 0
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_satuan` (
`id` int(32) NOT NULL AUTO_INCREMENT,
`nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`is_aktif` int(1) NULL DEFAULT 1,
PRIMARY KEY (`id`) 
)
ENGINE = InnoDB
AUTO_INCREMENT = 10
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_set_gaji` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_jabatan` int(14) NULL DEFAULT NULL,
`gaji_pokok` double(20,2) NULL DEFAULT NULL,
`gaji_perjam` double(20,2) NULL DEFAULT NULL,
`gaji_tunjangan_jabatan` double(20,2) NULL DEFAULT NULL,
`is_guru` int(1) NULL DEFAULT 1,
`is_aktif` int(1) NULL DEFAULT 1,
PRIMARY KEY (`id`) ,
INDEX `id_jabatan` (`id_jabatan` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 7
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_trans_keluar` (
`id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
`user_id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`pemohon` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`tanggal` date NULL DEFAULT NULL,
`status` int(1) NULL DEFAULT NULL COMMENT '1: belum diverifikasi, 0: sudah diverifikasi',
`created_at` datetime NULL DEFAULT NULL,
`updated_at` datetime NULL DEFAULT NULL,
PRIMARY KEY (`id`) ,
INDEX `user_id` (`user_id` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 0
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_trans_keluar_detail` (
`id` int(32) NOT NULL AUTO_INCREMENT,
`id_trans_keluar` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`satuan` int(4) NULL DEFAULT NULL,
`qty` int(32) NULL DEFAULT NULL,
`status` int(1) NULL DEFAULT 0 COMMENT '1:sudah diverifikasi, 0: belum',
PRIMARY KEY (`id`) ,
INDEX `id_trans_keluar` (`id_trans_keluar` ASC) USING BTREE,
INDEX `satuan` (`satuan` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 33
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_trans_masuk` (
`id` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
`user_id` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`tanggal` date NULL DEFAULT NULL,
`status` int(1) NULL DEFAULT 0 COMMENT '0: Belum diverifikasi, 1: sudah diverifikasi',
`created_at` datetime NULL DEFAULT NULL,
`updated_at` datetime NULL DEFAULT NULL,
`is_bos` int(1) NULL DEFAULT 0 COMMENT '0: Bukan Bos, 1: Dari Bos',
PRIMARY KEY (`id`) ,
INDEX `user_id` (`user_id` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 0
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_trans_masuk_detail` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_trans_masuk` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`satuan` int(10) NULL DEFAULT NULL,
`qty` int(10) NULL DEFAULT NULL,
`status` int(1) NULL DEFAULT NULL COMMENT '0: Belum diverifikasi, 1: Sudah diverifikasi',
PRIMARY KEY (`id`) ,
INDEX `id_trans_masuk` (`id_trans_masuk` ASC) USING BTREE,
INDEX `satuan` (`satuan` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 3
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_user` (
`id_user` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
`username` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`password` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`id_level_user` int(11) NULL DEFAULT NULL,
`id_pegawai` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`status` int(1) NULL DEFAULT NULL,
`last_login` datetime NULL DEFAULT NULL,
`created_at` datetime NULL DEFAULT NULL,
`updated_at` datetime NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id_user`) ,
INDEX `id_level_user` (`id_level_user` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 0
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_user_detail` (
`id_user_detail` int(11) NOT NULL AUTO_INCREMENT,
`id_user` varchar(8) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
`nama_lengkap_user` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'Akun Baru',
`alamat_user` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
`tanggal_lahir_user` date NULL DEFAULT '1970-01-01',
`jenis_kelamin_user` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`no_telp_user` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
`gambar_user` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'user_default.png',
`thumb_gambar_user` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'user_default_thumb.png',
PRIMARY KEY (`id_user_detail`) ,
UNIQUE INDEX `id_user` (`id_user` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 11
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;

CREATE TABLE `tbl_verifikasi` (
`id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
`id_out` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`id_out_detail` int(32) NULL DEFAULT NULL,
`id_in` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`id_in_detail` int(32) NULL DEFAULT NULL,
`user_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`tanggal` date NULL DEFAULT NULL,
`gambar_bukti` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
`harga_satuan` double(20,2) NULL DEFAULT NULL,
`harga_total` double(20,2) NULL DEFAULT NULL,
`status` int(1) NULL DEFAULT NULL,
`tipe_akun` int(1) NULL DEFAULT NULL,
`kode_akun` int(1) NULL DEFAULT NULL,
`sub1_akun` int(1) NULL DEFAULT NULL,
`sub2_akun` int(1) NULL DEFAULT NULL,
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
`tipe_transaksi` int(1) NULL DEFAULT 2 COMMENT '1: Penerimaan, 2: Pengeluaran',
PRIMARY KEY (`id`) ,
INDEX `id_out` (`id_out` ASC) USING BTREE,
INDEX `id_out_detail` (`id_out_detail` ASC) USING BTREE,
INDEX `user_id` (`user_id` ASC) USING BTREE,
INDEX `id_in` (`id_in` ASC) USING BTREE,
INDEX `id_in_detail` (`id_in_detail` ASC) USING BTREE
)
ENGINE = InnoDB
AUTO_INCREMENT = 0
AVG_ROW_LENGTH = 0
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci
KEY_BLOCK_SIZE = 0
MAX_ROWS = 0
MIN_ROWS = 0
ROW_FORMAT = Compact;


ALTER TABLE `tbl_guru` ADD CONSTRAINT `tbl_guru_ibfk_1` FOREIGN KEY (`kode_jabatan`) REFERENCES `tbl_jabatan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_hak_akses` ADD CONSTRAINT `f_level_user` FOREIGN KEY (`id_level_user`) REFERENCES `tbl_level_user` (`id_level_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_hak_akses` ADD CONSTRAINT `tbl_hak_akses_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `tbl_menu` (`id_menu`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_lap_bku_detail` ADD CONSTRAINT `tbl_lap_bku_detail_ibfk_1` FOREIGN KEY (`kode_header`) REFERENCES `tbl_lap_bku` (`kode`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_log_kunci` ADD CONSTRAINT `tbl_log_kunci_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_master_kode_akun_internal` ADD CONSTRAINT `tbl_master_kode_akun_internal_ibfk_1` FOREIGN KEY (`tipe_bos`) REFERENCES `tbl_master_kode_akun` (`tipe`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_master_kode_akun_internal` ADD CONSTRAINT `tbl_master_kode_akun_internal_ibfk_2` FOREIGN KEY (`kode_bos`) REFERENCES `tbl_master_kode_akun` (`kode`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_master_kode_akun_internal` ADD CONSTRAINT `tbl_master_kode_akun_internal_ibfk_3` FOREIGN KEY (`kode_bos_sub1`) REFERENCES `tbl_master_kode_akun` (`sub_1`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_master_kode_akun_internal` ADD CONSTRAINT `tbl_master_kode_akun_internal_ibfk_4` FOREIGN KEY (`kode_bos_sub2`) REFERENCES `tbl_master_kode_akun` (`sub_2`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_penggajian` ADD CONSTRAINT `tbl_penggajian_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `tbl_guru` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_penggajian` ADD CONSTRAINT `tbl_penggajian_ibfk_2` FOREIGN KEY (`id_jabatan`) REFERENCES `tbl_jabatan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_rapbs` ADD CONSTRAINT `tbl_rapbs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_rapbs_detail` ADD CONSTRAINT `tbl_rapbs_detail_ibfk_1` FOREIGN KEY (`id_header`) REFERENCES `tbl_rapbs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_set_gaji` ADD CONSTRAINT `tbl_set_gaji_ibfk_1` FOREIGN KEY (`id_jabatan`) REFERENCES `tbl_jabatan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_trans_keluar` ADD CONSTRAINT `tbl_trans_keluar_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_trans_keluar_detail` ADD CONSTRAINT `tbl_trans_keluar_detail_ibfk_1` FOREIGN KEY (`id_trans_keluar`) REFERENCES `tbl_trans_keluar` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_trans_keluar_detail` ADD CONSTRAINT `tbl_trans_keluar_detail_ibfk_2` FOREIGN KEY (`satuan`) REFERENCES `tbl_satuan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_trans_masuk` ADD CONSTRAINT `tbl_trans_masuk_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_trans_masuk_detail` ADD CONSTRAINT `tbl_trans_masuk_detail_ibfk_1` FOREIGN KEY (`id_trans_masuk`) REFERENCES `tbl_trans_masuk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_trans_masuk_detail` ADD CONSTRAINT `tbl_trans_masuk_detail_ibfk_2` FOREIGN KEY (`satuan`) REFERENCES `tbl_satuan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_user` ADD CONSTRAINT `tbl_user_ibfk_1` FOREIGN KEY (`id_level_user`) REFERENCES `tbl_level_user` (`id_level_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_verifikasi` ADD CONSTRAINT `tbl_verifikasi_ibfk_1` FOREIGN KEY (`id_out`) REFERENCES `tbl_trans_keluar` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_verifikasi` ADD CONSTRAINT `tbl_verifikasi_ibfk_2` FOREIGN KEY (`id_out_detail`) REFERENCES `tbl_trans_keluar_detail` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_verifikasi` ADD CONSTRAINT `tbl_verifikasi_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_verifikasi` ADD CONSTRAINT `tbl_verifikasi_ibfk_4` FOREIGN KEY (`id_in`) REFERENCES `tbl_trans_masuk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `tbl_verifikasi` ADD CONSTRAINT `tbl_verifikasi_ibfk_5` FOREIGN KEY (`id_in_detail`) REFERENCES `tbl_trans_masuk_detail` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

