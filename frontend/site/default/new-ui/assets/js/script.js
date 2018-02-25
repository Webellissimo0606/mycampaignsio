
var CampaignsIoPages = (function(){

    var elems = {
        is_dashboard_page: null,
        is_domain_analytics_page: null,
        is_business_overview_page: null,
        base_url: null,
        domain_id: null,
        domain_url: null,
        csrf_token: null,
        csrf_hash: null,
    };

    function init_seo_data_and_keyword_position(){
    
        jQuery.post( elems.base_url.value +'auth/analyze/getkeywordreport', {
            domain_id: elems.domain_id.value
        }, function (data) {
            var elem = {
                seo_data: {
                    moved_up_clicks: document.querySelector('.moved-up-clicks-val'),
                    moved_down_clicks: document.querySelector('.moved-down-clicks-val'),
                    no_changes: document.querySelector('.no-changes-val'),
                },
                key_pos: {
                    top10: document.querySelector('.keyword-pos-top-10-val'),
                    top20: document.querySelector('.keyword-pos-top-20-val'),
                    top50: document.querySelector('.keyword-pos-top-50-val'),
                },
            };

            if( elem.seo_data.moved_up_clicks ){
                elem.seo_data.moved_up_clicks.innerHTML = data.keyword_changes['positive'];    
            }

            if( elem.seo_data.moved_down_clicks ){
                elem.seo_data.moved_down_clicks.innerHTML = data.keyword_changes['negative'];
            }

            if( elem.seo_data.no_changes ){
                elem.seo_data.no_changes.innerHTML = data.keyword_changes['nochange'];
            }

            if( elem.key_pos.top10 ){
                elem.key_pos.top10.innerHTML = data.position['top10'];
            }

            if( elem.key_pos.top20 ){
                elem.key_pos.top20.innerHTML = data.position['top20'];
            }

            if( elem.key_pos.top50 ){
                elem.key_pos.top50.innerHTML = data.position['top50'];
            }

            /*var TPL = $('#template_keyword_stats').html(),
            keyword_average_position = Math.round(data.avg_position['average_position']);
            if( TPL ){
              TPL = TPL.replace(/{keyword_average_position}/g, keyword_average_position);
              
              TPL = TPL.replace(/{keyword_top5}/g, data.position['top5']);
              TPL = TPL.replace(/{keyword_total_search}/g, data.total_keywords['total_keyword_search']);
              $('#panel_template_keyword_stats').append(TPL);
            }*/
        }, 'json');

        jQuery.post( elems.base_url.value +'analytics/piwik/getsearchengineclicks', {
            domainId: elems.domain_id.value,
            [elems.csrf_token.value]: elems.csrf_hash.value,
        }, function(data){
            var elem = {
                seo_data: {
                    month_clicks: document.querySelector('.month-clicks-val'),
                },
            };
            if( elem.seo_data.month_clicks ){
                elem.seo_data.month_clicks.innerHTML = data.status == 'success' ? data.payload.totalClicks : 0;
            }
        },'json');
    }

    function init_gtmetrix_only_browser(){  // And server region.

        var el = {
            gtmetrix: {
                browser: {
                    wrapper: document.querySelector('.gtmetrix-browser-row'),
                    thumb: null,
                    desktop_mobile: null,
                    title: null,
                },
                serverRegionEl: document.querySelector('.server-region-val'),
            },
        };

        // Using Browser.

        if( el.gtmetrix.browser.wrapper ){
            el.gtmetrix.browser.thumb = el.gtmetrix.browser.wrapper.querySelector('.browser-thumb');
            el.gtmetrix.browser.desktop_mobile = el.gtmetrix.browser.wrapper.querySelector('.browser-desktop-mobile');
            el.gtmetrix.browser.title = el.gtmetrix.browser.wrapper.querySelector('.browser-title');
        }

        if( el.gtmetrix.browser.desktop_mobile ){
            el.gtmetrix.browser.desktop_mobile.innerHTML = isBrowser_inMobile() ? 'Mobile' : 'Desktop';
        }

        if( el.gtmetrix.browser.thumb && el.gtmetrix.browser.title ){
            if ( isBrowser_Chrome() ) {
                el.gtmetrix.browser.title.innerHTML = 'Chrome';
                el.gtmetrix.browser.thumb.src = elems.base_url.value + "assets/images/chrome.png";
            }
            else if ( isBrowser_Mozilla() ) {
                el.gtmetrix.browser.title.innerHTML = 'Mozilla';
                el.gtmetrix.browser.thumb.src = elems.base_url.value + "assets/images/mozilla.jpg";
            }
            else if ( isBrowser_Opera() ) {
                el.gtmetrix.browser.title.innerHTML = 'Opera';
                el.gtmetrix.browser.thumb.src = elems.base_url.value + "assets/images/opera.png";
            }
            else if ( isBrowser_IE() ) {
                el.gtmetrix.browser.title.innerHTML = 'IE';
                el.gtmetrix.browser.thumb.src = elems.base_url.value + "assets/images/ie.jpg";
            }
            else if ( isBrowser_Safari() ) {
                el.gtmetrix.browser.title.innerHTML = 'Safari';
                el.gtmetrix.browser.thumb.src = elems.base_url.value + "assets/images/safari.png";
            }
        }

        // Test Server Region.

        if( el.gtmetrix.serverRegionEl){
            el.gtmetrix.serverRegionEl.innerHTML = geoplugin_city() ? geoplugin_city() + ", " + geoplugin_countryName() : geoplugin_countryName();
        }
    }

    function init_gtmetrix(){

        var load_data = function( data, report_date ){

            var el = {
                gtmetrix: {
                    view_more_link: document.querySelector('.gtm-view-more-link'),
                    report_date: document.querySelector('.report_date'),
                    screenshot: document.querySelector('.gtm-screenshot'),
                    browser: {
                        wrapper: document.querySelector('.gtmetrix-browser-row'),
                        thumb: null,
                        desktop_mobile: null,
                        title: null,
                    },
                    serverRegionEl: document.querySelector('.server-region-val'),
                    load_time: {
                        wrapper: document.querySelector('.gtm-load-time'),
                        value: false,
                        unit: false,
                    },
                    page_size: {
                        wrapper: document.querySelector('.gtm-page-size'),
                        value: false,
                        unit: false,
                    },
                    requests: {
                        wrapper: document.querySelector('.gtm-requests'),
                        value: false,
                    },
                    page_speed: {
                        wrapper: document.querySelector('.gtm-page-speed'),
                        grade: false,
                        value: false,
                    },
                    yslow: {
                        wrapper: document.querySelector('.gtm-y-slow-score'),
                        grade: false,
                        value: false,
                    },
                }
            };

            // View more link.

            if( el.gtmetrix.view_more_link ){
                el.gtmetrix.view_more_link.href = data.report_url;
                el.gtmetrix.view_more_link.style.display = '';
            }

            // Report date.
            
            if( el.gtmetrix.report_date ){
                el.gtmetrix.report_date.innerHTML = report_date;
            }

            // Screenshot.

            if( el.gtmetrix.screenshot ){
                el.gtmetrix.screenshot.src = data.report_url + "/screenshot.jpg";
            }

            // Using Browser.

            if( el.gtmetrix.browser.wrapper ){
                el.gtmetrix.browser.thumb = el.gtmetrix.browser.wrapper.querySelector('.browser-thumb');
                el.gtmetrix.browser.desktop_mobile = el.gtmetrix.browser.wrapper.querySelector('.browser-desktop-mobile');
                el.gtmetrix.browser.title = el.gtmetrix.browser.wrapper.querySelector('.browser-title');
            }

            if( el.gtmetrix.browser.desktop_mobile ){
                el.gtmetrix.browser.desktop_mobile.innerHTML = isBrowser_inMobile() ? 'Mobile' : 'Desktop';
            }

            if( el.gtmetrix.browser.thumb && el.gtmetrix.browser.title ){
                if ( isBrowser_Chrome() ) {
                    el.gtmetrix.browser.title.innerHTML = 'Chrome';
                    el.gtmetrix.browser.thumb.src = elems.base_url.value + "assets/images/chrome.png";
                }
                else if ( isBrowser_Mozilla() ) {
                    el.gtmetrix.browser.title.innerHTML = 'Mozilla';
                    el.gtmetrix.browser.thumb.src = elems.base_url.value + "assets/images/mozilla.jpg";
                }
                else if ( isBrowser_Opera() ) {
                    el.gtmetrix.browser.title.innerHTML = 'Opera';
                    el.gtmetrix.browser.thumb.src = elems.base_url.value + "assets/images/opera.png";
                }
                else if ( isBrowser_IE() ) {
                    el.gtmetrix.browser.title.innerHTML = 'IE';
                    el.gtmetrix.browser.thumb.src = elems.base_url.value + "assets/images/ie.jpg";
                }
                else if ( isBrowser_Safari() ) {
                    el.gtmetrix.browser.title.innerHTML = 'Safari';
                    el.gtmetrix.browser.thumb.src = elems.base_url.value + "assets/images/safari.png";
                }
            }

            // Test Server Region.

            if( el.gtmetrix.serverRegionEl){
                el.gtmetrix.serverRegionEl.innerHTML = geoplugin_city() ? geoplugin_city() + ", " + geoplugin_countryName() : geoplugin_countryName();
            }

            // Load Time.

            if( el.gtmetrix.load_time.wrapper ){
                el.gtmetrix.load_time.value = el.gtmetrix.load_time.wrapper.querySelector('.time-val');
                el.gtmetrix.load_time.unit = el.gtmetrix.load_time.wrapper.querySelector('.time-unit');
            }

            if( el.gtmetrix.load_time.value ){
                el.gtmetrix.load_time.value.innerHTML = Math.round(data.page_load_time / 1000 * 100) / 100;
            }

            if( el.gtmetrix.load_time.unit ){
                el.gtmetrix.load_time.unit.innerHTML = 's';
            }

            // Page Size.

            if( el.gtmetrix.page_size.wrapper ){
                el.gtmetrix.page_size.value = el.gtmetrix.page_size.wrapper.querySelector('.size-val');
                el.gtmetrix.page_size.unit = el.gtmetrix.page_size.wrapper.querySelector('.size-unit');
            }

            var formated_page_size = format_bytes(data.page_bytes);

            if( el.gtmetrix.page_size.value ){
                el.gtmetrix.page_size.value.innerHTML = formated_page_size.val;
            }

            if( el.gtmetrix.page_size.unit ){
                el.gtmetrix.page_size.unit.innerHTML = formated_page_size.unit;
            }

            // Requests.

            if( el.gtmetrix.requests.wrapper ){
                el.gtmetrix.requests.value = el.gtmetrix.requests.wrapper.querySelector('.requests-val');
            }

            if( el.gtmetrix.requests.value ){
                el.gtmetrix.requests.value.innerHTML = data.page_elements;
            }

            // Page Speed.

            if( el.gtmetrix.page_speed.wrapper ){
                el.gtmetrix.page_speed.grade = el.gtmetrix.page_speed.wrapper.querySelector('.speed-grade');
                el.gtmetrix.page_speed.value = el.gtmetrix.page_speed.wrapper.querySelector('.speed-val');

                if (data.pagespeed_score < 60) {
                    Util.addClass( el.gtmetrix.page_speed.wrapper, 'campaignsio-admin-orange' );
                }
                else if (data.pagespeed_score >= 60 && data.pagespeed_score < 80) {
                    Util.addClass( el.gtmetrix.page_speed.wrapper, 'campaignsio-admin-yellow' );
                }
                else {
                    Util.addClass( el.gtmetrix.page_speed.wrapper, 'campaignsio-admin-green' );
                }
            }

            if( el.gtmetrix.page_speed.grade ){
                el.gtmetrix.page_speed.grade.innerHTML = getGrade( data.pagespeed_score );
            }

            if( el.gtmetrix.page_speed.value ){
                el.gtmetrix.page_speed.value.innerHTML = data.pagespeed_score + '%';
            }

            // Y Slow Score.

            if( el.gtmetrix.yslow.wrapper ){
                el.gtmetrix.yslow.grade = el.gtmetrix.yslow.wrapper.querySelector('.score-grade');
                el.gtmetrix.yslow.value = el.gtmetrix.yslow.wrapper.querySelector('.score-val');

                if (data.yslow_score < 60) {
                    Util.addClass( el.gtmetrix.yslow.wrapper, 'campaignsio-admin-orange' );
                }
                else if (data.yslow_score >= 60 && data.yslow_score < 80) {
                    Util.addClass( el.gtmetrix.yslow.wrapper, 'campaignsio-admin-yellow' );
                }
                else {
                    Util.addClass( el.gtmetrix.yslow.wrapper, 'campaignsio-admin-green' );
                }
            }

            if( el.gtmetrix.yslow.grade ){
                el.gtmetrix.yslow.grade.innerHTML = getGrade( data.yslow_score );
            }

            if( el.gtmetrix.yslow.value ){
                el.gtmetrix.yslow.value.innerHTML = data.yslow_score + '%';
            }
        };

        jQuery.post( elems.base_url.value + 'auth/gtmetrix', {
            gtmetrix: elems.domain_url.value,
            [elems.csrf_token.value]: elems.csrf_hash.value,
        }, function(data, status) {
            if( data.status ){
                load_data( $.parseJSON( data.metrix ), data.date );
            }
            else{
                console.error("Error on GTMetrix request");
            }
        },'json');
    }

    function init_google_search_console(){

    }

    function init_website_traffic(){

        var load_chart = function(data){
            var i, display = [];
            for(i in data){
                if( data.hasOwnProperty( i ) ){
                    display.push({
                        x: new Date( i ),
                        y: parseInt(data[i], 10)
                    });
                }
            }
            websiteTrafficChart( display );
        };

        var dataElem = document.querySelector('input[name="id_website_traffic_data"]');
        var dataElemValue = dataElem ? JSON.parse( dataElem.value ) : [];

        if( dataElemValue && Object.keys( dataElemValue ).length ){
            load_chart( dataElemValue );
            dataElem.parentNode.removeChild( dataElem );
            return;
        }

        jQuery.ajax({
            type: "post",
            url: elems.base_url.value + 'analytics/analytics/getGoogleAnalytics',
            dataType: 'json',
            data: {
                id: elems.domain_id.value,
                [elems.csrf_token.value]: elems.csrf_hash.value,
                days:15,
            },
            success: function (data, status) {
                if ( 'success' === data.type ) { load_chart( data.payload ); }
            },
            error: function (data) {
                // console.log( data );
            },
        });
    }

    function init_domain_uptime_performance(){
        
        var loadChart = function(data, ready_load_time){
            
            var i, chartData = [];

            if( 'undefined' !== typeof data.uptimedaystats && data.uptimedaystats.length ){

                for(i=0;i<data.uptimedaystats.length;i++){
                    chartData[i] = {
                        x: new Date( data.uptimedaystats[i].completed_on ),
                        y: ready_load_time ? data.uptimedaystats[i].load_time : Math.round( data.uptimedaystats[i].load_time + 0)
                    };
                }

                responseTimeAndUptimeChart( chartData );
            }

            uptimeChart([
                Math.round( data.uptime1daypercentage + 0 ),
                Math.round( data.uptime7daypercentage + 0 ),
                Math.round( data.uptime30daypercentage + 0 )
            ]);
        };

        var uptimeDataElem = document.querySelector('input[name="id_uptime_data"]');
        var uptimeDataElemValue = uptimeDataElem ? JSON.parse( uptimeDataElem.value ) : [];

        var uptimePerformanceDataElem = document.querySelector('input[name="id_uptime_performance_data"]');
        var uptimePerformanceDataElemValue = uptimePerformanceDataElem ? JSON.parse( uptimePerformanceDataElem.value ) : [];

        if( ( uptimeDataElemValue && Object.keys( uptimeDataElemValue ).length ) || uptimePerformanceDataElemValue && Object.keys( uptimePerformanceDataElemValue ).length ){

            loadChart({
                uptimedaystats: uptimePerformanceDataElemValue,
                uptime1daypercentage: uptimeDataElemValue['one_day'],
                uptime7daypercentage: uptimeDataElemValue['seven_days'],
                uptime30daypercentage: uptimeDataElemValue['thirty_days'],
            }, true);
            
            if( uptimeDataElem ){ uptimeDataElem.parentNode.removeChild( uptimeDataElem ); }

            if( uptimePerformanceDataElem ){ uptimePerformanceDataElem.parentNode.removeChild( uptimePerformanceDataElem ); }

            return;
        }

        jQuery.post( elems.base_url.value + 'auth/uptimestats', {
            domain_id: elems.domain_id.value,
        }, function(data) {
            loadChart( data );
        }, 'json');
    }

    function init_domain_analytics_page(){

        var total_visits_data_elem = document.querySelector('input[name="id_total_visits_data"]'),
            unique_visits_data_elem = document.querySelector('input[name="id_unique_visits_data"]'),
            page_per_visit_data_elem = document.querySelector('input[name="id_page_per_visit_data"]'),
            referrer_visits_data_elem = document.querySelector('input[name="id_referrer_visits_data"]'),
            referrer_visits_data_graph_elem = document.querySelector('input[name="id_referrer_visits_graph_data"]'),
            total_visits_val, total_visits_array = [],
            unique_visits_val, unique_visits_array = [],
            page_per_visit_val, page_per_visit_array = [],
            referrer_visits_val, referrer_visits_data_arr = [], referrer_visits_labels_arr = [],
            referrer_visits_graph_val, referrer_visits_graph_arr = [], referrer_visits_graph_labels = [], referralDataPack = [], organicDataPack = [], directDataPack = [],
            k;

        if( total_visits_data_elem ){

            total_visits_val = JSON.parse(total_visits_data_elem.value);

            for(k in total_visits_val){
                if( total_visits_val.hasOwnProperty(k) ){
                    total_visits_array.push( {x: new Date(k), y: total_visits_val[k] } );
                }
            }

            analyticsVisitsChart( total_visits_array, "totalVisitsChart" );
        }

        if( unique_visits_data_elem ){

            unique_visits_val = JSON.parse(unique_visits_data_elem.value);

            for(k in unique_visits_val){
                if( unique_visits_val.hasOwnProperty(k) ){
                    unique_visits_array.push( {x: new Date(k), y: unique_visits_val[k] } );
                }
            }

            analyticsVisitsChart( unique_visits_array, "uniqueVisitsChart" );
        }

        if( page_per_visit_data_elem ){

            page_per_visit_val = JSON.parse(page_per_visit_data_elem.value);

            for(k in page_per_visit_val){
                if( page_per_visit_val.hasOwnProperty(k) ){
                    page_per_visit_array.push( {x: new Date(k), y: page_per_visit_val[k] } );
                }
            }

            analyticsVisitsChart( page_per_visit_array, "pagePerVisitChart" );
        }

        if( referrer_visits_data_elem ){
            referrer_visits_val = JSON.parse(referrer_visits_data_elem.value);
            for( k in referrer_visits_val ){
                if( referrer_visits_val.hasOwnProperty(k) ){
                    referrer_visits_labels_arr.push( referrer_visits_val[k].x );
                    referrer_visits_data_arr.push( referrer_visits_val[k].y );
                }
            }
            referrerVisitsChart( referrer_visits_data_arr, referrer_visits_labels_arr, "visitsChart" );
        }

        if( referrer_visits_data_graph_elem ){
            referrer_visits_graph_val = JSON.parse(referrer_visits_data_graph_elem.value);
            for(k in referrer_visits_graph_val){
                if( referrer_visits_graph_val.hasOwnProperty(k) ){
                    referrer_visits_graph_labels.push( k );
                    directDataPack.push( referrer_visits_graph_val[k][0]['nb_visits'] );
                    organicDataPack.push( referrer_visits_graph_val[k][1]['nb_visits'] );
                    referralDataPack.push( referrer_visits_graph_val[k][2]['nb_visits'] );
                }
            }

            referrer_visits_graph_arr = [
                {
                    label: 'direct',
                    data: directDataPack,
                    backgroundColor: "rgba(26,198,91,0.9)",
                    borderColor: "rgba(26,198,91,1)",
                    borderWidth: 1,
                },
                {
                    label: 'organic',
                    data: organicDataPack,
                    backgroundColor: "rgba(26,198,180,0.9)",
                    borderColor: "rgba(26,198,180,1)",
                    borderWidth: 1,
                },
                {
                    label: 'referral',
                    data: referralDataPack,
                    backgroundColor: "rgba(180,198,26,0.9)",
                    borderColor: "rgba(180,198,26,1)",
                    borderWidth: 1,
                },
            ];

            referrerVisitsGraphChart( referrer_visits_graph_arr, referrer_visits_graph_labels, 'twelveMonthsChart');
        }
    }

    function init_business_overview_page(){
        var k, tmp_obj, prev_12_months_vs_now_data, near_upcoming_invoices_data, near_upcoming_invoices_dataset, near_upcoming_invoices_dataset_labels, near_upcoming_invoices_chart,
            prev_12_months_vs_now_data_elem = document.querySelector('input[name="prev_12_months_vs_now_data"]'),
            near_upcoming_invoices_data_elem = document.querySelector('input[name="near_upcoming_invoices_data"]');
        
        if( prev_12_months_vs_now_data_elem ){
            prev_12_months_vs_now_data = JSON.parse( prev_12_months_vs_now_data_elem.value );
            if( prev_12_months_vs_now_data ){
                Morris.Area({
                    element: 'prev_12_months_vs_now_chart',
                    data: prev_12_months_vs_now_data ,
                    xkey: 'period',
                    ykeys: ['iphone', 'ipad', 'itouch'],
                    labels: ['iphone', 'ipad', 'itouch'],
                    pointSize: 0,
                    lineWidth:0,
                    fillOpacity: 0.6,
                    pointStrokeColors:['#2ecd99', '#4e9de6', '#f0c541'],
                    behaveLikeLine: true,
                    grid: false,
                    hideHover: 'auto',
                    lineColors: ['#2ecd99', '#4e9de6', '#f0c541'],
                    resize: true,
                    redraw: true,
                    smooth: true,
                    gridTextColor:'#878787',
                    gridTextFamily:"Poppins",
                    parseTime: false,
                });
            }

            prev_12_months_vs_now_data_elem.parentNode.removeChild(prev_12_months_vs_now_data_elem);
        }

        if( near_upcoming_invoices_data_elem ){
            near_upcoming_invoices_data = JSON.parse( near_upcoming_invoices_data_elem.value );
            near_upcoming_invoices_dataset = [];
            near_upcoming_invoices_dataset_labels = null;
            for(k in near_upcoming_invoices_data){
                if( near_upcoming_invoices_data.hasOwnProperty(k) ){
                    
                    // console.log( k, near_upcoming_invoices_data[k] );

                    if( null === near_upcoming_invoices_dataset_labels ){
                        near_upcoming_invoices_dataset_labels = Object.keys( near_upcoming_invoices_data[k].data );
                    }

                    tmp_obj = {
                        label: near_upcoming_invoices_data[k].label,
                        data: Object.values( near_upcoming_invoices_data[k].data ),
                    };

                    switch( parseInt(k, 10) ){
                        case 0:
                            tmp_obj.backgroundColor = 'rgba(240,197,65,.6)';
                            tmp_obj.borderColor = 'rgba(240,197,65,.6)';
                            break;
                        case 1:
                            tmp_obj.backgroundColor = 'rgba(46,205,153,.6)';
                            tmp_obj.borderColor = 'rgba(46,205,153,.6)';
                            break;
                        case 2:
                            tmp_obj.backgroundColor = 'rgba(78,157,230,.6)';
                            tmp_obj.borderColor = 'rgba(78,157,230,.6)';
                            break;
                    }

                    near_upcoming_invoices_dataset.push( tmp_obj );
                }
            }

            near_upcoming_invoices_chart = document.getElementById("near_upcoming_invoices_chart");

            if( near_upcoming_invoices_chart ){
                new Chart( near_upcoming_invoices_chart.getContext("2d"), {
                    type:"bar",
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July"],
                        datasets: near_upcoming_invoices_dataset,
                    },
                    options: {
                        tooltips: {
                            mode:"label"
                        },
                        scales: {
                            yAxes: [
                                {
                                    stacked: true,
                                    gridLines: {
                                        color: "rgba(135,135,135,0)",
                                    },
                                    ticks: {
                                        fontFamily: "Poppins",
                                        fontColor:"#878787"
                                    }
                                }
                            ],
                            xAxes: [
                                {
                                    stacked: true,
                                    gridLines: {
                                        color: "rgba(135,135,135,0)",
                                    },
                                    ticks: {
                                        fontFamily: "Poppins",
                                        fontColor:"#878787"
                                    }
                                }
                            ],
                        },
                        elements:{
                            point: {
                                hitRadius:40
                            }
                        },
                        animation: {
                            duration: 3000
                        },
                        responsive: true,
                        maintainAspectRatio:false,
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor:'rgba(33,33,33,1)',
                            cornerRadius:0,
                            footerFontFamily:"'Poppins'"
                        }
                    }
                });
            }
        }
    }

    function init(){

        elems.is_dashboard_page = document.querySelector('input[name="is_dashboard_page"]');
        elems.is_domain_analytics_page = document.querySelector('input[name="is_domain_analytics_page"]');
        elems.is_business_overview_page = document.querySelector('input[name="is_business_overview_page"]');
        
        if( elems.is_dashboard_page ){
            
            elems.base_url = document.querySelector('input[name="base_url"]');
            elems.domain_id = document.querySelector('input[name="domain_id"]');
            elems.domain_url = document.querySelector('input[name="domain_url"]');
            elems.csrf_token = document.querySelector('input[name="csrf_token"]');
            elems.csrf_hash = document.querySelector('input[name="csrf_hash"]');

            // init_gtmetrix(); // @note: Doesn't need anymore.
            init_gtmetrix_only_browser();

            init_website_traffic();
            init_google_search_console();
            init_domain_uptime_performance();
            // init_seo_data_and_keyword_position();   // @note: Doesn't need anymore.
        }
        else if( elems.is_domain_analytics_page ){
            init_domain_analytics_page();
        }
        else if( elems.is_business_overview_page ){
            init_business_overview_page();
        }
    }

    return {
        init: init,
    }
}());

