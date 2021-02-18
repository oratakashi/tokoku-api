<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model
{
    public function findByUsername($username){
        return $this->db->get_where("tab_user", ["username" => $username])->result_array();
    }

    public function insert($data){
        $this->db->insert("tab_user", $data);
        return $this->db->affected_rows();
    }

    public function getById($id){
        $this->db->join('setting', 'setting.iduser = tab_user.id');
        $this->db->where('tab_user.id', $id);
        return $this->db->get('tab_user')->row_array();
    }
}