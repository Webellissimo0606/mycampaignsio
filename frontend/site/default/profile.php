<?php 
global $active_main_nav_item;
$active_main_nav_item = 'profile';

$display_fields = array(
    'fullname' => ( $profile->first_name ? $profile->first_name : '' ) . ' ' . ( $profile->last_name ? $profile->last_name : '' ),
    'email' => $profile->email ? $profile->email : '--',
    'username' => $profile->username ? $profile->username : '--',
    'phone' => $profile->phone ? $profile->phone : '--',
    'company' => $profile->company ? $profile->company : '--',
    'country' => $profile->country ? $profile->country : '--',
    'website' => $profile->website ? $profile->website : '--',
    'address' => $profile->address ? $profile->address : '--'
);

$display_fullname = '' !== $display_fields['fullname'] ? $display_fields['fullname'] : '--';
?>

<?php require 'parts/top.php'; ?>

<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>PROFILE</h3>
                </div>
                <div class="right-pos">
                    <a href="<?php echo site_url('auth/profile/editprofile');?>" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE3C9;</i><small class="white">EDIT PROFILE</small></a>
                </div>
            </div>
            <div class="content-column-inner">
                <div class="profile-thumb"><img src="<?php echo gravatar_thumb($profile->email, 340); ?>" alt=""></div>
                <ul class="profile-info-list">
                    <li>Name:<span><?php echo $display_fullname; ?></span></li>
                    <li>Email address:<span><?php echo $display_fields['email']; ?></span></li>
                    <li>Username:<span><?php echo $display_fields['username']; ?></span></li>
                    <li>Phone:<span><?php echo $display_fields['phone']; ?></span></li>
                    <li>Company:<span><?php echo $display_fields['company']; ?></span></li>
                    <li>Country:<span><?php echo $display_fields['country']; ?></span></li>
                    <li>Website:<span><?php echo $display_fields['website']; ?></span></li>
                    <li>Address:<span><?php echo $display_fields['address']; ?></span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="content-column w-100 w-third-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>API KEYS</h3>
                </div>
            </div>
            <div class="content-column-inner">
                <span class="fw5">Pushbullet API:</span>
                <br>
                <br>
                <input type="text" readonly="" value="<?php echo $profile->pushbullet_api ? $profile->pushbullet_api : 'Not added'; ?>" class="pv3 ph2 f6 fw3 tc w-100">
            </div>
        </div>
    </div>
</div>

<?php require 'parts/bottom.php'; ?>