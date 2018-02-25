<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('googletracker.php');
class Trace extends GoogleTracker
    {
       public function index()
        {
            //$test =  new GoogleTracker(array('git'), 'www.kernel.org', 50);
			$test =  new GoogleTracker;
			$test->res(array('php'), 'www.w3schools.com', 50);
            //$test->use_proxy('proxy.txt');
            $test->run();
 
            print_r($test->get_results());
            echo "================<br>";
            print_r($test->get_debug_info());
        }
 
    }
?>