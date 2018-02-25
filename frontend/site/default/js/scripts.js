var siteUrl = 'http://campaignsio.dev/';

var WP_Upgrades = ( function( site_url, domain_id ) {

    if( '/' === site_url.substring( site_url.length - 1 ) || '\\' === site_url.substring( site_url.length - 1 ) ){
        site_url = site_url.substring( 0, site_url.length - 1 );
    }

    var upgrades_pre = site_url + '/auth/site/upgrades/' + domain_id;
    var updates_pre = site_url + '/auth/site/update/' + domain_id;

    var api_url = {
        'upgrades':{
            'all': upgrades_pre + '/all',
        },
        'update': {
            'all': updates_pre + '/all',
            'core': updates_pre + '/core',
            'themes': {
                'all': updates_pre + '/themes/all',
                'single': updates_pre + '/theme/%theme_slug%',
            },
            'plugins': {
                'all': updates_pre + '/plugins/all',
                'single': updates_pre + '/plugin/%plugin_slug%',
            },
        },
    };

    var elems = {
        coreUpgradesNum: null,
        themesUpgradesNum: null,
        pluginsUpgradesNum: null,
        coreUpgradesContent: null,
        coreUpgradesContent_tbody: null,
        themesUpgradesContent: null,
        themesUpgradesContent_tbody: null,
        pluginsUpgradesContent: null,
        pluginsUpgradesContent_tbody: null
    };

    var available_upgrades_count = {
        'summ' : 0,
        'core' : 0,
        'themes' : 0,
        'plugins' : 0,
    };

    var current_updating = {
        'summ' : 0,
        'core' : 0,
        'themes' : 0,
        'plugins' : 0,
    };

    var updatesSelections = {
        core: 0,
        themes: [],
        plugins: [],
    };

    function _init() {
        _init_dom_elements( _load_upgrades );
    }

    function _init_dom_elements( callback ) {

        elems.coreUpgradesNum = jQuery('.upd-core-num');
        elems.themesUpgradesNum = jQuery('.upd-themes-num');
        elems.pluginsUpgradesNum = jQuery('.upd-plugins-num');
        elems.coreUpgradesContent = jQuery('.upd-core-content');
        elems.coreUpgradesContent_tbody = jQuery('.upd-core-content table tbody');
        elems.themesUpgradesContent = jQuery('.upd-themes-content');
        elems.themesUpgradesContent_tbody = jQuery('.upd-themes-content table tbody');
        elems.pluginsUpgradesContent = jQuery('.upd-plugins-content');
        elems.pluginsUpgradesContent_tbody = jQuery('.upd-plugins-content table tbody');

        if( 'undefined' !== callback && 'function' === typeof(callback) ){
            callback();
        }
    }

    function _enable_core_actions(){
        elems.coreUpgradesContent.find('.update-wp-core').removeClass('btn-primary');
        elems.coreUpgradesContent.find('.update-wp-core').addClass('btn-default');
    }

    function _disable_core_actions(){
        elems.coreUpgradesContent.find('.update-wp-core').removeClass('btn-default');
        elems.coreUpgradesContent.find('.update-wp-core').addClass('btn-primary');
    }

    function _enable_themes_actions(){
        elems.themesUpgradesContent.find('.update-wp-themes').removeClass('btn-default');
        elems.themesUpgradesContent.find('.update-wp-themes').addClass('btn-primary');
    }

    function _disable_themes_actions(){
        elems.themesUpgradesContent.find('.update-wp-themes').removeClass('btn-primary');
        elems.themesUpgradesContent.find('.update-wp-themes').addClass('btn-default');
    }

    function _enable_plugins_actions(){
        elems.pluginsUpgradesContent.find('.update-wp-plugins').removeClass('btn-default');
        elems.pluginsUpgradesContent.find('.update-wp-plugins').addClass('btn-primary');
    }

    function _disable_plugins_actions(){
        elems.pluginsUpgradesContent.find('.update-wp-plugins').addClass('btn-default');
        elems.pluginsUpgradesContent.find('.update-wp-plugins').removeClass('btn-primary');
    }

    function _init_events( callback ) {
        var eee = this;

        jQuery('.update-wp-core').on('click', function(e){
            e.preventDefault();
            if( 'undefined' === typeof whileBackupRun[ domain_id ] || false === whileBackupRun[ domain_id ] ){

                // console.log( current_updating.core );
                // console.log( updatesSelections.core );

                if( 0 === current_updating.core && 1 === updatesSelections.core ){

                    console.log( whileBackupRun );
                    console.log( current_updating );

                    _update_core();
                    updatesSelections.core = 0;
                }
            }
            else{
                already_running_backup_process();
            }
        });
    
        jQuery('.update-wp-themes').on('click', function(e){
            e.preventDefault();
            if( 'undefined' === typeof whileBackupRun[ domain_id ] || false === whileBackupRun[ domain_id ] ){
                if( 0 === current_updating.themes && 0 < updatesSelections.themes.length ){
                    var i;
                    for(i=0; i<updatesSelections.themes.length; i++){
                        _update_theme( updatesSelections.themes[i] );
                    }
                    updatesSelections.themes = [];
                }
            }
            else{
                already_running_backup_process();
            }
        });

        jQuery('.update-wp-plugins').on('click', function(e){
            e.preventDefault();
            if( 'undefined' === typeof whileBackupRun[ domain_id ] || false === whileBackupRun[ domain_id ] ){
                if( 0 === current_updating.plugins && 0 < updatesSelections.plugins.length ){
                    for(i=0; i<updatesSelections.plugins.length; i++){
                        _update_plugin( updatesSelections.plugins[i] );
                    }
                    updatesSelections.plugins = [];
                }                
            }
            else{
                already_running_backup_process();
            }
        });

        jQuery('.check-wp-update').on('click', function(){
            var i = 0;
            var pos = 0;
            var arr = [];
            var toggleClassName = 'checked';
            var action = ! jQuery(this).hasClass(toggleClassName) ? 'add' : 'remove';
            var updateSlug = jQuery(this).find('input').data('wp_update_slug');
            var updateType = jQuery(this).find('input').data('wp_update_type');

            if( '' !== updateSlug && ( 'plugins' === updateType || 'themes' === updateType || 'core' === updateType ) ){
                switch(updateType){
                    case 'plugins':
                    case 'themes':
                        pos = updatesSelections[updateType].indexOf( updateSlug );
                        switch( action ){
                            case 'add':
                                if ( 0 > pos ){
                                    updatesSelections[updateType][updatesSelections[updateType].length] = updateSlug;
                                    if( 'plugins' === updateType ){
                                        _enable_plugins_actions();
                                    }
                                    else{
                                        _enable_themes_actions();
                                    }
                                }
                                break;
                            default:
                                if ( 0 <= pos ){
                                    arr = [];
                                    pos = 0;
                                    for(i=0; i<updatesSelections[updateType].length; i++){
                                        if( updateSlug !== updatesSelections[updateType][i] ){
                                            arr[pos] = updatesSelections[updateType][i];
                                            pos++;
                                        }
                                    }
                                    
                                    updatesSelections[updateType] = arr;

                                    if( 0 === updatesSelections[updateType].length ){
                                        if( 'plugins' === updateType ){
                                            _disable_plugins_actions();
                                        }
                                        else{
                                            _disable_themes_actions();
                                        }
                                    }
                                    else{
                                        if( 'plugins' === updateType ){
                                            _enable_plugins_actions();
                                        }
                                        else{
                                            _enable_themes_actions();
                                        }
                                    }
                                }
                                break;
                        }
                        break;
                    case 'core':
                        if( 'add' === action ){
                            updatesSelections.core = 1;
                            _enable_core_actions();
                        }
                        else{
                            updatesSelections.core = 0;
                            _disable_core_actions();
                        }
                        break;
                }

                switch( action ){
                    case 'add':
                        jQuery(this).addClass(toggleClassName);
                        break;
                    default:
                        jQuery(thisF).removeClass(toggleClassName);
                        break;
                }
            }
        });

        if( 'undefined' !== callback && 'function' === typeof(callback) ){
            callback();
        }
    }

    function _load_upgrades() {

        jQuery.get(
            api_url.upgrades.all,
            {}, // Options
            function (data, status) {
                if( 'undefined' !== data && null !== data &&  ( 'undefined' === data.error || 1 !== parseInt( data.error, 10 ) ) && 'success' === status && 'undefined' !== data.core && 'undefined' !== data.themes && 'undefined' !== data.plugins ){
                    if( elems.coreUpgradesNum.length ) { 
                        if( false === data.core ){
                            elems.coreUpgradesNum[0].innerHTML = '0'; 
                            elems.coreUpgradesNum.addClass('badge-success');
                        }
                        else{
                            elems.coreUpgradesNum[0].innerHTML = '1'; 
                            elems.coreUpgradesNum.addClass('badge-warning');
                            elems.coreUpgradesContent.removeClass('none-wp-update');
                        }
                    }
                    if( elems.themesUpgradesNum.length ) {
                        elems.themesUpgradesNum[0].innerHTML = data.themes.length;
                        if( 0 === data.themes.length ){
                            elems.themesUpgradesNum.addClass('badge-success');
                        }
                        else{
                            elems.themesUpgradesNum.addClass('badge-warning');
                            elems.themesUpgradesContent.removeClass('none-wp-update');
                        }
                    }
                    if( elems.pluginsUpgradesNum.length ) { 
                        elems.pluginsUpgradesNum[0].innerHTML = data.plugins.length;
                        if( 0 === data.plugins.length ){
                            elems.pluginsUpgradesNum.addClass('badge-success');
                        }
                        else{
                            elems.pluginsUpgradesNum.addClass('badge-warning');
                            elems.pluginsUpgradesContent.removeClass('none-wp-update');
                        }
                    }
                    if( elems.coreUpgradesContent_tbody.length ){ elems.coreUpgradesContent_tbody[0].innerHTML = _gen_elem_content('core', data.core); }
                    if( elems.themesUpgradesContent_tbody.length ){ elems.themesUpgradesContent_tbody[0].innerHTML = _gen_elem_content('themes', data.themes); }
                    if( elems.pluginsUpgradesContent_tbody.length ){ elems.pluginsUpgradesContent_tbody[0].innerHTML = _gen_elem_content('plugins', data.plugins); }
                    _init_events( _check_available_upgrades );
                }
            }, 'json' ).fail(function(data, status) {
                /*console.log(data);
                console.log(status);*/
                console.log("FAILED: Retrieve available upgrades.");
            });
    }

    function _gen_elem_content( ct, val ) {
        var s, ret = '';
        if( 'undefined' !== ct ){

            switch( ct ){
                case 'core':
                    if( val ){
                        available_upgrades_count.core++;
                        ret += _gen_tr_html( ct, 'core', 'WordPress has an updated version', val.current_version, val.version, 'Update', available_upgrades_count.core );
                    }
                    else{
                        ret += _gen_tr_html( ct, 'core', 'Latest Updates are on. Good job.', null, null, null, 1 );
                    }
                    break;
                case 'themes':
                    if( val.length ){
                        for( i in val ){
                            if( val.hasOwnProperty(i) ){
                                available_upgrades_count.themes++;
                                ret += _gen_tr_html( ct, val[i].theme, val[i].name, val[i].old_version, val[i].new_version, 'Update', available_upgrades_count.themes );
                            }
                        }
                    }
                    else{
                        ret += _gen_tr_html( ct, false, 'Latest Updates are on. Good job.', null, null, null, 1 );
                    }

                    break;
                case 'plugins':
                    if( val.length ){
                        for( i in val ){
                            if( val.hasOwnProperty(i) ){
                                available_upgrades_count.plugins++;
                                ret += _gen_tr_html( ct, val[i].slug, val[i].name, val[i].old_version, val[i].new_version, 'Update', available_upgrades_count.plugins );
                            }
                        }
                    }
                    else{
                        ret += _gen_tr_html( ct, false, 'Latest Updates are on. Good job.', null, null, null, 1 );
                    }

                    break;
            }

        }

        return ret;
    }

    function _gen_tr_html( type, slug, title, currVersion, newVersion, buttonStr, num ) {
        var tr = '', dataType = '', dataSlug = '';
        if( 'undefined' !== type && ( 'undefined' !== title || 'undefined' !== buttonStr ) ) {
            dataType = 'undefined' !== type && type ? ' data-wp_update_type="' + type + '"' : '';
            dataSlug = 'undefined' !== typeof slug ? ' data-wp_update_slug="' + slug + '"' : '';
            if( currVersion === newVersion ){
                tr += '<tr class="' + ( 0 < (num%2) ? 'odd' : 'even' ) + ' no-result" role="row">\
                    <td></td><td></td><td> ' + title + '</td><td></td><td></td></tr>';
            }
            else{
                tr += '<tr data-row_id="' + type + '_' + slug + '" class="' + ( 0 < (num%2) ? 'odd' : 'even' ) + '" role="row">\
                        <!--<td class="sorting_1"><button class="btn btn-primary" type="button"' + dataType + '' + dataSlug + '>' + buttonStr + '</button></td>-->\
                        <td class="sorting_1">\
                            <div class="icheckbox_square-blue check-wp-update" style="position: relative;">\
                                <input type="checkbox" data-checkbox="icheckbox_square-blue" style="position: absolute; opacity: 0;" ' + dataType + '' + dataSlug + '>\
                            </div>\
                        </td>\
                        <td><span class="badge badge-primary">' + num + '</span></td>\
                        <td> ' + title + '</td>\
                        <td> ' + currVersion + ' <i class="fa fa-long-arrow-right"></i> </td>\
                        <td>  ' + newVersion + ' </td>\
                        </tr>';
            }
        }
        return tr;
    }

    function _check_available_upgrades() {
        available_upgrades_count.summ = available_upgrades_count.core + available_upgrades_count.themes + available_upgrades_count.plugins;
    }

    function _update_core() {
        current_updating.core++;
        jQuery('tr[data-row_id="core_core"] td:first-child').html('<i class="fa fa-spinner spin-animation"></i>');
        _disable_core_actions();
        jQuery.post(
            api_url.update.core,
            { },    // Options
            function( data ){
                _on_core_update_request_complete( data );
            },
            'json'
        ).fail(function() {
            _on_core_update_request_complete({ error: 1, message: 'An unexpected error occured. Please, try again.' });
        });
    }

    function _update_theme( theme_slug ) {
        if( 'undefined' !== typeof theme_slug && theme_slug ){
            current_updating.themes++;
            jQuery('tr[data-row_id="themes_' + theme_slug + '"] td:first-child').html('<i class="fa fa-spinner spin-animation"></i>');            
            _disable_themes_actions();
            jQuery.post(
                'all' === theme_slug ? api_url.update.themes.all : api_url.update.themes.single.replace(/%theme_slug%/i, theme_slug),
                { },    // Options
                function( data ){
                    _on_theme_update_request_complete( data, theme_slug );
                },
                'json'
            ).fail(function() {
                _on_theme_update_request_complete({ error: 1, message: 'An unexpected error occured. Please, try again.' }, theme_slug);
            });
        }
    }

    function _update_plugin( plugin_slug ) {
        if( 'undefined' !== typeof plugin_slug && plugin_slug ){
            current_updating.plugins++;
            jQuery('tr[data-row_id="plugins_' + plugin_slug + '"] td:first-child').html('<i class="fa fa-spinner spin-animation"></i>');
            _disable_plugins_actions();
            jQuery.post(
                'all' === plugin_slug ? api_url.update.plugins.all : api_url.update.plugins.single.replace(/%plugin_slug%/i, plugin_slug),
                { },    // Options
                function( data ){
                    _on_plugin_update_request_complete( data, plugin_slug );
                },
                'json'
            ).fail(function() {
                _on_plugin_update_request_complete({ error: 1, message: 'An unexpected error occured. Please, try again.' }, plugin_slug);
            });
        }
    }

    function _on_core_update_request_complete( data ) {
        if( 'undefined' !== data.error && 0 === parseInt(data.error, 10) ){
            jQuery('tr[data-row_id="core_core"] td:first-child').html('<i class="fa fa-check" style="color:#18a689;"></i>');
        }
        else{
            jQuery('tr[data-row_id="core_core"] td:first-child').html('<i class="fa fa-close" style="color:#C75757;"></i>');
        }
        available_upgrades_count.core--;
        current_updating.core--;
        _check_available_upgrades();
    }

    function _on_theme_update_request_complete( data, slug ) {
        if( 'undefined' !== data.error && 0 === parseInt(data.error, 10) ){
            jQuery('tr[data-row_id="themes_' + slug + '"] td:first-child').html('<i class="fa fa-check" style="color:#18a689;"></i>');
        }
        else{
            jQuery('tr[data-row_id="themes_' + slug + '"] td:first-child').html('<i class="fa fa-close" style="color:#C75757;"></i>');
        }
        available_upgrades_count.themes--;
        current_updating.themes--;
        _check_available_upgrades();
    }

    function _on_plugin_update_request_complete( data, slug ) {
        if( 'undefined' !== data.error && 0 === parseInt(data.error, 10) ){
            jQuery('tr[data-row_id="plugins_' + slug + '"] td:first-child').html('<i class="fa fa-check" style="color:#18a689;"></i>');
        }
        else{
            jQuery('tr[data-row_id="plugins_' + slug + '"] td:first-child').html('<i class="fa fa-close" style="color:#C75757;"></i>');
        }
        available_upgrades_count.plugins--;
        current_updating.plugins--;
        _check_available_upgrades();
    }

    return {
        run : _init
    };
});

