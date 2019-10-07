<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grafik_barang extends CI_Controller {

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

		//pesan stok dibawah rop
		$this->load->model('Mod_home');
		$barang = $this->Mod_home->get_barang();
			foreach ($barang as $key) {
				if ($key->stok_barang < $key->rop_barang) {
					$this->session->set_flashdata('cek_stok', 'Terdapat Stok Barang dibawah nilai Reorder Point, Mohon di cek ulang / melakukan permintaan');
				}
			}

		$this->load->model('mod_grafik_barang','m_grafik');
		$this->load->model('pengguna/mod_pengguna');
		$this->load->model('pesan/mod_pesan','psn');
	}

	public function index()
	{
		$id_user = $this->session->userdata('id_user');
		$query = $this->mod_pengguna->get_detail_user($id_user);
		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan
		$data = array(
			'content'=>'view_grafik_barang',
			'css'=>'cssGrafikBarang',
			'js'=>'jsGrafikBarang',
			'title' => 'PT.Surya Putra Barutama',
			'data_user' => $query,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
		);
		
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function grafik_step_2()
	{
		$this->load->model('rop/mod_rop','m_rop');

		$id_user = $this->session->userdata('id_user'); 
		$query_user = $this->mod_pengguna->get_detail_user($id_user);
		$bulan = $this->input->post('tanggalGrafik');
		$period_tampil = $this->input->post('periodeGrafik');

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$jenis_transaksi = $this->input->post('JenisGrafik');
		$transaksi = '';
		//convert dateType bulan (Ym) to (Ymd) and add +1 month
		$bulan_index = date('Y-m-t', strtotime('+0 month', strtotime($bulan)));

		$bulan_awal = date('Y-m-d', strtotime('-11 month', strtotime($bulan)));
		$bulan_akhir = date('Y-m-t', strtotime('+0 month', strtotime($bulan)));
		$id_barang = $this->input->post('barangGrafik');

		$query_barang = $this->m_rop->get_nama_barang($id_barang);
		if ($jenis_transaksi == 'trans_keluar') {
			$query = $this->m_rop->get_pengeluaran_barang($id_barang, $bulan_index, $period_tampil);
			$transaksi = "Pengeluaran";
		}else{
			$query = $this->m_grafik->get_penerimaan_barang($id_barang, $bulan_index, $period_tampil);
			$transaksi = "Penerimaan";
		}
		

		$nama_barang = "";
		foreach ($query_barang as $key) {
			$nama_barang = $key->nama_barang;
		}

		$md_grafik = array();
		foreach ($query as $key) {
			$md_grafik[] = $key->md;
		}

		$qty_grafik = array();
		foreach ($query as $key) {
			$qty_grafik[] = $key->total;
		}

		$data = array(
			'css'=>'cssGrafikBarang',
			'js'=>'jsGrafikBarang',
			'content'=>'view_grafik_barang_2',
			'title' => 'PT.Surya Putra Barutama',
			'bulan' => $bulan,
			'md_grafik' => json_encode($md_grafik),
			'qty_grafik' => json_encode($qty_grafik),
			'bulan_awal' => $bulan_awal,
			'bulan_akhir' => $bulan_akhir,
			'nama_barang' => $nama_barang,
			'transaksi' => $transaksi,
			'data_user' => $query_user,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
		);
		$this->load->view('view_home',$data);
	}


}
