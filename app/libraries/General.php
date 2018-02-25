<?php
defined('BASEPATH') or exit('No direct script access allowed');
class General
{
    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('phpass');
        $this->ci->load->library('session');
        $this->ci->load->database();
        $this->ci->load->model('auth/users');
        $this->ci->load->model('auth/usergroups_model');

    }

    public function exportToCSV($data, $fileName, $headerDisplayed = false)
    {
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Description: File Transfer');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$fileName}");
        header("Expires: 0");
        header("Pragma: public");
        $fh = @fopen('php://output', 'w');
        foreach ($data as $d) {
            if (!$headerDisplayed) {
                fputcsv($fh, array_keys($d));
                $headerDisplayed = true;
            }
            fputcsv($fh, $d);
        }
        fclose($fh);
        exit;
    }
}
