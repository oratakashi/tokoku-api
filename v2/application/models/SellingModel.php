<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SellingModel extends CI_Model
{
  public function readThisMonth($iduser)
  {
    return $this->db->query("SELECT * FROM tblpenjualan where MONTH(tgl) = MONTH(CURRENT_DATE()) AND YEAR(tgl) = YEAR(CURRENT_DATE()) and iduser='$iduser'")->result_array();
  }

  public function readById($id)
  {
    return $this->db->get_where('tblpenjualan', ["kode_penjualan" => $id])->row_array();
  }
}

/* End of file SellingModel.php */
