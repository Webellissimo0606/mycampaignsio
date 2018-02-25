<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory().'header');
?>

<div class="page-container edit_profile_page">
  <!-- Content -->
  <div class="page-container">
      <!-- Content -->
      <div class="page-content">
          <div class="page-content-inner">
              <div class="panel_top">
                  <div class="container">

      <!-- Page header -->

      
      <div class="row">
       <!-- <div class="left_sidebar pull-left col-md-2">
          <?php
		  $size = 170;
		  $default = site_url().'uploads/images/profile.jpg';
		  $default = '';
		  $grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $profile->email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
		  ?>
          <img src="<?php echo $grav_url; ?>" alt="<?php echo $profile->username;?>" />
          <p class="username"><?php echo $profile->first_name.' '.$profile->last_name;?></p>
          <p class="emailaddress"><?php echo $profile->email;?></p>
        </div>-->
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
        <div class="main_content col-md-12">
          <h3 class="profiletitle"><span class="profile_title">Add Domain</span></h3>
	<br>
          <div class="user_profile row">
		 <div class="x_content">
           <!-- <form id="addDomain" class="validate" method="POST" action="<?= $this->config->item('base_url').'serp/adddomain/insertDomain.html';?>">-->
		<?php $attributes = array('class' => 'validate', 'id' => 'adddomain','method' => 'POST'); echo form_open($this->config->item('base_url').'serp/adddomain/insertDomain.html', $attributes) ?>
		<div class="item form-group col-md-12 col-sm-6 col-xs-12">
                        <div class="row">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12 col-md-offset-1" for="fname">Domain Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input id="domain_name" class="form-control"  name="domain_name" data-validate-words="2" placeholder="Domain Name" required type="text">
                            </div>
                        </div>

                </div>
		<!--<div class="item form-group col-md-12 col-sm-6 col-xs-12">
                        <div class="row">
                            <label class="control-label col-md-2 col-sm-2 col-xs-12 col-md-offset-1" for="fname">KeyWords <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
				<textarea class="form-control"  name="keywords" id="keywords" placeholder="Enter Keywords here" col="10" row="10"></textarea>
                            </div>
                        </div>

                </div>-->
		<div class="clear"></div>
		<div class="ln_solid"></div>
		<div class="item form-group col-md-12 col-sm-6 col-xs-12">
                        <div class="row">
			<div class="col-md-3 col-sm-3 col-xs-12">
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" id="cancel" name="cancel" class="btn btn-primary">Cancel</button>
                            <button id="submit" name="submit" type="submit" class="btn btn-success">Submit</button>
			</div>
                        </div>
                    </div>
		</div>
	     <?php echo form_close(); ?>
		</div>

          </div>
          <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view(get_template_directory().'footer'); ?>
