<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="panel-default">
			<div class="ciuis-invoice-summary"></div>
		</div>
		<div style="border-bottom-left-radius: 10px;border-bottom-right-radius: 10px;" class="panel panel-default panel-table">
			<div class="panel-heading md-mb-20" style="height: 70px;">
				<div class="pull-left">
					<a href="<?php echo base_url('invoices/add');?>" type="button" class="pull-left btn btn-default btn-xl  text-muted ion-plus-round">
					<?php echo lang('newinvoice'); ?>
					</a>
				</div>
				<div class="pull-right">
					<div class="ciuis-external-search-in-table">
					  <input class="search-table-external" id="search" name="search" type="text" placeholder="<?php echo lang('searchword')?>">
					  <i class="ion-ios-search"></i>
					</div>
					<div style="width: 200px" class="btn-group btn-hspace">
						<select id="invoicefilterbystatus" class="select2" onChange="window.location.href=this.value">
						<option value='?'><?php echo lang('invoicefilterstatus') ?></option>
						<option value='?'><?php echo lang('all') ?></option>
						<option value="?filter-status=1"><?php echo lang('draft') ?></option>
						<option value="?filter-status=2"><?php echo lang('paid') ?></option>
						<option value="?filter-status=3"><?php echo lang('notpaid') ?></option>
						<option value="?filter-status=4"><?php echo lang('cancelled') ?></option>
					</select>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<table id="table2" class="table table-striped table-hover table-fw-widget">
					<thead style="">
						<tr>
							<th width="130px"><?php echo lang('invoiceidno'); ?></th>
							<th><?php echo lang('invoicetablecustomer'); ?></th>
							<th class="hidden-xs"><?php echo lang('billeddate'); ?></th>
							<th class="hidden-xs"><?php echo lang('invoiceduedate'); ?></th>
							<th class="hidden-xs"><?php echo lang('status'); ?></th>
							<th class="text-right"><?php echo lang('invoiceamount'); ?></th>
							<th class="text-right"><?php echo lang('action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($invoices as $fa){ ?>
						<tr data-filterable data-filter-status="<?php echo $fa['statusid']?>" class="ciuis-254325232345">
							<td><a style="background: rgba(245, 245, 245, 0.4); padding: 5px; border-radius: 5px; border: 1px dashed #393939;" href="<?php echo site_url('invoices/invoice/'.$fa['id']); ?>"><b class="text-muted"> <?php echo lang('invoiceprefix'),'-',str_pad($fa['id'], 6, '0', STR_PAD_LEFT); ?></b></a>
							</td>
							<td><span class="title uppercase"><a href="<?php echo site_url('customers/customer/'.$fa['customerid']); ?>"> <b><?php if($fa['customer']===NULL){echo $fa['individual'];} else echo $fa['customer']; ?></b></a></span>
							</td>
							<td class="hidden-xs">
								<b class="text-muted">
								<?php switch($settings['dateformat']){ 
								case 'yy.mm.dd': echo _rdate($fa['datecreated']);break; 
								case 'dd.mm.yy': echo _udate($fa['datecreated']); break;
								case 'yy-mm-dd': echo _mdate($fa['datecreated']); break;
								case 'dd-mm-yy': echo _cdate($fa['datecreated']); break;
								case 'yy/mm/dd': echo _zdate($fa['datecreated']); break;
								case 'dd/mm/yy': echo _kdate($fa['datecreated']); break;
								}?>
								</b>
							</td>
							<td class="hidden-xs">
								<b class="text-muted"><?php if($fa['duedate'] == 0000-00-00){echo '<span class="badge">No Due Date</span>';};?>
								<?php switch($settings['dateformat']){ 
								case 'yy.mm.dd': echo _rdate($fa['duedate']);break; 
								case 'dd.mm.yy': echo _udate($fa['duedate']); break;
								case 'yy-mm-dd': echo _mdate($fa['duedate']); break;
								case 'dd-mm-yy': echo _cdate($fa['duedate']); break;
								case 'yy/mm/dd': echo _zdate($fa['duedate']); break;
								case 'dd/mm/yy': echo _kdate($fa['duedate']); break;
								}?>
								</b>
							</td>
							<td class="hidden-xs">
							<?php $totalx = $fa['total'];$this->db->select_sum('amount')->from('payments')->where('(invoiceid ='.$fa['id'].') ');$paytotal = $this->db->get();
								$balance = $totalx - $paytotal->row()->amount;
								if($balance > 0) {echo '';}else echo '<h4 class="pull-left" style="font-weight: 900;color: #22c39e;">'.lang('paidinv').' <i class="icon ion-checkmark-circled"></i></h4>';					
								if($paytotal->row()->amount < $fa['total'] && $paytotal->row()->amount > 0 && $fa['statusid'] == 3){echo '<h4 class="text-uppercase pull-left" style="font-weight: 900;color: #ffbc00;display: table-cell;">'.lang('partial').' <i class="icon ion-checkmark-circled"></i></h4>';}else{
									if($paytotal->row()->amount < $fa['total'] && $paytotal->row()->amount > 0){echo '<h4 class="text-uppercase  pull-left" style="font-weight: 900;color: #ffbc00;display: table-cell;">'.lang('partial').' <i class="icon ion-checkmark-circled"></i></h4>';}
									if($fa['statusid'] == 3){echo '<h4 class="pull-left text-danger" style="font-weight: 900;display: table-cell;">'.lang('unpaid').' <i class="icon ion-close-circled"></i></h4>';}
								}
								if($fa['statusid'] == 1){echo '<h4 class="text-uppercase  pull-left text-muted" style="font-weight: 900;display: table-cell;">'.lang('draft').' <i class="icon ion-document"></i></h4>';}
								if($fa['statusid'] == 4){echo '<h4 class="text-uppercase  pull-left text-danger" style="font-weight: 900;display: table-cell;">'.lang('cancelled').' <i class="icon ion-android-cancel"></i></h4>';}
								?></td>
							<td class="text-right">
								<?php $totalx = $fa['total'];$this->db->select_sum('amount')->from('payments')->where('(invoiceid ='.$fa['id'].') ');$paytotal = $this->db->get();?>
								<?php $balance = $totalx - $paytotal->row()->amount;?>
								<h4 class="pull-right">
								<b>
								<span class="money-area"><?php echo $fa['total'];?></span>
								
								<br>
								<small><?php if($balance == 0){echo '';}else{
									echo ''.lang('balance').'  <span class="money-area">'.$balance.'</span>';
									}?></small></b>
								</h4>
							</td>
							<td class="text-right">
								<div class="btn-group btn-hspace">
									<button type="button" data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="ion-android-checkmark-circle"></span></button>
									<ul style="margin-right: 40px;margin-top: -30px;" role="menu" class="dropdown-menu dropdown-menu-right">
									<li><a href="<?php echo site_url('invoices/edit/'.$fa['id']); ?>" ><?php echo lang('updateinvoicetitle'); ?></a></li>
									<li><a href="<?php echo site_url('invoices/invoice/'.$fa['id']); ?>"><?php echo lang('viewinvoice'); ?></a></li>
									<li class="divider"></li>
									<li><a href="#" data-toggle="modal" data-target="#mod-danger<?=$fa['id']?>"><?php echo lang('delete'); ?></a></li>
								  	</ul>
								</div>
								<div id="mod-danger<?=$fa['id']?>" tabindex="-1" role="dialog" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
											</div>
											<div class="modal-body">
												<div class="text-center">
													<div class="text-danger"><span class="modal-main-icon mdi mdi-close-circle-o"></span>
													</div>
													<h3><?php echo lang('attention'); ?></h3>
													<p><?php echo lang('invoiceattentiondetail'); ?></p>
													<div class="xs-mt-50"> <a type="button" data-dismiss="modal" class="btn btn-space btn-default"><?php echo lang('cancel'); ?></a> <a href="<?php echo site_url('invoices/remove/'.$fa['id']); ?>" type="button" class="btn btn-space btn-danger"><?php echo lang('delete'); ?></a> </div>
												</div>
											</div>
											<div class="modal-footer"></div>
										</div>
									</div>
								</div>
							</td>
						</tr>
							<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_table.php';?>
<script type="text/javascript">
(function umd(root, name, factory)
	{
	  'use strict';
	  if ('function' === typeof define && define.amd) {
		define(name, ['jquery'], factory);
	  } else {
		root[name] = factory();
	  }
	}
	(this, 'CiuisInvoiceStats', function UMDFactory()
		{
		  'use strict';
		  var ReportOverview = ReportOverviewConstructor;
		  reportCircleGraph();
		  return ReportOverview;
		  function ReportOverviewConstructor(options) {
			var factory = {
				init: init
			  },
			  _elements = {
				$element: options.element
			  };
			init();
			return factory;
			function init() {
			  _elements.$element.append($(getTemplateString()));

			  $('.invoice-percent').percentCircle({
				width: 130,
				trackColor: '#ececec',
				barColor: '#22c39e',
				barWeight: 3,
				endPercent: 0.<?php echo $ofx ?>,
				fps: 60
			  });
			  $('.invoice-percent-2').percentCircle({
				width: 130,
				trackColor: '#ececec',
				barColor: '#ee7a6b',
				barWeight: 3,
				endPercent: 0.<?php echo $ofy ?>,
				fps: 60
			  });

			  $('.invoice-percent-3').percentCircle({
				width: 130,
				trackColor: '#ececec',
				barColor: '#808281',
				barWeight: 3,
				endPercent: 0.<?php echo $vgy ?>,
				fps: 60
			  });
			}
			function getTemplateString()
			{
			  return [
				'<div>',
				'<div class="row">',
				'<div class="col-md-12">',
				'<div style="border-top-left-radius: 10px;" class="ciuis-right-border-b1 ciuis-invoice-summaries-b1">',
				'<div class="box-header"><?php echo lang('totalinvoice'); ?></div>',
				'<div class="box-content">',
				'<div class="sentTotal">{{totalinvoicesayisi}}</div>'.replace(/{{totalinvoicesayisi}}/, options.data.totalinvoicesayisi),
				'</div>',
				'<div class="box-foot">',
				'<div class="sendTime box-foot-left"><?php echo lang('invoiceamount'); ?><br><span class="box-foot-stats"><strong><?php echo currency;?> <?php switch($settings['unitseparator']){case ',': echo number_format($fam, 2, ',', '.');break;case '.': echo number_format($fam, 2, '.', ',');break;}?></strong></span></div>'.replace(/{{date}}/, options.data.date),
				'</div>',
				'</div>',
				'<div class="ciuis-right-border-b1 ciuis-invoice-summaries-b1">',
				'<div class="box-header"><?php echo lang('paid'); ?></div>',
				'<div class="box-content invoice-percent">',
				'<div class="percentage">%<?php echo $ofx ?></div>',
				'</div>',
				'<div class="box-foot">',
				'<span class="arrow arrow-up"></span>',
				'<div class="box-foot-left"><?php echo lang('invoiceamount'); ?><br><span class="box-foot-stats"><strong><?php echo currency;?> <?php switch($settings['unitseparator']){case ',': echo number_format($ofv, 2, ',', '.');break;case '.': echo number_format($ofv, 2, '.', ',');break;}?></strong></span></div>',
				'<span class="arrow arrow-down"></span>',
				'<div class="box-foot-right"><br><span class="box-foot-stats""><strong><?php echo $otf ?></strong> (%<?php echo $ofx ?>)</span></div>',
				'</div>',
				'</div>',
				'<div class="ciuis-right-border-b1 ciuis-invoice-summaries-b1">',
				'<div class="box-header"><?php echo lang('unpaidinvoice'); ?></div>',
				'<div class="box-content invoice-percent-2">',
				'<div class="percentage">%<?php echo $ofy ?></div>',
				'</div>',
				'<div class="box-foot">',
				'<span class="arrow arrow-up"></span>',
				'<div class="box-foot-left"><?php echo lang('invoiceamount'); ?><br><span class="box-foot-stats"><strong><?php echo currency;?> <?php switch($settings['unitseparator']){case ',': echo number_format($oft, 2, ',', '.');break;case '.': echo number_format($oft, 2, '.', ',');break;}?></strong></span></div>'.replace(/{{OdenmeyenInvoicesAmount}}/, options.data.OdenmeyenInvoicesAmount),
				'<span class="arrow arrow-down"></span>',
				'<div class="box-foot-right"><br><span class="box-foot-stats"><strong><?php echo $tef ?></strong> (%<?php echo $ofy ?>)</span></div>',
				'</div>',
				'</div>',
				'<div style="border-top-right-radius: 10px;" class="ciuis-invoice-summaries-b1">',
				'<div class="box-header"><?php echo lang('overdue'); ?></div>',
				'<div class="box-content invoice-percent-3">',
				'<div class="percentage">%<?php echo $vgy ?></div>',
				'</div>',
				'<div class="box-foot">',
				'<span class="arrow arrow-up"></span>',
				'<div class="box-foot-left"><?php echo lang('invoiceamount'); ?><br><span class="box-foot-stats"><strong><?php echo currency;?> <?php switch($settings['unitseparator']){case ',': echo number_format($vgf, 2, ',', '.');break;case '.': echo number_format($vgf, 2, '.', ',');break;}?></strong></span></div>'.replace(/{{VadesiDolanInvoices}}/, options.data.VadesiDolanInvoices),
				'<div class="box-foot-right"><br><span class="box-foot-stats"><strong><?php echo $vdf ?></strong> (%<?php echo $vgy ?>)</span></div>',
				'</div>',
				'</div>'
			  ].join('');
			}
		  }
  		function reportCircleGraph() {
			$.fn.percentCircle = function pie(options) {
				var settings = $.extend({
					width: 130,
					trackColor: '#fff',
					barColor: '#fff',
					barWeight: 3,
					startPercent: 0,
					endPercent: 1,
					fps: 60
				}, options);
				this.css({
					width: settings.width,
					height: settings.width
				});
				var _this = this,
					canvasWidth = settings.width,
					canvasHeight = canvasWidth,
					id = $('canvas').length,
					canvasElement = $('<canvas id="' + id + '" width="' + canvasWidth + '" height="' + canvasHeight + '"></canvas>'),
					canvas = canvasElement.get(0).getContext('2d'),
					centerX = canvasWidth / 2,
					centerY = canvasHeight / 2,
					radius = settings.width / 2 - settings.barWeight / 2,
					counterClockwise = false,
					fps = 500 / settings.fps,
					update = 0.01;
				this.angle = settings.startPercent;
				this.drawInnerArc = function (startAngle, percentFilled, color) {
					var drawingArc = true;
					canvas.beginPath();
					canvas.arc(centerX, centerY, radius, (Math.PI / 180) * (startAngle * 360 - 90), (Math.PI / 180) * (percentFilled * 360 - 90), counterClockwise);
					canvas.strokeStyle = color;
					canvas.lineWidth = settings.barWeight - 2;
					canvas.stroke();
					drawingArc = false;
				};
				this.drawOuterArc = function (startAngle, percentFilled, color) {
					var drawingArc = true;
					canvas.beginPath();
					canvas.arc(centerX, centerY, radius, (Math.PI / 180) * (startAngle * 360 - 90), (Math.PI / 180) * (percentFilled * 360 - 90), counterClockwise);
					canvas.strokeStyle = color;
					canvas.lineWidth = settings.barWeight;
					canvas.lineCap = 'round';
					canvas.stroke();
					drawingArc = false;
				};
				this.fillChart = function (stop) {
					var loop = setInterval(function () {
						canvas.clearRect(0, 0, canvasWidth, canvasHeight);
						_this.drawInnerArc(0, 360, settings.trackColor);
						_this.drawOuterArc(settings.startPercent, _this.angle, settings.barColor);
						_this.angle += update;
						if (_this.angle > stop) {
							clearInterval(loop);
						}
					}, fps);
				};
				this.fillChart(settings.endPercent);
				this.append(canvasElement);
				return this;
			};
		}
		function getMockData() {
			return {
				totalinvoicesayisi: <?php echo $tfa ?>,
			};
		}
	}));
(function activateCiuisInvoiceStats($) {
	'use strict';
	var $el = $('.ciuis-invoice-summary');
	return new CiuisInvoiceStats({
		element: $el,
		data: {
			totalinvoicesayisi: <?php echo $tfa ?>,
		}
	});
}(jQuery));
</script>
<script>
$('.search-table-external').on( 'keyup click', function () {
   $('#table2').DataTable().search(
	   $('.search-table-external').val()
   ).draw();
} );	
</script>
