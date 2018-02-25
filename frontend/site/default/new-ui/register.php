<?php
global $current_page;
$current_page = "register";

$theme_base_url = base_url() . 'frontend/site/default/new-ui/';

$attrs = array(
    'form' => array('class' => 'login-page-form relative dib center white')
);

$enabled_register = array(
    'facebook' => 1 === (int) $this->config->item('enable_facebook'),
    'twitter' => 1 === (int) $this->config->item('enable_twitter'),
    'gplus' => 1 === (int) $this->config->item('enable_gplus'),
    'linkedin' => 1 === (int) $this->config->item('enable_linkedin'),
    'github' => 1 === (int) $this->config->item('enable_github'),
    'instagram' => 1 === (int) $this->config->item('enable_instagram'),
    'microsoft' => 1 === (int) $this->config->item('enable_microsoft'),
    'envato' => 1 === (int) $this->config->item('enable_envato'),
    'bitbucket' => 1 === (int) $this->config->item('enable_bitbucket'),
    'paypal' => 1 === (int) $this->config->item('enable_paypal'),
    'yandex' => 1 === (int) $this->config->item('enable_yandex'),
);

require 'parts/html-top.php';
?>
<div id="campaign-io-admin" class="f6">

	<section id="login-page" class="w-100 min-h-100 absolute dt pa3">
        
        <div class="dtc v-mid tc">

            <?php echo form_open($this->uri->uri_string(), $attrs['form']); ?>

                <div class="login-logo">
                    <img src="<?php echo $theme_base_url; ?>img/campaigns-io-logo.png" alt="" />
                </div>
                <br/><br/>
                
                <?php login_form_messages(
                    isset($errors) ? ( is_array($errors) ? $errors : array($errors) ) : array(),
                    isset($success) ? $success : '',
                    isset($message) ? $message : ''
                ); ?>

                <br/>
                <?php echo form_input($first_name); ?>
                <br/>
                <?php echo form_input($last_name); ?>
                <br/>
                <?php echo form_input($username); ?>
                <br/>
                <?php echo form_input($email); ?>
                <br/>
                <?php echo form_password($password); ?>
                <br/>
                <?php echo form_password($confirm_password); ?>
                <br/><br/>

                <?php if ( $show_captcha ) {
                    login_form_captcha($use_recaptcha, $this->config, $captcha_html, $captcha );
                } ?>

                <button type="submit" name="" class="btn btn-color fw6 f3" value="true">REGISTER</button>
                <br/><br/><br/>
                <a href="<?php echo base_url(); ?>auth/login" title="" class="dib btn btn-lines fw5 f7">SIGN IN</a>
                <br/><br/><br/>
            <?php echo form_close(); ?>
            
            <br/>

            <div class="alt-register-wrap">
                <p>Or create an account with your platform of choice</p>
                <div class="alt-register-list">
                    <?php if( $enabled_register['facebook'] ) { ?>
                    <a class="register-btn facebook" href="<?php echo site_url('auth/oauth2/facebook');?>" title="Facebook">
                        <img src="<?php echo $theme_base_url; ?>assets/svg/facebook.svg" alt="" />
                    </a>
                    <?php } ?>
                    <?php if( $enabled_register['twitter'] ) { ?>
                    <a class="register-btn twitter" href="<?php echo site_url('auth/oauth/twitter');?>" title="Twitter">
                        <img src="<?php echo $theme_base_url; ?>assets/svg/twitter.svg" alt="" />
                    </a>
                    <?php } ?>
                    <?php if( $enabled_register['gplus'] ) { ?>
                    <a class="register-btn google-plus" href="<?php echo site_url('auth/oauth2/google');?>" title="Google+">
                        <img src="<?php echo $theme_base_url; ?>assets/svg/googleplus.svg" alt="" />
                    </a>
                    <?php } ?>
                    <?php if( $enabled_register['linkedin'] ) { ?>
                    <a class="register-btn linkedin" href="<?php echo site_url('auth/oauth2/linkedin');?>" title="LinkedIn">
                        <img src="<?php echo $theme_base_url; ?>assets/svg/linkedin.svg" alt="" />
                    </a>
                    <?php } ?>
                    <?php if( $enabled_register['github'] ) { ?>
                    <a class="register-btn github" href="<?php echo site_url('auth/oauth2/github');?>" title="Github">
                        <img src="<?php echo $theme_base_url; ?>assets/svg/github.svg" alt="" />
                    </a>
                    <?php } ?>
                    <?php if( $enabled_register['instagram'] ) { ?>
                    <a class="register-btn instagram" href="<?php echo site_url('auth/oauth2/instagram');?>" title="Instagram">
                        <img src="<?php echo $theme_base_url; ?>assets/svg/instagram.svg" alt="" />
                    </a>
                    <?php } ?>
                    <?php if( $enabled_register['microsoft'] ) { ?>
                    <a class="register-btn microsoft" href="<?php echo site_url('auth/oauth/microsoft');?>" title="Microsoft">
                        <img src="<?php echo $theme_base_url; ?>assets/svg/microsoft.svg" alt="" />
                    </a>
                    <?php } ?>
                    <?php if( $enabled_register['envato'] ) { ?>
                    <a class="register-btn envato" href="<?php echo site_url('auth/oauth/envato');?>" title="Envato">
                        <img src="<?php echo $theme_base_url; ?>assets/svg/envato.svg" alt="" />
                    </a>
                    <?php } ?>
                    <?php if( $enabled_register['bitbucket'] ) { ?>
                    <a class="register-btn bitbucket" href="<?php echo site_url('auth/oauth/bitbucket');?>" title="Bitbucket">
                        <img src="<?php echo $theme_base_url; ?>assets/svg/bitbucket.svg" alt="" />
                    </a>
                    <?php } ?>
                    <?php if( $enabled_register['paypal'] ) { ?>
                    <a class="register-btn paypal" href="<?php echo site_url('auth/oauth/paypal');?>" title="Paypal">
                        <img src="<?php echo $theme_base_url; ?>assets/svg/paypal.svg" alt="" />
                    </a>
                    <?php } ?>
                    <?php if( $enabled_register['yandex'] ) { ?>
                    <a class="register-btn yandex" href="<?php echo site_url('auth/oauth/yandex');?>" title="Yandex">
                        <img src="<?php echo $theme_base_url; ?>assets/svg/yandex.svg" alt="" />
                    </a>
                    <?php } ?>
                </div>
            </div>
            
            <br/><br/><br/>
        </div>
    </section>
</div>

<?php require 'parts/html-bottom.php'; ?>