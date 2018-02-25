<?php
global $active_content_nav_items;
$active_content_nav_items = 'overview';

require 'parts/top.php';

$domain_data = $this->session->get_userdata();

/* ==================== // SEO & Keywords Position ==================== */

$seo_and_keywords_data = just_get_keyword_report( $this->analyze_model, $domain_data['user_id'], $domain_id );
$seo_and_keywords = array(
	'position' => isset( $seo_and_keywords_data['position'] ) ? $seo_and_keywords_data['position'] : array(
		'top5' => 'n/a',
		'top10' => 'n/a',
		'top20' => 'n/a',
		'top50' => 'n/a',
	),
	'keyword_changes' => isset( $seo_and_keywords_data['keyword_changes'] ) ? $seo_and_keywords_data['keyword_changes']  : array(
		'positive' => 'n/a',
		'negative' => 'n/a',
		'nochange' => 'n/a'
	),
);

$new_piwik_andler = new New_Piwik_Handler( $this->ci, $this->analyze_model );
$month_clicks_response = $new_piwik_andler->getsearchengineclicks( $domain_id );
$month_clicks = isset( $month_clicks_response['status'] ) && 'success' === $month_clicks_response['status'] ? $month_clicks_response['clicks'] : 'n/a';

/* ==================== Month clicks // ==================== */

/* ==================== // Website Traffic ==================== */

$websiteTraffic = array( 'payload' => array() );
$new_analytics_handler = new New_Analytics_Handler( $this->db, $this->config, $this->analyze_model );
$websiteTraffic_data = $new_analytics_handler->get_google_analytics( $domain_data['user_id'], $domain_id, 15 );
if( 'sucess' === $websiteTraffic_data['status'] && $websiteTraffic_data['payload'] ){
	$websiteTraffic['payload'] = $websiteTraffic_data['payload'];
}

/* ==================== Website Traffic // ==================== */

/* ==================== // Uptime Stats ==================== */

$uptimestats_response = just_domain_uptime_stats($domain_id, $this->analyze_model);

$uptime_data = array( 'one_day' => null, 'seven_days' => null, 'thirty_days' => null );
$uptime_performance = array();

if( $uptimestats_response ){

	if( isset( $uptimestats_response['uptimedaystats'] ) && ! empty( $uptimestats_response['uptimedaystats'] ) ){
		foreach ($uptimestats_response['uptimedaystats'] as $key => $val) {
			$uptime_performance[] = array(
				'completed_on' => $val['completed_on'],
				'load_time' => $val['load_time'],
			);
		}
	}

	if( isset( $uptimestats_response['uptime1daypercentage'] ) ){
		$uptime_data['one_day'] = round( $uptimestats_response['uptime1daypercentage'], 1 );
	}

	if( isset( $uptimestats_response['uptime7daypercentage'] ) ){
		$uptime_data['seven_days'] = round( $uptimestats_response['uptime7daypercentage'], 1 );
	}

	if( isset( $uptimestats_response['uptime30daypercentage'] ) ){
		$uptime_data['thirty_days'] = round( $uptimestats_response['uptime30daypercentage'], 1 );
	}
}

/* ==================== Uptime Stats // ==================== */

/* ==================== // GTMetrix ==================== */

$gtMetrix_response = just_gtmetrix_data( $domain_data['domainUrl'], $this->analyze_model, $this->ci_auth, $this->session );

if( isset( $gtMetrix_response['status'] ) && $gtMetrix_response['status'] && 
	isset( $gtMetrix_response['metrix'] ) && ! empty( $gtMetrix_response['metrix'] ) &&
	isset( $gtMetrix_response['date'] ) && ! empty( $gtMetrix_response['date'] ) ){
	$gtMetrix['loaded'] = true;
	$gtMetrix['metrix'] = json_decode( $gtMetrix_response['metrix'], true );
	$gtMetrix['date'] = $gtMetrix_response['date'];
}

/* ==================== GTMetrix // ==================== */

unset( $seo_and_keywords_data );
unset( $new_piwik_andler );
unset( $new_analytics_handler );
unset( $gtMetrix_response );

?>

