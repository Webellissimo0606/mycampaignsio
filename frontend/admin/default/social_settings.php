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
    <h3>Social Login settings<small>10+ social login options</small></h3>
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
    <li class="active">Social Login settings</li>
  </ul>
</div>
<!-- /breadcrumbs line -->

<div class="post_contents">
<?php $attributes = array('class' => 'validate', 'id' => 'socialLogin'); echo form_open($this->uri->uri_string(), $attributes) ?>
<div class="block">
  <div class="form-group">
    <h5>Facebook</h5>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Enable Facebook Login: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_facebook" class="styled" <?php if($social_data->enable_facebook==1) { echo 'checked="checked"'; }?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_facebook" class="styled" <?php if($social_data->enable_facebook!=1) { echo 'checked="checked"'; }?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>App ID: </label>
        <?php echo form_input($facebook_app_id); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>App Secret: </label>
        <?php echo form_password($facebook_app_secret); ?> </div>
    </div>
  </div>
  <div class="form-group">
    <h5>Twitter</h5>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Enable Twitter Login: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_twitter" class="styled" <?php if($social_data->enable_twitter==1) { echo 'checked="checked"'; }?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_twitter" class="styled" <?php if($social_data->enable_twitter!=1) { echo 'checked="checked"'; }?>  value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Consumer Key (API Key): </label>
        <?php echo form_input($tw_consumer_key); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Consumer Secret (API Secret): </label>
        <?php echo form_password($tw_consumer_secret); ?> </div>
    </div>
  </div>
  <div class="form-group">
    <h5>Google</h5>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Enable Google Login: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_gplus" class="styled" <?php if($social_data->enable_gplus==1) { echo 'checked="checked"'; }?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_gplus" class="styled" <?php if($social_data->enable_gplus!=1) { echo 'checked="checked"'; }?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client ID: </label>
        <?php echo form_input($google_app_id); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client secret: </label>
        <?php echo form_password($google_app_secret); ?> </div>
    </div>
  </div>
  <div class="form-group">
    <h5>LinkedIn</h5>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Enable LinkedIn Login: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_linkedin" class="styled" <?php if($social_data->enable_linkedin==1) { echo 'checked="checked"'; }?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_linkedin" class="styled" <?php if($social_data->enable_linkedin!=1) { echo 'checked="checked"'; }?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client ID: </label>
        <?php echo form_input($linkedin_client_id); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client Secret: </label>
        <?php echo form_password($linkedin_client_secret); ?> </div>
    </div>
  </div>
  <div class="form-group">
    <h5>GitHub</h5>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Enable GitHub Login: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_github" class="styled" <?php if($social_data->enable_github==1) { echo 'checked="checked"'; }?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_github" class="styled" <?php if($social_data->enable_github!=1) { echo 'checked="checked"'; }?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client ID: </label>
        <?php echo form_input($github_client_id); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client Secret: </label>
        <?php echo form_password($github_client_secret); ?> </div>
    </div>
  </div>
  <div class="form-group">
    <h5>Instagram</h5>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Enable Instagram Login: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_instagram" class="styled" <?php if($social_data->enable_instagram==1) { echo 'checked="checked"'; }?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_instagram" class="styled" <?php if($social_data->enable_instagram!=1) { echo 'checked="checked"'; }?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client ID: </label>
        <?php echo form_input($instagram_client_id); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client Secret: </label>
        <?php echo form_password($instagram_client_secret); ?> </div>
    </div>
  </div>
  <div class="form-group">
    <h5>Microsoft</h5>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Enable Microsoft Login: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_microsoft" class="styled" <?php if($social_data->enable_microsoft==1) { echo 'checked="checked"'; }?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_microsoft" class="styled" <?php if($social_data->enable_microsoft!=1) { echo 'checked="checked"'; }?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client ID: </label>
        <?php echo form_input($microsoft_client_id); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client Secret: </label>
        <?php echo form_password($microsoft_client_secret); ?> </div>
    </div>
  </div>
  <div class="form-group">
    <h5>Envato</h5>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Enable Envato Login: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_envato" class="styled" <?php if($social_data->enable_envato==1) { echo 'checked="checked"'; }?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_envato" class="styled" <?php if($social_data->enable_envato!=1) { echo 'checked="checked"'; }?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client ID: </label>
        <?php echo form_input($envato_client_id); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client Secret: </label>
        <?php echo form_password($envato_client_secret); ?> </div>
    </div>
  </div>
  <div class="form-group">
    <h5>Paypal</h5>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Enable Paypal Login: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_paypal" class="styled" <?php if($social_data->enable_paypal==1) { echo 'checked="checked"'; }?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_paypal" class="styled" <?php if($social_data->enable_paypal!=1) { echo 'checked="checked"'; }?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client ID: </label>
        <?php echo form_input($paypal_client_id); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client Secret: </label>
        <?php echo form_password($paypal_client_secret); ?> </div>
    </div>
  </div>
  <div class="form-group">
    <h5>Yandex</h5>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Enable Yandex Login: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_yandex" class="styled" <?php if($social_data->enable_yandex==1) { echo 'checked="checked"'; }?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_yandex" class="styled" <?php if($social_data->enable_yandex!=1) { echo 'checked="checked"'; }?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client ID: </label>
        <?php echo form_input($yandex_client_id); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Client Secret: </label>
        <?php echo form_password($yandex_client_secret); ?> </div>
    </div>
  </div>
  
  <div class="form-group">
    <h5>Bitbucket</h5>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Enable Bitbucket Login: </label>
        <br />
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_bitbucket" class="styled" <?php if($social_data->enable_bitbucket==1) { echo 'checked="checked"'; }?> value="1">
          Yes </label>
        <label class="radio-inline radio-info">
          <input type="radio" name="enable_bitbucket" class="styled" <?php if($social_data->enable_bitbucket!=1) { echo 'checked="checked"'; }?> value="0">
          No </label>
      </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Consumer Key (API Key): </label>
        <?php echo form_input($bitbucket_key); ?> </div>
    </div>
    <div class="row settings_fields">
      <div class="col-md-6">
        <label>Consumer Secret (API Secret): </label>
        <?php echo form_password($bitbucket_secret); ?> </div>
    </div>
  </div>
  
  <div class="form-actions text-right">
    <input type="hidden" name="task" value="settings.update" />
    <input type="submit" class="btn btn-primary" value="Submit">
  </div>
</div>
<?php echo form_close(); ?>
<?php $this->load->view(get_theme_directory().'footer'); ?>