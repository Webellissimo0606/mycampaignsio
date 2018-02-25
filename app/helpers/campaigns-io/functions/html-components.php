<?php
function user_navigation( $current_page = '', $user_parent_id = null ){

	if( ! $user_parent_id ){
		$user_parent_id = isset( $_SESSION['parent_id'] ) ? $_SESSION['parent_id'] : false;
	}

	$items = array(
	    array(
	        'id' => 'profile',
	        'title' => 'My profile',
	        'icon' => '&#xE851;',
	        'link' => base_url('auth/profile')
	    )
	);

	if( ! $user_parent_id ){

		$items[] = array(
	        'id' => 'domains-assign',
	        'title' => 'Assign domains',
	        'icon' => '&#xE862',
	        'link' => base_url('assigndomain')
	    );

		$items[] = array(
	        'id' => 'sub-users',
	        'title' => 'List Subuser',
	        'icon' => '&#xE7EF',
	        'link' => base_url('listsubuser')
	    );

		$items[] = array(
	        'id' => 'groups',
	        'title' => 'List Group',
	        'icon' => '&#xE241',
	        'link' => base_url('listgroups')
	    );
	}

	$items[] = array(
        'id' => 'domains',
        'title' => 'My domains',
        'icon' => '&#xE85D',
        'link' => base_url('domains')
    );

    $items[] = array(
        'id' => 'logout',
        'title' => 'Logout',
        'icon' => '&#xE879;',
        'link' => base_url('auth/logout')
    );
	?>
	<nav class="author-nav">
	    <?php
	    foreach ($items as $k => $v) {
	    	$classAttr = $current_page === $v['id'] ? ' class="active"': '';
	    	?>
	        <a href="<?php echo $v['link']; ?>" title="<?php echo $v['title']; ?>" <?php echo $classAttr; ?>>
	            <i class="material-icons"><?php echo $v['icon']; ?></i>
	            <span><?php echo $v['title']; ?></span>
	        </a><?php
	    }
	    ?>
	</nav><?php
}

function main_navigation( $current_page = '' ){
	$items = array(
		array(
			'id' => 'business-overview',
			'title' => 'Business Overview',
			'icon' => '&#xE871;',
			'link' => base_url('panel'),
		),
		/*array(
			'id' => 'listproducts',
			'title' => 'List Products',
			'icon' => '&#xE871;',
			'link' => base_url('listproduct'),
		),*/
		/*array(
			'id' => 'crm',
			'title' => 'CRM',
			'icon' => '&#xE871;',
			'link' => base_url('panel'),
		),*/
		/*array(
			'id' => 'websites',
			'title' => 'Websites',
			'icon' => '&#xE051;',
			'link' => '#',
		),*/
		array(
			'id' => 'domains',
			'title' => 'Domains',
			'icon' => '&#xE85D;',
			'link' => base_url('domains'),
		),
		array(
			'id' => 'uptime-report',
			'title' => 'Uptime',
			'icon' => '&#xE922;',
			'link' => base_url('uptime/report'),
		),
		array(
			'id' => 'backups',
			'title' => 'Backups',
			'icon' => '&#xE149;',
			'link' => base_url('auth/backups'),
		),
		array(
			'id' => 'seo',
			'title' => 'SEO',
			'icon' => '&#xE880;',
			'link' => '#',
		),
		array(
			'id' => 'security',
			'title' => 'Security',
			'icon' => '&#xE32A;',
			'link' => '#',
		),
		array(
			'id' => 'backlink_manager',
			'title' => 'Backlink manager',
			'icon' => '&#xE157;',
			'link' => base_url('listlinkdomain'),
		),
		array(
			'id' => 'seo_tasks',
			'title' => 'SEO Tasks',
			'icon' => '&#xE880;',
			'link' => base_url('seoreporting/getprojects'),
		)
	);
	?>
	<nav class="main-nav f6 fw5"><?php
		foreach ($items as $k => $v) {
			$classAttr = $current_page === $v['id'] ? ' class="active"' : '';
			?>
	        <a href="<?php echo $v['link']; ?>" title="<?php echo $v['title']; ?>" <?php echo $classAttr; ?>>
	            <i class="material-icons"><?php echo $v['icon']; ?></i>
	            <span><?php echo $v['title']; ?></span>
	        </a><?php
		} ?>
	    <a href="#" title="" class="collapse-expand">
	        <i class="material-icons collapse">&#xE5C4;</i>
	        <i class="material-icons expand">&#xE5C8;</i>
	    </a>
	</nav>
	<span class="toggle-author-nav when-collapsed"><i class="material-icons">&#xE5D2;</i></span>
	<span class="toggle-author-nav when-expanded"><i class="material-icons">&#xE5CD;</i></span>
	<?php
}

