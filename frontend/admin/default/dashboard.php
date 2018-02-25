<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_theme_directory().'header');
?>

<!-- Page container -->

<div class="page-container">

<!-- sidebar -->
<?php $this->load->view(get_theme_directory().'left_sidebar'); ?>
<!-- /sidebar --> 

<!-- Page content -->
<div class="page-content">

<!-- Page header -->
<div class="page-header">
  <div class="page-title">
    <h3>Dashboard <small>admin panel of <?php echo $this->config->item('website_name');?></small></h3>
  </div>
</div>
<!-- /page header -->

<?php if(isset($errors) && $errors!='') { ?>
<div class="alert alert-danger fade in block-inner alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?php echo $errors; ?></div>
<?php } ?>
<?php if(isset($success) && $success!='') { ?>
<div class="alert alert-success fade in block-inner alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?php echo $success; ?></div>
<?php } ?>

<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
  <ul class="breadcrumb">
    <li><a href="index.html">Home</a></li>
    <li class="active">Dashboard</li>
  </ul>
</div>
<!-- /breadcrumbs line -->

<?php if($this->ci_auth->canDo('access_backend')) { ?>
<div class="post_contents"> 
  <!-- Info blocks -->
  <ul class="info-blocks">
  
    <?php if($this->ci_auth->canDo('view_users')) { ?>
    <li class="bg-info">
      <div class="top-info"> <a href="<?php echo site_url('admin/user');?>">Users</a> <small>users management</small> </div>
      <a href="<?php echo site_url('admin/user');?>"><i class="icon-users"></i></a> <span class="bottom-info bg-primary"><?php echo $this->ci_auth->count_users();?> users</span> </li>
    <?php } ?>
    
    <?php if($this->ci_auth->canDo('edit_users')) { ?>
    <li class="bg-info">
      <div class="top-info"> <a href="<?php echo site_url('admin/user/newuser');?>">New User</a> <small>create new users</small> </div>
      <a href="<?php echo site_url('admin/user/newuser');?>"><i class="icon-user-plus"></i></a> <span class="bottom-info bg-primary">new users</span> </li>
    <?php } ?>
    
    <?php if($this->ci_auth->canDo('view_user_groups')) { ?>
    <li class="bg-info">
      <div class="top-info"> <a href="<?php echo site_url('admin/usergroups');?>">User Groups</a> <small>user groups management</small> </div>
      <a href="<?php echo site_url('admin/usergroups');?>"><i class="icon-users2"></i></a> <span class="bottom-info bg-primary"><?php echo $this->ci_auth->count_user_groups();?> user groups</span> </li>
    <?php } ?>
    
    <?php if($this->ci_auth->canDo('edit_user_groups')) { ?>
    <li class="bg-info">
      <div class="top-info"> <a href="<?php echo site_url('admin/usergroups/newusergroup');?>">New User Group</a> <small>create new user groups</small> </div>
      <a href="<?php echo site_url('admin/usergroups/newusergroup');?>"><i class="icon-user-plus3"></i></a> <span class="bottom-info bg-primary">Define permissions</span> </li>
    <?php } ?>
    
    <?php if($this->ci_auth->canDo('general_settings')) { ?>
    <li class="bg-info">
      <div class="top-info"> <a href="<?php echo site_url('admin/settings/general');?>">Settings</a> <small>general settings</small> </div>
      <a href="<?php echo site_url('admin/settings/general');?>"><i class="icon-settings"></i></a> <span class="bottom-info bg-primary">general site settings</span> </li>
    <?php } ?>
    
    <?php if($this->ci_auth->canDo('general_settings')) { ?>
    <li class="bg-info">
      <div class="top-info"> <a href="<?php echo site_url('admin/settings/social');?>">Social Login</a> <small>social login settings</small> </div>
      <a href="<?php echo site_url('admin/settings/social');?>"><i class="icon-cogs"></i></a> <span class="bottom-info bg-primary">10 social logins</span> </li>
    <?php } ?>
  </ul>
  <!-- /info blocks --> 
</div>
<?php } ?>
<?php $this->load->view(get_theme_directory().'footer'); ?>
