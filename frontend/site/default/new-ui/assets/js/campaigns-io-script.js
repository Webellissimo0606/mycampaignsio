var DataTableOptions = {
    perPage: 5,
    perPageDefault: 5,
    perPageSelect: [5, 10, 15, 20, 25, 50, 100],
    prevText: "Prev",
    nextText: "Next",
    fixedColumns: false,
    labels: {
        placeholder: "Search...", // The search input placeholder
        perPage: "{select}", // per-page dropdown label
        noRows: "No entries found", // Message shown when there are no search results
        info: "Showing {start} to {end} of {rows} entries"
    }
};

var DataTableElemInit = function( selector, def_rows_per_page, table_id ){
    var dtable, rows_per_page, cookie_id = 'campaigns-io[table-rows-per-page]' + ( 'undefined' !== typeof table_id ? '[' + table_id + ']' : '' );
    rows_per_page = getCookie(cookie_id);
    rows_per_page = rows_per_page ? intval(rows_per_page) : rows_per_page;
    rows_per_page = ! rows_per_page && 'undefined' !== typeof def_rows_per_page ? def_rows_per_page : rows_per_page;
    rows_per_page = ! rows_per_page ? DataTableOptions.perPage : rows_per_page;
    DataTableOptions.perPage = rows_per_page ? rows_per_page : DataTableOptions.perPageDefault;
    dtable = new DataTable( selector, DataTableOptions );
    if( dtable && dtable.wrapper ) dtable.on( 'datatable.perpage', function(val){ setCookie(cookie_id, val, 7) });
    return dtable;
};

