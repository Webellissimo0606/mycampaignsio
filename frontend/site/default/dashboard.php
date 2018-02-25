<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header');
?>

<style>
    .score-circle strong {
        position: absolute;
        top: 50px;
        left: 0;
        width: 100%;
        text-align: center;
        line-height: 12px;
        font-size: 14px;
        color: #EAEBEB;
    }
    div#loadingmessage {
        position: absolute;
        font-size: 20px;
        color: #eee;
        margin: 45px;
    }
</style>
<div class="page-container changemycolors">
    <div id="page-content-wrapper">

        <div class="panel_top myonecolor">

            <div class="pane_top_inside">
                <div class="container">
                    <div class="row gt_metrix">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-10 col-sm-10 col-cs-2 text-left">
                                    <h2>Speed Assesment from GT Metrix</h2>
                                </div>
                                <div class="col-md-2 col-sm-2 col-cs-2 text-right">
                                    <div class="refresh_gtmet_data"><i class="fa fa-refresh" style="font-size: 25px;margin-top: 7px"></i></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-5 col-cs-12 text-center">
                                    <img src="http://placehold.it/216x162?text=Loading" class="img-responsive" id="gtmet_image">
                                        
                                </div>

                                <div class="col-md-7 col-sm-7 col-cs-12">

                                    <h3 class="gtm_title">Latest Performance Report - </h3>
                                    <a class="gt_link" id="gt_link" href="" target="_blank"></a>

                                    <div class="info_site">
                                        <label>Report Generated</label><span class="report_data" id="get_cur_data">Sun, Jan 10, 2016, 9.57AM+0000 </span><br>
                                        <label>Test Server Region</label><span class="server_data">
                                            <!--<img class="img-responsive" src="<?php echo base_url() ?>assets/images/uk.png"> London, UK-->
                                        </span><br>
                                        <label>Using</label><span id="browser_det" class="using_data"><img class="img-responsive" id="browser_imag" src=""> </span>
                                    </div></div>

                                <div class="clearfix"></div>
                                <div class="block-sec">

                                    <div class="col-lg-5 col-md-7 col-sm-12 col-xs-12">
                                        <h3>Performance Scores</h3>
                                        <div class="left_pane">


                                            <div class="pane">
                                                <h5>PageSpeed </h5>
                                                <div class="speed_stat" id="PageSpeed_scores"><span class="load"></span></div>
                                            </div>
                                            <div class="pane">
                                                <h5>Y Slow Scores </h5>
                                                <div class="speed_stat" id="yslow_scores"><span class="load"></span></div>

                                            </div>

                                        </div></div>
                                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 ">
                                        <h3>Page Details</h3>
                                        <div class="right_pane ">


                                            <div class="pane">
                                                <h5>Load Time </h5>
                                                <div class="speed_stat" id="Pg_loa_tim"><span class="load"></span></div>
                                            </div>
                                            <div class="pane">
                                                <h5> Page Size</h5>
                                                <div class="speed_stat" id="to_pg_sz"><span class="load"></span></div>
                                            </div>
                                            <div class="pane">
                                                <h5>Requests</h5>
                                                <div class="speed_stat" id="rq"><span class="load"></span></div>
                                            </div>


                                        </div></div>
                                    <div class="clearfix"></div>
                                </div>


                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="resultHeader" style="margin-top:0;max-height: 162px">


                                <div class="row" id="uptime_stats_graph">
                                 <script type="html/tpl" id="uptime_stats_template"> 

                                    <div class="col-md-9 col-sm-6">
                                        <header>
                                            <h3> <i class="fa fa-line-chart"></i>
                                                Uptime<span> Last 24 Hours</span></h3>
                                            <div class="uptimne_bar"></div>

                                            <h3> <i class="fa fa-line-chart"></i>
                                                Response Time <span>Last 24 Hours</span></h3>
                                            <p>Average time in milliseconds for your domain to respond.</p></header>
                                        <div class="response-time-graph ">
                                            <canvas height="58" id="canvas-response-time-graph"/>
                                        </div>

                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="gt_matrix_subblock">
                                            <span><i class="fa fa-dot-circle-o"></i>
                                                Current Status</span>
                                            <h3 class="up_stat"><i class="fa fa-dot-circle-o"></i> Up
                                            </h3>
                                            <span class="gt_prev_time">Up for {uptime_stats_uptime_hours} hours ( {uptime_stat_current_date} )</span>
                                        </div>


                                        <div class="gt_matrix_subblock">
                                            <span><i class="fa fa-bar-chart"></i>

                                                Uptime</span>
                                            <span class="gt_prev_uptime"><span>{uptime_stats_24_per}%</span>(Last 24 Hours)</span>
                                            <span class="gt_prev_uptime"><span>{uptime_stats_7_per}%</span>(Last 7 Days)</span>
                                            <span class="gt_prev_uptime"><span>{uptime_stats_30_per}%</span>(Last 30 Days)</span>
                                        </div>
                                        <div class="gt_matrix_subblock gt_downtime">
                                            <span><i class="fa fa-dot-circle-o"></i>

                                                Latest Downtime</span>
                                            <p>Recorded on dd/mmm/yyyy</p>
                                        </div>
                              <a href="/uptime/statusreport"><button type="button" class="btn btn-primary mycustombutton">More Info</button></
                                    </div>

                                 </script>   
                                </div>


                            </div>
							
							    <div id="uptime_stat" class="panel">
                      <script type="html/tpl" id="uptime_stats_template_2"> 
                            <header><i class="fa fa-clock-o"></i> Domain Uptime Stats <a href="/uptime/statusreport"><button type="button" class="btn btn-primary mycustombutton2">More Info</button></a> </header>
                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="content">
                                        <h4><i class="fa fa-clock-o"></i>{uptime_stats_24_per} %</h4>
                                        <p>Last 24 Hours</p>
                                    </div>

                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="content">
                                        <h4><i class="fa fa-calendar"></i>
                                            {uptime_stats_7_per} %</h4>
                                        <p>Last 7 Days</p>
                                    </div>

                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <div class="content">
                                        <h4><i class="fa fa-calendar"></i>
                                            {uptime_stats_30_per} %</h4>
                                        <p>Last 30 Days</p>
                                    </div>

                                </div></div>
                               </script> 

                        </div>
							
                        </div>






                    </div>
                </div>
            </div>
        </div>  </div>
    <div class="empty_block_custom"></div>
    <div class="panel_top myonecolor" id="panel_template_keyword_stats">
 <script type="html/tpl" id="template_keyword_stats">


   <div class="pane_top_inside">
       <div class="container">
           <div class="row">
               <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                   <h4 class="panel-title">Average Position</h4>
                   <div>
                      <div class="c100 p100 small green">
							<span> {keyword_average_position}</span>
							<div class="slice">
								<div class="bar"></div>
								<div class="fill"></div>
							</div>
						</div>
                   </div>
               </div>
               <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                   
                   <h4 class="panel-title">Keyword Position</h4>
                   
                   <div class="makemeright">
						<div class="c100 p70 small">
							<span>{keyword_top10}</span>
							<div class="slice">
								<div class="bar"></div>
								<div class="fill"></div>
							</div>
						</div>
                       <p>Top 10</p>
                   </div>
                   <div class="makemeright">
						<div class="c100 p70 small orange">
							<span>{keyword_top20}</span>
							<div class="slice">
								<div class="bar"></div>
								<div class="fill"></div>
							</div>
						</div>
                       <p>Top 20</p>
                   </div>
                    <div class="makemeright makethatfifty">
						<div class="c100 p70 small green ">
							<span>{keyword_top50}</span>
							<div class="slice">
								<div class="bar"></div>
								<div class="fill"></div>
							</div>
						</div>
                       <p>Top 50</p>
                   </div>
               </div>
               <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 last_stat-box">
                   
                   <h4 class="panel-title">Keyword Change</h4>
                   <div class="makemeright">
						<div class="c100 p70 small green">
							<span>{keyword_positive}</span>
							<div class="slice">
								<div class="bar"></div>
								<div class="fill"></div>
							</div>
						</div>
                       <p>
                           Up
                       </p>
                   </div>
                   <div class="makemeright makethatdown">
						<div class="c100 p70 small">
							<span>{keyword_negative}</span>
							<div class="slice">
								<div class="bar"></div>
								<div class="fill"></div>
							</div>
						</div>
                       <p>
                           Down
                       </p>
                   </div>
                   <div class="makemeright">
						<div class="c100 p70 small ">
							<span>{keyword_nochange}</span>
							<div class="slice">
								<div class="bar"></div>
								<div class="fill"></div>
							</div>
						</div>
                       <p>
                           No change
                       </p>
                   </div>
               </div>
               <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 last_stat-box">
                   
                   <h4 class="panel-title">Total Keywords</h4>
                   <div>
						<div class="c100 p90 small green">
							<span>{keyword_total_search}</span>
							<div class="slice">
								<div class="bar"></div>
								<div class="fill"></div>
							</div>
						</div>
                      
                   </div>
               </div>
               <a href="<?php echo site_url('auth/viewSerp'); ?>"><button type="button" class="btn btn-primary mycustombutton3">More Info</button></a>
           </div>
       </div>

        </script>
    </div>
    <div class="panel_top myonecolor">

        <div class="pane_top_inside">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
