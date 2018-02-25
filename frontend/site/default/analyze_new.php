<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header_new');
?>

  <!-- BEGIN PAGE CONTENT -->
		
			<div class="row">
        	
				<div class="col-md-12">
				<div class="cg-dual-panel">
				<div class="row">
				<div class="col-md-4 col-sm-6">
					<div class="panel">  
						<div class="panel-header panel-controls mytopbarbg1 mypanelheader">
							<h3><strong>GENERAL INFO</strong></h3>
						</div>
						
						<div class="panel cg-circle-type-2 mypanelsecond mypanelsecondone">
							<div class="panel-content widget-info">
								<div class="">
									<div class="left">
										 <p class="mynumbercountup"><?php echo $report['config']['location'] ?></p>
									</div>
									<div class="right">
										 <span><i class="fa fa-globe bg-blue"></i> <?php echo $report['config']['browser']['name'] ?></span> 
										  
									</div>
								</div>
							</div>
						</div>
						
						<div class="panel cg-circle-type-2 mypanelsecond mypanelsecondone">
							<div class="panel-content widget-info">
								<div class="">
									<div class="left">
										<p class="mynumbercountup"> Up / Down</p>
									</div>
									<div class="right">
										
										<p class="text"><i class="fa fa-cloud-upload bg-blue"></i> <?php echo number_format($report['config']['bandwidth']['downstream'] / 1000, 1); ?>/<?php echo number_format($report['config']['bandwidth']['upstream'] / 1000), 1; ?> Mbps</p>
									</div>
								</div>
							</div>
						</div>
						
						<div class="panel cg-circle-type-2 mypanelsecond ">
							<div class="panel-content widget-info">
								<div class="">
									<div class="left">
									<p class="mynumbercountup"> Latency</p>
									</div>
									<div class="right">
										
										<p class="text">	<i class="fa fa-signal bg-blue"></i> <?php echo $report['config']['latency'] ?>ms</p>
									</div>
								</div>
							</div>
						</div>
						
<div class="clearfix"></div>
					</div>
				</div>
				
				<div class="col-md-4 col-sm-6">
					<div class="panel cg-score-lg">   
						<div class="panel-header panel-controls mytopbarbg1 mypanelheader">
							<h3><strong>SCORES</strong></h3>
						</div>
						
						<div class="widget-progress-bar mywidgetbars" style="margin-top: 20px; margin-bottom:35px; padding-left: 20px; padding-right: 20px;">


						<?php 
							$score = $report['summary']['score'];
							$rep_tips = array();
                            $score = $report['summary']['score'];
                            $total_issues = $total_improvements = $total_successes = $total_checks = 0;
						 ?>	
<div class="col-md-4 col-sm-4 col-xs-12">
<div class="cg-squre-box">
						<div class="clearfix">
						<div class="title">Score</div>
						<div class="number"><?php echo $score; ?>%</div>
						</div>
						<div class="progress">
						<div class="progress-bar progress-bar-primary stat1" data-transitiongoal="<?php echo $score; ?>" style="width: <?php echo $score; ?>%;" aria-valuenow="<?php echo $score; ?>"></div>
						</div>


						<?php
						$color = "#eee";
						$firstbyte = (($report['performanceTimings']['firstByte'] - $report['performanceTimings']['navigationStart']) / 60) / 10;
						$first_byte_ms = ($firstbyte * 1000);
						$firstbyte_percent = ($first_byte_ms / 200) * 100;
						
						?>

</div>
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
<div class="cg-squre-box">
						<div class="clearfix">
						<div class="title"> First Byte</div>
						<div class="number"> <?php echo round($firstbyte,2); ?> sec</div>
						</div>
						<div class="progress">
						<div class="progress-bar progress-bar-primary stat2" data-transitiongoal="<?php echo $firstbyte_percent; ?>" style="width: <?php echo $firstbyte_percent; ?>%;" aria-valuenow="<?php echo $firstbyte_percent; ?>"></div>
						</div>




						<?php
                                    $load_percent = 0;
                                    $color = "#eee";
                                    if (!empty($report)) {
                                        $load = $report['summary']['loadTime'] / 1000;

                                        $load_percent = ($load / 4) * 100;
                                        
                                    }
                        ?>            
