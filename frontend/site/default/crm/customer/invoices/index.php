<?php include_once(APPPATH . 'views/customer/inc/header.php'); ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="panel-default">
			<div class="ciuis-invoice-summary"></div>
		</div>
		<div style="border-top-left-radius: 10px;border-top-right-radius: 10px;" class="panel panel-default panel-table">
			<div class="panel-heading" style="height: 70px;">
				<div class="pull-left">
					<b class="text-uppercase"><?php echo lang('invoices') ?></b>
				</div>
				<div class="pull-right">
					<div class="ciuis-external-search-in-table">
					  <input class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('searchword')?>">
					  <i class="ion-ios-search"></i>
					</div>
					<div style="width: 200px" class="btn-group btn-hspace">
						<select id="invoicefilterbystatus" class="select2" onChange="window.location.href=this.value">
						<option value='?'><?php echo lang('invoicefilterstatus') ?></option>
						<option value='?'><?php echo lang('all') ?></option>
						<option value="?filter-status=1"><?php echo lang('draft') ?></option>
						<option value="?filter-status=2"><?php echo lang('paid') ?></option>
						<option value="?filter-status=3"><?php echo lang('notpaid') ?></option>
						<option value="?filter-status=4"><?php echo lang('cancelled') ?></option>
					</select>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<table id="table2" class="table table-striped table-hover table-fw-widget">
					<thead style="">
						<tr>
							<th width="130px"><?php echo lang('invoiceidno'); ?></th>
							<th><?php echo lang('invoicetablecustomer'); ?></th>
							<th class="hidden-xs"><?php echo lang('billeddate'); ?></th>
							<th class="hidden-xs"><?php echo lang('invoiceduedate'); ?></th>
							<th class="hidden-xs"><?php echo lang('status'); ?></th>
							<th class="text-right"><?php echo lang('invoiceamount'); ?></th>
						</tr>
					</thead>
					<hr style="margin-bottom:0px;">
					<tbody>
						<?php foreach($invoices as $fa){ ?>
						<tr class="ciuis-254325232345">
							<td><a style="background: rgba(245, 245, 245, 0.4); padding: 5px; border-radius: 5px; border: 1px dashed #393939;" href="<?php echo site_url('customer/invoice/'.$fa['id']); ?>"><b class="text-muted"> <?php echo lang('invoiceprefix'),'-',str_pad($fa['id'], 6, '0', STR_PAD_LEFT); ?></b></a>
							</td>
							<td><span class="title uppercase"><a> <b><?php if($fa['customer']===NULL){echo $fa['namesurname'];} else echo $fa['customer']; ?></b></a></span>
							</td>
							<td class="hidden-xs">
								<b class="text-muted">
								<?php switch($settings['dateformat']){ 
								case 'yy.mm.dd': echo _rdate($fa['datecreated']);break; 
								case 'dd.mm.yy': echo _udate($fa['datecreated']); break;
								case 'yy-mm-dd': echo _mdate($fa['datecreated']); break;
								case 'dd-mm-yy': echo _cdate($fa['datecreated']); break;
								case 'yy/mm/dd': echo _zdate($fa['datecreated']); break;
								case 'dd/mm/yy': echo _kdate($fa['datecreated']); break;
								}?>
								</b>
							</td>
							<td class="hidden-xs">
								<b class="text-muted"><?php if($fa['duedate'] == 0000-00-00){echo '<span class="badge">No Due Date</span>';};?>
								<?php switch($settings['dateformat']){ 
								case 'yy.mm.dd': echo _rdate($fa['duedate']);break; 
								case 'dd.mm.yy': echo _udate($fa['duedate']); break;
								case 'yy-mm-dd': echo _mdate($fa['duedate']); break;
								case 'dd-mm-yy': echo _cdate($fa['duedate']); break;
								case 'yy/mm/dd': echo _zdate($fa['duedate']); break;
								case 'dd/mm/yy': echo _kdate($fa['duedate']); break;
								}?>
								</b>
							</td>
							<td class="hidden-xs">
							<?php $totalx = $fa['total'];$this->db->select_sum('amount')->from('payments')->where('(invoiceid ='.$fa['id'].') ');$paytotal = $this->db->get();
								$balance = $totalx - $paytotal->row()->amount;
								if($balance > 0) {echo '';}else echo '<h4 class="pull-left" style="font-weight: 900;color: #22c39e;">'.lang('paidinv').' <i class="icon ion-checkmark-circled"></i></h4>';					
								if($paytotal->row()->amount < $fa['total'] && $paytotal->row()->amount > 0 && $fa['statusid'] == 3){echo '<h4 class="text-uppercase pull-left" style="font-weight: 900;color: #ffbc00;display: table-cell;">'.lang('partial').' <i class="icon ion-checkmark-circled"></i></h4>';}else{
									if($paytotal->row()->amount < $fa['total'] && $paytotal->row()->amount > 0){echo '<h4 class="text-uppercase  pull-left" style="font-weight: 900;color: #ffbc00;display: table-cell;">'.lang('partial').' <i class="icon ion-checkmark-circled"></i></h4>';}
									if($fa['statusid'] == 3){echo '<h4 class="pull-left text-danger" style="font-weight: 900;display: table-cell;">'.lang('unpaid').' <i class="icon ion-close-circled"></i></h4>';}
								}
								if($fa['statusid'] == 1){echo '<h4 class="text-uppercase  pull-left text-muted" style="font-weight: 900;display: table-cell;">'.lang('draft').' <i class="icon ion-document"></i></h4>';}
								if($fa['statusid'] == 4){echo '<h4 class="text-uppercase  pull-left text-danger" style="font-weight: 900;display: table-cell;">'.lang('cancelled').' <i class="icon ion-android-cancel"></i></h4>';}
								?></td>
							<td class="text-right">
								<?php $totalx = $fa['total'];$this->db->select_sum('amount')->from('payments')->where('(invoiceid ='.$fa['id'].') ');$paytotal = $this->db->get();?>
								<?php $balance = $totalx - $paytotal->row()->amount;?>
								<h4 class="pull-right">
								<b><?php echo number_format($fa['total'], 2, ',', '.'); ?> <?php echo currency;?><br>
								<small><?php if($balance == 0){echo '';}else echo ''.lang('balance').': '.number_format($balance, 2, ',', '.').''. currency.''?></small></b>
								</h4>
							</td>
							<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php include_once(APPPATH . 'views/customer/inc/sidebar.php'); ?>
<?php include_once(APPPATH . 'views/inc/footer_table.php');?>
<script>
$('.search-table-external').on( 'keyup click', function () {
   $('#table2').DataTable().search(
	   $('.search-table-external').val()
   ).draw();
} );	
</script>