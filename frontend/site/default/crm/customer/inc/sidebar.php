<?php $overdueinvoices = $this->Customer_Model->overdueinvoices();?>
<?php $dueinvoices = $this->Customer_Model->dueinvoices();?>
<?php $logs = $this->Customer_Model->logs();?>
<aside class="page-aside hidden-md hidden-xs ciuis-sag-sidebar-xs">
	<div id="events">
		<div class="ciuis-1 col-xs-12 nopadding">
		<div class="col-xs-6 nopadding date-and-time-ciuis">
			<i class="ion-ios-clock-outline"></i>
			<span id="time-ciuis">
				<?php setlocale(LC_TIME, '' . lang('language_datetime') . '');
$time = date('H:i');
echo $time;?>
			</span>
		</div>
		<div class="col-xs-6 date-a">
			<?php setlocale(LC_ALL, '' . lang('language_datetime') . '');
echo strftime("%d %B %Y, %A");?>
		</div>
		</div>
		<div class="row">
			<div class="events events_xs col-md-12">
				<ul>
					<?php foreach ($overdueinvoices as $invoice) {
	?>
					<li class="next overdueinvoices">
						<label class="date"> <i class="ion-alert"></i> </label>
						<a style="color:#393939" href="<?php echo base_url('invoices/invoice/' . $invoice['id'] . '') ?>"> <span class='class="text-white" ion-ios-arrow-forward'></span> </a>
						<h3 class="text-bold overduetext"><?php echo lang('overdueinvoices') ?> ( <?php echo lang('invoiceprefix'), '-', $invoice['id']; ?> )</h3>
						<p>
							<span style="color:#393939;" class='duration text-bold'>
								<?php if ($invoice['type'] == 0) {echo $invoice['customer'];} else {
		echo $invoice['individual'];
	}
	?>
							</span>
							<span style="color:#393939;" class='location text-bold'>
								<?php echo number_format($invoice['total'], 2, ',', '.'), currency; ?>
							</span>
							<span style="color:#8a2b24;" class='duration text-bold'>
								<?php
$today = time();
	$duedate = strtotime($invoice['duedate']); // or your date as well
	$datecreated = strtotime($invoice['datecreated']);
	$paymentday = $duedate - $datecreated; // Calculate days left.
	$paymentx = $today - $datecreated;
	$paymentdatenet = $paymentday - $paymentx;
	if ($paymentdatenet < 0) {
		echo '<span class="text-default mdi mdi-timer-off"></span> <span class="text-default"><b>Over</b> </span>';
		echo '<b>', floor($paymentdatenet / (60 * 60 * 24)), '</b> days';

	}
	?>
							</span>
						</p>
					</li>
					<?php }?>
					<?php foreach ($dueinvoices as $invoice) {
	?>
					<li class="next dueinvoices">
						<label class="date"> <i class="ion-alert"></i> </label>
						<a href="<?php echo base_url('invoices/invoice/' . $invoice['id'] . '') ?>"> <span class='ion-ios-arrow-forward'></span> </a>
						<h3 class="text-bold"><?php echo lang('duepayment'); ?> ( <?php echo lang('invoiceprefix'), '-', $invoice['id']; ?> )</h3>
						<p>
							<span style="color:black;" class='duration text-bold'>
								<?php if ($invoice['type'] == 0) {echo $invoice['customer'];} else {
		echo $invoice['individual'];
	}
	?>
							</span>
							<span style="color:black;" class='location text-bold'>
								<?php echo number_format($invoice['total'], 2, ',', '.'), currency; ?>
							</span>
						</p>
					</li>
					<?php }?>
				</ul>
			</div>
			<div class="ciuis-activity-line col-md-12">
				<ul class="ciuis-activity-timeline">
					<?php foreach ($logs as $log) {?>
					<li class="ciuis-activity-detail" ng-repeat="activity  ciuis-activity-past">
						<div class="ciuis-activity-title">
							<?php echo _adate($log['date']); ?>
						</div>
						<div class="ciuis-activity-detail-body">
							<?php echo $log['detail']; ?>
							<div style="margin-right: 15px;border-radius: 7px;background: transparent;color: #bdbdbd;" class="pull-right label label-default"><small><?php echo tes_ciuis($log['date']); ?></small></div>
						</div>
					</li>
					<?php }?>
					<a href="#" id="loadMore" class="activity_tumu"><i style="font-size:22px;" class="icon ion-android-arrow-down"></i></a>
				</ul>
			</div>
		</div>
	</div>
</aside>