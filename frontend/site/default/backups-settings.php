<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view( get_template_directory() . 'header_new' );

$domain_id = (int) $domain_data->id;
$domain_url = $domain_data->domain_name;
$domain_url_link = add_url_scheme( remove_last_slash( $domain_url ) );
$domain_admin_url = $domain_data->adminURL;
$domain_admin_url_link = site_url('auth/site/viewsite/'. $domain_data->id);

$backupTypeError = form_error( 'backupType' );
$backupExcludeFoldersError = form_error( 'backupExcludeFolders' );
$backupExcludeFilesError = form_error( 'backupExcludeFiles' );

$backup_type = '';
$exclude_folders = '';
$exclude_files = '';

if( $domain_id > 0 ){

	$backup_settings = get_backup_settings( $domain_id );
	$backup_settings = isset( $backup_settings[0] ) ? $backup_settings[0] : false;

	if( $backup_settings ){
		$backup_type = $backup_settings->backup_type;
		$exclude_folders = $backup_settings->exclude_folders;
		$exclude_files = $backup_settings->exclude_files;
	}
}

?>

<div class="row">
	<div class="col-md-12">
		<div class="panel">

			<div class="panel-header">
				<h3>Backup settings</h3>
			</div>

			<div class="panel-content">
				<?php echo form_open( current_url(), array( 'method' => 'post', 'class' => 'form-horizontal backups-settings-form' ) ); ?>

				  	<div class="form-group">
						<label class="col-sm-2 control-label">Domain</label>
						<div class="col-sm-10">
							<a href="<?php echo $domain_url_link; ?>" title="" target="_blank"><?php echo $domain_url; ?></a>
						</div>
					</div>

					<div class="form-group">
					<label class="col-sm-2 control-label">Admin URL</label>
						<div class="col-sm-10">
							<a href="<?php echo $domain_admin_url_link; ?>" title="" target="_blank"><?php echo $domain_admin_url; ?></a>
						</div>
					</div>

					<div class="form-group">
						<label for="backupType" class="col-sm-2 control-label">Type</label>
						<div class="col-sm-10">
							
							<?php if( $backupTypeError ){ ?><div class="alert alert-danger" role="alert"><?php echo $backupTypeError; ?></div><?php } ?>

							<label class="radio-inline">
								<?php

								$attr = array(
								    'name'      => 'backupType',
								    'value'     => 'db-only',
								    'checked'   => 'files-db' !== $backup_type ? true : false,
								    'type'		=> 'radio'
							    );

							    echo form_checkbox( $attr );
							    
							    ?> Database only
							</label>
							<label class="radio-inline">
								<?php 

								$attr = array(
								    'name'      => 'backupType',
								    'value'     => 'files-db',
								    'checked'   => 'files-db' === $backup_type ? true : false,
								    'type'		=> 'radio'
							    );

							    echo form_checkbox( $attr );
							    
							    ?> Files and database
							</label>
						</div>
					</div>

					<div class="exclude-files-group">

						<div class="form-group">
							<div class="col-sm-2">
								<label for="backupExcludeFolders" class="control-label">Exclude Folders</label>
								<br/><small>Within "wp-content" folder</small>
							</div>
							<div class="col-sm-10">
								
								<?php if( $backupExcludeFoldersError ){ ?><div class="alert alert-danger" role="alert"><?php echo $backupExcludeFoldersError; ?></div><?php } ?>

								<small>Insert folders seperated by commas ";"</small>

								<?php

								$data = array(
									'name'  => 'backupExcludeFolders',
									'value'	=> set_value('backupExcludeFolders', $exclude_folders),
									'class'	=> 'form-control',
									'rows'	=> '',
									'cols'	=> '',
								);

								echo form_textarea($data);

								?>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-2">
								<label for="backupExcludeFolders" class="control-label">Exclude Files</label>
								<br/><small>Within "wp-content" folder</small>
							</div>
							<div class="col-sm-10">
								
								<?php if( $backupExcludeFilesError ){ ?> <div class="alert alert-danger" role="alert"><?php echo $backupExcludeFilesError; ?></div> <?php } ?>

								<small>Insert files seperated by commas ";"</small>
								
								<?php

								$data = array(
									'name'  => 'backupExcludeFiles',
									'value'	=> set_value('backupExcludeFiles', $exclude_files),
									'class' => 'form-control',
									'rows'	=> '',
									'cols'	=> '',
								);

								echo form_textarea($data);

								?>
							</div>
						</div>

					</div>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<br/>
							<?php 

							$attr = array(
								'name' => 'submit_backup_settings',
							    'class' => 'btn btn-primary',
							    'type' => 'submit',
							    'content' => 'Save settings'
							);

							echo form_button($attr); 

							?>

							<a href="<?php echo site_url( 'auth/wordpress' ); ?>" title="" class="btn btn-default pull-right">Cancel</a>

							<br/>
							<br/>
							<br/>
						</div>
					</div>

				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
	
</div>

<?php $this->load->view(get_template_directory().'footer_new'); ?>
