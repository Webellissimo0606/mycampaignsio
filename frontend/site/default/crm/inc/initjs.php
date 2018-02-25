<script src="<?php echo base_url(); ?>assets/crm/lib/chartjs/dist/Chart.bundle.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/crm/js/autoNumeric.js"></script>
<script src="<?php echo base_url(); ?>assets/crm/js/jquery.alphanumeric.js"></script>
<script src="<?php echo base_url('assets/crm/lib/filterjitsu/jquery.filterjitsu.js') ?>"></script>

<script>
function speak(CiuisVoiceNotification) {
  var s = new SpeechSynthesisUtterance();
    s.volume = 0.5;
    s.rate = 1;
    s.pitch = 1;
    s.lang = '<?php echo lang('voice_language'); ?>';
	s.text = CiuisVoiceNotification;
	  window.speechSynthesis.speak(s);
}
<?php $newreminder = $this->Report_Model->newreminder();?>
<?php $openticket = $this->Report_Model->otc();?>
var voice = document.querySelectorAll('body');
var voicetest = 'Test voice';
var advancedsummaryshow = '<?php echo lang('advancedsummaryshow') ?>';
var reminder = '<?php echo $message = sprintf(lang('reminder_voice'), $newreminder) ?>';
var oepnticket = '<?php echo $message = sprintf(lang('open_ticket_voice'), $openticket) ?>';
var emptyinvoice = '<?php echo lang('empty_invoice'); ?>';
</script>
<?php
if ($settings['pushState'] == 1) {
    echo '<script src="' . base_url() . 'assets/crm/js/pushstate.js" type="text/javascript"></script>
	<script type="text/javascript">
		  $(document).ready(function(){
			//initialize the javascript
			App.init();
			App.pushstate();

		  });
	</script>';
}
?>
<?php
switch ($settings['unitseparator']) {
case ',':
    $aSep = '.';
    $aDec = ',';
    break;
case '.':
default:
    $aSep = ',';
    $aDec = '.';
    break;
}
?>
<?php
switch ($settings['currencyposition']) {
case 'before':
    $position = 'p';
    break;
case 'after':
default:
    $position = 's';
    break;
}
?>
<script>
	$( document ).ready( function () {
		// AUTO NUMERIC MONEY FORMATTER
		// MONEY FORMAT TYPE SETTINGS
		$( ".money-area" ).autoNumeric( 'init', {
			aSep: '<?php echo $aSep ?>',
			aDec: '<?php echo $aDec ?>',
			aPad: 2,
			aSign: '<?php echo currency ?>',
			pSign: '<?php echo $position ?>',

		});
		// AUTONUMERIC MONEY FORMAT
		$( ".input-money-format" ).autoNumeric( 'init', {
			aSep: '<?php echo $aSep ?>',
			aDec: '<?php echo $aDec ?>',
			aPad: 2
		});
		// AUTONUMERIC MONEY FORMAT CLEAN FORM
		$( 'form' ).submit( function () {
			var form = $( this );
			$( '.input-money-format' ).each( function ( i ) {
				var self = $( this );
				try {
					var v = self.autoNumeric( 'get' );
					self.autoNumeric( 'destroy' );
					self.val( v );
				} catch ( err ) {
					console.log( "Not an autonumeric field: " + self.attr( "amount" ) );
				}
			} );
			return true;
		});
	});
