<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
global $active_main_nav_item;
$active_main_nav_item = 'list-sub-users';
global $include_filterable_table;
$include_filterable_table = true;
?>

<?php require 'parts/top.php'; ?>

<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>SUBUSER LIST</h3>
                </div>
                <div class="right-pos"><a href="<?php echo base_url(); ?>addsubuser" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE145;</i><small class="white">ADD NEW SUBUSER</small></a>
                </div>
            </div>
            <div class="content-column-inner">
                <div class="list-table-wrap">
                    <table class="filter-table list-table mv3 collapse tc">
                        <thead>
                            <tr>
                                <th data-sortable="false"></th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>ACCOUNT PLAN</th>
                                <th>DOMAINS</th>
                                <th data-sortable="false">ACTION</th>
                            </tr>
                        </thead>
                        <tbody> <?php
                            if( is_array($userlist) && ! empty($userlist) ){
                                $counter = 0;
                                foreach($userlist as $user){
                                    $counter++;
                                    ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td class="tl"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td>Demo Plan</td>
                                        <td><?php echo $user['total_domains']; ?></td>
                                        <td>
                                        	<a href="<?php echo site_url(); ?>editsubuser/<?php echo $user['id']; ?>" title="" class="dib mv1 mh1 f7 btn-color no-underline pv1 pr2 pl1-l br1"><i class="material-icons">&#xE3C9;</i><small class="fw7">EDIT</small></a>
                                        	<a href="<?php echo site_url(); ?>deletesubuser/<?php echo $user['id']; ?>" title="" class="dib mv1 mh1 f7 btn-lines btn-dark-br0 no-underline pv1 pr2 pl1-l br1" onclick="return on_click_domain_remove('Are you sure you want to delete this user?')"><i class="material-icons">&#xE872;</i><small class="fw7">REMOVE</small></a>
                                        </td>
                                    </tr> <?php
                                }
                            }
                            else{ ?>
                                <tr>
                                    <td colspan="4">No entries found</td>
                                </tr> <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'parts/bottom.php'; ?>