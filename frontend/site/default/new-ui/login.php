<?php
global $current_page;

$current_page = "login";

$theme_base_url = base_url() . 'frontend/site/default/new-ui/';

$links = array(
    'register' => base_url() . 'auth/register',
    'forgot_pass' => base_url() . 'auth/forgotpassword',
);

$attrs = array(
    'form' => array('class' => 'login-page-form relative dib center white')
);

$enabled_login = array(
    'facebook' => 1 === (int) $this->config->item('enable_facebook'),
    'twitter' => 1 === (int) $this->config->item('enable_twitter'),
    'gplus' => 1 === (int) $this->config->item('enable_gplus'),
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
                <h1 class="fw5 mb2">Welcome to the Dashboard.</h1>
                <h3 class="fw4 mt2">Please Sign in to get access</h3>
                
                <?php login_form_messages(
                    isset($errors) ? ( is_array($errors) ? $errors : array($errors) ) : array(),
                    isset($success) ? $success : '',
                    isset($message) ? $message : ''
                ); ?>
                
                <br/>
                <?php echo form_input( $login ); ?>
                <br/>
                <?php echo form_password( $password ); ?>
                <label class="remember" for="remember"><?php echo form_checkbox( $remember ); ?> Remember me </label>
                <br/><br/>

                <?php if ( $show_captcha ) {
                    login_form_captcha($use_recaptcha, $this->config, $captcha_html, $captcha );
                } ?>

                <button type="submit" name="" value="true" class="btn btn-color fw6 f3">SIGN IN <i class="material-icons fw6 br-100 ba">&#xE5C8;</i></button>
                <br/><br/><br/>
                <a href="<?php echo $links['forgot_pass']; ?>" title="" class="dib btn btn-lines fw5 f7">FORGOT PASSWORD?</a>
                <br/><br/><br/>
            <?php echo form_close(); ?>

            <?php if( $enabled_login['facebook'] || $enabled_login['twitter'] || $enabled_login['gplus'] ){ ?>
            <br/>
            <div class="social-login-wrap">
                <?php if ( $enabled_login['facebook'] ) { ?>
                <a href="<?php echo site_url('auth/oauth2/facebook');?>" class="social-login facebook">
                    <span><img src="<?php echo $theme_base_url; ?>assets/svg/facebook.svg" alt="" /></span>
                    <span>Login with Facebook</span>
                </a>
                <?php } ?>
                <?php if ( $enabled_login['twitter'] ) { ?>
                <a href="<?php echo site_url('auth/oauth/twitter');?>" class="social-login twitter">
                    <span><img src="<?php echo $theme_base_url; ?>assets/svg/twitter.svg" alt="" /></span>
                    <span>Login with Twitter</span>
                </a>
                <?php } ?>
                <?php if ( $enabled_login['gplus'] ) { ?>
                <a href="<?php echo site_url('auth/oauth2/google');?>" class="social-login google-plus">
                    <span><img src="<?php echo $theme_base_url; ?>assets/svg/googleplus.svg" alt="" /></span>
                    <span>Login with Google+</span>
                </a>
                <?php } ?>
            </div>
            <?php } ?>

            <br/><br/>
            <a href="<?php echo $links['register']; ?>" title="" class="create-account dib btn btn-dark fw7 f5 pv3-25 ph3-25">CREATE AN ACCOUNT</a>
            <br/><br/><br/>
        </div>
    </section>
</div>

<?php require 'parts/html-bottom.php'; ?>
