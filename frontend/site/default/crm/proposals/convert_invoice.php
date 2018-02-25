<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
<div class="ciuis-body-content">
	<?php echo form_open('proposals/convertinvoice/'.$proposals['id'].'/',array("class"=>"form-horizontal main-content container-fluid col-xs-12 col-md-12 col-lg-9 invoice-form")); ?>
	<input type="hidden" name="id" value="">
	<input type="hidden" name="type" value="purchase_invoice">
	<input type="hidden" name="related_to" value="">
	<div style="border-radius: 10px;" class="panel panel-white panel-form">
		<div class="panel-body" style="padding:30px 20px;">
			<div class="invoice-extra button-properties-list col-md-8 md-p-0">
				<div class="form-group property md-p-0" data-name="series" data-title="<?php echo lang('invoicenumber')?>" data-status="passive">
					<label for="in_series" class="col-sm-2 control-label"><?php echo lang('serie')?></label>
					<div class="col-sm-2">
						<div class="input-group-icon">
							<input type="text" name="series" id="in_series" class="form-control input-xs" value="<?php echo $this->input->post('series'); ?>">
							<div class="input-group-icon-addon"><i class="fa fa-sort-alpha-asc fa-fw"></i>
							</div>
						</div>
					</div>
					<label for="in_no" class="col-sm-2 control-label control-label-sm"><?php echo lang('invno')?></label>
					<div class="col-sm-4">
						<div class="input-group-icon">
							<input type="text" name="no" id="in_no" class="form-control input-xs" value="<?php echo $this->input->post('no'); ?>">
							<div class="input-group-icon-addon"><i class="fa fa-sort-numeric-asc fa-fw"></i>
							</div>
						</div>
					</div>
					<div class="col-sm-2"><br class="visible-xs">
						<a href="#" class="btn btn-default delete"><i class="icon icon-left mdi mdi mdi-delete text-danger"></i></a>
					</div>
				</div>
				<div class="form-group md-p-0">
					<div class="col-sm-1">
						<div class="button-properties" style="visibility: visible;"><button class="btn btn-lg btn-default btn-reverse" data-name="series"><i class="icon ion-plus"></i> <?php echo lang('addinvoicenumber'); ?></button> </div>
					</div>
				</div>
			</div>
			<div class="form-group md-p-0">
				<div class="col-sm-4 hidden-xs">
					<div class="btn-group pull-right">
						<a href="<?php echo site_url('invoices/'); ?>" class="btn btn-default btn-lg"><?php echo lang('cancel'); ?></a>
						<button type="submit" class="btn btn-space btn-default btn-lg save-invoice"><?php echo lang('save'); ?></button>
					</div>
				</div>
			</div>
			<hr>
			<div class="col-md-6">
				<div class="form-group">
					<label for="in_account_name" class="col-sm-3 control-label control-label"><?php echo lang('invoicetablecustomer'); ?></label>
					<div class="col-sm-9 add-on-edit">
						<select  name="customerid" class="form-control select2">

							<option value="<?php echo $proposals['relation'];?>"><?php if($proposals['relation_type']==0) {echo $proposals['customer'];} else echo $proposals['individual'];?></option>
							
							
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="in_date_issue" class="col-sm-3 control-label"><?php echo lang('dateofissuance'); ?></label>
					<div class="col-sm-9">
						<div data-min-view="2" data-date-format="dd.mm.yyyy" class="input-group date datetimepicker"> <span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
							<input placeholder="<?php echo date(" d.m.Y "); ?>" required type='input' name="datecreated" value="<?php echo $this->input->post('datecreated'); ?>" class="form-control" id="datecreated"/>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div id="toggle-invoice-status">
					<div class="form-group">
						<label class="col-md-3 control-label control-label-sm"><?php echo lang('invoicestatus'); ?></label>
						<div class="col-md-9">
							<div class="btn-group" data-toggle="buttons">
								<label class="btn btn-lg btn-default btn-reverse active">
								<input name="statusid" type="radio" autocomplete="off" checked value="3">
								<?php echo lang('willbepaid'); ?></label>
											<label class="btn btn-lg btn-default btn-reverse">
								<input name="statusid" type="radio" autocomplete="off" value="2">
								<?php echo lang('paid'); ?></label>
								<label class="btn btn-lg btn-default btn-reverse">
								<input name="statusid" type="radio"  autocomplete="off"  value="1">
								<?php echo lang('draft'); ?></label>
							</div>
						</div>
					</div>
					<div id="toggle-account-info">
						<div class="form-group toggle-cash" style="display:none;">
							<label for="vade" class="col-md-3 control-label control-label-sm"><?php echo lang('paidcashornank'); ?></label>
							<div class="col-md-9">
								<select required name="accountid" class="form-control select2">
									<option value=""><?php echo lang('choiseaccount'); ?></option>
									<?php 
									foreach($all_accounts as $account)
									{
										$selected = ($account['id'] == $this->input->post('accountid')) ? ' selected="selected"' : null;

										echo '<option value="'.$account['id'].'" '.$selected.'>'.$account['name'].'</option>';
									} 
									?>
								</select>
							</div>
						</div>
						<div class="form-group toggle-payment" style="display:none;">
							<label for="vade" class="col-md-3 control-label control-label-sm"><span><?php echo lang('datepaid'); ?></span></label>
							<div class="col-md-9">
								<div class=" input-group-icon">
									<div data-min-view="2" data-date-format="dd.mm.yyyy" class="input-group date datetimepicker"> <span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
							<input required placeholder="<?php echo date(" d.m.Y "); ?>" type='input' name="date_payment" value="<?php echo $this->input->post('date_payment'); ?>" class="form-control" id="date_payment"/>
						</div>
								</div>
							</div>
						</div>
						<div class="form-group toggle-due" style="">
							<label for="vade" class="col-md-3 control-label control-label-sm"><?php echo lang('duedate'); ?></label>
							<div class="col-md-9">
								<div data-min-view="2" data-date-format="dd.mm.yyyy" class="input-group date datetimepicker"> <span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
									<input placeholder="<?php echo date(" d.m.Y "); ?>" type='input' name="duedate" value="<?php echo $this->input->post('date_payment'); ?>" class="form-control" id="duedate"/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="hidden-lg">
				<hr>
				<div class="form-group">
					<div class="col-md-offset-3 col-md-5">
						<button class="btn btn-save btn-lg" data-post-form="#form" tabindex="-1" data-loading-text="Kaydet"><?php echo lang('save'); ?></button>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-body2 table-responsive">
			<table class="table table-hovered table-invoice">
				<thead>
					<tr>
						<th width="330">
							<?php echo lang('productservice'); ?>
						</th>
						<th width="210" style="min-width:127px;">
							<?php echo lang('quantity'); ?>
						</th>
						<th width="210">
							<?php echo lang('price'); ?>
						</th>
						<th width="210">
							<?php echo lang('tax'); ?>
						</th>
						<th width="230">
							<?php echo lang('total'); ?>
						</th>
						<th width="40"></th>
						<th width="40"></th>
					</tr>
				</thead>
				<tbody>
						<?php foreach($proposalitems as $item){?>
						<input hidden="" type="text" name="in[itemid][]" value="<?php echo $item['id']?>">
						<tr class="line select-properties-list">
						<td class="add-on-edit">
							<div class="add-on-edit">
								<input class="input-productcode" type="hidden" name="in[product_id][]" value="<?php echo $item['in[product_id]'];?>">
								<input type="text" name="in[name][]" class="form-control input-product autocomplate-product ui-autocomplete-input" autocomplete="off" value="<?php echo $item['in[name]'];?>">
								<small class="hide">
									<?php echo lang('new'); ?>
								</small>
							</div>
							<input type="hidden" name="in[code][]" class="form-control input-code">
							<div class="property" data-name="description" data-title="<?php echo lang('description')?>" data-status="<?php if ($item['in[description]'] == NULL) {echo 'passive';} else echo'active';?>">
								<textarea type="text" name="in[description][]" class="form-control input-item-description" placeholder="<?php echo lang('description')?>"><?php echo $item['in[description]'];?></textarea>
								<div class="property-delete"><a class="btn delete text-danger"><i class="icon icon-left mdi mdi mdi-delete"></i></a>
								</div>
							</div>
						</td>
						<td>
							<div class="input-group">
								<input type="text" name="in[amount][]" class="form-control input-amount filter-money" value="<?php echo $item['in[amount]'];?>">
								<div class="input-group-addon text-muted">
									<a class="input-unit-editable" tabindex="-1">
										<?php echo $item['in[unit]']?>
									</a>
									<input type="hidden" name="in[unit][]" value="<?php echo $item['in[unit]'];?>" class="input-unit input-xs">
								</div>
							</div>
						</td>
						<td>
							<div class="input-group-icon">
								<input type="text" name="in[price][]" class="form-control input-price filter-money" value="<?php echo $item['in[price]'];?>">
								<input type="hidden" name="in[pricepost][]" class="price-post" value="<?php echo $item['in[price]'];?>">
								<input type="hidden" name="in[price_discounted][]" class="form-control input-price-discounted" value="<?php echo $item['in[price_discounted]'];?>">
							</div>
							<div class="property" data-name="indirim" data-title="<?php echo lang('discount'); ?>" data-status="<?php if ($item['in[discount_rate]'] == 0) {echo 'passive';} else echo'active';?>">
								<div class="input-group">
									<input type="text" name="in[discount_rate][]" class="form-control input-discount-rate delete-on-delete" value="<?php echo $item['in[discount_rate]'];?>">
									<input name="in[discount_type][]" type="hidden" value="<?php echo $item['in[discount_type]'];?>" class="input-discount-type">
									<input type="hidden" name="in[discount_rate_status][]" class="form-control input-status" value="<?php echo $item['in[discount_rate_status]'];?>">
									<div class="input-group-addon text-muted">%</div>
								</div>
							</div>
						</td>
						<td>
							<div class="input-group">
								<input type="text" name="in[vat][]" class="form-control input-vat input-vat-vat filter-number" value="<?php echo $item['in[vat]'];?>">
								<input type="hidden" name="in[total_vat][]" class="input-vat-vat-total" value="<?php echo $item['in[total_vat]'];?>">
								<div class="input-group-addon text-muted">
									<?php echo lang('vat'); ?> %</div>
							</div>

						</td>
						<td>
							<div class="input-group">
								<input type="text" class="form-control input-total filter-money on-tab-add-line" value="<?php echo $item['in[total]'];?>">
								<input type="hidden" name="in[total][]" value="<?php echo $item['in[total]'];?>" class="input-total-real">
								<input name="in[currency][]" type="hidden" value="USD" class="input-currency">
								<input name="rate_in[currency][]" type="hidden" value="1" class="input-rate">
								<div class="input-group-addon text-muted">
									<?php echo currency;?>
								</div>
							</div>
						</td>
						<td style="vertical-align: middle">
							<div class="select-properties"></div>
						</td>
						<td style="vertical-align: middle"><a class="btn btn-default btn-sm delete-line"><i class="icon icon-left mdi mdi mdi-delete text-danger"></i></a>
						</td>
					</tr>
						<?php }?>
						<tr class="sample-line select-properties-list line-new" style="display:none;">
						<td class="add-on-edit">
							<div class="add-on-edit">
								<input type="hidden" name="in[itemid][]">
								<input hidden="" type="text" name="id" value="">
								<input class="input-productcode" type="hidden" name="in[product_id][]">
								<input type="text" name="in[name][]" class="form-control input-product autocomplate-product ui-autocomplete-input " autocomplete="on" placeholder="Product Name">
								<small class="hide"><?php echo lang('new'); ?></small> </div>
							<input type="hidden" name="in[code][]" class="form-control input-code">
							<div class="property" data-name="description" data-title="<?php echo lang('description')?>" data-status="passive">
								<textarea type="text" name="in[description][]" class="form-control" placeholder="<?php echo lang('description')?>"></textarea>
								<div class="property-delete"><a class="btn delete text-danger"><i class="icon icon-left mdi mdi mdi-delete"></i></a>
								</div>
							</div>
						</td>
						<td>
							<div class="input-group ">
								<input type="text" name="in[amount][]" class="form-control input-amount filter-money" value="1,00">
								<div class="input-group-addon text-muted">
									<a class="input-unit-editable" tabindex="-1"><?php echo lang('unit'); ?></a>
									<input type="hidden" name="in[unit][]" value="<?php echo lang('unit'); ?>" class="input-unit input-xs">
								</div>
							</div>
						</td>
						<td>
							<div class="input-group-icon ">
								<input type="text" name="in[price][]" class="form-control input-price filter-money">
								<input type="hidden" name="in[pricepost][]" class="price-post">
								<input type="hidden" name="in[price_discounted][]" class="form-control input-price-discounted" value="0">
							</div>
							<div class="property" data-name="indirim" data-title="<?php echo lang('discount'); ?>" data-status="passive">
							<div class="input-group ">
								 <input type="text" name="in[discount_rate][]" class="form-control input-discount-rate delete-on-delete" value="0">
							  <input name="in[discount_type][]" type="hidden" value="rate" class="input-discount-type">
							  <input type="hidden" name="in[discount_rate_status][]" class="form-control input-status" value="0">
								<div class="input-group-addon text-muted">%</div>
							</div>
						  </div>
						</td>
						<td>
							<div class="input-group ">
								<input type="text" name="in[vat][]" class="form-control input-vat input-vat-vat filter-number" value="0,00">
								<input type="hidden" name="in[total_vat][]" class="input-vat-vat-total" value="0">
								<div class="input-group-addon text-muted"><?php echo lang('vat'); ?> %</div>
							</div>
							
						</td>
						<td>
							<div class="input-group ">
								<input type="text" class="form-control input-total filter-money on-tab-add-line" value="0,00">
								<input type="hidden" name="in[total][]" class="input-total-real">
								<input name="in[currency][]" type="hidden" value="USD" class="input-currency">
								<input name="rate_in[currency][]" type="hidden" value="1" class="input-rate">
								<div class="input-group-addon text-muted"><?php echo currency;?></div>
							</div>
						</td>
						<td style="vertical-align: middle;">
							<div class="select-properties"></div>
						</td>
						<td style="vertical-align: middle;"><a class="btn btn-default btn-sm delete-line"><i class="icon icon-left mdi mdi mdi-delete text-danger"></i></a>
						</td>
					</tr>
					</tbody>
				<tfoot>
					<tr>
						<td colspan="3">
							<a class="btn btn-default btn-reverse lg-mt-30" id="add-line">
								<?php echo lang('addnewline'); ?>
							</a>
							<div class="clearfix"></div>
							<div class="pull-left" id="currency-list" style="margin-top:30px;"> </div>
						</td>
						<td colspan="3" rowspan="2" style="padding:0px;">
							<table class="table-total pull-right select-properties-list">
								<tbody>
									<tr class="sub-totals">
										<th width="280"><?php echo lang('subtotal'); ?></th>
										<th class="text-right" width="170">
											<div class="sub-total"><span class="money-format"><?php echo $proposals['total_sub']?></span>
											</div>
										</th>
										<th width="50">
											<div class="select-properties dropdown" style="display: none;"><a class="dropdown-toggle btn btn-sm btn-default " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <span class="vals"><i class="icon ion-plus"></i></span> </a>
												<ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu1">
													<li>
														<a href="#" data-name="subtotaldiscount">
															<?php echo lang('subtotaldiscount'); ?>
														</a>
													</li>
												</ul>
											</div>
										</th>
									</tr>
									<tr class="no-border line-discount">
										<th width="200">
											<?php echo lang('linediscount');?>
										</th>
										<th class="text-right"><span class="money-format"><?php echo $proposals['total_discount']?></span>
										</th>
										<th></th>
									</tr>
									<tr class="no-border sub-total-discount property" data-name="subtotaldiscount" data-title="<?php echo lang('subtotaldiscount')?>" data-status="<?php if ($proposals['sub_discount'] == 0) {echo 'passive';} else echo'active';?>">
										<th width="200">
											<?php echo lang('subtotaldiscount')?>
										</th>
										<th class="text-right">
											<div class="input-group">
												<input type="text" name="sub_discount" class="form-control filter-money input-sub-discount delete-on-delete" value="<?php echo $proposals['sub_discount'] ?>">
												<input name="sub_discount_type" type="hidden" value="<?php echo $proposals['sub_discount_type'] ?>" class="input-discount-type">
												<input type="hidden" name="total_sub_discount" value="<?php echo $proposals['total_sub_discount'] ?>" class="input-sub-discount-total">
												<input type="hidden" name="sub_discount_status" value="<?php echo $proposals['sub_discount_status'] ?>" class="input-status">
												<div class="input-group-addon text-muted">%</div>
											</div>
										</th>
										<th> <a class="btn btn-sm btn-default delete text-danger"><i class="icon icon-left mdi mdi mdi-delete"></i></a> </th>
									</tr>
									<tr class="no-border gross-total">
										<th width="200">
											<?php echo lang('grosstotal')?><?php $grosstotal = ($proposals['total_sub'] - $proposals['total_discount']);?>
										</th>
										<th class="text-right"><span class="money-format"><?php echo $grosstotal?></span>
										</th>
										<th></th>
									</tr>
								</tbody>
								<tbody>
									<tr>
										<th>TAX</th>
										<th class="text-right"><span class="vat-total money-format"><?php echo $proposals['total_vat']?></span>
										</th>
										<th></th>
									</tr>
								</tbody>
								<tbody>
									<tr class="money-bold">
										<th>
											<?php echo lang('grandtotal'); ?>
										</th>
										<th class="text-right"><span class="grant-total money-format"><?php echo $proposals['total']?></span>
										</th>
										<th></th>
									</tr>
								</tbody>
							</table>
							<input type="hidden" class="input-sub-total" name="total_sub" value="<?php echo $proposals['total_sub'] ?>">
							<input type="hidden" class="input-line-discount" name="total_discount" value="<?php echo $proposals['total_discount'] ?>">
							<input type="hidden" class="input-vat-total" name="total_vat" value="<?php echo $proposals['total_vat'] ?>">
							<input type="hidden" class="input-grant-total" name="total" value="<?php echo $proposals['total'] ?>">
							<input type="hidden" name="staffid" value="<?php echo $this->session->userdata('logged_in_staff_id'); ?>">
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	</form>
	<?php include_once( APPPATH . 'views/inc/sidebar.php' );?>
	<?php include_once( APPPATH . 'views/inc/footer_invoice.php' );?>
  	<script src="<?php echo base_url('assets/lib/jquery-ui/jquery-ui.js')?>"></script>
  	<link rel="stylesheet" href="<?php echo base_url('assets/lib/jquery-ui/jquery-ui.css')?>">
  	<script>
	$(function () {
		"use strict";
		var id;
		id = new Invoice_Create({currency : 'USD', edit : 1,type : 'sale', payment_count : 1,copy:0});
		
	});
	</script>
	<script>
	
	$( ".save-invoice" ).click( function (){
		var invoicetotal = $('.input-grant-total').val();
		if (invoicetotal > 0) {
				$( ".invoice-form" ).submit();
		}else{
			$.gritter.add( {
				title: '<b><?php echo lang('notification')?></b>',
				text: '<?php echo lang('pleaseenteritem')?>',
				position: 'bottom',
				class_name: 'color danger',
			} );
			speak(emptyinvoice);
		}
	});	
	</script>