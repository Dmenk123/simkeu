<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Set_gaji_guru extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//profil data
		$this->load->model('profil/mod_profil','prof');
		$this->load->model('mod_set_gaji_guru','m_set');
	}

	public function index()
	{	
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$data = array(
			'data_user' => $data_user
		);

		$content = [
			'css' 	=> 'cssSetGajiGuru',
			'modal' => 'modalSetGajiGuru',
			'js'	=> 'jsSetGajiGuru',
			'view'	=> 'view_list_set_gaji_guru'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_data()
	{
		$list = $this->m_set->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $sat) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $no;
			$row[] = $sat->nama;
			$row[] = '
				<div>
	                <span class="pull-left">Rp. </span>
	                  <span class="pull-right">'.number_format($sat->tunjangan,2,",",".").'</span>
	             </div>
			';

			//add html for action
			$row[] = '
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_jabatan('."'".$sat->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_jabatan('."'".$sat->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
			';

			$data[] = $row;
		}//end loop

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_set->count_all(),
			"recordsFiltered" => $this->m_set->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function add()
	{
		//validasi
		$arr_valid = $this->_validate();
		$nama = trim(strtoupper($this->input->post('nama')));
		$tunjangan = trim($this->input->post('tunjangan_raw'));
		
		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		}

		$data = array(
			'nama' => $nama,
			'tunjangan' => $tunjangan
		);

		$insert = $this->m_set->save($data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Master Jabatan Berhasil ditambahkan',
		));
	}

	public function edit($id)
	{
		$data = $this->m_set->get_by_id($id);		
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

		$this->m_set->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Master Tunjangan Berhasil diupdate',
		));
	}

	public function delete($id)
	{
		// $this->m_set->delete_by_id($id);
		$this->m_set->update(['id' => $id], ['is_aktif '=> 0]);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Master Jabatan Berhasil dihapus',
		));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('nama') == '') {
			$data['inputerror'][] = 'nama';
            $data['error_string'][] = 'Wajib mengisi nama';
            $data['status'] = FALSE;
		}
			
        return $data;
	}
}
