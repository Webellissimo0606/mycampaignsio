<?php
// Based on controller 'analytics/piwik'.
class New_Piwik_Handler {

	private $ci;
	private $analyze_model;

	function __construct( $ci, $analyze_model ){
		$this->ci = $ci;
		$this->analyze_model = $analyze_model;
	}

	private function getPiwikResults( $urlParam, $limit = '' ) {
        $apiUrl = $this->ci->config->config['piwik']['api_url'];
        $token_auth = $this->ci->config->config['piwik']['auth_token'];
        $url = $apiUrl . $urlParam . "&token_auth=" . $token_auth . "&format=JSON&filter_limit=" . ( $limit ? 40 : (int) $limit );

        $arrContextOptions = array( "ssl" => array( "verify_peer" => false, "verify_peer_name" => false, ) );
        $fetched = file_get_contents( $url, false, stream_context_create( $arrContextOptions ) );
        $result = json_decode( $fetched, true );
        return $result;
    }

	public function getsearchengineclicks( $domainId ) {

		$return = array( 'status' => 'error', 'clicks' => null );

		$domain = $this->analyze_model->getDomain( $domainId );

		if ( 0 < $domain[0]->piwik_site_id ) {
			
			$totalClicks = 0;

			// Getting total visitors graph.
			$url = "?module=API&method=Referrers.getSearchEngines&idSite=";
			$url .= $domain[0]->piwik_site_id;
			$url .= "&period=month&date=today&showColumns=label,nb_visits";

			$response = $this->getPiwikResults( $url );

			if ( $response && isset( $response['result'] ) && 'error' !== $response['result'] ){
				$return['status'] = 'success';
				foreach($response as $res) {
					$totalClicks += $res['nb_visits'];
				}
			}
			else{
				$return['msg'] = $response;
			}

			$return['clicks'] = $totalClicks;
		}
		else {
			$return['msg'] = 'Piwik data not available';
		}
		return $return;
    }
}