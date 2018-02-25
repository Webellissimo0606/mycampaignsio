var DomainHeatmaps = (function(){
	
	var elems = {
			domain_heatmap_select: null,
			delete_domain_heatmap: null,
		};

	var init = function(){

		var i;

		elems.domain_heatmap_select = document.querySelector('.domain-heatmap-select');
		elems.delete_domain_heatmap = document.querySelectorAll('.delete-domain-heatmap');

		if( elems.domain_heatmap_select ){
			elems.domain_heatmap_select.onchange = function(ev){
				if( this.value ) window.location.href = this.value;
			};
		}

		if( elems.delete_domain_heatmap.length ){

			for(i=0; i<elems.delete_domain_heatmap.length; i++){
				
				elems.delete_domain_heatmap[i].onclick = function(ev){
					if( confirm('Are you sure you want to delete heatmap?') ){
						return true;
					}
					else{
						return false;
					}					
				};
			}
		}

    };

    return {
        init: init,
    }
}());

(function(){
    "use strict";
    setTimeout(function(){
    	DomainHeatmaps.init();
    }, 100);
}());