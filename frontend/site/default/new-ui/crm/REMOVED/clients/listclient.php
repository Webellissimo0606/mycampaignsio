<?php
if( ! isset( $current_page ) ){
    global $current_page;
    $current_page = 'business-clients';
}

require FCPATH . '/frontend/site/default/new-ui/parts/top.php';
?>

<div class="content-row">

    <div class="content-column w-100 w-two-thirds-l">
    
        <div class="content-column-main">
            
            <div class="title">
                <div class="left-pos"><h3>CLIENT LIST</h3></div>
                <div class="right-pos">
                    <a href="<?php echo base_url(); ?>addclient" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE145;</i><small class="white">ADD NEW CLIENT</small></a>
                </div>
            </div>

            <div class="content-column-inner">
                <div class="list-table-wrap">
                    <table class="filter-table list-table mv3 collapse tc">
                        <thead>
                            <tr>
                                <th>FIRST NAME</th>
                                <th>LAST NAME</th>
                                <th>COMPANY NAME</th>
                                <th>PHONE NUMBER</th>
                                <th>EMAIL</th>
                                <th>CREDIT LIMIT</th>
                                <th>TERMS</th>
                                <th data-sortable="false">ACTION</th>
                            </tr>
                        </thead>
                        <tbody> <?php
                            if ( is_array( $clientlist ) && ! empty( $clientlist ) ) {
	                           foreach ( $clientlist as $client ) { ?>
                                    <tr>
                                        <td><?php echo $client['firstname'] ?></td>
                                        <td><?php echo $client['lastname'] ?></td>
                                        <td><?php echo $client['companyname']; ?></td>
                                        <td><?php echo $client['phonenumber']; ?></td>
                                        <td><?php echo $client['email']; ?></td>
                                        <td><?php echo $client['creditlimit']; ?></td>
                                        <td><?php echo $client['terms']; ?> Days</td>
                                        <td>
                                            <a href="<?php echo site_url(); ?>editclient/<?php echo $client['id']; ?>" title="" class="dib mv1 mh1 f7 btn-color no-underline pv1 pr2 pl1-l br1"><i class="material-icons">&#xE3C9;</i><small class="fw7">EDIT</small></a>
                                            <a href="<?php echo site_url(); ?>deleteclient/<?php echo $client['id']; ?>" title="" class="dib mv1 mh1 f7 btn-lines btn-dark-br0 no-underline pv1 pr2 pl1-l br1" onclick="return on_click_domain_remove('Are you sure you want to delete this client?')"><i class="material-icons">&#xE872;</i><small class="fw7">REMOVE</small></a>
                                        </td>
                                    </tr> <?php
                                }
                            }
                            else { ?>
                                <tr><td colspan="8">No entries found</td></tr> <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require FCPATH . '/frontend/site/default/new-ui/parts/bottom.php'; ?>
