<?php
	$user_session = $this->session->get_userdata();
	$user_session['email'] = isset($user_session['email']) ? $user_session['email'] : '';
	/* $profile = isset($profile) ? $profile : user_profile_data( $this->db, $user_session['user_id'] );
	$profile->email = $user_session['email'];*/
	$user_fullname =  $user_session['staffname'];
	$user_email = $user_session['email'];
	/*debug($user_session);
	die();*/

	$newnotification = $this->Notifications_Model->newnotification();
	$readnotification = $this->Notifications_Model->readnotification();
	$settings = $this->Settings_Model->get_settings_ciuis();
	$todos = $this->Trivia_Model->get_todos();
	$donetodo = $this->Trivia_Model->get_done_todos();
	$notifications = $this->Notifications_Model->get_all_notifications();

	$current_page = '';

	$user_profile_data = user_profile_data( $this->db, $user_session['user_id'] );
	$user_fullname = format_user_fullname( $user_profile_data->first_name, $user_profile_data->last_name );
	$user_gravatar = gravatar_thumb( $user_profile_data->email, 112);

	if( isset( $_COOKIE['campaigns-io']['collapse-sidebar'] ) ){
        $collapsed_sidebar = 1 === (int) $_COOKIE['campaigns-io']['collapse-sidebar'];
    }
    if( isset( $_COOKIE['campaigns-io']['collapse-author-nav'] ) ){
		// NOTE: Replaced to keep users navigation menu collapsed on pages load.
		// $collapsed_author_nav = 1 === (int) $_COOKIE['campaigns-io']['collapse-author-nav'];

		$collapsed_author_nav = 1;
    }

    $crm_assets_path = base_url('assets/crm') . '/';
	$theme_base_css_path = base_url('frontend/site/default/new-ui/assets/css') . '/';
?>
<!DOCTYPE html>

<html lang="<?php echo lang('language_datetime'); ?>">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="<?php echo $crm_assets_path; ?>img/logo-fav.png">
	<title><?php echo $title; ?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo $crm_assets_path; ?>lib/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $crm_assets_path; ?>lib/material-design-icons/css/material-design-iconic-font.min.css"/>
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo $crm_assets_path; ?>lib/datetimepicker/css/bootstrap-datetimepicker.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $crm_assets_path; ?>lib/jquery.gritter/css/jquery.gritter.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $crm_assets_path; ?>lib/select2/css/select2.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $crm_assets_path; ?>css/ciuis.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $crm_assets_path; ?>lib/material/angular-material.min.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $crm_assets_path; ?>css/animate.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $crm_assets_path; ?>lib/ionicons/css/ionicons.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo $crm_assets_path; ?>lib/bootstrap-slider/css/bootstrap-slider.css"/>


	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
	<link rel="stylesheet" type="text/css" href="<?php echo $theme_base_css_path; ?>tachyons.min.css"/>

	<link rel="stylesheet" type="text/css" href="<?php echo $theme_base_css_path; ?>general.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $theme_base_css_path; ?>sidebar.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $theme_base_css_path; ?>main-section-layout.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $theme_base_css_path; ?>styles.min.css"/>
</head>

