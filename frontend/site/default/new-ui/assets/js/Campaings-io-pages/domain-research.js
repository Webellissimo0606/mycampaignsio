function trendChart(chartData, chartElemId){
    
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
                fill: 1
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
            /*tooltips: {
                callbacks: {
                    label: function(tooltipItems, data) { return ' ' + tooltipItems.yLabel + ' visits'; }
                },
            },*/
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: "day",
                        displayFormats: {
                            // "day": 'MMM D, YY'
                            "day": 'Y-MM-DD'
                        },
                        // tooltipFormat: 'MMM D, YY'
                        tooltipFormat: 'Y-MM-DD'
                    },
                    scaleLabel: {
                        display: false
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 4,
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
                        // autoSkip: true,
                        // maxTicksLimit: 5,
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

function competitorsGraphChart(chartData, datasetLabel, chartElemId){
    chartData = 'undefined' === chartData || ! chartData || ! jQuery.isArray( chartData ) || ! chartData.length ? [] : chartData;
    var chart = {
        elem: document.getElementById(chartElemId),
        data: {
	        datasets: [{
	        	label: datasetLabel,
	        	data: chartData,
	        	backgroundColor: "rgba(26,198,91,0.3)",
	        	borderColor: "rgba(26,198,91,1)",
	        }]
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
                callbacks: {
                    label: function(tooltipItem, data) {
                    	return data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].domain_label + ' ( ' + tooltipItem.xLabel + ", " + tooltipItem.yLabel + ' )';
                    }
                }
            },
            scales: {
                xAxes: [{ 
                    scaleLabel: {
                        display: true,
                        labelString: "Keywords",
                        fontColor: '#a1b4c4',
                    },
                   ticks: {
                        fontColor: '#a1b4c4'
                    },
                }],
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: "Visibility",
                        fontColor: '#a1b4c4',
                    },
                    ticks: {
                        fontColor: '#a1b4c4'
                    },
                }],
            },
        },
    };

    if( chart.elem ){
        new Chart(chart.elem, {
            type: 'bubble',
            data: chart.data,
            options: chart.options
        });
    }
}

