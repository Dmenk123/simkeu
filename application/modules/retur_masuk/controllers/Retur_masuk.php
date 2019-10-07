<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_masuk extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//cek apablia session kosong
		if ($this->session->userdata('username') === null) {
			//direct ke controller login
			redirect('login');
		}
		$this->load->model('mod_retur_masuk','r_masuk');
		$this->load->model('pengguna/mod_pengguna');
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
		$query = $this->mod_pengguna->get_detail_user($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		//simpan data pada array
		$data = array(
			'content'=>'view_list_retur_masuk',
			'css'=>'cssReturMasuk',
			'modal'=>'modalReturMasuk',
			'js'=>'jsReturMasuk',
			'title' => 'PT.Surya Putra Barutama',
			'data_user' => $query,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
		);
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function add_retur_masuk()
	{
		//$this->_validate();
		$initiated_date = date('Y-m-d H:i:s');
		//for table retur_masuk
		$data_retur_masuk = array(			
				'id_retur_masuk' => $this->input->post('fieldIdReturMasuk'),
				'id_user' => $this->input->post('fieldIdUserReturMasuk'),
				'id_supplier' => $this->input->post('fieldIdSupplier'),
				'tgl_retur_masuk' => $this->input->post('fieldTanggalReturMasuk'),
				'timestamp_retur_masuk' => $initiated_date, 
			);

		//for table retur_keluar_detail
		$hitung_retur_keluar = count($this->input->post('fieldIdReturKeluarDetail'));
		$data_order_keluar_detail = array();
			for ($i=0; $i < $hitung_retur_keluar; $i++) 
			{
			$data_order_keluar_detail[$i] = array(
				'id_retur_keluar_detail' => $this->input->post('fieldIdReturKeluarDetail')[$i],
				'id_retur_masuk' => $this->input->post('fieldIdReturMasuk'),
				);
			}

		$this->db->update_batch('tbl_retur_keluar_detail',$data_order_keluar_detail,'id_retur_keluar_detail');

		//for table retur_masuk_detail
		$hitung_retur_masuk = count($this->input->post('fieldIdBarangReturMasuk'));
		$data_retur_masuk_detail = array();
			for ($i=0; $i < $hitung_retur_masuk; $i++) 
			{
			$data_retur_masuk_detail[$i] = array(
				'id_retur_masuk' => $this->input->post('fieldIdReturMasuk'),
				'id_retur_keluar' => $this->input->post('fieldIdReturKeluar'),
				'id_barang' => $this->input->post('fieldIdBarangReturMasuk')[$i],
				'id_satuan' => $this->input->post('fieldIdSatuanReturMasuk')[$i],
				'qty_retur_masuk' => $this->input->post('fieldJumlahBarangReturMasuk')[$i],
				'keterangan_retur_masuk' => $this->input->post('fieldKeteranganBarangReturMasuk')[$i],
				'timestamp' => $initiated_date, 
				);
			}
								
		$insert = $this->r_masuk->save($data_retur_masuk, $data_retur_masuk_detail);
		//var_dump($data_beli, $data_order_detail, $data_beli_detail);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_tambah" => 'Data Transaksi Penerimaan Retur Barang Berhasil ditambahkan'
			));
	}

	public function ajax_get_header_form()
	{
		$data = array(
			'kode_retur_masuk'=> $this->r_masuk->getKodeReturMasuk(),
		);

		echo json_encode($data);
	}

	public function cetak_tanda_terima_retur()
	{
		$this->load->library('Pdf_gen');

		$id_retur_masuk = $this->uri->segment(3);
		$query_header = $this->r_masuk->get_detail_retur_masuk_header($id_retur_masuk);
		$query = $this->r_masuk->get_detail_retur_masuk($id_retur_masuk);

		$data = array(
			'title' => 'Tanda Terima Retur Barang',
			'hasil_header' => $query_header,
			'hasil_data' => $query, 
			);

	    $html = $this->load->view('view_detail_retur_masuk_report', $data, true);
	    
	    $filename = 'tanda_terima_retur_'.$id_retur_masuk.'_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function delete_retur_masuk($id)
	{
		$this->r_masuk->delete_by_id($id);

		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Penerimaan Retur Barang No.'.$id.' Berhasil dihapus'
		));
	}

	public function edit_retur_masuk($id)
	{
		$data = array(
			'data_header' => $this->r_masuk->get_detail_retur_masuk_header($id),
			'data_isi' => $this->r_masuk->get_detail_retur_masuk($id),
		);

		echo json_encode($data);
	}

	public function list_retur_masuk()
	{
		$list = $this->r_masuk->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $listReturMasuk) {
			$link_detail = site_url('retur_masuk/retur_masuk_detail/').$listReturMasuk->id_retur_masuk;
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $listReturMasuk->id_retur_masuk;
			$row[] = $listReturMasuk->username;
			$row[] = $listReturMasuk->tgl_retur_masuk;
			$row[] = $listReturMasuk->nama_supplier;
			//add html for action button
			$row[] = '<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Retur Masuk Detail" id="btn_detail" onclick=""><i class="glyphicon glyphicon-info-sign"></i> '.$listReturMasuk->jml.' Items</a>
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editReturMasuk('."'".$listReturMasuk->id_retur_masuk."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteReturMasuk('."'".$listReturMasuk->id_retur_masuk."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->r_masuk->count_all(),
						"recordsFiltered" => $this->r_masuk->count_filtered(),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function list_retur_keluar()
	{
		// get data from ajax object
		$id_retur_keluar = $this->input->post('idReturKeluar');

		$data = array(
			'data_list' => $this->r_masuk->get_list_retur_keluar($id_retur_keluar),
			'data_supplier' => $this->r_masuk->get_list_supplier($id_retur_keluar),
		);

		echo json_encode($data);
	}

	public function suggest_id_retur_keluar()
	{
		$id_retur_keluar = [];
		if(!empty($this->input->get("q"))){
			$key = $_GET['q'];
			$query = $this->r_masuk->lookup_id_retur_keluar($key);
		}else{
			$query = $this->r_masuk->lookup_id_retur_keluar();
		}
		
		foreach ($query as $row) {
			$id_retur_keluar[] = array(
						'id' => $row->id_retur_keluar,
						'text' => $row->id_retur_keluar,
					);
		}
		echo json_encode($id_retur_keluar);
	}

	public function retur_masuk_detail()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query_user = $this->mod_pengguna->get_detail_user($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$id_retur_masuk = $this->uri->segment(3); 
		$query_header = $this->r_masuk->get_detail_retur_masuk_header($id_retur_masuk);
		$query = $this->r_masuk->get_detail_retur_masuk($id_retur_masuk);

		$data = array(
			'css'=>'cssReturMasuk',
			'js'=>'jsReturMasuk',
			'content' => 'view_detail_retur_masuk',
			'title' => 'PT.Surya Putra Barutama',
			'hasil_header' => $query_header,
			'hasil_data' => $query, 
			'data_user' => $query_user,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			);
		$this->load->view('view_home',$data);
	}

	public function update_retur_masuk()
	{
		//delete in tabel_retur_keluar_detail where id_r_keluar
		$id = $this->input->post('fieldIdReturKeluar');
		$initiated_date = date('Y-m-d H:i:s');
		$hapus_retur_keluar_detail = $this->r_keluar->hapus_retur_keluar_detail($id);

		//update header
		$data_header = array(
			'tgl_retur_keluar' => $this->input->post('fieldTanggalReturKeluar'),
		); 
		$this->r_keluar->update_header_retur_keluar(array('id_retur_keluar' => $id), $data_header);

		//proses insert ke tabel_masuk_detail
		$hitung_detail = count($this->input->post('fieldIdBarangReturKeluar'));
		$data_retur_keluar_detail = array();
			for ($i=0; $i < $hitung_detail; $i++) 
			{
			$data_retur_keluar_detail[$i] = array(
				'id_retur_keluar' =>$id,
				'id_trans_masuk' => $this->input->post('fieldIdMasuk')[$i],
				'id_barang' => $this->input->post('fieldIdBarangReturKeluar')[$i],
				'id_satuan' => $this->input->post('fieldIdSatuanReturKeluar')[$i],
				'qty_retur_keluar' => $this->input->post('fieldJumlahBarangReturKeluar')[$i],
				'keterangan_retur_keluar' => $this->input->post('fieldKeteranganBarangReturKeluar')[$i],
				'timestamp' => $initiated_date, 
				);
			}
		
		$insert_update = $this->r_keluar->insert_update_retur_keluar($data_retur_keluar_detail);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_update" => 'Data Retur Pengeluaran Barang No.'.$id.' Berhasil diupdate'
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