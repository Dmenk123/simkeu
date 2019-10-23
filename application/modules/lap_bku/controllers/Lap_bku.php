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

	public function laporan_bku_detail()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$bln_awal = $this->input->get('bln_awal');
		$bln_akhir = $this->input->get('bln_akhir');
		$tahun = $this->input->get('tahun');
		
		//menghilangkan string 0 pada bulan
		$arr_pecah_bulan = $this->hilangakan_stringkosong_bulan($bln_awal, $bln_akhir, $tahun);
		$bulan_awal_fix = $arr_pecah_bulan['tanggal_awal'];
		$bulan_akhir_fix = $arr_pecah_bulan['tanggal_akhir'];

		//untuk mengetahui berapa bulan yg didapat dari pilihan
		$arr_bulan = $this->pecah_bulan($bulan_awal_fix, $bulan_akhir_fix, $tahun);
		
		//cari periode untuk tampilan pada laporan
		$arr_bln_indo = $this->bulan_indo();
		$periode1 = $arr_bln_indo[$bln_awal].' '.$tahun;
		$periode2 = $arr_bln_indo[$bln_awal].' '.$tahun;
		$saldo_awal = 0;
		$arr_data = [];

		foreach ($arr_bulan as $keys => $value) {
			//cek bulan sudah dikunci atau belum
			$q_cek = $this->db->query("SELECT * FROM lap_bku WHERE is_kunci = '1' and bulan = '".$value->month_raw."' and tahun = '".$value->year_raw."'")->row();

			if (!$q_cek) {
				//get detail laporan  jika belum dikunci
				$query = $this->lap->get_detail($value->month_raw, $tahun);

				foreach ($query as $key => $val) {
					$arr_data[$key]['tanggal'] = date('d-m-Y', strtotime($val->tanggal));
					if ($val->tipe_transaksi == 1) {
						$arr_data[$key]['kode'] = $val->id_in.'-'.$val->id_in_detail;
					}else{
						$arr_data[$key]['kode'] = $val->id_in.'-'.$val->id_out_detail;
					}
					
					$arr_data[$key]['tanggal'] = $val->keterangan;
					
					if ($val->tipe_transaksi == 1) {
						$arr_data[$key]['penerimaan'] = number_format($val->harga_total,2,",",".");
						$arr_data[$key]['pengeluaran'] = '0,00';
					}else{
						$arr_data[$key]['penerimaan'] = '0,00';
						$arr_data[$key]['pengeluaran'] = number_format($val->harga_total,2,",",".");
					}
					
					//saldo
					$arr_data[$key]['saldo'] = '';
				}
			}else{
				
			}
			

		}

		$q_saldo_awal = $this->lap->get_saldo_awal($bln_awal, $tahun);

		
		
		
		$data = array(
			'data_user' => $data_user,
			'arr_bulan' => $this->bulan_indo(),
			'hasil_data' => $arr_data,
			'periode' => $periode1.' s/d '.$periode2,
			'bln_awal' => $bln_awal,
			'bln_akhir' => $bln_akhir,
			'tahun' => $tahun
		);

		$content = [
			'css' 	=> 'cssLapBku',
			'modal' => null,
			'js'	=> 'jsLapBku',
			'view'	=> 'view_lap_bku_detail'
		];

		$this->template_view->load_view($content, $data);
	}

	public function cetak_report_mutasi($tglAwal= 0, $tglAkhir= 0, $pilihanTampil= 0)
	{
		$this->load->library('Pdf_gen');
		$id_user = $this->session->userdata('id_user');
		if ($pilihanTampil == "semua") {
			$query = $this->lap->get_detail($tglAwal, $tglAkhir);
		}else{
			$query = $this->lap->get_detail2($tglAwal, $tglAkhir);
		}
		
		$query_footer = $this->lap->get_detail_footer($id_user);


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
