<?php include_once dirname( dirname(__FILE__) ) . '/inc/header.php'; ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9 hidden-sm hidden-md hidden-lg" style="<?php if (if_admin) {echo 'display:none';}?>">
		<?php include dirname(dirname(__FILE__)) . '/inc/widgets/panel_bottom_summary.php'; ?>
	</div>
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9 hidden-sm hidden-md hidden-lg" style="<?php if (!if_admin) {echo 'display:none';}?>">
		<?php include dirname(dirname(__FILE__)) . '/inc/widgets/panel_bottom_summary_staff.php'; ?>
	</div>
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
							<div class="ciuis-dashboard-box-stats ciuis-dashboard-box-stats-main">
								<div class="ciuis-dashboard-box-stats-amount"><?php echo $otc ?></div>
								<div class="ciuis-dashboard-box-stats-caption">
								<?php if ($otc > 1) {echo lang('newtickets');}else echo lang('newticket') ?>
								</div>
								<div class="ciuis-dashboard-box-stats-change">
									<div class="ciuis-dashboard-box-stats-value ciuis-dashboard-box-stats-value--positive">+<?php echo $twt ?></div>
									<div class="ciuis-dashboard-box-stats-period"><?php echo lang('thisweek'); ?></div>
								</div>
							</div>
							<div class="ciuis-dashboard-box-stats">
								<div class="ciuis-dashboard-box-stats-amount"><?php echo $yms ?></div>
								<div class="ciuis-dashboard-box-stats-caption">
								<?php if ($yms > 1) {echo lang('newcustomers');}else echo lang('newcustomer') ?>
								</div>
								<div class="ciuis-dashboard-box-stats-change">
									<div class="ciuis-dashboard-box-stats-value ciuis-dashboard-box-stats-value-negative ion-refresh"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="hidden-xs">
						<h4 class="text-center"><?php echo lang('monthlyexpenses'); ?></h4>
					<div id="monthlyexpenses" style="height: 145px;display: block;bottom: 14px;position: absolute;width: 100%;border-bottom-left-radius: 5px;"></div>
					</div>
				</div>
			</div>
			<div class="col-sm-9 xs-p-0">
				<div class="panel panel-default trr-5">
					<div class="tab-container ciuis-summary-two trr-5">
						<ul class="nav nav-tabs trr-5">
						    <?php if (!if_admin) {echo '<li class="active"><a href="#home" data-toggle="tab">'.lang('welcome').'</a></li>';}else echo '<li class="active"><a href="#home" data-toggle="tab">'.lang('welcome').'</a></li>';?>
							<button class="btn btn-default btn-sm pull-right md-mt-10 md-mr-10 ion-eye show-advanced-summary hidden">  Show Advanced Summary</button>
							<button class="btn btn-info btn-sm pull-right md-mt-10 md-mr-10 ion-eye-disabled hide-advanced-summary" style="display: none">  Hide Advanced Summary</button>
						</ul>
						<div class="tab-content brr-5 trr-5" <?php if (if_admin) {echo 'style="display:none"';}?>>
							<div id="home" class="tab-pane active cont">
								<div class="col-sm-4 nopadding">
									<div class="col-md-12 nopadding">
										<div class="hpanel stats">
											<div style="padding-top: 0px;line-height: 0.99;margin-right: 10px;" class="panel-body h-200 xs-p-0">
												<div class="col-md-12 xs-mb-20 md-pl-0">
													<h3 style="font-size:27px;line-height: 0.8;font-weight: 500;" class="no-margins font-extra-bold text-warning">
														<span class="money-area"><?php echo $bkt; ?></span>
													</h3>
													<small><b><?php echo lang('todayssales'); ?></b></small>
													<div class="pull-right text-<?php if ($bkt>$ogt){ echo 'default';}else{ echo 'danger';} ?>"><b>
													<?php  $mna = $bkt - $ogt;  if(empty($ogt)) {echo 'N/A';} else echo floor($mna / $ogt * 100); echo '% '; ?><?php if ($bkt>$ogt){ echo '<i class="icon ion-arrow-up-c"></i>';}else{ echo '<i class="icon ion-arrow-down-c"></i>';} ?>
													</b>
													</div>
												</div>
												<div class="col-md-12 nopadding md-pt-10 xs-pt-20" style="border-top: 1px solid #e0e0e0;">
												<div class="stats-title">
													<h4 style="font-weight: 500;color: #c7cbd5;">
														<?php echo lang('netcashflow'); ?>
													</h4>
												</div>
													<h3 style="font-weight: 500;" class="m-b-xs">
													<?php $netcashflow = $pay - $exp ?>
														<span class="money-area"><?php echo $netcashflow; ?></span>
													</h3>
													<div style="height: 10px" class="progress">
													  <div style="font-size: 7px;line-height: 10px;width:<?php echo $inp ?>%" class="progress-bar progress-bar-success progress-bar-striped"><?php echo $inp ?>% <?php echo lang('incomings'); ?><span class="sr-only"><?php echo lang('incomings'); ?></span></div>
													  <div style="font-size: 7px;line-height: 10px;width:<?php echo $ogp ?>%" class="progress-bar progress-bar-danger progress-bar-striped"><?php echo $ogp ?>% <?php echo lang('outgoings'); ?><span class="sr-only"><?php echo lang('outgoings'); ?></span></div>
													</div>
													<div class="row">
														<div class="col-xs-6">
															<small class="stats-label text-uppercase text-bold text-success">
																<?php echo lang('incomings'); ?>
															</small>
															<h4 class="money-area"><?php echo $pay; ?></h4>
														</div>

														<div class="col-xs-6">
															<small class="stats-label text-uppercase text-bold text-danger"><?php echo lang('outgoings'); ?></small>
															<h4 class="money-area"><?php echo $exp; ?></h4>
														</div>
													</div>
													<?php echo lang('cashflowdetail'); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<hr class="hidden-sm hidden-md hidden-lg">
								<div class="col-sm-8 nopadding">
									<div class="widget widget-fullwidth ciuis-body-loading">
										<div class="widget-chart-container">
											<div class="widget-counter-group widget-counter-group-right">
												<div class="pull-left">
													<div class="pull-left text-left">
														<h4 style="padding: 0px;margin: 0px;">
															<b><?php echo lang('weeklygraphtitle'); ?></b>
														</h4>
														<small>
															<?php echo lang('weeklygraphdetailtext'); ?>
														</small>
													</div>
												</div>
												<div class="counter counter-big">
													<div class="text-warning value">
														<span class="money-area"><?php echo $bht; ?></span>
													</div>
													<div class="desc">
														<?php echo lang('thisweek'); ?>
													</div>
												</div>
												<div class="counter counter-big">
													<div class="value">
													<span class="text-<?php if ($bht>$ohc){ echo 'default';}else{ echo 'danger';} ?>">
													<?php  $mna = $bht - $ohc;  if(empty($ohc)) {echo 'N/A';} else echo floor($mna / $ohc * 100); echo '%'; ?></span>
													</div>
													<div class="desc"><?php if ($bht>$ohc){ echo lang('increase');}else{ echo lang('recession');} ?></div>
												</div>
											</div>
											<div ng-controller="mainChartCtrl" class="my-2">
												<div class="chart-wrapper" style="height:235px;">
													<canvas id="main-chart" height="235px"></canvas>
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
						
						<div class="tab-content brr-5 trr-5" <?php if (!if_admin) {echo 'style="display:none"';}?>>
							<div id="home" class="tab-pane active cont">
								<div class="col-sm-4 nopadding">
									<div class="col-md-12 nopadding">
										<div class="hpanel stats">
											<div style="padding-top: 0px;line-height: 0.99;margin-right: 10px;" class="panel-body h-200 xs-p-0">
												<div class="col-md-12 xs-mb-20 md-pl-0">
													<?php 
													$time = date( "H" );
													$timezone = date( "e" );
													if ( $time < "12" ){
														echo '<div class="col-md-12 text-center"><p><img src="'.base_url('assets/img/morning.png').'" alt=""></p><p><h4>'.lang('goodmorning').'</h4><span>'.$this->session->userdata('staffname').'</span></p></div>';
													}
													else if ( $time >= "12" && $time < "17" ) {
														echo '<div class="col-md-12 text-center"><p><img src="'.base_url('assets/img/afternoon.png').'" alt=""></p><p><h4>'.lang('goodafternoon').'</h4><span>'.$this->session->userdata('staffname').'</span></p></div>';
													}
													else if ( $time >= "17" && $time < "19" ) {
														echo '<div class="col-md-12 text-center"><p><img src="'.base_url('assets/img/evening.png').'" alt=""></p><p><h4>'.lang('goodevening').'</h4><span>'.$this->session->userdata('staffname').'</span></p></div>';
													}
													else if ( $time >= "19" ) {
														echo '<div class="col-md-12 text-center"><p><img src="'.base_url('assets/img/night.png').'" alt=""></p><p><h4>'.lang('goodnight').'</h4><span>'.$this->session->userdata('staffname').'</span></p></div>';
													}?>
													<div class="col-md-12  md-pt-10 xs-pt-20 text-center" style="border-top: 1px solid #e0e0e0;">Have nice day!</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<hr class="hidden-sm hidden-md hidden-lg">
								<div class="col-sm-8 nopadding">
									<div class="panel panel-default">
										<div class="panel-body" style="height: 400px; overflow: scroll; zoom: 0.8; margin-top: 25px; box-shadow: inset 0px 17px 50px 10px #ffffff; overflow-y: scroll;">
											<ul class="user-timeline user-timeline-compact">
												<?php foreach($todos as $mytodo){?>
												<li class="latest">
													<div class="user-timeline-date"><?php echo tes_ciuis($mytodo['date']);?></div>
													<div class="user-timeline-title"><?php echo _adate($mytodo['date']) ?></div>
													<div class="user-timeline-description"><?php echo $mytodo['description']?></div>
												</li>
										   		<?php }?>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div id="ticketssummary" class="tab-pane">
								<div class="ciuis-ticket-stats">
									<div class="col-md-12 md-p-0" style=" margin-top: -33px; border-bottom: 1px solid #e0e0e0; ">
										<ul style="padding: 0px;" class="ciuis-ticket-stats grid">
											<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-3"> <span style="line-height: 30px;" class="ciuis-is-x-status"><?php echo lang('open')?></span>
												<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $ysy ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $ysy ?> </span>
													<span class="ciuis-is-x-completes">
														<?php $this->db->where('statusid',1);$this->db->from('tickets'); echo $this->db->count_all_results();?> /
														<?php $this->db->from('tickets'); echo $this->db->count_all_results();?> </span>
													</span>
													</span>
												</div>
											</li>
											<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-3"> <span style="line-height: 30px;" class="ciuis-is-x-status"><?php echo lang('inprogress')?></span>
												<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $bsy ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $bsy ?> </span>
													<span class="ciuis-is-x-completes">
														<?php $this->db->where('statusid',2);$this->db->from('tickets'); echo $this->db->count_all_results();?> /
														<?php $this->db->from('tickets'); echo $this->db->count_all_results();?> </span>
													</span>
													</span>
												</div>
											</li>
											<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-3"> <span style="line-height: 30px;" class="ciuis-is-x-status"><?php echo lang('answered')?></span>
												<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $twy ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $twy ?> </span>
													<span class="ciuis-is-x-completes">
														<?php $this->db->where('statusid',3);$this->db->from('tickets'); echo $this->db->count_all_results();?> /
														<?php $this->db->from('tickets'); echo $this->db->count_all_results();?> </span>
													</span>
													</span>
													</span>
												</div>
											</li>
											<li class="ciuis-ist-tab col-md-3"> <span style="line-height: 30px;" class="ciuis-is-x-status"><?php echo lang('closed')?></span>
												<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $iey ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $iey ?> </span>
													<span class="ciuis-is-x-completes">
														<?php $this->db->where('statusid',4);$this->db->from('tickets'); echo $this->db->count_all_results();?> /
														<?php $this->db->from('tickets'); echo $this->db->count_all_results();?> </span>
													</span>
													</span>
												</div>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9 hidden-xs" style="<?php if (if_admin) {echo 'display:none';}?>">
		<?php include dirname(dirname(__FILE__)) . '/inc/widgets/panel_bottom_summary.php'; ?>
	</div>
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9 hidden-xs" style="<?php if (!if_admin) {echo 'display:none';}?>">
		<?php include dirname(dirname(__FILE__)) . '/inc/widgets/panel_bottom_summary_staff.php'; ?>
	</div>
	<div class="main-content container-fluid md-mt-15 col-xs-12 col-md-12 col-lg-9 advanced-summary" style="display: none">
		<div class="panel" style="border-radius: 5px;">
			<div class="panel-body md-p-0">
				<div class="as-container">
				<div class="as-header">
					<h2>Invoice Summary</h2>
				</div>
					<div class="col-md-6">
					<div class="content-group-sm">
						<div class="pull-right progress-right-info text-teal-800">65.8%</div>
						<h4 class="text-semibold mt-0">Open rate</h4>
						<div class="progress progress-sm" style="height: 10px;">
							<div class="progress-bar progress-bar-warning bg-teal-400" style="width: 65.8%">
							</div>
						</div>
					</div>
					<div class="content-group-sm">
						<div class="pull-right progress-right-info text-teal-800">65.8%</div>
						<h4 class="text-semibold mt-0">Open rate</h4>
						<div class="progress progress-sm" style="height: 10px;">
							<div class="progress-bar progress-bar-warning bg-teal-400" style="width: 65.8%">
							</div>
						</div>
					</div>
					<div class="content-group-sm">
						<div class="pull-right progress-right-info text-teal-800">65.8%</div>
						<h4 class="text-semibold mt-0">Open rate</h4>
						<div class="progress progress-sm" style="height: 10px;">
							<div class="progress-bar progress-bar-warning bg-teal-400" style="width: 65.8%">
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="content-group-sm">
						<div class="pull-right progress-right-info text-teal-800">65.8%</div>
						<h4 class="text-semibold mt-0">Open rate</h4>
						<div class="progress progress-sm" style="height: 10px;">
							<div class="progress-bar progress-bar-warning bg-teal-400" style="width: 65.8%">
							</div>
						</div>
					</div>
					<div class="content-group-sm">
						<div class="pull-right progress-right-info text-teal-800">65.8%</div>
						<h4 class="text-semibold mt-0">Open rate</h4>
						<div class="progress progress-sm" style="height: 10px;">
							<div class="progress-bar progress-bar-warning bg-teal-400" style="width: 65.8%">
							</div>
						</div>
					</div>
					<div class="content-group-sm">
						<div class="pull-right progress-right-info text-teal-800">65.8%</div>
						<h4 class="text-semibold mt-0">Open rate</h4>
						<div class="progress progress-sm" style="height: 10px;">
							<div class="progress-bar progress-bar-warning bg-teal-400" style="width: 65.8%">
							</div>
						</div>
					</div>
				</div>
				</div>
				<hr>
				<div class="as-container">
				<div class="as-header">
					<h2>Proposal Summary</h2>
				</div>
					<ul class="ciuis-ticket-stats as-summary-content">
					<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-2">
						<span class="ciuis-is-x-status">
							Draft						</span>
						<div class="pull-right">
						   <span class="ciuis-ticket-percent"> 
						   <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: 0%;"></span> </span> 
						   <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> 0% </span> 
						   <span class="ciuis-is-x-completes">0/5</span></span>
						   </span>
						</div>
					</li>
					<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-2">
						<span class="ciuis-is-x-status">
							Sent						</span>
						<div class="pull-right">
						   <span class="ciuis-ticket-percent"> 
						   <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: 0%"></span> </span> 
						   <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> 0% </span> 
						   <span class="ciuis-is-x-completes">0/5</span></span>
						   </span>
						</div>
					</li>
					<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-2">
						<span class="ciuis-is-x-status">
							OPEN						</span>
						<div class="pull-right">
						   <span class="ciuis-ticket-percent"> 
						   <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: 40%;"></span> </span> 
						   <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> 40% </span> 
						   <span class="ciuis-is-x-completes">2/5</span></span>
						   </span>
						</div>
					</li>
					<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-2">
						<span class="ciuis-is-x-status">
							Revised						</span>
						<div class="pull-right">
						   <span class="ciuis-ticket-percent"> 
						   <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: 0%;"></span> </span> 
						   <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> 0% </span> 
						   <span class="ciuis-is-x-completes">0/5</span></span>
						   </span>
						</div>
					</li>
					<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-2">
						<span class="ciuis-is-x-status">
							Declined						</span>
						<div class="pull-right">
						   <span class="ciuis-ticket-percent"> 
						   <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: 40%;"></span> </span> 
						   <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> 40% </span> 
						   <span class="ciuis-is-x-completes">2/5</span></span>
						   </span>
						</div>
					</li>
					<li class="ciuis-ist-tab col-md-2">
						<span class="ciuis-is-x-status">
							Accepted						</span>
						<div class="pull-right">
						   <span class="ciuis-ticket-percent"> 
						   <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: 20%;"></span> </span> 
						   <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> 20% </span> 
						   <span class="ciuis-is-x-completes">1/5</span></span>
						   </span>
						</div>
					</li>
				</ul>
				</div>
				
			</div>
		</div>
	</div>

