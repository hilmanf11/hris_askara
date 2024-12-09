<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Attandance_days extends CI_Controller
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
            $this->load->view('report/attandance_days');
        } else {
            redirect('error_access');
        }
    }

    function cekStatusMasuk($shift, $masuk) {
        // Konversi waktu masuk ke format detik
        $masukTime = strtotime($masuk);
        
        // Variabel untuk menyimpan jarak waktu terkecil
        $closestShift = null;
        $minDiff = null;
    
        // Cari waktu shift terdekat
        foreach ($shift as $shiftTime) {
            $shiftTimeInSeconds = strtotime($shiftTime);
            $diff = abs($masukTime - $shiftTimeInSeconds);
    
            if (is_null($minDiff) || $diff < $minDiff) {
                $minDiff = $diff;
                $closestShift = $shiftTime;
            }
        }
    
        // Periksa apakah waktu masuk lebih awal atau lebih lambat dari shift terdekat
        if ($masukTime <= strtotime($closestShift)) {
            return "ON TIME";
        } else {
            return "LATE";
        }
    }

    public function printcsv(){
        if ($this->input->get()) {

            header("Content-type: text/csv; charset=utf-8");
            header("Content-Disposition: attachment; filename=attandance_days_" . time() . ".csv");
            $output = fopen("php://output", "w");

            fputcsv($output, array(
                "No","Departement", "Departement Sub", "Employee ID", "Employee Name", "Shift", "Trans Date", "Time In/Out",
                "Status", "Permit", "Description"
            ));

            $filter_from = $this->input->get('filter_from');
            $filter_to = $this->input->get('filter_to');
            $filter_division = $this->input->get('filter_division');
            $filter_departement = $this->input->get('filter_departement');
            $filter_departement_sub = $this->input->get('filter_departement_sub');
            $filter_employee = $this->input->get('filter_employee');
            $filter_permit_type = $this->input->get('filter_permit_type');
            $filter_status_in = $this->input->get('filter_status_in');
            $filter_status = $this->input->get('filter_status');
            $filter_status_employee = $this->input->get('filter_status_employee');

            $this->db->select('b.id as employee_id, b.number as employee_number, b.name as employee_name, c.name as division_name, 
            d.name as departement_name, e.name as departement_sub_name, g.name as shift_name, h.days, b.date_sign');
            $this->db->from('employees b');
            $this->db->join('divisions c', 'b.division_id = c.id');
            $this->db->join('departements d', 'b.departement_id = d.id');
            $this->db->join('departement_subs e', 'b.departement_sub_id = e.id');
            $this->db->join('shift_employees f', 'b.id = f.employee_id', 'left');
            $this->db->join('shifts g', 'f.shift_id = g.id', 'left');
            $this->db->join('shift_details h', 'h.shift_id = g.id', 'left');
            $this->db->where('b.deleted', 0);
            $this->db->like('b.status', $filter_status_employee);
            $this->db->like('b.id', $filter_employee);
            $this->db->like('c.id', $filter_division);
            $this->db->like('d.id', $filter_departement);
            $this->db->like('e.id', $filter_departement_sub);
            $this->db->group_by('b.id');
            $this->db->order_by('d.name', 'ASC');
            $this->db->order_by('e.name', 'ASC');
            $this->db->order_by('b.name', 'ASC');
            $records = $this->db->get()->result_array();

            $no = 1;
            foreach ($records as $data) {
                $start = strtotime($filter_from);
                $finish = strtotime($filter_to);

                //Calendars
                $this->db->select('trans_date, description');
                $this->db->from('calendars');
                $this->db->where("trans_date between '$filter_from' and '$filter_to'");
                $holidays = $this->db->get()->result_array();
                $arrHoliday = [];
                foreach ($holidays as $holiday) {
                    $arrHoliday[] = ["trans_date" => $holiday['trans_date'], "description" => $holiday['description']];
                }

                //Attandance and Overtime
                $this->db->select("date_in, time_in, date_out, time_out");
                $this->db->from('attandances');
                $this->db->where("date_in between '$filter_from' and '$filter_to'");
                $this->db->where('number', $data['employee_number']);
                $this->db->order_by('date_in', 'asc');
                $attandances = $this->db->get()->result_array();
                $arrAttandance = [];
                foreach ($attandances as $attandance) {
                    $arrAttandance[] = ["date_in" => $attandance['date_in'], "time_in" => $attandance['time_in'], "date_out" => $attandance['date_out'], "time_out" => $attandance['time_out']];
                }

                $arrAbsence = [];
                foreach ($arrAttandance as $arrAB) {
                    $arrAbsence[] = $arrAB['date_in'];
                }

                //Shifts
                $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, c.name, d.name as shift_name");
                $this->db->from('shift_employees b');
                $this->db->join('shifts c', 'c.id = b.shift_id');
                $this->db->join('shift_details d', 'd.shift_id = c.id');
                $this->db->where('b.employee_id', $data['employee_id']);
                $shifts = $this->db->get()->result_array();
                $arrShift = [];
                foreach ($shifts as $shift) {
                    $arrShift[] = $shift['start'];
                }

                //PERMIT
                $this->db->select("a.*, c.name as reason_name, d.name as permit_name");
                $this->db->from('permits a');
                $this->db->join('reasons c', 'a.reason_id = c.id');
                $this->db->join('permit_types d', 'a.permit_type_id = d.id');
                $this->db->where('a.employee_id', $data['employee_id']);
                $this->db->where("a.permit_date between '$filter_from' and '$filter_to'");
                $permits = $this->db->get()->result_array();
                $arrPermit = [];
                foreach ($permits as $permit) {
                    $arrPermit[] = ["permit_date" => $permit['permit_date'], "permit_name" => $permit['permit_name'], "note" => $permit['note']];
                }

                //Resignation
                $this->db->select('*');
                $this->db->from('resignations');
                $this->db->where('employee_id', $data['employee_id']);
                $this->db->where("(resign_date between '$filter_from' and '$filter_to' or resign_date < '$filter_from')");
                $resignation = $this->db->get()->row();

                //Change Day Start
                $this->db->select("*");
                $this->db->from('change_days');
                $this->db->where('employee_id', $data['employee_id']);
                $this->db->where("(start between '$filter_from' and '$filter_to' or end between '$filter_from' and '$filter_to')");
                $change_days = $this->db->get()->result_array();
                $arrChangeDay = [];
                foreach ($change_days as $change_day) {
                    $arrChangeDay[] = ["start" => $change_day['start'], "end" => $change_day['end'], "remarks" => $change_day['remarks']];
                }
                
                $permit = "";
                $time_in = "";
                $time_out = "";
                $status = "";
                $remarks = "";
                for ($i = $start; $i <= $finish; $i += (60 * 60 * 24)) {
                    $working_date = date('Y-m-d', $i);

                    if (@$data['days'] == "5") {
                        //sabtu dan minggu libur
                        if (date('w', $i) !== '0' && date('w', $i) !== '6') {
                            if(!in_array($working_date, $arrAbsence)){
                                $status = "ABSENCE";
                                $remarks = "";
                            }

                            if (!empty($resignation)) {
                                $status = "RESIGN";
                                $remarks = $resignation->remarks;
                            }

                            if (strtotime($data['date_sign']) > strtotime($working_date)) {
                                $status = "NOT JOIN YET";
                                $remarks = "";
                            }

                            if (empty($arrShift)) {
                                $status = "UN SETTING";
                                $remarks = "Check Shift Employee";
                            }

                            foreach ($arrHoliday as $arrH) {
                                if ($working_date == $arrH['trans_date']) {
                                    $status = "HOLIDAY";
                                    $remarks = $arrH['description'];
                                }
                            }

                            foreach ($arrPermit as $arrP) {
                                if ($working_date == $arrP['permit_date']) {
                                    $status = "PERMIT";
                                    $permit = $arrP['permit_name'];
                                    $remarks = $arrP['note'];
                                }
                            }

                            foreach ($arrChangeDay as $arrC) {
                                if ($working_date == $arrC['start']) {
                                    $status = "CHANGE DAY START";
                                    $remarks = $arrC['remarks'];
                                }elseif ($working_date == $arrC['end']) {
                                    $status = "CHANGE DAY END";
                                    $remarks = $arrC['remarks'];
                                }
                            }

                            foreach ($arrAttandance as $arrA) {
                                if ($working_date == $arrA['date_in']) {
                                    $date_in = $arrA['time_in'];
                                    
                                    $time_in = $arrA['time_in'];
                                    $time_out = $arrA['time_out'];
                                    $status = $this->cekStatusMasuk($arrShift, $date_in);
                                    $remarks = "";
                                }
                            }
                        } else {
                            foreach ($arrChangeDay as $arrC) {
                                if ($working_date == $arrC['start']) {
                                    $status = "CHANGE DAY START";
                                    $remarks = $arrC['remarks'];
                                }elseif ($working_date == $arrC['end']) {
                                    $status = "CHANGE DAY END";
                                    $remarks = $arrC['remarks'];
                                }else{
                                    $status = "WEEKEND";
                                    $remarks = "";
                                }
                            }

                            if (empty($arrChangeDay)){
                                $status = "WEEKEND";
                                $remarks = "";
                            }
                        }
                    } else {
                        if (date('w', $i) !== '0') {
                            foreach ($arrAttandance as $arrAB) {
                                $arrAbsence[] = $arrAB['date_in'];
                            }

                            if(!in_array($working_date, $arrAbsence)){
                                $status = "ABSENCE";
                                $remarks = "";
                            }
                            
                            if (!empty($resignation)) {
                                $status = "RESIGN";
                                $remarks = $resignation->remarks;
                            }

                            if (strtotime($data['date_sign']) > strtotime($working_date)) {
                                $status = "NOT JOIN YET";
                                $remarks = "";
                            }

                            if (empty($arrShift)) {
                                $status = "UN SETTING";
                                $remarks = "Check Shift Employee";
                            }

                            foreach ($arrHoliday as $arrH) {
                                if ($working_date == $arrH['trans_date']) {
                                    $status = "HOLIDAY";
                                    $remarks = $arrH['description'];
                                }
                            }

                            foreach ($arrPermit as $arrP) {
                                if ($working_date == $arrP['permit_date']) {
                                    $status = "PERMIT";
                                    $permit = $arrP['permit_name'];
                                    $remarks = $arrP['note'];
                                }
                            }

                            foreach ($arrChangeDay as $arrC) {
                                if ($working_date == $arrC['start']) {
                                    $status = "CHANGE DAY START";
                                    $remarks = $arrC['remarks'];
                                }elseif ($working_date == $arrC['end']) {
                                    $status = "CHANGE DAY END";
                                    $remarks = $arrC['remarks'];
                                }
                            }

                            foreach ($arrAttandance as $arrA) {
                                if ($working_date == $arrA['date_in']) {
                                    $date_in = $arrA['time_in'];
                                    
                                    $time_in = $arrA['time_in'];
                                    $time_out = $arrA['time_out'];
                                    $status = $this->cekStatusMasuk($arrShift, $date_in);
                                    $remarks = "";
                                }
                            }
                        } else {
                            foreach ($arrChangeDay as $arrC) {
                                if ($working_date == $arrC['start']) {
                                    $status = "CHANGE DAY START";
                                    $remarks = $arrC['remarks'];
                                }elseif ($working_date == $arrC['end']) {
                                    $status = "CHANGE DAY END";
                                    $remarks = $arrC['remarks'];
                                }else{
                                    $status = "WEEKEND";
                                    $remarks = "";
                                }
                            }

                            if (empty($arrChangeDay)){
                                $status = "WEEKEND";
                                $remarks = "";
                            }
                        }
                    }

                    $data2 = array(
                        $no,
                        $data['departement_name'],
                        $data['departement_sub_name'],
                        "'".$data['employee_number'],
                        $data['employee_name'],
                        $data['shift_name'],
                        date("d F Y", strtotime(@$working_date)),
                        @$time_in . ' - ' . @$time_out,
                        $status,
                        $permit,
                        $remarks
                    );

                    fputcsv($output, $data2);
                    $no++;

                    $permit = "";
                    $time_in = "";
                    $time_out = "";
                    $status = "";
                    $remarks = "";
                }
            }
        }
    }

    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=attandance_days_$format.xls");
        }

        if ($this->input->get()) {
            $filter_from = $this->input->get('filter_from');
            $filter_to = $this->input->get('filter_to');
            $filter_division = $this->input->get('filter_division');
            $filter_departement = $this->input->get('filter_departement');
            $filter_departement_sub = $this->input->get('filter_departement_sub');
            $filter_employee = $this->input->get('filter_employee');
            $filter_permit_type = $this->input->get('filter_permit_type');
            $filter_status_in = $this->input->get('filter_status_in');
            $filter_status = $this->input->get('filter_status');
            $filter_status_employee = $this->input->get('filter_status_employee');

            $this->db->select('b.id as employee_id, b.number as employee_number, b.name as employee_name, c.name as division_name, 
            d.name as departement_name, e.name as departement_sub_name, g.name as shift_name, h.days, b.date_sign');
            $this->db->from('employees b');
            $this->db->join('divisions c', 'b.division_id = c.id');
            $this->db->join('departements d', 'b.departement_id = d.id');
            $this->db->join('departement_subs e', 'b.departement_sub_id = e.id');
            $this->db->join('shift_employees f', 'b.id = f.employee_id', 'left');
            $this->db->join('shifts g', 'f.shift_id = g.id', 'left');
            $this->db->join('shift_details h', 'h.shift_id = g.id', 'left');
            $this->db->where('b.deleted', 0);
            $this->db->like('b.status', $filter_status_employee);
            $this->db->like('b.id', $filter_employee);
            $this->db->like('c.id', $filter_division);
            $this->db->like('d.id', $filter_departement);
            $this->db->like('e.id', $filter_departement_sub);
            $this->db->group_by('b.id');
            $this->db->order_by('d.name', 'ASC');
            $this->db->order_by('e.name', 'ASC');
            $this->db->order_by('b.name', 'ASC');
            $records = $this->db->get()->result_array();

            //Config
            $this->db->select('*');
            $this->db->from('config');
            $config = $this->db->get()->row();

            echo '<html><head><title>Print Data</title></head><style>body {font-family: Arial, Helvetica, sans-serif;}#customers {border-collapse: collapse;width: 100%;font-size: 10px;}#customers td, #customers th {border: 1px solid #ddd;padding: 2px;}#customers tr:nth-child(even){background-color: #f2f2f2;}#customers tr:hover {background-color: #ddd;}#customers th {padding-top: 2px;padding-bottom: 2px;text-align: left;color: black;}</style>
            <style> .str{ mso-number-format:\@; } </style>
            <body>
            <center>
                <div style="float: left; font-size: 12px; text-align: left;">
                    <table style="width: 100%;">
                        <tr>
                            <td width="50" style="font-size: 12px; vertical-align: top; text-align: center; vertical-align:jus margin-right:10px;">
                                <img src="' . $config->favicon . '" width="30">
                            </td>
                            <td style="font-size: 14px; text-align: left; margin:2px;">
                                <b>' . $config->name . '</b><br>
                                <small>' . $config->description . '</small>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="float: right; font-size: 12px; text-align: right;">
                    Print Date ' . date("d M Y H:i:s") . ' <br>
                    Print By ' . $this->session->username . '  
                </div>
            </center><br><br><br>
            <center>
                <h3 style="margin:0;">Report Attandance Days</h3>
                <span>Period ' . $filter_from . ' to ' . $filter_to . '</span>
            </center>
            <br>
            
            <table id="customers" border="1">
                <tr>
                    <th width="20">No</th>
                    <th>Departement</th>
                    <th>Departement Sub</th>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Shift</th>
                    <th>Trans Date</th>
                    <th>Time In/Out</th>
                    <th>Status</th>
                    <th>Permit</th>
                    <th>Description</th>
                </tr>';
            $no = 1;

            $start = strtotime($filter_from);
            $finish = strtotime($filter_to);

            //Calendars
            $this->db->select('trans_date, description');
            $this->db->from('calendars');
            $this->db->where("trans_date between '$filter_from' and '$filter_to'");
            $holidays = $this->db->get()->result_array();
            $arrHoliday = [];
            foreach ($holidays as $holiday) {
                $arrHoliday[] = ["trans_date" => $holiday['trans_date'], "description" => $holiday['description']];
            }

            foreach ($records as $data) {
                //Attandance and Overtime
                $this->db->select("date_in, time_in, date_out, time_out");
                $this->db->from('attandances');
                $this->db->where("date_in between '$filter_from' and '$filter_to'");
                $this->db->where('number', $data['employee_number']);
                $this->db->order_by('date_in', 'asc');
                $attandances = $this->db->get()->result_array();
                $arrAttandance = [];
                foreach ($attandances as $attandance) {
                    $arrAttandance[] = ["date_in" => $attandance['date_in'], "time_in" => $attandance['time_in'], "date_out" => $attandance['date_out'], "time_out" => $attandance['time_out']];
                }

                $arrAbsence = [];
                foreach ($arrAttandance as $arrAB) {
                    $arrAbsence[] = $arrAB['date_in'];
                }

                //Shifts
                $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, c.name, d.name as shift_name");
                $this->db->from('shift_employees b');
                $this->db->join('shifts c', 'c.id = b.shift_id');
                $this->db->join('shift_details d', 'd.shift_id = c.id');
                $this->db->where('b.employee_id', $data['employee_id']);
                $shifts = $this->db->get()->result_array();
                $arrShift = [];
                foreach ($shifts as $shift) {
                    $arrShift[] = $shift['start'];
                }

                //PERMIT
                $this->db->select("a.*, c.name as reason_name, d.name as permit_name");
                $this->db->from('permits a');
                $this->db->join('reasons c', 'a.reason_id = c.id');
                $this->db->join('permit_types d', 'a.permit_type_id = d.id');
                $this->db->where('a.employee_id', $data['employee_id']);
                $this->db->where("a.permit_date between '$filter_from' and '$filter_to'");
                $permits = $this->db->get()->result_array();
                $arrPermit = [];
                foreach ($permits as $permit) {
                    $arrPermit[] = ["permit_date" => $permit['permit_date'], "permit_name" => $permit['permit_name'], "note" => $permit['note']];
                }

                //Resignation
                $this->db->select('*');
                $this->db->from('resignations');
                $this->db->where('employee_id', $data['employee_id']);
                $this->db->where("(resign_date between '$filter_from' and '$filter_to' or resign_date < '$filter_from')");
                $resignation = $this->db->get()->row();

                //Change Day Start
                $this->db->select("*");
                $this->db->from('change_days');
                $this->db->where('employee_id', $data['employee_id']);
                $this->db->where("(start between '$filter_from' and '$filter_to' or end between '$filter_from' and '$filter_to')");
                $change_days = $this->db->get()->result_array();
                $arrChangeDay = [];
                foreach ($change_days as $change_day) {
                    $arrChangeDay[] = ["start" => $change_day['start'], "end" => $change_day['end'], "remarks" => $change_day['remarks']];
                }
                
                $permit = "";
                $time_in = "";
                $time_out = "";
                $status = "";
                $remarks = "";
                for ($i = $start; $i <= $finish; $i += (60 * 60 * 24)) {
                    $working_date = date('Y-m-d', $i);

                    if (@$data['days'] == "5") {
                        //sabtu dan minggu libur
                        if (date('w', $i) !== '0' && date('w', $i) !== '6') {
                            if(!in_array($working_date, $arrAbsence)){
                                $status = "ABSENCE";
                                $remarks = "";
                            }

                            if (!empty($resignation)) {
                                $status = "RESIGN";
                                $remarks = $resignation->remarks;
                            }

                            if (strtotime($data['date_sign']) > strtotime($working_date)) {
                                $status = "NOT JOIN YET";
                                $remarks = "";
                            }

                            if (empty($arrShift)) {
                                $status = "UN SETTING";
                                $remarks = "Check Shift Employee";
                            }

                            foreach ($arrHoliday as $arrH) {
                                if ($working_date == $arrH['trans_date']) {
                                    $status = "HOLIDAY";
                                    $remarks = $arrH['description'];
                                }
                            }

                            foreach ($arrPermit as $arrP) {
                                if ($working_date == $arrP['permit_date']) {
                                    $status = "PERMIT";
                                    $permit = $arrP['permit_name'];
                                    $remarks = $arrP['note'];
                                }
                            }

                            foreach ($arrChangeDay as $arrC) {
                                if ($working_date == $arrC['start']) {
                                    $status = "CHANGE DAY START";
                                    $remarks = $arrC['remarks'];
                                }elseif ($working_date == $arrC['end']) {
                                    $status = "CHANGE DAY END";
                                    $remarks = $arrC['remarks'];
                                }
                            }

                            foreach ($arrAttandance as $arrA) {
                                if ($working_date == $arrA['date_in']) {
                                    $date_in = $arrA['time_in'];
                                    
                                    $time_in = $arrA['time_in'];
                                    $time_out = $arrA['time_out'];
                                    $status = $this->cekStatusMasuk($arrShift, $date_in);
                                    $remarks = "";
                                }
                            }
                        } else {
                            foreach ($arrChangeDay as $arrC) {
                                if ($working_date == $arrC['start']) {
                                    $status = "CHANGE DAY START";
                                    $remarks = $arrC['remarks'];
                                }elseif ($working_date == $arrC['end']) {
                                    $status = "CHANGE DAY END";
                                    $remarks = $arrC['remarks'];
                                }else{
                                    $status = "WEEKEND";
                                    $remarks = "";
                                }
                            }

                            if (empty($arrChangeDay)){
                                foreach ($arrAttandance as $arrA) {
                                    if ($working_date == $arrA['date_in']) {
                                        $date_in = $arrA['time_in'];
                                        
                                        $time_in = $arrA['time_in'];
                                        $time_out = $arrA['time_out'];
                                        $status = $this->cekStatusMasuk($arrShift, $date_in);
                                        $remarks = "";
                                    }else{
                                        $status = "WEEKEND";
                                        $remarks = "";
                                    }
                                }
                            }
                        }
                    } else {
                        if (date('w', $i) !== '0') {
                            foreach ($arrAttandance as $arrAB) {
                                $arrAbsence[] = $arrAB['date_in'];
                            }

                            if(!in_array($working_date, $arrAbsence)){
                                $status = "ABSENCE";
                                $remarks = "";
                            }
                            
                            if (!empty($resignation)) {
                                $status = "RESIGN";
                                $remarks = $resignation->remarks;
                            }

                            if (strtotime($data['date_sign']) > strtotime($working_date)) {
                                $status = "NOT JOIN YET";
                                $remarks = "";
                            }

                            if (empty($arrShift)) {
                                $status = "UN SETTING";
                                $remarks = "Check Shift Employee";
                            }

                            foreach ($arrHoliday as $arrH) {
                                if ($working_date == $arrH['trans_date']) {
                                    $status = "HOLIDAY";
                                    $remarks = $arrH['description'];
                                }
                            }

                            foreach ($arrPermit as $arrP) {
                                if ($working_date == $arrP['permit_date']) {
                                    $status = "PERMIT";
                                    $permit = $arrP['permit_name'];
                                    $remarks = $arrP['note'];
                                }
                            }

                            foreach ($arrChangeDay as $arrC) {
                                if ($working_date == $arrC['start']) {
                                    $status = "CHANGE DAY START";
                                    $remarks = $arrC['remarks'];
                                }elseif ($working_date == $arrC['end']) {
                                    $status = "CHANGE DAY END";
                                    $remarks = $arrC['remarks'];
                                }
                            }

                            foreach ($arrAttandance as $arrA) {
                                if ($working_date == $arrA['date_in']) {
                                    $date_in = $arrA['time_in'];
                                    
                                    $time_in = $arrA['time_in'];
                                    $time_out = $arrA['time_out'];
                                    $status = $this->cekStatusMasuk($arrShift, $date_in);
                                    $remarks = "";
                                }
                            }
                        } else {
                            foreach ($arrChangeDay as $arrC) {
                                if ($working_date == $arrC['start']) {
                                    $status = "CHANGE DAY START";
                                    $remarks = $arrC['remarks'];
                                }elseif ($working_date == $arrC['end']) {
                                    $status = "CHANGE DAY END";
                                    $remarks = $arrC['remarks'];
                                }else{
                                    $status = "WEEKEND";
                                    $remarks = "";
                                }
                            }

                            if (empty($arrChangeDay)){
                                foreach ($arrAttandance as $arrA) {
                                    if ($working_date == $arrA['date_in']) {
                                        $date_in = $arrA['time_in'];
                                        
                                        $time_in = $arrA['time_in'];
                                        $time_out = $arrA['time_out'];
                                        $status = $this->cekStatusMasuk($arrShift, $date_in);
                                        $remarks = "";
                                    }else{
                                        $status = "WEEKEND";
                                        $remarks = "";
                                    }
                                }
                            }
                        }
                    }

                    if ($status == "WEEKEND") {
                        $style = "style='color:red; font-weight:bold;'";
                    } elseif ($status == "PERMIT") {
                        $style = "style='color:blue; font-weight:bold;'";
                    } elseif ($status == "CHANGE DAY START" || $status == "CHANGE DAY END") {
                        $style = "style='color:gray; font-weight:bold;'";
                    } elseif ($status == "LATE") {
                        $style = "style='color:orange; font-weight:bold;'";
                    } elseif ($status == "ON TIME") {
                        $style = "style='color:green; font-weight:bold;'";
                    } elseif ($status == "NOT JOIN YET") {
                        $style = "style='color:gray; font-weight:bold;'";
                    } else {
                        $style = "style='color:red; font-weight:bold;'";
                    }

                    $html =     '<tr>
                                    <td>' . $no . '</td>
                                    <td>' . $data['departement_name'] . '</td>
                                    <td>' . $data['departement_sub_name'] . '</td>
                                    <td style="mso-number-format:\@;">' . $data['employee_number'] . '</td>
                                    <td>' . $data['employee_name'] . '</td>
                                    <td>' . $data['shift_name'] . '</td>
                                    <td>' . date("d F Y", strtotime(@$working_date)) . '</td>
                                    <td>' . @$time_in . ' - ' . @$time_out . '</td>
                                    <td ' . $style . '>' . $status . '</td>
                                    <td ' . $style . '>' . @$permit . '</td>
                                    <td>' . $remarks . '</td>
                                </tr>';

                    if ($status == $filter_status) {
                        echo  $html;      
                        $no++;
                    } else if (@$permit->permit_name != "" && @$permit->permit_name == @strtoupper($filter_permit_type)) {
                        echo  $html;      
                        $no++;
                    } else if ($filter_status_in == "INOUT" && !empty(@$attandance->time_in) && !empty(@$attandance->time_out)){
                        echo  $html;      
                        $no++;
                    } else if ($filter_status_in == "IN" && @$attandance->time_in != null && @$attandance->time_out == null){
                        echo  $html;      
                        $no++;
                    } else if ($filter_status_in == "OUT" && empty(@$attandance->time_in) && !empty(@$attandance->time_out)){
                        echo  $html;      
                        $no++;
                    } else if($filter_status == "" && $filter_permit_type == "" && $filter_status_in == "") {
                        echo  $html;      
                        $no++;
                    }

                    $permit = "";
                    $time_in = "";
                    $time_out = "";
                    $status = "";
                    $remarks = "";
                }
            }

            echo '</table></body></html>';
        }
    }
}
