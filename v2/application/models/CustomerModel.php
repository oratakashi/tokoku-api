<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerModel extends CI_Model
{
  public function getById($id)
  {
    $this->db->select('id_customers, nama_customers, telp, alamat_customers, surel');

    return $this->db->get_where('tblcustomers', ["id_customers" => $id])->row_array();
  }
}

/* End of file CustomerModel.php */
