<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>ADD NEW SITE</h3>
                </div>
            </div>
            <div class="content-column-inner">
                <?php edit_domain_form_component( $domain['id'], $user['id'], $domain['form_data'], $available, $google_client_id, $google_oauth_redirect_uri ); ?>
            </div>
        </div>
    </div>
</div>