var MyCampaignsIo = (function(){

    var elems = {
        app_wrapper: null,
        header: {
            search: {
                form: null,
                input: null,
                submit: null,
                close: null,
            },
        },
        sidebar: {
            collapse_expand: null,
            author_thumb_wrap: null,
        },
        pages: {
            edit_site: {
                tabs: null,
                tabs_content_wrapper: null,
                tabs_buttons_wrapper: null,
                prev_button: null,
                next_button: null,
            }
        },
        tab_content_rows: null,
        select_domain: null
    };

    var states = {
        pages: {
            edit_site: {
                active_tab: 1,
            },
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

        CampaignsIoPages.init();
        
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
        var i, j, row_tab_items;
        elems.tab_content_rows = elems.app_wrapper.querySelectorAll('.content-row.tab-contents-row');

        if( elems.tab_content_rows ){

            for(i=0; i<elems.tab_content_rows.length; i++){
                
                row_tab_items = elems.tab_content_rows[i].querySelectorAll('.content-tab-items ul li');

                if( row_tab_items ){
                    for(j=0; j<row_tab_items.length; j++){
                        row_tab_items[j].addEventListener('click', on_click_content_row_tab);
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
        var dtable, rowsNumSelector;
        var selector = ".filter-table";
        var tbls = document.querySelectorAll(".filter-table");
        if( tbls.length ){
            dtable = new DataTable( ".filter-table", {
                perPage: getCookie('campaigns-io[table-rows-per-page]') ? intval(getCookie('campaigns-io[table-rows-per-page]')) : 5,
                prevText: "Prev",
                nextText: "Next",
                fixedColumns: false,
                labels: {
                    placeholder: "Search...", // The search input placeholder
                    perPage: "{select}", // per-page dropdown label
                    noRows: "No entries found", // Message shown when there are no search results
                    info: "Showing {start} to {end} of {rows} entries"
                },
            });

            if( dtable && dtable.wrapper ){
                dtable.on( 'datatable.perpage', function(val){
                    setCookie('campaigns-io[table-rows-per-page]', val, 7);
                });
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

    function on_click_content_row_tab(ev){
        
        ev.preventDefault();
        ev.stopPropagation();

        var i, prev_active_tab, prev_active_tab_num, wrapper_row, prev_active_elem, new_active_elem;

        if( ! Util.hasClass( this, 'active' ) ){

            prev_active_tab = this.parentNode.querySelector('li.active');
            prev_active_tab_num = 0;
            
            new_active_tab_num = intval( this.getAttribute('data-tab-item') );

            if( prev_active_tab ){
                prev_active_tab_num = intval( prev_active_tab.getAttribute('data-tab-item') );
                Util.removeClass( prev_active_tab, 'active' );
            }

            wrapper_row = this.parentNode;
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

            Util.addClass( this, 'active' );
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
        run: init,
    };
}());

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
            url: siteBaseURL + 'auth/site/update_now/' + domain_id,
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

        if( updateButtons ){

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
    
    var choicesElem_1 = document.querySelector('select[name="subusers"].multi-select-box');
    var choicesElem_2 = document.querySelector('select[name="groups"].multi-select-box');
    var choicesElem_3 = document.querySelector('select[name="engines"].multi-select-box');

    if( choicesElem_1 ){
        new Choices(choicesElem_1,{
            maxItemCount: 3,
            removeItemButton: true,
        });
    }

    if( choicesElem_2 ){
        new Choices(choicesElem_2,{
            maxItemCount: 3,
            position: 'top',
            removeItemButton: true,
        });
    }

    if( choicesElem_3 ){
        new Choices(choicesElem_3,{
            maxItemCount: 3,
            removeItemButton: true,
        });
    }

    var submitProjectFormBtn = document.querySelector('.submit-edit-project-btn');
    if( submitProjectFormBtn ){
        submitProjectFormBtn.addEventListener('click', on_click_edit_project_form_submit );
    }

    var domain_connect_to_google_switch = document.querySelector('input[name="connect_to_google"]');
    
    if( domain_connect_to_google_switch ){
        
        var selectGaField = document.querySelector('.choose-ga-account-field');
        
        if( selectGaField ){
            selectGaField.style.display = domain_connect_to_google_switch.checked ? '' : 'none';
        }

        domain_connect_to_google_switch.onchange = function(e){
            var selGaField = document.querySelector('.choose-ga-account-field');
            if( selGaField ){
                selGaField.style.display = this.checked ? '' : 'none';
            }
        };

        new Choices( document.querySelector('select[name="gaAccounts"]') );
        new Choices( document.querySelector('select[name="frequency"]') );

        var addNewGaccountBtn = document.querySelector('.add-new-google-account');
        if( addNewGaccountBtn ){

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

            addNewGaccountBtn.addEventListener('click', function(ev){
                ev.preventDefault();
                ev.stopPropagation();

                var win = window.open(_url, "campaigns_io_add_ga_account", 'width=800, height=600');

                // var pollTimer = window.setInterval( function() {

                //     console.log( win.document );
                    
                    // if ( "undefined" !== typeof win.document ) {

                    //     console.log( 'win.document.URL:', win.document.URL, REDIRECT );

                    //     if ( -1 !== win.document.URL.indexOf( REDIRECT ) ) {
                    //         window.clearInterval( ollTimer );
                    //         // connectToGoogle = 1;
                    //         win.close();

                    //     }
                    // }
                
                // }, 1000);

            });
        }
    }

    WordPressUpdates.init();
}());

function isBrowser_inMobile(){
    return ( /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4)) );
}
function isBrowser_Chrome(){ return /chrome/.test(navigator.userAgent.toLowerCase()); }
function isBrowser_Safari(){ return false; /* @note: Return always false. */ }
function isBrowser_IE(){ return jQuery.browser.ie; }
function isBrowser_Opera(){ return jQuery.browser.opera; }
function isBrowser_Mozilla(){ return jQuery.browser.mozilla; }

function format_bytes(bytes, unitInSmallTag) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    var ret = {
        val: null,
        unit: null
    };
    if (bytes == 0){
        ret = {
            val: 0,
            unit: 'Byte'
        };
    }
    else{
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        ret = {
            val: Math.round(bytes / Math.pow(1024, i), 2),
            unit: sizes[i]
        };
    }    
    return ret;
}

function getGrade(score) {
    var grade1 = '';
    if (score > 89) {
        grade1 = 'A';
    } else if (score > 79 && score <= 89) {
        grade1 = 'B';
    } else if (score > 69 && score <= 79) {
        grade1 = 'C';
    } else if (score > 59 && score <= 69) {
        grade1 = 'D';
    } else if (score > 49 && score <= 59) {
        grade1 = 'E';
    } else if (score > 39 && score <= 49) {
        grade1 = 'F';
    } else if (score > 29 && score <= 39) {
        grade1 = 'G';
    } else if (score > 19 && score <= 29) {
        grade1 = 'H';
    } else if (score > 9 && score <= 19) {
        grade1 = 'I';
    } else {
        grade1 = 'J';
    }
    return grade1;
}

function websiteTrafficChart(chartData){
    chartData = 'undefined' === chartData || ! chartData || ! jQuery.isArray( chartData ) || ! chartData.length ? [] : chartData;
    var i;
    var chart = {
        elem: document.getElementById("webTrafficChart"),
        data: {
            datasets: [{
                label: "Visits",
                data: chartData,
                backgroundColor: 'rgba(26,198,180,0.5)',
                borderColor: 'rgba(26,198,180,1)',
                borderWidth: 2,
                fill: 0
            }],
        },
        options:{
            responsiveAnimationDuration: 0, // Disable animation after resize.
            legend: {
                display: false,
                labels: {
                    fontColor: '#a1b4c4'
                }
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: "day",
                        displayFormats: {
                            "day": 'YYYY-MM-DD'
                        },
                        tooltipFormat: 'YYYY-MM-DD'
                    },
                    scaleLabel: {
                        display: false,
                        labelString: "Day",
                        fontColor: '#a1b4c4',
                    },
                    gridLines: {
                        color: 'rgba(255,255,255,0.17)',
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 8,
                        fontColor: '#a1b4c4',
                        maxRotation: 0,
                        minRotation: 0
                    },
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: "Visits",
                        fontColor: '#a1b4c4',
                    },
                    gridLines: {
                        color: 'rgba(255,255,255,0.17)',
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 8,
                        fontColor: '#a1b4c4'
                    },
                }],
            },
        },
    };

    if( chart.elem ){
        new Chart(chart.elem, {
            type: 'line',
            data: chart.data,
            options: chart.options,
        });
    }
}

function referrerVisitsChart(chartData, chartLabel, chartElemId){
    chartData = 'undefined' === chartData || ! chartData || ! jQuery.isArray( chartData ) || ! chartData.length ? [] : chartData;
    var chart = {
        elem: document.getElementById(chartElemId),
        data: {
            datasets: [{
                // label: "ms",
                // xValueFormatString: "Year ####",
                data: chartData,
                // backgroundColor: 'rgba(26,198,180,0.5)',
                // borderColor: 'rgba(26,198,180,1)',
                // borderWidth: 1,
                // fill: 0
                backgroundColor: [ 'rgba(26,198,91,0.9)', 'rgba(180,198,26,0.9)', 'rgba(26,198,180,0.9)' ],
                // borderColor: [ 'rgba(26,198,91,1)', 'rgba(180,198,26,1)', 'rgba(26,198,180,1)' ],
                borderColor: [ 'rgba(0,0,0,0.1)', 'rgba(0,0,0,0.1)', 'rgba(0,0,0,0.1)' ],
            }],
            labels: chartLabel,

        },
        options: {
            responsiveAnimationDuration: 0, // Disable animation after resize.
            legend: {
                display:true,
                position: 'bottom',
                fullWidth: false,
                labels: {
                    fontColor: '#a1b4c4'
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItems, data) {
                        var indice = tooltipItems.index;                 
                        return data.labels[indice] +': '+data.datasets[0].data[indice] + '%';
                    }
                },
            },
            /*scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: "day",
                        displayFormats: {
                            "day": 'MMM D, YY'
                        },
                        tooltipFormat: 'MMM D, YY'
                    },
                    scaleLabel: {
                        display: false,
                        // labelString: "Day time",
                        fontColor: '#a1b4c4',
                    },
                    gridLines: {
                        color: 'rgba(255,255,255,0.17)',
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 2,
                        fontColor: '#a1b4c4',
                        maxRotation: 0,
                        minRotation: 0
                    },
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        // labelString: "Response time (ms)",
                        fontColor: '#a1b4c4',
                    },
                    gridLines: {
                        color: 'rgba(255,255,255,0.17)',
                    },
                    ticks: {
                        beginAtZero: true,
                        autoSkip: true,
                        maxTicksLimit: 5,
                        fontColor: '#a1b4c4'
                    },
                }],
            }*/
        },
    };

    if( chart.elem ){
        new Chart(chart.elem, {
            type: 'pie',
            data: chart.data,
            options: chart.options,
        });
    }
}