</script>
<script>
	$( document ).ready( function () {
		// ADD TODO
		var base_url = '<?php echo base_url(); ?>';
		$( ".todopost" ).click( function () {
			if($(".tododetail")[0].checkValidity()) {
        $.ajax( {
				type: "POST",
				url: base_url + "trivia/addtodo",
				data: {
					tododetail: $( ".tododetail" ).val()
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<?php echo lang('todoadded') ?>',
						position: 'bottom',
						class_name: 'color success',
					} );
					var todoid = data.insert_id;
					$( '.todo-item' ).append( '<li class="todo-alt-item" data-todoid="'+todoid+'"><div class="todo-c" style="display: grid;margin-top: 10px;"><div class="todo-item-header"> <div class="btn-group-sm btn-space pull-right" data-id="'+todoid+'"> <button type="button" class="btn btn-default btn-sm donetodo ion-checkmark"></button> <button type="button" class="btn btn-default btn-sm removetodo ion-trash-a"></button> </div> <span style="padding:5px;" class="pull-left label label-default"><?php echo lang('now') ?></span> </div> <label data-tododone="'+todoid+'" for="done">'+ $( '.tododetail' ).val() +'</label> </div></li> ');
					$( 'input[type="text"],textarea' ).val( '' );
				}
			} );
			return false;
    		}else
			{
			$.gritter.add( {
				title: '<b><?php echo lang('attention') ?></b>',
				text: '<?php echo lang('emptytodo') ?>',
				position: 'bottom',
				class_name: 'color danger',
			} );
			}
		} );
		// MARK TODO AS DONE
		$( ".donetodo" ).click( function () {
			var todoid = $( this ).parent().data( 'id' );
			var $tr = $( this ).closest( 'li' );
			$.ajax( {
				type: "POST",
				url: base_url + "trivia/donetodo",
				data: {
					todo: todoid,
					tododetail: $( ".tododetail" ).val()
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<?php echo lang('tododone') ?>',
						position: 'bottom',
						class_name: 'color success',
					} );
					$tr.find( 'div' ).fadeOut( 1000, function () {
						$tr.remove();
					} );
					$( '.todo-item-done' ).append( '<li class="todo-alt-item-done" data-id=""><div class="todo-c" style="display: grid;margin-top: 10px;"><div class="todo-item-header"> <div class="btn-group-sm btn-space pull-right" data-id=""> <button type="button" class="btn btn-default btn-sm donetodo ion-checkmark"></button> <button type="button" class="btn btn-default btn-sm removetodo ion-trash-a"></button> </div> <span style="padding:5px;" class="pull-left label label-success"><?php echo lang('donetodo') ?></span> </div> <label data-tododone="2" for="done">'+ $( '.tododetail' ).val() +'</label></div></li>');
				}
			} );
			return false;
		} );
		// REMOVE TODO
		$( ".removetodo" ).click( function () {
			var todoid = $( this ).parent().data( 'id' );
			var $tr = $( this ).closest( 'li' );
			$.ajax( {
				type: "POST",
				url: base_url + "trivia/removetodo",
				data: {
					todo: todoid
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<?php echo lang('tododeleted') ?>',
						position: 'bottom',
						class_name: 'color warning',
					} );
					$tr.find( 'div' ).fadeOut( 1000, function () {
						$tr.remove();
					} );
				}
			} );
			return false;
		} );
		$( ".modaleventadd" ).click( function () {
			$( '#addevent' ).modal();
		});
		// ADD EVENT
		$( ".postevent" ).click( function () {
		if($("#addeventform")[0].checkValidity()) {
			$.ajax( {
				type: "POST",
				url: base_url + "calendar/addevent",
				data: {
					title: $( ".ci-event-title" ).val(),
					publievent: $( ".ci-public-check" ).is(':checked'),
					detail: $( ".ci-event-description" ).val(),
					start: $( ".ci-event-start" ).val(),
					end: $( ".ci-event-end" ).val(),
					public: $( ".ci-event-public" ).val(),
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$('#addevent').modal('hide');
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<?php echo lang('eventadded') ?>',
						position: 'bottom',
						class_name: 'color success',
					} );
				}
			} );
			return false;
		}else console.log("invalid form");
		} );
		// CHANCE TICKET STATUS
		$( ".statuschance" ).change( function () {
			$.ajax( {
				type: "POST",
				url: base_url + "tickets/chancestatus",
				data: {
					statusid: $( '#optionstatus option:selected' ).val(),
					ticketid: $( ".tickid" ).val()
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<?php echo lang('ticketstatuschanced') ?>',
						class_name: 'color success'
					} );
					$( ".label-status" ).text( $( '#optionstatus option:selected' ).text() );
				}
			} );
			return false;
		});
		// INVOICE MARK AS CANCELLED
		$( ".invoicecancelled" ).click( function () {
			var sid = $('.cancelid').val();
			var iid = $('.invoiceidpost').val();
			$.ajax( {
				type: "POST",
				url: base_url + "invoices/cancelled",
				data: {
					statusid: sid,
					invoiceid: iid,
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<b><?php echo lang('invoicecancelled') ?></b>',
						class_name: 'color danger'
					} );
					$('.toggle-due').hide();
					$('.toggle-cash').hide();
					$('.cancelledinvoicealert').show();
				}
			} );
			return false;
		});
		// Proposals Status
		$( ".mark-as-btw" ).click( function () {
			var statusid = $( this ).parent().data( 'status' );
			var proposal = $( this ).parent().data( 'proposal' );
			var statusna = $( this ).parent().data( 'sname' );
			$.ajax( {
				type: "POST",
				url: base_url + "proposals/markas",
				data: {
					statusid: statusid,
					proposal: proposal,
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<b><?php echo lang('markedas') ?> '+statusna+'</b>',
						class_name: 'color success'
					} );
					$( ".p-s-lab" ).text(statusna);
				}
			} );
			return false;
		});
		// Proposals Status
		$( ".mark-as-lost-lead" ).click( function () {
			var leadid = $( this ).parent().data( 'leadid' );
			var markname = $( this ).parent().data( 'markname' );
			$.ajax( {
				type: "POST",
				url: base_url + "leads/markas_lost",
				data: {
					leadid: leadid,
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<b><?php echo lang('leadmarkedas') ?> '+markname+'</b>',
						class_name: 'color success'
					} );
					$( ".unmark-as-lost-lead" ).show();
					$( ".mark-as-lost-lead" ).hide();
					$( ".mark-lost" ).show();
				}
			} );
			return false;
		});
		$( ".mark-as-junk-lead" ).click( function () {
			var leadid = $( this ).parent().data( 'leadid' );
			var markname = $( this ).parent().data( 'markname' );
			$.ajax( {
				type: "POST",
				url: base_url + "leads/markas_junk",
				data: {
					leadid: leadid,
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<b><?php echo lang('leadmarkedas') ?> '+markname+'</b>',
						class_name: 'color success'
					} );
					$( ".unmark-as-junk-lead" ).show();
					$( ".mark-as-junk-lead" ).hide();
					$( ".mark-junk" ).show();
				}
			} );
			return false;
		});
		$( ".unmark-as-lost-lead" ).click( function () {
			var leadid = $( this ).parent().data( 'leadid' );
			var markname = $( this ).parent().data( 'markname' );
			$.ajax( {
				type: "POST",
				url: base_url + "leads/unmarkas_lost",
				data: {
					leadid: leadid,
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<b><?php echo lang('leadunmarkedas') ?> '+markname+'</b>',
						class_name: 'color success'
					} );
					$( ".mark-as-lost-lead" ).show();
					$( ".unmark-as-lost-lead" ).hide();
					$( ".mark-lost" ).hide();
				}
			} );
			return false;
		});
		$( ".unmark-as-junk-lead" ).click( function () {
			var leadid = $( this ).parent().data( 'leadid' );
			var markname = $( this ).parent().data( 'markname' );
			$.ajax( {
				type: "POST",
				url: base_url + "leads/unmarkas_junk",
				data: {
					leadid: leadid,
				},
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<b><?php echo lang('leadunmarkedas') ?> '+markname+'</b>',
						class_name: 'color success'
					} );
					$( ".mark-as-junk-lead" ).show();
					$( ".unmark-as-junk-lead" ).hide();
					$( ".mark-junk" ).hide();
				}
			} );
			return false;
		});
		// Resume...
		// LOGO ANIMATION
		$( '#ciuis-logo-donder' ).addClass( 'animated rotateIn' ); // Logo Transform
		// NOTIFICATION MARK AS READ
		$( '.markok' ).click( function () {
			var notificationid = $( this ).parent().data( 'id' );
			$.ajax( {
				url: '<?php echo base_url('notifications/markread'); ?>',
				data: {
					"notificationid": notificationid
				},
				type: 'post',
			} );
		});
		// NOTIFICATION MARK AS READ CUSTOMER
		$( '.cusmark' ).click( function () {
			var contactid = $( this ).parent().data( 'id' );
			$.ajax( {
				url: '<?php echo base_url('customer/markread'); ?>',
				data: {
					"contactid": contactid
				},
				type: 'post',
			} );
		});
		$( '.mark-read-reminder' ).click( function () {
			var $li = $( this ).closest( 'li.reminder' );
			var reminderid = $( this ).parent().data( 'reminderid' );
			$.ajax( {
				url: base_url + "trivia/markreadreminder/" +reminderid,
				data: {
					"reminderid": reminderid
				},
				type: 'post',
				dataType: "text",
				cache: false,
				success: function ( data ) {
					$.gritter.add( {
						title: '<b><?php echo lang('notification') ?></b>',
						text: '<b><?php echo lang('remindermarkasread') ?></b>',
						class_name: 'color success'
					} );
					$li.remove();
				}
			} );
		});
		// LOAD MORE LOGS
		$( ".ciuis-activity-detail" ).slice( 0, 2 ).show();
		$( "#loadMore" ).on( 'click', function ( e ) {
			e.preventDefault();
			$( ".ciuis-activity-detail:hidden" ).slice( 0, 6 ).slideDown();
			if ( $( ".ciuis-activity-detail:hidden" ).length == 0 ) {
				$( "#load" ).fadeOut( 'slow' );
			}
			$( 'html,body' ).animate( {
				scrollTop: $( this ).offset().top
			}, 1500 );
		});
		// PAGE FADE SCROLL
		$( window ).scroll( function () {
			$( ".fadeaway" ).css( "opacity", 1 - $( window ).scrollTop() / 150 );
		} );
	} );
