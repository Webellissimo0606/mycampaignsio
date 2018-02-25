<?php
$assets_base = base_url('assets/crm');
?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="ciuis-an-x">
			<div class="col-md-3 col-sm-3 col-lg-3 nopadding">
				<div class="ciuis-summary-two panel">
					<div style="font-size: 14px;padding: 12px 12px 12px 0px;border-bottom: 1px solid #f1f1f1;margin: 0;" class="panel-heading">
						<span style="padding-left: 20px;"><?php echo lang('panelsummary'); ?></span>
						<div class="tools"><span class="icon ion-flag"></span></div>
					</div>
					<div class="panel-body" style="margin: 0;padding: 0;">
						<div class="ciuis-dashboard-box-b1-xs-ca-body">
							<div class="ciuis-dashboard-box-stats ciuis-dashboard-box-stats-main text-center">
								<div class="ciuis-dashboard-box-stats-amount" style="font-size: 24px;padding-top: 10px"><?php echo $customerdebt ?> <?php echo $settings['currency'] ?></div>
								<div class="ciuis-dashboard-box-stats-caption">
								<?php echo lang('currentdebt') ?>
								</div>
							</div>
							<div class="col-md-12 nopadding">
								<div class="hpanel stats">
									<div style="padding-top: 0px;line-height: 0.99;" class="panel-body h-200 xs-p-0">
										<div class="col-md-12 xs-mb-20 nopadding">
											<?php $time = date( "H" );$timezone = date( "e" );if ( $time < "12" ) 
{echo '<div class="col-md-12 text-center"><p><img src="'.base_url('assets/crm/img/morning.png').'" alt=""></p><p><h4>'.lang('goodmorning').'</h4><span>'.$_SESSION[ 'name' ].'</span></p></div>';} else if ( $time >= "12" && $time < "17" ) 
{echo '<div class="col-md-12 text-center"><p><img src="'.base_url('assets/crm/img/afternoon.png').'" alt=""></p><p><h4>'.lang('goodafternoon').'</h4><span>'.$this->session->userdata('staffname').'</span></p></div>';} else if ( $time >= "17" && $time < "19" ) 
{echo '<div class="col-md-12 text-center"><p><img src="'.base_url('assets/crm/img/evening.png').'" alt=""></p><p><h4>'.lang('goodevening').'</h4><span>'.$this->session->userdata('staffname').'</span></p></div>';} else if ( $time >= "19" ) 
{echo '<div class="col-md-12 text-center"><p><img src="'.base_url('assets/crm/img/night.png').'" alt=""></p><p><h4>'.lang('goodnight').'</h4><span>'.$this->session->userdata('staffname').'</span></p></div>';}?>
										<div class="col-md-12  md-pt-10 xs-pt-20 text-center" style="border-top: 1px solid #e0e0e0;"><?php echo lang('haveaniceday') ?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-9 xs-p-0">
				<div class="panel panel-default trr-5">
					<div class="tab-container ciuis-summary-two trr-5">
						<ul class="nav nav-tabs trr-5">
							<li class="active"><a href="#home" data-toggle="tab"><?php echo lang('welcome')?></a></li>
						</ul>
						<div class="tab-content brr-5 trr-5">
							<div id="home" class="tab-pane active cont">
								<hr class="hidden-sm hidden-md hidden-lg">
								<div class="col-sm-12 nopadding">
									<div class="widget widget-fullwidth ciuis-body-loading" style="padding: 20px; border-radius: 10px;">
										<div class="widget-chart-container">
											<div class="widget-counter-group widget-counter-group-right">
												<div class="pull-left">
													<div class="pull-left text-left">
														<h4 style="padding: 0px;margin: 0px;">
															<b><?php echo lang('salesgraphtitle');?></b>
														</h4>
														<small>
															<?php echo lang('staffsalesgraphdescription');?>
														</small>
													</div>
												</div>
												<div class="counter counter-big">
													<div class="text-warning value">
														<span class="money-area">FOR</span>
													</div>
													<div class="desc">
														<?php echo lang('thisweek'); ?>
													</div>
												</div>
											</div>
											<div ng-controller="mainChartCtrl" class="my-2">
												<div class="chart-wrapper" style="height:235px;">
													<canvas id="customerthisyearsalesgraph" height="235"></canvas>
												</div>
											</div>
										</div>
										<div class="ciuis-body-spinner">
											<svg width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
												<circle fill="none" stroke-width="4" stroke-linecap="round" cx="33" cy="33" r="30" class="circle"></circle>
											</svg>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
	<?php include(APPPATH . 'views/inc/widgets/panel_bottom_summary_customer.php'); ?>
	</div>
	<?php include_once(APPPATH . 'views/customer/inc/sidebar.php'); ?>
	<?php include_once( APPPATH . 'views/customer/inc/footer.php' );?>
	<script src="<?php echo base_url(); ?>assets/crm/lib/chartjs/dist/Chart.bundle.js" type="text/javascript"></script>
	<script>
		$( function () {
			var data = {
				"labels": [ "<?php echo lang('january');?>", "<?php echo lang('february');?>", "<?php echo lang('march');?>", "<?php echo lang('april');?>", "<?php echo lang('may');?>", "<?php echo lang('june');?>", "<?php echo lang('july');?>", "<?php echo lang('august');?>", "<?php echo lang('september');?>", "<?php echo lang('october');?>", "<?php echo lang('november');?>", "<?php echo lang('december');?>" ],
				"datasets": [ {
					"type": "line",
					backgroundColor: 'rgba(253, 253, 253, 0.65)',
					"hoverBorderColor": "#f5f5f5",
					borderColor: '#000',
					borderWidth: 1,
					"data": <?php echo $customer_annual_sales_chart ?>,
				} ]
			};
			var options = {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					xAxes: [ {
						categoryPercentage: .2,
						barPercentage: 1,
						position: 'top',
						gridLines: {
							color: 'rgba(189, 189, 189, 0.5)',
							zeroLineColor: 'rgba(189, 189, 189, 0.5)',
							drawTicks: true,
							borderDash: [ 5, 5 ],
							offsetGridLines: false,
							tickMarkLength: 10,
							callback: function ( value ) {
								console.log( value )
									// return value.charAt(0) + value.charAt(1) + value.charAt(2);
							}
						},
						ticks: {
							callback: function ( value ) {
								return value.charAt( 0 ) + value.charAt( 1 ) + value.charAt( 2 );
							}
						}
					} ],
					yAxes: [ {
						display: false,
						gridLines: {
							drawBorder: true,
							drawOnChartArea: true,
							borderDash: [ 8, 5 ],
							offsetGridLines: true
						},
						ticks: {
							beginAtZero: true,
							max: <?php echo $ycr ?>,
							maxTicksLimit: 12,
						}
					} ]
				},
				legend: {
					display: false
				}
			};
			var ctx = $( '#customerthisyearsalesgraph' );
			var mainChart = new Chart( ctx, {
				type: 'bar',
				data: data,
				options: options
			} );
		} );
	</script>