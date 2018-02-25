/**
 * Site : http:www.smarttutorials.net
 * @author muni
 */
$('#country_name').autocomplete({
		      	source: function( request, response ) {
		      		$.ajax({
		      			url : 'ajax.php',
		      			dataType: "json",
						data: {
						   name_startsWith: request.term,
						   type: 'country'
						},
						 success: function( data ) {
							 response( $.map( data, function( item ) {
								return {
									label: item,
									value: item
								}
							}));
						}
		      		});
		      	},
		      	autoFocus: true,
		      	minLength: 0      	
		      });
		      
		      
		      $('#fruit').autocomplete({
		      	source: function( request, response ) {
		      		$.ajax({
		      			url : 'ajax.php',
		      			dataType: "json",
						data: {
						   name_startsWith: request.term,
						   type: 'fruit'
						},
						 success: function( data ) {
							 response( $.map( data, function( item ) {
								return {
									label: item,
									value: item
								}
							}));
						}
		      		});
		      	},
		      	autoFocus: true,
		      	minLength: 0		      	
		      });
		      
		      $('#baby').autocomplete({
		      	source: function( request, response ) {
		      		$.ajax({
		      			url : 'ajax.php',
		      			dataType: "json",
						data: {
						   name_startsWith: request.term,
						   type: 'baby'
						},
						 success: function( data ) {
							 response( $.map( data, function( item ) {
								return {
									label: item,
									value: item
								}
							}));
						}
		      		});
		      	},
		      	autoFocus: true,
		      	minLength: 0		      	
		      });
		      
		      
		      
		      $(".delete").on('click', function() {
					$('.case:checkbox:checked').parents("tr").remove();
				    $('.check_all').prop("checked", false); 
					check();
				
				});
				var i=$('table tr').length;
				
				$(".addmore").on('click',function(){
					count=$('table tr').length;
					
				    var data="<tr><td><input type='checkbox' class='case'/></td><td><span id='snum"+i+"'>"+count+".</span></td>";
				    data +="<td><input type='text' id='countryname_"+i+"' name='countryname[]'/></td> <td><input type='text' id='country_no_"+i+"' name='country_no[]'/></td><td><input type='text' id='phone_code_"+i+"' name='phone_code[]'/></td><td><input type='text' id='country_code_"+i+"' name='country_code[]'/></td></tr>";
					$('table').append(data);
					row = i ;
					$('#countryname_'+i).autocomplete({
			      	source: function( request, response ) {
			      		$.ajax({
			      			url : 'ajax.php',
			      			dataType: "json",
							data: {
							   name_startsWith: request.term,
							   type: 'country_table',
							   row_num : row
							},
							 success: function( data ) {
								 response( $.map( data, function( item ) {
								 	var code = item.split("|");
									return {
										label: code[0],
										value: code[0],
										data : item
									}
								}));
							}
			      		});
			      	},
			      	autoFocus: true,	      	
			      	minLength: 0,
			      	select: function( event, ui ) {
						var names = ui.item.data.split("|");
						console.log(names[1], names[2], names[3]);						
						$('#country_no_'+row).val(names[1]);
						$('#phone_code_'+row).val(names[2]);
						$('#country_code_'+row).val(names[3]);
					}		      	
			      });
			      
			      $('#country_code_'+i).autocomplete({
			      	source: function( request, response ) {
			      		$.ajax({
			      			url : 'ajax.php',
			      			dataType: "json",
							data: {
							   name_startsWith: request.term,
							   type: 'country_table',
							   row_num : row
							},
							 success: function( data ) {
								 response( $.map( data, function( item ) {
								 	var code = item.split("|");
									return {
										label: code[3],
										value: code[3],
										data : item
									}
								}));
							}
			      		});
			      	},
			      	autoFocus: true,	      	
			      	minLength: 0,
			      	select: function( event, ui ) {
						var names = ui.item.data.split("|");					
						$('#country_no_'+row).val(names[1]);
						$('#phone_code_'+row).val(names[2]);
						$('#countryname_'+row).val(names[0]);
					}		      	
			      });
			      $('#phone_code_'+i).autocomplete({
			      	source: function( request, response ) {
			      		$.ajax({
			      			url : 'ajax.php',
			      			dataType: "json",
							data: {
							   name_startsWith: request.term,
							   type: 'country_table',
							   row_num : row
							},
							 success: function( data ) {
								 response( $.map( data, function( item ) {
								 	var code = item.split("|");
									return {
										label: code[2],
										value: code[2],
										data : item
									}
								}));
							}
			      		});
			      	},
			      	autoFocus: true,	      	
			      	minLength: 0,
			      	select: function( event, ui ) {
						var names = ui.item.data.split("|");					
						$('#country_no_'+row).val(names[1]);
						$('#country_code_'+row).val(names[3]);
						$('#countryname_'+row).val(names[0]);
					}		      	
			      });
			      $('#country_no_'+i).autocomplete({
			      	source: function( request, response ) {
			      		$.ajax({
			      			url : 'ajax.php',
			      			dataType: "json",
							data: {
							   name_startsWith: request.term,
							   type: 'country_table',
							   row_num : row
							},
							 success: function( data ) {
								 response( $.map( data, function( item ) {
								 	var code = item.split("|");
									return {
										label: code[1],
										value: code[1],
										data : item
									}
								}));
							}
			      		});
			      	},
			      	autoFocus: true,	      	
			      	minLength: 0,
			      	select: function( event, ui ) {
						var names = ui.item.data.split("|");					
						$('#country_code_'+row).val(names[3]);
						$('#phone_code_'+row).val(names[2]);
						$('#countryname_'+row).val(names[0]);
					}		      	
			      });
			      
					i++;
				});
				
				function select_all() {
					$('input[class=case]:checkbox').each(function(){ 
						if($('input[class=check_all]:checkbox:checked').length == 0){ 
							$(this).prop("checked", false); 
						} else {
							$(this).prop("checked", true); 
						} 
					});
				}
				
				function check(){
					obj=$('table tr').find('span');
					$.each( obj, function( key, value ) {
					id=value.id;
					$('#'+id).html(key+1);
					});
					}
					
					
					
					
					
					$('#countryname_1').autocomplete({
			      	source: function( request, response ) {
			      		$.ajax({
			      			url : 'ajax.php',
			      			dataType: "json",
							data: {
							   name_startsWith: request.term,
							   type: 'country_table',
							   row_num : 1
							},
							 success: function( data ) {
								 response( $.map( data, function( item ) {
								 	var code = item.split("|");
									return {
										label: code[0],
										value: code[0],
										data : item
									}
								}));
							}
			      		});
			      	},
			      	autoFocus: true,	      	
			      	minLength: 0,
			      	select: function( event, ui ) {
						var names = ui.item.data.split("|");
						console.log(names[1], names[2], names[3]);						
						$('#country_no_1').val(names[1]);
						$('#phone_code_1').val(names[2]);
						$('#country_code_1').val(names[3]);
					}		      	
			      });
			      
			      $('#country_code_1').autocomplete({
			      	source: function( request, response ) {
			      		$.ajax({
			      			url : 'ajax.php',
			      			dataType: "json",
							data: {
							   name_startsWith: request.term,
							   type: 'country_code',
							   row_num : 1
							},
							 success: function( data ) {
								 response( $.map( data, function( item ) {
								 	var code = item.split("|");
									return {
										label: code[3],
										value: code[3],
										data : item
									}
								}));
							}
			      		});
			      	},
			      	autoFocus: true,	      	
			      	minLength: 0,
			      	select: function( event, ui ) {
						var names = ui.item.data.split("|");					
						$('#country_no_1').val(names[1]);
						$('#phone_code_1').val(names[2]);
						$('#countryname_1').val(names[0]);
					},
					open: function() {
					$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
					},
					close: function() {
					$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
					}		      	
			      });
			      
			      $('#country_no_1').autocomplete({
			      	source: function( request, response ) {
			      		$.ajax({
			      			url : 'ajax.php',
			      			dataType: "json",
							data: {
							   name_startsWith: request.term,
							   type: 'country_no',
							   row_num : 1
							},
							 success: function( data ) {
								 response( $.map( data, function( item ) {
								 	var code = item.split("|");
									return {
										label: code[1],
										value: code[1],
										data : item
									}
								}));
							}
			      		});
			      	},
			      	autoFocus: true,	      	
			      	minLength: 0,
			      	select: function( event, ui ) {
						var names = ui.item.data.split("|");					
						$('#country_code_1 ').val(names[3]);
						$('#phone_code_1').val(names[2]);
						$('#countryname_1').val(names[0]);
					},
					open: function() {
					$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
					},
					close: function() {
					$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
					}		      	
			      });
			      
			      $('#phone_code_1').autocomplete({
			      	source: function( request, response ) {
			      		$.ajax({
			      			url : 'ajax.php',
			      			dataType: "json",
							data: {
							   name_startsWith: request.term,
							   type: 'phone_code',
							   row_num : 1
							},
							 success: function( data ) {
								 response( $.map( data, function( item ) {
								 	var code = item.split("|");
									return {
										label: code[2],
										value: code[2],
										data : item
									}
								}));
							}
			      		});
			      	},
			      	autoFocus: true,	      	
			      	minLength: 0,
			      	select: function( event, ui ) {
						var names = ui.item.data.split("|");					
						$('#country_code_1 ').val(names[3]);
						$('#country_no_1 ').val(names[1]);
						$('#countryname_1').val(names[0]);
					},
					open: function() {
					$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
					},
					close: function() {
					$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
					}		      	
			      });