<div class="content-row">

	<div class="content-column w-100 w-two-thirds-l">
		<div class="content-column-main content-col">
	        
	        <div class="title website-traffic-title">
	        	<div class="left-pos dn-m">
	        		<a href="<?php echo base_url(); ?>analytics/analytics/<?php echo $domain_id; ?>" title="" class="btn-color f7 no-underline pv1 ph3 br1"><span class="white">VIEW MORE</span></a>
	        	</div>

	        	<div class="center-pos tc-l">
	        		<h3>WEBSITE TRAFFIC</h3>
	        	</div>

	        	<div class="right-pos">
	        		<a href="<?php echo base_url(); ?>auth/site/viewsite/<?php echo $domain_id; ?>" target="_blank" title="" class="btn-color f7 no-underline pv1 ph3 br1"><span class="white">WP LOGIN</span></a>
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
							<div class="dtc tc v-mid"><span>n/a</span>TRAFFIC THIS<br>WEEK</div>
						</div>
					</div>
				</div>

				<div class="inline-stat-num">
					<span class="stat-num campaignsio-admin-green month-clicks-val"><?php echo $month_clicks; ?></span>
					<span class="stat-label">Clicks this month</span>
				</div>

				<div class="inline-stat-num">	
					<span class="stat-num campaignsio-admin-yellow moved-up-clicks-val"><?php echo $seo_and_keywords['keyword_changes']['positive']; ?></span>
					<span class="stat-label">Moved up</span>
				</div>

				<div class="inline-stat-num">
					<span class="stat-num campaignsio-admin-orange moved-down-clicks-val"><?php echo $seo_and_keywords['keyword_changes']['negative']; ?></span>
					<span class="stat-label">Moved down</span>
				</div>

				<div class="inline-stat-num">
					<span class="stat-num campaignsio-admin-action-color no-changes-val"><?php echo $seo_and_keywords['keyword_changes']['nochange']; ?></span>
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
			<div class="content-column-inner">
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-green keyword-pos-top-10-val"><?php echo $seo_and_keywords['position']['top10']; ?></span>
					<span class="stat-label">Top 10</span>
				</div>
				<hr>
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-yellow keyword-pos-top-20-val"><?php echo $seo_and_keywords['position']['top20']; ?></span>
					<span class="stat-label">Top 20</span>
				</div>
				<hr>
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-orange keyword-pos-top-50-val"><?php echo $seo_and_keywords['position']['top50']; ?></span>
					<span class="stat-label">Top 50</span>
				</div>
			</div>
		</div>
	</div>

</div>