function domain_navigation( $current_page = '', $domain_id = 0, $user_domains = array()){

	if( 0 === $domain_id ){
	    return;
	}
		if( ! empty( $user_domains ) ){ ?>
	    <div class="select-domain mt1 mb3">
	        <select class="pv2 ph1 search-and-select">
	        <?php
	        foreach( $user_domains as $k => $v ){
	            switch($current_page){
	                case 'single-domain':
	                    $op_val = base_url( 'domains/' . $v->id );
	                    break;
	                case 'single-domain-analytics':
	                    $op_val = base_url( 'domains/' . $v->id . '/analytics' );
	                    break;
	                case 'single-domain-wordpress':
	                    $op_val = base_url( 'domains/' . $v->id . '/wordpress' );
	                    break;
	                case 'single-domain-serps':
	                // case 'view-serps':
	                	// $op_val = base_url( 'auth/viewSerp/' . $v->id );
	                    $op_val = base_url( 'domains/' . $v->id . '/serps' );
	                    break;
	                case 'single-domain-heatmaps':
	                    $op_val = base_url( 'domains/' . $v->id . '/heatmaps' );
	                    break;
	                case 'single-domain-research':
	                    $op_val = base_url( 'domains/' . $v->id . '/research' );
	                    break;
	                case 'single-domain-e-commerce':
	                    $op_val = base_url( 'domains/' . $v->id . '/e-commerce' );
	                    break;
	            }
	            $op_sel = $domain_id === (int) $v->id ? ' selected="selected"': "";
	            $op_name = $v->domain_name;
	            ?><option value="<?php echo html_escape($op_val); ?>" <?php echo $op_sel; ?>><?php echo $op_name; ?></option><?php
	        } ?>
	        </select>
	    </div>
	    <?php
	}


}

function user_domain_navigation($current_page = '', $domain_id = 0 )
{
	$items = array(
	    array(
	        'id' => 'single-domain',
	        'title' => 'Overview',
	        'link' => base_url( 'domains/' . $domain_id )
	    ),
	    array(
	        'id' => 'single-domain-serps',
	        'title' => 'SERPS',
	        'link' => base_url( 'domains/' . $domain_id . '/serps' )
	    ),
	    // TODO: Remove 'view-serps'.
	    /*array(
	        'id' => 'view-serps',
	        'title' => 'SERPS',
	        'link' => base_url( 'auth/viewSerp/' . $domain_id )
	    ),*/
	    array(
	        'id' => 'single-domain-research',
	        'title' => 'Research',
	        'link' => base_url( 'domains/' . $domain_id . '/research' )
	    ),
	    array(
	        'id' => 'single-domain-analytics',
	        'title' => 'Analytics',
	        'link' => base_url( 'domains/' . $domain_id . '/analytics' )
	    ),
	    array(
	        'id' => 'single-domain-e-commerce',
	        'title' => 'E-Commerce',
	        'link' => base_url( 'domains/' . $domain_id . '/e-commerce' )
	    ),
	    array(
	        'id' => 'single-domain-heatmaps',
	        'title' => 'Heatmaps',
	        'link' => base_url( 'domains/' . $domain_id . '/heatmaps' )
	    )
	);
	?>
	<nav><?php
	    foreach ($items as $k => $v) {
	    	$classAttr = $current_page === $v['id'] ? ' class="active"' : ''; ?>
	        <a href="<?php echo $v['link']; ?>" title="<?php echo $v['title']; ?>" <?php echo $classAttr; ?>><?php echo $v['title']; ?></a><?php
	    } ?>
	</nav>
<?php
}

