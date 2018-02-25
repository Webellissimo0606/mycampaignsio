    <?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view(get_template_directory() . 'header_new');
    $domain_data = $this->session->get_userdata();
    ?>    
    
    
    <!-- BEGIN PAGE CONTENT -->
<div class="row">
  <div class="col-md-12">
    <div class="panel"> 
      <div class="panel-header">
          <h3>This Month's Sales Performance 
            <form action="<?php echo base_url(); ?>ecommerce" method="post" class="pull-right">
            <div class="form-group">
              <div class="prepend-icon" style="float: left;margin-right: 10px;">
                <input type="text" name="date" class="date-picker form-control" placeholder="Select a date..." id="datepicker" value="<?php echo $_SESSION['ecom_data_date']; ?>">
                <i class="icon-calendar"></i>
              </div>
              <input type="submit" name="" value="SUBMIT" class="btn btn-primary">
            </div>
            </form>
            
          </script></h3>
          
      </div>
      <div class="panel-content">
        <div class="row">
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box top-10">
              <h3>Total Sales </h3>
              <span id="total_sales_summary">0</span>
              <p>Total amount of sales done this month</p>
            </div>
          </div>

          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box top-20">
              <h3>Sales Value </h3>
              <span id="total_sales_value">0</span>
              <p>Sales Value month to date</p>
            </div>
          </div>

          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box top-50">
              <h3>Average Order</h3>
              <span id="average_order">0</span>
              <p> Average order value month to date</p>
            </div>
          </div>

          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box top-100">
              <h3>Abandonded Cart Value</h3>
              <span id="abandoned_cart_value">0</span>
              <p>Value of incomplete orders month to date</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="refined-tab">
      <div class="nav-tabs3">
        <ul id="myTab6" class="nav nav-tabs">
          <li class="active level2"><a data-toggle="tab" href="#product">Product Data</a></li>
          <li class="level2"><a data-toggle="tab" href="#referrer">Referrer Type</a></li>
          <li class="level2"><a data-toggle="tab" href="#keywords">Keywords</a></li>
        </ul>
      
        <div class="tab-content">
          <div id="product" class="tab-pane fade in active">
            <div class="panel">
              <div class="panel-content pagination2 table-responsive">
                <table class="table table-hover ds-list-table">
                    <thead>
                      <tr>
                          <th>Product name</th>
                          <th>Revenue</th>
                          <th>Quantity</th>
                          <th>Orders</th>
                          <th>Average Price</th>
                          <th>Average quantity</th>
                          <th>Conversion rate</th>
                      </tr>
                        
                    </thead>
                    <tbody id="product_data">
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Loding....</td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
          <div id="referrer" class="tab-pane fade">
            <div class="panel">
              <div class="panel-content pagination2 table-responsive">
               <table class="table table-hover ds-list-table">
                  <thead>
                    <tr>
                        <th>Referrer type</th>
                        <th>Visits</th>
                        <th>Ecommerce Orders</th>
                        <th>Total Revenue</th>
                        <th>Ecommerce Order Conversion Rate</th>
                        <th>Average Order Value</th>
                        <th>Purchased products</th>
                        <th>Revenue Per Visit</th>
                    </tr>
                      
                  </thead>
                  <tbody id="referrer_type">
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>Loding....</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
               </table>
              </div>  
            </div>
          </div>
         <div id="keywords" class="tab-pane fade">
            <div class="panel">
              <div class="panel-content pagination2 table-responsive">
                <table class="table table-hover ds-list-table">
                    <thead>
                      <tr>
                        <th>Keyword</th>
                        <th>Visits</th>
                        <th>Ecommerce orders</th>
                        <th>Total Revenue</th>
                        <th>Ecommerce order conversion rate</th>
                        <th>Average Order value</th>
                        <th>Purchased products</th>
                        <th>Revenue per visit</th>
                      </tr>
                    </thead>
                    <tbody id="keyworddata">
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Loding....</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
</div>

     <?php
      defined('BASEPATH') OR exit('No direct script access allowed');
      $this->load->view(get_template_directory() . 'footer_new');
      ?>

      <script type="text/javascript">
        jQuery(document).ready(function(){
            //getting the summary stats
            $.post('<?php echo base_url(); ?>analytics/analytics/ecommercesummarystats',{domainId:'<?php echo $domain_data['domainId'];?>'},function(data){
              if(data.status == 'success') {
                // $('#abandoned_cart_value').html(data.payload.siteResult[0]['currency']+' '+data.payload.abandonedcartstats.revenue);
                $('#total_sales_summary').html(data.payload.ecommercestats.total_sale);
                $('#total_sales_value').html(data.payload.ecommercestats['currency']+' '+data.payload.ecommercestats.sales_value);
                $('#average_order').html(data.payload.ecommercestats['currency']+' '+data.payload.ecommercestats.average_order);

              } else {

              }


            },'json');

            //getting product stats

            $.post('<?php echo base_url(); ?>analytics/analytics/ecommerceproductdata',{domainId:'<?php echo $domain_data['domainId']; ?>'},function(data){
              if(data.status == 'success') {
                    var html = '';
                   for(var i=0;i<data.payload.productdata.length;i++) {
                      html+='<tr>';
                      if(typeof data.payload.productdata[i].label == 'undefined') {
                        html+='<td>-</td>';  
                      }else{
                        html+='<td>'+data.payload.productdata[i].label+'</td>';  
                      }
                      if(typeof data.payload.productdata[i].revenue == 'undefined') {
                        html+='<td>-</td>';
                      }else{
                        html+='<td>'+data.payload.productdata[i].revenue+'</td>';
                      }
                      if(typeof data.payload.productdata[i].quantity == 'undefined') {
                        html+='<td>-</td>';
                      }else{
                        html+='<td>'+data.payload.productdata[i].quantity+'</td>';
                      }
                      if(typeof data.payload.productdata[i].orders == 'undefined') {
                        html+='<td>-</td>';
                      }else{
                        html+='<td>'+data.payload.productdata[i].orders+'</td>';
                      }
                      if(typeof data.payload.productdata[i].avg_price == 'undefined') {
                      html+='<td>-</td>';
                      }else{
                        html+='<td>'+data.payload.productdata[i].avg_price+'</td>';
                      }
                      if(typeof data.payload.productdata[i].avg_quantity == 'undefined') {
                      html+='<td>-</td>';
                      }else{
                        html+='<td>'+data.payload.productdata[i].avg_quantity+'</td>';
                      }
                      if(typeof data.payload.productdata[i].conversion_rate == 'undefined') {
                      html+='<td>-</td>';
                      }else{
                        html+='<td>'+data.payload.productdata[i].conversion_rate+'</td>';
                      }
                     html+='</tr>'
                   }   
                   
                   $('#product_data').html(html);


              } else {
                  $('#product_data').html(data.msg);
              }


            },'json');

            //keyword
            $.post('<?php echo base_url(); ?>analytics/piwik/ecommercekeywords',function(data){
              if(data.status == 'success') {
                    var html = '';
                   for(var i=0;i<data.payload.keywords.length;i++) {
                      html+='<tr>';
                      if(typeof data.payload.keywords[i].label == 'undefined') {
                        html+='<td>-</td>';  
                      }else{
                        html+='<td>'+data.payload.keywords[i].label+'</td>';  
                      }
                      if(typeof data.payload.keywords[i].nb_visits == 'undefined') {
                      html+='<td>-</td>';
                      }else{
                        html+='<td>'+data.payload.keywords[i].nb_visits+'</td>';
                      }
                      if(typeof data.payload.keywords[i]['nb_conversions'] != 'undefined') {  
                       html+='<td>'+data.payload.keywords[i]['nb_conversions']+'</td>';
                      }else{
                         html+='<td>0</td>';
                      }
                      if(typeof data.payload.keywords[i]['revenue'] != 'undefined') {  
                       html+='<td>'+data.payload.siteResult[0]['currency']+' '+data.payload.keywords[i]['revenue']+'</td>';
                      }else{
                        html+='<td>0</td>';
                      }
                      var conversionrate = 0;
                      if(typeof data.payload.keywords[i]['goals'] != 'undefined' && typeof data.payload.keywords[i]['goals']['idgoal=ecommerceOrder'] != 'undefined'){
                        conversionrate = (data.payload.keywords[i]['goals']['idgoal=ecommerceOrder']['nb_visits_converted']*100)/data.payload.keywords[i]['nb_visits'];  
                      }
                      

                      html+='<td>'+Math.round(conversionrate,2)+'%</td>';
                      if(typeof data.payload.keywords[i]['revenue'] != 'undefined') {  
                       html+='<td>'+data.payload.siteResult[0]['currency']+' '+data.payload.keywords[i]['revenue']+'</td>';
                      }else{
                        html+='<td>0</td>';
                      }
                      if(typeof data.payload.keywords[i]['goals'] != 'undefined' && typeof data.payload.keywords[i]['goals']['idgoal=ecommerceOrder'] != 'undefined'){
                      html+='<td>'+data.payload.keywords[i]['goals']['idgoal=ecommerceOrder']['items']+'</td>';
                      }else{
                         html+='<td>0</td>';
                      }
                      var revenuepervisit = 0;
                      if(typeof data.payload.keywords[i]['nb_conversions'] != 'undefined' && data.payload.keywords[i]['nb_visits'] != 'undefined'){
                        revenuepervisit = data.payload.keywords[i]['nb_conversions'] / data.payload.keywords[i]['nb_visits'];  
                      }
                      html+='<td>'+data.payload.siteResult[0]['currency']+' '+Math.round(revenuepervisit,2)+'</td>';
                     html+='</tr>'
                   }   
                   
                   $('#keyworddata').html(html);


              } else {
                  $('#keyworddata').html(data.msg);
              }


            },'json');

            //getting referrer type
            $.post('<?php echo base_url(); ?>analytics/piwik/ecommercereferrertype',function(data){
              if(data.status == 'success') {
                    var html = '';
                   for(var i=0;i<data.payload.referrer.length;i++) {
                      html+='<tr>';
                      if(typeof data.payload.referrer[i].label == 'undefined') {
                        html+='<td>-</td>';  
                      }else{
                        html+='<td>'+data.payload.referrer[i].label+'</td>';  
                      }
                      if(typeof data.payload.referrer[i].nb_visits == 'undefined') {
                        html+='<td>-</td>';  
                      }else{
                        html+='<td>'+data.payload.referrer[i].nb_visits+'</td>';  
                      }

                      if(typeof data.payload.referrer[i]['goals'] != 'undefined' && typeof data.payload.referrer[i]['goals']['idgoal=ecommerceOrder'] != 'undefined') {
                        html+='<td>'+data.payload.referrer[i].nb_conversions+'</td>';  
                      }else{
                        html+='<td>0</td>';  
                      }
                      if(typeof data.payload.referrer[i]['revenue'] != 'undefined') {  
                       html+='<td>'+data.payload.siteResult[0]['currency']+' '+data.payload.referrer[i]['revenue']+'</td>';
                      }else{
                        html+='<td>0</td>';
                      }
                      var conversionrate = 0;
                      if(typeof data.payload.referrer[i]['goals'] != 'undefined' && typeof data.payload.referrer[i]['goals']['idgoal=ecommerceOrder'] != 'undefined'){
                        conversionrate = (data.payload.referrer[i]['goals']['idgoal=ecommerceOrder']['nb_visits_converted']*100)/data.payload.referrer[i]['nb_visits'];  
                      }
                      html+='<td>'+Math.round(conversionrate,2)+'%</td>';
                      if(typeof data.payload.referrer[i]['revenue'] != 'undefined') {  
                       html+='<td>'+data.payload.siteResult[0]['currency']+' '+data.payload.referrer[i]['revenue']+'</td>';
                      }else{
                        html+='<td>0</td>';
                      }
                      if(typeof data.payload.referrer[i]['goals'] != 'undefined' && typeof data.payload.referrer[i]['goals']['idgoal=ecommerceOrder'] != 'undefined'){
                      html+='<td>'+data.payload.referrer[i]['goals']['idgoal=ecommerceOrder']['items']+'</td>';
                      }else{
                         html+='<td>0</td>';
                      }
                      var revenuepervisit = 0;
                      if(typeof data.payload.referrer[i]['nb_conversions'] != 'undefined' && data.payload.referrer[i]['nb_visits'] != 'undefined'){
                        revenuepervisit = data.payload.referrer[i]['nb_conversions'] / data.payload.referrer[i]['nb_visits'];  
                      }
                      html+='<td>'+data.payload.siteResult[0]['currency']+' '+Math.round(revenuepervisit,2)+'</td>';
                     html+='</tr>'
                   }   
                   
                   $('#referrer_type').html(html);


              } else {
                  $('#referrer_type').html(data.msg);
              }


            },'json');




        });
        

      </script>
    
        <!-- END PAGE CONTENT -->
