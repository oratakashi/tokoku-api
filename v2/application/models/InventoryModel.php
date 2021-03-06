<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InventoryModel extends CI_Model
{
  public function readLimit($iduser, $offset)
  {
    $this->db->where('iduser', $iduser);
    return $this->db->get('stok_bahan', 20, $offset)->result_array();
  }

  public function searchLimit($keyword, $iduser, $offset)
  {
    $this->db->like("nama_bahan", $keyword);
    $this->db->where('iduser', $iduser);
    return $this->db->get('stok_bahan', 20, $offset)->result_array();
  }

  public function readTambahStokTotal($id)
  {
    return $this->db->query("SELECT CASE WHEN (SUM(jumlah)) IS NOT NULL THEN (SUM(jumlah)) ELSE 0 END AS total from dtltambah_stok where id_bahan='$id'")->row_array();
  }

  public function readPenjualanTotal($id)
  {
    return $this->db->query("SELECT CASE WHEN (SUM(jumlah)) IS NOT NULL THEN (SUM(jumlah)) ELSE 0 END AS total from dtlpenjualan where idbarang='$id'")->row_array();
  }

  public function getTotal($iduser)
  {
    return $this->db->query("
      SELECT SUM( ((SELECT CASE WHEN (SUM(dtltambah_stok.jumlah)) IS NOT NULL THEN (SUM(dtltambah_stok.jumlah)) ELSE 0 END AS total from dtltambah_stok where dtltambah_stok.id_bahan=stock.id_bahan) - (SELECT CASE WHEN (SUM(dtlpenjualan.jumlah)) IS NOT NULL THEN (SUM(dtlpenjualan.jumlah)) ELSE 0 END AS total from dtlpenjualan where dtlpenjualan.idbarang=stock.id_bahan)) * stock.harga_per ) as total FROM stok_bahan stock where iduser='$iduser'
    ")->row_array();
  }
}

/* End of file InventoryModel.php */
