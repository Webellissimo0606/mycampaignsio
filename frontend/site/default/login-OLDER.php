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
  <body class="account separate-inputs" data-page="login">


    <!-- BEGIN LOGIN BOX -->
    <div class="container" id="login-block">
        <div class="row">

        <?php 
        if ($login_by_username AND $login_by_email) {
          $login_label = 'Email or login';
        } else if ($login_by_username) {
          $login_label = 'Login';
        } else {
          $login_label = 'Email';
        } 
        ?>
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <div class="account-wall">

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
                      <?php echo $success; ?>
                    </div>
                    <?php } ?>
                    <?php if(isset($message) && $message!='') { ?>
                    <div class="alert alert-success fade in block-inner alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <?php echo $message; ?>
                    </div>
                    <?php } ?>

                      <div class="login-block">
                        <img class="logo-center" src="/assets/images/logo-dark.png" style="margin: 10px auto 20px; display: block;" alt="Campaigns.io" />

                        <?php $attributes = array('class' => 'form-signin', 'id' => 'loginForm'); echo form_open($this->uri->uri_string(), $attributes) ?>
                        <div class="append-icon">
                            <?php echo form_input($login); ?>
                            <i class="icon-user"></i>
                        </div>
                        <div class="append-icon">
                            <?php echo form_password($password); ?>
                            <i class="icon-lock"></i>
                        </div>

                        <?php if ($show_captcha) {

                        if ($use_recaptcha) { /* Google Recaptcha Part*/?>
                          <div class="form-group has-feedback">
                            <label>Captcha:</label>
                            <div class="g-recaptcha" data-size="normal" data-sitekey="<?php echo $this->config->item('recaptcha_sitekey'); ?>" style="transform:scale(0.88);transform-origin:0;-webkit-transform:scale(0.88);transform:scale(0.88);-webkit-transform-origin:0 0;transform-origin:0 0; 0">
                            </div>
                          </div>
                        <?php } else { ?>

                          <div class="form-group has-feedback">
                            <label>Enter the code exactly as it appears:</label>
                            <?php echo $captcha_html; ?>
                          </div>
                          <div class="form-group has-feedback">
                            <label>Confirmation Code</label>
                            <?php echo form_input($captcha); ?> <i class="icon-shield form-control-feedback"></i>
                          </div>
                        <?php } }?>

                        <div class="row form-actions">
                          <div class="col-lg-6 col-md-6 col-xs-6">
                            <div class="checkbox checkbox-success">
                              <label><?php echo form_checkbox($remember); ?> <?php echo lang('remember_me'); ?>Remember me </label>
                            </div>
                          </div>

                          <div class="col-lg-6 m-b-10">
                            <p class="pull-right" style="margin-top: 7px"><a href="<?php echo base_url(); ?>auth/register">New here? Sign up</a>
                            <br>
                            <p class="pull-right"><a href="<?php echo base_url(); ?>auth/forgotpassword"> Forgot password?</a></p>
                            </p>
                          </div>
                          <div class="col-lg-12 col-md-12 col-xs-12">
                            <button name="button" type="submit" class="btn btn-block btn-success" id="button" value="true">SIGN IN</button>
                          </div>
                        </div>
                      
                      </div>    
                        <div class="social-btn">
                            <?php if($this->config->item('enable_facebook')==1) { ?>
                            <a href="<?php echo site_url('auth/oauth2/facebook');?>" class="btn btn-block btn-social btn-facebook"><i class="fa fa-facebook"></i>Login with Facebook</a>
                            <?php }?>
                            
                            <?php if($this->config->item('enable_twitter')==1) { ?>
                            <a href="<?php echo site_url('auth/oauth/twitter');?>" class="btn btn-block btn-social btn-twitter"><i class="fa fa-twitter"></i>Login with Twitter</a>
                            <?php }?>

                            <?php if($this->config->item('enable_gplus')==1) { ?>
                            <a href="<?php echo site_url('auth/oauth2/google');?>" class="btn btn-block btn-social btn-google"><i class="fa fa-google"></i>Login with Google</a>
                            <?php }?>

                        </div>
                    <?php echo form_close(); ?> 

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
