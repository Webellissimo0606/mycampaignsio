<?php /* ?>


// TODO: Doesn't need the file. Should be removed!


<?php
global $active_content_nav_items;
$active_content_nav_items = $active_content_nav_items ? $active_content_nav_items : '';

$valid_content_nav_pages = array('overview');

if (!in_array($active_content_nav_items, $valid_content_nav_pages, true)) {
	return;
}

$session_user_data = $this->session->get_userdata();

$domain_id = $session_user_data['domainId'];

$content_nav_items = array(
	array(
		'id' => 'overview',
		'title' => 'Business Overview',
		// 'link' => base_url('auth/dashboard/' . $domain_id),
		'link' => '/businessoverview',
	),

);

$query = "SELECT * FROM user_domain ud JOIN domains d ON d.id=ud.domain_id WHERE ud.user_id='" . $session_user_data['user_id'] . "' ORDER BY d.id DESC;";
$query = $this->db->query($query);
$user_domains = $query->result_array();

?>
<nav>
	<?php
foreach ($content_nav_items as $k => $v) {?>
        <a href="<?php echo $v['link']; ?>" title="<?php echo $v['title']; ?>" <?php echo $active_content_nav_items === $v['id'] ? ' class="active"' : ''; ?>><?php echo $v['title']; ?></a><?php
}
?>
</nav>

<?php */ ?>
