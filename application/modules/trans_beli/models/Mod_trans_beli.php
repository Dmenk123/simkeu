<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_trans_beli extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	private function _get_datatables_query($term='') //term is value of $_REQUEST['search']
	{
		$column = array(
			'tbl_trans_beli.id_trans_beli',
			'tbl_user.username',
			'tbl_trans_beli.tgl_trans_beli',
			'tbl_supplier.nama_supplier',
			);

		$this->db->select('
			tbl_trans_beli.id_trans_beli,
			tbl_trans_beli_detail.id_trans_beli_detail,
			tbl_barang.nama_barang,
			tbl_user.username,
			tbl_trans_beli.tgl_trans_beli,
			tbl_supplier.nama_supplier,
			COUNT(tbl_barang.id_barang) AS jml
			');

		$this->db->from('tbl_trans_beli');
		//join 'tbl', on 'tbl = tbl' , type join
		$this->db->join(
			'tbl_trans_beli_detail',
			'tbl_trans_beli.id_trans_beli = tbl_trans_beli_detail.id_trans_beli','left');
		$this->db->join(
			'tbl_barang',
			'tbl_trans_beli_detail.id_barang = tbl_barang.id_barang','left');
		$this->db->join(
			'tbl_user',
			'tbl_trans_beli.id_user = tbl_user.id_user','left');
		$this->db->join(
			'tbl_supplier',
			'tbl_trans_beli.id_supplier = tbl_supplier.id_supplier','left');

		$this->db->like('tbl_trans_beli.id_trans_beli',$term);
		$this->db->or_like('tbl_barang.nama_barang',$term);
		$this->db->or_like('tbl_user.username',$term);
		$this->db->or_like('tbl_trans_beli.tgl_trans_beli',$term);
		$this->db->or_like('tbl_supplier.nama_supplier',$term);
		$this->db->group_by('tbl_trans_beli.id_trans_beli');

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
		$this->db->from('tbl_trans_beli');
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from('tbl_trans_beli');
		$this->db->where('id_trans_beli',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data_beli, $data_beli_detail)
	{
		//insert into tbl_trans_beli 
		$this->db->insert('tbl_trans_beli',$data_beli);

		//insert into tbl_trans_order_detail 
		//$this->db->update_batch('tbl_trans_order_detail',$data_order_detail, 'id_trans_beli');

		//insert into tbl_trans_beli_detail 
		$this->db->insert_batch('tbl_trans_beli_detail',$data_beli_detail);
		// return $this->db->insert_id();
	}

	public function get_list_permintaan($idTransOrder)
	{
		$this->db->select('
			tbl_trans_order_detail.id_trans_order_detail,
			tbl_trans_order_detail.id_trans_order,
			tbl_trans_order_detail.id_barang, 
			tbl_barang.nama_barang,
		    tbl_trans_order_detail.id_satuan,
			tbl_satuan.nama_satuan,
		    tbl_trans_order_detail.qty_order,
		    tbl_trans_order_detail.keterangan_order,
		    tbl_trans_order_detail.id_trans_beli
		');
		$this->db->from('tbl_trans_order_detail');
		$this->db->join(
			'tbl_barang',
			'tbl_trans_order_detail.id_barang = tbl_barang.id_barang',
			'left');
		$this->db->join(
			'tbl_satuan',
			'tbl_trans_order_detail.id_satuan = tbl_satuan.id_satuan',
			'left');
		$this->db->where('tbl_trans_order_detail.id_trans_order', $idTransOrder);
		$this->db->where('tbl_trans_order_detail.id_trans_beli', "0");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function insert_update_pembelian($data_beli_detail)
	{
		$this->db->insert_batch('tbl_trans_beli_detail',$data_beli_detail);
	}

	public function update_data_header_beli($where, $data_header)
	{
		$this->db->update('tbl_trans_beli', $data_header, $where);
		return $this->db->affected_rows();
	}

	public function hapus_data_beli_detail($id)
	{
		$this->db->where('id_trans_beli', $id);
		$this->db->delete('tbl_trans_beli_detail');
	}

	public function delete_by_id($id)
	{
		$this->db->where('id_trans_beli', $id);
		$this->db->delete('tbl_trans_beli');

		$this->db->where('id_trans_beli', $id);
		$this->db->delete('tbl_trans_beli_detail');
	}

	public function update_by_id($where, $nilai)
	{
		$this->db->update('tbl_trans_order_detail', $nilai, $where);
	}

	public function get_detail_pembelian($id_trans_beli)
	{
		$this->db->select(' tbl_barang.id_barang,
							tbl_barang.nama_barang,
							tbl_satuan.nama_satuan,
							tbl_satuan.id_satuan,
							tbl_trans_order_detail.qty_order,
							tbl_trans_order_detail.id_trans_order_detail,
							tbl_trans_beli.tgl_trans_beli,
							tbl_trans_beli_detail.id_trans_order,
							tbl_trans_beli_detail.id_trans_beli_detail,
							tbl_trans_beli_detail.id_trans_beli,
							tbl_trans_beli_detail.qty_beli,
							tbl_trans_beli_detail.keterangan_beli');
		$this->db->from('tbl_trans_beli_detail');
		$this->db->join('tbl_trans_order_detail', 'tbl_trans_order_detail.id_trans_order_detail = tbl_trans_beli_detail.id_trans_order_detail','right');
		$this->db->join('tbl_trans_beli', 'tbl_trans_beli.id_trans_beli = tbl_trans_beli_detail.id_trans_beli','left');
		$this->db->join('tbl_barang', 'tbl_barang.id_barang = tbl_trans_beli_detail.id_barang','left');
		$this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_trans_beli_detail.id_satuan','left');

        $this->db->where('tbl_trans_beli.id_trans_beli', $id_trans_beli);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_detail_pembelian_header($id_trans_beli)
	{
		$this->db->select('tbl_trans_beli.id_trans_beli,
							tbl_trans_beli.tgl_trans_beli,
							tbl_user.id_user,
							tbl_user.username,
							tbl_user_detail.nama_lengkap_user,
							tbl_supplier.id_supplier,
							tbl_supplier.nama_supplier,
							tbl_supplier.alamat_supplier');

		$this->db->from('tbl_trans_beli');
		$this->db->join('tbl_user', 'tbl_trans_beli.id_user = tbl_user.id_user','left');
		$this->db->join('tbl_user_detail', 'tbl_trans_beli.id_user = tbl_user_detail.id_user','left');
		$this->db->join('tbl_supplier','tbl_trans_beli.id_supplier = tbl_supplier.id_supplier','left');
        $this->db->where('tbl_trans_beli.id_trans_beli', $id_trans_beli);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_edit_pembelian_header($id_trans_beli)
	{
		$this->db->select('tbl_trans_beli.id_trans_beli,
							tbl_trans_beli.tgl_trans_beli,
							tbl_trans_beli_detail.id_trans_order,
							tbl_user.id_user,
							tbl_user.username,
							tbl_user_detail.nama_lengkap_user,
							tbl_supplier.id_supplier,
							tbl_supplier.nama_supplier,
							tbl_supplier.alamat_supplier');

		$this->db->from('tbl_trans_beli');
		$this->db->join('tbl_user', 'tbl_trans_beli.id_user = tbl_user.id_user','left');
		$this->db->join('tbl_user_detail', 'tbl_trans_beli.id_user = tbl_user_detail.id_user','left');
		$this->db->join('tbl_supplier','tbl_trans_beli.id_supplier = tbl_supplier.id_supplier','left');
		$this->db->join('tbl_trans_beli_detail','tbl_trans_beli.id_trans_beli = tbl_trans_beli_detail.id_trans_beli','left');
        $this->db->where('tbl_trans_beli.id_trans_beli', $id_trans_beli);
        $this->db->group_by('tbl_trans_beli_detail.id_trans_order');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

    function getKodeTransBeli(){
            $q = $this->db->query("SELECT MAX(RIGHT(id_trans_beli,6)) as kode_max from tbl_trans_beli WHERE DATE_FORMAT(tgl_trans_beli, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')");
            $kd = "";
            if($q->num_rows()>0){
                foreach($q->result() as $k){
                    $tmp = ((int)$k->kode_max)+1;
                    $kd = sprintf("%06s", $tmp);
                }
            }else{
                $kd = "000001";
            }
            return "PO".date('my').$kd;
    }

    function satuan(){
		$this->db->order_by('name','ASC');
		$namaSatuan= $this->db->get('tbl_satuan,tbl_barang');
		return $namaSatuan->result_array();
	}

	public function lookup_id_order($keyword = "")
	{
		$this->db->select('id_trans_order');
		$this->db->from('tbl_trans_order_detail');
		$this->db->where('id_trans_beli',"0");
		$this->db->like('id_trans_order',$keyword);
		$this->db->limit(5);
		$this->db->order_by('id_trans_order', 'DESC');
		$this->db->group_by('id_trans_order');
		$query = $this->db->get();
		return $query->result();
	}

	public function lookup_id_supplier($keyword = "")
	{
		$this->db->select('id_supplier,nama_supplier,status');
		$this->db->from('tbl_supplier');
		$this->db->like('nama_supplier',$keyword);
		$this->db->where('status', 'aktif');
		$this->db->limit(5);
		$this->db->order_by('nama_supplier', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

}