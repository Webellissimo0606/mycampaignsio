<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
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
							<a href="<?php echo base_url('tickets/ticket/'.$ticket['id'].'');?>">
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
				<div class="ciuis-ticket hidden-xs">
					<div class="ciuis-ticket-top">
						<div class="ciuis-ticket-stats">
							<div class="row" style="padding: 0px 20px 0px 20px;">
								<ul class="ciuis-ticket-stats grid">
									<li onclick="window.location='<?php echo base_url('tickets/open/')?>'" class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-3"> <span class="ciuis-is-x-status"><?php echo lang('open')?></span>
										<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $ysy ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $ysy ?> </span>
											<span class="ciuis-is-x-completes">
												<?php $this->db->where('statusid',1);$this->db->from('tickets'); echo $this->db->count_all_results();?> /
												<?php $this->db->from('tickets'); echo $this->db->count_all_results();?> </span>
											</span>
											</span>
										</div>
									</li>
									<li onclick="window.location='<?php echo base_url('tickets/inprogress/')?>'" class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-3"> <span class="ciuis-is-x-status"><?php echo lang('inprogress')?></span>
										<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $bsy ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $bsy ?> </span>
											<span class="ciuis-is-x-completes">
												<?php $this->db->where('statusid',2);$this->db->from('tickets'); echo $this->db->count_all_results();?> /
												<?php $this->db->from('tickets'); echo $this->db->count_all_results();?> </span>
											</span>
											</span>
										</div>
									</li>
									<li onclick="window.location='<?php echo base_url('tickets/answered/')?>'" class="ciuis-ist-tab ciuis-ist-tab-border-right col-md-3"> <span class="ciuis-is-x-status"><?php echo lang('answered')?></span>
										<div class="pull-right"> <span class="ciuis-ticket-percent"> <span class="ciuis-ticket-percent-bg"> <span class="ciuis-ticket-percent-fg" style="width: <?php echo $twy ?>%;"></span> </span> <span class="ciuis-ticket-percent-labels"> <span class="ciuis-ticket-percent-label"> %<?php echo $twy ?> </span>
											<span class="ciuis-is-x-completes">
												<?php $this->db->where('statusid',3);$this->db->from('tickets'); echo $this->db->count_all_results();?> /
												<?php $this->db->from('tickets'); echo $this->db->count_all_results();?> </span>
											</span>
											</span>
											</span>
										</div>
									</li>
									<li onclick="window.location='<?php echo base_url('tickets/closed/')?>'" class="ciuis-ist-tab col-md-3"> <span class="ciuis-is-x-status"><?php echo lang('closed')?></span>
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
	<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_table.php'; ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/crm/lib/summernote/summernote.css"/>
	<script src="<?php echo base_url(); ?>assets/crm/lib/summernote/summernote.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/crm/js/app-form-wysiwyg.js" type="text/javascript"></script>
	<script type="text/javascript">
		$( document ).ready( function () {
			//initialize the javascript
			App.init();
			App.textEditors();
		} );
	</script>