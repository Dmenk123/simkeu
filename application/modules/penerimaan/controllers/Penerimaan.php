<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//profil data
		$this->load->model('profil/mod_profil','prof');
		$this->load->model('mod_penerimaan','m_in');
		$this->load->model('verifikasi_out/mod_verifikasi_out','m_vout');
	}

	public function index()
	{	
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$data = array(
			'data_user' => $data_user
		);

		$content = [
			'css' 	=> 'cssPenerimaan',
			'modal' => null,
			'js'	=> 'jsPenerimaan',
			'view'	=> 'view_list_penerimaan'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_penerimaan($status=0)
	{
		$list = $this->m_in->get_datatables($status);
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $list_in) {
			$link_detail = site_url('penerimaan/penerimaan_detail/').$list_in->id;
			$link_edit = site_url('penerimaan/penerimaan_edit/').$list_in->id;
			$no++;
			$row = array();
			if ($status == 1) {
				$row[] = $list_in->id_verifikasi;
			}
			$row[] = $list_in->id;
			$row[] = date('d-m-Y', strtotime($list_in->tanggal));
			$row[] = $list_in->nama_lengkap_user;
			if ($list_in->status == 0) {
				//belum di verifikasi
				$row[] = '<span style="color:red">Belum Di Verifikasi</span>';
			}else{
				$row[] = '<span style="color:green">Sudah Di Verifikasi</span>';
			}
			
			if ($list_in->status == 0) {
				//belum di verifikasi
				$row[] = '
					<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Detail" id="btn_detail" onclick="">
						<i class="glyphicon glyphicon-info-sign"></i></a>
					<a class="btn btn-sm btn-primary" href="'.$link_edit.'" title="Edit" id="btn_edit" onclick=""><i class="glyphicon glyphicon-pencil"></i></a>
				';
			}else{
				$row[] = '
					<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Detail" id="btn_detail" onclick=""><i class="glyphicon glyphicon-info-sign"></i></a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deletePenerimaan('."'".$list_in->id_verifikasi."'".')"><i class="glyphicon glyphicon-trash"></i></a>
				';
			}
			
			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_in->count_all($status),
						"recordsFiltered" => $this->m_in->count_filtered($status),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function penerimaan_detail()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query_user = $this->prof->get_detail_pengguna($id_user);

		$id = $this->uri->segment(3); 
		$query_header = $this->m_in->get_detail_header($id);
		$query = $this->m_in->get_detail($id);

		$data = array(
			'data_user' => $query_user,
			'hasil_header' => $query_header,
			'hasil_data' => $query
		);

		$content = [
			'css' 	=> 'cssPenerimaan',
			'modal' => null,
			'js'	=> 'jsPenerimaan',
			'view'	=> 'view_detail_penerimaan'
		];

		$this->template_view->load_view($content, $data);
	}

	public function add_penerimaan()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$data = array(
			'data_user' => $data_user
		);

		$content = [
			'css' 	=> 'cssPenerimaan',
			'modal' =>  null,
			'js'	=> 'jsPenerimaan',
			'view'	=> 'view_add_penerimaan'
		];

		$this->template_view->load_view($content, $data);
	}

	public function proses_penerimaan()
	{
		$timestamp = date('Y-m-d H:i:s');
		$keterangan = $this->input->post('i_keterangan');
		$satuan = $this->input->post('i_satuan');
		$qty = $this->input->post('i_qty');
		$harga_raw = $this->input->post('i_harga_raw');
		$harga_total_raw = $this->input->post('i_harga_total_raw');
		$akun = $this->input->post('i_akun');
		$gambar = $this->input->post('i_gambar');
		$ceklis = $this->input->post('ceklis');

		$this->db->trans_begin();
		if ($this->input->post('ceklis') == 't' ) {
			if(!empty($_FILES['i_gambar']['name']))
			{
				$this->konfigurasi_upload_bukti($this->input->post('i_gambar'));
				//get detail extension
				$pathDet = $_FILES['i_gambar']['name'];
				$extDet = pathinfo($pathDet, PATHINFO_EXTENSION);
				if ($this->gbr_bukti->do_upload('i_gambar')) 
				{
					$gbrBukti = $this->gbr_bukti->data();
					//inisiasi variabel u/ digunakan pada fungsi config img bukti
					$nama_file_bukti = $gbrBukti['file_name'];
					//load config img bukti
					$this->konfigurasi_image_resize($nama_file_bukti);
					//clear img lib after resize
					$this->image_lib->clear();
				} //end

				$kode = $this->m_in->getKodePenerimaan();
				$kode_detail = $this->m_in->getKodePenerimaanDetail();

				//set tipe akun
				$arr_akun1 = explode("-", $akun);
				$tipe_akun = $arr_akun1[0]; 
				//set kode dan sub akun
				$kode_akun = null;
				$sub1_akun = null;
				$sub2_akun = null;
				$arr_akun2 = explode(".", $arr_akun1[1]);
				for ($z=0; $z <count($arr_akun2); $z++) { 
					if ($z == 0) {
						$kode_akun = $arr_akun2[$z];
					}elseif($z == 1){
						$sub1_akun = $arr_akun2[$z];
					}elseif($z == 2){
						$sub2_akun = $arr_akun2[$z];
					}
				}

				$data_header = [
					'id' => $kode,
					'user_id' => $this->session->userdata('id_user'),
					'tanggal' => date('Y-m-d'),
					'status' => 1,
					'created_at' => $timestamp
				];

				$data_isi = [
					'id' => $kode_detail,
					'id_trans_masuk' => $kode,
					'keterangan' => $keterangan,
					'satuan' => $satuan,
					'qty' => $qty,
					'status' => 1
				];

				$data_verifikasi = [
					'id' => $this->m_vout->getKodeVerifikasi(),
					'id_in' => $kode,
					'id_in_detail' => $kode_detail,
					'tanggal' => date("Y-m-d"),
					'user_id' => $this->session->userdata('id_user'),
					'gambar_bukti' => $nama_file_bukti,
					'harga_satuan' => $harga_raw,
					'harga_total' => $harga_total_raw,
					'status' => 1,
					'tipe_akun' => $tipe_akun,
					'kode_akun' => $kode_akun,
					'sub1_akun' => $sub1_akun,
					'sub2_akun' => $sub2_akun,
					'tipe_transaksi' => 1,
					'created_at' => $timestamp
				];

				$this->m_in->save($data_header, $data_isi, $data_verifikasi);

				if ($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$this->session->set_flashdata('feedback_gagal','Gagal Input dan Verifikasi data.'); 
					redirect($this->uri->segment(1));
				}
				else {
					$this->db->trans_commit();
					$this->session->set_flashdata('feedback_success','Berhasil Input dan Verifikasi data.'); 
					redirect($this->uri->segment(1));
				}
			}else{
				$this->db->trans_rollback();
				$this->session->set_flashdata('feedback_gagal','Mohon Lengkapi Kelengkapan Data'); 
				redirect($this->uri->segment(1));
			}
		}else{
			$this->db->trans_rollback();
			$this->session->set_flashdata('feedback_gagal','Mohon centang pilihan setuju'); 
			redirect($this->uri->segment(1));
		}
	}

	public function konfigurasi_upload_bukti($nmfile)
	{ 
		//konfigurasi upload img display
		$config['upload_path'] = './assets/img/bukti_verifikasi/';
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
	    $config['source_image'] = './assets/img/bukti_verifikasi/'.$filename;
	    $config['create_thumb'] = FALSE;
	    $config['maintain_ratio'] = FALSE;
	    $config['new_image'] = './assets/img/bukti_verifikasi/'.$filename;
	    $config['overwrite'] = TRUE;
	    $config['width'] = 450; //resize
	    $config['height'] = 500; //resize
	    $this->load->library('image_lib',$config); //load image library
	    $this->image_lib->initialize($config);
	    $this->image_lib->resize();
	}

	public function hapus_penerimaan_finish($id)
	{
		$this->db->trans_begin();

		//ambil data dan hapus data verifikasi
		$data_lawas = $this->db->query("select * from tbl_verifikasi where id = '".$id."'")->row();
		$this->m_vout->delete_ver_by_id($id);
		//update penerimaan detil
		$this->db->update('tbl_trans_masuk_detail', ['status' => 0], ['id' => $data_lawas->id_in_detail]);
		//update penerimaan
		$this->db->update('tbl_trans_masuk', ['status' => 0], ['id' => $data_lawas->id_in]);
		
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			echo json_encode(array(
				"status" => FALSE,
				"pesan" => 'Data gagal dihapus'
			));
		}
		else {
			$this->db->trans_commit();
			echo json_encode(array(
				"status" => TRUE,
				"pesan" => 'Data Sukses dihapus'
			));
		}
		
	}

	public function penerimaan_edit($id)
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$query = $this->m_in->get_detail($id, 'edit');

		$data = array(
			'data_user' => $data_user,
			'hasil_data' => $query
		);

		$content = [
			'css' 	=> 'cssPenerimaan',
			'modal' =>  null,
			'js'	=> 'jsPenerimaan',
			'view'	=> 'view_edit_penerimaan'
		];

		$this->template_view->load_view($content, $data);
	}

	// =====================================================================================================================

	

	

	public function update_pengeluaran()
	{
		// $this->_validate();
		$this->db->trans_begin();
		//delete id order in tabel detail
		$id = $this->input->post('fieldId');
		$timestamp = date('Y-m-d H:i:s');
		$hapus_data_detail = $this->m_in->hapus_data_detail($id);

		//update header
		$data_header = array(
			'updated_at' => $timestamp
		); 
		$this->m_in->update_data_header(array('id' => $id), $data_header);

		//proses insert ke tabel detail
		$hitung_detail = count($this->input->post('i_satuan'));
		$data_detail = array();
		for ($i=0; $i < $hitung_detail; $i++) 
		{
			$data_detail[$i] = array(
				'id_trans_keluar' => $id,
				'keterangan' => $this->input->post('i_keterangan')[$i],
				'satuan' => $this->input->post('i_satuan')[$i],
				'qty' => $this->input->post('i_jumlah')[$i]
			);
		}

		$insert_update = $this->m_in->insert_update($data_detail);

		if ($this->db->trans_status() === FALSE) {
        	$this->db->trans_rollback();
        	echo json_encode(array(
				"status" => FALSE,
				"pesan_tambah" => 'Data Transaksi Pengeluaran Gagal Diupdate'
			));
		}
		else {
		    $this->db->trans_commit();
		    echo json_encode(array(
				"status" => TRUE,
				"pesan_tambah" => 'Data Transaksi Pengeluaran Berhasil Diupdate'
			));
		}
	}

	

	public function cetak_nota_pengeluaran()
	{
		$this->load->library('Pdf_gen');

		$id = $this->uri->segment(3);
		$query_header = $this->m_in->get_detail_header($id);
		$query = $this->m_in->get_detail($id);

		$data = array(
			'title' => 'Report Pencatatan Pengeluaran',
			'hasil_header' => $query_header,
			'hasil_data' => $query, 
		);

	    $html = $this->load->view('view_detail_pengeluaran_report', $data, true);
	    
	    $filename = 'nota_pengeluaran_'.$id.'_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	// ====================================================================================================

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('fieldNamaBarangOrder') == '') {
			$data['inputerror'][] = 'formNamaBarangOrder';
            $data['error_string'][] = 'Nama Barang is required';
            $data['status'] = FALSE;
		}

		if($this->input->post('fieldNamaSatuanOrder') == '')
		{
			$data['inputerror'][] = 'formNamaSatuanOrder';
			$data['error_string'][] = 'Satuan is required';
			$data['status'] = FALSE;
		}

        if($this->input->post('fieldJumlahBarangOrder') == '')
		{
			$data['inputerror'][] = 'formJumlahBarangOrder';
			$data['error_string'][] = 'Jumlah Barang is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('fieldTanggalOrder') == '')
		{
			$data['inputerror'][] = 'fieldTanggalOrder';
			$data['error_string'][] = 'Tanggal is required';
			$data['status'] = FALSE;
		}
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
	}

	public function suggest_barang()
	{
		// $q = $this->input->post('kode',TRUE);
		$q = strtolower($_GET['term']);
		$query = $this->m_in->lookup($q);
		//$barang = array();

		foreach ($query as $row) {
			$barang[] = array(
						'label' => $row->nama_barang,
						'id_barang' => $row->id_barang,
						'nama_satuan' => $row->nama_satuan,
						'id_satuan' => $row->id_satuan
					);
		}
		echo json_encode($barang);
	}

	public function get_data_barang($rowIdBrg)
	{
		$query = $this->m_in->lookup2($rowIdBrg);
		echo json_encode($query);
	}

}