<?php include_once dirname(dirname(__FILE__)) . '/inc/footer.php'; ?>
<script>
	console.clear();
	$( function () {
		Highcharts.setOptions( {
			colors: [ '#ffbc00', 'rgb(239, 89, 80)' ]
		} );

		Highcharts.chart( 'monthlyexpenses', {
			title: {
				text: '',
			},
			credits: {
				enabled: false
			},
			chart: {
				backgroundColor: 'transparent',
				marginBottom: 0,
				marginLeft: -10,
				marginRight: -10,
				marginTop: 0,

				type: 'area',
			},
			exporting: {
				enabled: false
			},
			plotOptions: {
				series: {
					fillOpacity: 0.1
				},
				area: {
					lineWidth: 1,
					marker: {
						lineWidth: 2,
						symbol: 'circle',
						fillColor: 'black',
						radius: 3,
					},
					legend: {
						radius: 2,
					}
				}
			},
			xAxis: {
				categories: [ "<?php echo lang('january'); ?>", "<?php echo lang('february'); ?>", "<?php echo lang('march'); ?>", "<?php echo lang('april'); ?>", "<?php echo lang('may'); ?>", "<?php echo lang('june'); ?>", "<?php echo lang('july'); ?>", "<?php echo lang('august'); ?>", "<?php echo lang('september'); ?>", "<?php echo lang('october'); ?>", "<?php echo lang('november'); ?>", "<?php echo lang('december'); ?>" ],
				visible: true,
			},
			yAxis: {
				title: {
					enabled: false
				},
				visible: false
			},
			tooltip: {
				shadow: false,
				useHTML: true,
				padding: 0,
				formatter: function () {
					return '<div class="bis-tooltip" style="background-color: ' + this.color + '">' + this.x + ' <span>' + this.y + '$' + '</span></div>'
				}
			},
			legend: {
				align: 'right',
				enabled: false,
				verticalAlign: 'top',
				layout: 'vertical',
				x: -15,
				y: 100,
				itemMarginBottom: 20,
				useHTML: true,
				labelFormatter: function () {
					console.log( this )
					return '<span style="color:' + this.color + '">' + this.name + '</span>'
				},
				symbolPadding: 0,
				symbolWidth: 0,
				symbolRadius: 0
			},
			series: [ {"data":<?php echo $monthly_expense_graph ?>}]
		}, function ( chart ) {
			var series = chart.series;
			series.forEach( function ( serie ) {
				console.log( serie )
				if ( serie.legendSymbol ) {
					console.log( serie.legendSymbol.destroy )

					serie.legendSymbol.destroy();
				}
				if ( serie.legendLine ) {
					serie.legendLine.destroy();
				}
			} );
		} );
	} );
