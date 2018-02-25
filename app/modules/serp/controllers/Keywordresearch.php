<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Keywordresearch extends CI_Controller
{
    private $ci;

    public function __construct()
    {
        parent::__construct();
        //$ci =& get_instance();
        $this->load->model('auth/analyze_model');
        $this->load->model('serp/serpcompetitorcompare_model');
        $this->load->model('serp/serporganickeyword_model');
        $this->load->model('serp/serpcompetitororganicsearch_model');
        $this->load->model('serp/serpdomain_model');
        $this->load->model('serp/serpdomainhistory_model');
        $this->load->model('serp/serpaddkeywords_model');
        $this->ci = &get_instance();
    }

    public function domain_compare()
    {
        $domain1 = strtr($this->session->userdata('domainUrl'), array('http://' => '', 'https://' => '', 'www.' => ''));
        $domain2 = strtr($_POST['domain2'], array('http://' => '', 'https://' => '', 'www.' => ''));
        $domain3 = strtr($_POST['domain3'], array('http://' => '', 'https://' => '', 'www.' => ''));

        $res = $this->serpcompetitorcompare_model->getserpcompetitorByDomains($domain1, $domain2);

        if (!$res || strtotime("+7 day", strtotime($res['created'])) < time()) {
            $token = $this->ci->config->config['serpstat']['token'];
            if ($domain1 && $domain2) {
                $url    = 'http://api.serpstat.com/v3/domains_intersection?query=' . $domain1 . ',' . $domain2 . '&se=g_uk&token=' . $token;
                $result = json_decode(file_get_contents($url), true);
                if (isset($result['status_msg']) && $result['status_msg'] == 'OK') {
                    $this->db->flush_cache();
                    $array                         = array();
                    $array['domain1']              = $domain1;
                    $array['domain2']              = $domain2;
                    $array['total_common_keyword'] = $result['result']['total'];
                    $array['result']               = json_encode($result['result']['hits']);
                    $array['created']              = date('Y-m-d H:i:s');
                    $array['modified']             = date('Y-m-d H:i:s');
                    $this->db->insert('serp_competitor_compare', $array);
                    $insert_id            = $this->db->insert_id();
                    $total_common_keyword = $array['total_common_keyword'];
                }
                sleep(1);
                $url    = 'http://api.serpstat.com/v3/domains_uniq_keywords?query=' . $domain1 . '&minus_domain=' . $domain2 . '&se=g_uk&token=' . $token;
                $result = json_decode(file_get_contents($url), true);
                if (isset($result['status_msg']) && $result['status_msg'] == 'OK') {
                    $this->db->flush_cache();
                    $array                   = array();
                    $array['domain1_unique'] = $result['result']['total'];
                    $array['domain1_total']  = $result['result']['total'] + $total_common_keyword;
                    $this->db->update('serp_competitor_compare', $array, array('id' => $insert_id));
                }
                sleep(1);
                $url    = 'http://api.serpstat.com/v3/domains_uniq_keywords?query=' . $domain2 . '&minus_domain=' . $domain1 . '&se=g_uk&token=' . $token;
                $result = json_decode(file_get_contents($url), true);
                if (isset($result['status_msg']) && $result['status_msg'] == 'OK') {
                    $this->db->flush_cache();
                    $array                   = array();
                    $array['domain2_unique'] = $result['result']['total'];
                    $array['domain2_total']  = $result['result']['total'] + $total_common_keyword;
                    $this->db->update('serp_competitor_compare', $array, array('id' => $insert_id));
                }
            }
            $res = $this->serpcompetitorcompare_model->getserpcompetitorByDomains($domain1, $domain2);
            if ($res) {
                $this->load->view(get_template_directory() . '/domain_compare_competition', $res);
            } else {
                $this->load->view(get_template_directory() . '/domain_compare_competition');
            }
        } else {
            $this->load->view(get_template_directory() . '/domain_compare_competition', $res);
        }

    }

    public function organic_keywords()
    {
        $domain = strtr($this->session->userdata('domainUrl'), array('http://' => '', 'https://' => '', 'www.' => ''));
        $res  = $this->serporganickeyword_model->getSerpOrganiKeywordByDomain($domain);
        if (!$res || strtotime("+7 day", strtotime($res['created'])) < time()) {
            if ($domain) {
            	$token = $this->ci->config->config['serpstat']['token'];
            	sleep(1);
                $url    = 'http://api.serpstat.com/v3/domain_keywords?query=' . $domain . '&se=g_uk&token=' . $token;
                $result = json_decode(file_get_contents($url), true);
                if (isset($result['status_msg']) && $result['status_msg'] == 'OK') {
                    $this->db->flush_cache();
                    $array             = array();
                    $array['domain']   = $domain;
                    $array['result']   = json_encode($result['result']['hits']);
                    $array['created']  = date('Y-m-d H:i:s');
                    $array['modified'] = date('Y-m-d H:i:s');
                    $this->db->insert('serp_organic_keyword',$array);
                }
               $res = $this->serporganickeyword_model->getSerpOrganiKeywordByDomain($domain); 
               if ($res) {
               		$this->load->view(get_template_directory() . '/organic_keywords', $res);		
               } else {
               		$this->load->view(get_template_directory() . '/organic_keywords');
               }
               
            }
        } else {
        	 $this->load->view(get_template_directory() . '/organic_keywords', $res);
        }
    }

    public function competitor_organic_search(){
        
    	$domain = strtr($this->session->userdata('domainUrl'), array('http://' => '', 'https://' => '', 'www.' => ''));
    	$res  = $this->serpcompetitororganicsearch_model->getcompetitorOrganicSearchByDomain($domain);
    	if (!$res || strtotime("+7 day", strtotime($res['created'])) < time()) {
    	    if ($domain) {
    	    	$token = $this->ci->config->config['serpstat']['token'];
    	    	sleep(1);
    	        $url    = 'http://api.serpstat.com/v3/competitors?query=' . $domain . '&se=g_uk&token=' . $token;
    	        $result = json_decode(file_get_contents($url), true);
    	        if (isset($result['status_msg']) && $result['status_msg'] == 'OK') {
    	            $this->db->flush_cache();
    	            $array             = array();
    	            $array['domain']   = $domain;
    	            $array['result']   = json_encode($result['result']);
    	            $array['created']  = date('Y-m-d H:i:s');
    	            $array['modified'] = date('Y-m-d H:i:s');
    	            $this->db->insert('serp_competitor_organic_search',$array);
    	        }
    	       $res = $this->serpcompetitororganicsearch_model->getcompetitorOrganicSearchByDomain($domain); 
    	       if ($res) {
    	       		$this->load->view(get_template_directory() . '/competitor_organic_search', $res);		
    	       } else {
    	       		$this->load->view(get_template_directory() . '/competitor_organic_search');
    	       }
    	       
    	    }
    	} else {
    		 $this->load->view(get_template_directory() . '/competitor_organic_search', $res);
    	}
    	
    }

    public function keyword_position_distribution()
    {
    	$domain = strtr($this->session->userdata('domainUrl'), array('http://' => '', 'https://' => '', 'www.' => ''));
    	$domainId = $this->session->userdata['domainId'];

    	$serp = $this->analyze_model->getSerpResult($domainId);
    	   $array[0] = 0;
    		$array[1] = 1;
    		$array[2] = 2;
    		$array[3] = 3;
    		$array[4] = 4;
    		$array[5] = 5;
    		$array[6] = 6;
    	if($serp) {
    		$pos1 = $pos2 = $pos3 = $pos4 = $pos5 = $pos6 = $pos7 = 0;
    		foreach($serp as $s){
    			if($s['position'] == 1) {
    				$pos1+= 1;
    				$array[0] = $pos1;
    			}else if($s['position']>=2 && $s['position']<=3) {
    				$pos2+= 1;
    				$array[1] = $pos2;
    			}else if($s['position']>=4 && $s['position']<=5) {
    				$pos3+= 1;
    				$array[2] = $pos3;
    			}else if($s['position']>=6 && $s['position']<=10) {
    				$pos4+= 1;
    				$array[3] = $pos4;	
    			}else if($s['position']>=11 && $s['position']<=20) {
    				$pos5+= 1;
    				$array[4] = $pos5;
    			}else if($s['position']>=21 && $s['position']<=50) {
    				$pos6+= 1;
    				$array[5] = $pos6;
    			}else if($s['position']>=51 && $s['position']<=100) {
    				$pos7+= 1;
    				$array[6] = $pos7;
    			}	
    		}
    	}
    	$return['status'] = true;
    	$return['data'] = $array;
    	echo json_encode($return);die;
    }

    public function competitors_graph()
    {
    	$domain = strtr($this->session->userdata('domainUrl'), array('http://' => '', 'https://' => '', 'www.' => ''));
    	$res  = $this->serpcompetitororganicsearch_model->getcompetitorOrganicSearchByDomain($domain);
    	if($res){
    		$res = json_decode($res['result'],true);
    		$return['status'] = 'success';
    		$return['data'] = $res;
    	} else {
    		$return['status'] = 'error';
    		$return['data'] = null;
    	}
    	echo json_encode($return);die;
    	
    }

    public function get_domain_info()
    {
    	$domain = strtr($this->session->userdata('domainUrl'), array('http://' => '', 'https://' => '', 'www.' => ''));
    	$res  = $this->serpdomain_model->getDomainInfoByDomain($domain);
    	if (!$res || strtotime("+7 day", strtotime($res['created'])) < time()) {
    	    if ($domain) {
    	    	$token = $this->ci->config->config['serpstat']['token'];
    	    	sleep(1);
    	        $url    = 'http://api.serpstat.com/v3/domain_info?query=' . $domain . '&se=g_uk&token=' . $token;
    	        $result = json_decode(file_get_contents($url), true);
    	        
    	        if (isset($result['status_msg']) && $result['status_msg'] == 'OK') {
    	            $this->db->flush_cache();
    	            $array             = array();
    	            $array['domain']   = $domain;
    	            $array['visible'] = $result['result']['visible'];
    	            $array['keywords'] = $result['result']['keywords']; 
    	            $array['traff'] = $result['result']['traff'];
    	            $array['visible_dynamic'] = $result['result']['visible_dynamic'];
    	            $array['keywords_dynamic'] = $result['result']['keywords_dynamic'];
    	            $array['traff_dynamic'] = $result['result']['traff_dynamic'];
    	            $array['date'] = $result['result']['date'];
    	            $array['prev_date'] = $result['result']['prev_date'];
    	            $array['new_keywords'] = $result['result']['new_keywords'];
    	            $array['out_keywords'] = $result['result']['out_keywords'];
    	            $array['rised_keywords'] = $result['result']['rised_keywords'];
    	            $array['down_keywords'] = $result['result']['down_keywords'];
    	            $array['ad_keywords'] = $result['result']['ad_keywords'];
    	            $array['ads'] = $result['result']['result']['ads'];
        	        $array['created']  = date('Y-m-d H:i:s');
    	            $array['modified'] = date('Y-m-d H:i:s');
    	            $this->db->insert('serp_domain_info',$array);
    	        }
    	       $res = $this->serpdomain_model->getDomainInfoByDomain($domain); 
    	       if ($res) {
    	       		$return['status'] = true;
    	       		$return['data'] = $res;
    	       } else {
    	       		$return['status'] = false;
    	       		$return['data'] = null;
    	       }
    	       
    	    }
    	} else {
    		 $return['status'] = true;
    		 $return['data'] = $res;
    	}
    	echo json_encode($return);
    }

    public function getDomainTrend()
    {
    	$domain = strtr($this->session->userdata('domainUrl'), array('http://' => '', 'https://' => '', 'www.' => ''));
    	$res  = $this->serpdomainhistory_model->getSerpDomainHistoryByDomain($domain);
    	if (!$res || strtotime("+7 day", strtotime($res[0]['created'])) < time()) {
    	    if ($domain) {
    	    	$token = $this->ci->config->config['serpstat']['token'];
    	    	sleep(1);
    	        $url    = 'http://api.serpstat.com/v3/domain_history?query=' . $domain . '&se=g_uk&token=' . $token;
    	        $result = json_decode(file_get_contents($url), true);

    	        if (isset($result['status_msg']) && $result['status_msg'] == 'OK') {
					$this->db->flush_cache();    	        	
    	        	$this->db->query("delete from serp_domain_history where domain='".$domain."'");

    	            $this->db->flush_cache();
    	            foreach($result['result'] as $res){
    	            	$array             = array();
    	            	$array['domain']   = $domain;
    	            	$array['visible_static'] = $res['visible_static'];
    	            	$array['date'] = $res['date'];
    	            	$array['ads'] = $res['ads'];
    	            	$array['new_keywords'] = $res['new_keywords'];
    	            	$array['rised_keywords'] = $res['rised_keywords'];
    	            	$array['down_keywords'] = $res['down_keywords'];
    	            	$array['visible'] = $res['visible'];
    	            	$array['ad_keywords'] = $res['ad_keywords'];
    	            	$array['traff'] = $res['traff'];
    	            	$array['out_keywords'] = $res['out_keywords'];
    	            	$array['keywords'] = $res['keywords'];
    	            	$array['created']  = date('Y-m-d H:i:s');
    	            	$array['modified'] = date('Y-m-d H:i:s');
    	            	
    	            	$this->db->insert('serp_domain_history',$array);	
    	            	
    	            }
    	            
    	        }
    	       $res = $this->serpdomainhistory_model->getSerpDomainHistoryByDomain($domain); 
    	       if ($res) {
    	       		$return['status'] = true;
    	       		$return['data'] = $res;
    	       } else {
    	       		$return['status'] = false;
    	       		$return['data'] = null;
    	       }
    	       
    	    }
    	} else {
    		 $return['status'] = true;
    		 $return['data'] = $res;
    	}
    	echo json_encode($return);

    }

    public function adkeywords()
    {
    	    	$domain = strtr($this->session->userdata('domainUrl'), array('http://' => '', 'https://' => '', 'www.' => ''));
    	    	$res  = $this->serpaddkeywords_model->getAdKeywordsByDomain($domain);
    	    	if (!$res || strtotime("+7 day", strtotime($res[0]['created'])) < time()) {
    	    	    if ($domain) {
    	    	    	$token = $this->ci->config->config['serpstat']['token'];
    	    	    	sleep(1);
    	    	        $url    = 'http://api.serpstat.com/v3/ad_keywords?query=' . $domain . '&se=g_uk&token=' . $token;
    	    	        $result = json_decode(file_get_contents($url), true);

    	    	        if (isset($result['status_msg']) && $result['status_msg'] == 'OK') {
    						$this->db->flush_cache();    	        	
    	    	        	$this->db->query("delete from serp_ad_keywords where domain='".$domain."'");
    	    	            $this->db->flush_cache();
    	    	            foreach($result['result'] as $res){
    	    	            	$array             = array();
    	    	            	$array['domain']   = $domain;
    	    	            	$array['region_queries_count'] = $res['region_queries_count'];
    	    	            	$array['region_queries_count_wide'] = $res['region_queries_count_wide'];
    	    	            	$array['keyword'] = $res['keyword'];
    	    	            	$array['title'] = $res['title'];
    	    	            	$array['url'] = $res['url'];
    	    	            	$array['text'] = $res['text'];
    	    	            	$array['found_results'] = $res['found_results'];
    	    	            	$array['url_crc'] = $res['url_crc'];
    	    	            	$array['crc'] = $res['crc'];
    	    	            	$array['cost'] = $res['cost'];
    	    	            	$array['concurrency'] = $res['concurrency'];
    	    	            	$array['position'] = $res['position'];
    	    	            	$array['keyword_id'] = $res['keyword_id'];
    	    	            	$array['subdomain'] = $res['subdomain'];
    	    	            	$array['created']  = date('Y-m-d H:i:s');
    	    	            	$array['modified'] = date('Y-m-d H:i:s');
    	    	            	$this->db->insert('serp_ad_keywords',$array);	
    	    	            }
    	    	        }
    	    	       $res = $this->serpaddkeywords_model->getAdKeywordsByDomain($domain); 
    	    	       if ($res) {
    	    	       		$this->load->view(get_template_directory() . '/ads_keywords', array('data'=>$res));	
    	    	       } else {
    	    	       		$this->load->view(get_template_directory() . '/ads_keywords');	
    	    	       }
    	    	    }
    	    	} else {
    	    		 $this->load->view(get_template_directory() . '/ads_keywords', array('data'=>$res));	
    	    	}
    }

}
