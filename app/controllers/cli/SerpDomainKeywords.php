<?php
class SerpDomainKeywords extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('input');
        $this->load->model('auth/analyze_model');
        $this->load->model('serp/domain_model');
        $this->load->model('serp/serpdomainkeywords_model');
    }
    public function index()
    {
        if (!$this->input->is_cli_request()) {
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }
        $allDomains = $this->analyze_model->getAllDomains();
        foreach ($allDomains as $domains) {
            $searchengines = $this->analyze_model->getSearchEngineByDomainId();
            foreach ($searchengines as $engines) {
                $exist = $this->serpdomainkeywords_model->getDataExistByDate(date('Y-m-d'), $engines['id']);
                if (!$exist) {
                    $domain = urlencode($domains['domain_name']);
                    $token  = $this->ci->config->config['serpstat']['token'];
                    $engine = $engines['name'];
                    $url    = 'http://api.serpstat.com/v3/domain_keywords?query=' . $domain . '
                          &token=' . $domain . '&se=' . $engine;
                    $result = json_decode(file_get_contents($url), true);
                    //storing data
                    $this->serpdomainkeywords_model->insertSerpDomainKeywords($domains['id'], $engines['id'], $result['result'], $result['status']);
                    sleep(2);
                }
            }
        }
    }
}
