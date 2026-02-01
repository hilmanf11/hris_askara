<?php
error_reporting(0);
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Live_attandances extends CI_Controller
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

        $this->restrict_user = $this->accessmodule->restrictSelf();
    }

    //HALAMAN UTAMA
    public function index()
    {
        if (empty($this->session->username)) {
            redirect('error_session');
        } elseif ($this->checkuserAccess($this->id_menu()) > 0) {
            $data['button'] = $this->getbutton($this->id_menu());
            $attandance = $this->attendance($this->session->number);
            $data['attandance'] = $attandance;

            $this->load->view('template/header', $data);
            $this->load->view('attandance/live_attandances');
        } else {
            redirect('error_access');
        }
    }

    private function attendance($number)
    {
        if (!empty($number)) {
            $employee = $this->crud->read("employees", [], ["number" => $number]);

            if ($employee) {
                $today     = date('Y-m-d');
                $yesterday = date('Y-m-d', strtotime('-1 day'));
                $currentTime = date("H:i:s");

                // Cek dahulu apakah karyawan punya shift ID = 20241126000001 (pagi/malam)
                $this->db->select('b.shift_id');
                $this->db->from('shift_employees b');
                $this->db->where('b.employee_id', $employee->id);
                $empShift = $this->db->get()->row();

                $isSecurityShift = ($empShift->shift_id == '20241126000001');

                $this->db->from('attandances');
                $this->db->where('number', $number);
                $this->db->group_start();
                $this->db->where('date_in', $today);
                $this->db->or_where('date_out', $today);
                $this->db->group_end();
                $this->db->order_by('date_in', 'DESC');
                $this->db->order_by('time_in', 'DESC');
                $this->db->limit(1);
                $attandances = $this->db->get()->row();

                if (!$attandances && $currentTime < "03:00:00") {
                    $this->db->from('attandances');
                    $this->db->where('number', $number);
                    $this->db->where('date_in', $yesterday);
                    $this->db->where("(date_out IS NULL OR date_out = '0000-00-00')", null, false);
                    $this->db->order_by('time_in', 'DESC');
                    $this->db->limit(1);
                    $attandances = $this->db->get()->row();
                }

                $dayNum   = date('N');
                $workingType = ($dayNum >= 6) ? 'WEEKEND' : 'WEEKDAY';
                $tolerance_hour_min = date("H:i:s", strtotime('-2 Hour', strtotime(@$attandances->time_in)));
                $tolerance_hour_plus = date("H:i:s", strtotime('+2 Hour', strtotime(@$attandances->time_out)));

                if ($isSecurityShift) { // jika security

                    // -------------------------------
                    //  CASE 1: SHIFT PAGI / MALAM
                    //  Selalu weekday dan ignore weekend logic
                    // -------------------------------

                    $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, c.name, d.name as shift_name");
                    $this->db->from('shift_employees b');
                    $this->db->join('shifts c', 'c.id = b.shift_id');
                    $this->db->join('shift_details d', 'd.shift_id = c.id');
                    $this->db->where('b.employee_id', $employee->id);

                    // Tidak pakai d.working karena shift pagi/malam selalu weekday di DB

                    // Filter berdasarkan jam ± 2 jam
                    $this->db->where("TIME(d.start) >= TIME('$tolerance_hour_min')");
                    $this->db->where("TIME(d.start) <= TIME('$tolerance_hour_plus')");

                    $shift = $this->db->get()->row();
                } else {

                    // -------------------------------
                    //  CASE 2: NORMAL SHIFT
                    //  Ikuti Weekday & Weekend
                    // -------------------------------
                    $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, c.name, d.name as shift_name");
                    $this->db->from('shift_employees b');
                    $this->db->join('shifts c', 'c.id = b.shift_id');
                    $this->db->join('shift_details d', 'd.shift_id = c.id');
                    $this->db->where('b.employee_id', $employee->id);

                    // Filter working weekday/weekend
                    $this->db->where('d.working', $workingType);

                    // Filter berdasarkan jam ± 2 jam
                    $this->db->where("TIME(d.start) >= TIME('$tolerance_hour_min')");
                    $this->db->where("TIME(d.start) <= TIME('$tolerance_hour_plus')");

                    $shift = $this->db->get()->row();
                }

                $folder_in = date("Ym", strtotime(@$attandances->date_in));
                $folder_out = date("Ym", strtotime(@$attandances->date_out));

                if (!empty($attandances->foto_in)) {
                    if (substr($attandances->foto_in, -4) == "jpeg") {
                        $foto_in = 'assets/document/attendance/' . $folder_in . '/' . substr($attandances->foto_in, -15);
                    } else {
                        $foto_in = 'assets/document/attendance/' . $folder_in . '/' . substr($attandances->foto_in, -14);
                    }
                } else {
                    $foto_in = null;
                }

                if (!empty($attandances->foto_out)) {
                    if (substr($attandances->foto_out, -4) == "jpeg") {
                        $foto_out = 'assets/document/attendance/' . $folder_out . '/' . substr($attandances->foto_out, -15);
                    } else {
                        $foto_out = 'assets/document/attendance/' . $folder_out . '/' . substr($attandances->foto_out, -14);
                    }
                } else {
                    $foto_out = null;
                }

                $data = array(
                    "title" => "Success",
                    "message" => "Data Found",
                    "theme" => "success",
                    "results" => array(
                        "start" => @$shift->start,
                        "end" => @$shift->end,
                        "shift_name" => @$shift->name,
                        "shift_detail" => @$shift->shift_name,
                        "date_in" => @$attandances->date_in,
                        "date_out" => @$attandances->date_out,
                        "time_in" => @$attandances->time_in,
                        "time_out" => @$attandances->time_out,
                        "foto_in" => $foto_in,
                        "foto_out" => $foto_out,
                    )
                );
                return $data;
            } else {
                return [];
            }
        } else {
            return "Cannot Process your request";
        }
    }

    public function checkLocationAttendance()
    {

        if ($this->input->post()) {
            $post = $this->input->post();

            $latitudeUser = floatval($post['latitude']);
            $longitudeUser = floatval($post['longitude']);

            $this->db->select("*");
            $this->db->from('office_locations');
            $this->db->where('link', 'http://ias.askara-int.com/hris/');
            $companies = $this->db->get()->result_array();

            $matchedCompany = null;
            $nearestDistance = null;
            $latOffice = null;
            $longOffice = null;
            $companyCode = null;

            foreach ($companies as $company) {

                $distance = $this->calculateDistance(
                    $latitudeUser,
                    $longitudeUser,
                    floatval($company['latitude']),
                    floatval($company['longitude'])
                );

                // Simpan jarak paling dekat
                if ($nearestDistance === null || $distance < $nearestDistance) {
                    $nearestDistance = $distance;
                    $companyCode = $company['number'];
                    $latOffice = $company['latitude'];
                    $longOffice = $company['longitude'];
                }

                // cek apakah jarak <= radius di database
                if ($distance <= floatval($company['radius'])) {
                    $matchedCompany = [
                        "company_name" => $company['name'],
                        "company_latitude" => $company['latitude'],
                        "company_longitude" => $company['longitude'],
                        "company_code" => $company['number'],
                        "radius_limit" => $company['radius'],
                        "distance_meters" => round($distance, 2),
                        "user_latitude" => $latitudeUser,
                        "user_longitude" => $longitudeUser
                    ];
                    break; // stop kalau ada yang masuk radius
                }
            }

            if ($matchedCompany) {

                die(json_encode([
                    "title" => "Success",
                    "message" => "Location Accepted",
                    "theme" => "success",
                    "data" => $matchedCompany
                ]));
            }

            die(json_encode([
                "title" => "Failed",
                "message" => "Titik absen anda tidak sesuai dengan Lokasi yang ditentukan. Jarak dengan titik lokasi absen adalah " . round($nearestDistance, 2) . " Meter",
                "theme" => "error",
            ]));
        } else {
            show_error("Cannot process your request");
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $earthRadius * $angle;
    }

    //GET DATA
    public function checkin()
    {
        $folder = date("Ym");
        $path = 'assets/document/attendance/' . $folder;

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        if ($this->input->post()) {
            $post = $this->input->post();
            $number = $this->session->number;
            $employee = $this->crud->read("employees", [], ["number" => $number]);

            if ($post['date_in'] != "" && $post['time_in'] != "") {
                if ($post['date_out'] != "" && $post['time_out'] != "") {
                    $date_in = $post['date_in'];
                    $foto = $this->crud->upload('foto', ['png', 'jpg', 'jpeg'], 'assets/document/attendance/' . $folder . '/', ["number" => $number, "date_in" => $date_in], "attandances", "foto_out");

                    $this->insertLog($number, $post, $foto, "OUT");

                    $this->db->select('*');
                    $this->db->from('attandances');
                    $this->db->where('number', $number);
                    $this->db->where("date_in = '$date_in'");
                    $attandance_in = $this->db->get()->row();

                    if ($post['outside_radius'] == 1) {
                        $status_out = 1;
                    } else {
                        $status_out = 0;
                    }

                    $data = array(
                        'number' => $number,
                        'date_out' => $post['date_out'],
                        'time_out' => $post['time_out'],
                        'foto_out' => @$foto,
                        'status' => 'WEB_' . ($post['position'] ?? "UNKNOWN"),
                        'status_out' => $status_out,
                        'lat_out' => $post['latitude'],
                        'lng_out' => $post['longitude'],
                    );

                    if ($this->crud->update('attandances', ["id" => @$attandance_in->id], $data)) {

                        $response = [
                            "title" => "Success",
                            "message" => "Attendance Out Completed",
                            "theme" => "success"
                        ];

                        die(json_encode($response));
                    } else {
                        show_error("Attendance Out UnComplete");
                    }
                } else {
                    $foto = $this->crud->upload('foto', ['png', 'jpg', 'jpeg'], 'assets/document/attendance/' . $folder . '/');

                    $this->insertLog($number, $post, $foto, "IN");

                    $tolerance_hour_min = date("H:i:s", strtotime('-2 Hour', strtotime($post['time_in'])));
                    $tolerance_hour_plus = date("H:i:s", strtotime('+2 Hour', strtotime($post['time_in'])));
                    $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, c.name, d.name as shift_name");
                    $this->db->from('shift_employees b');
                    $this->db->join('shifts c', 'c.id = b.shift_id');
                    $this->db->join('shift_details d', 'd.shift_id = c.id');
                    $this->db->where('b.employee_id', $employee->id);
                    $this->db->where("TIME(d.start) >=  TIME('$tolerance_hour_min') && TIME(d.start) <= TIME('$tolerance_hour_plus')");
                    $shift = $this->db->get()->row();

                    if ($post['outside_radius'] == 1) {
                        $status_in = 1;
                    } else {
                        $status_in = 0;
                    }
                    $data = array(
                        'number' => $number,
                        'date_in' => $post['date_in'],
                        'time_in' => $post['time_in'],
                        'foto_in' => @$foto,
                        "shift_start" => @$shift->start,
                        "shift_end" => @$shift->end,
                        "shift_name" => @$shift->shift_name,
                        "shift_detail" => @$shift->name,
                        "shift_days" => @$shift->days,
                        'status' => 'WEB_' . ($post['position'] ? $post['position'] : "UNKNOWN"),
                        'status_in' => $status_in,
                        'lat_in' => $post['latitude'],
                        'lng_in' => $post['longitude'],
                    );

                    if ($this->crud->create('attandances', $data)) {
                        $response = [
                            "title" => "Success",
                            "message" => "Attendance In Completed",
                            "theme" => "success",
                            "results" => $shift
                        ];

                        die(json_encode($response));
                    } else {
                        show_error("Attendance In UnComplete");
                    }
                }
            } else {
                show_error("Cannot Process your request");
            }
        } else {
            show_error("Cannot Process your request");
        }
    }

    private function insertLog($number, $data, $foto, $status)
    {
        if ($status == 'IN') {
            $attendance_date = $data['date_in'];
            $attendance_time = $data['time_in'];
        } else {
            $attendance_date = $data['date_out'];
            $attendance_time = $data['time_out'];
        }
        $logData = [
            "employee_id"   => $number,
            "attendance_date"  => $attendance_date,
            "attendance_time"  => $attendance_time,
            "latitude" => $data['latitude'],
            "longitude" => $data['longitude'],
            "is_outside_radius" => $data['outside_radius'] ?  $data['outside_radius'] : 0,
            "photo"       => $foto,
            "status"       => $status,
        ];

        return $this->crud->create("attendance_location_logs", $logData);
    }

    //GET DATATABLES
    public function history()
    {
        if ($this->input->post()) {
            $month = $this->input->post('month');
            $number = $this->session->number;
            //Employee
            $this->db->select("a.*");
            $this->db->from('attandances a');
            $this->db->where('a.number', $number);
            $this->db->like('a.date_in', $month, 'after');
            $this->db->group_by("a.date_in");
            $this->db->order_by("a.date_in", "asc");


            $result = $this->db->get()->result_array();
            $data = [];
            foreach ($result as $r) {
                $data[] = [
                    'id' => $r['id'],
                    'date' => $r['date_in'],
                    'in'   => $r['time_in'] ? $r['time_in'] : '',
                    'out'  => $r['time_out'] ? $r['time_out'] : '',
                    'foto_in'  => $r['foto_in'] ? $r['foto_in'] : '',
                    'foto_out'  => $r['foto_out'] ? $r['foto_out'] : '',
                    'status_in'  => $r['status_in'],
                    'status_out' => $r['status_out'],
                ];
            }

            echo json_encode($data);
        }
    }
}
