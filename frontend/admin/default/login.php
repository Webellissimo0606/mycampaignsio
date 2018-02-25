<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_theme_directory().'header');
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or login';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
} 

?>
<!-- Login wrapper -->
<div class="login_container">
  <h1 class="text-center page-title">Admin</h1>
  <div class="login_content well">
    <?php $attributes = array('class' => 'validate', 'id' => 'loginForm'); echo form_open($this->uri->uri_string(), $attributes) ?>
    <?php if(!empty($errors)) { if(is_array($errors)) {  ?>
    <div class="alert alert-danger  alert-dismissible fade in block-inner">
      <button class="close" data-dismiss="alert" type="button">&times;</button>
      <?php foreach($errors as $error) { echo '<p>'.$error.'</p>'; }  ?>
    </div>
    <?php } else { ?>
    <div class="alert alert-danger  alert-dismissible fade in block-inner">
      <button class="close" data-dismiss="alert" type="button">&times;</button>
      <?php echo $errors; ?></div>
    <?php } } ?>
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
    <div class="form-group has-feedback">
      <label>Username: <span class="mandatory">*</span></label>
      <?php echo form_input($login); ?> <i class="icon-users form-control-feedback"></i> </div>
    <div class="form-group has-feedback">
      <label>Password: <span class="mandatory">*</span></label>
      <?php echo form_password($password); ?> <i class="icon-lock form-control-feedback"></i> </div>
    <?php if ($show_captcha) {

		if ($use_recaptcha) { /* Google Recaptcha Part*/?>
    <div class="form-group has-feedback">
      <label>Captcha:</label>
      <div class="g-recaptcha" data-size="normal" data-sitekey="<?php echo $this->config->item('recaptcha_sitekey'); ?>" style="transform:scale(0.88);transform-origin:0;-webkit-transform:scale(0.88);transform:scale(0.88);-webkit-transform-origin:0 0;transform-origin:0 0; 0"></div>
    </div>
    <?php } else { ?>
    <div class="form-group has-feedback">
      <label>Enter the code exactly as it appears:</label>
      <?php echo $captcha_html; ?></div>
    <div class="form-group has-feedback">
      <label>Confirmation Code</label>
      <?php echo form_input($captcha); ?> <i class="icon-shield form-control-feedback"></i> </div>
    <?php } } ?>
    <div class="row form-actions">
      <div class="col-xs-6">
        <div class="checkbox checkbox-success">
          <label><?php echo form_checkbox($remember); ?> <?php echo lang('remember_me'); ?>Remember me </label>
        </div>
      </div>
      <div class="col-xs-6"> <?php echo form_button($submit); ?> </div>
    </div>
    <?php echo form_close(); ?> </div>
</div>
<?php $this->load->view(get_theme_directory().'footer'); ?>
