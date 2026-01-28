<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Salary_slips extends CI_Controller
{
    protected $restrict_user;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('crud');
        $this->load->model('emails');
        $this->load->model('accessmodule');

        $this->restrict_user = $this->accessmodule->restrictSelf();
    }

    //HALAMAN UTAMA
    public function index()
    {
        if (empty($this->session->username)) {
            redirect('error_session');
        } elseif ($this->checkuserAccess($this->id_menu()) > 0) {
            $data['button'] = $this->getbutton($this->id_menu());

            $this->load->view('template/header', $data);
            $this->load->view('report/salary_slips');
        } else {
            redirect('error_access');
        }
    }

    public function datatables()
    {
        if ($this->input->get()) {
            $filter_from = $this->input->get('filter_from');
            $filter_to = $this->input->get('filter_to');
            $filter_division = $this->input->get('filter_division');
            $filter_departement = $this->input->get('filter_departement');
            $filter_departement_sub = $this->input->get('filter_departement_sub');
            $filter_employee = $this->input->get('filter_employee');
            $filter_group = $this->input->get('filter_group');
            $filter_is_publish = $this->input->get('filter_is_publish');
            $username = $this->session->username;

            $period_start = date("Y-m", strtotime($filter_from));
            $period_end = date("Y-m", strtotime($filter_to));

            $username = $this->session->username;

            $allowed_groups = $this->db
                ->select('group_id')
                ->from('privilege_groups')
                ->where('username', $username)
                ->where('status', 1)
                ->get()
                ->result_array();

            $allowed_group_ids = array_column($allowed_groups, 'group_id');



            //Select Query
            $this->db->select('
                a.approved,
                a.approved_to,
                a.approved_by,
                a.approved_date,
                b.departement_id,
                b.departement_sub_id,
                b.group_id,
                c.name as departement_name, 
                d.name as departement_sub_name, 
                e.name as group_name, 
                COUNT(b.id) as employee, 
                SUM(a.net_income) as income');
            $this->db->from('payrolls a');
            $this->db->join('employees b', "a.employee_id = b.id");
            $this->db->join('departements c', "b.departement_id = c.id");
            $this->db->join('departement_subs d', "b.departement_sub_id = d.id");
            $this->db->join('groups e', "b.group_id = e.id");
            if ($username !== 'admin' && !empty($allowed_group_ids)) {
                $this->db->join(
                    'privilege_groups f',
                    "b.group_id = f.group_id
                    AND f.username = '$username'
                    AND f.status = 1",
                    'left'
                );
            }
            $this->db->where('a.deleted', 0);
            if ($username !== 'admin' && !empty($allowed_group_ids)) {
                $this->db->group_start();
                    // whitelist group
                    $this->db->where_in('b.group_id', $allowed_group_ids);

                    $this->db->or_where('b.number', $this->session->userdata()['number']);
                $this->db->group_end();
            }
            $this->db->where('a.is_publish', $filter_is_publish);
            $this->db->where('a.period_start =', $period_start);
            $this->db->where('a.period_end =', $period_end);
            if (isset($this->restrict_user)) {
                $this->accessmodule->applyEmployeeRestriction($this->restrict_user, 'b');
            }
            $this->db->like('b.id', $filter_employee);
            $this->db->like('b.division_id', $filter_division);
            $this->db->like('b.departement_id', $filter_departement);
            $this->db->like('b.departement_sub_id', $filter_departement_sub);
            $this->db->like('b.group_id', $filter_group);
            $this->db->group_by(array("c.id", "d.id"));
            $this->db->order_by('c.name', 'ASC');
            $this->db->order_by('SUM(a.net_income)', 'ASC');
            //Get Data Array
            $records = $this->db->get()->result_array();

            echo json_encode($records);
        }
    }

    public function getData()
    {
        if ($this->input->get()) {
            $filter_from = $this->input->get('filter_from');
            $filter_to = $this->input->get('filter_to');
            $filter_division = $this->input->get('filter_division');
            $filter_departement = $this->input->get('filter_departement');
            $filter_departement_sub = $this->input->get('filter_departement_sub');
            $filter_employee = $this->input->get('filter_employee');
            $filter_group = $this->input->get('filter_group');
            $username = $this->session->username;

            $period_start = date("Y-m", strtotime($filter_from));
            $period_end = date("Y-m", strtotime($filter_to));

            $username = $this->session->username;
            if ($username == "admin") {
                $privilege = "";
            } else {
                $privilege = " and e.status = '1'";
            }
            $restrictionSQL = '';
            if (isset($this->restrict_user)) {
                $restrictionSQL = $this->accessmodule->getEmployeeRestrictionSQL($this->restrict_user, 'b');
            }

            $query = $this->db->query("SELECT a.*, b.bank_branch, b.bank_no, b.national_id, b.tax_id, i.name as marital_name, b.email, d.ter as ter_persentase
            FROM payrolls a
            JOIN employees b ON a.employee_id = b.id
            LEFT JOIN privilege_groups e ON b.group_id = e.group_id and e.username = '$username' $privilege
            LEFT JOIN marital_categories d ON a.ter_number = d.number
            LEFT JOIN maritals i ON a.marital = i.number
            WHERE a.period_start = '$period_start'
            $restrictionSQL
            AND a.period_end = '$period_end'
            AND b.division_id LIKE '%$filter_division%'
            AND b.departement_id LIKE '%$filter_departement%'
            AND b.departement_sub_id LIKE '%$filter_departement_sub%'
            AND b.group_id LIKE '%$filter_group%'
            AND a.employee_id LIKE '%$filter_employee%'
            GROUP BY b.id
            ORDER BY a.`name` ASC");
            $records = $query->result_array();

            die(json_encode($records));
        }
    }

    public function sendMail()
    {
        error_reporting(0);
        $filter_from = $this->input->get('filter_from');
        $filter_to = $this->input->get('filter_to');
        $record = $this->input->post('data');

        $total_correction = ($record['amount_plus_correction'] + $record['amount_plus_salary'] + $record['amount_plus_backup'] + $record['amount_plus_cc'] + $record['amount_plus_holiday']);
        //Config
        $this->db->select('*');
        $this->db->from('config');
        $config = $this->db->get()->row();

        $html = '<html>
                <head>
                    <title>Print Data</title>
                </head>
                <style>
                    body {font-family: Arial, Helvetica, sans-serif;}
                    #customers {border-collapse: collapse;width: 100%;font-size: 14px;}
                    #customers th {border: 1px solid black;padding: 5px;}
                    #customers td {padding-left: 5px; padding-right:5px;}
                    #customers tr:hover {background-color: #ddd;}
                    #customers th {padding-top: 2px;padding-bottom: 2px;text-align: left;color: black;}
                </style>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
                <body>';

        //Correction
        //jika dia ada tunjuangan ambil field dan isinya
        $q_correction_plus = $this->db->query("SELECT employee_id, `trans_date`, correction_name, remarks, SUM(amount) as amount FROM corrections WHERE employee_id='$record[employee_id]' and trans_date between '$filter_from' and '$filter_to' and correction_type = 'PLUS' GROUP BY correction_name");
        $r_correction_plus = $q_correction_plus->result_array();

        $r_correction_total = 0;
        $html_correction_plus = "";
        foreach ($r_correction_plus as $r_correction_plus_data) {
            $r_correction_total += $r_correction_plus_data['amount'];
            $html_correction_plus .= '<tr>
                                        <td style="text-align:left;">' . $r_correction_plus_data['remarks'] . '</td>
                                        <td style="text-align:right;"><b>' . number_format($r_correction_plus_data['amount']) . '</b></td>
                                    </tr>';
        }

        //Deduction Amount
        $total_deduction_amount = 0;
        foreach (json_decode($record['deduction_amount'], true) as $deduction_amount => $val_deduction_amount) {
            $total_deduction_amount += $val_deduction_amount;
        }

        //Deduction Number
        $total_deduction_number = 0;
        foreach (json_decode($record['deduction_number'], true) as $deduction_number => $val_deduction_number) {
            if ($val_deduction_number == "") {
                $val_deduction_number = 0;
            }

            $total_deduction_number += $val_deduction_number;
        }

        //Allowance Amount
        //jika dia ada tunjuangan ambil field dan isinya
        // $r_allowance = json_decode($record['allowence'], true);
        // $arr_allowance_amount_total = 0;
        // foreach ($r_allowance as $allowance_data => $allowance_val) {
        //     $arr_allowance_amount_total += $allowance_val;
        // }

        $total_allowence = 0;
        $total_allowence_temp = 0;
        $total_allowence_fix = 0;
        foreach (json_decode($record['allowence'], true) as $allowence => $val_allowence) {
            $allTemp = $this->crud->read("allowances", ["number" => $allowence], ["type" => "TEMPORARY"]);
            $allFix = $this->crud->read("allowances", ["number" => $allowence], ["type" => "FIX"]);
            
            if(!empty($allTemp)){
                $total_allowence_temp += (int) $val_allowence;
            }

            if(!empty($allFix)){
                $total_allowence_fix += (int) $val_allowence;
            }

            $total_allowence += (int)$val_allowence;
        }
        //-------------------------------------------------------------------------------------------------------------------------------

        //Deduction Amount
        //Jika dia ada pemotongan gaji ambil field dan isinya
        $q_deduction = $this->db->query("SELECT b.number, b.name, SUM(a.amount) as amount
            FROM deductions b
            LEFT JOIN setup_deductions a ON a.deduction_id = b.id
            WHERE a.employee_id = '$record[employee_id]'
            GROUP BY b.number, b.name ORDER BY b.name asc");
        $r_deduction = $q_deduction->result_array();

        $arr_deduction_amount_total = 0;
        $html_deduction = "";
        foreach ($r_deduction as $deduction_data) {
            $arr_deduction_amount_total += $deduction_data['amount'];
            $html_deduction .= ' <tr>
                                        <td style="text-align:left;">' . $deduction_data['name'] . '</td>
                                        <td style="text-align:right;"><b>' . number_format($deduction_data['amount']) . '</b></td>
                                    </tr>';
        }
        //-------------------------------------------------------------------------------------------------------------------------------------------------------

        //BPJS Employee
        //Kalo dia mempunyai BPJS
        $bpjsKeys = json_decode($record['bpjs_employee'], true);
        $html_bpjs_emp = "";
        $arr_bpjs_emp_amount_total = 0;
        foreach ($bpjsKeys as $bpjs_employee => $bpjs_value) {
            $arr_bpjs = explode("_", $bpjs_employee);
            $r_bpjs_emp = $this->crud->read('bpjs', ['status' => 0], ["number" => $arr_bpjs[0]]);

            $arr_bpjs_emp_amount_total += !empty($bpjs_value) ? $bpjs_value : 0;
            if (!empty($r_bpjs_emp->employee)) {
                $html_bpjs_emp .= ' <tr>
                                        <td style="text-align:left;">' . $r_bpjs_emp->name . '<i style="font-size:10px;">('.$r_bpjs_emp->employee.'%)</i></td>
                                        <td style="text-align:right;"><b>' . number_format($bpjs_value) . '</b></td>
                                    </tr>';
            }
        }
        //-------------------------------------------------------------------------------------------------------------------------------------------------------

        //BPJS Company
        //Kalo dia mempunyai BPJS
        $bpjsComps = json_decode($record['bpjs_company'], true);
        $html_bpjs_com = "";
        $arr_bpjs_com_amount_total = 0;
        foreach ($bpjsComps as $bpjs_company => $bpjs_com_val) {
            $com_bpjs = explode("_", $bpjs_company);
            $r_bpjs_com = $this->crud->read('bpjs', ['status' => 0], ["number" => $com_bpjs[0]]);

            $arr_bpjs_com_amount_total += !empty($bpjs_com_val) ? $bpjs_com_val : 0;
            if (!empty($r_bpjs_com->company)) {
                $html_bpjs_com.= ' <tr>
                                        <td style="text-align:left;">' . $r_bpjs_com->name . ' <i style="font-size:10px;">('.$r_bpjs_com->company.'%)</i></td>
                                        <td style="text-align:right;"><b>' . number_format($bpjs_com_val) . '</b></td>
                                    </tr>';
            }
        }
        //-------------------------------------------------------------------------------------------------------------------------------------------------------

        $total_income = $record['salary'] + $total_allowence + $record['total_overtime_amount'] + $record['correction_plus'];
        $total_deduction = $record['deduction_absence_amount'] + $total_deduction_amount + $arr_deduction_amount_total + $record['loan_bank'] + $record['loan_cooperative'] + $record['loan_other'] + $record['correction_minus'];

        $html .= '  <center><div class="container" style="border:1px solid black; margin-bottom:20px; padding-top:10px; float:left;">
                <table style="width: 100%;">
                    <tr>
                        <td style="font-size: 16px; text-align: left; margin:2px;">
                            <b>' . $config->name . '</b>
                        </td>
                        <td style="font-size: 16px; text-align: right; margin:2px;">
                            SALARY SLIP
                        </td>
                    </tr>
                </table>
                <hr>
                <div style="float:left; width:100%;">
                    <table style="width:100%; margin-bottom: 5px; font-size: 12px;">
                        <tr>
                            <th style="text-align:left; width: 30%;">Cut Off Period</th>
                            <th style="text-align:left; width: 5%;">:</th>
                            <th style="text-align:left; width: 60%;">' . date("d M Y", strtotime($filter_from)) . ' to ' . date("d M Y", strtotime($filter_to)) . '</th>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Employee ID</th>
                            <th style="text-align:left;">:</th>
                            <th style="text-align:left;">' . $record['number'] . '</th>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Employee Name</th>
                            <th style="text-align:left;">:</th>
                            <th style="text-align:left;">' . $record['name'] . '</th>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Departement</th>
                            <th style="text-align:left;">:</th>
                            <th style="text-align:left;">' . $record['departement_name'] . '</th>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Departement Sub</th>
                            <th style="text-align:left;">:</th>
                            <th style="text-align:left;">' . $record['departement_sub_name'] . '</th>
                        </tr>
                        <tr>
                            <th style="text-align:left; width: 30%;">National ID</th>
                            <th style="text-align:left; width: 5%;">:</th>
                            <th style="text-align:left;">' . $record['national_id'] . '</th>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Tax ID</th>
                            <th style="text-align:left;">:</th>
                            <th style="text-align:left;">' . $record['tax_id'] . '</th>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Martial Status</th>
                            <th style="text-align:left;">:</th>
                            <th style="text-align:left;">(' . $record['marital'] . ') <small>' . $record['marital_name'] . '<small></th>
                        </tr>
                        <tr>
                            <th style="text-align:left;">TER Code</th>
                            <th style="text-align:left;">:</th>
                            <th style="text-align:left;">'.$record['ter_number'].'</th>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Working Days</th>
                            <th style="text-align:left;">:</th>
                            <th style="text-align:left;">' . $record['attandance_wd'] . ' Days</th>
                        </tr>
                    </table>
                </div>
                <div style="float:left; width:100%;">
                    <div class="row">
                        <div class="col p-0">
                            <table id="customers" style="width:100%; border-right: 1px solid black;">
                                <tr>
                                    <th colspan="2" style="text-align:center">INCOME</th>
                                </tr>
                                <tr>
                                    <td style="text-align:left;" width="250">Basic Salary</td>
                                    <td style="text-align:right;" width="150"><b>' . number_format($record['salary']) . '</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">Allowance (Fix)</td>
                                    <td style="text-align:right;"><b>'.number_format($total_allowence_fix).'</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">Allowance (Tmp)</td>
                                    <td style="text-align:right;"><b>'.number_format($total_allowence_temp).'</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">Overtime</td>
                                    <td style="text-align:right;">(<b>' . ($record['overtime_weekday'] + $record['overtime_holiday']) . '</b> Hour) <b>' . number_format(($record['overtime_amount_weekday'] + $record['overtime_amount_holiday'])) . '</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">Correction Overtime</td>
                                    <td style="text-align:right;">(<b>' . $record['overtime_correction'] . '</b> Hour) <b>' . number_format(($record['overtime_amount_correction'])) . '</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">Correction</td>
                                    <td style="text-align:right;">-</td>
                                </tr>
                                ' . $html_correction_plus . '
                                <tr>
                                    <th style="text-align:left;">TOTAL INCOME <i>(a)</i></th>
                                    <th style="text-align:right;"><b>' . number_format($total_income) . '</b></th>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align:center">BPJS COMPANY</th>
                                </tr>
                                ' . $html_bpjs_com . '
                                <tr>
                                    <th style="text-align:left;">TOTAL BPJS COMPANY <i>(b)</i></th>
                                    <th style="text-align:right;"><b>' . number_format(($arr_bpjs_com_amount_total)) . '</b></th>
                                </tr>
                                <tr>
                                    <th style="text-align:left;">BRUTO INCOME <i>(c) = (a + b)</i></th>
                                    <th style="text-align:right;"><b>' . number_format($total_income + $arr_bpjs_com_amount_total) . '</b></th>
                                </tr>
                            </table>
                        </div>
                        <div class="col p-0">
                            <table id="customers" style="width:100%; border-left: 1px solid black;">
                                <tr>
                                    <th colspan="2" style="text-align:center">DEDUCTION</th>
                                </tr>
                                <tr>
                                    <td style="text-align:left;" width="250">Absence</td>
                                    <td style="text-align:right;" width="150">(<b>' . ($record['deduction_absence'] + $total_deduction_number) . '</b> Days) <b>' . number_format($record['deduction_absence_amount'] + $total_deduction_amount) . '</b></td>
                                </tr>
                                ' . @$html_deduction . '
                                <tr>
                                    <td style="text-align:left;">Loans</td>
                                    <td style="text-align:right;"><b>' . number_format($record['loan_other'] + $record['loan_cooperative'] + $record['loan_bank']) . '</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">Correction Minus</td>
                                    <td style="text-align:right;"><b>' . number_format($record['correction_minus']) . '</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">BPJS Company</td>
                                    <td style="text-align:right;"><b>' . number_format($arr_bpjs_com_amount_total) . '</b></td>
                                </tr>
                                <tr>
                                    <th style="text-align:left;">TOTAL DEDUCTION <i>(d)</i></th>
                                    <th style="text-align:right;"><b>' . number_format($total_deduction + $arr_bpjs_com_amount_total) . '</b></th>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align:center">BPJS EMPLOYEE</th>
                                </tr>
                                ' . $html_bpjs_emp . '
                                <tr>
                                    <th style="text-align:left;">TOTAL BPJS <i>(e)</i></th>
                                    <th style="text-align:right;"><b>' . number_format(($arr_bpjs_emp_amount_total)) . '</b></th>
                                </tr>
                                <tr>
                                    <th style="text-align:left;">TER ('.$record['ter_persentase'].') <i>(f)</i></th>
                                    <th style="text-align:right;"><b>' . number_format($record['ter']) . '</b></th>
                                </tr>
                                <tr>
                                    <th style="text-align:left;">NET INCOME <i>(c - d - e - f)</i></th>
                                    <th style="text-align:right;"><b>' . number_format($record['net_income']) . '</b></th>
                                </tr>
                                <tr>
                                    <th style="text-align:center;">Print By</th>
                                    <th style="text-align:center;">Accept By</th>
                                </tr>
                                <tr>
                                    <th style="text-align:center; height:70px;"></th>
                                    <th style="text-align:center; height:70px;"></th>
                                </tr>
                                <tr>
                                    <th style="text-align:center;">' . $this->session->name . '</th>
                                    <th style="text-align:center;">' . $record['name'] . '</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div></center>';
        $html .= '</body></html>';

        $email = $this->emails->emailSalarySlip($record['email'], $record['name'], $config->name, base64_encode($html));
        if (!filter_var($record['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array("title" => "Not Registered", "message" => "Email not register in Employee List", "theme" => "error"));
        } elseif ($email) {
            echo json_encode(array("title" => "Delivered", "message" => "Salary Slip successfully sent to " . $record['email'], "theme" => "success"));
        } else {
            echo json_encode(array("title" => "Failed", "message" => "Salary Slip unseccessfull sent to " . $record['email'], "theme" => "error"));
        }
    }

    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=salary_slip_$format.xls");
        }

        if ($this->input->get()) {
            $filter_from = $this->input->get('filter_from');
            $filter_to = $this->input->get('filter_to');
            $filter_division = $this->input->get('filter_division');
            $filter_departement = $this->input->get('filter_departement');
            $filter_departement_sub = $this->input->get('filter_departement_sub');
            $filter_employee = $this->input->get('filter_employee');
            $filter_group = $this->input->get('filter_group');
            $filter_is_publish = $this->input->get('filter_is_publish');
            $username = $this->session->username;

            $period_start = date("Y-m", strtotime($filter_from));
            $period_end = date("Y-m", strtotime($filter_to));

            $username = $this->session->username;
            if ($username == "admin") {
                $privilege = "";
            } else {
                $privilege = " and f.status = '1'";
            }

            $restrictionSQL = '';
            if (isset($this->restrict_user)) {
                $restrictionSQL = $this->accessmodule->getEmployeeRestrictionSQL($this->restrict_user, 'b');
            }
            $query = $this->db->query("SELECT a.*, b.bank_branch, b.bank_no, b.national_id, b.tax_id, i.name as marital_name, b.email, d.ter as ter_persentase
            FROM payrolls a
            JOIN employees b ON a.employee_id = b.id
            LEFT JOIN privilege_groups e ON b.group_id = e.group_id and e.username = '$username' $privilege
            LEFT JOIN marital_categories d ON a.ter_number = d.number
            LEFT JOIN maritals i ON a.marital = i.number
            WHERE a.period_start = '$period_start'
            $restrictionSQL
            AND a.period_end = '$period_end'
            AND a.is_publish = '$filter_is_publish'
            AND b.division_id LIKE '%$filter_division%'
            AND b.departement_id LIKE '%$filter_departement%'
            AND b.departement_sub_id LIKE '%$filter_departement_sub%'
            AND b.group_id LIKE '%$filter_group%'
            AND a.employee_id LIKE '%$filter_employee%'
            GROUP BY b.id
            ORDER BY a.`name` ASC");
            $records = $query->result_array();

            //Config
            $this->db->select('*');
            $this->db->from('config');
            $config = $this->db->get()->row();

            $html = '<html>
                <head>
                    <title>Print Data</title>
                </head>
                <style>
                    body {font-family: Arial, Helvetica, sans-serif;}
                    #customers {border-collapse: collapse;width: 100%;font-size: 14px;}
                    #customers th {border: 1px solid black;padding: 5px;}
                    #customers td {padding-left: 5px; padding-right:5px;}
                    #customers tr:hover {background-color: #ddd;}
                    #customers th {padding-top: 2px;padding-bottom: 2px;text-align: left;color: black;}
                </style>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
                <body>';
            $no = 1;
            //Looping per Employee
            foreach ($records as $record) {
                //Correction
                //jika dia ada tunjuangan ambil field dan isinya
                $q_correction_plus = $this->db->query("SELECT employee_id, `trans_date`, correction_name, remarks, SUM(amount) as amount FROM corrections WHERE employee_id='$record[employee_id]' and trans_date between '$filter_from' and '$filter_to' and correction_type = 'PLUS' GROUP BY correction_name");
                $r_correction_plus = $q_correction_plus->result_array();

                $r_correction_total = 0;
                $html_correction_plus = "";
                foreach ($r_correction_plus as $r_correction_plus_data) {
                    $r_correction_total += $r_correction_plus_data['amount'];
                    $html_correction_plus .= '<tr>
                                                <td style="text-align:left;">' . $r_correction_plus_data['remarks'] . '</td>
                                                <td style="text-align:right;"><b>' . number_format($r_correction_plus_data['amount']) . '</b></td>
                                            </tr>';
                }

                //Deduction Amount
                $total_deduction_amount = 0;
                foreach (json_decode($record['deduction_amount'], true) as $deduction_amount => $val_deduction_amount) {
                    $total_deduction_amount += $val_deduction_amount;
                }

                //Deduction Number
                $total_deduction_number = 0;
                foreach (json_decode($record['deduction_number'], true) as $deduction_number => $val_deduction_number) {
                    if ($val_deduction_number == "") {
                        $val_deduction_number = 0;
                    }

                    $total_deduction_number += $val_deduction_number;
                }

                //Allowance Amount
                //jika dia ada tunjuangan ambil field dan isinya
                // $r_allowance = json_decode($record['allowence'], true);
                // $arr_allowance_amount_total = 0;
                // foreach ($r_allowance as $allowance_data => $allowance_val) {
                //     $arr_allowance_amount_total += $allowance_val;
                // }

                $total_allowence = 0;
                $total_allowence_temp = 0;
                $total_allowence_fix = 0;
                foreach (json_decode($record['allowence'], true) as $allowence => $val_allowence) {
                    $allTemp = $this->crud->read("allowances", ["number" => $allowence], ["type" => "TEMPORARY"]);
                    $allFix = $this->crud->read("allowances", ["number" => $allowence], ["type" => "FIX"]);
                    
                    if(!empty($allTemp)){
                        $total_allowence_temp += (int) $val_allowence;
                    }

                    if(!empty($allFix)){
                        $total_allowence_fix += (int) $val_allowence;
                    }

                    $total_allowence += (int)$val_allowence;
                }
                
                //Deduction Amount
                //Jika dia ada pemotongan gaji ambil field dan isinya
                $q_deduction = $this->db->query("SELECT b.number, b.name, SUM(a.amount) as amount
                    FROM deductions b
                    LEFT JOIN setup_deductions a ON a.deduction_id = b.id
                    WHERE a.employee_id = '$record[employee_id]'
                    GROUP BY b.number, b.name 
                    HAVING SUM(a.amount) > 0
                    ORDER BY b.name asc");
                $r_deduction = $q_deduction->result_array();

                $arr_deduction_amount_total = 0;
                $html_deduction = "";
                foreach ($r_deduction as $deduction_data) {
                    $arr_deduction_amount_total += $deduction_data['amount'];
                    $html_deduction .= ' <tr>
                                            <td style="text-align:left;">' . $deduction_data['name'] . '</td>
                                            <td style="text-align:right;"><b>' . number_format($deduction_data['amount']) . '</b></td>
                                        </tr>';
                }
                //-------------------------------------------------------------------------------------------------------------------------------------------------------

                //BPJS Employee
                //Kalo dia mempunyai BPJS
                $bpjsKeys = json_decode($record['bpjs_employee'], true);
                $html_bpjs_emp = "";
                $arr_bpjs_emp_amount_total = 0;
                foreach ($bpjsKeys as $bpjs_employee => $bpjs_value) {
                    $arr_bpjs = explode("_", $bpjs_employee);
                    $r_bpjs_emp = $this->crud->read('bpjs', ['status' => 0], ["number" => $arr_bpjs[0]]);

                    $arr_bpjs_emp_amount_total += !empty($bpjs_value) ? $bpjs_value : 0;
                    if (!empty($r_bpjs_emp->employee)) {
                        $html_bpjs_emp .= ' <tr>
                                                <td style="text-align:left;">' . $r_bpjs_emp->name . ' <i style="font-size:10px;">('.$r_bpjs_emp->employee.'%)</i></td>
                                                <td style="text-align:right;"><b>' . number_format($bpjs_value) . '</b></td>
                                            </tr>';
                    }
                }
                //-------------------------------------------------------------------------------------------------------------------------------------------------------

                //BPJS Company
                //Kalo dia mempunyai BPJS
                $bpjsComps = json_decode($record['bpjs_company'], true);
                $html_bpjs_com = "";
                $arr_bpjs_com_amount_total = 0;
                foreach ($bpjsComps as $bpjs_company => $bpjs_com_val) {
                    $com_bpjs = explode("_", $bpjs_company);
                    $r_bpjs_com = $this->crud->read('bpjs', ['status' => 0], ["number" => $com_bpjs[0]]);

                    $arr_bpjs_com_amount_total += !empty($bpjs_com_val) ? $bpjs_com_val : 0;
                    if (!empty($r_bpjs_com->company)) {
                        $html_bpjs_com.= ' <tr>
                                                <td style="text-align:left;">' . $r_bpjs_com->name . ' <i style="font-size:10px;">('.$r_bpjs_com->company.'%)</i></td>
                                                <td style="text-align:right;"><b>' . number_format($bpjs_com_val) . '</b></td>
                                            </tr>';
                    }
                }
                //-------------------------------------------------------------------------------------------------------------------------------------------------------

                $total_income = $record['salary'] + $total_allowence + $record['total_overtime_amount'] + $record['correction_plus'];
                $total_deduction = $record['deduction_absence_amount'] + $total_deduction_amount + $arr_deduction_amount_total + $record['loan_bank'] + $record['loan_cooperative'] + $record['loan_other'] + $record['correction_minus'];
                if ($no % 2 == 0) {
                    $html .= '<div style="page-break-after:always;">';
                }

                $html .= '  <div class="container" style="border:1px solid black; margin-bottom:20px; padding-top:10px; float:left;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td width="80" style="font-size: 12px; vertical-align: top; text-align: center; vertical-align:jus margin-right:10px;">
                                                <img src="' . $config->favicon . '" width="40">
                                            </td>
                                            <td style="font-size: 20px; text-align: left; margin:2px;">
                                                <b>' . $config->name . '</b>
                                            </td>
                                            <td style="font-size: 20px; text-align: right; margin:2px;">
                                                SALARY SLIP
                                            </td>
                                        </tr>
                                    </table>
                                    <hr>
                                    <div style="float:left; width:50%;">
                                        <table style="width:100%; margin-bottom: 5px; font-size: 12px;">
                                            <tr>
                                                <th style="text-align:left; width: 30%;">Cut Off Period</th>
                                                <th style="text-align:left; width: 5%;">:</th>
                                                <th style="text-align:left; width: 60%;">' . date("d M Y", strtotime($filter_from)) . ' to ' . date("d M Y", strtotime($filter_to)) . '</th>
                                            </tr>
                                            <tr>
                                                <th style="text-align:left;">Employee ID</th>
                                                <th style="text-align:left;">:</th>
                                                <th style="text-align:left;">' . $record['number'] . '</th>
                                            </tr>
                                            <tr>
                                                <th style="text-align:left;">Employee Name</th>
                                                <th style="text-align:left;">:</th>
                                                <th style="text-align:left;">' . $record['name'] . '</th>
                                            </tr>
                                            <tr>
                                                <th style="text-align:left;">Departement</th>
                                                <th style="text-align:left;">:</th>
                                                <th style="text-align:left;">' . $record['departement_name'] . '</th>
                                            </tr>
                                            <tr>
                                                <th style="text-align:left;">Departement Sub</th>
                                                <th style="text-align:left;">:</th>
                                                <th style="text-align:left;">' . $record['departement_sub_name'] . '</th>
                                            </tr>
                                        </table>
                                    </div>
                                    <div style="float:left; width:50%;">
                                        <table style="width:100%; margin-bottom: 5px; font-size: 12px;">
                                            <tr>
                                                <th style="text-align:left; width: 30%;">National ID</th>
                                                <th style="text-align:left; width: 5%;">:</th>
                                                <th style="text-align:left;">' . $record['national_id'] . '</th>
                                            </tr>
                                            <tr>
                                                <th style="text-align:left;">Tax ID</th>
                                                <th style="text-align:left;">:</th>
                                                <th style="text-align:left;">' . $record['tax_id'] . '</th>
                                            </tr>
                                            <tr>
                                                <th style="text-align:left;">Martial Status</th>
                                                <th style="text-align:left;">:</th>
                                                <th style="text-align:left;">(' . $record['marital'] . ') <small>' . $record['marital_name'] . '<small></th>
                                            </tr>
                                            <tr>
                                                <th style="text-align:left;">TER Code</th>
                                                <th style="text-align:left;">:</th>
                                                <th style="text-align:left;">'.$record['ter_number'].'</th>
                                            </tr>
                                            <tr>
                                                <th style="text-align:left;">Working Days</th>
                                                <th style="text-align:left;">:</th>
                                                <th style="text-align:left;">' . $record['attandance_wd'] . ' Days</th>
                                            </tr>
                                        </table>
                                    </div>
                                    <div style="float:left; width:100%;">
                                        <div class="row">
                                            <div class="col p-0">
                                                <table id="customers" style="width:100%; border-right: 1px solid black;">
                                                    <tr>
                                                        <th colspan="2" style="text-align:center">INCOME</th>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:left;" width="250">Basic Salary</td>
                                                        <td style="text-align:right;" width="150"><b>' . number_format($record['salary']) . '</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:left;">Allowance (Fix)</td>
                                                        <td style="text-align:right;"><b>'.number_format($total_allowence_fix).'</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:left;">Allowance (Tmp)</td>
                                                        <td style="text-align:right;"><b>'.number_format($total_allowence_temp).'</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:left;">Overtime</td>
                                                        <td style="text-align:right;">(<b>' . ($record['overtime_weekday'] + $record['overtime_holiday']) . '</b> Hour) <b>' . number_format(($record['overtime_amount_weekday'] + $record['overtime_amount_holiday'])) . '</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:left;">Correction Overtime</td>
                                                        <td style="text-align:right;">(<b>' . $record['overtime_correction'] . '</b> Hour) <b>' . number_format(($record['overtime_amount_correction'])) . '</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:left;">Correction</td>
                                                        <td style="text-align:right;">-</td>
                                                    </tr>
                                                    ' . $html_correction_plus . '
                                                    <tr>
                                                        <th style="text-align:left;">TOTAL INCOME <i>(a)</i></th>
                                                        <th style="text-align:right;"><b>' . number_format($total_income) . '</b></th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="2" style="text-align:center">BPJS COMPANY</th>
                                                    </tr>
                                                    ' . $html_bpjs_com . '
                                                    <tr>
                                                        <th style="text-align:left;">TOTAL BPJS COMPANY <i>(b)</i></th>
                                                        <th style="text-align:right;"><b>' . number_format(($arr_bpjs_com_amount_total)) . '</b></th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align:left;">BRUTO INCOME <i>(c) = (a + b)</i></th>
                                                        <th style="text-align:right;"><b>' . number_format($total_income + $arr_bpjs_com_amount_total) . '</b></th>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col p-0">
                                                <table id="customers" style="width:100%; border-left: 1px solid black;">
                                                    <tr>
                                                        <th colspan="2" style="text-align:center">DEDUCTION</th>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:left;" width="250">Absence</td>
                                                        <td style="text-align:right;" width="150">(<b>' . ($record['deduction_absence'] + $total_deduction_number) . '</b> Days) <b>' . number_format($record['deduction_absence_amount'] + $total_deduction_amount) . '</b></td>
                                                    </tr>
                                                    ' . @$html_deduction . '
                                                    <tr>
                                                        <td style="text-align:left;">Loans</td>
                                                        <td style="text-align:right;"><b>' . number_format($record['loan_other'] + $record['loan_cooperative'] + $record['loan_bank']) . '</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:left;">Correction Minus</td>
                                                        <td style="text-align:right;"><b>' . number_format($record['correction_minus']) . '</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align:left;">BPJS Company</td>
                                                        <td style="text-align:right;"><b>' . number_format($arr_bpjs_com_amount_total) . '</b></td>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align:left;">TOTAL DEDUCTION <i>(d)</i></th>
                                                        <th style="text-align:right;"><b>' . number_format($total_deduction + $arr_bpjs_com_amount_total) . '</b></th>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="2" style="text-align:center">BPJS EMPLOYEE</th>
                                                    </tr>
                                                    ' . $html_bpjs_emp . '
                                                    <tr>
                                                        <th style="text-align:left;">TOTAL BPJS <i>(e)</i></th>
                                                        <th style="text-align:right;"><b>' . number_format(($arr_bpjs_emp_amount_total)) . '</b></th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align:left;">TER ('.$record['ter_persentase'].') <i>(f)</i></th>
                                                        <th style="text-align:right;"><b>' . number_format($record['ter']) . '</b></th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align:left;">NET INCOME <i>(c - d - e - f)</i></th>
                                                        <th style="text-align:right;"><b>' . number_format($record['net_income']) . '</b></th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align:center;">Print By</th>
                                                        <th style="text-align:center;">Accept By</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align:center; height:70px;"></th>
                                                        <th style="text-align:center; height:70px;"></th>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align:center;">' . $this->session->name . '</th>
                                                        <th style="text-align:center;">' . $record['name'] . '</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>';

                if ($no % 2 == 0) {
                    $html .= '</div>';
                }
                $no++;
            }

            $html .= '</body></html>';
            echo $html;
            $html .= '</table>';
        }
    }

    public function print_recap($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=report_summary_payroll_$format.xls");
        }

        if ($this->input->get()) {
            $filter_from = $this->input->get('filter_from');
            $filter_to = $this->input->get('filter_to');
            $filter_division = $this->input->get('filter_division');
            $filter_departement = $this->input->get('filter_departement');
            $filter_departement_sub = $this->input->get('filter_departement_sub');
            $filter_employee = $this->input->get('filter_employee');
            $filter_group = $this->input->get('filter_group');
            $username = $this->session->username;

            $period_start = date("Y-m", strtotime($filter_from));
            $period_end = date("Y-m", strtotime($filter_to));
            $username = $this->session->username;
            if ($username == "admin") {
                $privilege = "";
            } else {
                $privilege = " and f.status = '1'";
            }

            //Select Query
            $this->db->select('
                b.departement_id,
                b.departement_sub_id,
                b.group_id,
                c.name as departement_name, 
                d.name as departement_sub_name, 
                e.name as group_name, 
                COUNT(b.id) as employee, 
                SUM(a.net_income) as income');
            $this->db->from('payrolls a');
            $this->db->join('employees b', "a.employee_id = b.id");
            $this->db->join('departements c', "b.departement_id = c.id");
            $this->db->join('departement_subs d', "b.departement_sub_id = d.id");
            $this->db->join('groups e', "b.group_id = e.id");
            $this->db->join('privilege_groups f', "(b.group_id = f.group_id or b.group_id is null or b.group_id = '') and f.username = '$username' $privilege", 'left');
            $this->db->where('a.deleted', 0);
            if (isset($this->restrict_user)) {
                $this->accessmodule->applyEmployeeRestriction($this->restrict_user, 'b');
            }
            $this->db->where('a.period_start =', $period_start);
            $this->db->where('a.period_end =', $period_end);
            $this->db->like('b.id', $filter_employee);
            $this->db->like('b.division_id', $filter_division);
            $this->db->like('b.departement_id', $filter_departement);
            $this->db->like('b.departement_sub_id', $filter_departement_sub);
            $this->db->like('b.group_id', $filter_group);
            $this->db->group_by(array("c.id", "d.id", "e.id"));
            $this->db->order_by('c.name', 'ASC');
            $this->db->order_by('SUM(a.net_income)', 'ASC');
            //Get Data Array
            $payrolls = $this->db->get()->result_array();

            //Config Page
            $rows = 40;
            $page = ceil(count($payrolls) / $rows);

            //Config
            $this->db->select('*');
            $this->db->from('config');
            $config = $this->db->get()->row();

            $html = '<html><head><title>Print Data</title></head><style>body {font-family: Arial, Helvetica, sans-serif;}#customers {border-collapse: collapse;width: 100%;font-size: 12px;}#customers td, #customers th {border: 1px solid #ddd;padding: 2px;}#customers tr:nth-child(even){background-color: #f2f2f2;}#customers tr:hover {background-color: #ddd;}#customers th {padding-top: 2px;padding-bottom: 2px;text-align: left;color: black;}</style><body>';
            $no = 1;
            $hal = 1;
            for ($i = 0; $i < $page; $i++) {
                //Select Query
                $this->db->select('
                    b.departement_id,
                    b.departement_sub_id,
                    b.group_id,
                    c.name as departement_name, 
                    d.name as departement_sub_name, 
                    e.name as group_name, 
                    COUNT(b.id) as employee, 
                    SUM(a.net_income) as income');
                $this->db->from('payrolls a');
                $this->db->join('employees b', "a.employee_id = b.id");
                $this->db->join('departements c', "b.departement_id = c.id");
                $this->db->join('departement_subs d', "b.departement_sub_id = d.id");
                $this->db->join('groups e', "b.group_id = e.id");
                $this->db->join('privilege_groups f', "b.group_id = f.group_id and f.username = '$username' $privilege", 'left');
                $this->db->where('a.deleted', 0);
                if (isset($this->restrict_user)) {
                    $this->accessmodule->applyEmployeeRestriction($this->restrict_user, 'b');
                }
                $this->db->where('a.period_start =', $period_start);
                $this->db->where('a.period_end =', $period_end);
                $this->db->like('b.id', $filter_employee);
                $this->db->like('b.division_id', $filter_division);
                $this->db->like('b.departement_id', $filter_departement);
                $this->db->like('b.departement_sub_id', $filter_departement_sub);
                $this->db->like('b.group_id', $filter_group);
                $this->db->group_by(array("c.id", "d.id"));
                $this->db->order_by('c.name', 'ASC');
                $this->db->order_by('SUM(a.net_income)', 'ASC');
                $this->db->limit(50, ($i * 50));
                //Get Data Array
                $records = $this->db->get()->result_array();

                $html .= '  <center>
                            <div style="float: left; font-size: 12px; text-align: left;">
                                <table style="width: 100%;">
                                    <tr>
                                        <td width="50" style="font-size: 12px; vertical-align: top; text-align: center; vertical-align:jus margin-right:10px;">
                                            <img src="' . $config->favicon . '" width="30">
                                        </td>
                                        <td style="font-size: 14px; text-align: left; margin:2px;">
                                            <b>' . $config->name . '</b><br>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="float: right; font-size: 12px; text-align: right;">
                                Print Date ' . date("d M Y H:m:s") . ' <br>
                                Print By ' . $this->session->username . ' <br>
                                Page ' . $hal . '/' . $page . '
                            </div>
                        </center>
                        <br><br><br>
                        <center>
                            <h3 style="margin:0;">REPORT RECAP SALARY SLIP</h3>
                            <p style="margin:0;">Period <b>' . date("d F Y", strtotime($filter_from)) . '</b> to <b>' . date("d F Y", strtotime($filter_to)) . '</b></p>
                            <br>
                        </center>
                        
                        <table id="customers" border="1">
                            <tr>
                                <th width="20" style="text-align:center;">No</th>
                                <th style="text-align:center;">Departement</th>
                                <th style="text-align:center;">Departement Sub</th>
                                <th style="text-align:center;">Employee</th>
                            </tr>';
                $totalEmployee = 0;
                $totalIncome = 0;
                foreach ($records as $data) {
                    $html .= '  <tr>
                                    <td style="text-align:center;">' . $no . '</td>
                                    <td>' . $data['departement_name'] . '</td>
                                    <td>' . $data['departement_sub_name'] . '</td>
                                    <td style="text-align:right;">' . number_format($data['employee']) . '</td>
                                </tr>';
                    $totalEmployee += $data['employee'];
                    $totalIncome += $data['income'];
                    $no++;
                }

                $hal++;
                if (($i + 1) != $page) {
                    $html .= '<div style="page-break-after:always;"></div>';
                }
            }

            $html .= '</body></html>';
            echo $html;
        }
    }

    public function printPaySlip()
    {
        $date_to = $this->input->get('filter_to');
        $date_from = $this->input->get('filter_from');
        $division = $this->input->get('filter_division');
        $departement = $this->input->get('filter_departement');
        $departement_sub = $this->input->get('filter_departement_sub');
        $employee = $this->input->get('filter_employee');
        $group = $this->input->get('filter_group');
        $filter_is_publish = $this->input->get('filter_is_publish');

        $time_to = strtotime($date_to);
        $time_from = strtotime($date_from);
        $period_to = date('Y-m', $time_to);
        $period_from = date('Y-m', $time_from);
        $payroll_cutoff = date('d M Y', $time_from).' - '.date('d M Y', $time_to);

        $this->db->distinct()->select("COUNT(trans_date) AS count")->from("calendars")->where("trans_date BETWEEN '$date_from' AND '$date_to'", NULL, FALSE);
        $count_national_day = intval($this->db->get()->row()->count);
        $national_day = $count_national_day.'d';
        // die(var_dump($count_national_day));

        $mulai = new DateTime($date_from);
        $selesai = new DateTime($date_to);
        $selesai->modify('+1 day'); // agar tanggal akhir ikut dihitung
        $daterange = new DatePeriod($mulai, new DateInterval('P1D'), $selesai);

        $total = [
            'sabtu' => 0,
            'minggu' => 0,
            'senin_kamis' => 0
        ];

        foreach ($daterange as $tanggal) {
            $hari = $tanggal->format('N'); // format('N'): 1 = Senin ... 7 = Minggu

            if ($hari == 7) { $total['minggu']++; }
            elseif ($hari == 6) { $total['sabtu']++; }
            else { $total['senin_kamis']++; }
        }

        $total_attandace = [
            '5' => ['weekday' => $total['senin_kamis'], 'weekend' => $total['sabtu'] + $total['minggu']],
            '6' => ['weekday' => $total['senin_kamis'] + $total['sabtu'], 'weekend' => $total['minggu']]
        ];

        $total_attandace['5']['weekday'] -= $count_national_day;
        $total_attandace['6']['weekday'] -= $count_national_day;

        $username = $this->session->username;
        if ($username == "admin") {
            $privilege = "";
        } else {
            $privilege = " and k.status = '1'";
        }

        $allowed_groups = $this->db
            ->select('group_id')
            ->from('privilege_groups')
            ->where('username', $username)
            ->where('status', 1)
            ->get()
            ->result_array();

        $allowed_group_ids = array_column($allowed_groups, 'group_id');

        $this->db->distinct()->select(
            "COALESCE(TRIM(b.logo), '') AS logo,".
            "COALESCE(TRIM(b.name), '') AS division,".
            "TRIM(a.number) AS nik,".
            "TRIM(a.name) AS fullname,".
            "COALESCE(TRIM(c.name), '') AS dept,".
            "COALESCE(TRIM(d.name), '') AS sub_dept,".
            "COALESCE(TRIM(e.name), '') AS grup,".
            "COALESCE(TRIM(f.number), '') AS marital,".
            "TRIM(a.tax_id) AS npwp,".
            "COALESCE(TRIM(g.salary), '') AS salary,".
            "COALESCE(g.ter, 0) AS pph21,".
            "COALESCE(g.net_income, 0) AS thp,".
            "CASE WHEN COALESCE(g.ter, 0) < 0 OR COALESCE(g.tax_allowance, 0) = 0 THEN 0 ELSE COALESCE(g.ter, 0) END AS tax,".
            "COALESCE(TRIM(g.allowence), '') AS allowance,".
            "COALESCE(TRIM(g.overtime_amount_weekday), '') AS weekday,".
            "COALESCE(TRIM(g.overtime_amount_saturday), '') AS saturday,".
            "COALESCE(TRIM(g.overtime_amount_sunday), '') AS sunday,".
            "COALESCE(TRIM(g.overtime_amount_holiday), '') AS holiday,".
            "COALESCE(TRIM(g.bpjs_employee), '') AS bpjs_employee,".
            "COALESCE(TRIM(g.total_all_deduction), '') AS deduction,".
            "COALESCE(TRIM(h.bpjs), '') AS bpjs,".
            "COALESCE(TRIM(g.bpjs_company), '') AS bpjs_company,".
            "COALESCE(TRIM(g.attandance_wd), '') AS workingday,".
            "COALESCE(TRIM(g.attandance), '') AS attandance,".
            "COALESCE(j.days, 5) AS days,".
            "TRIM(a.id) AS id,".
            "TRIM(a.group_id) AS group_id,".
            "COALESCE(g.correction_plus, 0) AS correction_plus,".
            "COALESCE(g.correction_minus, 0) AS correction_minus"
        )->from("employees a")
            ->join("divisions b", "a.division_id = b.id", "left")
            ->join("departements c", "a.departement_id = c.id", "left")
            ->join("departement_subs d", "a.departement_sub_id = d.id", "left")
            ->join("groups e", "a.group_id = e.id", "left")
            ->join("maritals f", "a.marital_id = f.id", "left")
            ->join("payrolls g", "a.id = g.employee_id", "left")
            ->join("setup_salaries h", "a.id = h.employee_id", "left")
            ->join("shift_employees i", "a.id = i.employee_id", "left")
            ->join("shift_details j", "i.shift_id = j.shift_id", "left");
        if ($username !== 'admin' && !empty($allowed_group_ids)) {
            $this->db->join(
                'privilege_groups k',
                "a.group_id = k.group_id
                AND k.username = '$username'
                AND k.status = 1",
                'left'
            );
        }
        if ($username !== 'admin' && !empty($allowed_group_ids)) {
            $this->db->group_start();
                // whitelist group
                $this->db->where_in('a.group_id', $allowed_group_ids);

                $this->db->or_where('a.number', $this->session->userdata()['number']);
            $this->db->group_end();
        }
        $this->db->where("g.period_start", $period_from)->where("g.period_end", $period_to)->where("a.division_id", $division)->where("g.is_publish", $filter_is_publish);
        if (isset($this->restrict_user)) {
            $this->accessmodule->applyEmployeeRestriction($this->restrict_user, 'a');
        }
        if (!empty($departement)) { $this->db->where("a.departement_id", $departement); }
        if (!empty($departement_sub)) { $this->db->where("a.departement_sub_id", $departement_sub); }
        if (!empty($employee)) { $this->db->where("a.id", $employee); }
        if (!empty($group)) { $this->db->where("a.group_id", $group); }
        $this->db->order_by("fullname");
        $get = $this->db->get()->result();
        // die($this->db->last_query());
        // die(var_dump($get));

        $overtimes = [];
        $allowances = [];
        $attandances = [];
        foreach ($get as $index => $row) {
            $attandances[$row->nik] = 0;
            $overtimes[$row->id] = 0;

            if ($row->group_id == '20221213000002') {
                $allowances[$row->id] = 0;
                $allowance = json_decode($row->allowance);
                foreach ($allowance as $key => $value) {
                    if (floatval($value) > 0) { $allowances[$row->id] += floatval($value); }
                }
            }
        }

        $this->db->distinct()->select("TRIM(number) AS id, COUNT(date_in) AS count")->from("attandances")
        ->where_in("number", array_keys($attandances))->where("date_in BETWEEN '$date_from' AND '$date_to'", NULL, FALSE)
        ->group_by("number")->order_by("id");
        foreach ($this->db->get()->result() as $key => $row) { $attandances[$row->id] = intval($row->count); }
        // die($this->db->last_query());
        // die(var_dump($attandances));

        $this->db->distinct()->select("TRIM(employee_id) AS id, SUM(duration_hour) AS total")->from("overtimes")
        ->where_in("employee_id", array_keys($overtimes))->where("trans_date BETWEEN '$date_from' AND '$date_to'", NULL, FALSE)
        ->group_by("employee_id")->order_by("id");
        foreach ($this->db->get()->result() as $key => $row) { $overtimes[$row->id] = intval($row->total); }

        $html = <<<HTML
            <!doctype html>
            <html lang="id">
            <head>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width,initial-scale=1" />
                <title>Payslip</title>
                <style>
                    @page { size: A4; margin: 20mm; }
                    body { width: 210mm; min-height: 297mm; margin: 0 auto; background: #f6f8fb;}

                    :root{--muted:#9a9a9a;--accent:#e9f0f6;--border:#e0e0e0;--bg:#ffffff;--primary:#222}
                    body{font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background:#f6f8fb; color:var(--primary)}
                    .card{max-width:900px;margin:8px auto;background:var(--bg);padding:22px;border-radius:6px;box-shadow:0 2px 8px rgba(16,24,40,0.06)}
                    .header{display:flex;justify-content:space-between;align-items:flex-start}
                    .logo{display:flex;gap:12px;align-items:center}
                    .logo img{width:64px;height:40px;object-fit:contain}
                    .confidential{color:#d35400;font-size:12px;font-weight:600}
                    h5{margin:8px 0 4px;font-size:18px}
                    .meta{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:8px}
                    .meta-left,.meta-right{font-size:13px}
                    .meta-row{display:flex;gap:8px;margin-bottom:6px}
                    .meta-row .label{min-width:160px;}
                    .table-wrap{display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-top:18px}
                    .box{border:1px solid var(--border);border-radius:4px;overflow:hidden;background:#fff}
                    table{width:100%;border-collapse:collapse}
                    th,td{padding:10px 12px;font-size:13px}
                    thead th{background:var(--accent);text-align:left;font-weight:600;font-size:13px;border-bottom:1px solid var(--border);}
                    .value{white-space:nowrap;text-align:right}
                    .totals{display:flex;justify-content:space-between;align-items:center;padding:12px 0px;border-top:1px solid var(--border);background:#fbfcfd}
                    .lower-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-top:18px}
                    .small-list{padding:12px}
                    .small-list h4{margin:0 0 8px;font-size:13px}
                    .benefit-row{display:flex;justify-content:space-between;padding:6px 0;font-size:13px;border-bottom:1px dashed var(--border)}
                    .attendance{font-size:13px}
                    .muted{color:var(--muted);font-size:13px}
                    @media print {
                        body { background: none !important; }
                        .card { box-shadow: none !important; background: #fff !important; }
                    }
                </style>
            </head>
            <body>
        HTML;

        $def_style_r = 'width: 50%; padding-bottom: 20px;';
        $def_style_l = 'width: 50%; padding-bottom: 20px;';
        $def_padding_r = 'padding-left: 3px;';
        $def_padding_l = 'padding-right: 3px;';
        foreach ($get as $index => $row) {
            $allowance = json_decode($row->allowance);
            $bpjs_employee = json_decode($row->bpjs_employee);
            $bpjs_company = json_decode($row->bpjs_company);
            $attandance = json_decode($row->attandance);
            $correction_plus = $row->correction_plus;
            $correction_minus = $row->correction_minus;
            $is_hl = $row->group_id == '20221213000002';
            $pagebreak = ($index != 0) ? 'style="page-break-before: always;"' : '';
            // die(var_dump($allowance));

            $logo = '';
            if (!empty($row->logo)) {
                $imgData = file_get_contents($row->logo);

                // Deteksi tipe mime (opsional tapi bagus digunakan)
                $finfo = finfo_open(FILEINFO_MIME_TYPE);

                // Format untuk img src
                $logo = '<img src="data:'.finfo_buffer($finfo, $imgData).';base64,'.base64_encode($imgData).'" style="width: 70px; height: 70px;">';
            }

            $div_tt = '';
            $total_allowence = 0;
            foreach ($allowance as $key => $value) {
                if (floatval($value) > 0) {
                    $total_allowence += floatval($value);
                    $key = strtoupper($key);
                    $value = number_format($value, 0, ',', '.');
                    $field = $this->crud->read('allowances', [], ["number" => $key]);
                    $div_tt .= ($is_hl) ? '' : '<tr><td>'.$field->name.'</td><td class="value">'.$value.'</td></tr>';
                }
            }

            $employee = $row->nik.' / '.$row->fullname;

            $salary = ($is_hl) ? $allowances[$row->id] : floatval($row->salary);
            $tt = floatval($allowance->tt ?? 0);
            $tax = floatval($row->tax);
            $weekday = floatval($row->weekday);
            $saturday = floatval($row->saturday);
            $sunday = floatval($row->sunday);
            $holiday = floatval($row->holiday);
            $sum_overtime = $weekday + $saturday + $sunday + $holiday;
            $sum_earnings = $salary + $total_allowence + $tax + $sum_overtime + $correction_plus;

            $bpjs_emp = floatval($bpjs_employee->bpjs_employee ?? 0);
            $jht_emp = floatval($bpjs_employee->jht_employee ?? 0);
            $deduction = floatval($row->deduction);
            $jp_emp = floatval($bpjs_employee->jp_employee ?? 0);
            $pph21 = floatval($row->pph21);
            $sum_deduction = $bpjs_emp + $jht_emp + $deduction + $jp_emp + $pph21 + $correction_minus;

            $salary = number_format($salary, 0, ',', '.');
            $tunjangan_tetap = number_format($tt, 0, ',', '.');
            $tax = number_format($tax, 0, ',', '.');
            $overtime = number_format($sum_overtime, 0, ',', '.');
            $correction_plus = number_format($correction_plus, 0, ',', '.');
            $total_earnings = number_format($sum_earnings, 0, ',', '.');

            $bpjsk = number_format($bpjs_emp, 0, ',', '.');
            $jht = number_format($jht_emp, 0, ',', '.');
            $deduction = number_format($deduction, 0, ',', '.');
            $jp = number_format($jp_emp, 0, ',', '.');
            $pph21 = number_format($pph21, 0, ',', '.');
            $correction_minus = number_format($correction_minus, 0, ',', '.');
            $total_deduction = number_format($sum_deduction, 0, ',', '.');

            $total_home_pay = number_format(($row->thp), 0, ',', '.');

            $bpjs = floatval($row->bpjs);
            $jkk_emp = floatval($bpjs_employee->jkk_employee ?? 0);
            $jkm_emp = floatval($bpjs_employee->jkm_employee ?? 0);
            $jht_com = floatval($bpjs_company->jht_company ?? 0);
            $jp_com = floatval($bpjs_company->jp_company ?? 0);
            $bpjs_com = floatval($bpjs_company->bpjs_company ?? 0);

            $bpjs_rate = number_format($bpjs, 0, ',', '.');
            $jkk = number_format($jkk_emp, 0, ',', '.');
            $jkm = number_format($jkm_emp, 0, ',', '.');
            $jht_company = number_format($jht_com, 0, ',', '.');
            $jp_company = number_format($jp_com, 0, ',', '.');
            $bpjsk_company = number_format($bpjs_com, 0, ',', '.');
            $total_benefits = number_format(($bpjs + $bpjs + $jkk_emp + $jkm_emp + $jht_com + $jp_com + $bpjs_com), 0, ',', '.');

            $actual_wd = $attandances[$row->nik].'d';
            $schedule_wd = intval($total_attandace[$row->days]['weekday']).'d';
            $dayoff = intval($total_attandace[$row->days]['weekend']).'d';
            $time_off_code = 'H: '.$attandances[$row->nik].'d S: '.intval($attandance->sa ?? 0).'d I: '.intval($attandance->ia ?? 0).'d';
            $overtime_hour = $overtimes[$row->id].'h';
            $div_space = ($is_hl) ? '<tr><td></td><td class="value">&nbsp;</td></tr>' : '';

            $html .= <<<HTML
            <div $pagebreak>
                <div class="card">
                    <div class="header">
                        <div class="logo">$logo</div>
                        <div style="text-align:right"><div class="confidential">*CONFIDENTIAL</div></div>
                    </div>
                    <div class="header">
                        <div><h5>$row->division</h5></div>
                        <div style="text-align:right"><h5>PAYSLIP</h5></div>
                    </div>

                    <div class="meta">
                        <div class="meta-left">
                            <div class="meta-row"><div class="label">Payroll cut off</div><div>$payroll_cutoff</div></div>
                            <div class="meta-row"><div class="label">ID / Name</div><div>$employee</div></div>
                            <div class="meta-row"><div class="label">Job position</div><div>$row->sub_dept</div></div>
                            <div class="meta-row"><div class="label">Organization</div><div>$row->dept</div></div>
                        </div>
                        <div class="meta-right">
                            <div class="meta-row"><div class="label">Grade / Level</div><div> &minus; / $row->grup</div></div>
                            <div class="meta-row"><div class="label">PTKP</div><div>$row->marital</div></div>
                            <div class="meta-row"><div class="label">NPWP</div><div>$row->npwp</div></div>
                        </div>
                    </div>

                    <div class="table-wrap">
                    <div class="box">
                        <table>
                            <thead><tr><th>Earnings</th><th class="value"></th></tr></thead>
                            <tbody>
                            <tr><td>Basic Salary</td><td class="value">$salary</td></tr>
                            $div_tt
                            <tr><td>Tax Allowance</td><td class="value">$tax</td></tr>
                            <tr><td>Overtime ($overtime_hour)</td><td class="value">$overtime</td></tr>
                            <tr><td>Correction Plus</td><td class="value">$correction_plus</td></tr>
                            <tr><td></td><td class="value">&nbsp;</td></tr>
                            $div_space
                            </tbody>
                            <tfoot><tr><td style="font-weight:700">Total earnings</td><td class="value" style="font-weight:700">$total_earnings</td></tr></tfoot>
                        </table>
                    </div>

                    <div class="box">
                    <table>
                        <thead><tr><th>Deductions</th><th class="value"></th></tr></thead>
                        <tbody>
                            <tr><td>BPJS Kesehatan Employee</td><td class="value">$bpjsk</td></tr>
                            <tr><td>JHT Employee</td><td class="value">$jht</td></tr>
                            <tr><td>Potongan Absen</td><td class="value">$deduction</td></tr>
                            <tr><td>Jaminan Pensiun Employee</td><td class="value">$jp</td></tr>
                            <tr><td>PPH 21</td><td class="value">$pph21</td></tr>
                            <tr><td>Correction Minus</td><td class="value">$correction_minus</td></tr>
                            <tr><td></td><td class="value">&nbsp;</td></tr>
                        </tbody>
                        <tfoot><tr><td style="font-weight:700">Total deductions</td><td class="value" style="font-weight:700">$total_deduction</td></tr></tfoot>
                    </table>
                    </div>
                    </div>

                    <div class="totals" style="margin-top: 14px;">
                        <div></div>
                        <table style="width: fit-content; float: right;"><tr>
                            <td style="font-size: 18px; font-weight: 700; padding-right: 100px;">Take Home Pay</td>
                            <td style="font-size: 20px; font-weight: 700; padding-right: 0px;">Rp$total_home_pay</td>
                        </tr></table>
                    </div>

                    <div class="lower-grid">
                        <div class="box small-list">
                            <h4>Benefits*</h4>
                            <div class="benefit-row"><div>BPJS TK Rate</div><div>$bpjs_rate</div></div>
                            <div class="benefit-row"><div>BPJS Kes Rate</div><div>$bpjs_rate</div></div>
                            <div class="benefit-row"><div>JKK</div><div>$jkk</div></div>
                            <div class="benefit-row"><div>JKM</div><div>$jkm</div></div>
                            <div class="benefit-row"><div>JHT Company</div><div>$jht_company</div></div>
                            <div class="benefit-row"><div>Jaminan Pensiun Company</div><div>$jp_company</div></div>
                            <div class="benefit-row"><div>BPJS Kesehatan Company</div><div>$bpjsk_company</div></div>
                            <div style="padding-top:8px;font-weight:700">Total benefits <span style="float:right">$total_benefits</span></div>
                        </div>

                        <div class="box small-list">
                            <h4>Attendance Summary</h4>
                            <div class="attendance">
                                <div class="benefit-row"><div>Actual Working Day</div><div>$actual_wd</div></div>
                                <div class="benefit-row"><div>Schedule Working Day</div><div>$schedule_wd</div></div>
                                <div class="benefit-row"><div>Dayoff</div><div>$dayoff</div></div>
                                <div class="benefit-row"><div>National Holiday</div><div>$national_day</div></div>
                                <div class="benefit-row"><div>Attendance/Time Off Code</div><div>$time_off_code</div></div>
                                <div class="benefit-row"><div>Overtime Hours*</div><div>$overtime_hour</div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            HTML;
        }

        $html .= <<<HTML
                </body>
            </html>
        HTML;

        echo $html;
    }
}
