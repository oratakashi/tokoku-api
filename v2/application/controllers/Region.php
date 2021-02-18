<?php


defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Region extends REST_Controller
{


  public function __construct()
  {
    parent::__construct();
    $this->load->model('RegionModel', 'region');
  }


  public function index_get()
  {
    if (!empty($this->uri->segment(2))) {
      if (!empty($this->get("keyword"))) {
        if ($this->uri->segment(2) == "regencies") {
          $this->searchRegency();
        } else if ($this->uri->segment(2) == "provinces") {
          $this->searchProvince();
        } else {
          $message = array(
            "status"        => FALSE,
            "message"       => "Not Found",
            "data"          => null
          );

          $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
      } else {
        $message = array(
          "status"        => FALSE,
          "message"       => "Please insert a Keyword!",
          "data"          => null
        );

        $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
      }
    } else {
      $message = array(
        "status"        => FALSE,
        "message"       => "Not Found",
        "data"          => null
      );

      $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  public function searchRegency()
  {
    $data_regency = $this->region->searchRegency($this->get("keyword"));

    $message = array(
      "status"        => TRUE,
      "message"       => "Found " . count($data_regency) . " regencies",
      "data"          => $data_regency
    );

    $this->response($message, REST_Controller::HTTP_OK);
  }

  public function searchProvince()
  {
    $data_province = $this->region->searchProvince($this->get("keyword"));

    $message = array(
      "status"        => TRUE,
      "message"       => "Found " . count($data_province) . " province",
      "data"          => $data_province
    );

    $this->response($message, REST_Controller::HTTP_OK);
  }
}

/* End of file Region.php */
