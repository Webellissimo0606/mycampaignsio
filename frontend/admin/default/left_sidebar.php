<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$uri_segment_1=$this->uri->segment(1);
$uri_segment_2=$this->uri->segment(2);
$uri_segment_3=$this->uri->segment(3);
?>

<!-- Sidebar -->

<div class="sidebar">
  <div class="sidebar-content"> 
    
    <!-- User dropdown -->
    <div class="user-menu dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <?php 
		  $size =300; 
		  $default = site_url().'uploads/images/profile.jpg'; 
		  $default = '';
		  $grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim($this->ci_auth->useremail()) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size; 
		  ?>
      <img src="<?php echo $grav_url; ?>" alt="<?php echo $this->ci_auth->username(); ?>" />
      <div class="user-info"><?php echo $this->ci_auth->username(); ?><span><?php echo $this->ci_auth->usergroup(); ?></span> </div>
      </a> </div>
    <!-- /user dropdown -->
    <div class="clearfix"></div>
    
    <!-- Main navigation -->
    <ul class="navigation">
      <li class="<?php if(strtolower($uri_segment_2)=='dashboard') { echo 'active'; }  ?>"><a href="<?php echo site_url('admin/dashboard');?>"><span>Dashboard</span> <i class="icon-screen2"></i></a></li>
      <?php if($this->ci_auth->canDo('view_users') || $this->ci_auth->canDo('edit_users')) { ?>
      <li class="<?php if(strtolower($uri_segment_2)=='user') { echo 'active'; }  ?>"> <a href="#"><span>Users</span> <i class="icon-users"></i></a>
        <ul>
          <?php if($this->ci_auth->canDo('edit_users')) { ?>
          <li class="<?php if(strtolower($uri_segment_2)=='user' && $uri_segment_3=='newuser') { echo 'active'; }  ?>"><a href="<?php echo site_url('admin/user/newuser');?>">Add New User</a></li>
          <?php } ?>
          <?php if($this->ci_auth->canDo('view_users')) { ?>
          <li class="<?php if(strtolower($uri_segment_2)=='user' && $uri_segment_3=='') { echo 'active'; }  ?>"><a href="<?php echo site_url('admin/user');?>">Users</a></li>
          <?php } ?>
          <?php if($this->ci_auth->canDo('edit_users')) { ?>
          <li class="<?php if(strtolower($uri_segment_2)=='user' && $uri_segment_3=='assignsites') { echo 'active'; }  ?>"><a href="<?php echo site_url('admin/user/assignsites');?>">Assign Sites</a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
      <?php if($this->ci_auth->canDo('edit_user_groups') || $this->ci_auth->canDo('view_user_groups')) { ?>
      <li class="<?php if(strtolower($uri_segment_2)=='usergroups') { echo 'active'; }  ?>"> <a href="#"><span>User Groups</span> <i class="icon-users2"></i></a>
        <ul>
          <?php if($this->ci_auth->canDo('edit_user_groups')) { ?>
          <li class="<?php if(strtolower($uri_segment_3)=='newusergroup') { echo 'active'; }  ?>"><a href="<?php echo site_url('admin/usergroups/newusergroup');?>">Add New User Group</a></li>
          <?php } ?>
          <?php if($this->ci_auth->canDo('view_user_groups')) { ?>
          <li class="<?php if(strtolower($uri_segment_2)=='usergroups' && $uri_segment_3=='') { echo 'active'; }  ?>"><a href="<?php echo site_url('admin/usergroups');?>">User Groups</a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
      <?php if($this->ci_auth->canDo('general_settings')) { ?>
      <li class="<?php if(strtolower($uri_segment_2)=='settings') { echo 'active'; }  ?>"> <a href="#"><span>Settings</span> <i class="icon-settings"></i></a>
        <ul>
          <li class="<?php if(strtolower($uri_segment_3)=='general') { echo 'active'; }  ?>"><a href="<?php echo site_url('admin/settings/general');?>">General Settings</a></li>
          <li class="<?php if(strtolower($uri_segment_3)=='social') { echo 'active'; }  ?>"><a href="<?php echo site_url('admin/settings/social');?>">Social Login Settings</a></li>
        </ul>
      </li>
      <?php } ?>
    </ul>
    <!-- /main navigation --> 
    
  </div>
</div>

<!-- /sidebar --> 
