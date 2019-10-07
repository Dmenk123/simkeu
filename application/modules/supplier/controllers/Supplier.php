<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//cek apablia session kosong
		if ($this->session->userdata('username') === null) {
			//direct ke controller login
			redirect('login');
		}elseif ($this->session->userdata('id_level_user') != '1' && $this->session->userdata('id_level_user') != '4') {
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
		$this->load->model('mod_supplier','supl');
		$this->load->model('profil/mod_profil','prof');
		$this->load->model('pesan/mod_pesan','psn');
	}

	public function index()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query = $this->prof->get_detail_pengguna($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		if ($this->session->userdata('id_level_user') == 1) {
			$data = array(
				'content'=>'view_list_supplier',
				'css'=>'cssSupplier',
				'modal'=>'modalSupplier',
				'js'=>'jsSupplier',
				'title' => 'PT.Surya Putra Barutama',
				'data_user' => $query,
				'qty_notif' => $jumlah_notif,
				'isi_notif' => $notif,
			);
		}elseif ($this->session->userdata('id_level_user') == 4) {
			$data = array(
				'content'=>'view_list_supplier_pembelian',
				'css'=>'cssSupplier',
				'modal'=>'modalSupplier',
				'js'=>'jsSupplier',
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
		//membuat format data json untuk ajax		
		//ambil datatable dari model denga alias name=supl
		$list = $this->supl->get_datatables();
		$data = array();
		$no =$_POST['start'];
		if ($this->session->userdata('id_level_user') == 1) {
			foreach ($list as $listSupplier) {
				$no++;
				$row = array();
				//loop value tabel db
				$row[] = $listSupplier->id_supplier;
				$row[] = $listSupplier->nama_supplier;
				$row[] = $listSupplier->alamat_supplier;
				$row[] = $listSupplier->telp_supplier;
				$row[] = $listSupplier->status;

				//add html for action
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editSupplier('."'".$listSupplier->id_supplier."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteSupplier('."'".$listSupplier->id_supplier."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

				$data[] = $row;
			}//end loop
		}elseif ($this->session->userdata('id_level_user') == 4) {
			foreach ($list as $listSupplier) {
				$no++;
				$row = array();
				//loop value tabel db
				$row[] = $listSupplier->id_supplier;
				$row[] = $listSupplier->nama_supplier;
				$row[] = $listSupplier->alamat_supplier;
				$row[] = $listSupplier->telp_supplier;
				$row[] = $listSupplier->status;

				//add html for action
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editSupplier('."'".$listSupplier->id_supplier."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';

				$data[] = $row;
			}//end loop
		}	
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->supl->count_all(),
						"recordsFiltered" => $this->supl->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->supl->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{	
		$this->_validate();
		$initiated_date = date('Y-m-d H:i:s');
		$data = array(			
				'id_supplier' => $this->supl->getKodeSupplier(),
				'nama_supplier' => trim(strtoupper($this->input->post('namaSupplier'))),
				'alamat_supplier' => trim(strtoupper($this->input->post('alamatSupplier'))),
				'telp_supplier' => $this->input->post('telpSupplier'),
				'status' => $this->input->post('statusSupplier'),
				'timestamp' => $initiated_date, 
			);
		$insert = $this->supl->save($data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_tambah" => 'Master Supplier Berhasil ditambahkan',
			));
	}

	public function ajax_update()
	{
		$initiated_date = date('Y-m-d H:i:s');
		$this->_validate();
		$data = array(
				'nama_supplier' => trim(strtoupper($this->input->post('namaSupplier'))),
				'alamat_supplier' => $this->input->post('alamatSupplier'),
				'telp_supplier' => $this->input->post('telpSupplier'),
				'status' => $this->input->post('statusSupplier'),
				'timestamp' => $initiated_date,
			);
		$this->supl->update(array('id_supplier' => $this->input->post('id')), $data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_update" => 'Master Supplier Berhasil diupdate',
			));
	}

	public function ajax_delete($id)
	{
		$this->supl->delete_by_id($id);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Master Supplier No.'.$id.' Berhasil dihapus',
			));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('namaSupplier') == '') {
			$data['inputerror'][] = 'namaSupplier';
            $data['error_string'][] = 'Nama Supplier is required';
            $data['status'] = FALSE;
		}

		if($this->input->post('alamatSupplier') == '')
		{
			$data['inputerror'][] = 'alamatSupplier';
			$data['error_string'][] = 'Alamat Supplier is required';
			$data['status'] = FALSE;
		}

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
	}
}
