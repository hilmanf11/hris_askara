<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Detail_payroll_report extends CI_Controller
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
            $this->load->view('report/detail_payroll_report');
        } else {
            redirect('error_access');
        }
    }

    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=detail_report_$format.xls");
        }

        if ($this->input->get()) {
            $filter_from = $this->input->get('filter_from');
            $filter_to = $this->input->get('filter_to');
            $filter_division = $this->input->get('filter_division');
            $filter_departement = $this->input->get('filter_departement');
            $filter_departement_sub = $this->input->get('filter_departement_sub');
            $filter_employee = $this->input->get('filter_employee');
            $filter_status_employee = $this->input->get('filter_status_employee');

            $group_harian = ["20221213000002", "20221213000003"];

            $this->db->select('b.id as employee_id, b.position_id, b.number as employee_number, b.name as employee_name, c.name as division_name, i.name as position_name, j.name as group_name, k.name as contract_name,
            d.name as departement_name, e.name as departement_sub_name, g.name as shift_name, h.days, b.date_sign, b.group_id');
            $this->db->from('employees b');
            $this->db->join('divisions c', 'b.division_id = c.id');
            $this->db->join('departements d', 'b.departement_id = d.id');
            $this->db->join('departement_subs e', 'b.departement_sub_id = e.id');
            $this->db->join('shift_employees f', 'b.id = f.employee_id', 'left');
            $this->db->join('shifts g', 'f.shift_id = g.id', 'left');
            $this->db->join('shift_details h', 'h.shift_id = g.id', 'left');
            $this->db->join('positions i', 'b.position_id = i.id', 'left');
            $this->db->join('groups j', 'b.group_id = j.id', 'left');
            $this->db->join('contracts k', 'b.contract_id = k.id', 'left');
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
                <h3 style="margin:0;">Absensi</h3>
                <span>Period ' . $filter_from . ' to ' . $filter_to . '</span>
            </center>
            <br>
            
            <table id="customers" border="1">
                <tr>
                    <th style="text-align:center;" width="20">No</th>
                    <th style="text-align:center;">Employee ID</th>
                    <th style="text-align:center;">Full Name</th>
                    <th style="text-align:center;">Date</th>
                    <th style="text-align:center;">Shift</th>
                    <th style="text-align:center;">Schedule In</th>
                    <th style="text-align:center;">Schedule Out</th>
                    <th style="text-align:center;">Attendance Code</th>
                    <th style="text-align:center;">Check In</th>
                    <th style="text-align:center;">Check Out</th>
                    <th style="text-align:center;">Overtime</th>
                    <th style="text-align:center;">Overtime Break</th>
                    <th style="text-align:center;">Total Round</th>
                    <th style="text-align:center;">Division</th>
                    <th style="text-align:center;">Organization</th>
                    <th style="text-align:center;">Job Position</th>
                    <th style="text-align:center;">Job Level</th>
                    <th style="text-align:center;">Employment Status</th>
                    <th style="text-align:center;">Join Date</th>
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

            $dates = $this->dateRange($filter_from, $filter_to);
            //BOARD OF DIRECTOR, DIRECTOR, MANAGER, ASST. MANAGER, LEADER, STAFF
            foreach ($records as $data) {

                foreach ($dates as $date) {
                    $is_harian = in_array($data['group_id'], $group_harian);

                    //Resignation
                    $this->db->select('*');
                    $this->db->from('resignations');
                    $this->db->where('employee_id', $data['employee_id']);
                    $resignation = $this->db->get()->row();

                    if ($resignation && !empty($resignation->resign_date)) {

                        // resign sebelum periode → skip
                        if ($resignation->resign_date <= $date) {
                            continue;
                        }
                    }
                    $this->db->select("date_in, time_in, date_out, time_out");
                    $this->db->from('attandances');
                    $this->db->where("date_in", $date);
                    $this->db->where('number', $data['employee_number']);
                    $this->db->order_by('date_in', 'asc');
                    $attandance = $this->db->get()->row();

                    $this->db->select("b.number as attendance_code");
                    $this->db->from('permits a');
                    $this->db->join('permit_types b', 'a.permit_type_id = b.id', 'left');
                    $this->db->where("permit_date", $date);
                    $this->db->where('employee_id', $data['employee_id']);
                    $this->db->group_start()
                      ->where('a.approved_to IS NULL', null, false)
                      ->or_where('a.approved_to', '')
                    ->group_end();
                    $this->db->order_by('a.permit_date', 'asc');
                    $permit = $this->db->get()->row();


                    
                    $start = strtotime($date);
                    $dayOfWeek = date('w', $start);
                    if ($dayOfWeek == 0) {
                        $dayOfWeek = 7;
                    }
                    
                    $time_in = '';
                    $time_out = '';
                    $shift_name = '';
                    $shift_start = '';
                    $shift_end = '';
                    $attendance_code = '';
                    if ($attandance) {
                        $time_in = $attandance->time_in;
                        $time_out = $attandance->time_out;
                        $attendance_code = 'H';
                    }

                    if ($permit) {
                        $attendance_code = $permit->attendance_code;
                    }

                    $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, c.name, d.name as shift_name");
                    $this->db->from('shift_employees b');
                    $this->db->join('shifts c', 'c.id = b.shift_id');
                    $this->db->join('shift_details d', 'd.shift_id = c.id');
                    $this->db->where('b.employee_id', $data['employee_id']);
                    $this->db->where('d.days >=', $dayOfWeek);
                    if ($dayOfWeek == 6) {
                        $this->db->where('d.working', 'WEEKEND');
                    } else if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                        $this->db->where('d.working', 'WEEKDAY');
                    }
                    $shift = $this->db->get()->row();

                    $overtime = $this->computeOvertimes($data['employee_id'], $date);


                    if ($shift) {
                        $shift_name = $shift->name;
                        $shift_start = $shift->start;
                        $shift_end = $shift->end;
                    } else {
                        if ($dayOfWeek == 7) {
                            $shift_name = 'Minggu';
                        } else if ($dayOfWeek == 6) {
                            $shift_name = 'Sabtu';
                        }
                        $shift_start = '00:00:00';
                        $shift_end = '00:00:00';
                    }

                    if (!$is_harian) {
                        $this->db->select('trans_date, description');
                        $this->db->from('calendars');
                        $this->db->where("trans_date",$date);
                        $this->db->where("permit",1);
                        $holiday_yes = $this->db->get()->row();

                        if ($holiday_yes) {
                            $shift_name = $holiday_yes->description;
                            $shift_start = '00:00:00';
                            $shift_end = '00:00:00';
                        }
                    }
                    $this->db->select('trans_date, description');
                    $this->db->from('calendars');
                    $this->db->where("trans_date",$date);
                    $this->db->where("permit",0);
                    $holiday_no = $this->db->get()->row();
                    if ($holiday_no) {
                        $shift_name = $holiday_no->description;
                        $shift_start = '00:00:00';
                        $shift_end = '00:00:00';
                    }

                    $html = '<tr>
                                <td style="text-align:center;">' . $no . '</td>
                                <td style="mso-number-format:\@;">' . $data['employee_number'] . '</td>
                                <td>' . $data['employee_name'] . '</td>
                                <td>' . $date . '</td>
                                <td>' . $shift_name . '</td>
                                <td>' . $shift_start . '</td>
                                <td>' . $shift_end . '</td>
                                <td style="text-align:center;">' . $attendance_code . '</td>
                                <td>' . @$time_in . '</td>
                                <td>' . @$time_out . '</td>
                                <td>' . $overtime['plan'] . '</td>
                                <td>' . $overtime['break'] . '</td>
                                <td>' . $overtime['actual'] . '</td>
                                <td>' . $data['division_name'] . '</td>
                                <td>' . $data['departement_name'] . '</td>
                                <td>' . $data['position_name'] . '</td>
                                <td>' . $data['group_name'] . '</td>
                                <td>' . $data['contract_name'] . '</td>
                                <td>' . $data['date_sign'] . '</td>
                            </tr>';
                    
                    echo  $html;      
                    $no++;
                }
            }

            echo '</table></body></html>';
        }
    }

    private function dateRange($start, $end)
    {
        $dates = [];
        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            (new DateTime($end))->modify('+1 day')
        );

        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    private function computeOvertimes($employeeId, $trans_date): array
    {
        // Ambil semua overtime
        $overtimes = $this->db
            ->select('duration_hour, break_hour')
            ->where('employee_id', $employeeId)
            ->where('trans_date', $trans_date)
            ->group_start()
              ->where('approved_to IS NULL', null, false)
              ->or_where('approved_to', '')
            ->group_end()
            ->get('overtimes')
            ->row();

        $duration = $overtimes->duration_hour ?? 0;
        $break    = $overtimes->break_hour ?? 0;
        $plan = $duration + $break;
        $result = [
            'plan'  => $plan,
            'break'  => $break,
            'actual' => $duration,
        ];

        return $result;
    }


}
