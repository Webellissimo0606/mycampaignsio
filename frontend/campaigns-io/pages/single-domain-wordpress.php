<div class="relative db w-100 ph2 pv1 cf">
	<a href="<?php echo $domain['url']['wp_login']; ?>" target="_blank" title="" class="btn-color fl f7 no-underline pv2 ph3 br1 mr3">
		<span class="white">WP LOGIN</span>
	</a>

	<?php if( isset( $page_data_last_update ) && $page_data_last_update ){ ?>
	<span class="mv3-s ml0-s fl w-100 w-auto-ns fr-ns"><small class="dib mv3 mv0-ns mr2" style="color:#c9c9c9;">Last updates check: <?php echo $page_data_last_update; ?></small> 
		<?php if( isset( $page_data_update_now_link ) && $page_data_update_now_link ){ ?>
		<a href="<?php echo $page_data_update_now_link; ?>" title="" class="dib f7 btn-color no-underline pv2 ph3 br1"><span class="white">CHECK NOW</span></a></span>
		<?php } ?>
	<?php } ?>
</div>

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
							<th>INSTALLED VERSION</th>
							<th>NEW VERSION</th>
							<?php if( $domain['wp_updates']['can_update_plugins'] ){ ?><th></th><?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						if( $domain['wp_updates']['error'] && 'unreachable' === $domain['wp_updates']['error'] ){
							echo '<tr><td colspan="4">n/a</td></tr>';
						}
						else{
							if( empty( $domain['wp_updates']['plugins'] ) ){
								echo '<tr><td colspan="4">All plugins are updated</td></tr>';
							}
							else{
								foreach ($domain['wp_updates']['plugins'] as $key => $val) {
									echo '<tr>';
									echo '<td class="tl">'.$val['name'].'</td>';
									echo '<td>'.$val['old_version'].'</td>';
									echo '<td>'.$val['new_version'].'</td>';

									if( $domain['wp_updates']['can_update_plugins'] ){
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
							<?php if( $domain['wp_updates']['can_update_themes'] ){ ?><th></th><?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						if( $domain['wp_updates']['error'] && 'unreachable' === $domain['wp_updates']['error'] ){
							echo '<tr><td colspan="4">n/a</td></tr>';
						}
						else{
							if( empty( $domain['wp_updates']['themes'] ) ){
								echo '<tr><td colspan="4">All themes are updated</td></tr>';
							}
							else{
								foreach ($domain['wp_updates']['themes'] as $key => $val) {
									echo '<tr>';
									echo '<td class="tl">'.$val['name'].'</td>';
									echo '<td>'.$val['current_version'].'</td>';
									echo '<td>'.$val['new_version'].'</td>';

									if( $domain['wp_updates']['can_update_themes'] ){
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
							<th>INSTALLED VERSION</th>
							<th>NEW VERSION</th>
							<?php if( $domain['wp_updates']['can_update_core'] ){ ?><th></th><?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php
						if( $domain['wp_updates']['error'] && 'unreachable' === $domain['wp_updates']['error'] ){
							echo '<tr><td colspan="4">n/a</td></tr>';
						}
						else{
							if( empty( $domain['wp_updates']['core'] ) ){
								echo '<tr><td colspan="4">WordPress core is updated</td></tr>';
							}
							else{
								echo '<tr>';
								echo '<td>'.$domain['wp_updates']['core']['current_version'].'</td>';
								echo '<td>'.$domain['wp_updates']['core']['version'].'</td>';
								if( $domain['wp_updates']['can_update_core'] ){
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

<?php echo form_hidden( 'base_url', base_url() ); ?>
<?php echo form_hidden( 'domain_id', $domain['id'] ); ?>