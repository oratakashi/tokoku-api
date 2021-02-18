<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Users extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();


        $this->load->model('UserModel', 'users');
        $this->load->model('RegionModel', 'region');
        $this->load->model('SettingModel', 'setting');
    }

    public function index_get()
    {
        $message = array(
            "status"        => FALSE,
            "message"       => "Not Found",
            "data"          => null
        );

        $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
    }

    public function index_post()
    {
        if (!empty($this->uri->segment(2))) {
            if ($this->uri->segment(2) === "register") {
                $this->register();
            }
        } else {
            $message = array(
                "status"    => FALSE,
                "message"   => "Not Found",
                "data"      => null
            );

            $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function register()
    {
        $iduser = (strtotime("now"));
        $data_user = array(
            "id"        => $iduser,
            "fullname"      => $this->post("name"),
            "username"      => $this->post("username"),
            "password"      => md5($this->post("password")),
            "email"         => $this->post("email"),
            "plan"          => $this->post("plan"),
            "no_tlp"        => $this->post("notelp"),
            "alamat"        => $this->post("alamat"),
            "status"        => 1,
            "tgl_reg"       => date("Y-m-d H:i:s")
        );

        $data_setting = array(
            "iduser"        => $iduser,
            "perusahaan"    => $this->post("perusahaan"),
            "alamat"        => $this->post("alamat"),
            "tlp"          => $this->post("notelp"),
            "namap"         => $this->post("name"),
            "pesan"         => "Terima Kasih Atas Kunjungannya"
        );

        if (!empty($this->post("regency_id"))) {
            $data_region = $this->region->getRegionByRegencyId($this->post("regency_id"));

            $data_user["provi"] = $data_region["province_id"];
            $data_user["kotab"] = $data_region["regency_id"];

            if (empty($this->users->findByUsername($this->post("username")))) {
                $user = $this->users->insert($data_user);
                $seting = $this->setting->insert($data_setting);

                if ($user > 0 && $seting > 0) {
                    $message = array(
                        "status"    => TRUE,
                        "message"   => "Registration has been success!",
                        "data"      => $this->users->getById($iduser)
                    );

                    $this->response($message, REST_Controller::HTTP_OK);
                } else {
                    $message = array(
                        "status"    => FALSE,
                        "message"   => "Failed to register new user!, Please Try Again!",
                        "data"      => null
                    );

                    $this->response($message, REST_Controller::HTTP_CONFLICT);
                }
            } else {
                $message = array(
                    "status"    => FALSE,
                    "message"   => "Username already used!",
                    "data"      => null
                );

                $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $message = array(
                "status"    => FALSE,
                "message"   => "Regency ID null!",
                "data"      => null
            );

            $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
