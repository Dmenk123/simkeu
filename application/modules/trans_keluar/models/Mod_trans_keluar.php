<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_trans_keluar extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	//datatable setting
	private function _get_datatables_query($term='') //term is value of $_REQUEST['search']
	{
		
		$column = array(
			'tbl_trans_keluar.id_trans_keluar',
			'tbl_user_detail.nama_lengkap_user',
			'tbl_trans_keluar.tgl_trans_keluar',
			'tbl_borongan.nama_borongan',
			'tbl_borongan_detail.nama_borongan_detail',
			null,
			);

		$this->db->select('
			tbl_trans_keluar.id_trans_keluar,
			tbl_trans_keluar_detail.id_trans_keluar_detail,
			tbl_barang.nama_barang,
			tbl_user_detail.nama_lengkap_user,
			tbl_trans_keluar.tgl_trans_keluar,
			tbl_borongan.nama_borongan,
			tbl_borongan_detail.nama_borongan_detail,
			COUNT(tbl_barang.id_barang) AS jml
			');

		$this->db->from('tbl_trans_keluar');
		//join 'tbl', on 'tbl = tbl' , type join
		$this->db->join(
			'tbl_trans_keluar_detail',
			'tbl_trans_keluar.id_trans_keluar = tbl_trans_keluar_detail.id_trans_keluar','left');
		$this->db->join(
			'tbl_barang',
			'tbl_trans_keluar_detail.id_barang = tbl_barang.id_barang','left');
		$this->db->join(
			'tbl_user_detail',
			'tbl_trans_keluar.id_user = tbl_user_detail.id_user','left');
		$this->db->join(
			'tbl_borongan',
			'tbl_trans_keluar.id_borongan = tbl_borongan.id_borongan','left');
		$this->db->join(
			'tbl_borongan_detail',
			'tbl_trans_keluar.id_borongan_detail = tbl_borongan_detail.id_borongan_detail','left');

		$this->db->like('tbl_trans_keluar.id_trans_keluar',$term);
		$this->db->or_like('tbl_barang.nama_barang',$term);
		$this->db->or_like('tbl_trans_keluar.tgl_trans_keluar',$term);
		$this->db->or_like('tbl_user_detail.nama_lengkap_user',$term);
		$this->db->or_like('tbl_borongan.nama_borongan',$term);
		$this->db->or_like('tbl_borongan_detail.nama_borongan_detail',$term);
		$this->db->group_by('tbl_trans_keluar.id_trans_keluar');
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_REQUEST['order']['0']['column']], $_REQUEST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatables_query($term);

		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatables_query($term);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from('tbl_trans_keluar');
		return $this->db->count_all_results();
	}

	//end datatable setting

	public function delete_by_id($id)
	{
		$this->db->where('id_trans_keluar', $id);
		$this->db->delete('tbl_trans_keluar');

		$this->db->where('id_trans_keluar', $id);
		$this->db->delete('tbl_trans_keluar_detail');
	}

	public function get_detail_pengeluaran($id_trans_keluar)
	{
		$this->db->select(' tbl_barang.id_barang,
							tbl_barang.nama_barang,
							tbl_satuan.nama_satuan,
							tbl_satuan.id_satuan,
							tbl_trans_keluar.tgl_trans_keluar,
							tbl_trans_keluar_detail.id_trans_keluar_detail,
							tbl_trans_keluar_detail.id_trans_keluar,
							tbl_trans_keluar_detail.qty_keluar,
							tbl_trans_keluar_detail.keterangan_keluar');
		$this->db->from('tbl_trans_keluar_detail');
		$this->db->join('tbl_trans_keluar', 'tbl_trans_keluar.id_trans_keluar = tbl_trans_keluar_detail.id_trans_keluar','left');
		$this->db->join('tbl_barang', 'tbl_barang.id_barang = tbl_trans_keluar_detail.id_barang','left');
		$this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_trans_keluar_detail.id_satuan','left');

        $this->db->where('tbl_trans_keluar_detail.id_trans_keluar', $id_trans_keluar);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_detail_pengeluaran_header($id_trans_keluar)
	{
		$this->db->select('tbl_trans_keluar.id_trans_keluar,
							tbl_trans_keluar.tgl_trans_keluar,
							tbl_user.id_user,
							tbl_user.username,
							tbl_user_detail.nama_lengkap_user,
							tbl_borongan.id_borongan,
							tbl_borongan.nama_borongan,
							tbl_borongan_detail.id_borongan_detail,
							tbl_borongan_detail.nama_borongan_detail');

		$this->db->from('tbl_trans_keluar');
		$this->db->join('tbl_user', 'tbl_trans_keluar.id_user = tbl_user.id_user','left');
		$this->db->join('tbl_user_detail', 'tbl_trans_keluar.id_user = tbl_user_detail.id_user','left');
		$this->db->join('tbl_borongan','tbl_trans_keluar.id_borongan = tbl_borongan.id_borongan','left');
		$this->db->join('tbl_borongan_detail','tbl_trans_keluar.id_borongan_detail = tbl_borongan_detail.id_borongan_detail','left');
        $this->db->where('tbl_trans_keluar.id_trans_keluar', $id_trans_keluar);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_edit_pengeluaran_header($id_trans_keluar)
	{
		$this->db->select('tbl_trans_keluar.id_trans_keluar,
							tbl_trans_keluar.tgl_trans_keluar,
							tbl_user.id_user,
							tbl_user.username,
							tbl_user_detail.nama_lengkap_user,
							tbl_borongan.id_borongan,
							tbl_borongan.nama_borongan,
							tbl_borongan_detail.id_borongan_detail,
							tbl_borongan_detail.nama_borongan_detail');

		$this->db->from('tbl_trans_keluar');
		$this->db->join('tbl_user', 'tbl_trans_keluar.id_user = tbl_user.id_user','left');
		$this->db->join('tbl_user_detail', 'tbl_trans_keluar.id_user = tbl_user_detail.id_user','left');
		$this->db->join('tbl_borongan','tbl_trans_keluar.id_borongan = tbl_borongan.id_borongan','left');
		$this->db->join('tbl_borongan_detail','tbl_trans_keluar.id_borongan_detail = tbl_borongan_detail.id_borongan_detail','left');
        $this->db->where('tbl_trans_keluar.id_trans_keluar', $id_trans_keluar);
        //$this->db->group_by('tbl_trans_masuk_detail.id_trans_beli');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	function getKodeTransKeluar(){
        $q = $this->db->query("SELECT MAX(RIGHT(id_trans_keluar,5)) as kode_max from tbl_trans_keluar WHERE DATE_FORMAT(tgl_trans_keluar, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')");
            $kd = "";
            if($q->num_rows()>0){
                foreach($q->result() as $k){
                    $tmp = ((int)$k->kode_max)+1;
                    $kd = sprintf("%05s", $tmp);
                }
            }else{
                $kd = "00001";
            }
            return "OUT".date('my').$kd;
    }

    public function hapus_data_keluar_detail($id)
	{
		$this->db->where('id_trans_keluar', $id);
		$this->db->delete('tbl_trans_keluar_detail');
	}

	public function insert_update_pengeluaran($data_keluar_detail)
	{
		$this->db->insert_batch('tbl_trans_keluar_detail',$data_keluar_detail);
	}

	public function lookup_id_borongan($keyword = "")
	{
		$this->db->select('id_borongan, nama_borongan');
		$this->db->from('tbl_borongan');
		$this->db->where('status','aktif');
		$this->db->like('nama_borongan',$keyword);
		$this->db->limit(5);
		$this->db->order_by('id_borongan', 'DESC');
		//$this->db->group_by('id_trans_beli');
		$query = $this->db->get();
		return $query->result();
	}

	public function lookup_id_borongan_detail($keyword = "", $id_borongan)
	{
		$this->db->select('id_borongan_detail, nama_borongan_detail, id_borongan');
		$this->db->from('tbl_borongan_detail');
		$this->db->where('status','aktif');
		$this->db->where('id_borongan',$id_borongan);
		$this->db->like('nama_borongan_detail',$keyword);
		$this->db->limit(5);
		$this->db->order_by('id_borongan', 'DESC');
		//$this->db->group_by('id_trans_beli');
		$query = $this->db->get();
		return $query->result();
	}

    function satuan(){
		$this->db->order_by('name','ASC');
		$namaSatuan= $this->db->get('tbl_satuan,tbl_barang');
		return $namaSatuan->result_array();
	}

	public function save($data_keluar, $data_keluar_detail)
	{
		//insert into tbl_trans_keluar 
		$this->db->insert('tbl_trans_keluar',$data_keluar);

		//insert into tbl_trans_keluar_detail 
		$this->db->insert_batch('tbl_trans_keluar_detail',$data_keluar_detail);
	}

	public function update_data_header_keluar($where, $data_header)
	{
		$this->db->update('tbl_trans_keluar', $data_header, $where);
		return $this->db->affected_rows();
	}

}