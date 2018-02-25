<div class="relative db w-100 ph2 pv1 cf">
	<h4 class="fw5 ma0 mv1 ph2 fl mr3">KEYWORD SEARCH ENGINE POSITIONS</h4>
	<span class="mv3-s ml0-s fl w-100 w-auto-ns fr-ns">
		<a href="#" title="" class="serp-keyword-add dib f7 btn-color no-underline pv2 ph3 br1"><span class="white">ADD KEYWORDS</span></a>
	</span>
</div>

<div data-tab-id="serps-tabs" class="content-row tab-contents-row">
     
    <div class="content-tab-items">
	    <ul>
			<li data-tab-item="1" class="w-25 w-auto-l <?php echo '1' === $serps['active_settings_tab'] ? 'active' : ''; ?>">CURRENT DATA</li>
			<li data-tab-item="2" class="w-25 w-auto-l <?php echo '2' === $serps['active_settings_tab'] ? 'active' : ''; ?>">GRAPHS</li>
			<li data-tab-item="3" class="w-25 w-auto-l <?php echo '3' === $serps['active_settings_tab'] ? 'active' : ''; ?>">EDIT KEYWORDS</li>
			<li data-tab-item="4" class="w-25 w-auto-l <?php echo '4' === $serps['active_settings_tab'] ? 'active' : ''; ?>">LINK WEBMASTER TOOLS</li>
		</ul>
	</div>

	<div class="content-column w-100">
		
		<div class="content-column-main">

			<div class="content-column-inner">

				<div data-tab-content="1" class="content-tab-content current-data-content <?php echo '1' === $serps['active_settings_tab'] ? 'active' : ''; ?>">
					
					<?php if( ! empty( $available_values['search_engines'] ) ){ ?>

						<ul class="serp-search-engines"><?php
							foreach ( $available_values['search_engines'] as $key => $val ) {
								?><li><a href="#" tile="" class="br-pill" data-engine-id="<?php echo $key; ?>"><img src="<?php  echo $val['flag']; ?>" alt="" class="br-pill" /><span><?php  echo $val['title']; ?></span></a></li><?php
							} ?>
						</ul>

						<hr style="margin-top:0.5rem;"/>

					<?php } ?>
					
					<div class="serp-report-wrapper">
						<?php echo $serps['report_html']; ?>
					</div>


					<span class="serp-report-loader hidden invisible">
						<span class="loading-icon"><span><i class="material-icons">&#xE86A;</i></span></span>
					</span>
		        </div>
				
            	<div data-tab-content="2" class="content-tab-content graphs-content <?php echo '2' === $serps['active_settings_tab'] ? 'active' : ''; ?>" style="overflow:visible;">
	            	
	            	<div class="relative w-100 w-40-l fr-l pl3-l cf">

		            	<div class="relative fl w-100">
		            		<label class="db mb2">Keyword:</label>
	                        <select class="graph-select-keyword w-100 pa2"><?php 
	                        	if( ! empty( $available_values['graph_keyword'] ) ){
	                        		foreach ( $available_values['graph_keyword'] as $key => $val ) {
	                        			$sel = $key . "" === $default_values['graph_keyword'] . "" ? ' selected="selected"': '';
	                        			?><option value="<?php echo $key; ?>" <?php echo $sel; ?>><?php echo $val; ?></option><?php
	                        		}
	                        	} ?>
	                        </select>
	                        <hr class=""/>
	                    </div>

	                    <div class="relative fl w-100">
		                        <label class="db mb2">Time Period:</label>
		                        <select class="graph-select-time w-100 pa2"><?php 
		                        	if( ! empty( $available_values['graph_time'] ) ){
		                        		foreach ( $available_values['graph_time'] as $key => $val ) {
		                        			$sel = $key . "" === $default_values['graph_time'] . "" ? ' selected="selected"': '';
		                        			?><option value="<?php echo $key; ?>" <?php echo $sel; ?>><?php echo $val; ?></option><?php
		                        		}
		                        	} ?>
		                        </select>
	                    	<hr class=""/>
	                    </div>

	                    <div class="relative fl w-100">
	                        <label class="db mb2">Search Engine:</label>
	                        <select class="graph-select-engine w-100 pa2"><?php 
	                        	if( ! empty( $available_values['graph_engine'] ) ){
	                        		foreach ( $available_values['graph_engine'] as $key => $val ) {
	                        			$sel = $key . "" === $default_values['graph_engine'] . "" ? ' selected="selected"': '';
	                        			?><option value="<?php echo $key; ?>" <?php echo $sel; ?>><?php echo $val; ?></option><?php
	                        		}
	                        	} ?>
	                        </select>
	                    </div>

                    </div>

                    <hr class="dn-l"/>

                    <div class="relative w-100  fl-l w-60-l" style="margin:0 auto;">

				        <div class="aspect-ratio aspect-ratio--16x9">
				        	<div class="aspect-ratio--object dt tc black-70 bg-white-10 fw1">
				        		<span class="dtc v-mid pa3 f5 f4-ns serp-keyword-graph white-60">Select Keyword, Time Period &amp; Search Engine</span>
				        	</div>
						</div>

					</div>
	            </div>

            	<div data-tab-content="3" class="content-tab-content edit-keywords-content <?php echo '3' === $serps['active_settings_tab'] ? 'active' : ''; ?>">
					
					<?php // TODO: ... ?
					/* ?>
            		<!-- <div class="cf">
            			<a href="#" title="" class="fl dib mb2 mr1 pv2 ph3 f7 btn-color btn-dark-br0 no-underline br1 white"><span class="">DELETE SELECTED KEYWORDS</span></a>
            		</div>            		
            		<hr/> -->
            		<?php */
            		?>            		

	            	<div class="list-table-wrap">
	            		<?php serp_edit_keywords_table( $serps['edit_keywords'] )  ?>
			        </div>
            	</div>

            	<div data-tab-content="4" class="content-tab-content link-tools-content <?php echo '4' === $serps['active_settings_tab'] ? 'active' : ''; ?>">
            		<div class="cf">
            			<div class="fr">
            				<div class="fl mh2 mb3" style="width:120px">
        						<span class="dib w-100 mb2">Start date</span>
        						<input type="text" name="key-search-start-date" value="<?php echo $keys_filters['selected']['start_date']; ?>" style="padding:11px; color:#C9C9C9;" />
            				</div>
            				<div class="fl mh2 mb3" style="width:120px">
        						<span class="dib w-100 mb2">End date</span>
        						<input type="text" name="key-search-end-date" value="<?php echo $keys_filters['selected']['end_date']; ?>" style="padding:11px; color:#C9C9C9;"/>
            				</div>
            				<div class="fl mh2 mb3" style="width:190px">
								<span class="db mb2">Dimension</span>
								<select name="key-search-dimension"><?php
									foreach($keys_filters['available']['search_dimensions'] as $k => $v){
										echo '<option value="' . $k . '"' . ( $k === $keys_filters['selected']['search_dimensions'] ? ' selected' : '') . '>' . $v . '</option>';
									} ?>
								</select>
							</div>
            				<div class="fl mh2 mb3" style="width:160px">
								<span class="db mb2">Type</span>
								<select name="key-search-type"><?php
									foreach($keys_filters['available']['search_types'] as $k => $v){
										echo '<option value="' . $k . '"' . ( $k === $keys_filters['selected']['search_types'] ? ' selected' : '') . '>' . $v . '</option>';
									} ?>
								</select>
							</div>
            			</div>
            			<a href="#" title="" class="serp-keyword-webmaster-add fl dib mb2 mr1 pv2 ph3 f7 btn-color btn-dark-br0 no-underline br1"><span class="">ADD WEBMASTER KEYWORDS</span></a>
            		</div>

            		<hr/>

            		<div id="serp-keywords-table-wrapper" class="list-table-wrap" style="min-height:140px;">
            			<?php echo serp_keywords_table_html( $serps['keywords'], 'query' ); ?>
			        </div>
            	</div>

			</div>

		</div>

	</div>