var MyCampaignsIo = (function(){

    var elems = {
        app_wrapper: null,
        header: {
            search: {
                form: null,
                input: null,
                submit: null,
                close: null
            },
        },
        sidebar: {
            collapse_expand: null,
            author_thumb_wrap: null
        },
        pages: {
            edit_site: {
                tabs: null,
                tabs_content_wrapper: null,
                tabs_buttons_wrapper: null,
                prev_button: null,
                next_button: null
            }
        },
        select_domain: null
    };

    var states = {
        pages: {
            edit_site: {
                active_tab: 1
            }
        }
    };

    /* ------------------------------ // Initialization Functions ------------------------------ */

    function init(){
        elems.app_wrapper = document.getElementById('campaign-io-admin');
        elems.select_domain = document.querySelector('.select-domain select');
        init_header();
        init_sidebar();
        init_pages();
        init_contents_in_tabs();
        init_message_elements();
        init_filter_tables();
        
        setTimeout(function(){
            Util.removeClass(elems.app_wrapper, 'before-init');
        }, 100);
        
        if( elems.select_domain ){
            
            new Choices( elems.select_domain );

            elems.select_domain.onchange = function(e){
                if( this.value ){
                    window.location.href = this.value;
                }
            };
        }

        // Uptime stats period/days filter.
        elems.uptime_stats_days = document.querySelector('select.uptime-stats-days');
        if( elems.uptime_stats_days ){
            new Choices( elems.uptime_stats_days, false );

            elems.uptime_stats_days.onchange = function(e){
                if( this.value ){
                    document.getElementById('frm_status_report').submit();
                }
            };
        }
    }

    function init_header(){

        elems.header.search.form = document.querySelector('.header-search form');

        if( elems.header.search.form ){

            elems.header.search.input = elems.header.search.form.querySelector('.search-input');
            elems.header.search.submit = elems.header.search.form.querySelector('.search-submit');
            elems.header.search.close = elems.header.search.form.querySelector('.search-close');

            if( elems.header.search.submit ){
                elems.header.search.submit.addEventListener('click', on_click_header_search_submit );
            }

            if( elems.header.search.close ){
                elems.header.search.close.addEventListener('click', on_click_header_search_close);
            }
        }
    }
    
    function init_sidebar(){

        elems.sidebar.author_thumb_wrap = document.querySelector('.author-thumb-wrap');

        if( elems.sidebar.author_thumb_wrap ){
            elems.sidebar.author_thumb_wrap.addEventListener('click', function(ev){
                ev.preventDefault();
                ev.stopPropagation();
                var cookieVal = getCookie('campaigns-io[collapse-author-nav]');
                setCookie('campaigns-io[collapse-author-nav]', intval( cookieVal ) ? 0 : 1, 7);
                Util.toggleClass(elems.app_wrapper, 'collapse-author-nav');
            });
        }

        elems.sidebar.collapse_expand = document.querySelector('.collapse-expand');
        if( elems.sidebar.collapse_expand ){
            elems.sidebar.collapse_expand.addEventListener('click', function(ev){
                ev.preventDefault();
                ev.stopPropagation();
                Util.toggleClass(elems.app_wrapper, 'collapse-sidebar');
                jQuery(window).trigger('resize');
                var cookieVal = getCookie('campaigns-io[collapse-sidebar]');
                setCookie('campaigns-io[collapse-sidebar]', intval( cookieVal ) ? 0 : 1, 7);
            });
        }
    }

    function init_contents_in_tabs(){
        var i, j, row_tab_items, tab_content_rows = elems.app_wrapper ? elems.app_wrapper.querySelectorAll('.content-row.tab-contents-row') : null;
        if( tab_content_rows ){
            for(i=0; i<tab_content_rows.length; i++){
                row_tab_items = tab_content_rows[i].querySelectorAll('.content-tab-items ul li');
                if( row_tab_items ){
                    for( j=0; j<row_tab_items.length; j++ ){
                        row_tab_items[j].addEventListener( 'click', function(ev){ on_click_content_row_tab( ev, this ); });
                    }
                }
            }
        }
    }

    function init_pages(){
        init_page_edit_site();
    }

    function init_page_edit_site(){
        var i, page_elems = elems.pages.edit_site;
        page_elems.tabs = document.querySelectorAll('.edit-site-tabs li');
        if( page_elems.tabs ){
            page_elems.tabs_content_wrapper = document.querySelector('.edit-site-form-inner');
            page_elems.tabs_buttons_wrapper = document.querySelector('.edit-site-form-buttons-wrap');
            if( page_elems.tabs_buttons_wrapper ){
                page_elems.prev_button = page_elems.tabs_buttons_wrapper.querySelector('button.prev');
                page_elems.next_button = page_elems.tabs_buttons_wrapper.querySelector('button.next');
                if( page_elems.prev_button ){ page_elems.prev_button.addEventListener('click', on_click_edit_site_prev_button); }
                if( page_elems.next_button ){ page_elems.next_button.addEventListener('click', on_click_edit_site_next_button); }
            }
            for(i=0; i<page_elems.tabs.length; i++){ page_elems.tabs[i].addEventListener('click', on_click_edit_site_tab); }
        }
    }

    function init_message_elements(){
        var i, close_elems = document.querySelectorAll('.msg .close');
        if( close_elems.length ){
            for(i=0; i<close_elems.length; i++){
                close_elems[i].addEventListener('click', on_click_message_close_elems );
            }
        }
    }

    function init_filter_tables(){
        var i, selector = ".filter-table", filterTables = document.querySelectorAll(selector),
            table_rows_per_page = 5,
            this_table_id,
            this_table_rows_per_page;
        
        if( filterTables ){ 
            
            for(i=0; i<filterTables.length; i++){

                // @note: Doesn't need to apply filters without records.
                if( filterTables[i].querySelector('tbody tr.no-records') ) continue;

                // @note: Handle SERP page "Edit Keywords" table in file 'domain-serps.js'.
                if( Util.hasClass( filterTables[i], 'serps-info-table' ) ) continue;

                // @note: Handle SERP page "Edit Keywords" table in file 'domain-serps.js'.
                if( Util.hasClass( filterTables[i], 'edit-keywords-table' ) ) continue;

                // @note: Handle SERP page "Edit Keywords" table in file 'domain-serps.js'.
                if( Util.hasClass( filterTables[i], 'webmaster-tools-table' ) ) continue;

                this_table_id = filterTables[i].getAttribute('data-table-id');

                this_table_rows_per_page = filterTables[i].getAttribute('data-rows-per-page'); 
                this_table_rows_per_page = this_table_rows_per_page ? this_table_rows_per_page : table_rows_per_page;

                DataTableElemInit( filterTables[i], this_table_rows_per_page, this_table_id );
            }
        }
    }

    /* ------------------------------ // Events Handlers ------------------------------ */

    function on_click_header_search_submit(ev){
        if( ! Util.hasClass( elems.header.search.form, 'enable' ) ){
            ev.preventDefault();
            ev.stopPropagation();
            Util.addClass( elems.header.search.form, 'enable' );
            elems.header.search.input.focus();
        }
    }

    function on_click_header_search_close(ev){
        Util.removeClass( elems.header.search.form, 'enable' );
    }

    function on_click_edit_site_prev_button(ev){
        ev.preventDefault();
        ev.stopPropagation();
        page_edit_site_update_tab( states.pages.edit_site.active_tab - 1 );
    }

    function on_click_edit_site_next_button(ev){
        ev.preventDefault();
        ev.stopPropagation();
        page_edit_site_update_tab( states.pages.edit_site.active_tab + 1 );
    }

    function on_click_edit_site_tab(ev){
        ev.preventDefault();
        ev.stopPropagation();
        page_edit_site_update_tab( intval( this.getAttribute('data-tab') ) );
    }

    function on_click_content_row_tab(ev, item){

        ev.preventDefault();
        ev.stopPropagation();

        var i, prev_active_tab, prev_active_tab_num, wrapper_row, prev_active_elem, new_active_elem, tabs_group_id;

        if( ! Util.hasClass( item, 'active' ) ){

            prev_active_tab = item.parentNode.querySelector('li.active');
            prev_active_tab_num = 0;
            
            new_active_tab_num = intval( item.getAttribute('data-tab-item') );

            if( prev_active_tab ){
                prev_active_tab_num = intval( prev_active_tab.getAttribute('data-tab-item') );
                Util.removeClass( prev_active_tab, 'active' );
            }

            wrapper_row = item.parentNode;

            ok = Util.hasClass('.content-row') && Util.hasClass('.tab-contents-row');
            i = 0;
            while(!ok && i < 20){   // NOTE: Use counter 'i' just in case that something changed in HTMl and is not possible to find wrapper row element.
                wrapper_row = wrapper_row.parentNode;
                ok = Util.hasClass(wrapper_row, 'content-row') && Util.hasClass(wrapper_row, 'tab-contents-row');
                i++;
            }

            if( ok && wrapper_row ){
                if( new_active_tab_num ){
                    new_active_elem = wrapper_row.querySelector('[data-tab-content="'+new_active_tab_num+'"]');
                    if( new_active_elem ){
                        Util.addClass( new_active_elem, 'active' );
                    }
                }
                if( prev_active_tab_num ){
                    prev_active_elem = wrapper_row.querySelector('[data-tab-content="'+prev_active_tab_num+'"]');
                    if( prev_active_elem ){
                        Util.removeClass( prev_active_elem, 'active' );
                    }
                }
            }

            Util.addClass( item, 'active' );

            tabs_group_id = wrapper_row.getAttribute('data-tab-id');
            tabs_group_id = 'undefined' !== typeof tabs_group_id && null !== tabs_group_id && '' !== tabs_group_id ? tabs_group_id : null;
            if( null !== tabs_group_id ){
                var cookieVal = getCookie();
                setCookie( 'campaigns-io[content-tabs][' + tabs_group_id + ']', new_active_tab_num, 7 );
            }
        }
    }

    function on_click_message_close_elems(ev){
        
        ev.preventDefault();
        ev.stopPropagation();
        
        var parent = this.parentNode;
        
        if( Util.hasClass(parent,'msg') ){
            parent.parentNode.removeChild(parent);
        }
    }

    /* ------------------------------ // Helper Functions ------------------------------ */

    function page_edit_site_update_tab(tab_num){
        if( states.pages.edit_site.active_tab !== tab_num ){
            
            var i, tmp_fn, this_tab_num,
                page_elems = elems.pages.edit_site,
                transitionMs = 100,
                addClassname = [],
                removeClassnames = [];

            states.pages.edit_site.active_tab = tab_num;

            for(i=0; i<page_elems.tabs.length; i++){
                this_tab_num = intval( page_elems.tabs[i].getAttribute('data-tab') );
                if( tab_num === this_tab_num ){
                    tmp_fn = Util.addClass;
                    addClassname.push( 'active-' + this_tab_num );
                }
                else{
                    tmp_fn = Util.removeClass;
                    removeClassnames.push( 'active-' + this_tab_num );
                }
                tmp_fn( page_elems.tabs[i], 'active' );
            }

            if( addClassname.length ){
                for(i=0; i<addClassname.length; i++){
                    Util.addClass( page_elems.tabs_content_wrapper, addClassname[i] );
                }
            }

            if( removeClassnames.length ){
                for(i=0; i<removeClassnames.length; i++){
                    Util.removeClass( page_elems.tabs_content_wrapper, removeClassnames[i] );
                }
            }

            setTimeout(function(){
                if( addClassname.length ){
                    for(i=0; i<addClassname.length; i++){
                        Util.addClass( page_elems.tabs_buttons_wrapper, addClassname[i] );
                    }
                }
                if( removeClassnames.length ){
                    for(i=0; i<removeClassnames.length; i++){
                        Util.removeClass( page_elems.tabs_buttons_wrapper, removeClassnames[i] );
                    }
                }      
            }, transitionMs);
        }
    }

    /* ------------------------------ // Return - Object public face ------------------------------ */

    return {
        run: init
    };
}());

