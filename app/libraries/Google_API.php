<?php
        if (!defined('BASEPATH')) exit('No direct script access allowed');
        ini_set('include_path', 'C:\wamp\www\analytics\google_api\src');
        set_include_path(APPPATH . 'third_party/google_api/src');
        require_once APPPATH . 'third_party/google_api/vendor/autoload.php';
        require_once APPPATH . 'third_party/google_api/src/Google/Client.php';

        class Google_API extends Google_Client {
            function __construct($params = array()) {
                parent::__construct();
            }
        } 

        ?>