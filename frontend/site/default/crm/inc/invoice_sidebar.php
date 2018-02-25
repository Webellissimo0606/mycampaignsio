<?$inv = $invoices['id'] ?>
<?$total = $invoices['total'];?>
<aside class="page-aside ciuis-sag-sidebar-xs">
	<div class="ciuis-body-scroller nano ps-container ps-theme-default" data-ps-id="ac74da58-8e1c-6b15-4582-65d6b23ba5fc">
		<ul style="position: fixed;z-index: 99;width: 330px;background: linear-gradient(to bottom,#ffffff,#f7f7f7) #f5f5f5;border-top-right-radius: 0.5rem 0.5rem;border-top-left-radius: 0.5rem 0.5rem;" class="ciuis-sidemax nav nav-tabs">
			<li class="active"><a href="#events" data-toggle="tab" aria-expanded="true"><span style="font-size:18px;"><?php echo lang('balance')?> : <span class="money-area"><?php $kalan = $total - $tadtu->row()->amount;  echo $kalan ?></span></span>
			<?php if($tadtu->row()->amount < $total && $tadtu->row()->amount > 0 ){echo '<span class="pull-right label label-warning" style=" margin-top: 10px; margin-left: 40px; ">'.lang('partial').'</span>';}?></a>
			</li>
			<li>
				<?php if($kalan > 0) {echo '';}else echo '<h4 style="font-weight: 900; color: #22c39e; float: right; margin-top: 25px;">'.lang('paidinv').' <i class="icon ion-checkmark-circled"></i></h4>'; ?>
			</li>
		</ul>
		<div class="tab-container">
			<div style="margin-top: 60px;background: rgb(255, 255, 255);border-radius: 10px;padding: 20px;
    margin-bottom: 15px;" class="tab-content ">
				<div id="detail" class="tab-pane cont active">
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-12 detail_ui_elemani_invoice">
								<div class="col-md-2 nopadding">
									<span class="ion-ios-bell detail-ui-ikon-invoice"></span>
								</div>
								<div class="col-md-8 detail-ui-detail-invoice">
									<?php
									$today = time();
									$duedate = strtotime( $invoices[ 'duedate' ] ); // or your date as well
									$datecreated = strtotime( $invoices[ 'datecreated' ] );
									$paymentday = $duedate - $datecreated; // Bunun sonucu 14 gün olcak
									$paymentx = $today - $datecreated;
									$paymentdatenet = $paymentday - $paymentx;
									if($invoices['duedate'] == 0000-00-00){
										echo '<span class="badge">No Due Date</span>';
									}else{
										if ( $paymentdatenet < 0 ) {
										echo '<span class="text-danger mdi mdi-timer-off"></span> <span class="text-danger"><b>'.lang('overdue').'</b> </span>';
										echo '<b>', floor( $paymentdatenet / ( 60 * 60 * 24 ) ), '</b> days';;

									} else {
										echo lang('payableafter') . floor( $paymentdatenet / ( 60 * 60 * 24 ) ) . ' '.lang('day').'';

									}
									}
									?>
								</div>
							</div>
							<div class="col-md-12 detail_ui_elemani_invoice">
								<div class="col-md-2 nopadding">
									<span class="ion-android-mail detail-ui-ikon-invoice"></span>
								</div>
								<div class="col-md-8 detail-ui-detail-invoice">
									<?php if($invoices['datesend'] == '0000-00-00 00:00:00'){echo lang('notyetbeensent');}else echo _adate($invoices['datesend']);?>
								</div>
							</div>
							<div class="col-md-12 detail_ui_elemani_invoice">
								<div class="col-md-2 nopadding">
									<span class="ion-person detail-ui-ikon-invoice"></span>
								</div>
								<div class="col-md-8 detail-ui-detail-invoice"><a href="<?php echo $invoices['staffid'];?>"><b><?php echo $invoices['staffmembername'];?></b></a>
								</div>
							</div>
							<div class="btn-group">
								<a href="<?php echo base_url('invoices/share/'.$invoices['id'].''); ?>" class="btn btn-default"><i class="ion-send"></i> <?php echo lang('send'); ?></a>
								<?php echo form_close(); ?>
								<a target="new" href="<?php echo base_url('invoices/print_/'.$invoices['id'].''); ?>" type="submit" class="btn btn-default" data-original-title="<?php echo lang('print')?>" data-placement="bottom" data-toggle="tooltip"><i class="ion-android-print"></i></a>
								<a href="<?php echo base_url('invoices/download/'.$invoices['id'].''); ?>" type="submit" class="btn btn-space btn-default" data-original-title="<?php echo lang('download')?>" data-placement="bottom" data-toggle="tooltip"><i class="ion-arrow-down-c"></i></a>
							</div>
							<a type="button" <?php if($kalan> 0) {echo '';}else echo 'disabled'; ?> href="#paymentadd" data-toggle="tab" class="btn btn-default pull-right"><?php echo lang('recordpayment'); ?></a>
						</div>
					</div>
				</div>
				<div id="paymentadd" class="tab-pane cont">
					<div class="row">
						<?php echo form_open('payments/add/',array("class"=>"form-vertical")); ?>
						<div class="col-md-12">
							<div class="form-group">
								<label for="">
									<?php echo lang('datepayment')?>
								</label>
								<div data-min-view="2" data-date-format="dd.mm.yyyy" class="input-group date datetimepicker"> <span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
									<input required type='input' name="date" value="" placeholder="<?php echo date('d.m.Y');?>" class=" form-control" id=""/>
								</div>
							</div>
							<div class="form-group">
								<label for="">
									<?php echo lang('amount')?>
								</label>
								<div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-money"></i></span>
									<input type="text" name="amount" value="" class="input-money-format form-control" placeholder="0.00">
									<input type="hidden" name="invoicetotal" value="<?php echo $total; ?>">
									<input type="hidden" name="customerid" value="<?php echo $invoices['customerid']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="">
									<?php echo lang('description')?>
								</label>
								<div class="input-group xs-pt-10"><span class="input-group-addon"><i class="ion-asterisk"></i></span>
									<input type="text" name="not" value="" class=" form-control" id="" placeholder="Description for this payment">
								</div>
							</div>
							<div class="form-group">
								<label for="">Account</label>
								<select id="accountid" name="accountid" class="form-control select2">
									<?php
									foreach ( $accounts as $account ) {
										$selected = ( $account[ 'id' ] == $this->input->post( 'accountid' ) ) ? ' selected="selected"' : null;
										echo '<option value="' . $account[ 'id' ] . '" ' . $selected . '>' . $account[ 'name' ] . '</option>';
									}
									?>
								</select>
							</div>
							<input type="hidden" name="invoiceid" value="<?php echo $invoices['id'];?>">
							<input type="hidden" name="staffid" value="<?php echo $this->session->userdata('logged_in_staff_id'); ?>">
						</div>
						<div class="pull-right">
							<div class="btn-group">
								<a href="#detail" data-toggle="tab" aria-expanded="true" class="btn btn-default ">
									<?php echo lang('cancel')?>
								</a>
								<button type="submit" class="btn btn-space btn-default">
									<?php echo lang('save')?>
								</button>
							</div>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default panel-table borderten">
			<div class="panel-heading"><?php echo lang('payments')?><span class="panel-subtitle"><?php echo lang('paymentsside')?></span>
			</div>
			<div class="panel-body <?php if ($tadtu->row()->amount == 0){echo 'text-center';}?>">
			<?php if ($tadtu->row()->amount == 0){echo '<img width="80%" src="'.base_url('assets/img/payments.png').'">';}?>
			
				<table class="table <?php if ($tadtu->row()->amount == 0){echo 'hide';}?>">
					<thead>
						<tr>
							<th style="width:30%;"><?php echo lang('date')?></th>
							<th class="text-left"><?php echo lang('account')?></th>
							<th class="text-right"><?php echo lang('amount')?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($payments as $payment){?>
						<tr>
							<td>
								<small class="text-muted">
									<?php echo _udate($payment['date'])?>
								</small>
							</td>
							<td class="text-left">
								<small>
									<?php echo $payment['accountname'];?>
								</small>
							</td>
							<td class="text-right">
								<span class="badge">
								<span class="money-area"><?php echo $payment['amount']?></span>
								</span>
							</td>
						</tr>
						<?php }?>
					</tbody>
				</table>
				<br>
			</div>
		</div>
	</div>
</aside>