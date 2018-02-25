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
    <h3>New User Group<small><?php echo $this->config->item('website_name');?> user group</small></h3>
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
<div class="alert alert-info fade in block-inner alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?php echo $message; ?></div>
<?php } ?>

<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
  <ul class="breadcrumb">
    <li><a href="<?php echo site_url('admin/dashboard');?>">Home</a></li>
    <li class="">User Groups</li>
    <li class="active">New User Group</li>
  </ul>
</div>
<!-- /breadcrumbs line -->

<div class="post_contents">
  <?php $attributes = array('class' => 'validate form-horizontal', 'id' => 'newUserGroup'); echo form_open($this->uri->uri_string(), $attributes) ?>
  <div class="block">
    <div class="form-group">
      <label class="col-sm-2 control-label">Group Name: <span class="mandatory">*</span></label>
      <div class="col-sm-6"> <?php echo form_input($group_name); ?> </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Status: <span class="mandatory">*</span></label>
      <div class="col-sm-10">
        <?php 
			$userstatus = array ( ''  => '', '1'    => 'Active', '2'   => 'Inactive');
			$status_atts='data-placeholder="Choose a Status" class="select required" tabindex="2"';
			echo form_dropdown('status', $userstatus, $this->form_validation->set_value('status') ,$status_atts); 
			?>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-12 control-label">User Role Permissions:</label>
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h6 class="panel-title"><i class="icon-bubble4"></i> Permissions</h6>
          </div>
          <div class="panel-body">
            <div class="block-inner">
              
              <?php if($this->ci_auth->canDo('access_backend')) { ?>
        <div class="permission_group">
                <label class="checkbox-inline checkbox-info">
                <div class="checker">
                <?php echo form_checkbox($access_backend); ?>
                  </div>Can access Backend</label>
                </div>
                <?php } ?>
           
            <?php if($this->ci_auth->canDo('view_users')) { ?>
           <div class="permission_group">
                <label class="checkbox-inline checkbox-info">
                <div class="checker">
                <?php echo form_checkbox($view_users); ?>
                  </div>View Users</label>
                </div>
                <?php } ?>
                
                 <?php if($this->ci_auth->canDo('edit_users')) { ?>
                <div class="permission_group">
                <label class="checkbox-inline checkbox-info">
                <div class="checker">
                <?php echo form_checkbox($edit_users); ?>
                  </div>Create/Edit Users</label>
                </div>
                <?php } ?>

                <?php if($show_delete_user==1) { ?>
                 <?php if($this->ci_auth->canDo('delete_users')) { ?>
                <div class="permission_group">
                <label class="checkbox-inline checkbox-info">
                <div class="checker">
                <?php echo form_checkbox($delete_users); ?>
                  </div>Delete Users</label>
                </div>
                <?php } ?>
                <?php } ?>
                
                 <?php if($this->ci_auth->canDo('view_user_groups')) { ?>
                <div class="permission_group">
                <label class="checkbox-inline checkbox-info">
                <div class="checker">
                <?php echo form_checkbox($view_user_groups); ?>
                  </div>View User Groups</label>
                </div>
                <?php } ?>

                 <?php if($this->ci_auth->canDo('edit_user_groups')) { ?>
                <div class="permission_group">
                <label class="checkbox-inline checkbox-info">
                <div class="checker">
                <?php echo form_checkbox($edit_user_groups); ?>
                  </div>Create/Edit User Groups</label>
                </div>
                <?php } ?>
                
                <?php if($show_delete_user_group==1) { ?>
                 <?php if($this->ci_auth->canDo('delete_user_groups')) { ?>
                <div class="permission_group">
                <label class="checkbox-inline checkbox-info">
                <div class="checker">
                <?php echo form_checkbox($delete_user_groups); ?>
                  </div>Delete User Groups</label>
                </div>    
                <?php } ?>
                <?php } ?>
                
                 <?php if($this->ci_auth->canDo('general_settings')) { ?>
                <div class="permission_group">
                <label class="checkbox-inline checkbox-info">
                <div class="checker">
                <?php echo form_checkbox($general_settings); ?>
                  </div>Access Settings</label>
                </div>    
                <?php } ?>

                <div class="permission_group">
                <label class="checkbox-inline checkbox-info">
                <div class="checker">
                <?php echo form_checkbox($login_to_frontend); ?>
                  </div>Login to frontend</label>
                </div>    

              </div>
          </div>
        </div>
      </div>
    </div>
    <div class="form-actions text-right">
      <input type="submit" class="btn btn-primary" value="Submit">
    </div>
  </div>
  <?php echo form_close(); ?> </div>
<?php $this->load->view(get_theme_directory().'footer'); ?>