function analyticsVisitsChart(chartData, chartElemId){
    chartData = 'undefined' === chartData || ! chartData || ! jQuery.isArray( chartData ) || ! chartData.length ? [] : chartData;
    var chart = {
        elem: document.getElementById(chartElemId),
        data: {
            datasets: [{
                // label: "ms",
                // xValueFormatString: "Year ####",
                data: chartData,
                backgroundColor: 'rgba(26,198,180,0.5)',
                borderColor: 'rgba(26,198,180,1)',
                borderWidth: 1,
                // fill: 0
            }],
        },
        options: {
            responsiveAnimationDuration: 0, // Disable animation after resize.
            legend: {
                display:false,
                labels: {
                    fontColor: '#a1b4c4'
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItems, data) { return ' ' + tooltipItems.yLabel + ' visits'; }
                },
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: "day",
                        displayFormats: {
                            "day": 'MMM D, YY'
                        },
                        tooltipFormat: 'MMM D, YY'
                    },
                    scaleLabel: {
                        display: false,
                        // labelString: "Day time",
                        fontColor: '#a1b4c4',
                    },
                    gridLines: {
                        // color: 'rgba(255,255,255,0.17)',
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 2,
                        fontColor: '#a1b4c4',
                        maxRotation: 0,
                        minRotation: 0
                    },
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        // labelString: "Response time (ms)",
                        fontColor: '#a1b4c4',
                    },
                    gridLines: {
                        // color: 'rgba(255,255,255,0.17)',
                    },
                    ticks: {
                        beginAtZero: true,
                        autoSkip: true,
                        maxTicksLimit: 5,
                        fontColor: '#a1b4c4'
                    },
                }],
            }
        },
    };

    if( chart.elem ){
        new Chart(chart.elem, {
            type: 'line',
            data: chart.data,
            options: chart.options,
        });
    }
}

