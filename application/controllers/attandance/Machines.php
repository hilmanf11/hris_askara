<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Machines extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('crud');

        //VALIDASI FORM
        $this->form_validation->set_rules('number', 'Code', 'required|min_length[1]|max_length[20]|is_unique[machines.number]');
    }

    //HALAMAN UTAMA
    public function index()
    {
        if (empty($this->session->username)) {
            redirect('error_session');
        } elseif ($this->checkuserAccess($this->id_menu()) > 0) {
            $data['button'] = $this->getbutton($this->id_menu());

            $this->load->view('template/header', $data);
            $this->load->view('attandance/machines');
        } else {
            redirect('error_access');
        }
    }

    //GET DATA
    public function reads()
    {
        $post = isset($_POST['q']) ? $_POST['q'] : "";
        $send = $this->crud->reads('machines', ["name" => $post], [], "", "name", "asc");
        echo json_encode($send);
    }

    public function testing2(){
        $this->load->library('Zkteco_lib');
        $logs = $this->zkteco_lib->getAttendance("192.168.3.181");
        $this->zkteco_lib->disconnect();

        $start_date = '2025-07-01';
        $end_date   = '2025-07-30';

        $filtered_logs = array_filter($logs, function($log) use ($start_date, $end_date) {
            $log_date = substr($log['record_time'], 0, 10);
            return ($log_date >= $start_date) && ($log_date <= $end_date);
        });

        // Tampilkan dalam format JSON
        header('Content-Type: application/json');
        echo json_encode(array_values($filtered_logs), JSON_PRETTY_PRINT);
    }
    
    // public function testing(){
    //     $IP = "192.168.3.181";
    //     $Key = "0";

    //     $Connect = fsockopen($IP, "80", $errno, $errstr, 1);
    //     if ($Connect) {
    //         $soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">$Key</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
    //         $newLine = "\r\n";

    //         fputs($Connect, "POST /iWsService HTTP/1.0" . $newLine);
    //         fputs($Connect, "Content-Type: text/xml" . $newLine);
    //         fputs($Connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
    //         fputs($Connect, $soap_request . $newLine);

    //         $buffer = "";
    //         while ($Response = fgets($Connect, 1024)) {
    //             $buffer .= $Response;
    //         }

    //         // Parsing XML response
    //         function Parse_Data($data, $p1, $p2) {
    //             $data = " " . $data;
    //             $hasil = "";
    //             $awal = strpos($data, $p1);
    //             if ($awal != "") {
    //                 $akhir = strpos(strstr($data, $p1), $p2);
    //                 if ($akhir != "") {
    //                     $hasil = substr($data, $awal + strlen($p1), $akhir - strlen($p1));
    //                 }
    //             }
    //             return $hasil;
    //         }

    //         $buffer = Parse_Data($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
    //         echo "<pre>" . print_r($buffer, true) . "</pre>";
    //     } else {
    //         echo "Koneksi Gagal";
    //     }
    // }

    //GET DATATABLES
    public function datatables()
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
            $this->db->from('machines');
            $this->db->where('deleted', 0);
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

    //CREATE DATA
    public function create()
    {
        if ($this->input->post()) {
            if ($this->form_validation->run() == TRUE) {
                $post   = $this->input->post();
                $send   = $this->crud->create('machines', $post);
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
            $send = $this->crud->update('machines', ["id" => $id], $post);
            echo $send;
        } else {
            show_error("Cannot Process your request");
        }
    }

    //DELETE DATA
    public function delete()
    {
        $data = $this->input->post();
        $send = $this->crud->delete('machines', $data);
        echo $send;
    }

    //UPLOAD DATA
    public function upload()
    {
        error_reporting(0);
        $ip = $this->input->post('ip');
        $port = $this->input->post('port');
        $keys = $this->input->post('keys');
        $period_from = $this->input->post('period_from');
        $period_to = $this->input->post('period_to');
        $type = $this->input->post('type');

        $this->load->library('Zkteco_lib');
        $logs = $this->zkteco_lib->getAttendance($ip);
        $this->zkteco_lib->disconnect();

        $filtered_logs = array_filter($logs, function($log) use ($period_from, $period_to) {
            $log_date = substr($log['record_time'], 0, 10);
            return ($log_date >= $period_from) && ($log_date <= $period_to);
        });

        usort($filtered_logs, function($a, $b) {
            return strtotime($a['record_time']) - strtotime($b['record_time']);
        });

        if(count($filtered_logs) > 0){
            header('Content-Type: application/json');
            die(json_encode(array("code" => 200, "message" => "Data Founded", "results" => array_values($filtered_logs), "total" => count($filtered_logs))));
        }else{
            die(json_encode(array("code" => 500, "message" => "Not Connected to Machine Attandance")));
        }
    }

    public function uploadcreate()
    {
        if ($this->input->post()) {
            $data = $this->input->post('data');
            $record_time = explode(" ", $data['record_time']);
            $date_in = $record_time[0];
            $time_in = $record_time[1];

            //Employee
            $this->db->select('*');
            $this->db->from('employees');
            $this->db->where('machine_code', $data['user_id']);
            $employee = $this->db->get()->row();

            //Jika Terdaftar di master employee
            if (!empty($employee->number)) {
                //Attandance
                $this->db->select('*');
                $this->db->from('attandances');
                $this->db->where('number', $employee->number);
                $this->db->where("(date_in = '$date_in' or date_out = '$date_in')");
                // $this->db->or_where("date_out = '$date_in'");
                $attandance = $this->db->get()->row();

                $tolerance_hour_min = date("H:i:s", strtotime('-2 Hour', strtotime(@$time_in)));
                $tolerance_hour_plus = date("H:i:s", strtotime('+2 Hour', strtotime(@$time_in)));
                $this->db->select("b.start, b.end, b.days, b.working, b.tolerance, b.name");
                $this->db->from('shift_employees a');
                $this->db->join('shift_details b', 'a.shift_id = b.shift_id');
                $this->db->where('a.employee_id', $employee->id);
                $this->db->where("TIME(b.start) >=  TIME('$tolerance_hour_min') && TIME(b.start) <= TIME('$tolerance_hour_plus')");
                $shift_in = $this->db->get()->row();

                $this->db->select("b.start, b.end, b.days, b.working, b.tolerance, b.name");
                $this->db->from('shift_employees a');
                $this->db->join('shift_details b', 'a.shift_id = b.shift_id');
                $this->db->where('a.employee_id', $employee->id);
                $shift_out = $this->db->get()->row();

                // $time_begin = strtotime($date_in . " " . $time_in);
                // $time_end = strtotime($date_in . " " . @$shift->end);
                // $tomorrow = date("Y-m-d", strtotime('+1 days', strtotime($date_in)));

                // $diff = $time_begin - $time_end;
                // $hour = floor($diff / (60 * 60));

                // if ($hour < 0) {
                //     $date_out = $date_in;
                // } else {
                //     $date_out = $tomorrow;
                // }

                if(!empty(@$shift_in->start)){
                    $data_attandance = array(
                        'number' => $employee->number,
                        'date_in' => $date_in,
                        'time_in' => $time_in,
                        'shift_start' => @$shift_in->start,
                        'shift_end' => @$shift_in->end,
                        'shift_name' => @$shift_in->name,
                        'shift_days' => @$shift_in->days,
                        'status' => "MACHINE",
                    );
                }elseif(!empty(@$shift_out->start)){
                    $data_attandance = array(
                        'number' => $employee->number,
                        'date_out' => $date_in,
                        'time_out' => $time_in,
                        'status' => "MACHINE",
                    );
                }else{
                    die(json_encode(array("title" => "Warning", "message" => $employee->name . " Not Yet Shift Setting", "theme" => "error")));
                }

                if (!empty($attandance->number)) {
                    // if(@$attandance->date_out == ""){
                    //     $data_attandance = array(
                    //         'number' => $employee->number,
                    //         'date_in' => $date_in,
                    //         'time_in' => $time_in,
                    //         'status' => "MACHINE",
                    //     );
                    // }else{
                    //     $data_attandance = array(
                    //         'number' => $employee->number,
                    //         'date_out' => $date_in,
                    //         'time_out' => $time_in,
                    //         'status' => "MACHINE",
                    //     );
                    // }

                    $this->crud->update('attandances', ["id" => $attandance->id], $data_attandance, "logs");
                    echo json_encode(array("title" => "Updated", "message" => $employee->name . " | " . $date_in . " " . $time_in . " Update data Check In", "theme" => "success"));
                } else {
                    $this->crud->create('attandances', $data_attandance, "logs");
                    echo json_encode(array("title" => "Created", "message" => $employee->name . " | " . $date_in . " " . $time_in . " Create data Check In", "theme" => "success"));
                }
            } else {
                echo json_encode(array("title" => "Warning", "message" => "User ID ".$data['user_id']." in Machine Un Registered in Data Employee", "theme" => "error"));
            }
        }
    }

    //PRINT & EXCEL DATA
    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=machines_$format.xls");
        }

        //Config
        $this->db->select('*');
        $this->db->from('config');
        $config = $this->db->get()->row();

        $this->db->select('*');
        $this->db->from('machines');
        $this->db->where('deleted', 0);
        $this->db->order_by('name', 'ASC');
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
                            <small>MASTER MACHINE ATTANDANCE</small>
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
                <th>Name</th>
                <th>Type</th>
                <th>Ip Address</th>
                <th>Port</th>
                <th>Keys</th>
            </tr>';
        $no = 1;
        foreach ($records as $data) {
            $html .= '<tr>
                        <td>' . $no . '</td>
                        <td>' . $data['number'] . '</td>
                        <td>' . $data['name'] . '</td>
                        <td>' . $data['type'] . '</td>
                        <td>' . $data['ip'] . '</td>
                        <td>' . $data['port'] . '</td>
                        <td>' . $data['keys'] . '</td>
                    </tr>';
            $no++;
        }

        $html .= '</table></body></html>';
        echo $html;
    }
}
