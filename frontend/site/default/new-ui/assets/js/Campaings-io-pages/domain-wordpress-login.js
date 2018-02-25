(function(){
    
    "use strict";
    
    function wp_login_action(){
    
	    var redirect_url = null,
	    	domain_id_elem = document.querySelector('input[name="domain_id"]'),
	    	siteBaseURL_elem = document.querySelector('input[name="base_url"]'),
	    	wp_ping_url_elem = document.querySelector('input[name="wp_ping_url"]'),
	    	wp_login_action_url_elem = document.querySelector('input[name="wp_login_action_url"]'),
	    	wp_unreachable_url_elem = document.querySelector('input[name="wp_unreachable_url"]');    

	    var domain_id = domain_id_elem ? parseInt( domain_id_elem.value, 10 ) : 0,
	    	siteBaseURL = siteBaseURL_elem ? siteBaseURL_elem.value : null,
	    	wp_ping_url = wp_ping_url_elem ? wp_ping_url_elem.value : null;

	    redirect_url = siteBaseURL;

	    if( ! wp_ping_url ){
	    	window.location.href = siteBaseURL;
	    	return;
	    }

	    jQuery.ajax({
	        type: 'post',
	        url: wp_ping_url,
	        success: function (data) {
	    		if( 1 === parseInt( data, 10 ) ){
	    			redirect_url = wp_login_action_url_elem ? wp_login_action_url_elem.value : null;
	    		}
	    		else{
	    			redirect_url = wp_unreachable_url_elem ? wp_unreachable_url_elem.value : null;
	    		}
	        },
	        error: function (data) {
	    		redirect_url = wp_unreachable_url_elem ? wp_unreachable_url_elem.value : null;
	        },
	    }).done(function (data) {
	    	window.location.href = redirect_url;
	    });
	}

	setTimeout( wp_login_action, 100 );
}());
