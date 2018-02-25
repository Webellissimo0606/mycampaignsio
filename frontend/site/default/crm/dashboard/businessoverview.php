<?php
global $active_content_nav_items;
$active_content_nav_items = 'overview';

$domain_data = $this->session->get_userdata();
?>
<?php require_once dirname(dirname(__FILE__)) . '/common/top.php';?>

  <link rel="stylesheet" href="<?php echo base_url(); ?>frontend/site/default/new-ui/assets/css/calendar.css" />

  <style type="text/css">
    .content-column-main{
        margin: 10px;
    }
    .material_icons{
      font-size: 64px;
    }
    .icondiv{
      text-align:center;
    }
    .new_nav a {
      width: 200px;
    }
    #select2div{
      width: 200px;
    }
    .customercanvas{
      padding-left: 20px;
      width: 97%;
    }
    .invoicescanvas{
      padding-left: 20px;
      padding-bottom: 20px;
      width: 97%;
    }
    /*.invoicescanvasdiv{
      width: 30%;
    }
    
    .invoicetablediv{
      width: 70%;
    }*/
    .incomingsvsoutgoinscanvas{
      padding-left: 20px;
      padding-bottom: 20px;
      width: 97%;
    }
  </style>

<div class="content-row">

    <div class="content-column w-100 w-25-ns">
        <div class="content-column-main">
            <div class="fl w-100 w-50-ns ">
               <h3> Bank Balance </h3>
               <div class="money-area"><?php echo $tht ?></div>
            </div>
            <div class="fl w-100 w-50-ns  icondiv">
                <i class="material-icons  material_icons">account_balance</i>
            </div>
        </div>

    </div>

    <div class="content-column w-100 w-25-ns">
      <div class="content-column-main">
          <div class="fl w-100 w-50-ns ">
            <h3> Debtors Due </h3>
            <div class=""> R12344</div>
          </div>
          <div class="fl w-100 w-50-ns  icondiv">
              <i class="material-icons  material_icons">attach_money</i>
          </div>
      </div>
    </div>

    <div class="content-column w-100 w-25-ns">
        <div class="content-column-main">
            <div class="fl w-100 w-50-ns ">
               <h3> Creditors Due </h3>
               <div> R12344</div>
            </div>
            <div class="fl w-100 w-50-ns  icondiv">
                <i class="material-icons  material_icons">attach_money</i>
            </div>
        </div>
    </div>

    <div class="content-column w-100 w-25-ns">
       <div class="content-column-main">
            <div class="fl w-100 w-50-ns ">
               <h3> Sales This Month </h3>
               <div class="money-area"><?php echo $salesThisMonth ?></div>
            </div>
            <div class="fl w-100 w-50-ns  icondiv">
                <i class="material-icons  material_icons">insert_chart</i>
            </div>
        </div>

    </div>

</div>

<div class="content-row">
    <div class="content-column w-100 w-75-ns">
        <div class="content-column-main" >    
            <h3 style="padding: 0px;margin: 0px;"><b><?php echo lang('incomingsvsoutgoings');?></b></h3>
              <small><?php echo lang('currentyearstats');?></small>
        </div>      
          <div class="incomingsvsoutgoinscanvas">
            <canvas style="padding-top: 25px;" id="incomingsvsoutgoins"></canvas>
          </div>  
                       
    </div>

      <div class="fl w-100 ">
        <div class="content-column w-100 w-25-ns" style="height: 250px">
            <div class="content-column-main content-col">
                <h3>Profit and Loss</h3>
            </div>
        </div>
      </div>
      <div class="fl w-100">
        <div class="content-column w-100 w-25-ns" style="height: 250px">
            <div class="content-column-main content-col">
                <h3> Calendar </h3>
               <div class="jquery-calendar" style="margin-left: 25px"></div>
               <div>
                  <input class="date-picker" type="text" style="margin-left: 25px" value="<?php echo date('Y-m-d'); ?>">
               </div>
            </div>
        </div>
      </div>

</div>

