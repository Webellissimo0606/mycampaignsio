var DomainEcommerce = (function(){

	var elems = {
		'sales_performance_days_filter_form' : null,
		'sales_performance_days_filter' : null
	};

	var init = function(){
		elems.sales_performance_days_filter_form = document.querySelector('.sales-performance-days-filter')
		elems.sales_performance_days_filter = elems.sales_performance_days_filter_form.querySelector('select');
		if( elems.sales_performance_days_filter ){
			new Choices( elems.sales_performance_days_filter, { shouldSort: false } );
			elems.sales_performance_days_filter.onchange = function(){
				elems.sales_performance_days_filter_form.submit();
			}
		}
    };

    return {
        init: init
    }
}());

(function(){
    "use strict";
    DomainEcommerce.init();
}());