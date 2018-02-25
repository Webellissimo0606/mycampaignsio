var DomainOverview = (function(){

	var elems = {
        base_url: null,
        domain_id: null,
        domain_url: null,
        csrf_token: null,
        csrf_hash: null
    };

    /* ------------------------------ // Charts ------------------------------ */

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

	/* ------------------------------ Charts // ------------------------------ */

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

        // @note: Doesn't need any more.
        /*jQuery.ajax({
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
        });*/
    }

    function init_google_search_console(){ }

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

            return;
        }

        jQuery.post( elems.base_url.value + 'auth/uptimestats', {
            domain_id: elems.domain_id.value,
        }, function(data) {
            loadChart( data );
        }, 'json');
    }
	
	function init_gtmetrix_only_browser(){  // And server region.

        var el = {
        	gtmetrix:{
        		browser: {},
        		serverRegionEl: null,
        		wrapper: document.querySelector('.gtmetrix_wrapper'),
        		rescan_url: document.querySelector('input[name="gtmetrix_rescan_url"]'),
        		rescan_btn: document.querySelector('.rescan-gtmetrix'),
        		loader_overlay: document.querySelector('.gtmetrix-loader-overlay')
        	}
        };

        var rescaning_now = false;

		var run_gtmetrix = function(){

			el.gtmetrix.browser = {
				wrapper: document.querySelector('.gtmetrix-browser-row'),
                thumb: null,
                desktop_mobile: null,
                title: null,
			};

			el.gtmetrix.serverRegionEl = document.querySelector('.server-region-val');

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
		};

		var show_gtmetrix_loader = function(){

		};

		var hide_gtmetrix_loader = function(){

		};

        // Rescan button.
        if( el.gtmetrix.rescan_btn ){

        	el.gtmetrix.rescan_btn.onclick = function(ev){

        		if( rescaning_now ) return;

        		rescaning_now = true;

        		Util.removeClass( el.gtmetrix.loader_overlay, 'hidden' );

				setTimeout( function(){
					if( rescaning_now ){
						setTimeout( function(){
							Util.removeClass( el.gtmetrix.loader_overlay, 'invisible' );
						}, 0 );
					}
				}, 500);

		        jQuery.ajax({
		            type: 'post',
		            dataType: 'json',
		            url: el.gtmetrix.rescan_url.value,
		            data: {},
		            success: function(data) {
		            	if( 'undefined' !== typeof data.html ){
		            		el.gtmetrix.wrapper.innerHTML = data.html;
		            		run_gtmetrix();
		            	}
		            },
		            error: function(data) {
		                console.error('ERROR', data);
		            },
		         }).done( function(data) {
		         	rescaning_now = false;
		         	Util.addClass( el.gtmetrix.loader_overlay, 'invisible' );
		        	setTimeout( function(){
			        	Util.addClass( el.gtmetrix.loader_overlay, 'hidden' );
					}, 500 );
		        });
        	};
	    }

	    run_gtmetrix();
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

    /* ------------------------------ // Older functions ------------------------------ */

    // @note: Doesn't need anymore.
	/*function init_seo_data_and_keyword_position(){
    
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

            // var TPL = $('#template_keyword_stats').html(),
            // keyword_average_position = Math.round(data.avg_position['average_position']);
            // if( TPL ){
            //   TPL = TPL.replace(/{keyword_average_position}/g, keyword_average_position);
              
            //   TPL = TPL.replace(/{keyword_top5}/g, data.position['top5']);
            //   TPL = TPL.replace(/{keyword_total_search}/g, data.total_keywords['total_keyword_search']);
            //   $('#panel_template_keyword_stats').append(TPL);
            // }
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
    }*/

    // @note: Doesn't need anymore.
    /*function init_gtmetrix(){

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
    }*/

    /* ------------------------------ Older functions // ------------------------------ */

	var init = function(){

		elems.base_url = document.querySelector('input[name="base_url"]');
        elems.domain_id = document.querySelector('input[name="domain_id"]');
        elems.domain_url = document.querySelector('input[name="domain_url"]');
        elems.csrf_token = document.querySelector('input[name="csrf_token"]');
        elems.csrf_hash = document.querySelector('input[name="csrf_hash"]');

        init_website_traffic();
        init_google_search_console();
        init_domain_uptime_performance();
		// init_seo_data_and_keyword_position();   // @note: Doesn't need anymore.
		// init_gtmetrix(); // @note: Doesn't need anymore.
		init_gtmetrix_only_browser();
    };

    return {
        init: init,
    }
}());

(function(){
    "use strict";

    // @note: Don't need any more.
    /*var submitProjectFormBtn = document.querySelector('.submit-edit-project-btn');
    if( submitProjectFormBtn ){ submitProjectFormBtn.addEventListener('click', on_click_edit_project_form_submit ); }*/

    DomainOverview.init();
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

// @note: Don't need any more.
/*var clicked_edit_project_submit = false;
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
} */