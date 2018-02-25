<?php
global $active_content_nav_items;
$active_content_nav_items = 'wordpress';

$session_user_data = $this->session->get_userdata();

$domain_id = $session_user_data['domainId'];

$wp_user_can_manage_options = $wp_updates_info['manage_options'];
?>

<?php require 'parts/top.php'; ?>

<div class="content-row">

	<div class="content-column w-100 w-50-l">
			
		<div class="content-column-main">

			<div class="title">
	            <div class="left-pos">
	                <h3>PLUGINS UPDATES</h3>
	            </div>
	        </div>

			<div class="content-column-inner">
				<table class="list-table collapse tc">
					<thead>
						<tr>
							<th class="tl">PLUGIN</th>
							<th>ACTIVE VERSION</th>
							<th>NEW VERSION</th>
							<?php if( $wp_user_can_manage_options ){ ?><th></th><?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						if( $wp_updates_info['error'] && 'unreachable' === $wp_updates_info['error'] ){
							echo '<tr><td colspan="4">n/a</td></tr>';
						}
						else{
							if( empty( $wp_updates_info['plugins'] ) ){
								echo '<tr><td colspan="4">All plugins are updated</td></tr>';
							}
							else{
								foreach ($wp_updates_info['plugins'] as $key => $val) {
									echo '<tr>';
									echo '<td class="tl">'.$val['name'].'</td>';
									echo '<td>'.$val['old_version'].'</td>';
									echo '<td>'.$val['new_version'].'</td>';

									if( $wp_user_can_manage_options ){
										echo '<td>';
										echo '<button data-update-type="plugin" data-update-id="'.$val['slug'].'" class="wp-update-btn dib mv1 mh1 f7 btn-color no-underline pv1 pr2 pl1-l br1">';
										echo '<i class="material-icons">&#xE8D7;</i>';
										echo '<small class="fw7">UPDATE</small>';
										echo '</button></td></tr>';
									}
								}
							}
						}
						?>
					</tbody>
				</table>
		    </div>

		</div>

	</div>

	<div class="content-column w-100 w-50-l">
			
		<div class="content-column-main">

			<div class="title">
	            <div class="left-pos">
	                <h3>THEMES UPDATES</h3>
	            </div>
	        </div>

			<div class="content-column-inner">
				
				<table class="list-table collapse tc">
					<thead>
						<tr>
							<th class="tl">THEME</th>
							<th>ACTIVE VERSION</th>
							<th>NEW VERSION</th>
							<?php if( $wp_user_can_manage_options ){ ?><th></th><?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						if( $wp_updates_info['error'] && 'unreachable' === $wp_updates_info['error'] ){
							echo '<tr><td colspan="4">n/a</td></tr>';
						}
						else{
							if( empty( $wp_updates_info['themes'] ) ){
								echo '<tr><td colspan="4">All themes are updated</td></tr>';
							}
							else{
								foreach ($wp_updates_info['themes'] as $key => $val) {
									echo '<tr>';
									echo '<td class="tl">'.$val['name'].'</td>';
									echo '<td>'.$val['current_version'].'</td>';
									echo '<td>'.$val['new_version'].'</td>';

									if( $wp_user_can_manage_options ){
										echo '<td>';
										echo '<button data-update-type="theme" data-update-id="'.$val['theme'].'" class="wp-update-btn dib mv1 mh1 f7 btn-color no-underline pv1 pr2 pl1-l br1">';
										echo '<i class="material-icons">&#xE8D7;</i>';
										echo '<small class="fw7">UPDATE</small>';
										echo '</button></td></tr>';
									}
								}
							}
						}
						?>
					</tbody>
				</table>

		    </div>

		</div>

	</div>

</div>

<div class="content-row">
	<div class="content-column w-100">
			
		<div class="content-column-main">

			<div class="title">
	            <div class="left-pos">
	                <h3>CORE UPDATE</h3>
	            </div>
	        </div>

			<div class="content-column-inner">
				
				<table class="list-table collapse tc">
					<thead>
						<tr>
							<th>ACTIVE VERSION</th>
							<th>NEW VERSION</th>
							<?php if( $wp_user_can_manage_options ){ ?><th></th><?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						if( $wp_updates_info['error'] && 'unreachable' === $wp_updates_info['error'] ){
							echo '<tr><td colspan="4">n/a</td></tr>';
						}
						else{
							if( empty( $wp_updates_info['core'] ) ){
								echo '<tr><td colspan="4">WordPress core is updated</td></tr>';
							}
							else{
								echo '<tr>';
								echo '<td>'.$wp_updates_info['core']['current_version'].'</td>';
								echo '<td>'.$wp_updates_info['core']['version'].'</td>';
								if( $wp_user_can_manage_options ){
									echo '<td>';
									echo '<button data-update-type="core" class="wp-update-btn dib mv1 mh1 f7 btn-color no-underline pv1 pr2 pl1-l br1">';
									echo '<i class="material-icons">&#xE8D7;</i>';
									echo '<small class="fw7">UPDATE</small>';
									echo '</button></td></tr>';
								}
							}
						}
						?>
					</tbody>
				</table>

		    </div>
			
		</div>

	</div>
</div>

<?php /* ?>
<div class="content-row">

	<div class="content-column w-100 w-50-l">
		
		<div class="content-column-main">

			<div class="title">
                <div class="left-pos">
                    <h3>BACKUP TASKS</h3>
                </div>
                <div class="right-pos">
                    <a href="add-backup-task.php" title="" class="profile-edit dib btn-color f7 fw5 no-underline pv1 pl2 pr3 br1"><i class="material-icons white">&#xE145;</i><small class="white">ADD TASK</small></a>
                </div>
            </div>

			<div class="content-column-inner">
				<div class="list-table-wrap" style="padding-bottom:0;">
	        		<table class="list-table collapse tc">
	        			<thead>
		        			<tr>
		        				<th>TYPE</th>
		        				<th>RUN EVERY</th>
		        				<th>LAST RUN</th>
		        				<th>ACTION</th>
		        			</tr>
		        		</thead>
		        		<tbody>
		        			<tr>
		        				<td></td>
		        				<td></td>
		        				<td></td>
		        				<td></td>
		        			</tr>
		        		</tbody>
	        		</table>
	        	</div>
		    </div>

		</div>
	
	</div>

	<div class="content-column w-100 w-50-l">
		
		<div class="content-column-main">

			<div class="title">
                <div class="left-pos nowrap">
                    <h3>COMPLETED BACKUPS</h3>
                </div>
                <div class="right-pos nowrap">
                    <a href="backups-settings.php" title="" class="profile-edit dib btn-color f7 fw5 no-underline pv1 pl1 pr2 br1 mr2"><i class="material-icons white">&#xE8B8;</i><small class="white">BACKUPS SETTINGS</small></a>
            		<a href="#" title="" class="profile-edit dib btn-lines f7 fw5 no-underline pv1 pl1 pr2 br1"><i class="material-icons">&#xE864;</i><small class="">CREATE BACKUP</small></a>
                </div>
            </div>

			<div class="content-column-inner">
				<div class="list-table-wrap" style="padding-bottom:0;">
	        		<table class="list-table collapse tc">
	        			<thead>
		        			<tr>
		        				<th>DATE</th>
		        				<th>TYPE</th>
		        				<th>ACTION</th>
		        			</tr>
		        		</thead>
		        		<tbody>
		        			<tr>
		        				<td></td>
		        				<td></td>
		        				<td></td>
		        			</tr>
		        		</tbody>
	        		</table>
	        	</div>
		    </div>

		</div>
	
	</div>

</div>
<?php */ ?>

<input type="hidden" name="base_url" value="<?php echo base_url(); ?>" />
<input type="hidden" name="domain_id" value="<?php echo $domain_id; ?>" />

<?php require 'parts/bottom.php'; ?>