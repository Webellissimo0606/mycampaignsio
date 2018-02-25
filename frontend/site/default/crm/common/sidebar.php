<?php
if (!isset($profile)) {
	$user_session = $this->session->get_userdata();
	$user_session['email'] = isset($user_session['email']) ? $user_session['email'] : '';
	$profile = isset($profile) ? $profile : user_profile_data($this->db, $user_session['user_id']);
	$profile->email = isset($user_session['email']) ? $user_session['email'] : '';
	unset($user_session);
}
$user_fullname = ($profile->first_name ? $profile->first_name : '') . ' ' . ($profile->last_name ? $profile->last_name : '');
$user_fullname = '' !== $user_fullname ? $user_fullname : '--';
?>

<span class="behind-sidebar db fixed"></span>

<aside id="sidebar" class="relative overflow-x-hidden">

    <header class="sidebar-header dt w-100">

        <div class="author-thumb-wrap">
            <div class="thumb relative dtc">
                <span class="aspect-ratio--1x1 br-100">
                    <span class="aspect-ratio--object br-100" style="background-image: url(<?php echo gravatar_thumb($profile->email, 112); ?>);"></span>
                </span>
            </div>
            <div class="thumb-texts dtc v-mid">
                <small class="dib">Welcome</small>
                <br/>
                <strong><?php echo $user_fullname; ?></strong>
            </div>
        </div>

        <?php require 'author-nav.php';?>

    </header>

    <?php require 'main-nav.php';?>
</aside>