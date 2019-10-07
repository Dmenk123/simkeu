<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trans_masuk extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//cek apablia session kosong
		if ($this->session->userdata('username') === null) {
			//direct ke controller login
			redirect('login');
		}
		$this->load->model('mod_trans_masuk','t_masuk');
		//profil data
		$this->load->model('profil/mod_profil','prof');
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

	public function index()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query = $this->prof->get_detail_pengguna($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		//simpan data pada array
		$data = array(
			'content'=>'view_list_trans_masuk',
			'css'=>'cssTransMasuk',
			'modal'=>'modalTransMasuk',
			'js'=>'jsTransMasuk',
			'title' => 'PT.Surya Putra Barutama',
			'data_user' => $query,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
		);
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function add_trans_masuk()
	{
		$this->_validate();
		$initiated_date = date('Y-m-d H:i:s');
		//for table trans_masuk
		$data_masuk = array(			
				'id_trans_masuk' => $this->input->post('fieldIdMasuk'),
				'id_user' => $this->input->post('fieldIdUserMasuk'),
				'id_supplier' => $this->input->post('fieldIdSupplier'),
				'tgl_trans_masuk' => $this->input->post('fieldTanggalMasuk'),
				'timestamp_trans_masuk' => $initiated_date, 
			);

		//for table trans_beli_detail
		$hitung_beli = count($this->input->post('fieldIdBarangMasuk'));
		$data_beli_detail = array();
			for ($i=0; $i < $hitung_beli; $i++) 
			{
			$data_beli_detail[$i] = array(
				'id_trans_beli_detail' => $this->input->post('fieldIdTransBeliDetail')[$i],
				'id_trans_masuk' => $this->input->post('fieldIdMasuk'),
				);
			}

		$this->db->update_batch('tbl_trans_beli_detail',$data_beli_detail,'id_trans_beli_detail');

		//for table trans_masuk_detail
		$hitung_masuk = count($this->input->post('fieldIdBarangMasuk'));
		$data_masuk_detail = array();
			for ($i=0; $i < $hitung_masuk; $i++) 
			{
			$data_masuk_detail[$i] = array(
				'id_trans_beli_detail' => $this->input->post('fieldIdTransBeliDetail')[$i],
				'id_trans_masuk' => $this->input->post('fieldIdMasuk'),
				'id_trans_beli' => $this->input->post('fieldIdPembelian'),
				'id_barang' => $this->input->post('fieldIdBarangMasuk')[$i],
				'id_satuan' => $this->input->post('fieldIdSatuanMasuk')[$i],
				'qty_masuk' => $this->input->post('fieldJumlahBarangMasuk')[$i],
				'keterangan_masuk' => $this->input->post('fieldKeteranganBarangMasuk')[$i],
				'tgl_trans_masuk_detail' => $this->input->post('fieldTanggalMasuk'),
				'timestamp' => $initiated_date, 
				);
			}
								
		$insert = $this->t_masuk->save($data_masuk, $data_masuk_detail);
		//var_dump($data_beli, $data_order_detail, $data_beli_detail);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_tambah" => 'Data Transaksi Penerimaan Barang Berhasil ditambahkan'
			));
	}

	public function ajax_get_header_form()
	{
		$data = array(
			'kode_trans_beli'=> $this->t_masuk->getKodeTransMasuk(),
		);

		echo json_encode($data);
	}

	public function cetak_tanda_terima_barang()
	{
		$this->load->library('Pdf_gen');

		$id_trans_masuk = $this->uri->segment(3);
		$query_header = $this->t_masuk->get_detail_penerimaan_header($id_trans_masuk);
		$query = $this->t_masuk->get_detail_penerimaan($id_trans_masuk);

		$data = array(
			'title' => 'Tanda Terima Barang',
			'hasil_header' => $query_header,
			'hasil_data' => $query, 
			);

	    $html = $this->load->view('view_detail_trans_masuk_report', $data, true);
	    
	    $filename = 'tanda_terima_'.$id_trans_masuk.'_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function delete_trans_masuk($id)
	{
		$nilai = array(
               'id_trans_masuk' => 0,
            );

		$this->t_masuk->delete_by_id($id);
		$this->t_masuk->update_by_id(array('id_trans_masuk' => $id), $nilai);

		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Transaksi Penerimaan Barang No.'.$id.' Berhasil dihapus'
		));
	}

	public function edit_trans_masuk($id)
	{
		$data = array(
			'data_header' => $this->t_masuk->get_edit_penerimaan_header($id),
			'data_isi' => $this->t_masuk->get_detail_penerimaan($id),
		);

		echo json_encode($data);
	}

	public function list_trans_masuk()
	{
		$list = $this->t_masuk->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $listTransMasuk) {
			$link_detail = site_url('trans_masuk/trans_masuk_detail/').$listTransMasuk->id_trans_masuk;
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $listTransMasuk->id_trans_masuk;
			$row[] = $listTransMasuk->username;
			$row[] = $listTransMasuk->tgl_trans_masuk;
			$row[] = $listTransMasuk->nama_supplier;
			//add html for action button
			$row[] = '<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Penerimaan Detail" id="btn_detail" onclick=""><i class="glyphicon glyphicon-info-sign"></i> '.$listTransMasuk->jml.' Items</a>
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editTransMasuk('."'".$listTransMasuk->id_trans_masuk."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteTransMasuk('."'".$listTransMasuk->id_trans_masuk."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->t_masuk->count_all(),
						"recordsFiltered" => $this->t_masuk->count_filtered(),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function list_pembelian()
	{
		// get data from ajax object
		$id_trans_beli = $this->input->post('idTransBeli');

		$data = array(
			'data_list' => $this->t_masuk->get_list_pembelian($id_trans_beli),
			'data_supplier' => $this->t_masuk->get_list_supplier($id_trans_beli),
		);

		echo json_encode($data);
	}

	public function suggest_id_pembelian()
	{
		$barang = [];
		if(!empty($this->input->get("q"))){
			$key = $_GET['q'];
			$query = $this->t_masuk->lookup_id_pembelian($key);
		}else{
			$query = $this->t_masuk->lookup_id_pembelian();
		}
		
		foreach ($query as $row) {
			$barang[] = array(
						'id' => $row->id_trans_beli,
						'text' => $row->id_trans_beli,
					);
		}
		echo json_encode($barang);
	}

	public function trans_masuk_detail()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query_user = $this->prof->get_detail_pengguna($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$id_trans_masuk = $this->uri->segment(3); 
		$query_header = $this->t_masuk->get_detail_penerimaan_header($id_trans_masuk);
		$query = $this->t_masuk->get_detail_penerimaan($id_trans_masuk);

		$data = array(
			'css'=>'cssTransMasuk',
			'js'=>'jsTransMasuk',
			'content' => 'view_detail_trans_masuk',
			'title' => 'PT.Surya Putra Barutama',
			'hasil_header' => $query_header,
			'hasil_data' => $query, 
			'data_user' => $query_user,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			);
		$this->load->view('view_home',$data);
	}

	public function update_trans_masuk()
	{
		//delete in tabel_masuk_detail where id_t_beli
		$id = $this->input->post('fieldIdMasuk');
		$initiated_date = date('Y-m-d H:i:s');
		$hapus_data_masuk_detail = $this->t_masuk->hapus_data_masuk_detail($id);

		//update header
		$data_header = array(
			'tgl_trans_masuk' => $this->input->post('fieldTanggalMasuk'),
		); 
		$this->t_masuk->update_data_header_masuk(array('id_trans_masuk' => $id), $data_header);

		//proses insert ke tabel_masuk_detail
		$hitung_detail = count($this->input->post('fieldIdBarangMasuk'));
		$data_masuk_detail = array();
			for ($i=0; $i < $hitung_detail; $i++) 
			{
			$data_masuk_detail[$i] = array(
				'id_trans_masuk' =>$id,
				'id_trans_beli_detail' => $this->input->post('fieldIdTransBeliDetail')[$i],
				'id_trans_beli' => $this->input->post('fieldIdBeli')[$i],
				'id_barang' => $this->input->post('fieldIdBarangMasuk')[$i],
				'id_satuan' => $this->input->post('fieldIdSatuanMasuk')[$i],
				'qty_masuk' => $this->input->post('fieldJumlahBarangMasuk')[$i],
				'keterangan_masuk' => $this->input->post('fieldKeteranganBarangMasuk')[$i],
				'tgl_trans_masuk_detail' => $this->input->post('fieldTanggalMasuk'),
				'timestamp' => $initiated_date, 
				);
			}
		
		$insert_update = $this->t_masuk->insert_update_penerimaan($data_masuk_detail);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_update" => 'Data Transaksi Penerimaan Barang No.'.$id.' Berhasil diupdate'
		));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('fieldTanggalMasuk') == '')
		{
			$data['inputerror'][] = 'fieldTanggalMasuk';
			$data['error_string'][] = 'tanggal Penerimaan is required';
			$data['status'] = FALSE;
		}
		
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
	}

}