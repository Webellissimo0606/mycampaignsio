<div id="campaign-io-admin" class="f6">

	<section class="wp-login-wrap w-100 h-100 min-h-100 absolute dt pa3">

        <div class="dtc v-mid tc">

            <img src="<?php echo base_url( 'frontend/site/default/new-ui/img/campaigns-io-logo.png' ); ?>" alt="" />
            <br/>
            <div class="wp-login-content pv4 ph5">
                <h3 class="fw4">LOGGING INTO</h3>
                <h2 class="fw4 white"><small><?php echo $domain['name'] ?></small></h2>
                <span class="loading-icon"><i class="material-icons">&#xE86A;</i></span>
                <h3 class="fw3">as <i class="fw4 white"><?php echo $domain['adminUsername'] ?></i></h3>
            </div>

        </div>

    </section>

</div>

<?php echo form_hidden( 'base_url', base_url() ); ?>
<?php echo form_hidden( 'domain_id', $domain['id'] ); ?>
<?php echo form_hidden( 'wp_login_action_url', $wp_login_action_url ); ?>
<?php echo form_hidden( 'wp_unreachable_url', $wp_unreachable_url ); ?>
<?php echo form_hidden( 'wp_ping_url', $wp_ping_url ); ?>
