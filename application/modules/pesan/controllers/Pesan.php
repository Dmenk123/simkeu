<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesan extends CI_Controller {

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
		$this->load->model('mod_pesan','psn');
		$this->load->helper('fungsi');
		$this->load->model('profil/mod_profil','prof');
	}

	public function index()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query = $this->prof->get_detail_pengguna($id_user);
		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan
		
		$data = array(
			'content'=>'view_list_pesan',
			'css'=>'cssPesan',
			'modal'=>'modalPesan',
			'js'=>'jsPesan',
			'title' => 'PT.Surya Putra Barutama',
			'data_user' => $query,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
		);
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function pesan_list($idUser='')
	{
		//membuat format data json untuk ajax		
		$data = array();
		$no =$_POST['start'];
		$list = $this->psn->get_datatables($idUser);
		foreach ($list as $listPesan) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = $listPesan->nama_lengkap_user;
			$row[] = $listPesan->subject_pesan;
			$row[] = $listPesan->isi_pesan;
			$row[] = $listPesan->dt_post;
			//add html for action
			$row[] = '<a class="btn btn-sm btn-default" href="javascript:void(0)" title="Detail" onclick="detailPesan('."'".$listPesan->id_pesan."'".')"><i class="glyphicon glyphicon-search"></i> Detail</a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deletePesan('."'".$listPesan->id_pesan."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}//end loop
		$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->psn->count_all(),
					"recordsFiltered" => $this->psn->count_filtered($idUser),
					"data" => $data,
				);
		
		//output to json format
		echo json_encode($output);
	}

	
	public function detail_pesan($id)
	{
		$data = $this->psn->get_by_id($id);
		echo json_encode($data);
	}

	public function add_pesan()
	{
		$this->_validate();
		$initiated_date = date('Y-m-d H:i:s');
		$time = time();
		$data = array(			
				'id_user' => $this->input->post('idUser'),
				'subject_pesan' => trim($this->input->post('subjectPesan')),
				'id_user_target' => $this->input->post('sendTo'),
				'isi_pesan' => trim($this->input->post('isiPesan')),
				'dt_post' => $initiated_date,
				'time_post' => $time,
			);
		$insert = $this->psn->save_data_pesan($data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_tambah" => 'Pesan Berhasil ditambahkan',
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

	public function load_row_notif()
	{ //fungsi load_row untuk menampilkan jlh data pada navbar secara realtime
		$id_user = $this->session->userdata('id_user');
        echo $this->psn->notif_count($id_user); //jumlah data akan langsung di tampilkan
    }
 
    public function load_data_notif()
    { //fungsi load_data untuk menampilkan isi data pada navbar secara realtime
 		$id_user = $this->session->userdata('id_user');
        $data = $this->psn->get_notifikasi($id_user);
        $no = 0;
        if (count($data) > 0 ) {
        	foreach($data as $rdata) { 
	        	$no++;
	            if($no % 2 == 0) {
	            	$strip='strip1';
	            }else{
	            	$strip='strip2';
	            }
	            echo"<li><a href=\"#\" class=\"".$strip." linkNotif\" id=\"".$rdata->id_pesan."\">".$rdata->subject_pesan."<br>
	            <small><strong>".$rdata->nama_lengkap_user."</strong>  (".timeAgo($rdata->time_post).")</small>
	            </a><li>";
	        }
        }
        
    }

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('subjectPesan') == '') {
			$data['inputerror'][] = 'subjectPesan';
            $data['error_string'][] = 'Subject Pesan is required';
            $data['status'] = FALSE;
		}

		if($this->input->post('sendTo') == '')
		{
			$data['inputerror'][] = 'sendTo';
			$data['error_string'][] = 'Tujuan Kirim is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('isiPesan') == '')
		{
			$data['inputerror'][] = 'isiPesan';
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
