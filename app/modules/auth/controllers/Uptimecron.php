<?php
/**
 *
 * @package        Modules
 * @author        1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license        http://opensource.org/licenses/MIT License
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Uptimecron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('auth/analyze_model');
        $this->load->model('serp/domain_model');
        $this->load->model('uptime/uptimeincident_model');
        $this->load->model('auth/uptimestats_model');
        $this->load->library('upmonitor');
        $this->load->library('gtmetrix');
        $this->salt = 'X9WX^YvmY!5]\LnD';
    }

    public function getuptimestats() {
        
        // Getting all uptime.
        $this->db->select("domain_id, userid, created, up_time_id, next_run");
        $this->db->from('uptime');
        $query = $this->db->get();
        $uptimes_data = $query->result_array();

        foreach( $uptimes_data as $uptime ) {

            // Getting the user details.
            $this->db->select("username, password, id, uptime_token");
            $this->db->from('users as u');
            $this->db->where("id=", $uptime['userid']);
            $this->db->limit(1);

            $query = $this->db->get();
            $userdetail = $query->row_array();

            if ( $userdetail['uptime_token'] ){
                $token = $userdetail['uptime_token'];
            }
            else {

                $ch = curl_init( 'http://api.upmonitor.io/api/v1/authenticate/' );

                curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, array(
                    'username' => $userdetail['username'],
                    'password' => sha1( $userdetail['username'] . $this->salt )
                ));
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

                $result = curl_exec( $ch );
                
                $token = json_decode( $result, true );
                $token = isset( $token['token'] ) $token['token'] : false; 
                
                if( $token ){
                    // Updating the token.
                    $this->db->flush_cache();
                    $data['uptime_token'] = $token;
                    $this->db->where( 'id', $userdetail['id'] );
                    $this->db->update( 'users', $data );
                }
            }

            if( ! $token ){
                continue;
            }

            $fromDateTime = new DateTime( $uptime['created'] );

            if ( null !== $uptime['next_run'] ) {
                
                // Get the last date from uptime stats.
                $this->db->select( 'MAX(completed_on) AS completed_on' );
                $this->db->from( 'uptime_stats' );
                $this->db->where('domain_id', $uptime['domain_id'] );
                $this->db->order_by( 'completed_on', 'DESC' );
                $this->db->limit( 1 );

                $query = $this->db->get();
                $result = $query->row_array();

                $fromDateTime = new DateTime( $result && isset( $result['completed_on'] ) ? $result['completed_on'] : $uptime['next_run'] );
            }

            $from = $fromDateTime->format( 'Y-m-d\TH:i:s\Z' );

            $curentDateTime = new DateTime();
            $to = $curentDateTime->format( 'Y-m-d\TH:i:s\Z' );

            if( $from > $to ) {
                $fromDateTime = new DateTime( date( 'Y-m-d H:i:s', strtotime( '-10 minutes' ) ) );
                $from = $fromDateTime->format( 'Y-m-d\TH:i:s\Z' );
            }

            $http_query_args = http_build_query( array(
                'until' => $to,
                'since' => $from,
                'module' => 'http',
                'site' => $uptime['up_time_id']
            ));

            $ch = curl_init( 'http://api.upmonitor.io/api/v1/results/?' . $http_query_args );

            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Token ' . $token,
                'Content-Type: application/json',
                'Content-Length: 0'
            ) );

            $result = curl_exec( $ch );
            $result = json_decode($result, true);
            
            $this->saveUptimeStat( $result, $uptime['up_time_id'], $uptime['userid'], $uptime['domain_id'] );

            /*if( isset( $result['next'] ) && null !== $result['next'] ) {

                $ch = curl_init( $result['next'] );

                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Token ' . $token,
                    'Content-Type: application/json',
                    'Content-Length: 0'
                ));

                $result = curl_exec($ch);
                $result = json_decode($result, true);
                $this->saveUptimeStat( $result, $uptime['up_time_id'], $uptime['userid'], $uptime['domain_id'] );
            }*/

            // Updating the next run.
            $this->db->flush_cache();
            $data['next_run'] = date( 'Y-m-d H:i:s', strtotime( "+1 minutes" ) );
            $this->db->where( 'up_time_id', $uptime['up_time_id'] );
            $this->db->update( 'uptime', $data );
        }
    }

    public function saveUptimeStat($result, $uptimeId, $userId, $domainId) {

        if ( ! $result || ! isset( $result['results'] ) || ! is_array( $result['results'] ) ) {
            return false;
        }
        
        foreach ( array_reverse( $result['results'] ) as $res ) {
            
            // Inserting in uptime stats table.
            $data = array(
                'user_id' => $userId,
                'domain_id' => $domainId,
                'uptime_id' => $uptimeId,
                'completed_on' => date('Y-m-d H:i:s', strtotime( $res['completed'] ) ),
                'error' => $res['error'],
                'module' => $res['module'],
                'worker' => $res['worker'],
                'created' => date('Y-m-d H:i:s'),
            );
            
            if ( isset( $res['data']['load'] ) ) {
                $data['load_time'] = $res['data']['load'];
            }

            try {

                $this->db->flush_cache();
                $this->db->insert( 'uptime_stats', $data );

                if( $this->uptimestats_model->update_daily_uptime_stats( $domainId, true ) ){
                    $this->uptimestats_model->update_summ_uptime_stats( $domainId );
                }

                // NOTE: Just for debugging. Will be removed when ensure the correct functionality.
                $this->load->library('email');
                $this->email->from('jiannst@gmail.com', 'Campaigns.io');
                $this->email->to( 'jiannst@gmail.com', 'Yiannis' );
                $this->email->subject('Uptime database COMPLETED SUCCESSFUL' );
                $this->email->message( json_encode( $data ) );
                $this->email->send();
            }
            catch (Exception $e) {

                // NOTE: Just for debugging. Will be removed when ensure the correct functionality.
                $this->load->library('email');
                $this->email->from('jiannst@gmail.com', 'Campaigns.io');
                $this->email->to( 'jiannst@gmail.com', 'Yiannis' );
                $this->email->subject('Uptime database exception' );
                $this->email->message( json_encode( $data ) . "\n\n\n" . $e );
                $this->email->send();
            }

            //updating to the domain table for server status
            // $updateDomain = \App\Models\Domain::find($domainId);
            if ( 0 !== (int) $res['error'] && ! empty( $res['error'] ) ) {

                $this->db->flush_cache();
                $this->db->where( 'id', $domainId );
                $this->db->update( 'domains', array( 'server_status' => 'DOWN' ) ) ;

                // Get the down count.
                $this->db->flush_cache();
                $query = $this->db->query( "SELECT * FROM uptime_incidents WHERE domain_id = " . $domainId . " LIMIT 1" );
                $already_in__uptime_incidents = $query ? $query->row_array() : false;

                $uptime_incidents__save_data = array(
                    'domain_id' => $domainId,
                    'downtime' => $res['completed'],
                    'downtime_worker' => $res['worker'],
                    'error' => $res['error'],
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $this->db->flush_cache();

                if( $already_in__uptime_incidents ){
                    $this->db->where( 'id', $already_in__uptime_incidents['id'] );
                    $this->db->update( 'uptime_incidents', $uptime_incidents__save_data );
                }
                else{
                    $uptime_incidents__save_data['created_at'] = date('Y-m-d H:i:s');
                    $this->db->insert( 'uptime_incidents', $uptime_incidents__save_data );
                }

                /*$set_domain_status_down = false;

                if( $already_in_uptime_confirm_downtime ){

                    if ( 3 <= $already_in_uptime_confirm_downtime['down_count'] && isset( $exist ) && null === $exist['downtime'] &&  null !== $exist['uptime'] ){

                        // $set_domain_status_down = true;

                    }
                    else if ( 3 === $already_in_uptime_confirm_downtime['down_count'] && isset( $exist ) && null !== $exist['downtime'] && null === $exist['uptime'] ){

                        // $set_domain_status_down = true;

                    }
                    else if (  null === $already_in_uptime_confirm_downtime['down_count'] || 3 > $already_in_uptime_confirm_downtime['down_count'] ){

                        // $set_domain_status_down = true;
                    }

                    
                    $this->db->update( 'uptime_incidents', array(
                        'domain_id' => $domainId,
                        'downtime' => $res['completed'],
                        'downtime_worker' => $res['worker'],
                        'error' => $res['error'],
                        'created_at' => date('Y-m-d H:i:s')
                    ));
                }
                else{

                    // $set_domain_status_down = true;

                    $this->db->flush_cache();

                    $this->db->insert( 'uptime_incidents', array(
                        'domain_id' => $domainId,
                        'downtime' => $res['completed'],
                        'downtime_worker' => $res['worker'],
                        'error' => $res['error'],
                        'created_at' => date('Y-m-d H:i:s')
                    ));

                }*/

                $this->db->flush_cache();
                $query = $this->db->query( "SELECT * FROM uptime_confirm_downtime WHERE domain_id='" . $domainId . "' LIMIT 1" );
                $already_in__uptime_confirm_downtime = $query->row_array();

                $uptime_confirm_downtime__save_data = array( 'worker' => $res['worker'] );
                
                if ( $already_in__uptime_confirm_downtime ) {
                    $down_counter = $already_in__uptime_confirm_downtime['down_count'] + 1;
                    $uptime_confirm_downtime__save_data['down_count'] = $down_count;
                    $this->db->where( "id", $already_in__uptime_confirm_downtime['id'] );
                    $this->db->update( 'uptime_confirm_downtime', $uptime_confirm_downtime__save_data );
                }
                else {
                    $down_counter = 1;
                    $uptime_confirm_downtime__save_data['down_count'] = $down_count;
                    $uptime_confirm_downtime__save_data['domain_id'] = $domainId;
                    $uptime_confirm_downtime__save_data['created'] = date('Y-m-d H:i:s');
                    $this->db->insert( 'uptime_confirm_downtime', $uptime_confirm_downtime__save_data );
                }

                if( 3 === $down_counter ){
                    // $this->sendEmailToUserOnDomainStatusDown( $domainId, $userId, $res );
                    $this->notify_domain_status_change( false, $domainId, $userId, $res );
                }

                /*$exist = $this->uptimeincident_model->findLastTimeUpDomain( $domainId );
                
                // Update "incidents" DB table.
                if ( $exist && ( ( null === $exist['uptime'] && null !== $exist['downtime'] ) || ( null === $exist['downtime'] && null !== $exist['uptime'] )) {
                    $this->db->flush_cache();
                    $this->db->where( 'id', $exist['id'] );
                    $this->db->update( 'uptime_incidents', array(
                        'domain_id' => $domainId,
                        'downtime_worker' => $res['worker'],
                        'error' => $res['error']
                    ));
                }*/
            }
            else {

                $this->db->flush_cache();
                $this->db->where( 'id', $domainId );
                $this->db->update( 'domains', array( 'server_status' => 'UP' ));

                $query = $this->db->query( "SELECT * FROM uptime_incidents WHERE domain_id = " . $domainId . " LIMIT 1" );
                $already_in__uptime_incidents = $query ? $query->row_array() : false;

                if( $already_in__uptime_incidents ){
                    $this->db->flush_cache();
                    $this->db->where( 'id', $exist['id'] );
                    $this->db->update( 'uptime_incidents', array(
                        'domain_id' => $domainId,
                        'uptime' => $res['completed'],
                        'uptime_worker' => $res['worker'],
                        'updated_at' => date('Y-m-d H:i:s')
                    ));
                }

                // Updating the confirmdowntime if domain is up in between.
                $this->db->flush_cache();
                $query = $this->db->query( "SELECT * FROM uptime_confirm_downtime WHERE domain_id='" . $domainId . "' LIMIT 1" );
                $already_in_uptime_confirm_downtime = $query->row_array();

                if ( $already_in_uptime_confirm_downtime ) {

                    $this->db->flush_cache();
                    $this->db->where( 'domain_id', $domainId );
                    $this->db->update( 'uptime_confirm_downtime', array(
                        'down_count' => 0,  // Reset down-status counter. 
                        'updated' => date('Y-m-d H:i:s')
                    ));

                    if( 3 <= $already_in_uptime_confirm_downtime['down_count'] ){
                        $this->notify_domain_status_change( true, $domainId, $userId, $res );
                    }
                }

                /*
                // Checking if domain is down last time.
                // Add the up stat if down exist.
                $exist = $this->uptimeincident_model->findLastTimeUpDomain( $domainId );

                if ( isset( $exist ) && null === $exist['uptime'] && null !== $exist['downtime'] ) {
                    $this->db->flush_cache();
                    $this->db->where( 'id', $exist['id'] );
                    $this->db->update( 'uptime_incidents', array(
                        'domain_id' => $domainId,
                        'uptime' => $res['completed'],
                        'uptime_worker' => $res['worker'],
                        'updated_at' => date('Y-m-d H:i:s')
                    ));

                    $this->sendEmailToUserOnDomainStatusUp( $domainId, $userId, $res );
                }
                elseif ( isset( $exist ) && null !== $exist['downtime'] && null !== $exist['uptime'] ) {
                    // ...
                }
                elseif ( ! isset( $exist ) ) {
                    // ...
                }
                elseif ( isset( $exist ) && null === $exist['downtime'] && null !== $exist['uptime'] ) {
                    // ...
                }*/
            }
        }
    }

    private function notify_domain_status_change( $domain_is_up, $domain_id, $user_id, $metrics ){

        // return; // NOTE: Deny notifications send.

        $domain_data = $this->analyze_model->getDomain( $domain_id );
        $response_time = $this->uptimestats_model->getAverageResponseByDomainId( $domain_id );

        $send_data = array(
            'error' => $res['error'],
            'worker' => $res['worker'],
            'domain' => $domain_data[0]->domain_name,
            'report_for' => date('Y-m-d H:i'),
            'responsetime' => $response_time['avg_load_time'],
            'overalluptime' => $this->uptimestats_model->uptimePercentageInDays( $domain_id, 0 ),
        );

        $this->load->library('email');

        // Getting the user from domainid.
        $userinfo = $this->analyze_model->getUserInfoByDomainId( $domain_id );

        $this->email->from('info@campaigns.io', 'Campaigns.io');
        $this->email->to( $userinfo['email'], $userinfo['username'] );
        // $this->email->cc('obsession.raj@gmail.com');

        if( ! $domain_is_up ){
            $view = $this->load->view( get_template_directory() . '/uptime_email', $send_data, true );
            $this->email->subject( 'Server UP notification for ' . $userinfo['domain_name'] );
        }
        else{
            $view = $this->load->view( get_template_directory() . '/downtime_email', $send_data, true );
            $this->email->subject( 'Server DOWN notification for ' . $userinfo['domain_name'] );            
        }

        $this->email->message($view);
        $this->email->send();

        // Sending notification to push bullet.
        $userProfile = $this->analyze_model->getuserprofile( $userId );

        if ( $userProfile['pushbullet_api'] ) {
           
           Pushbullet\Connection::setCurlCallback( function($curl) {
               curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
           }); 
           
           $pb = new Pushbullet\Pushbullet( $userProfile['pushbullet_api'] );

           if( $pb ){

               if( $domain_is_up ){

                    $pb->allDevices()->pushNote(
                        'Server UP notification for ' . $send_data['domain'],
                        "Server up report by worker " . $send_data['worker']
                    );      
               }
               else{

                    $pb->allDevices()->pushNote(
                        'Server DOWN notification for ' . $send_data['domain'],
                        'Server down report by worker ' . $send_data['worker'] . ' with error ' . $send_data['error']
                    );
               }
           }
        }
    }

    /*private function sendEmailToUserOnDomainStatusDown( $domainId, $userId, $res ) {
        
        // return; // NOTE: Deny notifications send.

        $userDomain = $this->analyze_model->getDomain( $domainId );
        $responsetime = $this->uptimestats_model->getAverageResponseByDomainId( $domainId );

        $data = array(
            'error' => $res['error'],
            'worker' => $res['worker'],
            'domain' => $userDomain[0]->domain_name,
            'report_for' => date('Y-m-d H:i'),
            'responsetime' => $responsetime['avg_load_time'],
            'overalluptime' => $this->uptimestats_model->uptimePercentageInDays( $domainId, 0 ),
        );

        // Getting the user from domainid.
        $userinfo = $this->analyze_model->getUserInfoByDomainId( $domainId );
        $view = $this->load->view( get_template_directory() . '/downtime_email', $data, true );

        $this->load->library('email');

        $this->email->from('info@campaigns.io', 'Campaigns.io');
        $this->email->to( $userinfo['email'], $userinfo['username'] );

        // $this->email->cc('obsession.raj@gmail.com');
        $this->email->subject('Server down notification for ' . $userinfo['domain_name']);
        $this->email->message($view);
        $this->email->send();

        // Sending notification to push bullet.
        $userProfile = $this->analyze_model->getuserprofile( $userId );

        if ( $userProfile['pushbullet_api'] ) {
           
           Pushbullet\Connection::setCurlCallback(function($curl) {
               curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
           }); 
           
           $pb = new Pushbullet\Pushbullet( $userProfile['pushbullet_api'] );
           $pb->allDevices()->pushNote(
                'Server down notification for ' . $data['domain'],
                'Server down report by worker ' . $data['worker'] . ' with error ' . $data['error']
            );     
        }
    }

    private function sendEmailToUserOnDomainStatusUp( $domainId, $userId, $res ) {        
        
        // return; // NOTE: Deny notifications send.

        $userDomain = $this->analyze_model->getDomain( $domainId );
        $responsetime = $this->uptimestats_model->getAverageResponseByDomainId( $domainId );

        $data = array(
            'error' => $res['error'],
            'worker' => $res['worker'],
            'report_for' => date('Y-m-d H:i'),
            'domain' => $userDomain[0]->domain_name,
            'responsetime' => $responsetime['avg_load_time'],
            'overalluptime' => $this->uptimestats_model->uptimePercentageInDays( $domainId, 0 ),
        );

        // Getting the user detail from domain.
        $view = $this->load->view( get_template_directory() . '/uptime_email', $data, true );

        // Getting the user from domainid.
        $userinfo = $this->analyze_model->getUserInfoByDomainId( $domainId );
        $this->load->library('email');

        $this->email->from('info@campaigns.io', 'Campaigns.io');
        $this->email->to($userinfo['email'], $userinfo['username']);

        // $this->email->cc('obsession.raj@gmail.com');
        $this->email->subject('Server up notification for ' . $userinfo['domain_name']);
        $this->email->message($view);
        $this->email->send();

        // Sending notification to push bullet.
        $userProfile = $this->analyze_model->getuserprofile( $userId );

        if ( $userProfile['pushbullet_api'] ) {
           
           Pushbullet\Connection::setCurlCallback( function ($curl) {
               curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
           });

           $pb = new Pushbullet\Pushbullet( $userProfile['pushbullet_api'] );
           $pb->allDevices()->pushNote( 'Server up notification for ' . $data['domain'], "Server up report by worker " . $data['worker'] );
        }
    }*/

    private function cron_job_execution( $type ){

        $view_path = get_template_directory();

        switch( $type ){
            case 'daily':
                $day = 0;
                $view_path .= '/dailycron';
                $email_subject = "Daily report for domain uptime";
                break;
            case 'weekly':
                $day = 7;
                $view_path .= '/weeklycron';
                $email_subject = "Weekly report for domain uptime";
                break;
            case 'monhtly':
                $day = 30;
                $view_path .= '/monthlycron';
                $email_subject = "Monthly report for domain uptime";
                break;
        }

        // Getting all users.
        $users = $this->analyze_model->getallUsers();

        foreach ($users as $user) {
            
            // Check if user has any domain.
            $query = $this->db->query( "SELECT * FROM domains WHERE userid = '" . $user['id'] . "' LIMIT 1" );
            $domainExist = $query->row_array();

            if ( $domainExist ) {

                $totaltime_temp = array();

                $globalPerformance = $this->uptimestats_model->getGlobalPerformanceByUserId( $user['id'] );
                $overalloutage = $this->uptimeincident_model->getOverallIncidentsByUserId( $day, $user['id'] );

                if( 'daily' === $type ){

                    $totaltime = $this->uptimestats_model->getTotalUptimesByUserIdInSingleDay( $user['id'], $day );

                    $count = 0;
                    $totaluptime = 0;

                    if ( $data['uptime'] ) {
                        foreach ( $data['uptime'] as $up ) {
                            $totaluptime += $up['uptime_percentage'];
                            $count++;
                        }
                    }

                    if ( $data['downtime'] ) {
                        foreach ( $data['downtime'] as $dp ) {
                            $totaluptime += $dp['uptime_percentage'];
                            $count++;
                        }
                    }

                    $data = array(
                        'uptime' => $this->uptimestats_model->getUptimeByUserIdSingleDay( $user['id'], $day ),
                        'downtime' => $this->uptimestats_model->getDowntimeByUserIdWithOutageSingleDay( $user['id'], $day ),
                        'global_performance' => $globalPerformance['average_perfomance'] ? round( $globalPerformance['average_perfomance'] / 1000, 2 ) : 0,
                        'overalluptime' => 0 !== $count ? round( $totaluptime / $count, 5 ) : 0,
                        'report_for' => date( 'Y-m-d', strtotime( '-' . $day . ' day', time() ) ),
                        'nostats' => $this->analyze_model->getNoStatsDomainDailyCron( $data['uptime'], $data['downtime'], $user['id'] )
                    );
                }
                else{

                    $totaltime = $this->uptimestats_model->getTotalUptimesByUserId( $user['id'], $day );

                    $data = array(
                        'uptime' => $this->uptimestats_model->getUptimeByUserId( $user['id'], $day ),
                        'downtime' => $this->uptimestats_model->getDowntimeByUserId( $user['id'], $day ),
                        'overalluptime' => round( $this->analyze_model->uptimePercentageInDaysOverall( $day, $user['id'] ), 5 ),
                        'global_performance' => $globalPerformance['average_perfomance'] ? round( $globalPerformance['average_perfomance'] / 1000, 2 ) : 0,
                        'report_for' => date( 'Y-m-d', strtotime( '-' . $day . ' day', time() ) ) . ' to ' . date('Y-m-d'),
                        'nostats' => $this->analyze_model->getNoStatsDomain( $user['id'] ),
                    );
                }

                if ( $totaltime ) {
                    foreach ( $totaltime as $t ) {
                        $totaltime_temp[ $t['domain_id'] ]['total_stats'] = $t['total_stats'];
                    }
                }

                $data['totaltime'] = $totaltime_temp;
                $data['overalloutage'] = $overalloutage['overalloutage'];

                $view = $this->load->view( $view_path, $data, true );

                $this->load->library('email');
                $this->email->from('info@campaigns.io', 'Campaigns.io');
                $this->email->to($user['email'], $user['username']);
                // $this->email->cc('obsession.raj@gmail.com');
                $this->email->subject( $email_subject );
                $this->email->message($view);
                $this->email->send();
            }
        }
    }

    public function dailycron() {

        $this->cron_job_execution( 'daily' );
        return;
        
        // TODO: Remove when confirm that replacement function works properly.
        /*$day = 0;
        
        // Getting all users.
        $users = $this->analyze_model->getallUsers();

        // $objUptimeStats = new \App\Models\Uptimestats();
        // $objUptime      = new \App\Models\Uptime();
        // $objIncident    = new \App\Models\Uptimeincidents();
        
        foreach ($users as $user) {
            
            // Check if user has any domain.
            $query = $this->db->query( "SELECT * FROM domains WHERE userid = '" . $user['id'] . "' LIMIT 1" );
            $domainExist = $query->row_array();

            if ($domainExist) {

                $totaltime_temp = array();

                $totaltime = $this->uptimestats_model->getTotalUptimesByUserIdInSingleDay( $user['id'], $day );
                if ($totaltime) {
                    foreach ($totaltime as $t) {
                        $totaltime_temp[$t['domain_id']]['total_stats'] = $t['total_stats'];
                    }
                }

                $count = 0;
                $totaluptime = 0;

                if ($uptime) {
                    foreach ($uptime as $up) {
                        $totaluptime += $up['uptime_percentage'];
                        $count++;
                    }
                }

                if ($downtime) {
                    foreach ($downtime as $dp) {
                        $totaluptime += $dp['uptime_percentage'];
                        $count++;
                    }
                }

                $globalPerformance = $this->uptimestats_model->getGlobalPerformanceByUserId( $user['id'] );
                $overalloutage = $this->uptimeincident_model->getOverallIncidentsByUserId( $day, $user['id'] );

                $data = array(
                    // Get the uptimes of all domains of user.
                    'uptime' => $this->uptimestats_model->getUptimeByUserIdSingleDay( $user['id'], $day ),
                    // Get the downtimes of all domains of user.
                    'downtime' => $this->uptimestats_model->getDowntimeByUserIdWithOutageSingleDay( $user['id'], $day ),
                    'totaltime' => $totaltime_temp,
                    'overalloutage' => $overalloutage['overalloutage'],
                    'global_performance' => $globalPerformance['average_perfomance'] ? round( $globalPerformance['average_perfomance'] / 1000, 2 ) : 0,
                    'overalluptime' => 0 !== $count ? round( $totaluptime / $count, 5 ) : 0,
                    'report_for' => date( 'Y-m-d', strtotime( '-' . $day . ' day', time() ) ),
                );

                // $data['overalluptime'] = $objUptimeStats->uptimePercentageInSingleDayOverall( $day, $user->id );

                $data['nostats']   = $this->analyze_model->getNoStatsDomainDailyCron( $data['uptime'], $data['downtime'], $user['id'] );
                
                echo '<pre>';
                print_r($data);
                echo '------------------<br>';

                $view = $this->load->view( get_template_directory() . '/dailycron', $data, true );
                $this->load->library('email');
                $this->email->from('info@campaigns.io', 'Campaigns.io');
                $this->email->to( $user['email'], $user['username']);

                // $this->email->cc('obsession.raj@gmail.com');
                $this->email->subject('Daily report for domain uptime');
                $this->email->message($view);
                $this->email->send();
            }
        }*/
    }

    public function monthlycron() {

        $this->cron_job_execution( 'monthly' );
        return;

        // TODO: Remove when confirm that replacement function works properly.
        /*$day = 30;
        
        // Getting all users.
        $users = $this->analyze_model->getallUsers();

        foreach ($users as $user) {
            
            // Check if user has any domain.
            $query = $this->db->query( "SELECT * FROM domains WHERE userid='" . $user['id'] . "' LIMIT 1" );
            $domainExist = $query->row_array();

            if ( $domainExist ) {

                $data['overalluptime'] = $this->analyze_model->uptimePercentageInDaysOverall( $day, $user['id'] );
                $data['overalluptime'] = round( $data['overalluptime'], 5 );
                $globalPerformance = $this->uptimestats_model->getGlobalPerformanceByUserId( $user['id'] );
                $data['global_performance'] = $globalPerformance['average_perfomance'] ? round( $globalPerformance['average_perfomance'] / 1000, 2 ) : 0;

                $overalloutage = $this->uptimeincident_model->getOverallIncidentsByUserId( $day, $user['id'] );
                $data['overalloutage'] = $overalloutage['overalloutage'];

                $temp = array();

                $totaltime = $this->uptimestats_model->getTotalUptimesByUserId( $user['id'], $day );
                if ( $totaltime ) {
                    foreach ( $totaltime as $t ) {
                        $temp[ $t['domain_id'] ]['total_stats'] = $t['total_stats'];
                    }
                }

                // Get the uptimes of all domains of user.
                $data['uptime'] = $this->uptimestats_model->getUptimeByUserId( $user['id'], $day );

                // Get the downtimes of all domains of user.
                $data['downtime'] = $this->uptimestats_model->getDowntimeByUserId( $user['id'], $day );

                $data['totaltime']  = $temp;
                $data['report_for'] = date( 'Y-m-d', strtotime( '-' . $day . ' day', time() ) ) . ' to ' . date('Y-m-d');
                $data['nostats'] = $this->analyze_model->getNoStatsDomain( $user['id'] );

                $view = $this->load->view( get_template_directory() . '/monthlycron', $data, true );
                $this->load->library('email');
                $this->email->from('info@campaigns.io', 'Campaigns.io');
                $this->email->to($user['email'], $user['username']);
                // $this->email->cc('obsession.raj@gmail.com');
                $this->email->subject('Monthly report for domain uptime');
                $this->email->message($view);
                $this->email->send();
            }
        }*/
    }

    public function weeklycron() {

        $this->cron_job_execution( 'weekly' );
        return;

        // TODO: Remove when confirm that replacement function works properly.
        /*$day = 7;

        // Getting all users.
        $users = $this->analyze_model->getallUsers();

        foreach ($users as $user) {
            
            // Check if user has any domain.
            $query = $this->db->query( "SELECT * FROM domains WHERE userid='" . $user['id'] . "' LIMIT 1" );
            $domainExist = $query->row_array();

            if ( $domainExist ) {

                $data['overalluptime'] = $this->analyze_model->uptimePercentageInDaysOverall( $day, $user['id'] );
                $globalPerformance = $this->uptimestats_model->getGlobalPerformanceByUserId( $user['id'] );
                $data['global_performance'] = $globalPerformance['average_perfomance'] ? round( $globalPerformance['average_perfomance'] / 1000, 2 ) : 0;

                $overalloutage = $this->uptimeincident_model->getOverallIncidentsByUserId( $day, $user['id'] );
                $data['overalloutage'] = $overalloutage['overalloutage'];

                $temp = array();

                $totaltime = $this->uptimestats_model->getTotalUptimesByUserId( $user['id'], $day );
                if  ($totaltime ) {
                    foreach ($totaltime as $t) {
                        $temp[ $t['domain_id'] ]['total_stats'] = $t['total_stats'];
                    }
                }

                // Get the uptimes of all domains of user.
                $data['uptime'] = $this->uptimestats_model->getUptimeByUserId( $user['id'], $day );
                
                // The downtimes of all domains of user.
                $data['downtime'] = $this->uptimestats_model->getDowntimeByUserId( $user['id'], $day );

                $data['totaltime'] = $temp;
                $data['report_for'] = date( 'Y-m-d', strtotime( '-' . $day . ' day', time() ) ) . ' to ' . date('Y-m-d');
                $data['nostats'] = $this->analyze_model->getNoStatsDomain( $user['id'] );
                
                $view = $this->load->view( get_template_directory() . '/weeklycron', $data, true );

                $this->load->library('email');
                $this->email->from('info@campaigns.io', 'Campaigns.io');
                $this->email->to($user['email'], $user['username']);
                // $this->email->cc('obsession.raj@gmail.com');
                $this->email->subject('Monthly report for domain uptime');
                $this->email->message( $view );
                $this->email->send();
            }
        }*/
    }
}
