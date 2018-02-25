<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php';?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="col-md-12 nopadding">
			<div class="ciuis-389123411">
				<div class="ciuis98765789 fadeIn">
					<div class="ciuis9876578d">
						<div class="ciuis9876578detail-24242424"></div>
						<img src="<?php echo base_url() ?>assets/crm/img/accountbg.png" style="width: 125px;" alt="" class="pull-right">
						<h2><?php echo $accounts['name']; ?></h2>
						<h4 class="text-bold money-area"><b><?php $this->db->select_sum('amount')->from('payments')->where('(accountid = ' . $accounts['id'] . ' and transactiontype = 0)');
$mbb = $this->db->get();
if ($mbb->row()->amount > 0) {
	echo $mbb->row()->amount;
} else {
    echo '0.00'.$currency ;
}?></b></h4>
						<p>
							<b><?php echo lang('accountstatus'); ?>: <?php switch ($accounts['status']) {
case '0':echo '<span class="text-success">' . lang('active') . '</span>';
	break;case '1':echo '<span class="text-danger">' . lang('inactive') . '</span>';
	break;}?></b>
						</p>
						<div class="bar">
							<div class="complete"><b class="text-muted"><?php if ($accounts['type'] == 1) {echo '' . $accounts['bankname'] . ' / ' . $accounts['branchbank'] . '';}?></b></div>
							<div class="complete"><b><?php if ($accounts['type'] == 1) {echo '' . lang('account') . ': ' . $accounts['account'] . '';}?></b></div>
							<div class="complete"><b><?php if ($accounts['type'] == 1) {echo '' . lang('iban') . ': ' . $accounts['iban'] . '';}?></b></div>
						</div>
					</div>
					<div class="ciuis987654789080">
						<div class="ciuis-asx-mob-245">
							<div class="panel panel-default panel-table borderten">
								<div class="panel-heading"><?php echo lang('accountactivity'); ?><div class="tools">
										<div class="btn-group">
											<button data-toggle="modal" data-target="#exportdata" class="pull-left btn btn-default"><i class="icon icon-left ion-ios-cloud-download hidden-xs"></i><?php echo lang('exportdata'); ?></button>
										<button data-toggle="modal" data-target="#remove" class="pull-left btn btn-default"><?php echo lang('delete'); ?></button>
										<button data-toggle="modal" data-target="#update" class="pull-left btn btn-default"><?php echo lang('edit'); ?></button>
										</div>
									</div>
									<span class="panel-subtitle"><?php
