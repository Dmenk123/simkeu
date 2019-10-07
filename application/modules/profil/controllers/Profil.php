<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('username') === null) {
			redirect('login');
		}
		$this->load->library(array('upload','image_lib'));
		$this->load->model('mod_profil','prof');
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

	public function detail_pengguna()
	{
		$id_user = $this->uri->segment(3); 
		$query = $this->prof->get_detail_pengguna($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$data = array(
			'css'=>'cssProfil',
			'js'=>'jsProfil',
			'content' => 'view_detail_pengguna',
			'title' => 'PT.Surya Putra Barutama',
			'data_user' => $query,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			);
		//var_dump($data['hasil_data']);
		$this->load->view('view_home',$data);
	}

	public function form_detail_pengguna()
	{
		$id_user = $this->uri->segment(3); 
		$query = $this->prof->get_detail_pengguna($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$data = array(
			'css'=>'cssProfil',
			'js'=>'jsProfil',
			'content' => 'view_form_detail_pengguna',
			'title' => 'PT.Surya Putra Barutama',
			'data_user' => $query,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			);
		$this->load->view('view_home',$data);
	}

	public function update()
	{
		$id_nama = $this->session->userdata('id_user');
		

        if(isset($_FILES['foto_user'])) {
        	$nmfile = "img_".$id_nama;

			$config['upload_path'] = './assets/img/user_img/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; 
			$config['max_size'] = '2048'; 
	        $config['max_width']  = '0'; 
	        $config['max_height']  = '0';
	        $config['file_name'] = $nmfile; //nama yang terupload nantinya
	        $this->upload->initialize($config);

        	if ($this->upload->do_upload('foto_user')) {
        	 	$gbr = $this->upload->data();
        	 	////[ THUMB IMAGE ]
        	 	$config2['image_library'] = 'gd2';
        	 	$config2['source_image'] = './assets/img/user_img/'.$gbr['file_name'];
        	 	$config2['create_thumb'] = TRUE;
        	 	$config2['thumb_marker'] = '_thumb';
        	 	$config2['maintain_ratio'] = FALSE;
        	 	$config2['overwrite'] = TRUE;
        	 	$config2['quality'] = '60%';
        	 	$config2['width'] = 45;
        	 	$config2['height'] = 45;
        	 	$config2['new_image'] = './assets/img/user_img/thumbs/'.$gbr['file_name'];
        	 	$this->load->library('image_lib',$config2);
        	 	$this->image_lib->initialize($config2); 
        	 	$output_thumb = $gbr['raw_name'].'_thumb'.$gbr['file_ext'];	

        	 	if ( !$this->image_lib->resize()){
                    $this->session->set_flashdata('errors', $this->image_lib->display_errors('', '')); 
        	 	}

        	 	//[ MAIN IMAGE ]
                $config['image_library'] = 'gd2';
                $config['source_image'] = './assets/img/user_img/'.$gbr['file_name'];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;
                $config['new_image'] = './assets/img/user_img/'.$gbr['file_name'];
                $config['overwrite'] = TRUE;
                $config['width'] = 250;
                $config['height'] = 250;
                $this->load->library('image_lib',$config); 
                $this->image_lib->initialize($config);
        	 	
        	 	

        	 	$id = $this->input->post('id_user');
	        		$data = array(
	        		'nama_lengkap_user' =>$this->input->post('nama_lengkap'),
	        		'alamat_user' =>$this->input->post('alamat'),
	        		'tanggal_lahir_user' =>$this->input->post('tanggal_lahir'),
	        		'jenis_kelamin_user' =>$this->input->post('jenis_kelamin'),
	        		'no_telp_user' =>$this->input->post('cp_user'),		
	               	'gambar_user' =>$gbr['file_name'],
		            'thumb_gambar_user' =>$output_thumb, 

	            );
	        	var_dump($data['thumb_gambar_user']);	
	        	if ( !$this->image_lib->resize()){
                    $this->session->set_flashdata('errors', $this->image_lib->display_errors('', '')); 
        	 	}

        	}
        	else 
        	{
        		$id = $this->input->post('id_user');
        		$data = array(
	        		'nama_lengkap_user' =>$this->input->post('nama_lengkap'),
	        		'alamat_user' =>$this->input->post('alamat'),
	        		'tanggal_lahir_user' =>$this->input->post('tanggal_lahir'),
	        		'jenis_kelamin_user' =>$this->input->post('jenis_kelamin'),
	        		'no_telp_user' =>$this->input->post('cp_user'),
	            );
        	}
        	 	
        	$this->prof->update(array('id_user' => $id), $data);

            $this->session->set_flashdata("pesan", "<div class=\"col-md-12\"><div class=\"alert alert-success\" id=\"alert\">Setting Profil Berhasil !!</div></div>");
            redirect("profil/detail_pengguna/$id");
        	 
        }
	}
}
