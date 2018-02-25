<?php
if( ! function_exists('user_navigation') ){
    require_once FCPATH . 'app/helpers/campaigns-io/functions_helper.php';
}

global $current_page;

user_navigation( $current_page );