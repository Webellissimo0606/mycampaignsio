<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header');
?>
<!DOCTYPE html>

<div class="container-fluid">
	<div class="row">
		
		<div class="col-sm-3 col-md-2 sidebar">
		
			<div class="input-group stylish-input-group">
                    <input type="text" class="form-control searchdomains"  placeholder="Search" >
                    <span class="input-group-addon">
                        <button type="submit">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>  
                    </span>
            </div>
		
          <ul class="nav nav-sidebar" id="domainlist">
          </ul>
        </div>
	
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		
			<div class="row">

				<div class="col-xs-6 col-lg-4">
				
					<div class="panel with-nav-tabs panel-default myupdates all-sites-updates">
						<div class="panel-heading">
							<i class="fa fa-file myupdatesicon"></i> <h3 class="sub-header">Updates</h3>
						</div>
						<div class="panel-body">
							<div class="tab-content">
								<ul class="wp-summ-upgrades-list">
									<li class="core"><span class="col-xs-6 col-sm-9 col-lg-8">WP Core Updates</span><i class="summ-core-num">N/A</i></li>
									<li class="plugins"><span class="col-xs-6 col-sm-9 col-lg-8">Plugins Updates</span><i class="summ-themes-num">N/A</i></li>
									<li class="themes"><span class="col-xs-6 col-sm-9 col-lg-8">Themes Updates</span><i class="summ-plugins-num">N/A</i></li>
								</ul>
							</div>
						</div>
						<!-- <div class="panel-footer" style="display:none;">
						</div> -->
					</div>
				
				</div>
				
				<div class="col-xs-6 col-lg-4">
				
					<div class="panel with-nav-tabs panel-default myupdates myoptimization">
						<div class="panel-heading">
							<i class="fa  fa-terminal myupdatesicon"></i> <h3 class="sub-header">Optimazation</h3>
						
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab4default" data-toggle="tab"> <span class="bignumber"> 14 </span>Comments </a></li>
								<li><a href="#tab5default" data-toggle="tab"> <span class="bignumber2"> 29 </span> Revisons </a></li>
								<li><a href="#tab6default" data-toggle="tab"> <span class="bignumber3"> 10.4 </span> MB Database </a></li>
								<li></li>
							</ul>
						</div>
						<div class="panel-body">
							<div class="tab-content">
								<div class="tab-pane fade in active" id="tab4default">Default 1</div>
								<div class="tab-pane fade" id="tab5default">Default 2</div>
								<div class="tab-pane fade" id="tab6default">Default 3</div>
							</div>
						</div>
						<div class="panel-footer">
							<button type="button" class="btn btn-default updateall"> <i class="fa fa-magic"></i> Optimize all </button>
						</div>
						
					</div>
				
				</div>
				
				<div class="col-xs-6 col-lg-4 removepadding">
				
					<div class="panel with-nav-tabs panel-default mybackup">
						<div class="panel-heading">
							<i class="fa fa-database mybackupicon"></i><h3 class="sub-header">Backups</h3>
						</div>
						<div class="panel-body">
							<div class="tab-content">
								<div class="tab-pane fade in active" id="">
									
									<div class="rowone">
									<h5 class="">Daily Backup Symmary</h5>
									<h6 class="">Calculate last 7 days</h6>
									</div>
									
									<div class="row">
									<div class="col-xs-6 col-lg-3">
										<span class="backupnumber">22</span>
										<span class="backuptext">TOTAL WEBSITE</span>
									</div>	
										
									<div class="col-xs-6 col-lg-3">
										<div class="c100 p80 small green">
											<span>80%</span>
											<div class="slice">
												<div class="bar"></div>
												<div class="fill"></div>
											</div>
										</div>
									</div>
									
									<div class="col-xs-6 col-lg-3">
										<span class="backupnumber">29</span>
										<span class="backuptext">TOTAL DATABASE</span>
									</div>
									
									<div class="col-xs-6 col-lg-3">
										<div class="c100 p50 small green">
											<span>50%</span>
											<div class="slice">
												<div class="bar"></div>
												<div class="fill"></div>
											</div>
										</div>
									</div>
									</div>
									
									<div class="row lastrowprogress">
										<h5 class="">Account Disk Usage: <span class="changeme">3.9 GB / 25 GB Total</span></h5>
										<div class="progress">
											<div class="progress-bar" style="min-width:15em;width:15%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="15" role="progressbar"> 15% </div>
										</div>
										<span class="changeme2">*Upgrade for more space</span>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				
				</div>
				
			</div>
			

		
		
			<div class="row">
			
				<div class="col-xs-6 col-lg-8">
				
					<div class="panel with-nav-tabs panel-default mystats">
						<div class="panel-heading">
							<i class="fa  fa-bar-chart mystatsicon"></i> <h3 class="sub-header">Statistic</h3>
						</div>
						<div class="panel-body">
							<div class="tab-content">
								
							</div>
						</div>
					</div>
				
				</div>
				
				<div class="col-xs-6 col-lg-4  myuptime">
				
					 <i class="fa fa-long-arrow-up myarrowup"></i><h3 class="sub-header">Uptime monitor</h3>
					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Status</th>
									<th>Name</th>
									<th>Rate</th>
									<th><?php echo date('M d', strtotime('-1 day', time()));?></th>
									<th><?php echo date('M d', strtotime('-2 day', time()));?></th>
									<th><?php echo date('M d', strtotime('-3 day', time()));?></th>
								</tr>
							</thead>
							<tbody id="uptime_stats_table">
								<tr>
									<td colspan="6">Loading Uptime Stats..</td>
								</tr>	
								
							</tbody>
						</table>
					</div>
				
				</div>
			
			</div>
		
		</div>
		
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {

		var currSiteUrl = 'undefined' !== siteUrl ? siteUrl : '/';

		//getting user domains
		$.post( currSiteUrl + 'auth/home/alldomains',function(data){

			if(data.status){

				var html = '';
				for(var i=0;i<data.domains.length;i++){

					html +=' <li> '+data.domains[i].domain_name+' <a class="btn btn-default mysidebarbutton1" href="' + currSiteUrl + 'auth/site/viewsite/'+data.domains[i].id+'" role="button" target="_blank">Login</a> <a class="btn btn-default mysidebarbutton2" href="' + currSiteUrl + 'auth/dashboard/'+data.domains[i].id+'" role="button">Stats</a> </li>';
				}
				
				$('#domainlist').html(html);
			}else{
				var html = '';
					html+=' <li> No domains added </li>';
				$('#domainlist').html(html);
			}
		},'json')


		//get the uptime stats
		$.post('/auth/home/uptimestats',function(data){
			if(data.status){
				var html = '';
				for(var i=0;i<data.uptime.length;i++){
					html+=" <tr>";
					html+='<td><i class="fa fa-arrow-circle-'+data.uptime[i][0]['current_status']+' statusicon '+data.uptime[i][0]['current_status']+'"></i></td>';
					html+='<td>'+data.uptime[i][0]['domain_name']+'</td>';
					html+='<td>'+Math.ceil((data.uptime[i][0]['avg_load_time']+data.uptime[i][1]['avg_load_time']+data.uptime[i][2]['avg_load_time'])/3)+' s</td>';
					html+='<td>'+data.uptime[i][0]['percentage']+'%</td>';
					html+='<td>'+data.uptime[i][1]['percentage']+'%</td>'; 
					html+='<td>'+data.uptime[i][2]['percentage']+'%</td>';
					html+='</tr>';
				}
				$('#uptime_stats_table').html(html);
			}else{
				var html = '';
					html+=' <tr><td colspan="6">No records found</td></tr>';
				$('#uptime_stats_table').html(html);
			}

		},'json')

		$('.searchdomains').keyup(function(){

		       var searchText = $(this).val();

		       $('#domainlist > li').each(function(){
		       		var currentLiText = $(this).text(),
		       		    showCurrentLi = currentLiText.indexOf(searchText) !== -1;

		       		$(this).toggle(showCurrentLi);
		         

		       });     
		   });

	});

</script>
