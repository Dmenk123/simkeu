<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//cek apablia session kosong
		if ($this->session->userdata('username') === null) {
			//direct ke controller login
			redirect('login');
		}elseif ($this->session->userdata('id_level_user') != '1' && $this->session->userdata('id_level_user') != '3') {
			redirect('home/oops');
		}

		$this->load->model('mod_barang','item');
		//profil data
		$this->load->model('profil/mod_profil','prof');
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
		$this->load->model('pesan/mod_pesan','psn');
		$id_user = $this->session->userdata('id_user'); 
		$query = $this->prof->get_detail_pengguna($id_user);
		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		if ($this->session->userdata('id_level_user') == 1) {
			$data = array(
				'content'=>'view_list_barang',
				'css'=>'cssBarang',
				'modal'=>'modalBarang',
				'js'=>'jsBarang',
				'title' => 'PT.Surya Putra Barutama',
				'data_user' => $query,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}else if (($this->session->userdata('id_level_user') == 3)) {
			$data = array(
				'content'=>'view_list_barang_gdg',
				'css'=>'cssBarang',
				'modal'=>'modalBarang',
				'js'=>'jsBarang',
				'title' => 'PT.Surya Putra Barutama',
				'data_user' => $query,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function ajax_list()
	{
		//ambil datatable dari model denga alias name=item
		$list = $this->item->get_datatables();
		$data = array();
		$no =$_POST['start'];
		if ($this->session->userdata('id_level_user') == 1) {
			foreach ($list as $listBarang) {
				$no++;
				$row = array();
				//loop value tabel db
				$row[] = $listBarang->id_barang;
				$row[] = $listBarang->nama_barang;
				$row[] = $listBarang->nama_satuan;
				$row[] = $listBarang->stok_barang;
				$row[] = $listBarang->ss_barang;
				$row[] = $listBarang->rop_barang;
				$row[] = $listBarang->keterangan_kategori;
				$row[] = $listBarang->status;

				//add html for action
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_barang('."'".$listBarang->id_barang."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_barang('."'".$listBarang->id_barang."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

				$data[] = $row;
			}//end loop
		}else if (($this->session->userdata('id_level_user') == 3)) {
			foreach ($list as $listBarang) {
				$no++;
				$row = array();
				//loop value tabel db
				$row[] = $listBarang->id_barang;
				$row[] = $listBarang->nama_barang;
				$row[] = $listBarang->nama_satuan;
				$row[] = $listBarang->stok_barang;
				$row[] = $listBarang->ss_barang;
				$row[] = $listBarang->rop_barang;
				$row[] = $listBarang->keterangan_kategori;
				$row[] = $listBarang->status;

				//add html for action
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_barang('."'".$listBarang->id_barang."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';

				$data[] = $row;
			}//end loop
		}
		
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->item->count_all(),
						"recordsFiltered" => $this->item->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->item->get_by_id($id);
		$data->level = $this->session->userdata('id_level_user');
		echo json_encode($data);
	}

	public function ajax_add()
	{
		//ambil nama dari input yg berisi id		
		$ambil_kategori = $this->item->ambil_kategori($this->input->post('kategoriBarang'));

		foreach ($ambil_kategori as $val) {
			$nama_kategori = $val->nama_kategori;
		}

		$this->_validate();
		$initiated_date = date('Y-m-d H:i:s');
		$data = array(			
				'id_barang' => $this->item->getKodeBarang($nama_kategori),
				'nama_barang' => trim(strtoupper($this->input->post('namaBarang'))),
				'id_satuan' => $this->input->post('satuanBarang'),
				'stok_awal' => $this->input->post('stokBarang'),
				'stok_barang' => $this->input->post('stokBarang'),
				'id_kategori' => $this->input->post('kategoriBarang'),
				'status' => $this->input->post('statusBarang'),
				'timestamp' => $initiated_date, 
			);
		$insert = $this->item->save($data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_tambah" => 'Master Barang Berhasil ditambahkan',
			));
	}

	public function ajax_update()
	{
		$initiated_date = date('Y-m-d H:i:s');
		$this->_validate();
		$data = array(
				'nama_barang' => trim(strtoupper($this->input->post('namaBarang'))),
				'id_satuan' => $this->input->post('satuanBarang'),
				'stok_awal' => $this->input->post('stokBarang'),
				'id_kategori' => $this->input->post('kategoriBarang'),
				'status' => $this->input->post('statusBarang'),
				'timestamp' => $initiated_date,
			);
		$this->item->update(array('id_barang' => $this->input->post('id')), $data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_update" => 'Master Barang Berhasil diupdate',
			));
	}

	public function ajax_delete($id)
	{
		$this->item->delete_by_id($id);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Master Barang No.'.$id.' Berhasil dihapus',
			));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('namaBarang') == '') {
			$data['inputerror'][] = 'namaBarang';
            $data['error_string'][] = 'Nama Barang is required';
            $data['status'] = FALSE;
		}

		if($this->input->post('satuanBarang') == '')
		{
			$data['inputerror'][] = 'satuanBarang';
			$data['error_string'][] = 'Please select Satuan';
			$data['status'] = FALSE;
		}

		if($this->input->post('satuanBarang') == '' )
		{
			$data['inputerror'][] = 'satuanBarang';
			$data['error_string'][] = 'Nama Barang is required';
			$data['status'] = FALSE;
		}

        if($this->input->post('kategoriBarang') == '')
		{
			$data['inputerror'][] = 'kategoriBarang';
			$data['error_string'][] = 'Please select Kategori';
			$data['status'] = FALSE;
		}
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
	}
}
