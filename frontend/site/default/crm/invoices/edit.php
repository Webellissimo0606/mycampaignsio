<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-md-9">
		<?php echo form_open('invoices/edit/'.$invoices['id'],array("class"=>"form-horizontal")); ?>
		<div style="border-radius: 10px;" class="panel panel-white panel-form">
			<div class="panel-body" style="padding:30px 20px;">
				<div class="invoice-extra button-properties-list col-md-8 md-p-0">
				<div class="form-group property md-p-0" data-name="series" data-title="<?php echo lang('invoicenumber')?>" data-status="<?php if($invoices['no'] < 0){echo 'passive';}else echo 'active';?>">
					<div class="col-sm-3">
					<label for="in_series" class=""><?php echo lang('serie')?></label>
						<div class="input-group-icon">
							<input type="text" name="series" id="in_series" class="form-control input-xs" value="<?php echo ($this->input->post('series') ? $this->input->post('series') : $invoices['series']); ?>">
							<div class="input-group-icon-addon"><i class="fa fa-sort-alpha-asc fa-fw"></i>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
					<label for="in_no" class=""><?php echo lang('invno')?></label>
						<div class="input-group-icon">
							<input type="text" name="no" id="in_no" class="form-control input-xs" value="<?php echo ($this->input->post('no') ? $this->input->post('no') : str_pad($invoices['no'], 6, '0', STR_PAD_LEFT)); ?>">
							<div class="input-group-icon-addon"><i class="fa fa-sort-numeric-asc fa-fw"></i>
							</div>
						</div>
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
							<a href="<?php echo site_url('invoices/invoice/'.$invoices['id'].''); ?>" class="btn btn-default btn-lg">View Invoice</a>
							<a href="<?php echo site_url('invoices/'); ?>" class="btn btn-default btn-lg"><?php echo lang('cancel'); ?></a>
							<button name="saveinvoice" type="submit" class="btn btn-space btn-default btn-lg"><?php echo lang('save'); ?></button>
						</div>
					</div>
				</div>
				<hr>
				<div class="col-md-6">
					<div class="form-group">
						<label for="in_account_name" class="col-sm-3 control-label control-label"><?php echo lang('choisecustomer'); ?></label>
						<div class="col-sm-9 add-on-edit">
							<select required name="customerid" class="form-control select2">
							<option value="<?php echo $invoices['customerid'];?>"><?php if($invoices['type']==0) {echo $invoices['customer'];} else echo $invoices['individual'];?></option>
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
					<div class="form-group">
						<label for="in_date_issue" class="col-sm-3 control-label"><?php echo lang('dateofissuance'); ?></label>
						<div class="col-sm-9">
							<div data-min-view="2" data-date-format="dd.mm.yyyy" class="input-group date datetimepicker"> <span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
								<input type='input' name="datecreated" value="<?php echo ($this->input->post('datecreated') ? $this->input->post('datecreated') : _udate($invoices['datecreated'])); ?>" class="form-control" id="datecreated"/>
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
								<label class="btn btn-lg btn-default btn-reverse <?php if($invoices['statusid'] == 3){echo'active';}?>">
								<input name="statusid" type="radio" autocomplete="off" <?php if($invoices['statusid'] == 3){echo'checked';}?> value="3">
								<?php echo lang('willbepaid'); ?></label>
											<label class="btn btn-lg btn-default btn-reverse <?php if($invoices['statusid'] == 2){echo'active';}?>">
								<input name="statusid" type="radio" autocomplete="off" <?php if($invoices['statusid'] == 2){echo'checked';}?> value="2">
								<?php echo lang('paid'); ?></label>
								<label class="btn btn-lg btn-default btn-reverse <?php if($invoices['statusid'] == 1){echo'active';}?>">
								<input name="statusid" type="radio"  autocomplete="off" <?php if($invoices['statusid'] == 1){echo'checked';}?>  value="1">
								<?php echo lang('draft'); ?></label>
							</div>
							<div class="btn-group pull-right">
							<button type="button" class="btn btn-default btn-lg dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<?php echo lang('markas')?> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<input hidden="" type="text" class="cancelid" value="4">
								<input hidden="" type="text" class="invoiceidpost" value="<?php echo $invoices['id']?>">
								<li><a class="invoicecancelled"><?php echo lang('cancelled')?></a></li>
							</ul>
						</div>
						</div>
					</div>
						<div id="toggle-kasa-bilgileri">
							<div class="form-group toggle-cash" style="<?php if($invoices['statusid'] == 2){echo'display:interit';}else echo 'display:none'?>">
								<label for="vade" class="col-md-3 control-label control-label-sm"></label>
								<div class="col-md-9">
									<div role="alert" class="alert alert-success alert-icon alert-icon-colored alert-dismissible" style="margin-top: 5px;">
									<div class="icon"><span class="mdi <?php if($invoices['statusid'] == 2){echo 'mdi-check';}else echo 'ion-alert';?>"></span></div>
									<div class="message" style="display: inherit">
										<strong><?php if($invoices['statusid'] == 2){echo lang('thisinvoicehasbeenpaid');}else echo lang('recordpaymentfirst');?></strong>
									</div>
								  	</div>
								</div>
							</div>
							<div class="form-group toggle-due" style="<?php if($invoices['statusid'] == 3){echo'display:interit';}else echo 'display:none'?>">
							<label for="vade" class="col-md-3 control-label control-label-sm"><?php echo lang('duedate'); ?></label>
							<div class="col-md-9">
								<div data-min-view="2" data-date-format="dd.mm.yyyy" class="input-group date datetimepicker"> <span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
									<input placeholder="<?php echo date(" d.m.Y "); ?>" type='input' name="duedate" value="<?php echo ($this->input->post('duedate') ? $this->input->post('duedate') : _udate($invoices['duedate'])); ?>" class="form-control" id="duedate"/>
								</div>
							</div>
						</div>
						<div class="form-group cancelledinvoicealert" style="<?php if($invoices['statusid'] == 4){echo'display:interit';}else echo 'display:none'?>">
								<label for="vade" class="col-md-3 control-label control-label-sm"></label>
								<div class="col-md-9">
									<div role="alert" class="alert alert-danger alert-icon alert-icon-colored alert-dismissible" style="margin-top: 5px;">
									<div class="icon"><span class="mdi ion-android-cancel"></span></div>
									<div class="message" style="display: inherit">
										<strong><?php echo lang('cancelledinvoice');?></strong>
									</div>
								  	</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" class="input-sub-total" name="total_sub" value="0">
				<input type="hidden" class="input-line-discount" name="total_discount" value="0">
				<input type="hidden" class="input-vat-total" name="total_vat" value="0">
				<input type="hidden" class="input-grant-total" name="total" value="0">
			</div>
		</div>
		<div style="border-radius: 10px;" class="panel panel-white second-form">
			<div class="panel-body2 table-responsive">
				<table class="table table-hovered table-invoice">
					<thead>
					<tr>
						<th width="330"><?php echo lang('productservice'); ?></th>
						<th width="210" style="min-width:127px;"><?php echo lang('quantity'); ?></th>
						<th width="210"><?php echo lang('price'); ?></th>
						<th width="210"><?php echo lang('tax'); ?></th>
						<th width="230"><?php echo lang('total'); ?></th>
						<th width="40"></th>
						<th width="40"></th>
					</tr>
					</thead>
					<tbody>
						<?php foreach($invoiceitems as $item){?>
						<input hidden="" type="text" name="in[itemid][]" value="<?php echo $item['id']?>">
						<tr class="line select-properties-list line-new">
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
						<td style="vertical-align: middle"><a data-invitemid="<?php echo $item['id']?>" class="btn btn-default btn-sm delete-line"><i class="icon icon-left mdi mdi mdi-delete text-danger"></i></a>
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
						<td style="vertical-align: middle;"><a class="btn btn-default btn-sm delete-line deldb"><i class="icon icon-left mdi mdi mdi-delete text-danger"></i></a>
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
											<div class="sub-total"><span class="money-format"><?php echo $invoices['total_sub']?></span>
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
										<th class="text-right"><span class="money-format"><?php echo $invoices['total_discount']?></span>
										</th>
										<th></th>
									</tr>
									<tr class="no-border sub-total-discount property" data-name="subtotaldiscount" data-title="<?php echo lang('subtotaldiscount')?>" data-status="<?php if ($invoices['sub_discount'] == 0) {echo 'passive';} else echo'active';?>">
										<th width="200">
											<?php echo lang('subtotaldiscount')?>
										</th>
										<th class="text-right">
											<div class="input-group">
												<input type="text" name="sub_discount" class="form-control filter-money input-sub-discount delete-on-delete" value="<?php echo $invoices['sub_discount'] ?>">
												<input name="sub_discount_type" type="hidden" value="<?php echo $invoices['sub_discount_type'] ?>" class="input-discount-type">
												<input type="hidden" name="total_sub_discount" value="<?php echo $invoices['total_sub_discount'] ?>" class="input-sub-discount-total">
												<input type="hidden" name="sub_discount_status" value="<?php echo $invoices['sub_discount_status'] ?>" class="input-status">
												<div class="input-group-addon text-muted">%</div>
											</div>
										</th>
										<th> <a class="btn btn-sm btn-default delete text-danger"><i class="icon icon-left mdi mdi mdi-delete"></i></a> </th>
									</tr>
									<tr class="no-border gross-total">
										<th width="200">
											<?php echo lang('grosstotal')?><?php $grosstotal = ($invoices['total_sub'] - $invoices['total_discount']);?>
										</th>
										<th class="text-right"><span class="money-format"><?php echo $grosstotal?></span>
										</th>
										<th></th>
									</tr>
								</tbody>
								<tbody>
									<tr>
										<th>TAX</th>
										<th class="text-right"><span class="vat-total money-format"><?php echo $invoices['total_vat']?></span>
										</th>
										<th></th>
									</tr>
								</tbody>
								<tbody>
									<tr class="money-bold">
										<th>
											<?php echo lang('grandtotal'); ?>
										</th>
										<th class="text-right"><span class="grant-total money-format"><?php echo $invoices['total']?></span>
										</th>
										<th></th>
									</tr>
								</tbody>
							</table>
							<input type="hidden" class="input-sub-total" name="total_sub" value="<?php echo $invoices['total_sub'] ?>">
							<input type="hidden" class="input-line-discount" name="total_discount" value="<?php echo $invoices['total_discount'] ?>">
							<input type="hidden" class="input-vat-total" name="total_vat" value="<?php echo $invoices['total_vat'] ?>">
							<input type="hidden" class="input-grant-total" name="total" value="<?php echo $invoices['total'] ?>">
							<input type="hidden" name="staffid" value="<?php echo $this->session->userdata('logged_in_staff_id'); ?>">
						</td>
					</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
	</form>
	<!--<script>
		function showEdit( editableItem ) {
			$( editableItem ).css( "background", "#FFF" );
		}

		function UpdateSingleInvoiceItem( editableItem, column, id ) {
			$( editableItem ).css( "background", "gray" );
			$.ajax( {
				url: "<?php echo base_url('invoices/updateinvoiceitemsingle');?>",
				type: "POST",
				data: 'column=' + column + '&lastvalue=' + editableItem.value + '&id=' + id,
				success: function ( data ) {
					$( editableItem ).css( "background", "#e0f5c9" );
				}
			} );
		}
	</script>-->
	<?php include_once dirname(dirname(__FILE__)) . '/inc/invoice_sidebar.php'; ?>
	<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_invoice.php' ;?>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script>
	$(function () {
		"use strict";
		var id;
		id = new Invoice_Create({currency : 'USD', edit : 1,type : 'sale', payment_count : 0,copy:0});
	});			
	</script>