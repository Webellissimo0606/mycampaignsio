<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php';?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="account-container-ciuis-65343256347">
			<section class="accounts-information-ciuis-1881-sonuzadek">
				<svg class="icon" width="40px" height="30px">
					<use width="25px" xlink:href="#bell"/>
				</svg>
				<section class="account-information-cover-234">
					<h3 class="information">
						<?php echo lang('accountswelcome'); ?>
					</h3>
					<a data-target="#newaccount" data-toggle="modal" class="reconnect-cta">
						<b><?php echo lang('newaccount'); ?></b>
					</a>
				</section>
			</section>
			<section class="ciuis-accounts">
				<?php foreach ($accounts as $account) {
    ?>
				<a href="<?php echo base_url('accounts/account/' . $account['id'] . '') ?>" class="huppur ciuis-account checking">
					<svg class="icon" width="23px" height="30px">
						<use width="23px" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#<?php if ($account['type'] == 0) {
        echo 'safe';
    } else {
        echo 'card';
    } ?>"></use>
					</svg>
					<div class="ciuis-account-information">
						<h4 class="ciuis-account-type"><?php echo $account['name']; ?></h4>
						<p class="ciuis-account-detail"><?php if ($account['status'] == 0) {
        echo '' . lang('accuntactive') . '';
    } else {
        echo '' . lang('accuntnotactive') . '';
    } ?></p>
					</div>
					<label class="ciuis-account-temprorary">
					<?php $this->db->select_sum('amount')->from('payments')->where('(accountid = ' . $account['id'] . ' and transactiontype = 0)');
    $mbb = $this->db->get();
    if ($mbb->row()->amount > 0) {
        echo '<p class="money-area">' . $mbb->row()->amount . '</p>';
    } else {
        echo currency . '0.00';
    } ?></label>
				</a>

				<?php
}?>
				<span class="pull-right">
					<p class="money-area"><?php echo $tht; ?></p>
				</span>
			</section>
		</div>
		<div class="ciuis-account-attr"></div>
	</div>
	<div id="newaccount" tabindex="-1" role="dialog" class="modal fade colored-header colored-header-warning">
		<?php echo form_open('accounts/add', array("class" => "form-vertical","id"=>"addAccount")); ?>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><span class="mdi mdi-close"></span></button>
					<h3 class="modal-title text-modal">
						<?php echo lang('newaccount'); ?>
						<div class="col-md-4 xs-pt-10 pull-right">
							<div class="btn-group" data-toggle="buttons">
								<label class="btn btn-md btn-warning btn-reverse active">
								<input name="type" type="radio" checked value="0"><?php echo lang('cash') ?>
								</label>
								<label class="btn btn-md btn-warning btn-reverse">
								<input name="type" type="radio" value="1"><?php echo lang('bank') ?>
								</label>
							</div>
						</div>
					</h3>
					<span class="text-modal-detail">
						<?php echo lang('newaccountdetail'); ?>
					</span>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label class=""><?php echo lang('accountname') ?></label>
								<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-money-box"></i></span>
									<input required type="text" name="name" value="<?php echo $this->input->post('name'); ?>" class="form-control" id="name"/ placeholder="<?php echo lang('accountname') ?>">
								</div>
							</div>
							<div style="display:none" class="bank">
								<div class="form-group col-md-6 xs-pt-10 xs-pb-10 xs-pl-0">
									<label class=""><?php echo lang('bankname') ?></label>
									<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-balance"></i></span>
										<input type="text" name="bankname" value="<?php echo $this->input->post('bankname'); ?>" class="form-control" id="bankname"/ placeholder="Global Bank">
									</div>
								</div>
								<div class="form-group col-md-6 xs-pt-10 xs-pb-10 xs-pr-0 xs-pl-0">
									<label class=""><?php echo lang('branchbank') ?></label>
									<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-city-alt"></i></span>
										<input type="text" name="branchbank" value="<?php echo $this->input->post('branchbank'); ?>" class="form-control" id="branchbank"/ placeholder="Eg: Paris">
									</div>
								</div>
								<div class="form-group xs-pb-10">
									<label class=""><?php echo lang('accountnumber') ?></label>
									<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-collection-item-1"></i></span>
										<input type="text" required name="account" value="<?php echo $this->input->post('account'); ?>" class="form-control" id="account"/ placeholder="0000071219812874">
									</div>
								</div>
								<div class="form-group xs-pb-10">
									<label class=""><?php echo lang('iban') ?></label>
									<div class="input-group"><span class="input-group-addon"><i class="mdi mdi-card"></i></span>
										<input type="text" name="iban" value="<?php echo $this->input->post('iban'); ?>" class="form-control" id="iban"/ placeholder="GB29 RBOS 6016 1331 9268 19">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="switch-button switch-button-success pull-left">
						<input type="checkbox" checked value="0" name="status" id="swt6"><span>
							<label for="swt6"></label></span>
					</div>
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
	
	<?php include_once dirname(dirname(__FILE__)) . '/inc/footer.php';?>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
 $('#addAccount').validate({
	 ignore:'',
	 rules:{
		 account:{
			 required:function(){
				 return $("input[name='type']:checked").val()==1;
			 },
			 number:true
		 }
	 }
        });

});		$( document ).ready( function () {
			"use strict";
			$( 'input[name=type]' ).change( function () {
				if ( !$( this ).is( ':checked' ) ) {
					return;
				}
				if ( $( this ).val() === '0' ) {
					$( '.bank' ).hide();
				} else if ( $( this ).val() === '1' ) {
					$( '.bank' ).show();
				}
			} );
		} );
	</script>
	<svg id="svg-icons" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px">
		<symbol id="done" viewBox="0 0 400 400" style="enable-background:new 0 0 400 400;" xml:space="preserve">
			<g>
				<g>
					<path d="M199.996,0C89.713,0,0,89.72,0,200s89.713,200,199.996,200S400,310.28,400,200S310.279,0,199.996,0z M199.996,373.77
			C104.18,373.77,26.23,295.816,26.23,200c0-95.817,77.949-173.769,173.766-173.769c95.817,0,173.771,77.953,173.771,173.769
			C373.768,295.816,295.812,373.77,199.996,373.77z"></path>
					<path d="M272.406,134.526L169.275,237.652l-41.689-41.68c-5.123-5.117-13.422-5.12-18.545,0.003
			c-5.125,5.125-5.125,13.425,0,18.548l50.963,50.955c2.561,2.558,5.916,3.838,9.271,3.838s6.719-1.28,9.279-3.842
			c0.008-0.011,0.014-0.022,0.027-0.035L290.95,153.071c5.125-5.12,5.125-13.426,0-18.546
			C285.828,129.402,277.523,129.402,272.406,134.526z"></path>
				</g>
			</g>
		</symbol>
		<symbol id="bell" viewBox="0 0 649.897 649.897">
			<g>
				<g>
					<g>
						<path d="M510.042,231.101c-4.376,0-8.229-3.113-9.061-7.52c-15.225-81.146-77.017-146.636-157.423-166.822
				c-4.962-1.232-7.951-6.256-6.718-11.218c1.264-4.931,6.163-7.859,11.218-6.719c87.371,21.943,154.557,93.104,171.105,181.338
				c0.925,5.023-2.373,9.862-7.396,10.817C511.182,231.07,510.627,231.101,510.042,231.101z"></path>
						<path d="M571.679,531.153H79.317c-38.739,0-52.299-16.827-56.953-26.812c-4.53-9.77-8.568-30.388,15.348-58.587
				c28.292-33.407,52.207-96.031,52.146-136.773c-0.031-44.102,0.709-94.274,1.695-111.811l3.637-16.95
				c3.976-17.813,10.201-35.226,18.491-51.714c24.101-48.016,64.689-86.601,114.276-108.636
				c62.069-27.521,134.031-26.443,195.669,3.174c56.307,27.059,100.038,75.814,119.978,133.753
				c4.283,12.389,7.488,25.333,9.523,38.493l1.633,10.448c0.617,13.437,0.986,58.802,0.986,102.257
				c0,40.125,24.963,102.502,54.488,136.28c25.795,29.494,22.004,50.42,17.504,60.344
				C623.177,514.51,609.925,531.153,571.679,531.153z M322.354,24.553c-29.278,0-57.662,5.979-84.382,17.813
				c-44.379,19.755-80.684,54.272-102.257,97.233c-7.366,14.669-12.913,30.141-16.457,46.012l-3.359,14.947
				c-0.678,15.132-1.418,64.689-1.356,108.39c0.031,46.413-25.98,114.923-58.001,152.738c-10.817,12.759-15.225,24.84-11.773,32.268
				c3.637,7.858,16.581,12.512,34.579,12.512h492.331c17.598,0,30.141-4.499,33.561-12.081c3.422-7.55-1.416-19.97-13.004-33.223
				c-33.994-38.862-61.145-107.28-61.145-153.199c0-43.023-0.37-87.988-0.832-99.884l-1.479-9.061
				c-1.788-11.68-4.623-23.207-8.444-34.209c-17.813-51.775-56.923-95.353-107.342-119.577
				C384.363,31.519,353.882,24.553,322.354,24.553z"></path>
					</g>
					<path d="M325.498,649.897c-54.303,0-98.466-44.193-98.466-98.466c0-6.811,5.516-12.327,12.327-12.327h172.247
			c6.812,0,12.328,5.547,12.328,12.327C423.964,605.734,379.77,649.897,325.498,649.897z M252.735,563.759
			c5.886,34.855,36.274,61.452,72.763,61.452c36.49,0,66.877-26.597,72.764-61.452H252.735z"></path>
				</g>
			</g>
		</symbol>
		<symbol id="safe" viewBox="0 0 442 442">
			<g>
				<path d="M432,0H10C4.477,0,0,4.478,0,10v422c0,5.522,4.477,10,10,10h422c5.523,0,10-4.478,10-10V10C442,4.478,437.523,0,432,0z
		 M422,422H20V20h402V422z"></path>
				<path d="M273.901,148.188c-0.202-0.147-0.411-0.275-0.62-0.404C258.534,137.224,240.481,131,221,131s-37.534,6.224-52.281,16.784
		c-0.209,0.129-0.418,0.258-0.62,0.404c-0.22,0.16-0.424,0.332-0.627,0.506C145.359,165.108,131,191.408,131,221
		c0,49.626,40.374,90,90,90s90-40.374,90-90c0-29.592-14.359-55.892-36.472-72.306C274.325,148.521,274.121,148.348,273.901,148.188
		z M221,251c-16.542,0-30-13.458-30-30s13.458-30,30-30s30,13.458,30,30S237.542,251,221,251z M253.618,159.092l-11.923,16.411
		C235.384,172.62,228.38,171,221,171s-14.384,1.62-20.695,4.502l-11.923-16.411C198.129,153.935,209.226,151,221,151
		S243.871,153.935,253.618,159.092z M172.216,170.868l11.914,16.399C175.981,196.166,171,208.011,171,221
		c0,1.903,0.117,3.777,0.325,5.625l-19.293,6.269C151.366,229.026,151,225.056,151,221C151,201.359,159.143,183.593,172.216,170.868
		z M158.218,251.914l19.303-6.272c7.024,12.344,19.14,21.429,33.479,24.352v20.281C187.799,286.94,168.243,272.191,158.218,251.914z
		 M231,290.274v-20.281c14.339-2.923,26.456-12.008,33.479-24.352l19.303,6.272C273.757,272.191,254.201,286.94,231,290.274z
		 M291,221c0,4.056-0.366,8.026-1.032,11.895l-19.293-6.269c0.208-1.848,0.325-3.723,0.325-5.625
		c0-12.989-4.981-24.834-13.13-33.733l11.914-16.399C282.857,183.593,291,201.359,291,221z"></path>
				<path d="M50,349.997V382c0,5.522,4.477,10,10,10h322c5.523,0,10-4.478,10-10V154.111c0-5.522-4.477-10-10-10s-10,4.478-10,10V372
		H70v-22.003c9.387-3.926,16-13.202,16-23.997v-50c0-10.795-6.613-20.071-16-23.997v-62.005c9.387-3.926,16-13.203,16-23.997v-50
		c0-10.795-6.613-20.071-16-23.997V70h302v50c0,5.522,4.477,10,10,10s10-4.478,10-10V60c0-5.522-4.477-10-10-10H60
		c-5.523,0-10,4.478-10,10v32.003C40.613,95.929,34,105.205,34,116v50c0,10.795,6.613,20.071,16,23.997v62.005
		c-9.387,3.926-16,13.203-16,23.997v50C34,336.795,40.613,346.071,50,349.997z M66,276v50c0,3.309-2.691,6-6,6s-6-2.691-6-6v-50
		c0-3.309,2.691-6,6-6S66,272.691,66,276z M54,166v-50c0-3.309,2.691-6,6-6s6,2.691,6,6v50c0,3.309-2.691,6-6,6S54,169.309,54,166z"></path>
			</g>
		</symbol>
		<symbol id="card" viewBox="0 0 463 463">
			<g>
				<g>
					<g>
						<path d="M423.5,88h-384C17.72,88,0,105.72,0,127.5v208C0,357.28,17.72,375,39.5,375h384c21.78,0,39.5-17.72,39.5-39.5v-208
				C463,105.72,445.28,88,423.5,88z M448,335.5c0,13.51-10.991,24.5-24.5,24.5h-384C25.991,360,15,349.01,15,335.5v-208
				c0-13.51,10.991-24.5,24.5-24.5h384c13.509,0,24.5,10.99,24.5,24.5V335.5z"></path>
						<path d="M119.5,152h-48C58.542,152,48,162.542,48,175.5v32c0,12.958,10.542,23.5,23.5,23.5h48c12.958,0,23.5-10.542,23.5-23.5
				v-32C143,162.542,132.458,152,119.5,152z M80,216h-8.5c-4.687,0-8.5-3.813-8.5-8.5v-32c0-4.687,3.813-8.5,8.5-8.5H80V216z
				 M128,207.5c0,4.687-3.813,8.5-8.5,8.5H95v-17h33V207.5z M128,184H95v-17h24.5c4.687,0,8.5,3.813,8.5,8.5V184z"></path>
						<path d="M111.5,280h-48c-8.547,0-15.5,6.953-15.5,15.5v16c0,8.547,6.953,15.5,15.5,15.5h48c8.547,0,15.5-6.953,15.5-15.5v-16
				C127,286.953,120.047,280,111.5,280z M112,311.5c0,0.275-0.224,0.5-0.5,0.5h-48c-0.276,0-0.5-0.225-0.5-0.5v-16
				c0-0.275,0.224-0.5,0.5-0.5h48c0.276,0,0.5,0.225,0.5,0.5V311.5z"></path>
						<path d="M207.5,280h-48c-8.547,0-15.5,6.953-15.5,15.5v16c0,8.547,6.953,15.5,15.5,15.5h48c8.547,0,15.5-6.953,15.5-15.5v-16
				C223,286.953,216.047,280,207.5,280z M208,311.5c0,0.275-0.224,0.5-0.5,0.5h-48c-0.276,0-0.5-0.225-0.5-0.5v-16
				c0-0.275,0.224-0.5,0.5-0.5h48c0.276,0,0.5,0.225,0.5,0.5V311.5z"></path>
						<path d="M303.5,280h-48c-8.547,0-15.5,6.953-15.5,15.5v16c0,8.547,6.953,15.5,15.5,15.5h48c8.547,0,15.5-6.953,15.5-15.5v-16
				C319,286.953,312.047,280,303.5,280z M304,311.5c0,0.275-0.224,0.5-0.5,0.5h-48c-0.276,0-0.5-0.225-0.5-0.5v-16
				c0-0.275,0.224-0.5,0.5-0.5h48c0.276,0,0.5,0.225,0.5,0.5V311.5z"></path>
						<path d="M399.5,280h-48c-8.547,0-15.5,6.953-15.5,15.5v16c0,8.547,6.953,15.5,15.5,15.5h48c8.547,0,15.5-6.953,15.5-15.5v-16
				C415,286.953,408.047,280,399.5,280z M400,311.5c0,0.275-0.224,0.5-0.5,0.5h-48c-0.276,0-0.5-0.225-0.5-0.5v-16
				c0-0.275,0.224-0.5,0.5-0.5h48c0.276,0,0.5,0.225,0.5,0.5V311.5z"></path>
						<path d="M375.5,152c-8.71,0-17.135,2.953-24,8.274c-6.865-5.321-15.29-8.274-24-8.274c-21.78,0-39.5,17.72-39.5,39.5
				c0,21.78,17.72,39.5,39.5,39.5c8.71,0,17.135-2.953,24-8.274c6.865,5.321,15.29,8.274,24,8.274c21.78,0,39.5-17.72,39.5-39.5
				C415,169.72,397.28,152,375.5,152z M375.5,216c-7.06,0-13.577-3.009-18.354-8.473c-1.424-1.629-3.482-2.563-5.646-2.563
				s-4.222,0.935-5.646,2.563C341.077,212.991,334.56,216,327.5,216c-13.509,0-24.5-10.99-24.5-24.5s10.991-24.5,24.5-24.5
				c7.06,0,13.577,3.009,18.354,8.473c1.424,1.629,3.482,2.563,5.646,2.563s4.222-0.935,5.646-2.563
				C361.923,170.009,368.44,167,375.5,167c13.509,0,24.5,10.99,24.5,24.5S389.009,216,375.5,216z"></path>
					</g>
				</g>
			</g>
		</symbol>
	</svg>