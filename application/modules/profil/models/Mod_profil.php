<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_profil extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	public function save($data_user, $data_user_detail)
	{
		//insert into tbl_user 
		$this->db->insert('tbl_user',$data_user);

		//insert into tbl_user_detail 
		$this->db->insert('tbl_user_detail',$data_user_detail);
	}

	public function update($where, $data)
	{
		$this->db->update('tbl_user_detail', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id_user', $id);
		$this->db->delete($this->table);
	}

	public function get_detail_pengguna($id_user)
	{
		$this->db->select('*');
		$this->db->from('tbl_user_detail');
		$this->db->where('id_user', $id_user);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	function getKodeUser(){
            $q = $this->db->query("select MAX(RIGHT(id_user,5)) as kode_max from tbl_user");
            $kd = "";
            if($q->num_rows()>0){
                foreach($q->result() as $k){
                    $tmp = ((int)$k->kode_max)+1;
                    $kd = sprintf("%05s", $tmp);
                }
            }else{
                $kd = "00001";
            }
            return "USR".$kd;
    }
}