</script>
<?php if ($this->session->flashdata('ntf1')) {
    ?>
<script type="text/javascript">
	$.gritter.add( {
		title: '<b><?php echo lang('notification') ?></b>',
		text: '<?php echo $this->session->flashdata('ntf1'); ?>',
		class_name: 'color success'
	} );
</script>
<?php
}?>
<?php if ($this->session->flashdata('ntf2')) {
        ?>
<script type="text/javascript">
	$.gritter.add( {
		title: '<b><?php echo lang('notification') ?></b>',
		text: '<?php echo $this->session->flashdata('ntf2'); ?>',
		class_name: 'color primary'
	} );
</script>
<?php
    }?>
<?php if ($this->session->flashdata('ntf3')) {
        ?>
<script type="text/javascript">
	$.gritter.add( {
		title: '<b><?php echo lang('notification') ?></b>',
		text: '<?php echo $this->session->flashdata('ntf3'); ?>',
		class_name: 'color warning'
	} );
</script>
<?php
    }?>
<?php if ($this->session->flashdata('ntf4')) {
        ?>
<script type="text/javascript">
	$.gritter.add( {
		title: '<b><?php echo lang('notification') ?></b>',
		text: '<?php echo $this->session->flashdata('ntf4'); ?>',
		class_name: 'color danger'
	} );
</script>
<?php
    }?>