</div>

<div class="serp-add-keywords-layer hidden">
	<div class="serp-add-keywords-wrapper">
		<div class="serp-add-keywords-inner">
			<div class="serp-add-keywords-form cf">
				<span class="white fw3 f4">ADD KEYWORDS <small>(one keyword per line)</small></span>
				<br/><br/>
				<textarea class="serp-add-keywords-textarea w-100 mb3"></textarea>
				<span class="mv3-s ml0-s fl w-100 w-auto-ns fr-ns">
					<a href="#" title="" class="serp-keywords-save dib f7 btn-color no-underline pv2 ph3 br1"><span class="white">SAVE KEYWORDS</span></a>
				</span>
				<button type="button" class="close close-add-keywords-layer" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
		</div>	
	</div>
</div>

<?php echo form_hidden( 'report_url', $serps['report_url'] ); ?>
<?php echo form_hidden( 'keyword_remove_url', $serps['keyword_remove_url'] ); ?>
<?php echo form_hidden( 'keyword_overall_url', $serps['keyword_overall_url'] ); ?>
<?php echo form_hidden( 'keyword_add_url', $serps['keyword_add_url'] ); ?>
<?php echo form_hidden( 'keyword_add_webmaster_tool_url', $serps['keyword_add_webmaster_tool_url'] ); ?>
<?php echo form_hidden( 'filter_keywords_url', $serps['filter_keywords_url'] ); ?>