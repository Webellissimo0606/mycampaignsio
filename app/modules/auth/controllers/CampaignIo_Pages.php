<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class CampaignIo_Pages extends CI_Controller {

    protected $ci;

	protected $page_data = array(
		'view_filename'=> null,
		'document_data' => array(
            'title' => 'Campaigns.io',
            'styles' => array(),
            'top_scripts' => array(),
            'bottom_scripts' => array(),
        ),
        'url' => array(
            'home' => null,
            'logo' => null,
        ),
        'current_page' => false,
        'collapsed_sidebar' => false,
        'collapsed_author_nav' => false,
	);

	public function __construct() {

    	parent::__construct();

        $this->ci = &get_instance();

        $this->load_helpers();

        $theme_url = base_url() . 'frontend/site/default/new-ui/';

        $this->page_data['url']['home'] = base_url() . 'auth/home';
        $this->page_data['url']['logo'] = $theme_url . 'img/campaigns-io-logo.png';

        if( isset( $_COOKIE['campaigns-io']['collapse-sidebar'] ) ){
            $this->page_data['collapsed_sidebar'] = 1 === (int) $_COOKIE['campaigns-io']['collapse-sidebar'];
        }

        if( isset( $_COOKIE['campaigns-io']['collapse-author-nav'] ) ){
            // NOTE: Replaced to keep users navigation menu collapsed on pages load.
            // $this->page_data['collapsed_author_nav'] = 1 === (int) $_COOKIE['campaigns-io']['collapse-author-nav'];

            $this->page_data['collapsed_author_nav'] = 1;
        }
    }

    protected function load_models(){
        // @note: Even if remains empty, DON'T REMOVE IT. Is usable in 'child' class "Page_Authorized".
    }

    protected function load_helpers(){
        $this->load->helper('campaigns-io/functions');
        $this->load->helper('campaigns-io/classes');
    }

    protected function load_libraries(){
        // @note: Even if remains empty, DON'T REMOVE IT. Is usable in 'child' class "Page_Authorized".
    }

    protected function display_page($page){
        $this->page_document_data($page);

        $this->page_data['current_page'] = $page;
       	$this->load->view( "campaigns-io/templates/html-header" , $this->page_data );
        $this->load->view( "campaigns-io/templates/page-top" , $this->page_data );
        $this->load->view( 'campaigns-io/pages/' . $page, $this->page_data);
        $this->load->view( 'campaigns-io/templates/page-bottom', $this->page_data );
        $this->load->view( 'campaigns-io/templates/html-footer', $this->page_data );
    }

    protected function page_document_data($page){
        $this->page_stylesheets($page);
        $this->page_scripts_top($page);
        $this->page_scripts_bottom($page);
    }

    protected function page_stylesheets($page){

        $local_base = base_url() . "frontend/site/default/new-ui/assets/css/";

        $styles = array();
        $styles[] = array( "rel" => "stylesheet", "href" => "https://fonts.googleapis.com/icon?family=Material+Icons" );

        switch( $page ){
            case 'business-overview':
                $styles[] = array( "rel" => "stylesheet", "href" => "//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" );
                $styles[] = array( "rel" => "stylesheet", "href" => $local_base . "calendar.css" );
                break;
            case 'single-domain-serps':
                $styles[] = array( "rel" => "stylesheet", "href" => $local_base . "calendar.css" );
                break;
        }

        // @note: Use instead local file (at least for now).
        // $styles[] = array( "rel" => "stylesheet", "href" => base_url() . "https://unpkg.com/tachyons@4.7.0/css/tachyons.min.css" );
        $styles[] = array( "rel" => "stylesheet", "href" => $local_base . "tachyons.min.css" );

        $styles[] = array( "rel" => "stylesheet", "href" => $local_base . "body.min.css" );
        $styles[] = array( "rel" => "stylesheet", "href" => $local_base . "forms.min.css" );
        $styles[] = array( "rel" => "stylesheet", "href" => $local_base . "general.min.css" );
        $styles[] = array( "rel" => "stylesheet", "href" => $local_base . "sidebar.min.css" );
        $styles[] = array( "rel" => "stylesheet", "href" => $local_base . "main-section-layout.min.css" );
        $styles[] = array( "rel" => "stylesheet", "href" => $local_base . "styles.min.css" );
        $styles[] = array( "rel" => "stylesheet", "href" => $local_base . "new-style.css" );
        $styles[] = array( "rel" => "stylesheet", "href" => $local_base . "font-awesome.min.css" );


        $this->page_data['document_data']['styles'] = $styles;
    }

    protected function page_scripts_top($page){

        $top_scripts = array(
            // @note New version of Chart.js [2.6.0] has bugs in IE browsers, especially using time scales.
            array( "type" => "text/javascript", "src" => "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js" )
        );

        switch( $page ){
            case 'login':
            case 'register':
            case 'forgot-password':
                $top_scripts[] = array( "src" => "https://www.google.com/recaptcha/api.js" );
                break;
        }

        $this->page_data['document_data']['top_scripts'] = $top_scripts;
    }

    protected function page_scripts_bottom($page){

        $local_base = base_url() . "frontend/site/default/new-ui/assets/js/";

        $bottom_scripts = array();

        $bottom_scripts[] = array( "type" => "text/javascript", "src" => "http://www.geoplugin.net/javascript.gp", "language" => "JavaScript" );
        $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "jquery-3.2.1.min.js" );
        $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "jquery-migrate-1.4.1.min.js");

        // @note: Include script only in pages need ? ==> Maybe not, use it in any page.
        $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "Choices/assets/scripts/dist/choices.min.js" );

        switch( $page ){
            case 'groups':
            case 'subusers':
            case 'domains':
            case 'business-overview':
            case 'single-domain-serps':
            case 'uptime-report':
            case 'uptime-status-report':
            case 'single-domain-heatmaps-list':
            case 'single-domain-research':
            case 'single-domain-e-commerce':
                $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "Vanilla-DataTables/dist/vanilla-dataTables.min.js" );
                break;
        }

        switch( $page ){
            case 'business-overview':
                $bottom_scripts[] = array( "src" => "//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js" );
                $bottom_scripts[] = array( "src" => "//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js" );
                $bottom_scripts[] = array( "src" => base_url() . "frontend/site/default/new-ui/assets/js/calendar.js" );
                $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "Campaings-io-pages/business-overview.js" );
                break;
            case 'domains-add':
            case 'single-domain-edit':
                $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "Campaings-io-pages/domain-add-edit.js" );
                break;
            case 'single-domain':
                $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "Campaings-io-pages/domain-overview.js" );
                break;
            case 'single-domain-serps':
                $bottom_scripts[] = array( "src" => base_url() . "frontend/site/default/new-ui/assets/js/calendar.js" );
                $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "Campaings-io-pages/domain-serps.js" );
                break;
            case 'single-domain-analytics':
                $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "Campaings-io-pages/domain-analytics.js" );
                break;
            case 'single-domain-wordpress':
                $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "Campaings-io-pages/domain-wordpress.js" );
                break;
            case 'single-domain-wordpress-login':
                $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "Campaings-io-pages/domain-wordpress-login.js" );
                break;
            case 'single-domain-heatmaps':
            case 'single-domain-heatmaps-list':
                $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "Campaings-io-pages/domain-heatmaps.js" );
                break;
            case 'single-domain-research':
                $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "Campaings-io-pages/domain-research.js" );
                break;
            case 'single-domain-e-commerce':
                $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "Campaings-io-pages/domain-e-commerce.js" );
                break;
        }

        $bottom_scripts[] = array( "type" => "text/javascript", "src" => $local_base . "campaigns-io-script.js" );

        $this->page_data['document_data']['bottom_scripts'] = $bottom_scripts;
    }

    /* =============== // PAGES =============== */

    public function single_domain_wordpress_invalid_access(){

        $package = isset( $_GET['package'] ) && '' !== trim($_GET['package']) ? $_GET['package'] : null;
        $signature = isset( $_GET['signature'] ) && '' !== trim($_GET['signature']) ? $_GET['signature'] : null;

        if( null === $package ||  null === $signature ){
            die('Invalid request-response');
        }

        $data = validate_api_response( array( 'package' => $package, 'signature' => $signature ) );

        $site_id = isset($data['site_id']) && $data['site_id'] ? $data['site_id'] : null;
        $request_url = isset( $data['request_url'] ) && $data['request_url'] ? $data['request_url'] : null;
        $request_action = isset( $data['request_action'] ) && $data['request_action'] ? $data['request_action'] : null;
        $error = isset($data['error']) && $data['error'] ? $data['error'] : null;

        if( null === $site_id || null === $request_url || null === $error ){
            die('Invalid request-response arguments');
        }

        switch( $error ){
            case 'invalid-request': die('Invalid request'); exit;
            case 'invalid-user':    die('Invalid username'); exit;
            case 'invalid-access':  // When user hasn't valid tokens or website hasn't saved any token value before ( eg. after plugin (re-) activation ).

                // TODO: ....
                // $this->validate_view_site();
                // $this->validate_view_site_author( $site_id );

                $site = array(
                    'id' => $site_id,
                    'data' => get_site_by_id( $site_id, $this->ci_auth->ci ),
                );

                $wp_auth_access = new WP_Request_Access( $site, $this->session, $this->ci_auth );
                $wp_auth_access->request_tokens( $request_action );
                exit;
        }

        die('Invalid error type');
    }

    /* =============== PAGES // =============== */
}