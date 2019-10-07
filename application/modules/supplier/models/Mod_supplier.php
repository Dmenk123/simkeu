<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_supplier extends CI_Model
{
	var $table = 'tbl_supplier';
	var $column_order = array('id_supplier','nama_supplier','alamat_supplier','telp_supplier','status',null,null); //set column field database for datatable orderable
	var $column_search = array('id_supplier','nama_supplier','alamat_supplier','telp_supplier','status'); //set column field database for datatable searchable just id_barang and nama_barang are searchable
	var $order = array('id_supplier' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->from($this->table);

		$i = 0;
	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
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

		$this->db->select('id_supplier,nama_supplier,alamat_supplier,telp_supplier,status');
		
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
		$this->db->where('id_supplier',$id);
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
		$this->db->where('id_supplier', $id);
		$this->db->delete($this->table);
	}

	public function getKodeSupplier(){

	    $q = $this->db->query("select MAX(RIGHT(id_supplier,5)) as kode_max from tbl_supplier");
        $kd = "";
        if($q->num_rows()>0){
            foreach($q->result() as $hasil){
                $tmp = ((int)$hasil->kode_max)+1;
                $kd = sprintf("%05s", $tmp);
            }
        }else{
                $kd = "00001";
        }

        return "SUP".$kd;
    }


}