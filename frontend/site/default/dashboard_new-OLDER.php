<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header_new');
?>

        
    
      <div class="row">
      
        <div class="col-md-6">
          <div class="panel"> 
              <div class="panel-header">
                <h3><strong>GT Metrix</strong></h3>
              </div>
              
              
              <div class="panel-content gtm-box">
                <div class="row" style="margin-bottom: 30px;">
                  <div class="col-md-5 col-sm-5 col-xs-12">
                    <img id="gtmet_image" class="img-responsive myimg" src="http://placehold.it/216x162?text=Loading">
                  </div>
                  <div class="col-md-7 col-sm-7 col-xs-12">
                    <h4 class="gtm_title">Latest Performance Report for</h4>
                    <a id="gt_link" class="gt_link" href="" target="_blank"></a>

                    <div class="report-box">
                      <div class="report-row">
                        <span class="report_title">Report Generated on</span>
                        <span id="get_cur_data" class="report_data"></span>
                      </div>
                      <div class="report-row">
                        <span class="report_title">Test Server Region</span>
                        <span class="server_data"></span>
                      </div>

                      <div class="report-row">
                        <span class="report_title">Using</span>
                        <span id="browser_det" class="using_data">
                          <img id="browser_imag" class="img-responsive" src="">
                        </span>
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
              <div class="panel-footer" style="background: rgba(246, 167, 79, 0.5);">
                <div class="row">
                  <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="page-stat">
                      <h4 id="Pg_loa_tim"></h4>
                      <p>Load Time</p>
                  </div>
                  </div>
                  
                  <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="page-stat">
                      <h4 id="to_pg_sz"></h4>
                      <p>Page Size</p>
                    </div>
                    
                  </div>
                  
                  <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="page-stat">
                      <h4 id="rq"></h4>
                      <p>Requests</p>
                    </div>
                  </div>
                  
                
                
                
                  <div class="col-md-4 col-sm-4 col-xs-6">
                    <div id="new_page_score" class="page-stat">
                      <h4 id="PageSpeed_scores"></h4>
                      <p>Page Speed </p>
                    </div>
                  
                  </div>
                  
                  <div class="col-md-4 col-sm-4 col-xs-6">
                    <div id="new_yslow_scores" class="page-stat">
                      <h4 id="yslow_scores"></h4>
                      <p>Y Slow Scores </p>
                    </div>
                    
                  </div>
                  
                  <div class="col-md-4 col-sm-4 col-xs-6">
                    <a href="" target="_blank"><button class="btn btn-primary gtbutton" type="button">View</button></a>
                  </div>
                </div>  
              </div>
              
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="panel">  
            <?php if($downTime['server_status'] == 'DOWN')$up_class="site_down"; ?>
            <?php if($downTime['server_status'] == NULL)$up_class="no_stats"; ?>
            <?php if($downTime['server_status'] == 'UP')$up_class="site_up"; ?>
              <div class="panel-header <?php echo $up_class; ?>">
                <h3><strong>RESPONSE TIME &amp; UPTIME</strong>
                  <?php if($downTime['server_status'] == 'DOWN'):?>  
                      (Site down from <?php echo date('d M Y', strtotime($downTime['downtime'])); ?> @
                      <?php echo date("H:i",strtotime($downTime['downtime'])); ?>)
                   <?php endif; ?> 
                
                <?php if($downTime['server_status'] == 'UP'): ?>
                  <a href="<?php echo base_url(); ?>uptime/report" class="btn btn-sm btn-success pull-right">Up</a>
                <?php elseif($downTime['server_status'] == 'DOWN'): ?>
                  <a href="<?php echo base_url(); ?>uptime/report" class="btn btn-sm btn-danger pull-right">Down</a>
                <?php else: ?>
                  <a href="<?php echo base_url(); ?>uptime/report" class="btn btn-sm btn-warning pull-right">No stats</a>
                <?php endif; ?>
                </h3>
              </div>
              
              <div class="panel-content">
                <div id="torpe"></div>
              </div>
              
              
              
              <div class="panel-footer" id="uptime_stats_graph">
                  <script type="html/tpl" id="uptime_stats_template"> 
                    <div class="row">
                      <div class="col-md-4">
                   
                        <div class="uptime-box">
                          <div class="uptime-icon">
                            <i class="icon-clock"></i>
                          </div>

                          <div class="uptime-content">
                            <h4>
                            {uptime_stats_24_per} %
                            </h4>
                            <p>Last 24 Hours</p>
                          </div>
                          
                        </div>
                     
                      </div>
                   
                      <div class="col-md-4">
                       
                        <div class="uptime-box">
                          <div class="uptime-icon">
                            <i class="icon-calendar"></i>
                          </div>
                          <div class="uptime-content">
                            <h4>
                            {uptime_stats_7_per} %
                            </h4>
                            <p>Last 7 Days</p>
                          </div>
                        </div>
                         
                      </div>
                   
                      <div class="col-md-4">
                       
                        <div class="uptime-box">
                          <div class="uptime-icon">
                            <i class="icon-calendar"></i>
                          </div>
                          <div class="uptime-content">
                            <h4>
                            {uptime_stats_30_per} %
                            </h4>
                            <p>Last 30 Days</p>
                          </div>
                        </div>
                       
                      </div>
                    </div>
                  </script>
              </div>
              
              
              
              
          </div>
        </div>
        
      </div>

      <div id="panel_template_keyword_stats">    
        <script type="html/tpl" id="template_keyword_stats">
          <div class="row">
            <div class="col-lg-4 col-md-12">
              <div class="panel">  
                <div class="panel-header">
                  <h3 class="clear"><i class="icon-trophy"></i><strong>Keyword Position</strong></h3>
                </div>
                
                <div class="panel-content"> 

                  <div class="row">
                    <div class="col-md-4 col-xs-12 col-sm-4">
                      <div class="top-key top-10">
                        <h4>{keyword_top10}</h4>
                        <span>Top 10</span>
                      </div>
                    </div>

                    <div class="col-md-4 col-xs-12 col-sm-4">
                      <div class="top-key top-20">
                        <h4>{keyword_top20}</h4>
                        <span>Top 20</span>
                      </div>
                    </div>

                    <div class="col-md-4 col-xs-12 col-sm-4">
                      <div class="top-key top-50">
                        <h4>{keyword_top50}</h4>
                        <span>Top 50</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="panel-footer clearfix">
                  <a href="<?php echo base_url(); ?>auth/viewSerp" class="btn btn-rounded btn-blue pull-right" >VIEW DETAIL</a>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
              <div class="panel">  
                <div class="panel-header">
                  <h3><i class="icon-magnifier"></i><strong>Search Engine Data</strong></h3>
                </div>
                <div class="panel-content">
                  <div class="row">
                    <div class="col-md-5"> 
                      <div class="click-box">
                        <i class="icon-cursor"></i>
                        <h3>Clicks this month</h3>
                        <span id="google_clicks">0</span>
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="keyword-stat up">
                        <h3><i class="icon-arrow-up"></i>up <span class="pull-right">{keyword_positive}</span></h3>
                      </div>

                      <div class="keyword-stat down">
                        <h3><i class="icon-arrow-down"></i>Down <span class="pull-right">{keyword_negative}</span></h3>
                      </div>

                      <div class="keyword-stat no-change">
                        <h3><i class="icon-control-pause"></i>No Change <span class="pull-right">{keyword_nochange}</span></h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
              <div  class="panel key_avg_pos">  
                <div class="panel-header">
                  <h3><strong>Average Position</strong></h3> 
                </div>
                
                <div class="panel-content bg-blue">
                  <div class="row">
                    <div class="col-md-9"><h4>Last week position</h4></div>
                    <div class="col-md-3"><span>{keyword_average_position}</span></div>
                  </div>
                </div>
                
              </div>
              <div class="panel key_avg_pos">  
                <div class="panel-header">
                  <h3><strong>Traffic</strong> </h3>
                </div> 
                
                <div class="panel-content bg-yellow">
                  <div class="row">
                    <div class="col-md-9"><h4>Traffic Current Week</h4></div>
                    <div class="col-md-3"><div id="chart2" ></div></div>
                  </div>
                  
                </div>
                
              </div>
            </div>
          </div>
        </script>
      </div>
     
      
      <div class="row">
      
        <div class="col-md-6">
          <div class="panel bg-light-blue">   <!-- FIRST PANEL START -->
              <div class="panel-header bg-green">
                <h3><strong>Website Traffic</strong></h3>
              </div>
              <div class="panel-content bg-green">
                <div class="content">
                  <div id="chart"></div>
                </div>
              </div>

              <div class="panel-footer bg-green clearfix">
                <a href="<?php echo base_url(); ?>analytics/analytics" class="btn btn-rounded btn-blue pull-right">VIEW MORE</a>
              </div>
              
          </div>
        </div>
      
        <div class="col-md-6">
          <div class="panel"> 
              <div class="panel-header">
                <h3><strong>ON Page Analysis</strong></h3>
              </div>
              
              <div class="panel-content">
                <div id="loadingmessage" class="spacing messages">
                    Please be patient while we analyse your domain.
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="col-md-4 col-sm-4 col-xs-6 myminheights score-data">
                      <div class="score-circle" style="padding-top: 10px">
                          <strong></strong>
                      </div>
                      <div class="scroTitleDashboard score" style="padding-top: 20px;color: #2B3D4F;">Overall Score</div>
                    </div>
                    
                    <div class="col-md-4 col-sm-4 col-xs-6 myminheights firstbyte" data-firstbytepercent="" data-firstbyte="" data-color="">
                      <canvas id="firstbyte" height="110" >
                      </canvas>
                      <div class="scroTitleDashboard" style="color:#2B3D4F;"><a href="#" data-toggle="tooltip" style="color:#2B3D4F;" title="Google recommends a time less than 200 ms (represented in gray)">?</a> Time to First Byte</div>
                    </div>
                    
                    <div class="col-md-4 col-sm-4 col-xs-6 myminheights loadtime">
                      <canvas id="loadtime" height="110">
                      </canvas>
                      <div class="scroTitleDashboard" style="color:#2B3D4F;"><a href="#" data-toggle="tooltip" style="color:#2B3D4F;" title="67% of users demand that a page must be loaded within 4 seconds (represented in gray)">?</a> Page Load Time</div>
                    </div>
            
                  </div>
                </div>
              </div>

              <div class="panel-footer clearfix">
                <a href="<?php echo base_url(); ?>auth/analyze" class="btn btn-rounded btn-blue pull-right">VIEW MORE</a>
              </div>
                
                
                
                
            </div>
          </div>
         
      </div>
      
      <div class="row">
      
        <div class="col-md-12">
          <div class="panel">   <!-- FIRST PANEL START -->
            <div class="panel-header">
              <h3><strong>GOOGLE WEBMASTER TOOLS</strong></h3>
            </div> 

            <div class="panel-content google-webmaster-not">
                 Google Webmaster Tools is not enabled
            </div>

            <div class="panel-content google-webmaster refined-tab">
              <div class="nav-tabs3" id="interest_tabs">
                <ul id="myTab6" class="nav nav-tabs">
                  <li class="active level2"><a data-toggle="tab" href="#web"> <i class="icon-screen-desktop"></i> DESKTOP / LAPTOP </a></li>
                  <li class="level2"><a data-toggle="tab" href="#smartphone"> <i class="icon-screen-smartphone"></i> SMARTPHONE </a></li>
                </ul>
              <div class="tab-content">

                <div id="web" class="tab-pane fade active in">
                </div>

                <div id="smartphone" class="tab-pane fade in">
                </div>
              </div>

              <!--all web content-->
              <div class="tab-content web-content" style="display: block">

              </div>

              <!--smartphone tab content-->
              <div class="tab-content smartphone-content" style="display:none;">

              </div>

              </div>
            </div>
          </div>   <!-- FIRST PANEL END -->   
        </div>

      </div>
        <!-- END PAGE CONTENT -->
  </div>
      <!-- END MAIN CONTENT -->
      
    
    
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'footer_new');
?>
<?php 
  $domain_data = $this->session->get_userdata();
