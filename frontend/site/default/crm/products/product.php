<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="col-sm-12 md-pl-0 md-pr-0 xs-pl-0 xs-pr-0">
			<div class="panel panel-white borderten" style="padding:20px">
				<div class="panel-heading-title">
					<div class="col-md-8 md-pl-0 md-pb-20">
						<h4><b><?php echo $product['productname']?></b></h4>
						<span class="text-muted">
							<?php echo $product['description']?>
						</span>
					</div>
					<div class="col-md-4">
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-default btn-lg dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<?php echo lang('action')?> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li>
									<a data-toggle="modal" data-target="#edit">
										<?php echo lang('edit')?>
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a data-toggle="modal" data-target="#remove">
										<?php echo lang('delete')?>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<table class="table lg-p-20 md-p-20">
							<tbody>
								<tr class="line-small no-border">
									<td class="small-title" width="20%">
										<?php echo lang('purchaseprice')?>
									</td>
									<td class="text-right">
										<span style="font-size:18px;">
                                    <span class="money-format"><span class="money-area"><?php echo $product['purchase_price']?></span></span>
									</td>
								</tr>
								<tr class="line-small no-border">
									<td class="small-title">
										<?php echo lang('salesprice')?>
									</td>
									<td class="text-right">
										<span style="font-size:18px;">
                                    <span class="money-format"><span class="money-area"><?php echo $product['sale_price']?></span></span>
									</td>
								</tr>
								<tr class="line-small no-border">
									<td class="small-title">
										<?php echo lang('vat')?>
									</td>
									<td class="text-right">
										<span style="font-size:18px;">
                                    <span class="money-format"><span class="money-main"><?php echo $product['vat']?>  %</span></span>
									</td>
								</tr>
								<tr class="line-small no-border">
									<td class="small-title"><?php echo lang('instock')?></td>
									<td class="text-right">
									<span class="label label-default"><?php echo $product['stock']?></span>
									<i class="ion-arrow-right-c"></i>
									<span class="label label-default"><?php echo lang('remainingstock')?> : <b><?php echo $remaining = $product['stock'] - $tsp ?></b></span>
									</td>
								</tr>
								<tr>
									<td><b><?php echo lang('productcode')?></b></td>
									<td class="text-right"><span class="text-success"><b><?php echo $product['code']?></b></span></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">

						<div class="col-md-6">
							<div class="ciuis-product-summary">
								<h5 class="text-bold text-uppercase"><?php echo lang('totalsales')?></h5>
								<small><?php echo lang('totalsalesproductsub') ?></small>
								<h1 class="txt-scale-xs no-margin-top xs-28px figures"><span class=""><?php echo $tsp ?></span></h1>							
							</div>
						</div>
						<div class="col-md-6">
							<div class="ciuis-product-summary">
								<h5 class="text-bold text-uppercase text-success"><?php echo lang('netearnings')?></h5>
								<small><?php echo lang('netearningssub')?></small>
								<?php
	
								$pricepurchase = $tsp *  $product['purchase_price'];
								$pricesales = $tsp * $product['sale_price'];

								$netearnings = $pricesales - $pricepurchase;

								?>
								<h1 class="txt-scale-xs no-margin-top xs-28px figures"><span class="money-area"><?php echo $netearnings ?></span></h1>						
								<p class="secondary-text">
									<strong class="text-muted"><?php echo lang('productnetearnings') ?></strong>
									
								</p>
								<br>
							</div>
						</div>
					</div>
				</div>
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
							<?php echo lang('productattentiondetail'); ?>
						</p>
						<div class="xs-mt-50">
							<a type="button" data-dismiss="modal" class="btn btn-space btn-default">
								<?php echo lang('cancel'); ?>
							</a>
							<a href="<?php echo base_url('products/remove/'.$product['id'].'')?>" type="button" class="btn btn-space btn-danger">
								<?php echo lang('delete'); ?>
							</a>
						</div>
					</div>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
	<div id="edit" tabindex="-1" role="content" class="modal fade colored-header colored-header-warning">
		<div class="modal-dialog">
			<?php echo form_open('products/edit/'.$product['id'],array("class"=>"form-vertical")); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
					<h3 class="modal-title">
						<?php echo lang('updateproduct')?>
					</h3>
				</div>
				<div class="col-md-6 md-pr-0">
					<div class="modal-body md-pr-5">
						<div class="form-group">
							<label for="productname">
								<?php echo lang('productname'); ?>
							</label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-ios-pricetags"></i></span>
								<input type="text" name="productname" value="<?php echo ($this->input->post('productname') ? $this->input->post('productname') : $product['productname']); ?>" class="form-control" id="productname" placeholder="productname"/>
							</div>
						</div>
						<div class="form-group">
							<label for="sale_price">
								<?php echo lang('purchaseprice'); ?>
							</label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-social-usd"></i></span>
								<input type="text" name="purchase_price" value="<?php echo ($this->input->post('purchase_price') ? $this->input->post('purchase_price') : $product['purchase_price']); ?>" class="form-control" id="purchase_price" placeholder="purchase_price"/>
							</div>
						</div>
						<div class="form-group">
							<label for="sale_price">
								<?php echo lang('salesprice'); ?>
							</label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-social-usd"></i></span>
								<input type="text" name="sale_price" value="<?php echo ($this->input->post('sale_price') ? $this->input->post('sale_price') : $product['sale_price']); ?>" class="form-control" id="sale_price" placeholder="sale_price"/>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 md-pl-0">
					<div class="modal-body md-pl-5">
						<div class="form-group">
							<label for="code">
								<?php echo lang('productcode'); ?>
							</label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-ios-barcode"></i></span>
								<input type="text" name="code" value="<?php echo ($this->input->post('code') ? $this->input->post('code') : $product['code']); ?>" class="form-control" id="code" placeholder="Product Code"/>
							</div>
						</div>
						<div class="form-group">
							<label for="vat">
								<?php echo lang('vat'); ?>
							</label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-ios-medical"></i></span>
								<input type="text" name="vat" value="<?php echo ($this->input->post('vat') ? $this->input->post('vat') : $product['vat']); ?>" class="form-control" id="vat" placeholder="Vat Rate %"/>
							</div>
						</div>
						<div class="form-group">
							<label for="stock">
								<?php echo lang('instock'); ?>
							</label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-cube"></i></span>
								<input type="text" name="stock" value="<?php echo ($this->input->post('stock') ? $this->input->post('stock') : $product['stock']); ?>" class="form-control" id="stock" placeholder="In Stock"/>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 md-pt-0">
					<div class="modal-body md-pt-0">
						<div class="form-group">
							<label for="description">
								<?php echo lang('description'); ?>
							</label>
							<div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-ios-compose-outline"></i></span>
								<textarea name="description" class="form-control" id="description" placeholder="Description"><?php echo ($this->input->post('description') ? $this->input->post('description') : $product['description']); ?></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="col-md-6 pull-left text-left md-pl-0">
							<div class="ciuis-body-checkbox has-success">
								<input name="stocktracking" id="yes" type="checkbox" value="1">
								<label for="yes">
									<?php echo lang('stocktracking')?>
								</label>
							</div>
						</div>
						<div class="btn-group">
							<button type="button" data-dismiss="modal" class="btn btn-default modal-close">
								<?php echo lang('cancel'); ?>
							</button>
							<button type="submit" class="btn btn-default">
								<?php echo lang('save'); ?>
							</button>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
	<?php include_once dirname(dirname(__FILE__)) . '/inc/sidebar.php'; ?>
	<?php include_once dirname(dirname(__FILE__)) . '/inc/footer.php'; ?>