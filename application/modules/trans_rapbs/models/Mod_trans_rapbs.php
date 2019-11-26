<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_trans_rapbs extends CI_Model
{
	var $column_search = array(
		"tr.id",
		"tr.tahun",
		"tr.created_at",
		"tud.nama_lengkap_user",
	);

	var $column_order = array(
		"tr.id",
		"tr.tahun",
		"tr.created_at",
		"tud.nama_lengkap_user",
		null
	);

	var $order = array('tr.created_at' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($term='', $tahun)
	{
		$column = array(
			"tr.id",
			"tr.tahun",
			"tm.created_at",
			"tud.nama_lengkap_user",
			null,
		);

		$this->db->select("tr.*, tud.nama_lengkap_user");
		$this->db->from('tbl_rapbs as tr');
		$this->db->join('tbl_user as tu', 'tr.user_id = tu.id_user', 'left');
		$this->db->join('tbl_user_detail as tud', 'tu.id_user = tud.id_user', 'left');
		$this->db->where("tr.tahun = '".$tahun."' and tr.deleted_at is null");
				
		$i = 0;
		foreach ($this->column_search as $item) 
		{
			if($_POST['search']['value']) 
			{
				if($i===0) 
				{
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if(count($this->column_search) - 1 == $i) 
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

	function get_datatables($tahun)
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatables_query($term, $tahun);

		if($_REQUEST['length'] != -1)
		$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($tahun)
	{
		$term = $_REQUEST['search']['value'];
		$this->_get_datatables_query($term, $tahun);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($tahun)
	{
		$this->db->select("tr.*, tud.nama_lengkap_user");
		$this->db->from('tbl_rapbs as tr');
		$this->db->join('tbl_user as tu', 'tr.user_id = tu.id_user', 'left');
		$this->db->join('tbl_user_detail as tud', 'tu.id_user = tud.id_user', 'left');
		$this->db->where("tr.tahun = '".$tahun."' and tr.deleted_at is null");
		return $this->db->count_all_results();
	}

	public function get_data_field()
	{
		$query = $this->db->query("SELECT * FROM tbl_master_kode_akun_internal where is_aktif = 1 ORDER BY kode, sub_1 asc")->result();
		return $query;
	}

	// ================================================================================

	public function save($data_header=null, $data_detail=null, $data_verifikasi=null)
	{ 
		if ($data_header != null) {
			$this->db->insert('tbl_trans_masuk',$data_header);
		}
		
		if ($data_detail != null) {
			$this->db->insert('tbl_trans_masuk_detail',$data_detail);
		}

		if ($data_verifikasi != null) {
			$this->db->insert('tbl_verifikasi',$data_verifikasi);
		}
	}

	function getKodePenerimaan(){
		$q = $this->db->query("SELECT MAX(RIGHT(id,5)) as kode_max from tbl_trans_masuk WHERE DATE_FORMAT(tanggal, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')");
		$kd = "";
		if($q->num_rows()>0){
			foreach($q->result() as $k){
				$tmp = ((int)$k->kode_max)+1;
				$kd = sprintf("%05s", $tmp);
			}
		}else{
			$kd = "00001";
		}
		return "MSK".date('my').$kd;
	}
	
	function getKodePenerimaanDetail(){
		$q = $this->db->query("SELECT MAX(id) as kode_detail from tbl_trans_masuk_detail")->row();
		if ($q) {
			$kode = (int)$q->kode_detail + 1;
		}else{
			$kode = 1;
		}

		return $kode;
    }

	public function get_detail_header($id)
	{
		$this->db->select('tm.*,tud.nama_lengkap_user');
		$this->db->from('tbl_trans_masuk tm');
		$this->db->join('tbl_user tu', 'tm.user_id = tu.id_user','left');
		$this->db->join('tbl_user_detail tud', 'tu.id_user = tud.id_user', 'left');
        $this->db->where('tm.id', $id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function get_detail($id_header, $edit='')
	{
		if ($edit == '') {
			$this->db->select('tmd.*, tv.*, ts.nama as nama_satuan');
			$this->db->from('tbl_trans_masuk_detail tmd');
			$this->db->join('tbl_satuan ts', 'tmd.satuan = ts.id','left');
			$this->db->join('tbl_verifikasi tv', 'tv.id_in = tmd.id_trans_masuk');
		}else{
			$this->db->select('tm.*, tmd.id as id_detail, tmd.id_trans_masuk, tmd.keterangan, tmd.satuan, tmd.qty, ts.nama as nama_satuan');
			$this->db->from('tbl_trans_masuk tm');
			$this->db->join('tbl_trans_masuk_detail tmd', 'tm.id = tmd.id_trans_masuk');
			$this->db->join('tbl_satuan ts', 'tmd.satuan = ts.id','left');
		}
				
        $this->db->where('tmd.id_trans_masuk', $id_header);

        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            if ($edit = '') {
            	return $query->result();
            }else{
            	return $query->row();
            }
        }
	}

	public function update_data($where, $data, $table)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function insert_update($data_order_detail)
	{
		$this->db->insert_batch('tbl_trans_keluar_detail',$data_order_detail);
	}

	// ============================================================================================

	public function get_by_id($id)
	{
		$this->db->from('tbl_trans_order');
		$this->db->where('id_trans_order',$id);
		$query = $this->db->get();

		return $query->row();
	}

	

	public function delete_by_id($id)
	{
		$this->db->where('id_trans_order', $id);
		$this->db->delete('tbl_trans_order');

		$this->db->where('id_trans_order', $id);
		$this->db->delete('tbl_trans_order_detail');
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