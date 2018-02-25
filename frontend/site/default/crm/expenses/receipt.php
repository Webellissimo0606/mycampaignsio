<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="row">
			<div class="col-md-4 md-pr-0">
				<div class="ciuis-expenses-ciuis-expenses-receipt-detail-container">
					<div class="ciuis-expenses-ciuis-expenses-receipt-detail-left">
						<div class="ciuis-expenses-ciuis-expenses-receipt-detail-info-box">
							<div class="ciuis-expenses-receipt-detail"> <?php echo lang('receiptfor')?> </br>
								<span class="text-muted text-danger"><?php echo lang('expenseprefix'),'-',str_pad($expenses['id'], 6, '0', STR_PAD_LEFT) ?></span>
								<div class="btn-group btn-space pull-right">								
								<button type="button" data-target="#editexpense" data-toggle="modal" data-placement="left" title="" class="btn btn-lg btn-default" data-original-title="Edit"><?php echo lang('edit');?></button>
								<button data-toggle="modal" data-target="#remove" class="btn btn-lg btn-default"><i class="ion-trash-b"></i></button>
								</div>								
								<br><h4><?php echo $expenses['title'] ?></h4> <small><?php echo $expenses['desc'] ?></small></div>
							<div class="ciuis-expenses-receipt-xs-colum"> <i class="icon-wallet" aria-hidden="true"></i>
								<p><?php echo lang('amount')?>:</br>
									<span style="font-size: 26px;font-weight: 900;">
									<span class="money-area"><?php echo $expenses['amount']?></span> 
									</span>
								<br><small><?php echo lang('paidvia')?> <b><?php echo $expenses['account'];?></b></small>
								</p>
							</div>
							<div class="ciuis-expenses-receipt-xs-colum"> <i class="icon-calendar" aria-hidden="true"></i>
								<p><?php echo lang('date')?>:</br>
									<span><b><?php echo  _adate($expenses['date']); ?></b></span>
								</p>
							</div>
							<div class="ciuis-expenses-receipt-xs-colum"> <i class="icon-star" aria-hidden="true"></i>
								<p><?php echo lang('staff')?>:</br>
									<span><b><?php echo $expenses['staff'] ?></b></span>
								</p>
							</div>
							<div class="ciuis-expenses-receipt-xs-colum"> <i class="icon-star" aria-hidden="true"></i>
								<p><?php echo lang('category')?>:</br>
									<span><b><?php echo $expenses['category'] ?></b></span>
								</p>
							</div>
						</div>
						<div style="<?php if($expenses['customerid'] == 0){echo 'display:none';}?>" class="expenseconvert"> 
						<a style="<?php if($expenses['invoiceid'] != NULL){echo 'display:none';}?>" href="#" data-target="#convert" data-toggle="modal" class="expenseconvertbutton"><b><i class="ion-document-text"> </i><?php echo lang('convertinvoice')?></b></a>
						<a style="<?php if($expenses['invoiceid'] == NULL){echo 'display:none';}?>" href="<?php echo base_url('invoices/invoice/'.$expenses['invoiceid'].'')?>" class="expenseconvertbutton"><b><i class="ion-document-text"> </i><?php echo lang('invoiceprefix'),'-',str_pad($expenses['invoiceid'], 6, '0', STR_PAD_LEFT) ?></b></a>
						</div>
					</div>
				</div>
			</div>
		<div class="col-md-8">
			<div style="border-radius: 10px;" class="panel panel-default panel-table">
				<div class="panel-heading">
					<B><?php echo lang('expensestitle'); ?></B>
					<div class="pull-right btn-space" id="buttons"></div>
					<button type="button" data-target="#addexpense" data-toggle="modal" data-placement="left" title="" class="btn btn-space btn-default md-trigger btn-lg pull-right" data-original-title="Add Expense"><i class="ion-plus-round"></i> <?php echo lang('addexpense'); ?></button>
					<span class="panel-subtitle"><?php echo lang('expensesdescription'); ?></span>
				</div>
				<div class="panel-body">
					<table id="table2" class="table table-striped table-hover table-fw-widget">
						<thead>
							<tr class="text-muted">
								<th width="100px"><?php echo lang('expense'); ?></th>
								<th><?php echo lang('detail'); ?></th>
								<th><?php echo lang('category'); ?></th>
								<th><?php echo lang('date'); ?></th>
								<th class="text-right"><?php echo lang('amount'); ?></th>
							</tr>
						</thead>
						<?php foreach($expensesdata as $expense){ ?>
						<tr>
							<td width="130px" class="ciuis_expense_receipt">
								<a class="ciuis_expense_receipt_number huppur" href="<?php echo base_url('expenses/receipt/'.$expense['id'].'');?>"><span><?php echo lang('expenseprefix'),'-',str_pad($expense['id'], 6, '0', STR_PAD_LEFT) ?></span></a>
							</td>
							<td class="md-pt-10">
								<span><b><?php echo $expense['title']; ?></b></span>
								<p>
								<code class="pull-left md-mb-5"><?php echo lang('addedby'); ?> <a href="<?php echo base_url('staff/staffmember/'.$expense['staffid'].'')?>"><?php echo $expense['staff']; ?></a></code>
								</p>
							</td>
							<td><?php echo $expense['category']; ?></td>
							<td class="md-pt-10">
							<?php switch($settings['dateformat']){ 
								case 'yy-mm-dd': echo _pdate($expense['date']);break; 
								case 'dd-mm-yy': echo _udate($expense['date']); break;
							}?>
							</td>
							<td class="md-pt-10 text-right">
								<b class="money-area"><?php echo $expense['amount']?></b><BR>
								<div style="<?php if($expense['customerid'] == 0){echo 'display:none';} ?>">
									<?php if ( $expense[ 'invoiceid' ] == NULL ) {$billstatus = lang('notbilled') and $color = 'warning';}else $billstatus = lang('billed') and $color = 'success'; ?>
									<span class="label label-<?php echo $color ?>"><?php echo  $billstatus ?></span>
								</div>
							</td>
						</tr>
						<?php } ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include_once dirname(dirname(__FILE__)) . '/inc/sidebar.php'; ?>