<div class="content-row">
<!-- <div class="content-row invoicetablediv"> -->
    <div class="content-column w-100 w-70-ns">
      <div class="content-column-main">
        <h3> Upcoming Invoices </h3>
        <table class="filter-table list-table mv3 collapse tc" style="width: 97%">
          <thead>
            <tr>
              <th>Client</th>
              <th>Due</th>
              <th>Invoiced On</th>
              <th>Total</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($invoices as $key => $invoice) { ?>
            <tr>
               <td><?php echo $invoice['namesurname'] ?></td>
               <td><?php echo $invoice['duedate'] ?></td>
               <td><?php echo $invoice['datecreated'] ?></td>
               <td class="money-area"><?php echo $invoice['in[total]'] ?></td>
               <td><span class="label label-primary"> <?php echo $invoice['name'] ?> </span></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>    
<!-- </div> -->
 <!-- Invoices Summary Chart-->
              <!-- <div class="content-row invoicescanvasdiv"> -->
                <div class="content-column w-100 w-30-ns">
                  <div class="content-column-main">
                    <h3> <?php echo lang('invoicesbystatuses');?> </h3>
                      <small><?php echo lang('billsbystatus');?></small>
                  </div>
                  <div class="invoicescanvas">
                    <canvas id="invoice_chart_by_status"></canvas>
                  </div>  
                </div>
              <!-- </div> -->
</div>              
<!-- customer Summary Chart-->
                <div class="content-row">
                  <div class="content-column w-100 w-70-ns animated fadeIn">
                    <div class="content-column-main" id="select2div">
                       <h3> <?php echo lang('customermonthlyreporttitle');?> </h3>
                            <?php
                            echo '<select name="m" class="form-control select2" data-none-selected-text="April">' . PHP_EOL;
                            for ( $m = 1; $m <= 12; $m++ ) {
                              $_selected = '';
                              if ( $m == date( 'm' ) ) {
                                $_selected = ' selected';
                              }
                              echo '  <option value="' . $m . '"' . $_selected . '>' . ( date( 'F', mktime( 0, 0, 0, $m, 1 ) ) ) . '</option>' . PHP_EOL;
                            }
                            echo '</select>' . PHP_EOL;
                            ?>
                         </div>   
                      <div class="customercanvas">
                        <canvas class="customergraph_ciuis-xe chart mtop20" id="customergraph_ciuis-xe" height="100"></canvas>
                      </div>
                    </div>
                  </div>
<!-- Incoming Vs Outgoing -->


<!-- select2 -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/crm/lib/select2/css/select2.min.css"/>


<!-- Calendar -->
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
     <script src="<?php echo base_url(); ?>frontend/site/default/new-ui/assets/js/calendar.js"></script>
<!-- Invoices Summary-->
<script>
  new Chart($('#invoice_chart_by_status'), {
             type: 'horizontalBar',
             data: <?php echo $invoice_chart_by_status; ?>,
       options: {
         legend: {
          display: false,
         },
              scales: {
                  yAxes: [{
                      ticks: {
                          fontColor: "#c8c8c8",
                      }
                  }],
                  xAxes: [{
                      ticks: {
                          fontColor: "#c8c8c8",
                      },
                  }],
              }
       }
     });
</script>    
<!-- Incoming Vs Outgoing  -->
<script>
  new Chart($('#incomingsvsoutgoins'), {
             type: 'line',
             data: <?php echo $incomings_vs_outgoins; ?>,
              
             options: { 
              legend: {
                  labels: {
                      fontColor: "#c8c8c8",
                  }
              },
              scales: {
                  yAxes: [{
                      ticks: {
                          fontColor: "#c8c8c8",
                      }
                  }],
                  xAxes: [{
                      ticks: {
                          fontColor: "#c8c8c8",
                      },
                  }],
              }
          }
     });
</script>
<!-- customer Summary -->
<script>
  var ciuis_url = "<?php echo base_url();?>";
  var AylikCustomerGrafigi;
  $( function () {
    $.get( ciuis_url + 'report/customer_monthly_increase_chart/' + $( 'select[name="m"]' ).val(), function ( response ) {
      var ctx = $( '#customergraph_ciuis-xe' ).get( 0 ).getContext( '2d' );
      AylikCustomerGrafigi = new Chart( ctx, {
        'type': 'bar',
        data: response,
        backgroundColor: "#c8c8c8",
        options: {
          responsive: true,
          legend: {
                  labels: {
                      fontColor: "#c8c8c8",
                  }
              },
              scales: {
                  yAxes: [{
                      ticks: {
                          fontColor: "#c8c8c8",
                      }
                  }],
                  xAxes: [{
                      ticks: {
                          fontColor: "#c8c8c8",
                      },
                  }],
              }
        },
      } );
    }, 'json' );
    $( 'select[name="m"]' ).on( 'change', function () {
      AylikCustomerGrafigi.destroy();
      $.get( ciuis_url + 'report/customer_monthly_increase_chart/' + $( 'select[name="m"]' ).val(), function ( response ) {
        var ctx = $( '#customergraph_ciuis-xe' ).get( 0 ).getContext( '2d' );
        AylikCustomerGrafigi = new Chart( ctx, {
          'type': 'bar',
          data: response,
          backgroundColor: "#c8c8c8",
          options: {
            responsive: true,
            legend: {
                  labels: {
                      fontColor: "#c8c8c8",
                  }
              },
              scales: {
                  yAxes: [{
                      ticks: {
                          fontColor: "#c8c8c8",
                      }
                  }],
                  xAxes: [{
                      ticks: {
                          fontColor: "#c8c8c8",
                      },
                  }],
              }
          },
        } );
      }, 'json' );
    } );
  } );
</script> 

<input type="hidden" name="is_dashboard_page" value="1" />
<input type="hidden" name="base_url" value="<?php echo base_url(); ?>" />
<input type="hidden" name="domain_id" value="<?php echo $domain_id; ?>" />
<input type="hidden" name="domain_url" value="<?php echo $domain_data['domainUrl']; ?>" />
<input type="hidden" name="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
<input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_token_name(); ?>" />
<?php include dirname(dirname(__FILE__)) . '/common/bottom.php';?>