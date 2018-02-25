<?php include_once(APPPATH . 'views/inc/header.php'); ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-12">
 	<div class="col-md-12 borderten md-p-0">
  	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-3 md-pl-0 lead-left-bar">
		<div class="panel panel-default panel-table borderten lead-manager-head">
			<div class="panel-heading">
				<div class="btn-group col-md-12 md-pr-0 md-pl-0 md-pb-10">
					<a href="<?php echo base_url('leads/add')?>" class="btn  btn-default btn-big col-md-4"><i class="icon ion-ios-personadd-outline"></i><?php echo lang('lead')?> </a>
					<button data-target="#add_status" data-toggle="modal" class="btn  btn-default btn-big col-md-4"><i class="icon ion-ios-plus-outline"></i> <?php echo lang('status')?> </button>
					<button data-target="#add_source" data-toggle="modal" class="btn  btn-default btn-big col-md-4"><i class="icon ion-ios-plus-outline"></i> <?php echo lang('source')?> </button>
				</div>
				<a href="<?php echo base_url('leads/index')?>" class="btn btn-lg btn-default show-kanban col-md-12"><?php echo lang('showtable');?></a>
				<hr>
			</div>
			<div class="panel-body lead-manager">
				<table id="table" class="table table-striped table-hover table-fw-widget">
					<thead>
						<tr class="text-muted">
							<th>
								<?php echo lang('leadssources')?>
							</th>
							<th class="text-right"></th>
						</tr>
					</thead>
					<?php foreach($leadssources as $source){ ?>
					<tr>
						<td>
							<b class="text-muted">
								<?php echo $source['name']; ?>
							</b>
						</td>
						<td class="pull-right" style="padding-top:7px;">
							<button type="button" data-target="#update_source<?php echo $source['id']; ?>" data-toggle="modal" data-placement="left" title="" class="btn btn-default" data-original-title="Update Source"><i class="ion-ios-compose"></i></button>
							<a href="<?php echo site_url('leads/removesource/'.$source['id']); ?>"><button class="btn btn-default"><i class="ion-ios-trash"></i></button></a>
						</td>
					</tr>
					<div id="update_source<?php echo $source['id']; ?>" tabindex="-1" role="content" class="modal fade colored-header colored-header-dark">
						<div class="modal-dialog">
							<?php echo form_open('leads/update_source/'.$source['id']); ?>
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
									<h3 class="modal-title">
										<?php echo lang('updatesource'); ?>
									</h3>
								</div>
								<div class="modal-body">
									<div class="form-group">
										<label for="name">
											<?php echo lang('name'); ?>
										</label>
										<div class="input-group"><span class="input-group-addon"><i class="ion-navicon"></i></span>
											<input type="text" name="name" value="<?php echo ($this->input->post('name') ? $this->input->post('name') : $source['name']); ?>" class="form-control" id="name" placeholder="name"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" data-dismiss="modal" class="btn btn-default modal-close">
										<?php echo lang('cancel'); ?>
									</button>
									<button type="submit" class="btn btn-default">
										<?php echo lang('save'); ?>
									</button>
								</div>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
					<?php } ?>
				</table>
				<table id="table" class="table table-striped table-hover table-fw-widget">
					<thead>
						<tr class="text-muted">
							<th>
								<?php echo lang('leadsstatuses')?>
							</th>
							<th class="text-right"></th>
						</tr>
					</thead>
					<?php foreach($leadsstatuses as $status){ ?>
					<tr>
						<td>
							<b class="text-muted">
								<?php echo $status['name']; ?>
							</b>
						</td>
						<td class="pull-right" style="padding-top:7px;">
							<button type="button" data-target="#update_status<?php echo $status['id']; ?>" data-toggle="modal" data-placement="left" title="" class="btn btn-default" data-original-title="Update Expense Category"><i class="ion-ios-compose"></i></button>
							<a href="<?php echo site_url('leads/removestatus/'.$status['id']); ?>"><button class="btn btn-default"><i class="ion-ios-trash"></i></button></a>
						</td>
					</tr>
					<div id="update_status<?php echo $status['id']; ?>" tabindex="-1" role="content" class="modal fade colored-header colored-header-dark">
						<div class="modal-dialog">
							<?php echo form_open('leads/update_status/'.$status['id']); ?>
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
									<h3 class="modal-title">
										<?php echo lang('updatestatus'); ?>
									</h3>
								</div>
								<div class="modal-body">
									<div class="form-group">
										<label for="name">
											<?php echo lang('name'); ?>
										</label>
										<div class="input-group"><span class="input-group-addon"><i class="ion-navicon"></i></span>
											<input type="text" name="name" value="<?php echo ($this->input->post('name') ? $this->input->post('name') : $status['name']); ?>" class="form-control" id="name" placeholder="name"/>
										</div>
									</div>
									<div class="form-group">
										<label for="color">
											<?php echo lang('color'); ?>
										</label>
										<div class="input-group"><span class="input-group-addon"><i class="ion-navicon"></i></span>
											<input type="text" name="color" value="<?php echo ($this->input->post('color') ? $this->input->post('color') : $status['color']); ?>" class="form-control" id="color" placeholder="color"/>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" data-dismiss="modal" class="btn btn-default modal-close">
										<?php echo lang('cancel'); ?>
									</button>
									<button type="submit" class="btn btn-default">
										<?php echo lang('save'); ?>
									</button>
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
  	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9 md-p-0 lead-kanban">
  	<div class="lead-scroll-horizontal">
	  <?php foreach($leadsstatuses as $status){?>
	  <?php $leads = $this->db->select( '*,leadsstatus.name as statusname,staff.staffname as leadassigned,staff.staffavatar as assignedavatar,leadssources.name as sourcename,leads.name as leadname,leads.id as id' )->order_by('leads.id', 'asc')->join( 'leadsstatus', 'leads.status = leadsstatus.id', 'left' )->join( 'leadssources', 'leads.source = leadssources.id', 'left' )->join( 'staff', 'leads.assigned = staff.id', 'left' )->where( 'public != "0" AND  staffid = '.$this->session->userdata( 'logged_in_staff_id' ).' AND assigned = '. $this->session->userdata( 'logged_in_staff_id' ) .' OR status = "'.$status['id'].'"' )->get( 'leads')->result_array(); ?>
		<div id="leadlist" class="md-p-0" style="border-right: 1px solid #ebebeb;float: left;width: 300px">
		  <div class="lead-status-header" style="background: <?php echo $status['color']?>"><h4 class="text-bold leads-status-name"><?php echo $status['name'] ?>&nbsp;&nbsp;<button class="pull-right btn btn-default btn-xs lead-collapse ion-arrow-left-b"></button><span class="label label-default"></span></h4></div>
		  <ul class="leads" data-status="<?php echo $status['id']?>">
		  <?php foreach($leads as $lead){?>
			<li id="<?php echo $lead['id']?>" class="leadcard">
			  <dl  data-lead="<?php echo $lead['id']?>">
				<dt><?php echo $lead['leadname'] ?> <?php echo $lead['id']?></dt>
				<dd><strong>Source: <?php echo $lead['sourcename'] ?></strong></dd>
				<dd class="date">Added: <?php echo _adate($lead['dateadded']) ?></dd>
			  </dl>
			</li>
		  <?php }?>	
		  </ul>
		</div>
	  <?php }?>	
	  </div>
	</div>
	</div>
</div>
</div>
<div id="add_status" tabindex="-1" role="content" class="modal fade colored-header colored-header-dark">
	<div class="modal-dialog">
		<?php echo form_open('leads/add_status'); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
				<h3 class="modal-title">
					<?php echo lang('addstatus')?>
				</h3>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="name">
						<?php echo lang('name'); ?>
					</label>
					<div class="input-group"><span class="input-group-addon"><i class="ion-navicon"></i></span>
						<input type="text" name="name" value="<?php echo $this->input->post('name'); ?>" class="form-control" id="name" placeholder="name"/>
					</div>
				</div>
				<div class="form-group">
					<label for="name">
						<?php echo lang('color'); ?>
					</label>
					<div class="input-group"><span class="input-group-addon"><i class="ion-navicon"></i></span>
						<input type="text" name="color" value="<?php echo $this->input->post('color'); ?>" class="form-control" id="color" placeholder="color"/>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default modal-close">
					<?php echo lang('cancel'); ?>
				</button>
				<button type="submit" class="btn btn-default">
					<?php echo lang('save'); ?>
				</button>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
<div id="add_source" tabindex="-1" role="content" class="modal fade colored-header colored-header-dark">
	<div class="modal-dialog">
		<?php echo form_open('leads/add_source'); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
				<h3 class="modal-title">
					<?php echo lang('addsource')?>
				</h3>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="name">
						<?php echo lang('name'); ?>
					</label>
					<div class="input-group"><span class="input-group-addon"><i class="ion-navicon"></i></span>
						<input type="text" name="name" value="<?php echo $this->input->post('name'); ?>" class="form-control" id="name" placeholder="name"/>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default modal-close">
					<?php echo lang('cancel'); ?>
				</button>
				<button type="submit" class="btn btn-default">
					<?php echo lang('save'); ?>
				</button>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
<?php include_once( APPPATH . 'views/inc/footer.php' );?>
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
<script>
	var h = $( document ).innerHeight();
	function SortTable() {
		$( '.leads' ).each( function ( i ) {
			$( ".TableNum" + i ).sortable( {
				connectWith: ".leads",
				opacity: 0.8,
				update: function () {
					var lead = $('.leadis'+i).children().data( 'lead' );
					$.ajax( {
						type: "POST",
						url: "<?php echo base_url('leads/movelead')?>",
						data: {
							leadid: lead,
							statusid: $( ".TableNum" + i ).data( 'status' ),
						},
						dataType: "text",
						cache: false,
						success: function ( data ) {
							alert( lead );
						}
					} );
					CountingLeads();
				}
			} );
			$( ".TableNum" + i ).disableSelection();
			$( ".collapse" + i ).click( function () {
			$( '.statusis' + i ).hide();
			} );
		} );
	}
	function CountingLeads() {
		$( '.leads' ).each( function () {
			var CheckNum = $( this ).find( 'li' ).size();
			$( this ).prev( 'div' ).find( 'span' ).html( CheckNum + "&nbsp;" );
		} );
	}
	function SetCard() {
		$( '.leads' ).each( function ( i ) {
			$( this ).addClass( 'TableNum' + i );
			$( this ).addClass( 'statusis' + i );
			$( this ).find( 'li' ).addClass( "ui-state-default" );
			$( this ).height( h );
		} );
		$( '.leadcard' ).each( function ( i ) {
			$( this ).addClass( 'leadis' + i );
		} );
		$( '.lead-collapse' ).each( function ( i ) {
			$( this ).addClass( 'collapse' + i );
		} );
	}
	CountingLeads();
	SetCard();
	SortTable();
</script>