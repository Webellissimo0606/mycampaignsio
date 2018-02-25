<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Reporting extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth/user_model');
        $this->load->model('auth/analyze_model');
        $this->load->model('seoreporting/seoreportingproject_model');
        $this->load->model('seoreporting/seoreportingtask_model');
        $this->load->model('seoreporting/seoreportingtasklog_model');
        $this->load->library('session');
        $this->ci = &get_instance();
    }

    public function setrecurringjobs()
    {
        $sql1    = "select * from seo_reporting_project where user_id='" . $userId . "'";
        $query   = $this->db->query($sql1);
        $result1 = $query->result_array($query);
        if ($result1) {
            foreach ($result1 as $res1) {
                //searching for incomplete recurring jobs
                $sql2 = "select * from seo_reporting_task t where t.project_id='" . $res1['project_id'] . "''
                   where t.recurring='TRUE'";
                $query   = $this->db->query($sql2);
                $result2 = $query->result_array($query);
                if ($result2) {
                    foreach ($result2 as $res2) {
                        $sql3    = "select * from seo_reporting_tasklog where task_id='" . $res2['id'] . "' where status='COMPLETE' order by id desc ";
                        $query   = $this->db->query($sql3);
                        $result3 = $query->row_array($query);
                        if ($result3) {
                            $array               = array();
                            $array['task_id']    = $result3['task_id'];
                            $array['notes']      = $result3['notes'];
                            $array['cost']       = $result3['cost'];
                            $array['status']     = 'INCOMPLETE';
                            $array['start_date'] = date('Y-m-d H:i:s');
                            $array['end_date'] = date('Y').'-'.date('m').'-'.$res2['recurring_days'];
                            $this->db->insert('seo_reporting_tasklog', $array);
                            //updating the modified on task
                            $array = array();
                            $array['modified'] = date('Y-m-d H:i:s');
                            $this->db->insert('seo_reporting_task', $array,array('id'=>$result3['task_id']));

                        }
                    }
                }
            }
        }
    }

    public function getprojects()
    {
        global $current_page;
        $current_page = 'seoreporting-project';
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {

        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $user_id             = $this->ci_auth->get_user_id();
                $projects            = $this->seoreportingproject_model->getProjectsByUserId($user_id);
                $data['projectlist'] = $projects;
                $this->load->view(get_template_directory() . '/new-ui/seoreporting_listproject', $data);
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function editproject()
    {
        global $current_page;
        $current_page = 'seoreporting-project';
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {

        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                if ($_POST) {
                    $project_id                   = $this->input->post('project_id');
                    $array                        = array();
                    $array['project_name']        = $this->input->post('project_name');
                    $array['project_description'] = $this->input->post('project_description');
                    $array['client_name']         = $this->input->post('client_name');
                    $array['modified']            = date('Y-m-d H:i:s');
                    $this->db->update('seo_reporting_project', $array, array('id' => $project_id));
                    $this->session->set_flashdata('success_msg', "Project has been updated successfully");
                    redirect(site_url('/seoreporting/getprojects'));
                } else {
                    $project_id            = $this->uri->segment(2);
                    $project               = $this->seoreportingproject_model->getProjectById($project_id);
                    $data['projectdetail'] = $project;
                    $this->load->view(get_template_directory() . '/new-ui/seoreporting_editproject', $data);
                }

            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function getjobs()
    {
        global $current_page;
        $current_page = 'seoreporting-task';
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {

        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $project_id = $this->uri->segment(3);

            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function addproject()
    {
        global $current_page;
        $current_page = 'seoreporting-project';
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {

        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {

                if ($_POST) {
                    $array['project_name']        = $this->input->post('project_name');
                    $array['project_description'] = $this->input->post('project_description');
                    $array['user_id']             = $this->ci_auth->get_user_id();
                    $array['status']              = 'RUNNING';
                    $array['client_name']         = $this->input->post('client_name');
                    $array['created']             = date('Y-m-d H:i:s');
                    $this->db->insert('seo_reporting_project', $array);
                    $this->session->set_flashdata('success_msg', "Project has been added successfully");
                    redirect(site_url('/seoreporting/getprojects'));
                } else {
                    $this->load->view(get_template_directory() . '/new-ui/seoreporting_addproject');
                }

            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function listtask()
    {
        global $current_page;
        $current_page = 'seoreporting-task';
        $userId = $this->ci_auth->get_user_id();
        $filter = $_GET['status'];
        // $tasks      = $this->seoreportingtask_model->getTaskByProjectId($project_id);
        $tasks = $this->seoreportingtask_model->getTaskByUserId($userId, $filter);
        $return     = array();
        if ($tasks) {
            foreach ($tasks as $key => $task) {
                $latest                 = $this->seoreportingtasklog_model->getLatestJobByJobId($task['task_id']);
                $return[$key]           = $task;
                $return[$key]['status'] = $latest['status'];
                $return[$key]['start_date'] = $latest['start_date'];
                $return[$key]['end_date'] = $latest['end_date'];
            }
        }
        $data['tasks'] = $return;
        $data['filter'] = $_GET['status'];
        $this->load->view(get_template_directory() . 'new-ui/seoreporting_listtask', $data);

    }

    public function viewtask()
    {
        global $current_page;
        $current_page = 'seoreporting-task';
        $task_id                = $this->uri->segment(2);
        $data['tasklog_detail'] = $this->seoreportingtasklog_model->getLatestJobByJobId($task_id);
        $data['task_detail']    = $this->seoreportingtask_model->getTaskByTaskId($task_id);
        echo $this->load->view(get_template_directory() . 'new-ui/seoreporting_viewtask', $data, true);
        die;
    }

    public function deleteproject()
    {
        global $current_page;
        $current_page = 'seoreporting-project';
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {

        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $project_id      = $this->uri->segment(2);
                $array           = array();
                $array['status'] = 'DELETED';
                $this->db->where('id', $project_id);
                $this->db->update('seo_reporting_project', $array);
                $this->session->set_flashdata('success_msg', "Project has been deleted successfully");
                redirect(site_url('/seoreporting/getprojects'));

            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function addjob()
    {
        global $current_page;
        $current_page = 'seoreporting-task';
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {

        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                if ($_POST) {
                    $array               = array();
                    $project_id          = $this->input->post('project');
                    $array['project_id'] = $this->input->post('project');
                    $array['task_name']  = $this->input->post('task_name');
                    $array['notes']      = $this->input->post('notes');
                    if ($this->input->post('recurring') == 'on') {
                        $array['recurring']      = 'TRUE';
                        $array['recurring_days'] = $this->input->post('recurring_days', 1);
                    } else {
                        $array['recurring']      = 'FALSE';
                        $array['recurring_days'] = 0;
                    }
                    $array['cost']    = $this->input->post('cost');
                    $array['created'] = date('Y-m-d H:i:s');
                    $array['modified'] = date('Y-m-d H:i:s');
                    $this->db->insert('seo_reporting_task', $array);
                    $insert_id = $this->db->insert_id();

                    //inserting in task log table
                    $array               = array();
                    $array['task_id']    = $insert_id;
                    $array['notes']      = $this->input->post('notes');
                    $array['cost']       = $this->input->post('cost');
                    $array['status']     = 'INCOMPLETE';
                    $array['start_date'] = date('Y-m-d H:i:s');
                    if ($this->input->post('recurring') == 'on') {
                        $recurring_days    = $this->input->post('recurring_days', 1);
                        $array['end_date'] = date('Y').'-'.date('m').'-'.$recurring_days;
                    }
                    $this->db->insert('seo_reporting_tasklog', $array);
                    $this->session->set_flashdata('success_msg', "Job has been added to the project");
                    redirect(site_url('/seoreporting/' . $project_id . '/listjobs'));

                } else {
                    //getting all projects
                    $user_id          = $this->ci_auth->get_user_id();
                    $projects         = $this->seoreportingproject_model->getProjectsByUserId($user_id);
                    $data['projects'] = $projects;
                    $this->load->view(get_template_directory() . 'new-ui/seoreporting_addtask', $data);
                }

            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function deletejob()
    {
        global $current_page;
        $current_page = 'seoreporting-task';
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {

        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $job_id     = $this->uri->segment(2);
                $latest_job = $this->seoreportingtask_model->getTaskByTaskId($job_id);
                // $latest_job = $this->seoreportingtasklog_model->getLatestJobByJobId($job_id);
                $this->db->flush_cache();
                $this->db->where('id', $latest_job['task_id']);
                $this->db->delete('seo_reporting_task');
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function completejob()
    {
        global $current_page;
        $current_page = 'seoreporting-task';
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {

        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $job_id          = $this->uri->segment(3);
                $latest_job      = $this->db->seoreportingtasklog_model->getLatestJobByJobId($job_id);
                $array           = array();
                $array['status'] = 'COMPLETED';
                $this->db->where('id', $latest_job['id']);
                $this->db->seoreportingtasklog_model->update('seo_reporting_tasklog', $array);

            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function updatetaskstatus()
    {
        global $current_page;
        $current_page = 'seoreporting-task';
        $task_log_id = $this->input->post('task_log_id');
        if ($this->input->post('type') == 'updatestatus') {
            if ($this->input->post('status') == 1) {
                $array           = array();
                $array['status'] = 'COMPLETE';
                $this->db->update('seo_reporting_tasklog', $array, array('id' => $task_log_id));
            } else {
                $array           = array();
                $array['status'] = 'INCOMPLETE';
                $this->db->update('seo_reporting_tasklog', $array, array('id' => $task_log_id));
            }
        }
        if ($this->input->post('added_notes') && $this->input->post('type') == 'updatenotes') {
            //getting the old notes
            $tasklog_detail = $this->seoreportingtasklog_model->getTaskLogById($task_log_id);
            $array          = array();
            $array['notes'] = $tasklog_detail['notes'] . "\n" . $this->input->post('added_notes');
            $this->db->update('seo_reporting_tasklog', $array, array('id' => $task_log_id));
        }
        if ($this->input->post('type') == 'updaterecurring') {
            $task_id            = $this->input->post('taskid');
            $array              = array();
            $array['recurring'] = $this->input->post('recurring');
            $this->db->update('seo_reporting_task', $array, array('id' => $task_id));
        }
        $return['status'] = true;
        $return['msg']    = 'Task Updated successfully';
        echo json_encode($return);die;
    }
}