<div class="content-row">
	
	<div class="content-column w-100 w-two-thirds-l">
	    <div class="content-column-main content-col">

		    <?php
		    	$screenshot_src = '';

		   		$gtMetrix_hrefAttr = '#';
	        	$gtMetrix_classAttr = ' class="gtm-view-more-link btn-color f7 no-underline pv1 ph3 br1"';
	        	$gtMetrix_styleAttr = ' style="display:none;"';

	        	if( isset( $gtMetrix['metrix']['report_url'] ) && '' !== trim( $gtMetrix['metrix']['report_url'] ) ){
	        		
	        		$screenshot_src = ' src="'.$gtMetrix['metrix']['report_url'] . '/screenshot.jpg"';

	        		$gtMetrix_hrefAttr = $gtMetrix['metrix']['report_url'];
	        		$gtMetrix_styleAttr = '';
	        	}
	        	
	        	$gtMetrix_hrefAttr = ' href="' . $gtMetrix_hrefAttr . '"';
		    ?>

	        <div class="title">
	        	<div class="left-pos">
	        		<h3>GT METRIX</h3>
	        	</div>
	        	<div class="right-pos">
	        		<a title="" target="_blank" <?php echo $gtMetrix_hrefAttr . $gtMetrix_classAttr . $gtMetrix_styleAttr; ?>><span class="white">VIEW MORE</span></a>
	            </div>
	        </div>

	        <div class="fl w-100 w-40-ns w-30-l">
	            <div class="content-column-inner">
	                <img class="br1 gtm-screenshot"<?php echo $screenshot_src; ?>/>
	            </div>
	        </div>

	        <div class="fl w-100 w-60-ns w-30-l">
	            <div class="content-column-inner">
	            
	                <h4 class="ma0 f5 fw5">Latest Performance Report for</h4>

	                <a  class="gtmetrix-site-link" href="<?php echo $domain_data['domainUrl']; ?>" target="_blank"><?php echo strtr( $domain_data['domainUrl'], array('www.' => '', 'https://' => '', 'http://' => '', '/' => '' ) ); ?></a>

	                <div class="report-box mt2">
	                    
	                    <div class="gtmetrix-report-row">
	                        <span><strong>Report Generated on</strong></span>
	                        <span class="report_date"><?php echo $gtMetrix['date']; ?></span>
	                    </div>
	                    
	                    <br/>
	                    <div class="gtmetrix-report-row">
	                        <span><strong>Test Server Region</strong></span>
	                        <span class="server-region-val">n/a</span>
	                    </div>

	                    <br/>

	                    <div class="gtmetrix-report-row">
	                        <span><strong>Using</strong></span>
	                        <div class="gtmetrix-browser-row"><img src="" class="browser-thumb"/>(<i class="browser-desktop-mobile">n/a</i>) <i class="browser-title"></i></div>
	                    </div>
	                </div>

	            </div>
	        </div>

	        <div class="fl w-100 w-100-ns w-40-l">

		        <?php
		        $loadTimeValueHtml = '<small><span class="time-val"></span><small class="time-unit">n/a</small></small>';
		        $pageSizeValueHtml = '<small><span class="size-val"></span><small class="size-unit">n/a</small></small>';
		        $requestsValueHtml = '<small class="requests-val">n/a</small>';
		        $pageSpeedValueHtml = '<small><span class="speed-grade"></span> <small>(<span class="speed-val">n/a</span>)</small></small>';
		        $ySlowScoreValueHtml = '<small><span class="score-grade"></span> <small>(<span class="score-val">n/a</span>)</small></small>';

		        if( isset( $gtMetrix['metrix']['page_load_time'] ) ){
		        	$loadTimeValueHtml = '<small><span class="time-val">' . ( round( $gtMetrix['metrix']['page_load_time'] / 1000 * 100 ) / 100 ) . '</span><small class="time-unit">s</small></small>';
		        }

		        if( isset( $gtMetrix['metrix']['page_bytes'] ) ){
		        	$formated_bytes = format_bytes( $gtMetrix['metrix']['page_bytes'] );
		        	$pageSizeValueHtml = '<small><span class="size-val">' . $formated_bytes['val'] . '</span><small class="size-unit">' . $formated_bytes['unit'] . '</small></small>';
		        }

		        if( isset( $gtMetrix['metrix']['page_elements'] ) ){
		        	$requestsValueHtml = '<small class="requests-val">' . $gtMetrix['metrix']['page_elements'] . '</small>';
		        }

		        if( isset( $gtMetrix['metrix']['pagespeed_score'] ) ){
		        	
		        	if ($gtMetrix['metrix']['pagespeed_score'] < 60) {
		        		$pagespeed_score_classname = ' campaignsio-admin-orange';
	                }
	                else if ($gtMetrix['metrix']['pagespeed_score'] >= 60 && $gtMetrix['metrix']['pagespeed_score'] < 80) {
	                	$pagespeed_score_classname = ' campaignsio-admin-yellow';
	                }
	                else {
	                	$pagespeed_score_classname = ' campaignsio-admin-green';
	                }

		        	$pageSpeedValueHtml = '<small><span class="speed-grade">' . get_grade( $gtMetrix['metrix']['pagespeed_score'] ) . '</span> <small>(<span class="speed-val">' . $gtMetrix['metrix']['pagespeed_score'] . '%</span>)</small></small>';
		        }

		        if( isset( $gtMetrix['metrix']['yslow_score'] ) ){

		        	if ($gtMetrix['metrix']['yslow_score'] < 60) {
		        		$yslow_score_classname = ' campaignsio-admin-orange';
	                }
	                else if ($gtMetrix['metrix']['yslow_score'] >= 60 && $gtMetrix['metrix']['yslow_score'] < 80) {
	                	$yslow_score_classname = ' campaignsio-admin-yellow';
	                }
	                else {
	                	$yslow_score_classname = ' campaignsio-admin-green';
	                }

		        	$ySlowScoreValueHtml = '<small><span class="score-grade">'. get_grade( $gtMetrix['metrix']['yslow_score'] ) .'</span> <small>(<span class="score-val">' . $gtMetrix['metrix']['yslow_score'] . '%</span>)</small></small>';
		        }

		        ?>

	            <div class="fl w-100 w-50-ns">
	                <div class="content-column-inner">

	                    <hr class="dn-l" style="margin-top: -1.5rem;"/>

	                    <div class="bold-stat-num">
	                        <span class="stat-num gtm-load-time"><?php echo $loadTimeValueHtml; ?></span>
	                        <span class="stat-label">Load time</span>
	                    </div>

	                    <hr/>

	                    <div class="bold-stat-num">
	                        <span class="stat-num gtm-page-size"><?php echo $pageSizeValueHtml; ?></span>
	                        <span class="stat-label">Page size</span>
	                    </div>

	                    <hr/>

	                    <div class="bold-stat-num">
	                        <span class="stat-num gtm-requests"><?php echo $requestsValueHtml; ?></span>
	                        <span class="stat-label">Requests</span>
	                    </div>

	                </div>
	            </div>

	            <div class="fl w-100 w-50-ns">
	                <div class="content-column-inner">

	                    <hr class="dn-l" style="margin-top: -1.5rem;"/>

	                    <div class="bold-stat-num">
	                        <span class="stat-num gtm-page-speed<?php echo $pagespeed_score_classname; ?>"><?php echo $pageSpeedValueHtml; ?></span>
	                        <span class="stat-label">Page speed</span>
	                    </div>

	                    <hr/>

	                    <div class="bold-stat-num">
	                        <span class="stat-num gtm-y-slow-score<?php echo $yslow_score_classname; ?>"><?php echo $ySlowScoreValueHtml; ?></span>
	                        <span class="stat-label">Y Slow score</span>
	                    </div>

	                    <hr/>
	                </div>
	            </div>
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
							<div class="dtc tc v-mid"><span>n/a<small>%</small></span>AVERAGE <br/>CLICK THROUGH</div>
						</div>
					</div>
				</div>

				<div class="inline-stat-num">
					<span class="stat-num campaignsio-admin-green">n/a</span>
					<span class="stat-label">Total Clicks</span>
				</div>

				<div class="inline-stat-num">	
					<span class="stat-num campaignsio-admin-yellow">n/a</span>
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
					<span class="stat-num campaignsio-admin-green keyword-pos-top-10-val"><?php echo $wp_updates_info['core']; ?></span>
					<span class="stat-label">Core update</span>
				</div>
				<hr>
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-yellow keyword-pos-top-20-val"><?php echo $wp_updates_info['plugins']; ?></span>
					<span class="stat-label">Plugins updates</span>
				</div>
				<hr>
				<div class="bold-stat-num">
					<span class="stat-num campaignsio-admin-orange keyword-pos-top-50-val"><?php echo $wp_updates_info['themes']; ?></span>
					<span class="stat-label">Themes updates</span>
				</div>
				<hr/>
				<a href="<?php echo base_url( 'auth/wordpress/' . $domain_id ); ?>" title="" class="btn-color f7 no-underline pv1 ph3 br1 fr"><span class="white">VIEW MORE</span></a>
			</div>

	    </div>
	</div>

