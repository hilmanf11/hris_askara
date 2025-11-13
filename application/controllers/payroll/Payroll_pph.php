<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_pph extends CI_Controller
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
            $this->load->view('payroll/payroll_pph');
        } else {
            redirect('error_access');
        }
    }


    //GET DATATABLES
    public function datatables()
    {
        if ($this->input->post()) {

            $month_start = (int) $this->input->post('month_start');
            $month_end   = (int) $this->input->post('month_end');
            $year        = (int) $this->input->post('year');
            $filter_division = $this->input->post('filter_division');
            $filter_departement = $this->input->post('filter_departement');
            $filter_departement_sub = $this->input->post('filter_departement_sub');
            $filter_employee = $this->input->post('filter_employee');

            if (!$month_start) $month_start = 1;
            if (!$month_end)   $month_end   = 12;
            if (!$year)        $year        = date('Y');

            // karena kolom varchar 'YYYY-MM', kita juga buat string 'YYYY-MM'
            $period_start = sprintf('%04d-%02d', $year, $month_start); // contoh: 2025-01
            $period_end   = sprintf('%04d-%02d', $year, $month_end);   // contoh: 2025-05

            $page   = (int) $this->input->post('page') ?: 1;
            $rows   = (int) $this->input->post('rows') ?: 10;
            $offset = ($page - 1) * $rows;

            $this->db->start_cache();

            $this->db->select("
                a.employee_id,
                c.name AS division_name,
                d.name AS departement_name,
                e.name AS departement_sub_name,
                b.number AS employee_number,
                b.name   AS employee_name,
                b.date_sign,

                SUM(IF(SUBSTRING(a.period_start,6,2)='01', a.pph, 0)) AS jan,
                SUM(IF(SUBSTRING(a.period_start,6,2)='02', a.pph, 0)) AS feb,
                SUM(IF(SUBSTRING(a.period_start,6,2)='03', a.pph, 0)) AS mar,
                SUM(IF(SUBSTRING(a.period_start,6,2)='04', a.pph, 0)) AS apr,
                SUM(IF(SUBSTRING(a.period_start,6,2)='05', a.pph, 0)) AS may,
                SUM(IF(SUBSTRING(a.period_start,6,2)='06', a.pph, 0)) AS jun,
                SUM(IF(SUBSTRING(a.period_start,6,2)='07', a.pph, 0)) AS jul,
                SUM(IF(SUBSTRING(a.period_start,6,2)='08', a.pph, 0)) AS aug,
                SUM(IF(SUBSTRING(a.period_start,6,2)='09', a.pph, 0)) AS sep,
                SUM(IF(SUBSTRING(a.period_start,6,2)='10', a.pph, 0)) AS oct,
                SUM(IF(SUBSTRING(a.period_start,6,2)='11', a.pph, 0)) AS nov,
                SUM(IF(SUBSTRING(a.period_start,6,2)='12', a.pph, 0)) AS `dec`
            ");

            $this->db->from('payrolls_pph a');
            $this->db->join('employees b', 'a.employee_id = b.id');
            $this->db->join('divisions c', 'b.division_id = c.id');
            $this->db->join('departements d', 'b.departement_id = d.id');
            $this->db->join('departement_subs e', 'b.departement_sub_id = e.id');

            $this->db->where('b.status', 0);

            $this->db->where('a.period_start >=', $period_start);
            $this->db->where('a.period_start <=', $period_end);
            $this->db->like('b.division_id', $filter_division);
            $this->db->like('b.departement_id', $filter_departement);
            $this->db->like('b.departement_sub_id', $filter_departement_sub);
            $this->db->like('b.id', $filter_employee);

            $this->db->group_by('a.employee_id');

            $this->db->stop_cache();

            $totalRows = $this->db->count_all_results();
            $this->db->order_by('b.name', 'ASC');

            $this->db->limit($rows, $offset);
            $records = $this->db->get()->result_array();
           
            $this->db->flush_cache();


            $result['total'] = $totalRows;
            $result['rows']  = $records;

            echo json_encode($result);
        }
    }


    //CREATE DATA
    public function create()
    {
        if ($this->input->post()) {
            $post = $this->input->post();
            $period_start = date("Y-m", strtotime($post['period_start']));
            $period_end = date("Y-m", strtotime($post['period_end']));
            $post['period_start'] = $period_start;
            $post['period_end'] = $period_end;

            $payroll_pph = $this->crud->reads('payrolls_pph', [], ["employee_id" => $post['employee_id'], "period_start" => $post['period_start'], "period_end" => $post['period_end']]);
            if (count($payroll_pph) > 0) {
                echo json_encode(array("title" => "Duplicate", "message" => "PPH21 has been created", "theme" => "error"));
            } else {
                $send = $this->crud->create('payrolls_pph', $post);
                echo $send;
            }
        } else {
            show_error("Cannot Process your request");
        }
    }

    //UPLOAD DATA
    public function upload()
    {
        error_reporting(0);
        require_once 'assets/vendors/excel_reader2.php';
        $target = basename($_FILES['file_upload']['name']);
        move_uploaded_file($_FILES['file_upload']['tmp_name'], $target);
        chmod($_FILES['file_upload']['name'], 0777);
        $file = $_FILES['file_upload']['name'];
        $data = new Spreadsheet_Excel_Reader($file, false);
        $total_row = $data->rowcount($sheet_index = 0);

        for ($i = 3; $i <= $total_row; $i++) {
            $datas[] = array(
                'employee_number' => $data->val($i, 2),
                'period_start' => $data->val($i, 3),
                'period_end' => $data->val($i, 4),
                'pph' => $data->val($i, 5),
            );
        }

        $datas['total'] = count($datas);
        echo json_encode($datas);
        unlink($_FILES['file_upload']['name']);
    }

    public function uploadclearFailed()
    {
        @unlink('failed/payroll_pph.txt');
    }

    public function uploadcreateFailed()
    {
        if ($this->input->post()) {
            $message = $this->input->post('message');
            $textFailed = fopen('failed/payroll_pph.txt', 'a');
            fwrite($textFailed, $message . "\n");
            fclose($textFailed);
        }
    }

    public function uploadDownloadFailed()
    {
        $file = "failed/payroll_pph.txt";

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
            $employee = $this->crud->read('employees', [], ["number" => $data['employee_number']]);

            if (!empty($employee)) {
                $payrolls_pph = $this->crud->read('payrolls_pph', [], ["employee_id" => $employee->id, "period_start" => $data['period_start'], "period_end" => $data['period_end']]);

                if (!empty($payrolls_pph)) {
                    echo json_encode(array("title" => "Available", "message" => $employee->name . ". PPH21 has been created", "theme" => "error"));
                } else {
                    $post_payroll_pph = array(
                        'employee_id' => $employee->id,
                        'period_start' => $data['period_start'],
                        'period_end' => $data['period_end'],
                        'pph' => $data['pph'],
                    );

                    $send = $this->crud->create('payrolls_pph', $post_payroll_pph);
                    echo $send;
                }
            } else {
                echo json_encode(array("title" => "Not Found", "message" => $data['number'] . " Employee ID Not Found", "theme" => "error"));
            }
        }
    }

    //PRINT & EXCEL DATA
    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=PPH21_$format.xls");
        }

        $month_start = (int) $this->input->get('month_start');
        $month_end   = (int) $this->input->get('month_end');
        $year        = (int) $this->input->get('year');
        $filter_division = $this->input->get('filter_division');
        $filter_departement = $this->input->get('filter_departement');
        $filter_departement_sub = $this->input->get('filter_departement_sub');
        $filter_employee = $this->input->get('filter_employee');

        if (!$month_start) $month_start = 1;
        if (!$month_end)   $month_end   = 12;
        if (!$year)        $year        = date('Y');

        $period_start = sprintf('%04d-%02d', $year, $month_start); // contoh: 2025-01
        $period_end   = sprintf('%04d-%02d', $year, $month_end);  

        //Config
        $this->db->select('*');
        $this->db->from('config');
        $config = $this->db->get()->row();

        $monthLabels = [
            1  => 'Jan',
            2  => 'Feb',
            3  => 'Mar',
            4  => 'Apr',
            5  => 'May',
            6  => 'Jun',
            7  => 'Jul',
            8  => 'Aug',
            9  => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec',
        ];

        $monthFields = [
            1  => 'jan',
            2  => 'feb',
            3  => 'mar',
            4  => 'apr',
            5  => 'may',
            6  => 'jun',
            7  => 'jul',
            8  => 'aug',
            9  => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dec',
        ];

        $this->db->start_cache();

        $this->db->select("
            a.employee_id,
            c.name AS division_name,
            d.name AS departement_name,
            e.name AS departement_sub_name,
            b.number AS employee_number,
            b.name   AS employee_name,
            b.date_sign,

            SUM(IF(SUBSTRING(a.period_start,6,2)='01', a.pph, 0)) AS jan,
            SUM(IF(SUBSTRING(a.period_start,6,2)='02', a.pph, 0)) AS feb,
            SUM(IF(SUBSTRING(a.period_start,6,2)='03', a.pph, 0)) AS mar,
            SUM(IF(SUBSTRING(a.period_start,6,2)='04', a.pph, 0)) AS apr,
            SUM(IF(SUBSTRING(a.period_start,6,2)='05', a.pph, 0)) AS may,
            SUM(IF(SUBSTRING(a.period_start,6,2)='06', a.pph, 0)) AS jun,
            SUM(IF(SUBSTRING(a.period_start,6,2)='07', a.pph, 0)) AS jul,
            SUM(IF(SUBSTRING(a.period_start,6,2)='08', a.pph, 0)) AS aug,
            SUM(IF(SUBSTRING(a.period_start,6,2)='09', a.pph, 0)) AS sep,
            SUM(IF(SUBSTRING(a.period_start,6,2)='10', a.pph, 0)) AS oct,
            SUM(IF(SUBSTRING(a.period_start,6,2)='11', a.pph, 0)) AS nov,
            SUM(IF(SUBSTRING(a.period_start,6,2)='12', a.pph, 0)) AS `dec`
        ");

        $this->db->from('payrolls_pph a');
        $this->db->join('employees b', 'a.employee_id = b.id');
        $this->db->join('divisions c', 'b.division_id = c.id');
        $this->db->join('departements d', 'b.departement_id = d.id');
        $this->db->join('departement_subs e', 'b.departement_sub_id = e.id');

        $this->db->where('b.status', 0);

        $this->db->where('a.period_start >=', $period_start);
        $this->db->where('a.period_start <=', $period_end);
        $this->db->like('b.division_id', $filter_division);
        $this->db->like('b.departement_id', $filter_departement);
        $this->db->like('b.departement_sub_id', $filter_departement_sub);
        $this->db->like('b.id', $filter_employee);

        $this->db->group_by('a.employee_id');
        $this->db->stop_cache();
        $this->db->order_by('b.name', 'ASC');

        $records = $this->db->get()->result_array();
        
        $this->db->flush_cache();

        $monthColspan = $month_end - $month_start + 1;

        $html = '<html><head><title>Print Data</title></head><style>body {font-family: Arial, Helvetica, sans-serif;}#customers {border-collapse: collapse;width: 100%;font-size: 12px;}#customers td, #customers th {border: 1px solid #ddd;padding: 2px;}#customers tr:nth-child(even){background-color: #f2f2f2;}#customers tr:hover {background-color: #ddd;}#customers th {padding-top: 2px;padding-bottom: 2px;text-align: left;color: black;}</style><body>
        <style> .str{ mso-number-format:\@; } </style>
        <center>
            <div style="float: left; font-size: 12px; text-align: left;">
                <table style="width: 100%;">
                    <tr>
                        <td width="50" style="font-size: 12px; vertical-align: top; text-align: center; vertical-align:jus margin-right:10px;">
                            <img src="' . $config->favicon . '" width="30">
                        </td>
                        <td style="font-size: 14px; text-align: left; margin:2px;">
                            <b>' . $config->name . '</b><br>
                            <small>DATA PPH 21</small>
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
                <th rowspan="2" style="text-align:center;" width="20">No</th>
                <th rowspan="2" style="text-align:center;">Division</th>
                <th rowspan="2" style="text-align:center;">Departement</th>
                <th rowspan="2" style="text-align:center;">Departement Sub</th>
                <th rowspan="2" style="text-align:center;">Employee</th>
                <th rowspan="2" style="text-align:center;">Join Date</th>
                <th colspan="' . $monthColspan . '" style="text-align:center;">' . $year . '</th>';
        $html .= '</tr>';
        $html .= '<tr>';
            for ($m = $month_start; $m <= $month_end; $m++) {
                $label = $monthLabels[$m];
                $html .= '<th style="text-align:center;">' . $label . '</th>';
            }

        $html .= '</tr>';
        $no = 1;
        foreach ($records as $data) {
            $html .= '  <tr>
                            <td>' . $no . '</td>
                            <td>' . $data['division_name'] . '</td>
                            <td class="str">' . $data['departement_name'] . '</td>
                            <td>' . $data['departement_sub_name'] . '</td>
                            <td>' . $data['employee_name'] . '</td>
                            <td>' . $data['date_sign'] . '</td>';
            for ($m = $month_start; $m <= $month_end; $m++) {
                $field = $monthFields[$m];
                $value = isset($data[$field]) ? $data[$field] : 0;
                $html .= '<td style="text-align:right;">' . round($value, 2) . '</td>';
            }
            $html .= '</tr>';
            $no++;
        }

        $html .= '</table></body></html>';
        echo $html;
    }
}
