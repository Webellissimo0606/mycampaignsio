<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'); ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="ticket-container tab-container">
			<div class="active-view">
				<div class="list-view hidden-xs">
					<div class="list-view-top">
						<div style="width: 100%" class="btn-group btn-xl md-p-0">
							<button style="width: 100%" type="button" data-toggle="dropdown" class="btn btn-default btn-xl dropdown-toggle"><i class="ion-android-funnel pull-left"></i> <?php echo $ticketstatustitle ?></button>
							<ul style="width: 100%" role="menu" class="dropdown-menu">
								<li><a href="<?php echo base_url('tickets')?>"><b><?php echo lang('alltickets')?></b></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo base_url('tickets/open')?>"><?php echo lang('open')?></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo base_url('tickets/inprogress')?>"><?php echo lang('inprogress')?></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo base_url('tickets/answered')?>"><?php echo lang('answered')?></a></li>
								<li class="divider"></li>
								<li><a href="<?php echo base_url('tickets/closed')?>"><?php echo lang('closed')?></a></li>
							</ul>
						</div>
					</div>
					<ul class="list-view-list full-scroll nav tickets-tab">
						<?php foreach($dtickets as $tic){ ?>
						<li class="<?php if($tic['id'] === $ticket['id']){echo 'active';}?>">
							<a href="<?php echo base_url('tickets/ticket/'.$tic['id'].'');?>">
								<?php 
								if ($tic['priority']== 0){ echo'<span class="ticket-priority label label-primary">'.lang('low').'</span>';} 
								if ($tic['priority']== 1){ echo'<span class="ticket-priority label label-warning">'.lang('medium').'</span>';}
								if ($tic['priority']== 2){ echo'<span class="ticket-priority label label-danger">'.lang('high').'</span>';}
								?>
								<h3><?php echo lang('ticket'),'-',$tic['id']; ?></h3>
								<h4><?php echo $tic['subject']; ?></h4>
								<p><?php echo $tic['message']; ?></p>
							</a>
						</li>
						<?php } ?>
					</ul>
				</div>
				<div class="ciuis-ticket">
					<div class="ciuis-ticket-top hidden-xs">
						<div class="ciuis-ticket-stats">
							<div class="row" style="padding: 0px 20px 0px 20px;">
								<ul class="ciuis-ticket-stats grid">
									<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-3">
										<span class="ciuis-is-x-status">
											<?php echo lang('open')?>
										</span>
										<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $ysy ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $ysy ?> </span>
											<span class="ciuis-is-x-completes">
												<?php $this->db->where('statusid',1);$this->db->from('tickets'); echo $this->db->count_all_results();?> /
												<?php $this->db->from('tickets'); echo $this->db->count_all_results();?> </span>
											</span>
											</span>
										</div>
									</li>
									<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-3">
										<span class="ciuis-is-x-status">
											<?php echo lang('inprogress')?>
										</span>
										<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $bsy ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $bsy ?> </span>
											<span class="ciuis-is-x-completes">
												<?php $this->db->where('statusid',2);$this->db->from('tickets'); echo $this->db->count_all_results();?> /
												<?php $this->db->from('tickets'); echo $this->db->count_all_results();?> </span>
											</span>
											</span>
										</div>
									</li>
									<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-3">
										<span class="ciuis-is-x-status">
											<?php echo lang('answered')?>
										</span>
										<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $twy ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $twy ?> </span>
											<span class="ciuis-is-x-completes">
												<?php $this->db->where('statusid',3);$this->db->from('tickets'); echo $this->db->count_all_results();?> /
												<?php $this->db->from('tickets'); echo $this->db->count_all_results();?> </span>
											</span>
											</span>
											</span>
										</div>
									</li>
									<li class="ciuis-ist-tab col-md-3">
										<span class="ciuis-is-x-status">
											<?php echo lang('closed')?>
										</span>
										<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $iey ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $iey ?> </span>
											<span class="ciuis-is-x-completes">
												<?php $this->db->where('statusid',4);$this->db->from('tickets'); echo $this->db->count_all_results();?> /
												<?php $this->db->from('tickets'); echo $this->db->count_all_results();?> </span>
											</span>
											</span>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="panel borderten">
						<?php $replies  = $this->db->get_where('ticketreplies',array('ticketid'=> $ticket['id']))->result_array(); ?>
						<div class="ciuis-ticket-detail full-scroll tab-pane cont" id="ticketdetail">
						<div style="width: 200px; margin-top:-5px" class="pull-right">
							<select name="" id="optionstatus" class="select2 statuschance">
								<option><?php echo lang('changestatus')?></option>
								<option value="1"><?php echo lang('open')?></option>
								<option value="2"><?php echo lang('inprogress')?></option>
								<option value="3"><?php echo lang('answered')?></option>
								<option value="4"><?php echo lang('closed')?></option>
							</select>
						</div>
							<h2>
								<div class="btn-group">
									<button data-toggle="modal" data-target="#remove" type="button" data-toggle="tooltip" data-placement="bottom" title="Are you sure?" class="btn btn-default">
										<?php echo lang('delete')?>
									</button>
									<input hidden="" class="tickid" value="<?php echo $ticket['id']; ?>">
									<button data-target="#assignstaff" data-toggle="modal" type="button" class="btn btn-default">
										<?php echo lang('assignstaff')?>
									</button>
								
								</div>
							</h2>
							<hr>
							<div class="ciuis-ticket-info">
								<div class="ciuis-ticket-row">
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label">
											<?php echo lang('assignedstaff')?>
										</div>
										<div class="ticket-data user-avatar">
											<?php if ($ticket['stid']==0) { echo '<span class="label label-default">'.lang('notassignedanystaff').'</span>';} else echo '<a style="text-transform: uppercase;" href="'.base_url('staff/staffmember/'.$ticket['stid'].'').'"> <img src="'.base_url().'/uploads/staffavatars/'.$ticket['staffavatar'].'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$ticket['staffmembername'].'"><b>'.$ticket['staffmembername'].'</b></a>'; ?>
										</div>
									</div>
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label">
											<?php echo lang('customer')?>
										</div>
										<div class="ticket-data">
											<a href="<?php echo base_url('customers/customer/'.$ticket['customerid'].'')?>">
												<?php if($ticket['type']==0) {echo $ticket['companyname'];}else echo $ticket['namesurname']; ?>
											</a>
										</div>
									</div>
								</div>
								<div class="ciuis-ticket-row">
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label">
											<?php echo lang('contactname')?>
										</div>
										<div class="ticket-data">
											<?php echo $ticket['contactname']; ?>
											<?php echo $ticket['surname']; ?>
										</div>
									</div>
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label">
											<?php echo lang('department')?>
										</div>
										<div class="ticket-data">
											<?php echo $ticket['department']; ?>
										</div>
									</div>
								</div>
								<div class="ciuis-ticket-row">
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label">
											<?php echo lang('status')?>
										</div>
										<div class="ticket-data label-status">
											<?php 
											if ($ticket['statusid']== 1){ echo'<span class="label label-warning">'.lang('open').'</span>';} 
											if ($ticket['statusid']== 2){ echo'<span class="label label-primary">'.lang('inprogress').'</span>';}
											if ($ticket['statusid']== 3){ echo'<span class="label label-default">'.lang('answered').'</span>';}
											if ($ticket['statusid']== 4){ echo'<span class="label label-success">'.lang('closed').'</span>';}
											?>
										</div>
									</div>
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label">
											<?php echo lang('priority')?>
										</div>
										<div class="ticket-data">
											<?php 
											if ($ticket['priority']== 0){ echo'<span class="ticket-priority label label-primary">'.lang('low').'</span>';} 
											if ($ticket['priority']== 1){ echo'<span class="ticket-priority label label-warning">'.lang('medium').'</span>';}
											if ($ticket['priority']== 2){ echo'<span class="ticket-priority label label-danger">'.lang('high').'</span>';}
											?>
										</div>
									</div>
								</div>
								<div class="ciuis-ticket-row">
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label">
											<?php echo lang('datetimeopened')?>
										</div>
										<div class="ticket-data">
											<?php echo  _adate($ticket['date']); ?>
										</div>
									</div>
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label">
											<?php echo lang('datetimelastreplies')?>
										</div>
										<div class="ticket-data">
											<?php echo  _adate($ticket['lastreply']); ?>
										</div>
									</div>
								</div>
								<div class="ciuis-ticket-row">
									<div class="ciuis-ticket-fieldgroup full">
										<div class="ticket-label">
											<?php echo lang('subject')?>
										</div>
										<div class="ticket-data">
											<?php echo $ticket['subject']; ?>
										</div>
									</div>
								</div>
								<div class="ciuis-ticket-row">
									<div class="ciuis-ticket-fieldgroup full">
										<div class="ticket-label">
											<b>
												<?php echo lang('message')?>
											</b>
										</div>
										<div style="padding: 20px;border: 2px dashed #b7d4cd;border-radius: 10px;marg" class="ticket-data">
											<?php echo $ticket['message']; ?>
											<?php if ($ticket['attachment'] != NULL) {echo '<br><span class="label label-default pull-right"><i class="ion-android-attach"></i> <a href="'.base_url('uploads/ticket_attachments/'.$ticket['attachment'].'').'">'.$ticket['attachment'].'</a></span><br>'; }?>
										</div>
										
									</div>
								</div>
								<div class="ciuis-ticket-row">
									<div class="ciuis-ticket-fieldgroup full">
										<div class="ticket-data">
											<?php foreach($replies as $reply){ ?>
											<div class="main-content container-fluid">
												<div class="email-head">
													<div class="email-head-sender">
														<div class="date">
															<?php echo  _adate($reply['date']); ?>
														</div>
														<div class="sender">
															<a href="">
															<span class="label label-lg label-success"><?php echo $reply['name']?> <?php echo lang('replied')?></span>
															</a>
															<div class="actions"><a href="#" data-toggle="dropdown" class="icon toggle-dropdown"><i class="mdi mdi-caret-down"></i></a>
																<ul role="menu" class="dropdown-menu">
																	<li>
																		<a href="#">
																			<?php echo lang('delete')?>
																		</a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
												</div>
												<div class="email-body">
													<blockquote style="border-left: 2px dotted #393939;">
														<?php echo $reply['message']?>
													</blockquote>
													<?php if ($reply['attachment'] != NULL) {echo '<span class="label label-default pull-right"><i class="ion-android-attach"></i> <a href="'.base_url('uploads/ticket_attachments/'.$reply['attachment'].'').'">'.$reply['attachment'].'</a></span>'; }?>
												</div>
												<hr>
											</div>
											<?php } ?>
										</div>
										<?php echo form_open_multipart('tickets/addreply',array("class"=>"form-horizontal")); ?>
										<div class="email editor 
										<?php if($ticket['stid'] !== $this->session->userdata('logged_in_staff_id') && $ticket['stid'] != 0){echo 'hidden';}?>">
											<textarea name="message" id="replyeditor" cols="30" rows="10"></textarea>
											<input hidden="" type="text" name="ticketid" value="<?php echo $ticket['id']; ?>">
											<input hidden="" type="text" name="contactid" value="<?php echo $ticket['contactid']; ?>">
											<input hidden="" type="text" name="name" value="<?php echo $staffname = $this->session->staffname; ?>">
											<input hidden="" type="text" name="staffid" value="<?php echo $this->session->userdata('logged_in_staff_id'); ?>">
											<input hidden="" type="text" name="date" value="<?php echo date(" Y.m.d H:i:s "); ?>">
											<div class="form-group pull-left col-md-8 xs-pl-10">
												<div class="file-upload">
													<div class="file-select">
														<div class="file-select-button" id="fileName"><span class="mdi mdi-accounts-list-alt"></span>
															<?php echo lang('attachment')?>
														</div>
														<div class="file-select-name" id="noFile">
															<?php echo lang('notchoise')?>
														</div>
														<input type="file" name="attachment" id="chooseFile">
													</div>
												</div>
											</div>
											<div class="form-group">
												<button type="button" class="btn btn-default btn-space"><i class="icon s7-mail"></i> <?php echo lang('cancel')?></button>
												<button type="submit" class="btn btn-warning btn-space"><i class="icon s7-close"></i> <?php echo lang('reply')?></button>
											</div>
										</div>
										<?php echo form_close(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="assignstaff" tabindex="-1" role="content" class="modal fade colored-header colored-header-warning">
		<div class="modal-dialog">
			<?php echo form_open('tickets/assignstaff'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
					<h3 class="modal-title">
						<?php echo lang('assignstaff')?>
					</h3>
				</div>
				<div class="modal-body">

					<div class="form-group">
						<h4><b><?php echo lang('assignstaff'); ?></b></h4>
						<select name="staffid" class="select2 ">
							<option selected="selected" value="0">
								<?php echo lang('assignstaff'); ?>
							</option>
							<?php
							foreach ( $all_staff as $staff ) {
								echo '<option value="' . $staff[ 'id' ] . '">' . $staff[ 'staffname' ] . '</option>';
							}
							?>
						</select>
					</div>
					<input hidden="" type="text" name="ticketid" value="<?php echo $ticket['id']?>">

				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default modal-close">
						<?php echo lang('cancel'); ?>
					</button>
					<button type="submit" class="btn btn-default">
						<?php echo lang('assign'); ?>
					</button>
				</div>
			</div>
			<?php echo form_close(); ?>
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
							<?php echo lang('ticketattentiondetail'); ?>
						</p>
						<div class="xs-mt-50">
							<a type="button" data-dismiss="modal" class="btn btn-space btn-default">
								<?php echo lang('cancel'); ?>
							</a>
							<a href="<?php echo base_url('tickets/remove/'.$ticket['id'].'')?>" type="button" class="btn btn-space btn-danger">
								<?php echo lang('delete'); ?>
							</a>
						</div>
					</div>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
	<?php include_once dirname(dirname(__FILE__)) . '/inc/sidebar.php'; ?>
	<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_table.php'; ?>
	<script type="text/javascript">
		$( '#chooseFile' ).bind( 'change', function () {
			var filename = $( "#chooseFile" ).val();
			if ( /^\s*$/.test( filename ) ) {
				$( ".file-upload" ).removeClass( 'active' );
				$( "#noFile" ).text( "<?php echo lang('notassignedanystaff')?>" );
			} else {
				$( ".file-upload" ).addClass( 'active' );
				$( "#noFile" ).text( filename.replace( "C:\\fakepath\\", "" ) );
			}
		} );
	</script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/summernote/summernote.css"/>
	<script src="<?php echo base_url(); ?>assets/lib/summernote/summernote.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/js/app-form-wysiwyg.js" type="text/javascript"></script>
	<script type="text/javascript">
		$( document ).ready( function () {
			App.init();
			App.textEditors();
		} );
	</script>