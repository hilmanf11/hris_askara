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
    // jika admin, akun pak andi, dan department HR bisa melihat semua data
    if ($user_session['username'] == 'admin' || $user_session['number'] == '10010009040004') {
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
        $is_operator = null;
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

          $is_operator = in_array('20221213000003', $groups ?? []);

          $this->db->select('a.departement_id');
          $this->db->from('employees a');
          $this->db->group_start();
          $this->db->group_start();
          $this->db->where('a.division_id', $employee->division_id);
          $this->db->where_in('group_id', $groups);
          $this->db->group_end();

          // Operator Inotek
          if ($is_operator) {
            $this->db->or_group_start();
            $this->db->where('a.division_id', '20240806000001'); // Inotek
            $this->db->where('a.group_id', '20221213000003');   // Operator
            $this->db->group_end();
          }
          $this->db->group_end();
          $this->db->group_by('a.departement_id');
          $arr_dept = $this->db->get()->result_array();
          foreach ($arr_dept as $dept) {
            $dept_ids[] = $dept['departement_id'];
          }

          $this->db->select('a.departement_sub_id');
          $this->db->from('employees a');
          $this->db->group_start();
          $this->db->group_start();
          $this->db->where('a.division_id', $employee->division_id);
          $this->db->where_in('group_id', $groups);
          $this->db->group_end();

          // Operator Inotek
          if ($is_operator) {
            $this->db->or_group_start();
            $this->db->where('a.division_id', '20240806000001'); // Inotek
            $this->db->where('a.group_id', '20221213000003');   // Operator
            $this->db->group_end();
          }
          $this->db->group_end();
          $this->db->group_by('a.departement_sub_id');
          $arr_subdept = $this->db->get()->result_array();
          foreach ($arr_subdept as $subdept) {
            $subdept_ids[] = $subdept['departement_sub_id'];
          }
        }

        $admin_emdep = 0;
        if ($employee->number == '10020011240074') {
          $admin_emdep = 1;
        }

        $result = [
          'employee_number' =>  $employee->number,
          'employee_id' =>  $employee->id,
          'division_id' =>  $employee->division_id,
          'department_id' =>  $employee->departement_id,
          'departement_sub_id' =>  $employee->departement_sub_id,
          'privilege_groups' => $groups,
          'department_ids' => $dept_ids,
          'departement_sub_ids' => $subdept_ids,
          'is_operator' => $is_operator,
          'admin_emdep' => $admin_emdep,
        ];

        return $result;
      }
    }
  }

  public function applyEmployeeRestriction($restrict_user, $alias = null)
  {
    if (!$restrict_user) {
      return;
    }

    $p = $alias ? $alias . '.' : '';

    if (isset($restrict_user['privilege_groups'])) {

      $this->db->group_start();

      // ===== BPI sendiri =====
      $this->db->group_start();
      $this->db->where($p . 'division_id', $restrict_user['division_id']);
      $this->db->where_in($p . 'group_id', $restrict_user['privilege_groups']);
      $this->db->group_end();

      // ===== Operator Inotek =====
      if (!empty($restrict_user['is_operator'])) {
        $this->db->or_group_start();
        $this->db->where($p . 'division_id', '20240806000001'); // Inotek
        $this->db->where($p . 'group_id', '20221213000003');    // Operator
        $this->db->group_end();
      }

      $this->db->group_end();
    } else if ($restrict_user['admin_emdep'] == 1) {
      $this->db->where($p . 'division_id', '20241213000001'); // emdep
    } else {
      $this->db->where($p . 'id', $restrict_user['employee_id']);
    }
  }

  public function getEmployeeRestrictionSQL($restrict, $alias = 'a')
  {
    if (!$restrict) {
      return '';
    }

    if (isset($restrict['privilege_groups'])) {

      $groups = implode("','", $restrict['privilege_groups']);

      $sql = " AND ( 
            (
                {$alias}.division_id = '{$restrict['division_id']}'
                AND {$alias}.group_id IN ('{$groups}')
            )";

      if (!empty($restrict['is_operator'])) {
        $sql .= "
            OR (
                {$alias}.division_id = '20240806000001'
                AND {$alias}.group_id = '20221213000003'
            )";
      }

      $sql .= " )";

      return $sql;
    } else if ($restrict['admin_emdep'] == 1) {
      return " AND {$alias}.division_id = '20241213000001'";
    } else {
      return " AND {$alias}.id = '{$restrict['employee_id']}'";
    }

  }
}
