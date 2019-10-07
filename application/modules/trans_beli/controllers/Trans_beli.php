<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trans_beli extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//cek apablia session kosong
		if ($this->session->userdata('username') === null) {
			//direct ke controller login
			redirect('login');
		}elseif ($this->session->userdata('id_level_user') != '1' && $this->session->userdata('id_level_user') != '4') {
			redirect('home/oops');
		}

		//pesan stok dibawah rop
		$this->load->model('Mod_home');
		$barang = $this->Mod_home->get_barang();
			foreach ($barang as $key) {
				if ($key->stok_barang < $key->rop_barang) {
					$this->session->set_flashdata('cek_stok', 'Terdapat Stok Barang dibawah nilai Reorder Point, Mohon di cek ulang / melakukan permintaan');
				}
			}
		$this->load->model('mod_trans_beli','t_beli');
		$this->load->model('trans_order/mod_trans_order');
		$this->load->model('pesan/mod_pesan','psn');
		//profil data
		$this->load->model('profil/mod_profil','prof');
	}

	public function index()
	{	
		$id_user = $this->session->userdata('id_user'); 
		$query = $this->prof->get_detail_pengguna($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		//simpan data pada array
		$data = array(
			'content'=>'view_list_trans_beli',
			'css'=>'cssTransBeli',
			'modal'=>'modalTransBeli',
			'js'=>'jsTransBeli',
			'title' => 'PT.Surya Putra Barutama',
			'data_user' => $query,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
		);
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function add_trans_beli()
	{
		$this->_validate();
		$initiated_date = date('Y-m-d H:i:s');
		//for table trans_beli
		$data_beli = array(			
				'id_trans_beli' => $this->input->post('fieldIdBeli'),
				'id_user' => $this->input->post('fieldIdUserBeli'),
				'id_supplier' => $this->input->post('fieldNamaSupplier'),
				'tgl_trans_beli' => $this->input->post('fieldTanggalBeli'),
				'timestamp_trans_beli' => $initiated_date, 
			);

		//for table trans_order_detail
		$hitung_order = count($this->input->post('fieldIdBarangBeli'));
		$data_order_detail = array();
			for ($i=0; $i < $hitung_order; $i++) 
			{
			$data_order_detail[$i] = array(
				'id_trans_order_detail' => $this->input->post('fieldIdTransOrderDetail')[$i],
				'id_trans_beli' => $this->input->post('fieldIdBeli'),
				);
			}

		$this->db->update_batch('tbl_trans_order_detail',$data_order_detail,'id_trans_order_detail');

		//for table trans_beli_detail
		$hitung_beli = count($this->input->post('fieldIdBarangBeli'));
		$data_beli_detail = array();
			for ($i=0; $i < $hitung_beli; $i++) 
			{
			$data_beli_detail[$i] = array(
				'id_trans_order_detail' => $this->input->post('fieldIdTransOrderDetail')[$i],
				'id_trans_beli' => $this->input->post('fieldIdBeli'),
				'id_trans_order' => $this->input->post('fieldIdOrder'),
				'id_barang' => $this->input->post('fieldIdBarangBeli')[$i],
				'id_satuan' => $this->input->post('fieldIdSatuanBeli')[$i],
				'qty_beli' => $this->input->post('fieldJumlahBarangAccBeli')[$i],
				'keterangan_beli' => $this->input->post('fieldKeteranganBarangBeli')[$i],
				'tgl_trans_beli_detail' => $this->input->post('fieldTanggalBeli'), 
				'timestamp' => $initiated_date,
				);
			}
								
		$insert = $this->t_beli->save($data_beli, $data_beli_detail);
		//var_dump($data_beli, $data_order_detail, $data_beli_detail);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_tambah" => 'Data Transaksi Pembelian Barang Berhasil ditambahkan'
			));
	}

	public function ajax_get_header_form()
	{
		$data = array(
			'kode_trans_beli'=> $this->t_beli->getKodeTransBeli(),
		);

		echo json_encode($data);
	}

	public function cetak_report_trans_beli_detail()
	{
		$this->load->library('Pdf_gen');

		$id_trans_beli = $this->uri->segment(3);
		$query_header = $this->t_beli->get_detail_pembelian_header($id_trans_beli);
		$query = $this->t_beli->get_detail_pembelian($id_trans_beli);

		$data = array(
			'title' => 'Form Purchase Order',
			'hasil_header' => $query_header,
			'hasil_data' => $query, 
			);

	    $html = $this->load->view('view_detail_trans_beli_report', $data, true);
	    
	    $filename = 'form_po_'.$id_trans_beli.'_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function delete_trans_beli($id)
	{
		$nilai = array(
               'id_trans_beli' => 0,
            );

		$this->t_beli->delete_by_id($id);
		$this->t_beli->update_by_id(array('id_trans_beli' => $id), $nilai);

		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Transaksi Order Barang No.'.$id.' Berhasil dihapus'
		));
	}

	public function edit_trans_beli($id)
	{
		$data = array(
			'data_header' => $this->t_beli->get_edit_pembelian_header($id),
			'data_isi' => $this->t_beli->get_detail_pembelian($id),
		);

		echo json_encode($data);
	}

	public function list_trans_beli()
	{
		$list = $this->t_beli->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $listTransBeli) {
			$link_detail = site_url('trans_beli/trans_beli_detail/').$listTransBeli->id_trans_beli;
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $listTransBeli->id_trans_beli;
			$row[] = $listTransBeli->username;
			$row[] = $listTransBeli->tgl_trans_beli;
			$row[] = $listTransBeli->nama_supplier;
			//add html for action button
			$row[] = '<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Pembelian Detail" id="btn_detail" onclick=""><i class="glyphicon glyphicon-info-sign"></i> '.$listTransBeli->jml.' Items</a>
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editTransBeli('."'".$listTransBeli->id_trans_beli."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteTransBeli('."'".$listTransBeli->id_trans_beli."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->t_beli->count_all(),
						"recordsFiltered" => $this->t_beli->count_filtered(),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function list_permintaan()
	{
		// get data from ajax object
		$id_trans_order = $this->input->post('idTransOrder');

		$data = array(
			'data_list' => $this->t_beli->get_list_permintaan($id_trans_order),
		);

		echo json_encode($data);
	}

	public function suggest_id_order()
	{
		$barang = [];
		if(!empty($this->input->get("q"))){
			$key = $_GET['q'];
			$query = $this->t_beli->lookup_id_order($key);
		}else{
			$query = $this->t_beli->lookup_id_order();
		}
		
		foreach ($query as $row) {
			$barang[] = array(
						'id' => $row->id_trans_order,
						'text' => $row->id_trans_order,
					);
		}
		echo json_encode($barang);
	}

	public function suggest_id_supplier()
	{
		$supplier = [];
		if(!empty($this->input->get("q"))){
			$key = $_GET['q'];
			$query = $this->t_beli->lookup_id_supplier($key);
		}else{
			$query = $this->t_beli->lookup_id_supplier();
		}
		
		foreach ($query as $row) {
			$supplier[] = array(
						'id' => $row->id_supplier,
						'text' => $row->nama_supplier,
					);
		}
		echo json_encode($supplier);
	}

	public function trans_beli_detail()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query_user = $this->prof->get_detail_pengguna($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$id_trans_beli = $this->uri->segment(3); 
		$query_header = $this->t_beli->get_detail_pembelian_header($id_trans_beli);
		$query = $this->t_beli->get_detail_pembelian($id_trans_beli);

		$data = array(
			'css'=>'cssTransBeli',
			'js'=>'jsTransBeli',
			'content' => 'view_detail_trans_beli',
			'title' => 'PT.Surya Putra Barutama',
			'hasil_header' => $query_header,
			'hasil_data' => $query, 
			'data_user' => $query_user,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			);
		$this->load->view('view_home',$data);
	}

	public function update_trans_beli()
	{
		//delete id beli in tabel detail
		$id = $this->input->post('fieldIdBeli');
		$initiated_date = date('Y-m-d H:i:s');
		$hapus_data_beli_detail = $this->t_beli->hapus_data_beli_detail($id);

		//update header
		$data_header = array(
			'id_supplier' => $this->input->post('fieldNamaSupplier'),
		); 
		$this->t_beli->update_data_header_beli(array('id_trans_beli' => $id), $data_header);

		//proses insert ke tabel detail
		$hitung_detail = count($this->input->post('fieldIdBarangBeli'));
		$data_beli_detail = array();
			for ($i=0; $i < $hitung_detail; $i++) 
			{
			$data_beli_detail[$i] = array(
				'id_trans_beli' =>$id,
				'id_trans_order_detail' => $this->input->post('fieldIdTransOrderDetail')[$i],
				'id_trans_order' => $this->input->post('fieldIdOrder')[$i],
				'id_barang' => $this->input->post('fieldIdBarangBeli')[$i],
				'id_satuan' => $this->input->post('fieldIdSatuanBeli')[$i],
				'qty_beli' => $this->input->post('fieldJumlahBarangAccBeli')[$i],
				'keterangan_beli' => $this->input->post('fieldKeteranganBarangBeli')[$i],
				'tgl_trans_beli_detail' => $this->input->post('fieldTanggalBeli'), 
				'timestamp' => $initiated_date,
				);
			}
		
		$insert_update = $this->t_beli->insert_update_pembelian($data_beli_detail);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_update" => 'Data Transaksi Pembelian Barang No.'.$id.' Berhasil diupdate'
		));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('fieldTanggalBeli') == '')
		{
			$data['inputerror'][] = 'fieldTanggalBeli';
			$data['error_string'][] = 'tanggal Pembelian is required';
			$data['status'] = FALSE;
		}
		
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
	}


	

}