<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_guru extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//profil data
		$this->load->model('profil/mod_profil','prof');
		$this->load->model('mod_guru','m_guru');
	}

	public function index()
	{	
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$data = array(
			'data_user' => $data_user
		);

		$content = [
			'css' 	=> 'cssMasterGuru',
			'modal' => 'modalMasterGuru',
			'js'	=> 'jsMasterGuru',
			'view'	=> 'view_list_master_guru'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_guru()
	{
		$list = $this->m_guru->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $val) {
			// $no++;
			$row = array();
			//loop value tabel db
			// $row[] = $no;
			$row[] = '<img src="'.base_url().'/assets/img/foto_guru/'.$val->foto.'" width="75" height="75">';
			$row[] = $val->nama;
			$row[] = $val->nama_jabatan;

			//add html for action
			$row[] = '
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_guru('."'".$val->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_guru('."'".$val->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
			';

			$data[] = $row;
		}//end loop

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_guru->count_all(),
			"recordsFiltered" => $this->m_guru->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function add()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$data = array(
			'data_user' => $data_user
		);

		$content = [
			'css' 	=> 'cssMasterGuru',
			'modal' => 'modalMasterGuru',
			'js'	=> 'jsMasterGuru',
			'view'	=> 'view_add_master_guru'
		];

		$this->template_view->load_view($content, $data);
	}

	public function suggest_jabatan()
	{
		if (isset($_GET['term'])) {
			$q = strtolower($_GET['term']);
		}else{
			$q = '';
		}
		
		$query = $this->m_guru->lookup_kode_jabatan($q);
		
		foreach ($query as $row) {
			$akun[] = array(
				'id' => $row->id,
				'text' => $row->nama
			);
		}
		echo json_encode($akun);
	}

	public function add_data()
	{
		$arr_valid = $this->_validate();
		$nip = trim(strtoupper($this->input->post('nip')));
		$nama = trim($this->input->post('nama'));
		$jabatan = trim($this->input->post('jabatan'));
		$tempat_lahir = trim($this->input->post('tempatlahir'));
		$hari = $this->input->post('hari');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$alamat = trim($this->input->post('alamat'));
		$jenkel = $this->input->post('jenkel');
		$namafileseo = $this->seoUrl($nama);

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$this->db->trans_begin();

		if(!empty($_FILES['gambar']['name']))
		{
			$this->konfigurasi_upload_bukti($namafileseo);
			//get detail extension
			$pathDet = $_FILES['gambar']['name'];
			$extDet = pathinfo($pathDet, PATHINFO_EXTENSION);
			if ($this->gbr_bukti->do_upload('gambar')) 
			{
				$gbrBukti = $this->gbr_bukti->data();
				//inisiasi variabel u/ digunakan pada fungsi config img bukti
				$nama_file_foto = $gbrBukti['file_name'];
				//load config img bukti
				$this->konfigurasi_image_resize($nama_file_foto);
				//clear img lib after resize
				$this->image_lib->clear();
			} //end
		}else{
			$this->db->trans_rollback();
			$this->session->set_flashdata('feedback_failed','Mohon Lengkapi Kelengkapan Data'); 
			echo json_encode(array(
				"status" => true
			));
			return;
		}

		$data = array(
			'nip' => $nip,
			'nama' => $nama,
			'kode_jabatan' => $jabatan,
			'alamat' => $alamat,
			'tempat_lahir' => $tempat_lahir,
			'tanggal_lahir' => date('Y-m-d', strtotime($tahun.'-'.$bulan.'-'.$hari)),
			'jenis_kelamin' => $jenkel,
			'foto' => $nama_file_foto
		);

		$insert = $this->m_guru->save($data);

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$this->session->set_flashdata('feedback_failed','Gagal Buat Master Guru.'); 
		}
		else {
			$this->db->trans_commit();
			$this->session->set_flashdata('feedback_success','Berhasil Buat Master Guru'); 
		}

		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Master Jabatan Berhasil ditambahkan',
		));
	}

	public function edit($id)
	{
		$data = $this->m_guru->get_by_id($id);		
		echo json_encode($data);
	}

	public function update()
	{
		$arr_valid = $this->_validate();
		$nama = trim(strtoupper($this->input->post('nama')));
		$tunjangan = trim($this->input->post('tunjangan_raw'));

		$data = array(
			'nama' => $nama,
			'tunjangan' => $tunjangan		
		);

		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$this->m_guru->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Master Tunjangan Berhasil diupdate',
		));
	}

	public function delete($id)
	{
		// $this->m_guru->delete_by_id($id);
		$this->m_guru->update(['id' => $id], ['is_aktif '=> 0]);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Master Jabatan Berhasil dihapus',
		));
	}

	public function konfigurasi_upload_bukti($nmfile)
	{ 
		//konfigurasi upload img display
		$config['upload_path'] = './assets/img/foto_guru/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp';
		$config['overwrite'] = TRUE;
		$config['max_size'] = '4000';//in KB (4MB)
		$config['max_width']  = '0';//zero for no limit 
		$config['max_height']  = '0';//zero for no limit
		$config['file_name'] = $nmfile;
		//load library with custom object name alias
		$this->load->library('upload', $config, 'gbr_bukti');
		$this->gbr_bukti->initialize($config);
	}

	public function konfigurasi_image_resize($filename)
	{
		//konfigurasi image lib
	    $config['image_library'] = 'gd2';
	    $config['source_image'] = './assets/img/foto_guru/'.$filename;
	    $config['create_thumb'] = FALSE;
	    $config['maintain_ratio'] = FALSE;
	    $config['new_image'] = './assets/img/foto_guru/'.$filename;
	    $config['overwrite'] = TRUE;
	    $config['width'] = 450; //resize
	    $config['height'] = 500; //resize
	    $this->load->library('image_lib',$config); //load image library
	    $this->image_lib->initialize($config);
	    $this->image_lib->resize();
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('nip') == '') {
			$data['inputerror'][] = 'nip';
            $data['error_string'][] = 'Wajib mengisi nip';
            $data['status'] = FALSE;
		}

		if ($this->input->post('nama') == '') {
			$data['inputerror'][] = 'nama';
            $data['error_string'][] = 'Wajib mengisi nama';
            $data['status'] = FALSE;
		}

		if ($this->input->post('jabatan') == null) {
			$data['inputerror'][] = 'jabatan';
            $data['error_string'][] = 'Wajib mengisi jabatan';
            $data['status'] = FALSE;
		}

		if ($this->input->post('tempatlahir') == '') {
			$data['inputerror'][] = 'tempatlahir';
            $data['error_string'][] = 'Wajib mengisi tempatlahir';
            $data['status'] = FALSE;
		}

		if ($this->input->post('hari') == '') {
			$data['inputerror'][] = 'hari';
            $data['error_string'][] = 'Wajib mengisi hari';
            $data['status'] = FALSE;
		}

		if ($this->input->post('bulan') == '') {
			$data['inputerror'][] = 'bulan';
            $data['error_string'][] = 'Wajib mengisi bulan';
            $data['status'] = FALSE;
		}

		if ($this->input->post('tahun') == '') {
			$data['inputerror'][] = 'tahun';
            $data['error_string'][] = 'Wajib mengisi tahun';
            $data['status'] = FALSE;
		}

		if ($this->input->post('alamat') == '') {
			$data['inputerror'][] = 'alamat';
            $data['error_string'][] = 'Wajib mengisi alamat';
            $data['status'] = FALSE;
		}

		if ($this->input->post('jenkel') == '') {
			$data['inputerror'][] = 'jenkel';
            $data['error_string'][] = 'Wajib mengisi jenkel';
            $data['status'] = FALSE;
		}
			
        return $data;
	}

	private function seoUrl($string) {
	    //Lower case everything
	    $string = strtolower($string);
	    //Make alphanumeric (removes all other characters)
	    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
	    //Clean up multiple dashes or whitespaces
	    $string = preg_replace("/[\s-]+/", " ", $string);
	    //Convert whitespaces and underscore to dash
	    $string = preg_replace("/[\s_]/", "-", $string);
	    return $string;
	}
}
