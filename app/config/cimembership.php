<?php 
/**
 * CIMembership
 * 
 * @package		Config
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Website details
|
| These details are used in emails sent by authentication library.
|--------------------------------------------------------------------------
*/

$ci = & get_instance();

$config['website_name'] = $ci->cimconfig->website_name;
$config['webmaster_email'] = $ci->cimconfig->webmaster_email;

/*
|--------------------------------------------------------------------------
| Security settings
|
| The library uses PasswordHash library for operating with hashed passwords.
| 'phpass_hash_portable' = Can passwords be dumped and exported to another server. If set to FALSE then you won't be able to use this database on another server.
| 'phpass_hash_strength' = Password hash strength.
|--------------------------------------------------------------------------
*/

$config['phpass_hash_portable'] = FALSE;
$config['phpass_hash_strength'] = 8;

/*
|--------------------------------------------------------------------------
| Registration settings
|
| 'allow_registration' = Registration is enabled or not
| 'captcha_registration' = Registration uses CAPTCHA
| 'email_activation' = Requires user to activate their account using email after registration.
| 'email_activation_expire' = Time before users who don't activate their account getting deleted from database. Default is 48 hours (60*60*24*2).
| 'email_account_details' = Email with account details is sent after registration (only when 'email_activation' is FALSE).
| 'use_username' = Username is required or not.
|
| 'username_min_length' = Min length of user's username.
| 'username_max_length' = Max length of user's username.
| 'password_min_length' = Min length of user's password.
| 'password_max_length' = Max length of user's password.
|--------------------------------------------------------------------------

*/

$config['allow_registration'] = TRUE;
$config['email_activation'] = TRUE;
$config['email_activation_expire'] = 60*60*24*2;
$config['email_account_details'] = TRUE;
$config['use_username'] = TRUE;
$config['username_min_length'] = 4;
$config['username_max_length'] = 20;
$config['password_min_length'] = 4;
$config['password_max_length'] = 20;
$config['new_user_group'] = 5;

$config['captcha_registration'] =  $ci->cimconfig->captcha_registration;
$config['captcha_forgetpassword'] =  $ci->cimconfig->captcha_forgetpassword;
$config['captcha_retrieveusername'] =  $ci->cimconfig->captcha_retrieveusername;


/*
|--------------------------------------------------------------------------
| Login settings
|
| 'login_by_username' = Username can be used to login.
| 'login_by_email' = Email can be used to login.
| You have to set at least one of 2 settings above to TRUE.
| 'login_by_username' makes sense only when 'use_username' is TRUE.
|
| 'login_record_ip' = Save in database user IP address on user login.
| 'login_record_time' = Save in database current time on user login.
|
| 'login_count_attempts' = Count failed login attempts.
| 'login_max_attempts' = Number of failed login attempts before CAPTCHA will be shown.
| 'login_attempt_expire' = Time to live for every attempt to login. Default is 24 hours (60*60*24).
|--------------------------------------------------------------------------
*/

$config['login_by_username'] = TRUE;
$config['login_by_email'] = TRUE;
$config['login_record_ip'] = TRUE;
$config['login_record_time'] = TRUE;
$config['login_count_attempts'] = TRUE;
$config['login_max_attempts'] = 5;
$config['login_attempt_expire'] = 60*60*24;

/*
|--------------------------------------------------------------------------
| Auto login settings
|
| 'autologin_cookie_name' = Auto login cookie name.
| 'autologin_cookie_life' = Auto login cookie life before expired. Default is 2 months (60*60*24*31*2).
|--------------------------------------------------------------------------
*/

$config['autologin_cookie_name'] = 'autologin';
$config['autologin_cookie_life'] = 60*60*24*31*2;

/*
|--------------------------------------------------------------------------
| Forgot password settings
|
| 'forgot_password_expire' = Time before forgot password key become invalid. Default is 15 minutes (60*15).
|--------------------------------------------------------------------------
*/

$config['forgot_password_expire'] = 60*15;

/*
|--------------------------------------------------------------------------
| Captcha
|
| You can set captcha that created by Auth library in here.
| 'captcha_path' = Directory where the catpcha will be created.
| 'captcha_fonts_path' = Font in this directory will be used when creating captcha.
| 'captcha_font_size' = Font size when writing text to captcha. Leave blank for random font size.
| 'captcha_grid' = Show grid in created captcha.
| 'captcha_expire' = Life time of created captcha before expired, default is 3 minutes (180 seconds).
| 'captcha_case_sensitive' = Captcha case sensitive or not.
|--------------------------------------------------------------------------

*/

