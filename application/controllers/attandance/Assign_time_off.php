<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Assign_Time_Off extends CI_Controller
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
            $this->load->view('attandance/assign_time_off');
        } else {
            redirect('error_access');
        }
    }

    //GET DATATABLES
    public function datatables()
    {
        if ($this->input->post()) {
            $filter_from = $this->input->get('filter_from');
            $filter_to = $this->input->get('filter_to');
            $filter_permit_type = $this->input->get('filter_permit_type');
            $filter_reason = $this->input->get('filter_reason');
            $result = array();

            $page   = $this->input->post('page');
            $rows   = $this->input->post('rows');
            //Pagination 1-10
            $page   = isset($page) ? intval($page) : 1;
            $rows   = isset($rows) ? intval($rows) : 10;
            $offset = ($page - 1) * $rows;

            //Employee
            $this->db->select("a.*, b.name as permit_name, c.name as reason_name");
            $this->db->from('assign_time_off_master a');
            $this->db->join('permit_types b', 'a.permit_type_id = b.id');
            $this->db->join('reasons c', 'a.reason_id = c.id');
            if ($filter_from != "" && $filter_to != "") {
                $this->db->where("(a.effective_date >= '$filter_from' and a.effective_date <= '$filter_to')");
            }
            $this->db->like('a.permit_type_id', $filter_permit_type);
            $this->db->like('a.reason_id', $filter_reason);
            $this->db->order_by("a.id", "asc");

            //Total Data
            $totalRows = $this->db->count_all_results('', false);
            //Limit 1 - 10
            $this->db->limit($rows, $offset);
            //Get Data Array
            $employees = $this->db->get()->result_array();

            $datas['total'] = $totalRows;
            $datas = array_merge($datas, ['rows' => @$employees]);
            echo json_encode($datas);
        }
    }

    //CREATE DATA
    public function create()
    {
        if ($this->input->post()) {

            $master = $this->input->post();
            $detail_json = $this->input->post('detail_data');

            if (!$master || !$detail_json) {
                echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
                return;
            }

            $detail = json_decode($detail_json, true);

            $data_master = [
                'permit_type_id' => $master['permit_type_id'],
                'reason_id' => $master['reason_id'],
                'effective_date' => $master['effective_date'],
                'description' => $master['description'],
            ];

            $get_master = $this->crud->read('assign_time_off_master', [], [
                'permit_type_id' => $master['permit_type_id'],
                'reason_id' => $master['reason_id'],
                'effective_date' => $master['effective_date']
            ]);
            if ($get_master) {
                echo json_encode(array("title" => "Duplicate", "message" => "Master Data already exists", "theme" => "error"));
            } else {
                $send = $this->crud->create('assign_time_off_master', $data_master);

                $get_master = $this->crud->read('assign_time_off_master', [], [
                    'permit_type_id' => $master['permit_type_id'],
                    'reason_id' => $master['reason_id'],
                    'effective_date' => $master['effective_date']
                ]);
                $master_id = $get_master->id;

                foreach ($detail as $d) {
                    $data_detail = [
                        'assign_time_off_id' => $master_id,
                        'employee_id' => $d['employee_id'],
                        'type_balance' => $d['type_balance'],
                        'balance' => $d['balance'],
                        'start_date' => $d['start_date'],
                        'end_date' => $d['end_date']
                    ];
                    $this->crud->create('assign_time_off_detail', $data_detail);
                }
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

            $master = $this->input->post();
            $detail_json = $this->input->post('detail_data');
            $delete_json = $this->input->post('deleted_ids');

            $data_master = [
                'permit_type_id' => $master['permit_type_id'],
                'reason_id' => $master['reason_id'],
                'effective_date' => $master['effective_date'],
                'description' => $master['description'],
            ];

            $this->db->select("a.id");
            $this->db->from('assign_time_off_master a');
            $this->db->like('a.permit_type_id', $master['permit_type_id']);
            $this->db->like('a.reason_id', $master['reason_id']);
            $this->db->like('a.effective_date', $master['effective_date']);
            $this->db->where('a.id !=', $id);
            $get_master = $this->db->get()->row();
            if ($get_master) {
                echo json_encode(array(
                    "title" => "Duplicate",
                    "message" => "Duplicate master data found. Please update the existing one.",
                    "theme" => "error"
                ));
            } else {
                $send = $this->crud->update('assign_time_off_master', ["id" => $id], $data_master);
                $detail = json_decode($detail_json, true);
                $deleted_ids = json_decode($delete_json, true);

                foreach ($deleted_ids as $id_detail) {
                    $delete = $this->crud->delete("assign_time_off_detail", ['id' => $id_detail]);
                }

                foreach ($detail as $d) {
                    $data_detail = [
                        'assign_time_off_id' => $id,
                        'employee_id' => $d['employee_id'],
                        'type_balance' => $d['type_balance'],
                        'balance' => $d['balance'],
                        'start_date' => $d['start_date'],
                        'end_date' => $d['end_date']
                    ];

                    if ($d["id"] != "") {
                        $this->crud->update('assign_time_off_detail', ['id' => $d['id']], $data_detail);
                    } else {
                        $this->crud->create('assign_time_off_detail', $data_detail);
                    }
                }

                echo $send;
            }
        } else {
            show_error("Cannot Process your request");
        }
    }

    //DELETE DATA
    public function delete()
    {
        $data = $this->input->post();
        $delete_detail = $this->crud->delete("assign_time_off_detail", ['assign_time_off_id' => $data['id']]);
        $delete = $this->crud->delete("assign_time_off_master", ['id' => $data['id']]);
        echo $delete;
    }

    //UPLOAD DATA
    public function upload()
    {
        error_reporting(0);

        if ($this->input->post('file_type') == "excel") {
            require_once 'assets/vendors/excel_reader2.php';
            $target = basename($_FILES['file_upload']['name']);
            move_uploaded_file($_FILES['file_upload']['tmp_name'], $target);
            chmod($_FILES['file_upload']['name'], 0777);
            $file = $_FILES['file_upload']['name'];
            $data = new Spreadsheet_Excel_Reader($file, false);
            $total_row = $data->rowcount($sheet_index = 0);

            $data_master = [
                'permit_type_id' => $this->input->post('upermit_type_id'),
                'reason_id' => $this->input->post('ureason_id'),
                'effective_date' => $this->input->post('ueffective_date'),
                'description' => $this->input->post('udescription'),
            ];

            $master_id = $this->crud->autoid('assign_time_off_master');
            for ($i = 2; $i <= $total_row; $i++) {
                if (!empty($employee->number)) {
                    $employee_id = trim($data->val($i, 2));

                    $this->db->select('*');
                    $this->db->from('employees');
                    $this->db->where('number', $employee_id);
                    $employee = $this->db->get()->row();

                    if (!empty($employee->number)) {
                        $datas[] = array(
                            'employee_id' => trim($data->val($i, 2)),
                            'balance' => $data->val($i, 4),
                            'start_date' => $data->val($i, 5),
                            'end_date' => $data->val($i, 6),
                            'assign_time_off_id' => $master_id,
                        );
                    }
                }
            }

            if ($datas) {
                $this->crud->create('assign_time_off_master', $data_master);
            }

            $datas['total'] = count($datas);
            echo json_encode($datas);

            unlink($_FILES['file_upload']['name']);
            //$this->db->truncate('attandance_temp');
        } else {
            echo json_encode("Format File Error");
        }
    }

    public function uploadclearFailed()
    {
        @unlink('failed/attandances.txt');
    }

    public function uploadcreateFailed()
    {
        if ($this->input->post()) {
            $message = $this->input->post('message');
            $textFailed = fopen('failed/attandances.txt', 'a');
            fwrite($textFailed, $message . "\n");
            fclose($textFailed);
        }
    }

    public function uploadDownloadFailed()
    {
        $file = "failed/attandances.txt";

        header('Content-Description: File Failed');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . @filesize($file));
        header("Content-Type: text/plain");
        @readfile($file);
    }

    public function uploadcreate()
    {
        if ($this->input->post()) {
            $data = $this->input->post('data');

            $data_update = [
                'employee_id' => $data['employee_id'],
                'balance' => $data['balance'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'assign_time_off_id' => $data['assign_time_off_id'],
                'status' => "UPLOAD",
            ];

            $this->crud->create('assign_time_off_detail', $data_update);

            echo json_encode(array("title" => "Created", "message" => $data['employee_id'] . " Create data Assign Time Off", "theme" => "success"));
        }
    }

    //PRINT & EXCEL DATA
    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=employee_$format.xls");
        }

        $filter_from = $this->input->get('filter_from');
        $filter_to = $this->input->get('filter_to');
        $filter_permit_type = $this->input->get('filter_permit_type');
        $filter_reason = $this->input->get('filter_reason');

        $this->db->select("a.id, a.effective_date, a.description, b.name as permit_name, c.name as reason_name, d.employee_id as employee_number, e.name as employee_name, d.balance, d.start_date, d.end_date");
        $this->db->from('assign_time_off_master a');
        $this->db->join('permit_types b', 'a.permit_type_id = b.id');
        $this->db->join('reasons c', 'a.reason_id = c.id');
        $this->db->join('assign_time_off_detail d', 'a.id = d.assign_time_off_id');
        $this->db->join('employees e', 'd.employee_id = e.number');
        if ($filter_from != "" && $filter_to != "") {
            $this->db->where("(a.effective_date >= '$filter_from' and a.effective_date <= '$filter_to')");
        }
        $this->db->like('a.permit_type_id', $filter_permit_type);
        $this->db->like('a.reason_id', $filter_reason);
        $this->db->order_by("a.id", "asc");
        $this->db->order_by("e.name", "asc");

        $records = $this->db->get()->result_array();

        $html = '<html><head><title>Print Data</title></head><style>body {font-family: Arial, Helvetica, sans-serif;}#customers {border-collapse: collapse;width: 100%;font-size: 12px;}#customers td, #customers th {border: 1px solid #ddd;padding: 2px;}#customers tr:nth-child(even){background-color: #f2f2f2;}#customers tr:hover {background-color: #ddd;}#customers th {padding-top: 2px;padding-bottom: 2px;text-align: left;color: black;}</style><body>
        <style> .str{ mso-number-format:\@; } </style>
        <center>
            <div style="float: left; font-size: 12px; text-align: left;">
                Human Resource Information System
            </div>
            <div style="float: right; font-size: 12px; text-align: right;">
                Print Date ' . date("d M Y H:i:s") . ' <br>
                Print By ' . $this->session->username . '  
            </div>
        </center><br>
        <center>
            <h3>Data Master Assign Time Off</h3>
        </center>
        
        <table id="assign_time_off" border="1">
            <tr>
                <th width="20">No</th>
                <th>ID</th>
                <th>Permit Type</th>
                <th>Reason Name</th>
                <th>Effective Date</th>
                <th>Description</th>
                <th>ID Employee</th>
                <th>Full Name</th>
                <th>Balance</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>';
        $no = 1;
        foreach ($records as $data) {
            if ($data['status'] == "1") {
                $style = "style='background:#FFDCDC;'";
            } else {
                $style = "";
            }

            $html .= '<tr ' . $style . '>
                    <td>' . $no . '</td>
                    <td class="str">' . $data['id'] . '</td>
                    <td>' . $data['permit_name'] . '</td>
                    <td>' . $data['reason_name'] . '</td>
                    <td>' . $data['effective_date'] . '</td>
                    <td>' . $data['description'] . '</td>
                    <td>' . $data['employee_number'] . '</td>
                    <td>' . $data['employee_name'] . '</td>
                    <td>' . $data['balance'] . '</td>
                    <td>' . $data['start_date'] . '</td>
                    <td>' . $data['end_date'] . '</td>
                    </tr>';
            $no++;
        }

        $html .= '</table></body></html>';
        echo $html;
    }

    public function getDetail($id)
    {

        $detail = $this->db->get_where('assign_time_off_detail', ['assign_time_off_id' => $id])->result_array();

        if ($detail) {
            echo json_encode([
                'success' => true,
                'detail' => $detail
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function datatableDetails($id)
    {

        $page   = $this->input->post('page');
        $rows   = $this->input->post('rows');
        //Pagination 1-10
        $page   = isset($page) ? intval($page) : 1;
        $rows   = isset($rows) ? intval($rows) : 10;
        $offset = ($page - 1) * $rows;

        //Employee
        $this->db->select("a.*, b.name as employee_name");
        $this->db->from('assign_time_off_detail a');
        $this->db->join('employees b', 'a.employee_id = b.number');
        $this->db->where('a.assign_time_off_id', $id);
        $this->db->order_by("b.name", "asc");

        //Total Data
        $totalRows = $this->db->count_all_results('', false);
        //Limit 1 - 10
        $this->db->limit($rows, $offset);
        //Get Data Array
        $employees = $this->db->get()->result_array();

        // die(json_encode($employees));

        $datas['total'] = $totalRows;
        $datas = array_merge($datas, ['rows' => @$employees]);
        echo json_encode($datas);
    }

    public function datatablesEmployee()
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
            $this->db->select('*');
            $this->db->from('employees');
            $this->db->where('deleted', 0);
            $this->db->where('status', 0);
            if (@count($filters) > 0) {
                foreach ($filters as $filter) {
                    $this->db->like($filter->field, $filter->value);
                }
            }
            $this->db->order_by('name', 'ASC');
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
}
