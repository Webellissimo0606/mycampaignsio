<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header');
?>

<div class="page-container-fluid">
    <!-- Content -->
    <div class="page-content">
        <div class="page-content-inner">
            <div class="panel_top">
                <div class="container">
                    <!-- Page header -->


                    <div class="row">

                        <!--  <div class="col-sm-12 col-lg-12 col-xs-12">
                              <div class="analyzeForm">
                                  <h1 class="text-center text-uppercase">
                                      Analyze and Optimize your website<br/>
                                      <small>Web Performance, SEO, Security, Quality and more</small>
                                  </h1>
                        <?php
                        $attributes = array('id' => 'analyzeForms');
                        echo form_open(site_url('auth/analyze/get_google_page_speed_result'), $attributes)
                        ?>
                                  <div class="inputHolder">
                                      <i class="fa fa-dashboard"></i>
                                      <input type="url" value="<?php if (isset($report['url']) && !empty($report['url'])) echo $report['url'] ?>" id="ana_url" name="ana_url" placeholder="http://"/>
                                      <button type="submit" name="ana_submit">Analyze</button>
                                  </div>
    
                        <?php echo form_close(); ?>
                                  <div id="resultss">
    
                                  </div>
                              </div>
    
                          </div>-->

                        <?php
                        if (!empty($report)) {
                            //$reports = $this->session->userdata('reports');
                            $rep_tips = array();
                            $score = $report['summary']['score'];
                            $total_issues = $total_improvements = $total_successes = $total_checks = 0;
                            ?>
                            <div class="clearfix"></div>
                            <div class="col-lg-12">
                                <div class="location-header">
                                    <div class="col-sm-3 browser">
                                        <?php if ($report['config']['browser']['name'] == 'Firefox') { ?>
                                            <i class="fa fa-firefox"></i>
                                            <span><?php echo $report['config']['browser']['name'] ?></span>
                                        <?php } ?>
                                        <?php if ($report['config']['browser']['name'] == 'Edge') { ?>
                                            <i class="fa fa-edge"></i>
                                            <span><?php echo $report['config']['browser']['name'] ?></span>
                                        <?php } ?>
                                        <?php if ($report['config']['browser']['name'] == 'Safari') { ?>
                                            <i class="fa fa-safari"></i>
                                            <span><?php echo $report['config']['browser']['name'] ?></span>
                                        <?php } ?>
                                        <?php if ($report['config']['browser']['name'] == 'Chrome') { ?>
                                            <i class="fa fa-chrome"></i>
                                            <span><?php echo $report['config']['browser']['name'] ?></span>
                                        <?php } ?>
                                        <?php if ($report['config']['browser']['name'] == 'Internet Explorer') { ?>
                                            <i class="fa fa-internet-explorer"></i>
                                            <span><?php echo $report['config']['browser']['name'] ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-3 location">
                                        <i class="fa fa-globe"></i>
                                        <span><?php echo $report['config']['location'] ?></span>
                                    </div>
                                    <div class="col-sm-3 bandwidth">
                                        <span><i class="fa fa-long-arrow-down"></i></span><span><i class="fa fa-long-arrow-up"></i></span>
                                        <span><?php echo number_format($report['config']['bandwidth']['downstream'] / 1000, 1); ?>/<?php echo number_format($report['config']['bandwidth']['upstream'] / 1000), 1; ?> Mbps</span>
                                    </div>
                                    <div class="col-sm-3 latency">
                                        <span>Latency: <?php echo $report['config']['latency'] ?> ms</span>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="panel_top">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="resultHeader">
                                    <div class="col-sm-4 text-center">
                                        <div class="cmskill1" data-skills="<?php echo ($score / 100); ?>" data-gradientstart="#fc6f5c" data-gradientend="#be4130">
                                            <strong></strong>
                                        </div>
                                        <div class="scroTitle">Score</div>
                                    </div>
                                    <script>
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
                                        var score = parseFloat($('.cmskill1').data("skills"));
                                        $('.cmskill1').circleProgress({
                                            value: score,
                                            size: 100,
                                            fill: {gradient: [grad1, grad2]}
                                        }).on('circle-animation-progress', function (event, progress) {
                                            $(this).find('strong').html(parseInt(100 * score) + '/100');
                                        });
                                    </script>
                                    <?php
                                    $color = "#eee";
                                    $firstbyte = (($report['performanceTimings']['firstByte'] - $report['performanceTimings']['navigationStart']) / 60) / 10;
                                    $first_byte_ms = ($firstbyte * 1000);
                                    $firstbyte_percent = ($first_byte_ms / 200) * 100;
                                    if ($firstbyte_percent < 35) {
                                        $color = "#00eb00";
                                    } else if ($firstbyte_percent > 35 && $firstbyte_percent < 85) {
                                        $color = "#e07100";
                                    } else {
                                        $color = "#FF7474";
                                    }
                                    ?>
                                    <div class="col-sm-4 firstbyte" data-firstbytepercent="<?php echo $firstbyte_percent; ?>" data-firstbyte="<?php echo $firstbyte ?>" data-color="<?php echo $color ?>">
                                        <canvas id="firstbyte" width="150" height="210">
                                        </canvas>
                                        <div class="scroTitle"><a href="#" data-toggle="tooltip" title="Google recommends a time less than 200 ms (represented in gray)">?</a>  First Byte</div>
                                        <script>
                                            var loadtimepercent = $('.firstbyte').data('firstbytepercent');
                                            var loadtime = $('.firstbyte').data('firstbyte');
                                            var loadtime_ms = "<?php echo $first_byte_ms ?>";
                                            var color = $('.firstbyte').data('color');
                                            var endanglepercent = (((13 / 100) * loadtimepercent) / 10).toFixed(1);
                                            var endangleinner = 1.5 + parseFloat(endanglepercent);
                                            var endAngle1 = 0;
                                            if (endangleinner.toString().split(".")[0] > 1 && loadtime_ms < 200) {
                                                endAngle1 = '0.' + endangleinner.toString().split(".")[1];

                                            } else if (loadtime_ms > 200) {
                                                endAngle1 = '1.0';
                                            } else {
                                                endAngle1 = endangleinner.toString().split(".")[0] + '.' + endangleinner.toString().split(".")[1];
                                            }

                                            var canvas = document.getElementById('firstbyte');
                                            var context = canvas.getContext('2d');
                                            var x = canvas.width / 2;
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
                                            var x1 = (canvas.width) / 2;
                                            var y1 = (canvas.height) / 2;
                                            var radius1 = 40;
                                            var startAngle1 = 1.5 * Math.PI;
                                            var endAngle1 = parseFloat(endAngle1) * Math.PI;
                                            context.beginPath();
                                            context.arc(x1, y1, radius1, startAngle1, endAngle1, counterClockwise);
                                            context.lineWidth = 10;

                                            // line color
                                            context.strokeStyle = color;
                                            context.stroke();
                                            context.beginPath();
                                            context.fillStyle = '#eee';
                                            context.font = "bold 12px Arial";
                                            context.fillText(loadtime.toFixed(2), 60, 100);
                                            context.beginPath();
                                            context.font = "bold 12px Arial";
                                            context.fillStyle = '#eee';
                                            context.fillText("sec", 60, 120);
                                        </script>
                                    </div>
                                    <?php
                                    $load_percent = 0;
                                    $color = "#eee";
                                    if (!empty($report)) {
                                        $load = $report['summary']['loadTime'] / 1000;

                                        $load_percent = ($load / 4) * 100;
                                        if ($load_percent < 25) {
                                            $color = "#00eb00";
                                        } else if ($load_percent > 25 && $load_percent < 85) {
                                            $color = "#e07100";
                                        } else {
                                            $color = "#FF7474";
                                        }
                                    }
                                    ?>
                                    <div class="col-sm-4 loadtime" data-timepercent="<?php echo $load_percent; ?>" data-time="<?php echo $load ?>" data-color="<?php echo $color ?>">
                                        <canvas id="loadtime" width="150" height="210">
                                        </canvas>
                                        <div class="scroTitle"><a href="#" data-toggle="tooltip" title="67% of users demand that a page must be loaded within 4 seconds (represented in gray)">?</a>  Load Time</div>
                                        <script>
                                            var loadtimepercent = $('.loadtime').data('timepercent');
                                            var loadtime = $('.loadtime').data('time');
                                            var color = $('.loadtime').data('color');
                                            var endanglepercent = (((13 / 100) * loadtimepercent) / 10).toFixed(1);
                                            var endangleinner = 1.5 + parseFloat(endanglepercent);
                                            var endAngle1 = 0;
                                            if (endangleinner.toString().split(".")[0] > 1 && loadtime < 4) {
                                                endAngle1 = '0.' + endangleinner.toString().split(".")[1];

                                            } else if (loadtime > 4) {
                                                endAngle1 = '1.0';
                                            } else {
                                                endAngle1 = endangleinner.toString().split(".")[0] + '.' + endangleinner.toString().split(".")[1];
                                            }

                                            console.log(loadtimepercent);
                                            var canvas = document.getElementById('loadtime');
                                            var context = canvas.getContext('2d');
                                            var x = canvas.width / 2;
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
                                            var x1 = (canvas.width) / 2;
                                            var y1 = (canvas.height) / 2;
                                            var radius1 = 40;
                                            var startAngle1 = 1.5 * Math.PI;
                                            var endAngle1 = parseFloat(endAngle1) * Math.PI;
                                            context.beginPath();
                                            context.arc(x1, y1, radius1, startAngle1, endAngle1, counterClockwise);
                                            context.lineWidth = 10;

                                            // line color
                                            context.strokeStyle = color;
                                            context.stroke();
                                            context.beginPath();
                                            context.fillStyle = '#eee';
                                            context.font = "bold 12px Arial";
                                            context.fillText(loadtime.toFixed(2), 60, 100);
                                            context.beginPath();
                                            context.font = "bold 12px Arial";
                                            context.fillStyle = '#eee';
                                            context.fillText("sec", 60, 120);
                                        </script>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-sm-12">

                                        <div id="fileCaptions">
                                            <span class="filecapItem filecapItemHtml"></span> HTML
                                            <span class="filecapItem filecapItemCss"></span>CSS
                                            <span class="filecapItem filecapItemScript"></span>Scripts
                                            <span class="filecapItem filecapItemImage"></span>Images
                                            <span class="filecapItem filecapItemOther"></span>Others
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
								
								 <div class="empty-block"></div>
                                <div class="row">
                                    <div class="col-lg-4  col-md-4 col-sm-4 col-xs-12">
                                        <div class="grey cirle_stat_style_1">
                                            <h2>25</h2>
                                            <span> issues</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="orng cirle_stat_style_1">
                                            <h2>2</h2>
                                            <span> Improvments</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4  col-md-4 col-sm-4 col-xs-12">
                                        <div class="grn cirle_stat_style_1">
                                            <h2>5</h2>
                                            <span> Success</span>
                                        </div>
                                    </div>


                                </div>
								<div class="empty-block"></div>
								
								
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                               
							     <div class="footer_panel">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3>Best practices summary</h3>
                            </div>
                            <div class="summaryHeader">
                                <?php
                                if (!empty($report['categories'])) {

                                    foreach ($report['categories'] as $key => $category) {
                                        $checks = 0;
                                        $issues = 0;
                                        $improve = 0;
                                        if (!empty($report['tips'])) {

                                            foreach ($report['tips'] as $k => $tips) {

                                                if ($tips['category'] == $category['name']) {
                                                    $rep_tips[$tips['category']][] = $tips;
                                                    $checks++;
                                                    if ($tips['score'] == 0 || $tips['score'] == '-1') {
                                                        $issues++;
                                                    }
                                                    if ($tips['score'] > 0 && $tips['score'] < 100) {
                                                        $improve++;
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                        <div class="col-sm-3"><div class="subSummaryHeader" data-name="<?php echo $category['name']; ?>" id="subSummaryHeader_<?php echo $key; ?>">

                                                <h5><?php echo $category['name'] ?></h5>
                                                <div class="subSummaryHeaderIssues">
                                                    <?php
                                                    if ($issues != 0 && $improve == 0) {
                                                        echo $issues . ' issues';
                                                        $total_issues += $issues;
                                                    }
                                                    if ($improve != 0) {
                                                        echo $improve . ' improv';
                                                        $total_improvements += $improve;
                                                    }
                                                    ?>
                                                </div>
                                                <div class="subSummaryHeaderChecks">
                                                    <?php
                                                    if ($checks != 0) {
                                                        echo $checks . ' checks';
                                                        $total_checks += $checks;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>


                        <?php $total_successes = $total_checks - ($total_improvements + $total_issues); ?>
                        <div class="clearfix"></div>

                        <div class="col-lg-12 description hide">
                            <?php
                            $tips_issues = array();
                            $tips_improvements = array();
                            $tips_clear = array();
                            foreach ($rep_tips as $key => $row) {
                                foreach ($row as $k => $data) {
                                    if ($data['score'] == 100) {
                                        $tips_clear[str_replace(' ', '', $data['category'])][] = $data;
                                    } else if (($data['score'] > 0) && ($data['score'] < 100)) {
                                        $tips_improvements[str_replace(' ', '', $data['category'])][] = $data;
                                    } else {
                                        $tips_issues[str_replace(' ', '', $data['category'])][] = $data;
                                    }
                                }
                            }
                            ?>
                            <h3><span class="category_name"></span> : tips and best practices</h3>
                            <div class="category_details">

                            </div>
                        </div>

                    </div>    
					</div>
							   

                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>

              
                <?php
                echo '';
                // print_r($report);
                echo '';
            }
            ?>
        </div>
    </div>

</div>
</div>
</div>

<?php $this->load->view(get_template_directory() . 'footer'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        var report = <?php echo json_encode($report); ?>;
        var tips_issues = <?php echo json_encode($tips_issues); ?>;
        var tips_improvements = <?php echo json_encode($tips_improvements); ?>;
        var tips_clear = <?php echo json_encode($tips_clear); ?>;
        var total_issues = <?php echo $total_issues ?>;
        var total_improvements = <?php echo $total_improvements ?>;
        var total_successes = <?php echo $total_successes ?>;





        $('.subSummaryHeader').click(function () {
            var category = $(this).data('name');
            $('.description').removeClass('hide');
            $('.category_name').html('');
            $('.category_details').html('');
            $('.category_name').html($(this).data('name'));
            var categ = category.replace(/\s+/g, '');
            var issues = tips_issues[categ];
            var improvements = tips_improvements[categ];
            var clear = tips_clear[categ];
            var html = '';
            if ($.type(issues) !== 'undefined') {
                html += "<h4>Your priorities for this category</h4>";
                $.each(issues, function (key, value) {
                    if (value.score === -1) {
                        value.score = 0;
                    }
                    html += "<div class='row issues'><div class='col-sm-3'><span class='cat_category'>" + value.category + "</span><br><span class='cat_score'>" + value.score + "/100</span> </div>";
                    html += "<div class='col-sm-9'><span class='cat_name'>" + value.name + "</span><br><span class='cat_advice'>" + value.advice + "</span> </div></div>";
                });
            }

            if ($.type(improvements) !== 'undefined') {
                if ($.type(issues) === 'undefined') {
                    html += "<h4>Your priorities for this category</h4>";
                }
                $.each(improvements, function (key, value) {
                    html += "<div class='row improvements'><div class='col-sm-3'><span class='cat_category'>" + value.category + "</span><br><span class='cat_score'>" + value.score + "/100</span> </div>";
                    html += "<div class='col-sm-9'><span class='cat_name'>" + value.name + "</span><br><span class='cat_advice'>" + value.advice + "</span> </div></div>";
                });
            }

            if ($.type(clear) !== 'undefined') {
                html += "<h4>Well done, these best practices are respected</h4>";
                $.each(clear, function (key, value) {

                    html += "<div class='row clear'><div class='col-sm-3'><span class='cat_category'>" + value.category + "</span><br><span class='cat_score'>" + value.score + "/100</span> </div>";
                    html += "<div class='col-sm-9'><span class='cat_name'>" + value.name + "</span><br><span class='cat_advice'>" + value.advice + "</span> </div></div>";
                });
            }
            $('.category_details').html(html);
//            $.ajax({
//                type: "POST",
//                url: siteUrl + "auth/analyze/getSubCategoryText",
//                data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', param: category},
//                success: function (data) {
//                    //  var issues = tips_issues[];
//                }
//            });
        });

    });

</script>