$config['captcha_path'] = 'assets/captcha/';
$config['captcha_fonts_path'] = 'assets/captcha/fonts/3.ttf';
$config['captcha_width'] = 170;
$config['captcha_height'] = 40;
$config['captcha_font_size'] = 15;
$config['captcha_grid'] = FALSE;
$config['captcha_expire'] = 180;
$config['captcha_case_sensitive'] = FALSE;

/*
|--------------------------------------------------------------------------
| reCAPTCHA
|
| 'use_recaptcha' = Use reCAPTCHA instead of common captcha
| You can get reCAPTCHA keys by registering at http://recaptcha.net
|--------------------------------------------------------------------------
*/

$config['use_recaptcha'] =  $ci->cimconfig->use_recaptcha;
$config['recaptcha_sitekey'] = $ci->cimconfig->recaptcha_sitekey;
$config['recaptcha_secretkey'] = $ci->cimconfig->recaptcha_secretkey;

/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/libraries/email.html
|
*/

$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";


/*
 * Countries List
 * 
 */

 $config['country_list'] = array(
	"AF"=>"Afghanistan",
	"AL"=>"Albania",
	"DZ"=>"Algeria",
	"AD"=>"Andorra",
	"AO"=>"Angola",
	"AI"=>"Anguilla",
	"AQ"=>"Antarctica",
	"AG"=>"Antigua and Barbuda",
	"AR"=>"Argentina",
	"AM"=>"Armenia",
	"AW"=>"Aruba",
	"AU"=>"Australia",
	"AT"=>"Austria",
	"AZ"=>"Azerbaijan",
	"BS"=>"Bahamas",
	"BH"=>"Bahrain",
	"BD"=>"Bangladesh",
	"BB"=>"Barbados",
	"BY"=>"Belarus",
	"BE"=>"Belgium",
	"BZ"=>"Belize",
	"BJ"=>"Benin",
	"BM"=>"Bermuda",
	"BT"=>"Bhutan",
	"BO"=>"Bolivia",
	"BA"=>"Bosnia and Herzegovina",
	"BW"=>"Botswana",
	"BR"=>"Brazil",
	"IO"=>"British Indian Ocean",
	"BN"=>"Brunei",
	"BG"=>"Bulgaria",
	"BF"=>"Burkina Faso",
	"BI"=>"Burundi",
	"KH"=>"Cambodia",
	"CM"=>"Cameroon",
	"CA"=>"Canada",
	"CV"=>"Cape Verde",
	"KY"=>"Cayman Islands",
	"CF"=>"Central African Republic",
	"TD"=>"Chad",
	"CL"=>"Chile",
	"CN"=>"China",
	"CX"=>"Christmas Island",
	"CC"=>"Cocos (Keeling) Islands",
	"CO"=>"Colombia",
	"KM"=>"Comoros",
	"CD"=>"Congo, Democratic Republic of the",
	"CG"=>"Congo, Republic of the",
	"CK"=>"Cook Islands",
	"CR"=>"Costa Rica",
	"HR"=>"Croatia",
	"CY"=>"Cyprus",
	"CZ"=>"Czech Republic",
	"DK"=>"Denmark",
	"DJ"=>"Djibouti",
	"DM"=>"Dominica",
	"DO"=>"Dominican Republic",
	"TL"=>"East Timor",
	"EC"=>"Ecuador",
	"EG"=>"Egypt",
	"SV"=>"El Salvador",
	"GQ"=>"Equatorial Guinea",
	"ER"=>"Eritrea",
	"EE"=>"Estonia",
	"ET"=>"Ethiopia",
	"FK"=>"Falkland Islands (Malvinas)",
	"FO"=>"Faroe Islands",
	"FJ"=>"Fiji",
	"FI"=>"Finland",
	"FR"=>"France",
	"GF"=>"French Guiana",
	"PF"=>"French Polynesia",
	"GA"=>"Gabon",
	"GM"=>"Gambia",
	"GE"=>"Georgia",
	"DE"=>"Germany",
	"GH"=>"Ghana",
	"GI"=>"Gibraltar",
	"GR"=>"Greece",
	"GL"=>"Greenland",
	"GD"=>"Grenada",
	"GP"=>"Guadeloupe",
	"GT"=>"Guatemala",
	"GN"=>"Guinea",
	"GW"=>"Guinea-Bissau",
	"GY"=>"Guyana",
	"HT"=>"Haiti",
	"HN"=>"Honduras",
	"HK"=>"Hong Kong",
	"HU"=>"Hungary",
	"IS"=>"Iceland",
	"IN"=>"India",
	"ID"=>"Indonesia",
	"IE"=>"Ireland",
	"IL"=>"Israel",
	"IT"=>"Italy",
	"CI"=>"Ivory Coast (C&ocirc;te d\'Ivoire)",
	"JM"=>"Jamaica",
	"JP"=>"Japan",
	"JO"=>"Jordan",
	"KZ"=>"Kazakhstan",
	"KE"=>"Kenya",
	"KI"=>"Kiribati",
	"KR"=>"Korea, South",
	"KW"=>"Kuwait",
	"KG"=>"Kyrgyzstan",
	"LA"=>"Laos",
	"LV"=>"Latvia",
	"LB"=>"Lebanon",
	"LS"=>"Lesotho",
	"LI"=>"Liechtenstein",
	"LT"=>"Lithuania",
	"LU"=>"Luxembourg",
	"MO"=>"Macau",
	"MK"=>"Macedonia, Republic of",
	"MG"=>"Madagascar",
	"MW"=>"Malawi",
	"MY"=>"Malaysia",
	"MV"=>"Maldives",
	"ML"=>"Mali",
	"MT"=>"Malta",
	"MH"=>"Marshall Islands",
	"MQ"=>"Martinique",
	"MR"=>"Mauritania",
	"MU"=>"Mauritius",
	"YT"=>"Mayotte",
	"MX"=>"Mexico",
	"FM"=>"Micronesia",
	"MD"=>"Moldova",
	"MC"=>"Monaco",
	"MN"=>"Mongolia",
	"ME"=>"Montenegro",
	"MS"=>"Montserrat",
	"MA"=>"Morocco",
	"MZ"=>"Mozambique",
	"NA"=>"Namibia",
	"NR"=>"Nauru",
	"NP"=>"Nepal",
	"NL"=>"Netherlands",
	"AN"=>"Netherlands Antilles",
	"NC"=>"New Caledonia",
	"NZ"=>"New Zealand",
	"NI"=>"Nicaragua",
	"NE"=>"Niger",
	"NG"=>"Nigeria",
	"NU"=>"Niue",
	"NF"=>"Norfolk Island",
	"NO"=>"Norway",
	"OM"=>"Oman",
	"PK"=>"Pakistan",
	"PS"=>"Palestinian Territory",
	"PA"=>"Panama",
	"PG"=>"Papua New Guinea",
	"PY"=>"Paraguay",
	"PE"=>"Peru",
	"PH"=>"Philippines",
	"PN"=>"Pitcairn Island",
	"PL"=>"Poland",
	"PT"=>"Portugal",
	"QA"=>"Qatar",
	"RE"=>"R&eacute;union",
	"RO"=>"Romania",
	"RU"=>"Russia",
	"RW"=>"Rwanda",
	"SH"=>"Saint Helena",
	"KN"=>"Saint Kitts and Nevis",
	"LC"=>"Saint Lucia",
	"PM"=>"Saint Pierre and Miquelon",
	"VC"=>"Saint Vincent and the Grenadines",
	"WS"=>"Samoa",
	"SM"=>"San Marino",
	"ST"=>"S&atilde;o Tome and Principe",
	"SA"=>"Saudi Arabia",
	"SN"=>"Senegal",
	"RS"=>"Serbia",
	"CS"=>"Serbia and Montenegro",
	"SC"=>"Seychelles",
	"SL"=>"Sierra Leon",
	"SG"=>"Singapore",
	"SK"=>"Slovakia",
	"SI"=>"Slovenia",
	"SB"=>"Solomon Islands",
	"SO"=>"Somalia",
	"ZA"=>"South Africa",
	"GS"=>"South Georgia and the South Sandwich Islands",
	"ES"=>"Spain",
	"LK"=>"Sri Lanka",
	"SR"=>"Suriname",
	"SJ"=>"Svalbard and Jan Mayen",
	"SZ"=>"Swaziland",
	"SE"=>"Sweden",
	"CH"=>"Switzerland",
	"TW"=>"Taiwan",
	"TJ"=>"Tajikistan",
	"TZ"=>"Tanzania",
	"TH"=>"Thailand",
	"TG"=>"Togo",
	"TK"=>"Tokelau",
	"TO"=>"Tonga",
	"TT"=>"Trinidad and Tobago",
	"TN"=>"Tunisia",
	"TR"=>"Turkey",
	"TM"=>"Turkmenistan",
	"TC"=>"Turks and Caicos Islands",
	"TV"=>"Tuvalu",
	"UG"=>"Uganda",
	"UA"=>"Ukraine",
	"AE"=>"United Arab Emirates",
	"GB"=>"United Kingdom",
	"US"=>"United States",
	"UM"=>"United States Minor Outlying Islands",
	"UY"=>"Uruguay",
	"UZ"=>"Uzbekistan",
	"VU"=>"Vanuatu",
	"VA"=>"Vatican City",
	"VE"=>"Venezuela",
	"VN"=>"Vietnam",
	"VG"=>"Virgin Islands, British",
	"WF"=>"Wallis and Futuna",
	"EH"=>"Western Sahara",
	"YE"=>"Yemen",
	"ZM"=>"Zambia",
	"ZW"=>"Zimbabwe");

