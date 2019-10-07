<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class mod_lap_keluar extends CI_Model
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
		"SELECT tbl_trans_keluar_detail.tgl_trans_keluar_detail, tbl_trans_keluar.id_trans_keluar, tbl_barang.nama_barang, tbl_satuan.nama_satuan, tbl_trans_keluar_detail.qty_keluar, CONCAT_WS(' - ',tbl_borongan.nama_borongan, tbl_borongan_detail.nama_borongan_detail) AS pengambil, tbl_trans_keluar_detail.keterangan_keluar
		 FROM tbl_trans_keluar
		 LEFT JOIN tbl_trans_keluar_detail ON tbl_trans_keluar_detail.id_trans_keluar = tbl_trans_keluar.id_trans_keluar 
		 LEFT JOIN tbl_barang ON tbl_trans_keluar_detail.id_barang = tbl_barang.id_barang 
		 LEFT JOIN tbl_satuan ON tbl_trans_keluar_detail.id_satuan = tbl_satuan.id_satuan 
		 LEFT JOIN tbl_borongan ON tbl_trans_keluar.id_borongan = tbl_borongan.id_borongan
		 LEFT JOIN tbl_borongan_detail ON tbl_trans_keluar.id_borongan_detail = tbl_borongan_detail.id_borongan_detail
		 WHERE tbl_trans_keluar_detail.tgl_trans_keluar_detail 
		 BETWEEN '".$tanggal_awal."' AND '".$tanggal_akhir."' ORDER BY tbl_trans_keluar_detail.tgl_trans_keluar_detail ASC"
		 );
        
        return $query->result();
	}

}