var WP_Summ_Upgrades = ( function( site_url, domain_id ) {

    if( '/' === site_url.substring( site_url.length - 1 ) || '\\' === site_url.substring( site_url.length - 1 ) ){
        site_url = site_url.substring( 0, site_url.length - 1 );
    }

    var elems = {
        'coreNum' : null,
        'themesNum' : null,
        'pluginsNum' : null,
    };

    function _init() {
        _init_dom_elements( _load_upgrades_num );
    }

    function _init_dom_elements( callback ) {

        elems.coreNum = jQuery('.available-wp-updates .summ-core-num');
        elems.themesNum = jQuery('.available-wp-updates .summ-themes-num');
        elems.pluginsNum = jQuery('.available-wp-updates .summ-plugins-num');

        if( 'undefined' !== callback && 'function' === typeof(callback) ){
            callback();
        }
    }

    function _load_upgrades_num() {
        
        jQuery.get(
             site_url + '/auth/site/summarycounts',
            {}, // Options
            function (data, status) {
                if( 'success' === status && 'undefined' !== data.core && 'undefined' !== data.themes && 'undefined' !== data.plugins ){

                    if( $('.site-wp-updates-list').length ){
                        elems.coreNum[0].innerHTML = data.core;
                        elems.themesNum[0].innerHTML = data.themes;
                        elems.pluginsNum[0].innerHTML = data.plugins;
                    }
                    else{
                        elems.coreNum[0].innerHTML = data.core + ' Updates';
                        elems.themesNum[0].innerHTML = data.themes + ' Updates';
                        elems.pluginsNum[0].innerHTML = data.plugins + ' Updates';
                    }
                }
            }, 'json' ).fail(function(data, status) {
                console.log(data);
                console.log(status);
                console.log("FAILED: Retrieve upgrades summary data.");
            });

    }

    return {
        run : _init
    };
});

