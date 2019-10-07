<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_pesan extends CI_Model
{
	var $column_search = array('tbl_pesan.id_pesan','tbl_user_detail.nama_lengkap_user','tbl_pesan.subject_pesan','tbl_pesan.isi_pesan');

	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	private function _get_datatables_query($term="", $id_user) //term is value of $_REQUEST['search']
	{
		$column = array(
			'tbl_pesan.id_pesan',
			'tbl_pesan.subject_pesan',
			'tbl_user_detail.nama_lengkap_user',
			'tbl_pesan.isi_pesan',
			'tbl_pesan.dt_post',
			);

		$this->db->select('
			tbl_pesan.id_pesan,
			tbl_pesan.subject_pesan,
			tbl_user_detail.nama_lengkap_user,
			tbl_pesan.isi_pesan,
			tbl_pesan.dt_post,
			');

		$this->db->from('tbl_pesan');
		//join 'tbl', on 'tbl = tbl' , type join
		$this->db->join(
			'tbl_user_detail',
			'tbl_pesan.id_user_target = tbl_user_detail.id_user','left');
		$this->db->where('tbl_pesan.id_user',$id_user);
		//$this->db->where("tbl_user_detail.nama_lengkap_user LIKE '".$term."' OR tbl_pesan.isi_pesan LIKE '".$term."' OR tbl_pesan.dt_post LIKE '".$term."'");
		$this->db->like('tbl_user_detail.nama_lengkap_user', $term);
		// $this->db->or_like('tbl_pesan.subject_pesan', $term);

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

	function get_datatables($id_user)
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatables_query($term, $id_user);

		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($id_user)
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatables_query($term, $id_user);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from('tbl_pesan');
		return $this->db->count_all_results();
	}

	// end setting datatable

	public function get_by_id($id)
	{
		$this->db->select('tbl_pesan.id_pesan, tbl_pesan.id_user, tbl_pesan.id_user_target, tbl_pesan.subject_pesan, tbl_pesan.isi_pesan');
		$this->db->from('tbl_pesan');
		$this->db->where('tbl_pesan.id_pesan',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save_data_pesan($data)
	{
		$this->db->insert('tbl_pesan',$data);
		return $this->db->insert_id();
	}

	public function update_feedback($where, $data)
	{
		$this->db->update('tbl_pesan', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_data_pesan($id)
	{
		$this->db->where('id_pesan', $id);
		$this->db->delete('tbl_pesan');
	}

	function notif_count($id_user) 
	{
        $this->db->from('tbl_pesan');
        $this->db->where('tbl_pesan.id_user_target', $id_user);
        $this->db->where('dt_read', null);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    function get_notifikasi($id_user) 
    {
    	$this->db->select('
			tbl_pesan.id_pesan,
			tbl_user_detail.nama_lengkap_user,
			tbl_pesan.subject_pesan,
			tbl_pesan.isi_pesan,
			tbl_pesan.dt_post,
			tbl_pesan.time_post,
			');
        $this->db->from('tbl_pesan');
        $this->db->join(
			'tbl_user_detail',
			'tbl_pesan.id_user = tbl_user_detail.id_user','left');
        $this->db->where('tbl_pesan.id_user_target', $id_user);
        $this->db->where('tbl_pesan.dt_read', null);
        $this->db->order_by('tbl_pesan.id_pesan', 'DESC');
 
        $query = $this->db->get();
 
        if ($query->num_rows() >0) {
            return $query->result();
        }
    }
	

}