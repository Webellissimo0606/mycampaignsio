<div class="content-row">

	<div class="content-column w-100 w-75-l">
		
		<div class="content-column-main content-col">

	        <div class="title website-traffic-title">
	        	<div class="left-pos"><h3>UPTIME</h3></div>
	        </div>
	    
	        <div class="content-column-inner">
	        	
	        	<div class="relative fl w-100 w-third-ns mb4 mb0-l">
	        		<div class="circle-stat" style="margin-bottom:0;">
						<div class="aspect-ratio aspect-ratio--1x1">
							<div class="dt aspect-ratio--object">
								<div class="dtc tc v-mid"><span><?php echo $uptime_data['totalupdomains'] + $uptime_data['totaldowndomains'] +
								$uptime_data['totalnostatsdomains'] ; ?></span>TOTAL<br>DOMAINS</div>
							</div>
						</div>
					</div>
	        	</div>

	        	<div class="relative fl w-100 w-third-ns mb4 mb0-l">
	        		<div class="circle-stat" style="margin-bottom:0; border-color:#1ac65b;">
						<div class="aspect-ratio aspect-ratio--1x1">
							<div class="dt aspect-ratio--object">
								<div class="dtc tc v-mid"><span><?php echo $uptime_data['totalupdomains']; ?></span>SITES<br>UP</div>
							</div>
						</div>
					</div>
	        	</div>

	        	<div class="relative fl w-100 w-third-ns mb4 mb0-l">
	        		<div class="circle-stat" style="margin-bottom:0; border-color:#ff6000;">
						<div class="aspect-ratio aspect-ratio--1x1">
							<div class="dt aspect-ratio--object">
								<div class="dtc tc v-mid"><span><?php echo $uptime_data['totaldowndomains']; ?></span>SITES<br>DOWN</div>
							</div>
						</div>
					</div>
	        	</div>

	        </div>

	        <hr style="margin:0;">
	    
	        <div class="content-column-inner">
	        	
	        	<div class="relative fl w-100 w-50-ns tc mb4 mb0-ns">
	        		<div class="bold-stat-num">
						<span class="stat-num campaignsio-admin-green"><small><?php echo $uptime_data['fastest_domain']; ?> <small>s</small></small></span>
						<span class="stat-label">Fastest Domain Speed</span>
					</div>
	        	</div>

	        	<div class="relative fl w-100 w-50-ns tc">
	        		<div class="bold-stat-num">
						<span class="stat-num campaignsio-admin-orange"><small><?php echo $uptime_data['slowest_domain']; ?> <small>s</small></small></span>
						<span class="stat-label">Slowest Domain Speed</span>
					</div>
	        	</div>

	        </div>
	    
	    </div>
	</div>

	<div class="content-column w-100 w-25-l">
		<div class="content-column-main">
			<div class="title">
				<div class="left-pos"><h3>OVERALL UPDATE</h3></div>
			</div>
			<?php  ?>
			<div class="content-column-inner">
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-green keyword-pos-top-10-val"><?php echo $uptime_data['uptime1daypercentage']; ?> <small></small></span>
					<span class="stat-label">Last 24 hours</span>
				</div>
				<hr>
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-yellow keyword-pos-top-20-val"><?php echo $uptime_data['uptime7daypercentage']; ?> <small></small></span>
					<span class="stat-label">Last 7 days</span>
				</div>
				<hr>
				<div class="bold-stat-num">
					<span class="stat-num keyword-pos-top-50-val"><?php echo $uptime_data['uptime30daypercentage']; ?> <small></small></span>
					<span class="stat-label">Last 30 days</span>
				</div>
			</div>
			<?php  ?>
		</div>
	</div>

</div>

<hr class="mv3 mh2 pt2 bt-0 br-0 bl-0" style="border-color: rgba(255, 255, 255, 0.1);"/>

