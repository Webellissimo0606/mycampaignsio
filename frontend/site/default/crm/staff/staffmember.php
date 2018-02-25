<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="user-profile">
			<div class="row">
				<div class="col-md-5">
					<div class="user-display">
						<div class="user-display-bg"><img src="<?php echo base_url(); ?>assets/crm/img/staffmember_bg.png" alt="<?php echo $staff['staffname']; ?>">
						</div>
						<div class="user-display-bottom">
							<div class="user-display-avatar"><img src="<?php echo base_url(); ?>uploads/staffavatars/<?php echo $staff['staffavatar']; ?>" alt="<?php echo $staff['staffname']; ?>">
							</div>
							<div class="user-display-info">
								<div class="name">
									<?php echo $staff['staffname']; ?>
								</div>
								<div class="nick"><span class="mdi mdi-account"></span> <?php echo $staff['department']; ?></div>
							</div>
							<div class="row user-display-details">
								<div class="col-xs-4">
									<div class="title"><?php echo lang('sales')?></div>
									<div class="counter"><b class="money-area"><?php echo $staffsalesgraph ?></b></div>
								</div>
								<div class="col-xs-4">
									<div class="title"><?php echo lang('customers')?></div>
									<div class="counter">
										<b><?php echo $havecustomers ?></b>
									</div>
								</div>
								<div class="col-xs-4">
									<div class="title"><?php echo lang('tickets')?></div>
									<div class="counter"><b><?php echo $havetickets ?></b></div>
								</div>
							</div>
						</div>
					</div>

					<div class="panel panel-default borderten">
						<div class="panel-heading panel-heading-divider"><?php echo lang('staffactivitiestitle')?><span class="panel-subtitle"><?php echo lang('staffactivitiesdescription')?></span>
						</div>
						<div class="panel-body">
							<ul class="user-timeline">
								<?php foreach ($logs as $log){ ?>
								<li>
									<div class="user-timeline-date">
										<?php echo $log['staffmembername']; ?>
									</div>
									<div class="user-timeline-title">
										<?php echo tes_ciuis($log['date']); ?>
									</div>
									<!-- Zaman Geri Sayma -->
									<div class="user-timeline-description">
										<?php echo $log['detail']; ?>
									</div>
								</li>
								<?php } ?>
							</ul>
						</div>
					</div>

				</div>
				<div class="col-md-7">
					<div class="user-info-list panel panel-default borderten">
						<div class="panel-heading panel-heading-divider">
						<?php if (!if_admin) {echo'<a href="'.base_url('staff/edit/'.$staff['id'].'').'" class="btn btn-default btn-lg pull-right">'.lang('edit').'</a>';}?>
						<?php echo lang('aboutofstafftitle')?><span class="panel-subtitle"><?php echo lang('aboutofstaffdescription')?></span>
						</div>
						<div style="margin-top: 10px;margin-bottom: 30px;" class="col-md-12">
							<div class="widget-chart-container">
								<div class="widget-counter-group widget-counter-group-right">
									<div class="pull-left md-mb-10">
										<div class="pull-left text-left">
											<h4><b><?php echo lang('staffsalesgraphtitle')?></b></h4>
											<small><?php echo lang('staffsalesgraphdescription')?></small>
										</div>
									</div>
									<div class="counter counter-big">
										<div class="text-warning value">
											<b class="money-area"><?php echo $staffsalesgraph ?></b>
										</div>
										<div class="desc"><?php echo lang('inthisyear')?></div>
									</div>
								</div>
								<div ng-controller="mainChartCtrl" class="md-mb-20">
									<div class="chart-wrapper" style="height:270px">
										<canvas id="staffmembersales" height="270px"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="panel panel-default borderten">
							<div class="tab-container">
								<ul class="nav nav-tabs nav-tabs-primary">
									<li class="active"><a href="#staffmember" data-toggle="tab" aria-expanded="true"><?php echo lang('staffinfotitle')?></a>
									</li>
									<li class=""><a href="#invoices" data-toggle="tab" aria-expanded="false"><?php echo lang('invoices')?></a>
									</li>
									<li class=""><a href="#tickets" data-toggle="tab" aria-expanded="false"><?php echo lang('tickets')?></a>
									</li>
								</ul>
								<div class="tab-content borderten md-p-0">
									<div id="staffmember" class="tab-pane cont active md-p-20">
										<div class="row">
											<div class="panel panel-default">
												<div class="panel-body">
													<table class="no-border no-strip skills">
														<tbody class="no-border-x no-border-y">
															<tr>
																<td class="icon"><span class="mdi mdi-case"></span>
																</td>
																<td class="item"><?php echo lang('department')?><span class="icon s7-portfolio"></span>
																</td>
																<td>
																	<?php echo $staff['department']; ?>
																</td>
															</tr>
															<tr>
																<td class="icon"><span class="mdi mdi-cake"></span>
																</td>
																<td class="item"><?php echo lang('staffbirthday')?><span class="icon s7-gift"></span>
																</td>
																<td>
																	<?php echo $staff['birthday']; ?>
																</td>
															</tr>
															<tr>
																<td class="icon"><span class="ion-android-call"></span>
																</td>
																<td class="item"><?php echo lang('staffphone')?><span class="icon s7-phone"></span>
																</td>
																<td>
																	<?php echo $staff['phone']; ?>
																</td>
															</tr>
															<tr>
																<td class="icon"><span class="mdi ion-location"></span>
																</td>
																<td class="item"><?php echo lang('staffaddress')?><span class="icon s7-map-marker"></span>
																</td>
																<td>
																	<?php echo $staff['address']; ?>
																</td>
															</tr>
															<tr>
																<td class="icon"><span class="mdi ion-android-mail"></span>
																</td>
																<td class="item"><?php echo lang('staffemail')?><span class="icon s7-global"></span>
																</td>
																<td>
																	<?php echo $staff['email']; ?>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
									<div id="invoices" class="tab-pane">
										<div class="panel panel-default panel-table">
											<div class="panel-body">
												<table id="table2" class="table table-striped table-hover table-fw-widget">
													<thead>
														<tr>
															<th><?php echo lang('id')?></th>
															<th><?php echo lang('dateofissuance')?></th>
															<th class="text-right"><?php echo lang('total')?></th>
														</tr>
													</thead>
													<?php foreach($invoices as $mf){ ?>
													<tr>
														<td>
															<a class="label label-default" href="<?php echo base_url('invoices/invoice/'.$mf['id'].'')?>"><i class="ion-document"> </i><?php echo $mf['id']; ?></a>
														</td>
														<td>
															<?php echo _adate($mf['datecreated']); ?>
														</td>
														<td class="text-right">
														<span class="money-area"><?php echo $mf['total'] ?></span>
														</td>
													</tr>
													<?php } ?>
												</table>
											</div>
										</div>
									</div>
									<div id="tickets" class="tab-pane">
										<div class="panel panel-default panel-table">
											<div class="panel-body">
												<table id="table2" class="table table-striped table-hover table-fw-widget">
													<thead>
														<tr>
															<th><?php echo lang('ticket')?></th>
															<th><?php echo lang('date')?></th>
															<th class="text-right"><?php echo lang('lastreply')?></th>
														</tr>
													</thead>
													<?php foreach($tickets as $ticket){ ?>
													<tr>
														<td>
															<a class="label label-default" href="<?php echo base_url('tickets/ticket/'.$ticket['id'].'')?>"><i class="ion-document"> </i><?php echo $ticket['subject']; ?></a>
														</td>
														<td>
															<?php echo _adate($ticket['date']); ?>
														</td>
														<td class="text-right">
														<span class="money-area"><?php echo _adate($ticket['lastreply']); ?></span>
														</td>
													</tr>
													<?php } ?>
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
		</div>
	</div>