?>
<script>

  $('#myTab6 li').on('click', 'a[data-toggle="tab"]', function (e) {
      e.preventDefault();

      var leveltext = ($(this).text()).trim();
      console.log(leveltext);
      if(leveltext == 'DESKTOP / LAPTOP'){
        $('.web-content').show();  
        $('.mobile-content').hide();  
        $('.smartphone-content').hide();  
      }
      if(leveltext == 'MOBILE'){
        console.log('mobile here');
        $('.web-content').hide();  
        $('.mobile-content').show();  
        $('.smartphone-content').hide();  
      }
      if(leveltext == 'SMARTPHONE'){
        console.log('smartphone here');
        $('.web-content').hide();  
        $('.mobile-content').hide();  
        $('.smartphone-content').show();  
      }
  })



  
  $(function () {
      var $ppc = $('.progress-pie-chart'),
              percent = parseInt($ppc.data('percent')),
              deg = 360 * percent / 100;
      if (percent > 50) {
          $ppc.addClass('gt-50');
      }
      $('.ppc-progress-fill').css('transform', 'rotate(' + deg + 'deg)');
      $('.ppc-percents span').html(percent + '%');
  });
</script>

<script>
    // activate jprogress
    // $(".progressbars").jprogress();
    // $(".progressbarsone").jprogress({
    //     background: "#FF2D55"
    // });
    // $(document).ready(function () {

    $.post('<?php echo base_url(); ?>auth/analyze/getkeywordreport', {domain_id:<?php echo $domain_id; ?>}, function (data) {
        var TPL = $('#template_keyword_stats').html(),
        keyword_average_position = Math.round(data.avg_position['average_position']);
        if( TPL ){
          TPL = TPL.replace(/{keyword_average_position}/g, keyword_average_position);
          TPL = TPL.replace(/{keyword_positive}/g, data.keyword_changes['positive']);
          TPL = TPL.replace(/{keyword_negative}/g, data.keyword_changes['negative']);
          TPL = TPL.replace(/{keyword_nochange}/g, data.keyword_changes['nochange']);
          TPL = TPL.replace(/{keyword_top5}/g, data.position['top5']);
          TPL = TPL.replace(/{keyword_top10}/g, data.position['top10']);
          TPL = TPL.replace(/{keyword_top20}/g, data.position['top20']);
          TPL = TPL.replace(/{keyword_top50}/g, data.position['top50']);
          TPL = TPL.replace(/{keyword_total_search}/g, data.total_keywords['total_keyword_search']);
          $('#panel_template_keyword_stats').append(TPL);
        }
    }, 'json');


    $.post('<?php echo base_url(); ?>auth/uptimestats', {domain_id:<?php echo $domain_id; ?>}, function (data) {
        var TPL = $('#uptime_stats_template').html();
        if( TPL ){
          TPL = TPL.replace(/{uptime_stats_24_per}/g, Math.round(data.uptime1daypercentage*1));
          TPL = TPL.replace(/{uptime_stats_7_per}/g, Math.round(data.uptime7daypercentage*1));
          TPL = TPL.replace(/{uptime_stats_30_per}/g, Math.round(data.uptime30daypercentage*1));
          TPL = TPL.replace(/{uptime_stats_uptime_hours}/g, data.totaluptimehours*1);
          TPL = TPL.replace(/{uptime_stat_current_date}/g, data.current_date);
          $('#uptime_stats_graph').append(TPL);
        }

        var Data = [];
        var splineLabel = [];
        var splineData = [];
        for(var i=0;i<data.uptimedaystats.length;i++){
          splineData.push(Math.round((data.uptimedaystats[i].load_time*1)));
          splineLabel.push(data.uptimedaystats[i].completed_on_time);
        }


        $('#torpe').highcharts({
               chart: {
                   type: 'area'
               },
               title: {
                   text: ''
               },
               subtitle: {
                   text: ''
               },
               xAxis: {
                   categories: splineLabel,
                   crosshair: true,
                   labels: {
                    enabled: true,
                    staggerLines: 2,
                    step: 5
                   },
                   
               },
               yAxis: {
                   min: 0,
                   title: {
                       text: ''
                   }
               },
               tooltip: {
                   headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                   pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                       '<td style="padding:0"><b>{point.y:.1f} ms</b></td></tr>',
                   footerFormat: '</table>',
                   shared: true,
                   useHTML: true
               },
               credits: {
                    enabled: false
                },
               plotOptions: {
                   column: {
                       pointPadding: 0.2,
                       borderWidth: 0
                   }
               },
               series: [{
                   name: ' ',
                   data: splineData

               }]
           });


        // var lineChartData = {
        //     labels: splineLabel,
        //     datasets: [
        //         {
        //             label: "My Second dataset",
        //             fillColor: "rgba(151,187,205,0.2)",
        //             strokeColor: "rgba(151,187,205,1)",
        //             pointColor: "rgba(151,187,205,1)",
        //             pointStrokeColor: "#fff",
        //             pointHighlightFill: "#fff",
        //             pointHighlightStroke: "rgba(151,187,205,1)",
        //             data: splineData
        //         }
        //     ]

        // }

        // var ctx1 = $("#canvas-response-time-graph").get(0).getContext("2d");
        // window.myLine = new Chart(ctx1).Line(lineChartData, {
        //     responsive: true, scaleOverride: true, scaleStartValue: 0, scaleStepWidth: 1200, scaleSteps: 4
        // });

        var TPL1 = $('#uptime_stats_template_2').html();
        if( TPL1 ){
          TPL1 = TPL1.replace(/{uptime_stats_24_per}/g, Math.round(data.uptime1daypercentage*1));
          TPL1 = TPL1.replace(/{uptime_stats_7_per}/g, Math.round(data.uptime7daypercentage*1));
          TPL1 = TPL1.replace(/{uptime_stats_30_per}/g, Math.round(data.uptime30daypercentage*1));
          $('#uptime_stat').append(TPL1);
        }
    }, 'json');


    // })