<!--                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div id="canvas-holder" class='chart-area-heading'>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12 ">
                                <ul class="chart_stats_inside">

                                </ul>
                            </div>
                        </div>-->

<div class="graph-box resultHeader">
    <header><h3> <i class="fa fa-line-chart"></i>
                                    Google Analytics <a href="<?php echo site_url('analytics/analytics'); ?>"><button type="button" class="btn btn-primary mycustombutton">More Info</button></a> </h3>
                                    <p>Session information of your websites. Last 14 days.</p></header>
                                    <div style="margin-top:10px"><canvas id="chart" height="135" width = "559" ></canvas></div>
                        </div>

                    </div>


                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div id="dare_boost" class="resultHeader">
                            <header><h3> <i class="fa fa-line-chart"></i>
                                    ON Page Analysis <a href="<?php echo site_url('auth/analyze'); ?>"><button type="button" class="btn btn-primary mycustombutton">More Info</button></a> </h3>
                                <p>Snap shot of your websites onpage performance.</p></header>
                            <div id="loadingmessage">
                                Please be patient while we analyse your domain.
                            </div>
                            <div class="col-sm-4 text-center score-data">
                                <div class="score-circle" style="padding-top: 10px">
                                    <strong></strong>
                                </div>

                                <div class="scroTitleDashboard score" style="padding-top: 20px;color: #2B3D4F;">Overall Score</div>
                            </div>

                            <div class="col-sm-4 firstbyte" data-firstbytepercent="" data-firstbyte="" data-color="" style="margin-left: -10px">
                                <canvas id="firstbyte" height="110" >
                                </canvas>
                                <div class="scroTitleDashboard" style="color:#2B3D4F;"><a href="#" data-toggle="tooltip" style="color:#2B3D4F;" title="Google recommends a time less than 200 ms (represented in gray)">?</a> Time to First Byte</div>

                            </div>

                            <div class="col-sm-4 loadtime" data-timepercent="" data-time="" data-color="" style="margin-left: -10px">
                                <canvas id="loadtime" height="110">
                                </canvas>
                                <div class="scroTitleDashboard" style="color:#2B3D4F;"><a href="#" data-toggle="tooltip" style="color:#2B3D4F;" title="67% of users demand that a page must be loaded within 4 seconds (represented in gray)">?</a> Page Load Time</div>

                            </div>

                        </div>
                    
                    </div>
                </div>


            </div>
        </div>  </div> 

    <div class="panel_top myonecolor">

        <div class="pane_top_inside">
            <div class="container">
                <div id="gm_tool" class="row keywords-style">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h2>Google Master Tool</h2>
                        <div class="container google-webmaster-not">
                            Google Webmaster Analysis Not Selected
                        </div>
                        <div class="container google-webmaster hide">
                            <ul class="nav nav-tabs" id="interest_tabs">
                                <!--top level tabs-->
                                <li  class="active"><a data-toggle="tab" href="#web"><i class="fa fa-question-circle"></i>

                                        Web</a></li>
                                <li><a data-toggle="tab" href="#mobile"><i class="fa fa-question-circle"></i>

                                        Mobile</a></li>
                                <li><a data-toggle="tab" href="#smartphone"><i class="fa fa-question-circle"></i>

                                        Smart Phone</a></li>
                            </ul>

                            <!--top level tab content-->
                            <div class="tab-content">
                                <!--all tab menu-->
                                <div id="web" class="tab-pane active in">

                                </div>

                                <!--brands tab menu-->
                                <div id="mobile" class="tab-pane">

                                </div>

                                <div id="smartphone" class="tab-pane">

                                </div>


                            </div>

                            <!--all web content-->
                            <div class="tab-content web-content">

                            </div>

                            <!--mobile tab content-->
                            <div class="tab-content mobile-content">

                            </div>

                            <!--smartphone tab content-->
                            <div class="tab-content smartphone-content">

                            </div>
                        </div>
                    </div>

                </div>
            </div>  </div></div>

    <div class="shdow_bottom">
        <div class="container">
            <img src="assets/images/shodow-bottom.png" alt=""/></div>
    </div> </div>

