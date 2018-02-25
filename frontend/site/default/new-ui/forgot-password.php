<?php
global $current_page;
$current_page = "forgot-password";

$theme_base_url = base_url() . 'frontend/site/default/new-ui/';

$attrs = array(
    'form' => array('class' => 'login-page-form relative dib center white')
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
                
                <?php echo form_input($login); ?>
                
                <br/>
                <br/>

                <?php if ( $captcha_forgetpassword ) {
                    login_form_captcha($use_recaptcha, $this->config, $captcha_html, $captcha );
                } ?>

                <button type="submit" name="" class="btn btn-color fw6 f3">RESET PASSWORD</button>
                <br/><br/><br/>
                <a href="<?php echo base_url(); ?>auth/login" title="" class="dib btn btn-lines fw5 f7">SIGN IN</a>
                <br/><br/><br/>
            <?php echo form_close(); ?>
            <br/><br/><br/>
        </div>
    </section>
</div>

<?php require 'parts/html-bottom.php'; ?>