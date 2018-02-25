<?php
  $crm_assets_path = base_url('assets/crm') . '/';
?>
</div>
<script src="<?php echo $crm_assets_path; ?>lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>

<script src="<?php echo $crm_assets_path; ?>js/main.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/moment.js/min/moment.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>

<script src="<?php echo $crm_assets_path; ?>lib/jquery.gritter/js/jquery.gritter.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/select2/js/select2.min.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/bootstrap-slider/js/bootstrap-slider.js" type="text/javascript"></script>
<script src="<?php echo $crm_assets_path; ?>lib/jquery.fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script src='<?php echo $crm_assets_path; ?>lib/jquery.fullcalendar/lang-all.js'></script>
<link rel="stylesheet" type="text/css" href="<?php echo $crm_assets_path; ?>lib/jquery.fullcalendar/fullcalendar.min.css"/>
<?php include_once dirname(dirname(__FILE__)) . '/inc/initjs.php';?>
<script type="text/javascript">
	$( document ).ready( function () {
		//initialize the javascript
		App.init();
		App.formElements();
		App.pageCalendar();
	} );
</script>
<script>
	var App = ( function () {
		'use strict';
		App.pageCalendar = function () {
			$( '#external-events .fc-event' ).each( function () {
				// store data so the calendar knows to render an event upon drop
				$( this ).data( 'event', {
					title: $.trim( $( this ).text() ), // use the element's text as the event title
					stick: true // maintain when user navigates (see docs on the renderEvent method)
				} );
				// make the event draggable using jQuery UI
				$( this ).draggable( {
					zIndex: 999,
					revert: true, // will cause the event to go back to its
					revertDuration: 0 //  original position after the drag
				} );
			} );
			$.post( '<?php echo base_url('calendar/get_Events'); ?>',
				function ( data ) {
					//alert(data);
					$( '#calendar' ).fullCalendar( {
						lang: '<?php echo lang('calendarlanguage'); ?>',
						header: {
							left: 'title',
							center: '',
							right: 'month,agendaWeek,agendaDay, today, prev,next',
						},
						eventClick: function ( event, jsEvent, view ) {
							$( '#modalTitle' ).html( event.title );
							$( '#eventdetail' ).html( event.activitydetail );
							$( '#staffname' ).html( event.stafname );
							$( '#startdate' ).html( event.start );
							$( '#enddate' ).html( event.end );
							$( '#eventUrl' ).attr( 'href', '<?php echo base_url('calendar/remove/') ?>' +'/'+ event.id );
							$( '#fullCalModal' ).modal();
						},
						defaultDate: new Date(),
						editable: true,
						eventLimit: true,
						droppable: true, // this allows things to be dropped onto the calendar
						drop: function () {
							// is the "remove after drop" checkbox checked?
							if ( $( '#drop-remove' ).is( ':checked' ) ) {
								// if so, remove the element from the "Draggable Events" list
								$( this ).remove();
							}
						},
						events: $.parseJSON( data )
					} );
				} );

		};
		return App;
	} )( App || {} );
</script>
</body>
</html>