<?php

/**
 *
 * @package        Modules
 * @author        1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license        http://opensource.org/licenses/MIT    MIT License
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Serpcron extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth/analyze_model');
        $this->load->model('serp/domain_model');
        $this->load->library('upmonitor');
        $this->load->library('gtmetrix');
        $this->load->library('email');
        $this->ci = &get_instance();
    }

    public function immediate()
    {
        //immediate run for serp
        //getting all domains
        $this->load->config('configuration', true);
        $domains = $this->analyze_model->getAllDomains();
        // $domains[]        = $this->analyze_model->getDomainByUserIdAndDomainId(1, 11);
        // $domains[0]['id'] = 11;
        foreach ($domains as $domain) {
            $domain['domain_name'] = rawurlencode(strtr($domain['domain_name'], array('https://' => '', 'http://' => '', 'www.' => '')));
            $keywords              = $this->analyze_model->getAllKeywordsForDelaySerpCronByDomainId($domain['id']);
            
            // $keywords = $this->analyze_model->getAllKeywordsForImmediateSerpCronByDomainId(9);
            
            if ($keywords) {
                $searchengines = $this->analyze_model->getSearchEngineByDomainId($domain['id']);
                $searchkeyword   = array();
                $searchkeywordid = array();
                foreach ($keywords as $keyword) {
                    $searchkeyword[]                   = $keyword['name'];
                    $searchkeywordid[$keyword['name']] = $keyword;
                }

                if ($searchengines && $keywords) {

                    foreach ($searchengines as $key => $searchengine) {

                        $data = array();

                        $engine = $engines['name'];

                        $token = $this->ci->config->config['serpstat']['token'];
                        $url   = 'http://api.serpstat.com/v3/domain_keywords?query=' . $domain['domain_name'] . '&token=' . $token . '&se=' . $searchengine['name'];

                        echo $url;
                        $result = json_decode(file_get_contents($url), true);
                        echo '<pre>';
                        print_r($result);

                        if ( isset($result['status_msg']) &&  $result['status_msg'] == 'OK') {

                            foreach ($result['result']['hits'] as $keyword) {
                                if (in_array(trim($keyword['keyword']), $searchkeyword)) {
                                    $array = array();
                                    $this->db->flush_cache();
                                    $array['user_id']            = $domain['userid'];
                                    $array['domain_id']          = $domain['id'];
                                    $array['search_engine_id']   = $searchengine['id'];
                                    $array['keyword_id']         = $searchkeywordid[trim($keyword['keyword'])]['id'];
                                    $array['search_engine']      = $searchengine['name'];
                                    $array['keyword']            = $keyword['keyword'];
                                    $array['position']           = $keyword['position'];
                                    $array['volume_google']      = $keyword['region_queries_count'];
                                    $array['cost']               = $keyword['cost'];
                                    $array['competition_in_ppc'] = $keyword['concurrency'];
                                    $array['results']            = $keyword['found_results'];
                                    $array['updated_date']       = date('Y-m-d H:i:s');
                                    $array['created_date']       = date('Y-m-d H:i:s');
                                    $exist                       = $this->analyze_model->getSerpByKeywordIdAndSearchEngine($searchkeywordid[trim($keyword['keyword'])]['id'], $searchengine['id']);
                                    if ($exist) {
                                        $this->db->update('serp', $array, array('id' => $exist['id']));
                                    } else {
                                        $this->db->insert('serp', $array);
                                    }
                                }
                            }
                        }
                        sleep(1);
                    }

                      foreach ($searchengines as $key => $searchengine) {
                        //updating the not found keywords
                        $this->updateNotFoundKeywords($domain['id'],$domain['userid'],$searchengine['id'], $searchengine['name']);
                      }  
                    

                    $nextRun = date('Y-m-d H:i:s', strtotime("+7 day", strtotime(date('Y-m-d H:i:s'))));
                    $this->analyze_model->updateKeywordNextSerpRun($domain['id'], $nextRun);
                }
            }
           
        }

    }

    public function delay()
    {
        //immediate run for serp
        //getting all domains
        $domains = $this->analyze_model->getAllDomains();
        if (!$domains) {
            return false;
        }
        foreach ($domains as $domain) {
            $keywords = $this->analyze_model->getAllKeywordsForDelaySerpCronByDomainId($domain['id']);

            $searchengines = $this->analyze_model->getSearchEngineByDomainId($domain['id']);
            if ($keywords) {
                // $totalsearchEngines = count($searchengines);
                // $totalKeywords      = count($keywords);
                // $chunk              = $totalKeywords / $totalsearchEngines;
                // $keywords           = array_chunk($keywords, $totalsearchEngines);
                  $temp = array(); 
                 foreach ($keywords as $key=>$keys) {
                     if ($key%2 == 0) {
                         $temp[0][] = $keys;
                     } else {
                         $temp[1][] = $keys;
                     }

                 } 
                $keywords = $temp; 
            }
            if ($searchengines && $keywords) {
                foreach ($searchengines as $key => $searchengine) {
                    if($keywords[$key]) {
                        foreach ($keywords[$key] as $keyword) {
                            $data                     = array();
                            $data['user_id']          = $keyword['user_id'];
                            $data['auth_token']       = '44E7hMC1UcYVoUVpsx7U';
                            $data['keywords']         = urlencode($keyword['name']);
                            $data['locale']           = urlencode(str_replace('-mobile', '', $searchengine['name']));
                            $data['domain_id']        = $keyword['domain_id'];
                            $data['mobile_search']    = (preg_match('/mobile/i', $searchengine['name'])) ? true : false;
                            $data['search_engine_id'] = $searchengine['id'];
                            $data['keyword_id']       = $keyword['id'];
                            $data['callback']         = 'http://my.campaigns.io/auth/getResult_Immediate.html?requestId=' . $data['user_id'] . ':' . $data['domain_id'] . ':' . $data['keyword_id'] . ':' . $data['search_engine_id']; //call back URL
                            $request                  = array();
                            $ch                       = curl_init();

                            if ($data['mobile_search']) {
                                $url                      = 'https://api.authoritylabs.com/keywords?keyword=' . $data['keywords'] . '&locale=' . $data['locale'] . '&callback=' . $data['callback'] . '&engine=google' . '&mobile=' . $data['mobile_search'] . '&auth_token=' . $data['auth_token']; //http://api.authoritylabs.com/keywords/priority  // if immigate queue     
                            } else {
                                $url                      = 'https://api.authoritylabs.com/keywords?keyword=' . $data['keywords'] . '&locale=' . $data['locale'] . '&callback=' . $data['callback'] . '&engine=google' . '&auth_token=' . $data['auth_token']; //http://api.authoritylabs.com/keywords/priority  // if immigate queue
                            }
                          
                            // $url                      = 'https://api.authoritylabs.com/keywords?keyword=' . $data['keywords'] . '&locale=' . $data['locale'] . '&callback=' . $data['callback'] . '&engine=google' . '&mobile=' . $data['mobile_search'] . '&auth_token=' . $data['auth_token'];
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                "Cookie: _papi_session_old=BAh7B0kiGXdhcmRlbi51c2VyLnVzZXIua2V5BjoGRVRbCEkiCVVzZXIGOwBGWwZpaUkiIiQyYSQxMCQ5S3F3SW5zY1dQRXB6dXIvY0FvQUFPBjsAVEkiD3Nlc3Npb25faWQGOwBGIiUxOTA1MzQ2MDIxYmYzMjIxMjIyOTU2NWU1YWRmZjRhMQ%3D%3D--a49a7eb78fb840b4ac331e99b7de91b2755950af",
                                "Content-Type: application/json",
                            )
                            );
                            $data1 = curl_exec($ch);
                            if ($data1 == 'OK') {
                                //inserting the next run for keyword
                                //
                                if($keyword['next_serp_run'] == null) {
                                    $nextRun = date('Y-m-d H:i:s', strtotime("+7 day", strtotime($keyword['create_at'])));
                                } else {
                                    $nextRun = date('Y-m-d H:i:s', strtotime("+7 day", strtotime($keyword['next_serp_run'])));    
                                }
                                $this->analyze_model->updateKeywordNextSerpRun($data['keyword_id'], $nextRun);
                            }

                        }     
                    }
                }
            }

            
            //getting the user information
            // $userInfo    = $this->analyze_model->getusersinfo($domain['userid']);
            // $userProfile = $this->analyze_model->getuserprofile($domain['userid']);
            // $this->sendemailupdate($userInfo, $userProfile, $domain, date('Y-m-d'));

        }

    }
    

    public function updateNotFoundKeywords($domainId, $userId, $searchengineid, $searchenginename)
    {
       $this->db->flush_cache();
       $query = "select name,id from keywords_master km where id not in (select keyword_id from serp where domain_id='".$domainId."') and domain_id='".$domainId."'";
       $query = $this->db->query($query);
       if ($query) {
            $result =  $query->result_array(); 
            if($result) {
                foreach($result as $res) {
                    $this->db->flush_cache();
                    $array = array();
                    $array['user_id'] = $userId;
                    $array['domain_id'] = $domainId;
                    $array['search_engine_id'] = $searchengineid;
                    $array['keyword_id'] = $res['id'];
                    $array['search_engine'] = $searchenginename;
                    $array['local'] = '';
                    $array['keyword'] = $res['name'];
                    $array['updated_date'] = date('Y-m-d H:i:s');
                    $array['created_date'] =  date('Y-m-d H:i:s');
                    $this->db->insert('serp', $array);
                }
            }
       } 
       



    }

    public function testemail()
    {
        $this->load->library('email');
        $this->email->from('info@campaigns.io', 'Campaigns.io');
        $this->email->to('obsession.raj@gmail.com');
        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');
        $this->email->send();
    }

    public function sendemailupdate($userinfo, $userProfile, $domaininfo, $date)
    {
        // $this->load->library('email');
        $message = " ";
        $message .= " Dear " . $userProfile['first_name'] . " " . $userProfile['last_name'] . ".<br><br>";
        $message .= " Please find attached a PDF & CSV report for your keywords for " . $domaininfo['domain_name'] . " for the week of " . $date . "<br>";
        $message .= " For more detailed analysis, please visit http://app.campaigns.io/auth/dashboard.html.<br>";
        $message .= " regards<br>";
        $message .= " https://campaigns.io";
        $to      = $userinfo[0]->email;
        $subject = 'Keywords update notification';
        $this->load->library('email');
        $this->email->from('info@campaigns.io', 'Campaigns.io');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();

    }
}