function referrerVisitsGraphChart(chartData, chartLabel, chartElemId){
    chartData = 'undefined' === chartData || ! chartData || ! jQuery.isArray( chartData ) || ! chartData.length ? [] : chartData;
    var chart = {
        elem: document.getElementById(chartElemId),
        data: {
            labels: chartLabel,
            datasets: chartData,
        },
        options: {
            responsiveAnimationDuration: 0, // Disable animation after resize.
            legend: {
                display:true,
                labels: {
                    fontColor: '#a1b4c4'
                }
            },
            animation: {
                duration: 10,
            },
            tooltips: {
                mode: 'label',
                // mode: 'index',
                callbacks: {
                    label: function(tooltipItem, data) {
                        return [data.datasets[tooltipItem.datasetIndex].label + ": " + tooltipItem.yLabel];
                    }
                }
            },
            scales: {
                xAxes: [{ 
                    stacked: true,
                    // type: 'time',
                    // time: {
                    //     unit: "day",
                    //     displayFormats: {
                    //         "day": 'MMM D, YY'
                    //     },
                    //     tooltipFormat: 'MMM D, YY'
                    // },
                    /*scaleLabel: {
                        display: false,
                        fontColor: '#a1b4c4',
                    },*/
                    gridLines: {
                        // color: 'rgba(255,255,255,0.17)',
                    },
                    ticks: {
                        // autoSkip: true,
                        // maxTicksLimit: 8,
                        fontColor: '#a1b4c4',
                        // maxRotation: 0,
                        // minRotation: 0
                    },
                }],
                yAxes: [{
                    stacked: true,
                    ticks: {
                        // callback: function(value) { return numberWithCommas(value); },
                        fontColor: '#a1b4c4'
                    },
                }],
            },
        },
    };

    if( chart.elem ){
        new Chart(chart.elem, {
            type: 'bar',
            data: chart.data,
            options: chart.options,
        });
    }
}

