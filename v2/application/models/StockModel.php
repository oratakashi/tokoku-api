<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StockModel extends CI_Model
{
  public function readThisMonth($iduser)
  {
    return $this->db->query("SELECT * FROM tbltambah_stok where MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE()) and iduser='$iduser' limit 20 offset 0")->result_array();
  }

  public function readThisMonthLimit($iduser, $offset)
  {
    return $this->db->query("SELECT * FROM tbltambah_stok where MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE()) and iduser='$iduser' limit 20 offset $offset")->result_array();
  }

  public function readFromDate($from, $to, $iduser)
  {
    $this->db->where("tanggal BETWEEN '$from' and '$to'");
    $this->db->where('iduser', $iduser);
    $this->db->order_by('tanggal', 'desc');
    return $this->db->get('tbltambah_stok', 20, 0)->result_array();
  }

  public function readFromDateLimit($from, $to, $iduser, $offset)
  {
    $this->db->where("tanggal BETWEEN '$from' and '$to'");
    $this->db->where('iduser', $iduser);
    $this->db->order_by('tanggal', 'desc');
    return $this->db->get('tbltambah_stok', 20, $offset)->result_array();
  }
}

/* End of file StockModel.php */