<!-- <div id="ciuisloader"></div> -->
<!-- ////////////////////////////////////////////////////////////////////////////////////// -->

	<?php
	// TODO: Remove following lines, replaced by "default" sidebar.
	/* ?>
   	<div class="ciuis-body-left-sidebar">
		<div class="left-sidebar-wrapper">
			<a href="#" class="left-sidebar-toggle"><?php echo lang('menu') ?></a>
			<div class="left-sidebar-spacer">
				 <div class="left-sidebar-scroll">
					<div class="left-sidebar-content">
						<nav class="author-nav f6">
				            <div class="author-thumb-wrap col-xs-3" >
					            <span class="thumb relative dtc">
					                <span class="aspect-ratio--1x1 br-100">
					                    <span class="aspect-ratio--object br-100">
					                    	<img src="<?php echo base_url(); ?>uploads/staffavatars/<?php echo $this->session->userdata('staffavatar'); ?>" alt="Avatar" class="profilepic">
					                    </span>
					            	</span>
					            </span>
					        </div>
					        <div class="col-xs-8 info">
					            <div class="thumb-texts dtc v-mid">
								    <small class="dib">Welcome  </small> <br>
								    <strong class="profilename">  <?php echo $user_fullname; ?></strong>
								</div>
					        </div>
					        <a href="http://campaign.app:8090/auth/profile" title="My profile" >
					            <i class="material-icons">&#xE851;</i>
					            <span>My profile</span>
					        </a>
					        <a href="http://campaign.app:8090/assigndomain" title="Assign domains" >
					            <i class="material-icons">&#xE862</i>
					            <span>Assign domains</span>
					        </a>
					        <a href="http://campaign.app:8090/listsubuser" title="List Subuser" >
					            <i class="material-icons">&#xE7EF</i>
					            <span>List Subuser</span>
					        </a>
					        <a href="http://campaign.app:8090/listgroups" title="List Group" >
					            <i class="material-icons">&#xE241</i>
					            <span>List Group</span>
					        </a>
					        <a href="http://campaign.app:8090/domains" title="My domains" >
					            <i class="material-icons">&#xE85D</i>
					            <span>My domains</span>
					        </a>
					        <a href="http://campaign.app:8090/auth/logout" title="Logout" >
					            <i class="material-icons">&#xE879;</i>
					            <span>Logout</span>
					        </a>
					    </nav>
					    <div id="blank_nav"></div>
				        <nav class="main-nav f6 fw5">
				        	<a href="http://campaign.app:8090/businessoverview" title="Business Overview" >
					            <i class="material-icons">&#xE871;</i>
					            <span>Business Overview</span>
					        </a>
					        <a href="http://campaign.app:8090/listproduct" title="List Products" >
					            <i class="material-icons">&#xE871;</i>
					            <span>List Products</span>
					        </a>
					        <a href="#" title="Websites" >
					            <i class="material-icons">&#xE051;</i>
					            <span>Websites</span>
					        </a>
					        <a href="http://campaign.app:8090/domains" title="Domains" >
					            <i class="material-icons">&#xE85D;</i>
					            <span>Domains</span>
					        </a>
					        <a href="#" title="Uptime" >
					            <i class="material-icons">&#xE922;</i>
					            <span>Uptime</span>
					        </a>
					        <a href="#" title="Backups" >
					            <i class="material-icons">&#xE149;</i>
					            <span>Backups</span>
					        </a>
					        <a href="#" title="SEO" >
					            <i class="material-icons">&#xE880;</i>
					            <span>SEO</span>
					        </a>
					        <a href="#" title="Security" >
					            <i class="material-icons">&#xE32A;</i>
					            <span>Security</span>
					        </a>
					        <a href="#" title="Backlink manager" >
					            <i class="material-icons">&#xE157;</i>
					            <span>Backlink manager</span>
					        </a>
					        <a href="#" title="" class="collapse-expand">
					        	<i class="material-icons collapse">&#xE5C4;</i>
					        	<i class="material-icons expand">&#xE5C8;</i>
					    	</a>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php */ ?>

	<div id="campaign-io-admin" class="f6 before-init <?php echo $collapsed_sidebar ? ' collapse-sidebar' : ''; echo $collapsed_author_nav ? ' collapse-author-nav': ''; ?>">

    	<span class="behind-sidebar db fixed"></span>

		<aside id="sidebar" class="relative overflow-x-hidden">

		    <header class="sidebar-header dt w-100">

		        <div class="author-thumb-wrap">
		            <div class="thumb relative dtc">
		                <span class="aspect-ratio--1x1 br-100">
		                    <span class="aspect-ratio--object br-100" style="background-image: url('<?php echo $user_gravatar; ?>');"></span>
		                </span>
		            </div>
		            <div class="thumb-texts dtc v-mid">
		                <small class="dib fw4">Welcome</small>
		                <br/>
		                <strong class="fw7"><?php echo $user_fullname; ?></strong>
		            </div>
		        </div>

		        <?php user_navigation( $current_page, $user['parent_id'] ); ?>

		    </header>

		    <?php main_navigation( $current_page, $domain['id'] ); ?>

		</aside>

		<section id="main" class="pa3">

			<div class="ciuis-body-wrapper ciuis-body-fixed-sidebar">

				<nav class="navbar navbar-default navbar-fixed-top ciuis-body-top-header">
					<div class="container-fluid">

						<?php /* ?>
						<div id="logociuis" class="navbar-header"><a id="ciuis-logo-donder" href="<?php echo base_url(); ?>panel" class="navbar-brand" style="background-image:url(<?php echo base_url('uploads/ciuis_settings/' . $settings['logo'] . '') ?>)"></a></div>
						<?php */ ?>

						<div class="ciuis-body-right-navbar">

							<?php /* ?>
							<ul class="nav navbar-nav navbar-right ciuis-body-user-nav">
								<li class="dropdown"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle"> <img src="<?php echo base_url(); ?>uploads/staffavatars/<?php echo $this->session->userdata('staffavatar'); ?>" alt="Avatar"><span class="user-name"><?php echo $this->session->userdata('staffname'); ?></span></a>
									<ul role="menu" class="dropdown-menu animated fadeIn">
										<li><div class="user-info"><div class="user-name"><?php echo $this->session->userdata('staffname'); ?></div></div></li>
										<li><a href="<?php echo base_url(); ?>staff/staffmember/<?php echo $this->session->userdata('logged_in_staff_id'); ?>"><span class="icon ion-android-person"></span> <?php echo lang('profile') ?></a></li>
										<li><a href="<?php echo base_url(); ?>staff/edit/<?php echo $this->session->userdata('logged_in_staff_id'); ?>"><span class="icon ion-gear-a"></span> <?php echo lang('settings') ?></a></li>
										<li><a href="<?php echo base_url(); ?>login/logout"><span class="icon ion-power"></span><?php echo lang('logout') ?></a></li>
									</ul>
								</li>
							</ul>
							<?php */ ?>

							<div class="crm-name"><span><?php echo $settings['crm_name'] ?></span></div>
							<?php include_once dirname(dirname(__FILE__)) . '/inc/navbar.php';?>
							<ul class="nav navbar-nav navbar-right ciuis-body-icons-nav">
								<?php if (!if_admin) {echo '<li class="dropdown"><a href="' . base_url('settings/edit/ciuis') . '"><span class="ion-ios-gear-outline"></span></a></li>';}?>
								<li class="dropdown">
								<a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle"><span class="ion-ios-paper-outline"></span></a>
									<ul class="dropdown-menu ciuis-body-connections animated fadeIn">
										<li>
											<div class="title"><?php echo lang('todo') ?></div>
											<div class="list">
											<div class="ciuis-body-scroller">
												<div class="content ciuis-todo">
													<div class="row">
														<div class="input-group xs-mb-15">
															<input required name="tododet" type="text" class="form-control tododetail" placeholder="<?php echo lang('newtodo') ?>">
		                           							<span class="input-group-btn">
		                            						<button type="button" class="btn btn-default todopost ion-plus-round"></button>
		                            						</span>
														</div>
													</div>
													<ul class="todo-item">
													<?php foreach ($todos as $todo) {?>
														<li class="todo-alt-item" data-todoid="<?php echo $todo['id'] ?>">
															<div class="todo-c" style="display: grid;margin-top: 10px;">
																<div class="todo-item-header">
																	<div class="btn-group-sm btn-space pull-right" data-id="<?php echo $todo['id'] ?>">
																	<button type="button" class="btn btn-default btn-sm donetodo ion-checkmark"></button>
																	<button type="button" class="btn btn-default btn-sm removetodo ion-trash-a"></button>
																	</div>
																<span style="padding:5px;" class="pull-left label label-default"><?php echo _adate($todo['id']) ?></span>
																</div>
																<label data-tododone="<?php echo $todo['id'] ?>" for="done"><?php echo $todo['description'] ?></label>
															</div>
														</li>
													<?php }?>
													</ul>
												</div>
												<div class="title"><?php echo lang('donetodo') ?></div>
												<div class="content ciuis-todo">
													<ul class="todo-item-done">
													<?php foreach ($donetodo as $done) {?>
														<li class="todo-alt-item-done" data-todoid="<?php echo $done['id'] ?>">
															<div class="todo-c" style="display: grid;margin-top: 10px;">
																<div class="todo-item-header">
																	<div class="btn-group-sm btn-space pull-right" data-id="<?php echo $done['id'] ?>">
																	<button type="button" class="btn btn-default btn-sm donetodo ion-checkmark"></button>
																	<button type="button" class="btn btn-default btn-sm removetodo ion-trash-a"></button>
																</div>
																<span style="padding:5px;" class="pull-left label label-success"><?php echo _adate($done['date']) ?></span>
																</div>
																<label data-tododone="<?php echo $done['id'] ?>" for="done"><?php echo $done['description'] ?></label>
															</div>
														</li>
													<?php }?>
													</ul>
												</div>
											</div>
											</div>
											<div class="footer"> <a class="modaleventadd"><b><i class="ion-plus-round"> </i><?php echo lang('addevent') ?></b></a></div>
										</li>
									</ul>
								</li>
								<li class="dropdown"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle"><span class="ion-ios-bell-outline"></span><span class="<?php echo $newnotification; ?>"></span></a>
									<ul class="dropdown-menu ciuis-body-notifications animated fadeIn">
										<li><div class="title"><?php echo lang('notifications') ?><span class="badge"><?php echo $tbs; ?></span></div>
											<div class="list">
												<div class="ciuis-body-scroller">
													<div class="content">
														<ul>
														<?php foreach ($notifications as $bil) {if ($bil['markread'] == 0) {$biloku = '-unread';} else { $biloku = '';}?>
															<li class="notification notification<?php echo $biloku; ?>" data-id="<?php echo $bil['notifyid'] ?>">
																<a href="<?php echo $bil['target'] ?>" class="markok">
																	<div class="image"><img src="<?php echo base_url(); ?>uploads/staffavatars/<?php echo $bil['perres']; ?>" alt=""></div>
																	<div class="notification-info"><?php echo $bil['detail']; ?><span class="date"><?php echo tes_ciuis($bil['date']); ?></span></div>
																</a>
															</li>
														<?php }?>

														</ul>
													</div>
												</div>
											</div>
											<div class="footer">
												<a href="#"><?php echo lang('close') ?></a>
											</div>
										</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</nav>
				<div id="addevent" tabindex="-1" role="content" class="modal fade colored-header colored-header-success">
					<div class="modal-dialog">
						<form id="addeventform" method="post" action="<?php echo base_url(); ?>calendar/addevent">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
									<h3 class="modal-title"><?php echo lang('addevent') ?></h3>
								</div>
								<div class="col-md-12">
									<div class="col-md-12 md-mt-20">
										<div class="form-group">
											<label for="title"><?php echo lang('title'); ?></label>
											<div class="input-group"><span class="input-group-addon"><i class="ion-information"></i></span>
												<input required type="text" name="title" value="" class="form-control ci-event-title" id="title" placeholder="Event Title"/>
											</div>
										</div>
								</div>
								<div class="col-md-12 md-pt-0">
										<div class="col-md-6 md-pl-0">
					                     <div class="form-group">
					                      <label for="start"><?php echo lang('start'); ?></label>
					                        <div data-start-view="3"  data-date-format="yyyy-mm-dd - HH:ii" data-link-field="dtp_input1" class="input-group date datetimepicker"><span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
					                          <input required size="16" type="text" value="" class="form-control ci-event-start" placeholder="<?php echo date(" d.m.Y "); ?>">
					                        </div>
					                    </div>
					                    </div>
										<div class="col-md-6 md-pr-0">
					                    <div class="form-group">
					                      <label for="end"><?php echo lang('end'); ?></label>
					                        <div data-start-view="3"  data-date-format="yyyy-mm-dd - HH:ii" data-link-field="dtp_input1" class="input-group date datetimepicker"><span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
					                          <input required size="16" type="text" value="" class="form-control ci-event-end" placeholder="<?php echo date(" d.m.Y "); ?>">
					                        </div>
					                    </div>
										</div>
								</div>
								</div>
								<div class="col-md-12 md-pt-0">
									<div class="col-md-12">
										<div class="form-group">
											<label for="description"><?php echo lang('description'); ?></label>
											<div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-ios-compose-outline"></i></span>
												<textarea required name="eventdetail" class="form-control ci-event-description" id="description" placeholder="Description"></textarea>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<div class="ciuis-body-checkbox has-primary pull-left">
											<input name="public" class="ci-public-check" id="public" type="checkbox" value="1">
											<label for="public"><?php echo lang('publicevent'); ?></label>
										</div>
										<button type="button" data-dismiss="modal" class="btn btn-default modal-close"><?php echo lang('cancel'); ?></button>
										<button type="submit" class="btn btn-default postevent"><?php echo lang('save'); ?></button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>

				<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

				<script type="text/javascript">
					$(function() {
					    var navLinks = $("#nav li a");
					    navLinks.click(function () {
					        navLinks.removeClass('active');
					        $(this).addClass('active');
					    });
					});
				</script>
