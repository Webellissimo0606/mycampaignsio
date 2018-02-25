<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="row">
			<div class="col-md-12">
				<div class="panel rad-5 panel-default printerareaciuis">
					<div class="panel-heading"><b><?php echo lang('menu_reports');?></b>
					</div>
					<div class="tab-container">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#financial_summary" data-toggle="tab"><?php echo lang('financialsummary');?></a>
							</li>
							<li><a href="#invoice_ist" data-toggle="tab"><?php echo lang('invoicessummary');?></a>
							</li>
						
							<li><a href="#customer_ist" data-toggle="tab"><?php echo lang('customersummary');?></a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="financial_summary" class="tab-pane active cont">
								<div class="row">
									<div class="col-md-12">
										<div class="widget widget-fullwidth ciuis-body-loading">
											<div class="widget-chart-container">
												<div class="widget-counter-group widget-counter-group-right">
													<div style="width: auto" class="pull-left">
														<i style="font-size: 38px;color: #eaeaea;margin-right: 10px;" class="ion-stats-bars pull-left"></i>
														<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
															<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('salesgraphtitle');?></b></h4>
															<small><?php echo lang('staffsalesgraphdescription');?></small>
														</div>
													</div>
													<div class="counter counter-big">
														<div class="text-success value">
															<b><span class="money-area"><?php echo $ycr; ?></span></b>
														</div>
														<div class="desc"><?php echo lang('inthisyear');?></div>
													</div>
												</div>
												<div ng-controller="mainChartCtrl" class="my-2">
													<div class="chart-wrapper" style="height:300px">
														<canvas id="m12gain" height="300px"></canvas>
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
									
									<div class="col-md-12">
										<div class="widget widget-fullwidth ciuis-body-loading">
											<div class="widget-chart-container">
												<div class="widget-counter-group widget-counter-group-right">
													<div style="width: auto" class="pull-left">
														<i style="font-size: 38px;color: #eaeaea;margin-right: 10px;" class="ion-stats-bars pull-left"></i>
														<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
															<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('weeklysaleschart');?></b></h4>
															<small><?php echo lang('currentweekstats');?></small>
														</div>
													</div>
													<div class="counter counter-big">
														<div class="text-success value">
															<b><span class="money-area"><?php echo $bht; ?></span></b>
														</div>
														<div class="desc"><?php echo lang('inthisweek');?></div>
													</div>
												</div>
												<div ng-controller="mainChartCtrl" class="my-2">
													<div class="chart-wrapper" style="height:300px">
														<canvas id="weeklygain_c" height="300px"></canvas>
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
									<div class="col-md-12">
									<div class="widget widget-fullwidth ciuis-body-loading">
											<div class="widget-chart-container">
												<div class="widget-counter-group widget-counter-group-right">
													<div style="width: auto;" class="pull-left">
														<i style="font-size: 38px;color: #eaeaea;margin-right: 10px" class="ion-stats-bars pull-left"></i>
														<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
															<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('incomingsvsoutgoings');?></b></h4>
															<small><?php echo lang('currentyearstats');?></small>
														</div>
													</div>
												</div>
												<div ng-controller="mainChartCtrl" class="my-2">
													<div class="chart-wrapper" style="height:auto">
														<canvas style="padding-top: 25px;" id="incomingsvsoutgoins"></canvas>
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
									<div class="col-md-12">
									<div class="widget widget-fullwidth ciuis-body-loading">
											<div class="widget-chart-container">
												<div class="widget-counter-group widget-counter-group-right">
													<div style="width: auto;" class="pull-left">
														<i style="font-size: 38px;color: #eaeaea;margin-right: 10px" class="ion-stats-bars pull-left"></i>
														<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
															<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('incomingsvsoutgoings');?></b></h4>
															<small><?php echo lang('currentyearstats');?></small>
														</div>
													</div>
												</div>
												<div ng-controller="mainChartCtrl" class="my-2">
													<div class="chart-wrapper" style="height:auto">
														<canvas style="padding-top: 25px;" id="expensesbycategories"></canvas>
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
									<div class="col-md-12">
									<div class="widget widget-fullwidth ciuis-body-loading">
											<div class="widget-chart-container">
												<div class="widget-counter-group widget-counter-group-right">
													<div style="width: auto;" class="pull-left">
														<i style="font-size: 38px;color: #eaeaea;margin-right: 10px" class="ion-stats-bars pull-left"></i>
														<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
															<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('staffbasedgraphics');?></b></h4>
															<small><?php echo lang('currentweekstats');?></small>
														</div>
													</div>
												</div>
												<div ng-controller="mainChartCtrl" class="my-2">
													<div class="chart-wrapper" style="height:auto">
														<canvas style="padding-top: 25px;" id="top_selling_staff_chart"></canvas>
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
							<div id="invoice_ist" class="tab-pane cont">
							<div class="row">
								<div class="col-md-12">
								<div class="widget-chart-container">
								<div class="widget-counter-group widget-counter-group-right">
									<div style="width: auto" class="pull-left">
										<i style="font-size: 38px;color: #eaeaea;margin-right: 10px" class="ion-stats-bars pull-left"></i>
										<div class="pull-right" style="text-align: left;margin-top: 10px;line-height: 10px;">
											<h4 style="padding: 0px;margin: 0px;"><b><?php echo lang('invoicesbystatuses');?></b></h4>
											<small><?php echo lang('billsbystatus');?></small>
										</div>
									</div>
								</div>
								<div ng-controller="mainChartCtrl" class="my-2">
									<div class="chart-wrapper">
										<canvas id="invoice_chart_by_status"></canvas>
									</div>
								</div>
								</div>
								</div>
								<div class="col-md-4">
									
								</div>
							</div>
							</div>
							<div id="customer_ist" class="tab-pane">
								<div class="row">
									<div class="col-md-12 animated fadeIn">
										<div class="panel">
											<div class="panel-heading">
												<?php echo lang('customermonthlyreporttitle');?>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-md-3">
														<?php
														echo '<select name="m" class="form-control select2" data-none-selected-text="April">' . PHP_EOL;
														for ( $m = 1; $m <= 12; $m++ ) {
															$_selected = '';
															if ( $m == date( 'm' ) ) {
																$_selected = ' selected';
															}
															echo '  <option value="' . $m . '"' . $_selected . '>' . ( date( 'F', mktime( 0, 0, 0, $m, 1 ) ) ) . '</option>' . PHP_EOL;
														}
														echo '</select>' . PHP_EOL;
														?>
													</div>
												</div>
												<canvas class="customergraph_ciuis-xe chart mtop20" id="customergraph_ciuis-xe" height="100"></canvas>
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
	</div>
	<?php include_once dirname(dirname(__FILE__)) . '/inc/sidebar.php'; ?>