function responseTimeAndUptimeChart(chartData){
    chartData = 'undefined' === chartData || ! chartData || ! jQuery.isArray( chartData ) || ! chartData.length ? [] : chartData;
    var chart = {
        elem: document.getElementById("responseAndUpTimeChart"),
        data: {
            datasets: [{
                label: "ms",
                xValueFormatString: "Year ####",
                data: chartData,
                backgroundColor: 'rgba(26,198,180,0.5)',
                borderColor: 'rgba(26,198,180,1)',
                borderWidth: 2,
                // fill: 0
            }],
        },
        options: {
            responsiveAnimationDuration: 0, // Disable animation after resize.
            legend: {
                display:false,
                labels: {
                    fontColor: '#a1b4c4'
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItems, data) { return ' ' + tooltipItems.yLabel + ' ms'; }
                },
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: "hour",
                        displayFormats: {
                            "hour": 'HH:mm'
                        },
                        tooltipFormat: 'YY-MM-DD, HH:mm'
                    },
                    scaleLabel: {
                        display: false,
                        labelString: "Day time",
                        fontColor: '#a1b4c4',
                    },
                    gridLines: {
                        color: 'rgba(255,255,255,0.17)',
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 9,
                        fontColor: '#a1b4c4',
                        maxRotation: 0,
                        minRotation: 0
                    },
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: "Response time (ms)",
                        fontColor: '#a1b4c4',
                    },
                    gridLines: {
                        color: 'rgba(255,255,255,0.17)',
                    },
                    ticks: {
                        beginAtZero: true,
                        // autoSkip: true,
                        // maxTicksLimit: 7,
                        fontColor: '#a1b4c4'
                    },
                }],
            }
        },
    };

    if( chart.elem ){
        new Chart(chart.elem, {
            type: 'line',
            data: chart.data,
            options: chart.options,
        });
    }
}

