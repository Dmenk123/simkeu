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
		//var_dump($keluar);
		//pemulusan pertama
		$s1t = $this->lib_forecast->smooth1($keluar, $alpha);
		//pemulusan kedua
		$s2t = $this->lib_forecast->smooth2($s1t, $alpha);
		//slope at
		$at = $this->lib_forecast->slope_at($s1t, $s2t);
		//slope bt
		$bt = $this->lib_forecast->slope_bt($s1t, $s2t, $alpha);
		//peramalan
		$ft = $this->lib_forecast->forecast($at, $bt);
		$ft_grafik = $this->lib_forecast->forecast($at, $bt);
		//absolute error
		$ae = $this->lib_forecast->absolute_error($keluar, $ft);
		//mean absolute error
		$mad = round((array_sum($keluar) - array_sum($ae)) / (count($ae) - 1), 2);
		//square error
		$sqe = $this->lib_forecast->square_error($ae);
		//mean square error
		//fungsi @ untk mengecek kesalahan aritmatik(ex: disini kesalahan apabila pembagian dengan nilai 0)
		$tes = count($ae);
		$mse = @(round(array_sum($sqe) / (count($ae) - 1), 2));
		if(false === $mse) {
		  $mse = null;
		}
		//absolute precentage error
		$ape = @($this->lib_forecast->absolute_precentage_error($keluar, $ft));
		if(false === $ape) {
		  $ape = null;
		}
		//mean absolute precentage error
		$mape = @(round(array_sum($ape) / (count($ae) - 1), 2));
		if(false === $mape) {
		  $mape = null;
		}
		//forecast ft+1
		$peramalan = round(end($ft) * 1);

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