<script>
    
     $('#interest_tabs').on('click', 'a[data-toggle="tab"]', function (e) {
        e.preventDefault();

        var $link = $(this);

        if (!$link.parent().hasClass('active')) {

            //remove active class from other tab-panes
            $('.tab-content:not(.' + $link.attr('href').replace('#', '') + ') .tab-pane').removeClass('active');

            // click first submenu tab for active section
            $('a[href="' + $link.attr('href') + '_all"][data-toggle="tab"]').click();

            // activate tab-pane for active section
            $('.tab-content.' + $link.attr('href').replace('#', '') + ' .tab-pane:first').addClass('active');
            $('.tab-content:not(#' + $link.attr('href').replace('#', '') + ') li').removeClass('active');
            $('#' + $link.attr('href').replace('#', '') + ' li:first').addClass('active');
            var $act = $('#' + $link.attr('href').replace('#', '') + ' li.active a:first');
            $('#' + $act.attr('href').replace('#', '')).addClass('active');
            console.log(as.attr('href').replace('#', ''));
        }


    });
    
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
    $(".progressbars").jprogress();
    $(".progressbarsone").jprogress({
        background: "#FF2D55"
    });

    $(document).ready(function () {

        $.post('/auth/analyze/getkeywordreport', {domain_id:<?php echo $domain_id; ?>}, function (data) {
            var TPL = $('#template_keyword_stats').html(),
                    keyword_average_position = Math.round(data.avg_position['average_position']);
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
        }, 'json')


        $.post('/auth/uptimestats', {domain_id:<?php echo $domain_id; ?>}, function (data) {
            var TPL = $('#uptime_stats_template').html(),
            TPL = TPL.replace(/{uptime_stats_24_per}/g, Math.round(data.uptime1daypercentage*1));
            TPL = TPL.replace(/{uptime_stats_7_per}/g, Math.round(data.uptime7daypercentage*1));
            TPL = TPL.replace(/{uptime_stats_30_per}/g, Math.round(data.uptime30daypercentage*1));
            TPL = TPL.replace(/{uptime_stats_uptime_hours}/g, data.totaluptimehours*1);
            TPL = TPL.replace(/{uptime_stat_current_date}/g, data.current_date);
            $('#uptime_stats_graph').append(TPL);


            var splineData = [];
            var splineLabel = [];
            for(var i=0;i<data.uptimedaystats.length;i++){
              splineData.push(Math.round((data.uptimedaystats[i].load_time*1)));
              splineLabel.push(data.uptimedaystats[i].completed_on_time);
            }

            var lineChartData = {
                labels: splineLabel,
                datasets: [
                    {
                        label: "My Second dataset",
                        fillColor: "rgba(151,187,205,0.2)",
                        strokeColor: "rgba(151,187,205,1)",
                        pointColor: "rgba(151,187,205,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data: splineData
                    }
                ]

            }

            var ctx1 = $("#canvas-response-time-graph").get(0).getContext("2d");
            window.myLine = new Chart(ctx1).Line(lineChartData, {
                responsive: true, scaleOverride: true, scaleStartValue: 0, scaleStepWidth: 1200, scaleSteps: 4
            });

            var TPL1 = $('#uptime_stats_template_2').html(),
            TPL1 = TPL1.replace(/{uptime_stats_24_per}/g, Math.round(data.uptime1daypercentage*1));
            TPL1 = TPL1.replace(/{uptime_stats_7_per}/g, Math.round(data.uptime7daypercentage*1));
            TPL1 = TPL1.replace(/{uptime_stats_30_per}/g, Math.round(data.uptime30daypercentage*1));
            $('#uptime_stat').append(TPL1);

        }, 'json')





    })
