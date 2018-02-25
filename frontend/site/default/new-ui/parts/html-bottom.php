<?php

if( ! isset( $current_page ) ){
    global $current_page;
}

$body_bottom_scripts = array(
	array( "language" => "JavaScript", "type" => "text/javascript", "src" => "http://www.geoplugin.net/javascript.gp" ),
	array("type" => "text/javascript", "src" => base_url('frontend/site/default/new-ui/assets/js/jquery-3.2.1.min.js') ),
	array("type" => "text/javascript", "src" => base_url('frontend/site/default/new-ui/assets/js/jquery-migrate-1.4.1.min.js') ),
	array("type" => "text/javascript", "src" => base_url('frontend/site/default/new-ui/assets/js/Vanilla-DataTables/dist/vanilla-dataTables.min.js') ),
	array("type" => "text/javascript", "src" => base_url('frontend/site/default/new-ui/assets/js/Choices/assets/scripts/dist/choices.min.js') ),
	array("type" => "text/javascript", "src" => "//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js" ),	// Used in 'Business 
	array("type" => "text/javascript", "src" => "//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js" ),	// Used in 'Business 
	array("type" => "text/javascript", "src" => base_url('frontend/site/default/new-ui/assets/js/calendar.js') ),	// Used in 'Business
);

if( 'business-overview' === $current_page ){
	$body_bottom_scripts[] = array( "type" => "text/javascript", "src" => base_url('frontend/site/default/new-ui/assets/js/Campaings-io-pages/business-overview.js') );
}

if( 'view-serps' === $current_page ){
	$body_bottom_scripts[] = array( "type" => "text/javascript", "src" => base_url('frontend/site/default/new-ui/assets/js/Campaings-io-pages/view-serp.js') );
}

if( 'seoreporting-project' === $current_page || 'seoreporting-task' === $current_page){
	$body_bottom_scripts[] = array( "type" => "text/javascript", "src" => base_url('frontend/site/default/new-ui/assets/js/SeoReporting/SeoReporting.js') );
}

// General Campaigns.io scripts file.
$body_bottom_scripts[] = array("type" => "text/javascript", "src" => base_url('frontend/site/default/new-ui/assets/js/campaigns-io-script.js') );
?>

<?php echo form_hidden( 'current_campaigns_page', $current_page ); ?>

<?php enqueue_html_scripts( $body_bottom_scripts ); ?>

</body>

</html>
