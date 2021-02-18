<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Report extends REST_Controller
{


  public function __construct()
  {
    parent::__construct();
    $this->load->model('SellingModel', 'selling');
  }


  public function index_get()
  {
    if (!empty($this->uri->segment(2))) {
      if ($this->uri->segment(2) === "sales") {
        $this->sales();
      } else if ($this->uri->segment(2) === "stocks") {
        $this->stocks();
      } else if ($this->uri->segment(2) === "inventories") {
        $this->inventories();
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

  public function sales()
  {
    if (!empty($this->get("iduser"))) {
      if (empty($this->uri->segment(3))) {
        if (empty($this->get("from")) && empty($this->get("to"))) {
          $query = $this->selling->readThisMonth($this->get("iduser"));
          $total = 0;
          foreach ($query as $row) {
            $total += $row["total"];
          }
          $data = array(
            "total"   => $total,
            "report"  => $query
          );

          $message = array(
            "status"        => TRUE,
            "message"       => "Found " . count($query) . " Data",
            "data"          => $data
          );

          $this->response($message, REST_Controller::HTTP_OK);
        }
      } else {
        $this->sales_detail();
      }
    } else {
      $message = array(
        "status"        => FALSE,
        "message"       => "ID User Tidak dikenali",
        "data"          => null
      );

      $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  public function sales_detail()
  {
    $this->response($this->selling->readById($this->uri->segment(3)), REST_Controller::HTTP_BAD_REQUEST);
  }

  public function stocks()
  {
  }

  public function inventories()
  {
  }
}

/* End of file Report.php */
