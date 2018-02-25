<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php';?>
<style type="text/css">
	.ciuis-tr{
		background-color:#F3F3F3 !important;
	}
	.ciuis-td{
		background-color:#fff;
		padding:10px;
		height:85%;
	}
	.dataTables_paginate{
		background-color:#f3f3f3;
	}
	.ciuis-body-datatable-footer{
		background-color:#f3f3f3;
		text-align:left;
		padding:0 !important;
	}

</style>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class='ciuis-customer-panel-xs'>
			<div class='ciuis-customer-panel-xs-icerik'>
				<header class='header_customer-xs'>
					<div class='main-header_customer-xs'>
						<div class='container-fluid'>
							<div class='top-header_customer-xs'>
								<div class='row'>
									<div class='col-md-6'>
										<ol class='breadcrumb-xs-customer'>
											<button data-target="#addcustomer" data-toggle="modal" style="margin-right:10px;" type="button" class="pull-left btn btn-warning"><i class="icon icon-left mdi mdi mdi-plus"></i><?php echo lang('newcustomer'); ?></button>
											<li>
												<a href='<?php echo base_url('customers') ?>'>
													<?php echo lang('customers'); ?>
												</a>
											</li>
											<li><a href='#'><i class="ion-ios-arrow-right"></i></a>
											</li>
											<li class='active'>
												<a href='#'>
													<?php echo $title ?>
												</a>
											</li>
										</ol>
									</div>
									<div style="padding-right: 20px;" class='col-md-5 hidden-xs'>
										<div class="searchcustomer-container">
											<div class="searchcustomer-box">
												<div class="searchcustomer-icon"><i class="ion-person-stalker"></i>
												</div>
												<input name="q" value="" x-webkit-speech='x-webkit-speech' class="searchcustomer-input" id="searchcustomer" type="text" placeholder="<?php echo lang('searchcustomer'); ?>"/>
												<i style="position: absolute; margin-top: 5px; right: 10px; font-size: 18px;" onclick="startDictation()" class="ion-ios-mic"></i>
												<ul class="searchcustomer-results" id="results"></ul>
											</div>
										</div>
									</div>

									<div class="col-md-1" style="margin-top: 5px;">
									<!-- Filter Area -->
									<div class="btn-group btn-hspace pull-right">
									  <button type="button" data-toggle="dropdown" class="dropdown-toggle btn-xl filter-button"><i class="icon-left ion-funnel"></i></button>
									  <ul class="dropdown-menu ciuis-body-connections pull-right ciuis-custom-filter">
										  <div class="filter">
										
											<div class="col-md-12">
												<h4><b><?php echo lang('customerfilter'); ?></b></h4>
											<select class="select2 "  onChange="window.location.href=this.value">
												<option selected="selected" value="?"><?php echo lang('selectcountry'); ?></option>
												<option value="?"><?php echo lang('all'); ?></option>
												<?php
