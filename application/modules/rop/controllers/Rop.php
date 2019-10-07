<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rop extends CI_Controller {

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
		$this->load->model('mod_rop','m_rop');
		$this->load->model('pengguna/mod_pengguna');
		$this->load->model('pesan/mod_pesan','psn');
		//pesan stok dibawah rop
		$this->load->model('Mod_home');
		$barang = $this->Mod_home->get_barang();

		foreach ($barang as $key) {
			if ($key->stok_barang < $key->rop_barang) {
				$this->session->set_flashdata('cek_stok', 'Terdapat Stok Barang dibawah nilai Reorder Point, Mohon di cek ulang / melakukan permintaan');
			}
		}
	}

	public function index()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query = $this->mod_pengguna->get_detail_user($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		if ($this->session->userdata('id_level_user') == 1 || $this->session->userdata('id_level_user') == 2 || $this->session->userdata('id_level_user') == 3 ) {
			$data = array(
				'content'=>'view_rop',
				'css'=>'cssRop',
				'modal'=>'modalRop',
				'js'=>'jsRop',
				'title' => 'PT.Surya Putra Barutama',
				'data_user' => $query,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function rop_step_2()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query_user = $this->mod_pengguna->get_detail_user($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$bulan = $this->input->post('tanggalRop');
		$period_tampil = $this->input->post('periodeRop');

		//convert dateType bulan (Ym) to (Ymt) and add -1 month
		$bulan_index = date('Y-m-t', strtotime('-1 month', strtotime($bulan)));
		$id_barang = $this->input->post('barangRop');

		$query_barang = $this->m_rop->get_nama_barang($id_barang);
		$query = $this->m_rop->get_pengeluaran_barang($id_barang, $bulan_index, $period_tampil);
		//echo var_dump($id_barang, $bulan_index, $query, $query_barang);

		if ($this->session->userdata('id_level_user') == 1 || $this->session->userdata('id_level_user') == 2 || $this->session->userdata('id_level_user') == 3 ) {
			$data = array(
				'css'=>'cssRop',
				'js'=>'jsRop',
				'content'=>'view_rop_2',
				'modal'=>'modalRop',
				'title' => 'PT.Surya Putra Barutama',
				'bulan' => $bulan,
				'period' => $period_tampil,
				'hasil_data' => $query,
				'hasil_barang' => $query_barang,
				'data_user' => $query_user,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}
		$this->load->view('view_home',$data);
	}

	public function rop_step_3()
	{
		$this->load->library('Lib_rop');
		$id_user = $this->session->userdata('id_user'); 
		$query_user = $this->mod_pengguna->get_detail_user($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$bulan = $this->uri->segment(3);
		$period_tampil = $this->uri->segment(4);
		//convert dateType bulan (Ym) to (Ymd) and add -1 month
		$bulan_index = date('Y-m-t', strtotime('-1 month', strtotime($bulan)));
		$id_barang = $this->input->post('idBarang');

		$query_barang = $this->m_rop->get_nama_barang($id_barang);
		$query = $this->m_rop->get_pengeluaran_barang($id_barang, $bulan_index, $period_tampil);

		//convert array=>stdObjectClass to array, parameter true = assosiative array
		//convert array stdObjectClass to json, then decode and save to array
		$stdClass = json_decode(json_encode($query),true);
		//get specific array value and save to new array variable
		foreach ($stdClass as $val) {
			$keluar[] = $val['total'];
		}

		//standar deviasi
		$stdev = $this->lib_rop->standardDeviation($keluar);

		//rata2 (mean)
		$rata2 = $this->lib_rop->mean($keluar);

		//lead time
		$hari = $this->input->post('leadTime');
		$lt = $this->lib_rop->leadTime($hari);

		//distribusi normal (Z)
		$sl = $this->input->post('serviceLevel');
		$persen = $sl/100;
		$z = $this->lib_rop->NormSInv($persen);

		//Safety Stock (SS)
		$ss = $this->lib_rop->safetyStock($z, $stdev, $lt);

		//Reorder Point(rop)
		$rop = $this->lib_rop->rop($rata2, $lt, $ss);

		
		if ($this->session->userdata('id_level_user') == 1 || $this->session->userdata('id_level_user') == 2 || $this->session->userdata('id_level_user') == 3 ) {
			$data = array(
				'css'=>'cssRop',
				'js'=>'jsRop',
				'content'=>'view_rop_3',
				'modal'=>'modalRop',
				'title' => 'PT.Surya Putra Barutama',
				'stdev' => $stdev,
				'lt' => $lt,
				'rata2' => $rata2,
				'z' => $z,
				'ss' => $ss,
				'rop' => $rop,
				'hasil_data' => $query,
				'hasil_barang' => $query_barang,
				'data_user' => $query_user,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}
		$this->load->view('view_home',$data);
	}

	public function suggest_barang()
	{
		$barang = [];
		if(!empty($this->input->get("q"))){
			$key = $_GET['q'];
			$query = $this->m_rop->lookup_data_barang($key);
		}else{
			$query = $this->m_rop->lookup_data_barang();
		}
		
		foreach ($query as $row) {
			$barang[] = array(
						'id' => $row->id_barang,
						'text' => $row->nama_barang,
					);
		}
		echo json_encode($barang);
	}

	public function ss_update()
	{
		$initiated_date = date('Y-m-d H:i:s');
		$data = array(
				'ss_barang' => $this->input->post('ssBarang'),
				'timestamp' => $initiated_date,
			);
		$this->m_rop->update_master(array('id_barang' => $this->input->post('idBarang')), $data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_update" => 'SS pada Master Barang Berhasil diupdate',
			));
	}

	public function rop_update()
	{
		$initiated_date = date('Y-m-d H:i:s');
		$data = array(
				'rop_barang' => $this->input->post('ropBarang'),
				'timestamp' => $initiated_date,
			);
		$this->m_rop->update_master(array('id_barang' => $this->input->post('idBarangRop')), $data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_update" => 'ROP pada Master Barang Berhasil diupdate',
			));
	}

}
