<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use Piwik\API\Request;
use Piwik\FrontController;

class Piwik extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'security'));
        $this->load->model('auth/analyze_model');
        $this->ci = &get_instance();
    }
    public function ecommerce()
    {
        if (!$this->ci_auth->is_logged_in()) {
            redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
              $date = $this->input->post('date');
              $_SESSION['ecom_data_date'] = $date;
                $this->load->view(get_template_directory() . '/ecommerce', $data);
            } else {
                redirect(site_url('/admin/login'));
            }
        }
    }

    public function getvisits()
    {
        if (!$this->ci_auth->is_logged_in()) {
            // redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domainId = $this->input->post('domainId');
                $domainDetails = $this->analyze_model->getDomain($domainId);
                $userDetails   = $this->analyze_model->getusersinfo($userId);
                $currentDate = date('Y-m-'.'01');
                if ($domainDetails[0]->piwik_site_id > 0) {
                   $url .= "?module=API&method=VisitsSummary.get&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=".$currentDate;
                   $url .= "&userLogin=" . $userDetails[0]->username;
                   $result = $this->getPiwikResults($url);
                   $totalUniqueVisitors = $result['nb_uniq_visitors'];
                   $totalVisitors = $result['nb_visits'];
                   $totalPagePerVisit = $result['nb_actions_per_visit'];
                   $return['payload']['totalUniqueVisitors'] =  $totalUniqueVisitors;
                   $return['payload']['totalVisitors'] = $totalVisitors;
                   $return['payload']['totalPagePerVisit'] = $totalPagePerVisit;
                   $return['status'] = 'success';
                   echo json_encode($return);die; 
                } else {
                    //no piwik added
                    $return['status'] = 'error';
                    $return['msg'] = 'No piwik data available';
                    echo json_encode($return);die;
                }
                
            } else {
                // redirect(site_url('/admin/login'));
            }
        }

    }

    public function totalvisits()
    {
        if (!$this->ci_auth->is_logged_in()) {
            // redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domainId = $this->input->post('domainId');
                $domainDetails = $this->analyze_model->getDomain($domainId);
                $userDetails   = $this->analyze_model->getusersinfo($userId);
                $currentDate = date('Y-m-'.'01');
                if ($domainDetails[0]->piwik_site_id > 0) {
                   //getting total visitors graph
                   $url .= "?module=API&method=VisitsSummary.getVisits&idSite=".$domainDetails[0]->piwik_site_id."&period=day&date=last30";
                   $url .= "&userLogin=" . $userDetails[0]->username;
                   $result = $this->getPiwikResults($url);
                   $totalVisitsGraph = $result;
                   $return['payload']['totalVisitsGraph'] = $totalVisitsGraph;
                   $return['status'] = 'success';
                   echo json_encode($return);die; 

                } else {
                    //no piwik added
                    $return['payload']['totalVisitsGraph'] = null;
                    $return['status'] = 'error';
                    $return['msg'] = 'No piwik data available';
                    echo json_encode($return);die; 

                }
                
            } else {
                // redirect(site_url('/admin/login'));
            }
        }

      

    }

    public function pagepervisit()
    {
        if (!$this->ci_auth->is_logged_in()) {
            // redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domainId = $this->input->post('domainId');
                $domainDetails = $this->analyze_model->getDomain($domainId);
                $userDetails   = $this->analyze_model->getusersinfo($userId);
                $currentDate = date('Y-m-'.'01');
                if ($domainDetails[0]->piwik_site_id > 0) {
                   //getting total visitors graph
                   $url .= "?module=API&method=VisitsSummary.get&idSite=".$domainDetails[0]->piwik_site_id."&period=day&date=last30&showColumns=label,nb_actions_per_visit";
                   $url .= "&userLogin=" . $userDetails[0]->username;
                   $result = $this->getPiwikResults($url);
                   $totalPagePerVisitGraph = $result;
                   $return['payload']['totalPagePerVisitGraph'] = $totalPagePerVisitGraph;
                   $return['status'] = 'success';
                   echo json_encode($return);die; 

                } else {
                    //no piwik added
                   $return['status'] = 'error';
                   $return['payload']['totalPagePerVisitGraph'] = null;
                   $return['msg'] = 'No piwik data available';
                   echo json_encode($return);die; 
                }
                
            } else {
                // redirect(site_url('/admin/login'));
            }
        }

    }

    public function uniquevisitors()
    {
        if (!$this->ci_auth->is_logged_in()) {
            // redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domainId = $this->input->post('domainId');
                $domainDetails = $this->analyze_model->getDomain($domainId);
                $userDetails   = $this->analyze_model->getusersinfo($userId);
                $currentDate = date('Y-m-'.'01');
                if ($domainDetails[0]->piwik_site_id > 0) {
                   //getting total visitors graph
                   $url .= "?module=API&method=VisitsSummary.getUniqueVisitors&idSite=".$domainDetails[0]->piwik_site_id."&period=day&date=last30";
                   $url .= "&userLogin=" . $userDetails[0]->username;
                   $result = $this->getPiwikResults($url);
                   $totalUniqueVisitorsGraph = $result;
                   $return['payload']['uniqueVisitorsGraph'] = $totalUniqueVisitorsGraph;
                   $return['status'] = 'success';
                   echo json_encode($return);die;
                } else {
                    //no piwik added
                    $return['payload']['uniqueVisitorsGraph'] = null;
                    $return['status'] = 'error';
                    $return['msg'] = 'No piwik data available';
                    echo json_encode($return);die;
                }
                
            } else {
                // redirect(site_url('/admin/login'));
            }
        }

    }

    public function referrervisits()
    {
        if (!$this->ci_auth->is_logged_in()) {
            // redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domainId = $this->input->post('domainId');
                $domainDetails = $this->analyze_model->getDomain($domainId);
                $userDetails   = $this->analyze_model->getusersinfo($userId);
                $currentDate = date('Y-m-'.'01');
                if ($domainDetails[0]->piwik_site_id > 0) {
                   //getting total visitors graph
                   $url .= "?module=API&method=Referrers.getReferrerType&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=".$currentDate."&showColumns=label,nb_visits";
                   $url .= "&userLogin=" . $userDetails[0]->username;
                   $result = $this->getPiwikResults($url);
                   $referrervisit = $result;
                   $return['payload']['referrervisit'] =  $referrervisit;


                   $url .= "?module=API&method=Referrers.getReferrerType&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=last12&showColumns=label,nb_visits";
                   $url .= "&userLogin=" . $userDetails[0]->username;
                   $result = $this->getPiwikResults($url);
                   $referrervisitgraph = $result;
                   $return['payload']['referrervisitgraph'] =  $referrervisitgraph;

                   $return['status'] = 'success';
                   echo json_encode($return);
                } else {
                    //no piwik added
                    $return['payload']['referrervisit'] =  null;
                    $return['status'] = 'error';
                    $return['msg'] = 'No piwik data available';
                    echo json_encode($return);

                }
                
            } else {
                // redirect(site_url('/admin/login'));
            }
        }
    }


    

    public function visittrends()
    {
        if (!$this->ci_auth->is_logged_in()) {
            // redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domainId = $this->input->post('domainId');
                $domainDetails = $this->analyze_model->getDomain($domainId);
                $userDetails   = $this->analyze_model->getusersinfo($userId);
                $currentDate = date('Y-m-'.'01');
                if ($domainDetails[0]->piwik_site_id > 0) {
                   //getting total visitors graph
                   $url .= "?module=API&method=Referrers.getReferrerType&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=last12&showColumns=label,nb_visits,segment";
                   $url .= "&userLogin=" . $userDetails[0]->username;
                   $result = $this->getPiwikResults($url);
                   $visitSourcesGraph = $result;
                } else {
                    //no piwik added
                }
                
            } else {
                // redirect(site_url('/admin/login'));
            }
        }
    }

 
    public function visitsources()
    {
        if (!$this->ci_auth->is_logged_in()) {
            // redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domainId = $this->input->post('domainId');
                $domainDetails = $this->analyze_model->getDomain($domainId);
                $userDetails   = $this->analyze_model->getusersinfo($userId);
                $currentDate = date('Y-m-'.'01');
                if ($domainDetails[0]->piwik_site_id > 0) {
                   //getting total visitors graph
                   $url .= "?module=API&method=Referrers.getWebsites&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=".$currentDate."&showColumns=label,nb_visits,segment";
                   $url .= "&userLogin=" . $userDetails[0]->username;
                   $result = $this->getPiwikResults($url,7);
                   $visitSourcesGraph = $result;
                   $return['status'] = 'success';
                   $return['payload']['sites'] = $visitSourcesGraph;
                   echo json_encode($return);die;  
                } else {
                    //no piwik added
                    $return['status'] = 'error';
                    $return['payload']['sites'] = null;
                    $return['msg'] = 'Piwik data not available';
                    echo json_encode($return);die;  
                }
                
            } else {
                // redirect(site_url('/admin/login'));
            }
        }
    }

    public function topcountry()
    {
        if (!$this->ci_auth->is_logged_in()) {
            // redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domainId = $this->input->post('domainId');
                $domainDetails = $this->analyze_model->getDomain($domainId);
                $userDetails   = $this->analyze_model->getusersinfo($userId);
                $currentDate = date('Y-m-'.'01');
                if ($domainDetails[0]->piwik_site_id > 0) {
                   //getting total visitors graph
                   $url .= "?module=API&method=UserCountry.getCountry&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=".$currentDate."&showColumns=label,nb_visits";
                   $url .= "&userLogin=" . $userDetails[0]->username;
                   $result = $this->getPiwikResults($url,7);
                   $topCountryGraph = $result;
                   $return['status'] = 'success';
                   $return['payload']['topcountries'] = $topCountryGraph;
                   echo json_encode($return);die;  
                } else {
                    //no piwik added
                    $return['status'] = false;
                    $return['payload'] = null;
                    $return['msg'] = 'Piwik data not available';
                    echo json_encode($return);die;
                }
                
            } else {
                // redirect(site_url('/admin/login'));
            }
        }
    }

    public function getsearchengineclicks() {

      if ( ! $this->ci_auth->is_logged_in() ) {
          redirect( site_url('auth/login') );
      }
      elseif ( $this->ci_auth->is_logged_in(false) ) {
          redirect('/auth/sendactivation/');
      }
      else {
          $user_id = $this->ci_auth->get_user_id();
          $domainId = $this->input->post('domainId');
          $domainDetails = $this->analyze_model->getDomain( $domainId );
          $currentDate = date('Y-m-'.'01');

          if ( $domainDetails[0]->piwik_site_id > 0) {
             //getting total visitors graph
             $url = "?module=API&method=Referrers.getSearchEngines&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=today&showColumns=label,nb_visits";
             $result = $this->getPiwikResults($url);
             $totalClicks = 0;
             if ( $result ) {

              if( isset( $result['result'] ) && 'error' === $result['result'] ){
                $return['status'] = false;
              }
              else{
                $return['status'] = 'success';
                foreach($result as $res) {
                  $totalClicks += $res['nb_visits'];
                }
              }
                
             }
             
             $return['payload']['totalClicks'] = $totalClicks;
             echo json_encode($return);die;  
          } else {
              //no piwik added
              $return['status'] = false;
              $return['payload']['totalClicks'] = null;
              $return['msg'] = 'Piwik data not available';
              echo json_encode($return);die;
          }
      }

      // if (!$this->ci_auth->is_logged_in()) {
      //     // redirect(site_url('auth/login'));
      // } elseif ($this->ci_auth->is_logged_in(false)) {
      //     // redirect('/auth/sendactivation/');
      // } else {
      //     if ($this->ci_auth->canDo('login_to_frontend')) {
      //         $domainId = $this->input->post('domainId');
      //         $domainDetails = $this->analyze_model->getDomain($domainId);
      //         $currentDate = date('Y-m-'.'01');
      //         if ($domainDetails[0]->piwik_site_id > 0) {
      //            //getting total visitors graph
      //            $url .= "?module=API&method=Referrers.getSearchEngines&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=today&showColumns=label,nb_visits";
      //            $result = $this->getPiwikResults($url);
      //            $totalClicks = 0;
      //            if ($result) {
      //               foreach($result as $res) {
      //                 $totalClicks+=$res['nb_visits'];
      //               }
      //            }
      //            $return['status'] = 'success';
      //            $return['payload']['totalClicks'] = $totalClicks;
      //            echo json_encode($return);die;  
      //         } else {
      //             //no piwik added
      //             $return['status'] = false;
      //             $return['payload']['totalClicks'] = null;
      //             $return['msg'] = 'Piwik data not available';
      //             echo json_encode($return);die;
      //         }
              
      //     } else {
      //         // redirect(site_url('/admin/login'));
      //     }
      // }

    }


    public function newvsreturning()
    {

    }
    private function getPiwikResults($urlParam,$limit='')
    {
        $apiUrl        = $this->ci->config->config['piwik']['api_url'];
        $token_auth = $this->ci->config->config['piwik']['auth_token'];
        $url        = $apiUrl;
        $url.=$urlParam;
        $url .= "&token_auth=$token_auth";
        if ($limit == '' ) {
          $limit=40;  
        }
        $url .= "&format=JSON&filter_limit=".$limit;
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        $fetched    = file_get_contents($url, false, stream_context_create($arrContextOptions));
        $result = json_decode($fetched,true);     
        return $result;
    }

    private function getPiwikResults1($urlParam,$limit='')
    {
        $apiUrl        = $this->ci->config->config['piwik']['api_url'];
        $token_auth = $this->ci->config->config['piwik']['auth_token'];
        // $url        = $apiUrl;
        $url=$urlParam;
        $url .= "&token_auth=$token_auth";
        if ($limit == '' ) {
          $limit=40;  
        }
        $url .= "&format=JSON&filter_limit=".$limit;

        require_once '/var/www/stats.campaigns.io/public_html' . "/papi.php";

        require_once '/var/www/stats.campaigns.io/public_html' . "/index.php";
        require_once '/var/www/stats.campaigns.io/public_html' . "/core/API/Request.php";

        $environment = new \Piwik\Application\Environment(null);
        $environment->init();

    }

    public function ecommercesummarystats()
    {
        if (!$this->ci_auth->is_logged_in()) {
            // redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domainId = $this->input->post('domainId');
                if(!$domainId) {
                  $domainId =$this->session->userdata('domainId');
                }
                if ($_SESSION['ecom_data_date'] && $_SESSION['ecom_data_date'] != '') {
                  $date = $_SESSION['ecom_data_date'];
                }else{
                  $date = 'today';
                }

                $domainDetails = $this->analyze_model->getDomain($domainId);
                if ($domainDetails[0]->piwik_site_id > 0) {
                   //getting total visitors graph
                   $url = "?module=API&method=Goals.get&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=".$date."&idGoal=ecommerceOrder";
                   $result = $this->getPiwikResults($url);

                   $url = "?module=API&method=Goals.get&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=".$date."&idGoal=ecommerceAbandonedCart";
                   $result1 = $this->getPiwikResults($url);


                   $url = "?module=API&method=SitesManager.getSiteFromId&idSite=".$domainDetails[0]->piwik_site_id."";
                   $siteResult = $this->getPiwikResults($url);


                   $return['status'] = 'success';
                   $return['payload']['ecommercestats'] = $result;
                   $return['payload']['siteResult'] = $siteResult;
                   $return['payload']['abandonedcartstats'] = $result1;


                   echo json_encode($return);die;  
                } else {
                    //no piwik added
                    $return['status'] = false;
                    $return['payload']['ecommercestats'] = null;
                    $return['msg'] = 'Piwik data not available';
                    echo json_encode($return);die;
                }
                
            } else {
                // redirect(site_url('/admin/login'));
            }
         }   

    }    

    public function ecommerceproductdata()
    {
        if (!$this->ci_auth->is_logged_in()) {
            // redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domainId = $this->input->post('domainId');
                if(!$domainId) {
                  $domainId =$this->session->userdata('domainId');
                }
                if ($_SESSION['ecom_data_date'] && $_SESSION['ecom_data_date'] != '') {
                  $date = $_SESSION['ecom_data_date'];
                }else{
                  $date = 'today';
                }
                $domainDetails = $this->analyze_model->getDomain($domainId);
                if ($domainDetails[0]->piwik_site_id > 0) {
                   //getting total visitors graph
                   $url .= "?module=API&method=Goals.getItemsName&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=".$date;
                   $result = $this->getPiwikResults($url);

                   $url = "?module=API&method=SitesManager.getSiteFromId&idSite=".$domainDetails[0]->piwik_site_id."";
                   $siteResult = $this->getPiwikResults($url);

                   $return['status'] = 'success';
                   $return['payload']['productdata'] = $result;
                   $return['payload']['siteResult'] = $siteResult;
               
                   echo json_encode($return);die;  
                } else {
                    //no piwik added
                    $return['status'] = false;
                    $return['payload']['productdata'] = null;
                    $return['msg'] = 'Piwik data not available';
                    echo json_encode($return);die;
                }
                
            } else {
                // redirect(site_url('/admin/login'));
            }
         }   

    }

    public function ecommercereferrertype()
    {
        if (!$this->ci_auth->is_logged_in()) {
            // redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domainId = $this->input->post('domainId');
                if(!$domainId) {
                  $domainId =$this->session->userdata('domainId');
                }

                $domainDetails = $this->analyze_model->getDomain($domainId);
                if ($_SESSION['ecom_data_date'] && $_SESSION['ecom_data_date'] != '') {
                  $date = $_SESSION['ecom_data_date'];
                }else{
                  $date = 'today';
                }
                if ($domainDetails[0]->piwik_site_id > 0) {
                   //getting total visitors graph
                   $url .= "?module=API&method=Referrers.getReferrerType&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=".$date;
                   $result = $this->getPiwikResults($url);

                   $url = "?module=API&method=SitesManager.getSiteFromId&idSite=".$domainDetails[0]->piwik_site_id."";
                   $siteResult = $this->getPiwikResults($url);

                   $return['status'] = 'success';
                   $return['payload']['referrer'] = $result;
                   $return['payload']['siteResult'] = $siteResult;
               
                   echo json_encode($return);die;  
                } else {
                    //no piwik added
                    $return['status'] = false;
                    $return['payload']['referrer'] = null;
                    $return['msg'] = 'Piwik data not available';
                    echo json_encode($return);die;
                }
                
            } else {
                // redirect(site_url('/admin/login'));
            }
         }   

    }


    public function ecommercekeywords()
    {
        if (!$this->ci_auth->is_logged_in()) {
            // redirect(site_url('auth/login'));
        } elseif ($this->ci_auth->is_logged_in(false)) {
            // redirect('/auth/sendactivation/');
        } else {
            if ($this->ci_auth->canDo('login_to_frontend')) {
                $domainId = $this->input->post('domainId');
                if(!$domainId) {
                  $domainId =$this->session->userdata('domainId');
                }
                $domainDetails = $this->analyze_model->getDomain($domainId);
                if ($_SESSION['ecom_data_date'] && $_SESSION['ecom_data_date'] != '') {
                  $date = $_SESSION['ecom_data_date'];
                }else{
                  $date = 'today';
                }
                if ($domainDetails[0]->piwik_site_id > 0) {
                   //getting total visitors graph
                   $url .= "?module=API&method=Referrers.getKeywords&idSite=".$domainDetails[0]->piwik_site_id."&period=month&date=".$date;
                   $result = $this->getPiwikResults($url);

                   $url = "?module=API&method=SitesManager.getSiteFromId&idSite=".$domainDetails[0]->piwik_site_id."";
                   $siteResult = $this->getPiwikResults($url);

                   $return['status'] = 'success';
                   $return['payload']['keywords'] = $result;
                   $return['payload']['siteResult'] = $siteResult;
               
                   echo json_encode($return);die;  
                } else {
                    //no piwik added
                    $return['status'] = false;
                    $return['payload']['keywords'] = null;
                    $return['msg'] = 'Piwik data not available';
                    echo json_encode($return);die;
                }
                
            } else {
                // redirect(site_url('/admin/login'));
            }
         }   

    }


    public function code()
    {
        $id            = $this->uri->segment(3);
        $domainDetails = $this->analyze_model->getDomain($id);
        if (!$domainDetails || !$domainDetails[0]->piwik_site_id) {
            echo 'No code available for this domain';
            die;
        }
        $sitename      = strtr($domainDetails[0]->domain_name, array('http://' => '', 'https://' => '', 'www.' => '', '/' => ''));

        $html = "";
        $html .= '<!-- Piwik -->';
        $html.="\n";
        $html .= '<script type="text/javascript">';
        $html.="\n";
        $html .= 'var _paq = _paq || [];';
        $html.="\n";
        $html .= '// tracker methods like "setCustomDimension" should be called before "trackPageView"';
        $html.="\n";
        $html .= '_paq.push(["setDocumentTitle", document.domain + "/" + document.title]);';
        $html.="\n";
        $html .= '_paq.push(["setCookieDomain", "*.' . $sitename . '"]);';
        $html.="\n";
        $html .= '_paq.push(["setDomains", ["*.' . $sitename . '"]]);';
        $html.="\n";
        $html .= "_paq.push(['trackPageView']);";
        $html.="\n";
        $html .= "_paq.push(['enableLinkTracking']);";
        $html.="\n";
        $html .= '(function() {';
        $html.="\n";
        $html .= 'var u="//stats.campaigns.io/";';
        $html.="\n";
        $html .= "_paq.push(['setTrackerUrl', u+'piwik.php']);";
        $html.="\n";
        $html .= "_paq.push(['setSiteId', '" . $domainDetails[0]->piwik_site_id . "']);";
        $html.="\n";
        $html .= "var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];";
        $html.="\n";
        $html .= "g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js';";
        $html .= "s.parentNode.insertBefore(g,s);";
        $html.="\n";
        $html .= '})()';
        $html.="\n";
        $html .= '</script>';
        $html.="\n";
        $html .= '<noscript><p><img src="//stats.campaigns.io/piwik.php?idsite=4&rec=1" style="border:0;" alt=""';
        $html .= ' /></p></noscript>';
        $html.="\n";
        $html .= '<!-- End Piwik Code -->';
        $text = htmlentities($html);
        echo '<code>';
        echo nl2br($text);
        echo '</code>';
    }

}
