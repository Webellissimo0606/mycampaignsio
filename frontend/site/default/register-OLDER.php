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
    <script src='https://www.google.com/recaptcha/api.js'></script>

</head>
<body class="account">

  <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-md-offset-3">
                    <div class="account-wall">
                    <div class="row">
                      <div class="col-md-12">
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
                      </div>
                    </div>
                          <div class="login-block">
                            <div class="row">
                                
                                <?php $attributes = array('class' => 'validate', 'id' => 'loginForm'); echo form_open($this->uri->uri_string(), $attributes) ?>

                                    <div class="form-group col-md-6 col-sm-6">
                                      <label>First name: <span class="mandatory">*</span></label>
                                      <?php echo form_input($first_name); ?></div>
                                    <div class="form-group col-md-6 col-sm-6">
                                      <label>Last name: <span class="mandatory">*</span></label>
                                      <?php echo form_input($last_name); ?></div>
                                    <div class="form-group col-md-6 col-sm-6">
                                      <label>Username: <span class="mandatory">*</span></label>
                                      <?php echo form_input($username); ?></div>
                                    <div class="form-group col-md-6 col-sm-6">
                                      <label>Email address: <span class="mandatory">*</span></label>
                                      <?php echo form_input($email); ?></div>
                                    <div class="form-group col-md-6 col-sm-6">
                                      <label>Password: <span class="mandatory">*</span></label>
                                      <?php echo form_password($password); ?></div>
                                    <div class="form-group col-md-6 col-sm-6">
                                      <label>Confirm Password: <span class="mandatory">*</span></label>
                                      <?php echo form_password($confirm_password); ?>
                                    </div>
                                    <?php if ($show_captcha) {

                                    if ($use_recaptcha) { /* Google Recaptcha Part*/?>
                                    <div class="form-group col-md-12">
                                      <div class="g-recaptcha" data-size="normal" data-sitekey="<?php echo $this->config->item('recaptcha_sitekey'); ?>" style="transform:scale(0.88);transform-origin:0;-webkit-transform:scale(0.88);transform:scale(0.88);-webkit-transform-origin:0 0;transform-origin:0 0; 0"></div>
                                    </div>
                                    <?php } else { ?>
                                    <div class="form-group">
                                      <label>Enter the code exactly as it appears:</label><br />
                                      <?php echo $captcha_html; ?></div>
                                    <div class="form-group">
                                      <label>Confirmation Code</label>
                                      <?php echo form_input($captcha); ?> <i class="icon-shield form-control-feedback"></i> </div>
                                    <?php } } ?>
                                    <div class="form-actions">
                                      <div class="col-md-6 col-lg-6 col-xs-12"><button name="button" type="submit" class="btn btn-block btn-success" id="button" value="true">Register</button></div> 
                                      <div class="col-md-6 col-lg-6 col-xs-12"> <a href="<?php echo base_url(); ?>auth/login" class="pull-right" style="margin-top: 7px">Already have an account? Sign In</a></div>
                                    </div>
                                <?php echo form_close(); ?>
                            </div>
                          </div>  

                          <div id="oauth_container" class="optional-singup">
                            <p>Or create and account with your platform of choice</p>
                            <div class="text-center">
                              <?php if($this->config->item('enable_facebook')==1) { ?>
                              <a class="btn btn-social-icon btn-facebook" href="<?php echo site_url('auth/oauth2/facebook');?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                              <?php }?>
                              <?php
                               if($this->config->item('enable_twitter')==1) { ?>
                              <a class="btn btn-social-icon btn-twitter" href="<?php echo site_url('auth/oauth/twitter');?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                              <?php }?>
                              <?php if($this->config->item('enable_gplus')==1) { ?>
                              <a class="btn btn-social-icon btn-google" href="<?php echo site_url('auth/oauth2/google');?>"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
                              <?php }?>
                              <?php if($this->config->item('enable_linkedin')==1) { ?>
                              <a class="btn btn-social-icon btn-linkedin" href="<?php echo site_url('auth/oauth2/linkedin');?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                              <?php }?>
                              <?php if($this->config->item('enable_github')==1) { ?>
                              <a class="btn btn-social-icon btn-github" href="<?php echo site_url('auth/oauth2/github');?>"><i class="fa fa-github" aria-hidden="true"></i></a>
                              <?php }?>
                              <?php if($this->config->item('enable_instagram')==1) { ?>
                              <a class="btn btn-social-icon btn-instagram" href="<?php echo site_url('auth/oauth2/instagram');?>"></a>
                              <?php }?>
                              <?php if($this->config->item('enable_microsoft')==1) { ?>
                              <a class="btn btn-social-icon btn-microsoft" href="<?php echo site_url('auth/oauth/microsoft');?>"><i class="fa fa-windows" aria-hidden="true"></i></a>
                              <?php }?>
                              <?php if($this->config->item('enable_envato')==1) { ?>
                              <a class="btn btn-social-icon btn-envato" href="<?php echo site_url('auth/oauth/envato');?>"></a>
                              <?php }?>
                              <?php if($this->config->item('enable_bitbucket')==1) { ?>
                              <a class="btn btn-social-icon btn-bitbucket" href="<?php echo site_url('auth/oauth/bitbucket');?>"><i class="fa fa-bitbucket" aria-hidden="true"></i></a>
                              <?php }?>
                              <?php if($this->config->item('enable_paypal')==1) { ?>
                              <a class="btn btn-social-icon btn-paypal" href="<?php echo site_url('auth/oauth/paypal');?>"></a>
                              <?php }?>
                              <?php if($this->config->item('enable_yandex')==1) { ?>
                              <a class="btn btn-social-icon btn-yandex" href="<?php echo site_url('auth/oauth/yandex');?>"></a>
                              <?php }?>
                            </ul>
                          </div>

                    </div>
                </div>
            </div>
        </div>
  
<?php $this->load->view(get_template_directory().'footer_new'); ?>





