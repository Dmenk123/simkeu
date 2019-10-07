<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_retur_masuk extends CI_Model
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
			'tbl_retur_masuk.id_retur_masuk',
			'tbl_user.username',
			'tbl_retur_masuk.tgl_retur_masuk',
			'tbl_supplier.nama_supplier',
			);

		$this->db->select('
			tbl_retur_masuk.id_retur_masuk,
			tbl_retur_masuk_detail.id_retur_masuk_detail,
			tbl_barang.nama_barang,
			tbl_user.username,
			tbl_retur_masuk.tgl_retur_masuk,
			tbl_supplier.nama_supplier,
			COUNT(tbl_barang.id_barang) AS jml
			');

		$this->db->from('tbl_retur_masuk');
		//join 'tbl', on 'tbl = tbl' , type join
		$this->db->join(
			'tbl_retur_masuk_detail',
			'tbl_retur_masuk.id_retur_masuk = tbl_retur_masuk_detail.id_retur_masuk','left');
		$this->db->join(
			'tbl_barang',
			'tbl_retur_masuk_detail.id_barang = tbl_barang.id_barang','left');
		$this->db->join(
			'tbl_user',
			'tbl_retur_masuk.id_user = tbl_user.id_user','left');
		$this->db->join(
			'tbl_supplier',
			'tbl_retur_masuk.id_supplier = tbl_supplier.id_supplier','left');

		$this->db->like('tbl_retur_masuk.id_retur_masuk',$term);
		$this->db->or_like('tbl_barang.nama_barang',$term);
		$this->db->or_like('tbl_user.username',$term);
		$this->db->or_like('tbl_retur_masuk.tgl_retur_masuk',$term);
		$this->db->or_like('tbl_supplier.nama_supplier',$term);
		$this->db->group_by('tbl_retur_masuk.id_retur_masuk');

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
		$this->db->from('tbl_retur_masuk');
		return $this->db->count_all_results();
	}

	//end datatable setting

	public function delete_by_id($id)
	{
		$this->db->where('id_retur_masuk', $id);
		$this->db->delete('tbl_retur_masuk');

		$this->db->where('id_retur_masuk', $id);
		$this->db->delete('tbl_retur_masuk_detail');
	}

	public function get_by_id($id)
	{
		$this->db->from('tbl_trans_beli');
		$this->db->where('id_trans_beli',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_list_retur_keluar($id_retur_keluar)
	{
		$this->db->select('
			tbl_retur_keluar_detail.id_retur_keluar_detail,
			tbl_retur_keluar_detail.id_retur_keluar,
			tbl_retur_keluar_detail.id_retur_masuk,
			tbl_retur_keluar_detail.id_barang, 
			tbl_barang.nama_barang,
		    tbl_retur_keluar_detail.id_satuan,
			tbl_satuan.nama_satuan,
		    tbl_retur_keluar_detail.qty_retur_keluar,
		    tbl_retur_keluar_detail.keterangan_retur_keluar,
		');
		$this->db->from('tbl_retur_keluar_detail');
		$this->db->join(
			'tbl_barang',
			'tbl_retur_keluar_detail.id_barang = tbl_barang.id_barang',
			'left');
		$this->db->join(
			'tbl_satuan',
			'tbl_retur_keluar_detail.id_satuan = tbl_satuan.id_satuan',
			'left');
		$this->db->where('tbl_retur_keluar_detail.id_retur_keluar', $id_retur_keluar);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_list_supplier($id_retur_keluar)
	{
		$this->db->select('
			tbl_retur_keluar.id_retur_keluar,
			tbl_retur_keluar.id_supplier,
			tbl_supplier.nama_supplier,
		');
		$this->db->from('tbl_retur_keluar');
		$this->db->join(
			'tbl_supplier',
			'tbl_retur_keluar.id_supplier = tbl_supplier.id_supplier',
			'left');
		$this->db->where('tbl_retur_keluar.id_retur_keluar', $id_retur_keluar);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_detail_retur_masuk($id_retur_masuk)
	{
		$this->db->select(' tbl_barang.id_barang,
							tbl_barang.nama_barang,
							tbl_satuan.nama_satuan,
							tbl_satuan.id_satuan,
							tbl_retur_masuk.tgl_retur_masuk,
							tbl_retur_masuk_detail.id_retur_masuk_detail,
							tbl_retur_masuk_detail.id_retur_keluar,
							tbl_retur_masuk_detail.id_retur_masuk,
							tbl_retur_masuk_detail.qty_retur_masuk,
							tbl_retur_masuk_detail.keterangan_retur_masuk');
		$this->db->from('tbl_retur_masuk_detail');
		$this->db->join('tbl_retur_masuk', 'tbl_retur_masuk.id_retur_masuk = tbl_retur_masuk_detail.id_retur_masuk','left');
		$this->db->join('tbl_barang', 'tbl_barang.id_barang = tbl_retur_masuk_detail.id_barang','left');
		$this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_retur_masuk_detail.id_satuan','left');

        $this->db->where('tbl_retur_masuk.id_retur_masuk', $id_retur_masuk);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_detail_retur_masuk_header($id_retur_masuk)
	{
		$this->db->select('tbl_retur_masuk.id_retur_masuk,
							tbl_retur_masuk.tgl_retur_masuk,
							tbl_retur_masuk_detail.id_retur_keluar,
							tbl_user.id_user,
							tbl_user.username,
							tbl_user_detail.nama_lengkap_user,
							tbl_supplier.id_supplier,
							tbl_supplier.nama_supplier,
							tbl_supplier.alamat_supplier');

		$this->db->from('tbl_retur_masuk');
		$this->db->join('tbl_retur_masuk_detail', 'tbl_retur_masuk.id_retur_masuk = tbl_retur_masuk_detail.id_retur_masuk','left');
		$this->db->join('tbl_user', 'tbl_retur_masuk.id_user = tbl_user.id_user','left');
		$this->db->join('tbl_user_detail', 'tbl_retur_masuk.id_user = tbl_user_detail.id_user','left');
		$this->db->join('tbl_supplier','tbl_retur_masuk.id_supplier = tbl_supplier.id_supplier','left');
        $this->db->where('tbl_retur_masuk.id_retur_masuk', $id_retur_masuk);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

    function getKodeReturMasuk(){
            $q = $this->db->query("SELECT MAX(RIGHT(id_retur_masuk,5)) as kode_max from tbl_retur_masuk WHERE DATE_FORMAT(tgl_retur_masuk, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')");
            $kd = "";
            if($q->num_rows()>0){
                foreach($q->result() as $k){
                    $tmp = ((int)$k->kode_max)+1;
                    $kd = sprintf("%04s", $tmp);
                }
            }else{
                $kd = "0001";
            }
            return "RTRI".date('my').$kd;
    }

    public function hapus_retur_keluar_detail($id)
	{
		$this->db->where('id_retur_keluar', $id);
		$this->db->delete('tbl_retur_keluar_detail');
	}

	public function insert_update_retur_keluar($data_retur_keluar_detail)
	{
		$this->db->insert_batch('tbl_retur_keluar_detail',$data_retur_keluar_detail);
	}

	public function lookup_id_retur_keluar($keyword = "")
	{
		$this->db->select('id_retur_keluar');
		$this->db->from('tbl_retur_keluar_detail');
		$this->db->like('id_retur_keluar',$keyword);
		$this->db->where('id_retur_masuk', '0');
		//$this->db->limit(5);
		$this->db->order_by('id_retur_keluar', 'DESC');
		$this->db->group_by('id_retur_keluar');
		$query = $this->db->get();
		return $query->result();
	}

    function satuan(){
		$this->db->order_by('name','ASC');
		$namaSatuan= $this->db->get('tbl_satuan,tbl_barang');
		return $namaSatuan->result_array();
	}

	public function save($data_retur_masuk, $data_retur_masuk_detail)
	{
		//insert into tbl_retur_masuk
		$this->db->insert('tbl_retur_masuk',$data_retur_masuk);

		//insert into tbl_retur_masuk_detail 
		$this->db->insert_batch('tbl_retur_masuk_detail',$data_retur_masuk_detail);
	}

	public function update_header_retur_keluar($where, $data_header)
	{
		$this->db->update('tbl_retur_keluar', $data_header, $where);
		return $this->db->affected_rows();
	}

	public function update_by_id($where, $nilai)
	{
		$this->db->update('tbl_trans_beli_detail', $nilai, $where);
	}

}