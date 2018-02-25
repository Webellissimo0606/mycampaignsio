<?php /* ?>

// TODO: Doesn't need the file. Should be removed!

<?php
$theme_base_url = base_url() . 'frontend/site/default/new-ui/';

$app_wrapper_classname = 'f6 before-init';

if (isset($_COOKIE['campaigns-io'])) {
	$app_wrapper_classname .= isset($_COOKIE['campaigns-io']['collapse-author-nav']) && 1 === (int) $_COOKIE['campaigns-io']['collapse-author-nav'] ? ' collapse-author-nav' : '';
	$app_wrapper_classname .= isset($_COOKIE['campaigns-io']['collapse-sidebar']) && 1 === (int) $_COOKIE['campaigns-io']['collapse-sidebar'] ? ' collapse-sidebar' : '';
}
?>

<?php require 'html-top.php';?>

    <div id="campaign-io-admin" class="<?php echo $app_wrapper_classname; ?>">

    	<?php // require 'header.php'; ?>

	    <?php require dirname(dirname(dirname(__FILE__))) . '/new-ui/parts/sidebar.php';?>

	    <section id="main" class="pa3">

            <div class="main-content-nav">

                <?php require 'content-nav.php';?>

            </div>

<?php */ ?>