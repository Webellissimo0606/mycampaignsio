<?php
if( ! function_exists('domain_navigation') ){
    require_once FCPATH . 'app/helpers/campaigns-io/functions_helper.php';
}

global $current_page;

$user_id = (int) $session_user_data['user_id'];
$domain_id = (int) $session_user_data['domainId'];

if( in_array( $current_page, array('single-domain', 'single-domain-analytics', 'single-domain-wordpress'/*, 'view-serps'*/, 'single-domain-heatmaps', 'single-domain-heatmaps-list', 'single-domain-research', 'single-domain-e-commerce' ), true ) && 0 < $domain['id'] ){

    $session_user_data = $this->session->get_userdata();

    $q = "SELECT * FROM user_domain ud JOIN domains d ON d.id=ud.domain_id WHERE ud.user_id='" . $session_user_data['user_id'] . "' ORDER BY d.id DESC;";
    $results = $this->db->query($q);
    $user_domains = $results->result_array();

    domain_navigation( $current_page, $domain_id, $user_domains );
}
if( $current_page === 'seoreporting-project' || $current_page  === 'seoreporting-task') {
	seoreporting_navigation($current_page);
}