<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Notifications extends CIUIS_Controller {
	function markread() {
		$this->Notifications_Model->markread();
	}
}