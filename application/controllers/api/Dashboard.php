<?php
header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('mobile');
        $this->load->model('emails');
    }

    //HALAMAN UTAMA
    public function index()
    {
        show_error("Cannot Process your request");
    }

    public function attendance($api_key = "")
    {
        $this->output->delete_cache();
        if (!empty($api_key)) {
            $user = $this->mobile->read("users", [], ["api_key" => $api_key]);
            
            if ($user) {
                $attandances = $this->mobile->read("attandances", [], ["number" => $user->number, "date_in" => date("Y-m-d")], 1, "", "", ["number", "date_in"]);
                $employee = $this->mobile->read("employees", [], ["number" => $user->number]);
                
                $tolerance_hour_min = date("H:i:s", strtotime('-2 Hour', strtotime(@$attandances->time_in)));
                $tolerance_hour_plus = date("H:i:s", strtotime('+2 Hour', strtotime(@$attandances->time_out)));
                $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, c.name, d.name as shift_name");
                $this->db->from('shift_employees b');
                $this->db->join('shifts c', 'c.id = b.shift_id');
                $this->db->join('shift_details d', 'd.shift_id = c.id');
                $this->db->where('b.employee_id', $employee->id);
                $this->db->where("TIME(d.start) >= TIME('$tolerance_hour_min') && TIME(d.start) <= TIME('$tolerance_hour_plus')");
                $shift = $this->db->get()->row();

                $folder_in = date("Ym", strtotime(@$attandances->date_in));
                $folder_out = date("Ym", strtotime(@$attandances->date_out));

                if(!empty($attandances->foto_in)){
                    if(substr($attandances->foto_in, -4) == "jpeg"){
                        $foto_in = 'assets/document/attendance/'.$folder_in.'/'. substr($attandances->foto_in, -15);
                    }else{
                        $foto_in = 'assets/document/attendance/'.$folder_in.'/'. substr($attandances->foto_in, -14);
                    }
                }else{
                    $foto_in = null;
                }
                
                if(!empty($attandances->foto_out)){
                    if(substr($attandances->foto_out, -4) == "jpeg"){
                        $foto_out = 'assets/document/attendance/'.$folder_out.'/'. substr($attandances->foto_out, -15);
                    }else{
                        $foto_out = 'assets/document/attendance/'.$folder_out.'/'. substr($attandances->foto_out, -14);
                    }
                }else{
                    $foto_out = null;
                }

                die(json_encode(array(
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
                )));
            } else {
                show_error("Api Key Invalid");
            }
        }else{
            show_error("Cannot Process your request");
        }
    }

    public function users($api_key = "")
    {
        $this->output->delete_cache();

        if (!empty($api_key)) {
            $user = $this->mobile->read("users", [], ["api_key" => $api_key]);

            if ($user) {
                $employee = $this->mobile->read("employees", [], ["number" => $user->number]);
                $position = $this->mobile->read("positions", [], ["id" => $employee->position_id]);

                if($user->avatar == null || $user->avatar == ""){
                    $avatar = "assets/image/users/default.png";
                }else{
                    $avatar = "assets/image/users/".$user->avatar;
                }

                die(json_encode(array(
                    "title" => "Success",
                    "message" => "Data Found",
                    "theme" => "success",
                    "results" => array(
                        "departement_id" => $user->departement_id,
                        "employee_id" => $user->number,
                        "name" => $user->name,
                        "position" => $user->position,
                        "access" => $position->access,
                        "avatar" => $avatar,
                    )
                )));
            } else {
                show_error("Api Key Invalid");
            }
        } else {
            show_error("Cannot Process your request");
        }
    }

    public function cutOff()
    {
        $send = $this->mobile->read('cutoff', [], [], "", "start", "desc");
        echo json_encode($send);
    }

    public function dayOff($api_key = "")
    {
        $this->output->delete_cache();
        if (!empty($api_key)) {
            $user = $this->mobile->read("users", [], ["api_key" => $api_key]);
            $today = date("Y-m-d");
            $departement_id = @$user->departement_id;
            
            if ($user) {
                $employees = $this->mobile->query("SELECT * FROM employees WHERE departement_id = '$departement_id' and `status` = '0'");

                $datas = array();
                foreach ($employees as $employee) {
                    $attandance = $this->mobile->read("attandances", [], ["date_in" => $today, "number" => $employee->number]);
                    $permit = $this->mobile->read("permits", [], ["permit_date" => $today, "employee_id" => $employee->id]);
                    $permit_type = $this->mobile->read("permit_types", [], ["id" => @$permit->permit_type_id]);

                    if(!empty($employee->image_profile)){
                        if(substr($employee->image_profile, -4) == "jpeg"){
                            $avatar = "assets/image/employee/profile/" . substr($employee->image_profile, -15);
                        }else{
                            $avatar = "assets/image/employee/profile/" . substr($employee->image_profile, -14);
                        }
                    }else{
                        $avatar = "assets/image/users/default.png";
                    }

                    //Shift and Setting Group
                    $tolerance_hour_min = date("H:i:s", strtotime('-2 Hour', strtotime(@$attandance->time_in)));
                    $tolerance_hour_plus = date("H:i:s", strtotime('+2 Hour', strtotime(@$attandance->time_in)));
                    $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, c.name, d.name as shift_name");
                    $this->db->from('shift_employees b');
                    $this->db->join('shifts c', 'c.id = b.shift_id');
                    $this->db->join('shift_details d', 'd.shift_id = c.id');
                    $this->db->where('b.employee_id', $employee->id);
                    if (@$attandance->time_in > "23:00:00") {
                        $this->db->where("d.start >= '$tolerance_hour_min'");
                    } elseif (@$attandance->time_in != "") {
                        $this->db->where("d.start >= '$tolerance_hour_min' and d.start <= '$tolerance_hour_plus'");
                    }
                    $shift = $this->db->get()->row();

                    if (@$attandance->time_in == null) {
                        if (@$permit->employee_id != null) {
                            $attandance_status = @strtoupper($permit_type->name);
                            $color = "blue";
                        } else {
                            $attandance_status = "ABSENCE";
                            $color = "red";
                        }
                    } else {
                        if (@$shift->start >= @$attandance->time_in) {
                            $attandance_status = "ON TIME";
                            $color = "green";
                        } elseif (@$shift->start == null) {
                            $attandance_status = "UN SETTING";
                            $color = "red";
                        } elseif (@$shift->start <= @$attandance->time_in) {
                            $attandance_status = "LATE";
                            $color = "orange";
                        } else {
                            $attandance_status = "ERROR";
                            $color = "red";
                        }
                    }

                    if($attandance_status != "ABSENCE"){
                        $datas[] = array(
                            "image" => $avatar,
                            "name" => $employee->name,
                            "time_in" => @$attandance->time_in,
                            "time_out" => @$attandance->time_out,
                            "description" => $attandance_status,
                            "color" => $color
                        );
                    }
                }

                die(json_encode(array(
                    "title" => "Success",
                    "message" => "Data Found",
                    "theme" => "success",
                    "results" => $datas
                )));
            } else {
                show_error("Api Key Invalid");
            }
        } else {
            show_error("Cannot Process your request");
        }
    }

    public function attandanceSummary($api_key = ""){
        $this->output->delete_cache();

        if (!empty($api_key)) {
            $user = $this->mobile->read("users", [], ["api_key" => $api_key]);
            
            if ($user) {
                
                $month = date("Y-m");
                $today = date("Y-m-d");
                $number = $user->number;
                $employee = $this->mobile->read("employees", [], ["number" => $number]);

                $start = strtotime($month . '-01');
                $finish = strtotime(date("Y-m-t"));

                $permits = 0;
                $working = 0;
                $absence = 0;
                $late = 0;
                $notyet = 0;
                $details = array();
                for ($i = $start; $i <= $finish; $i += (60 * 60 * 24)) {
                    $working_date = date('Y-m-d', $i);

                    //Working Calendar
                    $this->db->select('description');
                    $this->db->from('calendars');
                    $this->db->where('trans_date', $working_date);
                    $holiday = $this->db->get()->row();

                    //Attandance and Overtime
                    $this->db->select("b.date_in, b.time_in, b.date_out, b.time_out, c.request_code, c.start, c.end, c.duration, c.remarks");
                    $this->db->from('employees a');
                    $this->db->join('attandances b', 'a.number = b.number');
                    $this->db->join('overtimes c', 'a.id = c.employee_id and b.date_in = c.trans_date', 'left');
                    $this->db->where('b.date_in =', $working_date);
                    $this->db->where('a.number', $number);
                    $this->db->order_by('a.name', 'asc');
                    $this->db->order_by('b.date_in', 'asc');
                    $attandance = $this->db->get()->row();

                    //Resignation
                    $this->db->select('*');
                    $this->db->from('resignations');
                    $this->db->where('employee_id', $employee->id);
                    $this->db->where('resign_date <', $working_date);
                    $resignation = $this->db->get()->row();

                    //Permit
                    $this->db->select("a.*, c.name as reason_name, d.name as permit_name");
                    $this->db->from('permits a');
                    $this->db->join('employees b', 'a.employee_id = b.id');
                    $this->db->join('reasons c', 'a.reason_id = c.id');
                    $this->db->join('permit_types d', 'a.permit_type_id = d.id');
                    $this->db->where('b.number', $number);
                    $this->db->where('a.permit_date <=', $working_date);
                    $this->db->where('a.permit_date >=', $working_date);
                    $permit = $this->db->get()->row();

                    //Shift and Setting Group
                    $this->db->select("d.start, d.end, d.days, d.working, d.tolerance, d.name as shift_name");
                    $this->db->from('employees a');
                    $this->db->join('shift_employees b', 'a.id = b.employee_id');
                    $this->db->join('shifts c', 'c.id = b.shift_id');
                    $this->db->join('shift_details d', 'd.shift_id = c.id');
                    $this->db->where('a.number', $number);
                    $shift = $this->db->get()->row();

                    $this->db->select("*");
                    $this->db->from('change_days');
                    $this->db->where('employee_id', $employee->id);
                    $this->db->where('start', $working_date);
                    $change_day = $this->db->get()->row();

                    $this->db->select("*");
                    $this->db->from('change_days');
                    $this->db->where('employee_id', $employee->id);
                    $this->db->where('end', $working_date);
                    $change_day_end = $this->db->get()->row();

                    $time_in = @$shift->start;
                    $permit_remarks = @$permit->note;
                    $permit_name = @$permit->permit_name;

                    //Jika hari kerja nya adalah 5 hari
                    if (@$shift->days == "5") {
                        //sabtu dan minggu libur
                        if (date('w', $i) !== '0' && date('w', $i) !== '6') {
                            $weekend = "";
                        } else {
                            $weekend = "Weekend";
                        }
                    } else {
                        //minggu doang libur
                        if (date('w', $i) !== '0') {
                            $weekend = "";
                        } else {
                            $weekend = "Weekend";
                        }
                    }

                    if (@$time_in >= @$attandance->time_in) {
                        $status_masuk = "ON TIME";
                    } elseif (@$time_in == null) {
                        $status_masuk = "UN SETTING";
                    } elseif (@$time_in <= @$attandance->time_in) {
                        $status_masuk = "LATE";
                    } else {
                        $status_masuk = "ERROR";
                    }

                    //jika status tanggal merah nya kosong
                    if (@$holiday->description == null) {
                        //cek apakah harinya minggu jika iya maka default Weekend
                        if ($weekend == "Weekend") {
                            $holiday = $weekend;
                        } elseif ($permit_name != null) {
                            $holiday = @$holiday->description;
                        } else {
                            $holiday = $permit_remarks;
                        }
                        //isi dengan tanggal merah
                    } else {
                        $holiday = @$holiday->description;
                    }

                    if (@$attandance->time_in == null) {
                        if ($holiday == "Weekend") {
                            $permits += 0;
                            $working += 0;
                            $absence += 0;
                            $late += 0;
                            $notyet += 0;
                        } elseif ($permit_name != null) {
                            $permits += 1;
                            $working += 0;
                            $absence += 0;
                            $late += 0;
                            $notyet += 0;
                        } elseif($today <= $working_date) {
                            $permits += 0;
                            $working += 0;
                            $absence += 0;
                            $late += 0;
                            $notyet += 1;
                        }else{
                            $permits += 0;
                            $working += 0;
                            $absence += 1;
                            $late += 0;
                            $notyet += 0;
                        }
                    } else {
                        if ($holiday == "Weekend") {
                            $permits += 0;
                            $working += 0;
                            $absence += 0;
                            $late += 0;
                            $notyet += 0;
                        } elseif (@$permit_name != null) {
                            $permits += 1;
                            $working += 0;
                            $absence += 0;
                            $late += 0;
                            $notyet += 0;
                        } else {
                            if (@$status_masuk == "LATE") {
                                $permits += 0;
                                $working += 1;
                                $absence += 0;
                                $late += 1;
                                $notyet += 0;
                            } else if (@$status_masuk == "ON TIME") {
                                $permits += 0;
                                $working += 1;
                                $absence += 0;
                                $late += 0;
                                $notyet += 0;
                            } else {
                                $permits += 0;
                                $working += 1;
                                $absence += 0;
                                $late += 0;
                                $notyet += 0;
                            }
                        }
                    }

                    if (@$data['days'] == "5") {
                        //sabtu dan minggu libur
                        if (date('w', $i) !== '0' && date('w', $i) !== '6') {
                            if (!empty($holiday)) {
                                $status = "HOLIDAY";
                                $remarks = $holiday;
                            } else {
                                if (@$permit->permit_name != null) {
                                    $status = "PERMIT";
                                    $remarks = @$permit->note;
                                } elseif (@$shift->start == null && !empty($attandance->time_in)) {
                                    $status = "UN SETTING";
                                    $remarks = "Check Shift Employee";
                                } elseif ($data['date_sign'] > $working_date) {
                                    $status = "NOT JOIN YET";
                                    $remarks = "";
                                } elseif (!empty($resignation)) {
                                    $status = "RESIGN";
                                    $remarks = $resignation->remarks;
                                } elseif (@$change_day_end->end != null || @$change_day->start != null) {
                                    $status = "CHANGE DAY";
                                    $remarks = @$change_day_end->remarks;
                                } elseif($today < $working_date) {
                                    $status = "NOT YET";
                                    $remarks = "";
                                } elseif (empty($attandance->time_in) && empty($attandance->time_out)) {
                                    $status = "ABSENCE";
                                    $remarks = "";
                                } elseif (@$shift->start <= @$attandance->time_in) {
                                    $status = "LATE";
                                    $remarks = "";
                                } elseif (@$shift->start >= @$attandance->time_in) {
                                    $status = "ON TIME";
                                    $remarks = "";
                                }
                            }
                        } else {
                            if (@$change_day_end->end != null || @$change_day->start != null) {
                                $status = "CHANGE DAY";
                                $remarks = @$change_day->remarks;
                            } else {
                                $status = "WEEKEND";
                                $remarks = "";
                            }
                        }
                    } else {
                        //sabtu doang libur
                        if (date('w', $i) !== '0') {
                            if (!empty($holiday)) {
                                $status = "HOLIDAY";
                                $remarks = $holiday;
                            } else {
                                if (@$permit->permit_name != null) {
                                    $status = "PERMIT";
                                    $remarks = @$permit->note;
                                } elseif (@$shift->start == null && !empty($attandance->time_in)) {
                                    $status = "UN SETTING";
                                    $remarks = "Check Shift Employee";
                                } elseif ($employee->date_sign >= $working_date) {
                                    $status = "NOT JOIN YET";
                                    $remarks = "";
                                } elseif (!empty($resignation)) {
                                    $status = "RESIGN";
                                    $remarks = $resignation->remarks;
                                } elseif (@$change_day_end->end != null || @$change_day->start != null) {
                                    $status = "CHANGE DAY";
                                    $remarks = @$change_day_end->remarks;
                                } elseif($today < $working_date) {
                                    $status = "NOT YET";
                                    $remarks = "";
                                } elseif (empty($attandance->time_in) && empty($attandance->time_out)) {
                                    $status = "ABSENCE";
                                    $remarks = "";
                                } elseif (@$shift->start <= @$attandance->time_in) {
                                    $status = "LATE";
                                    $remarks = "";
                                } elseif (@$shift->start >= @$attandance->time_in) {
                                    $status = "ON TIME";
                                    $remarks = "";
                                }
                            }
                        } else {
                            if (@$change_day_end->end != null || @$change_day->start != null) {
                                $status = "CHANGE DAY";
                                $remarks = @$change_day->remarks;
                            } else {
                                $status = "WEEKEND";
                                $remarks = "";
                            }
                        }
                    }

                    if ($status == "WEEKEND") {
                        $color = "red";
                    } elseif ($status == "PERMIT") {
                        $color = "blue";
                    } elseif ($status == "CHANGE DAY") {
                        $color = "purple";
                    } elseif ($status == "LATE") {
                        $color = "orange";
                    } elseif ($status == "ON TIME") {
                        $color = "green";
                    } elseif ($status == "NOT YET") {
                        $color = "gray";
                    } elseif ($status == "NOT JOIN YET") {
                        $color = "pink";
                    } else {
                        $color = "red";
                    }

                    $details[] = array(
                        "trans_date" => $working_date,
                        "time_in" => @$attandance->time_in,
                        "time_out" => @$attandance->time_out,
                        "status" => $status,
                        "color" => $color,
                        "remarks" => $remarks,
                    );
                }

                echo json_encode(array(
                    "working" => $working, 
                    "permit" => $permits, 
                    "absence" => $absence, 
                    "notyet" => $notyet, 
                    "late" => $late, 
                    "details" => $details));
            } else {
                show_error("Api Key Invalid");
            }
        } else {
            show_error("Cannot Process your request");
        }
    }
}
