<div class="relative db w-100 ph2 pv1 cf">
	<?php if( isset( $page_data_last_update ) && $page_data_last_update ){ ?>
	<span class="mv3-s ml0-s fl w-100 w-auto-ns fr-ns"><small class="dib mv3 mv0-ns mr2" style="color:#c9c9c9;">Last statistics overview check: <?php echo $page_data_last_update; ?></small> 
		<?php if( isset( $page_data_update_now_link ) && $page_data_update_now_link ){ ?>
		<a href="<?php echo $page_data_update_now_link; ?>" title="" class="dib f7 btn-color no-underline pv2 ph3 br1"><span class="white">CHECK NOW</span></a></span>
		<?php } ?>
	<?php } ?>
</div>

<div class="content-row">

	<div class="content-column w-100 w-two-thirds-l">
		
		<div class="content-column-main content-col">

	        <div class="title website-traffic-title">

	        	<div class="relative fl w-100 dib dn-ns mb3 cf">
	        		<h3>WEBSITE TRAFFIC</h3>
	        	</div>

	        	<div class="left-pos">
	        		<a href="<?php echo $domain['url']['analytics']; ?>" title="" class="btn-color f7 no-underline pv1 ph3 br1"><span class="white">VIEW MORE</span></a>
	        	</div>

	        	<div class="dn dtc-ns ph-2 tc-ns">
	        		<h3>WEBSITE TRAFFIC</h3>
	        	</div>

	        	<div class="right-pos">
	        		<a href="<?php echo $domain['url']['wp_login']; ?>" target="_blank" title="" class="btn-color f7 no-underline pv1 ph3 br1"><span class="white">WP LOGIN</span></a>
	        	</div>

	        </div>
	    
	        <div class="content-column-inner">
	        	<canvas id="webTrafficChart"></canvas>
	        </div>
	    
	    </div>
	</div>
	
	<div class="content-column w-100 w-50-ns w-third-half-l">
		<div class="content-column-main">

			<div class="title">
				<div class="left-pos"><h3>SEO DATA</h3></div>
			</div>

			<div class="content-column-inner rmv-left-padd rmv-right-padd">
				
				<div class="circle-stat">
					<div class="aspect-ratio aspect-ratio--1x1">
						<div class="dt aspect-ratio--object">
							<div class="dtc tc v-mid"><span><?php echo $domain['seo']['week_clicks']; ?></span>TRAFFIC THIS<br>WEEK</div>
						</div>
					</div>
				</div>

				<div class="inline-stat-num">
					<span class="stat-num campaignsio-admin-green month-clicks-val"><?php echo $domain['seo']['month_clicks']; ?></span>
					<span class="stat-label">Clicks this month</span>
				</div>

				<div class="inline-stat-num">	
					<span class="stat-num campaignsio-admin-yellow moved-up-clicks-val"><?php echo $domain['seo']['keywords']['changes']['positive']; ?></span>
					<span class="stat-label">Moved up</span>
				</div>

				<div class="inline-stat-num">
					<span class="stat-num campaignsio-admin-orange moved-down-clicks-val"><?php echo $domain['seo']['keywords']['changes']['negative']; ?></span>
					<span class="stat-label">Moved down</span>
				</div>

				<div class="inline-stat-num">
					<span class="stat-num campaignsio-admin-action-color no-changes-val"><?php echo $domain['seo']['keywords']['changes']['nochange']; ?></span>
					<span class="stat-label">No changes</span>
				</div>
			</div>
		</div>
	</div>

	<div class="content-column w-100 w-50-ns w-third-half-l">
		<div class="content-column-main">
			<div class="title">
				<div class="left-pos"><h3>KEYWORD POSITION</h3></div>
			</div>
			<?php  ?>
			<div class="content-column-inner">
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-green keyword-pos-top-10-val"><?php echo $domain['seo']['keywords']['position']['top10']; ?></span>
					<span class="stat-label">Top 10</span>
				</div>
				<hr>
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-yellow keyword-pos-top-20-val"><?php echo $domain['seo']['keywords']['position']['top20']; ?></span>
					<span class="stat-label">Top 20</span>
				</div>
				<hr>
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-orange keyword-pos-top-50-val"><?php echo $domain['seo']['keywords']['position']['top50']; ?></span>
					<span class="stat-label">Top 50</span>
				</div>
			</div>
			<?php  ?>
		</div>
	</div>

