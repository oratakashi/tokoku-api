<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfitModel extends CI_Model
{
  public function getQtyLainLain($iduser)
  {
    $this->db->where('iduser', $iduser);
    $this->db->where("cfg not in ('kt1', 'kt2' , 'kt4', 'rtr')");
    $this->db->select('SUM(nominal) as nominal');
    return $this->db->get('dtltransaksi')->result_array();
  }
}

/* End of file ProfitModel.php */
