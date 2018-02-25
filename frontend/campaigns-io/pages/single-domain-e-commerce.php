<?php
// print_r( $ecom_data );
?>

<div class="white-body">
  <div class="container">
      <div class="col-info">
        <div class="top">
          <span class="title">Traffic <i class="ds-refresh"></i></span>
        </div>
        <?php 
          $traffic_num = $ecom_data['total_traffic'] - $ecom_data['total_traffic_prev'];
         
         ?>

        <div class="bottom">
          <span class="data"><?php echo $ecom_data['total_traffic']; ?></span> 
          <span class="diff <?php if($traffic_num<0)echo 'down';else echo 'up'; ?>"> <?php echo round($traffic_num,2); ?></span>
        </div>
      </div>
      <div class="col-info">
        <div class="top">
          <span class="title">Transactions <i class="ds-refresh"></i></span>
        </div>
        <?php 
          $trans_num = $ecom_data['current_month']['total_orders'] - $ecom_data['last_month']['total_orders'];
         ?>

        <div class="bottom">
          <span class="data"><?php echo $ecom_data['current_month']['total_orders']; ?></span> <span class="diff <?php if($trans_num>0)echo 'up';else echo 'down'; ?>"><?php echo round($trans_num,2); ?></span>
        </div>
      </div>
      <div class="col-info">
        <div class="top">
          <span class="title">Conversion Rate <i class="ds-refresh"></i></span>
        </div>

        <?php 
          if($ecom_data['current_month']['total_orders'] !=0) {
            $curent_month_converstion = round($ecom_data['total_traffic']/$ecom_data['current_month']['total_orders'],2);
            $last_month_converstion = round($ecom_data['total_traffic_prev']/$ecom_data['last_month']['total_orders'],2);
            $conversion_diff = $current_month_conversion - $last_month_converstion;  
          } else {
            $conversion_diff = 0;
          }
          
         ?>

        <div class="bottom">
          <span class="data"><?php echo number_format($ecom_data['total_traffic']/max($ecom_data['current_month']['total_orders'],2),1); ?>%</span> <span class="diff <?php if($conversion_diff<0)echo 'down';else echo 'up'; ?>"> <?php echo $conversion_diff; ?></span>
        </div>
      </div>
      <div class="col-info">
        <div class="top">
          <span class="title">Abandonded Cart <i class="ds-refresh"></i></span>
        </div>
        <div class="bottom">
          <span class="data"></span> <span class="diff up"></span>
        </div>
      </div>
      <div class="col-info">
        <div class="top">
          <span class="title">Average order value <i class="ds-refresh"></i></span>
        </div>
        <div class="bottom">
          <?php 

             $average_per_num = ($ecom_data['current_month']['total_sales']/max($ecom_data['current_month']['total_orders'],1)) - ($ecom['last_month']['average_sales']/max($ecom_data['last_month']['total_orders'],1));


             $average_per = (abs($average_per_num*100)) / max($ecom_data['last_month']['total_sales'],1); 
           ?>

          <span class="data"><?php echo round($ecom_data['current_month']['total_sales']/max($ecom_data['current_month']['total_orders'],1)); ?></span> <span class="diff <?php if($average_per_num<0)echo 'down';else echo 'up'; ?>"><?php echo round($average_per,2); ?>%</span>

        </div>
      </div>
  </div>

  <div class="revenue-box">
    <div class="one-half">
      <div class="revenue-box-child">
        <span class="top">Total Sales</span>
        <span class="bottom"><?php echo $ecom_data['current_month']['total_sales']; ?></span>
      </div>
      <div class="revenue-box-child">
        <span class="top">Last Month</span>
        <?php 
          if ($ecom_data['last_month']['total_sales'] !=0) {
            $calc = $ecom_data['current_month']['total_sales'] - $ecom_data['last_month']['total_sales'];
            $x = (abs($calc)*100)/$ecom_data['last_month']['total_sales'];
          } else {
            $x = 0;
          }
          
         ?>
        <span class="bottom"><?php echo $ecom_data['last_month']['total_sales']; ?> <span class="diff <?php if($calc > 0)echo 'up';else echo 'down'; ?>"><?php echo round($x,2); ?>%</span></span>
      </div>
      <div class="revenue-box-child">
        <span class="top">Refunds</span>
        <span class="bottom"><?php echo $ecom_data['current_month']['total_refunds']; ?></span>
      </div>
      <div class="revenue-box-child">
        <span class="top">Shipping</span>
        <span class="bottom"><?php echo $ecom_data['current_month']['total_shipping']; ?></span>
      </div>
    </div>

    <div class="one-half">

    </div>
  </div>

  <div class="content-row tab-contents-row ds-tab">

    <div class="content-tab-items">
        <ul>
        <li data-tab-item="1" class="active w-third w-auto-l">Overview</li>
        <li data-tab-item="2" class="w-third w-auto-l">Customers</li>
        <li data-tab-item="3" class="w-third w-auto-l">Traffic Sources</li>
        <li data-tab-item="4" class="w-third w-auto-l">Products</li>
        <li data-tab-item="5" class="w-third w-auto-l">Daily Revenue</li>
        <li data-tab-item="6" class="w-third w-auto-l">Payment Gateways</li>
      </ul>
    </div>

    <div class="content-column w-100">

      <div class="content-column-main">

        <div class="content-column-inner">

          <div data-tab-content="1" class="content-tab-content active">
            <div class="list-table-wrap">
              <table data-table-id="domain-ecommerce-product-data" class="filter-table list-table mv3 collapse tc">
                  <thead>
                    <tr>
                      <th>PRODUCT NAME</th>
                      <th>REVENUE</th>
                      <th>QUANTITY</th>
                      <th>ORDERS</th>
                      <th>AVERAGE PRICE</th>
                      <th>AVERAGE QUALITY</th>
                      <th>CONVERSION RATE</th>
                    </tr>
                  </thead>
                  <tbody><?php
                      if( empty( $product_data ) ){
                        ?><tr><td colspan="7">No data found</td></tr><?php
                      }
                      else{
                        foreach($product_data as $k => $v){ ?>
                        <tr>
                          <td><?php echo $v['label']; ?></td>
                          <td><?php echo $v['revenue']; ?></td>
                          <td><?php echo $v['quantity']; ?></td>
                          <td><?php echo $v['orders']; ?></td>
                          <td><?php echo $v['avg_price']; ?></td>
                          <td><?php echo $v['avg_quantity']; ?></td>
                          <td><?php echo $v['conversion_rate']; ?></td>
                        </tr><?php
                      }
                    } ?>
                  </tbody>
              </table>
            </div>
          </div>

          <div data-tab-content="2" class="content-tab-content">
            <div class="list-table-wrap">
              <table data-table-id="domain-ecommerce-referer-type" class="filter-table list-table mv3 collapse tc">
                <thead>
                  <tr>
                    <th>REFERRER TYPE</th>
                    <th>VISITS</th>
                    <th>ECOMERCE ORDERS</th>
                    <th>TOTAL REVENUE</th>
                    <th>ECOMMERCE ORDER CONVERSION RATE</th>
                    <th>AVERGAE ORDER VALUE</th>
                    <th>PURCHASED PRODUCTS</th>
                    <th>REVENUE PER VISIT</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="8">N/A</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div data-tab-content="3" class="content-tab-content">
            <div class="list-table-wrap">
              <table data-table-id="domain-ecommerce-keywords" class="filter-table list-table mv3 collapse tc">
                <thead>
                  <tr>
                    <th>REFERRER TYPE</th>
                    <th>VISITS</th>
                    <th>ECOMERCE ORDERS</th>
                    <th>TOTAL REVENUE</th>
                    <th>ECOMMERCE ORDER CONVERSION RATE</th>
                    <th>AVERGAE ORDER VALUE</th>
                    <th>PURCHASED PRODUCTS</th>
                    <th>REVENUE PER VISIT</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="8">N/A</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>

      </div>

    </div>

  </div>
</div>


<?php echo form_hidden( 'id_total_visits_data', json_encode( $domain['total_visits'] ) ); ?>

