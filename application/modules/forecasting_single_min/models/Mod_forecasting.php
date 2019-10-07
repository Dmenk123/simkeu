<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_forecasting extends CI_Model
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
			'tbl_forecast.id_forecast',
			'tbl_barang.nama_barang',
			'tbl_barang.id_barang',
			'tbl_forecast.tgl_forecast',
			'tbl_forecast.alpha_forecast',
			'tbl_forecast.hasil_forecast',
			'tbl_forecast.mad_forecast',
			'tbl_forecast.mae_forecast',
			'tbl_forecast.mape_forecast',
			);

		$this->db->select('
			tbl_forecast.id_forecast,
			tbl_barang.nama_barang,
			tbl_barang.id_barang,
			tbl_forecast.tgl_forecast,
			tbl_forecast.alpha_forecast,
			tbl_forecast.hasil_forecast,
			tbl_forecast.mad_forecast,
			tbl_forecast.mae_forecast,
			tbl_forecast.mape_forecast
			');

		$this->db->from('tbl_forecast');
		//join 'tbl', on 'tbl = tbl' , type join
		$this->db->join(
			'tbl_barang',
			'tbl_forecast.id_barang = tbl_barang.id_barang','left');

		$this->db->like('tbl_barang.nama_barang',$term);
		// $this->db->group_by('tbl_trans_keluar.id_trans_keluar');
		
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
		$this->db->from('tbl_forecast');
		return $this->db->count_all_results();
	}

	//end datatable setting

	public function get_pengeluaran_barang_grafik($id_barang, $bulan_index, $bulan_end , $period_tampil)
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
      				'".$bulan_end."' - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY as Date
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
		    	DATE_FORMAT(tgl_trans_keluar_detail, '%b') AS month,
		    	id_barang,
		    	SUM(qty_keluar) as amount,
		    	DATE_FORMAT(tgl_trans_keluar_detail, '%m-%Y') as md,
		    	DATE_FORMAT(tgl_trans_keluar_detail, '%Y') as tahun
  			FROM tbl_trans_keluar_detail
  			WHERE tgl_trans_keluar_detail < '".$bulan_index."' and tgl_trans_keluar_detail > Date_add('".$bulan_index."',interval - '".$period_tampil."' month) AND id_barang = '".$id_barang."'
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

	public function save_peramalan($data)
	{
		//insert into tbl_forecast
		$this->db->insert('tbl_forecast',$data);
	}

	public function delete_by_id($id)
	{
		$this->db->where('id_forecast', $id);
		$this->db->delete('tbl_forecast');
	}

}