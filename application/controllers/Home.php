<?php
// defined('BASEPATH ') OR exit('No direct script access allowed');
define('ADMINISTRATOR', '1', true);
define('TATAUSAHA', '2', true);
define('KEUANGAN', '3', true);
define('KEPSEK', '4', true);
define('GURU', '5', true);
class Home extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//cek apablia session kosong
		if ($this->session->userdata('username') === null) {
			//direct ke controller login
			redirect('login');
		}

		$this->load->model('Mod_home');
		//profil data
		$this->load->model('profil/mod_profil','prof');
	}

	public function index()
	{	
		$id_user = $this->session->userdata('id_user');
		$data_dashboard = [];

		if ($this->session->userdata('id_level_user') == ADMINISTRATOR) {
			$query = $this->prof->get_detail_pengguna($id_user);
			$data_dashboard = '';
			$component = null;
		} else if ($this->session->userdata('id_level_user') == TATAUSAHA) {
			$query = $this->prof->get_detail_pengguna($id_user);
			$data_dashboard = [
				'jumlah_guru' => $this->prof->get_jumlah_guru(),
				'jumlah_karyawan' => $this->prof->get_jumlah_karyawan()
			];
			$component = 'dashboard_guru';
		} else if ($this->session->userdata('id_level_user') == KEUANGAN) {
			$query = $this->prof->get_detail_pengguna($id_user);
			$data_dashboard = [
				'jumlah_guru' => $this->prof->get_jumlah_guru(),
				'jumlah_karyawan' => $this->prof->get_jumlah_karyawan()
			];
			$component = 'dashboard_guru';
		} else if ($this->session->userdata('id_level_user') == KEPSEK) {
			$query = $this->prof->get_detail_pengguna($id_user);
			$data_dashboard = [
				'jumlah_guru' => $this->prof->get_jumlah_guru()
			];
			$component = 'dashboard_user';
		} else if ($this->session->userdata('id_level_user') == GURU) {
			$query = $this->prof->get_detail_pegawai($id_user);
			$data_dashboard = [
				'detail_guru' => $this->prof->get_detail_guru($id_user)
			];
			$component = 'dashboard_user';
		}
		
		$data = array(
			'title' => 'SMP DARUL ULUM SURABAYA',
			'data_user' => $query,
			'data_dashboard' => $data_dashboard,
			'component' => $component
			// 'counter_barang' => $count_barang,
			// 'counter_stok' => $count_stok,
			// 'counter_supplier' => $count_supplier,
			// 'counter_pembelian' => $count_pembelian,
			// 'counter_user' => $count_user,
			// 'counter_user_level' => $count_user_level,
			// 'counter_borongan' => $count_borongan,
			// 'counter_pengambilan' => $count_pengambilan,
			// 'counter_order' => $count_trans_order,
			// 'counter_order_detail' => $count_order_detail,
			// 'counter_beli' => $count_trans_beli,
			// 'counter_beli_detail' => $count_beli_detail,
			// 'counter_masuk' => $count_trans_masuk,
			// 'counter_masuk_detail' => $count_masuk_detail,
			// 'counter_keluar' => $count_trans_keluar,
			// 'counter_keluar_detail' => $count_keluar_detail,
			// 'qty_notif' => $jumlah_notif,
			// 'isi_notif' => $notif,
		);

		$content = [
			'modal' => false,
			'js'	=> 'dashboard/jsDashboard',
			'css'	=> false,
			'view'	=> 'dashboard/view_list_dashboard'
		];

		$this->template_view->load_view($content, $data);
	}


	public function oops()
	{	
		$this->load->view('login/view_404');
	}

}
