<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mobile extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->default = $this->load->database('default', TRUE);
        $this->load->library('uuid');
    }

    function index()
    {
        show_404();
    }

    function uuid_key()
    {
        $id = $this->uuid->v4();
        $id = str_replace('-', '', $id);
        return $id;
    }

    function autoid($table)
    {
        $date = date("Ymd");
        $sql = $this->db->query("SELECT max(`id`) as kode FROM $table WHERE id like '%$date%'");
        $row = $sql->row();
        $kode = $row->kode;

        if ($kode == NULL) {
            $autoid        = $date . sprintf("%06s", $kode + 1);
        } else {
            $autoid        = (int) $kode + 1;
        }

        return $autoid;
    }

    function query($query)
    {
        $query = $this->db->query($query);
        $records = $query->result_object();
        return $records;
    }

    function read($table, $like = [], $where = [], $limit = "", $orderfield = "", $orderby = "", $groupby = [])
    {
        $this->db->select('*');
        $this->db->from($table);
        if ($where != []) {
            $this->db->where($where);
        }
        if ($like != []) {
            $this->db->like($like);
        }
        if ($limit != "") {
            $this->db->limit($limit);
        }
        if ($orderby != "") {
            $this->db->order_by($orderfield, $orderby);
        }
        if ($groupby != []) {
            $this->db->group_by($groupby);
        }
        $records = $this->db->get()->row();
        return $records;
    }

    function reads($table, $like = [], $where = [], $limit = "", $orderfield = "", $orderby = "", $groupby = [])
    {
        $this->db->select('*');
        $this->db->from($table);
        if ($where != []) {
            $this->db->where($where);
        }
        if ($like != []) {
            $this->db->like($like);
        }
        if ($limit != "") {
            $this->db->limit($limit);
        }
        if ($orderby != "") {
            $this->db->order_by($orderfield, $orderby);
        }
        if ($groupby != []) {
            $this->db->group_by($groupby);
        }
        $records = $this->db->get()->result_object();
        return $records;
    }

    function create($table, $values, $username)
    {
        $id = $this->autoid($table);
        $data = array_merge($values, [
            "id" => $id,
            "created_by" => $username,
            "created_date" => date('Y-m-d H:i:s')
        ]);

        if ($this->db->insert($table, $data)) {
            $this->logs("Create", json_encode($data), $table, $username);
            $this->approvals($table, $id, $username);
            return json_encode(array("title" => "Good Job", "message" => "Data Saved Successfully", "theme" => "success"));
        } else {
            return log_message('error', 'There is an error in your system or data');
        }
    }

    function update($table, $where, $values, $username)
    {
        $data   = array_merge($values, [
            "updated_by" => $username,
            "updated_date" => date('Y-m-d H:i:s')
        ]);

        $dataBefore = $this->read($table, [], $where);

        $this->db->where($where);
        if ($this->db->update($table, $data)) {
            $this->logs("Update Before", json_encode($dataBefore), $table, $username);
            $this->logs("Update New", json_encode($data), $table, $username);

            $read = $this->read($table, [], $where);
            $this->approvals($table, @$read->id, $username);

            return json_encode(array("title" => "Good Job", "message" => "Data Updated Successfully", "theme" => "success"));
        } else {
            return log_message('error', 'There is an error in your system or data');
        }
    }

    function delete($table, $data, $username)
    {
        $dataBefore = $this->read($table, [], $data);

        if ($this->db->delete($table, $data)) {
            $this->logs("Delete", json_encode($dataBefore), $table, $username);
            return json_encode(array("title" => "Good Job", "message" => "Data Deleted Successfully", "theme" => "success"));
        } else {
            return log_message('error', 'There is an error in your system or data');
        }
    }

    function upload($filename, $extension, $path, $id = [], $table = "", $field = "")
    {
        if (!empty(@$_FILES[$filename]["name"])) {
            //Setting Upload Image
            $file = $_FILES[$filename]["name"];
            $extension_explode = explode('.', $file);
            $extension_final = strtolower(end($extension_explode));
            $size = $_FILES[$filename]['size'];
            $temporary = $_FILES[$filename]['tmp_name'];
            $newname  = round(microtime(true)) . '.' . $extension_final;

            if (in_array($extension_final, $extension) === true || $file == "") {
                if ($size < 2097152) {
                    if ($file == "") {
                        if ($id == []) {
                            //
                        } else {
                            $records = $this->read($table, $id);
                            return @$records->$field;
                        }
                    } else {
                        if ($id == []) {
                            //
                        } else {
                            $records = $this->read($table, $id);
                            @unlink('"' . $records->$field . '"');
                        }

                        @move_uploaded_file($temporary, $path . $newname);
                        return $newname;
                    }
                } else {
                    show_error("Your file is too big a maximum of 2 mb", 200, "File Upload Error");
                    exit;
                }
            } else {
                show_error("Your extension file is not recognized", 200, "File Upload Error");
                exit;
            }
        } else {
            return null;
        }
    }

    function logs($action, $records, $table, $username)
    {
        $id = $this->autoid('logs');
        $data = array(
            "created_by" => $username,
            "created_date" => date('Y-m-d H:i:s'),
            "ip_address" => $this->input->ip_address(),
            "action" => $action,
            "menu" => $table,
            "description" => $records
        );

        $this->db->insert('logs', $data);
    }

    function approvals($table, $table_id, $username)
    {
        $this->apps = $this->load->database('apps', TRUE);

        $query = $this->db->query("DESCRIBE $table");
        $fields = $query->result_array();

        $user = $this->read('users', [], ["username" => $username]);
        $approval = $this->read('approvals', [], ["table_name" => $table, "departement_id" => @$user->departement_id]);

        $fieldExists = false;
        foreach ($fields as $field) {
            if ($field['Field'] == "approved") {
                $fieldExists = true;
                break;
            }
        }

        if ($fieldExists) {
            if (!empty($approval)) {
                $formApprove = [
                    "approved" => 1,
                    "approved_to" => $approval->user_approval_1,
                    "approved_by" => $username,
                ];

                $this->db->where(["id" => $table_id]);
                $this->db->update($table, $formApprove);

                $userFr = $this->read("users", [], ["username" => $approval->user_approval_1]);

                if (!empty(@$userFr->api_key)) {
                    $this->apps->where('api_key', @$userFr->api_key);
                    $userAp = $this->apps->get('users')->row();

                    // $this->firebaseApproval(@$userAp->token, $user->name, "Sent a request to approve data " . strtoupper(str_replace("_", " ", $table)));
                }
            }
        }
    }

    function firebaseApproval($token, $title, $messages)
    {
        $url = "https://fcm.googleapis.com/fcm/send";

        $notification = [
            'title' => $title,
            'body' => $messages
        ];

        $extraNotificationData = ["message" => $messages];

        $fields = array(
            "registration_ids" => array($token),
            "notification" => $notification
        );

        $headers = array(
            "Authorization:key=AAAACAakFgs:APA91bEodY7R-M67PGckJNeyU6PgIw0vvnCNH5zbYLPkxe8Z2kxUbyr21ec6YdnA5zbFH_fKZr4vOuJOS2dzllM13OGyZO3AId013_cBdD732J0h1iaj1IkglZmUSdcB412gcmd3w6Nv",
            "Content-Type: application/json"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        // if($result === FALSE){
        //     die('CURL FAILED' . curl_error($ch));
        // }else{
        //     die(json_encode(array("code" => 200, "message" => "Send Firebase Success", "theme" => "success", "results" => json_decode($result))));
        // }
        curl_close($ch);
    }

    function connectionInfo()
    {
        switch (connection_status()) {
            case CONNECTION_NORMAL:
                $txt = json_encode(array("title" => "Normal", "message" => "Connection is in a normal state", "theme" => "conn_normal"));
                break;
            case CONNECTION_ABORTED:
                $txt = json_encode(array("title" => "Normal", "message" => "Connection is aborted", "theme" => "conn_aborted"));
                break;
            case CONNECTION_TIMEOUT:
                $txt = json_encode(array("title" => "Normal", "message" => "Connection is timed out", "theme" => "conn_timeout"));
                break;
            case (CONNECTION_ABORTED & CONNECTION_TIMEOUT):
                $txt = json_encode(array("title" => "Normal", "message" => "Connection is aborted and timed out", "theme" => "conn_aborted_timeout"));
                break;
            default:
                $txt = json_encode(array("title" => "Unknown", "message" => "UNKNOWN Connection", "theme" => "conn_unknown"));
                break;
        }

        echo $txt;
    }

    public function pushNotification($token, $title, $message)
    {
        $url = "https://askara.hris-server.com//api/notifications/pushNotification";

        $data = [
            "token" => $token, // <<< Gunakan token asli di sini
            "title" => $title,
            "message" => $message,
        ];

        // --- PERBAIKAN: Definisikan Header Content-Type ---
        $headers = [
            "Content-Type: application/json",
            // 'Content-Length: ' . strlen(json_encode($data)) // Opsional
        ];
        // --- AKHIR PERBAIKAN ---

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // --- Terapkan Header ---
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Hapus baris SSL VERIFY kecuali ada error SSL
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $error = curl_error($ch);

        curl_close($ch);

        // if ($error) {
        //     echo "Curl Error: " . $error;
        // } else {
        //     echo $response;
        // }
    }
}
