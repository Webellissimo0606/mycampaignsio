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
    <link href="<?php echo base_url(); ?>assets/admin/layout1/css/layout.css" rel="stylesheet">
    <!-- BEGIN PAGE STYLE -->
    <link href="<?php echo base_url(); ?>assets/global/plugins/metrojs/metrojs.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/global/plugins/maps-amcharts/ammap/ammap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/global/plugins/datatables/dataTables.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- END PAGE STYLE -->
    <script src="<?php echo base_url(); ?>assets/global/plugins/modernizr/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    <link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/global/plugins/step-form-wizard/css/step-form-wizard.min.css" rel="stylesheet">
		
  </head>


<body class="fixed-topbar fixed-sidebar color-default ">

 <?php 
      $user_session = $this->session->get_userdata();
       //getting the domains of user
      $query = "select * from user_domain ud join domains d on d.id=ud.domain_id where ud.user_id='".$user_session['user_id']."' order by d.id desc ";
      $query        = $this->db->query($query);
      $domains = $query->result_array();
      ?>
    <section>

      <!-- BEGIN SIDEBAR -->
      <div class="sidebar">
        <div class="logopanel">
          <h1>
            <a href="<?php echo base_url(); ?>auth/home"></a>
          </h1>
        </div>
        <div class="sidebar-inner">
          
          <ul class="nav nav-sidebar">
            <li class=" nav-active active"><a href="<?php echo base_url(); ?>auth/home"><i class="icon-home"></i><span>Dashboard</span></a></li>
            <li class=" nav-active"><a href="<?php echo base_url(); ?>uptime/report"><i class="icon-clock"></i><span>Uptime</span></a></li>
            <li class=" nav-active"><a href="<?php echo base_url(); ?>auth/backups"><i class="icon-folder"></i><span>Backup</span></a></li>
            <li class=" nav-active"><a href="<?php echo base_url(); ?>domain"><i class="icon-magnifier"></i><span>All Websites</span></a></li>
            <?php if(!isset($user_session['parent_id'])): ?>
            <li class=" nav-active"><a href="<?php echo base_url(); ?>auth/add_project"><i class="icon-plus"></i><span>Add Monitor</span></a></li>
            <?php endif; ?>
            <li class=" nav-active"><a href="<?php echo base_url(); ?>listlinkdomain"><i class="icon-plus"></i>Backlink domains</a></li>
          </ul>

          
        </div>
      </div>
      <!-- END SIDEBAR -->
      <?php 
           $user_session = $this->session->get_userdata();
           if(isset($user_session['user_id']) && $user_session['user_id']){
               $query = "select u.id,u.email,concat(up.first_name,' ',up.last_name) as name from users u  left join user_profiles up on up.user_id=u.id where u.parent_id='".$user_session['user_id']."'";
               $query  = $this->db->query($query);
               if($query){
                $subuser = $query->result_array();  
               }
               

               $query1 = "select * from groups g where g.user_id='".$user_session['user_id']."'";
               $query1 = $this->db->query($query1);
               if($query1){
                 $groups = $query1->result_array();  
               }
           }
            ?>
        <div class="main-content">

          <!-- BEGIN TOPBAR -->
          <div class="topbar">
            <div class="header-left">
              <div class="topnav">
                <a class="menutoggle" href="#" data-toggle="sidebar-collapsed"><span class="menu__handle"><span>Menu</span></span></a>
              </div>

              <div class="header-left">
                <div class="form-group ds-top-select">
                  <select class="form-control" data-search="true" onchange="window.location.href = '<?php echo base_url(); ?>auth/dashboard/'+$(this).val()">
                     <?php if($domains): ?>
                       <?php foreach($domains as $domain): ?>
                        <option <?php if(isset($_SESSION['domainUrl']) && $domain['domain_name'] == $_SESSION['domainUrl'])echo 'selected="selected"'; ?> value="<?php echo $domain['id'] ?>"><?php echo $domain['domain_name']; ?></option>
                    <?php endforeach; ?>
                   <?php else: ?>
                        <option selected="selected" value="">No domain available</option>
                   
                     <?php endif; ?>  
                  </select>
                </div>
              </div>
              
            </div>
            <div class="ds-list-top col-md-3">
                <div class="row">
                  <div class="col-md-6">
                    <!-- <span class="ds-btn-title">Group: </span> -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-rounded btn-default dropdown-toggle" data-toggle="dropdown">Select Group<span class="caret"></span>
                      </button>
                      <span class="dropdown-arrow"></span>
                      <ul class="dropdown-menu" role="menu">
                        <?php if($groups): ?>
                          <li><a href="/auth/auth/setcurrentgroup/0">All groups</a></li>
                          <?php foreach($groups as $g): ?>
                            <li><a href="/auth/auth/setcurrentgroup/<?php echo $g['id']; ?>"><?php echo $g['group_name']; ?></a></li>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <li><a href="#">No groups</a></li>
                        <?php endif; ?>
                      </ul>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <!-- <span class="ds-btn-title">User: </span> -->
                      <div class="btn-group">
                        <button type="button" class="btn btn-rounded btn-default dropdown-toggle" data-toggle="dropdown">Select User<span class="caret"></span>
                        </button>
                        <span class="dropdown-arrow"></span>
                        <ul class="dropdown-menu" role="menu">
                          <?php if($subuser): ?>
                          <li><a href="/auth/auth/setcurrentuser/0">All subusers</a></li>  
                            <?php foreach($subuser as $sub): ?>
                          <li><a href="/auth/auth/setcurrentuser/<?php echo $sub['id']; ?>"><?php echo $sub['name'] ?></a></li>
                            <?php endforeach; ?>
                          
                          <?php else: ?>
                          <li><a href="#">No subusers</a></li>
                          <?php endif; ?>
                        </ul>
                      </div>
                    </div>

                  
                </div>
            </div>
            <div class="header-right">

              <ul class="header-menu nav navbar-nav">
                <!-- BEGIN USER DROPDOWN -->

                <?php 
                  $size = 40;
                  if (isset($user_session['email'])) {
                    $grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user_session['email'] ) ) ) . "?d=identicon&s=" . $size;  
                  } else {
                    $user_session['email'] = '';
                    $grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user_session['email'] ) ) ) . "?d=identicon&s=" . $size;
                  }

                 ?>
                 <?php
                    $query = "select concat(first_name,' ',last_name) as name from user_profiles where user_id='".$user_session['user_id']."'";
                    $query  = $this->db->query($query);
                    $userprofile = $query->row_array();
                  ?>
                
                <!-- BEGIN USER DROPDOWN -->
                <li class="dropdown" id="user-header">
                  <a href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                  <img src="<?php echo $grav_url; ?>" alt="<?php echo $userprofile['name']; ?>">
                  <span class="username"><?php echo $userprofile['name']; ?></span>
                  </a>
                  <ul class="dropdown-menu">
                     <li><a href="<?php echo base_url(); ?>auth/profile"><i class="icon-user"></i><span>My Profile</span></a>
                     </li>
                     <li>
                      <?php if(!$user_session['parent_id']): ?>
                     <li><a href="<?php echo base_url(); ?>assigndomain"><i class="icon-paper-clip"></i><span>Assign domains</span></a>
                     </li>
                     <li><a href="<?php echo base_url(); ?>listsubuser"><i class="icon-users"></i><span>List Subuser</span></a>
                     </li>
                     <li><a href="<?php echo base_url(); ?>listgroups"><i class="icon-layers"></i><span>List Group</span></a>
                     </li>
                   <?php endif; ?>
                     <li><a href="<?php echo base_url(); ?>domain"><i class="icon-globe" aria-hidden="true"></i><span>My domains</span></a>   
                     </li>
                     <li><a href="<?php echo base_url(); ?>auth/logout"><i class="icon-logout"></i><span>Logout</span></a>
                     </li>
                  </ul>
                </li>
                <!-- END USER DROPDOWN -->
                
              </ul>
            </div>
            <!-- header-right -->
          </div>
          
          <!-- BEGIN PAGE CONTENT -->
          <div class="page-content">
            <div class="ds-hori-menu">
              <div class="row">
                <div class="col-md-12">
                  <ul class="ds-menu">
                    <?php $url = $_SERVER['REQUEST_URI']; $page_url = substr($url, 0, strrpos( $url, '/')); ?>
                    <li <?php if ($page_url == '/auth/dashboard' || $url == '/auth/dashboard') {
                      echo 'class="active"';
                    }?>><a href="<?php echo base_url(); ?>auth/dashboard"><i class="icon-home"></i><span>Dashboard</span></a></li>

                    <li <?php if ($url == '/auth/viewSerp') {
                      echo 'class="active"';
                    }?>><a href="<?php echo base_url(); ?>auth/viewSerp"><i class="icon-key"></i><span>Keyword SERPS</span></a></li> 

                    <li <?php if ($url == '/auth/keyword_research') {
                      echo 'class="active"';
                    }?>><a href="<?php echo base_url(); ?>auth/keyword_research"><i class="icon-key"></i><span>Keyword Research</span></a></li> 

                    <li <?php if ($url == '/analytics/searchanalytics') {
                      echo 'class="active"';
                    }?>><a href="<?php echo base_url(); ?>analytics/searchanalytics"><i class="icon-magnifier"></i><span>Search Analytics</a></li>

                    <li <?php if ($url == '/analytics/analytics') {
                      echo 'class="active"';
                    }?>><a href="<?php echo base_url(); ?>analytics/analytics"><i class="icon-chart"></i><span>Analytics</span></a></li>

                    <li <?php if ($url == '/auth/wordpress') {
                      echo 'class="active"';
                    }?>><a href="<?php echo base_url(); ?>auth/wordpress"><i class="fa fa-wordpress"></i><span>WordPress</span></a></li>

                    <li <?php if ($url == '/ecommerce') {
                      echo 'class="active"';
                    }?>><a href="<?php echo base_url(); ?>ecommerce"><i class="icon-basket"></i><span>Ecommerce</span></a></li>

                    <li <?php if ($url == '/securityscan') {
                      echo 'class="active"';
                    }?>><a href="<?php echo base_url(); ?>securityscan"><i class="icon-shield"></i><span>Security Scan</span></a></li>
                    <li <?php if (preg_match('/heatmaps/i', $url)) {
                      echo 'class="active"';
                    }?>><a href="<?php echo base_url(); ?>heatmaps"><i class="icon-shield"></i><span>Heatmaps</span></a></li>


                    <li class="login pull-right"><a href="/auth/site/viewsite/<?php echo $user_session['domainId']; ?>" target="_blank"><i class="icon-login"></i><span>Login</span></a></li>  
                  </ul>
                </div>
              </div>
            </div>
            