</div>
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
<div class="cg-squre-box">
						<div class="clearfix">
						<div class="title">Load Time</div>
						<div class="number"><?php echo $load; ?> sec</div>
						</div>
						<div class="progress">
						<div class="progress-bar progress-bar-primary stat3" data-transitiongoal="<?php echo $load_percent; ?>" style="width: <?php echo $load_percent; ?>%;" aria-valuenow="<?php echo $load_percent; ?>"></div>
						</div>
						</div>
						</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="col-md-4 col-sm-6">
				
					<div class="panel">  
						<div class="panel-header panel-controls mytopbarbg1 mypanelheader">
							<h3><strong>OVERALL GUIDE</strong></h3>
						</div>
						
						<div class="panel cg-circle-type-1  mypanelsecond mypanelsecondone">
							<div class="panel-content widget-info">
								<div class="row">
									<div class="left">
									<p class="mynumbercountup">25</p>
										
									</div>
									<div class="right">
										
										<p class="text"><i class="fa fa-exclamation-circle bg-red"></i> Issues</p>
									</div>
								</div>
							</div>
						</div>
						
						<div class="panel cg-circle-type-1  mypanelsecond mypanelsecondone">
							<div class="panel-content widget-info">
								<div class="row">
									<div class="left">
										<p class="mynumbercountup">2</p>
									</div>
									<div class="right">
										
										<p class="text"><i class="fa fa-level-up bg-yellow"></i> Improvments</p>
									</div>
								</div>
							</div>
						</div>
						
						<div class="panel cg-circle-type-1  mypanelsecond ">
							<div class="panel-content widget-info">
								<div class="row">
									<div class="left">
										<p class="mynumbercountup">5</p>
									</div>
									<div class="right">
										
										<p class="text"><i class="fa fa-check-circle-o bg-green"></i> Success</p>
									</div>
								</div>
							</div>
						</div>
						
						<div class="clearfix"></div>
					</div>
				</div>
				
			

			</div>
			</div>
			</div>
				</div>
			
			
			
			
			<div class="row">
				<div class="col-md-12">
					<div class="panel">  
						<div class="panel-header panel-controls mytopbarbg1 mypanelheader">
							<h3><strong>BEST PRACTISE SUMMARY</strong></h3>
						</div>
						
						
						<div class="panel-content mypanelcontent">
							<div class="nav-tabs3">
								<?php
								if (!empty($report['categories'])) {
									?>
								<ul id="myTab6" class="nav nav-tabs mytopbarbg2 mytabsli">	
								<?php
									$i = 0;	
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
								        
								        <?php
								        	$total_summary = 0;
								        	if ($issues != 0 && $improve == 0) {
								        	    $total_summary += $issues;
								        	    $total_issues+= $issues;
								        	}

								        	if ($improve != 0) {
								        	    $total_summary += $improve;
								        	    $total_improvements+= $improve;
								        	}

								        	if ($checks != 0) {
								        	    $total_summary += $checks;
								        	    $total_successes+=$checks;
								        	}
								        ?>	        

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

								        <li <?php if($i == 0)echo 'class="active"';?>><a data-toggle="tab" class="subSummaryHeader header<?php echo $i;?>" data-name="<?php echo $category['name']; ?>" href="subSummaryHeader_<?php echo $key; ?>"> <?php echo $category['name']; ?>  <span class="badge badge-primary">
								        	<?php
								                    echo $total_summary;
								               ?>
								               </span>
								                </a></li>

								        <?php
								        $i++;
								    }
								}
								?>
								</ul>

							<div class="tab-content mytabcontent mytabcontentbackup">
								<div id="tab6_1" class="tab-pane fade active in"">
									<div class="panel-content mypanelcontent">
										<div class="nav-tabs3">
										<ul id="myTab12" class="nav nav-tabs mytopbarbg2 mytabsli">

										</ul>
										<div class="tab-content mytabcontent mytabcontentbackup">
										
											<div id="issue_html" class="tab-pane fade">
												
												
											</div>
											<div id="improvements_html" class="tab-pane fade">
												
												
											</div>
											<div id="clear_html" class="tab-pane fade">
												
												
											</div>

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
    
        $('[data-toggle="tooltip"]').tooltip();
        var report = <?php echo json_encode($report); ?>;
        var tips_issues = <?php echo json_encode($tips_issues); ?>;
        var tips_improvements = <?php echo json_encode($tips_improvements); ?>;
        var tips_clear = <?php echo json_encode($tips_clear); ?>;
        var total_issues = <?php echo $total_issues ?>;
        var total_improvements = <?php echo $total_improvements ?>;
        var total_successes = <?php echo $total_successes ?>;
        $('.subSummaryHeader, .header0').on('click',function () {

            var category = $(this).data('name');
            $('.description').removeClass('hide');
            $('.category_name').html('');
            $('.category_details').html('');
            $('.category_name').html($(this).data('name'));
            var categ = category.replace(/\s+/g, '');
            var issues = tips_issues[categ];
            var improvements = tips_improvements[categ];
            var clear = tips_clear[categ];
            var issue_html = '';
            var tab_html = '';
            var improvements_html = '';
            var clear_html = '';

            if ($.type(issues) !== 'undefined') {
                tab_html+='<li><a data-toggle="tab" href="#issue_html"> Issues<span class="badge badge-primary">'+issues.length+'</span></a></li>';
				
                    issue_html+='<div class="row">';
					issue_html+='<div class="col-md-12">';
								issue_html+='<h2><strong>Compliance : tips and best practices. Well done, these best practices are respected</strong></h2><br></div>';
						issue_html+='</div>';	
												
                $.each(issues, function (key, value) {
                    if (value.score === -1) {
                        value.score = 0;
                    }
						
						issue_html+='<div class="row mypractise">';
						
							issue_html+='<div class="col-md-2">';
								issue_html+='<span class="cat_category">'+ value.category +'</span>';
								issue_html+='<br>';
								issue_html+='<span class="cat_score">' + value.score +'/100</span>';
							issue_html+='</div>';
							
							issue_html+='<div class="col-md-10">';
								issue_html+='<span class="cat_name">' + value.name +'</span>';
								issue_html+='<br>';
								issue_html+='<span class="cat_advice">';
								issue_html+='<p>' + value.advice +'.</p>';
								issue_html+='</span>';
							issue_html+='</div>';
							
					issue_html+='</div>';		

                });
                issue_html+='</div>';	
            }

            if ($.type(improvements) !== 'undefined') {
                
                    tab_html+='<li > <a data-toggle="tab" href="#improvements_html"> Improvments<span class="badge badge-primary">'+improvements.length+'</span></a></li>';
                improvements_html+='<div class="row">';
					improvements_html+='<div class="col-md-12">';
								improvements_html+='<h2><strong>Compliance : tips and best practices. Well done, these best practices are respected</strong></h2><br></div>';
						improvements_html+='</div>';	
                $.each(improvements, function (key, value) {
                	
						
						improvements_html+='<div class="row mypractise">';
						
							improvements_html+='<div class="col-md-2">';
								improvements_html+='<span class="cat_category">'+ value.category +'</span>';
								improvements_html+='<br>';
								improvements_html+='<span class="cat_score">' + value.score +'/100</span>';
							improvements_html+='</div>';
							
							improvements_html+='<div class="col-md-10">';
								improvements_html+='<span class="cat_name">' + value.name +'</span>';
								improvements_html+='<br>';
								improvements_html+='<span class="cat_advice">';
								improvements_html+='<p>' + value.advice +'.</p>';
								improvements_html+='</span>';
							improvements_html+='</div>';
							
					
                   improvements_html+='</div>';
                });
                	improvements_html+='</div>';
            }

            if ($.type(clear) !== 'undefined') {
                tab_html+='<li><a data-toggle="tab" href="#clear_html"> Checks<span class="badge badge-primary">'+clear.length+'</span></a></li>';
                clear_html = '';
                clear_html+='<div class="row">';
					clear_html+='<div class="col-md-12">';
								clear_html+='<h2><strong>Compliance : tips and best practices. Well done, these best practices are respected</strong></h2><br></div>';
					clear_html+='</div>';	
                $.each(clear, function (key, value) {
                	
					
					clear_html+='<div class="row mypractise">';
					
						clear_html+='<div class="col-md-2">';
							clear_html+='<span class="cat_category">'+ value.category +'</span>';
							clear_html+='<br>';
							clear_html+='<span class="cat_score">' + value.score +'/100</span>';
						clear_html+='</div>';
						
						clear_html+='<div class="col-md-10">';
							clear_html+='<span class="cat_name">' + value.name +'</span>';
							clear_html+='<br>';
							clear_html+='<span class="cat_advice">';
							clear_html+='<p>' + value.advice +'.</p>';
							clear_html+='</span>';
						clear_html+='</div>';
						
					clear_html+='</div>';		
                });
                clear_html+='</div>';	
            }
            $('#myTab12').html(tab_html);
            $('#myTab12 li:first').addClass('active');

            $('#improvements_html').html(improvements_html).removeClass('fade active in');
            $('#clear_html').html(clear_html).removeClass('fade active in');
            $('#issue_html').html(issue_html).removeClass('fade active in');
            if ($.type(issues) !== 'undefined') {
            	$('#issue_html').html(issue_html).removeClass('fade active in').addClass('fade active in');
            }else if($.type(improvements) !== 'undefined'){
            	$('#improvements_html').html(improvements_html).removeClass('fade active in').addClass('fade active in');
            }else if($.type(clear) !== 'undefined'){
            	$('#clear_html').html(clear_html).removeClass('fade active in').addClass('fade active in');
            }	



        });
		
	$('.header0').trigger('click');
    

</script>

			