<?php
class Staff_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Get staff by id
    public function get_staff($id)
    {
        $this->db->select('*,languages.name as stafflanguage,departments.name as department, staff.id as id');
        $this->db->join('departments', 'staff.departmentid = departments.id', 'left');
        $this->db->join('languages', 'staff.language = languages.foldername', 'left');
        return $this->db->get_where('staff', array('staff.id' => $id))->row_array();
    }

    // Get all staff
    public function get_all_staff()
    {
        $this->db->select('*,departments.name as department, staff.id as id');
        $this->db->join('departments', 'staff.departmentid = departments.id', 'left');
        $this->db->where('staff.company_id =' . $_SESSION['company_id']);
        return $this->db->get_where('staff', array(''))->result_array();
    }

    // function to add new staff
    public function add_staff($params)
    {
        $this->db->insert('staff', $params);
        $staffmember = $this->db->insert_id();
        $staffname = $this->session->staffname;
        $stafadded = $this->input->post('staffname');
        $loggedinuserid = $this->session->logged_in_staff_id;
        $this->db->insert('logs', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('' . $message = sprintf(lang('xaddedstaff'), $staffname, $stafadded) . ''),
            'staffid' => $loggedinuserid,
            'company_id' => $_SESSION['company_id'],
        ));
        $this->db->insert('settings', array(
            'settingname' => "ciuis",
            'voicenotification' => "1",
            'accepted_files_formats' => "jpg,jpeg,doc,png,txt,docx",
            'company_id' => $_SESSION['company_id'],
            'address'=>($this->input->post('address'))?$this->input->post('address'):null
        ));
        $this->db->insert('currencies', array(
            'symbol' => "$",
            'name' => "USD",
            'default' => 1,
            'company_id' => $_SESSION['company_id'],
        ));
        return $this->db->insert_id();
    }

    // Function to update staff
    public function update_staff($id, $params)
    {
        $this->db->where('id', $id);
        $response = $this->db->update('staff', $params);
    }

    // Function to delete staff
    public function delete_staff($id)
    {
        $response = $this->db->delete('staff', array('id' => $id));
        $staffname = $this->session->staffname;
        $loggedinuserid = $this->session->logged_in_staff_id;
        $this->db->insert('logs', array(
            'date' => date('Y-m-d H:i:s'),
            'detail' => ('<a href="/staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang('deleted') . ' ' . lang('staff') . '-' . $id . ''),
            'staffid' => $loggedinuserid,
            'company_id' => $_SESSION['company_id'],
        ));
    }
    public function delete_avatar($id)
    {
        $response = $this->db->where('id', $id)->update('staff', array('staffavatar' => 'n-img.jpg'));
    }

    public function staffsalesgraph()
    {
        $this->db->select_sum('total');
        $this->db->from('sales');
        return $this->db->get_where('', array('staffid' => $this->session->userdata('logged_in_staff_id')))->row()->total;
    }

    public function staffmember_customer($id)
    {
        $this->db->from('customers');
        return $this->db->get_where('', array('staffid' => $id))->num_rows();
    }

    public function staffmember_tickets($id)
    {
        $this->db->from('tickets');
        return $this->db->get_where('', array('staffid' => $id))->num_rows();
    }

    public function staffmembersales($id)
    {
        $totalsales = array();
        $i = 0;
        for ($mo = 1; $mo <= 12; $mo++) {
            $this->db->select('total');
            $this->db->from('sales');
            $this->db->where('MONTH(sales.date)', $mo);
            $this->db->where('staffid = ' . $id . '');
            $gains = $this->db->get()->result_array();
            if (!isset($totalsales[$mo])) {
                $totalsales[$i] = array();
            }
            if (count($gains) > 0) {
                foreach ($gains as $gainx) {
                    $totalsales[$i][] = $gainx['total'];
                }
            } else {
                $totalsales[$i][] = 0;
            }
            $totalsales[$i] = array_sum($totalsales[$i]);
            $i++;
        }
        return json_encode($totalsales);
    }

    public function isDuplicate($email)
    {
        $this->db->get_where('staff', array('email' => $email), 1);
        return $this->db->affected_rows() > 0 ? true : false;
    }

    public function insertToken($user_id)
    {
        $token = substr(sha1(rand()), 0, 30);
        $date = date('Y-m-d');

        $string = array(
            'token' => $token,
            'user_id' => $user_id,
            'created' => $date,
        );
        $query = $this->db->insert_string('tokens', $string);
        $this->db->query($query);
        return $token . $user_id;
    }

    public function isTokenValid($token)
    {
        $tkn = substr($token, 0, 30);
        $uid = substr($token, 30);

        $q = $this->db->get_where('tokens', array(
            'tokens.token' => $tkn,
            'tokens.user_id' => $uid), 1);

        if ($this->db->affected_rows() > 0) {
            $row = $q->row();

            $created = $row->created;
            $createdTS = strtotime($created);
            $today = date('Y-m-d');
            $todayTS = strtotime($today);

            if ($createdTS != $todayTS) {
                return false;
            }

            $user_info = $this->getUserInfo($row->user_id);
            return $user_info;
        } else {
            return false;
        }
    }

    public function getUserInfo($id)
    {
        $q = $this->db->get_where('staff', array('id' => $id), 1);
        if ($this->db->affected_rows() > 0) {
            $row = $q->row();
            return $row;
        } else {
            error_log('no user found getUserInfo(' . $id . ')');
            return false;
        }
    }

    public function updateUserInfo($post)
    {
        $data = array(
            'password' => $post['password'],
            'last_login' => date('Y-m-d h:i:s A'),
            'inactive' => $this->inactive[1],
        );
        $this->db->where('id', $post['user_id']);
        $this->db->update('staff', $data);
        $success = $this->db->affected_rows();

        if (!$success) {
            error_log('Unable to updateUserInfo(' . $post['user_id'] . ')');
            return false;
        }

        $user_info = $this->getUserInfo($post['user_id']);
        return $user_info;
    }

    public function getUserInfoByEmail($email)
    {
        $q = $this->db->get_where('staff', array('email' => $email), 1);
        if ($this->db->affected_rows() > 0) {
            $row = $q->row();
            return $row;
        } else {
            error_log('no user found getUserInfo(' . $email . ')');
            return false;
        }
    }

    public function updatePassword($post)
    {
        $this->db->where('id', $post['user_id']);
        $this->db->update('staff', array('password' => $post['password']));
        $success = $this->db->affected_rows();

        if (!$success) {
            error_log('Unable to updatePassword(' . $post['user_id'] . ')');
            return false;
        }
        return true;
    }
}
