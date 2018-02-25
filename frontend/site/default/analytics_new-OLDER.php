    <?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $this->load->view(get_template_directory() . 'header_new');
    ?>    
    
    
    <!-- BEGIN PAGE CONTENT -->
    <div class="row">

        <div class="col-md-3 col-sm-4 col-xs-12">
          <div class="panel">
            <div class="panel-header">
            <h3><i class="icon-graph"></i> Total Visits <div id="total_visits" class="btn btn-success btn-rounded btn-xs pull-right"> </div></h3>
             
            </div>
            <div class="panel-content">
                <div id="total_visits_graph">
                </div>
            </div>
          </div>
           <div class="panel">
            <div class="panel-header">
            <h3><i class="icon-graph"></i> Unique Visitor <div id="total_unique_visits" class="btn btn-success btn-rounded btn-xs pull-right"></div></h3>
            </div>
            <div class="panel-content">
                <div id="total_unique_visits_graph"></div>
            </div>
          </div>
           <div class="panel">
            <div class="panel-header">
            <h3><i class="icon-graph"></i> Page Views <div id="total_page_per_visit" class="btn btn-success btn-rounded btn-xs pull-right"></div></h3>
            </div>
            <div class="panel-content">
                
                <div id="total_page_per_visit_graph"></div>
            </div>
          </div>
        </div>
        <div class="col-md-9 col-sm-8 col-xs-12 ">
          <div class="panel">
            <div class="panel-header"> 
              <h3><i class="icon-bar-chart"></i>Last 12 Months traffic</h3>
            </div>
            <div class="panel-content">
                <div id="referrer_graph"></div>
            </div>
          </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-6 col-sm-6">
    
          <div class="row">
          <div class="col-md-6 col-sm-12">
           <div class="panel">
            <div class="panel-header">
            <h3><i class="icon-trophy"></i> Top Countries (Visits)</h3>
            </div>
            <div class="panel-content padding-zero">  
                <div id="top_country_visit">
                  
                </div>
            </div> 
          </div>
          </div>
          <div class="col-md-6 col-sm-12">
         <div class="panel">
            <div class="panel-header">
            <h3><i class="icon-trophy"></i> Top Sources / Medium (Visits)</h3>
            </div>
            <div class="panel-content padding-zero">
                <div id="top_sources_visit">
                  
                </div>
            </div>
          </div>
          </div>
          </div>
        <!--   <div class="panel">
            <div class="panel-header">
            <h3><i class="icon-clock"></i> Site Visitor Stat</h3>
            </div>
            <div class="panel-content">
              
            </div>
          </div> -->
        </div>
        
        
        <div class="col-md-6 col-sm-6">
          <div class="panel">
            <div class="panel-header">
            <h3><i class="icon-pie-chart"></i> Visits</h3>
            </div>
            <div class="panel-content">
                <div id="visit_trends">
                  
                </div>
            </div>
          </div>

         
        </div>
           
        </div>
      </div>
      
      <?php
      defined('BASEPATH') OR exit('No direct script access allowed');
      $this->load->view(get_template_directory() . 'footer_new');
        $domain_data = $this->session->get_userdata();
      ?>
