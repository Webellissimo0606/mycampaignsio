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

class Uptimestats_model extends CI_Model {

    // private $uptime_stats_cache_time = 86400;    // @note: Keep "fresh" the cache data for one day.
    private $uptime_stats_cache_time = 3600;    // @note: Keep "fresh" the cache data for one hour.

    public function __construct() {
        parent::__construct();
        $this->load->helper('campaigns-io/functions');
    }

    public function update_daily_uptime_stats_by_user( $user_id, $force_update_all = false ){
        
        $sql = "SELECT DISTINCT id FROM domains WHERE monitorUptime = 1 AND userid=" . $user_id . " ORDER BY id DESC";
        
        $query = $this->db->query( $sql );
        
        $result = ! $query ? $query : $query->result_array();
        
        if( $result && ! empty( $result ) ){

            foreach( $result as $k => $v ){

                // Update table "uptime_stats_daily_summ" and "uptime_stats_daily_errors_summ".
                if( $this->update_daily_uptime_stats( $v['id'], $force_update_all ) ){

                    // Update table "uptime_stats_summ".
                    $this->update_summ_uptime_stats( $v['id'] );
                }
            }
        }
    }

    public function update_daily_uptime_stats($domain_id, $force_update_all = true){

        $last_updated = null;
        
        if( ! $force_update_all ){

            $sql = "SELECT modified
                    FROM uptime_stats_summ
                    WHERE domain_id=" . $domain_id . "
                    ORDER BY modified DESC
                    LIMIT 1";

            $query = $this->db->query( $sql );
            $result = ! $query ? $query : $query->result_array();

            $last_updated = isset( $result[0] ) && isset( $result[0]['modified'] ) ? $result[0]['modified'] : $last_updated;

            if( $last_updated ){

                // Check if cache time period passed/expired.
                if( $this->uptime_stats_cache_time > ( time() - strtotime( $last_updated ) ) ){
                    return false;
                }

                $last_updated = date( 'Y-m-d H:i:s', strtotime( $last_updated ) );
            }
        }

        if( $force_update_all || ! $last_updated ){

            $sql = "SELECT completed_on FROM uptime_stats WHERE domain_id=" . $domain_id . " ORDER BY completed_on ASC LIMIT 1";
            $query = $this->db->query( $sql );
            $result = ! $query ? $query : $query->result_array();
            $last_updated = isset( $result[0] ) && isset( $result[0]['completed_on'] ) ? $result[0]['completed_on'] : null;

            if( ! $last_updated ){ return false; }    // There is no record in uptime stats for the domain.
        }

        $sql = "INSERT INTO uptime_stats_daily_summ (domain_id, date, count, avg_load_time, module, modified)
                SELECT 
                    domain_id,
                    DATE_FORMAT(completed_on, '%Y-%m-%d 00:00:00') AS date,
                    COUNT(domain_id) AS count,
                    ROUND(AVG(load_time), 4) AS avg_load_time,
                    module,
                    NOW() AS modified
                FROM uptime_stats
                WHERE domain_id = " . $domain_id . "
                AND completed_on >= '" . date( 'Y-m-d', strtotime( $last_updated ) ) . " 00:00:00'
                AND load_time IS NOT NULL
                GROUP BY date, domain_id, module
                ON DUPLICATE KEY UPDATE domain_id = domain_id, date = date, count = count, avg_load_time = avg_load_time, modified=NOW();";

        $sql_errors = "INSERT INTO uptime_stats_daily_errors_summ (domain_id, date, count, modified)
                        SELECT 
                            domain_id,
                            DATE_FORMAT(completed_on, '%Y-%m-%d 00:00:00') AS date,
                            COUNT(domain_id) AS count,
                            NOW() AS modified
                        FROM uptime_stats
                        WHERE domain_id = " . $domain_id . "
                        AND completed_on >= '" . date( 'Y-m-d', strtotime( $last_updated ) ) . " 00:00:00'
                        AND load_time IS NULL
                        GROUP BY date, domain_id, module
                        ON DUPLICATE KEY UPDATE domain_id = domain_id, date = date, count = count, modified=NOW();";

        $this->db->query( $sql );
        $this->db->query( $sql_errors );

        return true;
    }