</div>

<div class="content-row">
	
	<div class="content-column w-100 w-two-thirds-l">
	    <div class="content-column-main content-col">

		    <?php
		    	$gtMetrix_hrefAttr = '#';
	        	$gtMetrix_classAttr = ' class="gtm-view-more-link btn-color f7 no-underline pv1 ph3 br1"';
	        	$gtMetrix_styleAttr = ' style="display:none;"';

	        	if( isset( $domain['gtmetrix']['metrix']['report_url'] ) && '' !== trim( $domain['gtmetrix']['metrix']['report_url'] ) ){
	        		$gtMetrix_hrefAttr = $domain['gtmetrix']['metrix']['report_url'];
	        		$gtMetrix_styleAttr = '';
	        	}
	        	
	        	$gtMetrix_hrefAttr = ' href="' . $gtMetrix_hrefAttr . '"';
		    ?>

		    <div class="title">

		    	<div class="relative fl w-100 dib dn-ns mb3 cf">
	        		<h3>GT METRIX</h3>
	        	</div>

	        	<?php /* ?>
	        	<div class="left-pos">
	        		<a title="" target="_blank" <?php echo $gtMetrix_hrefAttr . $gtMetrix_classAttr . $gtMetrix_styleAttr; ?>><span class="white">VIEW MORE</span></a>
	        	</div>

	        	<div class="dn dtc-ns ph-2 tc-ns">
	        		<h3>GT METRIX</h3>
	        	</div>
				
				<div class="right-pos">
	        		<span href="#" title="" class="rescan-gtmetrix btn-color f7 no-underline pv1 ph3 br1"><span class="white">RESCAN NOW</span></span>
	        	</div>
	        	<?php */ ?>

	        	<div class="left-pos">
	        		<h3>GT METRIX</h3>
	        	</div>
				
				<div class="right-pos">
	        		<a title="" target="_blank" <?php echo $gtMetrix_hrefAttr . $gtMetrix_classAttr . $gtMetrix_styleAttr; ?>><span class="white">VIEW MORE</span></a>
	        	</div>

	        </div>
			
			<div class="relative w-100 dib">
				<div class="gtmetrix_wrapper w-100 dib">
		        	<?php single_domain_gtmetrix_content( $domain['url']['site'], $domain['gtmetrix'] ); ?>
		    	</div>
		    	<div class="gtmetrix-loader-overlay serp-report-loader hidden invisible"><span class="loading-icon"><span><i class="material-icons">&#xE86A;</i></span></span></div>
		    </div>

	    </div>
	</div>
	
	<div class="content-column w-100 w-50-ns w-third-half-l">
	    <div class="content-column-main content-col">

	        <div class="title">
	        	<div class="left-pos">
	        		<h3>GOOGLE SEARCH CONSOLE</h3>
	        	</div>
	        </div>

	        <div class="content-column-inner rmv-left-padd rmv-right-padd">
				
				<br/>

				<div class="circle-stat">
					<div class="aspect-ratio aspect-ratio--1x1">
						<div class="dt aspect-ratio--object">
							<div class="dtc tc v-mid"><span><?php echo $domain['search_console']['average_ctr']; ?><small>%</small></span>AVERAGE <br/>CLICK THROUGH</div>
						</div>
					</div>
				</div>

				<div class="inline-stat-num">
					<span class="stat-num campaignsio-admin-green"><?php echo $domain['search_console']['clicks']; ?></span>
					<span class="stat-label">Total Clicks</span>
				</div>

				<div class="inline-stat-num">	
					<span class="stat-num campaignsio-admin-yellow"><?php echo $domain['search_console']['impressions']; ?></span>
					<span class="stat-label">Total Impressions</span>
				</div>

			</div>

	    </div>
	</div>

	<div class="content-column w-100 w-50-ns w-third-half-l">
	    <div class="content-column-main content-col">

	        <div class="title">
	        	<div class="left-pos">
	        		<h3>WORDPRESS</h3>
	        	</div>
	        </div>

	        <div class="content-column-inner">
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-green keyword-pos-top-10-val"><?php echo $domain['wp_updates']['core']; ?></span>
					<span class="stat-label">Core update</span>
				</div>
				<hr>
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-yellow keyword-pos-top-20-val"><?php echo $domain['wp_updates']['plugins']; ?></span>
					<span class="stat-label">Plugins updates</span>
				</div>
				<hr>
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-orange keyword-pos-top-50-val"><?php echo $domain['wp_updates']['themes']; ?></span>
					<span class="stat-label">Themes updates</span>
				</div>
				<hr/>
				<a href="<?php echo $domain['url']['wp_dashboard']; ?>" title="" class="btn-color f7 no-underline pv2 ph3 br1 fr"><span class="white">VIEW MORE</span></a>
			</div>

	    </div>
	</div>

