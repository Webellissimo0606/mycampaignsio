<!DOCTYPE html>
<html lang="<?php echo lang('language_datetime'); ?>">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/logo-fav.png">
	<title>
		<?php echo $title; ?>
	</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/material-design-icons/css/material-design-iconic-font.min.css"/>
	<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/datetimepicker/css/bootstrap-datetimepicker.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/jquery.gritter/css/jquery.gritter.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/select2/css/select2.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/ciuis.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/animate.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/bootstrap-slider/css/bootstrap-slider.css"/>
</head>
<body>
<?php $newnotification = $this->Customer_Model->newnotification();?>
<?php $readnotification = $this->Customer_Model->readnotification();?>
<?php $notifications = $this->Customer_Model->get_all_notifications();?>
<?php $settings = $this->Settings_Model->get_settings_ciuis();?>
	<!-- <div id="ciuisloader"></div> -->
	<div class="ciuis-body-wrapper ciuis-body-fixed-sidebar">
		<nav class="navbar navbar-default navbar-fixed-top ciuis-body-top-header">
			<div class="container-fluid">
				<div id="logociuis" class="navbar-header"><a id="ciuis-logo-donder" href="<?php echo base_url(); ?>panel" class="navbar-brand" style="background-image:url(<?php echo base_url('uploads/ciuis_settings/' . $settings['logo'] . '') ?>)"></a></div>

				<div class="ciuis-body-right-navbar">
					<ul class="nav navbar-nav navbar-right ciuis-body-user-nav">
						<li class="dropdown"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle"> <img src="<?php echo base_url('assets/img/customer_avatar.png'); ?>"><span class="user-name"><?php echo $_SESSION['name'] ?> <?php echo $_SESSION['surname'] ?></span></a>
							<ul role="menu" class="dropdown-menu animated fadeIn">
								<li>
									<div class="user-info">
										<div class="user-name">
											<?php echo $_SESSION['name'] ?>
											<?php echo $_SESSION['surname'] ?>
										</div>
									</div>
								</li>
								<li><a href="<?php echo base_url('customer/logout'); ?>"><span class="icon mdi mdi-power"></span><?php echo lang('logout') ?></a>
								</li>
							</ul>
						</li>
					</ul>
					<div class="crm-name">
						<span>
							<?php echo $settings['crm_name'] ?>
						</span>

					</div>
					<?php include_once APPPATH . 'views/customer/inc/navbar.php';?>
					<ul class="nav navbar-nav navbar-right ciuis-body-icons-nav">
						<li class="dropdown"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="dropdown-toggle"><span class="ion-ios-bell-outline"></span><span class="<?php echo $newnotification; ?>"></span></a>
							<ul class="dropdown-menu ciuis-body-notifications animated fadeIn">
								<li>
									<div class="title">
										<?php echo lang('notifications') ?><span class="badge"></span>
									</div>
									<div class="list">
										<div class="ciuis-body-scroller">
											<div class="content">
												<ul>
													<?php foreach ($notifications as $bil) {
	if ($bil['customerread'] == 0) {
		$biloku = '-unread';
	} else {
		$biloku = '';
	}
	?>
													<li class="notification notification<?php echo $biloku; ?>" data-id="<?php echo $bil['contactid'] ?>">
														<a href="<?php echo $bil['target'] ?>" class="cusmark">
															<div class="image"><img src="<?php echo base_url(); ?>uploads/staffavatars/<?php echo $bil['perres']; ?>" alt="">
															</div>
															<div class="notification-info">
																<?php echo $bil['detail']; ?>
																<span class="date">
																	<?php echo tes_ciuis($bil['date']); ?>
																</span>
															</div>
														</a>
													</li>
													<?php }?>
												</ul>
											</div>
										</div>
									</div>
									<div class="footer">
										<a href="#">
											<?php echo lang('all') ?>
										</a>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="ciuis-body-left-sidebar">
			<div class="left-sidebar-wrapper">
				<a href="#" class="left-sidebar-toggle">
					<?php echo lang('menu') ?>
				</a>
				<div class="left-sidebar-spacer">
					<div class="left-sidebar-scroll">
						<div class="left-sidebar-content">
							<ul class="sidebar-elements">
								<li data-toggle="tooltip" data-placement="right" data-container="body" title="<?php echo lang('dashboard') ?>"><a class="huppur" href="<?php echo base_url('customer'); ?>"><span class="ion-ios-analytics-outline"></span></a>
								</li>
								<li data-toggle="tooltip" data-placement="right" data-container="body" title="<?php echo lang('invoices') ?>"><a class="huppur" href="<?php echo base_url('customer/invoices'); ?>"><span class="ico-ciuis-invoices"></span></a>
								</li>
								<li data-toggle="tooltip" data-placement="right" data-container="body" title="<?php echo lang('proposals') ?>"><a class="huppur" href="<?php echo base_url('customer/proposals'); ?>"><span class="ico-ciuis-proposals"></span></a>
								</li>
								<li data-toggle="tooltip" data-placement="right" data-container="body" title="<?php echo lang('tickets') ?>"><a class="huppur" href="<?php echo base_url('customer/tickets'); ?>"><span class="ico-ciuis-supports"></span></a>
								</li>
								<li class="divider"></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>