var Backups_Completed_List = [];

var whileBackupRun = {};

function init_backups_exe(){
    if( jQuery('.backup-custom-exe').length ){
        var backups_exe_object = new Backups_Exe( siteUrl );
        backups_exe_object.run();
    }
}

function init_backups_remove(){
    if( jQuery('.backup-custom-remove').length ){
        var backups_remove_object = new Backups_Remove( siteUrl );
        backups_remove_object.run();
    }
}

function init_backups_restore(){
    if( jQuery('.backup-custom-restore').length ){
        var backups_restore_object = new Backups_Restore( siteUrl );
        backups_restore_object.run();
    }
}

function get_Backups_Completed_table_row( empty, backupId, domainId, title, url, type, date, num){

    var backupType;

    if( empty ){
        ret = '<tr class="odd">' + 
                    '<td colspan="6">No backups</td>' + 
                '</tr>';
    }
    else{
        backupType = 'db-only' === type ? 'Database' : 'Database and files';
        ret = '<tr class="'+ ( 0 < (num%2) ? 'odd' : 'even' ) +'" role="row">' + 
                    '<td class="date"><small>' + date + '</small></td>' + 
                    '<td class="type">' + backupType + '</td>' + 
                    /*'<td class="type">n/a</td>' +*/
                    '<td class="actions">' + 
                        '<div class="btn-group">' + 
                        '<a href="#" class="backup-custom-restore btn btn-default mybutton88" type="button" data-domainid="' + domainId + '" data-backupid="' + backupId + '"><i class="fa fa-history"></i> Restore</a>' + 
                        '<a href="#" class="backup-custom-remove btn btn-dark mybutton99" data-domainid="' + domainId + '" data-backupid="' + backupId + '"><i class="fa fa-trash-o"></i> Delete</a>' + 
                        '</div>' +
                    '</td>' + 
                '</tr>';
    }

    return ret;
}

