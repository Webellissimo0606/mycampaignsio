<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="row">
			<div class="col-md-12">
				<div style="border-radius: 10px;" class="panel panel-default panel-table">
					<div class="panel-heading">
						<B><?php echo lang('products'); ?></B>
						<div class="pull-right" id="buttons"></div>
						<button type="button" data-target="#add" data-toggle="modal" class="btn btn-space btn-default md-trigger btn-lg pull-right" data-original-title="Add Product"><i class="ion-plus-round"></i> <?php echo lang('addnewproduct'); ?></button>
						<span class="panel-subtitle">
							<?php echo lang('productsdescription'); ?>
						</span>
					</div>
					<div class="panel-body">
						<table id="table2" class="table table-striped table-hover table-fw-widget">
							<thead>
								<tr>
									<th><?php echo lang('id')?></th>
									<th><?php echo lang('productname')?></th>
									<th class="hidden-xs"><?php echo lang('purchaseprice')?></th>
									<th><?php echo lang('salesprice')?></th>
									<th class="text-right"><?php echo lang('productactions')?></th>
								</tr>
							</thead>
							<?php foreach($products as $u){ ?>
							<tr class="ciuis-254325232344" onclick="window.location='<?php echo base_url('products/product/'.$u['id'].'')?>'">
								<td><?php echo $u['id']; ?></td>
								<td><?php echo $u['productname']; ?></td>
								<td class="hidden-xs"><b class="money-area"><?php echo $u['purchase_price'] ?></b></td>
								<td><b class="money-area"><?php echo $u['sale_price']; ?></b></td>
								<td class="text-right">
								<div class="btn-group">
								<a href="<?php echo site_url('products/remove/'.$u['id']); ?>" class="btn btn-default"><i class="ion-trash-b"></i></a>
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

	<div id="add" tabindex="-1" role="content" class="modal fade colored-header colored-header-dark">
		<div class="modal-dialog">
			<?php echo form_open('products/add'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
					<h3 class="modal-title"><?php echo lang('addproduct')?></h3>
				</div>
				<div class="col-md-6 md-pr-0">
					<div class="modal-body md-pr-5">
						<div class="form-group">
							<label for="productname"><?php echo lang('productname'); ?></label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-ios-pricetags"></i></span>
								<input type="text" name="productname" value="<?php echo $this->input->post('productname'); ?>" class="form-control" id="productname" placeholder="<?php echo lang('productname')?>"/>
							</div>
						</div>
						<div class="form-group">
							<label for="sale_price"><?php echo lang('purchaseprice'); ?></label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-social-usd"></i></span>
								<input type="text" name="purchase_price" value="<?php echo $this->input->post('purchase_price'); ?>" class="form-control" id="purchase_price" placeholder="0,00"/>
							</div>
						</div>
						<div class="form-group">
							<label for="sale_price"><?php echo lang('salesprice'); ?></label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-social-usd"></i></span>
								<input type="text" name="sale_price" value="<?php echo $this->input->post('sale_price'); ?>" class="form-control" id="sale_price" placeholder="0,00"/>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 md-pl-0">
					<div class="modal-body md-pl-5">
						<div class="form-group">
							<label for="code"><?php echo lang('productcode'); ?></label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-ios-barcode"></i></span>
								<input type="text" name="code" value="<?php echo $this->input->post('code'); ?>" class="form-control" id="code" placeholder="<?php echo lang('productcode')?>"/>
							</div>
						</div>
						<div class="form-group">
							<label for="vat"><?php echo lang('vat'); ?></label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-ios-medical"></i></span>
								<input type="text" name="vat" value="<?php echo $this->input->post('vat'); ?>" class="form-control" id="vat" placeholder="Vat Rate %"/>
							</div>
						</div>
						<div class="form-group">
							<label for="stock"><?php echo lang('instock'); ?></label>
							<div class="input-group"><span class="input-group-addon"><i class="ion-cube"></i></span>
								<input type="text" name="stock" value="<?php echo $this->input->post('stock'); ?>" class="form-control" id="stock" placeholder="<?php echo lang('instock')?>"/>
							</div>
						</div>
					</div>
				</div>
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
					<div class="col-md-6 pull-left text-left md-pl-0">
	                    <div class="ciuis-body-checkbox has-success">
	                      <input name="stocktracking" id="yes" type="checkbox" value="1">
	                      <label for="yes"><?php echo lang('stocktracking')?></label>
	                    </div>
	                  </div>
						<div class="btn-group">
							<button type="button" data-dismiss="modal" class="btn btn-default modal-close"><?php echo lang('cancel'); ?></button>
						<button type="submit" class="btn btn-default"><?php echo lang('save'); ?></button>
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
	
<?php include_once dirname(dirname(__FILE__)) . '/inc/sidebar.php'; ?>
<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_table.php'; ?>