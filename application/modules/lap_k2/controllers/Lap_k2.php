<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_k2 extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//profil data
		$this->load->model('profil/mod_profil','prof');
		$this->load->model('mod_lap_k2','lap');
	}

	public function index()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$data = array(
			'data_user' => $data_user
		);

		$content = [
			'css' 	=> 'cssLapK2',
			'modal' => null,
			'js'	=> 'jsLapK2',
			'view'	=> 'view_lap_K2'
		];

		$this->template_view->load_view($content, $data);
	}

	public function laporan_k2_detail()
	{
		$this->load->library('Pdf_gen');
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$tahun = $this->input->get('tahun');

		//cari periode untuk tampilan pada laporan
		$arr_data = [];

		
		//get detail laporan jika belum dikunci
		$tanggal_awal = date('Y-m-d', strtotime($tahun.'-01-01'));
		$tanggal_akhir = date('Y-m-t', strtotime($tahun.'-12-01'));
		$query = $this->lap->get_detail($tanggal_awal, $tanggal_akhir);

		//ambil penerimaan (dana BOS)
		$query_masuk = $this->lap->get_penerimaan($bulan_awal_fix, $bulan_akhir_fix);
						
		//assign satu row array untuk saldo awal
		$arr_data[0]['kode'] = '-';
		$arr_data[0]['kegiatan'] = 'Penerimaan';
		$arr_data[0]['jumlah'] = $query_masuk->total_penerimaan;
		$arr_data[0]['jumlah_raw'] = $query_masuk->total_penerimaan;
		$arr_data[0]['tipe_out'] = null;

		//loop detail laporan dan assign array
		foreach ($query as $key => $val) {
			$arr_data[$key+1]['kode'] = $val->kode_in_text;
			$arr_data[$key+1]['kegiatan'] = $val->nama;
			$arr_data[$key+1]['jumlah'] = number_format($val->harga_total,0,",",".");
			$arr_data[$key+1]['jumlah_raw'] = $val->harga_total;
			$arr_data[$key+1]['tipe_out'] = $val->tipe;
		}
		
		$arr_bulan_indo = $this->bulan_indo();
		$txtPeriode = 'Triwulan '.$arr_pecah_bulan[2].' | '.$arr_bulan_indo[$arr_pecah_bulan[0]].' s.d '.$arr_bulan_indo[$arr_pecah_bulan[1]].' '.$tahun;

		$data = array(
			'title' => 'SMP Darul Ulum Surabaya',
			'data_user' => $data_user,
			'arr_bulan' => $this->bulan_indo(),
			'hasil_data' => $arr_data,
			'periode' => $txtPeriode,
			'bln_awal' => $arr_pecah_bulan[0],
			'bln_akhir' => $arr_pecah_bulan[1],
			'tahun' => $tahun
		);

		/*echo "<pre>";
		print_r ($data);
		echo "</pre>";
		exit;*/

	    $html = $this->load->view('view_lap_k7_cetak', $data, true);
	    
	    $filename = 'laporan_k7_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'landscape');
	}

	public function bulan_indo()
	{
		return [
			1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
		];
	}

	public function hari_indo()
	{
		return [
			0 => 'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
		];
	}

	public function pecah_bulan($tgl_awal, $tgl_akhir)
	{

		$date1  = $tgl_awal;
		$date2  = $tgl_akhir;
		$output = [];
		$time   = strtotime($date1);
		$last   = date('m-Y', strtotime($date2));

		do {
			$month_raw = date('m', $time);
		    $month = date('m-Y', $time);
		    $total = date('t', $time);

		    $arr = explode("-",$month);
		    $tahun_bulan = $arr[1].'-'.$arr[0];
			$tahun = $arr[1];
			
		    $output[] = [
				'month_raw' => $month_raw,
				'year_raw' => $tahun,
		        'month' => $tahun_bulan,
		        'total' => $total,
		    ];

		    $time = strtotime('+1 month', $time);
		} while ($month != $last);


		return $output;
	}

	public function hilangakan_stringkosong_bulan($bln_awal, $bln_akhir, $tahun)
	{
		$bulan_awal = ($bln_awal < 10) ? '0'.$bln_awal : $bln_awal;
		$bulan_akhir = ($bln_akhir < 10) ? '0'.$bln_akhir : $bln_akhir;

		$tanggal_awal = date('Y-m-d', strtotime($tahun.'-'.$bulan_awal.'-01'));
		$tanggal_akhir = date('Y-m-t', strtotime($tahun.'-'.$bulan_akhir.'-01'));

		return [
			'tanggal_awal' => $tanggal_awal,
			'tanggal_akhir' => $tanggal_akhir,	
		];
	}

}
