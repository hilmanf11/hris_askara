<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_bank extends CI_Controller
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
        if (empty($this->session->username)) {
            redirect('error_session');
        } elseif ($this->checkuserAccess($this->id_menu()) > 0) {
            $data['button'] = $this->getbutton($this->id_menu());

            $this->load->view('template/header', $data);
            $this->load->view('payroll/payroll_bank');
        } else {
            redirect('error_access');
        }
    }

    //GET DATA
    public function reads()
    {
        $post = isset($_POST['q']) ? $_POST['q'] : "";
        $get = $this->input->get();
        $division_id = $get['division_id'];

        $this->db->select('a.*, f.name as division_name, e.name as bank_name');
        $this->db->from('payroll_bank a');
        $this->db->join('divisions f', 'a.division_id = f.id', 'left');
        $this->db->join('banks e', 'a.bank_id = e.id');
        $this->db->group_start();
            $this->db->like('a.account_no', $post);
            $this->db->or_like('f.name', $post);
            $this->db->or_like('e.name', $post);
        $this->db->group_end();
        if ($division_id) {
            $this->db->where('a.division_id', $division_id);
        }
        $this->db->order_by('f.name', 'ASC');
        $this->db->order_by('e.name', 'ASC');
        $records = $this->db->get()->result_array();
        echo json_encode($records);


    }

    //GET DATATABLES
    public function datatables()
    {
        if ($this->input->post()) {
            $filters = json_decode($this->input->post('filterRules'));
            $page = $this->input->post('page');
            $rows = $this->input->post('rows');
            //Pagination 1-10
            $page   = isset($page) ? intval($page) : 1;
            $rows   = isset($rows) ? intval($rows) : 10;
            $offset = ($page - 1) * $rows;
            $result = array();
            //Select Query
            $this->db->select('a.*, f.name as division_name, e.name as bank_name');
            $this->db->from('payroll_bank a');
            $this->db->join('divisions f', 'a.division_id = f.id', 'left');
            $this->db->join('banks e', 'a.bank_id = e.id');
            $this->db->where('a.deleted', 0);
            if (@count($filters) > 0) {
                foreach ($filters as $filter) {
                    if($filter->field == "division_name"){
                        $this->db->like("f.name", $filter->value);
                    }else if($filter->field == "bank_name"){
                        $this->db->like("e.name", $filter->value);
                    }else if($filter->field == "account_no"){
                        $this->db->like("a.account_no", $filter->value);
                    }else{
                        $this->db->like("a." . $filter->field, $filter->value);
                    }
                }
            }
            $this->db->order_by('f.name', 'ASC');
            $this->db->order_by('e.name', 'ASC');
            //Total Data
            $totalRows = $this->db->count_all_results('', false);
            //Limit 1 - 10
            $this->db->limit($rows, $offset);
            //Get Data Array
            $records = $this->db->get()->result_array();
            //Mapping Data
            $result['total'] = $totalRows;
            $result = array_merge($result, ['rows' => $records]);
            echo json_encode($result);
        }
    }

    //CREATE DATA
    public function create()
    {
        if ($this->input->post()) {
          $post   = $this->input->post();

          $payroll_bank = $this->crud->reads("payroll_bank", [], [
              "division_id" => $post['division_id'],
              "bank_id" => $post['bank_id'],
              "account_no" => $post['account_no'],
          ]);

          if(count($payroll_bank) > 0){
              show_error("Duplicate Data", 200);
          }else{
              $send   = $this->crud->create('payroll_bank', $post);
              echo $send;
          }
            
        } else {
            show_error("Cannot Process your request");
        }
    }

    //UPDATE DATA
    public function update()
    {
        if ($this->input->post()) {
            $id   = base64_decode($this->input->get('id'));
            $post = $this->input->post();

            $send = $this->crud->update('payroll_bank', ["id" => $id], $post);
            echo $send;
        } else {
            show_error("Cannot Process your request");
        }
    }

    //DELETE DATA
    public function delete()
    {
        $data = $this->input->post();
        $send = $this->crud->delete('payroll_bank', $data);
        echo $send;
    }

    //PRINT & EXCEL DATA
    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=payroll_bank_$format.xls");
        }

        //Config
        $this->db->select('*');
        $this->db->from('config');
        $config = $this->db->get()->row();

        $this->db->select('a.*, f.name as division_name, e.name as bank_name');
        $this->db->from('payroll_bank a');
        $this->db->join('divisions f', 'a.division_id = f.id', 'left');
        $this->db->join('banks e', 'a.bank_id = e.id');
        $this->db->where('a.deleted', 0);
        $this->db->where('a.deleted', 0);
        $this->db->order_by('f.name', 'ASC');
        $this->db->order_by('e.name', 'ASC');
        $records = $this->db->get()->result_array();

        $html = '<html><head><title>Print Data</title></head><style>body {font-family: Arial, Helvetica, sans-serif;}#customers {border-collapse: collapse;width: 100%;font-size: 12px;}#customers td, #customers th {border: 1px solid #ddd;padding: 2px;}#customers tr:nth-child(even){background-color: #f2f2f2;}#customers tr:hover {background-color: #ddd;}#customers th {padding-top: 2px;padding-bottom: 2px;text-align: left;color: black;}</style><body>
        <center>
            <div style="float: left; font-size: 12px; text-align: left;">
                <table style="width: 100%;">
                    <tr>
                        <td width="50" style="font-size: 12px; vertical-align: top; text-align: center; vertical-align:jus margin-right:10px;">
                            <img src="' . $config->favicon . '" width="30">
                        </td>
                        <td style="font-size: 14px; text-align: left; margin:2px;">
                            <b>' . $config->name . '</b><br>
                            <small>PAYROLL ACCOUNT</small>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="float: right; font-size: 12px; text-align: right;">
                Print Date ' . date("d M Y H:m:s") . ' <br>
                Print By ' . $this->session->username . '  
            </div>
        </center>
        <br><br><br>
        
        <table id="customers" border="1">
            <tr>
                <th width="20">No</th>
                <th>Division</th>
                <th>Bank</th>
                <th>Account No</th>
            </tr>';
        $no = 1;
        foreach ($records as $data) {
            $html .= '  <tr>
                            <td>' . $no . '</td>
                            <td>' . $data['division_name'] . '</td>
                            <td>' . $data['bank_name'] . '</td>
                            <td>' . $data['account_no'] . '</td>
                        </tr>';
            $no++;
        }

        $html .= '</table></body></html>';
        echo $html;
    }
}
