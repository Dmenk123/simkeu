<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trans_rapbs extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//profil data
		$this->load->model('profil/mod_profil','prof');
		$this->load->model('mod_trans_rapbs','m_rapbs');
		$this->load->model('verifikasi_out/mod_verifikasi_out','m_vout');
		$this->load->library('excel');
	}

	public function index()
	{	
		$arr_bulan = [
			1 => 'Januari',
			2 => 'Februari',
			3 => 'Maret',
			4 => 'April',
			5 => 'Mei',
			6 => 'Juni',
			7 => 'Juli',
			8 => 'Agustus',
			9 => 'September',
			10 => 'Oktober',
			11 => 'November',
			12 => 'Desember'
		];

		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$data = array(
			'data_user' => $data_user,
			'arr_bulan' => $arr_bulan
		);

		$content = [
			'css' 	=> 'cssTransRapbs',
			'modal' => null,
			'js'	=> 'jsTransRapbs',
			'view'	=> 'view_list_trans_rapbs'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_rapbs($tahun)
	{
		$list = $this->m_rapbs->get_datatables($tahun);
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $datalist) {
			$no++;
			$row = array();
			$row[] = $datalist->id;
			$row[] = $datalist->tahun;
			$row[] = date('d-m-Y', strtotime($datalist->created_at));
			$row[] = $datalist->nama_lengkap_user;
			
			//cek kuncian
			// $cek_kunci = $this->cek_status_kuncian(date('m', strtotime($datalist->tanggal)), date('Y', strtotime($datalist->tanggal)));
			$link_edit = site_url('trans_rapbs/rapbs_edit/').$datalist->id;
			$link_detail = site_url('trans_rapbs/rapbs_detail/') . $datalist->id;
			//belum di verifikasi
			$row[] = '
				<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Detail" id="btn_detail">
					<i class="glyphicon glyphicon-info-sign"></i></a>
				<a class="btn btn-sm btn-primary" href="'.$link_edit.'" title="Edit" id="btn_edit" ><i class="glyphicon glyphicon-pencil"></i></a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteData(' . "'" . $datalist->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
			';
			
			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_rapbs->count_all($tahun),
						"recordsFiltered" => $this->m_rapbs->count_filtered($tahun),
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
		$status = $this->uri->segment(4);
		$query_header = $this->m_rapbs->get_detail_header($id, $status);
		$query = $this->m_rapbs->get_detail($id, $status);
		$sts = ($status == 'awal') ? 'awal' : 'finish';

		$data = array(
			'data_user' => $query_user,
			'hasil_header' => $query_header,
			'hasil_data' => $query,
			'sts' => $sts
		);

		// echo $this->db->last_query();
		
		$content = [
			'css' 	=> 'cssPenerimaan',
			'modal' => null,
			'js'	=> 'jsPenerimaan',
			'view'	=> 'view_detail_penerimaan'
		];

		$this->template_view->load_view($content, $data);
	}

	public function add_rapbs()
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);
		$get_field = $this->m_rapbs->get_data_field();

		$data = array(
			'data_user' => $data_user,
			'data_field' => $get_field
		);

		$content = [
			'css' 	=> 'cssTransRapbs',
			'modal' =>  null,
			'js'	=> 'jsTransRapbs',
			'view'	=> 'view_add_trans_rapbs'
		];

		$this->template_view->load_view($content, $data);
	}

	public function saveimport()
	{
		if (isset($_FILES["file"]["name"])) {
			$arr_data = [];
			$path = $_FILES["file"]["tmp_name"];
			$objPHPExcel = PHPExcel_IOFactory::load($path);

			//get only the Cell Collection
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			
			echo "<pre>";
			print_r ($cell_collection);
			echo "</pre>";
			exit;
			//extract to a PHP readable array format
			foreach ($cell_collection as $cell) {
				$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
				$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
				$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getFormattedValue();

				//The header will/should be in row 1 only. of course, this can be modified to suit your need.
				if ($row == 1) {
					$header[$row][$column] = $data_value;
				}else{
					$arr_data[$row][$column] = $data_value;
				} 
				
				// else {
				// 	if ($column <> "D") {
				// 		$arr_data[$row][$column] = str_replace(' ', '', $data_value);
				// 	} else {
				// 		$arr_data[$row][$column] = $data_value;
				// 	}
				// }
				// if ($row == 12) {
				// break;
				// }
			}	
		}
	}

	public function proses_penerimaan()
	{
		$timestamp = date('Y-m-d H:i:s');
		$keterangan = $this->input->post('i_keterangan');
		$satuan = $this->input->post('i_satuan');
		$qty = $this->input->post('i_qty');
		$harga_raw = $this->input->post('i_harga_raw');
		$harga_total_raw = $this->input->post('i_harga_total_raw');
		$gambar = $this->input->post('i_gambar');
		$ceklis = $this->input->post('ceklis');
		$is_bos = ($this->input->post('is_bos') == NULL) ? 0 : 1;
		$bln_int = (int)date('m');
		$thn_int = (int)date('Y');

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

				$kode = $this->m_rapbs->getKodePenerimaan();
				$kode_detail = $this->m_rapbs->getKodePenerimaanDetail();

				$data_header = [
					'id' => $kode,
					'user_id' => $this->session->userdata('id_user'),
					'tanggal' => date('Y-m-d'),
					'status' => 1,
					'created_at' => $timestamp,
					'is_bos' => $is_bos
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
					'tipe_akun' => null,
					'kode_akun' => null,
					'sub1_akun' => null,
					'sub2_akun' => null,
					'tipe_transaksi' => 1,
					'created_at' => $timestamp
				];

				$this->m_rapbs->save($data_header, $data_isi, $data_verifikasi);

				if ($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$this->session->set_flashdata('feedback_gagal','Gagal Input dan Verifikasi data.'); 
					redirect(base_url()."penerimaan?bulan=$bln_int&tahun=$thn_int#tab_progress");
				}
				else {
					$this->db->trans_commit();
					$this->session->set_flashdata('feedback_success','Berhasil Input dan Verifikasi data.'); 
					redirect(base_url()."penerimaan?bulan=$bln_int&tahun=$thn_int#tab_progress");
				}
			}else{
				$this->db->trans_rollback();
				$this->session->set_flashdata('feedback_gagal','Mohon Lengkapi Kelengkapan Data'); 
				redirect(base_url()."penerimaan?bulan=$bln_int&tahun=$thn_int#tab_progress");
			}
		}else{
			$this->db->trans_rollback();
			$this->session->set_flashdata('feedback_gagal','Mohon centang pilihan setuju'); 
			redirect(base_url()."penerimaan?bulan=$bln_int&tahun=$thn_int#tab_progress");
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

		$query = $this->m_rapbs->get_detail($id, 'edit');

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

	public function update_penerimaan()
	{
		$timestamp = date('Y-m-d H:i:s');
		$kode = $this->input->post('i_id_header');
		$kode_detail = $this->input->post('i_id_detail');  
		$keterangan = $this->input->post('i_keterangan');
		$satuan = $this->input->post('i_satuan');
		$qty = $this->input->post('i_qty');
		$harga_raw = $this->input->post('i_harga_raw');
		$harga_total_raw = $this->input->post('i_harga_total_raw');
		$gambar = $this->input->post('i_gambar');
		$is_bos = ($this->input->post('is_bos') == NULL) ? 0 : 1;
		$ceklis = $this->input->post('ceklis');
		$bln_int = (int)date('m');
		$thn_int = (int)date('Y');

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

				$data_header = [
					'user_id' => $this->session->userdata('id_user'),
					'status' => 1,
					'updated_at' => $timestamp,
					'is_bos' => $is_bos
				];

				$this->m_rapbs->update_data(['id' => $kode], $data_header, 'tbl_trans_masuk');

				$data_isi = [
					'keterangan' => $keterangan,
					'satuan' => $satuan,
					'qty' => $qty,
					'status' => 1
				];

				$this->m_rapbs->update_data(['id' => $kode_detail], $data_isi, 'tbl_trans_masuk_detail');
								
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
					'tipe_akun' => null,
					'kode_akun' => null,
					'sub1_akun' => null,
					'sub2_akun' => null,
					'tipe_transaksi' => 1,
					'created_at' => $timestamp
				];
				
				$this->m_rapbs->save(null, null, $data_verifikasi);

				if ($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$this->session->set_flashdata('feedback_failed','Gagal Input dan Verifikasi data.'); 
					redirect(base_url()."penerimaan?bulan=$bln_int&tahun=$thn_int#tab_progress");
				}
				else {
					$this->db->trans_commit();
					$this->session->set_flashdata('feedback_success','Berhasil Input dan Verifikasi data.'); 
					redirect(base_url()."penerimaan?bulan=$bln_int&tahun=$thn_int#tab_progress");
				}
			}else{
				$this->db->trans_rollback();
				$this->session->set_flashdata('feedback_failed','Mohon Lengkapi Kelengkapan Data'); 
				redirect(base_url()."penerimaan?bulan=$bln_int&tahun=$thn_int#tab_progress");
			}
		}else{
			$this->db->trans_rollback();
			$this->session->set_flashdata('feedback_failed','Mohon centang pilihan setuju'); 
			redirect(base_url()."penerimaan?bulan=$bln_int&tahun=$thn_int#tab_progress");
		}
	}

	public function cek_status_kuncian($bulan, $tahun)
	{
		$q = $this->db->query("SELECT * FROM tbl_log_kunci WHERE bulan = '" . $bulan . "' and tahun ='" . $tahun . "'");
		if ($q->num_rows() > 0) {
			$query = $q->row();
			if ($query->is_kunci == '1') {
				return TRUE;
			} else {
				return FALSE;
			}
		}else{
			return FALSE;
		}	
	}

	// =====================================================================================================================

	

	public function cetak_nota_pengeluaran()
	{
		$this->load->library('Pdf_gen');

		$id = $this->uri->segment(3);
		$query_header = $this->m_rapbs->get_detail_header($id);
		$query = $this->m_rapbs->get_detail($id);

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
		$query = $this->m_rapbs->lookup($q);
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
		$query = $this->m_rapbs->lookup2($rowIdBrg);
		echo json_encode($query);
	}

}