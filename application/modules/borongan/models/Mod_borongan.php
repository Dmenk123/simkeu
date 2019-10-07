<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_borongan extends CI_Model
{
	var $table = 'tbl_borongan';
	var $table2 = 'tbl_borongan_detail';
	var $column_order = array('id_borongan','nama_borongan','alamat_borongan','keterangan_borongan','telp_borongan','status',null,null);
	var $column_order2 = array('id_borongan_detail','nama_borongan_detail','alamat_borongan_detail','telp_borongan_detail','status',null,null);
	var $column_search = array('id_borongan','nama_borongan','alamat_borongan','keterangan_borongan','telp_borongan','status');
	var $column_search2 = array('id_borongan_detail','nama_borongan_detail','nama_borongan_detail','telp_borongan_detail','status');
	var $order = array('id_borongan' => 'desc'); // default order
	var $order2 = array('id_borongan_detail' => 'desc'); // default order 

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

		$this->db->select('id_borongan,nama_borongan,alamat_borongan,keterangan_borongan,telp_borongan,status');
		
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

	private function _get_datatables_personil_query()
	{
		$this->db->from($this->table2);

		$i = 0;
	
		foreach ($this->column_search2 as $item) // loop column 
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

				if(count($this->column_search2) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables_personil($id_borongan)
	{
		$this->_get_datatables_personil_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);

		$this->db->select('id_borongan_detail,
						  id_borongan,
						  nama_borongan_detail,
						  alamat_borongan_detail,
						  telp_borongan_detail,
						  status');
		$this->db->where('id_borongan', $id_borongan);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_personil()
	{
		$this->_get_datatables_personil_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_personil()
	{
		$this->db->from($this->table2);
		return $this->db->count_all_results();
	}

	public function get_detail_header($id_borongan)
	{
		$this->db->select('id_borongan,nama_borongan');
		$this->db->from('tbl_borongan');
        $this->db->where('id_borongan', $id_borongan);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_detail_borongan($id)
	{
		$this->db->select(' id_borongan_detail,
							id_borongan,
							nama_borongan_detail,
							alamat_borongan_detail,
							telp_borongan_detail,
							status');
		$this->db->from('tbl_borongan_detail');
        $this->db->where('id_borongan', $id);

        $query = $this->db->get();
		return $query->result();
	}
	
	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_borongan',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_by_id_personil($id)
	{
		$this->db->from($this->table2);
		$this->db->where('id_borongan_detail',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function save_personil($data)
	{
		$this->db->insert($this->table2, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function update_data_personil($where, $data)
	{
		$this->db->update($this->table2, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id_borongan', $id);
		$this->db->delete($this->table);
	}

	public function delete_by_id_personil($id)
	{
		$this->db->where('id_borongan_detail', $id);
		$this->db->delete($this->table2);
	}

	public function getKodeBorongan(){

	    $q = $this->db->query("select MAX(RIGHT(id_borongan,5)) as kode_max from tbl_borongan");
        $kd = "";
        if($q->num_rows()>0){
            foreach($q->result() as $hasil){
                $tmp = ((int)$hasil->kode_max)+1;
                $kd = sprintf("%05s", $tmp);
            }
        }else{
                $kd = "00001";
        }

        return "BOR".$kd;
    }


}