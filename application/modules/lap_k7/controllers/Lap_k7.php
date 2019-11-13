<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_k7 extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//profil data
		$this->load->model('profil/mod_profil','prof');
		$this->load->model('mod_lap_k7','lap');
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
			'css' 	=> 'cssLapK7',
			'modal' => null,
			'js'	=> 'jsLapK7',
			'view'	=> 'view_lap_k7'
		];

		$this->template_view->load_view($content, $data);
	}

	public function laporan_k7_detail()
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
		$periode2 = $arr_bln_indo[$bln_akhir].' '.$tahun;
		$saldo_awal = 0;
		$saldo_akhir = 0;
		$arr_data = [];

		foreach ($arr_bulan as $keys => $value) {
			//cek bulan sudah dikunci atau belum
			$q_cek = $this->db->query("SELECT * FROM tbl_lap_bku WHERE is_kunci = '1' and bulan = '".$value['month_raw']."' and tahun = '".$value['year_raw']."'")->row();
			
			if ($q_cek == null) {
				//get detail laporan jika belum dikunci
				$query = $this->lap->get_detail($value['month_raw'], $value['year_raw']);
				//ambil saldo akhir bulan sebelumnya
				$query_saldo = $this->lap->get_saldo_awal($value['month_raw'], $value['year_raw']);
				$saldo_awal += (int)$query_saldo;
				
				//assign satu row array untuk saldo awal
				$arr_data[0]['tanggal'] = date('d-m-Y', strtotime($value['year_raw'].'-'.$value['month_raw'].'-01'));
				$arr_data[0]['kode'] = '-';
				$arr_data[0]['bukti'] = '-';
				$arr_data[0]['keterangan'] = 'Saldo Awal';
				$arr_data[0]['penerimaan'] = '-';
				$arr_data[0]['pengeluaran'] = '-';
				$arr_data[0]['saldo_akhir'] = $saldo_awal;

				//loop detail laporan dan assign array
				foreach ($query as $key => $val) {
					$arr_data[$key+1]['tanggal'] = date('d-m-Y', strtotime($val->tanggal));
					
					$kode = "";
					if ($val->kode_akun != null) {
						$kode .= $val->kode_akun.'.';
						if ($val->sub1_akun != null) {
							$kode .= $val->sub1_akun.'.';
							
							if ($val->sub2_akun != null) {
								$kode .= $val->sub2_akun;
							}else{
								$kode = $val->kode_akun.'.'.$val->sub1_akun;
							}

						}else{
							$kode = $val->kode_akun;
						}
					}
					$arr_data[$key+1]['kode'] = $kode;
					
					if ($val->tipe_transaksi == 1) {
						$arr_data[$key+1]['bukti'] = $val->id_in;
					}else{
						$arr_data[$key+1]['bukti'] = $val->id_out;
					}
					
					$arr_data[$key+1]['keterangan'] = $val->keterangan;
					
					if ($val->tipe_transaksi == 1) {
						$arr_data[$key+1]['penerimaan'] = number_format($val->harga_total,2,",",".");
						$arr_data[$key+1]['pengeluaran'] = '0,00';
						$in_raw = $val->harga_total;
						$out_raw = 0;
					}else{
						$arr_data[$key+1]['penerimaan'] = '0,00';
						$arr_data[$key+1]['pengeluaran'] = number_format($val->harga_total,2,",",".");
						$in_raw = 0;
						$out_raw = $val->harga_total;
					}
					
					//saldo
					if ($saldo_awal == 0) {
						$saldo_akhir += (int)$saldo_awal + (int)$in_raw - (int)$out_raw;
					}else{
						$saldo_akhir += (int)$saldo_akhir + (int)$in_raw - (int)$out_raw;
					}
					
					//set saldo awal to 0
					$saldo_awal = 0;
					$arr_data[$key+1]['saldo_akhir'] = (int)$saldo_akhir;
				}
			}
			else
			{
				//get detail laporan
				$get_lap_header = $this->db->query("select * from tbl_lap_bku where bulan = '".$value->month_raw."' and tahun = '".$value->year_raw."' and is_kunci = '1'")->row();

				$query = $this->lap->get_detail_laporan($value->month_raw, $tahun, $get_lap_header->kode);
				// get saldo awal bulan terpilih
				$query_saldo = $this->db->query("select * from tbl_lap_bku where bulan = '".$value->month_raw."' tahun = '".$value->year_raw."' and is_kunci = '1'");
				$saldo_awal += (int)$query_saldo->saldo_awal;

				//assign satu row array untuk saldo awal
				$arr_data[0]['tanggal'] = date('d-m-Y', strtotime($value['year_raw'].'-'.$value['month_raw'].'-01'));
				$arr_data[0]['kode'] = '-';
				$arr_data[0]['keterangan'] = 'Saldo Awal';
				$arr_data[0]['penerimaan'] = '-';
				$arr_data[0]['pengeluaran'] = '-';
				$arr_data[0]['saldo_akhir'] = $saldo_awal;

				foreach ($query as $key => $val) {
					$arr_data[$key+1]['tanggal'] = date('d-m-Y', strtotime($val->tanggal));
					$arr_data[$key+1]['kode'] = $val->kode_akun_in_text;
					if ($val->tipe_transaksi == 1) {
						$arr_data[$key+1]['butki'] = $val->id_in;
					}else{
						$arr_data[$key+1]['butki'] = $val->id_out;
					}
					
					$arr_data[$key+1]['keterangan'] = $val->keterangan;
					
					if ($val->tipe_transaksi == 1) {
						$arr_data[$key+1]['penerimaan'] = number_format($val->harga_total,2,",",".");
						$arr_data[$key+1]['pengeluaran'] = '0,00';
						$in_raw = $val->harga_total;
						$out_raw = 0;
					}else{
						$arr_data[$key+1]['penerimaan'] = '0,00';
						$arr_data[$key+1]['pengeluaran'] = number_format($val->harga_total,2,",",".");
						$in_raw = 0;
						$out_raw = $val->harga_total;
					}
					
					//saldo
					if ($saldo_awal == 0) {
						$saldo_akhir += (int)$saldo_awal + (int)$in_raw - (int)$out_raw;
					}else{
						$saldo_akhir += (int)$saldo_akhir + (int)$in_raw - (int)$out_raw;
					}
					
					//set saldo awal to 0
					$saldo_awal = 0;
					$arr_data[$key+1]['saldo_akhir'] = (int)$saldo_akhir;
				}
			}
		}

		$txtPeriode = (count($arr_bulan) > 1) ? $periode1.' s/d '.$periode2 : $periode1;

		$data = array(
			'data_user' => $data_user,
			'arr_bulan' => $this->bulan_indo(),
			'hasil_data' => $arr_data,
			'periode' => $txtPeriode,
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

	public function cetak_report_bku($bln_awal, $bln_akhir, $tahun)
	{
		$this->load->library('Pdf_gen');
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		//menghilangkan string 0 pada bulan
		$arr_pecah_bulan = $this->hilangakan_stringkosong_bulan($bln_awal, $bln_akhir, $tahun);
		$bulan_awal_fix = $arr_pecah_bulan['tanggal_awal'];
		$bulan_akhir_fix = $arr_pecah_bulan['tanggal_akhir'];

		//untuk mengetahui berapa bulan yg didapat dari pilihan
		$arr_bulan = $this->pecah_bulan($bulan_awal_fix, $bulan_akhir_fix, $tahun);
		
		//cari periode untuk tampilan pada laporan
		$arr_bln_indo = $this->bulan_indo();
		$periode1 = $arr_bln_indo[$bln_awal].' '.$tahun;
		$periode2 = $arr_bln_indo[$bln_akhir].' '.$tahun;
		$saldo_awal = 0;
		$saldo_akhir = 0;
		$arr_data = [];

		foreach ($arr_bulan as $keys => $value) 
		{
			//cek bulan sudah dikunci atau belum
			$q_cek = $this->db->query("SELECT * FROM tbl_lap_bku WHERE is_kunci = '1' and bulan = '".$value['month_raw']."' and tahun = '".$value['year_raw']."'")->row();
			
			if ($q_cek == null) {
				//get detail laporan jika belum dikunci
				$query = $this->lap->get_detail($value['month_raw'], $value['year_raw']);
				//ambil saldo akhir bulan sebelumnya
				$query_saldo = $this->lap->get_saldo_awal($value['month_raw'], $value['year_raw']);
				$saldo_awal += (int)$query_saldo;
				
				//assign satu row array untuk saldo awal
				$arr_data[0]['tanggal'] = date('d-m-Y', strtotime($value['year_raw'].'-'.$value['month_raw'].'-01'));
				$arr_data[0]['kode'] = '-';
				$arr_data[0]['bukti'] = '-';
				$arr_data[0]['keterangan'] = 'Saldo Awal';
				$arr_data[0]['penerimaan'] = '-';
				$arr_data[0]['pengeluaran'] = '-';
				$arr_data[0]['saldo_akhir'] = $saldo_awal;

				//loop detail laporan dan assign array
				foreach ($query as $key => $val) {
					$arr_data[$key+1]['tanggal'] = date('d-m-Y', strtotime($val->tanggal));
					
					$kode = "";
					if ($val->kode_akun != null) {
						$kode .= $val->kode_akun.'.';
						if ($val->sub1_akun != null) {
							$kode .= $val->sub1_akun.'.';
							
							if ($val->sub2_akun != null) {
								$kode .= $val->sub2_akun;
							}else{
								$kode = $val->kode_akun.'.'.$val->sub1_akun;
							}

						}else{
							$kode = $val->kode_akun;
						}
					}
					$arr_data[$key+1]['kode'] = $kode;
					
					if ($val->tipe_transaksi == 1) {
						$arr_data[$key+1]['bukti'] = $val->id_in;
					}else{
						$arr_data[$key+1]['bukti'] = $val->id_out;
					}
					
					$arr_data[$key+1]['keterangan'] = $val->keterangan;
					
					if ($val->tipe_transaksi == 1) {
						$arr_data[$key+1]['penerimaan'] = number_format($val->harga_total,2,",",".");
						$arr_data[$key+1]['pengeluaran'] = '0,00';
						$in_raw = $val->harga_total;
						$out_raw = 0;
					}else{
						$arr_data[$key+1]['penerimaan'] = '0,00';
						$arr_data[$key+1]['pengeluaran'] = number_format($val->harga_total,2,",",".");
						$in_raw = 0;
						$out_raw = $val->harga_total;
					}
					
					//saldo
					if ($saldo_awal == 0) {
						$saldo_akhir += (int)$saldo_awal + (int)$in_raw - (int)$out_raw;
					}else{
						$saldo_akhir += (int)$saldo_akhir + (int)$in_raw - (int)$out_raw;
					}
					
					//set saldo awal to 0
					$saldo_awal = 0;
					$arr_data[$key+1]['saldo_akhir'] = (int)$saldo_akhir;
				}
			}
			else
			{
				//get detail laporan
				$get_lap_header = $this->db->query("select * from tbl_lap_bku where bulan = '".$value->month_raw."' and tahun = '".$value->year_raw."' and is_kunci = '1'")->row();

				$query = $this->lap->get_detail_laporan($value->month_raw, $tahun, $get_lap_header->kode);
				// get saldo awal bulan terpilih
				$query_saldo = $this->db->query("select * from tbl_lap_bku where bulan = '".$value->month_raw."' tahun = '".$value->year_raw."' and is_kunci = '1'");
				$saldo_awal += (int)$query_saldo->saldo_awal;

				//assign satu row array untuk saldo awal
				$arr_data[0]['tanggal'] = date('d-m-Y', strtotime($value['year_raw'].'-'.$value['month_raw'].'-01'));
				$arr_data[0]['kode'] = '-';
				$arr_data[0]['keterangan'] = 'Saldo Awal';
				$arr_data[0]['penerimaan'] = '-';
				$arr_data[0]['pengeluaran'] = '-';
				$arr_data[0]['saldo_akhir'] = $saldo_awal;

				foreach ($query as $key => $val) {
					$arr_data[$key+1]['tanggal'] = date('d-m-Y', strtotime($val->tanggal));
					$arr_data[$key+1]['kode'] = $val->kode_akun_in_text;
					if ($val->tipe_transaksi == 1) {
						$arr_data[$key+1]['butki'] = $val->id_in;
					}else{
						$arr_data[$key+1]['butki'] = $val->id_out;
					}
					
					$arr_data[$key+1]['keterangan'] = $val->keterangan;
					
					if ($val->tipe_transaksi == 1) {
						$arr_data[$key+1]['penerimaan'] = number_format($val->harga_total,2,",",".");
						$arr_data[$key+1]['pengeluaran'] = '0,00';
						$in_raw = $val->harga_total;
						$out_raw = 0;
					}else{
						$arr_data[$key+1]['penerimaan'] = '0,00';
						$arr_data[$key+1]['pengeluaran'] = number_format($val->harga_total,2,",",".");
						$in_raw = 0;
						$out_raw = $val->harga_total;
					}
					
					//saldo
					if ($saldo_awal == 0) {
						$saldo_akhir += (int)$saldo_awal + (int)$in_raw - (int)$out_raw;
					}else{
						$saldo_akhir += (int)$saldo_akhir + (int)$in_raw - (int)$out_raw;
					}
					
					//set saldo awal to 0
					$saldo_awal = 0;
					$arr_data[$key+1]['saldo_akhir'] = (int)$saldo_akhir;
				}
			}
		}
		
		$txtPeriode = (count($arr_bulan) > 1) ? $periode1.' s/d '.$periode2 : $periode1;

		$data = array(
			'title' => 'SMP Darul Ulum Surabaya',
			'data_user' => $data_user,
			'hasil_data' => $arr_data,
			'arr_bulan' => $this->bulan_indo(),
			'arr_hari' => $this->hari_indo(),
			'periode' => $txtPeriode,
			// 'hasil_footer' => $query_footer,
			'bln_awal' => $bln_awal,
			'bln_akhir' => $bln_akhir,
			'tahun' => $tahun
		);

	    $html = $this->load->view('view_lap_bku_cetak', $data, true);
	    
	    $filename = 'laporan_bku_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
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