switch ($accounts['type']) {
case '0':
	echo lang('accountactivitydetailcash');
	break;
case '1':
	echo lang('accountactivitydetailbank');
	break;
}
?></span>
								</div>
								<div class="panel-body">
									<table id="table" class="table table-striped table-hover table-fw-widget">
										<thead>
											<tr>
												<th><?php echo lang('transactiontype'); ?> &nbsp; <i class="ion-arrow-swap"></i></th>
												<th class="hidden-xs"><?php echo lang('transactiondate'); ?></th>
												<th class="hidden-xs"><?php echo lang('description'); ?></th>
												<th style="text-align: right;"><?php echo lang('amount'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($payments as $payment) {
	?>
											<tr style="<?php if ($payment['transactiontype'] == 1) {echo 'background-color: #ffedea;';}?>">
												<td><?php
if ($payment['transactiontype'] == 0) {
		echo '<span class="text-success"><b>' . lang('payment') . ' <i class="ion-arrow-down-a"></i></b></span>';} else {
		echo '<span class="text-danger"><b>' . lang('expense') . ' <i class="ion-arrow-up-a"></i></b</span>';
	}

	?></td>
												<td class="hidden-xs"><?php echo _adate($payment['date']); ?></td>
												<td class="hidden-xs"><?php echo $payment['not']; ?></td>
												<td style="text-align:right;">
												<span class="money-area"><?php echo $payment['amount']; ?></span>
												</td>
											</tr>
											<?php }?>
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
	<div id="exportdata" tabindex="-1" role="dialog" style="" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
				</div>
				<div class="modal-body">
					<div class="text-center">
						<h3><?php echo lang('exportmodaltitle'); ?></h3>
						<span><?php echo lang('exportmodaldescription'); ?></span>
						<hr>
						<div id="buttons"></div>
					</div>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
	<div id="remove" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
			</div>
			<div class="modal-body">
				<div class="text-center">
					<div class="text-danger"><span class="modal-main-icon mdi mdi-close-circle-o"></span>
					</div>
					<h3>
						<?php echo lang('attention'); ?>
					</h3>
					<p>
						<?php echo lang('accountattentiondetail'); ?>
					</p>
					<div class="xs-mt-50">
						<a type="button" data-dismiss="modal" class="btn btn-space btn-default">
							<?php echo lang('cancel'); ?>
						</a>
						<a href="<?php echo base_url('accounts/remove/' . $accounts['id'] . '') ?>" type="button" class="btn btn-space btn-danger">
							<?php echo lang('delete'); ?>
						</a>
					</div>
				</div>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>
<div id="update" tabindex="-1" role="dialog" class="modal fade colored-header colored-header-warning">
		 <?php echo form_open('accounts/update/' . $accounts['id']); ?>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
					<h3 class="modal-title text-modal">
						<?php echo lang('updateaccount'); ?>
					</h3>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label class=""><?php echo lang('accountname') ?></label>
								<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-money-box"></i></span>
									<input required type="text" name="name" value="<?php echo ($this->input->post('name') ? $this->input->post('name') : $accounts['name']); ?>" class="form-control" id="name"/ placeholder="<?php echo lang('accountname') ?>">
								</div>
							</div>
							<div <?php if ($accounts['type'] == 0) {echo 'style="display:none"';}?>class="bank">
								<div class="form-group col-md-6 xs-pt-10 xs-pb-10 xs-pl-0">
									<label class=""><?php echo lang('bankname') ?></label>
									<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-balance"></i></span>
										<input type="text" name="bankname" value="<?php echo ($this->input->post('bankname') ? $this->input->post('bankname') : $accounts['bankname']); ?>" class="form-control" id="bankname"/ placeholder="Global Bank">
									</div>
								</div>
								<div class="form-group col-md-6 xs-pt-10 xs-pb-10 xs-pr-0 xs-pl-0">
									<label class=""><?php echo lang('branchbank') ?></label>
									<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-city-alt"></i></span>
										<input type="text" name="branchbank" value="<?php echo ($this->input->post('branchbank') ? $this->input->post('branchbank') : $accounts['branchbank']); ?>" class="form-control" id="branchbank"/ placeholder="Eg: Paris">
									</div>
								</div>
								<div class="form-group xs-pb-10">
									<label class=""><?php echo lang('accountnumber') ?></label>
									<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-collection-item-1"></i></span>
										<input type="text" name="account" value="<?php echo ($this->input->post('account') ? $this->input->post('account') : $accounts['account']); ?>" class="form-control" id="account"/ placeholder="0000071219812874">
									</div>
								</div>
								<div class="form-group xs-pb-10">
									<label class=""><?php echo lang('iban') ?></label>
									<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-card"></i></span>
										<input type="text" name="iban" value="<?php echo ($this->input->post('iban') ? $this->input->post('iban') : $accounts['iban']); ?>" class="form-control" id="iban"/ placeholder="GB29 RBOS 6016 1331 9268 19">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="switch-button switch-button-success pull-left">
						<input type="checkbox" <?=$accounts['status'] == 1 ? 'checked value="1"' : 'value="1"'?> name="status" id="swt6"><span>
							<label for="swt6"></label></span>
					</div>
					<button type="button" data-dismiss="modal" class="btn btn-default modal-close">
						<?php echo lang('cancel'); ?>
					</button>
					<button type="submit" class="btn btn-default modal-close">
						<?php echo lang('update'); ?>
					</button>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
	<?php include_once dirname(dirname(__FILE__)) . '/inc/sidebar.php';?>
	<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_table.php';?>
	<script>
		var table = $( '#table' ).DataTable();
		var buttons = new $.fn.dataTable.Buttons( table, {
			buttons: [ {
				extend: 'excel',
				text: '<img src="<?php echo base_url('assets/crm/img/excel.png'); ?>">',
				className: 'accountexportbutton',
				exportOptions: {
					modifier: {
						search: 'applied',
						order: 'applied'
					}
				}
			}, {
				extend: 'pdf',
				text: '<img src="<?php echo base_url('assets/crm/img/pdf.png'); ?>">',
				className: 'accountexportbutton',
				exportOptions: {
					modifier: {
						search: 'applied',
						order: 'applied'
					}
				}
			}, {
				extend: 'csv',
				text: '<img src="<?php echo base_url('assets/crm/img/csv.png'); ?>">',
				className: 'accountexportbutton',
				exportOptions: {
					modifier: {
						search: 'applied',
						order: 'applied'
					}
				}
			} ],
		} ).container().appendTo( $( '#buttons' ) );
	</script>
	<style>
		.dataTables_filter {
			display: none;
		}
		#table_length {
			display: none
		}
	</style>
	