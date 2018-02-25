<?php

/**
 * CIMembership
 *
 * @package        Modules
 * @author        1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license        http://opensource.org/licenses/MIT    MIT License
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Analyze_model extends CI_Model
{

    private $table_name = 'analyze';

    public function __construct()
    {
//        parent::__construct();
        //        $ci =& get_instance();
        //        $this->table_name            = $ci->config->item('db_table_prefix').$this->table_name;
        //
        $this->domainTable             = 'domains';
        $this->serpTable               = 'serp';
        $this->serpHistory             = 'serp_history';
        $this->searchEngineTable       = 'search_engine';
        $this->keywordTable            = 'keywords_master';
        $this->localSearchKeywordTable = 'domain_local_keyword_search';

        $this->load->library('phpass');
        $this->salt = 'X9WX^YvmY!5]\LnD';

    }

    /**
     * Insert Analyzed Website Report Id
     *
     * @param    array
     * @param    bool
     * @return    array
     */
    public function getSerpResult($domain_id, $search_engine_id = '') {
        $this->db->flush_cache();
        if ( '' === $search_engine_id ) {
            $query = $this->db->query( "SELECT * FROM search_engine WHERE domain_id='" . $domain_id . "' LIMIT 1" );
            $res = $query ? $query->row_array() : false;
            if( ! $res ){ return false; }
            $search_engine_id = $res['id'];
        }
        $this->db->flush_cache();
        $query = "SELECT * FROM serp s JOIN keywords_master km ON km.id=s.keyword_id WHERE s.domain_id='" . $domain_id . "' AND s.search_engine_id='" . $search_engine_id . "' ORDER BY s.keyword_id ASC ";
        $query = $this->db->query( $query );
        return $query ? $query->result_array() : false;
    }

    public function insertLocalSearchTown($town, $domainId, $userId)
    {
        //delete if localsearch town exist
        $this->db->flush_cache();
        $this->db->where('domain_id', $domainId);
        $this->db->delete($this->localSearchKeywordTable);

        $this->db->flush_cache();
        $data['domain_id']    = $domainId;
        $data['user_id']      = $userId;
        $data['town']         = $town;
        $data['created_date'] = date('Y-m-d H:i:s');
        $this->db->insert($this->localSearchKeywordTable, $data);
        return $this->db->insert_id();

    }

    public function getLocalSearchTownsByDomainId($domainId)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->localSearchKeywordTable);
        $this->db->where('domain_id', $domainId);
        $query  = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function insertEngine($data)
    {
        //check if search engine exist
        $this->db->select('*');
        $this->db->from($this->searchEngineTable);
        $this->db->where('domain_id', $data['domain_id']);
        $this->db->where('name', $data['name']);
        $this->db->limit("1");
        $query = $this->db->get();
        $exist = $query->row_array();
        if (!$exist) {
            $this->db->flush_cache();
            $this->db->insert($this->searchEngineTable, $data);
            return $this->db->insert_id();
        } else {
            return $exist['id'];
        }

    }

    public function insertkeyword($data, $totalSearchengines = 1)
    {
        $this->db->flush_cache();
        //check if the keyword already exist or not
        $this->db->select('count(*) as total');
        $this->db->from($this->keywordTable);
        $this->db->where('user_id', $data['user_id']);
        $this->db->where('domain_id', $data['domain_id']);

        $this->db->where('name', $data['name']);
        $this->db->limit(1);
        $query = $this->db->get();
        $count = $query->row_array();
        if ($count['total'] < $totalSearchengines) {
            $this->db->insert($this->keywordTable, $data);
            return $this->db->insert_id();
        } else {
            return false;
        }

    }

    public function insert_analyze_data($data)
    {
        $this->db->flush_cache();
        $data['created'] = date('Y-m-d H:i:s');
        if ($this->db->insert('analyze', $data)) {
            $analyze_id = $this->db->insert_id();
            $this->db->where('id', $analyze_id);
            $query = $this->db->get('analyze');
            if ($query->num_rows() == 1) {
                return $query->row();
            } else {
                return null;
            }
        }
        return null;
    }

    public function update_analyze_data($analyze_id, $data)
    {
        $this->db->flush_cache();
        $data['created'] = date('Y-m-d H:i:s');
        $this->db->where('id', $analyze_id);
        $this->db->update('analyze', $data);
    }

    public function checkDomainReport($url)
    {
        $this->db->flush_cache();
        $this->db->where('website_url', $url);
        $query = $this->db->get('analyze');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function update_domains($array, $domainId = '') {

        $this->db->flush_cache();
        
        if ( $domainId ) {
            $this->db->where( 'id', $domainId );
            $this->db->update( 'domains', $array );
            return $domainId;
        }
        else {

            $array['created'] = date('Y-m-d H:i:s');

            //        $this->db->where('userid', $array['userid']);
            //        $query = $this->db->get('domains');
            //        if ($query->num_rows() == 1) {
            //            $data = $query->row();
            //
            //            $domain_settings_id = $data->id;
            //            $update['monitorOnPageIssues'] = $array['monitorOnPageIssues'];
            //            $update['connectToGoogle'] = $array['connectToGoogle'];
            //            $update['domain_name'] = $array['domain_name'];
            //            $update['ga_account'] = $array['ga_account'];
            //            $this->db->where('id', $domain_settings_id);
            //            $this->db->update('domains', $update);
            //            return true;
            //        } else {

            if ( $this->db->insert( 'domains', $array ) ) {

                $last_id = $this->db->insert_id();
                
                // Inserting in user domain table.
                $this->db->flush_cache();
                
                $this->db->insert( 'user_domain', array( 
                    'user_id' => $this->ci_auth->get_user_id(),
                    'domain_id' => $last_id,
                    'created' => date('Y-m-d H:i:s')
                ));

                return $last_id;
            }
        }

        // }
        return null;
    }

    public function getDomain($domainId)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->domainTable);
        $this->db->where('id', $domainId);
        $this->db->limit("1");
        $query  = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getUserOAuthDetails($id)
    {
        $this->db->flush_cache();
        $this->db->where('user_id', $id);
        $query = $this->db->get('user_auth');
        if ( 0 < $query->num_rows() ) {
            return $query->row();
        } else {
            return null;
        }
    }

    public function getDomainDetails($id)
    {
		$this->db->flush_cache();
        $this->db->join("user_domain AS ud", "ud.domain_id=d.id");
        $this->db->where('ud.user_id', $id);
        $this->db->select('d.*');
        $query = $this->db->get('domains as d');
        if ( 0 < $query->num_rows() ) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function getDomainByUserIdAndDomainId($user_id, $domain_id) {

        /*$this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->domainTable . " as d");
        $this->db->join("user_domain AS ud", "ud.domain_id=d.id");
        $this->db->where('d.id=', $domainId);
        $this->db->where('ud.user_id=', $userId);
        $this->db->limit('1');
        $query = $this->db->get();*/

        $query = $this->db->query( "SELECT * FROM domains as d JOIN user_domain as ud  WHERE ( d.id=". $domain_id . " AND d.userid=" . $user_id . " ) OR ( ud.domain_id=d.id AND ud.user_id=" . $user_id . " AND d.id=". $domain_id . " ) LIMIT 1" );

        return $query->row_array();
    }

    // add project
    public function add_project($data)
    {
        $this->db->flush_cache();
        $this->db->select('domain_name');
        $this->db->where('domain_name', $data['domain_name']);
        $query = $this->db->get('uptime');
        if ($query->num_rows() > 0) {
            return 'This site is already added';
        } else {
            $this->db->insert('uptime', $data);
            return $this->db->insert_id();
        }
    }

    //get upmon
    public function get_upmon($user_id)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id =', $user_id);
        $query = $this->db->get();
        return ($query->result());
    }

    public function getAllUptimeMonitors()
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('uptime');
        $query = $this->db->get();
        return $query->result_array();
    }

    // save save_upmon
    public function save_upmon($user_id, $save_upmon)
    {
        $this->db->flush_cache();
        $this->db->where('id =', $user_id);
        $this->db->update('users', $save_upmon);
    }

    public function app($id, $data)
    {
        $this->db->flush_cache();
        $data['created'] = date('Y-m-d H:i:s');
        $this->db->insert('uptime', $data);

        return 'Domain added successfully';
    }

    //get users
    public function getusersinfo($user_id)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id =', $user_id);
        $query = $this->db->get();
        return ($query->result());
    }

    public function getuserprofile($userid)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('user_profiles');
        $this->db->where('user_id =', $userid);
        $this->db->limit("1");
        $query = $this->db->get();
        return ($query->row_array());

    }

    public function insertHistory($data)
    {
        //checking for duplicacy
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('serp_history');
        $this->db->where('search_engine_id =', $data['search_engine_id']);
        $this->db->where('keyword_id =', $data['keyword_id']);
        $this->db->where('date(compare_date) =', $data['compare_date']);
        $this->db->limit("1");
        $query = $this->db->get();
        $row   = $query->row_array();
        if (!$row) {
            $this->db->flush_cache();
            $query = $this->db->insert($this->serpHistory, $data);
            return $query;
        } else {
            return true;
        }

    }

    public function insertdata($data)
    {
        $this->db->flush_cache();
        $query = $this->db->insert($this->serpTable, $data);
        return $query;
    }

    public function updateSerp($data, $id)
    {
        $this->db->flush_cache();
        $this->db->where('id', $id);
        $query = $this->db->update($this->serpTable, $data);
        return $query;
    }

    public function getlastSerp($getfields = '', $match_values = '', $condition = '', $compare_type = '', $count = '', $num = '', $offset = '', $orderby = '')
    {
        $this->db->flush_cache();
        $fields = $getfields ? implode(',', $getfields) : '';
        $sql    = 'SELECT ';

        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM ' . $this->serpTable;
        $where = '';

        if ($match_values) {
            $keys         = array_keys($match_values);
            $compare_type = $compare_type ? $compare_type : 'like';
            if ($condition != '') {
                $and_or = $condition;
            } else {
                $and_or = ($compare_type == 'like') ? ' OR ' : ' AND ';
            }

            $where = 'WHERE ';
            switch ($compare_type) {
                case 'like':
                    $where .= $keys[0] . ' ' . $compare_type . '"%' . $match_values[$keys[0]] . '%" ';
                    break;

                case '=':
                default:
                    $where .= $keys[0] . ' ' . $compare_type . '"' . $match_values[$keys[0]] . '" ';
                    break;
            }
            $match_values = array_slice($match_values, 1);

            foreach ($match_values as $key => $value) {
                $where .= $and_or . ' ' . $key . ' ';
                switch ($compare_type) {
                    case 'like':
                        $where .= $compare_type . '"%' . $value . '%"';
                        break;

                    case '=':
                    default:
                        $where .= $compare_type . '"' . $value . '"';
                        break;
                }
            }
        }
        $orderby = ($orderby != '') ? ' order by id desc ' : '';
        if ($offset == "" && $num == "") {
            $sql .= ' ' . $where . $orderby;
        } elseif ($offset == "") {
            $sql .= ' ' . $where . $orderby . ' ' . 'limit ' . $num;
        } else {
            $sql .= ' ' . $where . $orderby . ' ' . 'limit ' . $offset . ',' . $num;
        }

        $query = ($count) ? 'SELECT count(*) FROM ' . $this->serpTable . ' ' . $where . $orderby : $sql;
        $query = $this->db->query($query);
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    public function getlastHistory($getfields = '', $match_values = '', $condition = '', $compare_type = '', $count = '', $num = '', $offset = '', $orderby = '')
    {
        $this->db->flush_cache();
        $fields = $getfields ? implode(',', $getfields) : '';
        $sql    = 'SELECT ';

        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM ' . $this->serpHistory;
        $where = '';

        if ($match_values) {
            $keys         = array_keys($match_values);
            $compare_type = $compare_type ? $compare_type : 'like';
            if ($condition != '') {
                $and_or = $condition;
            } else {
                $and_or = ($compare_type == 'like') ? ' OR ' : ' AND ';
            }

            $where = 'WHERE ';
            switch ($compare_type) {
                case 'like':
                    $where .= $keys[0] . ' ' . $compare_type . '"%' . $match_values[$keys[0]] . '%" ';
                    break;

                case '=':
                default:
                    $where .= $keys[0] . ' ' . $compare_type . '"' . $match_values[$keys[0]] . '" ';
                    break;
            }
            $match_values = array_slice($match_values, 1);

            foreach ($match_values as $key => $value) {
                $where .= $and_or . ' ' . $key . ' ';
                switch ($compare_type) {
                    case 'like':
                        $where .= $compare_type . '"%' . $value . '%"';
                        break;

                    case '=':
                    default:
                        $where .= $compare_type . '"' . $value . '"';
                        break;
                }
            }
        }
        $orderby = ($orderby != '') ? ' order by id desc ' : '';
        if ($offset == "" && $num == "") {
            $sql .= ' ' . $where . $orderby;
        } elseif ($offset == "") {
            $sql .= ' ' . $where . $orderby . ' ' . 'limit ' . $num;
        } else {
            $sql .= ' ' . $where . $orderby . ' ' . 'limit ' . $offset . ',' . $num;
        }

        $query = ($count) ? 'SELECT count(*) FROM ' . $this->serpHistory . ' ' . $where . $orderby : $sql;
        $query = $this->db->query($query);
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    public function gt_domain_gtmet()
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('domains');
        $query = $this->db->get();
        return $query->result();
    }

    public function update_gtmet_domain($data, $url)
    {
        $this->db->flush_cache();
        $data['created'] = date("Y-m-d h:i:s");
        $this->db->select('*');
        $this->db->from('gtmetrix');
        $this->db->where('url =', $url);
        $query  = $this->db->get();
        $result = $query->result();
        if ($result) {
            $this->db->where('url =', $url);
            $this->db->update('gtmetrix', $data);
            return 'Updated';
        } else {
            $data['url'] = $url;
            $this->db->insert('gtmetrix', $data);
            return 'Inserted';
        }
    }

    public function get_gtme($url)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('gtmetrix');
        $this->db->where('url=', $url);
        $query = $this->db->get();
        if ($query) {
            return $query->row();
        } else {
            return false;
        }

    }

    public function getkeywordByDomainId($domainId)
    {
        $this->db->flush_cache();
        $this->db->select('distinct(name) as name,domain_id');
        $this->db->from($this->keywordTable);
        $this->db->where('domain_id=', $domainId);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getKeywordPositionStats($user_id, $domain_id)
    {
        $top['top5']  = $this->getKeywordPosition($user_id, $domain_id, 0, 5);
        $top['top10'] = $this->getKeywordPosition($user_id, $domain_id, 0, 10);
        $top['top20'] = $this->getKeywordPosition($user_id, $domain_id, 0, 20);
        $top['top50'] = $this->getKeywordPosition($user_id, $domain_id, 0, 50);
        return $top;
    }

    public function getKeywordPositionComparitiveStats($user_id, $domain_id, $search_engine_id = '')
    {
        $top['top1_latest']      = $this->getKeywordPosition($user_id, $domain_id, 0, 1, $search_engine_id);
        $top['top3_latest']      = $this->getKeywordPosition($user_id, $domain_id, 0, 3, $search_engine_id);
        $top['top5_latest']      = $this->getKeywordPosition($user_id, $domain_id, 0, 5, $search_engine_id);
        $top['top10_latest']     = $this->getKeywordPosition($user_id, $domain_id, 0, 10, $search_engine_id);
        $top['top20_latest']     = $this->getKeywordPosition($user_id, $domain_id, 0, 20, $search_engine_id);
        $top['top30_latest']     = $this->getKeywordPosition($user_id, $domain_id, 0, 30, $search_engine_id);
        $top['ranked_latest']    = $this->getKeywordPosition($user_id, $domain_id, 0, null, $search_engine_id);
        $top['notranked_latest'] = $this->getKeywordPosition($user_id, $domain_id, null, 0, $search_engine_id);

        // $top['top1_history']      = $this->getKeywordPositionHistory($user_id, $domain_id, 0, 1);
        // $top['top3_history']      = $this->getKeywordPositionHistory($user_id, $domain_id, 0, 3);
        // $top['top5_history']      = $this->getKeywordPositionHistory($user_id, $domain_id, 0, 5);
        // $top['top10_history']     = $this->getKeywordPositionHistory($user_id, $domain_id, 0, 10);
        // $top['top20_history']     = $this->getKeywordPositionHistory($user_id, $domain_id, 0, 20);
        // $top['top30_history']     = $this->getKeywordPositionHistory($user_id, $domain_id, 0, 30);
        // $top['ranked_history']    = $this->getKeywordPositionHistory($user_id, $domain_id, 0);
        // $top['notranked_history'] = $this->getKeywordPositionHistory($user_id, $domain_id, null, 0);

        return $top;

    }

    public function getKeywordPositionHistory($user_id, $domain_id, $gt = null, $lt = null)
    {
        $this->db->flush_cache();
        $querydate = "select max(date(compare_date)) as compare_date from " . $this->serpHistory . " where user_id='" . $user_id . "' and domain_id='" . $domain_id . "'
        and date(compare_date)< (select max(date(updated_date))  from " . $this->serpHistory . " where user_id='" . $user_id . "' and domain_id='" . $domain_id . "')";
        $querydate = $this->db->query($querydate);
        $date      = $querydate->row_array();

        //getting the keyword positions
        $this->db->select("count((keyword)) as avg_count");
        $this->db->from('serp_history');
        $this->db->where('user_id', $user_id);

        if (isset($gt)) {
            $this->db->where('position >', $gt);
        }
        if (isset($lt)) {
            $this->db->where('position <=', $lt);
        }
        $this->db->where('domain_id =', $domain_id);
        $this->db->where('date(compare_date)=', $date['compare_date']);
        $this->db->limit("1");
        $query = $this->db->get();
        $count = $query->row();

        return $count->avg_count;

    }

    public function getKeywordPosition($user_id, $domain_id, $gt = null, $lt = null, $search_engine_id = '')
    {
        $this->db->flush_cache();
        // $querydate = "select date(updated_date) as updated_date from " . $this->serpTable . " where user_id='" . $user_id . "' and domain_id='" . $domain_id . "' order by updated_date desc limit 1";
        // $querydate = $this->db->query($querydate);
        // $date = $querydate->row_array();

        //getting the keyword positions
        $this->db->select("count(keyword) as avg_count");
        $this->db->from('serp as s');
        $this->db->join("user_domain AS ud", "ud.domain_id=s.domain_id");
        
        if ($search_engine_id != '') {
           $this->db->where('s.search_engine_id', $search_engine_id);     
        }
        

        $this->db->where('ud.user_id', $user_id);
        if (isset($gt)) {
            $this->db->where('position >', $gt);
        }
        if (isset($lt)) {
            $this->db->where('position <=', $lt);
        }
        $this->db->where('s.domain_id =', $domain_id);
        $this->db->order_by('updated_date', 'DESC');
        $this->db->limit("1");

        $query = $this->db->get();
        $count = $query->row();

        return $count->avg_count;
    }

    public function getAveragePosition($user_id, $domain_id)
    {
        $this->db->flush_cache();
        $query = "SELECT avg(position) as average_position from " . $this->serpTable . " as s
                 join user_domain ud on ud.domain_id=s.domain_id
                 WHERE ud.user_id=" . $user_id . "
                 AND s.domain_id=" . $domain_id . "
                 AND position!=0
                 AND
                 date(updated_date)
                 =(SELECT DATE(updated_date) from " . $this->serpTable . " s
                 join user_domain ud on ud.domain_id=s.domain_id
                 WHERE ud.user_id=" . $user_id . " AND s.domain_id=" . $domain_id . "
                 ORDER BY date(updated_date) DESC LIMIT 1)";
        $query = $this->db->query($query);
        $avg   = $query->row_array();
        return $avg;
    }

    public function getTotalKeywords($user_id, $domain_id)
    {
        $this->db->flush_cache();
        $query = "SELECT count((name)) AS total_keyword_search
        FROM " . $this->keywordTable . " as k
        join user_domain ud on ud.domain_id=k.domain_id
        WHERE ud.user_id = " . $user_id . "
          AND k.domain_id = " . $domain_id . "
          ";
        $query = $this->db->query($query);
        return $query->row_array();
    }

    public function getKeywordChangeFromWeeks($user_id, $domain_id)
    {
        $this->db->flush_cache();
        //getting latest position
        $query1 = "select (keyword) as keyword,position,keyword_id,date(updated_date)  from serp s
        join user_domain ud on ud.domain_id=s.domain_id
          WHERE ud.user_id = " . $user_id . " and s.domain_id=" . $domain_id;
        $query1 = $this->db->query($query1);

        $latestposition = $query1->result_array();

        //getting lastweek position

        $query2           = "select keyword,position,keyword_id,date(compare_date)  from " . $this->serpHistory . " sh  join user_domain ud on ud.domain_id=sh.domain_id WHERE ud.user_id =  " . $user_id . " and sh.domain_id=" . $domain_id . " and  date(compare_date)=(select max(date(compare_date)) as compare_date from " . $this->serpHistory . " sh join user_domain ud on ud.domain_id=sh.domain_id where ud.user_id='" . $user_id . "' and sh.domain_id='" . $domain_id . "' and date(compare_date)< (select max(date(updated_date))  from " . $this->serpHistory . " sh join user_domain ud on ud.domain_id=sh.domain_id where ud.user_id='" . $user_id . "' and sh.domain_id='" . $domain_id . "') )";
        $query2           = $this->db->query($query2);
        $lastweekposition = $query2->result_array();
        $positivecount    = 0;
        $negativecount    = 0;
        $nochangecount    = 0;

        foreach ($latestposition as $key => $latest) {

            foreach ($lastweekposition as $key1 => $lastweek) {

                if ($latest['keyword'] == $lastweek['keyword'] && $latest['keyword_id'] == $lastweek['keyword_id']) {
                    if ($latest['position'] < $lastweek['position']) {
                        $positivecount += 1;
                    } elseif ($latest['position'] > $lastweek['position']) {
                        $negativecount += 1;
                    } else {
                        $nochangecount += 1;
                    }
                }
            }
        }
        $notMatched = count($latestposition) - ($positivecount + $negativecount + $nochangecount);
        if ($notMatched > 0) {
            $nochangecount += $notMatched;
        }
        $count['positive'] = $positivecount;
        $count['negative'] = $negativecount;
        $count['nochange'] = $nochangecount;
        return $count;
    }

    public function update_gtmet_count($data, $user)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('gtmetrix_api_count');
        $this->db->where('domain_id =', $data['domain_id']);
        $query  = $this->db->get();
        $result = $query->result();
        if ($result) {
            if ($user == 1) {
                $data['count'] = $result[0]->count + 1;
            }

            $this->db->where('domain_id =', $data['domain_id']);
            $this->db->update('gtmetrix_api_count', $data);
            return 'success';
        } else {

            $this->db->insert('gtmetrix_api_count', $data);
            return 'success';
        }
    }

    public function check_gtmet_count($user_id)
    {
        $this->db->flush_cache();
        $this->db->select('sum(count) as count,credits');
        $this->db->from('gtmetrix_api_count as gmc');
        $this->db->join("user_domain AS ud", "ud.domain_id=gmc.domain_id");
        $this->db->where('ud.user_id =', $user_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllDomains()
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->domainTable);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getKeywordsByDomainIdAndUserId($domainId, $userId)
    {
        $this->db->flush_cache();
        $this->db->select('name as keyword');
        $this->db->from($this->keywordTable . " as k");
        $this->db->join("user_domain AS ud", "ud.domain_id=k.domain_id");
        $this->db->where('k.domain_id=', $domainId);
        $this->db->where('ud.user_id=', $userId);
        $this->db->group_by('name');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllKeywordsForImmediateSerpCronByDomainId($domainId)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->keywordTable);
        $this->db->where('next_serp_run=', null);
        $this->db->where('domain_id=', $domainId);
        $this->db->order_by("name", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getAllKeywordsForDelaySerpCronByDomainId($domainId)
    {
        $this->db->flush_cache();
        $currentDateTime = date('Y-m-d H:i:s');
        $this->db->select('*');
        $this->db->from($this->keywordTable);
        $this->db->where("((next_serp_run) <= '".$currentDateTime."' or next_serp_run is null)");
        $this->db->where('domain_id=', $domainId);
        $query = $this->db->get();
        if($query){
            return $query->result_array();    
        }else{
            return false;
        }
        
    }

    public function getSearchEngineByDomainId($domainId)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->searchEngineTable);
        $this->db->where("domain_id=", $domainId);
        $query = $this->db->get();
        if($query){
            return $query->result_array();    
        }else{
            return false;
        }
    }
    public function updateKeywordNextSerpRun($keywordId, $nextRun)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->keywordTable);
        $this->db->where("id=", $keywordId);
        $this->db->update($this->keywordTable, array('next_serp_run' => $nextRun));
    }

    public function getSearchedDomain($domain)
    {
        $this->db->flush_cache();
        $this->db->like('domain_name', $domain);
        $query = $this->db->get('domains');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function exportKeywordCsv($domainId, $userId)
    {
        $this->db->flush_cache();
        $query = "SELECT serp.local as `search engine`, serp.keyword,serp.position as `current position`,
        sh.position as `lastweek position`
        FROM " . $this->serpTable . "
        LEFT JOIN " . $this->serpHistory . " AS `sh` ON `sh`.`keyword_id`=`serp`.`keyword_id`
        AND (date(sh.updated_date)=
               (select max(date(updated_date)) as updated_date from " . $this->serpHistory . " sh join user_domain ud on ud.domain_id=sh.domain_id where ud.user_id='" . $userId . "' and sh.domain_id='" . $domainId . "'
        and date(updated_date)< (select max(date(updated_date))  from " . $this->serpHistory . " sh join user_domain ud on ud.domain_id=sh.domain_id where ud.user_id='" . $userId . "' and sh.domain_id='" . $domainId . "'))) join user_domain ud on ud.domain_id=serp.domain_id
        WHERE `serp`.`domain_id` = '" . $domainId . "'
          AND `ud`.`user_id` = '" . $userId . "'
        ORDER BY `serp`.`keyword` ASC";

        $query  = $this->db->query($query);
        $result = $query->result_array();
        if ($result) {
            foreach ($result as $res) {
                $d[$res['keyword']]['Keyword']                                                       = $res['keyword'];
                $d[$res['keyword']]['Google ' . strtoupper($res['search engine']) . ' CURRENT WEEK'] = ($res['current position'] == 0) ? '-' : $res['current position'];
                $d[$res['keyword']]['Google ' . strtoupper($res['search engine']) . ' LAST WEEK']    = ($res['lastweek position'] == 0) ? '-' : $res['lastweek position'];
            }
            $count = 0;
            foreach ($d as $data) {
                $return[$count] = $data;
                $count++;
            }

            return $return;
        } else {
            return false;
        }

    }
    public function deleteKeyword($domainId, $keyword, $userId)
    {

        $this->db->flush_cache();
        $this->db->where('domain_id=', $domainId);
        $this->db->where('user_id=', $userId);
        $this->db->where('name=', $keyword);
        $this->db->delete($this->keywordTable);

        //deleting from serp table
        $this->db->where('domain_id=', $domainId);
        $this->db->where('user_id=', $userId);
        $this->db->where('keyword=', $keyword);
        $this->db->delete($this->serpTable);

        //deleting from serp history table
        $this->db->where('domain_id=', $domainId);
        $this->db->where('user_id=', $userId);
        $this->db->where('keyword=', $keyword);
        $this->db->delete($this->serpHistory);

    }

    public function listallsites($user_id)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from($this->domainTable . " as d");
        $this->db->join("user_domain AS ud", "ud.domain_id=d.id");
        $this->db->where('ud.user_id =', $user_id);
        $query = $this->db->get();
        return ($query->result());
    }
    public function getClientSites($user_id)
    {
        $this->db->flush_cache();
        $this->db->select("s.*, asu.*");
        $this->db->from("sites_assigned AS asu");
        $this->db->join("domains AS s", "asu.site_id=s.id");
        $this->db->where("asu.client_user_id", $user_id);
        $query = $this->db->get();
        $this->db->last_query();
        return $query->result();
    }

    public function get_domain_uptime( $domain_id, $days = null ){

        $sql_errors = "SELECT SUM(count) AS count 
                       FROM uptime_stats_daily_errors_summ 
                       JOIN domains ON domains.id = uptime_stats_daily_errors_summ.domain_id
                       WHERE domain_id=" . $domain_id . "
                       AND domains.monitorUptime = 1";

        $sql_no_errors = "SELECT SUM(count) AS count
                          FROM uptime_stats_daily_summ
                          JOIN domains ON domains.id = uptime_stats_daily_errors_summ.domain_id
                          WHERE domain_id=" . $domain_id . "
                          AND domains.monitorUptime = 1";

        if( $days ){
            $date = date('Y-m-d', strtotime('-' . $days . ' day', time()));
            $sql_errors .= " AND date >= '" . $date . "'";
            $sql_no_errors .= " AND date >= '" . $date . "'";
        }

        $this->db->flush_cache();

        $query_errors = $this->db->query( $sql_errors );
        $query_no_errors = $this->db->query( $sql_no_errors );

        $result_errors = $query_errors ? $query_errors->row_array() : array();
        $result_no_errors = $query_no_errors ? $query_no_errors->row_array() : array();

        $errors_num = $result_errors && isset( $result_errors['count'] ) && $result_errors['count'] ? (int) $result_errors['count'] : 0;
        $no_errors_num = $result_no_errors && isset( $result_no_errors['count'] ) && $result_no_errors['count'] ? (int) $result_no_errors['count'] : 0;

        return array(
            'errors' => $errors_num,
            'no_errors_num' => $no_errors_num,
            'total_num' => $errors_num + $no_errors_num
        );
    }

    public function uptimePercentageInDays($domainId, $days) {

        $domain_uptime = $this->get_domain_uptime($domainId, $days, true);        
        return 0 < $domain_uptime['total_num'] ? ( $domain_uptime['no_errors_num'] * 100 ) / $domain_uptime['total_num'] : 0;

        /*$query = "select count(*) as noErrorCount from uptime_stats
            WHERE domain_id='" . $domainId . "'
            AND completed_on>='" . $date . "'
            AND (error='0' OR error='') limit 1
        ";
        $query        = $this->db->query($query);
        $noErrorCount = $query->row_array();

        $query = "select count(*) as totalCount from uptime_stats
            WHERE domain_id='" . $domainId . "'
            AND completed_on>='" . $date . "' limit 1
        ";

        $query      = $this->db->query($query);
        $totalCount = $query->row_array();

        if ($totalCount['totalCount'] != 0) {
            $totalPercentage = ($noErrorCount['noErrorCount'] * 100) / $totalCount['totalCount'];
            return $totalPercentage;
        }
        else {
            return 0;
        }*/

    }

    public function uptimePercentageInDaysOverall( $days, $userId ) {

        $date = date( 'Y-m-d H:i:s', strtotime( '-' . $days . ' day', time() ) );

        $errors_sql = "SELECT SUM(count) AS num 
                FROM uptime_stats_daily_errors_summ AS A
                JOIN domains AS B ON B.id = A.domain_id
                WHERE date >= '" . $date . "'
                AND B.monitorUptime = 1
                AND B.userid = '" . $userId . "'";

        $no_errors_sql = "SELECT SUM(A.count) AS num 
                        FROM uptime_stats_daily_summ AS A 
                        JOIN domains AS B ON B.id = A.domain_id
                        WHERE A.date >= '" . $date . "'
                        AND B.monitorUptime = 1
                        AND B.userid = '" . $userId . "'";

        $this->db->flush_cache();

        $errors_query = $this->db->query($errors_sql);
        $errors_query = $errors_query->row_array();

        $no_errors_query = $this->db->query($no_errors_sql);
        $no_errors_query = $no_errors_query->row_array();

        $errors_num = isset( $errors_query['num'] ) ? (int) $errors_query['num'] : 0;
        $no_errors_num = isset( $no_errors_query['num'] ) ? (int) $no_errors_query['num'] : 0;

        $total = $errors_num + $no_errors_num;

        return 0 === $total ? null : ( 100 * $no_errors_num ) / $total;
    }

    public function totalUptimeByDomainId( $domainId ) {

        $this->db->flush_cache();
        $query1 = "SELECT completed_on
                   FROM uptime_stats
                   WHERE ( error != '0' AND error != '' )
                   AND domain_id='" . $domainId . "'
                   ORDER BY completed_on desc
                   LIMIT 1";

        $query1        = $this->db->query($query1);
        $lastErrorDate = $query1->row_array();
        if (!$lastErrorDate['completed_on']) {
            $lastErrorDate['completed_on'] = '';
        }

        $query = "SELECT count(*) AS noErrorCount FROM uptime_stats
            WHERE domain_id='" . $domainId . "'
            AND error=0
            AND completed_on>=('" . $lastErrorDate['completed_on'] . "')
        ";
        $query = $this->db->query($query);
        if ($query) {
            $noErrorCount = $query->row_array();

            $totalUpHours = $noErrorCount['noErrorCount'];
            return $totalUpHours;
        } else {
            return 0;
        }

    }
    
    public function totalUptimeByUserId($userId) {

        $this->db->flush_cache();
        $query1 = "(select completed_on from uptime_stats us join
         domains d on d.id=us.domain_id join user_domain ud on ud.domain_id=d.id
         where (error!='0' and error!='') and ud.user_id='" . $userId . "' order by us.completed_on desc limit 1)";
        $query1        = $this->db->query($query1);
        $lastErrorDate = $query1->row_array();
        if (!$lastErrorDate['completed_on']) {
            $lastErrorDate['completed_on'] = '';
        }

        $query = "select count(*) as noErrorCount from uptime_stats us
            join domains d on d.id=us.domain_id join user_domain ud on ud.domain_id=d.id
            WHERE ud.user_id='" . $userId . "'
            AND (error='0' or error='')
            AND us.completed_on>=('" . $lastErrorDate['completed_on'] . "') limit 1
        ";
        $query = $this->db->query($query);
        if ($query) {
            $noErrorCount = $query->row_array();

            $totalUpHours = $noErrorCount['noErrorCount'];
            return $totalUpHours;
        } else {
            return 0;
        }

    }

    public function uptimeDayStatsByDomainId($domainId) {

        $date  = date('Y-m-d H:i:s', strtotime('-1 day', time()));

        $sql = "SELECT @@sql_mode AS sql_mode";
        $sql_mode = $this->db->query($sql);
        $sql_mode = $sql_mode->result_array();
        $sql_mode = isset( $sql_mode[0] ) && isset( $sql_mode[0]['sql_mode'] ) ? $sql_mode[0]['sql_mode'] : '';

        $temp_change_sql_mode = ! ( false === strrpos(  $sql_mode, 'ONLY_FULL_GROUP_BY' ) );

        if( $temp_change_sql_mode ){
            // @note: With sql_mode "ONLY_FULL_GROUP_BY" is not possible to grou results by "timekey", in the following query.
            $sql = "SET SESSION sql_mode = '" . str_replace( 'ONLY_FULL_GROUP_BY', "", str_replace( 'ONLY_FULL_GROUP_BY,', "", $sql_mode ) ) . "'";
            $this->db->query($sql);
        }

        $sql = "SELECT *,
                DATE_FORMAT(completed_on,'%h:%i') AS completed_on_time,
                ROUND( UNIX_TIMESTAMP(completed_on) / ( 30 * 60 ) ) AS timekey
                FROM uptime_stats
                WHERE domain_id=" . $domainId . "
                AND completed_on>='" . $date . "'
                AND (error='0' or error='')
                GROUP BY timekey";

        $this->db->flush_cache();
        $result = $this->db->query($sql);
        $result = $result ? $result->result_array() : false;

        if( $temp_change_sql_mode ){
            $sql = "SET SESSION sql_mode = '" . $sql_mode . "'";
            $this->db->query($sql);
        }
        
        return $result;
    }

    public function uptimeDayStatsByuserId($userId)
    {
        $this->db->flush_cache();

        $date = date('Y-m-d', strtotime('-1 day', time()));

        $query = "select avg(us.load_time)/1000 as load_time,date_format(us.completed_on,'%h:%i') as completed_on_time
        from uptime_stats us join domains d on d.id=us.domain_id join user_domain ud on ud.domain_id=d.id where ud.user_id='" . $userId . "' and us.completed_on>='" . $date . "' and (us.error='0' or us.error='') group by hour(us.completed_on)
        ";
        $query = $this->db->query($query);
        if ($query) {
            return $noErrorCount = $query->result_array();
        } else {
            return false;
        }

    }

    public function getUptimeByDomainId($domainId)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('uptime');
        $this->db->where('domain_id=', $domainId);
        $this->db->limit("1");
        $query = $this->db->get();

        return $query->row_array();
    }

    public function getAvgUptimeByDomainId($domainId)
    {
        $query = "select avg(load_time) as avg_load_time from uptime_stats where domain_id='" . $domainId . "' and date(completed_on)='" . date('Y-m-d') . "'";
        $query = $this->db->query($query);
        if ($query) {
            return $query->row_array();
        } else {
            $array['load_time'] = 0;
            return $array;
        }

    }

    public function stopUptime($user, $domainId)
    {
        $this->db->flush_cache();
        //domainDetails
        $domainDetails = $this->getDomain($domainId);
        //deleting from piwik
        $apiUrl     = $this->ci->config->config['piwik']['api_url'];
        $token_auth = $this->ci->config->config['piwik']['auth_token'];
        //getting the site detail
        $url = $apiUrl;
        $url .= "?module=API&method=SitesManager.deleteSite";
        $url .= "&idSite=" . urlencode($domainDetails[0]->piwik_site_id);
        $url .= "&format=PHP";
        $url .= "&token_auth=$token_auth";
        $fetched = file_get_contents($url);

        //uptime details
        $uptimeDetails = $this->getUptimeByDomainId($domainId);
        if ($uptimeDetails['up_time_id']) {
            $data_string = '';
            $ch          = curl_init('http://api.upmonitor.io/api/v1/sites/' . $uptimeDetails['up_time_id'] . '/');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Token ' . $user[0]->uptime_token,
                'Content-Type: application/json',
            )
            );
            $result = curl_exec($ch);
            $result = json_decode($result, true);
        }
    }
    public function deleteDomain($user, $domainId)
    {
        $this->db->flush_cache();
        //domainDetails
        $domainDetails = $this->getDomain($domainId);
        //deleting from piwik
        $apiUrl     = $this->ci->config->config['piwik']['api_url'];
        $token_auth = $this->ci->config->config['piwik']['auth_token'];
        //getting the site detail
        $url = $apiUrl;
        $url .= "?module=API&method=SitesManager.deleteSite";
        $url .= "&idSite=" . urlencode($domainDetails[0]->piwik_site_id);
        $url .= "&format=PHP";
        $url .= "&token_auth=$token_auth";
        $fetched = file_get_contents($url);

        //uptime details
        $uptimeDetails = $this->getUptimeByDomainId($domainId);

        if ($uptimeDetails['up_time_id']) {
            // $vars = array('username' => $user[0]->username,
            //     'password'               => sha1($user[0]->username.$this->salt));

            // $ch = curl_init('http://api.upmonitor.io/api/v1/authenticate/');
            // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // $result = curl_exec($ch);
            // $token  = json_decode($result, true);
            // $token  = $token['token'];

            $data_string = '';

            $ch = curl_init('http://api.upmonitor.io/api/v1/sites/' . $uptimeDetails['up_time_id'] . '/');

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Token ' . $user[0]->uptime_token,
                'Content-Type: application/json',
            )
            );
            $result = curl_exec($ch);
            $result = json_decode($result, true);

        }

        //delete from domain user table
        $this->db->where('domain_id=', $domainId);
        $this->db->delete('user_domain');

        //delete from domain groups table
        $this->db->where('domain_id=', $domainId);
        $this->db->delete('domain_groups');

        //delete from domain table
        $this->db->where('domain_id=', $domainId);
        $this->db->delete($this->domainTable);
        //delete from keywords table
        $this->db->where('domain_id=', $domainId);
        $this->db->delete($this->keywordTable);
        //delete from search engine table
        $this->db->where('domain_id=', $domainId);
        $this->db->delete($this->searchEngineTable);
        //delete from serp table
        $this->db->where('domain_id=', $domainId);
        $this->db->delete($this->serpTable);
        //delete from serp history table
        $this->db->where('domain_id=', $domainId);
        $this->db->delete($this->serpHistory);
        //delete from uptime table
        $this->db->where('domain_id=', $domainId);
        $this->db->delete('uptime');
        //delete from uptime stats
        $this->db->where('domain_id=', $domainId);
        $this->db->delete('uptime_stats');

        $test        = new gtmetrix("jody@creativehand.co.uk", "4ab0bab28d572f134fd39e47330fa778");
        $url_to_test = $domainDetails[0]->domain_name;
        $testid      = $test->test(array(
            'url' => $url_to_test,
        ));
        $test->delete();

        //delete from gtmetrix
        $this->db->where('url=', $domainDetails[0]->domain_name);
        $this->db->delete('gtmetrix');

        //delete from gtmetrix api count
        $this->db->where('domain_id=', $domainId);
        $this->db->delete('gtmetrix_api_count');
    }

    public function getTotalDomainsByUserId( $userId ) {
        $this->db->flush_cache();
        $this->db->select('COUNT(*) AS totalDomain');
        $this->db->from('domains');
        $this->db->where('userid=', $userId);
        $this->db->limit("1");
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getSingleDomainByUserId($userId)
    {
        $this->db->flush_cache();
        $this->db->select('d.id');
        $this->db->from('domains as d');
        $this->db->join("user_domain AS ud", "ud.domain_id=d.id");
        $this->db->where('ud.user_id=', $userId);
        $this->db->limit("1");
        $query = $this->db->get();
        return $query->row_array();

    }

    public function getAllDomainsByUserId($userId, $domain = '', $limit = '')
    {
        $this->db->flush_cache();
        $this->db->select('d.*,ud.domain_id,ud.user_id');
        $this->db->from('domains as d');
        $this->db->join("user_domain AS ud", "ud.domain_id=d.id");
        $this->db->where('ud.user_id=', $userId);
        if ($domain != '') {
            $this->db->where("d.domain_name like '%" . $domain . "%'");
        }
        if ($limit != '') {
            $this->db->limit(7);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getDomainsByUserId($userId, $domain = '', $limit = '', $page='')
    {
        $this->db->flush_cache();
        $this->db->select('count(*) as total_rows');
        $this->db->from('domains as d');
        $this->db->join("user_domain AS ud", "ud.domain_id=d.id");
        $this->db->where('ud.user_id=', $userId);
        if ($domain != '' && $domain != 'null') {
            $this->db->where("d.domain_name like '%" . $domain . "%'");
        }
        $query = $this->db->get();
        if($query) {
            $total = $query->row_array();
            $total = $total['total_rows'];
        }else{
            $total = 0;
        }


        $this->db->flush_cache();
        $this->db->select('d.*,ud.domain_id,ud.user_id');
        $this->db->from('domains as d');
        $this->db->join("user_domain AS ud", "ud.domain_id=d.id");
        $this->db->where('ud.user_id=', $userId);
        if ($domain != '' && $domain != 'null') {
            $this->db->where("d.domain_name like '%" . $domain . "%'");
        }
        if ($limit != '') {

            $offset = ($page - 1) * $limit;
            $this->db->limit($limit,$offset);
        } else {
            $this->db->limit(7);
        }

        $query = $this->db->get();
        if ($query) {
            $result = $query->result_array();
            $return = array();
            foreach ($result as $key => $res) {
                $return[$key]                  = $res;
                // $temp                          = $this->getAvgUptimeByDomainId($res['id']);
                $return[$key]['avg_load_time'] = 1;//$temp['avg_load_time'];
                $return[$key]['currentpage'] = $page;
                $return[$key]['offset'] = $offset;
                $return[$key]['total_rows'] = $total;
            }
            return $return;
        } else {
            return false;
        }

    }

    public function getAllDomainsBySubuserId($userId)
    {
        $this->db->flush_cache();
        $this->db->select('ud.domain_id');
        $this->db->from('domains as d');
        $this->db->join("user_domain AS ud", "ud.domain_id=d.id");
        $this->db->where('ud.user_id=', $userId);
        $query  = $this->db->get();
        $result = $query->result_array();
        if ($result) {
            $return = array();
            foreach ($result as $res) {
                $return[] = $res['domain_id'];
            }
            return $return;
        } else {
            return array();
        }
    }

    public function getUptimeDashboardStatByUserId($userId)
    {
        //getting all domains
        $allDomains = $this->getAllDomainsByUserId($userId);
        $dayArray   = array(1, 2, 3);

        foreach ($allDomains as $key => $domains) {
            //getting current status
            $query = "select error from uptime_stats
                WHERE domain_id='" . $domains['id'] . "'
                order by id desc
                ";
            $query         = $this->db->query($query);
            $currentStatus = $query->row_array();
            if ($currentStatus['error'] == 0) {
                $currentStatus = 'up';
            } else {
                $currentStatus = 'down';
            }

            foreach ($dayArray as $key1 => $day) {
                $date  = date('Y-m-d H:i:s', strtotime('-' . $day . ' day', time()));
                $query = "select count(*) as noErrorCount from uptime_stats
                WHERE domain_id='" . $domains['id'] . "'
                AND completed_on>='" . $date . "'
                AND error=0
            ";
                $query        = $this->db->query($query);
                $noErrorCount = $query->row_array();

                $query = "select count(*) as totalCount,avg(load_time) as avg_load_time from uptime_stats
                WHERE domain_id='" . $domains['id'] . "'
                AND completed_on>='" . $date . "'
            ";
                $query      = $this->db->query($query);
                $totalCount = $query->row_array();

                if ($totalCount['totalCount'] != 0) {
                    $result[$key][$key1]['percentage']     = round((($noErrorCount['noErrorCount'] * 100) / $totalCount['totalCount']), 2);
                    $result[$key][$key1]['avg_load_time']  = ceil($totalCount['avg_load_time'] / 1000);
                    $result[$key][$key1]['domain_name']    = strtr($domains['domain_name'], array('http://' => '', 'https://' => '', 'www.' => '', "/" => ''));
                    $result[$key][$key1]['current_status'] = $currentStatus;

                } else {
                    $result[$key][$key1]['percentage']     = 0;
                    $result[$key][$key1]['avg_load_time']  = 0;
                    $result[$key][$key1]['domain_name']    = strtr($domains['domain_name'], array('http://' => '', 'https://' => '', 'www.' => '', "/" => ''));
                    $result[$key][$key1]['current_status'] = $currentStatus;
                }
            }

        }
        return $result;

    }

    public function getLastDomainByUserId($userId)
    {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from('domains as d');
        $this->db->join("user_domain AS ud", "ud.domain_id=d.id");
        $this->db->where('ud.user_id=', $userId);
        $this->db->order_by('d.id', 'desc');
        $this->db->limit("1");
        $query = $this->db->get();
        return $query->row_array();

    }

    public function getTotalUpdomainsByUserId($userId) {
        $this->db->flush_cache();
        $this->db->select('COUNT(*) AS totalUpdomains');
        $this->db->from('domains');
        $this->db->where('userid=', $userId);
        $this->db->where('monitorUptime=', '1');
        $this->db->where('server_status=', 'UP');
        $this->db->limit("1");
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getTotalDownDomainsByUserId($userId) {
        $this->db->flush_cache();
        $this->db->select('COUNT(*) AS totalDownDomain');
        $this->db->from('domains');
        $this->db->where('userid=', $userId);
        $this->db->where('monitorUptime=', '1');
        $this->db->where('server_status=', 'DOWN');
        $this->db->limit("1");
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getTotalNoStatsDomainsByUserId($userId) {
        $this->db->flush_cache();
        $this->db->select('COUNT(*) AS totalNoStatDomain');
        $this->db->from('domains');
        $this->db->where('userid=', $userId);
        $this->db->where('server_status IS NULL OR monitorUptime = 0');
        $this->db->limit("1");
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getUserInfoByDomainId($domainId)
    {
        $this->db->flush_cache();
        $this->db->select("u.id,u.email,u.username,d.domain_name");
        $this->db->from("users u");
        $this->db->join("domains as d", "d.userid=u.id");
        $this->db->join("user_domain AS ud", "ud.user_id=u.id");
        $this->db->where("d.id=", $domainId);
        $this->db->limit("1");
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getallUsers()
    {
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->from("users u");
        $query = $this->db->get();
        return $query->result_array();

    }

    public function getNoStatsDomain($userId)
    {
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->from("domains as d");
        $this->db->join("user_domain AS ud", "ud.domain_id=d.id");
        $this->db->where("server_status IS NULL or d.monitorUptime = 0");
        $this->db->where("ud.user_id=", $userId);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getNoStatsDomainDailyCron($updomains, $downdomains, $userId)
    {
        $alldomainsids = array();
        if ($updomains) {
            foreach ($updomains as $updomain) {
                $alldomainsids[] = $updomain['domain_id'];
            }
        }
        if ($downdomains) {
            foreach ($downdomains as $downdomain) {
                $alldomainsids[] = $downdomain['domain_id'];
            }
        }
        if ($alldomainsids) {
            $alldomainsids = implode(",", $alldomainsids);
            $query         = "select domain_name from domains d join user_domain ud on ud.domain_id=d.id where ud.user_id='" . $userId . "' and d.id not in (" . $alldomainsids . ")";
            $query         = $this->db->query($query);
            $domains       = $query->result_array();
            return $domains;
        } else {
            return false;
        }

    }

    public function getGtMetrixByDomainId($domain)
    {
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->from("gtmetrix");
        $this->db->where("url=", $domain);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();

    }

    public function addSubUser($data, $userId)
    {
        $this->db->flush_cache();
        $insertdata['username']  = $data['username'];
        $insertdata['password']  = $this->ci_auth->hash_password($data['password']);
        $insertdata['email']     = $data['email'];
        $insertdata['gid']       = 5;
        $insertdata['parent_id'] = $userId;
        $insertdata['activated'] = 1;
        $insertdata['created']   = date('Y-m-d H:i:s');
        $this->db->insert('users', $insertdata);
        $id = $this->db->insert_id();

        //inserting in profile table
        $this->db->flush_cache();
        $insertprofiledata['first_name'] = $data['first_name'];
        $insertprofiledata['last_name']  = $data['last_name'];
        $insertprofiledata['user_id']    = $id;
        $this->db->insert('user_profiles', $insertprofiledata);
        return $id;
    }


    public function createUser($data)
    {
        $this->db->flush_cache();
        $insertdata['username']  = $data['username'];
        $insertdata['password']  = $this->ci_auth->hash_password($data['password']);
        $insertdata['email']     = $data['email'];
        $insertdata['gid']       = 5;
        $insertdata['activated'] = 1;
        $insertdata['created']   = date('Y-m-d H:i:s');
        $this->db->insert('users', $insertdata);
        $id = $this->db->insert_id();

        //inserting in profile table
        $array               = array();
        $array['first_name'] = $data['first_name'];
        $array['last_name']  = $data['last_name'];
        $array['phone']      = $data['phone'];
        $array['country']    = $data['country'];
        $array['user_id']    = $id;
        $this->db->flush_cache();
        $this->db->insert('user_profiles', $array);

        $this->db->flush_cache();
        $array            = array();
        $array['api_key'] = $id . $this->randomString(15);
        $this->db->where('id', $id);
        $this->db->update('users', $array);

        return $array['api_key'];

    }

    public function randomString($length = 7)
    {
        $str        = "";
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max        = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function deleteUser($subuserid, $userid)
    {
        $this->db->flush_cache();
        $this->db->where('parent_id=', $userid);
        $this->db->where('id=', $subuserid);
        $this->db->delete('users');

        $this->db->flush_cache();
        $this->db->where('user_id=', $subuserid);
        $this->db->delete('user_profiles');

    }

    public function getsubuserBySubuserId($subuserId, $userId)
    {
        $this->db->flush_cache();
        $this->db->select('u.*, up.first_name, up.last_name, up.phone, up.company, up.country, up.address');
        $this->db->from('users as u');
        $this->db->join("user_profiles AS up", "up.user_id=u.id");
        $this->db->where('parent_id =', $userId);
        $this->db->where('u.id =', $subuserId);
        $query = $this->db->get();
        return ($query->row_array());
    }
    public function updatesubuser($data, $userId)
    {
        $this->db->flush_cache();
        $insertdata['modified'] = date('Y-m-d H:i:s');
        $insertdata['username'] = $data['username'];
        $insertdata['email']    = $data['email'];

        if ($data['password']) {
            $insertdata['password'] = $this->ci->phpass->hash_password($data['password']);
        }

        $this->db->where('id', $data['subuserid']);
        $this->db->where('parent_id', $userId);
        $this->db->update('users', $insertdata);

        $this->db->flush_cache();
        $insertprofiledata['first_name'] = $data['first_name'];
        $insertprofiledata['last_name']  = $data['last_name'];
        $this->db->where('user_id', $data['subuserid']);
        $this->db->update('user_profiles', $insertprofiledata);
    }

    public function getallsubuserbyuserId($userId)
    {
        $this->db->flush_cache();
        $this->db->select('u.*, up.first_name, up.last_name, up.phone, up.company, up.country, up.address');
        $this->db->from('users as u');
        $this->db->join("user_profiles AS up", "up.user_id=u.id");
        $this->db->where('parent_id =', $userId);
        $query = $this->db->get();
        if ($query) {
            return ($query->result_array());
        } else {
            return false;
        }

    }
    
    /////////////////////////
    // public function addclient($data, $userId)
    // {
    //     $this->db->flush_cache();
    //     $insertdata['parent_id']  = $userId;
    //     $insertdata['firstname']  = $data['first_name'];
    //     $insertdata['lastname']  = $data['last_name'];
    //     $insertdata['companyname']  = $data['company_name'];
    //     $insertdata['phonenumber']  = $data['phone_number'];
    //     $insertdata['email']  = $data['email'];
    //     $insertdata['creditlimit']  = $data['credit_limit'];
    //     $insertdata['terms']  = $data['terms'];

    //     $this->db->insert('clients', $insertdata);
    //     $id = $this->db->insert_id();
    //     return $id;
    // }

    // public function updateclient($data, $userId)
    // {
    //     $this->db->flush_cache();
    //     $insertdata['parent_id']  = $userId;
    //     $insertdata['firstname']  = $data['first_name'];
    //     $insertdata['lastname']  = $data['last_name'];
    //     $insertdata['companyname']  = $data['company_name'];
    //     $insertdata['phonenumber']  = $data['phone_number'];
    //     $insertdata['email']  = $data['email'];
    //     $insertdata['creditlimit']  = $data['credit_limit'];
    //     $insertdata['terms']  = $data['terms'];

    //     $this->db->where('id', $data['clientid']); //hidden input type in edit form
    //     //$this->db->where('parent_id', $userId);
    //     $this->db->update('clients', $insertdata);
    // }
    
    // public function getallclientbyclientId($clientid)
    // {
    //     $this->db->flush_cache();
    //     $this->db->select('c.*');
    //     $this->db->from('clients as c');
    //     //$this->db->where('id =', $clientid);
    //     $query = $this->db->get();
    //     if ($query) {
    //         return ($query->result_array());
    //     } else {
    //         return false;
    //     }

    // }

    // public function getclientbyclientId($clientid)
    // {
    //     $this->db->flush_cache();
    //     $this->db->select('c.*');
    //     $this->db->from('clients as c');
    //     $this->db->where('id =', $clientid);
    //     $query = $this->db->get();
    //     if ($query) {
    //         return ($query->result_array());
    //     } else {
    //         return false;
    //     }

    // }

    //  public function deleteClient($clientid, $userid)
    // {
    //     $this->db->flush_cache();
    //     $this->db->where('parent_id=', $userid);
    //     $this->db->where('id=', $clientid);
    //     $this->db->delete('clients');
    // }

    public function assignDomainToSubuser($domainId, $subusers, $mainUser)
    {
        $this->db->flush_cache();
        $this->db->where('domain_id', $domainId);
        $this->db->where('user_id!=', $mainUser);
        $this->db->delete('user_domain');

        if( ! empty($subusers) ){
            foreach ($subusers as $subuser) {
                $this->db->flush_cache();
                $data              = array();
                $data['user_id']   = $subuser;
                $data['domain_id'] = $domainId;
                $data['created']   = date('Y-m-d H:i:s');
				$this->db->insert('user_domain', $data);
            }
        }
    }

    public function assigndomains($domains, $subuserId, $groupId)
    {
        if ($subuserId && $subuserId != 0) {
            //deleting the domains
            $this->db->flush_cache();
            $this->db->where('user_id', $subuserId);
            $this->db->delete('user_domain');
            foreach ($domains as $domain) {
                $this->db->flush_cache();
                $data['user_id']   = $subuserId;
                $data['domain_id'] = $domain;
                $data['created']   = date('Y-m-d H:i:s');
                $this->db->insert('user_domain', $data);
            }
        }

        if ($groupId && $groupId != 0) {
            //deleting the domains
            $this->db->flush_cache();
            $this->db->where('group_id', $groupId);
            $this->db->delete('domain_groups');
            foreach ($domains as $domain) {
                $this->db->flush_cache();
                $data['group_id']  = $groupId;
                $data['domain_id'] = $domain;
                $data['created']   = date('Y-m-d H:i:s');
                $this->db->insert('domain_groups', $data);
            }
        }

    }

    public function serpoverview($domainId, $searchEngine = 'en-uk', $keywordsArray = array(), $date = '') {

        $keywordsArray = ! is_array( $keywordsArray ) ? array( $keywordsArray ) : $keywordsArray;

        $currentseries = array();
        $historyseries = array();
        
        $searchEngine_alt_name = $searchEngine;

        switch( $searchEngine ){
            case 'en-uk':
                $searchEngine_alt_name = 'en-uk';
                break;
            case 'en-uk-mobile':
                $searchEngine_alt_name = 'en-uk-mobile';
                break;
            case 'en-us':
                $searchEngine_alt_name = 'en-us';
                break;
            case 'en-us-mobile':
                $searchEngine_alt_name = 'en-us-mobile';
                break;
            case 'en-ca':
                $searchEngine_alt_name = 'en-ca';
                break;
            case 'en-ca-mobile':
                $searchEngine_alt_name = 'en-ca-mobile';
                break;
            case 'en-au':
                $searchEngine_alt_name = 'en-au';
                break;
            case 'en-au-mobile':
                $searchEngine_alt_name = 'en-au-mobile';
                break;
        }

        $query1 = "SELECT keyword, position, date(created_date) AS date FROM serp_history WHERE 1=1";
        $query2 = "SELECT keyword, position, date(created_date) AS date FROM serp WHERE 1=1";

        if ( ! empty( $keywordsArray ) ) {
            $keywords = implode( ",", $keywordsArray );
            $query1 .= " AND keyword IN ('" . $keywords . "')";
            $query2 .= " AND keyword IN ('" . $keywords . "')";
        }

        $query1 .= " AND domain_id='" . $domainId . "' AND local IN ( '" . $searchEngine . "', '" . $searchEngine_alt_name . "' ) ";
        $query2 .= " AND domain_id='" . $domainId . "' AND search_engine IN ( '" . $searchEngine . "', '" . $searchEngine_alt_name . "' ) ";
        
        if ( '' !== $date ) {

            $currentDate = date('Y-m-d');

            switch( $date ){
                case '7days':
                case '7':
                case 7:
                    $date = date( 'Y-m-d', strtotime("-7 days") );
                    $query1 .= " AND created_date >= '" . $date . "' AND created_date<='" . $currentDate . "'";
                    break;
                case '15days':
                case '15':
                case 15:
                    $date = date( 'Y-m-d', strtotime("-15 days") );
                    $query1 .= " AND created_date >= '" . $date . "' AND created_date<='" . $currentDate . "'";
                    break;
                case '1month':
                case '30':
                case 30:
                    $date = date( 'Y-m-d', strtotime("-30 days") );
                    $query1 .= " AND created_date >= '" . $date . "' AND created_date<='" . $currentDate . "'";
                    break;
            }
        }

        $query1 .= " ORDER BY keyword, date ASC";

        $query1 = $this->db->query( $query1 );
        $query2 = $this->db->query( $query2 );

        $result1 = $query1 ? $query1->result_array() : false;
        $result2 = $query2 ? $query2->result_array() : false;

        $keywordname = array();
        $count = -1;

        if ( $result1 ) {

            foreach ( $result1 as $res ) {

               if( ! isset( $historyseries[ $res['keyword'] ] ) ){
                    $historyseries[$res['keyword']] = array(
                        'name' => $res['keyword'],
                        'date' => array(),
                        'data' => array(),
                    );
                }

                $historyseries[ $res['keyword'] ]['date'][] = $res['date'];
                $historyseries[ $res['keyword'] ]['data'][] = (int) $res['position'];
            }
        }

        if ( $result2 ) {

            foreach ( $result2 as $res ) {

                if( ! isset( $currentseries[ $res['keyword'] ] ) ){
                    $currentseries[ $res['keyword'] ] = array(
                        'name' => $res['keyword'],
                        'date' => array(),
                        'data' => array(),
                    );
                }

                $currentseries[ $res['keyword'] ]['date'][] = $res['date'];
                $currentseries[ $res['keyword'] ]['data'][] = (int) $res['position'];
            }
        }

        $returnseries = array();
        $returncategory = array();

        if ( ! empty( $historyseries ) && ! empty( $currentseries ) ) {

            $returnseries = $currentseries;

            foreach ( $historyseries as $hs ) {
                
                foreach ( $currentseries as $cs ) {

                    if ( strtolower($hs['name']) === strtolower($cs['name']) ) {
                        $key = $cs['name'];
                        $returnseries[$key] = array(
                            'title'=> $cs['name'],
                            'data' => $hs['data'],
                            'date' => $hs['date']
                        );
                        $returnseries[$key]['data'][] = $cs['data'][0];
                        $returnseries[$key]['date'][] = $cs['date'][0];
                    }
                }
            }
        }
        elseif ( ! empty( $currentseries ) ) {
            $returnseries = $currentseries;
        }
        elseif ( ! empty( $historyseries ) ) {  // @todo: Does it need?
            $returnseries = $historyseries;
        }

        if( ! empty( $returnseries ) ) {
            $i=0;
            foreach ($returnseries as $rs) {
                $returncategory[] = $i;
                $i++;
            }
        }

        return array(
            'series' => $returnseries,
            'category' => $returncategory
        );
    }

    public function checkKeywordExistByDomainId( $domainId ) {
        $this->db->flush_cache();
        $this->db->select('id');
        $this->db->from("keywords_master km");
        $this->db->where('km.domain_id', $domainId);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query && $query->row_array() ? true : false;
    }

    public function getSubusersByDomainIdAndParentId($domainId, $parentId)
    {				
		$return = array();
		
        $this->db->flush_cache();
        $this->db->select('u.id');
        $this->db->from("domains d");
        $this->db->join("user_domain AS ud", "ud.domain_id=d.id");
        $this->db->join("users AS u", "u.id=ud.user_id");
        $this->db->where('d.id', $domainId);
        $this->db->where('u.parent_id', $parentId);
        $query = $this->db->get();
        if ($query) {
            $result = $query->result_array();
            foreach ($result as $res) {
                $return[] = $res['id'];
            }
        }
		return $return;
    }

    public function getUserByApiKey($apikey)
    {

        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from("users ");
        $this->db->where('api_key', $apikey);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }

    }

    public function getSerpByKeywordIdAndSearchEngine($keywordId, $searchId) {
        $this->db->flush_cache();
        $this->db->select('*');
        $this->db->from("serp ");
        $this->db->where('keyword_id', $keywordId);
        $this->db->where('search_engine_id', $searchId);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }          
    }


    public function update_woo_customers($array) {

        $this->db->flush_cache();
        $query = $this->db->get_where('woo_customers', array('domain_id' => $array['domain_id'], 'customer_id'=>$array['customer_id']));
        if ( ! $query->result() ) {
            $this->db->insert( 'woo_customers', $array );
            $last_id = $this->db->insert_id();
            return $last_id;
        }
    }

    public function update_woo_orders($array) {

        $this->db->flush_cache();
        $query = $this->db->get_where('woo_orders', array('domain_id' => $array['domain_id'], 'order_id'=>$array['order_id']));
        if ( ! $query->result() ) {
            $this->db->insert( 'woo_orders', $array );
            $last_id = $this->db->insert_id();
            return $last_id;
        }
    }

    public function update_woo_products($array) {

        $this->db->flush_cache();
        $query = $this->db->get_where('woo_products', array('domain_id' => $array['domain_id'], 'product_id'=>$array['product_id']));
        if ( ! $query->result() ) {
            $this->db->insert( 'woo_products', $array );
            $last_id = $this->db->insert_id();
            return $last_id;
        }
    }

    public function update_woo_sales_report($array) {

        $this->db->flush_cache();
        $query = $this->db->get_where('woo_sales_report', array('domain_id' => $array['domain_id'], 'period'=>$array['period']));
        if ( ! $query->result() ) {
            $this->db->insert( 'woo_sales_report', $array );
            $last_id = $this->db->insert_id();
            return $last_id;
        } else {
            $res = $query->row_array();
            $id = $res['id'];
            $this->db->flush_cache();
            $this->db->where('id', $id);
            $this->db->update('woo_sales_report', $array);
        }
    }

    public function get_woo_current_month_sales($domain_id)
    {
        $query = "select * from woo_sales_report where domain_id='".$domain_id."' and period='month' ";
        $query = $this->db->query( $query );
        return $query ? $query->row_array() : false;

    }


    public function get_woo_last_month_sales($domain_id)
    {
        $query = "select * from woo_sales_report where domain_id='".$domain_id."' and period='last_month' ";
        $query = $this->db->query( $query );
        return $query ? $query->row_array() : false;
    }
}
