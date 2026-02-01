<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Salary_slips extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->library('Tcpdf_lib');
        $this->load->model('crud');
        $this->load->model('mobile');
    }

    public function index()
    {
        show_error("Cannot Process your request");
    }

    public function print($api_key = "", $date_from, $date_to)
    {
        if ($api_key != "") {
            $pdf = new Tcpdf_lib();
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->AddPage();

            $html = '<p style="text-align:right;color:red;">*CONFIDENTIAL</p>';

            $user = $this->mobile->read("users", [], ["api_key" => $api_key]);

            if (!$user) {
                show_error("Cannot Process your request");
                return;
            }

            $employee_data = $this->crud->read('employees', [], ['number' => $user->number]);
            $employee = $employee_data->id;

            $time_to = strtotime($date_to);
            $time_from = strtotime($date_from);
            $period_to = date('Y-m', $time_to);
            $period_from = date('Y-m', $time_from);
            $payroll_cutoff = date('d M Y', $time_from) . ' - ' . date('d M Y', $time_to);

            $cut_off = $this->crud->read('cutoff', [], ['start' => $date_from, 'finish' => $date_to]);
            if (empty($cut_off)) {
                $pdf->Output('salary_slip_blank.pdf', 'D');
                return;
            }

            $this->db->distinct()->select("COUNT(trans_date) AS count")->from("calendars")->where("trans_date BETWEEN '$date_from' AND '$date_to'", NULL, FALSE);
            $count_national_day = intval($this->db->get()->row()->count);
            $national_day = $count_national_day . 'd';

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

                if ($hari == 7) {
                    $total['minggu']++;
                } elseif ($hari == 6) {
                    $total['sabtu']++;
                } else {
                    $total['senin_kamis']++;
                }
            }

            $total_attandace = [
                '5' => ['weekday' => $total['senin_kamis'], 'weekend' => $total['sabtu'] + $total['minggu']],
                '6' => ['weekday' => $total['senin_kamis'] + $total['sabtu'], 'weekend' => $total['minggu']]
            ];

            $total_attandace['5']['weekday'] -= $count_national_day;
            $total_attandace['6']['weekday'] -= $count_national_day;

            $this->db->distinct()->select(
                "COALESCE(TRIM(b.logo), '') AS logo," .
                    "COALESCE(TRIM(b.name), '') AS division," .
                    "TRIM(a.number) AS nik," .
                    "TRIM(a.name) AS fullname," .
                    "COALESCE(TRIM(c.name), '') AS dept," .
                    "COALESCE(TRIM(d.name), '') AS sub_dept," .
                    "COALESCE(TRIM(e.name), '') AS grup," .
                    "COALESCE(TRIM(f.number), '') AS marital," .
                    "TRIM(a.tax_id) AS npwp," .
                    "COALESCE(TRIM(g.salary), '') AS salary," .
                    "COALESCE(g.ter, 0) AS pph21," .
                    "COALESCE(g.net_income, 0) AS thp," .
                    "CASE WHEN COALESCE(g.ter, 0) < 0 OR COALESCE(g.tax_allowance, 0) = 0 THEN 0 ELSE COALESCE(g.ter, 0) END AS tax," .
                    "COALESCE(TRIM(g.allowence), '') AS allowance," .
                    "COALESCE(TRIM(g.overtime_amount_weekday), '') AS weekday," .
                    "COALESCE(TRIM(g.overtime_amount_saturday), '') AS saturday," .
                    "COALESCE(TRIM(g.overtime_amount_sunday), '') AS sunday," .
                    "COALESCE(TRIM(g.overtime_amount_holiday), '') AS holiday," .
                    "COALESCE(TRIM(g.bpjs_employee), '') AS bpjs_employee," .
                    "COALESCE(TRIM(g.total_all_deduction), '') AS deduction," .
                    "COALESCE(TRIM(h.bpjs), '') AS bpjs," .
                    "COALESCE(TRIM(h.bpjs_kes), '') AS bpjs_kes," .
                    "COALESCE(TRIM(g.bpjs_company), '') AS bpjs_company," .
                    "COALESCE(TRIM(g.attandance_wd), '') AS workingday," .
                    "COALESCE(TRIM(g.attandance), '') AS attandance," .
                    "COALESCE(j.days, 5) AS days," .
                    "TRIM(a.id) AS id," .
                    "TRIM(a.group_id) AS group_id," .
                    "COALESCE(g.correction_plus, 0) AS correction_plus," .
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

            $this->db->where("g.period_start", $period_from)->where("g.period_end", $period_to)->where("g.is_publish", 1);
            $this->db->where("a.id", $employee);
            $this->db->order_by("fullname");
            $get = $this->db->get()->result();

            if (empty($get)) {
                $pdf->Output('salary_slip_blank.pdf', 'D');
                return;
            }

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
                        if (floatval($value) > 0) {
                            $allowances[$row->id] += floatval($value);
                        }
                    }
                }
            }

            $this->db->distinct()->select("TRIM(number) AS id, COUNT(date_in) AS count")->from("attandances")
                ->where_in("number", array_keys($attandances))->where("date_in BETWEEN '$date_from' AND '$date_to'", NULL, FALSE)
                ->group_by("number")->order_by("id");
            foreach ($this->db->get()->result() as $key => $row) {
                $attandances[$row->id] = intval($row->count);
            }

            $this->db->distinct()->select("TRIM(employee_id) AS id, SUM(duration_hour) AS total")->from("overtimes")
                ->where_in("employee_id", array_keys($overtimes))->where("trans_date BETWEEN '$date_from' AND '$date_to'", NULL, FALSE)
                ->group_by("employee_id")->order_by("id");
            foreach ($this->db->get()->result() as $key => $row) {
                $overtimes[$row->id] = intval($row->total);
            }

            foreach ($get as $index => $row) {
                $allowance = json_decode($row->allowance);
                $bpjs_employee = json_decode($row->bpjs_employee);
                $bpjs_company = json_decode($row->bpjs_company);
                $attandance = json_decode($row->attandance);
                $correction_plus = $row->correction_plus;
                $correction_minus = $row->correction_minus;
                $is_hl = $row->group_id == '20221213000002';
                $pagebreak = ($index != 0) ? 'style="page-break-before: always;"' : '';

                $logo = '';
                if (!empty($row->logo)) {
                    $division_logo = base_url('assets/image/division/' . $row->logo);

                    // Format untuk img src
                    $logo = '<img src="' . $division_logo . '" style="width: 50px; height: 50px;">';
                }

                $html .= '
                <table border="0" cellpadding="5" width="100%">
                    <tr>
                        <th colspan="2">' . $logo . '</th>
                    </tr>
                    <tr>
                        <th width="50%"><h3>' . $row->division . '</h3></th>
                        <th width="50%" align="right"><h3>PAYSLIP</h3></th>
                    </tr>
                </table>';

                $div_tt = '';
                $total_allowence = 0;
                $count_td = 0;
                foreach ($allowance as $key => $value) {
                    if (floatval($value) > 0) {
                        $total_allowence += floatval($value);
                        $key = strtoupper($key);
                        $value = number_format($value, 0, ',', '.');
                        $field = $this->crud->read('allowances', [], ["number" => $key]);
                        $div_tt .= ($is_hl) ? '' : '<tr><td style="border-left:0.2px solid #000;">' . $field->name . '</td><td style="border-right:0.2px solid #000;"  align="right">' . $value . '</td></tr>';
                        if (!$is_hl) {
                            $count_td++;
                        }
                    }
                }

                $employee = $row->nik . ' / ' . $row->fullname;

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
                $bpjs_kes = floatval($row->bpjs_kes) ? floatval($row->bpjs_kes) : $bpjs;
                $jkk_emp = floatval($bpjs_employee->jkk_employee ?? 0);
                $jkm_emp = floatval($bpjs_employee->jkm_employee ?? 0);
                $jht_com = floatval($bpjs_company->jht_company ?? 0);
                $jp_com = floatval($bpjs_company->jp_company ?? 0);
                $bpjs_com = floatval($bpjs_company->bpjs_company ?? 0);

                $bpjs_rate = number_format($bpjs, 0, ',', '.');
                $bpjs_kes_rate = number_format($bpjs_kes, 0, ',', '.');
                $jkk = number_format($jkk_emp, 0, ',', '.');
                $jkm = number_format($jkm_emp, 0, ',', '.');
                $jht_company = number_format($jht_com, 0, ',', '.');
                $jp_company = number_format($jp_com, 0, ',', '.');
                $bpjsk_company = number_format($bpjs_com, 0, ',', '.');
                $total_benefits = number_format(($bpjs + $bpjs + $jkk_emp + $jkm_emp + $jht_com + $jp_com + $bpjs_com), 0, ',', '.');

                $actual_wd = $attandances[$row->nik] . 'd';
                $schedule_wd = intval($total_attandace[$row->days]['weekday']) . 'd';
                $dayoff = intval($total_attandace[$row->days]['weekend']) . 'd';
                $time_off_code = 'H: ' . $attandances[$row->nik] . 'd S: ' . intval($attandance->sa ?? 0) . 'd I: ' . intval($attandance->ia ?? 0) . 'd';
                $overtime_hour = $overtimes[$row->id] . 'h';
                $div_space = ($is_hl) ? '<tr><td></td><td class="value">&nbsp;</td></tr>' : '';

                $earningsRowCount   = $count_td + 4; 
                $deductionsRowCount = 6;

                $maxRow   = max($earningsRowCount, $deductionsRowCount);
                $earnPad  = $maxRow - $earningsRowCount;
                $dedPad   = $maxRow - $deductionsRowCount;

                $emptyRow = '
                <tr>
                    <td style="border-left:0.2px solid #000;">&nbsp;</td>
                    <td style="border-right:0.2px solid #000;" align="right">&nbsp;</td>
                </tr>';

                $html .= '
                <table border="0" cellpadding="5" width="100%">
                    <tr>
                        <td width="15%">Payroll cut off</td>
                        <td width="35%">' . $payroll_cutoff . '</td>
                        <td width="15%">Grade / Level</td>
                        <td width="35%"> - / ' . $row->grup . '</td>
                    </tr>
                    <tr>
                        <td width="15%">ID / Name</td>
                        <td width="35%">' . $employee . '</td>
                        <td width="15%">PTKP</td>
                        <td width="35%">' . $row->marital . '</td>
                    </tr>
                    <tr>
                        <td width="15%">Job position</td>
                        <td width="35%">' . $row->sub_dept . '</td>
                        <td width="15%">NPWP</td>
                        <td width="35%">' . $row->npwp . '</td>
                    </tr>
                    <tr>
                        <td width="15%">Organization</td>
                        <td width="35%">' . $row->dept . '</td>
                        <td width="15%"></td>
                        <td width="35%"></td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                    </tr>
                </table>';

                $earningsTable = '
                <table border="0" cellpadding="6" width="100%">
                    <tr>
                        <td colspan="2" style="border:0.2px solid #000;" ><b>Earnings</b></td>
                    </tr>

                    <tr>
                        <td style="border-left:0.2px solid #000;">Basic Salary</td>
                        <td style="border-right:0.2px solid #000;" align="right">'.$salary.'</td>
                    </tr>
                    '.$div_tt.'
                    <tr>
                        <td style="border-left:0.2px solid #000;">Tax Allowance</td>
                        <td style="border-right:0.2px solid #000;" align="right" >'.$tax.'</td>
                    </tr>
                    <tr>
                        <td style="border-left:0.2px solid #000;">Overtime ('.$overtime_hour.')</td>
                        <td style="border-right:0.2px solid #000;" align="right" >'.$overtime.'</td>
                    </tr>
                    <tr>
                        <td style="border-left:0.2px solid #000;">Correction Plus</td>
                        <td style="border-right:0.2px solid #000;" align="right" >'.$correction_plus.'</td>
                    </tr>';

                    for ($i = 0; $i < $earnPad; $i++) {
                        $earningsTable .= $emptyRow;
                    }
                $earningsTable .= '
                    <tr>
                        <td style="border-left:0.2px solid #000; border-bottom:0.2px solid #000;"><b>Total earnings</b></td>
                        <td style="border-right:0.2px solid #000; border-bottom:0.2px solid #000;" align="right" ><b>'.$total_earnings.'</b></td>
                    </tr>
                </table>';

                $deductionsTable = '
                    <table border="0" cellpadding="6" width="100%">
                        <tr>
                            <td style="border:0.2px solid #000;" colspan="2"><b>Deductions</b></td>
                        </tr>

                        <tr>
                            <td width="60%" style="border-left:0.2px solid #000;">BPJS Kesehatan Employee</td>
                            <td width="40%" style="border-right:0.2px solid #000;" align="right">'.$bpjsk.'</td>
                        </tr>
                        <tr>
                            <td width="60%" style="border-left:0.2px solid #000;">JHT Employee</td>
                            <td width="40%" style="border-right:0.2px solid #000;" align="right">'.$jht.'</td>
                        </tr>
                        <tr>
                            <td width="60%" style="border-left:0.2px solid #000;">Potongan Absen</td>
                            <td width="40%" style="border-right:0.2px solid #000;" align="right">'.$deduction.'</td>
                        </tr>
                        <tr>
                            <td width="60%" style="border-left:0.2px solid #000;">Jaminan Pensiun Employee</td>
                            <td width="40%" style="border-right:0.2px solid #000;" align="right">'.$jp.'</td>
                        </tr>
                        <tr>
                            <td width="60%" style="border-left:0.2px solid #000;">PPH 21</td>
                            <td width="40%" style="border-right:0.2px solid #000;" align="right">'.$pph21.'</td>
                        </tr>
                        <tr>
                            <td width="60%" style="border-left:0.2px solid #000;">Correction Minus</td>
                            <td width="40%" style="border-right:0.2px solid #000;" align="right">'.$correction_minus.'</td>
                        </tr>';

                        for ($i = 0; $i < $dedPad; $i++) {
                            $deductionsTable .= $emptyRow;
                        }
                    $deductionsTable .= '
                        <tr>
                            <td width="60%" style="border-left:0.2px solid #000; border-bottom:0.2px solid #000;"><b>Total deductions</b></td>
                            <td width="40%" style="border-right:0.2px solid #000; border-bottom:0.2px solid #000;" align="right"><b>'.$total_deduction.'</b></td>
                        </tr>
                    </table>';


                $html .= '
                <table width="100%" cellpadding="4">
                    <tr>
                        <td width="50%" valign="top">
                            '.$earningsTable.'
                        </td>

                        <td width="4%"></td>

                        <td width="46%" valign="top">
                            '.$deductionsTable.'
                        </td>
                    </tr>
                </table>
                ';

                $html .= '
                <table width="100%" cellpadding="4">
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="75%" align="right" style="font-size:11px;">
                            <b>Take Home Pay</b>
                        </td>
                        <td width="25%" align="right" style="font-size:11px;">
                            <b>Rp '.$total_home_pay.'</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </table>
                ';

                $benefitsTable = '
                <table border="0" cellpadding="6" width="100%">
                    <tr>
                        <td colspan="2" style="border:0.2px solid #000;"><b>Benefits*</b></td>
                    </tr>

                    <tr>
                        <td style="border-left:0.2px solid #000;">BPJS TK Rate</td>
                        <td style="border-right:0.2px solid #000;" align="right">'.$bpjs_rate.'</td>
                    </tr>
                    <tr>
                        <td style="border-left:0.2px solid #000;">BPJS Kes Rate</td>
                        <td style="border-right:0.2px solid #000;" align="right">'.$bpjs_kes_rate.'</td>
                    </tr>
                    <tr>
                        <td style="border-left:0.2px solid #000;">JKK</td>
                        <td style="border-right:0.2px solid #000;" align="right">'.$jkk.'</td>
                    </tr>
                    <tr>
                        <td style="border-left:0.2px solid #000;">JKM</td>
                        <td style="border-right:0.2px solid #000;" align="right">'.$jkm.'</td>
                    </tr>
                    <tr>
                        <td style="border-left:0.2px solid #000;">JHT Company</td>
                        <td style="border-right:0.2px solid #000;" align="right">'.$jht_company.'</td>
                    </tr>
                    <tr>
                        <td style="border-left:0.2px solid #000;">Jaminan Pensiun Company</td>
                        <td style="border-right:0.2px solid #000;" align="right">'.$jp_company.'</td>
                    </tr>
                    <tr>
                        <td style="border-left:0.2px solid #000;">BPJS Kesehatan Company</td>
                        <td style="border-right:0.2px solid #000;" align="right">'.$bpjsk_company.'</td>
                    </tr>

                    <tr>
                        <td style="border-left:0.2px solid #000; border-bottom:0.2px solid #000;"><b>Total benefits</b></td>
                        <td style="border-right:0.2px solid #000; border-bottom:0.2px solid #000;" align="right">
                            <b>'.$total_benefits.'</b>
                        </td>
                    </tr>
                </table>';

                $attendanceTable = '
                <table border="0" cellpadding="6" width="100%">
                    <tr>
                        <td colspan="2" style="border:0.2px solid #000;"><b>Attendance Summary</b></td>
                    </tr>

                    <tr>
                        <td width="60%" style="border-left:0.2px solid #000;">Actual Working Day</td>
                        <td width="40%" style="border-right:0.2px solid #000;" align="right">'.$actual_wd.'</td>
                    </tr>
                    <tr>
                        <td width="60%" style="border-left:0.2px solid #000;">Schedule Working Day</td>
                        <td width="40%" style="border-right:0.2px solid #000;" align="right">'.$schedule_wd.'</td>
                    </tr>
                    <tr>
                        <td width="60%" style="border-left:0.2px solid #000;">Dayoff</td>
                        <td width="40%" style="border-right:0.2px solid #000;" align="right">'.$dayoff.'</td>
                    </tr>
                    <tr>
                        <td width="60%" style="border-left:0.2px solid #000;">National Holiday</td>
                        <td width="40%" style="border-right:0.2px solid #000;" align="right">'.$national_day.'</td>
                    </tr>
                    <tr>
                        <td width="60%" style="border-left:0.2px solid #000;">Attendance / Time Off Code</td>
                        <td width="40%" style="border-right:0.2px solid #000;" align="right">'.$time_off_code.'</td>
                    </tr>
                    <tr>
                        <td width="60%" style="border-left:0.2px solid #000; solid #000;">Overtime Hours*</td>
                        <td width="40%" style="border-right:0.2px solid #000; solid #000;" align="right">
                            '.$overtime_hour.'
                        </td>
                    </tr>
                    <tr>
                        <td width="60%" style="border-left:0.2px solid #000;"></td>
                        <td width="40%" style="border-right:0.2px solid #000;" align="right"></td>
                    </tr>
                    <tr>
                        <td width="60%" style="border-left:0.2px solid #000; border-bottom:0.2px solid #000;"></td>
                        <td width="40%" style="border-right:0.2px solid #000; border-bottom:0.2px solid #000;" align="right"></td>
                    </tr>
                </table>';

                $html .= '
                <table width="100%" cellpadding="4">
                    <tr>
                        <td width="50%" valign="top">
                            '.$benefitsTable.'
                        </td>

                        <td width="4%"></td>

                        <td width="46%" valign="top">
                            '.$attendanceTable.'
                        </td>
                    </tr>
                </table>
                ';

                $format  = date("Ymd");
                $pdf->SetFont('helvetica', '', 8);
                $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
                $pdf->Output('salary_slip_'.$format.'.pdf', 'D');   
            }

        } else {
            show_error("Cannot Process your request");
        }
    }
}