foreach ($countries as $country) {
    echo '<option value="?filter-country=' . $country['isocode'] . '">' . $country['shortname'] . '</option>';
}
?>
											</select>
											</div>
											<hr style="margin-bottom: 8px; border-top: 1px solid #fdfdfd;">
										  </div>
							
									  </ul>
									</div>
									<!-- Filter Area -->
									</div>

								</div>
							</div>

							<div class="page-header_customer-xs hidden-xs">
								<div class="row">
									<div style="padding-right: 0px;" class="col-lg-10">
										<ul class="nav nav-pills">
											<li class="active" role="presentation">
												<a href="<?php echo base_url('customers/index') ?>"><span class="label"><?php echo lang('totalcustomer'); ?></span><?php echo $mst ?></a>
											</li>
											<li role="presentation">
												<a href="<?php echo base_url('customers/corporate') ?>">
													<div class="col-md-6"><i style="font-size:48px;" class="ion-ios-briefcase-outline"></i>
													</div>
													<div style="margin-top: 15px" class="col-md-6">
														<?php echo $tks ?>
													</div>
												</a>
											</li>
											<li role="presentation">
												<a href="<?php echo base_url('customers/individual') ?>">
													<div class="col-md-6"><i style="font-size:48px;" class="ion-ios-people-outline"></i>
													</div>
													<div style="margin-top: 15px" class="col-md-6">
														<?php echo $tbm ?>
													</div>
												</a>
											</li>
											<li role="presentation">
												<a href="<?php echo base_url('customers/newcustomers') ?>">
													<div class="col-md-6"><i style="font-size:48px;" class="ion-ios-personadd-outline"></i>
													</div>
													<div style="margin-top: 15px" class="col-md-6">
														<?php echo $yms ?>
													</div>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</header>
			</div>
		</div>

		<div class="panel panel-default panel-table">
			<div class="panel-body">
				<table style="width: 100%" id="table2" class="table table-striped table-hover table-fw-widget">
					<thead style="display: none">
						<tr>
							<th></th>
							<th class="hidden-xs"></th>
							<th class="text-right"></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($customers as $f) {
    ?>
						<tr data-filterable data-filter-country="<?php echo $f['isocode'] ?>" class="ciuis-254325232345 ciuis-tr" onclick="window.location='<?php echo base_url('customers/customer/' . $f['id'] . '') ?>'">
							<td class="md-ml-15">
								<div class="ciuis-34525452 ciuis-td"><span class="title uppercase"><a style="color: #383838;font-weight: 700;" href="<?php echo site_url('customers/customer/' . $f['id']); ?>"><?php if ($f['type'] == 0) {
        echo $f['companyname'];
    } else {
        echo $f['namesurname'];
    } ?></a></span><br>
									<span style="color:#757575;font-weight: 400;">
										<?php echo $f['companyemail']; ?>
									</span>
									<div class="hellociuislan">
										<?php
$this->db->select('risk');
    $this->db->from('customers');
    $this->db->where('(id = ' . $f['id'] . ') ');
    $riskstatus = $this->db->get();
    if ($riskstatus->row()->risk < 1) {
        echo '<label style="margin-bottom: 0px;margin-top: 5px;font-weight: 600;font-size: 10px;">' . lang('notrisky') . '</label>';
    } else {
        if ($riskstatus->row()->risk > 50) {
            echo '<label style="margin-bottom: 0px;margin-top: 5px;font-weight: 600;font-size: 10px;">' . lang('riskstatus') . '</label>
														  <div style="height: 7px" class="progress"><div style="width: ' . $riskstatus->row()->risk . '%" class="progress-bar progress-bar-striped active progress-bar-cok-riskli"></div></div>';
        } else {
            echo '<label style="margin-bottom: 0px;margin-top: 5px;font-weight: 600;font-size: 10px;">' . lang('riskstatus') . '</label>
														  <div style="height: 7px" class="progress"><div style="width: ' . $riskstatus->row()->risk . '%" class="progress-bar progress-bar-primary"></div></div>';
        }
    } ?>
									</div>
								</div>
							</td>
							<td class="hidden-xs">
								<div class="col-lg-12 col-md-12 ciuis-td">
									<blockquote><?php echo $f['companyaddress']; ?><br>
									<b><?php echo $f['companyphone']; ?></b>
									</blockquote>
									</div>
							</td>
							<td>
								<div class="text-center md-mr-15 ciuis-td">
									<?php $this->db->select_sum('total')->from('invoices')->where('(statusid = 3 AND customerid = ' . $f['id'] . ') ');
    $mbb = $this->db->get();
    if ($mbb->row()->total > 0) {
        echo '<strong style="font-size: 20px;"><span class="money-area">' . $mbb->row()->total . '</span></strong><br><span style="font-size:10px">' . lang('currentdebt') . '</span>';
    } else {
        echo '<strong style="font-size: 35px;"><i class="text-success ion-android-checkmark-circle"></i></strong><br><span class="text-success" style="font-size:10px">' . lang('nobalance') . '</span>';
    } ?>
								</div>
							</td>
						</tr>
						<?php
}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div id="addcustomer" tabindex="-1" role="dialog" class="modal fade colored-header colored-header-warning">
	<?php echo form_open('customers/add', array("class" => "form-vertical")); ?>
	<div style="width: 70%;" class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
				<h3 class="modal-title">
					<?php echo lang('newcustomertitle'); ?>
					<div class="col-md-3 xs-pt-10 pull-right">
							<div class="btn-group" data-toggle="buttons">
								<label class="btn btn-xl btn-warning btn-reverse active">
								<input name="type" type="radio" checked value="0"><?php echo lang('company') ?>
								</label>
								<label class="btn btn-xl btn-warning btn-reverse">
								<input name="type" type="radio" value="1"><?php echo lang('individual') ?>
								</label>
							</div>
					</div>
				</h3>
				<span>
					<?php echo lang('newcustomerdescription'); ?>
				</span>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 col-md-6 col-lg-4">

						<!-- <input type="text" name="namesurname" value="" class="form-control" id="namesurname"/ placeholder="Company id"> -->


						<div id="" class="form-group company">
						<label class=""><?php echo lang('updatecustomercompanyname') ?></label>
						<div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-collection-item-1"></i></span>
						<input type="text" name="companyname" value="<?php echo $this->input->post('companyname'); ?>" class="form-control" id="taxnumber"/>
						</div>
						</div>
						<div style="display: none" id="" class="form-group individual">
						<label class=""><?php echo lang('updatecustomerindividualname') ?></label>
						<div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-collection-item-1"></i></span>
						<input type="text" name="namesurname" value="<?php echo $this->input->post('namesurname'); ?>" class="form-control" id="namesurname"/ placeholder="Customer Name Surname">
						</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-lg-4">
						<div class="form-group">
							<label for="taxoffice">
								<?php echo lang('taxofficeedit'); ?>
							</label>
							<div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-local-atm"></i></span>
								<input type="text" name="taxoffice" value="<?php echo $this->input->post('taxoffice'); ?>" class="form-control" id="taxoffice"/>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-lg-4">
						<div id="" class="form-group company">
						<label for="taxnumber">
								<?php echo lang('taxnumberedit'); ?>
							</label>
							<div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-collection-item-1"></i></span>
								<input type="number" name="taxnumber" value="<?php echo $this->input->post('taxnumber'); ?>" class="form-control required" id="taxnumber" required/>
							</div>
						</div>
						<div style="display: none" id="" class="form-group individual">
						<label for="socialsecuritynumber">
								<?php echo lang('socialsecuritynumberedit'); ?>
							</label>
							<div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-collection-item-1"></i></span>
								<input type="text" name="socialsecuritynumber" value="<?php echo $this->input->post('socialsecuritynumber'); ?>" class="form-control" id="socialsecuritynumber"/ placeholder="<?php echo lang('socialsecuritynumberedit'); ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-lg-4">
						<div class="form-group">
							<label for="companyexecutive">
								<?php echo lang('companyexecutiveupdate'); ?>
							</label>
							<div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-account"></i></span>
								<input type="text" name="companyexecutive" value="<?php echo $this->input->post('companyexecutive'); ?>" class="form-control" id="companyexecutive"/>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-lg-4">
						<div class="form-group">
							<label for="zipcode">
								<?php echo lang('zipcode'); ?>
							</label>
							<div class="input-group xs-pt-10"><span class="input-group-addon"><i class="ion-paper-airplane"></i></span>
								<input type="text" name="zipcode" value="<?php echo $this->input->post('zipcode'); ?>" class="form-control" id="zipcode"/>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-lg-4">
						<div class="form-group">
							<label for="companyphone">
								<?php echo lang('customerphoneupdate'); ?>
							</label>
							<div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-phone"></i></span>
								<input type="text" name="companyphone" value="<?php echo $this->input->post('companyphone'); ?>" class="form-control" id="companyphone"/>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-lg-4">
						<div class="form-group">
							<label for="companyfax">
								<?php echo lang('customerfaxupdate'); ?>
							</label>
							<div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-scanner"></i></span>
								<input type="text" name="companyfax" value="<?php echo $this->input->post('companyfax'); ?>" class="form-control" id="companyfax"/>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-lg-4">
						<div class="form-group">
							<label for="companyemail">
								<?php echo lang('emailedit'); ?>
							</label>
							<div class="input-group xs-pt-10"><span class="input-group-addon">@</span>
								<input required type="text" name="companyemail" value="<?php echo $this->input->post('companyemail'); ?>" class="form-control" id="companyemail"/>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-lg-4">
						<div class="form-group">
							<label for="companyweb">
								<?php echo lang('customerwebupdate'); ?>
							</label>
							<div class="input-group xs-pt-10"><span class="input-group-addon"><i class="mdi mdi-http"></i></span>
								<input type="text" name="companyweb" value="<?php echo $this->input->post('companyweb'); ?>" class="form-control" id="companyweb"/>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="companyaddress">
								<?php echo lang('customeraddressupdate'); ?>
							</label>
							<textarea name="companyaddress" class="form-control"><?php echo $this->input->post('companyaddress'); ?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-lg-3">
						<div class="form-group">
							<label for="companyemail">
								<?php echo lang('customercountryupdate'); ?>
							</label>
							<select required name="countryid" class="select2 required">
								<option value=""><?php echo lang('selectcountry'); ?></option>
								<?php
