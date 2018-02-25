<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view( get_template_directory() . 'header_new' );

// var_dump( $task_id );
// var_dump( $task_data );
// var_dump( $user_domains );

$task_title = '';
$task_domain = '';
$task_backup_type = '';
$task_exclude_folders = '';
$task_exclude_files = '';
$task_freq = '';

if( $task_id > 0 ){
	
	$task_data = get_backup_tasks( $task_id );
	$task_data = isset( $task_data[0] ) ? $task_data[0] : false;

	if( $task_data ){
		$task_title = $task_data->title;
		$task_domain = $task_data->domain_id;
		$task_backup_type = $task_data->backup_type;
		$task_exclude_folders = $task_data->exclude_folders;
		$task_exclude_files = $task_data->exclude_files;
		$task_freq = $task_data->exe_frequency;
	}
}

$backupTaskTitleError = form_error( 'backupTaskTitle' );
$backupTaskDomainError = form_error( 'backupTaskDomain' );
$backupTypeError = form_error( 'backupType' );
$backupExcludeFoldersError = form_error( 'backupExcludeFolders' );
$backupExcludeFilesError = form_error( 'backupExcludeFiles' );

?>

  <div class="row">
  	<div class="col-md-12">
  		<div class="panel">
  			<div class="panel-header">
  				<h3><?php echo 0 === $task_id ? 'Create backup task' : 'Edit backup task'; ?></h3>
  			</div>

  			<div class="panel-content">
  				<?php echo form_open( current_url(), array( 'method' => 'post', 'class' => 'form-horizontal backups-settings-form' ) ); ?>

  				  	<div class="form-group">
  						<label for="backupTaskTitle" class="col-sm-2 control-label">Task title</label>
  						<div class="col-sm-10">
  							
  							<?php if( $backupTaskTitleError ){ ?><div class="alert alert-danger" role="alert"><?php echo $backupTaskTitleError; ?></div><?php } ?>
  							
  							<?php

  							$attr = array(
  							    'name'      => 'backupTaskTitle',
  							    'value'     => set_value('backupTaskTitle', $task_title),
  							    'type'		=> 'text',
  							    'class'		=> 'form-control'
  						    );

  						    echo form_input( $attr );
  						    
  						    ?>
  						</div>
  					</div>

  				  	<div class="form-group">
  						<label for="backupTaskDomain" class="col-sm-2 control-label">Domain</label>
  						<div class="col-sm-10">
  							
  							<?php if( $backupTaskDomainError ){ ?><div class="alert alert-danger" role="alert"><?php echo $backupTaskDomainError; ?></div><?php } ?>

  							<?php 

  							$options = array( 'Select domain' );

  							if( count( $user_domains ) > 0 ){
  								foreach( $user_domains as $k => $v ){
  									$options[ $v->id ] = $v->domain_name;
  								}
  							}

  							$attrStr = ' class="form-control"';

  							echo form_dropdown('backupTaskDomain', $options, set_value('backupTaskDomain', $task_domain), $attrStr );

  							?>
  						</div>
  					</div>

  					<div class="form-group">
  						<label for="backupTaskFrequency" class="col-sm-2 control-label">Execute once a</label>
  						<div class="col-sm-10">

  							<?php 

  							$options = array( 'daily' => 'Day', 'weekly' => 'Week', 'monthly' => 'Month' );

  							$attrStr = ' class="form-control"';

  							echo form_dropdown('backupTaskFrequency', $options, set_value('backupTaskFrequency', $task_freq), $attrStr );

  							?>
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
  								    'checked'   => 'files-db' !== $task_backup_type ? true : false,
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
  								    'checked'   => 'files-db' === $task_backup_type ? true : false,
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
  									'value'	=> set_value('backupExcludeFolders', $task_exclude_folders),
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
  									'value'	=> set_value('backupExcludeFiles', $task_exclude_files),
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
  								'name' => 'submit_backup_task',
  							    'class' => 'btn btn-primary',
  							    'type' => 'submit',
  							    'content' => 0 === $task_id ? 'Create task' : 'Save task',
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

<?php 

$this->load->view( get_template_directory() . 'footer_new' );
 ?>