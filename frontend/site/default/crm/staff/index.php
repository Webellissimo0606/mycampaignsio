<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
<div class="ciuis-body-content">

	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="row">
          <?php foreach($staff as $t){ ?>
          <div class="col-md-4">
            <div class="user-display">
              <div class="user-display-bg"><img src="<?php echo base_url(); ?>assets/crm/img/staffmember_bg.png" alt="Avatar"></div>
              <div class="user-display-bottom">
                <div class="user-display-avatar"><img src="<?php echo base_url(); ?>uploads/staffavatars/<?php if($t['staffavatar'] != NULL){echo $t['staffavatar'];}else {echo 'n-img.jpg';}; ?>" alt="Avatar"></div>
                <div class="user-display-info">
                  <div class="name"><a href="<?php echo site_url('staff/staffmember/'.$t['id']); ?>"> <?php echo $t['staffname']; ?></a></div>
                  <div class="nick"><span class="mdi mdi-accounts-list-alt"></span> <?php echo $t['department']; ?></div>
                </div>
                <div class="row user-display-details">
                  <div class="col-xs-12">
                    <div class="btn-group btn-space pull-right"> <a href="<?php echo site_url('staff/edit/'.$t['id']); ?>" type="button" class="btn btn-default"><i class="icon mdi mdi-edit"></i></a> <button data-target="#remove<?php echo $t['id']?>" data-toggle="modal" type="button" class="btn btn-default"><i class="icon mdi mdi-delete"></i></button> </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="remove<?php echo $t['id']?>" tabindex="-1" role="dialog" class="modal fade">
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
								<?php echo lang('staffattentiondetail'); ?>
							</p>
							<div class="xs-mt-50">
								<a type="button" data-dismiss="modal" class="btn btn-space btn-default">
									<?php echo lang('cancel'); ?>
								</a>
								<a href="<?php echo base_url('staff/remove/'.$t['id'].'')?>" type="button" class="btn btn-space btn-danger">
									<?php echo lang('delete'); ?>
								</a>
							</div>
						</div>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
          <?php } ?>
          
        </div>		
	</div>
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-3 md-pl-0">
				<div class="panel panel-default panel-table borderten">
					<div class="panel-heading">
						 <div class="btn-group col-md-12 md-pr-0 md-pl-0 md-pb-20">
              	<a href="<?php echo base_url('staff/add')?>" class="btn  btn-default btn-big col-md-6"><i class="icon ion-person-add"></i><?php echo lang('addstaff')?> </a>
              <button data-target="#adddepartment" data-toggle="modal" class="btn  btn-default btn-big col-md-6"><i class="icon ion-android-add-circle"></i> <?php echo lang('adddepartment')?> </button>
              </div>
					</div>
					<div class="panel-body">
					  <table id="table" class="table table-striped table-hover table-fw-widget">
						<thead>
							<tr class="text-muted">
								<th><?php echo lang('departments')?></th>
								<th class="text-right"></th>
							</tr>
						</thead>
							<?php foreach($departments as $department){ ?>
							<tr>
								<td><b class="text-muted"><?php echo $department['name']; ?></b></td>
								<td class="pull-right md-pt-5">
									<button type="button" data-target="#updatedepartment<?php echo $department['id']; ?>" data-toggle="modal" data-placement="left" title="" class="btn btn-default" data-original-title="Update Expense Category"><i class="ion-ios-compose"></i></button>
									<a href="<?php echo site_url('staff/removedepartment/'.$department['id']); ?>"><button class="btn btn-default"><i class="ion-ios-trash"></i></button></a>
								</td>
							</tr>
							<div id="updatedepartment<?php echo $department['id']; ?>" tabindex="-1" role="content" class="modal fade colored-header colored-header-dark">
								 <div class="modal-dialog">
								 <?php echo form_open('staff/updatedepartment/'.$department['id']); ?>
								 <div class="modal-content">
								  <div class="modal-header">
									<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
										<h3 class="modal-title"><?php echo lang('updatedepartment'); ?></h3>
										</div>
										<div class="modal-body">
										<div class="form-group">
											<label for="name"><?php echo lang('name'); ?></label>
											<div class="input-group"><span class="input-group-addon"><i class="ion-navicon"></i></span>
												<input type="text" name="name" value="<?php echo ($this->input->post('name') ? $this->input->post('name') : $department['name']); ?>" class="form-control" id="name" placeholder="name"/>
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
							<?php } ?>
					  </table>
					  <br>
					</div>
      
       	
        		</div>
			</div>
</div>

<div id="adddepartment" tabindex="-1" role="content" class="modal fade colored-header colored-header-dark">
			 <div class="modal-dialog">
			 <?php echo form_open('staff/adddepartment'); ?>
			 <div class="modal-content">
			  <div class="modal-header">
				<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
					<h3 class="modal-title"><?php echo lang('adddepartment')?></h3>
					</div>
					<div class="modal-body">
					<div class="form-group">
						<label for="name"><?php echo lang('name'); ?></label>
						<div class="input-group"><span class="input-group-addon"><i class="ion-navicon"></i></span>
							<input type="text" name="name" value="<?php echo $this->input->post('name'); ?>" class="form-control" id="name" placeholder="name"/>
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

<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_table.php' ;?>





