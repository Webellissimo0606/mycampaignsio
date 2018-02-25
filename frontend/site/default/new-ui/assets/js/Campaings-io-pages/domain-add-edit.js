var EditDomainPage = (function(){

    var GoogleAccountChoices;

    var elems = {
        dropdown: {
            groups: null,
            engines: null,
            subusers: null,
            google_accounts: null,
        },
        switch: {
            connect_to_google: null,
            monitor_website_uptime: null,
        },
        field:{
            page_header: null,
            page_body: null,
            page_footer: null,
            frequency: null,
            google_account: null,
            crawl_errors: null,
            track_queries: null,
        },
        button: {
            add_google_account: null,
        }
    };

    function init_elems(){

        elems.dropdown.groups = document.querySelector('select[name="groups[]"].multi-select-box');
        elems.dropdown.engines = document.querySelector('select[name="engines[]"].multi-select-box');
        elems.dropdown.subusers = document.querySelector('select[name="subusers[]"].multi-select-box');
        elems.dropdown.google_accounts = document.querySelector('select[name="ga_account"]');

        elems.switch.connect_to_google = document.querySelector('input[name="connect_to_google"]');
        elems.switch.monitor_website_uptime = document.querySelector('input[name="monitor_website_uptime"]');

        elems.field.page_header = document.querySelector('.monitor_uptime_page_header_field');
        elems.field.page_body = document.querySelector('.monitor_uptime_page_body_field');
        elems.field.page_footer = document.querySelector('.monitor_uptime_page_footer_field');
        elems.field.frequency = document.querySelector('.monitor_uptime_freq_field');

        elems.field.google_account = document.querySelector('.choose-ga-account-field');
        elems.field.crawl_errors = document.querySelector('.choose-crawl-errors-field');
        elems.field.track_queries = document.querySelector('.choose-track-search-queries-field');

        elems.button.add_google_account = document.querySelector('.add-new-google-account');
    }

    function init_dropdowns(){
        if( elems.dropdown.subusers ){ new Choices( elems.dropdown.subusers, { maxItemCount: 3, removeItemButton: true } ); }
        if( elems.dropdown.groups ){ new Choices( elems.dropdown.groups, { maxItemCount: 3, position: 'top', removeItemButton: true } ); }
        if( elems.dropdown.engines ){ new Choices( elems.dropdown.engines, { maxItemCount: 3, removeItemButton: true } ); }
        if( elems.dropdown.google_accounts ){ GoogleAccountChoices = new Choices( elems.dropdown.google_accounts ) }
    }

    function init_switches(){
        if( elems.switch.monitor_website_uptime ){
            if( elems.field.page_header ){ elems.field.page_header.style.display = elems.switch.monitor_website_uptime.checked ? '' : 'none'; }
            if( elems.field.page_body ){ elems.field.page_body.style.display = elems.switch.monitor_website_uptime.checked ? '' : 'none'; }
            if( elems.field.page_footer ){ elems.field.page_footer.style.display = elems.switch.monitor_website_uptime.checked ? '' : 'none'; }
            if( elems.field.frequency ){ 
                new Choices( elems.field.frequency.querySelector('select'), { shouldSort: false } );
                elems.field.frequency.style.display = elems.switch.monitor_website_uptime.checked ? '' : 'none';
            }
        }
        if( elems.switch.connect_to_google ){
            if( elems.field.google_account ){ elems.field.google_account.style.display = elems.switch.connect_to_google.checked ? '' : 'none'; }
            if( elems.field.crawl_errors ){ elems.field.crawl_errors.style.display = elems.switch.connect_to_google.checked ? '' : 'none'; }
            if( elems.field.track_queries ){ elems.field.track_queries.style.display = elems.switch.connect_to_google.checked ? '' : 'none'; }
        }
    }

    function init_events(){
        if( elems.switch.monitor_website_uptime ){
            elems.switch.monitor_website_uptime.onchange = on_change_monitor_website_uptime;
        }
        if( elems.switch.connect_to_google ){
            elems.switch.connect_to_google.onchange = on_change_connect_to_google;
        }
        if( elems.button.add_google_account ){
            elems.button.add_google_account.addEventListener( 'click', on_click_add_new_ga_account );
        }
    }

    function on_change_monitor_website_uptime(ev){
        if( elems.field.page_header ){ elems.field.page_header.style.display = this.checked ? '' : 'none'; }
        if( elems.field.page_body ){ elems.field.page_body.style.display = this.checked ? '' : 'none'; }
        if( elems.field.page_footer ){ elems.field.page_footer.style.display = this.checked ? '' : 'none'; }
        if( elems.field.frequency ){ elems.field.frequency.style.display = this.checked ? '' : 'none'; }
    }

    function on_change_connect_to_google(ev){
        if( elems.field.google_account ){ elems.field.google_account.style.display = this.checked ? '' : 'none'; }
        if( elems.field.crawl_errors ){ elems.field.crawl_errors.style.display = this.checked ? '' : 'none'; }
        if( elems.field.track_queries ){ elems.field.track_queries.style.display = this.checked ? '' : 'none'; }
    }

    function on_click_add_new_ga_account(ev){
        
        ev.preventDefault();
        ev.stopPropagation();

        var TYPE = 'code';
        var Access_Type = 'offline';
        var ApprovalPrompt = 'force';

        var SCOPE = 'https://www.googleapis.com/auth/analytics.readonly';
            SCOPE += ' https://www.googleapis.com/auth/analytics';
            SCOPE += ' https://www.googleapis.com/auth/analytics.edit';
            SCOPE += ' https://www.googleapis.com/auth/analytics.manage.users';
            SCOPE += ' https://www.googleapis.com/auth/analytics.manage.users.readonly';
            SCOPE += ' https://www.googleapis.com/auth/analytics.manage.users.readonly';
            SCOPE += ' https://www.googleapis.com/auth/webmasters';

        var CLIENTID = document.querySelector('input[name="google_account_client_id"]').value;
        var REDIRECT = document.querySelector('input[name="google_auth_redirect_uri"]').value;
        
        var _url = "https://accounts.google.com/o/oauth2/auth?response_type=" + TYPE;
            _url += "&access_type=" + Access_Type;
            _url += "&client_id=" + CLIENTID;
            _url += "&redirect_uri=" + REDIRECT;
            _url += "&scope=" + SCOPE;
            _url += "&approval_prompt=" + ApprovalPrompt;

        var win = window.open(_url, "campaigns_io_add_ga_account", 'width=800, height=600');
        
        var elems_base_url = document.querySelector('input[name="base_url"]');

        var pollTimer = window.setInterval( function() {

            if ( "undefined" !== typeof win.document && "undefined" !== typeof win.document.URL ) {

                if ( -1 !== win.document.URL.indexOf( REDIRECT ) ) {
                    
                    window.clearInterval( pollTimer );
                    
                    win.close();

                    var selected_account = parseInt( elems.dropdown.google_accounts.value, 10);

                    jQuery.ajax({
                        type: "post",
                        url: elems_base_url.value + 'analytics/analytics/getUserGAAccounts',
                        dataType: 'json',
                        success: function (data, status) {
                            if ( 'success' === status ) {
                                var i, k, new_choices = [{ value: '0', label: 'Select' }];
                                if ( data.length ){
                                    for(i=0; i<data.length; i++){
                                        for(k in data[i]){
                                            if( data[i].hasOwnProperty(k) ){
                                                new_choices.push({ value: k, label: data[i][k] });
                                            }
                                        }
                                    }
                                }
                                
                                GoogleAccountChoices.clearStore();
                                GoogleAccountChoices.setValue( new_choices );
                                GoogleAccountChoices.setValueByChoice( '0' );
                            }
                            else{
                                console.log(data);
                            }
                        },
                        error: function (data) {
                            console.log( data );
                        },
                    });
                }
            }
        
        }, 1000);
    }

    this.init = function(){
        init_elems();
        init_dropdowns();
        init_switches();
        init_events();
    }

    return this;
}());

(function(){
    "use strict";
    EditDomainPage.init();
}());