</script>
<script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
<script>

    // $(document).ready(function () {
      
    var lt = '';
    if (geoplugin_city()) {
        lt = geoplugin_city() + ", " + geoplugin_countryName();
    } else {
        lt = geoplugin_countryName();
    }
    $('.server_data').append(lt);


    /*Begin Bowser detect coding*/
    var isMobile = false; //initiate as false
    // device detection
    if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
            || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4)))
        isMobile = true;
    $.browser.chrome = /chrome/.test(navigator.userAgent.toLowerCase());
    if (isMobile == false) {
        $('#browser_det').append(" (Desktop)");
    } else {
        $('#browser_det').append(" (Mobile)");
    }
    if ($.browser.chrome) {
        $('#browser_det').append(" Chrome");
        $('#browser_imag').attr("src", "<?php echo base_url() ?>assets/images/chrome.png");

        $.browser.safari = false;
    }
    if ($.browser.safari) {

        $('#browser_det').append(" Safari");
        $('#browser_imag').attr("src", "<?php echo base_url() ?>assets/images/safari.png");
    }
    if ($.browser.mozilla) {

        $('#browser_det').append(" Mozilla");
        $('#browser_imag').attr("src", "<?php echo base_url() ?>assets/images/mozilla.jpg");
    }
    if ($.browser.opera) {

        $('#browser_det').append(" Opera");
        $('#browser_imag').attr("src", "<?php echo base_url() ?>assets/images/opera.png");
    }
    if ($.browser.ie) {

        $('#browser_det').append(" IE");
        $('#browser_imag').attr("src", "<?php echo base_url() ?>assets/images/ie.jpg");
    }

    /*End Browser detect coding*/

    var trigger = $('.hamburger'),
        overlay = $('.overlay'),
        isClosed = false;

    trigger.click(function () {
        hamburger_cross();
    });

    $('.scroTitleDashboard').hide();
    $('#loadingmessage').show();
    getDareboostAPI();
    getGoogleAnalyticsData(); //getpiwikdata
    getWeeklyGoogleTraffic();
    getGoogleClicks();
    var web_url = '<?php echo $domain_data['domainUrl']; ?>';
    var short_url = '<?php echo strtr($domain_data['domainUrl'], array('www.'=>'','https://'=>'','http://'=>'','/'=>'')); ?>';
    $('.gt_link').empty();
    $('.gt_link').append(short_url);
    $('.load').html('Loading ...');
    $('#get_cur_data').empty();

    $.post("<?php echo base_url() ?>auth/auth/gtmetrix",
      {
          'gtmetrix': web_url,
          '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
      },
      function (data, status) {
          if(data.status){
              $('.load').html('');
              var datas = $.parseJSON(data.metrix);
              if (datas.pagespeed_score < 60) {
                  $('#new_page_score').css('background', '#eb393b');
                  $('#new_yslow_scores').css('color', '#fff');

              } else if (datas.pagespeed_score >= 60 && datas.pagespeed_score < 80) {
                  $('#new_page_score').css('background', '#e29b20');
                  $('#new_page_score').css('color', '#fff');

              } else {
                  $('#new_page_score').css('background', '#23ab11');
                  $('#new_page_score').css('color', '#fff');
              }
              if (datas.yslow_score < 60) {
                  $('#new_yslow_scores').css('background', '#eb393b');
                  $('#new_page_score').css('color', '#fff');

              } else if (datas.yslow_score >= 60 && datas.yslow_score < 80) {
                  $('#new_yslow_scores').css('background', '#e29b20');
                  $('#new_yslow_scores').css('color', '#fff');

              } else {
                  $('#new_yslow_scores').css('background', '#23ab11');
                  $('#new_yslow_scores').css('color', '#fff');

              }

              var pageSpeedGrade = getGrade(datas.pagespeed_score);
               var YSlowScoreGrade = getGrade(datas.yslow_score);
                $('#PageSpeed_scores').append(pageSpeedGrade + '(' + datas.pagespeed_score + '%)');
              $('#yslow_scores').append(YSlowScoreGrade + '(' + datas.yslow_score + '%)');
              $('#Pg_loa_tim').append(Math.round(datas.page_load_time / 1000 * 100) / 100 + 's');
              $('#to_pg_sz').append(bytesToSize(datas.page_bytes));
              $('#rq').append(datas.page_elements);
              $('#gt_link').attr("href", datas.report_url);
              $('.gtbutton').parent('a').attr("href", datas.report_url);
              

              var path = datas.report_url + "/screenshot.jpg";

              $("#gtmet_image").attr("src", path);

              var d = new Date();
              $('#get_cur_data').append(data.date);

          }
          
      },'json');



    $('.refresh_gtmet_data').click(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>auth/auth/refreshGTMetrixCount",
            dataType: 'json',
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', 'domainId': "<?php echo $this->session->userdata('domainId') ?>"},
            success: function (data) {
                if (data.credits == 1) {
                    var datas = data.datas;
                    $('.load').html('');
                    if (datas.pagespeed_score < 60) {
                        $('#PageSpeed_scores').css('color', '#eb393b');

                    } else if (datas.pagespeed_score >= 60 && datas.pagespeed_score < 80) {
                        $('#PageSpeed_scores').css('color', '#e29b20');

                    } else {
                        $('#PageSpeed_scores').css('color', '#23ab11');

                    }
                    if (datas.yslow_score < 60) {
                        $('#yslow_scores').css('color', '#eb393b');

                    } else if (datas.yslow_score >= 60 && datas.yslow_score < 80) {
                        $('#yslow_scores').css('color', '#e29b20');

                    } else {
                        $('#yslow_scores').css('color', '#23ab11');

                    }

                    var pageSpeedGrade = getGrade(datas.pagespeed_score);
                    var YSlowScoreGrade = getGrade(datas.yslow_score);
                    $('#PageSpeed_scores').html(pageSpeedGrade + '(' + datas.pagespeed_score + '%)');
                    $('#yslow_scores').html(YSlowScoreGrade + '(' + datas.yslow_score + '%)');
                    $('#Pg_loa_tim').html(Math.round(datas.page_load_time / 1000 * 100) / 100 + 's');
                    $('#to_pg_sz').html(bytesToSize(datas.page_bytes));
                    $('#rq').html(datas.page_elements);
                    $('#gt_link').attr("href", datas.report_url);

                    var path = datas.report_url + "/screenshot.jpg";

                    $("#gtmet_image").attr("src", path);

                    var d = new Date();
                    $('#get_cur_data').append(data.date);

                } else {
                    alert("No Credits Left.");
                }

            },
            complete: function () {
                $('#ga_account').css('display', 'block');
            }
        });
    });
    
    var webmaster = '<?php echo $this->session->userdata('webmaster')  ?>';
     
    if (webmaster == '1'){
        if(!($('.google-webmaster-not').hasClass('hide'))){
            $('.google-webmaster-not').addClass('hide');
        }
        if($('.google-webmaster').hasClass('hide')){
            $('.google-webmaster').removeClass('hide');
        }
        getCrawlErrors();
    }else{
        if(!($('.google-webmaster').hasClass('hide'))){
            $('.google-webmaster').addClass('hide');
        }
        if($('.google-webmaster-not').hasClass('hide')){
            $('.google-webmaster-not').removeClass('hide');
        }
    }

    // });

  function getGrade(score) {
    var grade1 = '';
    if (score > 89) {
        grade1 = 'A';
    } else if (score > 79 && score <= 89) {
        grade1 = 'B';
    } else if (score > 69 && score <= 79) {
        grade1 = 'C';
    } else if (score > 59 && score <= 69) {
        grade1 = 'D';
    } else if (score > 49 && score <= 59) {
        grade1 = 'E';
    } else if (score > 39 && score <= 49) {
        grade1 = 'F';
    } else if (score > 29 && score <= 39) {
        grade1 = 'G';
    } else if (score > 19 && score <= 29) {
        grade1 = 'H';
    } else if (score > 9 && score <= 19) {
        grade1 = 'I';
    } else {
        grade1 = 'J';
    }

    return grade1;
  }


  function hamburger_cross() {
    if (isClosed == true) {
        overlay.hide();
        trigger.removeClass('is-open');
        trigger.addClass('is-closed');
        isClosed = false;
    } else {
        overlay.show();
        trigger.removeClass('is-closed');
        trigger.addClass('is-open');
        isClosed = true;
    }
  }

  $('[data-toggle="offcanvas"]').click(function () {
      $('#wrapper').toggleClass('toggled');
  });

  function bytesToSize(bytes) {
      var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
      if (bytes == 0)
          return '0 Byte';
      var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
      return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
  }

  function formatDate(d) {
      var months = ["Januaray", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]; //you would need to include the rest
      var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];//you would need to include the rest
      return days[d.getDay()] + ", " + months[d.getMonth()] + " " + (d.getDate() < 10 ? "0" + d.getDate() : d.getDate()) + ", " + d.getFullYear();
  }


  function getDareboostAPI() {
    var url = jQuery('.domain-selected').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>auth/analyze/ajax_analyze_data",
        data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', url: '<?php echo $domain_data['domainUrl']; ?>'},
        success: function (data) {
            //var report= data.report;
            $('#loadingmessage').hide();
            $('.scroTitleDashboard').show();
            var report = JSON.parse(data);
            if (report.msg === 'success') {
                var score = parseFloat(report.score) / 100;

                var grad1 = '';
                var grad2 = '';
                if (score < 0.40) {
                    grad1 = '#ff1e41';
                    grad2 = '#ff5f43';
                } else if (score >= 0.40 && score < 0.80) {
                    grad1 = '#3399ff';
                    grad2 = '#33ccff';
                } else {
                    grad1 = '#33ff66';
                    grad2 = '#66ff33';
                }
                $('.score-circle').circleProgress({
                    value: score,
                    size: 90,
                    fill: {gradient: [grad1, grad2]}
                }).on('circle-animation-progress', function (event, progress) {
                    $(this).find('strong').html(parseInt(100 * score) + '/100');
                });

                /////First Byte
                var first_byte = ((parseFloat(report.performance_timing.firstByte) - parseFloat(report.performance_timing.navigationStart)) / 60) / 10;
                var first_byte_ms = (first_byte * 1000).toFixed(2);
                var firstbyte_percent = parseFloat(first_byte_ms / 200) * 100;
                var first_byte_color = '';
                $('.firstbyte').data('firstbytepercent', firstbyte_percent);
                $('.firstbyte').data('firstbyte', first_byte);
                $('.firstbyte').data('color', first_byte_color);

                if (firstbyte_percent < 35) {
                    first_byte_color = "#00eb00";
                } else if (firstbyte_percent > 35 && firstbyte_percent < 85) {
                    first_byte_color = "#e07100";
                } else {
                    first_byte_color = "#d10013";
                }

                var endanglepercent = (((13 / 100) * firstbyte_percent) / 10).toFixed(1);
                var endangleinner = 1.5 + parseFloat(endanglepercent);
                var endAngle1 = 0;
                if (endangleinner.toString().split(".")[0] > 1 && first_byte_ms < 200) {
                    endAngle1 = '0.' + endangleinner.toString().split(".")[1];

                } else if (first_byte_ms > 200) {
                    endAngle1 = '1.0';
                } else {
                    endAngle1 = endangleinner.toString().split(".")[0] + '.' + endangleinner.toString().split(".")[1];
                }
                var canvas = document.getElementById('firstbyte');
                var context = canvas.getContext('2d');
                var x = canvas.width / 4;
                var y = canvas.height / 2;
                var radius = 50;
                var startAngle = 1.5 * Math.PI;
                var endAngle = 0.8 * Math.PI;
                var counterClockwise = false;

                context.beginPath();
                context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
                context.lineWidth = 10;

                // line color
                context.strokeStyle = '#585858';
                context.stroke();
                var x1 = (canvas.width) / 4;
                var y1 = (canvas.height) / 2;
                var radius1 = 40;
                var startAngle1 = 1.5 * Math.PI;
                var endAngle1 = parseFloat(endAngle1) * Math.PI;
                context.beginPath();
                context.arc(x1, y1, radius1, startAngle1, endAngle1, counterClockwise);
                context.lineWidth = 10;

                // line color
                context.strokeStyle = first_byte_color;
                context.stroke();
                context.beginPath();
                context.fillStyle = '#585858';
                context.font = "bold 16px Arial";
                context.fillText(first_byte.toFixed(2), 65, 50);
                context.beginPath();
                context.font = "bold 16px Arial";
                context.fillStyle = '#585858';
                context.fillText("sec", 65, 70);


                /////Load Time

                var load = parseFloat(report.load_time) / 1000;

                var load_percent = parseFloat(load / 4) * 100;
                var load_time_color = "";
                if (load_percent < 25) {
                    load_time_color = "#00eb00";
                } else if (load_percent > 25 && load_percent < 85) {
                    load_time_color = "#e07100";
                } else {
                    load_time_color = "#d10013";
                }
                $('.loadtime').data('timepercent', load_percent);
                $('.loadtime').data('time', load);
                $('.loadtime').data('color', load_time_color);
                var endanglepercent = (((13 / 100) * load_percent) / 10).toFixed(1);
                var endangleinner = 1.5 + parseFloat(endanglepercent);
                var endAngle1 = 0;
                if (endangleinner.toString().split(".")[0] > 1 && load < 4) {
                    endAngle1 = '0.' + endangleinner.toString().split(".")[1];

                } else if (load > 4) {
                    endAngle1 = '1.0';
                } else {
                    endAngle1 = endangleinner.toString().split(".")[0] + '.' + endangleinner.toString().split(".")[1];
                }

                var canvas = document.getElementById('loadtime');
                var context = canvas.getContext('2d');
                var x = canvas.width / 4;
                var y = canvas.height / 2;
                var radius = 50;
                var startAngle = 1.5 * Math.PI;
                var endAngle = 0.8 * Math.PI;
                var counterClockwise = false;

                context.beginPath();
                context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
                context.lineWidth = 10;

                // line color
                context.strokeStyle = '#585858';
                context.stroke();
                var x1 = (canvas.width) / 4;
                var y1 = (canvas.height) / 2;
                var radius1 = 40;
                var startAngle1 = 1.5 * Math.PI;
                var endAngle1 = parseFloat(endAngle1) * Math.PI;
                context.beginPath();
                context.arc(x1, y1, radius1, startAngle1, endAngle1, counterClockwise);
                context.lineWidth = 10;

                // line color
                context.strokeStyle = load_time_color;
                context.stroke();
                context.beginPath();
                context.fillStyle = '#585858';
                context.font = "bold 16px Arial";
                context.fillText(load.toFixed(2), 65, 50);
                context.beginPath();
                context.font = "bold 16px Arial";
                context.fillStyle = '#585858';
                context.fillText("sec", 65, 70);
            } else {
                $('.scroTitleDashboard').hide();
                $('#loadingmessage').html('On Page Issues are not currently being monitored');
                $('#loadingmessage').show();

            }
        }
    });
  }

  function getGoogleAnalyticsData() {
    $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>analytics/analytics/getGoogleAnalytics",
        dataType: 'json',
        data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', id: '<?php echo $domain_data['domainId']; ?>',days:15},
        success: function (data) {
            if (data.type == 'success') {
               var lineChartData = [];
               $.each(data.payload, function (key, value) {
                   lineChartData.push({'date': key, 'value': value});
                     });
                 sessionLineChartByDate(lineChartData); 
            } else {
                $('.chart-area-heading').html('<h2>Analytics data not available.</h2>');
            }
        }
    });
  }

  function getWeeklyGoogleTraffic() {
   $.ajax({
       type: "POST",
       url: "<?php echo base_url() ?>analytics/analytics/getWeeklyGoogleTraffic",
       dataType: 'json',
       data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', id: '<?php echo $domain_data['domainId']; ?>'},
       success: function (data) {
           if (data.type == 'success') {
               $('#chart2').html(data.payload.value);
           } else {
               $('#chart2').html('<h2>Data Not Available.</h2>');
           }
       }
   });
  }

  function getGoogleClicks() {
    $.post('<?php echo base_url();?>analytics/piwik/getsearchengineclicks',{'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', domainId: '<?php echo $domain_data['domainId']; ?>'},function(data){
      if(data.status == 'success') {
        $('#google_clicks').html(data.payload.totalClicks);
      } else {f
        $('#google_clicks').html(0);
      }
    },'json')
  }

  function sessionLineChartByDate(lineChartData) {
    var labels = [];
    var data = [];

    $.each(lineChartData, function (key, value) {
      
       labels.push(value.date);
        data.push(value.value);
    });
    $('#chart').highcharts({
      chart: {
        height: 149,
        plotBorderColor: '#C21414',
        plotBorderColor: '#C21414',
        backgroundColor: 'transparent',
        spacingRight: 0,
        spacingLeft: 0,
        spacingBottom: 0,
        spacingTop: 0,
        marginBottom: 0,
        type: 'area',
      }, 
      title: {
           text: '',
      },
       
      credits: {
        enabled: false
      },
      colors: ['rgba(0,0,0,0.3)', 'rgba(0,0,0,0.3)'],
      exporting: {
          enabled: false
      },
      rangeSelector: {
          selected: 0,
          enabled: false
      },
      scrollbar: {
          enabled: false
      },
      navigator: {
          enabled: false
      },
      navigation: {
          buttonOptions: {
              enabled: false
          }
      },
      xAxis: {
          gridLineColor: 'transparent',
          gridLineColor: 'transparent',
          lineColor: 'transparent',
          tickColor: 'transparent',
          minorGridLineWidth: 0,
          title: {
            text: '',
          },
          labels: {
              enabled: false
          }
      },
      yAxis: {
          gridLineColor: 'transparent',
          gridLineColor: 'transparent',
          lineColor: 'transparent',
          title: {
            text: '',
          },
          labels: {
              enabled: false
          }
      },
      tooltip: {
          formatter:function(){
                 return  labels[this.key] +':'+ this.y+' visits';
             }
      },
       series: [{
            name:' ',
            data: data,
            type: 'spline',
            
         }]
    });
  }
    
  function getCrawlErrors() {
      var url = '<?php echo $domain_data['domainUrl']; ?>';
      $.ajax({
          type: "POST",
          url:"<?php echo base_url() ?>analytics/analytics/getCrawlErrors",
          dataType: 'json',
          data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', url: url},
          success: function (data) {
              var api_data = data.api_data;
              var accessToken = data.accessToken;
              var web_data = [];
              var mobile_data = [];
              var smartphone_data = [];
              var category_html = '';
              $.each(api_data.countPerTypes, function (key, value) {
                  if (value.platform === "web") {
                      if (value.entries[0].count !== "0") {
                          web_data.push({'category': value.category, 'count': value.entries[0].count});
                      }
                  }
                  if (value.platform === "mobile") {
                      if (value.entries[0].count !== "0") {
                          mobile_data.push({'category': value.category, 'count': value.entries[0].count});
                      }
                  }
                  if (value.platform === "smartphoneOnly") {
                      if (value.entries[0].count !== "0") {
                          smartphone_data.push({'category': value.category, 'count': value.entries[0].count});
                      }
                  }
              });
              // var webUrl = encodeURIComponent('<?php echo $this->session->userdata("domainHost") ?>');
              var webUrl = encodeURIComponent('<?php echo $domain_data['domainUrl']; ?>');

              if (web_data.length == 0) {
                  $('#web').html('<div class="webmaster_no_error">Great! No Error Found.</div>');
              } else {
                  var html = '<ul class="nav nav-tabs nav-primary">';
                  var category_html1 = '';

                  $.each(web_data, function (key, value) {
                      if (key == 0) {
                          html += '<li class="active"><a data-toggle="tab" href="#web_' + value.category + '"><i class="glyphicon glyphicon-alert"></i>' + value.category + '<span class="badge badge-primary webmaster_count">' + value.count + '</span></a> </li>';
                          $.get("https://www.googleapis.com/webmasters/v3/sites/" + webUrl + "/urlCrawlErrorsSamples?category=" + value.category + "&platform=web&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (cat_data) {


                              category_html1 += '<div id="web_' + value.category + '" class="tab-pane fade active in "><div class="pagination2"><table class="table table-bordered ds-list-table table-dynamic" id="data_table_web_' + value.category + '"><thead><tr><th>Priority </th><th>url</th><th>Response Code</th><th>Last Crawled</th></tr></thead></table></div></div>';
                              $('.web-content').html(category_html1);
                              var oTable1 = $('#data_table_web_' + value.category).DataTable({
                                  stateSave: true,
                                  bSortable: false,
                                  bPaginate: true,
                                  sPaginationType: "full_numbers",
                                  binfo: false,
                                  bFilter: true,
                                  aoColumns: [
                                      {"sWidth": "10%"},
                                      {"sWidth": "60%"},
                                      {"sWidth": "12%"},
                                      {"sWidth": "18%"}
                                  ]});
                              var responseCode = '-';
                              var pageUrl = '-';
                              var lastCrawled = '-';
                              $.each(cat_data.urlCrawlErrorSample, function (k, val) {
                                  if (val.pageUrl) {
                                      pageUrl = val.pageUrl;
                                  }
                                  if (val.responseCode) {
                                      responseCode = val.responseCode;
                                  }
                                  if (val.last_crawled) {
                                      lastCrawled = val.last_crawled;
                                  }
                                  oTable1.row.add([parseFloat(k + 1), pageUrl, responseCode, lastCrawled]).draw();
                              });
                          });



                      } else {
                          html += '<li><a data-toggle="tab" href="#web_' + value.category + '"><i class="glyphicon glyphicon-alert"></i>' + value.category + '<span class="webmaster_count badge badge-primary ">' + value.count + '</span></a> </li>';
                          $.get("https://www.googleapis.com/webmasters/v3/sites/" + webUrl + "/urlCrawlErrorsSamples?category=" + value.category + "&platform=web&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (cat_data) {

                              category_html1 += ' <div id="web_' + value.category + '" class="tab-pane fade in "><div class="pagination2"><table class="table table-hover ds-list-table" id="data_table_web_' + value.category + '"><thead><tr><th>Priority </th><th>url</th><th>Response Code</th><th>Last Crawled</th></tr></thead></table></div></div>';
                              $('.web-content').append(category_html1);
                              var oTable2 = $('#data_table_web_' + value.category).DataTable({
                                  stateSave: true,
                                  bPaginate: true,
                                  sPaginationType: "full_numbers",
                                  binfo: false,
                                  bFilter: true,
                                  aoColumns: [
                                      {"sWidth": "10%"},
                                      {"sWidth": "60%"},
                                      {"sWidth": "12%"},
                                      {"sWidth": "18%"}
                                  ]});
                              var responseCode = '-';
                              var pageUrl = '-';
                              var lastCrawled = '-';
                              $.each(cat_data.urlCrawlErrorSample, function (k, val) {
                                  if (val.pageUrl) {
                                      pageUrl = val.pageUrl;
                                  }
                                  if (val.responseCode) {
                                      responseCode = val.responseCode;
                                  }
                                  if (val.last_crawled) {
                                      lastCrawled = val.last_crawled;
                                  }
                                  oTable2.row.add([parseFloat(k + 1), pageUrl, responseCode, lastCrawled]).draw();
                              });
                          });

                      }



                  });
                  html += '</ul>';
                  $('#web').html(html);

              }

              if (mobile_data.length == 0) {
                  $('#mobile').html('<div class="webmaster_no_error">Great! No Error Found.</div>');
              } else {
                  var category_html = '';

                  var html = '<ul class="nav nav-tabs">';
                  $.each(mobile_data, function (key, value) {
                      if (key == 0) {
                          html += '<li class="active"><a data-toggle="tab" href="#mobile_' + value.category + '"><i class="glyphicon glyphicon-alert"></i>' + value.category + '<span class="webmaster_count badge badge-primary ">' + value.count + '</span></a> </li>';
                          $.get("https://www.googleapis.com/webmasters/v3/sites/" + webUrl + "/urlCrawlErrorsSamples?category=" + value.category + "&platform=web&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (mcat_data) {


                              category_html += ' <div id="mobile_' + value.category + '" class="tab-pane fade active in"><div class="pagination2"><table class="table table-hover ds-list-table" id="data_table_mobile_' + value.category + '"><thead><tr><th>Priority </th><th>url</th><th>Response Code</th><th>Last Crawled</th></tr></thead></table></div></div>';
                              $('#mobile-content').html(category_html);
                              var oTable3 = $('#data_table_mobile_' + value.category).DataTable({
                                  stateSave: true,
                                  bPaginate: true,
                                  sPaginationType: "full_numbers",
                                  binfo: false,
                                  bFilter: true,
                                  aoColumns: [
                                      {"sWidth": "10%"},
                                      {"sWidth": "60%"},
                                      {"sWidth": "12%"},
                                      {"sWidth": "18%"}
                                  ]});
                              $('#mobile-content').html(category_html);
                              var responseCode = '-';
                              var pageUrl = '-';
                              var lastCrawled = '-';
                              $.each(mcat_data.urlCrawlErrorSample, function (k, val) {
                                  if (val.pageUrl) {
                                      pageUrl = val.pageUrl;
                                  }
                                  if (val.responseCode) {
                                      responseCode = val.responseCode;
                                  }
                                  if (val.last_crawled) {
                                      lastCrawled = val.last_crawled;
                                  }
                                  oTable3.row.add([parseFloat(k + 1), pageUrl, responseCode, lastCrawled]).draw();
                              });
                          });
                      } else {
                          html += '<li><a data-toggle="tab" href="#mobile_' + value.category + '"><i class="glyphicon glyphicon-alert"></i>' + value.category + '<span class="webmaster_count badge badge-primary ">' + value.count + '</span></a> </li>';
                          $.get("https://www.googleapis.com/webmasters/v3/sites/" + webUrl + "/urlCrawlErrorsSamples?category=" + value.category + "&platform=mobile&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (mcat_data) {


                              category_html += ' <div id="mobile_' + value.category + '" class="tab-pane fade in "><div class="pagination2"><table class="table table-hover ds-list-table" id="data_table_mobile_' + value.category + '"><thead><tr><th>Priority </th><th>url</th><th>Response Code</th><th>Last Crawled</th></tr></thead></table></div></div>';
                              $('#mobile-content').append(category_html);
                              var oTable4 = $('#data_table_mobile_' + value.category).DataTable({
                                  stateSave: true,
                                  bPaginate: true,
                                  sPaginationType: "full_numbers",
                                  binfo: false,
                                  bFilter: true,
                                  aoColumns: [
                                      {"sWidth": "10%"},
                                      {"sWidth": "60%"},
                                      {"sWidth": "12%"},
                                      {"sWidth": "18%"}
                                  ]});
                              var responseCode = '-';
                              var pageUrl = '-';
                              var lastCrawled = '-';
                              $.each(mcat_data.urlCrawlErrorSample, function (k, val) {
                                  if (val.pageUrl) {
                                      pageUrl = val.pageUrl;
                                  }
                                  if (val.responseCode) {
                                      responseCode = val.responseCode;
                                  }
                                  if (val.last_crawled) {
                                      lastCrawled = val.last_crawled;
                                  }
                                  oTable4.row.add([parseFloat(k + 1), pageUrl, responseCode, lastCrawled]).draw();
                              });
                          });
                      }

                  });
                  html += '</ul>';
                  $('#mobile').html(html);

              }


              if (smartphone_data.length == 0) {
                  $('#smartphone').html('<div class="webmaster_no_error">Great! No Error Found.</div>');
              } else {
                  var category_html2 = '';

                  var html = '<ul class="nav nav-tabs">';
                  $.each(smartphone_data, function (key, value) {
                      if (key == 0) {
                          html += '<li class="active"><a data-toggle="tab" href="#smartphone_' + value.category + '"><i class="glyphicon glyphicon-alert"></i>' + value.category + '<span class="webmaster_count badge badge-primary ">' + value.count + '</span></a> </li>';
                          $.get("https://www.googleapis.com/webmasters/v3/sites/" + webUrl + "/urlCrawlErrorsSamples?category=" + value.category + "&platform=smartphoneOnly&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (sp_cat_data) {


                              category_html2 += ' <div id="smartphone_' + value.category + '" class="tab-pane fade active in"><div class="pagination2"><table class="table table-hover ds-list-table" id="data_table_smartphone_' + value.category + '"><thead><tr><th>Priority </th><th>url</th><th>Response Code</th><th>Last Crawled</th></tr></thead></table></div></div>';

                              $('.smartphone-content').html(category_html2);
                              var oTable5 = $('#data_table_smartphone_' + value.category).DataTable({
                                  stateSave: true,
                                  bPaginate: true,
                                  sPaginationType: "full_numbers",
                                  binfo: false,
                                  bFilter: true,
                                  aoColumns: [
                                      {"sWidth": "10%"},
                                      {"sWidth": "60%"},
                                      {"sWidth": "12%"},
                                      {"sWidth": "18%"}
                                  ]});
                              var responseCode = '-';
                              var pageUrl = '-';
                              var lastCrawled = '-';
                              $.each(sp_cat_data.urlCrawlErrorSample, function (k, val) {
                                  if (val.pageUrl) {
                                      pageUrl = val.pageUrl;
                                  }
                                  if (val.responseCode) {
                                      responseCode = val.responseCode;
                                  }
                                  if (val.last_crawled) {
                                      lastCrawled = val.last_crawled;
                                  }
                                  oTable5.row.add([parseFloat(k + 1), pageUrl, responseCode, lastCrawled]).draw();
                              });
                          });
                      } else {
                          html += '<li class=""><a data-toggle="tab" href="#smartphone_' + value.category + '"><i class="glyphicon glyphicon-alert"></i>' + value.category + '<span class="webmaster_count badge badge-primary ">' + value.count + '</span></a> </li>';
                          $.get("https://www.googleapis.com/webmasters/v3/sites/" + webUrl + "/urlCrawlErrorsSamples?category=" + value.category + "&platform=web&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (sp_cat_data) {


                              category_html2 += ' <div id="smartphone_' + value.category + '" class="tab-pane fade in "><div class="pagination2"><table class="table table-hover ds-list-table" id="data_table_smartphone_' + value.category + '"><thead><tr><th>Priority </th><th>url</th><th>Response Code</th><th>Last Crawled</th></tr></thead></table></div></div>';
                              $('.smartphone-content').append(category_html2);
                              var oTable6 = $('#data_table_smartphone_' + value.category).DataTable({
                                  stateSave: true,
                                  bPaginate: true,
                                  sPaginationType: "full_numbers",
                                  binfo: false,
                                  bFilter: true,
                                  aoColumns: [
                                      {"sWidth": "10%"},
                                      {"sWidth": "60%"},
                                      {"sWidth": "12%"},
                                      {"sWidth": "18%"}
                                  ]});
                              var responseCode = '-';
                              var pageUrl = '-';
                              var lastCrawled = '-';
                              $.each(sp_cat_data.urlCrawlErrorSample, function (sp_k, sp_val) {
                                  if (sp_val.pageUrl) {
                                      pageUrl = sp_val.pageUrl;
                                  }
                                  if (sp_val.responseCode) {
                                      responseCode = sp_val.responseCode;
                                  }
                                  if (sp_val.last_crawled) {
                                      lastCrawled = sp_val.last_crawled;
                                  }
                                  oTable6.row.add([parseFloat(sp_k + 1), pageUrl, responseCode, lastCrawled]).draw();
                              });
                          });
                      }

                  });
                  html += '</ul>';
                  $('#smartphone').html(html);

              }
              //$('.category-content').html(category_html);
          }
      });
  }  
   
</script>
