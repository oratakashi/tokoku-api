<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SettingModel extends CI_Model
{
    public function insert($data)
    {
        $this->db->insert("setting", $data);
        return $this->db->affected_rows();
    }
}