<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php'; ?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="row">
      <div style="border-radius: 10px" class="panel panel-default"> <?php echo form_open_multipart('staff/add/',array("class"=>"form-horizontal")); ?>
        <div class="col-sm-6">
          <div style="border-radius: 10px" class="panel panel-default">
            <div class="panel-heading panel-heading-divider"><b><?php echo lang('staffinfotitle');?></b><span class="panel-subtitle"><?php echo lang('staffinfodescription');?></span></div>
            <div class="panel-body">
              <div class="col-md-12">
                <div class="form-group">
                  <label><?php echo lang('staffnamesurname');?></label>
                  <div class="input-group"><span class="input-group-addon"><i class="mdi mdi-account-o"></i></span>
                    <input name="staffname" value="<?php $this->input->post('staffname') ?>" type="text" placeholder="Name Surname" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label for="birthday"><?php echo lang('staffbirthday');?></label>
                  <div data-min-view="2" data-date-format="yyyy-mm-dd" class="input-group date datetimepicker"> <span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
                    <input name="birthday" required type='input' name="birthday" value="<?php $this->input->post('birthday') ?>" class="form-control" id="birthday" />
                  </div>
                </div>
                <div class="form-group">
                  <label><?php echo lang('staffphone');?></label>
                  <div class="input-group "><span class="input-group-addon"><i class="mdi mdi-phone"></i></span>
                    <input name="phone" type="text" value="<?php $this->input->post('phone') ?>" placeholder="+90 (532) 000 0000" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label><?php echo lang('staffaddress');?></label>
                  <textarea name="address" class="form-control" placeholder="Address"><?php $this->input->post('address') ?></textarea>
                </div>
                <div class="form-group">
					<label for="departmentid"><?php echo lang('department');?></label>
					<select required name="departmentid" class="form-control select2">
						<?php
						foreach ( $departments as $department ) {
							$selected = ( $department[ 'id' ] == $this->input->post( 'departmentid' ) ) ? ' selected="selected"' : null;
							echo '<option value="' . $department[ 'id' ] . '" ' . $selected . '>' . $department[ 'name' ] . '</option>';
						}
						?>
					</select>
				</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div style="border-radius: 10px" class="panel panel-default">
            <div class="panel-heading panel-heading-divider"><b><?php echo lang('staffdetailstitle');?></b><span class="panel-subtitle"><?php echo lang('staffdetailsdescription');?></span></div>
            <div class="panel-body">
              <div class="col-md-12">
                
                <div class="form-group">
                  <label><?php echo lang('staffemail');?></label>
                  <div class="input-group"><span class="input-group-addon">@</span>
                    <input value="<?php $this->input->post('email') ?>" name="email" type="text" placeholder="johndoe@example.com" class="form-control">
                  </div>
                </div>
                <div class="form-group">
						<label for="ad">
							<b><?php echo lang('password');?></b>
						</label>
						<p class="xs-mb-5"><?php echo lang('loginpassword');?><a href="javascript:;" data-toggle="popover" data-trigger="hover" title="<?php echo lang('information')?>" data-content="<?php echo lang('staffhover')?>" data-placement="top"><b> ?</b></a>
						</p>
						<div class="input-group ">
							<input name="password" type="text" class="form-control " rel="gp" data-size="9" id="nc" data-character-set="a-z,A-Z,0-9,#">
							<span class="input-group-btn"><button type="button" class="btn btn-default getNewPass"><span class="ion-refresh"></span>
							</button>
							</span>
						</div>
					</div>
                <div class="form-group">
					<label for="language"><?php echo lang('languages');?></label>
					<select required name="language" class="form-control select2">
						<?php
						foreach ( $languages as $language ) {
							$selected = ( $language[ 'name' ] == $this->input->post( 'language' ) ) ? ' selected="selected"' : null;
							echo '<option value="' . $language[ 'foldername' ] . '" ' . $selected . '>' . $language[ 'name' ] . '</option>';
						}
						?>
					</select>
				</div>
                <div class="form-group">
						  <div class="file-upload">
							<div class="file-select">
							  <div class="file-select-button" id="fileName"><span class="mdi mdi-accounts-list-alt"></span> <?php echo lang('image');?></div>
							  <div class="file-select-name" id="noFile"><?php echo lang('nofile');?></div>
							  <input type="file" name="staffavatar" id="chooseFile">
							</div>
						  </div>
						</div>'
                <div class="row xs-pt-15">
                  <div class="col-xs-3">
                    <div class="ciuis-body-checkbox has-success">
						<input name="admin" class="success-check" id="admin" type="checkbox" value="1">
						<label for="admin"><?php echo lang('staffadmin');?></label>
					</div>
                  </div>
                  <div class="col-xs-3">
                    <div class="ciuis-body-checkbox has-primary">
						<input name="staffmember" class="primary-check" id="staffmember" type="checkbox" value="1">
						<label for="staffmember"><?php echo lang('staffisstaff');?></label>
					</div>
                  </div>
                  <div class="col-xs-3">
                    <div class="ciuis-body-checkbox has-danger">
						<input name="inactive" class="danger-check" id="inactive" type="checkbox" value="1">
						<label for="inactive"><?php echo lang('staffinactive');?></label>
					</div>
                  </div>
                  <div class="col-xs-3">
                    <p class="text-right">
                      <button type="submit" class="btn btn-space btn-default"><?php echo lang('save');?></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php echo form_close(); ?> </div>
      </div>
    </div>		
	</div>
	<?php include_once dirname(dirname(__FILE__)) . '/inc/sidebar.php'; ?>
<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_post.php'; ?>
<script type="text/javascript">
	// Generate a password string
	function randString( id ) {
		var dataSet = $( id ).attr( 'data-character-set' ).split( ',' );
		var possible = '';
		if ( $.inArray( 'a-z', dataSet ) >= 0 ) {
			possible += 'abcdefghijklmnopqrstuvwxyz';
		}
		if ( $.inArray( 'A-Z', dataSet ) >= 0 ) {
			possible += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		if ( $.inArray( '0-9', dataSet ) >= 0 ) {
			possible += '0123456789';
		}
		if ( $.inArray( '#', dataSet ) >= 0 ) {
			possible += '![]{}()%&*$#^<>~@|';
		}
		var text = '';
		for ( var i = 0; i < $( id ).attr( 'data-size' ); i++ ) {
			text += possible.charAt( Math.floor( Math.random() * possible.length ) );
		}
		return text;
	}

	// Create a new password on page load
	$( 'input[rel="gp"]' ).each( function () {
		$( this ).val( randString( $( this ) ) );
	} );

	// Create a new password
	$( ".getNewPass" ).click( function () {
		var field = $( this ).closest( 'div' ).find( 'input[rel="gp"]' );
		field.val( randString( field ) );
	} );

	// Auto Select Pass On Focus
	$( 'input[rel="gp"]' ).on( "click", function () {
		$( this ).select();
	} );
</script>
<script type="text/javascript">
$('#chooseFile').bind('change', function () {
  var filename = $("#chooseFile").val();
  if (/^\s*$/.test(filename)) {
    $(".file-upload").removeClass('active');
    $("#noFile").text("No file chosen..."); 
  }
  else {
    $(".file-upload").addClass('active');
    $("#noFile").text(filename.replace("C:\\fakepath\\", "")); 
  }
});	
</script>