</div>
<script src="<?php echo base_url(); ?>assets/crm/lib/chartjs/dist/Chart.bundle.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/crm/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script>
	new Chart($('#invoice_chart_by_status'), {
             type: 'horizontalBar',
             data: <?php echo $invoice_chart_by_status; ?>,
			 options: {
				 legend: {
					display: false,
				 }
			 }
		 });
</script>
<script>
	new Chart($('#incomingsvsoutgoins'), {
             type: 'bar',
             data: <?php echo $incomings_vs_outgoins; ?>,
			 options: {
				 legend: {
					display: false,
				 }
			 }
		 });
</script>
<script>
	new Chart($('#expensesbycategories'), {
             type: 'bar',
             data: <?php echo $expenses_by_categories; ?>,
			 options: {
				 legend: {
					display: false,
				 }
			 }
		 });
</script>
<script>
new Chart($('#top_selling_staff_chart'), {
		 type: 'line',
		 data: <?php echo $top_selling_staff_chart; ?>,
		 options:{
			 legend: {
				display: false,
			 }
	     }
		 });
</script>
<script>
	$( function () {
		
		var data = {
			"labels": [ "<?php echo lang('january'); ?>", "<?php echo lang('february'); ?>", "<?php echo lang('march'); ?>", "<?php echo lang('april'); ?>", "<?php echo lang('may'); ?>", "<?php echo lang('june'); ?>", "<?php echo lang('july'); ?>", "<?php echo lang('august'); ?>", "<?php echo lang('september'); ?>", "<?php echo lang('october'); ?>", "<?php echo lang('november'); ?>", "<?php echo lang('december'); ?>" ],
			"datasets": [ {
					"type": "line",
					backgroundColor: 'white',
					"hoverBorderColor": "#f5f5f5",

					borderColor: '#ffbc00',
					borderWidth: 1,
					"data": <?php echo $monthly_sales_graph ?>,
				}

			]
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
						color: '#C7CBD5',
						zeroLineColor: '#C7CBD5',
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
					display: true,
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
		var ctx = $( '#m12gain' );
		var mainChart = new Chart( ctx, {
			type: 'bar',
			data: data,
			options: options
		} );
	} );
</script>
<script>
	$( function () {
		//İstatistik
		var data = <?php echo $weekly_sales_chart_report ?>;
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
					display: true,
					gridLines: {
						drawBorder: true,
						drawOnChartArea: false,
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
		var ctx = $( '#weeklygain_c' );
		var mainChart = new Chart( ctx, {
			type: 'bar',
			data: data,
			options: options,
		} );
	} );
</script>
<script>
	var ciuis_url = "<?php echo base_url();?>";
	var AylikCustomerGrafigi;
	$( function () {
		$.get( ciuis_url + 'report/customer_monthly_increase_chart/' + $( 'select[name="m"]' ).val(), function ( response ) {
			var ctx = $( '#customergraph_ciuis-xe' ).get( 0 ).getContext( '2d' );
			AylikCustomerGrafigi = new Chart( ctx, {
				'type': 'bar',
				data: response,
				options: {
					responsive: true
				},
			} );
		}, 'json' );
		$( 'select[name="m"]' ).on( 'change', function () {
			AylikCustomerGrafigi.destroy();
			$.get( ciuis_url + 'report/customer_monthly_increase_chart/' + $( 'select[name="m"]' ).val(), function ( response ) {
				var ctx = $( '#customergraph_ciuis-xe' ).get( 0 ).getContext( '2d' );
				AylikCustomerGrafigi = new Chart( ctx, {
					'type': 'bar',
					data: response,
					options: {
						responsive: true
					},
				} );
			}, 'json' );
		} );
	} );
</script> 
<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_report.php' ;?>