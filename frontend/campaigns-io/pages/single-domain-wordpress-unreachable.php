<div id="campaign-io-admin" class="f6">

	<section class="wp-login-wrap w-100 h-100 min-h-100 absolute dt pa3">

        <div class="dtc v-mid tc">

            <img src="<?php echo base_url( 'frontend/site/default/new-ui/img/campaigns-io-logo.png' ); ?>" alt="" />
            <br/>
            <div class="wp-login-content pv3 ph3">
                
                <br/>

                <h3 class="fw4">LOGGING INTO</h3>
                <h2 class="fw4 white"><small><?php echo $domain['name'] ?></small></h2>
                
                <p class="fw4 pv3 campaignsio-admin-orange">WORDPRESS SITE IS NOT ACCESSIBLE</p>

                <h3 class="fw3 mb0">as <i class="fw4 white"><?php echo $domain['adminUsername'] ?></i></h3>

                <br/>
                
                <p class="ph4">Before try again to login, ensure that you have installed and activated the last version of plugin <a href="https://wordpress.org/plugins/wp-management-controller/" target="_blank" title="" class="white">Campaigns.io - WordPress Multisite Controller</a>, <br/> and you have inserted valid credentials in site settings.</p>
                
                <br/>

                <a href="<?php echo $try_login_url; ?>" title="" class="dib btn btn-lines fw4 f7 pv3-25 ph3-25 mt4 mh3">TRY LOGIN AGAIN</a>

                <a href="<?php echo $edit_domain_url; ?>" title="" class="dib btn btn-lines fw4 f7 pv3-25 ph3-25 mt4 mh3">EDIT SITE SETTINGS</a>
                
                <a href="<?php echo $domains_list_url; ?>" title="" class="dib btn btn-lines fw4 f7 pv3-25 ph3-25 mt4 mh3">BACK TO DOMAINS</a>

                <br/>
                <br/>

            </div>

        </div>

    </section>

</div>

<?php echo form_hidden( 'wp_login_action_url', $wp_login_action_url ); ?>