/* enable/disable solcial logins */
$config['enable_facebook'] = $ci->cimconfig->enable_facebook; 
$config['enable_twitter'] = $ci->cimconfig->enable_twitter; 
$config['enable_gplus'] = $ci->cimconfig->enable_gplus; 
$config['enable_linkedin'] = $ci->cimconfig->enable_linkedin; 
$config['enable_github'] = $ci->cimconfig->enable_github; 
$config['enable_instagram'] = $ci->cimconfig->enable_instagram; 
$config['enable_microsoft'] = $ci->cimconfig->enable_microsoft; 
$config['enable_envato'] = $ci->cimconfig->enable_envato; 
$config['enable_paypal'] = $ci->cimconfig->enable_paypal; 
$config['enable_yandex'] = $ci->cimconfig->enable_yandex; 
$config['enable_bitbucket'] = $ci->cimconfig->enable_bitbucket; 

/* Facebook Oauth Configuration */
$config['facebook_app_id'] = $ci->cimconfig->facebook_app_id; 
$config['facebook_app_secret'] = $ci->cimconfig->facebook_app_secret; 

/* Twitter Oauth Configuration */
$config['tw_consumer_key'] = $ci->cimconfig->tw_consumer_key; 
$config['tw_consumer_secret'] = $ci->cimconfig->tw_consumer_secret; 