</script><script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
<script>



    $(document).ready(function () {
        var location = '';
        if (geoplugin_city()) {
            location = geoplugin_city() + ", " + geoplugin_countryName();
        } else {
            location = geoplugin_countryName();
        }
        $('.server_data').append(location);


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
        getAnalyticsData();
        var web_url = $('#adv-search input').val();
        $('.gt_link').empty();
        $('.gt_link').append(web_url);
        $('.load').html('Loading ...');
        $('#get_cur_data').empty();

        $.post(siteUrl + "auth/auth/gtmetrix",
                {
                    'gtmetrix': web_url,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                function (data, status) {
                    if(data.status){
                        $('.load').html('');
                        var datas = $.parseJSON(data.metrix);
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
                          $('#PageSpeed_scores').append(pageSpeedGrade + '(' + datas.pagespeed_score + '%)');
                        $('#yslow_scores').append(YSlowScoreGrade + '(' + datas.yslow_score + '%)');
                        $('#Pg_loa_tim').append(Math.round(datas.page_load_time / 1000 * 100) / 100 + 's');
                        $('#to_pg_sz').append(bytesToSize(datas.page_bytes));
                        $('#rq').append(datas.page_elements);
                        $('#gt_link').attr("href", datas.report_url);

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
                url: siteUrl + "auth/auth/refreshGTMetrixCount",
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
         console.log(webmaster);
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

    });

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
            url: siteUrl + "auth/analyze/ajax_analyze_data",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', url: url},
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
                    context.fillStyle = '#eee';
                    context.font = "bold 16px Arial";
                    context.fillText(first_byte.toFixed(2), 65, 50);
                    context.beginPath();
                    context.font = "bold 16px Arial";
                    context.fillStyle = '#eee';
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
                    context.fillStyle = '#eee';
                    context.font = "bold 16px Arial";
                    context.fillText(load.toFixed(2), 65, 50);
                    context.beginPath();
                    context.font = "bold 16px Arial";
                    context.fillStyle = '#eee';
                    context.fillText("sec", 65, 70);
                } else {
                    $('.scroTitleDashboard').hide();
                    $('#loadingmessage').html('Not Opted For Monitoring On Page Issues.');
                    $('#loadingmessage').show();

                }
            }
        });
    }

    function getAnalyticsData() {
        var property = [];
//            var prop = 0;
        var prop;
        var url = jQuery('.domain-selected').val();

        $.ajax({
            type: "POST",
            url: siteUrl + "analytics/analytics/getUserAccounts",
            dataType: 'json',
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', url: url},
            success: function (dat) {
                var data = dat.views_data;
                if (data.hasOwnProperty('items')) {
                    var viewId = data.items[0].id;
                    var accessToken = dat.accToken;

                    //https://www.googleapis.com/analytics/v3/data/ga?start-date=30daysAgo&end-date=yesterday&metrics=ga:sessions,ga:pageviews&dimensions=ga:date,ga:country,ga:browser
                    $.get("https://www.googleapis.com/analytics/v3/data/ga?start-date=14daysAgo&end-date=yesterday&metrics=ga:sessions&dimensions=ga:date&ids=ga:" + viewId + "&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (data) {
                        var lineChartData = [];
                        $.each(data.rows, function (key, value) {
                            lineChartData.push({'date': value[0], 'value': value[1]});
                              });
                        sessionLineChartByDate(lineChartData);
                    });

                } else {
                    $('.chart-area-heading').html('<h2>Google Analytics Data Not Available.</h2>');
                }
            }
        });
    }

    function sessionLineChartByDate(lineChartData) {
            var labels = [];
           
            var data = [];
            var d =new Date("2015-03-25");
            $.each(lineChartData, function (key, value) {
                
//                  d = new Date(value.date.slice(0,4)+'-'+value.date.slice(4,6)+'-'+value.date.slice(-2));
//                labels.push($.format.date(d, "ddd, dd MMMM yyyy"));
                labels.push(value.date.slice(-2));
                data.push(value.value);
            });
            var chartData = {
                labels: labels,
                datasets: [
                    {
                        label: "Session",
                        fillColor: "rgba(151,187,205,0.2)",
                        strokeColor: "#058DC7",
                        pointColor: "#058DC7",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data: data
                    }
                ]
            }
             var ctx = $("#chart").get(0).getContext("2d");
           var myLineChart = new Chart(ctx).Line(chartData, {animationSteps: 50,
                        tooltipYPadding: 3,
                        tooltipXPadding: 3,
                        tooltipCornerRadius: 0,
                        tooltipTitleFontStyle: 'small',
                        tooltipFillColor: '#3D4049',
                        animationEasing: 'easeOutBounce',
                        scaleFontSize: 10,
                        scaleShowVerticalLines: false,
                        scaleShowHorizontalLines: false,
                        pointDot: true,
//                        showScale: false,
                        bezierCurve: false,
                        scaleFontColor: "#fff",
//                        responsive: true,
                        tooltipTemplate: "<% if (datasetLabel) { %><%= datasetLabel %> - <% } %><%= value %>",
                                    multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
                        });


        }
    

    
     function getCrawlErrors() {
                                var url = jQuery('.domain-selected').val();
                                $.ajax({
                                    type: "POST",
                                    url: siteUrl + "analytics/analytics/getCrawlErrors",
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
                                        var webUrl = encodeURIComponent('<?php echo $this->session->userdata("domainHost") ?>');

                                        if (web_data.length == 0) {
                                            $('#web').html('<div class="webmaster_no_error">Great! No Error Found.</div>');
                                        } else {
                                            var html = '<ul class="nav nav-tabs">';
                                            var category_html1 = '';

                                            $.each(web_data, function (key, value) {
                                                if (key == 0) {
                                                    html += '<li  class="active"><a data-toggle="tab" href="#web_' + value.category + '"><i class="fa fa-question-circle"></i>' + value.category + '<span class="webmaster_count">' + value.count + '</span></a> </li>';
                                                    $.get("https://www.googleapis.com/webmasters/v3/sites/" + webUrl + "/urlCrawlErrorsSamples?category=" + value.category + "&platform=web&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (cat_data) {


                                                        category_html1 += '<div id="web_' + value.category + '" class="tab-pane fade in active"><div class="table-responsive"><table class="table" id="data_table_web_' + value.category + '"><thead><tr><th>Priority </th><th>url</th><th>Response Code</th><th>Last Crawled</th></tr></thead></table></div></div>';
                                                        $('.web-content').html(category_html1);
                                                        var oTable1 = $('#data_table_web_' + value.category).DataTable({
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
                                                            oTable1.fnAddData([parseFloat(k + 1), pageUrl, responseCode, lastCrawled]);
                                                        });
                                                    });



                                                } else {
                                                    html += '<li><a data-toggle="tab" href="#web_' + value.category + '"><i class="fa fa-question-circle"></i>' + value.category + '<span class="webmaster_count">' + value.count + '</span></a> </li>';
                                                    $.get("https://www.googleapis.com/webmasters/v3/sites/" + webUrl + "/urlCrawlErrorsSamples?category=" + value.category + "&platform=web&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (cat_data) {

                                                        category_html1 += ' <div id="web_' + value.category + '" class="tab-pane fade in "><div class="table-responsive"><table class="table" id="data_table_web_' + value.category + '"><thead><tr><th>Priority </th><th>url</th><th>Response Code</th><th>Last Crawled</th></tr></thead></table></div></div>';
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
                                                            oTable2.fnAddData([parseFloat(k + 1), pageUrl, responseCode, lastCrawled]);
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
                                                    html += '<li><a data-toggle="tab" href="#mobile_' + value.category + '"><i class="fa fa-question-circle"></i>' + value.category + '<span class="webmaster_count">' + value.count + '</span></a> </li>';
                                                    $.get("https://www.googleapis.com/webmasters/v3/sites/" + webUrl + "/urlCrawlErrorsSamples?category=" + value.category + "&platform=web&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (mcat_data) {


                                                        category_html += ' <div id="mobile_' + value.category + '" class="tab-pane fade in"><div class="table-responsive"><table class="table" id="data_table_mobile_' + value.category + '"><thead><tr><th>Priority </th><th>url</th><th>Response Code</th><th>Last Crawled</th></tr></thead></table></div></div>';
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
                                                            oTable3.fnAddData([parseFloat(k + 1), pageUrl, responseCode, lastCrawled]);
                                                        });
                                                    });
                                                } else {
                                                    html += '<li><a data-toggle="tab" href="#mobile_' + value.category + '"><i class="fa fa-question-circle"></i>' + value.category + '<span class="webmaster_count">' + value.count + '</span></a> </li>';
                                                    $.get("https://www.googleapis.com/webmasters/v3/sites/" + webUrl + "/urlCrawlErrorsSamples?category=" + value.category + "&platform=mobile&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (mcat_data) {


                                                        category_html += ' <div id="mobile_' + value.category + '" class="tab-pane fade in "><div class="table-responsive"><table class="table" id="data_table_mobile_' + value.category + '"><thead><tr><th>Priority </th><th>url</th><th>Response Code</th><th>Last Crawled</th></tr></thead></table></div></div>';
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
                                                            oTable4.fnAddData([parseFloat(k + 1), pageUrl, responseCode, lastCrawled]);
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
                                                    html += '<li><a data-toggle="tab" href="#smartphone_' + value.category + '"><i class="fa fa-question-circle"></i>' + value.category + '<span class="webmaster_count">' + value.count + '</span></a> </li>';
                                                    $.get("https://www.googleapis.com/webmasters/v3/sites/" + webUrl + "/urlCrawlErrorsSamples?category=" + value.category + "&platform=smartphoneOnly&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (sp_cat_data) {


                                                        category_html2 += ' <div id="smartphone_' + value.category + '" class="tab-pane fade in"><div class="table-responsive"><table class="table" id="data_table_smartphone_' + value.category + '"><thead><tr><th>Priority </th><th>url</th><th>Response Code</th><th>Last Crawled</th></tr></thead></table></div></div>';

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
                                                            oTable5.fnAddData([parseFloat(k + 1), pageUrl, responseCode, lastCrawled]);
                                                        });
                                                    });
                                                } else {
                                                    html += '<li><a data-toggle="tab" href="#smartphone_' + value.category + '"><i class="fa fa-question-circle"></i>' + value.category + '<span class="webmaster_count">' + value.count + '</span></a> </li>';
                                                    $.get("https://www.googleapis.com/webmasters/v3/sites/" + webUrl + "/urlCrawlErrorsSamples?category=" + value.category + "&platform=web&access_token=" + accessToken, {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'}, function (sp_cat_data) {


                                                        category_html2 += ' <div id="smartphone_' + value.category + '" class="tab-pane fade in "><div class="table-responsive"><table class="table" id="data_table_smartphone_' + value.category + '"><thead><tr><th>Priority </th><th>url</th><th>Response Code</th><th>Last Crawled</th></tr></thead></table></div></div>';
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
                                                            oTable6.fnAddData([parseFloat(sp_k + 1), pageUrl, responseCode, lastCrawled]);
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
