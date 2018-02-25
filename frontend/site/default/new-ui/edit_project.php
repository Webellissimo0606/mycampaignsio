<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'parts/top.php';

$search_engines = array(
    array(
        'val' => 'g_us',
        'title' => 'google.com',
    ),
    array(
        'val' => 'g_uk',
        'title' => 'google.co.uk',
    ),
    array(
        'val' => 'g_ca',
        'title' => 'google.co.ca',
    ),
    array(
        'val' => 'g_au',
        'title' => 'google.co.au',
    ),
);

$selected_search_engines = array( $searchEngine[0]['name'] );

$domain_data = $this->session->get_userdata();

$user_ga_accounts_url = base_url() . 'analytics/analytics/getUserGAAccounts';

$user_ga_accounts_args = array( 'userId' => $domain_data['user_id'] );

$user_ga_accounts = domain_stats_curl_request( $user_ga_accounts_url, $user_ga_accounts_args );
$user_ga_accounts = ! $user_ga_accounts ? array() : $user_ga_accounts;

?>
<div class="content-row">
    <div class="content-column w-100 w-two-thirds-l">
        <div class="content-column-main">
            <div class="title">
                <div class="left-pos">
                    <h3>EDIT SITE</h3>
                </div>
            </div>
            <div class="content-column-inner">

                <ul class="edit-site-tabs f6-m f5">
                    <li data-tab="1" class="active w-third"><span class="num f3-m f2">1</span><span>Domain info</span></li>
                    <li data-tab="2" class="w-third"><span class="num f3-m f2">2</span><span>Connections</span></li>
                    <li data-tab="3" class="w-third"><span class="num f3-m f2">3</span><span>Monitor Keyword SERP</span></li>
                </ul>

                <form action="domains.php" class="edit-profile-form edit-site-form cf mt3">

                    <div class="edit-site-form-content-wrap mb3 pt3">

                        <div class="edit-site-form-inner active-1">
                    
                            <div class="edit-site-form-section ph3 cf">
                                
                                <div class="fl pa2 pt2-l pr3-l pl2-l w-100 w-50-l">
                                    <div class="field">
                                        <label for="domain_name">Domain name</label>
                                        <div class="tooltip">
                                            <input type="text" placeholder="Domain name" name="domain_name" value="<?php echo $domainDetails['domain_name']; ?>">
                                            <span class="tooltiptext" style="width:170px;">Please include  http://  or  https://</span>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="checkbox-wrap">
                                            <span>Would you like to Monitor website uptime?</span>
                                            <?php checkbox_component('monitor_website_uptime', 1 === (int) $domainDetails['monitorUptime']); ?>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="checkbox-wrap">
                                            <span>Is your site ecommerce site ?</span>
                                            <?php checkbox_component('is_ecommerce', 1 === (int) $domainDetails['is_ecommerce']); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="fl pa2 pt2-l pr2-l pl3-l w-100 w-50-l">
                                    <div class="field">
                                        <label for="page_header">Page Header</label>
                                        <input type="text" placeholder="Page Header to Search" name="page_header" value="<?php echo htmlspecialchars($uptimeDetails['keyword']['header']); ?>">
                                    </div>

                                    <div class="field">
                                        <label for="page_body">Page Body</label>
                                        <input type="text" placeholder="Page Body to Search" name="page_body" value="<?php echo htmlspecialchars($uptimeDetails['keyword']['body']); ?>">
                                    </div>

                                    <div class="field">
                                        <label for="page_footer">Page Footer</label>
                                        <input type="text" placeholder="Page Footer to Search" name="page_footer" value="<?php echo htmlspecialchars($uptimeDetails['keyword']['footer']); ?>">
                                    </div>

                                    <div class="field">
                                        <label for="frequency">Frequency</label>
                                        <select name="frequency">
                                            <?php
                                            $freqs = array( 1, 5, 15,30, 60, 120, 360, 720, 1440);
                                            $selected_freq = isset( $uptimeDetails['keyword']['frequency'] ) ? (int) $uptimeDetails['keyword']['frequency'] : 0;
                                            $selected_freq = $selected_freq && in_array($selected_freq, $freqs, true) ? $selected_freq : 5;

                                            foreach($freqs as $f){
                                                $sel = $f === $selected_freq ? ' selected="selected"': '';
                                                if( 60 > $f ){
                                                    echo '<option value="'.$f.'" '.$sel.'>'.$f.' min</option>';
                                                }
                                                else if( 60 === $f ){
                                                    echo '<option value="'.$f.'" '.$sel.'>'.( $f / 60 ).' hour</option>';
                                                }
                                                else{
                                                    echo '<option value="'.$f.'" '.$sel.'>'.( $f / 60 ).' hours</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>

                                    <div class="field">
                                        <label for="subusers">Assign subusers to domain</label>
                                        <select multiple="multiple" name="subusers" placeholder="Choose subusers" class="multi-select-box"> <?php 
                                            if( is_array($subusers) && ! empty($subusers) ){
                                                foreach($subusers as $subuser){ ?>
                                                    <option value="<?php echo $subuser['id']; ?>" <?php if( in_array( $subuser['id'], $domainSubusers ) ){ echo 'selected="selected"'; } ?>><?php echo $subuser['first_name'] . ' ' . $subuser['last_name']; ?></option> <?php
                                                }
                                            } ?>
                                        </select>
                                    </div>

                                    <div class="field">
                                        <label for="groups">Choose group to assign domain</label>
                                        <select multiple="multiple" name="groups" placeholder="Choose groups" class="multi-select-box"> <?php 
                                            if( is_array($groups) && ! empty($groups) ){
                                                foreach($groups as $group){ ?>
                                                    <option value="<?php echo $group['id']; ?>" <?php if( in_array( $group['id'], $domainGroups, true ) ){ echo 'selected="selected"'; } ?>><?php echo $group['group_name']; ?></option><?php 
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="edit-site-form-section active ph3 cf">
                                
                                <div class="fl pa2 pt2-l pr3-l pl2-l w-100 w-50-l">

                                    <div class="field">
                                        <label for="adminURL">Admin URL</label>
                                        <input type="text" placeholder="http://" name="adminURL" value="<?php echo $domainDetails['adminURL']; ?>">
                                    </div>

                                    <div class="field">
                                        <label for="adminUsername">Admin Username</label>
                                        <input type="text" placeholder="Admin Username" name="adminUsername" value="<?php echo $domainDetails['adminUsername']; ?>">
                                    </div>
                                    
                                    <div class="field">
                                        <div class="checkbox-wrap">
                                            <div class="checkbox-wrap">
                                                <span>Connect to Google</span>
                                                <?php checkbox_component('connect_to_google', 1 === (int) $domainDetails['connectToGoogle']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="field">
                                        <div class="checkbox-wrap">
                                            <div class="checkbox-wrap">
                                                <span>Monitor Malware</span>
                                                <?php checkbox_component('monitor_malware', 1 === (int) $domainDetails['monitorMalware']); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="checkbox-wrap">
                                            <div class="checkbox-wrap">
                                                <span>Would you like to get Crawl Errors from Google Webmaster Tools?</span>
                                                <?php checkbox_component('crawl_error_webmaster', 1 === (int) $domainDetails['webmaster']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="field">
                                        <div class="checkbox-wrap">
                                            <span>Would you like to track your Search Queries from Google Webmaster Tools?</span>
                                            <?php checkbox_component('search_query_webmaster', 1 === (int) $domainDetails['search_analytics']); ?>
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="fl pa2 pt2-l pr2-l pl3-l w-100 w-50-l">

                                    <div class="field choose-ga-account-field">
                                        <label for="">Choose your google webmaster account</label>
                                        <select name="gaAccounts">
                                            <option value="0">Select</option>
                                            <?php
                                                foreach($user_ga_accounts as $k => $v){
                                                    foreach($v as $x => $y){
                                                        echo '<option value="'.$x.'" '.( (int) $domain_data['gaAccount'] === (int) $x ? ' selected="selected"' : '' ).'>'.$y.'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                        <?php if( 1 === (int) $domain_data['user_id'] ){ ?>
                                        <button class="add-new-google-account btn-color fr f6 no-underline mt3 pv2 ph3 br1 lh-solid" style="cursor:pointer;"><small class="white">ADD NEW GOOGLE ACCOUNT</small></button>
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>

                            <div class="edit-site-form-section ph3 cf">

                                <div class="fl pa2 pt2-l pr3-l pl2-l w-100 w-50-l">
                                    <div class="field">
                                        <select multiple="multiple" name="engines" placeholder="Choose your search engine" class="multi-select-box"><?php 
                                            if( is_array($search_engines) && ! empty($search_engines) ){ ?>
                                                <optgroup label="Search engine"> <?php
                                                    foreach($search_engines as $se){ ?>
                                                        <option value="<?php echo $se['val']; ?>" <?php if( in_array( $se['val'], $selected_search_engines, true) ){ echo 'selected="selected"'; } ?>><?php echo $se['title']; ?></option> <?php
                                                    } ?>
                                                </optgroup> <?php
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <textarea name="keywords" class="form-control" rows="26" placeholder="Enter your keywords, one per line, that you would like to monitor"><?php echo $keywordsDetail; ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="fl pa2 pt2-l pr2-l pl3-l w-100 w-50-l">
                                    <div class="field">
                                        <div class="checkbox-wrap">
                                            <span>Include Mobile Search</span>
                                            <?php checkbox_component('include_mobile_search', 1 === (int) $domainDetails['mobile_search']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <input type="hidden" name="base_url" value="<?php echo base_url(); ?>" />
                    <input type="hidden" name="save_url" value="<?php echo base_url(); ?>auth/auth/editDomain" />
                    <input type="hidden" name="domain_id" value="<?php echo $domainId; ?>" />
                    <input type="hidden" name="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <input type="hidden" name="csrf_token" value="<?php echo $this->security->get_csrf_token_name(); ?>" />

                    <input type="hidden" name="google_account_client_id" value="<?php echo $client_id; ?>" />
                    <input type="hidden" name="google_auth_redirect_uri" value="<?php echo $redirect_uri; ?>" />

                    <div class="edit-site-form-buttons-wrap cf active-1">
                        <button type="button" class="btn-dark fl prev"><i class="material-icons">&#xE314;</i> <span>PREV</span></button>
                        <button type="button" class="btn-dark fr next"><span>NEXT</span> <i class="material-icons">&#xE315;</i></button>
                        <button type="button" class="submit-edit-project-btn btn-color fr finish"><span>FINISH</span></button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?php require 'parts/bottom.php'; ?>