<table style="visibility: hidden" id="table" class="table table-striped table-hover table-fw-widget">
<thead>
	<tr class="text-muted">
		<th><?php echo lang('id')?></th>
		<th><?php echo lang('title')?></th>
		<th><?php echo lang('description')?></th>
		<th><?php echo lang('staff')?></th>
		<th><?php echo lang('category')?></th>
		<th><?php echo lang('date')?></th>
		<th><?php echo lang('amount')?></th>
	</tr>
</thead>
<?php foreach($expensesdata as $expense){ ?>
<tr>
	<td><?php echo $expense['id']; ?></td>
	<td><?php echo $expense['title']; ?></td>
	<td><?php echo $expense['description']; ?></td>
	<td><?php echo $expense['staff']; ?></td>
	<td><?php echo $expense['category']; ?></td>
	<td><?php echo $expense['date']; ?></td>
	<td><?php echo currency;?> <?php echo number_format($expense['amount'], 2, ',', '.'); ?></td>
</tr>
<?php } ?>
</table>
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
							<?php echo lang('expensesatentiondetail'); ?>
						</p>
						<div class="xs-mt-50">
							<a type="button" data-dismiss="modal" class="btn btn-space btn-default">
								<?php echo lang('cancel'); ?>
							</a>
							<a href="<?php echo base_url('expenses/remove/'.$expenses['id'].'')?>" type="button" class="btn btn-space btn-danger">
								<?php echo lang('delete'); ?>
							</a>
						</div>
					</div>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
<div id="convert" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
		<?php echo form_open('expenses/convertinvoice/'.$expenses['id'].'',array("class"=>"form-vertical")); ?>
			<div class="modal-header">
				<button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
			</div>
			<div class="modal-body">
			<input type="hidden" name="itemname" value="<?php echo $expenses['title']?>">
			<input type="hidden" name="total" value="<?php echo $expenses['amount']?>">
			<input type="hidden" name="description" value="<?php echo $expenses['description']?>">
				<div class="text-center">
					<div class="text-success"><span class="modal-main-icon mdi mdi-info"></span>
					</div>
					<h3>
						<?php echo lang('information'); ?>
					</h3>
					<p>
						<?php echo lang('convertexpensedetail'); ?>
					</p>
					<div class="xs-mt-50">
						<a type="button" data-dismiss="modal" class="btn btn-space btn-default">
							<?php echo lang('cancel'); ?>
						</a>
						<button type="submit" class="btn btn-space btn-default">
							<?php echo lang('convert'); ?>
						</button>
					</div>
				</div>
			</div>
			<div class="modal-footer"></div>
		<?php echo form_close(); ?>
		</div>
	</div>
