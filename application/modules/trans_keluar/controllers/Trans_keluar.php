<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trans_keluar extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		//cek apablia session kosong
		if ($this->session->userdata('username') === null) {
			//direct ke controller login
			redirect('login');
		}elseif ($this->session->userdata('id_level_user') != '1' && $this->session->userdata('id_level_user') != '3') {
			redirect('home/oops');
		}
		$this->load->model('mod_trans_keluar','t_keluar');
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
			'content'=>'view_list_trans_keluar',
			'css'=>'cssTransKeluar',
			'modal'=>'modalTransKeluar',
			'js'=>'jsTransKeluar',
			'title' => 'PT.Surya Putra Barutama',
			'data_user' => $query,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
		);
		//parsing data ke file view home
		$this->load->view('view_home',$data);
	}

	public function add_trans_keluar()
	{
		$this->_validate();
		$initiated_date = date('Y-m-d H:i:s');
		//for table trans_keluar
		$data_keluar = array(			
				'id_trans_keluar' => $this->input->post('fieldIdKeluar'),
				'id_user' => $this->input->post('fieldIdUserKeluar'),
				'tgl_trans_keluar' => $this->input->post('fieldTanggalKeluar'),
				'timestamp_trans_keluar' => $initiated_date,
				'id_borongan' => $this->input->post('fieldIdBorongan'),
				'id_borongan_detail' => $this->input->post('fieldNamaPersonil'), 
			);

		//for table trans_masuk_detail
		$hitung_keluar = count($this->input->post('fieldIdBarangKeluar'));
		$data_keluar_detail = array();
			for ($i=0; $i < $hitung_keluar; $i++) 
			{
			$data_keluar_detail[$i] = array(
				'id_trans_keluar' => $this->input->post('fieldIdKeluar'),
				'id_barang' => $this->input->post('fieldIdBarangKeluar')[$i],
				'id_satuan' => $this->input->post('fieldIdSatuanKeluar')[$i],
				'qty_keluar' => $this->input->post('fieldJumlahBarangKeluar')[$i],
				'keterangan_keluar' => $this->input->post('fieldKeteranganBarangKeluar')[$i],
				'tgl_trans_keluar_detail' => $this->input->post('fieldTanggalKeluar'),
				'timestamp' => $initiated_date,
				);
			}

		$insert = $this->t_keluar->save($data_keluar, $data_keluar_detail);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_tambah" => 'Data Transaksi Pengeluaran Barang Berhasil ditambahkan'
			));
	}

	public function ajax_get_header_form()
	{
		$data = array(
			'kode_trans_keluar'=> $this->t_keluar->getKodeTransKeluar(),
		);

		echo json_encode($data);
	}

	public function delete_trans_keluar($id)
	{
		$this->t_keluar->delete_by_id($id);

		echo json_encode(array(
			"status" => TRUE,
			"pesan" => 'Data Transaksi Pengeluaran Barang No.'.$id.' Berhasil dihapus'
		));
	}

	public function edit_trans_keluar($id)
	{
		$data = array(
			'data_header' => $this->t_keluar->get_edit_pengeluaran_header($id),
			'data_isi' => $this->t_keluar->get_detail_pengeluaran($id),
		);

		echo json_encode($data);
	}

	public function list_trans_keluar()
	{
		$list = $this->t_keluar->get_datatables();
		$data = array();
		$no =$_POST['start'];
		foreach ($list as $listTransKeluar) {
			$link_detail = site_url('trans_keluar/trans_keluar_detail/').$listTransKeluar->id_trans_keluar;
			$no++;
			$row = array();
			//loop value tabel db
			$row[] = $listTransKeluar->id_trans_keluar;
			$row[] = $listTransKeluar->nama_lengkap_user;
			$row[] = $listTransKeluar->tgl_trans_keluar;
			$row[] = $listTransKeluar->nama_borongan;
			$row[] = $listTransKeluar->nama_borongan_detail;
			//add html for action button
			$row[] = '<a class="btn btn-sm btn-success" href="'.$link_detail.'" title="Pengeluaran Detail" id="btn_detail" onclick=""><i class="glyphicon glyphicon-info-sign"></i> '.$listTransKeluar->jml.' Items</a>
					<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="editTransKeluar('."'".$listTransKeluar->id_trans_keluar."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
					<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="deleteTransKeluar('."'".$listTransKeluar->id_trans_keluar."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}//end loop

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->t_keluar->count_all(),
						"recordsFiltered" => $this->t_keluar->count_filtered(),
						"data" => $data,
					);
		//output to json format
		echo json_encode($output);
	}

	public function suggest_id_borongan()
	{
		$borongan = [];
		if(!empty($this->input->get("q"))){
			$key = $_GET['q'];
			$query = $this->t_keluar->lookup_id_borongan($key);
		}else{
			$query = $this->t_keluar->lookup_id_borongan();
		}
		
		foreach ($query as $row) {
			$borongan[] = array(
						'id' => $row->id_borongan,
						'text' => $row->nama_borongan,
					);
		}
		echo json_encode($borongan);
	}

	public function suggest_id_borongan_detail()
	{
		// get data from ajax object (uri)
		$id_borongan = $this->uri->segment(3);
		$personil = [];
		if(!empty($this->input->get("q"))){
			$key = $_GET['q'];
			$query = $this->t_keluar->lookup_id_borongan_detail($key, $id_borongan);
		}else{
			$key = "";
			$query = $this->t_keluar->lookup_id_borongan_detail($key, $id_borongan);
		}
		
		foreach ($query as $row) {
			$personil[] = array(
						'id' => $row->id_borongan_detail,
						'text' => $row->nama_borongan_detail,
					);
		}
		echo json_encode($personil);
	}

	public function trans_keluar_detail()
	{
		$id_user = $this->session->userdata('id_user'); 
		$query_user = $this->prof->get_detail_pengguna($id_user);

		$jumlah_notif = $this->psn->notif_count($id_user);  //menghitung jumlah post
		$notif= $this->psn->get_notifikasi($id_user); //menampilkan isi postingan

		$id_trans_keluar = $this->uri->segment(3); 
		$query_header = $this->t_keluar->get_detail_pengeluaran_header($id_trans_keluar);
		$query = $this->t_keluar->get_detail_pengeluaran($id_trans_keluar);

		$data = array(
			'css'=>'cssTransKeluar',
			'js'=>'jsTransKeluar',
			'content' => 'view_detail_trans_keluar',
			'title' => 'PT.Surya Putra Barutama',
			'hasil_header' => $query_header,
			'hasil_data' => $query, 
			'data_user' => $query_user,
			'qty_notif' => $jumlah_notif,
			'isi_notif' => $notif,
			);
		$this->load->view('view_home',$data);
	}

	public function update_trans_keluar()
	{
		//delete in tabel_keluar_detail where id_t_keluar
		$id = $this->input->post('fieldIdKeluar');
		$initiated_date = date('Y-m-d H:i:s');
		$hapus_data_keluar_detail = $this->t_keluar->hapus_data_keluar_detail($id);

		//update header
		$data_header = array(
			'tgl_trans_keluar' => $this->input->post('fieldTanggalKeluar'),
			'id_borongan' => $this->input->post('fieldIdBorongan'),
			'id_borongan_detail' => $this->input->post('fieldNamaPersonil'),
		); 
		$this->t_keluar->update_data_header_keluar(array('id_trans_keluar' => $id), $data_header);

		//proses insert ke tabel_keluar_detail
		$hitung_detail = count($this->input->post('fieldIdBarangKeluar'));
		$data_keluar_detail = array();
			for ($i=0; $i < $hitung_detail; $i++) 
			{
			$data_keluar_detail[$i] = array(
				'id_trans_keluar' =>$id,
				'id_barang' => $this->input->post('fieldIdBarangKeluar')[$i],
				'id_satuan' => $this->input->post('fieldIdSatuanKeluar')[$i],
				'qty_keluar' => $this->input->post('fieldJumlahBarangKeluar')[$i],
				'keterangan_keluar' => $this->input->post('fieldKeteranganBarangKeluar')[$i],
				'tgl_trans_keluar_detail' => $this->input->post('fieldTanggalKeluar'),
				'timestamp' => $initiated_date, 
				);
			}
		
		$insert_update = $this->t_keluar->insert_update_pengeluaran($data_keluar_detail);
		echo json_encode(array(
			"status" => TRUE,
			"pesan_update" => 'Data Transaksi Pengeluaran Barang No.'.$id.' Berhasil diupdate'
		));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('fieldTanggalKeluar') == '')
		{
			$data['inputerror'][] = 'fieldTanggalKeluar';
			$data['error_string'][] = 'tanggal Pengeluaran is required';
			$data['status'] = FALSE;
		}
		
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
	}

}