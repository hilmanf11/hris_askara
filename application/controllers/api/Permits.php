<?php
header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Permits extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('mobile');
    }

    //HALAMAN UTAMA
    public function index()
    {
        show_error("Cannot Process your request");
    }

    public function reads($api_key = "", $limit = 30)
    {
        if ($api_key != "") {
            $user = $this->mobile->read("users", [], ["api_key" => $api_key]);

            if ($user) {
                $employee = $this->mobile->read("employees", [], ["number" => $user->number]);

                $this->db->select('a.*,
                    c.number as employee_number,
                    c.name as employee_name,
                    d.name as division_name,
                    e.name as departement_name,
                    f.name as departement_sub_name,
                    g.name as permit_type_name,
                    h.name as reason_name,
                    i.name as request_name
                ');
                $this->db->from('permits a');
                $this->db->join('employees c', 'a.employee_id = c.id');
                $this->db->join('divisions d', 'c.division_id = d.id');
                $this->db->join('departements e', 'c.departement_id = e.id');
                $this->db->join('departement_subs f', 'c.departement_sub_id = f.id');
                $this->db->join('permit_types g', 'a.permit_type_id = g.id');
                $this->db->join('reasons h', 'a.reason_id = h.id');
                $this->db->join('users i', 'a.created_by = i.username');
                // $this->db->where('a.deleted', 0);
                $this->db->where('c.deleted', 0);
                $this->db->where('c.status', 0);
                $this->db->like('c.id', $employee->id);
                $this->db->order_by('a.permit_date', 'DESC');
                $this->db->limit($limit);
                $records = $this->db->get()->result_array();

                $data = array();
                foreach ($records as $record) {
                    if (!empty($record['attachment'])) {
                        if (substr($record['attachment'], -4) == "jpeg") {
                            $attachment = "assets/image/attandance/" . substr($record['attachment'], -15);
                        } else {
                            $attachment = "assets/image/attandance/" . substr($record['attachment'], -14);
                        }
                    } else {
                        $attachment = null;
                    }

                    $data[] = array_merge($record, array("file_attachment" => $attachment));
                }

                die(json_encode(array(
                    "title" => "Success",
                    "message" => "Data Found",
                    "theme" => "success",
                    "results" => $data
                )));
            } else {
                show_error("Api Key Invalid");
            }
        } else {
            show_error("Cannot Process your request");
        }
    }

    // LIST WITH YEAR
    public function list($api_key = "", $year = "")
    {
        if ($api_key != "") {
            $user = $this->mobile->read("users", [], ["api_key" => $api_key]);

            if ($user) {
                $employee = $this->mobile->read("employees", [], ["number" => $user->number]);

                $this->db->select('a.*,
                    c.number as employee_number,
                    c.name as employee_name,
                    d.name as division_name,
                    e.name as departement_name,
                    f.name as departement_sub_name,
                    g.name as permit_type_name,
                    h.name as reason_name,
                    i.name as request_name
                ');
                $this->db->from('permits a');
                $this->db->join('employees c', 'a.employee_id = c.id');
                $this->db->join('divisions d', 'c.division_id = d.id');
                $this->db->join('departements e', 'c.departement_id = e.id');
                $this->db->join('departement_subs f', 'c.departement_sub_id = f.id');
                $this->db->join('permit_types g', 'a.permit_type_id = g.id');
                $this->db->join('reasons h', 'a.reason_id = h.id');
                $this->db->join('users i', 'a.created_by = i.username');
                // $this->db->where('a.deleted', 0);
                $this->db->where('YEAR(a.permit_date)', $year);
                $this->db->where('c.deleted', 0);
                $this->db->where('c.status', 0);
                $this->db->like('c.id', $employee->id);
                $this->db->order_by('a.permit_date', 'DESC');
                // $this->db->limit($limit);
                $records = $this->db->get()->result_array();

                $data = array();
                foreach ($records as $record) {
                    if (!empty($record['attachment'])) {
                        if (substr($record['attachment'], -4) == "jpeg") {
                            $attachment = "assets/image/attandance/" . substr($record['attachment'], -15);
                        } else {
                            $attachment = "assets/image/attandance/" . substr($record['attachment'], -14);
                        }
                    } else {
                        $attachment = null;
                    }

                    $data[] = array_merge($record, array("file_attachment" => $attachment));
                }

                die(json_encode(array(
                    "title" => "Success",
                    "message" => "Data Found",
                    "theme" => "success",
                    "results" => $data
                )));
            } else {
                show_error("Api Key Invalid");
            }
        } else {
            show_error("Cannot Process your request");
        }
    }

    public function readTypes()
    {
        $send = $this->mobile->reads('permit_types');
        echo json_encode($send);
    }

    public function readReason($permit_type_id)
    {
        if ($permit_type_id != "") {
            $send = $this->mobile->reads('reasons', [], ["permit_type_id" => $permit_type_id]);
            echo json_encode($send);
        } else {
            show_error("Cannot Process your request");
        }
    }

    public function readLeave($api_key, $permit_type_id)
    {
        if ($api_key != "" && $permit_type_id != "") {
            $user = $this->mobile->read("users", [], ["api_key" => $api_key]);

            if ($user) {
                $employee = $this->mobile->read("employees", [], ["number" => $user->number]);

                $year = date("Y");
                $calendar = $this->mobile->reads("calendars", ["trans_date" => $year], ["permit" => 1]);

                $permitType = $this->mobile->read('permit_types', [], ["id" => $permit_type_id, "cutoff" => "YES"]);
                $permits = $this->mobile->reads('permits', ["employee_id" => $employee->id, "DATE_FORMAT(permit_date, '%Y')" => $year], ["permit_type_id" => @$permitType->id]);
                $totalPermit = 0;
                foreach ($permits as $permit) {
                    $totalPermit += $permit->duration;
                }

                echo json_encode(["total" => (12 - $totalPermit - count($calendar))]);
            } else {
                show_error("Api Key Invalid");
            }
        } else {
            show_error("Cannot Process your request");
        }
    }

    public function readPermitAvailable($api_key)
    {
        if ($api_key != "") {
            $user = $this->mobile->read("users", [], ["api_key" => $api_key]);
            $post = $this->input->post();
            $permit_type_id = $post['permit_type_id'];
            $reason_id = $post['reason_id'];
            $date_from = $post['date_from'];
            $date_to = $post['date_to'];

            if ($user) {
                $employee = $this->mobile->read("employees", [], ["number" => $user->number]);

                $this->db->select("b.balance, b.type_balance");
                $this->db->from('assign_time_off_master a');
                $this->db->join('assign_time_off_detail b', 'a.id = b.assign_time_off_id');
                $this->db->join('employees c', 'b.employee_id = c.number');
                $this->db->group_start();
                $this->db->where('b.start_date <=', $date_to);
                $this->db->group_start();
                $this->db->where('b.end_date IS NULL', null, false);
                $this->db->or_where("b.end_date = '0000-00-00'", null, false);
                $this->db->or_where('b.end_date >=', $date_from);
                $this->db->group_end();
                $this->db->group_end();

                $this->db->like('a.permit_type_id', $permit_type_id);
                $this->db->like('a.reason_id', $reason_id);
                $this->db->like('c.id', $employee->id);
                $this->db->order_by("b.id", "desc");
                $assign_time_off = $this->db->get()->row();

                $date_from = new DateTime($date_from);
                $year = $date_from->format('Y');
                $month = $date_from->format('m');
                $previous_year = $year - 1;

                $calendar = $this->mobile->reads("calendars", ["trans_date" => $year], ["permit" => 1]);

                $permitType = $this->mobile->read('permit_types', [], ["id" => $permit_type_id, "cutoff" => "YES"]);
                if (empty($permitType)) {
                    echo json_encode(["total" => 0]);
                    return;
                }
                $sign_date = $employee->date_sign;

                $total_current = 0;
                $total_previous = 0;
                $balance = 0;
                if (strtotime('+1 year', strtotime($sign_date)) <= time()) {
                    $permits = $this->mobile->reads('permits', ["employee_id" => $employee->id, "DATE_FORMAT(permit_date, '%Y')" => $year], ["permit_type_id" => @$permitType->id]);
                    $totalPermit = 0;
                    foreach ($permits as $permit) {
                        $totalPermit += $permit->duration;
                    }

                    $expiry_date = new DateTime("$year-03-31");

                    $totalPermit_previous = 0;

                    if ($date_from <= $expiry_date) {
                        // Ambil data cuti untuk tahun sebelumnya (untuk akumulasi)
                        $permits_previous = $this->mobile->reads('permits', [
                            "employee_id" => $employee->id,
                            "DATE_FORMAT(permit_date, '%Y')" => $previous_year
                        ], ["permit_type_id" => @$permitType->id]);
                        foreach ($permits_previous as $permit) {
                            $totalPermit_previous += $permit->duration;
                        }
                        // Hitung sisa cuti tahun sebelumnya
                        $calendar_previous = $this->mobile->reads("calendars", ["trans_date" => $previous_year], ["permit" => 1]);
                        $total_leave_previous = 12 - $totalPermit_previous - count($calendar_previous);
                        $total_previous = max(0, $total_leave_previous);
                    }

                    $total_leave_current = 12 - $totalPermit - count($calendar);
                    $total_current = max(0, $total_leave_current);
                    $balance = $total_previous + $total_current;
                } else {
                    if (strtotime('+1 month', strtotime($sign_date)) <= time()) {
                        $permits = $this->mobile->read('permits', ["employee_id" => $employee->id, "DATE_FORMAT(permit_date, '%Y-%m')" => "$year-$month"], ["permit_type_id" => @$permitType->id]);

                        if ($permits) {
                            $balance = 0;
                        } else {
                            $balance = 1;
                        }
                    }
                }

                if (!empty($assign_time_off)) {
                    if ($assign_time_off->type_balance == 'add') {
                        $balance = $balance + $assign_time_off->balance;
                    } else {
                        $balance = $assign_time_off->balance;
                    }
                }

                echo json_encode(["total" => $balance]);
            } else {
                show_error("Api Key Invalid");
            }
        } else {
            show_error("Cannot Process your request");
        }
    }

    public function createData($api_key = "")
    {

        $this->apps = $this->load->database('apps', TRUE);

        if ($api_key != "") {
            $user = $this->mobile->read("users", [], ["api_key" => $api_key]);

            if ($user) {
                if ($this->input->post()) {
                    $post = $this->input->post();

                    $employee = $this->mobile->read("employees", [], ["number" => $user->number]);
                    $attachment = $this->mobile->upload('attachment', ['png', 'jpg', 'jpeg'], 'assets/image/attandance/');

                    //approvalUser
                    $approval = $this->mobile->read("approvals", [], ["employee_id" => $employee->id, "table_name" => "permits"]);
                    $approvalUser = $this->mobile->read("users", [], ["username" => $approval->user_approval_1]);

                    $date_from = strtotime($post['date_from']);
                    $date_to = strtotime($post['date_to']);

                    $send = "";
                    $leave = $post['leave'];
                    for ($i = $date_from; $i <= $date_to; $i += (60 * 60 * 24)) {
                        $permit_date = date('Y-m-d', $i);

                        if (($leave - 1) >= 0) {
                            $post_final = array(
                                "approved" => 1,
                                "approved_to" => $approval->user_approval_1,
                                "approved_by" => $user->username,
                                "employee_id" => $employee->id,
                                "permit_type_id" => $post['permit_type_id'],
                                "reason_id" => $post['reason_id'],
                                "trans_date" => date("Y-m-d"),
                                "permit_date" => $permit_date,
                                "duration" => "1",
                                "leave" => ($leave - 1),
                                "note" => $post['remarks'],
                                "attachment" => $attachment
                            );

                            $permits = $this->mobile->reads('permits', [], ["employee_id" => $employee->id, "permit_date" => $permit_date]);

                            if (count($permits) > 0) {
                                show_error("Your Permit application has been sent, please change to another date");
                            } else {
                                if ($this->mobile->create('permits', $post_final, $user->username)) {
                                    if (!empty($approvalUser->api_key)) {
                                        $this->apps->where('api_key', @$approvalUser->api_key);
                                        $userAp = $this->apps->get('users')->row();

                                        $message = "Sent a request to approve data Permit";

                                        $this->mobile->pushNotification(@$userAp->token, $user->name, $message);
                                    }
                                    $send = json_encode(array(
                                        "title" => "Success",
                                        "message" => "Permit Success Send",
                                        "theme" => "success"
                                    ));
                                } else {
                                    show_error("Permit Failed To Send");
                                }
                            }
                        } else {
                            show_error("Your permit has exceeded the limit");
                        }

                        $leave = ($leave - 1);
                    }

                    echo $send;
                } else {
                    show_error("Cannot Process your request");
                }
            } else {
                show_error("Api Key Invalid");
            }
        } else {
            show_error("Cannot Process your request");
        }
    }

    public function cancel($api_key = "")
    {
        if ($api_key != "") {
            $user = $this->mobile->read("users", [], ["api_key" => $api_key]);
            if ($user) {
                if ($this->input->post()) {
                    $data = $this->input->post();

                    $delete = $this->mobile->delete("permits", ['id' => $data['id']], $user->username);
                    echo $delete;
                } else {
                    show_error("Cannot Process your request");
                }
            } else {
                show_error("Api Key Invalid");
            }
        } else {
            show_error("Cannot Process your request");
        }
    }

    // NOTIF
    // function getAccessToken()
    // {
    //     // Path relatif ke file JSON
    //     $pathToFile = APPPATH . 'controllers/mobile/hris-app.json';

    //     $serviceAccount = json_decode(file_get_contents($pathToFile), true);
    //     $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    //     $iat = time();
    //     $exp = $iat + 360;
    //     $claimSet = base64_encode(json_encode([
    //         'iss' => $serviceAccount['client_email'],
    //         'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
    //         'aud' => 'https://oauth2.googleapis.com/token',
    //         'exp' => $exp,
    //         'iat' => $iat,
    //     ]));

    //     $signatureInput = $header . '.' . $claimSet;
    //     $signature = null;
    //     openssl_sign($signatureInput, $signature, $serviceAccount['private_key'], 'SHA256');

    //     $jwt = $signatureInput . '.' . base64_encode($signature);
    //     $ch = curl_init('https://oauth2.googleapis.com/token');
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    //         'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    //         'assertion' => $jwt,
    //     ]));

    //     $response = curl_exec($ch);
    //     curl_close($ch);

    //     $responseArray = json_decode($response, true);
    //     return $responseArray['access_token'];
    // }

    // function pushNotification($token, $title, $messages)
    // {
    //     $url = 'https://fcm.googleapis.com/v1/projects/hris-app-26211/messages:send';
    //     // $tokenUser = 'fjt-9yygSH-kcAiPoMY7cY:APA91bEpNVcIP47v5flZ4nSgJJ97i3pkgDip_zO5wlcuAOwOjSrEHIju-v_jqGruP-aW83O9qHhBVoAdv-w0-BmaXTp5QzUFw0LKfac_S4ZJ-DnoIk6NbCg';

    //     // Data notifikasi dari input
    //     $notification = [
    //         'title' => $title,
    //         'body' => $messages,
    //     ];

    //     // Payload yang akan dikirim ke Firebase
    //     $payload = [
    //         'message' => [
    //             'token' => $token,
    //             'notification' => $notification,
    //         ]
    //     ];

    //     $headers = [
    //         'Authorization: Bearer ' . $this->getAccessToken(),
    //         'Content-Type: application/json',
    //     ];

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    //     $result = curl_exec($ch);
    //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     curl_close($ch);
    // }
}
