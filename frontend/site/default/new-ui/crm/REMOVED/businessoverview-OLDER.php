<?php /* ?>


// TODO: Doesn't need the file. Should be removed!


<?php
global $active_content_nav_items;
$active_content_nav_items = 'overview';

$domain_data = $this->session->get_userdata();
?>
<?php include dirname(__FILE__) . '/common/top.php';?>

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
  </style>

<div class="content-row">

    <div class="content-column w-100 w-25-ns">
        <div class="content-column-main">
            <div class="fl w-100 w-50-ns ">
               <h3> Bank Balance </h3>
               <div> R12344</div>
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
               <div> R12344</div>
            </div>
            <div class="fl w-100 w-50-ns  icondiv">   
                <i class="material-icons  material_icons">insert_chart</i>
            </div>    
        </div>
     
    </div>

</div>

<div class="content-row">
    <div class="content-column w-100 w-50-ns">
            <div class="content-column-main" >
                <h3> Previous 12 months income VS current year </h3>
                <h4> Bar Chart</h4>
                   <div id="myfirstchart" style="width: 550px"> </div>
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

  <div class="content-column w-100 w-25-ns">
      <div class="content-column-main content-col">

          <div class="title">
            <div class="left-pos">
              <h3>GOOGLE SEARCH CONSOLE</h3>
            </div>
          </div>

          <div class="content-column-inner rmv-left-padd rmv-right-padd">

        <br/>

        <div class="circle-stat">
          <div class="aspect-ratio aspect-ratio--1x1">
            <div class="dt aspect-ratio--object">
              <div class="dtc tc v-mid"><span>n/a<small>%</small></span>AVERAGE <br/>CLICK THROUGH</div>
            </div>
          </div>
        </div>

        <div class="inline-stat-num">
          <span class="stat-num campaignsio-admin-green">n/a</span>
          <span class="stat-label">Total Clicks</span>
        </div>

        <div class="inline-stat-num">
          <span class="stat-num campaignsio-admin-yellow">n/a</span>
          <span class="stat-label">Total Impressions</span>
        </div>

      </div>

      </div>
  </div>

</div>

<div class="content-row">
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
            <tr>
               <td>John Doe</td>
               <td>2017-10-10</td>
               <td>2017-10-10</td>
               <td>111</td>
               <td><span class="label label-primary"> Paid </span></td>
            </tr>
            <tr>
               <td>Henry Den</td>
               <td>2017-10-10</td>
               <td>2017-10-10</td>
               <td>111</td>
               <td><span class="label label-primary"> Paid </span></td>
            </tr>
            <tr>
               <td>Henry Snow</td>
               <td>2017-10-10</td>
               <td>2017-10-10</td>
               <td>111</td>
               <td><span class="label label-primary"> Paid </span></td>
            </tr>
            
          </tbody>
        </table>
      </div>
    </div>
      <div class="content-column w-100 w-70-ns">
        <canvas id="chart_2" height="253" width="368" style="display: block; width: 368px; height: 253px;"></canvas>
      </div>
</div>

<!-- Charts -->
 <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<!-- Calendar -->
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
     <script src="<?php echo base_url(); ?>frontend/site/default/new-ui/assets/js/calendar.js"></script> 


<script type="text/javascript">
  var data=[{
            period: 'Son',
            iphone: 10,
            ipad: 80,
            itouch: 20
        }, {
            period: 'Mon',
            iphone: 130,
            ipad: 100,
            itouch: 80
        }, {
            period: 'Tue',
            iphone: 80,
            ipad: 30,
            itouch: 70
        }, {
            period: 'Wed',
            iphone: 70,
            ipad: 200,
            itouch: 140
        }, {
            period: 'Thu',
            iphone: 180,
            ipad: 50,
            itouch: 140
        }, {
            period: 'Fri',
            iphone: 105,
            ipad: 170,
            itouch: 80
        },
         {
            period: 'Sat',
            iphone: 250,
            ipad: 150,
            itouch: 200
        }];
    var dataNew = [{
            period: 'Jan',
            iphone: 10,
            ipad: 60,
            itouch: 20
        }, 
    {
            period: 'Feb',
            iphone: 110,
            ipad: 100,
            itouch: 80
        },
    {
            period: 'March',
            iphone: 120,
            ipad: 100,
            itouch: 80
        },
    {
            period: 'April',
            iphone: 110,
            ipad: 100,
            itouch: 80
        },
    {
            period: 'May',
            iphone: 170,
            ipad: 100,
            itouch: 80
        },
    {
            period: 'June',
            iphone: 120,
            ipad: 150,
            itouch: 80
        },
    {
            period: 'July',
            iphone: 120,
            ipad: 150,
            itouch: 80
        },
    {
            period: 'Aug',
            iphone: 190,
            ipad: 120,
            itouch: 80
        },
    {
            period: 'Sep',
            iphone: 110,
            ipad: 120,
            itouch: 80
        },
    {
            period: 'Oct',
            iphone: 10,
            ipad: 170,
            itouch: 10
        },
    {
            period: 'Nov',
            iphone: 10,
            ipad: 470,
            itouch: 10
        },
    {
            period: 'Dec',
            iphone: 30,
            ipad: 170,
            itouch: 10
        }
    ];
    
    var lineChart = Morris.Area({
        element: 'myfirstchart',
        data: data ,
        xkey: 'period',
        ykeys: ['iphone', 'ipad', 'itouch'],
        labels: ['iphone', 'ipad', 'itouch'],
        pointSize: 0,
        lineWidth:0,
    fillOpacity: 0.6,
    pointStrokeColors:['#2ecd99', '#4e9de6', '#f0c541'],
    behaveLikeLine: true,
    grid: false,
    hideHover: 'auto',
    lineColors: ['#2ecd99', '#4e9de6', '#f0c541'],
    resize: true,
    redraw: true,
    smooth: true,
    gridTextColor:'#878787',
    gridTextFamily:"Poppins",
        parseTime: false
    });



    var ctx2 = document.getElementById("chart_2").getContext("2d");
    var data2 = {
      labels: ["January", "February", "March", "April", "May", "June", "July"],
      datasets: [
        {
          label: "My First dataset",
          backgroundColor: "rgba(240,197,65,.6)",
          borderColor: "rgba(240,197,65,.6)",
          data: [10, 30, 80, 61, 26, 75, 40]
        },
        {
          label: "My Second dataset",
          backgroundColor: "rgba(46,205,153,.6)",
          borderColor: "rgba(46,205,153,.6)",
          data: [28, 48, 40, 19, 86, 27, 90]
        },
        {
          label: "My Third dataset",
          backgroundColor: "rgba(78,157,230,.6)",
          borderColor: "rgba(78,157,230,.6)",
          data: [8, 28, 50, 29, 76, 77, 40]
        }
      ]
    };
    
    var hBar = new Chart(ctx2, {
      type:"bar",
      data:data2,
      
      options: {
        tooltips: {
          mode:"label"
        },
        scales: {
          yAxes: [{
            stacked: true,
            gridLines: {
              color: "rgba(135,135,135,0)",
            },
            ticks: {
              fontFamily: "Poppins",
              fontColor:"#878787"
            }
          }],
          xAxes: [{
            stacked: true,
            gridLines: {
              color: "rgba(135,135,135,0)",
            },
            ticks: {
              fontFamily: "Poppins",
              fontColor:"#878787"
            }
          }],
          
        },
        elements:{
          point: {
            hitRadius:40
          }
        },
        animation: {
          duration: 3000
        },
        responsive: true,
        maintainAspectRatio:false,
        legend: {
          display: false,
        },
        
        tooltip: {
          backgroundColor:'rgba(33,33,33,1)',
          cornerRadius:0,
          footerFontFamily:"'Poppins'"
        }
        
      }
    });
  


</script>


<input type="hidden" name="is_dashboard_page" value="1" />
<input type="hidden" name="base_url" value="<?php echo base_url(); ?>" />
<input type="hidden" name="domain_id" value="<?php echo $domain_id; ?>" />
<input type="hidden" name="domain_url" value="<?php echo $domain_data['domainUrl']; ?>" />
<input type="hidden" name="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
<input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_token_name(); ?>" />

<?php include dirname(__FILE__) . '/common/bottom.php';?>

<?php */ ?>