foreach ($countries as $country) {
        $selected = ($country['id'] == $this->input->post('countryid')) ? ' selected="selected"' : null;
        echo '<option value="' . $country['id'] . '" ' . $selected . '>' . $country['shortname'] . '</option>';
    }
?>
							</select>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-lg-3">
					<div class="form-group">
							<label for="customerstate">
								<?php echo lang('customerstateupdate'); ?>
							</label>
							<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-http"></i></span>
								<input type="text" name="customerstate" value="<?php echo $this->input->post('customerstate'); ?>" class="form-control" id="customerstate"/>
							</div>
						</div></div>
					<div class="col-xs-12 col-md-6 col-lg-3">
					<div class="form-group">
							<label for="customercity">
								<?php echo lang('customercityupdate'); ?>
							</label>
							<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-http"></i></span>
								<input type="text" name="customercity" value="<?php echo $this->input->post('customercity'); ?>" class="form-control" id="customercity"/>
							</div>
						</div></div>
				<div class="col-xs-12 col-md-6 col-lg-3">
					<div class="form-group">
							<label for="customertown">
								<?php echo lang('customertownupdate'); ?>
							</label>
							<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-http"></i></span>
								<input type="text" name="customertown" value="<?php echo $this->input->post('customertown'); ?>" class="form-control" id="customertown"/>
							</div>
						</div></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default modal-close">
					<?php echo lang('cancel'); ?>
				</button>
				<button type="submit" class="btn btn-default modal-close">
					<?php echo lang('add'); ?>
				</button>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>
	<?php //include_once dirname(dirname(__FILE__)) . '/inc/sidebar.php';?>
	<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_table.php';?>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    $('form').each(function() {  // attach to all form elements on page
        $(this).validate({       // initialize plugin on each form
            // global options for plugin
        });
    });

});
$(document).ready(function () {
"use strict";
$('input[name=type]').change(function () {
	if (!$(this).is(':checked')) {
			return;
		}
	if ($(this).val() === '0') {
		$('.company').show();
		$('.individual').hide();
	} else if ($(this).val() === '1') {
		$('.company').hide();
		$('.individual').show();
	}
});
});
</script>
<script>
	( function () {
		var displayResults, findAll, maxResults, names, resultsOutput, searchcustomerInput;
		names = [
			<?php foreach ($customers as $f) {
    ?>
			"<a href='<?php echo base_url('customers/customer/' . $f['id'] . ''); ?>'><?php if ($f['type'] == 0) {
        echo $f['companyname'];
    } else {
        echo $f['namesurname'];
    } ?></a>",
			<?php
}?>
			""
		];
		findAll = ( function ( _this ) {
			return function ( wordList, collection ) {
				return collection.filter( function ( word ) {
					word = word.toLowerCase();
					return wordList.some( function ( name ) {
						return ~word.indexOf( name );
					} );
				} );
			};
		} )( this );
		displayResults = function ( resultsEl, wordList ) {
			return resultsEl.innerHTML = ( wordList.map( function ( name ) {
				return '<li>' + name + '</li>';
			} ) ).join( '' );
		};
		searchcustomerInput = document.getElementById( 'searchcustomer' );
		resultsOutput = document.getElementById( 'results' );
		maxResults = 20;
		searchcustomerInput.addEventListener( 'keyup', ( function ( _this ) {
			return function ( e ) {
				var suggested, value;
				value = searchcustomerInput.value.toLowerCase().split( ' ' );
				suggested = ( value[ 0 ].length ? findAll( value, names ) : [] );
				return displayResults( resultsOutput, suggested );
			};
		} )( this ) );
	} ).call( this );
	function startDictation() {
		if ( window.hasOwnProperty( 'webkitSpeechRecognition' ) ) {
			var recognition = new webkitSpeechRecognition();
			recognition.continuous = false;
			recognition.interimResults = false;
			recognition.lang = "<?php echo lang('language_datetime') ?>";
			recognition.start();
			recognition.onresult = function ( e ) {
				document.getElementById( 'searchcustomer' ).value = e.results[ 0 ][ 0 ].transcript;
				recognition.stop();
				$('.searchcustomer-input').value = e.results[ 0 ][ 0 ].transcript;
				$('.searchcustomer-input').focus();
				$('.searchcustomer-input').keyup();

			};
			recognition.onerror = function ( e ) {
				recognition.stop();
			}
		}
	}
</script>
<script>
		$('#searchcustomer').on( 'keyup click', function () {
		   $('#table2').DataTable().search(
			   $('#searchcustomer').val()
		   ).draw();
		} );
	</script>