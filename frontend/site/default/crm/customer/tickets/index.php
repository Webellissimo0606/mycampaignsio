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
						<?php foreach($tickets as $ticket){ ?>
						<li id="dana" class="<?php echo $ticket['priority']?>">
							<a href="<?php echo base_url('customer/ticket/'.$ticket['id'].'');?>">
								<?php switch($ticket['priority']){ 
								case '0': echo '<span class="ticket-priority label label-primary">'.lang('low').'</span>';break; 
								case '1': echo '<span class="ticket-priority label label-warning">'.lang('medium').'</span>'; break;
								case '2': echo '<span class="ticket-priority label label-danger">'.lang('high').'</span>'; break;
								}?>
								<h3><?php echo lang('ticket')?>: <?php echo $ticket['id']; ?></h3>
								<h4>
									<?php echo $ticket['subject']; ?>
								</h4>
								<p>
									<?php echo $ticket['message']; ?>
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
					<div class="ciuis-ticket-bottom pull-right lg-p-20">
						<button data-target="#addticket" data-toggle="modal" class="btn btn-warning btn-xl"><?php echo lang('newticket')?></button>
						<div id="addticket" tabindex="-1" role="content" class="modal fade colored-header colored-header-warning">
							<div class="modal-dialog">
								<?php echo form_open_multipart('customer/addticket'); ?>
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
										<h3 class="modal-title"><?php echo lang('newticket')?></h3>
									</div>
									<div class="modal-body">
										<div class="col-md-12">
											<div class="form-group">
												<label for="subject">
													<?php echo lang('subject')?>
												</label>
												<div class="input-group"><span class="input-group-addon"><i class="ion-information"></i></span>
													<input type="text" name="subject" value="<?php echo $this->input->post('subject'); ?>" class="form-control" id="title" placeholder="<?php echo lang('subject')?>"/>
												</div>
											</div>
										</div>
										<div class="col-md-6 md-pr-0">
											<div class="form-group">
												<label for="expensecategoryid">
													<?php echo lang('department')?>
												</label>
											
												<div class="add-on-edit">
													<select name="department" class="form-control select2">
														<?php
														foreach ( $departments as $department ) {
															$selected = ( $department[ 'id' ] == $this->input->post( 'department' ) ) ? ' selected="selected"' : "";
															echo '<option value="' . $department[ 'id' ] . '" ' . $selected . '>' . $department[ 'name' ] . '</option>';
														}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-6 md-pr-10">
											<div class="form-group">
												<label for="p">
													<?php echo lang('priority')?>
												</label>
												<div class="add-on-edit">
													<select name="priority" class="form-control select2">
														<option value="0"><?php echo lang('low')?></option>
														<option value="1"><?php echo lang('medium')?></option>
														<option value="2"><?php echo lang('high')?></option>
													</select>
												</div>
											</div>
										</div>
										<input hidden="" type="text" name="dateadded" value="<?php echo date(" Y-m-d H:i:s "); ?>"/>
										<input type="hidden" name="staffid" value="<?php echo $this->session->userdata('logged_in_staff_id'); ?>">
										<div class="col-md-12 md-pt-0">
											<div class="form-group">
												<label for="description">
													<?php echo lang('message')?>
												</label>
												<div class="input-group xs-mb-15"><span class="input-group-addon"><i class="ion-ios-compose-outline"></i></span>
													<textarea name="message" class="form-control" id="message" placeholder="Message"><?php echo $this->input->post('message'); ?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="file-upload">
													<div class="file-select">
														<div class="file-select-button" id="fileName"><span class="mdi mdi-accounts-list-alt"></span> <?php echo lang('attachment')?></div>
														<div class="file-select-name" id="noFile"><?php echo lang('nofile')?></div>
														<input type="file" name="attachment" id="chooseFile">
													</div>
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
								</div>
								<?php echo form_close(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
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
	<?php include_once(APPPATH . 'views/inc/footer_table.php'); ?>
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
			//initialize the javascript
			App.init();
			App.textEditors();
		} );
	</script>