var DomainAnalytics = (function(){

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

	var init = function(){

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

    };

    return {
        init: init,
    }
}());

(function(){
    "use strict";
    DomainAnalytics.init();
}());