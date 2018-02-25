<?php include_once dirname(dirname(__FILE__)) . '/inc/header.php';?>
<div class="ciuis-body-content">
	<div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
		<div class="row">
      <div style="border-radius: 10px" class="panel panel-default"> <?php echo form_open_multipart('staff/edit/' . $staff['id'], array("class" => "form-horizontal")); ?>
        <div class="col-sm-6">
          <div style="border-radius: 10px" class="panel panel-default">
            <div class="panel-heading panel-heading-divider"><b><?php echo lang('staffinfotitle'); ?></b><span class="panel-subtitle"><?php echo lang('staffinfodescription'); ?></span></div>
            <div class="panel-body">
              <div class="col-md-12">
                <div class="form-group">
                  <label><?php echo lang('staffnamesurname'); ?></label>
                  <div class="input-group"><span class="input-group-addon"><i class="mdi mdi-account-o"></i></span>
                    <input name="staffname" value="<?php echo ($this->input->post('staffname') ? $this->input->post('staffname') : $staff['staffname']); ?>" type="text" placeholder="Name Surname" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label for="birthday"><?php echo lang('staffbirthday'); ?></label>
                  <div data-min-view="2" data-date-format="yyyy-mm-dd" class="input-group date datetimepicker"> <span class="input-group-addon btn btn-default"><i class="icon-th mdi mdi-calendar"></i></span>
                    <input name="birthday" required type='input' name="birthday" value="<?php echo ($this->input->post('birthday') ? $this->input->post('birthday') : $staff['birthday']); ?>" class="form-control" id="birthday" />
                  </div>
                </div>
                <div class="form-group">
                  <label><?php echo lang('staffphone'); ?></label>
                  <div class="input-group "><span class="input-group-addon"><i class="mdi mdi-phone"></i></span>
                    <input name="phone" type="text" value="<?php echo ($this->input->post('phone') ? $this->input->post('phone') : $staff['phone']); ?>" placeholder="+90 (532) 000 0000" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <label><?php echo lang('staffaddress'); ?></label>
                  <textarea name="address" class="form-control" placeholder="Address"><?php echo ($this->input->post('address') ? $this->input->post('address') : $staff['address']); ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div style="border-radius: 10px" class="panel panel-default">
            <div class="panel-heading panel-heading-divider"><b><?php echo lang('staffdetailstitle'); ?>
            <button type="button" data-toggle="modal" data-target="#changepassword" class="btn btn-default pull-right">Change Password</button>
            </b><span class="panel-subtitle"><?php echo lang('staffdetailsdescription'); ?></span></div>
            <div class="panel-body">
              <div class="col-md-12">
                <div class="form-group">
					<label for="departmentid">Departments</label>
					<select required name="departmentid" class="form-control select2">
						<option value="<?php echo $staff['departmentid']; ?>">Active : <?php echo $staff['department']; ?></option>
						<?php
foreach ($departments as $department) {
	$selected = ($department['id'] == $this->input->post('departmentid')) ? ' selected="selected"' : null;
	echo '<option value="' . $department['id'] . '" ' . $selected . '>' . $department['name'] . '</option>';
}
?>
					</select>
				</div>
                <div class="form-group">
                  <label><?php echo lang('staffemail'); ?></label>
                  <div class="input-group"><span class="input-group-addon">@</span>
                    <input value="<?php echo ($this->input->post('email') ? $this->input->post('email') : $staff['email']); ?>" name="email" type="text" placeholder="johndoe@example.com" class="form-control">
                  </div>
                </div>
                <div class="form-group">
					<label for="language"><?php echo lang('languages') ?></label>
					<select required name="language" class="form-control select2">
						<option value="<?php echo $staff['foldername']; ?>">Active : <?php echo $staff['stafflanguage']; ?></option>
						<?php
foreach ($languages as $language) {
	$selected = ($language['name'] == $this->input->post('language')) ? ' selected="selected"' : null;
	echo '<option value="' . $language['foldername'] . '" ' . $selected . '>' . $language['name'] . '</option>';
}
?>
					</select>
				</div>
                <?php