function uptimeChart(chartData){
    chartData = 'undefined' === chartData || ! chartData || ! jQuery.isArray( chartData ) || ! chartData.length ? [] : chartData;
    var chart = {
        elem: document.getElementById("uptimeChart"),
        data: {
            labels: ["24 hours", "7 days", "30 days"],
            datasets: [{
                data: chartData,
                backgroundColor: ['rgba(26,198,91, 0.5)', 'rgba(180,198,26, 0.5)', 'rgba(255,96,0, 0.5)'],
                borderColor: ['rgba(26,198,91, 1)', 'rgba(180,198,26, 1)', 'rgba(255,96,0, 1)'],
                borderWidth: 2,
                fill: 0
            }],
        },
        options:{
            responsiveAnimationDuration: 0, // Disable animation after resize.
            legend: {
                display:false,
                labels: {
                    fontColor: '#a1b4c4'
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItems, data) { return ' ' + tooltipItems.yLabel + '%'; }
                },
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        color: 'rgba(255,255,255,0.17)',
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 3,
                        fontColor: '#a1b4c4',
                        maxRotation: 0,
                        minRotation: 0
                    },
                }],
                yAxes: [{
                    scaleLabel: {
                        display: false,
                        labelString: "%",
                        fontColor: '#a1b4c4',
                    },
                    gridLines: {
                        color: 'rgba(255,255,255,0.17)',
                    },
                    ticks: {
                        fontColor: '#a1b4c4',
                        beginAtZero: true,
                        maxTicksLimit: 10,
                        suggestedMin: 0,
                        suggestedMax: 100,
                    },
                }],
            }
        },
    };

    if( chart.elem ){
        new Chart(chart.elem, {
            type: 'bar',
            data: chart.data,
            options: chart.options,
        });
    }
}

