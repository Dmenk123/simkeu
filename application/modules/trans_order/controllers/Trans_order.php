<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trans_order extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//cek apablia session kosong
		if ($this->session->userdata('username') === null) {
			//direct ke controller login
			redirect('login');
		}
		$this->load->model('mod_trans_order','t_order');
		$this->load->model('pesan/mod_pesan','psn');
		//profil data
		$this->load->model('profil/mod_profil','prof');
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
			'content'=>'view_list_trans_order',
			'css'=>'cssTransOrder',
			'modal'=>'modalTransOrder',
			'js'=>'jsTransOrder',
			'title' => 'PT.Surya Putra Barutama',
			'data_user' => $query,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
		);
		//parsing data ke file view home
		$this->load->view('view_home',$data);

	}

	public function list_trans_order()
	{
		$list = $this->t_order->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $listTransOrder) {
			$link_detail = site_url('trans_order/trans_order_detail/').$listTransOrder->id_trans_order;
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $listTransOrder->id_trans_order;
			$row[] = $listTransOrder->username;
			$row[] = $listTransOrder->tgl_trans_order;
			//add html for action button
			$row[] = '<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Order Detail" id="btn_detail" onclick=""><i class="glyphicon glyphicon-info-sign"></i> '.$listTransOrder->jml.' Items</a>
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editTransOrder('."'".$listTransOrder->id_trans_order."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteTransOrder('."'".$listTransOrder->id_trans_order."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->t_order->count_all(),
						"recordsFiltered" => $this->t_order->count_filtered(),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function list_peramalan()
	{
		$this->load->model('forecasting/Mod_forecasting','m_ramal');
		$list = $this->m_ramal->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $listPeramalan) {
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = '<span class="row_idBrg_'.$listPeramalan->id_forecast.'">'.$listPeramalan->id_barang.'</span>';
			$row[] = $listPeramalan->nama_barang;
			$row[] = $listPeramalan->tgl_forecast;
			$row[] = $listPeramalan->alpha_forecast;
			$row[] = '<span class="row_ft_'.$listPeramalan->id_forecast.'">'.$listPeramalan->hasil_forecast.'</span>';
			$row[] = $listPeramalan->mape_forecast;
			//add html for action button
			$row[] = '<a class="btn btn-sm btn-default" href="javascript:void(0)" title="Ambil Data" onclick="ambilData('."'".$listPeramalan->id_forecast."'".')"> Ambil Data</a>';

			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->m_ramal->count_all(),
						"recordsFiltered" => $this->m_ramal->count_filtered(),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function edit_trans_order($id)
	{
		// $data = $this->t_order->get_by_id($id);
		// echo json_encode($data);
		
		$data = array(
			'data_header' => $this->t_order->get_detail_header($id),
			'data_isi' => $this->t_order->get_detail($id),
		);

		echo json_encode($data);
	}

	public function add_trans_order()
	{
		$this->_validate();
		$initiated_date = date('Y-m-d H:i:s');
		//for table trans_order
		$data_order = array(			
				'id_trans_order' => $this->input->post('fieldIdOrder'),
				'id_user' => $this->input->post('fieldIdUserOrder'),
				'tgl_trans_order' => $this->input->post('fieldTanggalOrder'),
				'timestamp_trans_order' => $initiated_date, 
			);

		//for table trans_order_detail
		$hitung = count($this->input->post('fieldIdBarangOrder'));
		$data_order_detail = array();
			for ($i=0; $i < $hitung; $i++) 
			{
				$data_order_detail[$i] = array(
					'id_trans_order' => $this->input->post('fieldIdOrder'),
					'id_barang' => $this->input->post('fieldIdBarangOrder')[$i],
					'id_satuan' => $this->input->post('fieldIdSatuanOrder')[$i],
					'qty_order' => $this->input->post('fieldJumlahBarangOrder')[$i],
					'keterangan_order' => $this->input->post('fieldKeteranganBarangOrder')[$i],
					'tgl_trans_order_detail' => $this->input->post('fieldTanggalOrder'),
					'timestamp' => $initiated_date, 
				);
			}

								
		$insert = $this->t_order->save($data_order, $data_order_detail);
		//var_dump($data_order);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_tambah" => 'Data Transaksi Order Barang Berhasil ditambahkan'
			));
	}

	public function update_trans_order()
	{
		$this->_validate();
		//delete id order in tabel detail
		$id = $this->input->post('fieldIdOrder');
		$initiated_date = date('Y-m-d H:i:s');
		$hapus_data_order_detail = $this->t_order->hapus_data_order_detail($id);

		//update header
		$data_header = array(
			'tgl_trans_order' => $this->input->post('fieldTanggalOrder'),
		); 
		$this->t_order->update_data_header_detail(array('id_trans_order' => $id), $data_header);

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

		$insert_update = $this->t_order->insert_update($data_order_detail);

		//update tbl_trans_beli_detail
		$id_t_order = $this->input->post('fieldIdOrder');
		$result_id_order_detail = $this->t_order->get_id_trans_order_detail($id_t_order);
		$result_id_beli_detail = $this->t_order->get_id_trans_beli_detail($id_t_order);

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
		$this->t_order->delete_by_id($id);
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
		$query_header = $this->t_order->get_detail_header($id_trans_order);
		$query = $this->t_order->get_detail($id_trans_order);

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
		$query_header = $this->t_order->get_detail_header($id_trans_order);
		$query = $this->t_order->get_detail($id_trans_order);

		$data = array(
			'title' => 'Report Transaksi Permintaan',
			'hasil_header' => $query_header,
			'hasil_data' => $query, 
			);

	    $html = $this->load->view('view_detail_trans_order_report', $data, true);
	    
	    $filename = 'report_permintaan_'.$id_trans_order.'_'.time();
	    $this->pdf_gen->generate($html, $filename, true, 'A4', 'portrait');
	}

	public function ajax_get_header_form()
	{
		$data = array(
			'kode_trans_order'=> $this->t_order->getKodeTransOrder(),
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
		$query = $this->t_order->lookup($q);
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
		$query = $this->t_order->lookup2($rowIdBrg);
		echo json_encode($query);
	}

}