<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="row">
			<div class="col-md-4 md-pr-0">
				<div class="panel panel-default panel-table borderten">
					<div class="panel-heading"><b><?php echo lang('expensescategories'); ?></b>
						<button type="button" data-target="#addexpensecategory" data-toggle="modal" data-placement="left" title="" class="btn btn-space btn-default md-trigger pull-right" data-original-title="Add Expense Category"><i class="ion-plus-round"></i></button>
						<span class="panel-subtitle"><?php echo lang('expensescategoriessub'); ?>
					</div>
					<div class="panel-body">
					  <table id="table" class="table table-striped table-hover table-fw-widget">
						<thead>
							<tr class="text-muted">
								<th><?php echo lang('category')?></th>
								<th class="text-right"></th>
							</tr>
						</thead>
							<?php foreach ($expensecat as $e) {
    ?>
							<tr>
								<td><b class="text-muted"><?php echo $e['name']; ?></b></td>
								<td class="pull-right md-pt-5">
									<button type="button" data-target="#editexpensecategory<?php echo $e['id']; ?>" data-toggle="modal" data-placement="left" title="" class="btn btn-default" data-original-title="Update Expense Category"><i class="ion-ios-compose"></i></button>
									<a href="<?php echo site_url('expenses/removecategory/'.$e['id']); ?>"><button class="btn btn-default"><i class="ion-ios-trash"></i></button></a>
								</td>
							</tr>
							<div id="editexpensecategory<?php echo $e['id']; ?>" tabindex="-1" role="content" class="modal fade colored-header colored-header-dark">
								 <div class="modal-dialog">
								 <?php echo form_open('expenses/editcategory/'.$e['id']); ?>
								 <div class="modal-content">
								  <div class="modal-header">
									<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
										<h3 class="modal-title"><?php echo lang('updateexpensecategory'); ?></h3>
										</div>
										<div class="modal-body">
										<div class="form-group">
											<label for="name"><?php echo lang('name'); ?></label>
											<div class="input-group"><span class="input-group-addon"><i class="ion-navicon"></i></span>
												<input type="text" name="name" value="<?php echo($this->input->post('name') ? $this->input->post('name') : $e['name']); ?>" class="form-control" id="name" placeholder="name"/>
											</div>
										</div>
										<div class="form-group">
										<label for="name"><?php echo lang('description'); ?></label>
											<div class="input-group"><span class="input-group-addon"><i class="ion-ios-list-outline"></i></span>
												<textarea name="description" class="form-control" id="description" placeholder="<?php echo lang('description'); ?>"><?php echo($this->input->post('description') ? $this->input->post('description') : $e['description']); ?></textarea>
											</div>
										</div>
										</div>
										<div class="modal-footer">
											<button type="button" data-dismiss="modal" class="btn btn-default modal-close"><?php echo lang('cancel'); ?></button>
											<button type="submit" class="btn btn-default"><?php echo lang('save'); ?></button>
										</div>
									</div>
									<?php echo form_close(); ?>
								</div>
							</div>
							<?php
} ?>
					  </table>
					  <br>
					</div>
        		</div>
			</div>
			<div id="addexpensecategory" tabindex="-1" role="content" class="modal fade colored-header colored-header-dark">
			 <div class="modal-dialog">
			 <?php echo form_open('expenses/addcategory'); ?>
			 <div class="modal-content">
			  <div class="modal-header">
				<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
					<h3 class="modal-title"><?php echo lang('addexpensecategory')?></h3>
					</div>
					<div class="modal-body">
					<div class="form-group">
						<label for="name"><?php echo lang('name'); ?></label>
						<div class="input-group"><span class="input-group-addon"><i class="ion-navicon"></i></span>
							<input type="text" name="name" value="<?php echo $this->input->post('name'); ?>" class="form-control required" id="name" placeholder="name" required/>
						</div>
					</div>
					<div class="form-group">
					<label for="name"><?php echo lang('description'); ?></label>
						<div class="input-group"><span class="input-group-addon"><i class="ion-ios-list-outline"></i></span>
							<textarea name="description" class="form-control" id="description" placeholder="<?php echo lang('description');?>"><?php echo $this->input->post('description'); ?></textarea>
						</div>
					</div>

					</div>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" class="btn btn-default modal-close"><?php echo lang('cancel'); ?></button>
						<button type="submit" class="btn btn-default"><?php echo lang('save'); ?></button>
					</div>
				</div>
				<?php echo form_close(); ?>
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
								<th class="hidden-xs"><?php echo lang('category'); ?></th>
								<th class="hidden-xs"><?php echo lang('date'); ?></th>
								<th class="text-right"><?php echo lang('amount'); ?></th>
							</tr>
						</thead>
						<?php foreach ($expenses as $expense) {
        ?>
						<tr class="" onclick="window.location='<?php echo base_url('expenses/receipt/'.$expense['id'].'')?>'">
							<td width="130px" class="ciuis_expense_receipt">
								<a class="ciuis_expense_receipt_number" href="<?php echo base_url('expenses/receipt/'.$expense['id'].''); ?>"><span><?php echo lang('expenseprefix'),'-',str_pad($expense['id'], 6, '0', STR_PAD_LEFT) ?></span></a>
							</td>
							<td class="md-pt-10">
								<span><b><?php echo $expense['title']; ?></b></span>
								<p>
								<code class="pull-left md-mb-5"><?php echo lang('addedby'); ?> <a href="<?php echo base_url('staff/staffmember/'.$expense['staffid'].'')?>"><?php echo $expense['staff']; ?></a></code>
								</p>
							</td>
							<td class="hidden-xs"><?php echo $expense['category']; ?></td>
							<td class="md-pt-10 hidden-xs">
							<?php switch ($settings['dateformat']) {
                                case 'yy.mm.dd': echo _pdate($expense['date']);break;
                                case 'dd.mm.yy': echo _udate($expense['date']); break;
                            } ?>
							<td class="md-pt-10 text-right">
								<b class="money-area"><?php echo $expense['amount']?></b><BR>
								<div style="<?php if ($expense['customerid'] == 0) {
                                echo 'display:none';
                            } ?>">
									<?php if ($expense[ 'invoiceid' ] == null) {
                                $billstatus = lang('notbilled') and $color = 'warning';
                            } else {
                                $billstatus = lang('billed') and $color = 'success';
                            } ?>
									<span class="label label-<?php echo $color ?>"><?php echo  $billstatus ?></span>
								</div>
							</td>
						</tr>
						<?php
    } ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="addexpense" tabindex="-1" role="content" class="modal fade colored-header colored-header-dark">
	<div class="modal-dialog">
		<?php echo form_open('expenses/add'); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
				<h3 class="modal-title"><?php echo lang('addexpense')?></h3>
			</div>
			<div class="col-md-6 md-pr-0">
				<div class="modal-body md-pr-5">
					<div class="form-group">
						<label for="title"><?php echo lang('title'); ?></label>
						<div class="input-group"><span class="input-group-addon"><i class="ion-information"></i></span>
							<input required type="text" name="title" value="<?php echo $this->input->post('title'); ?>" class="form-control" id="title" placeholder="<?php echo lang('title'); ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label for="amount"><?php echo lang('amount'); ?></label>
						<div class="input-group"><span class="input-group-addon"><i class="ion-social-usd"></i></span>
							<input required type="text" name="amount" value="<?php echo $this->input->post('amount'); ?>" class="input-money-format form-control" id="amount" placeholder="0.00"/>
						</div>
					</div>
					<div class="form-group">
						<label for="date"><?php echo lang('date'); ?></label>
						<div data-min-view="2" data-date-format="dd.mm.yyyy" class="input-group date datetimepicker"> <span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
							<input placeholder="<?php echo date(" d.m.Y "); ?>" required type='input' name="date" value="<?php echo $this->input->post('date'); ?>" class="form-control" id="date"/>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 md-pl-0">
				<div class="modal-body md-pl-5">
					<div class="form-group">
						<label for="expensecategoryid"><?php echo lang('category'); ?></label>
						<div class="add-on-edit">
							<select name="expensecategoryid" class="form-control select2 required" required>
								<?php
                                foreach ($expensecat as $expensecategory) {
                                    $selected = ($expensecategory['id'] == $this->input->post('expensecategoryid')) ? ' selected="selected"' : "";
                                    echo '<option value="'.$expensecategory['id'].'" '.$selected.'>'.$expensecategory['name'].'</option>';
                                }
                                ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="accountid"><?php echo lang('choiseaccount'); ?></label>
						<div class="add-on-edit">
							<select name="accountid" class="form-control select2">
								<?php
                                    foreach ($all_accounts as $account) {
                                        $selected = ($account['id'] == $this->input->post('accountid')) ? ' selected="selected"' : null;
                                        echo '<option value="'.$account['id'].'" '.$selected.'>'.$account['name'].'</option>';
                                    }
                                    ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="customerid"><?php echo lang('choisecustomer'); ?></label>
						<div class="add-on-edit">
							<select name="customerid" class="form-control select2">
								<option value=""><?php echo lang('choisecustomer'); ?></option>
								<?php
                                foreach ($all_customers as $customers) {
                                    $selected = ($customers[ 'id' ] == $this->input->post('customerid')) ? ' selected="selected"' : null;
                                    if ($customers[ 'type' ] == 0) {
                                        echo '<option value="' . $customers[ 'id' ] . '" ' . $selected . '>' . $customers[ 'companyname' ] . '</option>';
                                    } else {
                                        echo '<option value="' . $customers[ 'id' ] . '" ' . $selected . '>' . $customers[ 'namesurname' ] . '</option>';
                                    }
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
							<textarea name="description" class="form-control" id="description" placeholder="Description"><?php echo $this->input->post('description'); ?></textarea>
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

<table style="visibility: hidden" id="tableexpense" class="table table-striped table-hover table-fw-widget">
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
<?php foreach ($expenses as $expense) {
                                    ?>
<tr>
	<td><?php echo $expense['id']; ?></td>
	<td><?php echo $expense['title']; ?></td>
	<td><?php echo $expense['description']; ?></td>
	<td><?php echo $expense['staff']; ?></td>
	<td><?php echo $expense['category']; ?></td>
	<td><?php echo $expense['date']; ?></td>
	<td><?php echo currency; ?> <?php echo number_format($expense['amount'], 2, ',', '.'); ?></td>
</tr>
<?php
                                } ?>
</table>

<?php include_once(dirname(dirname(__FILE__)) . '/inc/footer_table.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    $('form').each(function() {  // attach to all form elements on page
        $(this).validate({       // initialize plugin on each form
            // global options for plugin
        });
    });

});$(document).ready(function () {
"use strict";
$('.hiddentable').hide();
});		
</script>
<script>
		var table = $( '#tableexpense' ).DataTable({
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
	