</div>
<div id="editexpense" tabindex="-1" role="content" class="modal fade colored-header colored-header-dark">
	<div class="modal-dialog">
		<?php echo form_open('expenses/edit/'.$expenses['id'],array("class"=>"form-vertical")); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
				<h3 class="modal-title"><?php echo lang('updateexpense')?> <?php echo $expenses['id']?></h3>
			</div>
			<div class="col-md-6 md-pr-0">
				<div class="modal-body md-pr-5">
					<div class="form-group">
						<label for="title"><?php echo lang('title'); ?></label>
						<div class="input-group"><span class="input-group-addon"><i class="ion-information"></i></span>
							<input type="text" name="title" value="<?php echo ($this->input->post('title') ? $this->input->post('title') : $expenses['title']); ?>" class="form-control" id="title" placeholder="title"/>
						</div>
					</div>
					<div class="form-group">
						<label for="amount"><?php echo lang('amount'); ?></label>
						<div class="input-group"><span class="input-group-addon"><i class="ion-social-usd"></i></span>
							<input type="text" name="amount" value="<?php echo ($this->input->post('amount') ? $this->input->post('amount') : $expenses['amount']); ?>" class="form-control" id="amount" placeholder="amount"/>
						</div>
					</div>
					 <div class="form-group">
                     	<label for="date"><?php echo lang('date'); ?></label>
                       <div data-min-view="50" class="input-group"> 
						<span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
                        <input data-date-format="yyyy-mm-dd hh:ii" placeholder="<?php echo date(" d.m.Y "); ?>" required name="date" type="text" value="<?php echo ($this->input->post('date') ? $this->input->post('date') : $expenses['date']); ?>" class="form-control datetimepicker">
                        </div>
                    </div>
				</div>
			</div>
			<div class="col-md-6 md-pl-0">
				<div class="modal-body md-pl-5">
					<div class="form-group">
						<label for="expensecategoryid"><?php echo lang('category'); ?></label>
						<div class="add-on-edit">
							<select name="expensecategoryid" class="form-control select2">
								<option value="<?php echo $expenses['expensecategoryid'];?>"><?php echo $expenses['category'];?></option>
								<?php
								foreach ( $expensecat as $expensecategory ) {
									$selected = ( $expensecategory[ 'id' ] == $this->input->post( 'expensecategoryid' ) ) ? ' selected="selected"' : null;
									echo '<option value="' . $expensecategory[ 'id' ] . '" ' . $selected . '>' . $expensecategory[ 'name' ] . '</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="accountid"><?php echo lang('choiseaccount'); ?></label>
						<div class="add-on-edit">
							<select name="accountid" class="form-control select2">
								<option value="<?php echo $expenses['accountid'];?>"><?php echo $expenses['account'];?></option>
								<?php
								foreach ( $all_accounts as $account ) {
									$selected = ( $account[ 'id' ] == $this->input->post( 'accountid' ) ) ? ' selected="selected"' : null;
									echo '<option value="' . $account[ 'id' ] . '" ' . $selected . '>' . $account[ 'name' ] . '</option>';
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="customerid"><?php echo lang('choisecustomer'); ?></label>
						<div class="add-on-edit">
							<select name="customerid" class="form-control select2">
							<option value="<?php echo $expenses['customerid'];?>"><?php if($expenses['type']==0) {echo $expenses['customer'];} else echo $expenses['individual'];?></option>
								<?php
								foreach ( $all_customers as $customers ) {
									$selected = ( $customers[ 'id' ] == $this->input->post( 'customerid' ) ) ? ' selected="selected"' : null;
									if ($customers[ 'type' ] ==0 ){
									echo '<option value="' . $customers[ 'id' ] . '" ' . $selected . '>' . $customers[ 'companyname' ] . '</option>';}
									else echo '<option value="' . $customers[ 'id' ] . '" ' . $selected . '>' . $customers[ 'namesurname' ] . '</option>';
								}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<input hidden="" type="text" name="dateadded" value="<?php echo date("Y-m-d H:i:s"); ?>"/>
			<input type="hidden" name="staffid" value="<?php echo $this->session->userdata('logged_in_staff_id'); ?>">
			<div class="col-md-12 md-pt-0">
				<div class="modal-body md-pt-0">
					<div class="form-group">
					<label for="description"><?php echo lang('description'); ?></label>
						<div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-ios-compose-outline"></i></span>
							<textarea name="description" class="form-control" id="description" placeholder="Description"><?php echo ($this->input->post('description') ? $this->input->post('description') : $expenses['desc']); ?></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default modal-close"><?php echo lang('cancel'); ?></button>
					<button type="submit" class="btn btn-default"><?php echo lang('save'); ?></button>
				</div>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_table.php'; ?>
<script>
		var table = $( '#table' ).DataTable({
        "paging":   false,
    });
		var buttons = new $.fn.dataTable.Buttons( table, {
			select: true,
			buttons: [{
				extend: 'print',
				text: '<i class="ion-printer"></i> <?php echo lang('print'); ?>',
				className: 'btn-lg pull-right btn-default',
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
