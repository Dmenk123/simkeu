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

	public function get_detail_pegawai($nip)
	{
		$this->db->select('*');
		$this->db->from('tbl_guru');
		$this->db->where('nip', $nip);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
	}

	public function cek_pass_lama($tipe, $id)
	{
		$flag_pass_guru = 'nip';

		if ($tipe == 'guru') {
			$this->db->select('*');
			$this->db->from('tbl_guru');
			$this->db->where('id', $id);
			$query = $this->db->get()->row();

			if ($query->password == null) {
				$password = $query->nip;
				$flag_pass_guru = 'nip';
			}else{
				$password = $query->password;
				$flag_pass_guru = 'enkripsi';
			}

		}else{
			$this->db->select('*');
			$this->db->from('tbl_user');
			$this->db->where('id_user', $id);
			$query = $this->db->get()->row();
			$password = $query->password;
		}
		
		return [
			'password' => $query->password,
			'flag_pass_guru' => $flag_pass_guru
		];
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

    public function get_detail_guru($nip)
    {
    	$this->db->select('*');
    	$this->db->from('tbl_guru');
    	$this->db->where('nip', $nip);
    	$query = $this->db->get();
    	return $query->row();
	}
	
	public function get_jumlah_guru()
	{
		$this->db->select('*');
		$this->db->from('tbl_guru');
		$this->db->where('is_aktif', '1');
		$this->db->where('is_guru', '1');
		
		return $query = $this->db->get()->num_rows();
	}

	public function get_jumlah_karyawan()
	{
		$this->db->select('*');
		$this->db->from('tbl_guru');
		$this->db->where('is_aktif', '1');
		$this->db->where('is_guru', '0');
		
		return $query = $this->db->get()->num_rows();
	}

	public function get_jumlah_belum_verifikasi()
	{
		$this->db->select('*');
		$this->db->from('tbl_trans_keluar_detail');
		$this->db->where('status', '0');

		return $query = $this->db->get()->num_rows();
	}

	public function get_jumlah_sudah_verifikasi($bulan, $tahun)
	{
		$strBulanTahun = $tahun.'-'.$bulan;
		$query = $this->db->query("
			SELECT tkd.*, tk.tanggal 
			from tbl_trans_keluar_detail tkd 
			join tbl_trans_keluar tk on tkd.id_trans_keluar = tk.id
			where DATE_FORMAT(tk.tanggal, '%Y-%m') = '".$strBulanTahun."'
		")->num_rows();
		
		return $query;
	}

	public function get_jumlah_penerimaan($bulan, $tahun)
	{
		$strBulanTahun = $tahun . '-' . $bulan;
		$query = $this->db->query("
			SELECT tmd.*, tm.tanggal 
			from tbl_trans_masuk_detail tmd 
			join tbl_trans_masuk tm on tmd.id_trans_masuk = tm.id
			where DATE_FORMAT(tm.tanggal, '%Y-%m') = '" . $strBulanTahun . "'
		")->num_rows();

		return $query;
	}
}