function openPiwikCode(url){
    var win = window.open(url, "", "width=600,height=400");
}

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

function get_multiselect_values( select, serializeReturn ){
    var ret = [];
    var opt, options;
    if( 'undefined' !== select ){
        options = select && select.options;
        for (var i=0, len=options.length; i<len; i++) {
            opt = options[i];
            if (opt.selected) {
                ret.push(opt.value || opt.text);
            }
        }
    }
    if( 'undefined' !== typeof serializeReturn && serializeReturn ){
        ret = ret.length ? ret.join(', ') : '';
    }
    return ret;
}

var clicked_edit_project_submit = false;

function on_click_edit_project_form_submit(ev){

    if( ! clicked_edit_project_submit ){
        
        clicked_edit_project_submit = true;

        var elems = {
            base_url: document.querySelector('input[name="base_url"]'),
            save_url: document.querySelector('input[name="save_url"]'),
            csrf_token: document.querySelector('input[name="csrf_token"]'),
            csrf_hash: document.querySelector('input[name="csrf_hash"]'),
            domain_id: document.querySelector('input[name="domain_id"]'),
            admin_url: document.querySelector('input[name="adminURL"]'),
            admin_username: document.querySelector('input[name="adminUsername"]'),
            domain_name: document.querySelector('input[name="domain_name"]'),
            ga_accounts: document.querySelector('select[name="gaAccounts"]'),
            frequency: document.querySelector('select[name="frequency"]'),
            connect_to_google: document.querySelector('input[name="connect_to_google"]'),
            monitor_page_issues: false,
            local_search: false,
            town: false,
            monitor_malware: document.querySelector('input[name="monitor_malware"]'),
            monitor_website_uptime: document.querySelector('input[name="monitor_website_uptime"]'),
            crawl_error_webmaster: document.querySelector('input[name="crawl_error_webmaster"]'),
            page_header: document.querySelector('input[name="page_header"]'),
            page_body: document.querySelector('input[name="page_body"]'),
            page_footer: document.querySelector('input[name="page_footer"]'),
            search_query_webmaster: document.querySelector('input[name="search_query_webmaster"]'),
            include_mobile_search: document.querySelector('input[name="include_mobile_search"]'),
            is_ecommerce: document.querySelector('input[name="is_ecommerce"]'),
            engines: document.querySelector('select[name="engines"]'), 
            keywords: document.querySelector('textarea[name="keywords"]'),
            subusers: document.querySelector('select[name="subusers"]'),
            groups: document.querySelector('select[name="groups"]'),
        };

        var saveData = {
            domainId: elems.domain_id ? parseInt( elems.domain_id.value, 10 ) : 0,
            [elems.csrf_token.value]: elems.csrf_hash.value,
            connectToGoogle: elems.connect_to_google && elems.connect_to_google.checked ? 1 : 0, 
            domain: elems.domain_name ? elems.domain_name.value.trim() : '',
            ga_account: elems.ga_accounts ? elems.ga_accounts.value : 0,
            monitor: elems.monitor_page_issues && elems.monitor_page_issues.checked ? 1 : 0,
            local_search: elems.local_search && elems.local_search.checked ? 1 : 0,
            town: elems.town ? get_multiselect_values( elems.town ) : [],
            monitorMalware: elems.monitor_malware && elems.monitor_malware.checked ? 1 : 0, 
            monitorUptime: elems.monitor_website_uptime && elems.monitor_website_uptime.checked ? 1 : 0, 
            adminURL: elems.admin_url ? elems.admin_url.value.trim() : '',
            adminUsername: elems.admin_username ? elems.admin_username.value.trim() : '',
            keywords: elems.keywords ? elems.keywords.value.trim() : '',
            mobile_search: elems.include_mobile_search && elems.include_mobile_search.checked ? 1 : 0,
            webmaster: elems.crawl_error_webmaster && elems.crawl_error_webmaster.checked ? 1 : 0,
            page_header: elems.page_header ? elems.page_header.value.trim() : '',
            page_body: elems.page_body ? elems.page_body.value.trim() : '',
            page_footer: elems.page_footer ? elems.page_footer.value.trim() : '',
            is_ecommerce: elems.is_ecommerce && elems.is_ecommerce.checked ? 1 : 0,
            search_analytics: elems.search_query_webmaster && elems.search_query_webmaster.checked ? 1 : 0,
            frequency: elems.frequency ? parseInt( elems.frequency.value, 10 ) : '',
            engines: elems.engines ? get_multiselect_values( elems.engines ) : [],
            subusers: elems.subusers ? get_multiselect_values( elems.subusers ) : [],
            groups: elems.groups ? get_multiselect_values( elems.groups ) : [],
        };

        var siteBaseURL = elems.base_url ? elems.base_url.value : '';
        var saveURL = elems.save_url ? elems.save_url.value : '';

        var ajaxData = {
            type: "post",
            url: saveURL,
            data: saveData,
        };

         jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: siteBaseURL + 'auth/editDomain',
            data: ajaxData,
            success: function (data) {
                // console.log("SUCCESS");
                // console.log(data);
                if( 1 === parseInt(data.error, 10) ){
                    console.error(data.msg);
                }
                else{
                    // window.location.href = window.location.href;
                    window.location.href = siteBaseURL +'auth/dashboard/' + ( typeof data.domainId !== 'undefined' ? data.domainId : '' );
                }
            },
            error: function (data) {
                // console.log("ERROR");
                console.log(data);
            },
        }).done(function (data) {
            // console.log("DONE");
            // console.log(data);

            clicked_edit_project_submit = false;
        });
    }
}
