<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_grafik_barang extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		//alternative load library from config
		$this->load->database();
	}

	public function get_penerimaan_barang($id_barang, $bulan_index, $period_tampil)
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
  			WHERE a.Date < '".$bulan_index."' and a.Date > Date_add('".$bulan_index."',interval - '".$period_tampil."' month)
  			GROUP BY month
			)t1

		LEFT JOIN (
			SELECT
		    	DATE_FORMAT(tgl_trans_masuk_detail, '%b') AS month,
		    	id_barang,
		    	SUM(qty_masuk) as amount,
		    	DATE_FORMAT(tgl_trans_masuk_detail, '%m-%Y') as md,
		    	DATE_FORMAT(tgl_trans_masuk_detail, '%Y') as tahun
  			FROM tbl_trans_masuk_detail
  			WHERE tgl_trans_masuk_detail < '".$bulan_index."' and tgl_trans_masuk_detail > Date_add('".$bulan_index."',interval - '".$period_tampil."' month) AND id_barang = '".$id_barang."'
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


}