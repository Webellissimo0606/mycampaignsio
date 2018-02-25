<?php $logs = $this->Logs_Model->panel_last_logs();?>
<?php $events = $this->Events_Model->get_all_events();?>
<?php $newtickets = $this->Tickets_Model->get_all_open_tickets();?>
<?php $dueinvoices = $this->Invoices_Model->dueinvoices();?>
<?php $overdueinvoices = $this->Invoices_Model->overdueinvoices();?>
<?php $todaypaymentsaction = $this->Payments_Model->todaypayments();?>
<?php $remindersidebar = $this->Trivia_Model->get_reminders();?>
<aside class="page-aside hidden-md hidden-sm hidden-xs ciuis-sag-sidebar-xs">
	<div id="events">
		<div class="ciuis-1 col-xs-12 nopadding">
				<div class="col-xs-4 nopadding date-and-time-ciuis">
					<i class="ion-ios-clock-outline"></i>
					<span id="time-ciuis">
						<?php setlocale(LC_TIME, '' . lang('language_datetime') . '');
$time = date('H:i');
echo $time;?>
					</span>
				</div>
				<div class="col-xs-7 date-a">
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
						<a href="<?php echo base_url('invoices/invoice/' . $invoice['id'] . '') ?>"> <span class='class="text-white" ion-ios-arrow-forward'></span> </a>
						<h3 class="text-bold overduetext"><?php echo lang('overdueinvoices') ?> ( INV-<?php echo $invoice['id']; ?> )</h3>
						<p>
							<span class='duration text-bold'>
								<?php if ($invoice['type'] == 0) {echo $invoice['customer'];} else {
		echo $invoice['individual'];
	}
	?>
							</span>
							<span class='location text-bold'>
								<span class="money-area"><?php echo $invoice['total'] ?></span>
							</span>
							<br>
							<span style="color: #ee7a6b;" class='text-bold'>
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
						<h3 class="text-bold"><?php echo lang('duepayment'); ?> ( INV-<?php echo $invoice['id']; ?> )</h3>
						<p>
							<span style="color:black;" class='duration text-bold'>
								<?php if ($invoice['type'] == 0) {echo $invoice['customer'];} else {
		echo $invoice['individual'];
	}
	?>
							</span>
							<span style="color:black;" class='location text-bold'>
								<span class="money-area"><?php echo $invoice['total'] ?></span>
							</span>
						</p>
					</li>
					<?php }?>
					<?php foreach ($todaypaymentsaction as $moneyaction) {
	?>
					<li class="next <?php if ($moneyaction['transactiontype'] == 0) {echo 'paymenttoday';} else {
		echo 'expensetoday';
	}
	?> ">
						<label class="detail"> <i class="<?php if ($moneyaction['transactiontype'] == 0) {echo 'ion-log-in';} else {
		echo 'ion-log-out';
	}
	?>"></i> </label>
						<a href=""> <span class='ion-ios-arrow-forward'></span> </a>
						<h3 class="text-bold"><?php if ($moneyaction['transactiontype'] == 0) {echo lang('paymentistoday');} else {
		echo lang('expensetoday');
	}
	?><small data-toggle="popover" data-trigger="hover" title="<?php echo lang('information') ?>" data-content="Next Features, testing." data-placement="top"><i style="font-size:14px;" class="icon ion-help-circled text-muted"></i></small></h3>
						<p>
							<span style="color:black;" class='duration text-bold'>
							<span class="money-area"><?php echo $moneyaction['amount'] ?></span>
							</span>
						</p>
					</li>
					<?php }?>
					<?php foreach ($newtickets as $ticket) {
	?>
					<li class="next newticketsidebar">
						<label class="date"> <i class="mdi mdi-ticket-star"></i> </label>
						<a href="<?php echo base_url('tickets/ticket/' . $ticket['id'] . '') ?>"> <span class='ion-ios-arrow-forward'></span> </a>
						<h3 class="text-bold"><?php echo $ticket['subject']; ?></h3>
						<p>
							<span style="color:black;" class='duration text-bold'>
								<?php echo $ticket['contactname']; ?> <?php echo $ticket['contactsurname']; ?>
							</span>
							<span style="color:black;" class='location text-bold'>
								<?php
if ($ticket['priority'] == 0) {echo '<span class="ticket-priority label label-primary">LOW</span>';}
	if ($ticket['priority'] == 1) {echo '<span class="ticket-priority label label-warning">MEDIUM</span>';}
	if ($ticket['priority'] == 2) {echo '<span class="ticket-priority label label-danger">HIGH</span>';}
	?>
							</span>
						</p>
					</li>
					<?php }?>
					<?php foreach ($events as $event) {
	?>
					<li class="<?php if ($event['end'] < date(" Y-m-d h:i:sa ")) {echo 'past';} else {echo 'next';}?>">
						<label class="date"> <span class="weekday"><?php $date = date('D', strtotime($event['start']));
	echo $date;?></span> <span class="day"><?php echo _dDay($event['start']); ?></span> </label>
						<a href=""> <span class='ion-ios-arrow-forward'></span> </a>
						<h3>
							<?php echo $event['title']; ?>
						</h3>
						<p>
							<span class='duration'>
								<?php echo _adate($event['start']); ?>
							</span>
							<span class='location'>
								<?php echo $event['staffmembername']; ?>
							</span>
						</p>
					</li>
					<?php }?>
					<?php foreach ($remindersidebar as $reminderside) {?>
					<li class="next reminder" data-reminderid="<?php echo $reminderside['id'] ?>">
						<label class="detail"> <i class="ion-ios-bell"></i> </label>
						<a class="mark-read-reminder" style="cursor: pointer"> <span class='ion-checkmark-round'></span> </a>
						<h3 class="text-bold" style="margin-bottom: 5px">
						<?php if ($reminderside['relation_type'] == 'event') {echo lang('eventreminder');}?>
						<?php if ($reminderside['relation_type'] == 'lead') {echo lang('leadreminder');}?>
						<?php if ($reminderside['relation_type'] == 'customer') {echo lang('customerreminder');}?>
						<?php if ($reminderside['relation_type'] == 'invoice') {echo lang('invoicereminder');}?>
						<?php if ($reminderside['relation_type'] == 'expense') {echo lang('expensereminder');}?>
						<?php if ($reminderside['relation_type'] == 'ticket') {echo lang('ticketreminder');}?>
						<?php if ($reminderside['relation_type'] == 'proposal') {echo lang('proposalreminder');}?>
						<small data-toggle="popover" data-trigger="hover" title="<?php echo lang('information') ?>" data-content="This reminder created by <?php echo $reminderside['remindercreator'] ?>" data-placement="top"><i style="font-size:14px;" class="icon ion-help-circled text-muted"></i></small></h3>
						<span class="reminder-detail" style="display: table-cell;"><?php echo $reminderside['description'] ?></span>
						<p style="display: table-footer-group;">
							<span style="color:black;" class='duration text-bold'>
							<span class="money-area"><?php echo _adate($reminderside['date']) ?></span>
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
							<div style="margin-right: 15px;border-radius: 7px;background: transparent;color: #bdbdbd;" class="pull-right label label-default"><small class="log-date"><?php echo tes_ciuis($log['date']); ?></small></div>
						</div>
					</li>
					<?php }?>
					<a href="#" id="loadMore" class="activity_tumu"><i style="font-size:22px;" class="icon ion-android-arrow-down"></i></a>
				</ul>
			</div>
		</div>
	</div>
</aside>