<?php
global $active_content_nav_items, $current_page;
$active_content_nav_items = $active_content_nav_items ? $active_content_nav_items : '';

$valid_content_nav_pages = array('business-overview', 'business-banking', 'business-clients', 'business-suppliers', 'business-products');

if ( ! in_array( $active_content_nav_items, $valid_content_nav_pages, true ) ) {
	if( ! in_array( $current_page, $valid_content_nav_pages, true ) ){
		return;
	}
	$active_content_nav_items = $current_page;
}

$content_nav_items = array(
	array(
		'id' => 'business-overview',
		'title' => 'Overview',
		'link' => base_url('businessoverview'),
	),
	array(
		'id' => 'business-banking',
		'title' => 'Banking',
		'link' => base_url('banking'),
	),
	array(
		'id' => 'business-clients',
		'title' => 'Clients',
		'link' => base_url('listclient'),
	),
    array(
		'id' => 'business-suppliers',
		'title' => 'Suppliers',
		'link' => base_url('suppliers'),
	),
	array(
		'id' => 'business-products',
		'title' => 'Products',
		'link' => base_url('listproduct'),
	),
	

);
?>
<nav>
	<?php
	foreach ($content_nav_items as $k => $v) {?>
	        <a href="<?php echo $v['link']; ?>" title="<?php echo $v['title']; ?>" <?php echo $active_content_nav_items === $v['id'] ? ' class="active"' : ''; ?>><?php echo $v['title']; ?></a><?php
	}
	?>
</nav>
