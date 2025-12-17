<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Summary_payroll_report extends CI_Controller
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
            $username = $this->session->username;

            $data['button'] = $this->getbutton($this->id_menu());
            $data['user'] = $this->crud->read("users", [], ["username" => $username]);

            $this->load->view('template/header', $data);
            $this->load->view('report/summary_payroll_report');
        } else {
            redirect('error_access');
        }
    }

    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=summary_payroll_report_$format.xls");
        }

        if ($this->input->get()) {
            $filter_from = $this->input->get('filter_from');
            $filter_to = $this->input->get('filter_to');
            $filter_division = $this->input->get('filter_division');
            $filter_departement = $this->input->get('filter_departement');
            $filter_departement_sub = $this->input->get('filter_departement_sub');
            $filter_employee = $this->input->get('filter_employee');

            $header_html = '<html><head><title>Print Data</title></head><style>body {font-family: Arial, Helvetica, sans-serif;}#customers {border-collapse: collapse;width: 100%;font-size: 10px;}#customers td, #customers th {border: 1px solid #ddd;padding: 2px;}#customers tr:nth-child(even){background-color: #f2f2f2;}#customers tr:hover {background-color: #ddd;}#customers th {padding-top: 2px;padding-bottom: 2px;text-align: left;color: black;}#ttd {border-collapse: collapse;width: 100%;font-size: 10px;}#ttd td, #ttd th {padding: 2px;}</style>
            <style> .str{ mso-number-format:\@; } </style>
            <body>';

            $this->db->select("a.division_id, a.departement_id, a.departement_sub_id, c.name as division_name, d.name as departement_name, e.name as departement_sub_name");
            $this->db->from('divisions c');
            $this->db->join('departements d', 'd.division_id = c.id');
            $this->db->join('departement_subs e', 'e.departement_id = d.id');
            $this->db->join('employees a', 'a.division_id = c.id and a.departement_id = d.id and a.departement_sub_id = e.id');
            $this->db->where('a.status', 0);
            $this->db->like('a.division_id', $filter_division);
            $this->db->like('a.departement_id', $filter_departement);
            $this->db->like('a.departement_sub_id', $filter_departement_sub);
            $this->db->like('a.id', $filter_employee);
            $this->db->group_by('c.id');
            $this->db->order_by('e.name', 'asc');
            $records = $this->db->get()->result_array();

            //Config
            $this->db->select('*');
            $this->db->from('config');
            $config = $this->db->get()->row();

            $start = strtotime($filter_from);
            $finish = strtotime($filter_to);

            $no = 1;
            $tot_wd        = 0;
            $tot_wd_ot     = 0;
            $tot_sat       = 0;
            $tot_sun       = 0;
            $tot_holiday   = 0;
            $tot_pot_hadir = 0;
            $tot_pot_absen = 0;
            $tot_pinjaman  = 0;
            $tot_sakit     = 0;
            $tot_cuti      = 0;
            $tot_absen     = 0;
            $tot_izin      = 0;


            foreach ($records as $record) {
                $html = '<div style="page-break-after:always;">
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
                    <h3 style="margin:0;"><b>' . $record['division_name'] . '</b></h3>
                    <h3 style="margin:0;">ABSEN MANAGER</h3>
                    <p style="margin:0;">Period ' . $filter_from . ' to ' . $filter_to . '</p>
                    <br><br>
                </center>
                <br>

                <table id="customers" border="1">
                <tr>
                    <th width="20" rowspan="2">No</th>
                    <th style="text-align:center;" rowspan="2">NRP</th>
                    <th style="text-align:center;" rowspan="2">Nama</th>
                    <th style="text-align:center;" rowspan="2">Dept</th>
                    <th style="text-align:center;" rowspan="2">Jabatan</th>
                    <th style="text-align:center;" rowspan="2">Gol</th>
                    <th style="text-align:center;" rowspan="2">Status</th>
                    <th style="text-align:center;" rowspan="2">HKW</th>
                    <th style="text-align:center;" rowspan="2">Hadir</th>
                    <th style="text-align:center;" colspan="4">Lembur karyawan</th>
                    <th style="text-align:center;" colspan="3">Potongan Karyawan</th>
                    <th style="text-align:center;" colspan="4">Absensi</th>
                </tr>
                <tr>
                    <th>Hkw</th>
                    <th>Sabtu</th>
                    <th>Minggu</th>
                    <th>Nas</th>
                    <th>Pot Hadir</th>
                    <th>Pot Absen</th>
                    <th>Pinjaman</th>
                    <th>S</th>
                    <th>CT</th>
                    <th>A</th>
                    <th>I</th>
                </tr>';

                //Ambil Employee berdasarkan departement dan divisionnnya
                $this->db->select("
                    a.id as employee_id, 
                    a.number, a.name, 
                    a.date_sign,
                    a.division_id, 
                    a.departement_id, 
                    a.departement_sub_id, 
                    c.name as division_name, 
                    d.name as departement_name, 
                    e.name as departement_sub_name, 
                    g.name as shift_name,
                    h.days,
                    i.name as position_name,
                    j.number as marital_number");
                $this->db->from('employees a');
                $this->db->join('divisions c', 'a.division_id = c.id');
                $this->db->join('departements d', 'a.departement_id = d.id');
                $this->db->join('departement_subs e', 'a.departement_sub_id = e.id');
                $this->db->join('shift_employees f', 'a.id = f.employee_id');
                $this->db->join('shifts g', 'f.shift_id = g.id');
                $this->db->join('shift_details h', 'h.shift_id = g.id');
                $this->db->join('positions i', 'a.position_id = i.id', 'left');
                $this->db->join('maritals j', 'a.marital_id = j.id', 'left');
                $this->db->where('a.status', '0');
                $this->db->where('a.division_id', $record['division_id']);
                $this->db->like('a.departement_id', $filter_departement);
                $this->db->like('a.departement_sub_id', $filter_departement_sub);
                $this->db->like('a.id', $filter_employee);
                $this->db->group_by('a.number');
                $this->db->order_by('d.name','a.name',  'asc');
                $employees = $this->db->get()->result_array();
                
                $no = 1;
                foreach ($employees as $data) {
                    $hkw = $this->getHkw($filter_from, $filter_to, $data['days'], $data['number'], $data['employee_id']);

                    //Overtime
                    $overtime  = $this->computeOvertimes($data['employee_id'], $filter_from, $filter_to);
                    $overtime_weekday           = $overtime['overtime_weekday'];
                    $overtime_holiday           = $overtime['overtime_holiday'];
                    $overtime_saturday          = $overtime['overtime_saturday'];
                    $overtime_sunday            = $overtime['overtime_sunday'];

                    //Permit
                    $permit = $this->getPermit($filter_from, $filter_to, $data['employee_id']);
                    $pot_hadir = $hkw['hkw']-$hkw['wd']-$permit['total'];
                    $pot_absen = $hkw['absen'] + $permit['izin'];

                    $html .= '<tr>
                                <td>' . $no . '</td>
                                <td style="mso-number-format:\@;">' . $data['number'] . '</td>
                                <td>' . $data['name'] . '</td>
                                <td>' . $data['departement_name'] . '</td>
                                <td>' . $data['position_name'] . '</td>
                                <td></td>
                                <td>' . $data['marital_number'] . '</td>
                                <td>' . $hkw['hkw'] . '</td>
                                <td>' . $hkw['wd'] . '</td>
                                <td>' . $overtime_weekday . '</td>
                                <td>' . $overtime_saturday . '</td>
                                <td>' . $overtime_sunday . '</td>
                                <td>' . $overtime_holiday . '</td>
                                <td>' . $pot_hadir . '</td>
                                <td>' . $pot_absen . '</td>
                                <td>' . 0 . '</td>
                                <td>' . $permit['sakit'] . '</td>
                                <td>' . $permit['cuti'] . '</td>
                                <td>' . $hkw['absen'] . '</td>
                                <td>' . $permit['izin'] . '</td>                            
                                ';


                    $html .= '</tr>';
                    $no++;
                    $wd        = (int) ($hkw['wd'] ?? 0);
                    $wd_ot     = (int) ($overtime_weekday ?? 0);
                    $sat       = (int) ($overtime_saturday ?? 0);
                    $sun       = (int) ($overtime_sunday ?? 0);
                    $holiday   = (int) ($overtime_holiday ?? 0);
                    $potHadir  = (int) ($pot_hadir ?? 0);
                    $potAbsen  = (int) ($pot_absen ?? 0);
                    $pinjaman  = 0;

                    $sakit     = (int) ($permit['sakit'] ?? 0);
                    $cuti      = (int) ($permit['cuti'] ?? 0);
                    $absen     = (int) ($hkw['absen'] ?? 0);
                    $izin      = (int) ($permit['izin'] ?? 0);

                    /* JUMLAHKAN */
                    $tot_wd        += $wd;
                    $tot_wd_ot     += $wd_ot;
                    $tot_sat       += $sat;
                    $tot_sun       += $sun;
                    $tot_holiday   += $holiday;
                    $tot_pot_hadir += $potHadir;
                    $tot_pot_absen += $potAbsen;
                    $tot_pinjaman  += $pinjaman;
                    $tot_sakit     += $sakit;
                    $tot_cuti      += $cuti;
                    $tot_absen     += $absen;
                    $tot_izin      += $izin;
                    
                }
                $html .= '<tr style="font-weight:bold;">
                    <td colspan="8" align="center">TOTAL</td>

                    <td>' . $tot_wd . '</td>
                    <td>' . $tot_wd_ot . '</td>
                    <td>' . $tot_sat . '</td>
                    <td>' . $tot_sun . '</td>
                    <td>' . $tot_holiday . '</td>
                    <td>' . $tot_pot_hadir . '</td>
                    <td>' . $tot_pot_absen . '</td>
                    <td>' . $tot_pinjaman . '</td>
                    <td>' . $tot_sakit . '</td>
                    <td>' . $tot_cuti . '</td>
                    <td>' . $tot_absen . '</td>
                    <td>' . $tot_izin . '</td>
                </tr>';

                $html .= '</table><br><br>';

                $html .= '
                <table id="ttd">
                    <tr>
                        <th style="width: 62%;" colspan="17"></th>
                        <th style="width: 10%; border: 1px solid #ddd;">Disetujui,</th>
                        <th style="width: 10%; border: 1px solid #ddd;">Diperiksa,</th>
                        <th style="width: 10%; border: 1px solid #ddd;">Dibuat,</th>
                    </tr>
                    <tr>
                        <th style="width: 62%; style="height: 65px" colspan="17"></th>
                        <th style="height: 65px; width: 13%; border: 1px solid #ddd;"></th>
                        <th style="height: 65px; width: 13%; border: 1px solid #ddd;"></th>
                        <th style="height: 65px; width: 13%; border: 1px solid #ddd;"></th>
                    </tr>
                    <tr>
                        <th style="width: 62%;" colspan="17"></th>
                        <th style="width: 10%; border: 1px solid #ddd;">Askara P. Harsono</th>
                        <th style="width: 10%; border: 1px solid #ddd;">Ariananda Desmaria</th>
                        <th style="width: 10%; border: 1px solid #ddd;">Nafisah A.</th>
                    </tr>
                ';
                $html .= '</div><br><br>';

                echo $header_html . $html;
            }
            echo '</body></html>';
        }
    }

    private function getHkw($filter_from, $filter_to, $days, $employee_number, $employee_id)
    {
        $start = strtotime($filter_from);
        $finish = strtotime($filter_to);

        //Looping tanggal awal sampai tanggal akhir
        $weekday = [];
        $weekday2 = [];
        $weekend = [];
        $weekend2 = [];

        for ($i = $start; $i <= $finish; $i += (60 * 60 * 24)) {
            //Jika tanggal yg di looping bukan hari sabtu dan minggu
            if (date('w', $i) !== '0' && date('w', $i) !== '6') {
                //Hari kerja
                $weekday[] = date('Y-m-d', $i);
            } else {
                //Hari libur
                $weekend[] = date('Y-m-d', $i);
            }

            if (date('w', $i) !== '0') {
                //Hari kerja
                $weekday2[] = date('Y-m-d', $i);
            } else {
                //Hari libur
                $weekend2[] = date('Y-m-d', $i);
            }
        }

        if ($days == "6") {
            $weekend_day = $weekend2;
            $weekday_day = $weekday2;
        } else {
            $weekend_day = $weekend;
            $weekday_day = $weekday;
        }

        //Tanggal merah di master calendar
        $this->db->select('trans_date');
        $this->db->from('calendars');
        $this->db->where('trans_date >=', $filter_from);
        $this->db->where('trans_date <=', $filter_to);
        $this->db->where('description !=', "Weekend");
        if (count($weekend_day) > 0) {
            $this->db->where_not_in('trans_date', $weekend_day);
        }
        $calendar = $this->db->get()->result_array();
        $calendar_amount = empty($calendar) ? 0 : count($calendar);
        $w_calendars = array();
        foreach ($calendar as $cal) {
            $w_calendars[] = $cal['trans_date'];
        }

        $hkw = (@count($weekday_day) - @$calendar_amount);

        //Attandances
        $tomorow = date('Y-m-d', strtotime("+1 day", strtotime($filter_from)));
        $this->db->select("number, date_in");
        $this->db->from('attandances');
        $this->db->where('number', $employee_number);
        $this->db->where("((date_in >= '$filter_from' and date_in <= '$filter_to') or (date_out >= '$tomorow' and date_out <= '$filter_to'))");
        if (count($weekend_day) > 0) {
            $this->db->where_not_in('date_in', $weekend_day);
        }
        if (count($w_calendars) > 0) {
            $this->db->where_not_in('date_in', $w_calendars);
        }
        $this->db->group_by('date_in');
        $attandances = $this->db->get()->result_array();
        $attandance_amount = count($attandances);

        $calendar_date = "'" . implode("', '", $w_calendars) . "'";

        if(count($w_calendars) > 0){
            $calendar_where = "and a.permit_date not in ($calendar_date)";
        }else{
            $calendar_where = "";
        }

        $permit_date = "'" . implode("', '", $weekend_day) . "'";
        $permitAbsenceYes = $this->getPermitAbsence( $employee_id, $filter_from, $filter_to, $permit_date, $calendar_where,'YES');
        $arr_total_permit_absence = $permitAbsenceYes['arr_total_permit_absence'];

        $changeDays = $this->getChangeDays($employee_id, $filter_from, $filter_to, $weekday_day);
        $changeDays_amount = $changeDays['changeDays_amount'];
        
        $working_days = ($attandance_amount + $arr_total_permit_absence + $changeDays_amount);

        if ($working_days >= $hkw) {
            $wd = $hkw;
        } else {
            $wd = $working_days;
        }

        $permitAbsenceNo = $this->getPermitAbsence($employee_id, $filter_from, $filter_to, $permit_date, $calendar_where,'NO');
        $arr_total_permit_absence_no = $permitAbsenceNo['arr_total_permit_absence'];

        $absen = (@count($weekday_day) - @$calendar_amount - @$attandance_amount - @$changeDays_amount - @$arr_total_permit_absence_no - $arr_total_permit_absence);

        return ['hkw' => $hkw, 'wd' => $wd, 'absen' => $absen];
    }

    private function getPermitAbsence($employeeId, $from, $to, $permit_date, $calendar_where, $absence): array
    {
        $q_permit_absence = $this->db->query("SELECT b.number, b.name, COUNT(a.permit_date) as amount
                FROM permit_types b
                LEFT JOIN permits a ON a.permit_type_id = b.id and a.employee_id = '$employeeId' and a.permit_date >= '$from' and a.permit_date <= '$to' and a.permit_date not in ($permit_date) $calendar_where
                WHERE b.absence = '$absence' and (a.approved_to = '' or a.approved_to is null)
                GROUP BY b.id");

        $r_permit_absence = $q_permit_absence->result_array();
        $arr_total_permit_absence = 0;
        foreach ($r_permit_absence as $permit_data_absence) {
            $arr_total_permit_absence += $permit_data_absence['amount'];
        }
        return [
            'arr_total_permit_absence' => $arr_total_permit_absence
        ];
    }

    private function getChangeDays($employeeId, $from, $to, $weekday_day): array
    {
        $this->db->select("COUNT(*) as days");
        $this->db->from('change_days');
        $this->db->where('employee_id', $employeeId);
        $this->db->group_start()
            ->where('approved_to IS NULL', null, false)
            ->or_where('approved_to', '')
        ->group_end();
        $this->db->where("(start between '$from' and '$to' or end between '$from' and '$to')");
        if (count($weekday_day) > 0) {
            $this->db->where_in('end', $weekday_day);
        }
        $changeDays = $this->db->get()->row();
        $changeDays_amount = empty($changeDays->days) ? 0 : $changeDays->days;

        return [
            'changeDays_amount' => $changeDays_amount
        ];
    }

    private function computeOvertimes($employeeId, $from, $to): array
    {
        // Ambil semua overtime
        $overtimes = $this->db
            ->where('employee_id', $employeeId)
            ->where('trans_date >=', $from)
            ->where('trans_date <=', $to)
            ->group_start()
                ->where('approved_to IS NULL', null, false)
                ->or_where('approved_to', '')
            ->group_end()
            ->get('overtimes')
            ->result_array();

        // Ambil tanggal libur sekali saja
        $holidays = $this->db
            ->select('trans_date')
            ->where('trans_date >=', $from)
            ->where('trans_date <=', $to)
            ->get('calendars')
            ->result_array();

        // Convert ke array tanggal saja
        $holidayDates = array_column($holidays, 'trans_date');

        $result = [
            'overtime_weekday'  => 0,
            'overtime_holiday'  => 0,
            'overtime_saturday' => 0,
            'overtime_sunday'   => 0,
        ];

        foreach ($overtimes as $overtime) {
            $date = $overtime['trans_date'];
            $duration = $overtime['duration_hour'];

            // Cek libur nasional
            if (in_array($date, $holidayDates)) {
                $result['overtime_holiday'] += $duration;
                continue;
            }

            $dayOfWeek = date('w', strtotime($date)); // 0=Sunday, 6=Saturday

            switch ($dayOfWeek) {
                case 0:
                    $result['overtime_sunday'] += $duration;
                    break;
                case 6:
                    $result['overtime_saturday'] += $duration;
                    break;
                default:
                    $result['overtime_weekday'] += $duration;
            }
        }

        return $result;
    }

    private function getPermit($filter_from, $filter_to, $employee_id)
    {
        $result = [
            'izin'  => 0,
            'sakit' => 0,
            'cuti'  => 0,
            'total' => 0,
        ];

        $query = "
            SELECT b.number, COUNT(a.duration) AS total
            FROM permit_types b
            LEFT JOIN permits a 
                ON a.permit_type_id = b.id
                AND a.employee_id = ?
                AND a.permit_date BETWEEN ? AND ?
                AND (a.approved_to = '' OR a.approved_to IS NULL)
            GROUP BY b.number
        ";

        $permits = $this->db->query($query, [
            $employee_id,
            $filter_from,
            $filter_to
        ])->result_array();

        foreach ($permits as $row) {
            switch ($row['number']) {
                case 'I':
                    $result['izin'] = (int) $row['total'];
                    break;

                case 'S':
                    $result['sakit'] = (int) $row['total'];
                    break;

                case 'CT':
                case 'CTK':
                    $result['cuti'] += (int) $row['total'];
                    break;
            }
            $result['total'] += (int) $row['total'];
        }

        return $result;
    }


}