function update_Backups_Completed_table() {
    var i,
        ret = '',
        len = Backups_Completed_List.length;

    if( len ){
        for( i=0; i<len; i++ ){
            ret += get_Backups_Completed_table_row( 0, Backups_Completed_List[i].backup_id, Backups_Completed_List[i].domain_id, Backups_Completed_List[i].title, Backups_Completed_List[i].url, Backups_Completed_List[i].type, Backups_Completed_List[i].date, i + 1 );
        }
    }
    else{
        ret += get_Backups_Completed_table_row( 1 );
    }

    jQuery('.backups_completed table tbody').html( ret );

    if( len ){
        init_backups_remove();
        init_backups_restore();
    }
}

function add_Backups_Completed_item( domain_id, backup_id, title, url, type, date, update_table, inTheBegin ) {
    var t = {
            'domain_id': domain_id,
            'backup_id': backup_id,
            'title': title,
            'url': url,
            'type': type,
            'date': date,
        };

    if( inTheBegin ){
        Backups_Completed_List.unshift( t );
    }
    else{
        Backups_Completed_List.push( t );
    }

    if( update_table ){
        update_Backups_Completed_table();
    }
}

function remove_Backups_Completed_item( bid, update_table ) {
    var i,
        ret = [],
        len = Backups_Completed_List.length;
    
    for( i=0; i<len; i++ ){
        if( bid !== Backups_Completed_List[i].backup_id ){
            ret.push( Backups_Completed_List[i] );
        }
    }

    Backups_Completed_List = ret;

    if( update_table ){
        update_Backups_Completed_table();
    }
}