/* Google Oauth Configuration */
$config['google_app_id'] = $ci->cimconfig->google_app_id; 
$config['google_app_secret'] = $ci->cimconfig->google_app_secret; 

/* Linkedin Configuration */
$config['linkedin_client_id'] = $ci->cimconfig->linkedin_client_id; 
$config['linkedin_client_secret'] = $ci->cimconfig->linkedin_client_secret; 

/* Github Configuration */
$config['github_client_id'] = $ci->cimconfig->github_client_id; 
$config['github_client_secret'] = $ci->cimconfig->github_client_secret; 

/* Instagram Configuration */
$config['instagram_client_id'] = $ci->cimconfig->instagram_client_id; 
$config['instagram_client_secret'] = $ci->cimconfig->instagram_client_secret; 

/* Microsoft Configuration */
$config['microsoft_client_id'] = $ci->cimconfig->microsoft_client_id; 
$config['microsoft_client_secret'] = $ci->cimconfig->microsoft_client_secret; 

/* Envato Configuration */
$config['envato_client_id'] = $ci->cimconfig->envato_client_id;
$config['envato_client_secret'] = $ci->cimconfig->envato_client_secret; 

/* Paypal Configuration */
$config['paypal_client_id'] = $ci->cimconfig->paypal_client_id; 
$config['paypal_client_secret'] = $ci->cimconfig->paypal_client_secret; 

/* Yandex configuration */
$config['yandex_client_id'] = $ci->cimconfig->yandex_client_id; 
$config['yandex_client_secret'] = $ci->cimconfig->yandex_client_secret; 

/* Bitbucket configuration */
$config['bitbucket_key'] = $ci->cimconfig->bitbucket_key; 
$config['bitbucket_secret'] = $ci->cimconfig->bitbucket_secret; 

/* End of file cimembership.php */
/* Location: ./application/config/cimembership.php */