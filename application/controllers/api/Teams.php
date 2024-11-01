<?php
header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Teams extends CI_Controller
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
        show_error("Cannot Process your request");
    }

    public function readService($dateSign = "")
    {
        if ($dateSign == "") {
            $date = $this->input->post('date');
        } else {
            $date = $dateSign;
        }

        $start  = date_create($date);
        $end = date_create(); // waktu sekarang
        $diff  = date_diff($start, $end);
        $d = $diff->d . ' Days ';

        if ($diff->y == 0) {
            $y = '';
        } else {
            $y = $diff->y . ' Years ';
        }

        if ($diff->m == 0) {
            $m = '';
        } else {
            $m = $diff->m . ' Month';
        }

        if ($dateSign == "") {
            echo $y . $m . $d;
        } else {
            return $y . $m;
        }
    }

    public function reads($api_key = "")
    {

        if ($api_key != "") {
            $user = $this->crud->read("users", [], ["api_key" => $api_key]);

            if ($user) {
                $employee = $this->crud->read("employees", [], ["number" => $user->number]);
                $positions = $this->crud->query("SELECT b.id, b.name FROM employees a JOIN positions b ON a.position_id = b.id WHERE a.departement_id = '$employee->departement_id' GROUP BY b.id ORDER BY b.level ASC");

                $datas = array();
                foreach ($positions as $position) {
                    $employees = $this->crud->reads("employees", [], ["position_id" => $position->id, "departement_id" => $employee->departement_id], "20", "name", "asc");

                    $datas2 = array();
                    foreach ($employees as $employee) {
                        if($employee->image_profile == null || $employee->image_profile == ""){
                            $avatar = "assets/image/users/default.png";
                        }else{
                            $avatar = "assets/image/users/".$employee->image_profile;
                        }

                        $datas2[] = array(
                            "avatar" => $avatar,
                            "name" => $employee->name,
                            "birthday" => date("d F Y", strtotime($employee->birthday)),
                            "services" => $this->readService($employee->date_sign),
                        );
                    }

                    $datas[] = array(
                        "position" => $position->name,
                        "details" => $datas2
                     );
                }

                die(json_encode(array(
                    "title" => "Success",
                    "message" => "Data Found",
                    "theme" => "success",
                    "results" => $datas
                )));
            } else {
                show_error("Teams Not Found");
            }
        } else {
            show_error("Cannot Process your request");
        }
    }

    public function positions()
    {
        $positions = $this->crud->reads('positions', [], [], "", "level", "asc");

        die(json_encode(array(
            "title" => "Success",
            "message" => "Data Found",
            "theme" => "success",
            "results" => $positions
        )));
    }
}
