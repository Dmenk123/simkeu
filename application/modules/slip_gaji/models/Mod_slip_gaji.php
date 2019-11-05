<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_slip_gaji extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	public function get_detail($bulan, $tahun)
	{ 
		$tanggal_awal = date('Y-m-d', strtotime($tahun.'-'.$bulan.'-01'));
		$tanggal_akhir = date('Y-m-t', strtotime($tahun.'-'.$bulan.'-01'));

		$query = $this->db->query("
			SELECT tv.*, CASE WHEN (tmd.keterangan is null) THEN tkd.keterangan ELSE tmd.keterangan END AS keterangan
			FROM tbl_verifikasi tv
			left join tbl_trans_masuk_detail tmd on concat(tv.id_in,'-',tv.id_in_detail) = concat(tmd.id_trans_masuk,'-',tmd.id)
			left join tbl_trans_keluar_detail tkd on concat(tv.id_out,'-',tv.id_out_detail) = concat(tkd.id_trans_keluar,'-',tkd.id)
			where tanggal between '$tanggal_awal' and '$tanggal_akhir' order by tv.tipe_transaksi, tv.tanggal, tv.id
		");

        return $query->result();
       
	}

	public function get_detail_laporan($bulan, $tahun, $kode_header)
	{
		$tanggal_awal = date('Y-m-d', strtotime($tahun.'-'.$bulan.'-01'));
		$tanggal_akhir = date('Y-m-t', strtotime($tahun.'-'.$bulan.'-01'));

		$query = $this->db->query("
			SELECT * from tbl_lap_bku_detail where tanggal between '$tanggal_awal' and '$tanggal_akhir' and is_kunci = '1'
		");

        return $query->result();
	}

	public function get_saldo_awal($bulan, $tahun)
	{
		$saldo = 0;
		$anchorBulan = (int)$bulan - 1;
		for ($i=$bulan; $i <= 1 ; $i--) { 
			$bln = ($i < 10) ? '0'.$i : $i;
			$q = $this->db->query("SELECT saldo_akhir FROM tbl_lap_bku WHERE bulan = '".$bln."' and tahun = '".$tahun."' and is_delete is null")->row();

			if ($q) {
				$saldo = $q->saldo_akhir;
				break;
			}
		}

		return $saldo;
	}
}