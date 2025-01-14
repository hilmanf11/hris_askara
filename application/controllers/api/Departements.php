<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Departements extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');
    $this->load->library('session');
    $this->load->model('crud');
  }

  //HALAMAN UTAMA
  public function index()
  {
    show_error("Cannot Process your request");
  }

  public function readFulls()
  {
    $this->db->select('a.number as code, b.name as division_name, a.name, a.description');
    $this->db->from('departements a');
    $this->db->join('divisions b', 'a.division_id = b.id');
    $this->db->where('a.deleted', 0);
    $this->db->order_by('b.name', 'ASC');
    $this->db->order_by('a.number', 'ASC');
    $departements = $this->db->get()->result_array();

    die(json_encode(array(
      'title' => 'Success',
      'message' => 'Data Found',
      'theme' => 'success',
      'results' => $departements
    )));
  }
}