    private function update_summ_uptime_stats($domain_id){

        $sql = "SELECT domain_id,
                       COUNT(id) AS days_count,
                       SUM(count) AS metrics_count,
                       SUM(avg_load_time) AS avg_load_time
                FROM uptime_stats_daily_summ
                WHERE domain_id=" . $domain_id;

        $query = $this->db->query( $sql );
        $result = ! $query ? $query : $query->result_array();
        $result = isset( $result[0] ) ? $result[0] : null;

        if( $result && 0 < (int) $result['days_count'] ){
            $summ_avg_load_time = (float) $result['avg_load_time'] / (int) $result['days_count'];
            $sql =  "INSERT INTO uptime_stats_summ(domain_id, count, avg_load_time, modified)
                     VALUES (" . $domain_id . "," . $result['metrics_count'] . "," . $summ_avg_load_time . ", NOW())
                     ON DUPLICATE KEY UPDATE count=" . $result['metrics_count'] . ", avg_load_time=" . $summ_avg_load_time . ", modified=NOW()";
            $this->db->query( $sql );
        }
    }

    public function getTotalUptimesByUserId($userId, $days ) {

        $now = time();
        $date = date( 'Y-m-d', strtotime( '-' . $days . ' day', $now ) );

        $this->update_daily_uptime_stats_by_user( $userId );

        $sql = "SELECT domain_id, SUM(count) AS total_stats
                FROM uptime_stats_daily_summ
                WHERE domain_id IN (
                    SELECT DISTINCT id FROM domains WHERE userid=" . $userId . " AND monitorUptime = 1
                )
                AND date >= '" . $date . " 00:00:00'
                GROUP BY domain_id";

        $errors_sql = "SELECT domain_id, SUM(count) AS total_stats
                        FROM uptime_stats_daily_errors_summ
                        WHERE domain_id IN (
                            SELECT DISTINCT id FROM domains WHERE userid=" . $userId . " AND monitorUptime = 1
                        )
                        AND date >= '" . $date . " 00:00:00'
                        GROUP BY domain_id";

        $query = $this->db->query( $sql );
        $uptime = $query ? $query->result_array() : array();

        $query_errors = $this->db->query( $errors_sql );
        $uptime_errors = $query_errors ? $query_errors->result_array() : array();

        if( empty( $uptime_errors ) ){
            return $uptime;
        }
        else if( empty( $uptime ) ){
            return $uptime_errors;
        }
        else{

            foreach ($uptime_errors as $k => $v) {
                $found = false;
                foreach( $uptime as $a => $b ){
                    if( $b['domain_id'] === $v['domain_id'] ){
                        $uptime[$a]['total_stats'] = (int) $uptime[$a]['total_stats'] + (int) $uptime_errors[$k]['total_stats'];
                        $found = true;
                        break;
                    }
                }
                if( ! $found ){
                    $uptime[] = $v;
                }
            }
        }
        
        return $uptime;
    }

    public function getUptimeByUserId($userId, $days = 30) {

        $this->update_daily_uptime_stats_by_user( $userId );
        
        $date  = date( 'Y-m-d H:i:s', strtotime( '-' . $days . ' day', time() ) );

        $sql = "SELECT 
                    SUM(A.count) AS total_stats, 
                    ROUND( SUM(A.avg_load_time) / COUNT(A.avg_load_time), 4 ) AS avg_load_time,
                    A.module,
                    A.domain_id,
                    B.domain_name
                FROM uptime_stats_daily_summ AS A 
                JOIN domains AS B ON B.id = A.domain_id
                WHERE B.server_status = 'UP'
                AND B.monitorUptime = 1
                AND B.userid = '" . $userId . "'
                AND A.date >= '" . $date . "'
                GROUP BY A.domain_id, A.module";

        $query = $this->db->query($sql);
        $ret = $query ? $query->result_array() : array();

        return $ret;
    }

    public function getDowntimeByUserId($userId, $days = 30) {

        $this->update_daily_uptime_stats_by_user( $userId );

        $date  = date( 'Y-m-d H:i:s', strtotime( '-' . $days . ' day', time() ) );

        $sql = "SELECT 
                    SUM(A.count) AS total_stats, 
                    ROUND( SUM(A.avg_load_time) / COUNT(A.avg_load_time), 4 ) AS avg_load_time, 
                    A.module, 
                    A.domain_id, 
                    B.domain_name
                FROM uptime_stats_daily_summ AS A 
                JOIN domains AS B ON B.id = A.domain_id
                WHERE B.server_status = 'DOWN'
                AND B.monitorUptime = 1
                AND B.userid = '" . $userId . "'
                AND A.date >= '" . $date . "'
                GROUP BY A.domain_id, A.module";

        $query = $this->db->query($sql);
        $ret = $query ? $query->result_array() : array();
        
        return $ret;
    }

