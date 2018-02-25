<?php
$this->load->view(get_template_directory() . 'header');

function get_excl_success_icon_html() {
	return '<i class="fa fa-check success"></i>';
}

function get_excl_error_icon_html() {
	return '<i class="fa fa-close error"></i>';
}

function get_backupTask_tableRow( $key, $val ){

	$task_settings = get_backup_tasks( $val->id );
	$task_settings = $task_settings && isset( $task_settings[0] ) ? $task_settings[0] : false;

	if( 'files-db' === $val->backup_type ){
		$exclude_folders_icon = '' === trim( $val->exclude_folders ) ? get_excl_error_icon_html() : get_excl_success_icon_html();
		$exclude_files_icon = '' === trim( $val->exclude_files ) ?  get_excl_error_icon_html() : get_excl_success_icon_html();
	}
	else{
		$exclude_folders_icon = '-';
		$exclude_files_icon = '-';
	}
	?>
	<tr data-taskid="<?php echo $val->id; ?>">
		<td class="num"><?php echo $key + 1; ?></td>
		<td class="title"><?php echo $val->title; ?></td>
		<td class="url"><a href="<?php echo $val->domain_url; ?>" title="" target="_blank"><?php echo $val->domain_name; ?></a></td>
		<td class="type"><?php echo 'files-db' === $val->backup_type ? 'Files and database' : 'Database only'; ?></td>
		<td class="exclude_folders"><?php echo $exclude_folders_icon; ?></td>
		<td class="exclude_files"><?php echo $exclude_files_icon; ?></td>
		<td class="last_run"><?php echo $val->last_run ? $val->last_run : '<i class="fa fa-spinner spin-animation"></i>'; ?></td>
		<td class="created"><?php echo $val->created; ?></td>
		<td class="actions">
			<a href="<?php echo site_url('/auth/backups/tasks/edit/' . $val->id ); ?>" class="btn btn-default" title=""><i class="fa fa-pencil"></i> Edit</a>
			<a href="<?php echo site_url('/auth/backups/tasks/delete/' . $val->domain_id . '/' . $val->id ); ?>" class="btn btn-default" title=""><i class="fa fa-trash"></i> Delete</a>
		</td>
	</tr><?php
}

$user_data = $this->session->get_userdata();
$user_domains = array();
$this->db->select('*');
$this->db->from('domains');
$this->db->where('userid', (int) $user_data['user_id'] );
$query = $this->db->get();

foreach( $query->result() as $row ) {
    $user_domains[] = $row;
}

$create_task_url = site_url('auth/backups/tasks/create');
$edit_task_url = site_url('auth/backups/tasks/edit');

$backupTasks = get_backup_tasks();

$daily_tasks = array();
$weekly_tasks = array();
$monthly_tasks = array();

if( ! empty( $backupTasks ) ){

	foreach ( $backupTasks as $key => $val ) {

		$domain_data = get_site_by_id( $val->domain_id );

		switch( $val->exe_frequency ){
			case 'daily':
				$val->{ 'domain_name' } = $domain_data->domain_name;
				$val->{ 'domain_url' } = add_url_scheme( remove_last_slash( $domain_data->domain_name ) );
				$daily_tasks[] = $val;
			break;
			case 'weekly':
				$val->{ 'domain_name' } = $domain_data->domain_name;
				$val->{ 'domain_url' } = add_url_scheme( remove_last_slash( $domain_data->domain_name ) );
				$weekly_tasks[] = $val;
			break;
			default:
			case 'monthly':
				$val->{ 'domain_name' } = $domain_data->domain_name;
				$val->{ 'domain_url' } = add_url_scheme( remove_last_slash( $domain_data->domain_name ) );
				$monthly_tasks[] = $val;
			break;
		}
	}
}

$backupTasks = null;

$successIcon = '<i class="fa fa-check" style="color:green;"></i>';
$errorIcon = '<i class="fa fa-close" style="color:red;"></i>';
?>

<!DOCTYPE html>

