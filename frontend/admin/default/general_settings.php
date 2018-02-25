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
    <h3>General settings<small>general site settings</small></h3>
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
    <li class="">Settings</li>
    <li class="active">General settings</li>
  </ul>
</div>
<!-- /breadcrumbs line -->

<div class="post_contents">
<?php $attributes = array('class' => 'validate', 'id' => 'generalSettings'); echo form_open($this->uri->uri_string(), $attributes) ?>
<div class="block">
  <div class="form-group">
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Site name: <span class="mandatory">*</span></label>
        <?php echo form_input($website_name); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Webmaster email: <span class="mandatory">*</span></label>
        <?php echo form_input($webmaster_email); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>New user role: <span class="mandatory">*</span></label>
        <br />
        <?php 
			$user_role_atts='data-placeholder="Choose a Status" class="select-full  required" tabindex="2"';
			echo form_dropdown('new_user_group', $user_roles, $gsettings->new_user_group ,$user_role_atts); 
			?>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Show capatcha on registration: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="captcha_registration" class="styled" <?php if($gsettings->captcha_registration==1) { echo 'checked="checked"'; } ?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="captcha_registration" class="styled" <?php if($gsettings->captcha_registration!=1) { echo 'checked="checked"'; } ?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Show capatcha on password reset: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="captcha_forgetpassword" class="styled" <?php if($gsettings->captcha_forgetpassword==1) { echo 'checked="checked"'; } ?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="captcha_forgetpassword" class="styled" <?php if($gsettings->captcha_forgetpassword!=1) { echo 'checked="checked"'; } ?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Show capatcha on retrieve username: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="captcha_retrieveusername" class="styled" <?php if($gsettings->captcha_retrieveusername==1) { echo 'checked="checked"'; } ?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="captcha_retrieveusername" class="styled" <?php if($gsettings->captcha_retrieveusername!=1) { echo 'checked="checked"'; } ?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Use google recaptcha: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="use_recaptcha" class="styled" <?php if($gsettings->use_recaptcha==1) { echo 'checked="checked"'; } ?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="use_recaptcha" class="styled" <?php if($gsettings->use_recaptcha!=1) { echo 'checked="checked"'; } ?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Recaptcha Site key: </label>
        <?php echo form_input($recaptcha_sitekey); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Recaptcha Secret key: </label>
        <?php echo form_input($recaptcha_secretkey); ?> </div>
    </div>
  </div>
  <div class="form-actions text-right">
    <input type="hidden" name="task" value="settings.update" />
    <input type="submit" class="btn btn-primary" value="Submit">
  </div>
</div>
<?php echo form_close(); ?>
<?php $this->load->view(get_theme_directory().'footer'); ?>