function edit_domain_form_component( $domain_id, $user_id, $data, $available_values, $google_client_id, $google_oauth_redirect_uri ){
    ?>

    <?php if( isset( $data['submission_failed_html'] ) ){ echo $data['submission_failed_html']; } ?>

    <ul class="edit-site-tabs f6-m f5">
        <li data-tab="1" class="active w-third"><span class="num f3-m f2">1</span><span>Domain info</span></li>
        <li data-tab="2" class="w-third"><span class="num f3-m f2">2</span><span>Connections</span></li>
        <li data-tab="3" class="w-third"><span class="num f3-m f2">3</span><span>Monitor Keyword SERP</span></li>
    </ul>

    <?php echo form_open( base_url( "domains/save" ), array( "class" => "edit-profile-form edit-site-form cf mt3" ) ); ?>

        <div class="edit-site-form-content-wrap mb3 pt3">

            <div class="edit-site-form-inner active-1">

                <div class="edit-site-form-section ph3 cf">

                	<div class="relative dib w-100 cf">

	                    <div class="fl pa2 pt2-l pr3-l pl2-l w-100 w-50-l">

	                        <div class="field">
	                            <label for="domain_name">Domain name <small class="fw4 o-90 campaignsio-admin-orange">(required)</small></label>
	                            <div class="tooltip">
	                                <?php echo form_input( array( "name" => "domain_name", "value" => $data['domain_name'], "placeholder" => "Domain name" ) ); ?>
	                                <span class="tooltiptext" style="width:170px;">Please include  http://  or  https://</span>
	                            </div>
	                        </div>

	                        <div class="field mt3 mt4-l">
	                            <div class="checkbox-wrap" style="padding-bottom:0.125rem;">
	                                <span>Is your site ecommerce site ?</span>
	                                <?php checkbox_component('is_ecommerce', 1 === $data['is_ecommerce']); ?>
	                            </div>
	                        </div>

	                    </div>

	                    <div class="fl pa2 pt2-l pr2-l pl3-l w-100 w-50-l">

	                    	<div class="field">
	                            <label for="subusers">Assign subusers to domain</label> <?php
	                            echo form_dropdown( 'subusers[]', $available_values['subusers_values'], $data['subusers'], array( 'multiple' => "multiple", 'placeholder' => "Choose subusers", 'class' => "multi-select-box" ) ); ?>
	                        </div>

	                        <div class="field">
	                            <label for="groups">Choose group to assign domain</label>
	                            <?php echo form_dropdown( 'groups[]', $available_values['group_values'], $data['groups'], array( 'multiple' => "multiple", 'placeholder' => "Choose groups", 'class' => "multi-select-box" ) ); ?>
	                        </div>

	                    </div>

	                </div>

	                <hr/>

                    <div class="relative dib w-100 cf">

	                    <div class="fl pa2 pt2-l pr3-l pl2-l w-100 w-50-l">

	                        <div class="field mt3 mt4-l">
	                            <div class="checkbox-wrap" style="padding-bottom:0.125rem;">
	                                <span>Would you like to Monitor website uptime?</span>
	                                <?php checkbox_component('monitor_website_uptime', 1 === $data['monitor_website_uptime'] ); ?>
	                            </div>
	                        </div>

	                        <div class="field monitor_uptime_freq_field">
	                            <label for="frequency">Frequency</label>
	                            <?php echo form_dropdown( 'frequency', $available_values['frequencies'], $data['frequency'] ); ?>
	                        </div>

	                    </div>

	                    <div class="fl pa2 pt2-l pr2-l pl3-l w-100 w-50-l">

	                        <div class="field monitor_uptime_page_header_field">
	                            <label for="page_header">Page Header</label>
	                            <?php echo form_input( array( "name" => "page_header", "value" => $data['page_header'], "placeholder" => "Page Header to Search" ) ); ?>
	                        </div>

	                        <div class="field monitor_uptime_page_body_field">
	                            <label for="page_body">Page Body</label>
	                            <?php echo form_input( array( "name" => "page_body", "value" => $data['page_body'], "placeholder" => "Page Body to Search" ) ); ?>
	                        </div>

	                        <div class="field monitor_uptime_page_footer_field">
	                            <label for="page_footer">Page Footer</label>
	                           	<?php echo form_input( array( "name" => "page_footer", "value" => $data['page_footer'], "placeholder" => "Page Footer to Search" ) ); ?>
	                        </div>

	                    </div>

                    </div>

                </div>

                <div class="edit-site-form-section active ph3 cf">

                    <div class="fl pa2 pt2-l pr3-l pl2-l w-100 w-50-l">

                        <div class="field">
                            <label for="adminURL">Admin Login URL <small class="fw4 o-90 campaignsio-admin-orange">(required)</small></label>
                            <?php echo form_input( array( "name" => "adminURL", "value" => $data['adminURL'], "placeholder" => "http://" ) ); ?>
                        </div>

                        <div class="field">
                            <label for="adminUsername">Admin Username <small class="fw4 o-90 campaignsio-admin-orange">(required)</small></label>
                            <?php echo form_input( array( "name" => "adminUsername", "value" => $data['adminUsername'], "placeholder" => "Admin Username" ) ); ?>
                        </div>

                        <div class="field mt3">
                            <div class="checkbox-wrap" style="padding-bottom:0.125rem;">
                                <span>Monitor Malware</span>
                                <?php checkbox_component('monitor_malware', 1 === $data['monitor_malware']); ?>
                            </div>
                        </div>

                    </div>

                    <div class="fl pa2 pt2-l pr2-l pl3-l w-100 w-50-l">

                    	<div class="field mt3 mt4-l">
	                            <div class="checkbox-wrap" style="padding-bottom:0.125rem;">
                                <span>Connect to Google</span>
                                <?php checkbox_component('connect_to_google', 1 === $data['connect_to_google']); ?>
                            </div>
                        </div>

                        <div class="field choose-ga-account-field">
                            <label for="ga_account">Choose your google webmaster account</label>
                            <?php echo form_dropdown( 'ga_account', $available_values['google_acounts'], $data['ga_account'] ); ?>

                            <?php if( 1 === $user_id ){ ?>

                            <button class="add-new-google-account btn-color fr f6 no-underline mt3 pv2 ph3 br1 lh-solid" style="cursor:pointer;"><small class="white">ADD NEW GOOGLE ACCOUNT</small></button>

                            <?php } ?>
                        </div>

                        <div class="field mt3 mt4-l choose-crawl-errors-field">
	                            <div class="checkbox-wrap" style="padding-bottom:0.125rem;">
                                <span>Would you like to get Crawl Errors from Google Webmaster Tools?</span>
                                <?php checkbox_component('crawl_error_webmaster', 1 === $data['crawl_error_webmaster']); ?>
                            </div>
                        </div>

                        <div class="field mt3 mt4-l choose-track-search-queries-field">
	                            <div class="checkbox-wrap" style="padding-bottom:0.125rem;">
                                <span>Would you like to track your Search Queries from Google Webmaster Tools?</span>
                                <?php checkbox_component('search_query_webmaster', 1 === $data['search_query_webmaster']); ?>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="edit-site-form-section ph3 cf">

                    <div class="fl pa2 pt2-l pr3-l pl2-l w-100 w-50-l">
                        <div class="field">
                        	<?php
                            echo form_dropdown( 'engines[]', $available_values['search_engines'], $data['engines'], array( 'multiple' => "multiple", 'placeholder' => "Choose your Search Engine", 'class' => "multi-select-box" )  );
                            ?>
                        </div>
                        <div class="field mt3">
	                            <div class="checkbox-wrap" style="padding-bottom:0.125rem;">
                                <span>Include Mobile Search</span>
                                <?php checkbox_component('include_mobile_search', 1 === $data['include_mobile_search']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="fl pa2 pt2-l pr2-l pl3-l w-100 w-50-l">
                        <div class="field">
                            <textarea name="keywords" class="form-control" rows="26" placeholder="Enter your keywords, one per line, that you would like to monitor"><?php echo $data['keywords']; ?></textarea>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div class="edit-site-form-buttons-wrap cf active-1">
            <button type="button" class="btn-dark fl prev"><i class="material-icons">&#xE314;</i> <span>PREV</span></button>
            <button type="button" class="btn-dark fr next"><span>NEXT</span> <i class="material-icons">&#xE315;</i></button>
            <!-- <button type="button" class="submit-edit-project-btn btn-color fr finish"><span>FINISH</span></button> -->
            <button type="submit" class="btn-color fr finish"><span>FINISH</span></button>
        </div>

		<?php echo form_hidden( 'domain_id', $domain_id ); ?>

   	<?php echo form_close();

   	echo form_hidden( 'base_url', base_url() );
	echo form_hidden( 'google_account_client_id', $google_client_id );
	echo form_hidden( 'google_auth_redirect_uri', $google_oauth_redirect_uri );
}

// @note: Same function in 'app/libraies/Ci_auth.php', but in global scope.
function is_user_logged_in( $activated = true ) {
	if( ! isset( $_SESSION['status'] ) ){
		return false;
	}
	return $_SESSION['status'] === ($activated ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED);
}

// @note: Same function in 'app/modules/auth.controllers/CampaignIo_Pages_Authorized.php', but in global scope.
function validate_user_access(){
	if ( ! is_user_logged_in() ) {
        redirect( base_url('auth/login') );
        exit;
    }
    else if( is_user_logged_in( false ) ) {
        redirect( base_url( 'auth/sendactivation' ) );
        exit;
    }
}

function serp_report( $db, $analyze_model, $domain, $user_id, $search_engine = '', $return_html = false ){

	$domain_id = $domain['id'];
	$domain_url = $domain['url'];

	$serp_data = $analyze_model->getSerpResult( $domain_id, $search_engine );

	$db->flush_cache();
	$db->select( 'max(updated_date) as updated_date' );
	$db->from( 'serp' );
	$db->where( 'domain_id=', $domain_id );
	$query = $db->get();
	$date_result = $query->row_array();
	$show_date = $date_result['updated_date'] ? date('d F Y', strtotime( $date_result['updated_date'] ) ) : '-';

	$stat_keys = array( 'top1_latest', 'top3_latest','top5_latest', 'top10_latest', 'top20_latest', 'top30_latest' ,'ranked_latest', 'notranked_latest' );
	$stats = $analyze_model->getKeywordPositionComparitiveStats( $user_id, $domain_id, $search_engine );

	foreach ( $stat_keys as $key => $val) {
		$stats[ $val ] = isset( $stats[ $val ] ) ? $stats[ $val ] : 'n/a';
	}

	if( $return_html ){
		ob_start();
	}

	?>
	<div class="cf">

		<?php /* ?>
		<div class="fl w-100 w-30-l">
            <div class="content-column-inner">
                <span class="f7">DOMAIN</span>
                <p><a href="<?php echo $domain_url; ?>" title="" target="_blank" class="no-underline underline-hover white f5 fw5"><?php echo $domain['url']; ?></a></p>
            </div>

            <hr class="dn-l" style="margin-top:0; margin-bottom:0;" />
        </div>
        <?php */ ?>

        <div class="fl w-100 tc">

	        <div class="fl w-100 w-25-ns">
	            <div class="content-column-inner">
	            	<div class="bold-stat-num">
						<span class="stat-num campaignsio-admin-green"><?php echo $stats['top1_latest']; ?></span>
						<span class="stat-label">FIRST PLACE</span>
					</div>
					<hr>
					<div class="bold-stat-num">
						<span class="stat-num campaignsio-admin-green"><?php echo $stats['top3_latest']; ?></span>
						<span class="stat-label">IN TOP 3</span>
					</div>
	            </div>

	            <hr class="dn-ns" style="margin-top:0; margin-bottom:0;"/>
	        </div>

	        <div class="fl w-100 w-25-ns">
	            <div class="content-column-inner">
	            	<div class="bold-stat-num">
						<span class="stat-num campaignsio-admin-yellow"><?php echo $stats['top5_latest']; ?></span>
						<span class="stat-label">IN TOP 5</span>
					</div>
					<hr>
					<div class="bold-stat-num">
						<span class="stat-num campaignsio-admin-yellow"><?php echo $stats['top10_latest']; ?></span>
						<span class="stat-label">IN TOP 10</span>
					</div>
	            </div>

	            <hr class="dn-ns" style="margin-top:0; margin-bottom:0;"/>
	        </div>

	        <div class="fl w-100 w-25-ns">
	            <div class="content-column-inner">
	            	<div class="bold-stat-num">
						<span class="stat-num campaignsio-admin-orange"><?php echo $stats['top20_latest']; ?></span>
						<span class="stat-label">IN TOP 20</span>
					</div>
					<hr>
					<div class="bold-stat-num">
						<span class="stat-num campaignsio-admin-orange"><?php echo $stats['top30_latest']; ?></span>
						<span class="stat-label">IN TOP 30</span>
					</div>

	            </div>

	            <hr class="dn-ns" style="margin-top:0; margin-bottom:0;"/>
	        </div>

	        <div class="fl w-100 w-25-ns">
	            <div class="content-column-inner">
	            	<div class="bold-stat-num">
						<span class="stat-num campaignsio-admin-action-color"><?php echo $stats['ranked_latest']; ?></span>
						<span class="stat-label">RANKED</span>
					</div>
					<hr>
					<div class="bold-stat-num">
						<span class="stat-num campaignsio-admin-action-color"><?php echo $stats['notranked_latest']; ?></span>
						<span class="stat-label">NOT RANKED</span>
					</div>
	            </div>
	        </div>

        </div>

    </div>

	<hr/>

	<div class="list-table-wrap">
		<table data-table-id="serp-info" data-rows-per-page="100" class="serps-info-table filter-table list-table mv3 collapse tc">
    		<thead>
    			<tr>
    				<th class="tl">KEYWORDS AS OF <i><?php echo $show_date; ?></i></th>
    				<th>POSITION</th>
    				<th>GOOGLE VOLUME</th>
    				<th>COST</th>
    				<th>COMPETITION IN PPC</th>
    				<th>RESULTS</th>
    				<th>GWT</th>
    			</tr>
    		</thead>
    		<tbody><?php
    			if( ! empty( $serp_data ) ){
    				foreach ( $serp_data as $key => $val ){ ?>
    					<tr>
		    				<td class="tl"><?php echo $val['keyword']; ?></td>
		    				<td><?php echo $val['position'] ? $val['position'] : "-"; ?></td>
		    				<td><?php echo $val['volume_google'] ? $val['volume_google'] : "-"; ?></td>
		    				<td><?php echo $val['cost'] ? $val['cost'] : "-"; ?></td>
		    				<td><?php echo $val['competition_in_ppc'] ? $val['competition_in_ppc'] : "-"; ?></td>
		    				<td><?php echo $val['results'] ? $val['results'] : "-"; ?></td>
		    				<td><?php
                                if( 'GWT' === $val['type'] ){ ?><i class="material-icons campaignsio-admin-green">&#xE5CA;</i><?php }
                                else{ ?><i class="material-icons campaignsio-admin-orange">&#xE5CD;</i><?php } ?>
                            </td>
		    			</tr> <?php
    				}
    			}
    			else{ ?>
    				<tr>
    					<td colspan="7">No keywords found</td>
    				</tr>
    				<?php
    			}?>
    		</tbody>
    	</table>
    </div><?php

    if( $return_html ){
	    $ret = ob_get_contents();
	    ob_end_clean();
	}
	else{
		$ret = true;
	}

    return $ret;
}

function serp_edit_keywords_table( $edit_keywords = array(), $return_html = false ){

	if( $return_html ){
		ob_start();
	}
	?>
	<table  data-table-id="serp-edit-keywords" data-rows-per-page="100" class="edit-keywords-table filter-table list-table mv3 collapse tc">
		<thead>
			<tr>
				<!-- <th class="nowrap" style="width:1px;"><label class="pointer"><input type="checkbox" /> CHECK ALL</label></th> -->
				<th class="tl">KEYWORDS</th>
				<th>ACTIONS</th>
			</tr>
		</thead>
		<tbody>
			<?php
    		if( ! empty( $edit_keywords ) ){
                foreach( $edit_keywords as $val ) { ?>
                	<tr>
                		<?php /* ?><td><input type="checkbox" class="del_keyword" name="del_keyword[]" value="<?php echo $val['name']; ?>"/></td><?php */ ?>
                    	<td class="tl"><?php echo $val['name']; ?></td>
        				<td><a href="#" title="" data-keyword="<?php echo $val['name']; ?>" class="serp-keyword-remove dib mv1 mh1 f7 btn-lines btn-dark-br0 no-underline pv1 pr2 pl1-l br1"><i class="material-icons">&#xE872;</i><small class="fw7">REMOVE</small></a></td>
        			</tr>
                	<?php
                }
    		}
    		else{ ?>
                <tr>
                	<td colspan="4">No keywords found</td>
                </tr>
                <?php
            }
    		?>
		</tbody>
	</table> <?php

	if( $return_html ){
	    $ret = ob_get_contents();
	    ob_end_clean();
	}
	else{
		$ret = true;
	}

    return $ret;
}

function serp_keywords_table_html( $data = array(), $type = 'query' ){

	ob_start();

	$keywords_table = false;
	switch( $type ){
		case 'page':
			$value_title = 'URL ADDRESS';
			break;
		case 'country':
			$value_title = 'COUNTRY';
			break;
		case 'device':
			$value_title = 'DEVICE';
			break;
		case 'query':
		default:
			$value_title = 'KEYWORD';
			$keywords_table = true;
	}

	?>
	<table data-table-id="serp-webmaster-tools" data-rows-per-page="100" class="webmaster-tools-table filter-table list-table mv3 collapse tc">
		<thead>
			<tr>
				<?php if( $keywords_table ){ ?>
				<th class="nowrap" data-sortable="false"><!-- <label class="pointer"><input type="checkbox" /> CHECK ALL</label> --></th><?php
				} ?>
				<th class="tl"><?php echo $value_title; ?></th>
				<th>CLICKS</th>
				<th>CTR</th>
				<th>IMPRESSION</th>
				<th>POSITION</th>
			</tr>
		</thead>
		<tbody>
			<?php
    		if( ! empty( $data ) ){
                foreach( $data as $val ) { ?>
                	<tr>
                		<?php if( $keywords_table ){ ?>
						<td><input type="checkbox" name="add_webmaster_kwewords[]" value="<?php echo $val['keyword']; ?>" /></td><?php
						} ?>
                    	<td class="tl"><?php echo $val['keyword']; ?></td>
        				<td><?php echo $val['clicks']; ?></td>
        				<td><?php echo $val['ctr']; ?>%</td>
        				<td><?php echo $val['impressions']; ?></td>
        				<td><?php echo $val['position']; ?></td>
        			</tr>
                	<?php
                }
    		}
    		else{ ?>
                <tr class="no-records">
                    <td colspan="6">No keywords found</td>
                </tr> <?php
            } ?>
		</tbody>
	</table>
	<?php
	$ret = ob_get_contents();
	ob_end_clean();
	return $ret;
}

function single_domain_gtmetrix_content( $url, $gtmetrix ){
	$screenshot_src = '';
	if( isset( $gtmetrix['metrix']['report_url'] ) && '' !== trim( $gtmetrix['metrix']['report_url'] ) ){
		$screenshot_src = ' src="'.$gtmetrix['metrix']['report_url'] . '/screenshot.jpg"';
	}
	?>
	<div class="fl w-100 w-40-ns w-30-l">
        <div class="content-column-inner">
            <img class="br1 gtm-screenshot"<?php echo $screenshot_src; ?>/>
        </div>
    </div>

    <div class="fl w-100 w-60-ns w-30-l">
        <div class="content-column-inner">

            <h4 class="ma0 f5 fw5">Latest Performance Report for</h4>

            <a  class="gtmetrix-site-link" href="<?php echo $url; ?>" target="_blank"><?php echo strtr( $url, array('www.' => '', 'https://' => '', 'http://' => '', '/' => '' ) ); ?></a>

            <div class="report-box mt2">

                <div class="gtmetrix-report-row">
                    <span><strong>Report Generated on</strong></span>
                    <span class="report_date"><?php echo $gtmetrix['date']; ?></span>
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

        if( isset( $gtmetrix['metrix']['page_load_time'] ) ){
        	$loadTimeValueHtml = '<small><span class="time-val">' . ( round( $gtmetrix['metrix']['page_load_time'] / 1000 * 100 ) / 100 ) . '</span><small class="time-unit">s</small></small>';
        }

        if( isset( $gtmetrix['metrix']['page_bytes'] ) ){
        	$formated_bytes = format_bytes( $gtmetrix['metrix']['page_bytes'] );
        	$pageSizeValueHtml = '<small><span class="size-val">' . $formated_bytes['val'] . '</span><small class="size-unit">' . $formated_bytes['unit'] . '</small></small>';
        }

        if( isset( $gtmetrix['metrix']['page_elements'] ) ){
        	$requestsValueHtml = '<small class="requests-val">' . $gtmetrix['metrix']['page_elements'] . '</small>';
        }

        if( isset( $gtmetrix['metrix']['pagespeed_score'] ) ){

        	if ($gtmetrix['metrix']['pagespeed_score'] < 60) {
        		$pagespeed_score_classname = ' campaignsio-admin-orange';
            }
            else if ($gtmetrix['metrix']['pagespeed_score'] >= 60 && $gtmetrix['metrix']['pagespeed_score'] < 80) {
            	$pagespeed_score_classname = ' campaignsio-admin-yellow';
            }
            else {
            	$pagespeed_score_classname = ' campaignsio-admin-green';
            }

        	$pageSpeedValueHtml = '<small><span class="speed-grade">' . get_grade( $gtmetrix['metrix']['pagespeed_score'] ) . '</span> <small>(<span class="speed-val">' . $gtmetrix['metrix']['pagespeed_score'] . '%</span>)</small></small>';
        }

        if( isset( $gtmetrix['metrix']['yslow_score'] ) ){

        	if ($gtmetrix['metrix']['yslow_score'] < 60) {
        		$yslow_score_classname = ' campaignsio-admin-orange';
            }
            else if ($gtmetrix['metrix']['yslow_score'] >= 60 && $gtmetrix['metrix']['yslow_score'] < 80) {
            	$yslow_score_classname = ' campaignsio-admin-yellow';
            }
            else {
            	$yslow_score_classname = ' campaignsio-admin-green';
            }

        	$ySlowScoreValueHtml = '<small><span class="score-grade">'. get_grade( $gtmetrix['metrix']['yslow_score'] ) .'</span> <small>(<span class="score-val">' . $gtmetrix['metrix']['yslow_score'] . '%</span>)</small></small>';
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
	<?php
}

function heatmap_form( $domain_id, $heatmap_id = 0, $heatmap_data = array() ){

	if( 0 === $heatmap_id ){	// @note: Add new heatmap.
		$form_action = base_url('domains/' . $domain_id . '/heatmaps/add');
		$name_value = '';
		$embed_url_value = '';
		$save_label = "SAVE HEATMAP";
		$cancel_link = base_url('domains/' . $domain_id . '/heatmaps');
	}
	else{	// @note: Edit heatmap.
		$form_action = base_url('domains/' . $domain_id . '/heatmaps/' . $heatmap_id . '/edit');
		$name_value = $heatmap_data['page_name'];
		$embed_url_value = $heatmap_data['embed_url'];
		$save_label = "UPDATE HEATMAP";
		$cancel_link = base_url('domains/' . $domain_id . '/heatmaps/list');
	}

	?>
	<div class="content-row">
	    <div class="content-column w-100">
	        <div class="content-column-main">
	            <div class="title">
	                <div class="left-pos">
	                    <h3>EDIT HEATMAP</h3>
	                </div>
	            </div>
	            <div class="content-column-inner">
	                <form action="<?php echo $form_action; ?>" method="post" class="edit-profile-form cf" style="width: 100%;">
	                    <div class="dib w-100 cf">
	                        <div class="field">
	                            <label for="hotjar_page_name">Page name:</label>
	                            <input type="text" name="hotjar_page_name" placeholder="Page name" value="<?php echo $name_value; ?>" />
	                        </div>
	                    </div>
	                    <div class="dib w-100 cf">
	                        <div class="field">
	                            <label for="hotjar_url">Hotjar URL:</label>
	                            <input type="text" name="hotjar_url" value="<?php echo $embed_url_value; ?>" placeholder="Hotjar URL">
	                        </div>
	                    </div>
	                    <hr class="w-100">
	                    <button type="submit" class="dib btn-color no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="white"><?php echo $save_label; ?></span>
	                    </button>

	                    <a href="<?php echo $cancel_link; ?>" class="fr dib btn-lines no-underline pointer ba f7 fw5 lh-solid pv3 ph4 br1"><span class="white">CANCEL</span></a>

	                    <br>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>
	<?php
}


function seoreporting_navigation($current_page)
{
	$items = array();
	$items[] = array(
        'id' => 'seoreporting-project',
        'title' => 'Projects',
        'icon' => '&#xE85D',
        'link' => base_url('seoreporting/getprojects')
    );
    $items[] = array(
        'id' => 'seoreporting-task',
        'title' => 'Tasks',
        'icon' => '&#xE85D',
        'link' => base_url('seoreporting/listjobs')
    );
    ?>
    <nav ><?php
    	foreach ($items as $k => $v) {
    		$classAttr = $current_page === $v['id'] ? ' class="active"' : '';
    		?>
            <a href="<?php echo $v['link']; ?>" title="<?php echo $v['title']; ?>" <?php echo $classAttr; ?>>
                <?php echo $v['title']; ?>
            </a><?php
    	} ?>

    </nav>
  <?php
}
function domains_competition_results( $data ){
	?><div class="content-column-inner" style="padding-top:0.5rem; padding-bottom:0;">
		<hr/>
        <div class="list-table-wrap">

        	<table class="list-table mt3 collapse tc">
                <thead>
                  <tr>
                  	<th>DOMAINS</th>
					<th>UNIQUE</th>
					<th>TOTAL NUMBER</th>
                  </tr>
                </thead>
                <tbody>
				    <tr>
				    	<td><?php echo $data['domain1']; ?></td>
				        <td><?php echo $data['domain1_unique']; ?></td>
				        <td><?php echo $data['domain1_total']; ?></td>
				    </tr>
				    <tr>
				    	<td><?php echo $data['domain2']; ?></td>
				    	<td><?php echo $data['domain2_unique']; ?></td>
				    	<td><?php echo $data['domain2_total']; ?></td>
				    </tr>
				    <tr>
				    	<td><?php echo $data['domain1'] . ' , ' . $data['domain2']; ?> <small>(COMMON KEYWORDS)</small></td>
				        <td></td>
				        <td><?php echo $data['total_common_keyword']; ?></td>
				    </tr>
				</tbody>
            </table>
    	</div>
    	<hr/>
    	<div class="list-table-wrap">

        	<table class="list-table mt3 collapse tc">
                <thead>
                  <tr>
					<th>#</th>
		            <th>KEYWORDS</th>
					<th>POS. <?php echo $domain1; ?></th>
					<th>POS. <?php echo $domain2; ?></th>
					<th>GOOGLE VOLUME</th>
					<th>CPC ($)</th>
					<th>COMPETITION IN PPC</th>
                  </tr>
                </thead>
                <tbody> <?php
                	if( empty( $data['result'] ) ){ ?>
                		<tr>
                			<td colspan="7">No available data</td>
                		</tr><?php
                	}
                	else{
	                	foreach( $data['result'] as $key => $val ){ ?>
				            <tr>
				                <td><?php echo $key + 1; ?></td>
				                <td><?php echo $val['keyword']; ?></td>
					            <td><?php echo $val['position1']; ?></td>
					            <td><?php echo $val['position2']; ?></td>
					            <td><?php echo $val['concurrency']; ?></td>
					            <td><?php echo $val['cost']; ?></td>
					            <td><?php echo $val['region_queries_count']; ?></td>
				            </tr> <?php
				     	}
				     }?>
				</tbody>
            </table>
    	</div>
    </div><?php

}
