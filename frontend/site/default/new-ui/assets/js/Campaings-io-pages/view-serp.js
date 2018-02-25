function addNewKeyword() {
	if( '' === jQuery('#add_keywords').val().trim() ) {
		alert('Please enter at least a keyword');
		return false;
	}
	else {
		return true;
	}
}

function confirmKeywordDelete() {
	if ( confirm('Are you sure you want to delete keyword?') ) {
		return true;
	}
	else {
		return false;
	}
}

function getSerpByEngine(engineid){
  	jQuery.pos('/auth/viewrserpreport',{search_engine_id:engineid},function(data){
  		$('#tab_content_serp').html(data);
  	},'html')
}

function confirmbulkdelete() {
  	if( confirm('Are you sure you want to delete keywords?') ){
  		return true;
  	}
  	else{
  		return false;
  	}
}

jQuery(document).ready(function () {

	var $ = jQuery;

    $('#del_keyword').on('click', function (event) {
        if( $('#del_keyword').is(":checked") ) {
        	$('.del_keyword').prop('checked',true);
        }
        else {
        	$('.del_keyword').prop('checked',false);
        }	
    });
      
  	$.ajax({
		url: site_url + "analytics/analytics/getSerpKeywords",
		data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
		dataType: 'json',
		success: function (s) {
		  	var html = '';
		  for (var i = 0; i < s.length; i++) {
		  	html+='<tr>'
		  	html+='<td>'+s[i][0]+'</td>';
		  	html+='<td>'+s[i][1]+'</td>';
		  	html+='<td>'+s[i][2]+'</td>';
		  	html+='<td>'+s[i][3]+'</td>';
		 	html+='</tr>';
		  } // End For 
		   	$('#dtable').html(html);
		}, error: function (e) {

	}});

	$('#check-all').on('click', function (event) {
		if ($('#check-all').is(":checked"))
		{
			$('#dtable input:checkbox').prop('checked',true);
		} else {
			$('#dtable input:checkbox').prop('checked',false);	
		}	

	});

	$("#menu5 #check-all").change(function () {
	  $("#menu5 input:checkbox").prop('checked', $(this).prop("checked"));
	});

	$(document).on("change", "#menu5 .add_keyword", function () {
	  if ($(this).prop('checked') == false) {
	      $("#menu5 #check-all").prop('checked', false)
	  }
	});

	$.post('/serp/analyze/getoverallvisibility',{searchengine:$('#choosen_searchengine').val(),keyword:$('#choosen_keyword').val(),date:$('#choosen_time').val()},function(data){
		if(data.type == 'success'){
			// $('#keyword_overall_graph').highcharts({
			//        chart: {
			//               type: 'line'
			//           },
			//           title: {
			//               text: 'Keyword serp'
			//           },
			//           subtitle: {
			//               text: ''
			//           },
			//           xAxis: {
			//               categories: data.category
			//           },
			//           yAxis: {
			//               title: {
			//                   text: 'Position'
			//               }
			//           },
			//           plotOptions: {
			//               line: {
			//                   dataLabels: {
			//                       enabled: true
			//                   },
			//                   enableMouseTracking: false
			//               }
			//           },
			//           series: data.series
			//    });
		}

	},'json');

	$.post('/auth/viewserpreport',function(data){
		$('#tab_content_serp').html(data);
	},'html');

}());