var confirmOnBackupsPageExit = function (e) {

    // If we haven't been passed the event get the window.event
    e = e || window.event;

    var message = 'A backup process is running. In case you exit the page until proecss complete, the proccess will be canceled and perhaps a problem will be appear in your domain.';

    if( e ) {
        e.returnValue = message;
    }

    return message;
};

function update_whileBackupRun( domainId ){
    delete whileBackupRun[ domainId ];
    if( 0 === Object.keys(whileBackupRun).length ){
        window.onbeforeunload = null;
    }
}

function already_running_backup_process(){

    alert("A backup process is running for the domain.\nWait until completion and try again.");
}

var Backups_List = ( function( site_url, domainId ) {

    if( '/' === site_url.substring( site_url.length - 1 ) || '\\' === site_url.substring( site_url.length - 1 ) ){
        site_url = site_url.substring( 0, site_url.length - 1 );
    }

    var api_url = site_url + '/auth/backups/completed' + ( domainId ? '/' + domainId : '' );

    function _init() {
        _init_dom_elements( _load_backups_list );
    }

    function _init_dom_elements( callback ) {

        if( 'undefined' !== callback && 'function' === typeof(callback) ){
            callback();
        }
    }

    function _load_backups_list(){

        jQuery.get(
            api_url,
            {}, // Options
            function (data, status) {
                var tbodyHtml = '',
                    k, i, l, c, f = false;
                if( 'success' === status ){                    
                    c = data.length;
                    if( 0 < c ){
                        for( k=0; k<c; k++ ){
                            add_Backups_Completed_item( data[k].domain_id, data[k].bid, data[k].name, data[k].url, data[k].type, data[k].created );
                        }
                    }
                }
                else{
                    console.log(data);
                    console.log(status);
                    console.log("FAILED: Retrieve backups list - status error.");
                }

                update_Backups_Completed_table();

            }, 'json' ).fail(function(data, status) {
                console.log(data);
                console.log(status);
                console.log("FAILED: Retrieve backups list.");
            });
    }

    return {
        run : _init
    };
});

