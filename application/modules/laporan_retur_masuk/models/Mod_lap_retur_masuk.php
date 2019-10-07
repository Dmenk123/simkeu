<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_lap_retur_masuk extends CI_Model
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
		"SELECT tbl_retur_masuk.tgl_retur_masuk, tbl_retur_masuk.id_retur_masuk, tbl_barang.nama_barang, tbl_satuan.nama_satuan, tbl_retur_masuk_detail.qty_retur_masuk, tbl_supplier.nama_supplier, tbl_retur_masuk_detail.keterangan_retur_masuk, tbl_user_detail.nama_lengkap_user
		 FROM tbl_retur_masuk
		 LEFT JOIN tbl_retur_masuk_detail ON tbl_retur_masuk_detail.id_retur_masuk = tbl_retur_masuk.id_retur_masuk 
		 LEFT JOIN tbl_barang ON tbl_retur_masuk_detail.id_barang = tbl_barang.id_barang 
		 LEFT JOIN tbl_satuan ON tbl_retur_masuk_detail.id_satuan = tbl_satuan.id_satuan 
		 LEFT JOIN tbl_supplier ON tbl_retur_masuk.id_supplier = tbl_supplier.id_supplier
		 LEFT JOIN tbl_user_detail ON tbl_retur_masuk.id_user = tbl_user_detail.id_user
		 WHERE tbl_retur_masuk.tgl_retur_masuk 
		 BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' ORDER BY tbl_retur_masuk.tgl_retur_masuk ASC"
		 );

         return $query->result();
	}

}