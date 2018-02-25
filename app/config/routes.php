<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
 */
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['admin'] = "admin/dashboard";
$route['auth/createuptimeuser'] = "uptime/uptime/createuseruptime";
$route['businessoverview'] = "auth/business/index";

$route['auth/site/viewsite/(.+)'] = 'auth/site/viewsite';
$route['auth/site/upgrades/(.+)'] = 'auth/site/upgrades';
$route['auth/site/update/(.+)'] = 'auth/site/update';
$route['auth/site/summary/(.+)'] = 'auth/site/summary';
$route['auth/dashboard/([0-9]+)'] = 'auth/dashboard';

$route['auth/wordpress/([0-9]+)'] = 'auth/wordpress';
$route['analytics/analytics/([0-9]+)'] = 'analytics/analytics';

// $route['auth/home/([0-9]+)'] = 'auth/home/index';	// @note: Doesn't need. Already exists the file "app/modules/auth/controllers/Home.php"
$route['auth/currentsubuser/([0-9]+)'] = 'auth/auth/setcurrentuser';

$route['addsubuser'] = 'auth/auth/addsubuser';
$route['editsubuser'] = 'auth/auth/editsubuser';
$route['editsubuser/([0-9]+)'] = 'auth/auth/editsubuser';
$route['deletesubuser/([0-9]+)'] = 'auth/auth/deletesubuser';
$route['listsubuser'] = 'auth/auth/listsubuser';
$route['assigndomain'] = 'auth/auth/assigndomain';
$route['assigndomain/([0-9]+)'] = 'auth/auth/assigndomain';
$route['assigndomain/([0-9]+)/([0-9]+)'] = 'auth/auth/assigndomain';

$route['addgroups'] = 'auth/auth/addgroups';
$route['editgroups'] = 'auth/auth/editgroups';
$route['editgroups/([0-9]+)'] = 'auth/auth/editgroups';
$route['deletegroups/([0-9]+)'] = 'auth/auth/deletegroups';
$route['listgroups'] = 'auth/auth/listgroups';

$route['ecommerce'] = 'analytics/piwik/ecommerce';
$route['securityscan'] = 'analytics/sucuri/securityscan';
$route['analytics/code/([0-9]+)'] = 'analytics/piwik/code';
$route['sso/([A-Za-z0-9]+)'] = 'auth/auth/sso';
$route['createuser'] = 'auth/auth/createuser';

$route['heatmaps/list'] = 'analytics/heatmaps/index';
$route['heatmaps/add'] = 'analytics/heatmaps/add';
$route['heatmaps/delete/([0-9]+)'] = 'analytics/heatmaps/delete';
$route['heatmaps/edit/([0-9]+)'] = 'analytics/heatmaps/edit';
$route['heatmaps/([0-9]+)'] = 'analytics/heatmaps/show';
$route['heatmaps'] = 'analytics/heatmaps/show';
$route['domain'] = 'auth/domain/index';

$route['addlinkdomain'] = 'domainlink/index/adddomain';
$route['listlinkdomain'] = 'domainlink/index/listdomain';
$route['editlinkdomain/([0-9]+)'] = 'domainlink/index/editdomain';
$route['editlinkdomain'] = 'domainlink/index/editdomain';
$route['deletelinkdomain/([0-9]+)'] = 'domainlink/index/deletedomain';
$route['addlinkpage'] = 'domainlink/index/addlinkpage';
$route['editlinkpage/([0-9]+)/([0-9]+)'] = 'domainlink/index/editlinkpage';
$route['editlinkpage'] = 'domainlink/index/editlinkpage';
$route['listlinkpage/([0-9]+)'] = 'domainlink/index/listlinkpage';
$route['getbacklinkdomains'] = 'domainlink/index/getbacklinkdomains';
$route['addbacklinkdomain'] = 'domainlink/index/addbacklinkdomain';
$route['deletelinkpage/([0-9]+)/([0-9]+)'] = 'domainlink/index/deletelinkpage';
$route['getregistrarinfo'] = 'domainlink/index/getregistrarinfo';