if ($staff['staffavatar'] == NULL || $staff['staffavatar'] === 'n-img.jpg') {
	echo '<div class="form-group">
						  <div class="file-upload">
							<div class="file-select">
							  <div class="file-select-button" id="fileName"><span class="mdi mdi-accounts-list-alt"> </span>' . lang('image') . '</div>
							  <div class="file-select-name" id="noFile">' . lang('notchoise') . '</div>
							  <input type="file" name="staffavatar" id="chooseFile">
							</div>
						  </div>
						</div>';
} else {
	echo '
					<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><img class="img-rounded xs-mr-10" height="25px" src="' . base_url('uploads/staffavatars/'), $staff['staffavatar'] . '" alt=""></span>
						<input type="text" name="staffavatar" value="' . $staff['staffavatar'] . '" class="form-control" id="staffavatar" placeholder="staffavatar"/>
						<span class="input-group-btn"><a href="' . base_url('staff/removestaffavatar/' . $staff['id'] . '') . '" type="button" class="btn btn-default icon ion-trash-b"></a></span>
					</div>
				</div>
					';
} 
?>

                <div class="row xs-pt-15">
                 <div <?php if (if_admin) {echo 'style="display:none"';}?>>
                  <div class="col-xs-3">
                    <div class="ciuis-body-checkbox has-success">
                      <input name="admin" id="evet" type="checkbox"  <?=$staff['admin'] == 1 ? 'checked value="1"' : 'value="1"'?>>
                      <label for="evet"><?php echo lang('staffadmin'); ?></label>
                    </div>
                  </div>
                  <div class="col-xs-3">
                    <div class="ciuis-body-checkbox has-primary">
                      <input name="staffmember" id="staffmember" type="checkbox" <?=$staff['staffmember'] == 1 ? 'checked value="1"' : 'value="1"'?>>
                      <label for="staffmember"><?php echo lang('staffisstaff'); ?></label>
                    </div>
                  </div>
                  <div class="col-xs-3">
                    <div class="ciuis-body-checkbox has-danger">
                      <input name="inactive" id="inactive" type="checkbox" <?=$staff['inactive'] == 1 ? 'checked value="1"' : 'value="1"'?>>
                      <label for="inactive"><?php echo lang('staffinactive'); ?></label>
                    </div>
                  </div>
                  </div>
                  <div class="col-xs-3 md-pr-0 pull-right">
                    <p class="text-right">
                      <button type="submit" class="btn btn-space btn-default"><?php echo lang('save'); ?></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php echo form_close(); ?> </div>
      </div>
    </div>
	</div>
	<div id="changepassword" tabindex="-1" role="dialog" class="modal fade">
						<?php echo form_open('staff/changestaffpassword/' . $staff['id'] . ''); ?>
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h3 class="pull-left">
										<?php echo lang('changepassword'); ?>
									</h3>
									<button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
								</div>
								<hr>
								<div class="modal-body">
									<div class="col-md-12 nopadding">
										<div class="form-group">
											<label for="ad">
												<b><?php echo lang('password'); ?></b>
											</label>
											<p class="xs-mb-5">Login Password<a href="javascript:;" data-toggle="popover" data-trigger="hover" title="<?php echo lang('information') ?>" data-content="<?php echo lang('contactprimaryhover') ?>" data-placement="top"><b> ?</b></a>
											</p>
											<div class="input-group ">
												<input name="staffnewpassword" type="text" class="form-control " rel="gp" data-size="9" id="nc" data-character-set="a-z,A-Z,0-9,#">
												<span class="input-group-btn"><button type="button" class="btn btn-default getNewPass"><span class="ion-refresh"></span>
												</button>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-default pull-right">
											<?php echo lang('changepassword'); ?>
										</button>
									</div>
								</div>
								<div class="modal-footer"></div>
							</div>
						</div>
						<?php echo form_close(); ?>
						</div>
<?php include_once dirname(dirname(__FILE__)) . '/inc/sidebar.php';?>
<?php include_once dirname(dirname(__FILE__)) . '/inc/footer_post.php';?>
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