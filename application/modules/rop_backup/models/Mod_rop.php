<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_rop extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	public function get_nama_barang($id_barang)
	{
		$this->db->select('tbl_barang.id_barang, tbl_barang.nama_barang, tbl_satuan.nama_satuan');
		$this->db->from('tbl_barang');
		$this->db->join('tbl_satuan', 'tbl_barang.id_satuan = tbl_satuan.id_satuan', 'left');
		$this->db->where('tbl_barang.status','aktif');
		$this->db->where('tbl_barang.id_barang',$id_barang);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_pengeluaran_barang($id_barang, $bulan_index)
	{
		$query =  $this->db->query(
		"SELECT 
			t1.month, t1.md, t1.tahun, coalesce(SUM(t1.amount+t2.amount), 0) AS total
		FROM (
  			SELECT 
			    DATE_FORMAT(a.Date,'%b') as month,
			  	DATE_FORMAT(a.Date, '%m-%Y') as md,
			    DATE_FORMAT(a.Date, '%Y') as tahun,
			  	'0' as  amount
  			FROM (
    			SELECT 
      				curdate() - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date
    			FROM (
				    SELECT 0 as a union all select 1 union all select 2 union all select 3 union all
				    SELECT 4 union all select 5 union all select 6 union all select 7 union all
				        SELECT 8 union all select 9) as a
   				CROSS JOIN (
				    SELECT 0 as a union all select 1 union all select 2 union all select 3 union all
				    SELECT 4 union all select 5 union all select 6 union all select 7 union all
				        SELECT 8 union all select 9) as b
    			CROSS JOIN ( 
				    SELECT 0 as a union all select 1 union all select 2 union all select 3 union all
				    SELECT 4 union all select 5 union all select 6 union all select 7 union all
				    SELECT 8 union all select 9) as c
  				) a
  			WHERE a.Date <= '".$bulan_index."' and a.Date >= Date_add('".$bulan_index."',interval - 12 month)
  			GROUP BY month
			)t1

		LEFT JOIN (
			SELECT
		    	DATE_FORMAT(tgl_trans_keluar_detail, '%b') AS month,
		    	id_barang,
		    	SUM(qty_keluar) as amount,
		    	DATE_FORMAT(tgl_trans_keluar_detail, '%m-%Y') as md,
		    	DATE_FORMAT(tgl_trans_keluar_detail, '%Y') as tahun
  			FROM tbl_trans_keluar_detail
  			WHERE tgl_trans_keluar_detail <= '".$bulan_index."' and tgl_trans_keluar_detail >= Date_add('".$bulan_index."',interval - 12 month) AND id_barang = '".$id_barang."'
  			GROUP BY md
		)t2
		ON t2.md = t1.md 
		GROUP BY t1.month
		ORDER BY t1.tahun ASC, t1.md ASC"
		);

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function lookup_data_barang($keyword = "")
	{
		$this->db->select('id_barang, nama_barang');
		$this->db->from('tbl_barang');
		$this->db->where('status','aktif');
		$this->db->like('nama_barang',$keyword);
		$this->db->limit(10);
		$this->db->order_by('nama_barang', 'ASC');
		//$this->db->group_by('id_trans_beli');
		$query = $this->db->get();
		return $query->result();
	}

	public function update_master($where, $data)
	{
		$this->db->update('tbl_barang', $data, $where);
		return $this->db->affected_rows();
	}

}