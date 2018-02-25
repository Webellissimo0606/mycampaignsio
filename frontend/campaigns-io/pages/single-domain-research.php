<?php

?>
<div class="content-row tab-contents-row">

	<h3 class="fw4 ph2">KEYWORD RESEARCH</h3>
     
    <div class="content-tab-items">
	    <ul>
			<li data-tab-item="1" class="active w-25 w-auto-l">OVERVIEW</li>
			<li data-tab-item="2" class="w-25 w-auto-l">POSITIONS</li>
			<li data-tab-item="3" class="w-25 w-auto-l">KEYWORD SELECTION</li>
			<li data-tab-item="4" class="w-25 w-auto-l">DOMAIN VS DOMAIN</li>
		</ul>
	</div>

	<div class="content-column w-100">
		
		<div class="content-column-main">

			<div class="content-column-inner">

				<div data-tab-content="1" class="content-tab-content active">
					
					<div class="content-row">

						<div class="content-column w-100 w-50-m w-25-l">
						    <div class="content-column-main content-col">
						        <div class="content-column-inner">

							        <div class="circle-stat campaignsio-admin-border-green" style="margin-bottom:0">
										<div class="aspect-ratio aspect-ratio--1x1">
											<div class="dt aspect-ratio--object">
												<div class="dtc tc v-mid"><span><small><?php echo $overview['info']['visibility']; ?></small></span>VISIBILITY</div>
											</div>
										</div>
									</div>

						        </div>
						    </div>
						</div>

						<div class="content-column w-100 w-50-m w-25-l">
						    <div class="content-column-main content-col">
						        <div class="content-column-inner">
				                    <div class="circle-stat campaignsio-admin-border-yellow" style="margin-bottom:0">
										<div class="aspect-ratio aspect-ratio--1x1">
											<div class="dt aspect-ratio--object">
												<div class="dtc tc v-mid"><span><small><?php echo $overview['info']['traffic']; ?></small></span>SE TRAFFIC</div>
											</div>
										</div>
									</div>
						        </div>
						    </div>
						</div>

						<div class="content-column w-100 w-50-m w-25-l">
						    <div class="content-column-main content-col">
						        <div class="content-column-inner">
				                    <div class="circle-stat campaignsio-admin-border-orange" style="margin-bottom:0">
										<div class="aspect-ratio aspect-ratio--1x1">
											<div class="dt aspect-ratio--object">
												<div class="dtc tc v-mid"><span><small><?php echo $overview['info']['organic_keywords']; ?></small></span>ORGANIC <br/>KEYWORDS</div>
											</div>
										</div>
									</div>
				                </div>
						    </div>
						</div>

						<div class="content-column w-100 w-50-m w-25-l">
						    <div class="content-column-main content-col">
						        <div class="content-column-inner">
							        <div class="circle-stat campaignsio-admin-border-action-color" style="margin-bottom:0">
										<div class="aspect-ratio aspect-ratio--1x1">
											<div class="dt aspect-ratio--object">
												<div class="dtc tc v-mid"><span><small><?php echo $overview['info']['ppc']; ?></small></span>KEYWORDS <br/>IN PPC</div>
											</div>
										</div>
									</div>
						        </div>
						    </div>
						</div>

					</div>

					<br/>

					<div class="content-row">

						<div class="content-column w-100 w-50-l">
						
						    <div class="content-column-main content-col">
						        <div class="title">
						        	<div class="left-pos">
						        		<h3>ORGANIC KEYWORDS</h3>
						        	</div>
						        </div>

						        <div class="content-column-inner">
						            
						            <div class="list-table-wrap">

							            <table class="filter-table list-table mv3 collapse tc">
							        		<thead>
							        			<tr>
						            				<th class="tl">KEYWORDS</th>
						            				<th>POSITION</th>
						            				<th>GOOGLE VOLUME</th>
						            				<th class="nowrap">CPC $</th>
							        			</tr>
							        		</thead>
							        		<tbody><?php 
							        			if( empty( $overview['organic_keywords'] ) ){ ?>
							        				<tr><td colspan="4">No data available</td></tr>
													<?php
							        			}
							        			else{
							        				foreach( $overview['organic_keywords'] as $k => $v ){?>
								        				<tr>
									        				<td class="tl"><?php echo $v['keyword']; ?></td>
									        				<td><?php echo $v['position']; ?></td>
									        				<td><?php echo $v['concurrency']; ?></td>
									        				<td><?php echo $v['cost']; ?></td>
								        				</tr><?php
								        			}
							        			} ?>
							        		</tbody>
							        	</table>

						        	</div>

						        </div>
						    </div>
						</div>

						<div class="content-column w-100 w-50-l">
							<div class="content-column-main content-col">
						        
						        <div class="title">
						        	<div class="left-pos">
						        		<h3>ADS KEYWORDS</h3>
						        	</div>
						        </div>

						        <div class="content-column-inner">
						            
						            <div class="list-table-wrap">

							            <table class="filter-table list-table mv3 collapse tc">
							        		<thead>
							        			<tr>
						            				<th class="tl">KEYWORD</th>
						            				<th>TITLE</th>
						            				<th>POSITION</th>
						            				<th>CONCURRENCY</th>
						            				<th>FOUND RESULTS</th>
							        			</tr>
							        		</thead>
							        		<tbody><?php 
							        			if( empty( $overview['add_keywords'] ) ){ ?>
							        				<tr><td colspan="5">No data available</td></tr>
													<?php
							        			}
							        			else{
								        			foreach( $overview['add_keywords'] as $k => $v ){?>
								        				<tr>
									        				<td class="tl"><?php echo $v['keyword']; ?></td>
									        				<td><?php echo $v['title']; ?></td>
									        				<td><?php echo $v['position']; ?></td>
									        				<td><?php echo $v['concurrency']; ?></td>
									        				<td><?php echo $v['found_results']; ?></td>
								        				</tr><?php
								        			}
							        			} ?>
							        		</tbody>
							        	</table>

						        	</div>

						        </div>
						    </div>
						</div>

					</div>

					<div class="content-row">
						
						<div class="content-column w-100 w-50-l">
						
						    <div class="content-column-main content-col">
						        
						        <div class="title">
						        	<div class="left-pos">
						        		<h3>KEYWORD POSITION DISTRIBUTION</h3>
						        	</div>
						        </div>

						        <div class="content-column-inner">
						            <div class="aspect-ratio aspect-ratio--16x9">
							        	<div class="aspect-ratio--object dt tc black-70 bg-white-10 fw1 f4 f3-ns">
							        		<span class="dtc v-mid pa3">
							        			<canvas id="keyword_position_distribution_graph"></canvas>
							        		</span>
							        	</div>
									</div>
						        </div>
						    </div>
						</div>

						<div class="content-column w-100 w-50-l">
						
						    <div class="content-column-main content-col">
						        
						        <div class="title">
						        	<div class="left-pos">
						        		<h3>SUBDOMAINS</h3>
						        	</div>
						        </div>

						        <div class="content-column-inner">
						            
						            <div class="list-table-wrap">

							            <table class="filter-table list-table mv3 collapse tc">
							        		<thead>
							        			<tr>
						            				<th class="tl">SUBDOMAIN</th>
						            				<th>NUMBER OF KEYWORDS</th>
							        			</tr>
							        		</thead>
							        		<tbody>
							        			<tr><td colspan="2">No data available</td></tr>
							        			<?php /* ?>
							        			<tr>
						            				<td class="tl">subdomain1.domain.com</td>
						            				<td>3</td>
							        			</tr>
							        			<tr>
						            				<td class="tl">subdomain2.domain.com</td>
						            				<td>2</td>
							        			</tr>
							        			<tr>
						            				<td class="tl">subdomain3.domain.com</td>
						            				<td>5</td>
							        			</tr>
							        			<tr>
						            				<td class="tl">subdomain4.domain.com</td>
						            				<td>7</td>
							        			</tr>
							        			<tr>
						            				<td class="tl">subdomain5.domain.com</td>
						            				<td>5</td>
							        			</tr>
							        			<tr>
						            				<td class="tl">subdomain6.domain.com</td>
						            				<td>2</td>
							        			</tr>
							        			<?php */ ?>
							        		</tbody>
							        	</table>

						        	</div>

						        </div>
						    </div>
						</div>

					</div>

					<div class="content-row">

						<div class="content-column w-100">
						
						    <div class="content-column-main content-col">
						        
						        <div class="title">
						        	<div class="left-pos">
						        		<h3>PAGES VISIBILITY</h3>
						        	</div>
						        </div>

						        <div class="content-column-inner">

						        	<div class="list-table-wrap">

							            <table class="filter-table list-table mv3 collapse tc">
							            	<thead>
							        			<tr>
						            				<th data-sortable="false" class="tl" style="padding:0 0.75rem;">PAGE URL</th>
						            				<th>CTR</th>
						            				<th>CLICKS</th>
						            				<th>IMPRESSIONS</th>
						            				<th>POSITION</th>
							        			</tr>
							        		</thead>
							        		<tbody><?php 
							        			if( empty( $overview['pages_visibility'] ) ){ ?>
							        				<tr><td colspan="5">No data available</td></tr>
													<?php
							        			}
							        			else{
								        			foreach( $overview['pages_visibility'] as $k => $v ){?>
								        				<tr>
													        <td class="tl" style="padding:0 0.75rem;"><a href="<?php echo $v['page']; ?>" title="" target="_blank" class="link white"><?php echo $v['page']; ?></a></td>
													        <td><?php echo round( $v['ctr'], 3 ); ?></td>
													        <td><?php echo $v['clicks']; ?></td>
													        <td><?php echo $v['impressions']; ?></td>
													        <td><?php echo round( $v['position'], 3 ); ?></td>
								        				</tr><?php
								        			}
							        			} ?>
							        		</tbody>
							        	</table>

						        	</div>
						            
						        </div>
						    </div>
						</div>

					</div>

					<div class="content-row">

						<div class="content-column w-100">
						
						    <div class="content-column-main content-col">
						        
						        <div class="title">
						        	<div class="left-pos">
						        		<h3>COMPETITORS IN ORGANIC SEARCH</h3>
						        	</div>
						        </div>

						        <div class="content-column-inner">
						            
						            <div class="list-table-wrap">

							            <table class="filter-table list-table mv3 collapse tc">
							        		<thead>
							        			<tr>
							        				<th style="width:1px;">#</th>
						            				<th class="tl">DOMAIN</th>
						            				<th>ALL KEYWORDS</th>
						            				<th>TRAFFIC DYNAMIC</th>
						            				<th>VISIBLE DYNAMIC</th>
						            				<th>KEYWORD DYNAMIC</th>
							        			</tr>
							        		</thead>
							        		<tbody><?php 
							        			if( empty( $overview['competitors_trend'] ) ){ ?>
							        				<tr><td colspan="6">No data available</td></tr>
													<?php
							        			}
							        			else{
							        				$cntr = 1;
								        			foreach( $overview['competitors_trend'] as $k => $v ){?>
								        				<tr>
									        				<td><?php echo $cntr; ?></td>
													        <td class="tl"><a href="http://<?php echo $v['domain']; ?>" title="" target="_blank" class="link white"><?php echo $v['domain']; ?></a></td>
													        <td><?php echo $v['keywords']; ?></td>
													        <td><?php echo $v['traff_dynamic']; ?></td>
													        <td><?php echo round( $v['visible_dynamic'], 9 ); ?></td>
													        <td><?php echo $v['keywords_dynamic']; ?></td>
								        				</tr><?php
								        				$cntr++;
								        			}
							        			} ?>
							        		</tbody>
							        	</table>

						        	</div>

						        </div>
						    </div>
						</div>
						
					</div>

					<div class="content-row">

						<div class="content-column w-100">
						
						    <div class="content-column-main content-col">
						        
						        <div class="title">
						        	<div class="left-pos">
						        		<h3>COMPETITORS GRAPH</h3>
						        	</div>
						        </div>

						        <div class="content-column-inner">
						            <div class="aspect-ratio aspect-ratio--16x9">
							        	<div class="aspect-ratio--object dt tc black-70 bg-white-10 fw1 f4 f3-ns">
							        		<span class="dtc v-mid pa3">
							        			<canvas id="competitors_graph_data" style="max-height:100rem !important;"></canvas>
							        		</span>
							        	</div>
									</div>
						        </div>
						    </div>
						</div>
						
					</div>

					<div class="content-row">

						<div class="content-column w-100 w-50-l">
						
						    <div class="content-column-main content-col">
						        
						        <div class="title">
						        	<div class="left-pos">
						        		<h3>VISIBILITY TREND</h3>
						        	</div>
						        </div>

						        <div class="content-column-inner">
						            
						            <div class="cf">

							        	<div class="relative fl w-100 w-50-ns pr2-ns mb3">
									        <div class="bold-stat-num">
						                        <small>
							                        <span class="stat-num campaignsio-admin-green"><small><?php echo $overview['trends']['visibility']['max_trend']; ?></small></span>
							                        <span class="stat-label">MAXIMUM NUMBER: <?php echo $overview['trends']['visibility']['max_trend_date']; ?></span>
						                        </small>
						                    </div>
						                </div>

						                <div class="relative fl w-100 w-50-ns pl2-ns mb3">
									        <div class="bold-stat-num">
						                        <small>
						                        	<span class="stat-num campaignsio-admin-action-color"><small><?php echo $overview['trends']['visibility']['min_trend']; ?></small></span>
						                        	<span class="stat-label">MINIMUM NUMBER: <?php echo $overview['trends']['visibility']['min_trend_date']; ?></span>
						                        </small>
						                    </div>
						                </div>

					                </div>

					                <div class="aspect-ratio aspect-ratio--4x3">
							        	<div class="aspect-ratio--object dt tc black-70 bg-white-10 fw1 f4 f3-ns">
							        		<span class="dtc v-mid pa3">
							        			<canvas id="visibility_trend_chart"></canvas>
							        		</span>
							        	</div>
									</div>

						        </div>
						    </div>
						</div>

						<div class="content-column w-100 w-50-l">
						
						    <div class="content-column-main content-col">
						        
						        <div class="title">
						        	<div class="left-pos">
						        		<h3>KEYWORDS TREND</h3>
						        	</div>
						        </div>

						        <div class="content-column-inner">

						        	<div class="cf">

							        	<div class="relative fl w-100 w-50-ns pr2-ns mb3">
									        <div class="bold-stat-num">
						                        <small>
							                        <span class="stat-num campaignsio-admin-green"><small><?php echo $overview['trends']['keywords']['max_trend']; ?></small></span>
							                        <span class="stat-label">MAXIMUM NUMBER: <?php echo $overview['trends']['keywords']['max_trend_date']; ?></span>
						                        </small>
						                    </div>
						                </div>

						                <div class="relative fl w-100 w-50-ns pl2-ns mb3">
									        <div class="bold-stat-num">
						                        <small>
						                        	<span class="stat-num campaignsio-admin-action-color"><small><?php echo $overview['trends']['keywords']['min_trend']; ?></small></span>
						                        	<span class="stat-label">MINIMUM NUMBER: <?php echo $overview['trends']['keywords']['min_trend_date']; ?></span>
						                        </small>
						                    </div>
						                </div>

					                </div>
						            
						            <div class="aspect-ratio aspect-ratio--4x3">
							        	<div class="aspect-ratio--object dt tc black-70 bg-white-10 fw1 f4 f3-ns">
							        		<span class="dtc v-mid pa3">
							        			<canvas id="keywords_trend_chart"></canvas>
							        		</span>
							        	</div>
									</div>

						        </div>
						    </div>
						</div>
						
					</div>

					<?php /* ?>

					<div class="content-row">

						<div class="content-column w-100">
						
						    <div class="content-column-main content-col">
						        
						        <div class="title">
						        	<div class="left-pos">
						        		<h3>BACKLINKS OVERVIEW</h3>
						        	</div>
						        </div>

						        <div class="content-column-inner">
						            
						        </div>
						    </div>
						</div>
						
					</div>

					<div class="content-row">

						<div class="content-column w-100">
						
						    <div class="content-column-main content-col">
						        
						        <div class="title">
						        	<div class="left-pos">
						        		<h3>NEW BACKLINKS</h3>
						        	</div>
						        </div>

						        <div class="content-column-inner" style="padding-bottom:0;">
						            
						             <div class="list-table-wrap">

							            <table class="filter-table list-table mv3 collapse tc">
							        		<thead>
							        			<tr>
							        				<th style="width:1px;">#</th>
						            				<th class="tl">DONOR URL</th>
						            				<th colspan="2" style="padding:0;">
						            					<table class="w-100 tc">
						            						<thead>
								            					<tr>
								            						<th colspan="2" style="padding-bottom:0;">DONOR FLOW METRICS</th>
								            					</tr>
								            					<tr>
								            						<th>SERPSTAT PAGE RANK</th>
								            						<th>SERPSTAT TRUST RANK</th>
								            					</tr>
							            					</thead>
						            					</table>
						            				</th>
						            				<th>LINK TYPE</th>
						            				<th>FIRST INDEXED<br/><span class="campaignsio-admin-action-color">LAST INDEXED</span></th>
							        			</tr>
							        		</thead>
							        		<tbody>
							        			<tr>
							        				<td colspan="6">No data available</td>
							        			</tr>
							        			<?php
							        			// <tr>
							        			// 	<td>1</td>
							        			// 	<td class="tl">
							        			// 		<ul class="list ma0 pa0">
								        		// 			<li class="pv1"><a href="#" title="" target="_blank" class="no-underline underline-hover white">https://www.myirelandt...</a></li>
								        		// 			<li class="pv1"><a href="http://shinepics.co.uk" title="" target="_blank" class="no-underline underline-hover white">shinepics.co.uk</a></li>
							        			// 		</ul>
							        			// 	</td>
						            			// 	<td>34</td>
						            			// 	<td>27</td>
						            			// 	<td>Follow</td>
						            			// 	<td>08 Mar 2017<br/><span class="campaignsio-admin-action-color">07 Jun 2017</span></td>
							        			// </tr>
							        			?>
							        		</tbody>
							        	</table>

						        	</div>

						        </div>
						    </div>
						</div>
						
					</div>

					<?php */ ?>

					<div class="content-row">

						<div class="content-column w-100">
						
						    <div class="content-column-main content-col">
						        
						        <div class="title">
						        	<div class="left-pos">
						        		<h3>REFFERERS LINKS</h3>
						        	</div>
						        </div>

						        <div class="content-column-inner" style="padding-bottom:0;">
						            
						             <div class="list-table-wrap">

							            <table class="filter-table list-table mv3 collapse tc">
							        		<thead>
							        			<tr>
							        				<th data-sortable="false" style="width:1px;">#</th>
						            				<th class="tl">REFFERER</th>
				            						<th>CLICKS</th>
				            						<th>DATE</th>
							        			</tr>
							        		</thead>
							        		<tbody><?php 
							        			if( empty( $overview['referrers_links'] ) ){ ?>
							        				<tr><td colspan="4">No data available</td></tr>
													<?php
							        			}
							        			else{
							        				$cntr = 1;
								        			foreach( $overview['referrers_links'] as $k => $v ){?>
								        				<tr>
									        				<td><?php echo $cntr; ?></td>
													        <td class="tl"><?php echo $v['refferer']; ?></td>
													        <td><?php echo $v['clicks']; ?></td>
													        <td><?php echo $v['date']; ?></td>
								        				</tr><?php
								        				$cntr++;
								        			}
							        			} ?>
							        		</tbody>
							        	</table>

						        	</div>

						        </div>
						    </div>
						</div>
						
					</div>

		        </div>

            	<div data-tab-content="2" class="content-tab-content">
            		
            		<?php if( ! empty( $available_values['search_engines'] ) ){ ?>

						<ul class="serp-search-engines"><?php
							foreach ( $available_values['search_engines'] as $key => $val ) {
								?><li><a href="#" tile="" class="br-pill" data-engine-id="<?php echo $key; ?>"><img src="<?php  echo $val['flag']; ?>" alt="" class="br-pill" /><span><?php  echo $val['title']; ?></span></a></li><?php
							} ?>
						</ul>

						<hr style="margin-top:0.5rem;"/>

					<?php } ?>

					<?php echo $positions['serp_report_html']; ?>

		        </div>

		        <div data-tab-content="3" class="content-tab-content">

		        	<?php if( ! empty( $available_values['search_engines'] ) ){ ?>

						<ul class="serp-search-engines"><?php
							foreach ( $available_values['search_engines'] as $key => $val ) {
								?><li><a href="#" tile="" class="br-pill" data-engine-id="<?php echo $key; ?>"><img src="<?php  echo $val['flag']; ?>" alt="" class="br-pill" /><span><?php  echo $val['title']; ?></span></a></li><?php
							} ?>
						</ul>

						<hr style="margin-top:0.5rem;"/>

					<?php } ?>

					<div class="list-table-wrap">

			            <table class="filter-table list-table mv3 collapse tc">
			        		<thead>
			        			<tr>
		            				<th data-sortable="false" class="tl">#</th>
		            				<th>KEYWORDS</th>
		            				<th>POSITION</th>
		            				<th>GOOGLE VOLUME</th>
		            				<th>COST</th>
		            				<th>COMPETITION IN PPC</th>
		            				<th>RESULTS</th>
		            				<th>SOCIAL DOMAINS</th>
			        			</tr>
			        		</thead>
			        		<tbody>
			        			<tr><td colspan="8">No data available</td></tr>
			        		</tbody>
			        	</table>

		        	</div>
				
		        </div>

		        <div data-tab-content="4" class="content-tab-content">
				
					<div class="content-column-main content-col">
				        
				        <div class="title" style="padding-top:0;">
				        	<h3>COMPARE DOMAIN WITH COMPETITOR</h3>
				        </div>

				        <div class="relative dib w-100 mt4">
							<input type="text" name="domain_compare_input" placeholder="Competitor's Domain" class="w-30 pa3" />
							<button class="domain-compare-btn btn-color f6 br1 ml3 white" style="padding:1rem 1.5rem">COMPARE</button>
						</div>

						<div class="domains-comparison-wrap relative dib w-100">
							
							<div class="domains-comparison-inner">

							</div>

						</div>

						<span class="domains-comparison-loader serp-report-loader pt4 hidden invisible">
							<span class="loading-icon"><span><i class="material-icons">&#xE86A;</i></span></span>
						</span>

				    </div>

		        </div>

			</div>

		</div>

	</div>

</div>

<?php echo form_hidden( 'domain_compare_url', $domain_compare_url ); ?>
<?php echo form_hidden( 'competitors_graph_data', $overview['competitors_graph_data'] ); ?>
<?php echo form_hidden( 'keyword_position_distribution', $overview['keyword_position_distribution'] ); ?>


<?php echo form_hidden( 'trends_date_data', json_encode( $overview['trends']['dates'] ) ); ?>
<?php echo form_hidden( 'trends_visibility_data', json_encode( $overview['trends']['visibility']['results'] ) ); ?>
<?php echo form_hidden( 'trends_keywords_data', json_encode( $overview['trends']['keywords']['results'] ) ); ?>