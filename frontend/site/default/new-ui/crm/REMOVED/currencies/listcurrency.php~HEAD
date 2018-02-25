<?php 
// defined('BASEPATH') OR exit('No direct script access allowed');

// global $active_main_nav_item;
// $active_main_nav_item = 'list-sub-users';

// global $include_filterable_table;
// $include_filterable_table = true;
?>

<?php require 'parts/top.php'; ?>

<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>CURRENCY LIST</h3>
                    <div class="" style="margin-top: 5px" >
                        <form action="<?php echo site_url(); ?>updateCurrency" method="POST" class="edit-profile-form cf" id="updatecurrency" style="width: 100%;">  <!-- small button form to post default currency, to make changes to all currencies on its basis -->
                            <input type="hidden" name="default_currency" value="<?php echo $defaultcurr['currencycode']; ?>">
                              <!-- <input type="submit" name="savecurrency" value="UPDATE CURRENCIES"  class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"> -->
                             <a href="#"> <button type="submit" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE3C9;</i><small class="white">UPDATE CURRENCIES</small></button></a>
                        </form>
                    </div>
                </div>
                <div class="right-pos">
                    <a href="<?php echo base_url(); ?>addcurrency" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE145;</i><small class="white">ADD NEW CURRENCY</small></a>
                </div>
                
                    
            </div>

            <div class="content-column-inner">
                <div class="list-table-wrap">
                    <table class="filter-table list-table mv3 collapse tc">
                        <thead>
                            <tr>
                                <th>CURRENCY CODE</th>
                                <th>PREFIX</th>
                                <th>SUFFIX</th>
                                <th>FORMAT</th>
                                <th>BASE CONV. RATE</th>
                                <th data-sortable="false">ACTION</th>
                            </tr>
                        </thead>
                        <tbody> <?php
                            if( is_array($currencylist) && ! empty($currencylist) ){
                                foreach($currencylist as $currency){ ?>
                                    <tr>
                                        <td><?php echo $currency['currencycode'] ?></td>
                                        <td><?php echo $currency['prefix']  ?></td>
                                        <td><?php echo $currency['suffix']; ?></td>
                                        <td><?php echo $currency['format']; ?></td>
                                        <td><?php if ($currency['default'] == 1) { ?>
                                                    0.00000
                                            <?php } else { ?> 
                                                  <?php echo $currency['baseconvrate']; ?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo site_url(); ?>editcurrency/<?php echo $currency['id']; ?>" title="" class="dib mv1 mh1 f7 btn-color no-underline pv1 pr2 pl1-l br1"><i class="material-icons">&#xE3C9;</i><small class="fw7">EDIT</small></a>
                                            <?php if ($currency['default'] == 0) {
                                              ?>  <a href="<?php echo site_url(); ?>deletecurrency/<?php echo $currency['id']; ?>" title="" class="dib mv1 mh1 f7 btn-lines btn-dark-br0 no-underline pv1 pr2 pl1-l br1" onclick="return on_click_domain_remove('Are you sure you want to delete this currency?')"><i class="material-icons">&#xE872;</i><small class="fw7">REMOVE</small></a>
                                            <?php } ?>
                                            
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