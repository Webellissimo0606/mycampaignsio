<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory().'header');
?>
<script type="text/javascript">
            $(function () {
                $('#fromdate').datepicker();
            });
			
			 $(function () {
                $('#todate').datepicker();
            });
</script>
<div class="page-container edit_profile_page"> 
  <!-- Content -->
  <div class="page-content">
    <div class="page-content-inner"> 
      
      <!-- Page header -->
      <div class="page-header">
        <div class="page-title profile-page-title">
          <h2>SERP</h2>
        </div>
      </div>
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
		  <h3 class="profiletitle"><span class="profile_title">Keyword Summary Report</span></h3>
		  <br>
		 
 <?php $attributes = array('class' => 'validate', 'id' => 'addProject'); echo form_open($this->uri->uri_string().'/search', $attributes) ?>
<div class="block">
    <div class="datatable-users">
      <table class="table table-striped">
        <thead>
          <tr>
            <th class="dt-head-center"><label>Website:</label>
				<?php $i=1; if(!empty($projectlist)) { ?>
                <select name="website" id="website" class="form-control">
				<?php foreach($projectlist as $prolist) {?>
				<option value="<?php echo $prolist->websiteid;?>" <?php if($prolist->websiteid == $selwebsite){?> selected="selected"<?php }?>><?php echo $prolist->vWebsite;?></option>
				<?php }?>
				</select><?php } else {?>  <select name="website" id="website" class="form-control">
				<option value="">--Select website--</option>				
				</select><?php }?></th>
            <th><label>From Date:</label><input type="text" name="fromdate" id="fromdate" class="form-control" value="<?php echo $fromdate;?>" data-date-format="yyyy-mm-dd"></th>  
			<th><label>To Date:</label><input type="text" name="todate" id="todate" class="form-control" value="<?php echo $todate;?>" data-date-format="yyyy-mm-dd"></th>
			<th><label>&nbsp;</label><?php echo form_button($submit);?></th>
          </tr>
        </thead>
        
      </table>
    </div>
  </div>	
</form>  
<br>
<?php //print_r($repdata);exit;?>		
		<!-- Striped and bordered datatable -->
  <div class="block">
    <div class="datatable-users">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th class="dt-head-center">#</th>
            <th>Keywords</th>            
            <th class="alignCenter">Search Engine</th>
            <th class="alignCenter">Position</th>
          </tr>
        </thead>
         <?php $i=1; if(!empty($repdata)) { foreach($repdata as $prolist) {  foreach($prolist as $value) { ?>
          <tr>
            <td><?php echo $i;?></td>
            <td><?php echo $value->name;?></td>           
			 <td><?php if($value->searchengines==1) {?>
			 Google <?php } else { ?> Yahoo <?php }?>
			 </td>
            <td><?php print_r($value->position);?></td>
          </tr>
          <?php $i++; } } } else { ?>
          <tr>
            <td colspan="5" align="center">No Keywords</td>
          </tr>
          <?php } ?>
      </table>
    </div>
  </div>
  <!-- /striped and bordered datatable --> 
		  </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view(get_template_directory().'footer'); ?>