var Backups_Tasks_List = ( function( site_url, domainId ) {

    if( '/' === site_url.substring( site_url.length - 1 ) || '\\' === site_url.substring( site_url.length - 1 ) ){
        site_url = site_url.substring( 0, site_url.length - 1 );
    }

    var api_url = site_url + '/auth/backups/tasks/list' + ( domainId ? '/' + domainId : '' );

    var elems = {
        'daily_backup_tasks_tbody' : null,
        'weekly_backup_tasks_tbody' : null,
        'monthly_backup_tasks_tbody' : null,
    };

    function _init() {
        _init_dom_elements( _load_backup_tasks_list );
    }

    function _init_dom_elements( callback ) {

        elems.daily_backup_tasks_tbody = jQuery('#daily-tasks .table-backups-tasks tbody');
        elems.weekly_backup_tasks_tbody = jQuery('#weekly-tasks .table-backups-tasks tbody');
        elems.monthly_backup_tasks_tbody = jQuery('#monthly-tasks .table-backups-tasks tbody');

        elems.daily_backup_tasks_num = jQuery('.daily-backup-tasks-num');
        elems.weekly_backup_tasks_num = jQuery('.weekly-backup-tasks-num');
        elems.monthly_backup_tasks_num = jQuery('.monthly-backup-tasks-num');

        if( 'undefined' !== callback && 'function' === typeof(callback) ){
            callback();
        }
    }

    function _load_backup_tasks_list() {
        jQuery.get(
            api_url,
            {}, // Options
            function (data, status) {
                /*console.log(data);
                console.log(status);*/
                var k, i, l, c, t;

                if( 'success' === status ){

                    c = data.length;

                    if( 0 < c ){

                        for( k=0; k<c; k++ ){
                            
                            if( 'success' === data[k].status && 0 === parseInt( data[k].error, 10 ) ){

                                l = data[k].data.length;

                                if( l ){
                                    
                                    for( i=0; i<l; i++ ){
                                        
                                        t = jQuery('tr[data-taskid="' + data[k].data[i].id + '"] td.last_run');

                                        if( t.length ){
                                            t.html( data[k].data[i].last_run ? data[k].data[i].last_run : '-' );
                                        }
                                        else{
                                            t.html( '-' );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                else{
                    console.log("FAILED: Error on status when retrieve backups tasks.");
                }

            }, 'json' ).fail(function(data, status) {
                console.log(data);
                console.log(status);
                console.log("FAILED: Retrieve backups tasks.");
            });
    }

    return {
        run : _init
    };
});

var Backups_Exe = ( function( site_url ) {

    if( '/' === site_url.substring( site_url.length - 1 ) || '\\' === site_url.substring( site_url.length - 1 ) ){
        site_url = site_url.substring( 0, site_url.length - 1 );
    }

    var api_url = site_url + '/auth/backups/execute';

    var elems = {
        triggerElems: null,
    }

    var backup_running = false;

    function _init() {
        _init_dom_elements( _init_events );
    }

    function _init_dom_elements( callback ){
        elems.triggerElems = jQuery('.backup-custom-exe');
        if( 'undefined' !== callback && 'function' === typeof(callback) ){
            callback();
        }
    }

    function _init_events(){
        elems.triggerElems.on('click', function( event ){

            var $this, domainId, request_url;

            if( ! backup_running ){

                $this = jQuery(this);
                domainId = $this.data('domainid');
                request_url = api_url + '/' + domainId;

                if( 'undefined' === typeof( whileBackupRun[ domainId ] ) || false === whileBackupRun[ domainId ] ){

                    window.onbeforeunload = confirmOnBackupsPageExit;

                    whileBackupRun[ domainId ] = true;

                    backup_running = true;

                    $this[0].innerHTML = '<i class="fa fa-spinner spin-animation"></i>';

                    jQuery.get(
                        request_url,
                        {}, // Options
                        function (data, status) {
                            /*console.log(data);
                            console.log(status);*/
                            if( ( 'undefined' !== data.status && 'success' === data.status) && ( 'undefined' === data.error || 1 !== parseInt( data.error, 10 ) ) ){
                                $this[0].innerHTML = '<i class="fa fa-check"></i> SUCCESS!';
                                $this.addClass('done success').unbind('click');
                                add_Backups_Completed_item( domainId, data.backup_info.bid, data.backup_info.name, data.url, data.backup_info.type, data.backup_info.date, true, true );
                            }
                            else{
                                $this[0].innerHTML = '<i class="fa fa-times"></i> Error.';
                                $this.addClass('done error').unbind('click');
                            }
                            backup_running = false;
                            update_whileBackupRun( domainId );
                        }, 'json' ).fail(function(data, status) {
                            console.log(data);
                            console.log(status);
                            console.log("FAILED: Backup execution");
                            $this[0].innerHTML = '<i class="fa fa-times"></i> Error.';
                            $this.addClass('done error').unbind('click');
                            backup_running = false;
                            update_whileBackupRun( domainId );
                        });
                
                }
                else{
                    already_running_backup_process();
                }
            }
        });
    }

    return {
        run : _init
    };
});

var Backups_Remove = ( function( site_url ) {

    if( '/' === site_url.substring( site_url.length - 1 ) || '\\' === site_url.substring( site_url.length - 1 ) ){
        site_url = site_url.substring( 0, site_url.length - 1 );
    }

    var api_url = site_url + '/auth/backups/delete';

    var elems = {
        triggerElems: null,
    };

    function _init() {
        _init_dom_elements( _init_events );
    }

    function _init_dom_elements( callback ){
        elems.triggerElems = jQuery('.backup-custom-remove');
        if( 'undefined' !== callback && 'function' === typeof(callback) ){
            callback();
        }
    }

    function _init_events(){
        elems.triggerElems.on('click', function( event ){
            var $this = jQuery(this);
            var domainId = $this.data('domainid');
            var backupId = $this.data('backupid');
            var request_url = api_url + '/' + domainId + '/' + backupId;
            
            if('undefined' === typeof( whileBackupRun[ domainId ] ) || false === whileBackupRun[ domainId ] ){

                window.onbeforeunload = confirmOnBackupsPageExit;

                whileBackupRun[ domainId ] = true;

                $this[0].innerHTML = '<i class="fa fa-spinner spin-animation"></i>';

                jQuery.get(
                request_url,
                {}, // Options
                function (data, status) {
                    /*console.log(data);
                    console.log(status);*/
                    if( ( 'undefined' !== data.status && 'success' === data.status) && ( 'undefined' === data.error || 1 !== parseInt( data.error, 10 ) ) ){
                        var $trEl = jQuery( $this.parents('tr')[0] );
                        $this[0].innerHTML = '<i class="fa fa-check" style="color:#fff;"></i>';
                        setTimeout(function(){
                            $this.parent('div').parent('td').parent('tr').fadeOut( 500, function() {
                                remove_Backups_Completed_item( backupId, true );
                            });
                        }, 0);
                    }
                    else{
                        $this[0].innerHTML = '<i class="fa fa-times" style="color:red;"></i>';
                    }
                    update_whileBackupRun( domainId );
                }, 'json' ).fail(function(data, status) {
                    console.log(data);
                    console.log(status);
                    console.log("FAILED: Backup removal");
                    $this[0].innerHTML = '<i class="fa fa-times" style="color:red;"></i>';
                    update_whileBackupRun( domainId );
                });
            }
            else{
                already_running_backup_process();
            }
        });
    }

    return {
        run : _init
    };
});

var Backups_Restore = ( function( site_url ) {

    if( '/' === site_url.substring( site_url.length - 1 ) || '\\' === site_url.substring( site_url.length - 1 ) ){
        site_url = site_url.substring( 0, site_url.length - 1 );
    }

    var api_url = site_url + '/auth/backups/restore';

    var elems = {
        triggerElems: null,
    };

    function _init() {
        _init_dom_elements( _init_events );
    }

    function _init_dom_elements( callback ){
        elems.triggerElems = jQuery('.backup-custom-restore');
        if( 'undefined' !== callback && 'function' === typeof(callback) ){
            callback();
        }
    }

    function _init_events(){
        elems.triggerElems.on('click', function( event ){
            var $this = jQuery(this);
            var domainId = $this.data('domainid');
            var backupId = $this.data('backupid');
            var request_url = api_url + '/' + domainId + '/' + backupId;

            if( 'undefined' === typeof( whileBackupRun[ domainId ] ) || false === whileBackupRun[ domainId ] ){

                window.onbeforeunload = confirmOnBackupsPageExit;

                whileBackupRun[ domainId ] = true;

                $this[0].innerHTML = '<i class="fa fa-spinner spin-animation"></i>';

                jQuery.get(
                request_url,
                {}, // Options
                function (data, status) {
                    /*console.log(data);
                    console.log(status);*/
                    if( ( 'undefined' !== data.status && 'success' === data.status) && ( 'undefined' === data.error || 1 !== parseInt( data.error, 10 ) ) ){
                        $this[0].innerHTML = '<i class="fa fa-check" style="color:green;"></i>';
                    }
                    else{
                        $this[0].innerHTML = '<i class="fa fa-times" style="color:red;"></i>';
                    }
                    update_whileBackupRun( domainId );
                }, 'json' ).fail(function(data, status) {
                    console.log(data);
                    console.log(status);
                    console.log("FAILED: Backup restore");
                    $this[0].innerHTML = '<i class="fa fa-times" style="color:red;"></i>';
                    update_whileBackupRun( domainId );
                });
            }
            else{
                already_running_backup_process();
            }
        });
    }

    return {
        run : _init
    };
});

function buckup_settings_pages() {

    switch( jQuery('input[name="backupType"]:checked').val() ){
        case 'files-db':
            jQuery('.exclude-files-group').removeClass('hidden');
        break;
        case 'db-only':
            jQuery('.exclude-files-group').addClass('hidden');
        break;
    }

    jQuery('input[name="backupType"]').on('change', function(){
        switch( jQuery( this ).val() ){
            case 'files-db':
                jQuery('.exclude-files-group').removeClass('hidden');
            break;
            case 'db-only':
                jQuery('.exclude-files-group').addClass('hidden');
            break;
        }
    });

}

$(document).ready(function () {

    if( 'undefined' !== siteUrl ){
        
        var trirSlash_siteUrl = false;

        if( '/' === siteUrl.substring( siteUrl.length - 1 ) || '\\' === siteUrl.substring( siteUrl.length - 1 ) ){
            trirSlash_siteUrl = siteUrl.substring( 0, siteUrl.length - 1 );
        }

        if( jQuery('.site-wp-updates-list').length && jQuery('.currDomainId').length ){

            if( jQuery('.currDomainId').val() === 'missing-data' ){
                console.log('WARNING: Missing domain data, admin URL or/and username.');
            }
            else{
                var wp_upgrade_object = new WP_Upgrades( siteUrl, jQuery('.currDomainId').val() );
                wp_upgrade_object.run();
            }
        }

        // if( jQuery('.myupdates.all-sites-updates').length ){
        if( jQuery('.available-wp-updates').length ){
            var wp_summ_upgrade_object = new WP_Summ_Upgrades( siteUrl );
            wp_summ_upgrade_object.run();
        }

        if( jQuery('.backups_completed').length ){
            var backups_list_object = new Backups_List( siteUrl, jQuery('.currDomainId').length ? parseInt( jQuery('.currDomainId').val(), 10) : null );
            backups_list_object.run();
        }

        if( jQuery('.backups_tasks_list').length ){
            var backups_tasks_list_object = new Backups_Tasks_List( siteUrl, jQuery('.currDomainId').length ? parseInt( jQuery('.currDomainId').val(), 10) : null );
            backups_tasks_list_object.run();
        }

        jQuery('.backups-page-msg .close-alert').on('click', function(){
            jQuery(this).parent( '.backups-page-msg' ).slideUp();
        });

        init_backups_exe();
    }

    if( jQuery('.backups-settings-form').length ){
        buckup_settings_pages();
    }
});


$(function(){
    'use strict';
    $(".cmskill").each(function () {
        var pint = $(this).attr('data-skills');
        var decs = pint * 100;
        var grs = $(this).attr('data-gradientstart');
        var gre = $(this).attr('data-gradientend');

        $(this).circleProgress({
            value: pint,
            startAngle: -Math.PI / 4 * 2,
            fill: {gradient: [[grs, 1], [gre, .2]], gradientAngle: Math.PI / 4 * 2},
            lineCap: 'round',
            thickness: 20,
            animation: {duration: 1800},
            size: 180
        }).on('circle-animation-progress', function (event, progress) {
            $(this).find('strong').html(parseInt(decs * progress) + '<span> / 100</span>');
        });
    });
});