$route['addclient'] = 'auth/client/addclient';
$route['listclient'] = 'auth/client/listclient';
$route['editclient/([0-9]+)'] = 'auth/client/editclient';
$route['editclient'] = 'auth/client/editclient';
$route['deleteclient/([0-9]+)'] = 'auth/client/deleteclient';
$route['deleteclient)'] = 'auth/client/deleteclient';

$route['addcurrency'] = 'auth/currency/addcurrency';
$route['listcurrency'] = 'auth/currency/listcurrency';
$route['editcurrency/([0-9]+)'] = 'auth/currency/editcurrency';
$route['editcurrency'] = 'auth/currency/editcurrency';
$route['deletecurrency/([0-9]+)'] = 'auth/currency/deletecurrency';
$route['deletecurrency)'] = 'auth/currency/deletecurrency';
$route['updateCurrency'] = 'auth/currency/updateCurrency';

$route['addproduct'] = 'auth/product/addproduct';
$route['listproduct'] = 'auth/product/listproduct';
$route['editproduct/([0-9]+)'] = 'auth/product/editproduct';
$route['editproduct'] = 'auth/product/editproduct';
$route['deleteproduct/([0-9]+)'] = 'auth/product/deleteproduct';
$route['deleteproduct)'] = 'auth/product/deleteproduct';
     
$route['seoreporting/getprojects'] = 'seoreporting/reporting/getprojects';
$route['seoreporting/addproject'] = 'seoreporting/reporting/addproject';
$route['seoreporting/([0-9]+)/editproject'] = 'seoreporting/reporting/editproject';
$route['seoreporting/updatetaskstatus'] = 'seroreporting/reporting/updatetaskstatus';
$route['seoreporting/([0-9]+)/deleteproject'] = 'seoreporting/reporting/deleteproject';
$route['seoreporting/addjob'] = 'seoreporting/reporting/addjob';
$route['seoreporting/listjobs'] = 'seoreporting/reporting/listtask';
$route['seoreporting/([0-9]+)/viewtask'] = 'seoreporting/reporting/viewtask';
$route['seoreporting/deletejob'] = 'seoreporting/reporting/deletejob';
$route['seoreporting/([0-9]+)/viewreport'] = 'seroreporting/reporting/viewreport';


/* ==================== // NEW ROUTES ==================== */

$campaigns_io_publc_pages = "auth/CampaignIo_Pages/";
$campaigns_io_authorized_pages = "auth/CampaignIo_Pages_Authorized/";

// $route['home'] = $campaigns_io_authorized_pages . 'home';	// @note: Under Construction.

// $route['profile'] = $campaigns_io_authorized_pages . 'profile';		// @note: Under Construction.
// $route['profile/edit'] = $campaigns_io_authorized_pages . 'profile_edit';	// @note: Under Construction.

$route['domains'] = $campaigns_io_authorized_pages . 'domains';
$route['domains/save'] = $campaigns_io_authorized_pages . 'domains_save';
$route['domains/add'] = $campaigns_io_authorized_pages . 'domains_add';
$route['domains/assign'] = $campaigns_io_authorized_pages . 'domains_assign';		// @note: Under Construction.
$route['domains/([0-9]+)'] = $campaigns_io_authorized_pages . 'single_domain';
$route['domains/([0-9]+)/edit'] = $campaigns_io_authorized_pages . 'single_domain_edit';
$route['domains/([0-9]+)/delete'] = $campaigns_io_authorized_pages . 'single_domain_delete';
$route['domains/([0-9]+)/gtmetrix-rescan'] = $campaigns_io_authorized_pages . 'single_domain_gtmetrix_rescan';
$route['domains/([0-9]+)/serps'] = $campaigns_io_authorized_pages . 'single_domain_serps';
$route['domains/([0-9]+)/serps/report'] = $campaigns_io_authorized_pages . 'single_domain_serps_report';
$route['domains/([0-9]+)/serps/keyword/overall'] = $campaigns_io_authorized_pages . 'single_domain_serps_keyword_overall';
$route['domains/([0-9]+)/serps/keyword/add'] = $campaigns_io_authorized_pages . 'single_domain_serps_keyword_add';
$route['domains/([0-9]+)/serps/keyword/add/webmaster'] = $campaigns_io_authorized_pages . 'single_domain_serps_webmaster_keyword_add';
$route['domains/([0-9]+)/serps/keyword/delete'] = $campaigns_io_authorized_pages . 'single_domain_serps_keyword_delete';
$route['domains/([0-9]+)/serps/filter-keywords'] = $campaigns_io_authorized_pages . 'single_domain_serps_filter_keywords';

