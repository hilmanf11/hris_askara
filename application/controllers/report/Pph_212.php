<?php
date_default_timezone_set("Asia/Bangkok");
defined('BASEPATH') or exit('No direct script access allowed');

class Pph_212 extends CI_Controller
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
        if (empty($this->session->username)) {
            redirect('error_session');
        } elseif ($this->checkuserAccess($this->id_menu()) > 0) {
            $data['button'] = $this->getbutton($this->id_menu());

            $this->load->view('template/header', $data);
            $this->load->view('report/pph_212');
        } else {
            redirect('error_access');
        }
    }

    //GET DATA
    public function groups()
    {
        $post = isset($_POST['q']) ? $_POST['q'] : "";
        $send = $this->crud->reads('groups', ["name" => $post], ["ppt" => "0"]);
        echo json_encode($send);
    }

    public function csv()
    {
        if ($this->input->get()) {

            header("Content-type: text/csv; charset=utf-8");
            header("Content-Disposition: attachment; filename=pph21_employee_" . time() . ".csv");
            $output = fopen("php://output", "w");
            fputcsv($output, array(
                "Masa Pajak", "Tahun Pajak", "Pembetulan", "NPWP", "Nama",
                "Kode Pajak", "Jumlah Bruto", "Jumlah Pph", "Kode Negara"
            ));

            $filter_from = $this->input->get('filter_from');
            $filter_to = $this->input->get('filter_to');
            $filter_group = $this->input->get('filter_group');

            $tgl_bp = date("d-M-y", strtotime($filter_from));
            $period_start = date("Y-m", strtotime($filter_from));
            $period_end = date("Y-m", strtotime($filter_to));

            $query = $this->db->query("SELECT b.name, b.tax_id, b.national_id, b.address, a.attandance_wd, a.marital, a.pph, a.income
                FROM payrolls a
                JOIN employees b ON a.employee_id = b.id
                JOIN groups d ON b.group_id = d.id and d.ppt = 0
                WHERE a.period_start = '$period_start' and a.period_end = '$period_end' and d.id like '%$filter_group%'
                GROUP BY a.id
                ORDER BY a.name ASC");
            $records = $query->result_array();

            //Config
            $this->db->select('*');
            $this->db->from('config');
            $config = $this->db->get()->row();

            $no = 1;
            foreach ($records as $record) {
                $number = "1.3-" . date("m", strtotime($filter_from)) . "." . date("y", strtotime($filter_from)) . "-" .  sprintf("%07s", $no);

                if ($record['tax_id'] == "") {
                    $tarif = "5";
                } else {
                    $tarif = "6";
                }

                $data = array(
                    date("m", strtotime($filter_from)),
                    date("Y", strtotime($filter_from)),
                    "0",
                    str_replace(array('.', '-'), '', $record['tax_id']),
                    $record['name'],
                    "21-100-03",
                    $record['income'],
                    $record['pph'],
                    ""
                );

                fputcsv($output, $data);

                $no++;
            }
        }
    }

    public function print($option = "")
    {
        if ($option == "excel") {
            $format  = date("Ymd");
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=pph21_employee_$format.xls");
        }

        if ($this->input->get()) {
            $filter_from = $this->input->get('filter_from');
            $filter_to = $this->input->get('filter_to');
            $filter_group = $this->input->get('filter_group');

            $tgl_bp = date("d-M-y", strtotime($filter_from));
            $period_start = date("Y-m", strtotime($filter_from));
            $period_end = date("Y-m", strtotime($filter_to));

            $query = $this->db->query("SELECT b.name, b.tax_id, b.national_id, b.address, a.attandance_wd, a.marital, a.pph, a.income
                FROM payrolls a
                JOIN employees b ON a.employee_id = b.id
                JOIN groups d ON b.group_id = d.id and d.ppt = 0
                LEFT JOIN sources c ON b.source_id = c.id
                WHERE a.period_start = '$period_start' and a.period_end = '$period_end' and d.id like '%$filter_group%'
                GROUP BY a.id
                ORDER BY c.name, a.name ASC");
            $records = $query->result_array();

            //Config
            $this->db->select('*');
            $this->db->from('config');
            $config = $this->db->get()->row();

            $html = '<html><head><title>Print Data</title></head><style>body {font-family: Arial, Helvetica, sans-serif;}#customers {border-collapse: collapse;width: 100%;font-size: 10px;}#customers td, #customers th {border: 1px solid #ddd;padding: 2px;}#customers tr:nth-child(even){background-color: #f2f2f2;}#customers tr:hover {background-color: #ddd;}#customers th {padding-top: 2px;padding-bottom: 2px;text-align: left;color: black;}</style>
                    <style> .str{ mso-number-format:\@; } </style>
                    <body>
                    <center>
                        <h3 style="margin:0;">TEMPLATE ESINDO (PPH 21) PTT</h3>
                        <b>Period ' . $filter_from . ' and ' . $filter_to . '</b>
                    </center>
                    <br><br>
                    <table id="customers" border="1">
                        <tr>
                            <th width="30">No</th>
                            <th style="text-align:center;">Masa Pajak</th>
                            <th style="text-align:center;">Tahun Pajak</th>
                            <th style="text-align:center;">Pembetulan</th>
                            <th style="text-align:center;">NPWP</th>
                            <th style="text-align:center;">Nama</th>
                            <th style="text-align:center;">Kode Pajak</th>
                            <th style="text-align:center;">Jumlah Bruto</th>
                            <th style="text-align:center;">Jumlah PPh</th>
                            <th style="text-align:center;">Kode Negara</th>
                        </tr>';
            $no = 1;
            foreach ($records as $record) {
                $html .= '<tr>
                            <td style="text-align:center;">' . $no . '</td>
                            <td style="text-align:center;">' . date("m", strtotime($filter_from)) . '</td>
                            <td style="text-align:center;">' . date("Y", strtotime($filter_from)) . '</td>
                            <td style="text-align:center;">0</td>
                            <td>' . str_replace(array('.', '-'), '', $record['tax_id']) . '</td>
                            <td>' . $record['name'] . '</td>
                            <td>21-100-03</td>
                            <td style="text-align:right;">' . number_format($record['income']) . '</td>
                            <td style="text-align:right;">' . number_format($record['pph']) . '</td>
                            <td style="text-align:center;"></td>
                        </tr>';
                $no++;
            }
            $html .= '</table>';
        }

        $html .= '</body></html>';
        echo $html;
    }
}
