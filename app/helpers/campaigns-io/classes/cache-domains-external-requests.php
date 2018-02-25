<?php
class Cache_Domains_External_Requests {

	private $db;
	private $db_table;
	private $keep_cache_fresh;

	function __construct( $db = null ){
		$this->db = $db;
		$this->db_table = 'domains_requests_cache';
		$this->keep_cache_fresh = 3600;	// Hour in seconds;
	}
	
	public function get_value( $domain_id = null, $request = null, $allow_expired = false ){
		if( null === $domain_id || null === $request ){ return false; }
		
		$return = array(
			'value' => null,
			'error' => null,
			'date' => null,
		);

		$domain_id = (int) $domain_id;
		$sql = 'SELECT response, response_type as type, date FROM ' . $this->db_table . ' WHERE domain_id = ' . $domain_id . ' AND request = "' . $request . '" LIMIT 1;';
		$query = $this->db->query( $sql );
		$row = $query->row_array();

		if( $row ){

			if( $allow_expired || ( time() < ( $this->keep_cache_fresh + strtotime( $row['date'] ) ) ) ){

				$return['date'] = $row['date'];

				switch( $row['type'] ){
					case 'string':
						$return['value'] = $row['response'];
						break;
					case 'boolean':
						$return['value'] = '1' === $row['response'] ? true : false;
						break;
					case 'integer':
					case 'double':
						$return['value'] = $row['response'] + 0;
						break;
					case 'array':
						$return['value'] = json_decode( stripslashes( $row['response'] ), true );
						break;
					default:
						$return['error'] = 'Invalid response type';
				}
			}
		}

		return $return;	// @note: Returns 'null' if not exists.
	}

	public function set_value( $domain_id = null, $request = null, $response = null ){
		// if( null === $domain_id || null === $request || null === $resposne ){ return false; }
		
		$domain_id = (int) $domain_id;
		$requests = $this->db->escape_str( $request );

		$response_data_type = gettype( $response );

		switch( $response_data_type ){
			case 'string':
				$response = $response;
				break;
			case 'boolean':
				$response = $response ? '1' : '0';
				break;
			case 'integer':
			case 'double':
				$response = $response + "";
				break;
			case 'array':
				$response = json_encode( $response );
				break;
			default:
				die('Cache_Domains_External_Requests:: Invalid response type');
		}

		$response =  $this->db->escape_str( $response );

		$sql = 'SELECT COUNT(id) as already_saved FROM ' . $this->db_table . ' WHERE domain_id = ' . $domain_id . ' AND request = "' . $request . '" LIMIT 1; ';
		$query = $this->db->query( $sql );
		$row = $query->row_array();

		if( 0 === (int) $row['already_saved'] ){
			$this->db->insert( $this->db_table, array(
				'domain_id' => $domain_id,
				'request' => $request,
				'response' => $response,
				'response_type' => $response_data_type,
			));
		}
		else{
			$this->db->where('domain_id', $domain_id);
			$this->db->where('request', $request);
			$this->db->update( $this->db_table, array(
				'response' => $response,
				'response_type' => $response_data_type,
				'date' => date( " Y.m.d H:i:s " ),
			));
		}
	}

	public function delete_value( $domain_id = null, $request = null ){
		$this->db->flush_cache();
        $this->db->where('domain_id', $domain_id);
        $this->db->where('request', $request);
        $this->db->delete( "domains_requests_cache" );
	}
}