</div>


<?php if( $domain['enabled_monitor_uptime'] ){ ?>

<div class="content-row">

	<div class="content-column w-100 w-50-l">
	    <div class="content-column-main content-col">
	        <div class="title">
	        	<div class="left-pos"><h3>DOMAIN UPTIME &amp; PERFORMANCE</h3></div>
	        	<div class="right-pos">
	        		<span class="domain-status white">Status: <?php
	        		if( 'down' === $domain['down_time']['server_status'] ){ ?>
	        			<span class="br1 campaignsio-admin-bg-orange"><small>DOWN</small></span><?php 
	        		}
	        		elseif( 'up' === $domain['down_time']['server_status'] ){ ?>
	        			<span class="br1 campaignsio-admin-bg-green"><small>UP</small></span><?php 
	        		}
	        		else{ ?>
	        			<span class="br1 campaignsio-admin-bg-yellow"><small>N/A</small></span><?php 
	        		}
	        		?></span></span>
	        	</div>
	        </div>
	        <div class="content-column-inner">
	        	<canvas id="responseAndUpTimeChart"></canvas>
	        </div>
	    </div>
	</div>

	<div class="content-column w-100 w-50-l">
	    <div class="content-column-main content-col">
	        <div class="title">
	        	<div class="left-pos"><h3>UPTIME</h3></div>
	        </div>
	        <div class="content-column-inner">
	        	<canvas id="uptimeChart"></canvas>
	        </div>
	    </div>
	</div>
</div>

<?php } ?>

<input type="hidden" name="is_dashboard_page" value="1" />

<?php echo form_hidden( 'base_url', base_url() ); ?>
<?php echo form_hidden( 'domain_id', $domain['id'] ); ?>
<?php echo form_hidden( 'domain_url', $domain['url']['site'] ); ?>
<?php echo form_hidden( 'gtmetrix_rescan_url', $domain['gtmetrix_rescan_url'] ); ?>

<input type="hidden" name="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
<input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_token_name(); ?>" />

<?php echo form_hidden( 'id_website_traffic_data', json_encode( $domain['traffic']['payload'] ) ); ?>

<?php if( $domain['enabled_monitor_uptime'] ){
	echo form_hidden( 'id_uptime_performance_data', json_encode( $domain['up_time']['performance'] ) );
	echo form_hidden( 'id_uptime_data', json_encode( $domain['up_time']['data'] ) ); 
} ?>