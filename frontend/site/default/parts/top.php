<?php
$theme_base_url = base_url() . 'frontend/site/default/';

$app_wrapper_classname = 'f6 before-init';

if( isset( $_COOKIE['campaigns-io'] ) ){	
	// NOTE: Replaced to keep users navigation menu collapsed on pages load.
	// $app_wrapper_classname .= isset( $_COOKIE['campaigns-io']['collapse-author-nav'] ) && 1 === (int) $_COOKIE['campaigns-io']['collapse-author-nav'] ? ' collapse-author-nav' : '';
	$app_wrapper_classname .= ' collapse-author-nav';
	
	$app_wrapper_classname .= isset( $_COOKIE['campaigns-io']['collapse-sidebar'] ) && 1 === (int) $_COOKIE['campaigns-io']['collapse-sidebar'] ? ' collapse-sidebar' : '';
}
?>

<?php require 'html-top.php'; ?>
    
    <div id="campaign-io-admin" class="<?php echo $app_wrapper_classname; ?>">

    	<?php /*require 'header.php';*/ ?>

	    <?php require 'sidebar.php'; ?>

	    <section id="main" class="pa3">
	    	
            <div class="main-content-nav cf">
                <a href="<?php echo base_url('auth/home'); ?>" title="campaigns.io" class="content-logo dib pa2"><img src="<?php echo $theme_base_url; ?>images/campaigns-io-logo.png" alt=""/></a>
            </div>