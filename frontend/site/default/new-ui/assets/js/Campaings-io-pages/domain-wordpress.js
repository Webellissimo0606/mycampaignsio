window.WordPressUpdatesConfirmPageLeave = false;
window.WordPressUpdatesCancel = false;

function WordPressUpdatesConfirmPageLeaveFn(event) {
    "use strict";
    if ( window.WordPressUpdatesConfirmPageLeave ) {
        var m = 'Changes you made may not be saved.';
        event.returnValue = m;

        if( m ){
            window.WordPressUpdatesCancel = true;
        }

        return m;
    }
}

var WordPressUpdates = (function(){

    var isOnProcess = false;
    var buffer = {};
    var updatingNow = { core: false, theme: {}, plugin: {} };

    var domain_id_elem, domain_id, siteBaseURL_elem, siteBaseURL;

    function update_request( post_data ){
        
        isOnProcess = true;
        
        jQuery.ajax({
            type: 'post',
            dataType: 'json',
            // url: siteBaseURL + 'auth/site/update_now/' + domain_id,
            url: siteBaseURL + 'domains/'+ domain_id + '/wordpress/update',
            data: post_data,
            success: function (data) {
                
                var tmp_btn;

                if( 'undefined' !== typeof data.themes ){
                    if( 'undefined' !== typeof data.themes.upgraded ){
                        for(k in data.themes.upgraded){
                            if( data.themes.upgraded.hasOwnProperty(k) ){
                                tmp_btn = document.querySelector('button[data-update-id="' + k + '"]');
                                if( 1 === parseInt( data.themes.upgraded[k] ) ){
                                    if( tmp_btn ){
                                        tmp_btn.innerHTML = '<i class="material-icons">&#xE876;</i><small class="fw7">UPDATED</small>';
                                        tmp_btn.className = 'dib mv1 mh1 f7 btn-color success no-underline pv1 pr2 pl1-l br1';
                                    }
                                    updatingNow.theme[k] = undefined;
                                }
                                else{
                                    if( tmp_btn ){
                                        tmp_btn.innerHTML = '<i class="material-icons">&#xE5CD;</i><small class="fw7">ERROR</small>';
                                        tmp_btn.className = 'dib mv1 mh1 f7 btn-color error no-underline pv1 pr2 pl1-l br1';
                                    }
                                }
                            }
                        }
                    }
                }

                if( 'undefined' !== typeof data.plugins ){
                    if( 'undefined' !== typeof data.plugins.upgraded ){
                        for(k in data.plugins.upgraded){
                            if( data.plugins.upgraded.hasOwnProperty(k) ){
                                tmp_btn = document.querySelector('button[data-update-id="' + k + '"]');
                                if( 1 === parseInt( data.plugins.upgraded[k] ) ){
                                    if( tmp_btn ){
                                        tmp_btn.innerHTML = '<i class="material-icons">&#xE876;</i><small class="fw7">UPDATED</small>';
                                        tmp_btn.className = 'dib mv1 mh1 f7 btn-color success no-underline pv1 pr2 pl1-l br1';
                                    }
                                    updatingNow.plugin[k] = undefined;
                                }
                                else{
                                    if( tmp_btn ){
                                        tmp_btn.innerHTML = '<i class="material-icons">&#xE5CD;</i><small class="fw7">ERROR</small>';
                                        tmp_btn.className = 'dib mv1 mh1 f7 btn-color error no-underline pv1 pr2 pl1-l br1';
                                    }
                                }
                            }
                        }
                    }
                }

                if( 'undefined' !== typeof data.core ){
                    data.core.error = parseInt( data.core.error, 10 );
                    tmp_btn = document.querySelector('button[data-update-type="core"]');
                    if( 1 !== data.core.error && 0 === data.core.error ){
                        if( tmp_btn ){
                            tmp_btn.innerHTML = '<i class="material-icons">&#xE876;</i><small class="fw7">UPDATED</small>';
                            tmp_btn.className = 'dib mv1 mh1 f7 btn-color success no-underline pv1 pr2 pl1-l br1';
                        }
                        updatingNow.core = false;
                    }
                    else{
                        if( tmp_btn ){
                            tmp_btn.innerHTML = '<i class="material-icons">&#xE5CD;</i><small class="fw7">ERROR</small>';
                            tmp_btn.className = 'dib mv1 mh1 f7 btn-color error no-underline pv1 pr2 pl1-l br1';
                        }
                    }
                }
            },
            error: function (data) {

                if( 'undefined' !== typeof post_data.theme ){
                    for(k in post_data.theme){
                        if( post_data.theme.hasOwnProperty(k) ){
                            tmp_btn = document.querySelector('button[data-update-id="' + post_data.theme[k] + '"]');
                            if( tmp_btn ){
                                tmp_btn.innerHTML = '<i class="material-icons">&#xE5CD;</i><small class="fw7">ERROR</small>';
                                tmp_btn.className = 'dib mv1 mh1 f7 btn-color error no-underline pv1 pr2 pl1-l br1';
                            }
                        }
                    }
                }

                if( 'undefined' !== typeof post_data.plugin ){
                    for(k in post_data.plugin){
                        if( post_data.plugin.hasOwnProperty(k) ){
                            tmp_btn = document.querySelector('button[data-update-id="' + post_data.plugin[k] + '"]');
                            if( tmp_btn ){
                                tmp_btn.innerHTML = '<i class="material-icons">&#xE5CD;</i><small class="fw7">ERROR</small>';
                                tmp_btn.className = 'dib mv1 mh1 f7 btn-color error no-underline pv1 pr2 pl1-l br1';
                            }
                        }
                    }
                }

                if( 'undefined' !== typeof post_data.core ){
                    tmp_btn = document.querySelector('button[data-update-type="core"]');
                    if( tmp_btn ){
                        tmp_btn.innerHTML = '<i class="material-icons">&#xE5CD;</i><small class="fw7">ERROR</small>';
                        tmp_btn.className = 'dib mv1 mh1 f7 btn-color error no-underline pv1 pr2 pl1-l br1';
                    }
                }

                isOnProcess = false;
                window.WordPressUpdatesConfirmPageLeave = false;
            },
        }).done(function (data) {
            if( window.WordPressUpdatesCancel ){
                return;
            }
            if( Object.keys( buffer ).length  ){
                update_request( buffer );
                buffer = {};
                window.WordPressUpdatesConfirmPageLeave = false;
            }
            else{
                isOnProcess = false;
            }
        });
    }

    function update_themes(ids){
        var i;
        if( ids.length ){
            for(i=0; i<ids.length; i++){ updatingNow.theme[ ids[i] ] = true; }
            update_request( {theme: ids} );
        }
    }

    function update_plugins(ids){
        var i;
        if( ids.length ){
            for(i=0; i<ids.length; i++){ updatingNow.plugin[ ids[i] ] = true; }
            update_request( {plugin: ids} );
        }
    }

        
    function update_core(){
        updatingNow.core = true;
        update_request( {core: 1} );
    }
    
    function on_click_update(ev){

        ev.preventDefault();
        ev.stopPropagation();

        var updateFunc, btnData = { type: this.getAttribute('data-update-type') };

        if( 'plugin' === btnData.type || 'theme' === btnData.type  || 'core' === btnData.type ){

            if( 'core' === btnData.type ){

                if( updatingNow[ btnData.type ] ){ 
                    return;
                }

                if( isOnProcess ){
                    buffer[btnData.type] = 1;
                    window.WordPressUpdatesConfirmPageLeave = true;
                }
                else{
                    update_core();
                }
            }
            else {
                
                btnData.id = this.getAttribute('data-update-id');

                if( ! btnData.id || 'undefined' !== typeof updatingNow[ btnData.type ][ btnData.id ] ){
                    return; 
                }

                if( isOnProcess ){
                    if( 'undefined' === typeof buffer[btnData.type] ){
                        buffer[btnData.type] = [];
                    }
                    buffer[btnData.type].push( btnData.id );
                    window.WordPressUpdatesConfirmPageLeave = true; 
                }
                else{
                    updateFunc = 'plugin' === btnData.type ? update_plugins : update_themes;
                    updateFunc( [ btnData.id ] );
                }
            }

            this.innerHTML = '<i class="material-icons">&#xE8D7;</i><small class="fw7">UPDATING...</small>';
            this.setAttribute("disabled", "disabled");
            this.className = 'dib btn-lines mv1 mh1 f7 fw5 no-underline pv1 pr2 pl1-l br1';
        }
    }

    var init = function(){
        
        var i, updateButtons = document.querySelectorAll('.wp-update-btn');

        if( updateButtons.length ){

            domain_id_elem = document.querySelector('input[name="domain_id"]');
            domain_id = domain_id_elem ? domain_id_elem.value : null;

            siteBaseURL_elem = document.querySelector('input[name="base_url"]');
            siteBaseURL = siteBaseURL_elem ? siteBaseURL_elem.value : null;

            for(i=0; i<updateButtons.length; i++){
                updateButtons[i].addEventListener('click', on_click_update );
            }

            window.onbeforeunload = WordPressUpdatesConfirmPageLeaveFn;
        }
    }

    return {
        init: init,
    }
}());

(function(){
    "use strict";
    WordPressUpdates.init();
}());