var DomainSERPS = (function(){
	
	var elems = {
			report_loader: null,
			report_url: null,
			report_wrapper: null,
			search_engines: null,
			active_search_engine: null,
			keyword_overall_url: null,
			keyword_remove_url: null,
			graph_select_boxes: {
				keyword: null,
				time: null,
				engine: null,
			},
			graph_wrapper: null,
			add_keywords: null,
			serp_add_keywords_layer: null,
			close_add_keywords_layer: null,
			serp_keywords_save: null,
			serp_keyword_webmaster_add: null,
			serp_add_keywords_textarea: null
		},
		search_engines = {
			cache: {},
			active_engine_id: ''
		},
		graph = {
			cache: {},
			active:{
				keyword: "n-o-n-e",
				time: "n-o-n-e",
				engine: "n-o-n-e",
			}
		};

	var on_serp_report_update = function( engine_elem, active_engine_id ){

		var table = elems.report_wrapper.querySelector(".filter-table");

		if( elems.active_search_engine ){
			Util.removeClass( elems.active_search_engine, 'active' );
		}

		if( '' === active_engine_id ){
			elems.active_search_engine = null;
		}
		else{
			Util.addClass( engine_elem, 'active' );
			elems.active_search_engine = engine_elem;
		}

        if( table ){
			DataTableElemInit( table, table.getAttribute('data-rows-per-page'), table.getAttribute('data-table-id') );
        }
	};

	var on_select_search_engine = function(ev){

		ev.preventDefault();
		ev.stopPropagation();

		var active_engine = this,
			ajax_completed = false,
			this_engine_id = active_engine.getAttribute('data-engine-id');

		search_engines.active_engine_id = search_engines.active_engine_id !== this_engine_id ? this_engine_id : '';

		if( 'undefined' !== typeof search_engines.cache[ search_engines.active_engine_id ] ){
			elems.report_wrapper.innerHTML = search_engines.cache[ search_engines.active_engine_id ];
			on_serp_report_update( active_engine, search_engines.active_engine_id );
		}
		else{

			Util.removeClass( elems.report_loader, 'hidden' );

			setTimeout( function(){
				if( ! ajax_completed ){
					setTimeout( function(){
						Util.removeClass( elems.report_loader, 'invisible' );
					}, 0 );
				}
			}, 500);

			jQuery.ajax({
	            type: "post",
	            dataType: 'json',
	            url: elems.report_url.value,
	            data: { engine: search_engines.active_engine_id },
	            success: function (data) {
	            	if( 1 === parseInt( data.error, 10 ) ){
						console.log( data );
	                }
	                else{
	                	search_engines.cache[ search_engines.active_engine_id ] = data.html;
	                	elems.report_wrapper.innerHTML = data.html;
	                	on_serp_report_update( active_engine, search_engines.active_engine_id );
	                }
	            },
	            error: function (data) {
	                console.log( data );
	            }
	        }).done(function(){
	        	ajax_completed = true;
	        	Util.addClass( elems.report_loader, 'invisible' );
	        	setTimeout( function(){
		        	Util.addClass( elems.report_loader, 'hidden' );
				}, 500 );
	        });
	    }
	};

	var on_click_serp_keyword_webmaster_add = function(ev){

		ev.preventDefault();
		ev.stopPropagation();

		var i, 
			selected_keywords = [],
			checkboxes = document.querySelectorAll('input[name="add_webmaster_kwewords[]"]');

		if( checkboxes.length ){
			for(i=0; i<checkboxes.length; i++){
				if( checkboxes[i].checked ) {
					selected_keywords.push( checkboxes[i].value );
				}
			}
		}

		if( ! selected_keywords.length ){
			alert("Select keywords to add in Webmaster Tools");
			return;
		}
		else {
			if ( confirm('Are you sure you want to insert selected keywords into Webmaster Tool ?') ) {

				jQuery.ajax({
		            type: "post",
		            dataType: 'json',
		            url: elems.keyword_add_webmaster_tool_url.value,
		            data: { add_keyword: selected_keywords },
		            success: function (data) {
		            	if( 1 === parseInt( data.error, 10 ) ){
		            		console.error( data );
			            }
			            else{
			            	window.location.href = window.location.href;
			            }
		            },
		            error: function (data) {
		                console.error( data );
		            }
		        });
			}
		}
	};

	var on_update_edit_keywords_table = function(){

		var dtTable = document.querySelector('.edit-keywords-table');

		dtTable = DataTableElemInit( dtTable, dtTable.getAttribute('data-rows-per-page'), dtTable.getAttribute('data-table-id') );

		var init_remove_events = function( container ){
			var i, remove_buttons = container.querySelectorAll('.serp-keyword-remove');
			if( remove_buttons.length ){
				for(i=0; i<remove_buttons.length; i++){
					remove_buttons[i].addEventListener('click', on_click_keyword_remove );
				}
			}
		};

		dtTable.on("datatable.init", function() { init_remove_events(this.container); });
		dtTable.on('datatable.page', function(page) { init_remove_events(this.container); });
		dtTable.on('datatable.search', function(query, matched) { init_remove_events(this.container); });
		dtTable.on('datatable.sort', function(column, direction) { init_remove_events(this.container); });
	};

	var on_click_add_keywords = function(ev){
		ev.preventDefault();
		ev.stopPropagation();
		Util.removeClass( elems.serp_add_keywords_layer, 'hidden' );
	};

	var on_click_serp_keywords_save = function(ev){
		ev.preventDefault();
		ev.stopPropagation();
		jQuery.ajax({
            type: "post",
            dataType: 'json',
            url: elems.keyword_add_url.value,
            data: { keywords: elems.serp_add_keywords_textarea.value.trim().match(/[^\r\n]+/g) },
            success: function (data) {
            	if( 1 === parseInt( data.error, 10 ) ){
            		console.error( data );	            	
	            }
	            else{
	            	elems.serp_add_keywords_textarea.value = '';
		            if( 'undefined' !== typeof data.new_table_html ){
		            	jQuery( '.edit-keywords-table' ).parents('.list-table-wrap')[0].innerHTML = data.new_table_html;
		            	on_update_edit_keywords_table();
			        }
	            }
            },
            error: function (data) {
                console.error( data );
            }
        });
		Util.addClass( elems.serp_add_keywords_layer, 'hidden' );
	};
	
	var on_click_keyword_remove = function(ev){
			
		ev.preventDefault();
		ev.stopPropagation();
		
		var keyword = this.getAttribute('data-keyword');
		var keyword_row = jQuery(this).parents('tr');
		keyword_row = keyword_row ? keyword_row[0] : null;

		if ( confirm('Are you sure you want to delete keyword "' + keyword + '" ?') ) {

			jQuery.ajax({
	            type: "post",
	            dataType: 'json',
	            url: elems.keyword_remove_url.value,
	            data: { keywords: [keyword] },
	            success: function (data) {
	            	if( 1 === parseInt( data.error, 10 ) ){
	            		console.error( data );		            	
		            }
		            else{
		            	if( 'undefined' !== typeof data.new_table_html ){
			            	jQuery( '.edit-keywords-table' ).parents('.list-table-wrap')[0].innerHTML = data.new_table_html;
			            	on_update_edit_keywords_table();
				        }
				        else{
				        	keyword_row.parentNode.removeChild( keyword_row );
				        }
				    }
	            },
	            error: function (data) {
	                console.error( data );
	            }
	        });
		}
	};

	var on_click_close_add_keywords_layer = function(ev){
		ev.preventDefault();
		ev.stopPropagation();
		Util.addClass( elems.serp_add_keywords_layer, 'hidden' );
	}

	var search_engines_events = function(){
		var i;
		elems.search_engines = document.querySelectorAll('.serp-search-engines li a');
		if( elems.search_engines.length ){
			for(i=0; i<elems.search_engines.length; i++){
				elems.search_engines[i].addEventListener('click', on_select_search_engine );
			}
		}
	};

	var search_engines_init = function(){
		elems.report_url = document.querySelector('input[name="report_url"]');
		elems.report_loader = document.querySelector('.serp-report-loader');
		elems.report_wrapper = document.querySelector('.serp-report-wrapper');
		search_engines_events();
	};

	var keywordOverallChart = function (chartData){
	    chartData = 'undefined' === chartData || ! chartData || ! jQuery.isArray( chartData ) || ! chartData.length ? [] : chartData;
	    var i;
	    var chart = {
	        elem: document.getElementById("keywordOverallChart"),
	        data: {
	            datasets: [{
	                label: "Keyword SERP",
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
	                display: true,
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
	                        display: true,
	                        labelString: "Date",
	                        fontColor: '#a1b4c4',
	                    },
	                    gridLines: {
	                        color: 'rgba(255,255,255,0.17)',
	                    },
	                    ticks: {
	                        autoSkip: true,
	                        maxTicksLimit: 6,
	                        fontColor: '#a1b4c4',
	                        maxRotation: 0,
	                        minRotation: 0
	                    },
	                }],
	                yAxes: [{
	                    scaleLabel: {
	                        display: true,
	                        labelString: "Position",
	                        fontColor: '#a1b4c4',
	                    },
	                    gridLines: {
	                        color: 'rgba(255,255,255,0.17)',
	                    },
	                    ticks: {
	                    	beginAtZero: true,
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
	};

	var update_active_graph = function( keyword, time, engine ){
		var selected = 0;
		var graph_html = '<canvas id="keywordOverallChart"></canvas>';
		var cache_key = keyword + '_' + time + '_' + engine;
		graph.active ={ keyword: keyword, time: time, engine: engine };
		selected += "n-o-n-e" === graph.active.keyword ? 0 : 1;
		selected += "n-o-n-e" === graph.active.time ? 0 : 1;
		selected += "n-o-n-e" === graph.active.engine ? 0 : 1;
		switch( selected ){
			case 0:
				elems.graph_wrapper.innerHTML = "";
				break;
			case 3:
				elems.graph_wrapper.innerHTML = "Loading...";

				if( 'undefined' !== typeof graph.cache[ cache_key ] ){
					elems.graph_wrapper.innerHTML = graph.cache[ cache_key ].html;
					if( graph.cache[ cache_key ].display ){
	                	setTimeout(function(){ keywordOverallChart( graph.cache[ cache_key ].display ); }, 0);
	                }
				}
				else{
					jQuery.ajax({
			            type: "post",
			            dataType: 'json',
			            url: elems.keyword_overall_url.value,
			            data: graph.active,
			            success: function (data) {
			            	if( 1 === parseInt( data.error, 10 ) ){
								console.error( data );
			                }
			                else{
			                	var i, 
			                		display = [],
			                		series = data.series,
			                		values_key = Object.keys(series).length ? Object.keys(series)[0] : null;

			                	if( values_key ){
				                	for(i in series[values_key].data ){
				                		if( series[values_key].data.hasOwnProperty(i) ){
				                			display.push({
								                x: new Date( series[values_key].date[i] ),
								                y: parseInt( series[values_key].data[i], 10 )
								            });
				                		}
				                	}

				                	graph.cache[ cache_key ] = { html: graph_html, display: display };
				                	setTimeout(function(){ keywordOverallChart( display ); }, 0);
				                }
				                else{
				                	graph.cache[ cache_key ] = { html: 'No data for selected filters', display: null };
				                }
				                elems.graph_wrapper.innerHTML = graph.cache[ cache_key ].html;
			                }
			            },
			            error: function (data) {
			                console.error( data );
			            }
			        });
				}
				break;
			default:
				elems.graph_wrapper.innerHTML = "Select Keyword, Time Period &amp; Search Engine";
		}
	};

	var graphs_init = function(){
		var i;

		elems.add_keywords = document.querySelector('.serp-keyword-add');
		elems.graph_wrapper = document.querySelector('.serp-keyword-graph');
		elems.serp_add_keywords_layer = document.querySelector('.serp-add-keywords-layer');
		elems.close_add_keywords_layer = document.querySelector('.close-add-keywords-layer');
		elems.serp_keywords_save = document.querySelector('.serp-keywords-save');
		elems.serp_keyword_webmaster_add = document.querySelector('.serp-keyword-webmaster-add');
		elems.keyword_overall_url = document.querySelector('input[name="keyword_overall_url"]');
		elems.keyword_remove_url = document.querySelector('input[name="keyword_remove_url"]');
		elems.keyword_add_url = document.querySelector('input[name="keyword_add_url"]');
		elems.keyword_add_webmaster_tool_url = document.querySelector('input[name="keyword_add_webmaster_tool_url"]');
		elems.serp_add_keywords_textarea = document.querySelector('.serp-add-keywords-textarea');

		elems.graph_select_boxes = {
			keyword: document.querySelector('select.graph-select-keyword'),
			time: document.querySelector('select.graph-select-time'),
			engine: document.querySelector('select.graph-select-engine')
		};

		if( elems.add_keywords ){
			elems.add_keywords.addEventListener('click', on_click_add_keywords );
		}

		if( elems.close_add_keywords_layer ){
			elems.close_add_keywords_layer.addEventListener('click', on_click_close_add_keywords_layer );
		}

		if( elems.serp_keywords_save ){
			elems.serp_keywords_save.addEventListener('click', on_click_serp_keywords_save );
		}

		if( elems.serp_keyword_webmaster_add ){
			elems.serp_keyword_webmaster_add.addEventListener('click', on_click_serp_keyword_webmaster_add );
		}

		if( elems.graph_select_boxes.keyword ){
			new Choices( elems.graph_select_boxes.keyword, { shouldSort: true } );
			elems.graph_select_boxes.keyword.onchange = function(e){				
				update_active_graph( this.value.trim(), graph.active.time, graph.active.engine );
	        };
		}

		if( elems.graph_select_boxes.time ){
			new Choices( elems.graph_select_boxes.time, { shouldSort: false } );
			elems.graph_select_boxes.time.onchange = function(e){
				update_active_graph( graph.active.keyword, this.value, graph.active.engine );
	        };
		}

		if( elems.graph_select_boxes.engine ){
			new Choices( elems.graph_select_boxes.engine, { shouldSort: false } );
			elems.graph_select_boxes.engine.onchange = function(e){
				update_active_graph( graph.active.keyword, graph.active.time, this.value );
	        };
		}

		on_update_edit_keywords_table();	// @note: Create and handle "DataTable" object for "Edit Keyword" table.
	};

	var keywords_filter_init = function(){

		var i, start_datepicker, end_datepicker;

		var elems = {
			start_date: document.querySelector('input[name="key-search-start-date"]'),
			end_date: document.querySelector('input[name="key-search-end-date"]'),
			search_type: document.querySelector('select[name="key-search-type"]'),
			search_dimension: document.querySelector('select[name="key-search-dimension"]'),
			filter_keywords_url: document.querySelector('input[name="filter_keywords_url"]'),
			serp_keywords_table_wrapper: document.getElementById('serp-keywords-table-wrapper'),
			serp_keyword_webmaster_add: document.querySelector('.serp-keyword-webmaster-add')
		};

		var active_filters = {
			start_date: null,
			end_date: null,
			search_type: null,
			search_dimension: null,
		};

		var on_filter_update = function(filter, new_val){

			var updated, tmp_arr;

			if( 'start_date' === filter || 'end_date' === filter ){
				
				tmp_arr = new_val.split('-');

				if( 1 === tmp_arr[1].length ){
					tmp_arr[1] = "0" + "" + tmp_arr[1];
				}

				if( 2 === tmp_arr[2].length, tmp_arr[2].length ){
					tmp_arr[2] = "0" + "" + tmp_arr[2];
				}

				new_val = tmp_arr.join('-');
			}

			updated = new_val !== active_filters[filter];

			if( ! updated ){
				return;
			}

			active_filters[filter] = new_val;

			jQuery.ajax({
	            type: "post",
	            dataType: 'json',
	            url: elems.filter_keywords_url.value,
	            data: active_filters,
	            success: function (data) {
	            	var newTableHtml;
	            	if( 'undefined' !== typeof data.html && null !== data.html ){
	            		elems.serp_keywords_table_wrapper.innerHTML = data.html;
	            		newTableHtml = elems.serp_keywords_table_wrapper.querySelector('table');
	            		if( ! newTableHtml.querySelector('tbody tr.no-records') ){
	            			DataTableElemInit( newTableHtml );
	            		}
	            		switch( active_filters.search_dimension ){
	            			case 'page':
	            			case 'country':
	            			case 'device':
	            				elems.serp_keyword_webmaster_add.style.display = 'none';
	            				break;
	            			case 'query':
	            			default:
	            				elems.serp_keyword_webmaster_add.style.display = '';
	            		}
	            	}
	            	else{
	            		console.error(data);
	            	}
	            },
	            error: function (data) {
	                console.error(data);
	            }
	        });
		}

		if( elems.start_date ){
			active_filters.start_date = elems.start_date.value;
			start_datepicker = $( elems.start_date ).datePicker();
			$( start_datepicker[0] ).on( 'keyup keydown keypress change paste', function(ev){
				on_filter_update( 'start_date', this.value );
			});
		}

		if( elems.end_date ){
			active_filters.end_date = elems.end_date.value;
			end_datepicker = $( elems.end_date ).datePicker();
			$( end_datepicker[0] ).on( 'keyup keydown keypress change paste', function(ev){
				on_filter_update( 'end_date', this.value );
			});
		}

		if( elems.search_type ){
			new Choices( elems.search_type, { shouldSort: false } );
			active_filters.search_type = elems.search_type.value;
			elems.search_type.onchange = function(e){				
				on_filter_update( 'search_type', this.value );
	        };
		}

		if( elems.search_dimension ){
			new Choices( elems.search_dimension, { shouldSort: false } );
			active_filters.search_dimension = elems.search_dimension.value;
			elems.search_dimension.onchange = function(e){
				on_filter_update( 'search_dimension', this.value );
	        };
		}
	};

	var init = function(){

		search_engines_init();

		keywords_filter_init();

		setTimeout(function(){	// @note: Wait some time to initialize DataTables.
			var a = document.querySelector('table.serps-info-table'), b = document.querySelector('table.webmaster-tools-table');
			DataTableElemInit( a, a.getAttribute('data-rows-per-page'), a.getAttribute('data-table-id') );
			DataTableElemInit( b, b.getAttribute('data-rows-per-page'), b.getAttribute('data-table-id') );
			graphs_init();			
		}, 0);
    };

    return {
        init: init,
    }
}());

(function(){
    "use strict";
    DomainSERPS.init();
}());