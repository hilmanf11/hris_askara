<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Cars extends CI_Controller
{
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
        $this->form_validation->set_rules('number', 'Code', 'required|min_length[1]|max_length[20]|is_unique[cars.number]');
    }

    //HALAMAN UTAMA
    public function index()
    {
        if (empty($this->session->username)) {
            redirect('error_session');
        } elseif ($this->checkuserAccess($this->id_menu()) > 0) {
            $data['button'] = $this->getbutton($this->id_menu());

            $this->load->view('template/header', $data);
            $this->load->view('business_trip/cars');
        } else {
            redirect('error_access');
        }
    }

    //GET DATA
    public function reads()
    {
        $post = isset($_POST['q']) ? $_POST['q'] : "";
        $location_id = $this->input->get('location_id') ? $this->input->get('location_id') : "";

        $this->db->select('a.*');
        $this->db->from('cars a');
        $this->db->like('a.name', $post);
        $this->db->like('a.location_id', $location_id);

        $this->db->order_by('a.name', 'asc');
        $send = $this->db->get()->result_array();
        echo json_encode($send);
    }

    //GET DATATABLES
    public function datatables()
    {
        $page    = (int) $this->input->post('page', true);
        $rows    = (int) $this->input->post('rows', true);
        $filters = json_decode($this->input->post('filterRules'));

        $page   = $page > 0 ? $page : 1;
        $rows   = $rows > 0 ? $rows : 10;
        $offset = ($page - 1) * $rows;

        $this->db->select("
            a.*,
            l.name AS location_name,
        ");

        $this->db->from('cars a');
        $this->db->join('locations l', 'l.id = a.location_id', 'left');

        $this->db->where('a.deleted', 0);

        if (!empty($filters)) {
            foreach ($filters as $filter) {
                if (!empty($filter->value)) {
                    switch ($filter->field) {
                        case 'location_name':
                            $this->db->like('l.name', $filter->value);
                            break;

                        default:
                            $this->db->like('a.' . $filter->field, $filter->value);
                            break;
                    }
                }
            }
        }

        $this->db->order_by('l.name', 'ASC');
        $this->db->order_by('a.number', 'ASC');

        $totalRows = $this->db->count_all_results('', false);

        $this->db->limit($rows, $offset);

        $records = $this->db->get()->result_array();

        echo json_encode([
            'total' => $totalRows,
            'rows'  => $records
        ]);
    }


    //CREATE DATA
    public function create()
    {
        if ($this->input->post()) {
            if ($this->form_validation->run() == TRUE) {
                $post   = $this->input->post();
                $send   = $this->crud->create('cars', $post);
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
            $send = $this->crud->update('cars', ["id" => $id], $post);
            echo $send;
        } else {
            show_error("Cannot Process your request");
        }
    }

    //DELETE DATA
    public function delete()
    {
        $data = $this->input->post();
        $send = $this->crud->delete('cars', $data);
        echo $send;
    }

    //PRINT & EXCEL DATA
    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=locations_$format.xls");
        }


        //Config
        $this->db->select('*');
        $this->db->from('config');
        $config = $this->db->get()->row();

        $this->db->select("
            a.*,
            l.name AS location_name,
        ");

        $this->db->from('cars a');
        $this->db->join('locations l', 'l.id = a.location_id', 'left');

        $this->db->where('a.deleted', 0);

        $this->db->order_by('l.name', 'ASC');
        $this->db->order_by('a.number', 'ASC');
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
                            <small>MASTER LOCATIONS</small>
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
                <th>Plat No</th>
                <th>Lokasi</th>
                <th>Description</th>
            </tr>';
        $no = 1;
        foreach ($records as $data) {
            $html .= '<tr>
                    <td>' . $no . '</td>
                    <td>' . $data['number'] . '</td>
                    <td>' . $data['location_name'] . '</td>
                    <td>' . $data['description'] . '</td>';
            $no++;
        }

        $html .= '</table></body></html>';
        echo $html;
    }
}
