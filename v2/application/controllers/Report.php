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
    $this->load->model('CustomerModel', 'customer');
    $this->load->model('StockModel', 'stock');
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
        "message"       => "Not Found",
        "data"          => null
      );

      $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  public function index_delete()
  {
    if (!empty($this->uri->segment(2))) {
      if ($this->uri->segment(2) == "sales") {
        $this->sales_delete();
      }
    } else {
      $message = array(
        "status"        => FALSE,
        "message"       => "Not Found!",
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
        } else if (!empty($this->get("from")) && !empty($this->get("to"))) {
          $to = $this->get("to");
          $from = $this->get("from");

          $query = $this->selling->readFromDate($from, $to, $this->get("iduser"));

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
        } else if (empty($this->get("from")) || empty($this->get("to"))) {
          $message = array(
            "status"        => FALSE,
            "message"       => "Failed to get data by date given!",
            "data"          => null
          );

          $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
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
    $detail = $this->selling->readById($this->uri->segment(3));

    if ($detail != null) {
      if ($detail["idpel"] == "1") {
        unset($detail["idpel"]);
        $detail["pelanggan_detail"] = array(
          "id_customers"  => "1",
          "nama_customers"  => "Umum",
          "telp"  => null,
          "alamat_customers"  => null,
          "surel"  => null
        );
      } else {
        $detail["pelanggan_detail"] = $this->customer->getById($detail["idpel"]);
        unset($detail["idpel"]);
      }

      $detail["detail"] = $this->selling->readDetailById($this->uri->segment(3));

      $message = array(
        "status"        => TRUE,
        "message"       => "Found Detail From " . $detail["kode_penjualan"],
        "data"          => $detail
      );
      $this->response($message, REST_Controller::HTTP_OK);
    } else {
      $message = array(
        "status"        => FALSE,
        "message"       => "ID Transaction not found!",
        "data"          => null
      );

      $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  public function sales_delete()
  {
    if (!empty($this->delete("iduser"))) {
      if (!empty($this->uri->segment(3))) {
        $query = $this->selling->delete($this->uri->segment(3), $this->delete("iduser"));

        if ($query > 0) {
          $message = array(
            "status"        => TRUE,
            "message"       => "Delete Success!",
            "data"          => null
          );

          $this->response($message, REST_Controller::HTTP_OK);
        } else {
          $message = array(
            "status"        => FALSE,
            "message"       => "Delete failed!",
            "data"          => null
          );

          $this->response($message, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
      } else {
        $message = array(
          "status"        => FALSE,
          "message"       => "Transaction ID Unknown!",
          "data"          => null
        );

        $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
      }
    } else {
      $message = array(
        "status"        => FALSE,
        "message"       => "Not Authorized!",
        "data"          => null
      );

      $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
    }
  }

  public function stocks()
  {
    if (!empty($this->get("iduser"))) {
      if (empty($this->get("from")) && empty($this->get("to"))) {
        if (empty($this->get("page"))) {
          $query = $this->stock->readThisMonth($this->get("iduser"));

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
        } else {
          $query = $this->stock->readThisMonthLimit($this->get("iduser"), ($this->get("page") * 20) - 20);

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
      } else if (!empty($this->get("from")) && !empty($this->get("to"))) {
        $to = $this->get("to");
        $from = $this->get("from");
        if (empty($this->get("page"))) {
          $query = $this->stock->readFromDate($from, $to, $this->get("iduser"));

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
        } else {
          $query = $this->stock->readFromDateLimit($from, $to, $this->get("iduser"), ($this->get("page") * 20) - 20);

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
      } else if (empty($this->get("from")) || empty($this->get("to"))) {
        $message = array(
          "status"        => FALSE,
          "message"       => "Failed to get data by date given!",
          "data"          => null
        );

        $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
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

  public function inventories()
  {
  }
}

/* End of file Report.php */
