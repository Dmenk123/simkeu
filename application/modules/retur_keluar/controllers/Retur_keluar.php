<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_keluar extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//cek apablia session kosong
		if ($this->session->userdata('username') === null) {
			//direct ke controller login
			redirect('login');
		}
		$this->load->model('mod_retur_keluar','r_keluar');
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
			'content'=>'view_list_retur_keluar',
			'css'=>'cssReturKeluar',
			'modal'=>'modalReturKeluar',
			'js'=>'jsReturKeluar',
			'title' => 'PT.Surya Putra Barutama',
			'data_user' => $query,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
		);
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function add_retur_keluar()
	{
		//$this->_validate();
		$initiated_date = date('Y-m-d H:i:s');
		//for table retur_keluar
		$data_retur_keluar = array(			
				'id_retur_keluar' => $this->input->post('fieldIdReturKeluar'),
				'id_user' => $this->input->post('fieldIdUserReturKeluar'),
				'id_supplier' => $this->input->post('fieldIdSupplier'),
				'tgl_retur_keluar' => $this->input->post('fieldTanggalReturKeluar'),
				'timestamp_retur_keluar' => $initiated_date, 
			);

		//for table retur_keluar_detail
		$hitung_retur = count($this->input->post('fieldIdBarangReturKeluar'));
		$data_retur_keluar_detail = array();
			for ($i=0; $i < $hitung_retur; $i++) 
			{
			$data_retur_keluar_detail[$i] = array(
				'id_trans_masuk' => $this->input->post('fieldIdPenerimaan'),
				'id_retur_keluar' => $this->input->post('fieldIdReturKeluar'),
				'id_barang' => $this->input->post('fieldIdBarangReturKeluar')[$i],
				'id_satuan' => $this->input->post('fieldIdSatuanReturKeluar')[$i],
				'qty_retur_keluar' => $this->input->post('fieldJumlahBarangReturKeluar')[$i],
				'keterangan_retur_keluar' => $this->input->post('fieldKeteranganBarangReturKeluar')[$i],
				'timestamp' => $initiated_date, 
				);
			}
								
		$insert = $this->r_keluar->save($data_retur_keluar, $data_retur_keluar_detail);
		//var_dump($data_beli, $data_order_detail, $data_beli_detail);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_tambah" => 'Data Transaksi Pengeluaran Retur Barang Berhasil ditambahkan'
			));
	}

	public function ajax_get_header_form()
	{
		$data = array(
			'kode_retur_keluar'=> $this->r_keluar->getKodeReturKeluar(),
		);

		echo json_encode($data);
	}

	public function cetak_surat_jalan_retur()
	{
		$this->load->library('Pdf_gen');

		$id_retur_keluar = $this->uri->segment(3);
		$query_header = $this->r_keluar->get_detail_retur_keluar_header($id_retur_keluar);
		$query = $this->r_keluar->get_detail_retur_keluar($id_retur_keluar);

		$data = array(
			'title' => 'Surat Jalan Retur Barang',
			'hasil_header' => $query_header,
			'hasil_data' => $query, 
			);

	    $html = $this->load->view('view_detail_retur_keluar_report', $data, true);
	    
	    $filename = 'surat_jalan_retur_'.$id_retur_keluar.'_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function delete_retur_keluar($id)
	{
		$this->r_keluar->delete_by_id($id);

		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Pengeluaran Retur Barang No.'.$id.' Berhasil dihapus'
		));
	}

	public function edit_retur_keluar($id)
	{
		$data = array(
			'data_header' => $this->r_keluar->get_detail_retur_keluar_header($id),
			'data_isi' => $this->r_keluar->get_detail_retur_keluar($id),
		);

		echo json_encode($data);
	}

	public function list_retur_keluar()
	{
		$list = $this->r_keluar->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $listReturKeluar) {
			$link_detail = site_url('retur_keluar/retur_keluar_detail/').$listReturKeluar->id_retur_keluar;
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $listReturKeluar->id_retur_keluar;
			$row[] = $listReturKeluar->username;
			$row[] = $listReturKeluar->tgl_retur_keluar;
			$row[] = $listReturKeluar->nama_supplier;
			//add html for action button
			$row[] = '<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Retur Keluar Detail" id="btn_detail" onclick=""><i class="glyphicon glyphicon-info-sign"></i> '.$listReturKeluar->jml.' Items</a>
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editReturKeluar('."'".$listReturKeluar->id_retur_keluar."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteReturKeluar('."'".$listReturKeluar->id_retur_keluar."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->r_keluar->count_all(),
						"recordsFiltered" => $this->r_keluar->count_filtered(),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function list_penerimaan()
	{
		// get data from ajax object
		$id_trans_masuk = $this->input->post('idTransMasuk');

		$data = array(
			'data_list' => $this->r_keluar->get_list_penerimaan($id_trans_masuk),
			'data_supplier' => $this->r_keluar->get_list_supplier($id_trans_masuk),
		);

		echo json_encode($data);
	}

	public function suggest_id_penerimaan()
	{
		$id_masuk = [];
		if(!empty($this->input->get("q"))){
			$key = $_GET['q'];
			$query = $this->r_keluar->lookup_id_penerimaan($key);
		}else{
			$query = $this->r_keluar->lookup_id_penerimaan();
		}
		
		foreach ($query as $row) {
			$id_masuk[] = array(
						'id' => $row->id_trans_masuk,
						'text' => $row->id_trans_masuk,
					);
		}
		echo json_encode($id_masuk);
	}

	public function retur_keluar_detail()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query_user = $this->mod_pengguna->get_detail_user($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$id_retur_keluar = $this->uri->segment(3); 
		$query_header = $this->r_keluar->get_detail_retur_keluar_header($id_retur_keluar);
		$query = $this->r_keluar->get_detail_retur_keluar($id_retur_keluar);

		$data = array(
			'css'=>'cssReturKeluar',
			'js'=>'jsReturKeluar',
			'content' => 'view_detail_retur_keluar',
			'title' => 'PT.Surya Putra Barutama',
			'hasil_header' => $query_header,
			'hasil_data' => $query, 
			'data_user' => $query_user,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			);
		$this->load->view('view_home',$data);
	}

	public function update_retur_keluar()
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