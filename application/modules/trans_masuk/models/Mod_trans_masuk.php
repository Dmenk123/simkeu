<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_trans_masuk extends CI_Model
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
			'tbl_trans_masuk.id_trans_masuk',
			'tbl_user.username',
			'tbl_trans_masuk.tgl_trans_masuk',
			'tbl_supplier.nama_supplier',
			);

		$this->db->select('
			tbl_trans_masuk.id_trans_masuk,
			tbl_trans_masuk_detail.id_trans_masuk_detail,
			tbl_barang.nama_barang,
			tbl_user.username,
			tbl_trans_masuk.tgl_trans_masuk,
			tbl_supplier.nama_supplier,
			COUNT(tbl_barang.id_barang) AS jml
			');

		$this->db->from('tbl_trans_masuk');
		//join 'tbl', on 'tbl = tbl' , type join
		$this->db->join(
			'tbl_trans_masuk_detail',
			'tbl_trans_masuk.id_trans_masuk = tbl_trans_masuk_detail.id_trans_masuk','left');
		$this->db->join(
			'tbl_barang',
			'tbl_trans_masuk_detail.id_barang = tbl_barang.id_barang','left');
		$this->db->join(
			'tbl_user',
			'tbl_trans_masuk.id_user = tbl_user.id_user','left');
		$this->db->join(
			'tbl_supplier',
			'tbl_trans_masuk.id_supplier = tbl_supplier.id_supplier','left');

		$this->db->like('tbl_trans_masuk.id_trans_masuk',$term);
		$this->db->or_like('tbl_barang.nama_barang',$term);
		$this->db->or_like('tbl_user.username',$term);
		$this->db->or_like('tbl_trans_masuk.tgl_trans_masuk',$term);
		$this->db->or_like('tbl_supplier.nama_supplier',$term);
		$this->db->group_by('tbl_trans_masuk.id_trans_masuk');

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
		$this->db->from('tbl_trans_masuk');
		return $this->db->count_all_results();
	}

	//end datatable setting

	public function delete_by_id($id)
	{
		$this->db->where('id_trans_masuk', $id);
		$this->db->delete('tbl_trans_masuk');

		$this->db->where('id_trans_masuk', $id);
		$this->db->delete('tbl_trans_masuk_detail');
	}

	public function get_by_id($id)
	{
		$this->db->from('tbl_trans_beli');
		$this->db->where('id_trans_beli',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_list_pembelian($idTransBeli)
	{
		$this->db->select('
			tbl_trans_beli_detail.id_trans_beli_detail,
			tbl_trans_beli_detail.id_trans_beli,
			tbl_trans_beli_detail.id_barang, 
			tbl_barang.nama_barang,
		    tbl_trans_beli_detail.id_satuan,
			tbl_satuan.nama_satuan,
		    tbl_trans_beli_detail.qty_beli,
		    tbl_trans_beli_detail.keterangan_beli,
		    tbl_trans_beli_detail.id_trans_masuk,
		');
		$this->db->from('tbl_trans_beli_detail');
		$this->db->join(
			'tbl_barang',
			'tbl_trans_beli_detail.id_barang = tbl_barang.id_barang',
			'left');
		$this->db->join(
			'tbl_satuan',
			'tbl_trans_beli_detail.id_satuan = tbl_satuan.id_satuan',
			'left');
		$this->db->where('tbl_trans_beli_detail.id_trans_beli', $idTransBeli);
		$this->db->where('tbl_trans_beli_detail.id_trans_masuk', "0");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_list_supplier($idTransBeli)
	{
		$this->db->select('
			tbl_trans_beli.id_trans_beli,
			tbl_trans_beli.id_supplier,
			tbl_supplier.nama_supplier,
		');
		$this->db->from('tbl_trans_beli');
		$this->db->join(
			'tbl_supplier',
			'tbl_trans_beli.id_supplier = tbl_supplier.id_supplier',
			'left');
		$this->db->where('tbl_trans_beli.id_trans_beli', $idTransBeli);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_detail_penerimaan($id_trans_masuk)
	{
		$this->db->select(' tbl_barang.id_barang,
							tbl_barang.nama_barang,
							tbl_satuan.nama_satuan,
							tbl_satuan.id_satuan,
							tbl_trans_beli_detail.id_trans_beli_detail,
							tbl_trans_masuk.tgl_trans_masuk,
							tbl_trans_masuk_detail.id_trans_beli,
							tbl_trans_masuk_detail.id_trans_masuk_detail,
							tbl_trans_masuk_detail.id_trans_masuk,
							tbl_trans_masuk_detail.qty_masuk,
							tbl_trans_masuk_detail.keterangan_masuk');
		$this->db->from('tbl_trans_masuk_detail');
		$this->db->join('tbl_trans_beli_detail', 'tbl_trans_beli_detail.id_trans_beli_detail = tbl_trans_masuk_detail.id_trans_beli_detail','right');
		$this->db->join('tbl_trans_masuk', 'tbl_trans_masuk.id_trans_masuk = tbl_trans_masuk_detail.id_trans_masuk','left');
		$this->db->join('tbl_barang', 'tbl_barang.id_barang = tbl_trans_masuk_detail.id_barang','left');
		$this->db->join('tbl_satuan', 'tbl_satuan.id_satuan = tbl_trans_masuk_detail.id_satuan','left');

        $this->db->where('tbl_trans_masuk.id_trans_masuk', $id_trans_masuk);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_detail_penerimaan_header($id_trans_masuk)
	{
		$this->db->select('tbl_trans_masuk.id_trans_masuk,
							tbl_trans_masuk.tgl_trans_masuk,
							tbl_user.id_user,
							tbl_user.username,
							tbl_user_detail.nama_lengkap_user,
							tbl_supplier.id_supplier,
							tbl_supplier.nama_supplier,
							tbl_supplier.alamat_supplier');

		$this->db->from('tbl_trans_masuk');
		$this->db->join('tbl_user', 'tbl_trans_masuk.id_user = tbl_user.id_user','left');
		$this->db->join('tbl_user_detail', 'tbl_trans_masuk.id_user = tbl_user_detail.id_user','left');
		$this->db->join('tbl_supplier','tbl_trans_masuk.id_supplier = tbl_supplier.id_supplier','left');
        $this->db->where('tbl_trans_masuk.id_trans_masuk', $id_trans_masuk);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_edit_penerimaan_header($id_trans_masuk)
	{
		$this->db->select('tbl_trans_masuk.id_trans_masuk,
							tbl_trans_masuk.tgl_trans_masuk,
							tbl_trans_masuk_detail.id_trans_beli,
							tbl_user.id_user,
							tbl_user.username,
							tbl_user_detail.nama_lengkap_user,
							tbl_supplier.id_supplier,
							tbl_supplier.nama_supplier,
							tbl_supplier.alamat_supplier');

		$this->db->from('tbl_trans_masuk');
		$this->db->join('tbl_user', 'tbl_trans_masuk.id_user = tbl_user.id_user','left');
		$this->db->join('tbl_user_detail', 'tbl_trans_masuk.id_user = tbl_user_detail.id_user','left');
		$this->db->join('tbl_supplier','tbl_trans_masuk.id_supplier = tbl_supplier.id_supplier','left');
		$this->db->join('tbl_trans_masuk_detail','tbl_trans_masuk.id_trans_masuk = tbl_trans_masuk_detail.id_trans_masuk','left');
        $this->db->where('tbl_trans_masuk.id_trans_masuk', $id_trans_masuk);
        $this->db->group_by('tbl_trans_masuk_detail.id_trans_beli');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

    function getKodeTransMasuk(){
            $q = $this->db->query("SELECT MAX(RIGHT(id_trans_masuk,5)) as kode_max from tbl_trans_masuk WHERE DATE_FORMAT(tgl_trans_masuk, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')");
            $kd = "";
            if($q->num_rows()>0){
                foreach($q->result() as $k){
                    $tmp = ((int)$k->kode_max)+1;
                    $kd = sprintf("%05s", $tmp);
                }
            }else{
                $kd = "00001";
            }
            return "RCV".date('my').$kd;
    }

    public function hapus_data_masuk_detail($id)
	{
		$this->db->where('id_trans_masuk', $id);
		$this->db->delete('tbl_trans_masuk_detail');
	}

	public function insert_update_penerimaan($data_masuk_detail)
	{
		$this->db->insert_batch('tbl_trans_masuk_detail',$data_masuk_detail);
	}

	public function lookup_id_pembelian($keyword = "")
	{
		$this->db->select('id_trans_beli');
		$this->db->from('tbl_trans_beli_detail');
		$this->db->where('id_trans_masuk',"0");
		$this->db->like('id_trans_beli',$keyword);
		$this->db->limit(5);
		$this->db->order_by('id_trans_beli', 'DESC');
		$this->db->group_by('id_trans_beli');
		$query = $this->db->get();
		return $query->result();
	}

    function satuan(){
		$this->db->order_by('name','ASC');
		$namaSatuan= $this->db->get('tbl_satuan,tbl_barang');
		return $namaSatuan->result_array();
	}

	public function save($data_masuk, $data_masuk_detail)
	{
		//insert into tbl_trans_masuk 
		$this->db->insert('tbl_trans_masuk',$data_masuk);

		//insert into tbl_trans_masuk_detail 
		$this->db->insert_batch('tbl_trans_masuk_detail',$data_masuk_detail);
	}

	public function update_data_header_masuk($where, $data_header)
	{
		$this->db->update('tbl_trans_masuk', $data_header, $where);
		return $this->db->affected_rows();
	}

	public function update_by_id($where, $nilai)
	{
		$this->db->update('tbl_trans_beli_detail', $nilai, $where);
	}

}