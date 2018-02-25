<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-md-9">
		<div class="row full-calendar">
			<div class="col-md-12">
				<div class="panel panel-default panel-fullcalendar borderten">
					<div class="panel-body">
						<div id="calendar"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="fullCalModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only"><?php echo lang('close')?></span></button>
					<h4 id="modalTitle" class="modal-title text-bold"></h4>
				</div>
				<div id="modalBody" class="modal-body">
					<p id="eventdetail"></p>
					<div class="pull-right">
					
					</div>
				</div>
				<div class="modal-footer">
					<div class="ticket-data user-avatar pull-left">
						<b id="staffname"></b>
						<span id="startdate"></span>
						<span id="enddate"></span>
					</div>
					<button type="button" class="btn btn-default" data-dismiss="modal">
						<?php echo lang('close')?>
					</button>
					<button class="btn btn-default"><a id="eventUrl" target="_blank"><?php echo lang('delete')?></a></button>
				</div>
			</div>
		</div>
	</div>
	
	<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_calendar.php'; ?>