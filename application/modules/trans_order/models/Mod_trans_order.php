<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_trans_order extends CI_Model
{
	/*var $column_order = array('id_trans_order','tbl_trans_order.id_user',null,null,null);
	var $column_search = array('id_trans_order','tbl_user.username'); 
	var $order = array('id_trans_order' => 'desc'); // default order */

	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	private function _get_datatables_query($term='') //term is value of $_REQUEST['search']
	{
		$column = array(
			'tbl_trans_order.id_trans_order',
			'tbl_user.username',
			'tbl_trans_order.tgl_trans_order',
			);

		$this->db->select('
			tbl_trans_order.id_trans_order,
			tbl_trans_order_detail.id_trans_order_detail,
			tbl_barang.nama_barang,
			tbl_user.username,
			tbl_trans_order.tgl_trans_order,
			COUNT(tbl_barang.id_barang) AS jml
			');

		$this->db->from('tbl_trans_order');
		//join 'tbl', on 'tbl = tbl' , type join
		$this->db->join(
			'tbl_trans_order_detail',
			'tbl_trans_order.id_trans_order = tbl_trans_order_detail.id_trans_order','left');
		$this->db->join(
			'tbl_barang',
			'tbl_trans_order_detail.id_barang = tbl_barang.id_barang','left');
		$this->db->join(
			'tbl_user',
			'tbl_trans_order.id_user = tbl_user.id_user','left');

		$this->db->like('tbl_trans_order.id_trans_order',$term);
		$this->db->or_like('tbl_barang.nama_barang',$term);
		$this->db->or_like('tbl_user.username',$term);
		$this->db->or_like('tbl_trans_order.tgl_trans_order',$term);
		$this->db->group_by('tbl_trans_order.id_trans_order');

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
		$this->db->from('tbl_trans_order');
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from('tbl_trans_order');
		$this->db->where('id_trans_order',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data_order, $data_order_detail)
	{
		//insert into tbl_trans_order 
		$this->db->insert('tbl_trans_order',$data_order);

		//insert into tbl_trans_order_detail 
		$this->db->insert_batch('tbl_trans_order_detail',$data_order_detail);
		// return $this->db->insert_id();
	}

	public function update_data_header_detail($where, $data_header)
	{
		$this->db->update('tbl_trans_order', $data_header, $where);
		return $this->db->affected_rows();
	}

	public function insert_update($data_order_detail)
	{
		$this->db->insert_batch('tbl_trans_order_detail',$data_order_detail);
	}

	public function hapus_data_order_detail($id)
	{
		$this->db->where('id_trans_order', $id);
		$this->db->delete('tbl_trans_order_detail');
	}

	public function delete_by_id($id)
	{
		$this->db->where('id_trans_order', $id);
		$this->db->delete('tbl_trans_order');

		$this->db->where('id_trans_order', $id);
		$this->db->delete('tbl_trans_order_detail');
	}

	public function get_detail($id_trans_order)
	{
		//$this->db->select('tbl_trans_order.id_trans_order,
		$this->db->select(' tbl_barang.id_barang,
							tbl_barang.nama_barang,
							tbl_satuan.nama_satuan,
							tbl_satuan.id_satuan,
							tbl_trans_order.tgl_trans_order,
							tbl_trans_order_detail.id_trans_beli,
							tbl_trans_order_detail.id_trans_order_detail,
							tbl_trans_order_detail.id_trans_order,
							tbl_trans_order_detail.qty_order,
							tbl_trans_order_detail.keterangan_order');
		$this->db->from('tbl_trans_order_detail');
		$this->db->join('tbl_trans_order', 'tbl_trans_order.id_trans_order = tbl_trans_order_detail.id_trans_order','left');
		$this->db->join('tbl_barang', 'tbl_barang.id_barang = tbl_trans_order_detail.id_barang','left');
		$this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_trans_order_detail.id_satuan','left');

        $this->db->where('tbl_trans_order.id_trans_order', $id_trans_order);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_id_trans_beli_detail($id_t_order)
	{
		$this->db->select('id_trans_beli_detail');
		$this->db->from('tbl_trans_beli_detail');
		$this->db->where('id_trans_order', $id_t_order);

		$query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
	}

	public function get_id_trans_order_detail($id_t_order)
	{
		$this->db->select('id_trans_order_detail');
		$this->db->from('tbl_trans_order_detail');
		$this->db->where('id_trans_order', $id_t_order);

		$query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
	}

	public function get_detail_header($id_trans_order)
	{
		$this->db->select('tbl_trans_order.id_trans_order,
							tbl_user.id_user,
							tbl_user.username,
							tbl_user_detail.nama_lengkap_user,
							tbl_trans_order.tgl_trans_order');
		$this->db->from('tbl_trans_order');
		$this->db->join('tbl_user', 'tbl_user.id_user = tbl_trans_order.id_user','left');
		$this->db->join('tbl_user_detail', 'tbl_user_detail.id_user = tbl_trans_order.id_user','left');
        $this->db->where('tbl_trans_order.id_trans_order', $id_trans_order);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}
    /*function getKodeTransOrder(){
            $q = $this->db->query("SELECT MAX(RIGHT(id_trans_order,5)) as kode_max from tbl_trans_order WHERE MONTH(tgl_trans_order) = MONTH(CURRENT_DATE())");
            $kd = "";
            if($q->num_rows()>0){
                foreach($q->result() as $k){
                    $tmp = ((int)$k->kode_max)+1;
                    $kd = sprintf("%05s", $tmp);
                }
            }else{
                $kd = "00001";
            }
            return "ORD".date('my').$kd;
    }*/

    function getKodeTransOrder(){
            $q = $this->db->query("SELECT MAX(RIGHT(id_trans_order,5)) as kode_max from tbl_trans_order WHERE DATE_FORMAT(tgl_trans_order, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')");
            $kd = "";
            if($q->num_rows()>0){
                foreach($q->result() as $k){
                    $tmp = ((int)$k->kode_max)+1;
                    $kd = sprintf("%05s", $tmp);
                }
            }else{
                $kd = "00001";
            }
            return "ORD".date('my').$kd;
    }

    function satuan(){
		$this->db->order_by('name','ASC');
		$namaSatuan= $this->db->get('tbl_satuan,tbl_barang');
		return $namaSatuan->result_array();
	}


	public function lookup($keyword)
	{
		$this->db->select('tbl_barang.nama_barang,tbl_barang.id_barang,tbl_satuan.id_satuan,tbl_satuan.nama_satuan,tbl_barang.status');
		$this->db->from('tbl_barang');
		$this->db->join('tbl_satuan','tbl_barang.id_satuan = tbl_satuan.id_satuan','left');
		$this->db->like('tbl_barang.nama_barang',$keyword);
		$this->db->where('tbl_barang.status', 'aktif');
		$this->db->limit(5);
		$query = $this->db->get();
		return $query->result();
	}

	public function lookup2($rowIdBrg)
	{
		$this->db->select('tbl_barang.nama_barang, tbl_satuan.id_satuan, tbl_satuan.nama_satuan, tbl_barang.status');
		$this->db->from('tbl_barang');
		$this->db->join('tbl_satuan','tbl_barang.id_satuan = tbl_satuan.id_satuan','left');
		$this->db->where('tbl_barang.id_barang',$rowIdBrg);
		$query = $this->db->get();
		return $query->result();
	}

}