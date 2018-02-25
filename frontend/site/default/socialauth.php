<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="admin-themes-lab">
    <meta name="author" content="themes-lab">
    <!--link rel="shortcut icon" href="http://my.campaigns.io/frontend/site/default/images/favicon.png" type="image/png"-->
    <link rel="shortcut icon" href="http://my.campaigns.io/themes/site/default/images/favicon.png" type="image/png">
    <title>Campaigns.io - re-discover your website</title>
    <link href="<?php echo base_url(); ?>assets/global/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/global/css/theme.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>assets/global/css/ui.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/global/css/bootstrap-social.css" rel="stylesheet">
        
  </head>
<body class="account separate-inputs" data-page="login">
  <div class="container" id="login-block">
    <div class="row">
      <div class="col-sm-6 col-md-4 col-md-offset-4">
        <div class="account-wall">
          <div class="login-block">
            <img class="logo-center" src="/assets/images/logo-dark.png" style="margin: 10px auto 10px; display: block;" alt="Campaigns.io" />
            <h4 style="margin-bottom: 20px; text-align: center;">Complete your registration</h4>
            <?php if(!empty($errors)) { if(is_array($errors)) {  ?>
              <div class="alert alert-danger  alert-dismissible fade in block-inner">
                <button class="close" data-dismiss="alert" type="button">&times;</button>
                <?php foreach($errors as $error) { echo '<p>'.$error.'</p>'; }  ?>
              </div>
            <?php } else { ?>
              <div class="alert alert-danger  alert-dismissible fade in block-inner">
                <button class="close" data-dismiss="alert" type="button">&times;</button>
                <?php echo $errors; ?>
              </div>
            <?php } } ?>
            <?php if(isset($success) && $success!='') { ?>
              <div class="alert alert-success fade in block-inner alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $success; ?>
              </div>
            <?php } ?>
            <?php if(isset($message) && $message!='') { ?>
              <div class="alert alert-success fade in block-inner alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $message; ?>
              </div>
            <?php } ?>
            <?php $attributes = array('class' => 'validate', 'id' => 'socialloginForm'); echo form_open($this->uri->uri_string(), $attributes) ?>
              <div class="form-group has-feedback">
                <!-- <label>Username: <span class="mandatory">*</span></label> -->
                <div class="append-icon">
                  <?php echo form_input($username); ?>
                  <i class="icon-user"></i>
                </div>
              </div>
              <div class="form-group has-feedback">
                <!-- <label>Email Address: <span class="mandatory">*</span></label> -->

                <div class="append-icon">
                    <?php echo form_input($email); ?>
                    <i class="icon-envelope"></i>
                </div>
              </div>

              <div class="row form-actions">
                <div class="col-lg-12 col-md-12 col-xs-12">
                  <button name="button" type="submit" class="btn btn-block btn-success" id="button" value="true">SIGN IN</button>
                </div>
              </div>
            <?php echo form_close(); ?> 
          </div>
        </div>
      </div>
    </div>
  </div>

<?php $this->load->view(get_template_directory().'footer_new'); ?>
