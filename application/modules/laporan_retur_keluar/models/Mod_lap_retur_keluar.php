<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_lap_retur_keluar extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	public function get_detail($tanggal_awal, $tanggal_akhir)
	{
		$query =  $this->db->query(
		"SELECT tbl_retur_keluar.tgl_retur_keluar, tbl_retur_keluar.id_retur_keluar, tbl_barang.nama_barang, tbl_satuan.nama_satuan, tbl_retur_keluar_detail.qty_retur_keluar, tbl_supplier.nama_supplier, tbl_retur_keluar_detail.keterangan_retur_keluar, tbl_user_detail.nama_lengkap_user
		 FROM tbl_retur_keluar
		 LEFT JOIN tbl_retur_keluar_detail ON tbl_retur_keluar_detail.id_retur_keluar = tbl_retur_keluar.id_retur_keluar 
		 LEFT JOIN tbl_barang ON tbl_retur_keluar_detail.id_barang = tbl_barang.id_barang 
		 LEFT JOIN tbl_satuan ON tbl_retur_keluar_detail.id_satuan = tbl_satuan.id_satuan 
		 LEFT JOIN tbl_supplier ON tbl_retur_keluar.id_supplier = tbl_supplier.id_supplier
		 LEFT JOIN tbl_user_detail ON tbl_retur_keluar.id_user = tbl_user_detail.id_user
		 WHERE tbl_retur_keluar.tgl_retur_keluar 
		 BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' ORDER BY tbl_retur_keluar.tgl_retur_keluar ASC"
		 );

         return $query->result();
	}

}