<script>
	var App = ( function () {
		'use strict';
		App.formElements = function () {
			//Js Code
			$( ".datetimepicker" ).datetimepicker( {
				autoclose: true,
				componentIcon: '.mdi.mdi-calendar',
				navIcons: {
					rightIcon: 'mdi mdi-chevron-right',
					leftIcon: 'mdi mdi-chevron-left'
				}
			} );
			//Select2
			$( ".select2" ).select2( {
				width: '100%'
			} );
			//Select2 tags
			$( ".tags" ).select2( {
				tags: true,
				width: '100%'
			} );
			$.fn.filterjitsu();
		};
		return App;
	} )( App || {} );
</script>
<script>
	/* Generate User Avatar */
	( function ( w, d ) {
		function LetterAvatar( name, size ) {
			name = name || '';
			size = size || 40;
			var colours = [
					"#6e7479", "#6e7479", "#6e7479", "#6e7479", "#6e7479", "#6e7479", "#6e7479", "#6e7479", "#6e7479", "#6e7479",
					"#6e7479", "#6e7479", "#6e7479", "#6e7479", "#6e7479", "#6e7479", "#6e7479", "#6e7479", "#6e7479", "#6e7479"
				],
				nameSplit = String( name ).toUpperCase().split( ' ' ),
				initials, charIndex, colourIndex, canvas, context, dataURI;
			if ( nameSplit.length == 1 ) {
				initials = nameSplit[ 0 ] ? nameSplit[ 0 ].charAt( 0 ) : '?';
			} else {
				initials = nameSplit[ 0 ].charAt( 0 ) + nameSplit[ 1 ].charAt( 0 );
			}
			if ( w.devicePixelRatio ) {
				size = ( size * w.devicePixelRatio );
			}
			charIndex = ( initials == '?' ? 72 : initials.charCodeAt( 0 ) ) - 64;
			colourIndex = charIndex % 20;
			canvas = d.createElement( 'canvas' );
			canvas.width = size;
			canvas.height = size;
			context = canvas.getContext( "2d" );
			context.fillStyle = colours[ colourIndex - 1 ];
			context.fillRect( 0, 0, canvas.width, canvas.height );
			context.font = Math.round( canvas.width / 2 ) + "px Helvetica Neue";
			context.textAlign = "center";
			context.fillStyle = "#ffffff";
			context.fillText( initials, size / 2, size / 1.5 );
			dataURI = canvas.toDataURL();
			canvas = null;
			return dataURI;
		}
		LetterAvatar.transform = function () {
			Array.prototype.forEach.call( d.querySelectorAll( 'img[avatar]' ), function ( img, name ) {
				name = img.getAttribute( 'avatar' );
				img.src = LetterAvatar( name, img.getAttribute( 'width' ) );
				img.removeAttribute( 'avatar' );
				img.setAttribute( 'alt', name );
			} );
		};
		if ( typeof define === 'function' && define.amd ) {
			define( function () {
				return LetterAvatar;
			} );
		} else if ( typeof exports !== 'undefined' ) {
			if ( typeof module != 'undefined' && module.exports ) {
				exports = module.exports = LetterAvatar;
			}
			exports.LetterAvatar = LetterAvatar;
		} else {
			window.LetterAvatar = LetterAvatar;
			d.addEventListener( 'DOMContentLoaded', function ( event ) {
				LetterAvatar.transform();
			} );
		}
	} )( window, document );
</script>
<script>
;(function() {
  function DropMenu() {}
  DropMenu.prototype = {
    init: function() {
      document.querySelector('.drop-menu ul').style.display = 'none';
      document.querySelector('.drop-menu > a').addEventListener('click', this.toggle, false);
    },
    toggle: function() {
      this.parentNode.classList.toggle('active');
    }
  };
  var dropMenu = new DropMenu();
  dropMenu.init();
})();
</script>