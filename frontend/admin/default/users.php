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
    <h3>Users <small>All <?php echo $this->config->item('website_name');?> users</small></h3>
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
  
  <?php if(isset($message) && $message!='') { ?>
  <div class="alert alert-success fade in block-inner alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <?php echo $message; ?></div>
  <?php } ?>



<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
  <ul class="breadcrumb">
    <li><a href="<?php echo site_url('admin/dashboard');?>">Home</a></li>
    <li class="active">Users</li>
  </ul>
</div>
<!-- /breadcrumbs line -->

<div class="post_contents"> 
  
  <!-- Striped and bordered datatable -->
  <div class="block">
    <div class="datatable-users">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th class="dt-head-center">#</th>
            <th>Name</th>
            <th>Email</th>
            <th class="alignCenter">Status</th>
            <th class="alignCenter">Group</th>
            <th class="alignCenter">Last Login</th>
            <th class="alignCenter">Created</th>
            <th class="alignCenter">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($users)) { foreach($users as $user) { ?>
          <tr>
            <td><?php echo $user->uid;?></td>
            <td><?php echo $user->first_name.' '.$user->last_name;?></td>
            <td><?php echo $user->email;?></td>
            <td class="text-center"><?php if($user->activated==1) { ?>
              <span class="label label-success">Active</span>
              <?php } else { ?>
              <span class="label label-danger">In Active</span>
              <?php } ?></td>
            <td align="center"><?php echo $user->name;?></td>
            <td align="center"><?php echo $user->last_login;?></td>
            <td align="center"><?php echo $user->created;?></td>
            <td><div class="table-controls">
            <?php if($this->ci_auth->canDo('edit_users') || $this->ci_auth->canDo('delete_users')) { if($user->can_edit==1 || $user->can_delete==1) { ?>
            <?php if($this->ci_auth->canDo('edit_users')) {  if($user->can_edit==1) { ?>
            <a title="" class="btn btn-info btn-icon btn-xs tip" href="<?php echo site_url('admin/user/edituser/'.$user->uid);?>" data-original-title="Print"><i class="icon-pencil"></i></a> 
            <?php } }  ?>
            <?php if($this->ci_auth->canDo('delete_users')) { if($user->can_delete==1) { ?>
            <a title="" class="btn btn-danger btn-icon btn-xs tip" href="<?php echo site_url('admin/user/deleteuser/'.$user->uid);?>" data-original-title="delete"><i class="icon-remove2"></i></a>
            <?php } } ?>
            <?php if($this->ci_auth->canDo('impersonate_users')) { if($user->can_impersonate==1) { 
                if($user->gid != 1 && $user->gid!=2){
              ?>
            <a title="" class="btn btn-danger btn-icon btn-xs tip" href="<?php echo site_url('admin/user/impersonateuser/'.$user->uid);?>" data-original-title="impersonate"><i class="icon-copy"></i></a>
            <?php } } }?>

            <?php } else { ?> -- <?php  } } else { ?>
            <a title="" class="btn btn-info btn-icon btn-xs tip" href="#" data-original-title="Print"><i class="icon-pencil"></i></a> 
            <?php } ?>
            </div></td>
          </tr>
          <?php } } else { ?>
          <tr>
            <td colspan="4" align="center">User is empty</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- /striped and bordered datatable --> 
  
</div>
<?php $this->load->view(get_theme_directory().'footer'); ?>