    public function uptimePercentageInDays($domainId, $days = 30) {

        $date = date( 'Y-m-d', strtotime( '-' . $days . ' day', time() ) );
        
        $query = "SELECT count(*) AS noErrorCount
                  FROM uptime_stats AS us
                  WHERE us.domain_id = '" . $domainId . "'
                  AND completed_on >= '" . $date . "'
                  AND ( error = '0' OR error = '' )";

        $query = $this->db->query( $query );
        $noErrorCount = $query->row_array();

        $query = "SELECT count(*) AS totalCount
                  FROM uptime_stats AS us
                  WHERE us.domain_id = '" . $domainId . "'
                  AND completed_on >= '" . $date . "'";

        $query = $this->db->query( $query );
        $totalCount = $query->row_array();

        return 0 !== $totalCount['totalCount'] ? round( ( $noErrorCount['noErrorCount'] * 100 ) / $totalCount['totalCount'], 2 ) : 0;
    }

    // NOTE: Doesn't get used anywhere...
    public function downtimePercentageInDays($domainId, $day = 30) {
        
        $date = date( 'Y-m-d', strtotime( '-' . $day . ' day', time() ) );
        
        $query = "SELECT count(*) AS errorCount 
                  FROM uptime_stats AS us 
                  WHERE us.domain_id='" . $domainId . "' 
                  AND completed_on>='" . $date . "' 
                  AND ( error != '0' AND error = '' )";

        $query = $this->db->query( $query );
        $errorCount = $query->row_array();

        $query = "SELECT count(*) AS totalCount
                  FROM uptime_stats AS us
                  WHERE us.domain_id='" . $domainId . "'
                  AND completed_on>='" . $date . "'";

        $query = $this->db->query( $query );
        $totalCount = $query->row_array();

        return 0 !== $totalCount['totalCount'] ? round( ( $errorCount['errorCount'] * 100 ) / $totalCount['totalCount'], 2 ) : 0;
    }

    public function uptimeDayStatsByUserId($userId) {
        $date = date( 'Y-m-d', strtotime( '-1 day', time() ) );
        $query = "SELECT avg( us.load_time ) / 1000 AS load_time, 
                  date_format( us.completed_on,'%h:%i' ) AS completed_on_time
                  FROM uptime_stats us
                  JOIN domain AS d ON d.id = us.domain_id
                  JOIN user_domain ud ON ud.domain_id = us.domain_id
                  WHERE ud.domain_id = '" . $userId . "
                  AND us.completed_on >= '" . $date . "'
                  AND ( us.error = '0' OR us.error = '' )
                  GROUP BY hour( us.completed_on )";
        $query = $this->db->query( $query );
        return $noErrorCount = $query->result_array();
    }

