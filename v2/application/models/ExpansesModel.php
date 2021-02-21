<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExpansesModel extends CI_Model
{
  public function readThisMonth($iduser, $offset)
  {
    $this->db->where("MONTH(tgl) = MONTH(CURRENT_DATE())");
    $this->db->where("YEAR(tgl) = YEAR(CURRENT_DATE())");
    $this->db->where('iduser', $iduser);
    return $this->db->get('dtltransaksi', 20, $offset)->result_array();
  }

  public function readFromDate($from, $to, $iduser, $offset)
  {
    $this->db->where("tgl BETWEEN '$from' and '$to'");
    $this->db->where('iduser', $iduser);
    $this->db->order_by('tgl', 'desc');
    return $this->db->get("dtltransaksi", 20, $offset)->result_array();
  }
}

/* End of file ExpansesModel.php */
