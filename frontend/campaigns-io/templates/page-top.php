<div id="campaign-io-admin" class="f6 before-init <?php echo $collapsed_sidebar ? ' collapse-sidebar' : ''; echo $collapsed_author_nav ? ' collapse-author-nav': ''; ?>">

    <span class="behind-sidebar db fixed"></span>

	<aside id="sidebar" class="relative overflow-x-hidden">

	    <header class="sidebar-header dt w-100">

	        <div class="author-thumb-wrap">
	            <div class="thumb relative dtc">
	                <span class="aspect-ratio--1x1 br-100">
	                    <span class="aspect-ratio--object br-100" style="background-image: url('<?php echo $user['gravatar']; ?>');"></span>
	                </span>
	            </div>
	            <div class="thumb-texts dtc v-mid">
	                <small class="dib">Welcome</small>
	                <br/>
	                <strong><?php echo $user['fullname']; ?></strong>
	            </div>
	        </div>

	        <?php user_navigation( $current_page, $user['parent_id'] ); ?>

	    </header>
	    	<div style="display: block; float: left; width: 100%;">
	       <?php
            // TODO: Remove 'view-serps'.
		    if( in_array( $current_page, array('single-domain', 'single-domain-analytics', 'single-domain-wordpress', 'single-domain-serps' /*, 'view-serps'*/, 'single-domain-heatmaps', 'single-domain-heatmaps-list', 'single-domain-research', 'single-domain-e-commerce' ), true ) && 0 < $domain['id'] ){
			    domain_navigation( $current_page, $domain['id'], isset( $user['domains'] ) ? $user['domains'] : array() );
			}
		    ?>
		    </div>
	    <?php main_navigation( $current_page ); ?>

	</aside>

    <section id="main" class="pa3">

        <div class="main-content-nav cf">

            <a href="<?php echo $url['home']; ?>" title="Campaigns.io - Home" class="content-logo dib pa2"><img src="<?php echo $url['logo']; ?>" alt="Campaigns.io logo" /></a>

            <br class="dn-l"/>

           <?php if( in_array( $current_page, array('single-domain', 'single-domain-analytics', 'single-domain-wordpress', 'single-domain-serps' /*, 'view-serps'*/, 'single-domain-heatmaps', 'single-domain-heatmaps-list', 'single-domain-research', 'single-domain-e-commerce' ), true ) && 0 < $domain['id'] ){
			    user_domain_navigation( $current_page, $domain['id']);
			}
		    ?>

        </div>