    // NOTE: Doesn't get used anywhere...
    public function getUpDomainsByUserId($userId) {
        $this->db->select('count(*) AS totalUpdomains');
        $this->where( "ud.user_id = ", $userId );
        $this->db->from('domains AS d');
        $this->db->join("user_domain AS ud", "ud.domain_id = d.id");
        $this->db->where("server_status = ", 'UP');
        $this->db->where("monitorUptime = ", '1');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getLatestDownDomainByUserId($userId) {

        $this->db->select("*");
        $this->db->from('uptime_stats');
        $this->db->where("user_id = ", $userId);
        $this->db->where("(error != '0' AND error != '' )");
        $this->db->order_by("completed_on", "desc");

        $this->db->limit(1);
        $query = $this->db->get();     

        $latestdown = $query->row_array();

        return ! $latestdown ? '' : "Domain " . $latestdown['domain_name'] . " on " . date( 'd M Y H:i:s', strtotime( $latestdown['completed_on'] ) );
    }

    // Return oldest modified domain date.
    private function get_user_oldest_modified_domain_avg_load_time($userId){
        $sql  = "SELECT modified
                FROM uptime_stats_summ
                INNER JOIN domains ON domains.id = uptime_stats_summ.domain_id
                WHERE domains.userid = " . $userId . "
                AND domains.monitorUptime = 1
                ORDER BY uptime_stats_summ.modified DESC
                LIMIT 1";
        $results = $this->db->query($sql)->result_array();
        $modified = $results && isset( $results[0] ) && isset( $results[0]['modified'] ) ? $results[0]['modified'] : null;
        return $modified;
    }

    private function update_single_domain_avg_load_time($domain_id){

        $sql = "SELECT count(id) AS count, avg(load_time) AS avg_load_time
                FROM uptime_stats
                WHERE domain_id = " . $domain_id . "
                AND load_time IS NOT NULL";

        $results = $this->db->query($sql)->result_array();
        $results = $results && ! empty( $results ) ? $results[0] : false;

        if( $results ){
            $insert = "INSERT INTO uptime_stats_summ(domain_id, count, avg_load_time, modified)
                       VALUES (" . $domain_id . "," . $results['count'] . "," . $results['avg_load_time'] . ",NOW())
                       ON DUPLICATE KEY UPDATE count=" . $results['count'] . ", avg_load_time=" . $results['avg_load_time'] . ", modified=NOW()";
            $this->db->query($insert);
        }
    }

    public function getFastestDomain($userId) {

        $this->update_daily_uptime_stats_by_user( $userId );

        $sql = "SELECT MIN(avg_load_time) AS fastest
                FROM uptime_stats_summ
                INNER JOIN domains on domains.id = uptime_stats_summ.domain_id
                WHERE domains.userid = " . $userId . "
                AND domains.monitorUptime = 1";

        $res = $this->db->query( $sql )->result_array();

        return $res;
    }

    public function getSlowestDomain($userId){

        $this->update_daily_uptime_stats_by_user( $userId );

        $sql = "SELECT MAX(avg_load_time) AS slowest
                FROM uptime_stats_summ
                INNER JOIN domains on domains.id = uptime_stats_summ.domain_id
                WHERE domains.userid = " . $userId . "
                AND domains.monitorUptime = 1";

        $res = $this->db->query($sql)->result_array();

        return $res;
    }

    public function downtimePercentageInDaysOverall($day, $userId) {
        
        $date = date('Y-m-d', strtotime('-' . $day . ' day', time() ) );
        
        $this->db->flush_cache();

        $this->db->select("count(*) AS errorCount");
        $this->db->from("uptime_stats AS us");
        $this->db->join("domains d", "d.id = us.domain_id");
        $this->db->join("user_domain AS ud", "ud.domain_id = d.id");
        $this->db->where("us.completed_on >= '" . $date . "'");
        $this->db->where("(us.error != '0' AND us.error != '' )");
        $this->db->where("ud.user_id = ", $userId);
        $this->db->where("d.monitorUptime = ", '1');
        $this->db->limit(1);
        $query = $this->db->get();
        $errorCount = $query->row_array();

        $this->db->select("count(*) AS totalCount");
        $this->db->from("uptime_stats AS us");
        $this->db->join("domains AS d", "d.id = us.domain_id");
        $this->db->join("user_domain AS ud", "ud.domain_id = d.id");
        $this->db->where("us.completed_on >= '".$date."'");
        $this->db->where("ud.user_id = ", $userId);
        $this->db->where("d.monitorUptime = ", '1');
        $this->db->limit(1);
        $query = $this->db->get();
        $totalCount = $query->row_array();
        
        $totalCount['totalCount'] = isset( $totalCount['totalCount'] ) ? (int) $totalCount['totalCount'] : 0;
        
        return 0 < $totalCount['totalCount'] ? round( ( $errorCount['errorCount'] * 100 ) / $totalCount['totalCount'], 2 ) : 0;
    }

    public function getAverageResponseByDomainId($domainId) {

        $date = date( 'Y-m-d H:i:s ', strtotime( '-' . $day . ' day', time() ) );

        $this->update_daily_uptime_stats_by_user( $userId );

        $this->db->flush_cache();
        $this->db->select("avg_load_time");
        $this->db->from("uptime_stats_summ");
        $this->db->where("domain_id=", $domainId);
        $this->db->limit(1);

        $query = $this->db->get();

        return $query->row_array();
    }

    public function getGlobalPerformanceByUserId($userId) {

        $this->update_daily_uptime_stats_by_user( $userId );

        $this->db->flush_cache();
        $this->db->select("( sum(avg_load_time) / count( avg_load_time ) ) AS average_perfomance");
        $this->db->from("uptime_stats_summ");
        $this->db->join("domains", "domains.id = uptime_stats_summ.domain_id");
        $this->db->where("domains.userid=", $userId);
        $this->db->where("domains.monitorUptime=", '1');
        $this->db->limit(1);
        
        $query = $this->db->get();

        return $query->row_array();
    }

    public function getTotalUptimesByUserIdInSingleDay($userId, $days = 30) {

        $date = date( 'Y-m-d', strtotime( '-' . $days . ' day', time() ) );
        $query = "SELECT us.domain_id, count( us.domain_id ) AS total_stats 
                    FROM uptime_stats us 
                    JOIN domains d ON d.id = us.domain_id
                    WHERE date( us.completed_on LIKE '" . $date . "%' )
                    AND d.monitorUptime=1
                    AND us.user_id='" . $userId . "'
                    GROUP BY us.domain_id";
        $query = $this->db->query( $query );
        return $query ? $query->result_array() : array();
    }

    public function getUptimeByUserIdWithOutageSingleDay($userId, $days = 30) {

        $uptime = $this->getUptimeByUserId($userId, $days);

        if ($uptime) {
            return false;
        }

        $temp = array();
        
        foreach ( $uptime as $key => $up) {
            $incidents = $this->countIncidentsByDomainId($up['domain_id'], $days);
            $temp[$key] = $up;
            $temp[$key]['outage'] = $incidents['incidents'];
            $temp[$key]['outagetime'] = $incidents['totaloutagetime'];
            $temp[$key]['uptime_percentage'] = round( 100 - ( ( $incidents['totaloutagetime'] * 100 ) / 86400), 5 );
            if($temp[$key]['uptime_percentage'] < 0){
                $temp[$key]['uptime_percentage'] = 0;
            }
        }

        return $temp;
    }

    public function getDowntimeByUserIdWithOutageSingleDay($userId, $days = 30) {

        $downtime = $this->getDowntimeByUserId($userId, $days);

        if ( ! $downtime ) {
            return false;
        }

        $temp = array();

        foreach ($downtime as $key => $up) {
            $incidents = $this->countIncidentsByDomainId( $up['domain_id'], $days );
            $temp[$key] = $up;
            $temp[$key]['outage'] = $incidents['incidents'];
            $temp[$key]['outagetime'] = $incidents['totaloutagetime'];
            $incidents['totaloutagetime'] = $incidents['totaloutagetime'] > 86400 ? 86400 : $incidents['totaloutagetime'];
            $temp[$key]['uptime_percentage'] = round( 100 - ( ( $incidents['totaloutagetime'] * 100 ) / 86400 ), 5 );
            $temp[$key]['uptime_percentage'] = $temp[$key]['uptime_percentage'] < 0 ? 0 : $temp[$key]['uptime_percentage'];
        }

        return $temp;
    }

    public function countIncidentsByDomainId($domainId, $days = 30) {
        
        $date = date( 'Y-m-d', strtotime('-' . $days . ' day', time() ) );
        $currentDate = date( 'Y-m-d', strtotime('-0 day', time() ) );

        $query = "SELECT count(*) AS incidents, ( SUM( TIMESTAMPDIFF( SECOND, COALESCE( downtime, 0 ), COALESCE( uptime, CURRENT_TIMESTAMP ) ) ) ) AS totaloutagetime 
                  FROM uptime_incidents ui 
                  WHERE ( 
                        ui.domain_id = '" . $domainId . "' 
                        AND ui.downtime IS NOT NULL 
                        AND (
                            date( ui.updated_at ) >= '" . $date . "' 
                            AND 
                            date( ui.updated_at ) <= '" . $currentDate . "'
                        ) 
                  ) OR ( 
                        ui.downtime IS NOT NULL AND ui.uptime IS NULL AND ui.domain_id='" . $domainId . "' 
                  )";

        $query = $this->db->query( $query );

        return $query->row_array();
    }

    public function getNostatsDomainByUserId( $userId ) {
        $query = "SELECT * FROM domains WHERE userid = '" . $userId . "' AND ( server_status IS NULL OR monitorUptime = 0 )";
        $query = $this->db->query( $query );
        if ( ! $query ) { return false; }
        $result = $query->result_array();
        return $result ? $result : false;
    }
}
