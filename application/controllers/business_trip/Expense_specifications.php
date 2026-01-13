<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Expense_specifications extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('crud');
        $this->load->model('accessmodule');

        //VALIDASI FORM
        $this->form_validation->set_rules('number', 'Code', 'required|min_length[1]|max_length[20]|is_unique[expense_specifications.number]');
    }

    //HALAMAN UTAMA
    public function index()
    {
        if (empty($this->session->username)) {
            redirect('error_session');
        } elseif ($this->checkuserAccess($this->id_menu()) > 0) {
            $data['button'] = $this->getbutton($this->id_menu());

            $this->load->view('template/header', $data);
            $this->load->view('business_trip/expense_specifications');
        } else {
            redirect('error_access');
        }
    }

    //GET DATA
    public function reads()
    {
        $post = isset($_POST['q']) ? $_POST['q'] : "";
        $division_id = $this->input->get('division_id') ? $this->input->get('division_id') : "";

        $this->db->select('a.*');
        $this->db->from('expense_specifications a');

        $this->db->order_by('a.start_date', 'asc');
        $send = $this->db->get()->result_array();
        echo json_encode($send);
    }

    //GET DATATABLES
    public function datatables()
    {
        $page    = (int) $this->input->post('page', true);
        $rows    = (int) $this->input->post('rows', true);
        $filters = json_decode($this->input->post('filterRules'));

        $page   = $page > 0 ? $page : 1;
        $rows   = $rows > 0 ? $rows : 10;
        $offset = ($page - 1) * $rows;

        $this->db->select("
            a.*,

            c.name AS category_name,
            ar.name AS area_name,

        ");

        $this->db->from('expense_specifications a');
        $this->db->join('expense_categories c', 'c.id = a.expense_category_id', 'left');
        $this->db->join('areas ar', 'ar.id = a.area_id', 'left');

        $this->db->where('a.deleted', 0);

        if (!empty($filters)) {
            foreach ($filters as $filter) {
                if (!empty($filter->value)) {
                    switch ($filter->field) {
                        case 'category_name':
                            $this->db->like('c.name', $filter->value);
                            break;

                        case 'area_name':
                            $this->db->like('ar.name', $filter->value);
                            break;

                        default:
                            $this->db->like('a.' . $filter->field, $filter->value);
                            break;
                    }
                }
            }
        }

        $this->db->order_by('a.start_date', 'DESC');
        $this->db->order_by('c.name', 'ASC');
        $this->db->order_by('ar.name', 'ASC');

        $totalRows = $this->db->count_all_results('', false);

        $this->db->limit($rows, $offset);

        $records = $this->db->get()->result_array();

        echo json_encode([
            'total' => $totalRows,
            'rows'  => $records
        ]);
    }


    //CREATE DATA
    public function create()
    {
        if ($this->input->post()) {
            if ($this->form_validation->run() == TRUE) {
                $post   = $this->input->post();
                $send   = $this->crud->create('expense_specifications', $post);
                echo $send;
            } else {
                show_error(validation_errors());
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
            $send = $this->crud->update('expense_specifications', ["id" => $id], $post);
            echo $send;
        } else {
            show_error("Cannot Process your request");
        }
    }

    //DELETE DATA
    public function delete()
    {
        $data = $this->input->post();
        $send = $this->crud->delete('expense_specifications', $data);
        echo $send;
    }

    //PRINT & EXCEL DATA
    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=expense_specifications_$format.xls");
        }


        //Config
        $this->db->select('*');
        $this->db->from('config');
        $config = $this->db->get()->row();

        $this->db->select("
            a.*,

            c.name AS category_name,
            l.name AS area_name,

        ");

        $this->db->from('expense_specifications a');
        $this->db->join('expense_categories c', 'c.id = a.expense_category_id', 'left');
        $this->db->join('areas l', 'l.id = a.area_id', 'left');
        $this->db->where('a.deleted', 0);
        $this->db->order_by('a.start_date', 'DESC');
        $this->db->order_by('c.name', 'ASC');
        $this->db->order_by('l.name', 'ASC');
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
                            <small>MASTER EXPENSE SPECIFICATIONS</small>
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
                <th>Code</th>
                <th>Start Date</th>
                <th>Valid Date</th>
                <th>Name Category</th>
                <th>Lokasi</th>
                <th>Director</th>
                <th>Asst. Manager</th>
                <th>Manager</th>
                <th>Leader</th>
                <th>Staff</th>
                <th>Operator</th>
                <th>Description</th>
            </tr>';
        $no = 1;
        foreach ($records as $data) {
            $html .= '<tr>
                    <td>' . $no . '</td>
                    <td>' . $data['number'] . '</td>
                    <td>' . $data['start_date'] . '</td>
                    <td>' . $data['valid_date'] . '</td>
                    <td>' . $data['category_name'] . '</td>
                    <td>' . $data['area_name'] . '</td>
                    <td style="text-align:right;">' . $this->formatAmount($data['director_amount']) . '</td>
                    <td style="text-align:right;">' . $this->formatAmount($data['manager_amount']) . '</td>
                    <td style="text-align:right;">' . $this->formatAmount($data['asst_manager_amount']) . '</td>
                    <td style="text-align:right;">' . $this->formatAmount($data['leader_amount']) . '</td>
                    <td style="text-align:right;">' . $this->formatAmount($data['staff_amount']) . '</td>
                    <td style="text-align:right;">' . $this->formatAmount($data['operator_amount']) . '</td>
                    <td>' . $data['description'] . '</td>';
            $no++;
        }

        $html .= '</table></body></html>';
        echo $html;
    }

    function formatAmount($value)
    {
        if ($value === null || $value === '') {
            return '';
        }
        return number_format((float)$value, 2, '.', ',');
    }

}
