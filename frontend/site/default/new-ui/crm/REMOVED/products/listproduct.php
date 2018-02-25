<?php
if( ! isset( $current_page ) ){
    global $current_page;
    $current_page = 'business-products';
}

require FCPATH . '/frontend/site/default/new-ui/parts/top.php';
?>

<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos"><h3>PRODUCT LIST</h3></div>
                <div class="right-pos">
                    <a href="<?php echo base_url(); ?>addproduct" title="" class="profile-edit dib f7 btn-color no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE145;</i><small class="white">ADD NEW PRODUCT</small></a>
                </div>
            </div>
            <div class="content-column-inner">
                <div class="list-table-wrap">
                    <table class="filter-table list-table mv3 collapse tc">
                        <thead>
                            <tr>
                                <th>TYPE</th>
                                <th>CODE</th>
                                <th>NAME</th>
                                <th>DESCRIPTION</th>
                                <th>PRICE</th>
                                <th data-sortable="false">ACTION</th>
                            </tr>
                        </thead>
                        <tbody> <?php
                            if( is_array( $productlist ) && ! empty( $productlist ) ){
                                foreach( $productlist as $product ){ ?>
                                    <tr>
                                        <td><?php echo $product['type'] ?></td>
                                        <td><?php echo $product['code']  ?></td>
                                        <td><?php echo $product['name']; ?></td>
                                        <td><?php echo $product['description']; ?></td>
                                        <td><?php echo $product['price']; ?></td>
                                        <td>
                                            <a href="<?php echo site_url(); ?>editproduct/<?php echo $product['id']; ?>" title="" class="dib mv1 mh1 f7 btn-color no-underline pv1 pr2 pl1-l br1"><i class="material-icons">&#xE3C9;</i><small class="fw7">EDIT</small></a>
                                            <a href="<?php echo site_url(); ?>deleteproduct/<?php echo $product['id']; ?>" title="" class="dib mv1 mh1 f7 btn-lines btn-dark-br0 no-underline pv1 pr2 pl1-l br1" onclick="return on_click_domain_remove('Are you sure you want to delete this product?')"><i class="material-icons">&#xE872;</i><small class="fw7">REMOVE</small></a>
                                        </td>
                                    </tr> <?php
                                }
                            }
                            else{ ?>
                                <tr><td colspan="6">No entries found</td></tr> <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require FCPATH . '/frontend/site/default/new-ui/parts/bottom.php'; ?>