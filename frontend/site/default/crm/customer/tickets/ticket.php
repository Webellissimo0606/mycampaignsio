<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="ticket-container tab-container">
			<div class="active-view">
				<div class="list-view">
					<div class="list-view-top">
						<input hidden="" type="search" class="textfilter" placeholder="Search by name"/>
						<form class="ticketfilterselectbox">
							<label class="select-box">
							<select id="select" class="filterbyword">
								<option value="all"><?php echo lang('all')?></option>
								<option value="2"><?php echo lang('high')?></option>
								<option value="1"><?php echo lang('medium')?></option>
								<option value="0"><?php echo lang('low')?></option>
							</select>
						</label>
						</form>
					</div>
					<ul class="list-view-list full-scroll nav tickets-tab">
						<?php foreach($dtickets as $tic){ ?>
						<li class="<?php if($tic['id'] === $ticket['id']){echo 'active';}?>">
							<a href="<?php echo base_url('customer/ticket/'.$tic['id'].'');?>">
								<?php 
							if ($tic['priority']== 0){ echo'<span class="ticket-priority label label-primary">'.lang('low').'</span>';} 
							if ($tic['priority']== 1){ echo'<span class="ticket-priority label label-warning">'.lang('medium').'</span>';}
							if ($tic['priority']== 2){ echo'<span class="ticket-priority label label-danger">'.lang('high').'</span>';}
							?>
								<h3><?php echo lang('ticket')?>: <?php echo $tic['id']; ?></h3>
								<h4>
									<?php echo $tic['subject']; ?>
								</h4>
								<p>
									<?php echo $tic['message']; ?>
								</p>
							</a>
						</li>
						<?php } ?>
					</ul>
				</div>
				<div class="ciuis-ticket">
					<div class="ciuis-ticket-top">
						<div class="ciuis-ticket-stats">
							<div class="row" style="padding: 0px 20px 0px 20px;">
								<ul class="ciuis-ticket-stats grid">
									<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-3"> <span class="ciuis-is-x-status"><?php echo lang('open')?></span>
										<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $ysy ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $ysy ?> </span>
											<span class="ciuis-is-x-completes">
												<?php $this->db->where('statusid = 1 AND contactid = '.$_SESSION[ 'contact_id' ].'');$this->db->from('tickets'); echo $this->db->count_all_results();?> /
												<?php $this->db->from('tickets')->where('contactid = '.$_SESSION[ 'contact_id' ].''); echo $this->db->count_all_results();?> </span>
											</span>
											</span>
										</div>
									</li>
									<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-3"> <span class="ciuis-is-x-status"><?php echo lang('inprogress')?></span>
										<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $bsy ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $bsy ?> </span>
											<span class="ciuis-is-x-completes">
												<?php $this->db->where('statusid = 2 AND contactid = '.$_SESSION[ 'contact_id' ].'');$this->db->from('tickets'); echo $this->db->count_all_results();?> /
												<?php $this->db->from('tickets')->where('contactid = '.$_SESSION[ 'contact_id' ].''); echo $this->db->count_all_results();?> </span>
											</span>
											</span>
										</div>
									</li>
									<li class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-3"> <span class="ciuis-is-x-status"><?php echo lang('answered')?></span>
										<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $twy ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $twy ?> </span>
											<span class="ciuis-is-x-completes">
												<?php $this->db->where('statusid = 3 AND contactid = '.$_SESSION[ 'contact_id' ].'');$this->db->from('tickets'); echo $this->db->count_all_results();?> /
												<?php $this->db->from('tickets')->where('contactid = '.$_SESSION[ 'contact_id' ].''); echo $this->db->count_all_results();?> </span>
											</span>
											</span>
											</span>
										</div>
									</li>
									<li class="ciuis-ist-tab col-md-3"> <span class="ciuis-is-x-status"><?php echo lang('closed')?></span>
										<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $iey ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $iey ?> </span>
											<span class="ciuis-is-x-completes">
												<?php $this->db->where('statusid = 4 AND contactid = '.$_SESSION[ 'contact_id' ].'');$this->db->from('tickets'); echo $this->db->count_all_results();?> /
												<?php $this->db->from('tickets')->where('contactid = '.$_SESSION[ 'contact_id' ].''); echo $this->db->count_all_results();?> </span>
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
							<h2><?php echo $ticket['id']; ?></h2>
							<div class="ciuis-ticket-info">
								<div class="ciuis-ticket-row">
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label"><?php echo lang('assignedstaff')?></div>
										<div class="ticket-data user-avatar">
											<?php if ($ticket['stid']==0) { echo '<span class="label label-default">'.lang('notassignedanystaff').'</span>';} else echo '<a style="text-transform: uppercase;" href="'.base_url('staff/staffmember/'.$ticket['stid'].'').'"> <img src="'.base_url().'/uploads/staffavatars/'.$ticket['staffavatar'].'" data-toggle="tooltip" data-placement="bottom" data-original-title="'.$ticket['staffmembername'].'"><b>'.$ticket['staffmembername'].'</b></a>'; ?>
										</div>
									</div>
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label"><?php echo lang('customer')?></div>
										<div class="ticket-data">
											<a href="<?php echo base_url('customers/customer/'.$ticket['customerid'].'')?>">
												<?php if($ticket['type']==0) {echo $ticket['companyname'];}else echo $ticket['namesurname']; ?>
											</a>
										</div>
									</div>
								</div>
								<div class="ciuis-ticket-row">
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label"><?php echo lang('contactname')?></div>
										<div class="ticket-data">
											<?php echo $ticket['contactname']; ?>
											<?php echo $ticket['surname']; ?>
										</div>
									</div>
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label"><?php echo lang('department')?></div>
										<div class="ticket-data">
											<?php echo $ticket['department']; ?>
										</div>
									</div>
								</div>
								<div class="ciuis-ticket-row">
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label"><?php echo lang('status')?></div>
										<div class="ticket-data">
											<?php 
											if ($ticket['statusid']== 1){ echo'<span class="label label-warning">'.lang('open').'</span>';} 
											if ($ticket['statusid']== 2){ echo'<span class="label label-primary">'.lang('inprogress').'</span>';}
											if ($ticket['statusid']== 3){ echo'<span class="label label-default">'.lang('answered').'</span>';}
											if ($ticket['statusid']== 4){ echo'<span class="label label-success">'.lang('closed').'</span>';}
											?>
										</div>
									</div>
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label"><?php echo lang('priority')?></div>
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
										<div class="ticket-label"><?php echo lang('datetimeopened')?></div>
										<div class="ticket-data">
											<?php echo  _adate($ticket['date']); ?>
										</div>
									</div>
									<div class="ciuis-ticket-fieldgroup">
										<div class="ticket-label"><?php echo lang('datetimelastreplies')?></div>
										<div class="ticket-data">
											<?php echo  _adate($ticket['lastreply']); ?>
										</div>
									</div>
								</div>
								<div class="ciuis-ticket-row">
									<div class="ciuis-ticket-fieldgroup full">
										<div class="ticket-label"><?php echo lang('subject')?></div>
										<div class="ticket-data">
											<?php echo $ticket['subject']; ?>
										</div>
									</div>
								</div>
								<div class="ciuis-ticket-row">
									<div class="ciuis-ticket-fieldgroup full">
										<div class="ticket-label"><b><?php echo lang('message')?></b>
										</div>
										<div style="padding: 20px;border: 2px dashed #b7d4cd;border-radius: 10px;" class="ticket-data">
											<?php echo $ticket['message']; ?>
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
															<?php echo $reply['date']?>
														</div>
														<div class="sender">
															<a href="">
															<span class="label label-lg label-success"><?php echo $reply['name']?> <?php echo lang('replied')?></span>
															</a>
															<div class="actions"><a href="#" data-toggle="dropdown" class="icon toggle-dropdown"><i class="mdi mdi-caret-down"></i></a>
																<ul role="menu" class="dropdown-menu">
																	<li><a href="#"><?php echo lang('delete')?></a>
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
										<?php echo form_open_multipart('customer/addreply',array("class"=>"form-horizontal")); ?>
										<div class="email editor">
											<textarea name="message" id="replyeditor" cols="30" rows="10"></textarea>
											<input hidden="" type="text" name="ticketid" value="<?php echo $ticket['id']; ?>">
											<input hidden="" type="text" name="staffid" value="<?php echo $ticket['staffid']; ?>">
											<div class="form-group pull-left col-md-8 xs-pl-10">
												<div class="file-upload">
													<div class="file-select">
														<div class="file-select-button" id="fileName"><span class="mdi mdi-accounts-list-alt"></span> <?php echo lang('attachment')?></div>
														<div class="file-select-name" id="noFile"><?php echo lang('nofile')?></div>
														<input type="file" name="attachment" id="chooseFile">
													</div>
												</div>
											</div>
											<div class="form-group">
												<button type="button" class="btn btn-default btn-space"><i class="icon s7-mail"></i><?php echo lang('cancel')?></button>
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
	<script>
		// short foreach
		function Each( el, callback ) {
			// get all el selectors = li
			const allDivs = document.querySelectorAll( el );
			const // new array
				alltoArr = Array.prototype.slice.call( allDivs );
			// foreach new array
			Array.prototype.forEach.call( alltoArr, ( selector, index ) => {
				// callback selector
				if ( callback ) return callback( selector );
			} );
		}
		// search filter
		function textFilter( input, selectorOutput ) {
			// get selector
			searchInput = document.querySelector( input );
			// listen on keyup
			searchInput.addEventListener( 'keyup', e => {
				// each function
				Each( selectorOutput, obj => {
					// get text to lower case
					const text = obj.textContent.toLowerCase();
					const // get value to lower case
						val = searchInput.value.toLowerCase();
					// hide or show 
					obj.style.display = !text.includes( val ) ? 'none' : '';
				} );
			} );
		}
		// select filter
		function selectFilter( input, selector ) {
			// get select class
			const search = document.querySelector( input );
			const // get li class
				div = document.querySelector( selector );
			// listen select on change
			search.addEventListener( 'change', e => {
				// if value all show all 
				if ( search.value === 'all' ) {
					// each function
					Each( selector, obj => {
						// empty display to show
						obj.style.display = '';
					} );
				} else {
					// each function
					Each( selector, obj => {
						// if contains class show if not hide
						if ( obj.classList.contains( search.value ) ) {
							// empty display to show
							obj.style.display = '';
						} else {
							// none display to hide
							obj.style.display = 'none';
						}
					} );
				}
			} );
		}
		// text filter
		textFilter( '.textfilter', '#dana' );
		// select filter
		selectFilter( '.filterbyword', '#dana' );
	</script>
	<?php include_once(APPPATH . 'views/customer/inc/sidebar.php'); ?>
	<?php include_once(APPPATH . 'views/inc/footer.php'); ?>
	<script type="text/javascript">
		$( '#chooseFile' ).bind( 'change', function () {
			var filename = $( "#chooseFile" ).val();
			if ( /^\s*$/.test( filename ) ) {
				$( ".file-upload" ).removeClass( 'active' );
				$( "#noFile" ).text( "<?php echo lang('nofile')?>" );
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