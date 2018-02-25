<?php
if( ! isset( $current_page ) ){
    global $current_page;
    $current_page = 'business-invoices';
}

require FCPATH . '/frontend/site/default/new-ui/parts/top.php';
?>

<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>INVOICE LIST</h3>
                </div>
                <div class="right-pos"><a href="<?php echo base_url(); ?>addinvoice" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE145;</i><small class="white">ADD NEW INVOICE</small></a>
                </div>
            </div>
            <div class="content-column-inner">
                <div class="list-table-wrap">
                    <table class="filter-table list-table mv3 collapse tc">
                        <thead>
                            <tr>
                                <th>INVOICE #</th>
                                <th>CLIENT NAME</th>
                                <th>INVOICE DATE</th>
                                <th>DUE DATE</th>
                                <th>TOTAL</th>
                                <th>DESCRIPTION</th>
                                <th>QUANTITY</th> 
                                <th>VALUE</th>
                                <th>STATUS</th>
                                <th data-sortable="false">ACTION</th>
                            </tr>
                        </thead>
                        <tbody> <?php
if (is_array($invoicelist) && !empty($invoicelist)) {
	foreach ($invoicelist as $invoice) {?>
                                    <tr>
                                        <td><?php echo $invoice['firstname'] ?></td>
                                        <td><?php echo $invoice['lastname'] ?></td>
                                        <td><?php echo $invoice['companyname']; ?></td>
                                        <td><?php echo $invoice['phonenumber']; ?></td>
                                        <td><?php echo $invoice['email']; ?></td>
                                        <td><?php echo $invoice['creditlimit']; ?></td>
                                        <td><?php echo $invoice['terms']; ?> Days</td>
                                        <td>
                                            <a href="<?php echo site_url(); ?>editinvoice/<?php echo $invoice['id']; ?>" title="" class="dib mv1 mh1 f7 btn-color no-underline pv1 pr2 pl1-l br1"><i class="material-icons">&#xE3C9;</i><small class="fw7">EDIT</small></a>
                                            <a href="<?php echo site_url(); ?>deleteinvoice/<?php echo $invoice['id']; ?>" title="" class="dib mv1 mh1 f7 btn-lines btn-dark-br0 no-underline pv1 pr2 pl1-l br1" onclick="return on_click_domain_remove('Are you sure you want to delete this invoice?')"><i class="material-icons">&#xE872;</i><small class="fw7">REMOVE</small></a>
                                        </td>
                                    </tr> <?php
}
} else {?>
                                <tr>
                                    <td colspan="4">No entries found</td>
                                </tr> <?php
}?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require FCPATH . '/frontend/site/default/new-ui/parts/bottom.php'; ?>