var Util = (function () {

    'use strict';

    var initialDocumentWidth = document.documentElement.clientWidth || document.body.clientWidth,
        initialPageYOffset = 'undefined' !== typeof (window.pageYOffset) ? window.pageYOffset : (document.documentElement.scrollTop || document.body.scrollTop),

        eventsStruct = {
            // events - a super-basic Javascript (publish subscribe) pattern
            // @future: Replace this function with jQuery special events???
            events: {},
            on: function (eventName, fn) {
                this.events[eventName] = this.events[eventName] || [];
                this.events[eventName].push(fn);
            },
            off: function (eventName, fn) {
                if (this.events[eventName]) {
                    var i;
                    for (i = 0; i < this.events[eventName].length; i = i + 1) {
                        if (this.events[eventName][i] === fn) {
                            this.events[eventName].splice(i, 1);
                            break;
                        }
                    }
                }
            },
            emit: function (eventName, data) {
                if (this.events[eventName]) {
                    var i,
                        l = this.events[eventName].length;

                    for (i = 0; i < l; i = i + 1) {
                        this.events[eventName][i](data);
                    }

                    // Replaced to work in IE8
                    /*this.events[eventName].forEach(function(fn) {
                        fn(data);
                    });*/
                }
            }
        },

        addClickEvent = function (theElem, theFunction, isTouchable, isJqueryObject) {
            if (theElem) {
                // console.log("BIND: ", isTouchable);
                try {
                    if (isJqueryObject) {
                        if (isTouchable) {
                            theElem.on('touchend', theFunction);
                        }
                        theElem.on('click', theFunction);
                    } else {
                        if (isTouchable) {
                            theElem.addEventListener('touchend', theFunction);
                        }
                        theElem.addEventListener('click', theFunction);
                    }
                } catch (error) {
                    console.error(error);
                    /*alert("!!!!! " + "Error in function 'bindClickEvent'");
                    console.log("!!!!! " + "Error in function 'bindClickEvent'");
                    console.log("!!!!! " + error);
                    console.log("!!!!! " + theElem);
                    console.log("!!!!! " + theFunction);
                    console.log("!!!!! " + isJqueryObject);*/
                }
            }
        },

        removeClickEvent = function (theElem, theFunction, isTouchable, isJqueryObject) {
            if (theElem) {
                try {
                    if (isJqueryObject) {
                        if (isTouchable) {
                            theElem.off('touchend', theFunction);
                        }
                        theElem.off('click', theFunction);
                    } else {
                        if (isTouchable) {
                            theElem.removeEventListener('touchend', theFunction);
                        }
                        theElem.removeEventListener('click', theFunction);
                    }
                } catch (error) {
                    console.error(error);
                    /*alert("!!!!! " + "Error in function 'unbindClickEvent'");
                    console.log("!!!!! " + "Error in function 'unbindClickEvent'");
                    console.log("!!!!! " + error);
                    console.log("!!!!! " + theElem);
                    console.log("!!!!! " + theFunction);
                    console.log("!!!!! " + isJqueryObject);*/
                }
            }
        },

        setCSStyle = function (el, property, value) {
            try {
                el.style[property] = value; // @note: Doesn't work in IE8, but it's ok because plugin doesn't support IE8.
            } catch (error) {
                property = property.replace(/([A-Z])/g, '-$1').toLowerCase();   // @note: Because jQuery doesn't support camel-case properties names (eg. "maxHeight" ).
                jQuery(el).css(property, value);
            }
        },

        toggleClassDOM = function (el, classVal) {
            if( hasClassDOM(el, classVal) ){
                removeClassDOM(el, classVal);
            }
            else{
                addClassDOM(el, classVal);
            }
        },

        hasClassDOM = function (el, classVal) {
            return el.className && new RegExp("(\\s|^)" + classVal + "(\\s|$)").test(el.className);
        },

        addClassDOM = function (el, theClass) {
            if (el.classList) {
                el.classList.add(theClass);
            } else {
                el.className += ' ' + theClass;
            }
        },

        removeClassDOM = function (el, theClass) {
            if (el.classList) {
                el.classList.remove(theClass);
            } else {
                el.className = el.className.replace(new RegExp('(^|\\b)' + theClass.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
            }
        };

    return {
        hasClass: hasClassDOM,
        addClass: addClassDOM,
        removeClass: removeClassDOM,
        toggleClass: toggleClassDOM,
    };
}());

(function(){
    "use strict";
    MyCampaignsIo.run();
}());

/* // Disabled on PIWIK removal.
function openPiwikCode(url){
    var win = window.open(url, "", "width=600,height=400");
}*/

function on_click_domain_remove(msg){
    if('undefined' !== typeof msg){
        if( msg ){
            msg = msg.trim();
            if( '' !== msg ){
                if( confirm(msg) ){
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                console.error("Invalid message content");
            }
        }
        else{
            console.error("Invalid message type");
        }
    }
    else{
        console.error("Invalid message");
    }
    return false;
}

function htmldecode(html) {
    var div = document.createElement('div');
    div.innerHTML = html;
    return div.innerText;
}

function intval(v){
    return parseInt(v,10);
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function deleteCookie(cname){
    setCookie(cname, '', 0);
}
