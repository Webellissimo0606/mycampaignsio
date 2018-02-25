<?php

if( ! function_exists('enqueue_html_stylesheets') ){
    require_once FCPATH . 'app/helpers/campaigns-io/functions_helper.php';
}

if( ! isset( $current_page ) ){
    global $current_page;
}

$stylesheets = array(
    array( "rel" => "stylesheet", "href" => "https://fonts.googleapis.com/icon?family=Material+Icons" ),
    array( "rel" => "stylesheet", "href" => "//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" ),   // Used in 'Business Overview'.
    array( "rel" => "stylesheet", "href" => base_url( 'frontend/site/default/new-ui/assets/css/calendar.css' ) ),   // Used in 'Business Overview'.
    // array( "rel" => "stylesheet", "href" => "https://unpkg.com/tachyons@4.7.0/css/tachyons.min.css" ),   // @note: Use instead local version. Recheck!
    array( "rel" => "stylesheet", "href" => base_url( 'frontend/site/default/new-ui/assets/css/tachyons.min.css' ) ),
    array( "rel" => "stylesheet", "href" => base_url( 'frontend/site/default/new-ui/assets/css/body.min.css' ) ),
    array( "rel" => "stylesheet", "href" => base_url( 'frontend/site/default/new-ui/assets/css/forms.min.css' ) ),
    array( "rel" => "stylesheet", "href" => base_url( 'frontend/site/default/new-ui/assets/css/general.min.css' ) ),
    array( "rel" => "stylesheet", "href" => base_url( 'frontend/site/default/new-ui/assets/css/sidebar.min.css' ) ),
    array( "rel" => "stylesheet", "href" => base_url( 'frontend/site/default/new-ui/assets/css/main-section-layout.min.css' ) ),
    array( "rel" => "stylesheet", "href" => base_url( 'frontend/site/default/new-ui/assets/css/styles.min.css' ) ),
);

if( in_array( $current_page, array('login', 'register', 'forgot-password'), true ) ){
    $stylesheets[] = array( "href" => 'https://www.google.com/recaptcha/api.js' );
}

$head_top_scripts = array(
    array( "type" => "text/javascript", "src" => "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js" ), // @note New version of Chart.js [2.6.0] has bugs in IE browsers, especially using time scales.
);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo isset( $document_data ) && isset( $document_data['title'] ) ? $document_data['title'] : 'Campaigns.io' ?></title>
    <?php enqueue_html_stylesheets( $stylesheets ); ?>
    <?php enqueue_html_scripts( $head_top_scripts ); ?>
</head>

<body>