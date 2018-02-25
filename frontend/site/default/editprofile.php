<?php 
global $active_main_nav_item;
$active_main_nav_item = 'profile';

$form_attr = array(
    'id' => 'editUser',
    'class' => 'edit-profile-form cf'
);
?>

<?php require 'parts/top.php'; ?>

<div class="content-row">
    <div class="content-column w-100">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>EDIT PROFILE</h3>
                </div>
            </div>
            <div class="content-column-inner">
                
                <?php login_form_messages(
                    isset($errors) ? ( is_array($errors) ? $errors : array($errors) ) : array(),
                    isset($success) ? $success : '',
                    isset($message) ? $message : '',
                    false
                ); ?>

                <div class="edit-profile-thumb"><img src="<?php echo gravatar_thumb($profile->email, 340); ?>" alt=""></div>

                <?php echo form_open($this->uri->uri_string(), $form_attr); ?>

                    <div class="field">
                        <label for="">First name:</label>
                        <?php echo form_input($first_name); ?>
                    </div>
                    <div class="field">
                        <label for="">Last name:</label>
                        <?php echo form_input($last_name); ?>
                    </div>
                    <div class="field">
                        <label for="">Email address:</label>
                        <?php echo form_input($email); ?>
                    </div>
                    <div class="field">
                        <label for="">Username:</label>
                        <?php echo form_input($username); ?>
                    </div>
                    <div class="field">
                        <label for="">Password:</label>
                        <?php echo form_password($password); ?>
                    </div>
                    <div class="field">
                        <label for="">Confirm password:</label>
                        <?php echo form_password($confirm_password); ?>
                    </div>
                    <div class="field">
                        <label for="">Phone:</label>
                        <?php echo form_input($phone); ?>
                    </div>
                    <div class="field">
                        <label for="">Company:</label>
                        <?php echo form_input($company); ?>
                    </div>
                    <div class="field">
                        <label for="">Country:</label>
                        <?php
                        $selected_country = ( ! isset( $profile->country ) || '' === $profile->country ) ? '' : $profile->country;
                        echo country_dropdown(
                            'country',
                            'country',
                            'form-control select-full',
                            $selected_country,
                            array(),
                            '',
                            $selection = NULL,
                            $show_all = true,
                            $country_atts
                        );
                        ?>
                    </div>
                    <div class="field">
                        <label for="">Website URL:</label>
                        <?php echo form_input($website); ?>
                    </div>
                    <div class="field">
                        <label for="">Pushbullet API key:</label>
                        <?php echo form_input($pushbulletapi); ?><span class="dib pt2 f7">Get from <a href="https://www.pushbullet.com/#settings/account" target="_blank" class="white no-underline underline-hover">https://www.pushbullet.com/#settings/account</a></span>
                    </div>
                    <div class="field">
                        <label for="">Address:</label>
                        <?php echo form_textarea($address); ?>
                    </div>
                    <hr class="relative dib w-100">
                    <button type="submit" class="btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="white">UPDATE PROFILE</span>
                    </button>
                    <a href="<?php echo site_url(); ?>auth/profile" title="" class="fr btn-lines no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="">CANCEL</span></a>
                    <br>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php require 'parts/bottom.php'; ?>