<?php include_once(dirname(dirname(__FILE__)) . '/inc/header.php'); ?>

<?php

$subject = $this->input->post('subject') ? $this->input->post('subject') : $proposals['subject'];
$related_customer = $this->input->post( 'related_customer' );
$related_lead = $this->input->post( 'related_lead' );
$assigned = $this->input->post('assigned') ? $this->input->post('assigned') : $proposals['assigned'];
$date = $this->input->post('date') ? $this->input->post('date') : _udate( $proposals['date'] );
$opentill = $this->input->post('opentill') ? $this->input->post('opentill') : _udate( $proposals['opentill'] );
$content = $this->input->post('content') ? $this->input->post('content') : $proposals['content'];

?>

<div class="ciuis-body-content">
	
	<div class="main-content container-fluid col-md-9">
		
		<?php echo form_open( 'proposals/edit/' . $proposals['id'], array( "class" => "form-horizontal" ) ); ?>
			
			<input class="relationer" name="relation_type" type="hidden" value="<?php echo $proposals['relation_type'] ?>">
			
			<div style="border-radius: 10px;" class="panel panel-white panel-form">
				
				<div class="panel-body" style="padding:30px 20px;">
					<div class="invoice-extra button-properties-list col-md-8">
						<h4><strong class="text-muted">ADD PROPOSAL</strong></h4>
					</div>
					<div class="form-group md-p-0">
						<div class="col-sm-4 hidden-xs">
							<div class="btn-group pull-right">
								<a href="<?php echo site_url('proposals/'); ?>" class="btn btn-default btn-lg">
									<?php echo lang('cancel'); ?>
								</a>
								<button type="submit" class="btn btn-space btn-default btn-lg save-invoice">
									<?php echo lang('save'); ?>
								</button>
							</div>
						</div>
					</div>
					<hr>
					<div class="col-md-6">
						<div class="form-group">
							<label for="title">
								<?php echo lang('subject') ?>
							</label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-information"></i></span>
								<input required="" type="text" name="subject" value="<?php echo $subject; ?>" class="form-control" id="subject" placeholder="<?php echo lang('subject') ?>">
							</div>
						</div>
						<div class="col-md-12 md-m-0 md-p-0">
							<div class="form-group">

								<div class="col-md-5 md-pl-0">
									<label for="in_account_name">
										<?php echo lang('related'); ?>
									</label>
									<select id="relation_type" required name="relation_type" class="form-control select2"> <?php 
										
										if ( 'customer' === $proposals['relation_type'] ) {
										    echo '<option value="customer" selected>' . lang('customer') . '</option>';
										}
										else{
											echo '<option value="customer">' . lang('customer') . '</option>';
										}

										if ( 'lead' === $proposals['relation_type'] ) {
										    echo '<option value="lead" selected>' . lang('lead') . '</option>';
										} 
										else{
											echo '<option value="lead">' . lang('lead') . '</option>';
										}?>
									</select>
								</div>
								
								<div class="col-md-2 md-pt-30 text-muted" style="font-size: 30px;"><i class="ion-ios-shuffle-strong"></i></div>

								<div class="col-md-5 md-pr-0 customer-select" style="<?php if ($proposals['relation_type'] == 'lead') { echo 'display:none'; }?>">
									<label for="customers"><?php echo lang('customers'); ?></label>
									<select required name="related_customer" class="form-control select2"><?php
										$pcustomer =  0 === (int) $proposals['type'] ? $proposals['customer'] : $proposals['individual'];
										if ( 'customer' === $proposals['relation_type'] ) {
			    							echo '<option value="'.$proposals['relation'].'">'.$pcustomer.'</option>';
			    						}
		                                foreach( $all_customers as $customers ) {
		                                    $selected = ( $customers[ 'id' ] === $related_customer ) ? ' selected="selected"' : null;

		                                    echo '<option value="' . $customers[ 'id' ] . '" ' . $selected . '>' . $customers[ 0 === (int) $customers[ 'type' ] ? 'companyname' : 'namesurname' ] . '</option>';
		                                }
		                                ?>
									</select>
								</div>

								<div class="col-md-5 md-pr-0 lead-select" style="<?php if ( 'customer' === $proposals['relation_type'] ) { echo 'display:none'; }?>">
									<label for="leads"><?php echo lang('leads'); ?></label>
									<select name="related_lead" class="form-control select2"><?php
										if ( 'lead' === $proposals['relation_type'] ) {
			                                echo '<option value="' . $proposals['relation'] . '">' . $proposals['leadname'] . '</option>';
			                            }
			                            foreach ($all_leads as $lead) {
			                                $selected = $lead[ 'id' ] === $related_lead ? ' selected="selected"' : null;
			                                echo '<option value="' . $lead[ 'id' ] . '" ' . $selected . '>' . $lead[ 'leadname' ] . '</option>';
			                            } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-12 md-m-0 md-p-0">
							<div class="form-group">
								<div class="col-md-6 md-pl-0">
									<label for="assigned">
										<?php echo lang('assigned'); ?>
									</label>
									<select required name="assigned" class="select2">
									<option value="<?php echo $assigned;?>"><?php echo $proposals['staffname'] ?></option>
										<?php
		                                foreach ($all_staff as $staff) {
		                                    $selected = $staff[ 'id' ] === $assigned ? ' selected="selected"' : null;
		                                    echo '<option value="' . $staff[ 'id' ] . '" ' . $selected . '>' . $staff[ 'staffname' ] . '</option>';
		                                }
		                                ?>
									</select>
								</div>
								<div class="col-md-6 md-pr-0">
									<label for="status"><?php echo lang('status'); ?></label>
									<select required name="statusid" class="form-control select2"><?php 
										if ( 1 === $proposals['statusid'] ) { echo '<option value="1">' . lang('draft') . '</option>'; }
		                                if ( 2 === $proposals['statusid'] ) { echo '<option value="2">' . lang('sent') . '</option>'; }
		                                if ( 3 === $proposals['statusid'] ) { echo '<option value="3">' . lang('open') . '</option>'; } 
		                                if ( 4 === $proposals['statusid'] ) { echo '<option value="4">' . lang('revised') . '</option>'; }
		                                if ( 5 === $proposals['statusid'] ) { echo '<option value="5">' . lang('declined') . '</option>'; }
		                                if ( 6 === $proposals['statusid'] ) { echo '<option value="6">' . lang('accepted') . '</option>'; }
										?>
										<option value="1"><?php echo lang('draft'); ?></option>
										<option value="2"><?php echo lang('sent'); ?></option>
										<option value="3"><?php echo lang('open'); ?></option>
										<option value="4"><?php echo lang('revised'); ?></option>
										<option value="5"><?php echo lang('declined'); ?></option>
										<option value="6"><?php echo lang('accepted'); ?></option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 md-pl-30">
						<div class="form-group">
							<label for="date">
								<?php echo lang('dateofissuance') ?>
							</label>
							<div data-min-view="2" data-date-format="dd.mm.yyyy" class="input-group date datetimepicker"> <span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
								<input placeholder="<?php echo date(" d.m.Y "); ?>" required type='input' name="date" value="<?php echo$date; ?>" class="form-control" id="date"/>
								<input type="hidden" name="datecreated" value="<?php echo date(" d.m.Y "); ?>">
							</div>
						</div>
						<div class="form-group has-warning">
							<label for="opentill">
								<?php echo lang('opentill'); ?>
							</label>
							<div data-min-view="2" data-date-format="dd.mm.yyyy" class="input-group date datetimepicker"> <span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
								<input placeholder="<?php echo date(" d.m.Y "); ?>" type='input' name="opentill" value="<?php echo $opentill; ?>" class="form-control" id="opentill"/>
							</div>
						</div>
						<div class="form-group pull-right">
							<div class="ciuis-body-checkbox has-primary">
							 	<input name="comment" id="comment" type="checkbox" <?php echo 1 === $proposals['comment'] ? 'checked' : ''; ?> value="1"> 
								<label for="comment"><?php echo lang('allowcomments');?></label>
							</div>
						</div>
					</div>
					<div class="col-md-12">
					<div class="form-group md-p-0">
						<textarea name="content" id="replyeditor" cols="30" rows="10"><?php echo $content; ?></textarea>
					</div>
					</div>
				</div>
				
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
							<?php foreach ( $proposalitems as $item ) { ?>
								<input hidden="" type="text" name="in[itemid][]" value="<?php echo $item['id']?>">
								<tr class="line select-properties-list">
									<td class="add-on-edit">
										<div class="add-on-edit">
											<input class="input-productcode" type="hidden" name="in[product_id][]" value="<?php echo $item['in[product_id]']; ?>">
											<input type="text" name="in[name][]" class="form-control input-product autocomplate-product ui-autocomplete-input" autocomplete="off" value="<?php echo $item['in[name]']; ?>">
											<small class="hide">
												<?php echo lang('new'); ?>
											</small>
										</div>
										<input type="hidden" name="in[code][]" class="form-control input-code">
										<div class="property" data-name="description" data-title="<?php echo lang('description')?>" data-status="<?php if ($item['in[description]'] == null) {
			                                        echo 'passive';
			                                    } else {
			                                        echo'active';
			                                    } ?>">
											<textarea type="text" name="in[description][]" class="form-control input-item-description" placeholder="<?php echo lang('description')?>"><?php echo $item['in[description]']; ?></textarea>
											<div class="property-delete"><a class="btn delete text-danger"><i class="icon icon-left mdi mdi mdi-delete"></i></a>
											</div>
										</div>
									</td>
									<td>
										<div class="input-group">
											<input type="text" name="in[amount][]" class="form-control input-amount filter-money" value="<?php echo $item['in[amount]']; ?>">
											<div class="input-group-addon text-muted">
												<a class="input-unit-editable" tabindex="-1">
													<?php echo $item['in[unit]']?>
												</a>
												<input type="hidden" name="in[unit][]" value="<?php echo $item['in[unit]']; ?>" class="input-unit input-xs">
											</div>
										</div>
									</td>
									<td>
										<div class="input-group-icon">
											<input type="text" name="in[price][]" class="form-control input-price filter-money" value="<?php echo $item['in[price]']; ?>">
											<input type="hidden" name="in[pricepost][]" class="price-post" value="<?php echo $item['in[price]']; ?>">
											<input type="hidden" name="in[price_discounted][]" class="form-control input-price-discounted" value="<?php echo $item['in[price_discounted]']; ?>">
										</div>
										<div class="property" data-name="indirim" data-title="<?php echo lang('discount'); ?>" data-status="<?php if ($item['in[discount_rate]'] == 0) {
			                                        echo 'passive';
			                                    } else {
			                                        echo'active';
			                                    } ?>">
											<div class="input-group">
												<input type="text" name="in[discount_rate][]" class="form-control input-discount-rate delete-on-delete" value="<?php echo $item['in[discount_rate]']; ?>">
												<input name="in[discount_type][]" type="hidden" value="<?php echo $item['in[discount_type]']; ?>" class="input-discount-type">
												<input type="hidden" name="in[discount_rate_status][]" class="form-control input-status" value="<?php echo $item['in[discount_rate_status]']; ?>">
												<div class="input-group-addon text-muted">%</div>
											</div>
										</div>
									</td>
									<td>
										<div class="input-group">
											<input type="text" name="in[vat][]" class="form-control input-vat input-vat-vat filter-number" value="<?php echo $item['in[vat]']; ?>">
											<input type="hidden" name="in[total_vat][]" class="input-vat-vat-total" value="<?php echo $item['in[total_vat]']; ?>">
											<div class="input-group-addon text-muted">
												<?php echo lang('vat'); ?> %</div>
										</div>
									</td>
									<td>
										<div class="input-group">
											<input type="text" class="form-control input-total filter-money on-tab-add-line" value="<?php echo $item['in[total]']; ?>">
											<input type="hidden" name="in[total][]" value="<?php echo $item['in[total]']; ?>" class="input-total-real">
											<input name="in[currency][]" type="hidden" value="USD" class="input-currency">
											<input name="rate_in[currency][]" type="hidden" value="1" class="input-rate">
											<div class="input-group-addon text-muted">
												<?php echo currency; ?>
											</div>
										</div>
									</td>
									<td style="vertical-align: middle"><div class="select-properties"></div></td>
									<td style="vertical-align: middle"><a class="btn btn-default btn-sm delete-line"><i class="icon icon-left mdi mdi mdi-delete text-danger"></i></a></td>
								</tr><?php
							} ?>
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
												<th width="280">
													<?php echo lang('subtotal'); ?>
												</th>
												<th class="text-right" width="170">
													<div class="sub-total"><span class="money-format"><span class="money-main">0</span><span class="money-float">,00</span></span> <i class="fa fa-try"></i>
													</div>
												</th>
												<th width="50">
													<div class="select-properties dropdown" style="visibility: visible;"><a class="dropdown-toggle btn btn-sm btn-default " data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> <span class="vals"><i class="icon ion-plus"></i></span> </a>
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
											<tr class="no-border line-discount" style="display: none;">
												<th width="200">
													<?php echo lang('linediscount');?>
												</th>
												<th class="text-right"><span class="money-format"><span class="money-main">0</span><span class="money-float">,00</span></span> <i class="fa fa-try"></i>
												</th>
												<th></th>
											</tr>
											<tr class="no-border gross-total" style="display: none;">
												<th width="200">
													<?php echo lang('grosstotal')?>
												</th>
												<th class="text-right"><span class="money-format"><span class="money-main">0</span><span class="money-float">,00</span></span> <i class="fa fa-try"></i>
												</th>
												<th></th>
											</tr>
										</tbody>
										<tbody>
											<tr>
												<th>
													<?php echo lang('tax')?>
												</th>
												<th class="text-right"><span class="vat-total money-format"><span class="money-main">0</span><span class="money-float">,00</span></span> <i class="fa fa-try"></i>
												</th>
												<th></th>
											</tr>
										</tbody>
										<tbody class="grandtotal">
											<tr class="money-bold">
												<th>
													<?php echo lang('grandtotal'); ?>
												</th>
												<th class="text-right"><span class="grant-total money-format"><span class="money-main">0</span><span class="money-float">,00</span></span> <i class="fa fa-try"></i>
												</th>
												<th></th>
											</tr>
										</tbody>
									</table>
									<input type="hidden" class="input-sub-total" name="total_sub" value="0">
									<input type="hidden" class="input-line-discount" name="total_discount" value="0">
									<input type="hidden" class="input-vat-total" name="total_vat" value="0">
									<input type="hidden" class="input-grant-total" name="total" value="0">
									<input type="hidden" name="addedfrom" value="<?php echo $this->session->userdata('logged_in_staff_id'); ?>">
								</td>
							</tr>
						</tfoot>
					</table>
				</div>

			</div>
		
		</form>

	</div>

	<?php include_once(dirname(dirname(__FILE__)) . '/inc/sidebar.php');?>
	<?php include_once(dirname(dirname(__FILE__)) . '/inc/footer_invoice.php');?>

	<script src="<?php echo base_url('assets/crm/lib/jquery-ui/jquery-ui.js')?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('assets/crm/lib/jquery-ui/jquery-ui.css')?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/crm/lib/summernote/summernote.css"/>
	<script src="<?php echo base_url(); ?>assets/crm/lib/summernote/summernote.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/crm/js/app-form-wysiwyg.js" type="text/javascript"></script>
	<script type="text/javascript">
		$( document ).ready( function () {
			App.init();
			App.textEditors();
		} );

		$( function () {
			"use strict";
			var id;
			id = new Invoice_Create( {
				currency: 'USD',
				edit: 1,
				type: 'sale',
				payment_count: 1,
				copy: 0
			} );

		} );

		$( ".save-invoice" ).click( function () {
			var invoicetotal = $( '.input-grant-total' ).val();
			if ( invoicetotal > 0 ) {
				$( ".invoice-form" ).submit();
			} else {
				$.gritter.add( {
					title: '<b><?php echo lang('
					notification ')?></b>',
					text: '<?php echo lang('pleaseenteritem ')?>',
					position: 'bottom',
					class_name: 'color danger',
				} );
			}
		} );

		$( '#relation_type' ).change( function () {
			if ( $( this ).val() == 'customer' ) { // or this.value == 'volvo'
				$( '.customer-select' ).show();
				$( '.lead-select' ).hide();
				$( '.relationer' ).val( $( this ).val() );
			}
		} );
		$( '#relation_type' ).change( function () {
			if ( $( this ).val() == 'lead' ) { // or this.value == 'volvo'
				$( '.customer-select' ).hide();
				$( '.lead-select' ).show();
				$( '.relationer' ).val( $( this ).val() );
			}
		} );
	</script>