$route['domains/([0-9]+)/heatmaps'] = $campaigns_io_authorized_pages . 'single_domain_heatmaps';
$route['domains/([0-9]+)/heatmaps/add'] = $campaigns_io_authorized_pages . 'single_domain_heatmaps_add';
$route['domains/([0-9]+)/heatmaps/([0-9]+)'] = $campaigns_io_authorized_pages . 'single_domain_heatmaps';
$route['domains/([0-9]+)/heatmaps/([0-9]+)/edit'] = $campaigns_io_authorized_pages . 'single_domain_heatmaps_edit';
$route['domains/([0-9]+)/heatmaps/([0-9]+)/delete'] = $campaigns_io_authorized_pages . 'single_domain_heatmaps_delete';
$route['domains/([0-9]+)/heatmaps/list'] = $campaigns_io_authorized_pages . 'single_domain_heatmaps_list';

$route['domains/([0-9]+)/research'] = $campaigns_io_authorized_pages . 'single_domain_research';
$route['domains/([0-9]+)/research/compare'] = $campaigns_io_authorized_pages . 'single_domain_compare';

$route['domains/([0-9]+)/e-commerce'] = $campaigns_io_authorized_pages . 'single_domain_e_commerce';

$route['domains/([0-9]+)/analytics'] = $campaigns_io_authorized_pages . 'single_domain_analytics';
$route['domains/([0-9]+)/wordpress'] = $campaigns_io_authorized_pages . 'single_domain_wordpress';
$route['domains/([0-9]+)/wordpress/ping'] = $campaigns_io_authorized_pages . 'single_domain_wordpress_ping';
$route['domains/([0-9]+)/wordpress/login'] = $campaigns_io_authorized_pages . 'single_domain_wordpress_login';
$route['domains/([0-9]+)/wordpress/login/action'] = $campaigns_io_authorized_pages . 'single_domain_wordpress_login_action';
$route['domains/([0-9]+)/wordpress/unreachable'] = $campaigns_io_authorized_pages . 'single_domain_wordpress_unreachable';
$route['domains/([0-9]+)/wordpress/update'] = $campaigns_io_authorized_pages . 'single_domain_wordpress_update';
$route['domains/([0-9]+)/wordpress/invalid-access'] = $campaigns_io_publc_pages . 'single_domain_wordpress_invalid_access';

// $route['sub-users'] = $campaigns_io_authorized_pages . 'sub_users';		// @note: Under Construction.
// $route['sub-users/add'] = $campaigns_io_authorized_pages . 'sub_users_add';		// @note: Under Construction.
// $route['sub-users/([0-9]+)/edit'] = $campaigns_io_authorized_pages . 'single_sub_user_edit';	// @note: Under Construction.

// $route['groups'] = $campaigns_io_authorized_pages . 'groups';	// @note: Under Construction.
// $route['groups/add'] = $campaigns_io_authorized_pages . 'groups_add';	// @note: Under Construction.
// $route['groups/([0-9]+)/edit'] = $campaigns_io_authorized_pages . 'single_group_edit';	// @note: Under Construction.

/* ==================== NEW ROUTES // ==================== */