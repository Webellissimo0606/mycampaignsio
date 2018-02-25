<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory().'header');
?>

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
        <div class="main_content pull-left col-md-6">
		  
          <h3 class="profiletitle"><span class="profile_title">Edit Keyword - ( <?php echo $websitename; ?>)</span></h3>  
	  
          <?php $attributes = array('class' => 'validate', 'id' => 'addKeyword'); echo form_open( site_url('serp/addproject/updatekeyword'), $attributes) ?>
          <div class="user_profile row">
            
            <div class="edit_profile_fields">
              <div class="col-md-11">
                <label>Keyword:</label>
                <?php echo form_input($keyword); ?></div>
            </div>
			<input type="hidden" name="projectid" id="projectid" value="<?php echo $id;?>">
			<input type="hidden" name="keywordid" id="keywordid" value="<?php echo $keywordid;?>">
			<input type="hidden" name="websiteid" id="websiteid" value="<?php echo $websiteid;?>">
			<input type="hidden" name="searchengineid" id="searchengineid" value="<?php echo $engineid;?>">
          </div>
          <div class="clear"></div>
          <div class="form-actions"> <?php echo form_button($submit);?> </div>
          <?php echo form_close(); ?> </div>
		  <div class="main_content pull-right col-md-6">
		  <h3 class="profiletitle"><span class="profile_title">Keyword List - ( <?php echo $websitename; ?>)</span></h3> 
		  <!-- Striped and bordered datatable -->
  <div class="block">
    <div class="datatable-users">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th class="dt-head-center">#</th>            
            <th>Keywords</th>           
            <th class="alignCenter">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; if(!empty($keywordlist)) { foreach($keywordlist as $keylist) { ?>
          <tr>
            <td><?php echo $i;?></td>
             <td><?php echo $keylist->keywordname;?></td>
			
            <td><div class="table-controls">
            <a title="" class="btn btn-info btn-icon btn-xs tip" href="<?php echo site_url('serp/addproject/editkeyword/'.$keylist->keywordid.'/'.$keylist->websiteid.'/'.$id);?>" data-original-title="Print"><i class="icon-pencil"></i></a> 
            <a title="" class="btn btn-danger btn-icon btn-xs tip" href="<?php echo site_url('serp/addproject/deletekeyword/'.$keylist->keywordid.'/'.$keylist->websiteid.'/'.$id);?>" data-original-title="delete"><i class="icon-remove2"></i></a>
            </div></td>
          </tr>
          <?php $i++; } } else { ?>
          <tr>
            <td colspan="5" align="center">No Keywords</td>
          </tr>
          <?php } ?>
        </tbody>
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