function keywordPositionDistributionGraphChart(chartData, chartLabels, datasetLabel, chartElemId){
    chartData = 'undefined' === chartData || ! chartData || ! jQuery.isArray( chartData ) || ! chartData.length ? [] : chartData;

    var chart = {
        elem: document.getElementById(chartElemId),
        data: {
        	labels: chartLabels,
	        datasets: [{
	        	label: datasetLabel,
	        	data: chartData,
	        	backgroundColor: "rgba(255,96,0,0.7)",
	        	// borderColor: "rgba(255,96,0,1)",
	        }]
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
                mode: 'index',
                callbacks: {
                    label: function(tooltipItem, data) {
                    	return tooltipItem.yLabel + " keyword" + ( 1 < tooltipItem.yLabel ? 's' : '' );
                    }
                }
            },
            scales: {
                xAxes: [{ 
                    // stacked: true,
                    scaleLabel: {
                        display: true,
                        labelString: "Position",
                        fontColor: '#a1b4c4',
                    },
                    /*gridLines: {
                        color: 'rgba(255,255,255,0.17)',
                    },*/
                    ticks: {
                        fontColor: '#a1b4c4'
                    },
                }],
                yAxes: [{
                    stacked: true,
                    scaleLabel: {
                        display: true,
                        labelString: "Keywords",
                        fontColor: '#a1b4c4',
                    },
                    ticks: {
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
            options: chart.options
        });
    }
}

var DomainResearch = (function(){

	var elems = {
		compare_btn : null,
		compare_url_input : null,
		compare_request_url : null,
		domains_comparison_inner: null,
		competitors_graph_data: null,
		keyword_position_distribution: null,
		trends_date_data: null,
		trends_visibility_data: null,
		trends_keywords_data: null
	};

	var comparing_domains = false;

	var init_competitors_chart = function( data ) {
		var k, data_array = [];
		if( Object.keys( data ).length ){
			for(k in data){
				if( data.hasOwnProperty(k) ){
					data_array.push({ domain_label: data[k].domain, x: data[k].x, y: data[k].y, r: 5 });
				}
			}
		}
		competitorsGraphChart( data_array, 'Keywords visibility per domain', 'competitors_graph_data' );
	};

	var init_keyword_position_distribution_chart = function( data ) {
		keywordPositionDistributionGraphChart( data, [ '1-1', '2-3', '4-5', '6-10', '11-20', '21-50', '51-100' ], 'Keywords position', 'keyword_position_distribution_graph' );
	};

	var init = function(){
		
		elems.compare_btn = document.querySelector('.domain-compare-btn');
		elems.compare_url_input = document.querySelector('input[name="domain_compare_input"]');
		elems.compare_request_url = document.querySelector('input[name="domain_compare_url"]');

		elems.competitors_graph_data = document.querySelector('input[name="competitors_graph_data"]');
		elems.keyword_position_distribution = document.querySelector('input[name="keyword_position_distribution"]');

		elems.trends_date_data = document.querySelector('input[name="trends_date_data"]');
		elems.trends_visibility_data = document.querySelector('input[name="trends_visibility_data"]');
		elems.trends_keywords_data = document.querySelector('input[name="trends_keywords_data"]');

		elems.domains_comparison_wrap = document.querySelector('.domains-comparison-wrap');
		elems.domains_comparison_inner = document.querySelector('.domains-comparison-inner');

		elems.compare_loader = document.querySelector('.domains-comparison-loader');

		if( elems.compare_btn && elems.compare_request_url && elems.compare_url_input ){
			elems.compare_btn.onclick = function(ev){
				
				if( comparing_domains ){ return; }

				if( '' === elems.compare_url_input.value.trim() ){
					alert("Competitor's domain URL is not valid.");
					return;
				}
				
				comparing_domains = true;

				Util.removeClass( elems.compare_loader, 'hidden' );

				setTimeout( function(){
					if( comparing_domains ){
						setTimeout( function(){
							Util.removeClass( elems.compare_loader, 'invisible' );
						}, 0 );
					}
				}, 100);

				jQuery.ajax({
		            type: 'post',
		            dataType: 'json',
		            url: elems.compare_request_url.value,
		            data: { url: elems.compare_url_input.value },
		            success: function (data) {
		                if( 'undefined' !== typeof data.html ){
		                	elems.domains_comparison_inner.innerHTML = data.html;
		                }
		                else{
		                	console.log(data);
		                }
		                comparing_domains = false;
		            },
		            error: function(data) {
		            	console.log(data);
		            	comparing_domains = false;
		            },
		        }).done(function(){
		        	Util.addClass( elems.compare_loader, 'invisible' );
		        	setTimeout( function(){
			        	Util.addClass( elems.compare_loader, 'hidden' );
					}, 500 );
		        });
			};
		}

		if( elems.competitors_graph_data ){
			init_competitors_chart( JSON.parse( elems.competitors_graph_data.value ) );
		}

		if( elems.keyword_position_distribution ){
			init_keyword_position_distribution_chart( JSON.parse( elems.keyword_position_distribution.value ) );
		}

		if( elems.trends_date_data ){

			var k, dates = JSON.parse( elems.trends_date_data.value );

			if( elems.trends_visibility_data ){
				var visibility_data = JSON.parse( elems.trends_visibility_data.value ),
					vis_graph_data = [];				
				for(k=0; k<visibility_data.length; k++){
					vis_graph_data.push({ x: new Date( dates[k] ), y: visibility_data[k] });
				}
			}

			if( elems.trends_keywords_data.value ){
				var keyboard_data = JSON.parse( elems.trends_keywords_data.value ),
					key_graph_data = [];
				for(k=0; k<keyboard_data.length; k++){
					key_graph_data.push({ x: new Date( dates[k] ), y: keyboard_data[k] });
				}
			}

			trendChart( vis_graph_data, "visibility_trend_chart" );
			trendChart( key_graph_data, "keywords_trend_chart" );
		}
    };

    return {
        init: init
    }
}());

(function(){
    "use strict";
    DomainResearch.init();
}());