<script type="text/javascript">
    jQuery(document).ready(function($){
      
        //getting visitsdata
        $.post('/analytics/analytics/getvisits',{domainId:'<?php echo $domain_data['domainId']; ?>'},function(data){
          if (data.status == "success") {
            $('#total_visits').html(data.payload.totalVisitors);
            $('#total_unique_visits').html(data.payload.totalUniqueVisitors);
            $('#total_page_per_visit').html(data.payload.totalPagePerVisit);
          } else {
            $('#total_visits').html(0);
            $('#total_unique_visits').html(0);
            $('#total_page_per_visit').html(0);
          }
        },'json');

        //getting country visits data - OK!
        $.post('/analytics/analytics/topcountry',{domainId:'<?php echo $domain_data['domainId']; ?>'},function(data){
          if (data.status == "success") {
            var html = '';
            html+='<table class="table table-bordered ds-list-table">';
              html+='<tbody><thead><tr><th>Country</th><th>Visits</th></tr></thead>';
              for(var i=0;i<data.payload.topcountries.length;i++){
                html+='<tr>';
                  html+='<td style="width:70%">'+data.payload.topcountries[i].label+'</td>';
                  html+='<td style="width:30%"><strong>'+data.payload.topcountries[i].nb_visits+'</strong></td>';
                html+='</tr>';
              }
               html+='</tbody>';
            html+='</table>';
            $('#top_country_visit').html(html);
          } else {
           $('#top_country_visit').html(data.msg);
          }
        },'json');

        //getting visit sources - OK!
        $.post('/analytics/analytics/visitsources',{domainId:'<?php echo $domain_data['domainId']; ?>'},function(data){
          if (data.status == "success") {
            var html = '';
            html+='<table class="table table-bordered ds-list-table">';
              html+='<thead><tr><th>Source</th><th>Visits</th></tr></thead>';
              html+='<tbody>';
              for(var i=0;i<data.payload.sites.length;i++){
                html+='<tr>';
                  html+='<td style="width:70%">'+data.payload.sites[i].label+'</td>';
                  html+='<td style="width:30%"><strong>'+data.payload.sites[i].nb_visits+'</strong></td>';
                html+='</tr>';
              }
               html+='</tbody>';
            html+='</table>';
            $('#top_sources_visit').html(html);
          } else {
           $('#top_sources_visit').html(data.msg);
          }
        },'json');

      
        //getting total visits graph - OK!
        $.post('/analytics/analytics/totalvisits',{domainId:'<?php echo $domain_data['domainId']; ?>'},function(response){
          if (response.status == "success") {
             var lineChartData = [];
             var labels = [];
             var data = [];
             $.each(response.payload.totalVisitsGraph, function (key, value) {
                 lineChartData.push({'date': key, 'value': value});
             });
             $.each(lineChartData, function (key, value) {
                 labels.push(value.date.slice(-2));
                 data.push(value.value);
             });
              $('#total_visits_graph').highcharts({
                     chart: {
                              type: 'area',
                              height: 100,
                             }, 
                     title: {
                         text: '',
                     },
                     xAxis: {
                         categories: labels
                             ,
                      labels: {
                     enabled: false
                     },
                        lineWidth: 0,
                           minorGridLineWidth: 0,
                           lineColor: 'transparent',
                          minorTickLength: 0,
                         tickLength: 0
                     },
                     yAxis: {
                         title: {
                             text: ''
                         },
                       
                          labels: {
                    enabled: false
                   },
                         
                     },
                     tooltip: {
                         valueSuffix: ''
                     },
                     legend:{
                        enabled:false
                     },
                     credits:{
                       enabled:false
                     },
                     exporting: {
                              enabled: false
                     },
                     plotOptions: {
                         area: {
                             marker: {
                                 enabled: false
                             }
                         }
                     },
                     series: [{
                          name:'visitor',
                          data: data
                     }]
                 });

          } else {
           $('#total_visits_graph').html(response.msg);
          }
        },'json');
        
        //unique visits graph - OK!
        $.post('/analytics/analytics/uniquevisitors',{domainId:'<?php echo $domain_data['domainId']; ?>'},function(response){
          if (response.status == "success") {
             var lineChartData = [];
             var labels = [];
             var data = [];
             $.each(response.payload.uniqueVisitorsGraph, function (key, value) {
                 lineChartData.push({'date': key, 'value': value});
             });
             $.each(lineChartData, function (key, value) {
                 labels.push(value.date.slice(-2));
                 data.push(value.value);
             });
              $('#total_unique_visits_graph').highcharts({
                    chart: {
                          type: 'area',
                          height: 100,
                      }, 
                     title: {
                         text: '',
                     },
                     xAxis: {
                         categories: labels
                             ,
                      labels: {
                     enabled: false
                     },
                        lineWidth: 0,
                           minorGridLineWidth: 0,
                           lineColor: 'transparent',
                          minorTickLength: 0,
                         tickLength: 0
                     },
                     yAxis: {
                         title: {
                             text: ''
                         },
                       
                          labels: {
                    enabled: false
                   },
                         
                     },
                     tooltip: {
                         valueSuffix: ''
                     },
                     plotOptions: {
                         area: {
                             marker: {
                                 enabled: false
                             }
                         }
                     },
                     legend:{
                        enabled:false
                     },
                     credits:{
                       enabled:false
                     },
                     exporting: {
                              enabled: false
                     },
                    
                     series: [{
                          name:'visitor',
                          data: data
                     }]
                 });

          } else {
           $('#total_unique_visits_graph').html(response.msg);
          }
        },'json');

        //page per visit graph - OK!
        $.post('/analytics/analytics/pagepervisit',{domainId:'<?php echo $domain_data['domainId']; ?>'},function(response){
          if (response.status == "success") {
             var lineChartData = [];
             var labels = [];
             var data = [];
             $.each(response.payload.totalPagePerVisitGraph, function (key, value) {
                 lineChartData.push({'date': key, 'value': Math.round(value*1)});
             });
             $.each(lineChartData, function (key, value) {
                 labels.push(value.date.slice(-2));
                 data.push(value.value);
             });
              $('#total_page_per_visit_graph').highcharts({
                    chart: {
                          type: 'area',
                          height: 100,
                      }, 
                     title: {
                         text: '',
                     },
                     xAxis: {
                         categories: labels
                             ,
                      labels: {
                     enabled: false
                     },
                        lineWidth: 0,
                           minorGridLineWidth: 0,
                           lineColor: 'transparent',
                          minorTickLength: 0,
                         tickLength: 0
                     },
                     yAxis: {
                         title: {
                             text: ''
                         },
                       
                          labels: {
                    enabled: false
                   },
                         
                     },
                     tooltip: {
                         valueSuffix: ''
                     },
                     plotOptions: {
                         area: {
                             marker: {
                                 enabled: false
                             }
                         }
                     },
                     legend:{
                        enabled:false
                     },
                     credits:{
                       enabled:false
                     },
                     exporting: {
                              enabled: false
                     },
                    
                     series: [{
                          name:'total',
                          data: data
                     }]
                 });

          } else {
           $('#total_page_per_visit_graph').html(response.msg);
          }
        },'json');

        //getting referrer graph - OK!
        $.post('/analytics/analytics/referrervisits',{domainId:'<?php echo $domain_data['domainId']; ?>'},function(response){
          if (response.status == "success") {
                var categories = [];
                var series = [];
                var totalvisits = 0;
                  $.each(response.payload.referrervisitgraph, function (key, value) {
                      categories.push(key);
                      
                  });                
                  
                  $.each(response.payload.referrervisit, function (key, value) {
                    totalvisits= totalvisits + value.nb_visits*1;
                  });                

                 $.each(response.payload.referrervisit, function (key, value) {
                    var data = [];
                    $.each(response.payload.referrervisitgraph, function (key, value1) {
                        if(value1.length == 0) {
                            data.push(0)
                        } else {
                           for(var j=0;j<value1.length;j++) {
                             if(value1[j]['label'] == value.label) {
                               data.push(value1[j]['nb_visits']);      
                             }
                           }   
                        }
                    })  

                    series.push({'name':value.label,'data':data});
                 }) 

             
                $('#referrer_graph').highcharts({
                    chart: {
                        type: 'column',
                        height: 500,
                    },
                    title: {
                        text: ''
                    },
                    xAxis: {
                        categories: categories
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: ''
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                            }
                        }
                    },
                    credits:{
                      enabled:false
                    },
                     exporting: {
                              enabled: false
                     },

                    legend: {
                        align: 'right',
                        x: -30,
                        verticalAlign: 'top',
                        y: 25,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        headerFormat: '<b>{point.x}</b><br/>',
                        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: true,
                                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                            }
                        }
                    },
                    series: series
                });

                //creating a pie chart
                var piedata = [];
                var per;
                $.each(response.payload.referrervisit, function (key, value) {
                    per = (value.nb_visits*1*100)/totalvisits;
                    piedata.push({'name':value.label,'y':per});
                })
                
                $('#visit_trends').highcharts({
                         chart: {
                             plotBackgroundColor: null,
                             plotBorderWidth: null,
                             plotShadow: false,
                             type: 'pie'
                         },
                         title: {
                             text: ''
                         },
                         tooltip: {
                             pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                         },
                         plotOptions: {
                             pie: {
                                 allowPointSelect: true,
                                 cursor: 'pointer',
                                 dataLabels: {
                                     enabled: false
                                 },
                                 showInLegend: true
                             }
                         },
                         credits:{
                          enabled:false
                         },
                         exporting:{
                          enabled:false
                         },
                         series: [{
                             name: ' ',
                             colorByPoint: true,
                             data: piedata
                         }]
                     });

            

          } else {
           $('#referrer_graph').html(response.msg);
          }
        },'json');

    });
</script>

    
        <!-- END PAGE CONTENT -->

