<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_barang extends CI_Model
{
	var $table = 'tbl_barang';
	//set column field database for datatable orderable
	var $column_order = array('id_barang','nama_barang','tbl_barang.id_satuan','tbl_barang.stok_barang','tbl_barang.ss_barang','tbl_barang.rop_barang','tbl_barang.id_kategori','tbl_barang.status',null); 
	//set column field database for datatable searchable
	var $column_search = array('id_barang','nama_barang','tbl_barang.id_satuan','tbl_barang.id_kategori','tbl_barang.status');  
	// default order 
	var $order = array('id_barang' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		
		//$this->db->from($this->table);
		$this->db->from($this->table);

		$i = 0;
		// loop column 
		foreach ($this->column_search as $item) 
		{
			// if datatable send POST for search
			if($_POST['search']['value']) 
			{
				// first loop
				if($i===0) 
				{
					// open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}
				//last loop
				if(count($this->column_search) - 1 == $i) 
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		// here order processing
		if(isset($_POST['order'])) 
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);

		$this->db->select('id_barang,nama_barang,nama_satuan,stok_barang,ss_barang,rop_barang,keterangan_kategori,tbl_barang.status');
		$this->db->from('tbl_satuan,tbl_kategori');
		$this->db->where('tbl_barang.id_satuan = tbl_satuan.id_satuan and tbl_barang.id_kategori = tbl_kategori.id_kategori');

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_barang',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id_barang', $id);
		$this->db->delete($this->table);
	}

	public function ambil_kategori($nama_kategori)
	{
		$this->db->select('*');
		$this->db->from('tbl_kategori');
		$this->db->where('id_kategori', $nama_kategori);
		
		$query = $this->db->get();
		return $query->result();
	}

	function getKodeBarang($barang){
            $q = $this->db->query("select MAX(RIGHT(id_barang,5)) as kode_max from tbl_barang where id_barang like '%$barang%'");
            $kd = "";
            if($q->num_rows()>0){
                foreach($q->result() as $hasil){
                    $tmp = ((int)$hasil->kode_max)+1;
                    $kd = sprintf("%05s", $tmp);
                }
            }else{
                $kd = "00001";
            }
            return "$barang".$kd;
    }

    function satuan(){
		$this->db->order_by('name','ASC');
		$namaSatuan= $this->db->get('tbl_satuan,tbl_barang');
		return $namaSatuan->result_array();
	}

}