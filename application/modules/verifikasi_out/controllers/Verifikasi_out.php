<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifikasi_out extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//profil data
		$this->load->model('profil/mod_profil','prof');
		$this->load->model('mod_verifikasi_out','m_vout');
	}

	public function index()
	{	
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);

		$data = array(
			'data_user' => $data_user
		);

		$content = [
			'css' 	=> 'cssVerifikasiOut',
			'modal' => 'modalVerifikasiOut',
			'js'	=> 'jsVerifikasiOut',
			'view'	=> 'view_list_verifikasi_out'
		];

		$this->template_view->load_view($content, $data);
	}

	public function list_verifikasi()
	{
		$list = $this->m_vout->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $listV) {
			$link_detail = site_url('verifikasi_out/verifikasi_detail/').$listV->id;
			$link_verifikasi = site_url('verifikasi_out/proses/').$listV->id;
			$no++;
			$row = array();
			$row[] = $listV->id;
			$row[] = date('d-m-Y', strtotime($listV->tanggal));
			$row[] = $listV->username;
			$row[] = $listV->pemohon;
			
			$row[] = '
				<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Detail" id="btn_detail" onclick="">
					<i class="glyphicon glyphicon-info-sign"></i></a>
				<a class="btn btn-sm btn-primary" href="'.$link_verifikasi.'" title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>
			';

			$data[] = $row;
		}//end loop

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->m_vout->count_all(),
			"recordsFiltered" => $this->m_vout->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function proses($id)
	{
		$id_user = $this->session->userdata('id_user'); 
		$data_user = $this->prof->get_detail_pengguna($id_user);
		$data_pengeluaran = $this->m_vout->get_by_id($id);
		$data_detail = $this->m_vout->get_detail_by_id($id);

		$data = array(
			'data_user'	 => $data_user,
			'data_form'	 => $data_pengeluaran,
			'data_detail' => $data_detail
		);

		$content = [
			'css' 	=> 'cssVerifikasiOut',
			'modal' => 'modalVerifikasiOut',
			'js'	=> 'jsVerifikasiOut',
			'view'	=> 'view_list_verifikasi_out_proses'
		];

		$this->template_view->load_view($content, $data);
	}

	public function update_trans_order()
	{
		$this->_validate();
		//delete id order in tabel detail
		$id = $this->input->post('fieldIdOrder');
		$initiated_date = date('Y-m-d H:i:s');
		$hapus_data_order_detail = $this->m_vout->hapus_data_order_detail($id);

		//update header
		$data_header = array(
			'tgl_trans_order' => $this->input->post('fieldTanggalOrder'),
		); 
		$this->m_vout->update_data_header_detail(array('id_trans_order' => $id), $data_header);

		//proses insert ke tabel detail
		$hitung_detail = count($this->input->post('fieldIdBarangOrder'));
		$data_order_detail = array();
			for ($i=0; $i < $hitung_detail; $i++) 
			{
			$data_order_detail[$i] = array(
				'id_trans_order' =>$id,
				'id_barang' => $this->input->post('fieldIdBarangOrder')[$i],
				'id_satuan' => $this->input->post('fieldIdSatuanOrder')[$i],
				'id_trans_beli' => $this->input->post('fieldIdBeli')[$i],
				'qty_order' => $this->input->post('fieldJumlahBarangOrder')[$i],
				'keterangan_order' => $this->input->post('fieldKeteranganBarangOrder')[$i],
				'tgl_trans_order_detail' => $this->input->post('fieldTanggalOrder'),
				'timestamp' => $initiated_date, 
				);
			}

		$insert_update = $this->m_vout->insert_update($data_order_detail);

		//update tbl_trans_beli_detail
		$id_m_vout = $this->input->post('fieldIdOrder');
		$result_id_order_detail = $this->m_vout->get_id_trans_order_detail($id_m_vout);
		$result_id_beli_detail = $this->m_vout->get_id_trans_beli_detail($id_m_vout);

		$data_id_order_detail = array();
		//cek apablia array bernilai kosong
		if (count($result_id_beli_detail) != 0) {
			//ambil variabel isi array untuk di foreach
			foreach ($result_id_beli_detail as $key => $val) {
				//jika tdk terdapat data kosong, eksekusi loop
				if ($val['id_trans_beli_detail'] != null) {
					// loop dengan batas key pada hasil foreach, key bernilai dinamis berdasarkan statement if diatas
					for ($i=0; $i <= $key; $i++) { 		
						$data_id_order_detail[$i] = array(
							'id_trans_beli_detail' => $result_id_beli_detail[$i]['id_trans_beli_detail'],
							'id_trans_order_detail' => $result_id_order_detail[$i]['id_trans_order_detail'],
						);
					}		
				}			
			}
			$this->db->update_batch('tbl_trans_beli_detail',$data_id_order_detail,'id_trans_beli_detail');
		}
		echo json_encode(array(
			"status" => TRUE,
			"pesan_update" => 'Data Transaksi Order Barang No.'.$id.' Berhasil diupdate'
		));
	}

	public function delete_trans_order($id)
	{
		$this->m_vout->delete_by_id($id);
		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Transaksi Order Barang No.'.$id.' Berhasil dihapus'
		));
	}

	public function trans_order_detail()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query_user = $this->prof->get_detail_pengguna($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$id_trans_order = $this->uri->segment(3); 
		$query_header = $this->m_vout->get_detail_header($id_trans_order);
		$query = $this->m_vout->get_detail($id_trans_order);

		$data = array(
			'css'=>'cssTransOrder',
			'js'=>'jsTransOrder',
			'content' => 'view_detail_trans_order',
			'title' => 'PT.Surya Putra Barutama',
			'hasil_header' => $query_header,
			'data_user' => $query_user, 
			'hasil_data' => $query,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			);
		$this->load->view('view_home',$data);
	}

	public function cetak_report_trans_order_detail()
	{
		$this->load->library('Pdf_gen');

		$id_trans_order = $this->uri->segment(3);
		$query_header = $this->m_vout->get_detail_header($id_trans_order);
		$query = $this->m_vout->get_detail($id_trans_order);

		$data = array(
			'title' => 'Report Transaksi Permintaan',
			'hasil_header' => $query_header,
			'hasil_data' => $query, 
			);

	    $html = $this->load->view('view_detail_trans_order_report', $data, true);
	    
	    $filename = 'report_permintaan_'.$id_trans_order.'_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function get_header_modal_form()
	{
		$data = array(
			'kode_pencatatan'=> $this->m_vout->getKodePengeluaran(),
		);

		echo json_encode($data);
	}

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
		$query = $this->m_vout->lookup($q);
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
		$query = $this->m_vout->lookup2($rowIdBrg);
		echo json_encode($query);
	}

}