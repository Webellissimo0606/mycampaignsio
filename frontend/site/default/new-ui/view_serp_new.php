<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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

	<div class="content-row tab-contents-row">
		
			 
				<h3 class="fw4 ph2">KEYWORD SEARCH ENGINE POSITIONS</h3>				
				
					<div class="content-tab-items">
						<ul >
							<li class="active w-25 w-auto-l" data-tab-item="1"> CURRENT DATA</li>
							<li data-tab-item="2" class="w-25 w-auto-l"> GRAPHS</li>
							<li data-tab-item="3" class="w-25 w-auto-l">EDIT KEYWORDS</li>
							<li data-tab-item="4" class="w-25 w-auto-l">LINK WEBMASTER TOOLS</li>
							<!-- <li class=""><a data-toggle="tab" href=""></a></li> -->
							<button class="fl dib mb2 mr1 pv2 ph3 f7 btn-color btn-dark-br0 no-underline br1" type="button" data-toggle="modal" data-target="#addKeywordmodal">ADD KEYWORDS</button>
						</ul>

						<div class="content-column w-100">
						    <div class="content-column-main">
						        <div class="content-column-inner">
						        	<div data-tab-content="1" class="content-tab-content active">
						       
						        	    <ul class="keyword-countries"><?php

						        	    	if( is_array( $searchengines ) && ! empty( $searchengines ) ){
							        	    	
							        	    	foreach( $searchengines as $engine ){
							        	    		switch( $engine['name'] ){
							        	    			case 'en-uk':
							        	    				$name = 'Google UK';
							        	    				break;
							        	    			case 'en-ca':
							        	    				$name = 'Google Canada';
							        	    				break;
							        	    			case 'en-au':
							        	    				$name = 'Google Australia';
							        	    				break;
							        	    			case 'en-us':
							        	    			default:
							        	    				$name = 'Google';
							        	    				break;
							        	    		} ?>
							        	    		<li><a href="#" tile="" class="br-pill active f6" onclick="getSerpByEngine('<?php echo $engine['id']; ?>')">  <img src="/img/flag-<?php echo $searchengine['name']; ?>" alt="" class="br-100"/><span><?php echo $name; ?></span></a></li> <?php
							        	    	}
						        	    	}
						        	    	?>
						        	    </ul>
						        	    
						        	    <hr style="margin-top:0.5rem;"/>
						        	    
						        	    <div id="tab_content_serp"></div>
						        	</div>
						        

						        	<div data-tab-content="2" class="content-tab-content">
						        		
									
											            	<div class="cf">

												            	<div class="relative fl w-100 w-third-ns">
												            		<div class="pv2 pa2-ns pl0-ns">
												                        <label for="" class="db fw5 mb2">Choose keyword:</label>
												                        <select class="w-100 pa2">
												                            <option></option>
												                        </select>
											                        </div>
											                    </div>

											                    <div class="relative fl w-100 w-third-ns">
												            		<div class="pv2 pa2-ns">
												                        <label for="" class="db fw5 mb2">Choose time:</label>
												                        <select class="w-100 pa2">
												                            <option>All time</option>
												                            <option>15 days</option>
												                            <option>30 days</option>
												                            <option>1 month</option>
												                        </select>
											                        </div>
											                    </div>

											                    <div class="relative fl w-100 w-third-ns">
												            		<div class="pv2 pa2-ns pr0-ns">
												                        <label for="" class="db fw5 mb2">Search engine:</label>
												                        <select class="w-100 pa2">
												                            <option>Google.co.uk</option>
												                            <option>Google.co.uk (mobile)</option>
												                        </select>
											                        </div>
											                    </div>

										                    </div>
											<hr/>
											        <div class="aspect-ratio aspect-ratio--16x9">
											        	<div class="aspect-ratio--object dt tc black-70 bg-white-10 fw1 f3 f2-ns"><span class="dtc v-mid pa3" id="keyword_overall_graph"></span></div>
													</div>
									
						        	</div>
						        	<div data-tab-content="3" class="content-tab-content">
						        		
									<?php if (isset($editKeywords) && !empty($editKeywords)) { ?>
										<form action="/auth/deletekeyword" method="post" onsubmit="return confirmbulkdelete();">
											<div class="cf">
												
												<button class="fl dib mb2 mr1 pv2 ph3 f7 btn-color btn-dark-br0 no-underline br1 white" type="submit">DELETE KEYWORDS</button>
											</div>
												<!-- TABLE START -->
													<table  class="list-table collapse tc mv3">
													    <thead>
															<tr>
																<th class="nowrap" style="width:1px;"><label class="pointer" for="del_keyword"><input type="checkbox" id="del_keyword"> CHECK ALL</label></th>
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
																<td><?= $val['name']; ?></td>
																<td><a href="/auth/deletekeyword?keyword=<?php echo $val['name']; ?>" class="dib mv1 mh1 f7 btn-lines btn-dark-br0 no-underline pv1 pr2 pl1-l br1" onclick="return confirmKeywordDelete();">
																	<i class="material-icons">&#xE872;</i>
																	<small class="fw7">REMOVE</small>
																	</a></td>
															</tr>

														    <?php
															    endforeach;
																endif;
															?>		
														</tbody>	
													</table>
											
												<!-- TABLE END -->
										
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
						        	<div data-tab-content="4" class="content-tab-content">
						        		
									<?php echo form_open(site_url('auth/addWebMasterKeywords')) ?>
										<div class="cf">
											<button class="fl dib mb2 mr1 pv2 ph3 f7 btn-color btn-dark-br0 no-underline br1" type="submit">ADD WEBMASTER KEYWORDS</button>
										</div>
											<table id="data_table" class="list-table collapse tc mv3">
													<thead>
														<tr>
															<th class="nowrap" style="width:1px;"><input type="checkbox" id="check-all"> <label for="del_keyword">Check All</label></th>
															<th>Keywords</th>	
															<th>Clicks</th>
															<th>Impressions</th>
															
														</tr>
													</thead>
													<tbody id="dtable">
													</tbody>
												</table>
											
										
									</form>
						        	</div>

						</div>
						</div>
						</div>        	

					</div>
				
			
		
	</div>
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