</script>
<style>
	.highcharts-tooltip-box {
		fill: transparent;
		stroke-width: 0;
	}
	.highcharts-legend-item {
		text-transform: uppercase;
	}
	.bis-tooltip {
		background-color:#fff;
		padding: 8px;
		border-radius: 30px;
		color: #FFF;
		box-shadow: 0px 5px 6px 3px rgba(0, 0, 0, 0.2);
	}
	.bis-tooltip span {
		font-weight: bold;
	}
</style>
<script>
	$( function () {
		var data = <?php echo $weekly_sales_chart ?>;
		var options = {
			responsive: true,
			maintainAspectRatio: false,
			scales: {
				xAxes: [ {
					categoryPercentage: .2,
					barPercentage: 1,
					position: 'top',
					gridLines: {
						color: '#C7CBD5',
						zeroLineColor: '#C7CBD5',
						drawTicks: true,
						borderDash: [ 8, 5 ],
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
						drawBorder: false,
						drawOnChartArea: false,
						borderDash: [ 8, 5 ],
						offsetGridLines: false
					},
					ticks: {
						beginAtZero: true,
						maxTicksLimit: 5,
					}
				} ]
			},
			legend: {
				display: false
			}
		};
		var ctx = $( '#main-chart' );
		var mainChart = new Chart( ctx, {
			type: 'bar',
			data: data,
			borderRadius: 10,
			options: options
		} );
	} );
</script>
<script type="text/javascript">
	$( ".show-advanced-summary" ).click( function () {
		$( '.advanced-summary' ).show();
		$( '.advanced-summary' ).addClass( 'animated fadeIn' );
		$( '.hide-advanced-summary' ).show();
		$( '.show-advanced-summary' ).hide();
		speak(advancedsummaryshow);
	} );
	$( ".hide-advanced-summary" ).click( function () {
		$( '.advanced-summary' ).hide();
		$( '.hide-advanced-summary' ).hide();
		$( '.show-advanced-summary' ).show();
	} );
</script>