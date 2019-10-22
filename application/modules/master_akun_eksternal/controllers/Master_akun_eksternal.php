<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_akun_internal extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//profil data
		$this->load->model('profil/mod_profil','prof');
		$this->load->model('mod_master_akun_internal','akun_i');
	}

	public function index()
	{	
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$data = array(
			'data_user' => $data_user
		);

		$content = [
			'css' 	=> 'cssMasterAkunInternal',
			'modal' => 'modalMasterAkunInternal',
			'js'	=> 'jsMasterAkunInternal',
			'view'	=> 'view_list_master_akun_internal'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_akun_internal()
	{
		$txtNamaIndex = "";
		$list = $this->akun_i->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $val) {
			$no++;
			$row = array();
			
			if ($val->nama_sub != null) {
				$txtNamaIndex = $val->nama_sub;
			}else{
				$q = $this->db->query("SELECT nama from tbl_master_kode_akun_internal WHERE kode = '".$val->kode."' and sub_1 is null and sub_2 is null")->row();
				$txtNamaIndex = $q->nama;
			}

			$row[] = $no;
			$row[] = $txtNamaIndex;
			$row[] = $val->nama;
			$row[] = $val->kode_in_text;

			//add html for action
			$row[] = '
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_akun('."'".$val->kode_in_text."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_akun('."'".$val->kode_in_text."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
			';

			$data[] = $row;
		}//end loop

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->akun_i->count_all(),
			"recordsFiltered" => $this->akun_i->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function add()
	{
		// $this->_validate();
		$kat_akun = $this->input->post('kat_akun');
		$nama = $this->input->post('nama');
		
		if ($nama == '' || $kat_akun == '') {
			echo json_encode(array(
				"status" => TRUE,
				"pesan" => 'Mohon Lengkapi isian pada form',
			));

			return;
		}

		$arr_akun = explode("-",$kat_akun);
		$kode = $arr_akun[0];
		$kode_in_text = $arr_akun[1];

		$q = $this->db->query("select max(sub_1) as last_sub1 from tbl_master_kode_akun_internal where kode = '".$kode."'")->row();
		if ($q->last_sub1 == null) {
			$kode_sub1_final = 1;
		}else{
			$kode_sub1_final = (int)$q->last_sub1 + 1;
		}
		
		$data = array(
			'nama' => $nama,
			'kode' => $kode,
			'sub_1' => $kode_sub1_final,
			'sub_2' => null,
			'tipe_bos' => null,
			'kode_bos' => null,
			'kode_bos_sub1' => null,
			'kode_bos_sub2' => null,
			'kode_in_text' => $kode.'.'.$kode_sub1_final
		);

		$insert = $this->akun_i->save($data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Master Akun Internal Berhasil ditambahkan',
		));
	}

	public function get_kategori_akun() {
		if (isset($_GET['term'])) {
			$q = strtolower($_GET['term']);
		}else{
			$q = '';
		}
		
		$query = $this->akun_i->lookup_kode_akun_internal($q);
		
		foreach ($query as $row) {
			$akun[] = array(
				'id' => $row->kode.'-'.$row->kode_in_text,
				'text' => $row->kode_in_text.' - '.$row->nama
			);
		}
		echo json_encode($akun);
	}

	public function edit($id)
	{
		$data = $this->akun_i->get_by_id($id);
		$q = $this->db->query("select * from tbl_master_kode_akun_internal where kode = '".$data->kode."' and sub_1 is null and sub_2 is null")->row();
		
		$hasil = [
			'nama' => $data->nama,
			'id' => $data->kode_in_text,
			'kat_id' => $q->kode.'-'.$data->kode_in_text,
			'kat_text' => $q->kode_in_text.' - '.$q->nama
		];
		
		echo json_encode($hasil);
	}

	public function update()
	{
		//$this->_validate();
		$arr_akun = explode("-", $this->input->post('kat_akun'));
		
		$kode = $arr_akun[0];
		$kode_in_text = $arr_akun[1];

		$kode_akun = null;
		$sub1_akun = null;
		$sub2_akun = null;
		$arr_akun2 = explode(".", $arr_akun[1]);
		for ($z=0; $z <count($arr_akun2); $z++) { 
			if ($z == 0) {
				$kode_akun = $arr_akun2[$z];
			}elseif($z == 1){
				$sub1_akun = $arr_akun2[$z];
			}elseif($z == 2){
				$sub2_akun = $arr_akun2[$z];
			}
		}

		$data = array(
			'nama' => $this->input->post('nama'),
			'kode' => $kode,
			'sub_1' => $sub1_akun,
			'sub_2' => $sub2_akun,
			'kode_in_text' => $arr_akun[1]
		);

		if ($this->input->post('nama') == '' || $this->input->post('kat_akun') == '') {
			echo json_encode(array(
				"status" => TRUE,
				"pesan" => 'Mohon Lengkapi isian pada form',
			));

			return;
		}

		$this->akun_i->update(array('kode_in_text' => $this->input->post('id')), $data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Master Satuan Berhasil diupdate',
		));
	}

	public function delete($id)
	{
		$this->akun_i->delete_by_id($id);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Master Satuan Berhasil dihapus',
		));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('nama') == '') {
			$data['inputerror'][] = 'Nama';
            $data['error_string'][] = 'Nama is required';
            $data['status'] = FALSE;
		}
		if($this->input->post('keterangan') == '')
        {
            $data['inputerror'][] = 'Keterangan';
            $data['error_string'][] = 'Keterangan is required';
            $data['status'] = FALSE;
        }
	}
}
