<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbox extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//cek apablia session kosong
		if ($this->session->userdata('username') === null) {
			//direct ke controller login
			redirect('login');
		}

		//pesan stok dibawah rop
		$this->load->model('Mod_home');
		$barang = $this->Mod_home->get_barang();
			foreach ($barang as $key) {
				if ($key->stok_barang < $key->rop_barang) {
					$this->session->set_flashdata('cek_stok', 'Terdapat Stok Barang dibawah nilai Reorder Point, Mohon di cek ulang / melakukan permintaan');
				}
			}
		$this->load->model('pesan/mod_pesan','psn');
		$this->load->model('mod_inbox','ibx');
		$this->load->helper('fungsi');
		//profil data
		$this->load->model('profil/mod_profil','prof');
	}

	public function index()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query = $this->prof->get_detail_pengguna($id_user);
		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan
		
		$data = array(
			'content'=>'view_list_inbox',
			'css'=>'cssInbox',
			'modal'=>'modalInbox',
			'js'=>'jsInbox',
			'title' => 'PT.Surya Putra Barutama',
			'data_user' => $query,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
		);
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function inbox_list($idUser='')
	{
		//membuat format data json untuk ajax		
		$data = array();
		$no =$_POST['start'];
		$list = $this->ibx->get_datatables($idUser);
		foreach ($list as $listInbox) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = $listInbox->nama_lengkap_user;
			$row[] = $listInbox->subject_pesan;
			$row[] = $listInbox->isi_pesan;
			$row[] = $listInbox->dt_post;
			$row[] = $listInbox->dt_read;
			//add html for action
			$row[] = '<a class="btn btn-sm btn-default" href="javascript:void(0)" title="Detail" onclick="detailInbox('."'".$listInbox->id_pesan."'".')"><i class="glyphicon glyphicon-search"></i> Detail</a>
				<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Balas" onclick="replyInbox('."'".$listInbox->id_pesan."'".')"><i class="glyphicon glyphicon-pencil"></i> Reply</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteInbox('."'".$listInbox->id_pesan."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}//end loop
		$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->ibx->count_all(),
					"recordsFiltered" => $this->ibx->count_filtered($idUser),
					"data" => $data,
				);
		
		//output to json format
		echo json_encode($output);
	}

	public function detail_inbox($id)
	{
		$data = $this->ibx->get_by_id($id);
		echo json_encode($data);
	}

	public function reply_inbox()
	{
		$this->_validate();
		$initiated_date = date('Y-m-d H:i:s');
		$time = time();
		$data = array(			
				'id_user' => $this->input->post('idUserInbox'),
				'subject_pesan' => trim($this->input->post('subjectPesanInbox')),
				'id_user_target' => $this->input->post('sendToInbox'),
				'isi_pesan' => trim($this->input->post('isiPesanInbox')),
				'dt_post' => $initiated_date,
				'time_post' => $time,
			);
		$insert = $this->psn->save_data_pesan($data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_tambah" => 'Pesan Berhasil ditambahkan',
			));
	}

	public function update_read($id)
	{
		$initiated_date = date('Y-m-d H:i:s');
		$data = array(
				'dt_read' => $initiated_date,
			);
		$this->ibx->update_data_inbox(array('id_pesan' => $id), $data);
		echo json_encode(array(
			"status" => TRUE
			));
	}

	public function delete_pesan($id)
	{
		$this->psn->delete_data_pesan($id);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Pesan Berhasil dihapus'
			));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('subjectPesanInbox') == '') {
			$data['inputerror'][] = 'subjectPesanInbox';
            $data['error_string'][] = 'Subject Pesan is required';
            $data['status'] = FALSE;
		}

		if($this->input->post('sendToInbox') == '')
		{
			$data['inputerror'][] = 'sendToInbox';
			$data['error_string'][] = 'Tujuan Kirim is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('isiPesanInbox') == '')
		{
			$data['inputerror'][] = 'isiPesanInbox';
			$data['error_string'][] = 'Isi Pesan is required';
			$data['status'] = FALSE;
		}

        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
	}

}
