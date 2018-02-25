<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header_new');

?>

<!-- BEGIN PAGE CONTENT -->
	<div class="row">
	    <div class="col-sm-12 col-md-12">
            <?php if ($this->session->flashdata('type') == 'error'): ?>
              	<div class="alert alert-danger"><?php echo $this->session->flashdata('msg'); ?></div>
      		<?php endif; ?>
		        
		    <?php if ($this->session->flashdata('type') == 'success'): ?>
		        <div class="alert alert-success"><?php echo $this->session->flashdata('msg'); ?></div>   
		    <?php endif; ?>
	    </div>
	</div>	

	<div class="row">
		<div class="col-md-12">
			<div class="panel">  
				<div class="panel-header">
					<h3><strong>Keyword Search Engine Positions</strong></h3>
				</div>
				
				<div class="panel-content refined-tab">
					<div class="nav-tabs3">
						<ul id="myTab6" class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#tab9_1"> <i class="icon-layers"></i> CURRENT DATA</a></li>
							<li class=""><a data-toggle="tab" href="#tab9_2"> <i class="icon-graph"></i> GRAPHS</a></li>
							<li class=""><a data-toggle="tab" href="#tab9_4"> <i class="icon-pencil"></i> EDIT KEYWORDS</a></li>
							<li class=""><a data-toggle="tab" href="#tab9_5"> <i class="icon-link"></i> LINK WEBMASTER TOOLS </a></li>
							<!-- <li class=""><a data-toggle="tab" href=""></a></li> -->
							 <button class="btn btn-blue pull-right" style="margin-bottom: 0" type="button" data-toggle="modal" data-target="#addKeywordmodal">ADD KEYWORDS</button>
						</ul>
							<div class="tab-content ">
							
								<div id="tab9_1" class="tab-pane fade active in"> 
									<ul id="myTab6" class="nav nav-tabs country-tab">

										<?php foreach($searchengines as $searchengine) 
											if($searchengine['name'] == 'g_uk'){
												$name = 'Google UK';
											} else if($searchengine['name'] == 'g_us') {
												$name = 'Google';
											}else if($searchengine['name'] == 'g_ca') {
												$name = 'Google Canada';
											}else if($searchengine['name'] == 'g_au') {
												$name = 'Google Australia';
											}
										?>
										<li class="active"><a href="#" onclick="getSerpByEngine('<?php echo $searchengine['id']; ?>')"> <i class="flags <?php echo $searchengine['name']; ?>"></i><span><?php echo $name; ?></span></a></li>
										
									</ul>
									<div id="tab_content_serp">
										
									</div>
								</div> 
								
								<div id="tab9_2" class="tab-pane fade">
									<div class="panel">
										<div class="panel-header bg-blue">
											<div class="row">
												<div class="col-md-2 col-sm-4 col-xs-4">
													<div class="form-group">
														<label>Choose keyword</label>
														<select name="choosen_keyword" id="choosen_keyword" class="form-control">
															<option value=""></option>	
														</select>
													</div>
												</div>	
												<div class="col-md-2 col-sm-4 col-xs-4">
													<div class="form-group">
														<label>Choose Time</label>
														<select name="choosen_time" id="choosen_time" class="form-control">
															<option value="">All time</option>	
															<option value="7days">7 days</option>	
															<option value="15days">15 days</option>	
															<option value="1month">1 month</option>	
														</select>
													</div>
												</div>
												<div class="col-md-2 col-sm-4 col-xs-4">
													<div class="form-group">
														<label>Search Engine</label>
														<select name="choosen_searchengine" id="choosen_searchengine" class="form-control">
															<option value="en-uk">Google.co.uk</option>	
															<option value="en-uk-mobile">Google.co.uk (mobile)</option>	
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="panel-content">
											<div id="keyword_overall_graph" style="min-width: 900px; height: 400px; margin: 0 auto">
											</div>
										</div>
									</div>
									
								</div>
								
								<div id="tab9_4" class="tab-pane fade"> <!-- 4 -->
									<?php if (isset($editKeywords) && !empty($editKeywords)) { ?>
										<form action="/auth/deletekeyword" method="post" onsubmit="return confirmbulkdelete();">
											<div class="row">
												<div class="col-md-12 col-xs-12 col-lg-12">
													<button class="btn btn-rounded btn-danger" type="submit">DELETE KEYWORDS</button>
												</div>
											</div>
											<div class="panel">
												
												<!-- TABLE START -->
												<div class="panel-content pagination2 table-responsive">
													<table class="table table-hover ds-list-table table-dynamic">
													    <thead>
															<tr>
																<th><input type="checkbox" id="del_keyword"> <label for="del_keyword">CHECK ALL</th>
																<th>KEYWORDS</th>
																<th>ACTION</th>
															</tr>
														</thead>
													    <tbody>
															<?php
																if (isset($editKeywords)):
														    	foreach ($editKeywords as $key1 => $val):
														    ?>

															<tr>
																<td><input class="del_keyword" type="checkbox" data-checkbox="icheckbox_square-blue"  name="del_keyword[]" value="<?php echo $val['name']; ?>"></td>
																<td><a href="#"><?= $val['name']; ?></a></td>
																<td><a href="/auth/deletekeyword?keyword=<?php echo $val['name']; ?>" onclick="return confirmKeywordDelete();"><button class="btn btn-danger" type="button">DELETE</button></a></td>
															</tr>

														    <?php
															    endforeach;
																endif;
															?>		
														</tbody>	
													</table>
												</div>
												<!-- TABLE END -->
											</div>
										</form>
									<?php
										}
										else {
									?>
										<div class="alert alert-danger" role="alert">No keywords found</div>
						            <?php
						            	}
						            ?>    
								</div>
							
								<div id="tab9_5" class="tab-pane fade">  <!-- 5 -->
									<?php echo form_open(site_url('auth/addWebMasterKeywords')) ?>
										<div class="row">
											<div class="col-sm-12">
												<button class="btn btn-rounded btn-success pull-right" type="submit">ADD WEBMASTER KEYWORDS</button>
											</div>
										</div>
										<div class="panel">
											<div class="panel-content pagination2 table-responsive">   <!-- TABLE START -->
												<table id="data_table" class="table table-bordered ds-list-table dataTable no-footer" role="grid">
													<thead>
														<tr>
															<th>Keywords</th>
															<th>Clicks</th>
															<th>Impressions</th>
															<th><input type="checkbox" id="check-all"> <label for="del_keyword">Check All</label></th>
														</tr>
													</thead>
													<tbody id="dtable">
														<!-- <tr>	
															<td></td>
															<td></td>
															<td></td>
															<td></td>
															<input class="del_keyword" type="hidden" name="add_keyword[]" data-checkbox="icheckbox_square-blue" value="">
														</tr> -->
													</tbody>
												</table>
											</div>
										</div>   <!-- TABLE END -->
										
									</form>
								</div>  <!-- 5 -->
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>

  <?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$this->load->view(get_template_directory() . 'footer_new');
  ?>

  <!-- Modal -->
