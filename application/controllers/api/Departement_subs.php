<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Departement_subs extends CI_Controller
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
    $this->db->select('a.number as code, b.name as division_name, c.name as departement_name, a.name, a.type as job_type');
    $this->db->from('departement_subs a');
    $this->db->join('divisions b', 'a.division_id = b.id');
    $this->db->join('departements c', 'a.departement_id = c.id');
    $this->db->where('a.deleted', 0);
    $this->db->order_by('b.name', 'ASC');
    $this->db->order_by('c.name', 'ASC');
    $this->db->order_by('a.number', 'ASC');

    $departement_subs = $this->db->get()->result_array();
    die(json_encode(array(
      'title' => 'Success',
      'message' => 'Data Found',
      'theme' => 'success',
      'results' => $departement_subs
    )));
  }
}
