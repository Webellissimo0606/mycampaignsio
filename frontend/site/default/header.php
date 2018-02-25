<?php
$assets_url = base_url('assets');

$user_session = $this->session->get_userdata();

/* --------------------------------------------------------------------------------------------------------------- */

// Getting the domains of user.
$query = "SELECT * FROM user_domain ud JOIN domains d ON d.id=ud.domain_id WHERE ud.user_id='" . $user_session['user_id'] . "' ORDER BY d.id DESC";
$query = $this->db->query( $query );
$domains = $query->result_array();

/* --------------------------------------------------------------------------------------------------------------- */

if( isset( $user_session['user_id'] ) && $user_session['user_id'] ) {
                
    $query = "SELECT u.id,u.email,concat(up.first_name,' ',up.last_name) AS name FROM users u LEFT JOIN user_profiles up ON up.user_id=u.id WHERE u.parent_id='" . $user_session['user_id'] . "'";
    $query  = $this->db->query( $query );
    
    if( $query ){
        $subuser = $query->result_array();
    }

    $query1 = "SELECT * FROM groups g WHERE g.user_id='" . $user_session['user_id'] . "'";
    $query1 = $this->db->query($query1);
    
    if( $query1 ){
        $groups = $query1->result_array();
    }
}

/* --------------------------------------------------------------------------------------------------------------- */

$size = 40;
$user_session['email'] = ! isset( $user_session['email'] ) ? '' : md5( strtolower( trim( $user_session['email'] ) ) ) ;
$grav_url = "https://www.gravatar.com/avatar/" . $user_session['email'] . "?d=identicon&s=" . $size;

$query = "SELECT concat(first_name,' ',last_name) AS name FROM user_profiles WHERE user_id='" . $user_session['user_id'] . "'";
$query = $this->db->query( $query );
$userprofile = $query->row_array();

/* --------------------------------------------------------------------------------------------------------------- */

$current_page = '';

// $user_profile_data = user_profile_data( $this->db, $user_session['user_id'] );
// $user_fullname = format_user_fullname( $user_profile_data->first_name, $user_profile_data->last_name );
// $user_gravatar = gravatar_thumb( $user_profile_data->email, 112);

if( isset( $_COOKIE['campaigns-io']['collapse-sidebar'] ) ){
    $collapsed_sidebar = 1 === (int) $_COOKIE['campaigns-io']['collapse-sidebar'];
}
if( isset( $_COOKIE['campaigns-io']['collapse-author-nav'] ) ){
	// NOTE: Replaced to keep users navigation menu collapsed on pages load.
	// $collapsed_author_nav = 1 === (int) $_COOKIE['campaigns-io']['collapse-author-nav'];
			
    $collapsed_author_nav = 1;
}

