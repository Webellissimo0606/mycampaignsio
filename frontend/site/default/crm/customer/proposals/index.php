<?php include_once(APPPATH . 'views/customer/inc/header.php'); ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div style="border-radius: 10px;" class="panel panel-default panel-table">
			<div class="panel-heading md-mb-20 md-pt-10" style="height: 48px; padding-top: 5px;">
				<div class="pull-left"><b class="text-uppercase"><?php echo lang('proposals') ?></b></div>
				<div class="pull-right">
					<div class="ciuis-external-search-in-table">
					  <input class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('searchword')?>">
					  <i class="ion-ios-search"></i>
					</div>
					<div style="width: 200px" class="btn-group btn-hspace">
						<select id="" class="select2" onChange="window.location.href=this.value" style="height: 40px !important;">
							<option value="?"><?php echo lang('filter') ?></option>
							<option value="?"><?php echo lang('all') ?></option>
							<option value="?filter-status=1"><?php echo lang('draft') ?></option>
							<option value="?filter-status=2"><?php echo lang('sent') ?></option>
							<option value="?filter-status=3"><?php echo lang('open') ?></option>
							<option value="?filter-status=4"><?php echo lang('revised') ?></option>
							<option value="?filter-status=5"><?php echo lang('declined') ?></option>
							<option value="?filter-status=6"><?php echo lang('accepted') ?></option>
						</select>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<table id="table2" class="table table-striped table-hover table-fw-widget">
					<thead>
						<tr>
							<th width="7%">
								<?php echo lang('id')?>
							</th>
							<th>
								<?php echo lang('name')?>
							</th>
							<th>
								<?php echo lang('to')?>
							</th>
							<th>
								<?php echo lang('total')?>
							</th>
							<th>
								<?php echo lang('date')?>
							</th>
							<th>
								<?php echo lang('opentill')?>
							</th>
							<th class="md-pl-20">
								<?php echo lang('assigned')?>
							</th>
							<th class="text-right">
								<?php echo lang('status')?>
							</th>
						</tr>
					</thead>
					<?php foreach($proposals as $proposal){ ?>
					<tr data-filterable data-filter-status="<?php echo $proposal['statusid']?>" data-filter-staff="<?php echo $proposal['assigned']?>" class="ciuis-254325232344" onclick="window.location='<?php echo base_url('share/proposal/'.$proposal['id'].'')?>'">
						<td>
							<?php echo $proposal['id']; ?>
							
						</td>
						<td>
							<strong>
								<?php echo $proposal['subject']; ?><br>
								<a href="<?php echo base_url('invoices/invoice/'.$proposal['invoiceid'].'')?>"  class="label label-default" style="<?php if($proposal['invoiceid'] === NULL){echo 'display:none';}?>"><b><i class="ion-document-text"> </i><?php echo lang('invoiced') ?></b></a>
							</strong>
						</td>
						<td>
							<?$pro = $this->Proposals_Model->get_proposals($proposal['id'],$proposal['relation_type'] );?>
							<?php if($pro['relation_type'] == 'customer'){if($pro['customer']===NULL){echo '<strong><a data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="'.lang('customer').'" href="'.base_url('customers/customer/'.$proposal['relation'].'').'">'.$pro['namesurname'].'</a></strong>' ;} else echo '<strong><a data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="'.lang('customer').'" href="'.base_url('customers/customer/'.$proposal['relation'].'').'">'.$pro['customer'].'</a></strong>';} ?>
							<?php if($pro['relation_type'] == 'lead'){echo '<strong><a data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="'.lang('lead').'" href="'.base_url('leads/lead/'.$proposal['relation'].'').'">'.$pro['leadname'].'</a></strong>';} ?>
						</td>
						<td>
							<strong class="text-muted"><span class="money-area"><?php echo $proposal['total']; ?></span></strong>
						</td>
						<td>
							<?php switch($settings['dateformat']){ 
								case 'yy.mm.dd': echo _rdate($proposal['date']);break; 
								case 'dd.mm.yy': echo _udate($proposal['date']); break;
								case 'yy-mm-dd': echo _mdate($proposal['date']); break;
								case 'dd-mm-yy': echo _cdate($proposal['date']); break;
								case 'yy/mm/dd': echo _zdate($proposal['date']); break;
								case 'dd/mm/yy': echo _kdate($proposal['date']); break;
								}?>
						</td>
						<td>
							<?php switch($settings['dateformat']){ 
								case 'yy.mm.dd': echo _rdate($proposal['opentill']);break; 
								case 'dd.mm.yy': echo _udate($proposal['opentill']); break;
								case 'yy-mm-dd': echo _mdate($proposal['opentill']); break;
								case 'dd-mm-yy': echo _cdate($proposal['opentill']); break;
								case 'yy/mm/dd': echo _zdate($proposal['opentill']); break;
								case 'dd/mm/yy': echo _kdate($proposal['opentill']); break;
								}?>
						</td>
						<td class="user-avatar md-pl-20"> <img src="<?php echo base_url('uploads/staffavatars/'.$proposal['staffavatar'].'')?>" alt="Avatar">
							<?php echo $proposal['staffmembername']; ?>
						</td>
						<td>
							<?php if($proposal['statusid'] == 1){echo '<span class="label label-success pull-right proposal-status-draft">'.lang('draft').'</span>';}  ?>
							<?php if($proposal['statusid'] == 2){echo '<span class="label label-success pull-right proposal-status-sent">'.lang('sent').'</span>';}  ?>
							<?php if($proposal['statusid'] == 3){echo '<span class="label label-success pull-right proposal-status-open">'.lang('open').'</span>';}  ?>
							<?php if($proposal['statusid'] == 4){echo '<span class="label label-success pull-right proposal-status-revised">'.lang('revised').'</span>';}  ?>
							<?php if($proposal['statusid'] == 5){echo '<span class="label label-success pull-right proposal-status-declined">'.lang('declined').'</span>';}  ?>
							<?php if($proposal['statusid'] == 6){echo '<span class="label label-success pull-right proposal-status-accepted">'.lang('accepted').'</span>';}  ?>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
<?php include_once(APPPATH . 'views/customer/inc/sidebar.php'); ?>
<?php include_once(APPPATH . 'views/inc/footer_table.php');?>