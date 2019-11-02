<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses_gaji extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//profil data
		$this->load->model('profil/mod_profil','prof');
		$this->load->model('verifikasi_out/mod_verifikasi_out','m_vout');
		$this->load->model('mod_proses_gaji','m_pro');
	}

	public function index()
	{	
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$data = array(
			'data_user' => $data_user
		);

		$content = [
			'css' 	=> 'cssProsesGaji',
			'modal' => 'modalProsesGaji',
			'js'	=> 'jsProsesGaji',
			'view'	=> 'view_list_proses_gaji'
		];

		$this->template_view->load_view($content, $data);
	}

	public function suggest_guru()
	{
		if (isset($_GET['term'])) {
			$q = strtolower($_GET['term']);
		}else{
			$q = '';
		}
		
		$query = $this->m_pro->lookup_kode_guru($q);
		
		foreach ($query as $row) {
			$akun[] = array(
				'id' => $row->id,
				'text' => $row->nip.' - '.$row->nama
			);
		}
		echo json_encode($akun);
	}

	public function get_data_guru($id)
	{
		$q = $this->db->query("
			SELECT tg.*, tj.nama as nama_jabatan, tsg.gaji_pokok, tsg.gaji_perjam, tsg.gaji_tunjangan_jabatan 
			FROM tbl_guru as tg
			JOIN tbl_jabatan tj on tg.kode_jabatan = tj.id and tj.is_aktif = 1
			JOIN tbl_set_gaji tsg on tg.kode_jabatan = tsg.id_jabatan and tsg.is_aktif = 1
			WHERE tg.id = '".$id."' and tg.is_aktif = 1
		 ")->row();

		if ($q->is_guru == 1) {
			$q->statuspeg = 'Guru';
		}else{
			$q->statuspeg = 'Staff/Karyawan';
		}
		
		echo json_encode($q);
	}

	public function list_data()
	{
		$list = $this->m_pro->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $sat) {
			// $no++;
			$row = array();
			//loop value tabel db
			// $row[] = $no;
			$row[] = $sat->nama_jabatan;
			$row[] = '
				<div>
	                <span class="pull-left">Rp. </span>
	                  <span class="pull-right">'.number_format($sat->gaji_pokok,2,",",".").'</span>
	             </div>
			';
			$row[] = '
				<div>
	                <span class="pull-left">Rp. </span>
	                  <span class="pull-right">'.number_format($sat->gaji_perjam,2,",",".").'</span>
	             </div>
			';
			$row[] = '
				<div>
	                <span class="pull-left">Rp. </span>
	                  <span class="pull-right">'.number_format($sat->gaji_tunjangan_jabatan,2,",",".").'</span>
	             </div>
			';

			//add html for action
			$row[] = '
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_data('."'".$sat->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$sat->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
			';

			$data[] = $row;
		}//end loop

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_pro->count_all(),
			"recordsFiltered" => $this->m_pro->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function add_data()
	{
		//validasi
		// $arr_valid = $this->_validate();
		
		/* if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		} */ 

		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
		$namapeg = $this->input->post('namapeg');
		$statuspeg = $this->input->post('statuspeg_raw');
		$jabatanpeg = $this->input->post('jabatanpeg_raw');
		$gapok = $this->input->post('gapok_raw');
		$gaperjam = $this->input->post('gaperjam_raw');
		$tunjangan = $this->input->post('tunjangan_raw');
		$tunjangan_lain = $this->input->post('tunjanganlain_raw');
		$potongan = $this->input->post('potongan_raw');
		$jumlahjam = $this->input->post('jumlahjam');
		$totalgaji = $this->input->post('totalgaji_raw');	
		
		$this->db->trans_begin();

		$arr_ins_gaji = [
			'id_guru' => $namapeg,
			'id_jabatan' => $jabatanpeg,
			'bulan' => $bulan,
			'tahun' => $tahun,
			'is_guru' => $statuspeg,
			'gaji_pokok' => $gapok,
			'gaji_perjam' => $gaperjam,
			'gaji_tunjangan_jabatan' => $tunjangan,
			'gaji_tunjangan_lain' => $tunjangan_lain,
			'potongan_lain' => $potongan,
			'total_take_home_pay' => $totalgaji,
			'created_at' => date('Y-m-d H:i:s')
		];
		
		$insert = $this->m_pro->save('tbl_penggajian', $data);

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$status = FALSE;
			$pesan = 'Gagal Proses Gaji';
		}
		else {
			$this->db->trans_commit();
			$status = TRUE;
			$pesan = 'Berhsil Proses Gaji';
		}
				
		echo json_encode(array(
			"status" => $status,
			"pesan" => $pesan,
		));
	}

	public function edit($id)
	{
		$data = $this->db->query("SELECT tbl_set_gaji.*, tbl_jabatan.nama as nama_jabatan from tbl_set_gaji join tbl_jabatan on tbl_set_gaji.id_jabatan = tbl_jabatan.id where tbl_set_gaji.id = '".$id."'")->row();

		//$data = $this->m_pro->get_by_id($id);		
		echo json_encode($data);
	}

	public function update_data()
	{
		$arr_valid = $this->_validate();
		
		if ($arr_valid['status'] == FALSE) {
			echo json_encode($arr_valid);
			return;
		} 

		$data = array(
			'id_jabatan' => $this->input->post('jabatan'),
			'gaji_pokok' => $this->input->post('gapok_raw'),
			'gaji_perjam' => $this->input->post('gaperjam_raw'),
			'gaji_tunjangan_jabatan' => $this->input->post('tunjangan_raw'),
			'is_guru' => $this->input->post('tipepeg')			
		);

		$this->m_pro->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Setting Gaji Berhasil diupdate',
		));
	}

	public function delete_data($id)
	{
		// $this->m_pro->delete_by_id($id);
		$this->m_pro->update(['id' => $id], ['is_aktif '=> 0]);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Setting Gaji Berhasil dihapus',
		));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('jabatan') == '') {
			$data['inputerror'][] = 'jabatan';
            $data['error_string'][] = 'Wajib mengisi jabatan';
            $data['status'] = FALSE;
		}

		if ($this->input->post('gapok') == '') {
			$data['inputerror'][] = 'gapok';
            $data['error_string'][] = 'Wajib mengisi Gaji Pokok';
            $data['status'] = FALSE;
		}

		if ($this->input->post('gaperjam') == '') {
			$data['inputerror'][] = 'gaperjam';
            $data['error_string'][] = 'Wajib mengisi Gaji per jam';
            $data['status'] = FALSE;
		}

		if ($this->input->post('tunjangan') == '') {
			$data['inputerror'][] = 'tunjangan';
            $data['error_string'][] = 'Wajib mengisi Gaji Tunjangan';
            $data['status'] = FALSE;
		}

		if ($this->input->post('tipepeg') == '') {
			$data['inputerror'][] = 'tipepeg';
            $data['error_string'][] = 'Wajib mengisi Tipe Pegawai';
            $data['status'] = FALSE;
		}
			
        return $data;
	}
}