<?php include_once dirname(dirname(__FILE__)) . '/inc/sidebar.php'; ?>
<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_table.php'; ?>
</div>
<script src="<?php echo base_url(); ?>assets/crm/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/crm/lib/chartjs/dist/Chart.bundle.js" type="text/javascript"></script>
<script>
	$( function () {
		var data = {
			"labels": [ "<?php echo lang('january'); ?>", "<?php echo lang('february'); ?>", "<?php echo lang('march'); ?>", "<?php echo lang('april'); ?>", "<?php echo lang('may'); ?>", "<?php echo lang('june'); ?>", "<?php echo lang('july'); ?>", "<?php echo lang('august'); ?>", "<?php echo lang('september'); ?>", "<?php echo lang('october'); ?>", "<?php echo lang('november'); ?>", "<?php echo lang('december'); ?>" ],
			"datasets": [ {
					"type": "line",
					backgroundColor: '#ffbc00',
					"hoverBorderColor": "#f5f5f5",

					borderColor: 'black',
					borderWidth: 1,
					"data": <?php echo $staffmembersales ?>,
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
		var ctx = $( '#staffmembersales' );
		var mainChart = new Chart( ctx, {
			type: 'bar',
			data: data,
			options: options
		} );
	} );
</script>
<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_table.php'; ?>