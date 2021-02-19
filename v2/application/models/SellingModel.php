<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SellingModel extends CI_Model
{
  public function readThisMonth($iduser)
  {
    return $this->db->query("SELECT * FROM tblpenjualan where MONTH(tgl) = MONTH(CURRENT_DATE()) AND YEAR(tgl) = YEAR(CURRENT_DATE()) and iduser='$iduser'")->result_array();
  }

  public function readFromDate($from, $to, $iduser)
  {
    $this->db->where("tgl BETWEEN '$from' and '$to'");
    $this->db->where('iduser', $iduser);

    $this->db->order_by('tgl', 'desc');
    return $this->db->get('tblpenjualan')->result_array();
  }

  public function readById($id)
  {
    return $this->db->get_where('tblpenjualan', ["kode_penjualan" => $id])->row_array();
  }

  public function readDetailById($id)
  {
    return $this->db->get_where('dtlpenjualan', ["kode_penjualan" => $id])->result_array();
  }

  public function delete($id, $iduser)
  {
    $this->db->where('kode_penjualan', $id);
    $this->db->where('iduser', $iduser);
    $this->db->delete('tblpenjualan');
    return $this->db->affected_rows();
  }
}

/* End of file SellingModel.php */
