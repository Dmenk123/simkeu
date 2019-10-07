<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Borongan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//cek apablia session kosong
		if ($this->session->userdata('username') === null) {
			//direct ke controller login
			redirect('login');
		}elseif ($this->session->userdata('id_level_user') != '1' && $this->session->userdata('id_level_user') != '2') {
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
		$this->load->model('mod_borongan','bor');
		//profil data
		$this->load->model('profil/mod_profil','prof');
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
				'content'=>'view_list_borongan',
				'css'=>'cssBorongan',
				'modal'=>'modalBorongan',
				'js'=>'jsBorongan',
				'title' => 'PT.Surya Putra Barutama',
				'data_user' => $query,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}elseif ($this->session->userdata('id_level_user') == 2) {
			$data = array(
				'content'=>'view_list_borongan_manager',
				'css'=>'cssBorongan',
				'modal'=>'modalBorongan',
				'js'=>'jsBorongan',
				'title' => 'PT.Surya Putra Barutama',
				'data_user' => $query,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function borongan_list()
	{
		//membuat format data json untuk ajax		
		$list = $this->bor->get_datatables();
		$data = array();
		$no =$_POST['start'];
		if ($this->session->userdata('id_level_user') == 1) {
			foreach ($list as $listBorongan) {
				$link_detail = site_url('borongan/borongan_detail/').$listBorongan->id_borongan;
				$no++;
				$row = array();
				//loop value tabel db
				$row[] = $listBorongan->id_borongan;
				$row[] = $listBorongan->nama_borongan;
				$row[] = $listBorongan->alamat_borongan;
				$row[] = $listBorongan->keterangan_borongan;
				$row[] = $listBorongan->telp_borongan;
				$row[] = $listBorongan->status;

				//add html for action
				$row[] = '<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Borongan Detail" id="btn_detail" onclick=""><i class="glyphicon glyphicon-info-sign"></i> Detail</a>
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editBorongan('."'".$listBorongan->id_borongan."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteBorongan('."'".$listBorongan->id_borongan."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

				$data[] = $row;
			}//end loop
		}elseif ($this->session->userdata('id_level_user') == 2) {
			foreach ($list as $listBorongan) {
				$link_detail = site_url('borongan/borongan_detail/').$listBorongan->id_borongan;
				$no++;
				$row = array();
				//loop value tabel db
				$row[] = $listBorongan->id_borongan;
				$row[] = $listBorongan->nama_borongan;
				$row[] = $listBorongan->alamat_borongan;
				$row[] = $listBorongan->keterangan_borongan;
				$row[] = $listBorongan->telp_borongan;
				$row[] = $listBorongan->status;

				//add html for action
				$row[] = '<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Borongan Detail" id="btn_detail" onclick=""><i class="glyphicon glyphicon-info-sign"></i> Detail</a>
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editBorongan('."'".$listBorongan->id_borongan."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';

				$data[] = $row;
			}//end loop
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->bor->count_all(),
						"recordsFiltered" => $this->bor->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function personil_list()
	{
		//membuat format data json untuk ajax		
		//ambil uri segment dari borongan/personil_list/id
		$id = $this->uri->segment(3);
		$list = $this->bor->get_datatables_personil($id);
		$data = array();
		$no =$_POST['start'];
		if ($this->session->userdata('id_level_user') == 1) {
			foreach ($list as $listPersonil) {
				$no++;
				$row = array();
				//loop value tabel db
				$row[] = $no;
				$row[] = $listPersonil->nama_borongan_detail;
				$row[] = $listPersonil->alamat_borongan_detail;
				$row[] = $listPersonil->telp_borongan_detail;
				$row[] = $listPersonil->status;

				//add html for action
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editPersonil('."'".$listPersonil->id_borongan_detail."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deletePersonil('."'".$listPersonil->id_borongan_detail."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

				$data[] = $row;
			}//end loop
		}elseif ($this->session->userdata('id_level_user') == 2) {
			foreach ($list as $listPersonil) {
				$no++;
				$row = array();
				//loop value tabel db
				$row[] = $no;
				$row[] = $listPersonil->nama_borongan_detail;
				$row[] = $listPersonil->alamat_borongan_detail;
				$row[] = $listPersonil->telp_borongan_detail;
				$row[] = $listPersonil->status;

				//add html for action
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editPersonil('."'".$listPersonil->id_borongan_detail."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';

				$data[] = $row;
			}//end loop
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->bor->count_all_personil(),
						"recordsFiltered" => $this->bor->count_filtered_personil(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function edit_borongan($id)
	{
		$data = $this->bor->get_by_id($id);
		echo json_encode($data);
	}

	public function edit_personil($id)
	{
		$data = $this->bor->get_by_id_personil($id);
		echo json_encode($data);
	}

	public function add_borongan()
	{
		$this->_validate();
		$initiated_date = date('Y-m-d H:i:s');
		$data = array(			
				'id_borongan' => $this->bor->getKodeBorongan(),
				'nama_borongan' => trim(strtoupper($this->input->post('namaBorongan'))),
				'alamat_borongan' => trim(strtoupper($this->input->post('alamatBorongan'))),
				'keterangan_borongan' => trim(strtoupper($this->input->post('keteranganBorongan'))),
				'telp_borongan' => $this->input->post('telpBorongan'),
				'status' => $this->input->post('statusBorongan'),
				'timestamp' => $initiated_date, 
			);
		$insert = $this->bor->save($data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_tambah" => 'Master Borongan Berhasil ditambahkan',
			));
	}

	public function add_personil()
	{
		$this->_validate2();
		$initiated_date = date('Y-m-d H:i:s');
		$data = array(	
				'id_borongan' => trim(strtoupper($this->input->post('idPersonil'))),		
				'nama_borongan_detail' => trim(strtoupper($this->input->post('namaPersonil'))),
				'alamat_borongan_detail' => trim(strtoupper($this->input->post('alamatPersonil'))),
				'telp_borongan_detail' => $this->input->post('telpPersonil'),
				'status' => $this->input->post('statusPersonil'),
				'timestamp' => $initiated_date, 
			);
		$insert = $this->bor->save_personil($data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_tambah" => 'Master Personil Borongan Berhasil ditambahkan',
			));
	}

	public function update_borongan()
	{
		$initiated_date = date('Y-m-d H:i:s');
		$this->_validate();
		$id = $this->input->post('id');
		$data = array(
				'nama_borongan' => trim(strtoupper($this->input->post('namaBorongan'))),
				'alamat_borongan' => trim(strtoupper($this->input->post('alamatBorongan'))),
				'keterangan_borongan' => trim(strtoupper($this->input->post('keteranganBorongan'))),
				'telp_borongan' => $this->input->post('telpBorongan'),
				'status' => $this->input->post('statusBorongan'),
				'timestamp' => $initiated_date,
			);
		$this->bor->update(array('id_borongan' => $this->input->post('id')), $data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_update" => 'Master Borongan No.'.$id.' Berhasil diupdate'
			));
	}

	public function update_personil()
	{
		$initiated_date = date('Y-m-d H:i:s');
		$this->_validate2();
		$id = $this->input->post('idPersonilDetail');
		$data = array(
				'nama_borongan_detail' => trim(strtoupper($this->input->post('namaPersonil'))),
				'alamat_borongan_detail' => trim(strtoupper($this->input->post('alamatPersonil'))),				
				'telp_borongan_detail' => $this->input->post('telpPersonil'),
				'status' => $this->input->post('statusPersonil'),
				'timestamp' => $initiated_date,
			);
		$this->bor->update_data_personil(array('id_borongan_detail' => $id), $data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_update" => 'Master Personil Borongan No.'.$id.' Berhasil diupdate'
			));
	}

	public function delete_borongan($id)
	{
		$this->bor->delete_by_id($id);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Master Pemborong No.'.$id.' Berhasil dihapus'
			));
	}

	public function delete_personil($id)
	{
		$this->bor->delete_by_id_personil($id);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Master Personil Borongan No.'.$id.' Berhasil dihapus'
			));
	}

	public function borongan_detail()
	{
		$this->load->model('pesan/mod_pesan','psn');
		$id_user = $this->session->userdata('id_user'); 
		$query_user = $this->prof->get_detail_pengguna($id_user);
		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$id_borongan = $this->uri->segment(3); 
		$query_header = $this->bor->get_detail_header($id_borongan);
		$query = $this->bor->get_detail_borongan($id_borongan);
		if ($this->session->userdata('id_level_user') == 1) {
			$data = array(
				'css'=>'cssBorongan',
				'js'=>'jsBorongan',
				'content' => 'view_detail_borongan',
				'title' => 'PT.Surya Putra Barutama',
				'modal'=>'modalPersonil',
				'hasil_header' => $query_header,
				'data_user' => $query_user,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}elseif ($this->session->userdata('id_level_user') == 2) {
			$data = array(
				'css'=>'cssBorongan',
				'js'=>'jsBorongan',
				'content' => 'view_detail_borongan_manager',
				'title' => 'PT.Surya Putra Barutama',
				'modal'=>'modalPersonil',
				'hasil_header' => $query_header,
				'data_user' => $query_user,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}	
		$this->load->view('view_home',$data);
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('namaBorongan') == '') {
			$data['inputerror'][] = 'namaBorongan';
            $data['error_string'][] = 'Nama Borongan is required';
            $data['status'] = FALSE;
		}

		if($this->input->post('alamatBorongan') == '')
		{
			$data['inputerror'][] = 'alamatBorongan';
			$data['error_string'][] = 'Alamat Borongan is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('keteranganBorongan') == '')
		{
			$data['inputerror'][] = 'keteranganBorongan';
			$data['error_string'][] = 'Keterangan Borongan is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('telpBorongan') == '')
		{
			$data['inputerror'][] = 'telpBorongan';
			$data['error_string'][] = 'Telepon Borongan is required';
			$data['status'] = FALSE;
		}

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
	}

	private function _validate2()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('namaPersonil') == '') {
			$data['inputerror'][] = 'namaPersonil';
            $data['error_string'][] = 'Nama Personil is required';
            $data['status'] = FALSE;
		}

		if($this->input->post('alamatPersonil') == '')
		{
			$data['inputerror'][] = 'alamatPersonil';
			$data['error_string'][] = 'Alamat Personil is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('telpPersonil') == '')
		{
			$data['inputerror'][] = 'telpPersonil';
			$data['error_string'][] = 'Telp Personil is required';
			$data['status'] = FALSE;
		}

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
	}
}
