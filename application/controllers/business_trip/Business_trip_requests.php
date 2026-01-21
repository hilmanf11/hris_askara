<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Business_trip_requests extends CI_Controller
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
    }

    //HALAMAN UTAMA
    public function index($id_menu = "")
    {
        if (empty($this->session->username)) {
            redirect('error_session');
        } elseif ($this->checkuserAccess($this->id_menu()) > 0) {
            $data['button'] = $this->getbutton($this->id_menu());
            $data['id_menu'] = $id_menu;


            $this->load->view('template/header', $data);
            $this->load->view('business_trip/business_trip_requests');
        } else {
            redirect('error_access');
        }
    }

    //GET DATA
    public function reads()
    {
        $post = isset($_POST['q']) ? $_POST['q'] : "";
        $send = $this->crud->reads('agreements', ["number" => $post]);
        echo json_encode($send);
    }

    //GET DATATABLES
    public function datatables()
    {
        $filter_divisions = $this->input->get('filter_divisions');
        $filter_departements = $this->input->get('filter_departements');
        $filter_departement_subs = $this->input->get('filter_departement_subs');
        $filter_employees = $this->input->get('filter_employees');

        $this->db->select("
            dr.id,
            dr.request_no,
            dr.request_date,
            dr.trip_type,

            div.name AS division_name,
            dept.name AS departement_name,
            sub.name AS departement_sub_name,

            emp.name AS employee_name,

            dto.date_in,
            dto.date_out,

            dtr.departure_date,
            dtr.return_date,

            COALESCE(dto.group_type, dtr.group_type) AS group_type,

            COALESCE(dto.area_id, dtr.area_id) AS area_id,

            COALESCE(dto.area_text, dtr.area_text) AS area_text,


            dr.created_date,
            dr.updated_date,
            dr.created_by,
            dr.updated_by,
            dre.id as dinas_emp_id
        ");

        $this->db->from('dinas_requests dr');

        // EMPLOYEE
        $this->db->join('dinas_request_employees dre', 'dre.dinas_request_no = dr.request_no');

        // MASTER DATA
        $this->db->join('divisions div', 'div.id = dre.division_id', 'left');
        $this->db->join('departements dept', 'dept.id = dre.departement_id', 'left');
        $this->db->join('departement_subs sub', 'sub.id = dre.departement_sub_id', 'left');
        $this->db->join('employees emp', 'emp.id = dre.employee_id', 'left');

        // TRIP
        $this->db->join('dinas_trip_one dto', 'dto.dinas_request_no = dr.request_no', 'left');

        $this->db->join('dinas_trip_round dtr', 'dtr.dinas_request_no = dr.request_no', 'left');

        // FILTER DATA VALID
        $this->db->where('dr.deleted', 0);

        $this->db->like('dre.employee_id', $filter_employees);
        $this->db->like('dre.division_id', $filter_divisions);
        $this->db->like('dre.departement_id', $filter_departements);
        $this->db->like('dre.departement_sub_id', $filter_departement_subs);

        $query = $this->db->get();

        $rows = [];
        foreach ($query->result() as $r) {

            if ($r->group_type == 'GROUP') {
                $area = $this->crud->read('areas', [], ['id' => $r->area_id])->name;
            } else {
                $area = $r->area_text;
            }

            $rows[] = [
                'id'                    => $r->id,
                'dinas_emp_id'          => $r->dinas_emp_id,
                'request_date'          => $r->request_date,
                'request_no'            => $r->request_no,
                'division_name'         => $r->division_name,
                'departement_name'      => $r->departement_name,
                'departement_sub_name'  => $r->departement_sub_name,
                'employee_name'         => $r->employee_name,

                // PRIORITAS ONE → ROUND
                'date_in'  => $r->date_in ?: $r->departure_date,
                'date_out' => $r->date_out ?: $r->return_date,

                'area'     => $area,
                'created_date'     => $r->created_date,
                'created_by'     => $r->created_by,
                'updated_date'     => $r->updated_date,
                'updated_by'     => $r->updated_by,
                'trip_type'     => $r->trip_type,

            ];
        }

        echo json_encode([
            'total' => count($rows),
            'rows'  => $rows
        ]);
    }

    //CREATE DATA
    public function create()
    {
        $this->db->trans_begin();

        try {

            // ===== AMBIL DATA JSON =====
            $data = json_decode($this->input->post('data'), true);

            if (!$data) {
                throw new Exception('Invalid request data');
            }
            $division_id = $data['division_id'];

            $division = $this->crud->read('divisions', [], ['id' => $division_id]);

            if (!$division) {
                throw new Exception('Division tidak ditemukan');
            }

            $initial_company = $division->number;

            $request_no = $this->generateRequestNo($initial_company);


            // ===== dinas_requests =====
            $dinas_requests = [
                'request_no'   => $request_no,
                'request_date' => $data['request_date'],
                'trip_type'    => $data['trip_type'],
                'division_id'    => $data['division_id'],
                'delivery_required'    => @$data['delivery_required'],
                'vehicle_type'    => @$data['vehicle_type'],
                'emoney_type'    => @$data['emoney_type'],
                'employee_delivery_id'    => @$data['employee_delivery_id'],
                'plat_no'    => @$data['plat_no'],
            ];

            $this->crud->create('dinas_requests', $dinas_requests);

            // ===== EMPLOYEES =====
            foreach ($data['employees'] as $emp) {
                $dinas_request_employees =
                    [
                        'dinas_request_no'  => $request_no,
                        'division_id'       => $emp['division_id'],
                        'departement_id'    => $emp['departement_id'],
                        'departement_sub_id' => $emp['departement_sub_id'],
                        'employee_id'       => $emp['employee_id']
                    ];
                $this->crud->create('dinas_request_employees', $dinas_request_employees);
            }

            $group_type = $data['trip']['group_type'];

            if ($group_type === 'GROUP') {
                $dest['area_id']     = $data['trip']['area_id'];
                $dest['location_id'] = $data['trip']['location_id'];
            } else {
                $dest['area_text']     = $data['trip']['area_text'];
                $dest['location_text'] = $data['trip']['location_text'];
            }


            // // ===== TRIP =====
            if ($data['trip_type'] === 'ONE') {

                $trip = [
                    'dinas_request_no'  => $request_no,
                    'group_type'       => $data['trip']['group_type'],
                    'remarks'          => $data['trip']['remarks'] ?? null,
                    'date_in'  => $data['trip']['date_in'],
                    'time_in'  => $this->_clean_time($data['trip']['time_in']),
                    'date_out' => $data['trip']['date_out'] ?: null,
                    'time_out' => $this->_clean_time($data['trip']['time_out'])
                ];

                $dinas_trip_one = array_merge($trip, $dest);

                $this->crud->create('dinas_trip_one', $dinas_trip_one);
            } else {
                $attachment = null;

                if (!empty($_FILES['attachment']['name'])) {
                    $attachment = $this->crud->upload(
                        'attachment',
                        ['pdf', 'png', 'jpg', 'jpeg', 'heic'],
                        'assets/image/dinas/'
                    );

                    if (!$attachment) {
                        throw new Exception('Upload attachment gagal');
                    }
                }

                $trip = [
                    'dinas_request_no'  => $request_no,
                    'remarks'          => $data['trip']['remarks'] ?? null,
                    'group_type'       => $data['trip']['group_type'],
                    'departure_dropoff_location' => $data['trip']['departure_dropoff_location'],
                    'departure_pickup_location'  => $data['trip']['departure_pickup_location'],
                    'departure_date' => $data['trip']['departure_date'],
                    'departure_time' => $this->_clean_time($data['trip']['departure_time']),
                    'return_pickup_location' => $data['trip']['return_pickup_location'],
                    'return_date' => $data['trip']['return_date'] ?: null,
                    'return_time' => $this->_clean_time($data['trip']['return_time']),
                    'attachment' => $attachment
                ];

                $dinas_trip_round = array_merge($trip, $dest);

                $this->crud->create('dinas_trip_round', $dinas_trip_round);
            }

            // ===== COMMIT =====
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Database error');
            }

            $this->db->trans_commit();

            echo json_encode([
                'status'  => true,
                'message' => 'Business trip berhasil disimpan'
            ]);
        } catch (Exception $e) {

            $this->db->trans_rollback();

            echo json_encode([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // ===== HELPER =====
    private function _clean_time($val)
    {
        if (!$val || strpos($val, '_') !== false) {
            return null;
        }
        return $val;
    }

    //UPDATE DATA
    public function update($id)
    {
        $this->db->trans_begin();

        try {

            // ===== AMBIL DATA JSON =====
            $data = json_decode($this->input->post('data'), true);

            if (!$data) {
                throw new Exception('Invalid request data');
            }

            $request = $this->crud->read('dinas_requests', [], ['id' => $id]);

            if (!$request) {
                throw new Exception('Request tidak ditemukan');
            }

            $request_no = $request->request_no;

            $division_id = $data['division_id'];

            $division = $this->crud->read('divisions', [], ['id' => $division_id]);

            if (!$division) {
                throw new Exception('Division tidak ditemukan');
            }

            // ===== dinas_requests =====
            $dinas_requests = [
                'request_no'   => $request_no,
                'request_date' => $data['request_date'],
                'trip_type'    => $data['trip_type'],
                'division_id'    => $data['division_id'],
                'delivery_required'    => @$data['delivery_required'],
                'vehicle_type'    => @$data['vehicle_type'],
                'emoney_type'    => @$data['emoney_type'],
                'employee_delivery_id'    => @$data['employee_delivery_id'],
                'plat_no'    => @$data['plat_no'],
            ];

            $this->crud->update('dinas_requests', ['request_no' => $request_no], $dinas_requests);

            $oldEmployees = $this->crud->reads('dinas_request_employees', [], ['dinas_request_no' => $request_no]);

            $oldMap = [];
            foreach ($oldEmployees as $row) {
                $oldMap[$row->employee_id] = $row;
            }

            $newEmployeeIds = [];


            // // ===== EMPLOYEES =====
            foreach ($data['employees'] as $emp) {
                $newEmployeeIds[] = $emp['employee_id'];

                $dinas_request_employees =
                    [
                        'dinas_request_no'  => $request_no,
                        'division_id'       => $emp['division_id'],
                        'departement_id'    => $emp['departement_id'],
                        'departement_sub_id' => $emp['departement_sub_id'],
                        'employee_id'       => $emp['employee_id']
                    ];
                if (isset($oldMap[$emp['employee_id']])) {
                    $this->crud->update('dinas_request_employees', ['dinas_request_no' => $request_no, 'employee_id' => $emp['employee_id']], $dinas_request_employees);
                } else {
                    $this->crud->create('dinas_request_employees', $dinas_request_employees);
                }
            }

            foreach ($oldMap as $employee_id => $row) {
                if (!in_array($employee_id, $newEmployeeIds)) {
                    $this->crud->delete('dinas_request_employees', ['dinas_request_no' => $request_no, 'employee_id' => $employee_id]);
                }
            }

            $group_type = $data['trip']['group_type'];

            if ($group_type === 'GROUP') {
                $dest['area_id']     = $data['trip']['area_id'];
                $dest['location_id'] = $data['trip']['location_id'];
                $dest['area_text']     = null;
                $dest['location_text']     = null;
            } else {
                $dest['area_id']     = null;
                $dest['location_id']     = null;
                $dest['area_text']     = $data['trip']['area_text'];
                $dest['location_text'] = $data['trip']['location_text'];
            }


            // // ===== TRIP =====
            if ($data['trip_type'] === 'ONE') {
                $this->crud->delete('dinas_trip_round', ['dinas_request_no' => $request_no]);

                $trip = [
                    'dinas_request_no'  => $request_no,
                    'group_type'       => $data['trip']['group_type'],
                    'remarks'          => $data['trip']['remarks'] ?? null,
                    'date_in'  => $data['trip']['date_in'],
                    'time_in'  => $this->_clean_time($data['trip']['time_in']),
                    'date_out' => $data['trip']['date_out'] ?: null,
                    'time_out' => $this->_clean_time($data['trip']['time_out'])
                ];

                $dinas_trip_one = array_merge($trip, $dest);

                $exists = $this->crud->read('dinas_trip_one', [], ['dinas_request_no' => $request_no]);

                if ($exists) {
                    $this->crud->update('dinas_trip_one', ['dinas_request_no' => $request_no], $dinas_trip_one);
                } else {
                    $this->crud->create('dinas_trip_one', $dinas_trip_one);
                }
            } else {
                $this->crud->delete('dinas_trip_one', ['dinas_request_no' => $request_no]);

                $attachment = null;

                if (!empty($_FILES['attachment']['name'])) {
                    $attachment = $this->crud->upload(
                        'attachment',
                        ['pdf', 'png', 'jpg', 'jpeg', 'heic'],
                        'assets/image/dinas/'
                    );

                    if (!$attachment) {
                        throw new Exception('Upload attachment gagal');
                    }
                }

                $trip = [
                    'dinas_request_no'  => $request_no,
                    'remarks'          => $data['trip']['remarks'] ?? null,
                    'group_type'       => $data['trip']['group_type'],
                    'departure_dropoff_location' => $data['trip']['departure_dropoff_location'],
                    'departure_pickup_location'  => $data['trip']['departure_pickup_location'],
                    'departure_date' => $data['trip']['departure_date'],
                    'departure_time' => $this->_clean_time($data['trip']['departure_time']),
                    'return_pickup_location' => $data['trip']['return_pickup_location'],
                    'return_date' => $data['trip']['return_date'] ?: null,
                    'return_time' => $this->_clean_time($data['trip']['return_time']),
                    'attachment' => $attachment
                ];

                $dinas_trip_round = array_merge($trip, $dest);

                $exists = $this->crud->read('dinas_trip_round', [], ['dinas_request_no' => $request_no]);

                if ($exists) {
                    $this->crud->update('dinas_trip_round', ['dinas_request_no' => $request_no], $dinas_trip_round);
                } else {
                    $this->crud->create('dinas_trip_round', $dinas_trip_round);
                }
            }

            // ===== COMMIT =====
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Database error');
            }

            $this->db->trans_commit();

            echo json_encode([
                'status'  => true,
                'message' => 'Business trip berhasil disimpan'
            ]);
        } catch (Exception $e) {

            $this->db->trans_rollback();

            echo json_encode([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    //DELETE DATA
    public function delete()
    {
        $data = $this->input->post();
        $dinas_emp_old = $this->crud->read('dinas_request_employees', [], ['id' => $data['id']]);
        $request_no = $dinas_emp_old->dinas_request_no;
        
        $send = $this->crud->delete('dinas_request_employees', $data);
        $dinas_emp_new = $this->crud->read('dinas_request_employees', [], ['dinas_request_no' => $request_no]);

        if (!$dinas_emp_new) {
            $this->crud->delete('dinas_trip_one', ['dinas_request_no' => $request_no]);
            $this->crud->delete('	dinas_trip_round', ['dinas_request_no' => $request_no]);
            $this->crud->delete('dinas_requests', ['request_no' => $request_no]);
        }

        echo $send;
    }

    //PRINT & EXCEL DATA
    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=Dinas_$format.xls");
        }
    }

    public function form_requests($id_menu = "", $id = "")
    {
        if (empty($this->session->username)) {
            redirect('error_session');
        } else {
            $data['id'] = $id;
            if ($id) {
                $data['detail'] = $this->getDetail($id);
            }
            $data['id_menu'] = $id_menu;
            $this->load->view('template/header', $data);
            $this->load->view('business_trip/form_requests');
        }
    }

    private function generateRequestNo($initial_company)
    {
        // Bulan & Tahun
        $monthYear = date('mY'); // 012026

        // Cari request terakhir di bulan ini
        $this->db->select('request_no');
        $this->db->from('dinas_requests');
        $this->db->like('request_no', '/' . $monthYear, 'both');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get()->row();

        if ($last) {
            // Ambil nomor urut
            $lastNumber = (int) substr($last->request_no, 0, 3);
            $newNumber  = $lastNumber + 1;
        } else {
            // Reset bulan baru
            $newNumber = 1;
        }

        $running = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return $running . '/ESS-' . $initial_company . '/' . $monthYear;
    }

    private function getDetail($id)
    {
        // ===== REQUEST =====
        $this->db->select('dr.*');
        $this->db->from('dinas_requests dr');
        $this->db->where('dr.id', $id);
        $request = $this->db->get()->row();

        if (!$request) return null;

        // ===== EMPLOYEES =====
        $employees = $this->db
            ->where('dinas_request_no', $request->request_no)
            ->get('dinas_request_employees')
            ->result();

        if ($request->trip_type == 'ONE') {
            $trip = $this->db
                ->where('dinas_request_no', $request->request_no)
                ->get('dinas_trip_one')
                ->row();
        } else {
            $trip = $this->db
                ->where('dinas_request_no', $request->request_no)
                ->get('dinas_trip_round')
                ->row();
        }

        return [
            'request'   => $request,
            'employees' => $employees,
            'trip'  => $trip,
        ];
    }
}
