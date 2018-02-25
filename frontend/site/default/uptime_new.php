<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header');
?>

		<div class="row">
			<div class="col-md-8">

				<div class="row">
				  <div class="col-md-4">
				    <div class="panel bg-blue ds-total-sites">
				      
				      <div class="panel-content">
				        <div class="panel-header">
				          <h3>Total Domains <?php echo $userId; ?></h3>
				        </div>
				        <div class="panel-content">
				          <span class="ds-numbers"><?php echo $totaldowndomains + $totalupdomains; ?></span>
				        </div>
				          
				      </div>
				    </div>
				  </div>

				  <div class="col-md-4">
				    <div class="panel bg-blue ds-up-sites">
				      
				      <div class="panel-content">
				        <div class="panel-header">
				          <h3>Sites Up</h3>
				        </div>
				        <div class="panel-content">
				          <span class="ds-numbers"><?php echo $totalupdomains; ?></span>
				        </div>
				          
				      </div>
				    </div>
				  </div>

				  <div class="col-md-4">
				    <div class="panel bg-blue ds-down-sites">
				      
				      <div class="panel-content">
				        <div class="panel-header">
				          <h3>Sites Down</h3>
				        </div>
				        <div class="panel-content">
				          <span class="ds-numbers"><?php echo $totaldowndomains; ?></span>
				        </div>
				          
				      </div>
				    </div>
				  </div>
				</div>
				<div style="overflow: auto; padding-bottom: 0" class="panel">  
					<div class="panel-header">
						<h3><strong>QUICK STATS</strong></h3>
					</div>

					<div class="panel-content padding-top-zero">
						<div class="row">
							<div class="col-md-6">
								<span class="fastest btn-success"><h4>Fastest Domain Speed<strong class="pull-right"><?php echo $fastest_domain; ?></strong></h4></span>
							</div>
							<div class="col-md-6">
								<span class="lowest btn-warning"><h4>Worst Domain Speed<strong class="pull-right"><?php echo $slowest_domain; ?></strong></h4></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="panel">  
					<div class="panel-header">
						<h3>OVERALL UPDATE</h3>
					</div>
					
					<div class="panel-content overall-uptime padding-top-zero">
						<span class="uptime bg-blue"><i class="icon-arrow-up "></i><strong><?php echo $uptime1daypercentage; ?>%</strong>  (last 24 hours)</span>
						<span class="uptime bg-blue"><i class="icon-arrow-up "></i><strong><?php echo $uptime7daypercentage; ?>%</strong> (last 7 days)</span>
						<span class="uptime bg-blue"><i class="icon-arrow-up "></i><strong><?php echo $uptime30daypercentage; ?>%</strong> (last 30 days)</span>
						<span class="down-site bg-danger"><i class="icon-arrow-down"></i> <?php echo $latestdowntime; ?></span>
					</div>
					
				</div>		
			</div>
		</div>
			
			
			
			
			
		<div class="row">
			<div class="col-md-12">
				<div class="panel">  
					<div class="panel-header">
						<h3>
							<form action="/uptime/report" method="post" enctype="multipart/form-data" id="frm_status_report" name="frm_status_report" class="pull-right">
								<div class="btn-group">
									<button class="btn btn-success <?php if($days == 1)echo 'active'; ?> " data-day="1">Today</button>
									<button class="btn btn-success <?php if($days == 7)echo 'active'; ?> " data-day="7">7 Days</button>
									<button class="btn btn-success <?php if($days == 30)echo 'active'; ?> " data-day="30">1 Month</button>
									<button class="btn btn-success <?php if($days == 365)echo 'active'; ?> " data-day="365">1 Year</button>
								</div>
								<input type="hidden" value="" name="days" id="days_report_status">
							</form> 
						</h3>
					</div>
					<div class="panel-content refined-tab">
						<div class="nav-tabs3">	

							<ul id="myTab6" class="nav nav-tabs"> 
							    <li class="active"><a class="red" data-toggle="pill" href="#sitesdown">Sites Down</a></li>
							    <li><a class="blue" data-toggle="pill" href="#sitesup">Sites Up</a></li>
							    <li><a class="green" data-toggle="pill" href="#siteswithnostats">Sites with No Stats</a></li>
							</ul>

							<div class="tab-content">
								<div id="siteswithnostats" class="tab-pane fade"> 
									<div class="panel">
										<div class="panel-content pagination2 table-responsive">
											<table class="table table-hover table-dynamic ds-list-table">
										        <thead>
										            <tr>
										                <th>Domain</th>
										            </tr>
										        </thead>
										        <tbody>
											        <?php 
											        	if($nostats):
											        	foreach($nostats as $key=>$nostat):	
											        ?>	
										            <tr>
										                <td>
										                	<?php echo $nostat['domain_name']; ?>
										                </td>  
										            </tr>
										          	<?php 
											          	endforeach;
											          	endif;
										          	?>
										        </tbody>
										    </table>
										</div>
									</div>
								</div>
								<div id="sitesdown" class="tab-pane fade in active">
							        <div class="panel">
							            <div class="panel-content pagination2 table-responsive">
							                <table class="table table-hover table-dynamic ds-list-table">
							                    <thead>
							                        <tr>
							                            <th>Domain</th>
							                            <th>Status</th>
							                            <th>Code</th>
							                            <th>Type</th>
							                            <th>Downtime (%)</th>
							                            <th>Loadtime</th>
							                        </tr>
							                    </thead>
							                    <tbody>
							                        <?php if($downtime):
							                        	foreach($downtime as $key=>$down):	
							                         ?>	
							                        <tr>
							                            <td><?php echo $down['domain_name']; ?></td>  
							                            <td class="">-</td>
							                            <td class="">-</td>
							                            <td><?php echo $down['module']; ?></td>
							                            <td><?php echo round((($down['total_stats']*100)/$totaltime[$down['domain_id']]['total_stats']), 2); ?>%</td>
							                            <td><?php echo round($down['avg_load_time']/100, 2); ?>s</td>
							                        </tr>
							                      	<?php 
							                      		endforeach;
							                      		endif;
							                       	?>
							                    </tbody>
							                </table>
							            </div>
							        </div>
								</div>
							    <div id="sitesup" class="tab-pane fade ">
							        <div class="panel">
							            <div class="panel-content pagination2 table-responsive">
							                <table class="table table-hover table-dynamic ds-list-table">
							                    <thead>
							                        <tr>
							                            <th>Domain</th>
							                            <th>Status</th>
							                            <th>Code</th>
							                            <th>Type</th> 
							                            <th>Uptime (%)</th>
							                            <th>Loadtime</th>
							                        </tr>
							                    </thead>
							                    <tbody>
							                        <?php if($uptime): ?>
							                        <?php foreach($uptime as $key=>$up): ?>
							                            <tr>
							                                <td><?php echo $up['domain_name']; ?></td>
							                                <td>OK</td>
							                                <td>200</td>
							                                <td><?php echo $up['module']; ?></td>
							                                <td><?php echo round((($up['total_stats']*100)/$totaltime[$up['domain_id']]['total_stats']), 2); ?>%</td>
							                                <td><?php echo round($up['avg_load_time']/1000, 2); ?>s</td>
							                            </tr>
							                        <?php 
							                        	endforeach;
							                        	endif;
							                        ?>
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
		</div>
      
 <?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 $this->load->view(get_template_directory() . 'footer_new');
 ?>     
 <script type="text/javascript">
 	$.post( '<?php echo base_url(); ?>auth/home/alldomains',function(data){
 		if(data.status){
 			var html = '';
 			var aclass = 'success';
 			for(var i=0;i<data.domain.length;i++){
 				if(i%2){
 					aclass = 'success';
 				}else{
 					aclass = 'active';
 				}
 				html+='<tr class="'+aclass+'">';
 				html+='<td>'+data.domain[i].domain_name+'</td>';
 				html+='</tr>';
 			}
 			$('#domainlist').html(html);
 		}else{
 			var html = '';
 				html+=' <tr><td> No domains added </td></tr>';
 			$('#domainlist').html(html);
 		}
 	},'json');	
 	
 	    
        $('.timetabs').click(function(){
            $('#days_report_status').val($(this).attr('data-day'));
            $('#frm_status_report').submit();
        })
      
    

 </script>