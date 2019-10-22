<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_bku extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//profil data
		$this->load->model('profil/mod_profil','prof');
		$this->load->model('mod_lap_bku','lap');
	}

	public function index()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$data = array(
			'data_user' => $data_user,
			'arr_bulan' => $this->bulan_indo()
		);

		$content = [
			'css' 	=> 'cssLapBku',
			'modal' => null,
			'js'	=> 'jsLapBku',
			'view'	=> 'view_lap_bku'
		];

		$this->template_view->load_view($content, $data);
	}

	public function laporan_mutasi_detail()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query_user = $this->mod_pengguna->get_detail_user($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$tampil_mutasi = $this->input->post('tampilMutasi');
		$tanggal_awal = $this->input->post('tanggalMutasiAwal');
		$tanggal_akhir = $this->input->post('tanggalMutasiAkhir');

		if ($tampil_mutasi == "semua") {
			$query = $this->lap_mutasi->get_detail($tanggal_awal, $tanggal_akhir);
			$tampil = 'semua';
		}else{
			$query = $this->lap_mutasi->get_detail2($tanggal_awal, $tanggal_akhir);
			$tampil = 'hanya-mutasi';
		}

		//echo var_dump($query_header);
		if ($this->session->userdata('id_level_user') == 1 || $this->session->userdata('id_level_user') == 2 || $this->session->userdata('id_level_user') == 3 ) {
			$data = array(
				'content'=>'view_lap_mutasi_detail',
				'css'=>'cssLapMutasi',
				'js'=>'jsLapMutasi',
				'title' => 'PT.Surya Putra Barutama',
				'hasil_data' => $query,
				'data_user' => $query_user,
				'tanggal_awal' => $tanggal_awal,
				'tanggal_akhir' => $tanggal_akhir,
				'pilihan_tampil' => $tampil,
				'tanggal' => $tanggal_awal.' s/d '.$tanggal_akhir,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function cetak_report_mutasi($tglAwal= 0, $tglAkhir= 0, $pilihanTampil= 0)
	{
		$this->load->library('Pdf_gen');
		$id_user = $this->session->userdata('id_user');
		if ($pilihanTampil == "semua") {
			$query = $this->lap_mutasi->get_detail($tglAwal, $tglAkhir);
		}else{
			$query = $this->lap_mutasi->get_detail2($tglAwal, $tglAkhir);
		}
		
		$query_footer = $this->lap_mutasi->get_detail_footer($id_user);


		$data = array(
			'title' => 'PT.Surya Putra Barutama',
			'hasil_data' => $query,
			'hasil_footer' => $query_footer,
			'tanggal_awal' => $tglAwal,
			'tanggal_akhir' => $tglAkhir,
			'tanggal' => $tglAwal.' s/d '.$tglAkhir,
			);

	    $html = $this->load->view('view_lap_mutasi_cetak', $data, true);
	    
	    $filename = 'laporan_mutasi_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function bulan_indo()
	{
		return [
			1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
		];
	}

}