<div class="page-container changemycolors">

	<?php echo $this->session->flashdata('backup_settings_form_msg'); ?>

	<?php echo $this->session->flashdata('backup_tasks_form_msg'); ?>

	<div class="panel_top myonecolor">

		<div class="pane_top_inside">

			<div class="container">

				<div id="" class="row keywords-style">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<h2>Sites</h2>
						<div class="container">
							<div class="table-responsive table-backups-wrapper">
								<table class="table-backups-sites table table-bordered table-hover table-condensed table-striped">
									<thead>
										<tr>
											<th class="num">#</th>
											<th class="url">URL</th>
											<th class="admin_url">Admin URL</th>
											<th class="type">Type</th>
											<th class="exclude_folders">Excl. folders</th>
											<th class="exclude_files">Excl. files</th>
											<th class="actions"></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if( ! empty( $user_domains ) ){
											$cnt = 1;
											foreach( $user_domains as $domain ){
												$domain_url = $domain->domain_name;
												$admin_url = site_url('auth/site/viewsite/'. $domain->id);
												$settings_url = site_url('auth/backups/settings/'. $domain->id);
												$backup_settings = get_backup_settings( $domain->id );
												$backup_settings = $backup_settings && isset( $backup_settings[0] ) ? $backup_settings[0] : false;
												$backup_type = $backup_settings ? $backup_settings->backup_type : 'db-only';

												if( 'files-db' === $backup_type ){
													$exclude_folders = $backup_settings ? trim( $backup_settings->exclude_folders ) : '';
													$exclude_files = $backup_settings ?  trim( $backup_settings->exclude_files ) : '';

													$exclude_folders_icon = '' === $exclude_folders ? get_excl_error_icon_html() : get_excl_success_icon_html();
													$exclude_files_icon = '' === $exclude_files ? get_excl_error_icon_html() : get_excl_success_icon_html();
												}
												else{
													$exclude_folders_icon = '-';
													$exclude_files_icon = '-';
												}

												$backup_type_text = 'files-db' === $backup_type ? 'Files and database' : 'Database only';
												 
												?>
												<tr>
													<td class="num"><?php echo $cnt; ?></td>
													<td class="url">
														<a href="<?php echo $domain_url; ?>" title="" target="_blank">
															<?php echo $domain->domain_name; ?>
														</a>
													</td>
													<td class="admin_url">
														<a href="<?php echo $admin_url; ?>" title="" target="_blank">
															<?php echo $domain->adminURL; ?>
														</a>
													</td>
													<td class="type"><?php echo $backup_type_text; ?></td>
													<td class="exclude_folders"><?php echo $exclude_folders_icon; ?></td>
													<td class="exclude_files"><?php echo $exclude_files_icon; ?></td>
													<td class="actions">
														<a href="<?php echo $settings_url; ?>" title="Backup settings" class="btn btn-default"><i class="fa fa-wrench"></i> Settings</a>
														<button class="btn btn-default backup-custom-exe" data-domainid="<?php echo $domain->id; ?>"><i class="fa fa-inbox"></i> Backup now</button>
													</td>
												</tr>	
												<?php
												$cnt++;
											}
										}
										?>
									</tbody>
								</table>
							</div>
							</div>
					</div>
				</div>

			</div>

		</div>

	</div>

	<div class="panel_top myonecolor">
		<div class="pane_top_inside">
            <div class="container">
                <div id="backups_tasks_list" class="row keywords-style">

                	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    	<h2>Backups Tasks</h2>

                        <div class="container">

                            <ul class="nav nav-tabs scheduled-backups-tabs">
                                <li class="active"><a data-toggle="tab" href="#daily-tasks">Daily<span class="num daily-backup-tasks-num"><?php echo count( $daily_tasks ); ?></span></a></li>
                                <li><a data-toggle="tab" href="#weekly-tasks">Weekly<span class="num weekly-backup-tasks-num"><?php echo count( $weekly_tasks ); ?></span></a></li>
                                <li><a data-toggle="tab" href="#monthly-tasks">Monthly<span class="num monthly-backup-tasks-num"><?php echo count( $monthly_tasks ); ?></span></a></li>
                            </ul>

                            <div class="tab-content">

                                <div id="daily-tasks" class="tab-pane active">
                                	
                                	<div class="table-responsive table-backups-wrapper">
										<table class="table-backups-tasks table table-bordered table-hover table-condensed table-striped">
											<thead>
												<tr>
													<th class="num">#</th>
													<th class="title">Title</th>
													<th class="url">URL</th>
													<th class="type">Type</th>
													<th class="exclude_folders">Excl. folders</th>
													<th class="exclude_files">Excl. files</th>
													<th class="last_run">Last run</th>
													<th class="created">Created</th>
													<th class="actions"></th>
												</tr>
											</thead>
											<tbody><?php
												if( ! empty( $daily_tasks ) ){
													foreach ( $daily_tasks as $key => $val ) {
														get_backupTask_tableRow( $key, $val );
													}
												}
												else{ ?>
													<tr>
														<td colspan="9"><br/>Has not scheduled daily tasks<br/><br/></td>
													</tr><?php
												} ?>
											</tbody>
										</table>
									</div>

                                </div>

                                <!--brands tab menu-->
                                <div id="weekly-tasks" class="tab-pane">
                                	
                                	<div class="table-responsive table-backups-wrapper">

										<table class="table-backups-tasks table table-bordered table-hover table-condensed table-striped">
											<thead>
												<tr>
													<th class="num">#</th>
													<th class="title">Title</th>
													<th class="url">URL</th>
													<th class="type">Type</th>
													<th class="exclude_folders">Excl. folders</th>
													<th class="exclude_files">Excl. files</th>
													<th class="last_run">Last run</th>
													<th class="created">Created</th>
													<th class="actions"></th>
												</tr>
											</thead>
											<tbody><?php
												if( ! empty( $weekly_tasks ) ){
													foreach ( $weekly_tasks as $key => $val ) {
														get_backupTask_tableRow( $key, $val );
													}
												}
												else{ ?>
													<tr>
														<td colspan="9"><br/>Has not scheduled weekly tasks<br/><br/></td>
													</tr><?php
												} ?>
											</tbody>
										</table>

									</div>

                                </div>

                                <div id="monthly-tasks" class="tab-pane">
                                	
                                	<div class="table-responsive table-backups-wrapper">

										<table class="table-backups-tasks table table-bordered table-hover table-condensed table-striped">
											<thead>
												<tr>
													<th class="num">#</th>
													<th class="title">Title</th>
													<th class="url">URL</th>
													<th class="type">Type</th>
													<th class="exclude_folders">Excl. folders</th>
													<th class="exclude_files">Excl. files</th>
													<th class="last_run">Last run</th>
													<th class="created">Created</th>
													<th class="actions"></th>
												</tr>
											</thead>
											<tbody><?php
												if( ! empty( $monthly_tasks ) ){
													foreach ( $monthly_tasks as $key => $val ) {
														get_backupTask_tableRow( $key, $val );
													}
												}
												else{ ?>
													<tr>
														<td colspan="9"><br/>Has not scheduled monthly tasks<br/><br/></td>
													</tr><?php
												} ?>
											</tbody>
										</table>

									</div>

                                </div>
                            </div>
                        </div>

                        <div class="create-task-wrapper">
	                    	<a href="<?php echo $create_task_url; ?>" title="" class="btn btn-default"><i class="fa fa-plus"></i> Create task</a>
	                    </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

	<div class="panel_top myonecolor">

		<div class="pane_top_inside">

			<div class="container">

				<div id="backups_completed" class="row keywords-style">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<h2>Completed Backups</h2>
						<div class="container">
							<div class="table-responsive table-backups-wrapper">
								<table class="table-backups table table-bordered table-hover table-condensed table-striped">
									<thead>
										<tr>
											<th class="num">#</th>
											<th class="title">Title</th>
											<th class="url">URL</th>
											<th class="type">Type</th>
											<th class="date">Date</th>
											<th class="actions"></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td colspan="6"><br/><i class="fa fa-spinner spin-animation"></i> Loading...<br/><br/></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

			</div>

		</div>

	</div>

</div>

<br/>