<div id="addKeywordmodal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal">&times;</button>
            	<h4 class="modal-title">Add Keywords (one keyword per line)</h4>
            </div>
        	<form action="/auth/addkeyword" method="post" onsubmit="return addNewKeyword();">
        		<div class="modal-body">
          			<textarea rows="10" cols="5" class="form-control" name="keywords" id="add_keywords"></textarea>
	            </div>	
            	<div class="modal-footer">
          		    <button class="btn btn-primary">Save</button>
            	</div>
	              	
        	</form>
        </div>

    </div>
</div>

<script type="text/javascript">
          function addNewKeyword()
          {
              if ($('#add_keywords').val() == '') {
                  alert('Please enter at least a keyword');
                  return false;
              } else {
                  return true;
              }
          }
          function confirmKeywordDelete()
          {
              if (confirm('Are you sure you want to delete keyword?')) {
                  return true;
              } else {
                  return false;
              }
          }

          $(document).ready(function () {
	        $('#del_keyword').on('ifChanged', function (event) {
	            if ($('#del_keyword').is(":checked"))
	            {	
	            	$('.del_keyword').iCheck('check');
	            } else {
	            	$('.del_keyword').iCheck('uncheck');
	            }	
	        });
              
          	var oTable = $('#data_table').dataTable({
          		aoColumnDefs: [{
	                "bSortable": true,
	                "aTargets": [0]
              	}],
              	aaSorting: [
              	    [1, 'asc']
              	],
	            fnCreatedRow: function( nRow, aData, iDataIndex ) {
	                $('#dtable tr:eq('+nRow+') input').val( aData[0]);
	            }
            });

			oTable.fnSort([[2, 'desc']]);
			$.ajax({
				url: site_url + "analytics/analytics/getSerpKeywords",
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
				dataType: 'json',
				success: function (s) {
				  // oTable.fnClearTable();
				  for (var i = 0; i < s.length; i++) {
				      oTable.fnAddData([s[i][0], s[i][1], s[i][2], s[i][3]]);
				  } // End For 
				}, error: function (e) {

			}});


			$('#check-all').on('ifChanged', function (event) {
				if ($('#check-all').is(":checked"))
				{
					$('#tab9_5 input:checkbox').iCheck('check');	
				} else {
					$('#tab9_5 input:checkbox').iCheck('uncheck');	
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
					$('#keyword_overall_graph').highcharts({
					       chart: {
					              type: 'line'
					          },
					          title: {
					              text: 'Keyword serp'
					          },
					          subtitle: {
					              text: ''
					          },
					          xAxis: {
					              categories: data.category
					          },
					          yAxis: {
					              title: {
					                  text: 'Position'
					              }
					          },
					          plotOptions: {
					              line: {
					                  dataLabels: {
					                      enabled: true
					                  },
					                  enableMouseTracking: false
					              }
					          },
					          series: data.series
					   });
				}

			},'json')

			$.post('/auth/viewserpreport',function(data){
				$('#tab_content_serp').html(data);
			},'html')




		})

          function getSerpByEngine(engineid){
          	$.pos('/auth/viewrserpreport',{search_engine_id:engineid},function(data){
          		$('#tab_content_serp').html(data);
          	},'html')
          }


		function confirmbulkdelete()
		{
		  	if(confirm('Are you sure you want to delete keywords?')){
		  		return true;
		  	}else{
		  		return false;
		  	}
		}
</script>