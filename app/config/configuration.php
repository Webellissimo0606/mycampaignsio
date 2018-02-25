
<?php
/**
 * CIMembership
 * 
 * @package		Config
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

defined('BASEPATH') OR exit('No direct script access allowed');
/* base url */

$config['website_url'] = 'http://app.campaigns.io';
//$config['website_url'] = 'http://localhost/campaigns.io';

/* database settings */
$config['db_hostname'] = '127.0.0.1';
$config['db_username'] = 'root';

if( preg_match('/localhost/i', $_SERVER['HTTP_HOST']) || preg_match('/campaignsio/i', $_SERVER['HTTP_HOST']) ){
	$config['db_password'] = '';	
	$config['database'] = 'campaigns_app';
}else{
	$config['db_password'] = 'j3nRxKUEGddYKJWpgmE69';
	$config['database'] = 'campaignsio';
}

//$config['db_password'] = 'open';

$config['db_table_prefix'] = '';

/*
|--------------------------------------------------------------------------
| Google OAuth Configuration
|--------------------------------------------------------------------------
|
| 
*/

// $config['google_oauth']['client_id'] = '640474972872-0mi8npf8cj9pkm3kh9j3b6pdu96ok2sl.apps.googleusercontent.com';
$config['google_oauth']['client_id'] = '806839305935-g9nsf1dpegronh2u5o84c45gpl616c1u.apps.googleusercontent.com';
// $config['google_oauth']['client_secret'] = 'c7DpCsPzcXG1F48dUgLe5s66';
$config['google_oauth']['client_secret'] = 'Z-v9tMubpJKor7dBkYFwfgnX';
$config['google_oauth']['redirect_uri'] = 'http://my.campaigns.io/analytics/analytics';
// $config['google_oauth']['api_key'] = 'AIzaSyCYjkF7Grd1JWE3EyoGTO_dW283u0CyN_o';
$config['google_oauth']['api_key'] = 'AIzaSyBKvF07TN55Vm9yW7yxhmENfJfRcnFFfoU';
$config['piwik']['api_url'] = 'https://stats.campaigns.io';
$config['piwik']['auth_token'] = 'd9f9a036b05a7fc10e8bab21c605cef6';
$config['serpstat']['token'] = '830feab951c10b57c3a7964e2111dd3c';