<div data-tab-id="uptime-tabs" class="relative content-row tab-contents-row">

	<div class="absolute-l top-0-l right-0-l w-30-l mh2 pb1 mt2-l mb0-l ph0-l pv0-l">

        <form action="./report" method="get" enctype="multipart/form-data" id="frm_status_report" name="frm_status_report">
            <div class="w-100 w-50-ns w-100-l fr-l" style="max-width:300px;">
                <select name="d" class="w-auto fl dib uptime-stats-days">
                    <?php foreach ($days_options as $key => $val) { ?>
                        <option value="<?php echo $val['days']; ?>"<?php if( $val['active'] ){ echo 'selected="selected"';} ?>><?php echo $val['label']; ?></option><?php
                    } ?>
                </select>
            </div>
        </form>

    </div>
     
    <div class="content-tab-items w-100 w-70-l">
	    <ul>
			<li data-tab-item="1" class="w-25 w-auto-l <?php echo '1' === $uptime_data['active_time_period_tab'] ? 'active' : ''; ?>">SITES DOWN</li>
			<li data-tab-item="2" class="w-25 w-auto-l <?php echo '2' === $uptime_data['active_time_period_tab'] ? 'active' : ''; ?>">SITES UP</li>
			<li data-tab-item="3" class="w-25 w-auto-l <?php echo '3' === $uptime_data['active_time_period_tab'] ? 'active' : ''; ?>">SITES WITH NO STATS</li>
		</ul>
	</div>

	<div class="content-column w-100">
		
		<div class="content-column-main">

			<div class="content-column-inner">
				
            	<div data-tab-content="1" class="content-tab-content sites-up-table-wrap <?php echo '1' === $uptime_data['active_time_period_tab'] ? 'active' : ''; ?>">
	            	
	            	<div class="list-table-wrap">
                    
	                	<table class="filter-table list-table mv3 collapse tc">
	                        <thead>
	                            <tr>
	                                <th data-sortable="false" style="width:1px;"></th>
	                                <th class="tl">DOMAIN</th>
	                                <th>STATUS</th>
	                                <?php /* ?>
	                                <th>CODE</th>
	                                <th>TYPE</th>
	                                <?php */ ?>
	                                <th>UPTIME</th>
	                                <th>LOADTIME</th>
	                            </tr>
	                        </thead>
	                		<tbody>
	                            <?php
	                            if( ! empty( $uptime_data['downtime'] ) ){
	                                $counter = 0;
	                                foreach( $uptime_data['downtime'] as $key => $val ) {
	                                    $counter++; ?>
	                        			<tr>
	                                        <td><?php echo $counter; ?></td>
	                                        <td class="tl"><a href="<?php echo $val['domain_name']; ?>" target="_blank" class="white link"><?php echo $val['domain_name']; ?><a/></td>
	                                        <td><div class="dib" style="background-color:#ff6000;width:16px;height:16px;border-radius:20px;">&nbsp;</div></td>
	                        				<?php /* ?>
	                                        <td>200</td>
	                                        <td><?php echo $val['module']; ?></td>
	                                        <?php */ ?>
	                                        <?php
	                                        foreach( $uptime_data['totaltime'] as $x => $y ){
	                                            if( $val['domain_id'] === $y['domain_id'] ){ ?>
	                                                <td><?php echo round( ( $val['total_stats'] * 100 ) / $y['total_stats'], 2 );?>%</td> <?php
	                                                break;
	                                            }
	                                        } ?>
	                                        <td><?php echo round( $val['avg_load_time'] / 1000 , 3); ?> <small>seconds</small></td>
	                        			</tr> <?php
	                                }
	                            }
	                            else{ ?>
	                                <tr><td colspan="7">No results found</td></tr>
	                                <?php
	                            } ?>
	                		</tbody>
	                	</table>

	            	</div>

	            </div>

				<div data-tab-content="2" class="content-tab-content sites-down-table-wrap <?php echo '2' === $uptime_data['active_time_period_tab'] ? 'active' : ''; ?>">

					<div class="list-table-wrap">
                    
	                	<table class="filter-table list-table mv3 collapse tc">
	                        <thead>
	                            <tr>
	                                <th data-sortable="false" style="width:1px;"></th>
	                                <th class="tl">DOMAIN</th>
	                                <th>STATUS</th>
	                                <?php /* ?>
	                                <th>CODE</th>
	                                <th>TYPE</th>
	                                <?php */ ?>
	                                <th>UPTIME</th>
	                                <th>LOADTIME</th>
	                            </tr>
	                        </thead>
	                		<tbody>
	                            <?php
	                            if( ! empty( $uptime_data['uptime'] ) ){
	                                $counter = 0;
	                                foreach( $uptime_data['uptime'] as $key => $val ) {
	                                    $counter++; ?>
	                        			<tr>
	                                        <td><?php echo $counter; ?></td>
	                                        <td class="tl"><a href="<?php echo $val['domain_name']; ?>" target="_blank" class="white link"><?php echo $val['domain_name']; ?><a/></td>
	                                        <td><div class="dib" style="background-color:#1ac65b;width:16px;height:16px;border-radius:20px;">&nbsp;</div></td>
	                        				<?php /* ?>
	                                        <td>200</td>
	                                        <td><?php echo $val['module']; ?></td>
	                                        <?php */ ?>
	                                        <?php
	                                        foreach( $uptime_data['totaltime'] as $x => $y ){
	                                        	if( $val['domain_id'] === $y['domain_id'] ){ ?>
	                                                <td><?php echo round( ( $val['total_stats'] * 100 ) / $y['total_stats'], 2 ); ?>%</td> <?php
	                                                break;
	                                            }
	                                        } ?>
	                                        <td><?php echo round( $val['avg_load_time'] / 1000 , 3); ?> <small>seconds</small></td>
	                        			</tr> <?php
	                                }
	                            }
	                            else{ ?>
	                                <tr><td colspan="7">No results found</td></tr>
	                                <?php
	                            } ?>
	                		</tbody>
	                	</table>

	            	</div>

		        </div>

            	<div data-tab-content="3" class="content-tab-content sites-with-no-stats-table-wrap <?php echo '3' === $uptime_data['active_time_period_tab'] ? 'active' : ''; ?>">
					
					<div class="list-table-wrap">
                    
	                	<table class="filter-table list-table mv3 collapse tc">
	                        <thead>
	                            <tr>
	                                <th data-sortable="false" style="width:1px;"></th>
	                                <th class="tl">DOMAIN</th>
	                                <th>STATUS</th>
	                                <?php /* ?>
	                                <th>CODE</th>
	                                <th>TYPE</th>
	                                <?php */ ?>
	                                <th>UPTIME</th>
	                                <th>LOADTIME</th>
	                            </tr>
	                        </thead>
	                		<tbody>
	                            <?php
	                            if( ! empty( $uptime_data['nostats'] ) ){
	                                $counter = 0;
	                                foreach( $uptime_data['nostats'] as $key => $val ) {
	                                    $counter++; ?>
	                        			<tr>
	                                        <td><?php echo $counter; ?></td>
	                                        <td class="tl"><a href="<?php echo $val['domain_name']; ?>" target="_blank" class="white link"><?php echo $val['domain_name']; ?><a/></td>
	                                        <td><div class="dib" style="background-color:#b4c61a;width:16px;height:16px;border-radius:20px;">&nbsp;</div></td>
	                        				<?php /* ?>
	                                        <td>200</td>
	                                        <td>N/A</td>
	                                        <?php */ ?>
	                                        <td>N/A</td>
	                                        <?php /* ?><td><?php echo round( $val['avg_load_time'] / 1000 , 3); ?> <small>seconds</small></td><?php */ ?>
	                                        <td>N/A</td>
	                        			</tr> <?php
	                                }
	                            }
	                            else{ ?>
	                                <tr><td colspan="7">No results found</td></tr>
	                                <?php
	                            } ?>
	                		</tbody>
	                	</table>

	            	</div>

            	</div>

			</div>

		</div>

	</div>

</div>
