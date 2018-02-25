<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
<link rel="icon" href="<?php echo base_url();?>assets/images/favicon.ico" />
<link rel="alternate" href="<?php echo base_url();?>" hreflang="x-default" />
<title><?php echo (!empty($seo_title)) ? $seo_title .' - ' : ''; echo $this->config->item('website_name'); ?></title>
<link href='http://fonts.googleapis.com/css?family=Oswald:700,400' rel='stylesheet' type='text/css'>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap-theme.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/icons.css" />
<script src="<?php echo base_url();?>assets/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_theme();?>/css/cimembership.css" />
<link rel="stylesheet" type="text/css" href="<?php echo get_theme();?>/css/styles.css" />
<script type="text/javascript" src="<?php echo get_theme();?>/js/plugins/forms/jquery.uniform.min.js"></script>
<script type="text/javascript" src="<?php echo get_theme();?>/js/plugins/forms/validate.min.js"></script>
<script type="text/javascript" src="<?php echo get_theme();?>/js/plugins/forms/select2.min.js"></script>

<script type="text/javascript" src="<?php echo get_theme();?>/js/plugins/interface/jquery.dataTables.min.js"></script>



<script type="text/javascript" src="<?php echo get_theme();?>/js/scripts.js"></script>
</head>
<body class="<?php if ($this->ci_auth->is_logged_in()) { } else  { echo 'full-width page-condensed'; } ?>">
<!-- Navbar -->
<div class="navbar navbar-inverse" role="navigation">
  <div class="navbar-header">
    <?php if (!$this->ci_auth->is_logged_in()) { ?>
    <a class="navbar-brand" href="<?php echo site_url('admin');?>"><?php echo $this->config->item('website_name'); ?></a></div>
  <?php } else { ?>
  <a class="navbar-brand" href="<?php echo site_url('admin');?>"><?php echo $this->config->item('website_name'); ?></a> <a class="sidebar-toggle"><i class="icon-paragraph-justify2"></i></a>
  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-icons"> <span class="sr-only">Toggle navbar</span> <i class="icon-grid3"></i> </button>
  <button type="button" class="navbar-toggle offcanvas"> <span class="sr-only">Toggle navigation</span> <i class="icon-paragraph-justify2"></i> </button>
</div>
<ul class="nav navbar-nav navbar-right collapse" id="navbar-icons">
  <li class="user dropdown"> <a class="dropdown-toggle" data-toggle="dropdown"><span>Hi <?php echo $this->ci_auth->username(); ?></span> <i class="caret"></i> </a>
    <ul class="dropdown-menu dropdown-menu-right icons-right">
      <li><a href="<?php echo site_url('admin/settings/general');?>"><i class="icon-cog"></i> Settings</a></li>
      <li><a href="<?php echo site_url('admin/dashboard/logout');?>"><i class="icon-exit"></i> Logout</a></li>
    </ul>
  </li>
</ul>
<?php } ?>
</div>
<!-- /navbar --> 

