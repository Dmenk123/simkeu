<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forecasting extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//cek apablia session kosong
		if ($this->session->userdata('username') === null) {
			//direct ke controller login
			redirect('login');
		}elseif ($this->session->userdata('id_level_user') != '1' && $this->session->userdata('id_level_user') != '2' && $this->session->userdata('id_level_user') != '3') {
			redirect('home/oops');
		}
		$this->load->model('mod_forecasting','m_ramal');
		$this->load->model('pengguna/mod_pengguna');
		//pesan stok dibawah rop
		$this->load->model('Mod_home');
		$barang = $this->Mod_home->get_barang();

		foreach ($barang as $key) {
			if ($key->stok_barang < $key->rop_barang) {
				$this->session->set_flashdata('cek_stok', 'Terdapat Stok Barang dibawah nilai Reorder Point, Mohon di cek ulang / melakukan permintaan');
			}
		}
		$this->load->model('pesan/mod_pesan','psn');
	}

	public function index()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query = $this->mod_pengguna->get_detail_user($id_user);
		$jumlah_notif = $this->psn->notif_count($id_user);
		$notif = $this->psn->get_notifikasi($id_user);

		if ($this->session->userdata('id_level_user') == 1 || $this->session->userdata('id_level_user') == 2 || $this->session->userdata('id_level_user') == 3 ) {
			$data = array(
				'content'=>'view_list_forecasting',
				'css'=>'cssForecasting',
				'modal'=>'modalForecasting',
				'js'=>'jsForecasting',
				'title' => 'PT.Surya Putra Barutama',
				'data_user' => $query,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function list_peramalan()
	{
		$list = $this->m_ramal->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $listPeramalan) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $listPeramalan->id_forecast;
			$row[] = $listPeramalan->nama_barang;
			$row[] = $listPeramalan->tgl_forecast;
			$row[] = $listPeramalan->alpha_forecast;
			$row[] = $listPeramalan->hasil_forecast;
			$row[] = $listPeramalan->mad_forecast;
			$row[] = $listPeramalan->mae_forecast;
			$row[] = $listPeramalan->mape_forecast;
			//add html for action button
			$row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deletePeramalan('."'".$listPeramalan->id_forecast."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_ramal->count_all(),
						"recordsFiltered" => $this->m_ramal->count_filtered(),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function forecasting_step_1()
	{
		$id_user = $this->session->userdata('id_user');
		$jumlah_notif = $this->psn->notif_count($id_user);
		$notif = $this->psn->get_notifikasi($id_user);
		$query = $this->mod_pengguna->get_detail_user($id_user);

		if ($this->session->userdata('id_level_user') == 1 || $this->session->userdata('id_level_user') == 2 || $this->session->userdata('id_level_user') == 3 ) {
			$data = array(
				'content'=>'view_forecasting',
				'css'=>'cssForecasting',
				'modal'=>'modalForecasting',
				'js'=>'jsForecasting',
				'title' => 'PT.Surya Putra Barutama',
				'data_user' => $query,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function forecasting_step_2()
	{
		$this->load->library('Lib_forecast');
		$this->load->model('rop/mod_rop','m_rop');

		$id_user = $this->session->userdata('id_user');
		$jumlah_notif = $this->psn->notif_count($id_user);
		$notif = $this->psn->get_notifikasi($id_user);
		$query_user = $this->mod_pengguna->get_detail_user($id_user);
		$bulan = $this->input->post('blnRamal');
		$period_tampil = $this->input->post('periodeForecast');

		//convert dateType bulan (Ym) to (Ymt) and add -1 month
		$bulan_index = date('Y-m-t', strtotime('-1 month', strtotime($bulan)));
		$id_barang = $this->input->post('barangRamal');

		$query_barang = $this->m_rop->get_nama_barang($id_barang);
		$query = $this->m_rop->get_pengeluaran_barang($id_barang, $bulan_index, $period_tampil);
		//echo var_dump($id_barang, $bulan_index, $query, $query_barang);

		//convert array=>stdObjectClass to array, parameter true = assosiative array
		//convert array stdObjectClass to json, then decode and save to array
		$stdClass = json_decode(json_encode($query),true);
		//get specific array value and save to new array variable
		foreach ($stdClass as $val) {
			$keluar[] = $val['total'];
		}

		// hitung peramalan menggunakan rumus pada library dan simpan pada variabel
		$hasil1 = $this->lib_forecast->peramalan_des($keluar, '0.1');
		$hasil2 = $this->lib_forecast->peramalan_des($keluar, '0.2');
		$hasil3 = $this->lib_forecast->peramalan_des($keluar, '0.3');
		$hasil4 = $this->lib_forecast->peramalan_des($keluar, '0.4');
		$hasil5 = $this->lib_forecast->peramalan_des($keluar, '0.5');
		$hasil6 = $this->lib_forecast->peramalan_des($keluar, '0.6');
		$hasil7 = $this->lib_forecast->peramalan_des($keluar, '0.7');
		$hasil8 = $this->lib_forecast->peramalan_des($keluar, '0.8');
		$hasil9 = $this->lib_forecast->peramalan_des($keluar, '0.9');
		//echo var_dump($hasil1);

		//simpan nilai mape pada array
		$hasil_alpha = array(
			'0.1' => $hasil1['mape'],
			'0.2' => $hasil2['mape'],
			'0.3' => $hasil3['mape'],
			'0.4' => $hasil4['mape'],
			'0.5' => $hasil5['mape'],
			'0.6' => $hasil6['mape'],
			'0.7' => $hasil7['mape'],
			'0.8' => $hasil8['mape'],
			'0.9' => $hasil9['mape'],
		);
		//cari nilai min pada array
		$hasil_alpha_min = min($hasil_alpha);
		//isi variabel = "", solusi variabel alpha tidak terbaca ketika data tidak ada transaksi
		$nilai_min_alpha = "";
		//loop jika kondisi true, simpan variabel yang berisi nilai key
		while ($cari_min_alpha = current($hasil_alpha)) {
		    if ($cari_min_alpha == $hasil_alpha_min) {
		    	//simpan nili key array pada varabel
		       	$nilai_min_alpha = key($hasil_alpha);
		    }
		    next($hasil_alpha);
		}

		if ($this->session->userdata('id_level_user') == 1 || $this->session->userdata('id_level_user') == 2 || $this->session->userdata('id_level_user') == 3 ) {
			$data = array(
				'css'=>'cssForecasting',
				'js'=>'jsForecasting',
				'content'=>'view_forecasting_2',
				'modal'=>'modalForecasting',
				'title' => 'PT.Surya Putra Barutama',
				'bulan_index' => $bulan_index,
				'id_barang' => $id_barang,
				'periodeForecast' => $period_tampil,
				'bulan' => $bulan,
				'hasil_data' => $query,
				'hasil_barang' => $query_barang,
				'data_user' => $query_user,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
				'alpha' => $nilai_min_alpha,
			);
		}
		$this->load->view('view_home',$data);
	}

	public function peramalan()
	{
		$this->load->model('rop/mod_rop','m_rop');
		$this->load->library('Lib_forecast');
		// get data from ajax object
		$alpha = $this->input->post('alpha');
		$bulan_index = $this->input->post('blnIndex');
		$id_barang = $this->input->post('idBarang');
		$period_tampil = $this->input->post('periodeForecast');

		$queryPemakaian = $this->m_rop->get_pengeluaran_barang($id_barang, $bulan_index, $period_tampil);
		//convert array=>stdObjectClass to array, parameter true = assosiative array
		//convert array stdObjectClass to json, then decode and save to array
		$stdClass = json_decode(json_encode($queryPemakaian),true);
		//get specific array value and save to new array variable
		foreach ($stdClass as $val) {
			$keluar[] = $val['total'];
		}
		
		$hasil = $this->lib_forecast->peramalan_des($keluar, $alpha);

		$s1t = $hasil['s1t'];
		$s2t = $hasil['s2t'];
		$at = $hasil['at'];
		$bt = $hasil['bt'];
		$ft = $hasil['ft'];
		$ft_grafik = $hasil['ft_grafik'];
		$ae = $hasil['ae'];
		$mad = $hasil['mad'];
		$sqe = $hasil['sqe'];
		$mse = $hasil['mse'];
		$ape = $hasil['ape'];
		$mape = $hasil['mape'];
		$peramalan = $hasil['peramalan'];

		// add +1month for periode grafik
		$bulan_forecast = date('Y-m-t', strtotime('+1 month', strtotime($bulan_index)));
		//bulan end berarti bulan untuk ft+1, +1day = last month 31/30 days + 1 day
		$bulan_end = date('Y-m-t', strtotime('+1 day', strtotime($bulan_index)));
		$query_grafik = $this->m_ramal->get_pengeluaran_barang_grafik($id_barang, $bulan_forecast, $bulan_end, $period_tampil);

		//for array grafik		
		$md_grafik = array();
		//$md_grafik[0] = $query_grafik[0]->md;
		foreach ($query_grafik as $key) {
			$md_grafik[] = $key->md;
		}

		$qty_grafik = array();
		foreach ($query_grafik as $key) {
			$qty_grafik[] = $key->total;
		}

		//remove_first element array and store to variable
		$ft_grafik1 = array_shift($ft_grafik);

		$data = array(
			'alpha_min' => $alpha,
			'real' => $keluar,
			's1t' => $s1t,
			's2t' => $s2t,
			'at' => $at,
			'bt' => $bt,
			'ft' => $ft,
			'ae' => $ae,
			'mad' => $mad,
			'sqe' => $sqe,
			'mse' => $mse,
			'ape' => $ape,
			'mape' => $mape,
			'peramalan' => $peramalan,
			'md_grafik' => $md_grafik,
			'ft_grafik' => $ft_grafik,
			'qty_grafik' => $qty_grafik,
		);

		echo json_encode($data);
	}

	public function simpan_data()
	{
		//for table trans_keluar
		$data = array(
				'id_barang' => $this->input->post('idBarang'),
				'id_satuan' => $this->input->post('idSatuan'),
				'alpha_forecast' => $this->input->post('alpha'),
				'tgl_forecast' => $this->input->post('tglRamal'),
				'hasil_forecast' => $this->input->post('hasilRamal'),
				'mad_forecast' => $this->input->post('hasilMad'),
				'mae_forecast' => $this->input->post('hasilMse'),
				'mape_forecast' => $this->input->post('hasilMape'), 
			);

		$insert = $this->m_ramal->save_peramalan($data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_simpan" => 'Data peramalan berhasil ditambahkan'
			));
	}

	public function delete_peramalan($id)
	{
		$this->m_ramal->delete_by_id($id);

		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Peramalan Barang No.'.$id.' Berhasil dihapus'
		));
	}

}
