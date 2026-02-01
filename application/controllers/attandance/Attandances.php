<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Attandances extends CI_Controller
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
        $this->load->model('accessmodule');

        //VALIDASI FORM
        $this->form_validation->set_rules('number', 'Employee', 'required|min_length[1]|max_length[30]');

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
            $this->load->view('attandance/attandances');
        } else {
            redirect('error_access');
        }
    }

    //GET DATA
    public function reads()
    {
        $post = isset($_POST['q']) ? $_POST['q'] : "";
        $send = $this->crud->reads('attandances', ["name" => $post]);
        echo json_encode($send);
    }

    //GET DATATABLES
    public function datatables()
    {
        if ($this->input->post()) {
            $filter_from = $this->input->get('filter_from');
            $filter_to = $this->input->get('filter_to');
            $filter_division = $this->input->get('filter_division');
            $filter_departement = $this->input->get('filter_departement');
            $filter_departement_sub = $this->input->get('filter_departement_sub');
            $filter_employee = $this->input->get('filter_employee');
            $filter_status = $this->input->get('filter_status');
            $result = array();

            $page   = $this->input->post('page');
            $rows   = $this->input->post('rows');
            //Pagination 1-10
            $page   = isset($page) ? intval($page) : 1;
            $rows   = isset($rows) ? intval($rows) : 10;
            $offset = ($page - 1) * $rows;

            //Employee
            $this->db->select("a.id, b.id as attandance_id, a.number, a.name, a.division_id, a.departement_id, a.departement_sub_id,
                DAYNAME(b.date_in) as dayname,
                b.date_in,
                b.date_out,
                b.time_in,
                b.time_out,
                c.name as division_name, 
                d.name as departement_name, 
                e.name as departement_sub_name, 
                g.name as contract_name,
                b.approved_by,
                b.approved_to,
                b.approved_date,
                b.status_in,
                b.status_out,
                b.foto_in,
                b.foto_out,
                b.status,
                b.lat_in,
                b.lng_in,
                b.lat_out,
                b.lng_out
                ");
            $this->db->from('employees a');
            $this->db->join('attandances b', 'a.number = b.number');
            $this->db->join('divisions c', 'a.division_id = c.id');
            $this->db->join('departements d', 'a.departement_id = d.id');
            $this->db->join('departement_subs e', 'a.departement_sub_id = e.id');
            $this->db->join('contracts g', 'g.id = a.contract_id', 'left');
            if ($filter_from != "" && $filter_to != "") {
                $this->db->where("(b.date_in >= '$filter_from' and b.date_in <= '$filter_to' or b.date_out >= '$filter_from' and b.date_out <= '$filter_to')");
            }
            if (isset($this->restrict_user)) {
                $this->accessmodule->applyEmployeeRestriction($this->restrict_user, 'a');
            }
            $this->db->like('a.division_id', $filter_division);
            $this->db->like('a.departement_id', $filter_departement);
            $this->db->like('a.departement_sub_id', $filter_departement_sub);
            $this->db->like('a.id', $filter_employee);
            $this->db->group_by("a.number");
            $this->db->group_by("b.date_in");
            $this->db->order_by("d.name", "asc");
            $this->db->order_by("a.name", "asc");
            $this->db->order_by("b.date_in", "asc");

            //Total Data
            $totalRows = $this->db->count_all_results('', false);
            //Limit 1 - 10
            $this->db->limit($rows, $offset);
            //Get Data Array
            $employees = $this->db->get()->result_array();
            $record = [];
            foreach ($employees as $key => $value) {
                $record[] = $value;

                $dayOfWeek = date('N', strtotime($value['date_in'])); // 1 (Mon) to 7 (Sun)

                $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, c.name, d.name as shift_name");
                $this->db->from('shift_employees b');
                $this->db->join('shifts c', 'c.id = b.shift_id');
                $this->db->join('shift_details d', 'd.shift_id = c.id');
                $this->db->where('b.employee_id', $value['id']);
                $this->db->where('d.days >=', $dayOfWeek);
                $shift = $this->db->get()->row();

                $record[$key]['shift_name'] = isset($shift->name) ? $shift->name : '';
            }
            
            // die(json_encode($employees));

            $datas['total'] = $totalRows;
            $datas = array_merge($datas, ['rows' => @$record]);
            echo json_encode($datas);
        }
    }

    //CREATE DATA
    public function create()
    {
        if ($this->input->post()) {
            if ($this->form_validation->run() == TRUE) {
                $post   = $this->input->post();
                $attandance = $this->crud->read("attandances", [], ["number" => $post['number'], "date_in" => $post['date_in']]);
                if ($attandance) {
                    echo json_encode(array("title" => "Available", "message" => "The attandance for this employee and date in has been created", "theme" => "error"));
                } else {
                    $send   = $this->crud->create('attandances', $post);
                    echo $send;
                }
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
            $send = $this->crud->update('attandances', ["id" => $id], $post);
            echo $send;
        } else {
            show_error("Cannot Process your request");
        }
    }

    //DELETE DATA
    public function delete()
    {
        $data = $this->input->post();
        $delete = $this->crud->delete("attandances", ['number' => $data['number'], 'date_in' => $data['date_in']]);
        echo $delete;
    }

    function formatTanggal($date)
    {
        // menggunakan class Datetime
        $datetime = DateTime::createFromFormat('d/m/Y', $date);
        return @$datetime->format('Y-m-d');
    }

    public function create_temp($number, $date, $time)
    {
        $this->db->select('*');
        $this->db->from('attandances_temp');
        $this->db->where("number", $number);
        $this->db->where("attandance_date", $date . " " . $time);
        $records = $this->db->get()->result_array();

        $datas = array(
            'number' => $number,
            'attandance_date' => $date . " " . $time
        );

        if (count($records) > 0) {
            return json_encode(array("title" => "Duplicated", "message" => "Data absence duplicate", "theme" => "error"));
        } else {
            $send = $this->crud->create('attandances_temp', $datas);
            return $send;
        }
    }

    //UPLOAD DATA
    public function upload()
    {
        error_reporting(0);

        if ($this->input->post('file_type') == "text") {
            $target = basename($_FILES['file_upload']['name']);
            move_uploaded_file($_FILES['file_upload']['tmp_name'], $target);
            $open = fopen($target, 'r');

            while (!feof($open)) {
                $getTextLine = fgets($open);
                $explodeLine = explode(":", $getTextLine);

                list($code, $date, $time, $id, $number) = $explodeLine;

                $this->create_temp(trim($number), trim(date('Y-m-d', strtotime($date))), trim(date('H:i:s', strtotime($time))));
            }

            $this->db->select('*');
            $this->db->from('attandance_temp');
            $this->db->order_by('number', 'asc');
            $this->db->order_by('attandance_date', 'asc');
            $attandance = $this->db->get()->result_object();

            foreach ($attandance as $data) {
                $datas[] = array(
                    'number' => $data->number,
                    'date_in' => date('d/m/Y H:i:s', strtotime($data->attandance_date)),
                    'date_out' => date('d/m/Y H:i:s', strtotime($data->attandance_date))
                );
            }

            $datas['total'] = count($datas);
            echo json_encode($datas);
            unlink($_FILES['file_upload']['name']);
            $this->db->truncate('attandance_temp');
        } elseif ($this->input->post('file_type') == "excel") {
            require_once 'assets/vendors/excel_reader2.php';
            $target = basename($_FILES['file_upload']['name']);
            move_uploaded_file($_FILES['file_upload']['tmp_name'], $target);
            chmod($_FILES['file_upload']['name'], 0777);
            $file = $_FILES['file_upload']['name'];
            $data = new Spreadsheet_Excel_Reader($file, false);
            $total_row = $data->rowcount($sheet_index = 0);

            $datas      = [];
            $logsInsert = [];

            $batchId = 'UPLOAD-' . date('YmdHis');

            for ($i = 2; $i <= $total_row; $i++) {
                // kolom 4 berisi tanggal & jam
                $col4 = trim($data->val($i, 4)); // contoh: "04/11/2025 08.00" atau "04/11/2025;08.00"

                if (strpos($col4, ';') !== false) {
                    $dateinParts = explode(";", $col4);
                    $rawDateTime = trim($dateinParts[0] . " " . $dateinParts[1]);
                } else {
                    $rawDateTime = $col4;
                }

                $machineCode = trim($data->val($i, 3));
                $lokasi      = trim($data->val($i, 5));
                $pin         = trim($data->val($i, 6));

                $scanDatetime = $this->normalizeScanDatetime($rawDateTime);

                $datas[] = array(
                    'machine_code' => $machineCode,
                    'lokasi'       => $lokasi,
                    'number'       => $pin,
                    'date_in'      => $rawDateTime,
                    'date_out'     => $rawDateTime,
                    'pin'          => $pin,
                    'scan_datetime' => $scanDatetime,
                );

                $logsInsert = array(
                    'machine_code'  => $machineCode,
                    'lokasi_id'     => $lokasi,
                    'pin'           => $pin,
                    'scan_raw'      => $rawDateTime,
                    'scan_datetime' => $scanDatetime,
                    'batch_id'      => $batchId,
                    'is_processed'  => 0,
                );
                $this->db->from('attandance_logs');
                if (!empty($pin)) {
                    $this->db->where('pin', $pin);
                }
                if (!empty($machineCode)) {
                    $this->db->where('machine_code', $machineCode);
                }
                $this->db->where('lokasi_id', $lokasi);

                if (!empty($scanDatetime)) {
                    $this->db->where('scan_datetime', $scanDatetime);
                } else {
                    // fallback: kalau parsing gagal, cek pakai raw string
                    $this->db->where('scan_raw', $rawDateTime);
                }

                $exists = $this->db->get()->num_rows() > 0;
                if (!$exists) {
                    $this->crud->create('attandance_logs', $logsInsert);
                } else {
                    if (!empty($pin)) {
                        $this->db->where('pin', $pin);
                    }
                    if (!empty($machineCode)) {
                        $this->db->where('machine_code', $machineCode);
                    }
                    $this->db->where('lokasi_id', $lokasi);
                    if (!empty($scanDatetime)) {
                        $this->db->where('scan_datetime', $scanDatetime);
                    } else {
                        $this->db->where('scan_raw', $rawDateTime);
                    }
                    $this->db->update('attandance_logs', $logsInsert);
                }
            }

            $datas['total'] = count($datas);
            $datas['batchId'] = $batchId;
            echo json_encode($datas);

            unlink($_FILES['file_upload']['name']);
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
            $scanDatetime = isset($data['scan_datetime']) ? trim($data['scan_datetime']) : null;
            $date_in = null; // Y-m-d
            $time_in = null; // H:i:s

            if (!empty($scanDatetime)) {
                $dt = DateTime::createFromFormat('Y-m-d H:i:s', $scanDatetime);
                if ($dt !== false) {
                    $date_in = $dt->format('Y-m-d');
                    $time_in = $dt->format('H:i:s');
                }
            }

            if (empty($date_in) || empty($time_in)) {
                echo json_encode([
                    "title"   => "Error",
                    "message" => $data['scan_datetime']." Failed to read date/time.",
                    "theme"   => "error",
                ]);
                return;
            }
            
            $machine_code = isset($data['machine_code']) ? trim($data['machine_code']) : '';
            $lokasi       = isset($data['lokasi']) ? trim($data['lokasi']) : '';
            $number_emp       = isset($data['number']) ? trim($data['number']) : '';
            $pin          = isset($data['pin']) ? trim($data['pin']) : '';
            $batchId          = isset($data['batchId']) ? trim($data['batchId']) : '';

            //Employee
            $this->db->select('*');
            $this->db->from('employees');

            if ($machine_code != '') {
                $this->db->where('machine_code', $machine_code);
            } else if ($number_emp != '') {
                $this->db->where('number', $number_emp);
            } else {
                 $this->db->where('id', 0);
            }
            $employee = $this->db->get()->row();

            //Jika Terdaftar di master employee
            if (!empty($employee->number)) {
                if ($lokasi == 1) {
                    // Kondisi Normal
                    $work_date = $date_in;

                    if (!empty($scanDatetime)) {
                        $scanDt  = DateTime::createFromFormat('Y-m-d H:i:s', $scanDatetime);
                        if ($scanDt) {
                            $scanHour = (int) $scanDt->format('H');

                            // aturan: scan jam 00:00 - 02:59 dianggap milik hari sebelumnya
                            if ($scanHour < 3) {
                                $work_date = $scanDt->modify('-1 day')->format('Y-m-d');
                            } else {
                                $work_date = $scanDt->format('Y-m-d');
                            }
                        }
                    }

                    $adjustedDateExpr = "(
                        CASE 
                            WHEN TIME(scan_datetime) < '03:00:00' 
                                THEN DATE(DATE_SUB(scan_datetime, INTERVAL 1 DAY))
                            ELSE DATE(scan_datetime)
                        END
                    )";

                    $this->db->select('
                        MIN(scan_datetime) AS first_scan,
                        MAX(scan_datetime) AS last_scan,
                        COUNT(*) AS total_scan
                    ');
                    $this->db->from('attandance_logs');
                    $this->db->where('pin', $pin);
                    $this->db->where('machine_code', $machine_code);
                    $this->db->where("$adjustedDateExpr =", $work_date);

                    if (!empty($lokasi)) {
                        $this->db->where('lokasi_id', $lokasi);
                    }

                    if (!empty($batchId)) {
                        $this->db->where('batch_id', $batchId);
                    }

                    $logSummary = $this->db->get()->row();

                    $firstScanDt = DateTime::createFromFormat('Y-m-d H:i:s', $logSummary->first_scan);
                    $lastScanDt  = DateTime::createFromFormat('Y-m-d H:i:s', $logSummary->last_scan);

                    $time_in_from_logs  = $firstScanDt ? $firstScanDt->format('H:i:s') : null;
                    $time_out_from_logs = $lastScanDt  ? $lastScanDt->format('H:i:s') : null;
                    $total_scan         = (int) $logSummary->total_scan;

                    $final_time_in  = null;
                    $final_time_out = null;

                    if ($total_scan >= 2) {
                        $final_time_in  = $time_in_from_logs;
                        $final_time_out = $time_out_from_logs;
                    } else {
                        // cuma satu log di hari itu
                        $scanHour = (int) $firstScanDt->format('H');

                        if ($scanHour <= 12) {
                            $final_time_in  = $time_in_from_logs;
                            $final_time_out = null; // IN_ONLY
                        } else {
                            $final_time_in  = null; // OUT_ONLY
                            $final_time_out = $time_out_from_logs;
                        }
                    }

                    // 3.4. Ambil data shift
                    $baseTimeForShift = $time_in;
                    if ($firstScanDt) {
                        $baseTimeForShift = $firstScanDt->format('H:i:s');
                    }

                    $isSecurity = false;

                    $this->db->select('c.name');
                    $this->db->from('shift_employees b');
                    $this->db->join('shifts c', 'c.id = b.shift_id');
                    $this->db->where('b.employee_id', $employee->id);
                    $this->db->where('c.number', 'SECURITY');
                    $rowShiftGroup = $this->db->get()->row();

                    if ($rowShiftGroup) {
                        $isSecurity = true;
                    }

                    if ($isSecurity) {
                        // proses khusus security,
                        $this->processSecurityAttendance($employee, $scanDatetime, $pin, $batchId, $lokasi);
                        return;
                    }

                    $dayOfWeek = date('N', strtotime($work_date)); // 1 (Mon) to 7 (Sun)
                    $tolerance_hour_min  = date("H:i:s", strtotime('-2 hour', strtotime($baseTimeForShift)));
                    $tolerance_hour_plus = date("H:i:s", strtotime('+2 hour', strtotime($baseTimeForShift)));

                    $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, c.name, d.name as shift_name");
                    $this->db->from('shift_employees b');
                    $this->db->join('shifts c', 'c.id = b.shift_id');
                    $this->db->join('shift_details d', 'd.shift_id = c.id');
                    $this->db->where('b.employee_id', $employee->id);
                    $this->db->where('d.days >=', $dayOfWeek);
                    $this->db->where("TIME(d.start) >= TIME('$tolerance_hour_min') AND TIME(d.start) <= TIME('$tolerance_hour_plus')");
                    $shift = $this->db->get()->row();

                    $baseShiftData = [
                        'shift_start'  => $shift->start      ?? null,
                        'shift_end'    => $shift->end        ?? null,
                        'shift_name'   => $shift->name       ?? null,
                        'shift_detail' => $shift->shift_name ?? null,
                        'shift_days'   => $shift->days       ?? null,
                    ];

                    $date_out = $work_date; // default: tidak nyebrang hari

                    if (!empty($final_time_in) && !empty($final_time_out)) {
                        // kalau jam out secara "angka" lebih kecil dari jam in (contoh 00:30 < 08:00)
                        // artinya dia pulang di hari berikutnya
                        if (strtotime($final_time_out) < strtotime($final_time_in)) {
                            $date_out = date('Y-m-d', strtotime($work_date . ' +1 day'));
                        }
                    }

                    // untuk kasus IN_ONLY / OUT_ONLY, bisa diperlakukan seperti ini:
                    if (empty($final_time_in) && !empty($final_time_out)) {
                        // cuma out_only
                        // kalau out di jam kecil (lewat tengah malam), anggap date_out = work_date + 1
                        if (strtotime($final_time_out) < strtotime('03:00:00')) {
                            $date_out = date('Y-m-d', strtotime($work_date . ' +1 day'));
                        }
                    }

                    // siapkan payload dasar
                    $data_att = array_merge($baseShiftData, [
                        'number'   => $employee->number,
                        'date_in'  => $work_date,
                        'date_out' => $date_out, 
                        'status'   => 'UPLOAD',
                    ]);

                    // isi time_in / time_out sesuai hasil summary logs
                    if (!empty($final_time_in)) {
                        $data_att['time_in'] = $final_time_in;
                    } else {
                        // date in dikosongkan juga kalau gak ada time in
                        $data_att['date_in'] = null;
                    }
                    if (!empty($final_time_out)) {
                        $data_att['time_out'] = $final_time_out;
                    } else {
                        // date out dikosongkan juga kalau gak ada time out
                        $data_att['date_out'] = null;
                    }

                    $this->db->from('attandances');
                    $this->db->where('number', $employee->number);
                    $this->db->where('date_in', $work_date);
                    $attendance = $this->db->get()->row();

                    // Create / Update
                    if (empty($attendance)) {
                        $this->crud->create('attandances', $data_att);
                        echo json_encode([
                            "title"   => "Created",
                            "message" => $employee->name . " | " . $work_date . " IN " . ($final_time_in ?? '-') . " OUT " . ($final_time_out ?? '-'),
                            "theme"   => "success",
                        ]);
                    } else {
                        $this->crud->update('attandances', ['id' => $attendance->id], $data_att);
                        echo json_encode([
                            "title"   => "Updated",
                            "message" => $employee->name . " | " . $work_date . " IN " . ($final_time_in ?? '-') . " OUT " . ($final_time_out ?? '-'),
                            "theme"   => "success",
                        ]);
                    }

                    //echo json_encode(array("title" => "Warning", "message" => $data['number'] . " Next", "theme" => "error"));
                } else {
                    $yesterday = date("Y-m-d", strtotime('-1 days', strtotime($date_in)));

                    //Attandance
                    $this->db->select('*');
                    $this->db->from('attandances');
                    $this->db->where('number', $employee->number);
                    $this->db->where("date_out = '$date_in'");
                    $attandance_out = $this->db->get()->row();

                    $tolerance_hour_min = date("H:i:s", strtotime('-2 Hour', strtotime(@$time_in)));
                    $tolerance_hour_plus = date("H:i:s", strtotime('+2 Hour', strtotime(@$time_in)));
                    $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, c.name, d.name as shift_name");
                    $this->db->from('shift_employees b');
                    $this->db->join('shifts c', 'c.id = b.shift_id');
                    $this->db->join('shift_details d', 'd.shift_id = c.id');
                    $this->db->where('b.employee_id', $employee->id);
                    $this->db->where("TIME(d.end) >=  TIME('$tolerance_hour_min') && TIME(d.end) <= TIME('$tolerance_hour_plus')");
                    $shift = $this->db->get()->row();

                    $time_begin = strtotime($date_in . " " . @$shift->start);
                    $time_end = strtotime($date_in . " " . $time_in);

                    $diff = $time_begin - $time_end;
                    $hour = floor($diff / (60 * 60));

                    // die($date_in . " " . @$shift->start . " - " . $date_in . " " . @$time_in . " | " . $hour);
                    // die($hour);
                    if ($hour < 0) {
                        $date_out = $date_in;
                    } else {
                        $date_out = $yesterday;
                    }

                    if (!empty($attandance_out->number)) {
                        if ((substr(@$time_in, 0, 2) <= "12")) {
                            $data_attandance = array(
                                'number' => $employee->number,
                                'date_in' => $date_in,
                                'time_in' => $time_in,
                                'shift_start' => @$shift->start,
                                'shift_name' => @$shift->name,
                                'shift_detail' => @$shift->shift_name,
                                'shift_days' => @$shift->days,
                                'status' => "UPLOAD",
                            );

                            $this->crud->update('attandances', ["id" => $attandance_out->id], $data_attandance);
                            echo json_encode(array("title" => "Updated", "message" => $employee->name . " | " . $date_in . " " . $time_in . " Update data Check In", "theme" => "success"));
                        } else {
                            $data_attandance = array(
                                'number' => $employee->number,
                                'date_out' => $date_in,
                                'time_out' => $time_in,
                                'shift_start' => @$shift->start,
                                'shift_name' => @$shift->name,
                                'shift_detail' => @$shift->shift_name,
                                'shift_days' => @$shift->days,
                                'status' => "UPLOAD",
                            );

                            $this->crud->update('attandances', ["id" => $attandance_out->id], $data_attandance);
                            echo json_encode(array("title" => "Updated", "message" => $employee->name . " | " . $date_in . " " . $time_in . " Update data Check Out", "theme" => "success"));
                        }
                    } else {
                        $data_attandance = array(
                            'number' => $employee->number,
                            'date_in' => $date_out,
                            // 'time_in' => @$shift->start,
                            'date_out' => $date_in,
                            'time_out' => $time_in,
                            'shift_start' => @$shift->start,
                            'shift_name' => @$shift->name,
                            'shift_detail' => @$shift->shift_name,
                            'shift_days' => @$shift->days,
                            'status' => "UPLOAD",
                        );
                        $this->crud->create('attandances', $data_attandance);
                        echo json_encode(array("title" => "Created", "message" => $employee->name . " | " . $date_in . " " . $time_in . " Create data Check Out", "theme" => "success"));
                    }
                    //echo json_encode(array("title" => "Warning", "message" => $data['number'] . " Next", "theme" => "error"));
                }
            } else {
                echo json_encode(array("title" => "Warning", "message" => $data['number'] . " Empoyee ID Un Registered", "theme" => "error"));
            }
        }
    }

    // public function uploadcreate()
    // {
    //     if ($this->input->post()) {
    //         $data = $this->input->post('data');
    //         $explode = explode(" ", $data['date_in']);
    //         $date_in = $this->formatTanggal($explode[0]);
    //         $time_in = $explode[1];

    //         //Attandance
    //         $this->db->select('*');
    //         $this->db->from('attandances');
    //         $this->db->where('number', $data['number']);
    //         $this->db->where('date_in', $date_in);
    //         $attandance = $this->db->get()->row();

    //         //Employee
    //         $this->db->select('*');
    //         $this->db->from('employees');
    //         $this->db->where('number', $data['number']);
    //         $employee = $this->db->get()->row();

    //         //Jika Terdaftar di master employee
    //         if (!empty($employee->number)) {
    //             //Jika sudah absen jam masuk
    //             if (!empty($attandance->date_in)) {
    //                 //Shift 2 check
    //                 if ((substr($attandance->time_in, 0, 2) == "00" or substr($attandance->time_in, 0, 2) == "01") and (substr($time_in, 0, 2) == "15" or substr($time_in, 0, 2) == "16")) {
    //                     $data_attandance = array(
    //                         'number' => $data['number'],
    //                         'date_out' => $attandance->date_in,
    //                         'time_out' => $attandance->time_in,
    //                         'date_in' => $date_in,
    //                         'time_in' => $time_in,
    //                     );
    //                     //Shift 3 check
    //                 } elseif ((substr($attandance->time_in, 0, 2) == "08" or substr($attandance->time_in, 0, 2) == "07") and (substr($time_in, 0, 2) == "23" or substr($time_in, 0, 2) == "00")) {
    //                     $data_attandance = array(
    //                         'number' => $data['number'],
    //                         'date_out' => $attandance->date_in,
    //                         'time_out' => $attandance->time_in,
    //                         'date_in' => $date_in,
    //                         'time_in' => $time_in,
    //                     );
    //                 } else {
    //                     $data_attandance = array(
    //                         'number' => $data['number'],
    //                         'date_out' => $date_in,
    //                         'time_out' => $time_in,
    //                     );
    //                 }

    //                 $this->crud->update('attandances', ["id" => $attandance->id], $data_attandance);
    //                 echo json_encode(array("title" => "Updated", "message" => $employee->name . " | " . $date_in . " " . $time_in . " Update data Check Out", "theme" => "success"));
    //             } else {
    //                 //Cek tanggal kemarin
    //                 $yesterday = date("Y-m-d", strtotime('-1 days', strtotime($date_in)));
    //                 $this->db->select("*");
    //                 $this->db->from('attandances');
    //                 $this->db->where('number', $data['number']);
    //                 $this->db->where('date_in', $yesterday);
    //                 $attandance_2 = $this->db->get()->row();

    //                 //jika tanggal kemarin ada jam masuk
    //                 if (!empty($attandance_2->date_in)) {
    //                     //jika jam keluar nya kosong
    //                     if (@$attandance_2->time_out == "" or @$attandance_2->time_out == null) {

    //                         $attandance_plus = date_create($attandance_2->date_in . " " . $attandance_2->time_in);
    //                         $date_plus = date_create($date_in . " " . $time_in);

    //                         //Jika jam masuk kemarin sama dengan jam masuk hari ini
    //                         if (substr($attandance_2->time_in, 0, 2) == substr($time_in, 0, 2)) {
    //                             $data_attandance = array(
    //                                 'number' => $data['number'],
    //                                 'date_in' => $date_in,
    //                                 'time_in' => $time_in,
    //                             );

    //                             $this->crud->create('attandances', $data_attandance);
    //                             echo json_encode(array("title" => "Saved", "message" => $employee->name . " | " . $date_in . " " . $time_in . " Create Check in", "theme" => "success"));

    //                             //Jika jam masuk kemarin kurang dari jam masuk sekarang
    //                         } elseif ($attandance_plus < $date_plus) {
    //                             $data_attandance = array(
    //                                 'number' => $data['number'],
    //                                 'date_in' => $date_in,
    //                                 'time_in' => $time_in,
    //                             );

    //                             $this->crud->create('attandances', $data_attandance);
    //                             echo json_encode(array("title" => "Saved", "message" => $employee->name . " | " . $date_in . " " . $time_in . " Create Check in", "theme" => "success"));
    //                         } else {
    //                             $data_attandance = array(
    //                                 'number' => $data['number'],
    //                                 'date_out' => $date_in,
    //                                 'time_out' => $time_in,
    //                             );

    //                             $this->crud->update('attandances', ["id" => @$attandance_2->id], $data_attandance);
    //                             echo json_encode(array("title" => "Updated", "message" => $employee->name . " Update data time out yesterday", "theme" => "success"));
    //                         }
    //                     } else {
    //                         $data_attandance = array(
    //                             'number' => $data['number'],
    //                             'date_in' => $date_in,
    //                             'time_in' => $time_in,
    //                         );

    //                         $this->crud->create('attandances', $data_attandance);
    //                         echo json_encode(array("title" => "Saved", "message" => $employee->name . " | " . $date_in . " " . $time_in . " Create Check in", "theme" => "success"));
    //                     }
    //                 } else {
    //                     $data_attandance = array(
    //                         'number' => $data['number'],
    //                         'date_in' => $date_in,
    //                         'time_in' => $time_in,
    //                     );

    //                     $this->crud->create('attandances', $data_attandance);
    //                     echo json_encode(array("title" => "Saved", "message" => $employee->name . " | " . $date_in . " " . $time_in . " Create Check in", "theme" => "success"));
    //                 }
    //             }
    //         } else {
    //             echo json_encode(array("title" => "Warning", "message" => $data['number'] . " Empoyee ID Un Registered", "theme" => "error"));
    //         }
    //     }
    // }

    //PRINT & EXCEL DATA
    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename= Attendance Recap_$format.xls");
        }

        $filter_from = $this->input->get('filter_from');
        $filter_to = $this->input->get('filter_to');
        $filter_division = $this->input->get('filter_division');
        $filter_departement = $this->input->get('filter_departement');
        $filter_departement_sub = $this->input->get('filter_departement_sub');
        $filter_employee = $this->input->get('filter_employee');
        $filter_status = $this->input->get('filter_status');

        //Config
        $this->db->select('*');
        $this->db->from('config');
        $config = $this->db->get()->row();

        $this->db->select("a.id, b.id as attandance_id, a.number, a.name, a.division_id, a.departement_id, a.departement_sub_id,
            DAYNAME(b.date_in) as dayname,
            b.date_in,
            b.date_out,
            b.time_in,
            b.time_out,
            c.name as division_name, 
            d.name as departement_name, 
            e.name as departement_sub_name, 
            g.name as contract_name");
        $this->db->from('employees a');
        $this->db->join('attandances b', 'a.number = b.number');
        $this->db->join('divisions c', 'a.division_id = c.id');
        $this->db->join('departements d', 'a.departement_id = d.id');
        $this->db->join('departement_subs e', 'a.departement_sub_id = e.id');
        $this->db->join('contracts g', 'g.id = a.contract_id', 'left');
        if ($filter_from != "" && $filter_to != "") {
            $this->db->where("(b.date_in >= '$filter_from' and b.date_in <= '$filter_to' or b.date_out >= '$filter_from' and b.date_out <= '$filter_to')");
        }
        if (isset($this->restrict_user)) {
            $this->accessmodule->applyEmployeeRestriction($this->restrict_user, 'a');
        }
        $this->db->like('a.division_id', $filter_division);
        $this->db->like('a.departement_id', $filter_departement);
        $this->db->like('a.departement_sub_id', $filter_departement_sub);
        $this->db->like('a.id', $filter_employee);
        $this->db->group_by("a.number");
        $this->db->group_by("b.date_in");
        $this->db->order_by("d.name", "asc");
        $this->db->order_by("a.name", "asc");
        $this->db->order_by("b.date_in", "asc");
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
                            <small>ATTANDANCE RECAP</small>
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
                <th>Day</th>
                <th>Date In</th>
                <th>Date Out</th>
                <th>Departement</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Type</th>
                <th>Check In</th>
                <th>Check Out</th>
            </tr>';
        $no = 1;
        foreach ($records as $data) {
            $html .= '<tr>
                        <td>' . $no . '</td>
                        <td>' . $data['dayname'] . '</td>
                        <td>' . $data['date_in'] . '</td>
                        <td>' . $data['date_out'] . '</td>
                        <td>' . $data['departement_name'] . '</td>
                        <td>' . $data['number'] . '</td>
                        <td>' . $data['name'] . '</td>
                        <td>' . $data['contract_name'] . '</td>
                        <td>' . $data['time_in'] . '</td>
                        <td>' . $data['time_out'] . '</td>
                    </tr>';
            $no++;
        }

        $html .= '</table></body></html>';
        echo $html;
    }

    private function normalizeScanDatetime($rawDateTime)
    {
        $scanDatetime = null;

        if (!empty($rawDateTime)) {
            // Normalisasi: ubah "7.56" di bagian jam jadi "7:56"
            // Contoh: "04/11/2025 7.56" -> "04/11/2025 7:56"
            $parts = preg_split('/\s+/', $rawDateTime, 2);

            if (count($parts) == 2) {
                $datePart = $parts[0];       // 24/11/2025
                $timePart = $parts[1];       // 07.35.18 atau 07.35 atau 07:35

                // Normalisasi: semua titik di bagian jam jadi titik dua
                // 07.35.18 -> 07:35:18
                // 07.35    -> 07:35
                // 07:35    -> 07:35 (nggak berubah)
                $timePartNorm = str_replace('.', ':', $timePart);

                $normalized = $datePart . ' ' . $timePartNorm;
            } else {
                // fallback kalau formatnya aneh
                $normalized = str_replace('.', ':', $rawDateTime);
            }

            // Daftar format yang mungkin dipakai di file
            $formats = [
                'd/m/Y H:i:s',
                'd/m/Y H:i',
                'd/m/y H:i:s',
                'd/m/y H:i',
            ];

            foreach ($formats as $fmt) {
                $dt = DateTime::createFromFormat($fmt, $normalized);
                if ($dt !== false) {
                    $scanDatetime = $dt->format('Y-m-d H:i:s');
                    break;
                }
            }
        }

        return $scanDatetime;
    }

    protected function processSecurityAttendance($employee, $scanDatetime, $pin, $batchId = null, $lokasi = null)
    {
        // --- 1. Persiapan tanggal & range waktu (D dan D+1) ---
        $baseDt   = DateTime::createFromFormat('Y-m-d H:i:s', $scanDatetime);

        $dateToday = $baseDt->format('Y-m-d');                      // D
        $dateNext  = $baseDt->modify('+1 day')->format('Y-m-d');    // D+1

        $startRange = $dateToday . ' 00:00:00';
        $endRange   = $dateNext  . ' 23:59:59';

        // --- 2. Ambil semua log antara D 00:00 s/d D+1 23:59 ---

        $this->db->select('scan_datetime, lokasi_id');
        $this->db->from('attandance_logs');
        $this->db->where('pin', $pin);
        $this->db->where('scan_datetime >=', $startRange);
        $this->db->where('scan_datetime <=', $endRange);

        if (!empty($batchId)) {
            $this->db->where('batch_id', $batchId);
        }
        if (!empty($lokasi)) {
            $this->db->where('lokasi_id', $lokasi);
        }

        $this->db->order_by('scan_datetime', 'ASC');
        $logs = $this->db->get()->result();

        if (empty($logs)) {
            echo json_encode([
                "title"   => "Warning",
                "message" => $employee->name. " | ". $scanDatetime." No attendance logs found for processing. ",
                "theme"   => "error",
            ]);
            return;
        }

        // --- 3. Kelompokkan log: pagi/sore hari ini & pagi besok ---

        $morningToday = [];   // D 05:00–11:59
        $eveningToday = [];   // D 15:00–23:59
        $morningNext  = [];   // D+1 00:00–09:59

        foreach ($logs as $row) {
            $dt = DateTime::createFromFormat('Y-m-d H:i:s', $row->scan_datetime);
            if (!$dt) continue;

            $d = $dt->format('Y-m-d');
            $t = $dt->format('H:i:s');

            if ($d == $dateToday) {
                if ($t >= '05:00:00' && $t <= '11:59:59') {
                    $morningToday[] = $t;
                } elseif ($t >= '15:00:00' && $t <= '23:59:59') {
                    $eveningToday[] = $t;
                }
            } elseif ($d == $dateNext) {
                if ($t >= '00:00:00' && $t <= '09:59:59') {
                    $morningNext[] = $t;
                }
            }
        }

        // --- 4. Tentukan ini SHIFT PAGI atau SHIFT MALAM ---

        $shiftDetailName = null;  // "SHIFT PAGI" atau "SHIFT MALAM"
        $date_in  = null;
        $time_in  = null;
        $date_out = null;
        $time_out = null;

        // CASE A: SHIFT PAGI (pagi & sore di hari yang sama)
        if (!empty($morningToday) && !empty($eveningToday)) {
            sort($morningToday);
            sort($eveningToday);

            $shiftDetailName = 'SHIFT PAGI';
            $date_in         = $dateToday;
            $time_in         = $morningToday[0];          // scan pagi paling awal
            $date_out        = $dateToday;
            $time_out        = end($eveningToday);        // scan sore paling akhir

        // CASE B: SHIFT MALAM (sore hari ini + pagi besok)
        } elseif (!empty($eveningToday) && !empty($morningNext)) {
            sort($eveningToday);
            sort($morningNext);

            $shiftDetailName = 'SHIFT MALAM';
            $date_in         = $dateToday;
            $time_in         = $eveningToday[0];          // sore pertama
            $date_out        = $dateNext;
            $time_out        = end($morningNext);         // pagi terakhir

        } elseif (!empty($morningToday) && empty($eveningToday)) {
            sort($morningToday);
            sort($eveningToday);

            $shiftDetailName = 'SHIFT PAGI';
            $date_in         = $dateToday;
            $time_in         = $morningToday[0];          // scan pagi paling awal
            $date_out        = null;
            $time_out        = null;
        } else {
            // === Fallback: kemungkinan ini checkout shift malam hari sebelumnya ===
            // Kasus: hanya ada scan pagi di hari ini (contoh: 12/11 07:10),
            // tapi ada scan sore di hari sebelumnya (11/11 18:10).

            if (!empty($morningToday) && empty($eveningToday)) {
                $prevDate       = date('Y-m-d', strtotime($dateToday . ' -1 day'));
                $prevStartRange = $prevDate . ' 00:00:00';
                $prevEndRange   = $prevDate . ' 23:59:59';

                $eveningPrev = [];

                // cari sore kemarin
                $this->db->select('scan_datetime');
                $this->db->from('attandance_logs');
                $this->db->where('pin', $pin);
                $this->db->where('scan_datetime >=', $prevStartRange);
                $this->db->where('scan_datetime <=', $prevEndRange);
                if (!empty($batchId)) {
                    $this->db->where('batch_id', $batchId);
                }
                if (!empty($lokasi)) {
                    $this->db->where('lokasi_id', $lokasi);
                }
                $this->db->order_by('scan_datetime', 'ASC');
                $prevLogs = $this->db->get()->result();

                foreach ($prevLogs as $rowPrev) {
                    $dtPrev = DateTime::createFromFormat('Y-m-d H:i:s', $rowPrev->scan_datetime);
                    if (!$dtPrev) continue;
                    $tPrev = $dtPrev->format('H:i:s');

                    // sore: 15:00–23:59
                    if ($tPrev >= '15:00:00' && $tPrev <= '23:59:59') {
                        $eveningPrev[] = $tPrev;
                    }
                }

                if (!empty($eveningPrev)) {
                    // -------------------------
                    // SHIFT MALAM (prevDate -> dateToday)
                    // -------------------------
                    sort($eveningPrev);
                    sort($morningToday);

                    $shiftDetailName = 'SHIFT MALAM';
                    $date_in         = $prevDate;
                    $time_in         = $eveningPrev[0];
                    $date_out        = $dateToday;
                    $time_out        = end($morningToday);

                } else {
                    // -------------------------
                    // Tidak ada sore kemarin -> anggap SHIFT PAGI IN_ONLY
                    // -------------------------
                    sort($morningToday);

                    $shiftDetailName = 'SHIFT PAGI';
                    $date_in         = $dateToday;
                    $time_in         = $morningToday[0];   // cuma punya jam masuk
                    $date_out        = null;
                    $time_out        = null;               // lupa checkout
                }

            } else {
                // pola lain yang belum dihandle → tetap warning
                echo json_encode([
                    "title"   => "Warning",
                    "message" => $employee->name. " | ". $scanDatetime. "The security log around " . $dateToday . " is incomplete to determine PAGI/MALAM SHIFT.",
                    "theme"   => "error",
                ]);
                return;
            }
        }


        // --- 5. Ambil data shift detail untuk employee ini (SHIFT SECURITY + detail yang dipilih) ---

        $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, c.name as group_name, d.name as detail_name");
        $this->db->from('shift_employees b');
        $this->db->join('shifts c', 'c.id = b.shift_id');
        $this->db->join('shift_details d', 'd.shift_id = c.id');
        $this->db->where('b.employee_id', $employee->id);
        $this->db->where('c.name', 'SHIFT SECURITY');     // nama grup shift
        $this->db->where('d.name', $shiftDetailName);     // "SHIFT PAGI" / "SHIFT MALAM"
        $shiftRow = $this->db->get()->row();

        $baseShiftData = [
            'shift_start'  => $shiftRow->start       ?? null,
            'shift_end'    => $shiftRow->end         ?? null,
            'shift_name'   => $shiftRow->group_name  ?? null,   // "SHIFT SECURITY"
            'shift_detail' => $shiftRow->detail_name ?? null,   // "SHIFT PAGI"/"SHIFT MALAM"
            'shift_days'   => $shiftRow->days        ?? null,
        ];

        // --- 6. Upsert ke attandances: 1 row per (employee, date_in) ---

        $this->db->from('attandances');
        $this->db->where('number', $employee->number);
        $this->db->where('date_in', $date_in);
        $attendance = $this->db->get()->row();

        $data_att = array_merge($baseShiftData, [
            'number'   => $employee->number,
            'date_in'  => $date_in,
            'time_in'  => $time_in,
            'date_out' => $date_out,
            'time_out' => $time_out,
            'status'   => 'UPLOAD',
        ]);

        if (empty($attendance)) {
            $this->crud->create('attandances', $data_att);
            $action = 'Created';
        } else {
            $this->crud->update('attandances', ['id' => $attendance->id], $data_att);
            $action = 'Updated';
        }

        echo json_encode([
            "title"   => $action,
            "message" => $employee->name . " | " . $date_in . " IN $time_in OUT $time_out ($shiftDetailName)",
            "theme"   => "success",
        ]);
    }
}