$theme_base_css_path = base_url('frontend/site/default/new-ui/assets/css') . '/';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="campaigns.io">
        <meta name="author" content="campaigns.io">
        <!--link rel="shortcut icon" href="http://my.campaigns.io/frontend/site/default/images/favicon.png" type="image/png"-->
        <link rel="shortcut icon" href="http://my.campaigns.io/themes/site/default/images/favicon.png" type="image/png">
        <title>Campaigns.io - re-discover your website</title>
        <link href="<?php echo $assets_url; ?>/global/css/style.css" rel="stylesheet">
        <link href="<?php echo $assets_url; ?>/global/css/theme.css" rel="stylesheet">
        <link href="<?php echo $assets_url; ?>/global/css/ui.css" rel="stylesheet">
        <link href="<?php echo $assets_url; ?>/admin/layout1/css/layout.css" rel="stylesheet">
        <!-- BEGIN PAGE STYLE -->
        <link href="<?php echo $assets_url; ?>/global/plugins/metrojs/metrojs.min.css" rel="stylesheet">
        <link href="<?php echo $assets_url; ?>/global/plugins/maps-amcharts/ammap/ammap.min.css" rel="stylesheet">
        <link href="<?php echo $assets_url; ?>/global/plugins/datatables/dataTables.min.css" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <!-- END PAGE STYLE -->
        <script src="<?php echo $assets_url; ?>/global/plugins/modernizr/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <link href="<?php echo $assets_url; ?>/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
        <link href="<?php echo $assets_url; ?>/global/plugins/step-form-wizard/css/step-form-wizard.min.css" rel="stylesheet">

        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
        <link rel="stylesheet" type="text/css" href="<?php echo $theme_base_css_path; ?>tachyons.min.css"/>
        
        <link rel="stylesheet" type="text/css" href="<?php echo $theme_base_css_path; ?>general.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $theme_base_css_path; ?>sidebar.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $theme_base_css_path; ?>main-section-layout.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $theme_base_css_path; ?>styles.min.css"/>
    </head>

    <body class="fixed-topbar fixed-sidebar color-default">

        <div id="campaign-io-admin" class="f6 before-init <?php echo $collapsed_sidebar ? ' collapse-sidebar' : ''; echo $collapsed_author_nav ? ' collapse-author-nav': ''; ?>">

            <span class="behind-sidebar db fixed"></span>

            <aside id="sidebar" class="relative overflow-x-hidden">
                
                <header class="sidebar-header dt w-100">
                    
                    <div class="author-thumb-wrap">
                        <div class="thumb relative dtc">
                            <span class="aspect-ratio--1x1 br-100">
                                <span class="aspect-ratio--object br-100" style="background-image: url('<?php echo $user_gravatar; ?>');"></span>
                            </span>
                        </div>
                        <div class="thumb-texts dtc v-mid">
                            <small class="dib fw4">Welcome</small>
                            <br/>
                            <strong class="fw7"><?php echo $user_fullname; ?></strong>
                        </div>
                    </div>

                    <?php user_navigation( $current_page, $user['parent_id'] ); ?>
                
                </header>

                <?php main_navigation( $current_page, $domain['id'] ); ?>

            </aside>

            <section id="main" class="pa3">

            <?php /* ?><section><?php */ ?>

                <?php /* ?>
                <div class="sidebar">
                    <div class="logopanel"><h1><a href="<?php echo base_url(); ?>auth/home"></a></h1></div>
                    <div class="sidebar-inner">
                        <ul class="nav nav-sidebar">
                            <li class=" nav-active active"><a href="<?php echo base_url(); ?>auth/home"><i class="icon-home"></i><span>Dashboard</span></a></li>
                            <li class=" nav-active"><a href="<?php echo base_url(); ?>uptime/report"><i class="icon-clock"></i><span>Uptime</span></a></li>
                            <li class=" nav-active"><a href="<?php echo base_url(); ?>auth/backups"><i class="icon-folder"></i><span>Backup</span></a></li>
                            <li class=" nav-active"><a href="<?php echo base_url(); ?>domain"><i class="icon-magnifier"></i><span>All Websites</span></a></li>
                            <?php if( ! isset( $user_session['parent_id'] ) ){ ?>
                                <li class=" nav-active"><a href="<?php echo base_url(); ?>auth/add_project"><i class="icon-plus"></i><span>Add Monitor</span></a></li>
                            <?php } ?>
                            <li class=" nav-active"><a href="<?php echo base_url(); ?>listlinkdomain"><i class="icon-plus"></i>Backlink domains</a></li>
                        </ul>
                    </div>
                </div><!-- END SIDEBAR -->
                <?php */ ?>

                <div class="main-content" style="margin-left:0;">

                    <div class="home-new-topbar bg-white">

                        <div class="cf">

                            <a href="<?php echo base_url('auth/home'); ?>" title="Campaigns.io - Home" class="content-logo dib pa2"><img src="<?php echo base_url('frontend/site/default/new-ui/img/campaigns-io-logo.png'); ?>" alt="Campaigns.io logo" /></a>

                            <div>
                                <select class="form-control" data-search="true" onchange="window.location.href = '<?php echo base_url(); ?>auth/dashboard/' + $(this).val()">
                                    <?php if( $domains ){
                                        foreach( $domains as $domain ){ ?>
                                            <option <?php if( isset( $_SESSION['domainUrl'] ) && $domain['domain_name'] === $_SESSION['domainUrl'] ) { echo 'selected="selected"'; } ?> value="<?php echo $domain['id'] ?>"><?php echo $domain['domain_name']; ?></option>
                                        <?php }; ?>
                                    <?php } else { ?>
                                        <option selected="selected" value="">No domain available</option>
                                    <?php } ?>  
                                </select>
                            </div>

                            <div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-rounded btn-default dropdown-toggle" data-toggle="dropdown">Select Group<span class="caret"></span></button>
                                    <span class="dropdown-arrow"></span>
                                    <ul class="dropdown-menu" role="menu"><?php
                                        if( $groups ){ ?>
                                            <li><a href="<?php echo base_url(); ?>auth/auth/setcurrentgroup/0">All groups</a></li>
                                            <?php foreach($groups as $g){ ?>
                                                <li><a href="<?php echo base_url(); ?>auth/auth/setcurrentgroup/<?php echo $g['id']; ?>"><?php echo $g['group_name']; ?></a></li> <?php
                                            }
                                        }
                                        else { ?>
                                            <li><a href="#">No groups</a></li><?php
                                        } ?>
                                    </ul>
                                </div>
                            </div>

                            <div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-rounded btn-default dropdown-toggle" data-toggle="dropdown">Select User<span class="caret"></span></button>
                                    <span class="dropdown-arrow"></span>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php if($subuser): ?>
                                            <li><a href="<?php echo base_url(); ?>auth/auth/setcurrentuser/0">All subusers</a></li>  
                                            <?php foreach($subuser as $sub): ?>
                                                <li><a href="<?php echo base_url(); ?>auth/auth/setcurrentuser/<?php echo $sub['id']; ?>"><?php echo $sub['name'] ?></a></li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li><a href="#">No subusers</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>

                    <?php /* ?>
                    <div class="topbar">

                        <div class="header-left">

                            <div class="topnav">
                                <a class="menutoggle" href="#" data-toggle="sidebar-collapsed"><span class="menu__handle"><span>Menu</span></span></a>
                            </div>

                            <div class="header-left" style="margin-left:50px;">
                                <div class="form-group ds-top-select">
                                    <select class="form-control" data-search="true" onchange="window.location.href = '<?php echo base_url(); ?>auth/dashboard/' + $(this).val()">
                                        <?php if( $domains ){
                                            foreach( $domains as $domain ){ ?>
                                                <option <?php if( isset( $_SESSION['domainUrl'] ) && $domain['domain_name'] === $_SESSION['domainUrl'] ) { echo 'selected="selected"'; } ?> value="<?php echo $domain['id'] ?>"><?php echo $domain['domain_name']; ?></option>
                                            <?php }; ?>
                                        <?php } else { ?>
                                            <option selected="selected" value="">No domain available</option>
                                        <?php } ?>  
                                    </select>
                                </div>
                            </div>              
                        </div>

                        <div class="ds-list-top col-md-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- <span class="ds-btn-title">Group: </span> -->
                                    <div class="btn-group">
                                    <button type="button" class="btn btn-rounded btn-default dropdown-toggle" data-toggle="dropdown">Select Group<span class="caret"></span></button>
                                    <span class="dropdown-arrow"></span>
                                    <ul class="dropdown-menu" role="menu"><?php
                                        if( $groups ){ ?>
                                            <li><a href="<?php echo base_url(); ?>auth/auth/setcurrentgroup/0">All groups</a></li>
                                            <?php foreach($groups as $g){ ?>
                                                <li><a href="<?php echo base_url(); ?>auth/auth/setcurrentgroup/<?php echo $g['id']; ?>"><?php echo $g['group_name']; ?></a></li> <?php
                                            }
                                        }
                                        else { ?>
                                            <li><a href="#">No groups</a></li><?php
                                        } ?>
                                    </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- <span class="ds-btn-title">User: </span> -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-rounded btn-default dropdown-toggle" data-toggle="dropdown">Select User<span class="caret"></span></button>
                                        <span class="dropdown-arrow"></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php if($subuser): ?>
                                                <li><a href="<?php echo base_url(); ?>auth/auth/setcurrentuser/0">All subusers</a></li>  
                                                <?php foreach($subuser as $sub): ?>
                                                    <li><a href="<?php echo base_url(); ?>auth/auth/setcurrentuser/<?php echo $sub['id']; ?>"><?php echo $sub['name'] ?></a></li>
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
                                <li class="dropdown" id="user-header">
                                    <a href="#" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                        <img src="<?php echo $grav_url; ?>" alt="<?php echo $userprofile['name']; ?>">
                                        <span class="username"><?php echo $userprofile['name']; ?></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url(); ?>auth/profile"><i class="icon-user"></i><span>My Profile</span></a></li>
                                        <li>
                                            <?php if( ! $user_session['parent_id'] ){ ?>
                                                <li><a href="<?php echo base_url(); ?>assigndomain"><i class="icon-paper-clip"></i><span>Assign domains</span></a></li>
                                                <li><a href="<?php echo base_url(); ?>listsubuser"><i class="icon-users"></i><span>List Subuser</span></a></li>
                                                <li><a href="<?php echo base_url(); ?>listgroups"><i class="icon-layers"></i><span>List Group</span></a></li>
                                            <?php } ?>
                                        <li><a href="<?php echo base_url(); ?>domains"><i class="icon-globe" aria-hidden="true"></i><span>My domains</span></a>   
                                        </li>
                                        <li><a href="<?php echo base_url(); ?>auth/logout"><i class="icon-logout"></i><span>Logout</span></a></li>
                                    </ul>
                                </li><!-- END USER DROPDOWN -->
                            </ul>
                        </div> <!-- header-right -->

                    </div><!-- END TOPBAR -->

                    <?php */ ?>
              
                    <!-- BEGIN PAGE CONTENT -->
                    <div class="page-content home-new-page-content">