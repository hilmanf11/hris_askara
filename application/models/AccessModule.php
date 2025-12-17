<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Accessmodule extends CI_Model
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

    public function restrictSelf()
    {
        $user_session = $this->session->userdata();
        
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $user_session['id']);
        $this->db->where('description', 'MOBILE');
        $data_user = $this->db->get()->row_array();
        //jika ada data user mobile, maka tidak ada pembatasan data
        // jika admin dan department HR bisa melihat semua data
        if($data_user || $user_session['username'] == 'admin') {
          return null;
        } else {
          $employee = $this->db
          ->where('number', $user_session['number'])
          ->get('employees')
          ->row();

          // jika askara internal dan HR
          if ($employee->division_id == '20221119000002' && $employee->departement_id == '20241217000001') {
            return null;
          } else {
            $groups = null;
            $dept_ids = null;
            $subdept_ids = null;
            //jika user BPI
            if ($employee->division_id == '20241213000005') {
              $privilage_group = $this->db
              ->select('*')
              ->from('privilege_groups')
              ->where('username', $user_session['username'])
              ->get()
              ->result();
  
              foreach ($privilage_group as $group) {
                $groups[] = $group->group_id;
              }
  
              $this->db->select('a.departement_id');
              $this->db->from('employees a');
              $this->db->where_in('group_id', $groups);
              $this->db->where('division_id', $employee->division_id);
              $this->db->group_by('a.departement_id');
              $arr_dept = $this->db->get()->result_array();
              foreach ($arr_dept as $dept) {
                $dept_ids[] = $dept['departement_id'];
              }
  
              $this->db->select('a.departement_sub_id');
              $this->db->from('employees a');
              $this->db->where_in('group_id', $groups);
              $this->db->where('division_id', $employee->division_id);
              $this->db->group_by('a.departement_sub_id');
              $arr_subdept = $this->db->get()->result_array();
              foreach ($arr_subdept as $subdept) {
                $subdept_ids[] = $subdept['departement_sub_id'];
              }
            } 
            return [
              'employee_number' =>  $employee->number,
              'employee_id' =>  $employee->id,
              'division_id' =>  $employee->division_id,
              'department_id' =>  $employee->departement_id,
              'departement_sub_id' =>  $employee->departement_sub_id,
              'privilege_groups' => $groups,
              'department_ids' => $dept_ids,
              'departement_sub_ids' => $subdept_ids,
            ];
          }

        }
    }

}
