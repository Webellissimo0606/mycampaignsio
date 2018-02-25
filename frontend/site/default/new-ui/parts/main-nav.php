<?php
if( ! function_exists('main_navigation') ){
    require_once FCPATH . 'app/helpers/campaigns-io/functions_helper.php';
}

global $current_page;

main_navigation( $current_page );