</div>

<div class="content-row">

	<div class="content-column w-100 w-50-l">
	    <div class="content-column-main content-col">
	        <div class="title">
	        	<div class="left-pos"><h3>DOMAIN UPTIME &amp; PERFORMANCE</h3></div>
	        	<div class="right-pos">
	        		<span class="domain-status white">Status: <?php
	        		if( 'DOWN' === $downTime['server_status'] ){ ?>
	        			<span class="br1 campaignsio-admin-bg-orange"><small>DOWN</small></span><?php 
	        		}
	        		elseif( 'UP' === $downTime['server_status'] ){ ?>
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

<input type="hidden" name="is_dashboard_page" value="1" />
<input type="hidden" name="base_url" value="<?php echo base_url(); ?>" />
<input type="hidden" name="domain_id" value="<?php echo $domain_id; ?>" />
<input type="hidden" name="domain_url" value="<?php echo $domain_data['domainUrl']; ?>" />
<input type="hidden" name="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
<input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_token_name(); ?>" />

<?php echo form_hidden( 'id_website_traffic_data', json_encode( $websiteTraffic['payload'] ) ); ?>
<?php echo form_hidden( 'id_uptime_performance_data', json_encode( $uptime_performance ) ); ?>
<?php echo form_hidden( 'id_uptime_data', json_encode( $uptime_data ) ); ?>

<?php require 'parts/bottom.php'; ?>