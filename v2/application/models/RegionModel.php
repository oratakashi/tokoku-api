<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RegionModel extends CI_Model
{
    public function getRegionByRegencyId($id)
    {
        return $this->db->get_where("view_region", ["regency_id" => $id])->row_array();
    }

    public function searchRegency($keyword)
    {
        $this->db->like("regency_name", $keyword);
        return $this->db->get('view_region')->result_array();
    }

    public function searchProvince($keyword)
    {
        $this->db->like("province_name", $keyword);
        $this->db->group_by('province_id');
        $this->db->select('province_id, province_name');
        return $this->db->get('view_region')->result_array();
    }
}
