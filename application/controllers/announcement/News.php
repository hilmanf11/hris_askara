<?php

date_default_timezone_set("Asia/Bangkok");

defined('BASEPATH') or exit('No direct script access allowed');



class News extends CI_Controller

{

    public function __construct()

    {

        parent::__construct();

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $this->load->library('session');

        $this->load->model('crud');
        $this->load->model('mobile');

    }



    //HALAMAN UTAMA

    public function index()

    {

        if (empty($this->session->username)) {

            redirect('error_session');

        } elseif ($this->checkuserAccess($this->id_menu()) > 0) {

            $data['button'] = $this->getbutton($this->id_menu());



            $this->load->view('template/header', $data);

            $this->load->view('announcement/news');

        } else {

            redirect('error_access');

        }

    }



    //GET DATA

    public function reads()

    {

        $post = isset($_POST['q']) ? $_POST['q'] : "";

        $send = $this->crud->reads('news', ["name" => $post]);

        echo json_encode($send);

    }



    //GET DATATABLES

    public function datatables()

    {

        if ($this->input->post()) {

            $page = $this->input->post('page');

            $rows = $this->input->post('rows');

            //Pagination 1-10

            $page   = isset($page) ? intval($page) : 1;

            $rows   = isset($rows) ? intval($rows) : 10;

            $offset = ($page - 1) * $rows;

            $result = array();

            //Select Query

            $this->db->select('*');

            $this->db->from('news');

            $this->db->where('deleted', 0);

            $this->db->order_by('name', 'ASC');

            //Total Data

            $totalRows = $this->db->count_all_results('', false);

            //Limit 1 - 10

            $this->db->limit($rows, $offset);

            //Get Data Array

            $records = $this->db->get()->result_array();



            $data = array();

            foreach ($records as $record) {

                // $division = $this->crud->read("divisions", [], ["id" => $record['division_id']]);

                // $departement_id = json_decode($record['departement_id'], false);
                $division_id = json_decode($record['division_id'], false);
                
                

                // $departement_value = "";
                $division_value = "";

                foreach ($division_id as $divisions => $value) {

                    // $dept = $this->crud->read("departements", [], ["id" => $value]);



                    // $departement_value .= $dept->name.", ";
                    $division = $this->crud->read("divisions", [], ["id" => $value]);

                    $division_value .= $division->name.", ";

                }



                $data[] = array(

                    "id" => $record['id'],
                    "division_id" => $division_id,
                    "division_name" => $division_value,

                    // "division_id" => $record['division_id'],

                    // "division_name" => $division->name,

                    // "departement_id" => $departement_id,

                    // "departement_name" => $departement_value,

                    "name" => $record['name'],

                    "description" => $record['description'],

                    "start_date" => $record['start_date'],

                    "finish_date" => $record['finish_date'],

                    "attachment" => $record['attachment'],

                    "created_by" => $record['created_by'],

                    "created_date" => $record['created_date'],

                    "updated_by" => $record['updated_by'],

                    "updated_date" => $record['updated_date'],

                );

            }



            //Mapping Data

            $result['total'] = $totalRows;

            $result = array_merge($result, ['rows' => $data]);

            echo json_encode($result);

        }

    }



    //CREATE DATA

    public function create()

    {

        if ($this->input->post()) {

            $post = $this->input->post();



            $attachment = $this->crud->upload('attachment', ["jpg", "png", "jpeg", "pdf"], 'assets/document/news/');



            $send = $this->crud->create('news', array(
                "division_id" => json_encode($post['division_id']),

                // "division_id" => $post['division_id'],

                // "departement_id" => json_encode($post['departement_id']),

                "name" => $post['name'],

                "start_date" => $post['start_date'],

                "finish_date" => $post['finish_date'],

                "description" => $post['description'],

                "attachment" => $attachment

            ));

            if ($send) {
                $this->apps = $this->load->database('apps', TRUE);

                $title = $post['name'];
                $message = strip_tags($post['description']);

                $userAp = $this->apps->get('users')->result_array();

                foreach ($userAp as $user) {
                    if (!empty(@$user['token'])) {
                        $this->mobile->pushNotification($user['token'], $title, $message);
                    }
                }
            }

            echo $send;

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

            $attachment = $this->crud->upload('attachment', ["jpg", "png", "jpeg", "pdf"], 'assets/document/news/', ["id" => $id], "news", "attachment");



            $send = $this->crud->update('news', ["id" => $id], array(
                    "division_id" => json_encode($post['division_id']),

                    // "division_id" => $post['division_id'],

                    // "departement_id" => json_encode($post['departement_id']),

                    "name" => $post['name'],

                    "start_date" => $post['start_date'],

                    "finish_date" => $post['finish_date'],

                    "description" => $post['description'],

                    "attachment" => $attachment,

                ));



            echo $send;

        } else {

            show_error("Cannot Process your request");

        }

    }



    //DELETE DATA

    public function delete()

    {

        $data = $this->input->post();

        $send = $this->crud->delete('news', $data);

        echo $send;

    }



    //PRINT & EXCEL DATA

    public function print($option = "")

    {

        if ($option == "excel") {

            $format  = date("Ymd");

            header("Content-type: application/vnd-ms-excel");

            header("Content-Disposition: attachment; filename=news_$format.xls");

        }



        //Config

        $this->db->select('*');

        $this->db->from('config');

        $config = $this->db->get()->row();



        $this->db->select('*');

        $this->db->from('news');

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

                            <small>MASTER DIVISION</small>

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

                <th>Start Date</th>

                <th>Finish Date</th>

                <th>Title</th>

            </tr>';

        $no = 1;

        foreach ($records as $data) {

            $html .= '<tr>

                    <td>' . $no . '</td>

                    <td>' . $data['start_date'] . '</td>

                    <td>' . $data['finish_date'] . '</td>

                    <td>' . $data['name'] . '</td>';

            $no++;

        }



        $html .= '</table></body></html>';

        echo $html;

    }

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

