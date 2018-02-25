  <?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header_new');
?>
    
        <!-- BEGIN PAGE CONTENT -->
        <div class="page-content page-thin">
		  
			<div class="row">
				<div class="col-md-12">
				
					<div class="wizard-div current wizard-sea">
                      
                      	<form class="wizard" data-style="sea" role="form" novalidate onsubmit="create_project()" method="post" action="javascript:void(0);">
					  
	                        <fieldset>
	                          <legend>Add Domain</legend>
							  
	                          <div class="row">
							  
	                            <div class="col-lg-6">
									<div class="row">
										<div class="col-lg-12 form-group">
											<label>Domain Name<span class="required">*</span></label>
											<input id="domain_name" class="form-control" type="text" required="" required data-parsley-group="block0" placeholder="Domain Name" data-validate-words="2" name="domain_name" value="<?php echo $domainDetails['domain_name']; ?>">
										</div>
										
										<div class="col-lg-12">
											<div class="form-group">
												<label for="monitor_website_uptime">
													<span>	Would you like to Monitor website uptime?</span>
												</label>
												<div class="myinput_pane">
													<label class="switch switch-green">
													<input class="switch-input" type="checkbox" name="monitor_website_uptime" <?php if($domainDetails['monitorUptime'] == 1)echo 'checked="checked"'; ?> id="monitor_website_uptime">
													<span class="switch-label" data-off="Off" data-on="On"></span>
													<span class="switch-handle"></span>
													</label>
												</div>
											</div>

											<div class="form-group ">
												<label for="is_ecommerce">
													<span>Is your site ecommerce site ?</span>
												</label>
												<div class="myinput_pane">
													<label class="switch switch-green">
													<input class="switch-input" type="checkbox"  name="is_ecommerce" id="is_ecommerce" <?php if($domainDetails['is_ecommerce'] == 1)echo 'checked="checked"'; ?>>
													<span class="switch-label" data-off="Off" data-on="On"></span>
													<span class="switch-handle"></span>
													</label>
												</div>
											</div>
											
										</div>
									</div>
								
	                            </div>
								
	                            <div class="col-lg-6" id="page_details">
									<div class="row">
										<div class="col-lg-12 form-group">
											<label>Page Header<span class="required">*</span></label>
											<input id="page_header" class="form-control" type="text" required="" placeholder="Page Header to Search" data-validate-words="2" name="page_header" value="<?php echo htmlspecialchars($uptimeDetails['keyword']['header']); ?>">
										</div>
										
										<div class="col-lg-12 form-group">
											<label>Page Body<span class="required">*</span></label>
											<input id="page_body" class="form-control" type="text" required="" placeholder="Page Body to Search" data-validate-words="2" name="page_body" value="<?php echo htmlspecialchars($uptimeDetails['keyword']['body']); ?>">
										</div>
										
										<div class="col-lg-12 form-group">
											<label>Page Footer<span class="required">*</span></label>
											<input id="page_footer" class="form-control" type="text" required="" placeholder="Page Footer to Search" data-validate-words="2" name="page_footer" value="<?php echo htmlspecialchars($uptimeDetails['keyword']['footer']); ?>">
										</div>
										<div class="col-lg-12 form-group">
											<label>Frequency<span class="required">*</span></label>
											<select name="frequency" id="frequency" class="form-control">
												<option value="1" <?php if(isset($uptimeDetails['keyword']['frequency']) && $uptimeDetails['keyword']['frequency'] == 1)echo 'selected="selected"'; ?>>1 min</option>	
												<option value="5" <?php if(isset($uptimeDetails['keyword']['frequency']) && $uptimeDetails['keyword']['frequency'] == 5)echo 'selected="selected"'; ?>>5 min</option>	
												<option value="15" <?php if(isset($uptimeDetails['keyword']['frequency']) && $uptimeDetails['keyword']['frequency'] == 15)echo 'selected="selected"'; ?>>15 min</option>	
												<option value="30" <?php if(isset($uptimeDetails['keyword']['frequency']) && $uptimeDetails['keyword']['frequency'] == 30)echo 'selected="selected"'; ?>>30 min</option>	
												<option value="60" <?php if(isset($uptimeDetails['keyword']['frequency']) && $uptimeDetails['keyword']['frequency'] == 60)echo 'selected="selected"'; ?>>1 hour</option>	
												<option value="120" <?php if(isset($uptimeDetails['keyword']['frequency']) && $uptimeDetails['keyword']['frequency'] == 120)echo 'selected="selected"'; ?>>2 hours</option>	
												<option value="360" <?php if(isset($uptimeDetails['keyword']['frequency']) && $uptimeDetails['keyword']['frequency'] == 360)echo 'selected="selected"'; ?>>6 hours</option>	
												<option value="720" <?php if(isset($uptimeDetails['keyword']['frequency']) && $uptimeDetails['keyword']['frequency'] == 720)echo 'selected="selected"'; ?>>12 hours</option>	
												<option value="1440" <?php if(isset($uptimeDetails['keyword']['frequency']) && $uptimeDetails['keyword']['frequency'] == 1440)echo 'selected="selected"'; ?>>24 hours</option>	
											</select>
										</div>


										<div class="col-lg-12 form-group">
											<label>Assign subusers to domain</label>
											<select multiple="multiple" class="form-control" data-search="true" name="subusers" id="subusers" placeholder="choose subuser">
											<?php if($subusers): ?>
												<?php foreach($subusers as $subuser): ?>
													<option value="<?php echo $subuser['id']; ?>" <?php if(in_array($subuser['id'], $domainSubusers))echo 'selected="selected"'; ?>><?php echo $subuser['first_name'].' '.$subuser['last_name']; ?></option>
											<?php endforeach; ?>
											<?php endif; ?>		
											</select>
										</div>

										<div class="col-lg-12 form-group">
											<label>Choose group to assign domain</label>
											<select multiple="multiple" class="form-control" data-search="true" name="groups" id="groups" placeholder="choose groups">
												<?php if($groups): ?>
												<?php foreach($groups as $group): ?>
												<option value="<?php echo $group['id']; ?>" <?php if(in_array($group['id'], $domainGroups))echo 'selected="selected"'; ?>><?php echo $group['group_name']; ?></option>
											<?php endforeach; ?>
											<?php endif; ?>
											</select>
										</div>
									</div>
									
	                            </div>

	                      		
	                          </div>
	                        </fieldset>
							
	                        <fieldset>
								<legend>Connections</legend>
								<div class="row">
									<div class="col-lg-6">
										<div class="row">
											<div class="col-lg-12 form-group">
												<label>Admin URL<span class="required">*</span></label>
												<input id="adminURL" class="form-control" type="text" required="" placeholder="http://" data-validate-words="2" name="adminURL" value="<?php echo $domainDetails['adminURL']; ?>">
											</div>
											
											<div class="col-lg-12 form-group">
												<label>Admin Username<span class="required">*</span></label>
												<input id="adminUsername" class="form-control" type="text" required="" placeholder="Username" data-validate-words="2" name="adminUsername" value="<?php echo $domainDetails['adminUsername']; ?>">
											</div>

											<div class="col-lg-12 form-group">
												<label for="connect_to_google">
													<span>Connect to Google</span>
												</label>
													<div class="myinput_pane">
														<label class="switch switch-green">
														<input class="switch-input" type="checkbox"  id="connect_to_google" name="connect_to_google" <?php if($domainDetails['connectToGoogle'] == 1)echo 'checked="checked"'; ?>>
														<span class="switch-label" data-off="Off" data-on="On"></span>
														<span class="switch-handle"></span>
														</label>
													</div>
												</div>
												
											<div class="col-lg-12 form-group">
												<label for="monitor_malware">
													<span>Monitor Malware</span>
												</label>
												<div class="myinput_pane">
													<label class="switch switch-green">
													<input class="switch-input" type="checkbox"  name="monitor_malware" id="monitor_malware" <?php if($domainDetails['monitorMalware'] == 1)echo 'checked="checked"'; ?>>
													<span class="switch-label" data-off="Off" data-on="On"></span>
													<span class="switch-handle"></span>
													</label>
												</div>
											</div>
												
											<div class="col-lg-12 form-group">
												<label for="crawl_error_webmaster">
												<span>Would you like to get Crawl Errors from Google Webmaster Tools?</span>
												</label>
												<div class="myinput_pane">
													<label class="switch switch-green">
													<input class="switch-input" type="checkbox" id="crawl_error_webmaster" name="crawl_error_webmaster" <?php if($domainDetails['webmaster'] == 1)echo 'checked="checked"'; ?>>
													<span class="switch-label" data-off="Off" data-on="On"></span>
													<span class="switch-handle"></span>
													</label>
												</div>
											</div>
												
											<div class="col-lg-12 form-group">
												<label for="search_query_webmaster">
												<span>Would you like to track your Search Queries from Google Webmaster Tools?</span>
												</label>
												<div class="myinput_pane">
													<label class="switch switch-green">
													<input class="switch-input" type="checkbox" id="search_query_webmaster" name="search_query_webmaster" <?php if($domainDetails['search_analytics'] == 1)echo 'checked="checked"'; ?>>
													<span class="switch-label" data-off="Off" data-on="On"></span>
													<span class="switch-handle"></span>
													</label>
												</div>
											</div>
										</div>
									</div>

									<div class="col-lg-6" id="google_webmaster_details" <?php if($domainDetails['connectToGoogle'] != 1) echo 'style="display:none;"'; ?> >
										<input type="hidden" name="connectToGoogleValue" id="connectToGoogleValue" value="<?php echo $google_connect ?>">
										<div class="row">
											<div class="col-lg-12">
												<label>Choose your google webmaster account</label>
												<select name="gaAccounts" id="gaAccounts" class="form-control" style="border:1px solid #57bcd1 !important;">
												    <option value="0">Select</option>
												</select>
											</div>
										</div>
										<?php $user_session = $this->session->get_userdata(); 
										?>
										<?php if(isset($user_session['parent_id']) && $user_session['parent_id'] != null): ?>
										<div class="row" style="margin-top: 20px;">
											<div class="col-lg-12">
												<button onclick="login()" type="button" class="btn btn-success">Add new google account</button><br>
												<span style="font-style: italic;">Please dont added if you already have added your account.</span>
											</div>	
												
										</div>
									<?php endif; ?>
		                            </div>
								</div>
							</fieldset>
						  
							<fieldset>
								<legend> Monitor Keyword SERP</legend>
								<div class="row">
									<div class="col-lg-6">
										<div class="row">
											<div class="col-lg-12 form-group">
												<select class="form-control" multiple="" data-placeholder="Choose your search engine" name="engines" id="engines" style="border:1px solid #57bcd1 !important;background-color: #fff;">
													<optgroup label="Search engine">

													  <option value="g_us" <?php if($searchEngine[0]['name'] == 'g_us')echo 'selected="selected"'; ?>>google.com</option>

													  <option value="g_uk" <?php if($searchEngine[0]['name'] == 'g_uk')echo 'selected="selected"'; ?>>google.co.uk</option>

													  <option value="g_ca" <?php if($searchEngine[0]['name'] == 'g_ca')echo 'selected="selected"'; ?>>google.co.ca</option>

													  <option value="g_au" <?php if($searchEngine[0]['name'] == 'g_au')echo 'selected="selected"'; ?>>google.co.au</option>

													</optgroup>
												</select>
											</div>
											
											<div class="col-lg-12 form-group">
												<textarea id="keywords" class="form-control" rows="20" placeholder="Enter your keywords, one per line, that you would like to monitor"><?php echo $keywordsDetail; ?></textarea>
											</div>
										</div>
		                            </div>
									
									<div class="col-lg-6">
										<div class="form-group col-lg-12">
										<label for="include_mobile_search">
										<span>Include Mobile Search</span>
										</label>
											<div class="myinput_pane">
												<label class="switch switch-green">
												<input class="switch-input" type="checkbox" <?php if($domainDetails['mobile_search'] == 1)echo 'checked="checked"'; ?> id="include_mobile_search" name="include_mobile_search" >
												<span class="switch-label" data-off="Off" data-on="On"></span>
												<span class="switch-handle"></span>
												</label>
											</div>
										</div>
		                            </div>
								</div>
							</fieldset>
						
                      	</form>

                    </div>
					
				</div>

			</div>

      	<!-- END PAGE CONTENT -->
      	</div>
    <!-- END MAIN CONTENT -->
    </section>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'footer_new');
?>   
<script type="text/javascript">
	var connectToGoogle = 0;
   	var googleAccount = 0;
   	var pageHeader = "";
   	var pageBody = "";
   	var pageFooter = "";
	
	function create_project(){
		var connect_to_google = 0;
		var monitor_malware = 0;
		var monitor_website_uptime = 0;
		var crawl_error_webmaster = 0;
		var search_query_webmaster = 0;
		var include_mobile_search = 0;
		var include_local_search = 0;
		var monitor_page_issues = 0;
		var is_ecommerce = 0;
		var towns = '';
		
		// var towns_arr = new Array();
		// $('.mytown').each(function(){
		//     if($(this).val() != ''){
		//         towns_arr.push($(this).val());
		//     }
		// })
		var engines = $('#engines').val();
		engines = engines ? engines.join(", ") : '';
		// towns = towns_arr.length ? towns_arr.join(", ") : '';
		if( typeof $('input[name=connect_to_google]:checked').val() != 'undefined') {
			connect_to_google = 1;
		}	
		if( typeof $('input[name=monitor_malware]:checked').val() != 'undefined') {
			monitor_malware = 1;
		}
		if( typeof $('input[name=monitor_website_uptime]:checked').val() != 'undefined') {
			monitor_website_uptime = 1;
		}	
		
		if( typeof $('input[name=crawl_error_webmaster]:checked').val() != 'undefined') {
			crawl_error_webmaster = 1;
		}	
		if( typeof $('input[name=search_query_webmaster]:checked').val() != 'undefined') {
			search_query_webmaster = 1;
		}	
		if( typeof $('input[name=include_mobile_search]:checked').val() != 'undefined') {
			include_mobile_search = 1;
		}	
		if( typeof $('input[name=is_ecommerce]:checked').val() != 'undefined') {
			is_ecommerce = 1;
		}	
		// if( typeof $('input[name=include_local_search]:checked').val() != 'undefined') {
		// 	include_local_search = 1;
		// }	
		if( typeof $('input[name=monitor_page_issues]:checked').val() != 'undefined') {
			monitor_page_issues = 1;
		}	
		var keywords = $('#keywords').val();
		jQuery.ajax({
	        type: "POST",
	        url: "<?php echo base_url(); ?>" + "auth/auth/editDomain",
	        data: {
	            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', 
	            connectToGoogle: connect_to_google, 
	            domain: $('#domain_name').val(), 
	            ga_account: $('#gaAccounts').val(),  
	            monitorMalware: monitor_malware, 
	            monitorUptime: monitor_website_uptime, 
	            adminURL: $('input[name=adminURL]').val(),
	            adminUsername: $('input[name=adminUsername]').val(),
	            engines: engines, 
	            keywords: keywords, 
	            mobile_search: include_mobile_search, 
	            // local_search: include_local_search, 
	            monitor: monitor_page_issues,
	            // towns:towns,
	            webmaster: crawl_error_webmaster,
	            page_header: $('#page_header').val(),
	            page_body:$('#page_body').val(),
	            page_footer:$('#page_footer').val(), 
	            is_ecommerce:is_ecommerce,
	            search_analytics: search_query_webmaster,
	            domainId:'<?php echo $domainId; ?>',
	            frequency:$('#frequency').val(),
	            subusers:$('#subusers').val(),
	            groups:$('#groups').val()
	        },
	        success: function (data) {
	            $('.domain-selected').val(data.domainURL);
	            $('.domain-select').val(data.domainHost);
	            domain_Id = data.domainId;

	        }
	    }).done(function (data) {
	        if (typeof domain_Id !== 'undefined') {
	            window.location.href = "<?php echo base_url(); ?>auth/dashboard/" + domain_Id;
	        } else {
	            window.location.href = "<?php echo base_url(); ?>auth/dashboard/";
	        }

	    });
	}


	// $('.mygtbutton').click(function(){
	// 	$('.add_town_div').append($('.repeat_town_div').html());
	// })
	var SCOPE = 'https://www.googleapis.com/auth/analytics.readonly https://www.googleapis.com/auth/analytics https://www.googleapis.com/auth/analytics.edit https://www.googleapis.com/auth/analytics.manage.users https://www.googleapis.com/auth/analytics.manage.users.readonly https://www.googleapis.com/auth/analytics.manage.users.readonly https://www.googleapis.com/auth/webmasters';
	var CLIENTID = "<?php echo $client_id ?>";
	var REDIRECT = '<?php echo $redirect_uri ?>';
	var TYPE = 'code';
	var Access_Type = 'offline';
	var ApprovalPrompt = 'force';
	//var _url = OAUTHURL + 'scope=' + SCOPE + '&client_id=' + CLIENTID + '&redirect_uri=' + REDIRECT + '&response_type=' + TYPE + '&access_type' + Access_Type + '&approval_prompt' + ApprovalPrompt;
	var _url = "https://accounts.google.com/o/oauth2/auth?response_type=" + TYPE + "&access_type=" + Access_Type + "&client_id=" + CLIENTID + "&redirect_uri=" + REDIRECT + "&scope=" + SCOPE + "&approval_prompt=" + ApprovalPrompt;
	var acToken;
	var tokenType;
	var expiresIn;
	var refToken;
	var user;

	function login() {
	    var win = window.open(_url, "windowname1", 'width=800, height=600');

	    var pollTimer = window.setInterval(function () {
	        if (typeof win.document !== "undefined") {


	            if (win.document.URL.indexOf(REDIRECT) !== -1) {
	                window.clearInterval(pollTimer);
	                connectToGoogle = 1;
	                win.close();

	                $.ajax({
	                    type: "POST",
	                    url: "<?php echo base_url(); ?>analytics/analytics/getUserGAAccounts",
	                    dataType: 'json',
	                    data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
	                    success: function (data) {
	                        $('#gaAccounts').html("");
	                        $.each(data, function (key, value) {
	                            $('#gaAccounts')
	                                    .append($("<option></option>")
	                                            .attr("value", value.id)
	                                            .text(value.name));
	                        });

	                    },
	                    complete: function () {
	                        $('#ga_account').css('display', 'block');
	                    }
	                });
	           //validateToken(acToken);
	            }
	        }
	    }, 100);

	    return true;
	}
	
	function login_webmaster() {
	    var win = window.open(_url, "windowname1", 'width=800, height=600');

	    var pollTimer = window.setInterval(function () {
	        if (typeof win.document !== "undefined") {


	            if (win.document.URL.indexOf(REDIRECT) !== -1) {
	                window.clearInterval(pollTimer);
	                connectToGoogle = 1;
	                win.close();

	               $.ajax({
	                    type: "POST",
	                    url: "<?php echo base_url(); ?>analytics/analytics/getUserGAAccounts",
	                    dataType: 'json',
	                    data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
	                    success: function (data) {
	                        

	                    },
	                    complete: function () {
	                       
	                    }
	                });
	            }
	        }
	    }, 100);

	    return true;
	}


	$('input[name=monitor_website_uptime]').click(function(){
		if( typeof $('input[name=monitor_website_uptime]:checked').val() != 'undefined') {
			// $('#google_webmaster_details').hide();
			// $('#page_details').show();
		}else{
			$('#google_webmaster_details').show();
			// $('#page_details').hide();
		}		
	});

	//added later
	var google_connect = 0;
	if( typeof $('input[name=connect_to_google]:checked').val() != 'undefined') {
		google_connect = 1;
	}
	if (google_connect == 1) {

	    connectToGoogle = $('#connectToGoogleValue').val();
	     if(connectToGoogle  == 1) {
	        if (googleAccount == 0) {
	            $('#ga_account').css('display', 'none');

	            $.ajax({
	                type: "POST",
	                url: "<?php echo base_url(); ?>analytics/analytics/getUserGAAccounts",
	                dataType: 'json',
	                data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
	                success: function (data) {
	                    $('#gaAccounts').html("");
	                    var gaAccountExist  =  false;

	                    $.each(data, function (key, value) {

	                        $('#gaAccounts')
	                                .append($("<option></option>")
	                                        .attr("value", value.id)
	                                        .text(value.name));
	                        googleAccount = 1;
	                        if(value.id == '<?php echo $domainDetails['ga_account']; ?>'){
	                        	gaAccountExist = true;
	                        }
	                    });
	                    if(gaAccountExist == false) {
	                    	$('#gaAccounts')
	                    	        .append($("<option></option>")
	                    	                .attr("value", '<?php echo $domainDetails['ga_account']; ?>')
	                    	                .text('<?php echo $domainDetails['ga_account']; ?> (Added from other account)'));
	                    	$('#gaAccounts').val('<?php echo $domainDetails['ga_account']; ?>');
	                    	$("#gaAccounts").select2("val", "<?php echo $domainDetails['ga_account']; ?>"); //set the value	
	                    }else{
	                    	$('#gaAccounts').val('<?php echo $domainDetails['ga_account']; ?>');
	                    	$("#gaAccounts").select2("val", "<?php echo $domainDetails['ga_account']; ?>"); //set the value
	                    }
	                    


	                },
	                complete: function () {
	                    // $('#ga_account').css('display', 'block');
	                }
	            });
	        } else {
	            // $('#ga_account').css('display', 'block');
	        }
	    }else{
	    	if('<?php echo $domainDetails['ga_account']; ?>'!=''){
	    		$('#gaAccounts')
	    		        .append($("<option></option>")
	    		                .attr("value", '<?php echo $domainDetails['ga_account']; ?>')
	    		                .text('<?php echo $domainDetails['ga_account']; ?> (Added from other account)'));
	    		$('#gaAccounts').val('<?php echo $domainDetails['ga_account']; ?>');
	    		$("#gaAccounts").select2("val", "<?php echo $domainDetails['ga_account']; ?>"); //set the value		
	    	}
	    	
	    }

	} else {
	    $('#gaAccounts')
	            .html($("<option></option>")
	                    .attr("value", '0')
	                    .text('select'));
	    googleAccount = 0;
	    // $('#ga_account').css('display', 'none');
	}

	//added later
	
	$('input[name=connect_to_google]').click(function () {
	    pageHeader = $('#page_header').val();
	    pageBody = $('#page_body').val();
	    pageFooter = $('#page_footer').val();
	    var google_connect = 0;
	    if( typeof $('input[name=connect_to_google]:checked').val() != 'undefined') {
	    	google_connect = 1;
	    	$('#google_webmaster_details').show();
	    	// $('#page_details').hide();
	    }else{
	    	// $('#google_webmaster_details').hide();
	    	// $('#page_details').show();
	    }	
	    // if ($('#ga_account').hasClass('hide')) {
	    //     $('#ga_account').removeClass('hide');
	    // }
	    // if (!$('#uptime_option').hasClass('hide')) {
	    //     $('#uptime_option').addClass('hide');
	    // }
	    if (google_connect == 1) {

	        connectToGoogle = $('#connectToGoogleValue').val();
	        if (connectToGoogle != 1) {
	            login();
	        } else {
	            if (googleAccount == 0) {
	                $('#ga_account').css('display', 'none');
	                $.ajax({
	                    type: "POST",
	                    url: "<?php echo base_url(); ?>analytics/analytics/getUserGAAccounts",
	                    dataType: 'json',
	                    data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
	                    success: function (data) {
	                        $('#gaAccounts').html("");
	                        var gaAccountExist = false
	                        $.each(data, function (key, value) {

	                            $('#gaAccounts')
	                                    .append($("<option></option>")
	                                            .attr("value", value.id)
	                                            .text(value.name));
	                            googleAccount = 1;
	                            if(value.id == '<?php echo $domainDetails['ga_account']; ?>') {
	                            	gaAccountExist = true;
	                            }
	                        });
	                        
	                        if(gaAccountExist == false) {
	                        	$('#gaAccounts')
	                        	        .append($("<option></option>")
	                        	                .attr("value", '<?php echo $domainDetails['ga_account']; ?>')
	                        	                .text('<?php echo $domainDetails['ga_account']; ?> (Added by subaccount)'));
	                        	$('#gaAccounts').val('<?php echo $domainDetails['ga_account']; ?>');
	                        	$("#gaAccounts").select2("val", "<?php echo $domainDetails['ga_account']; ?>"); //set the value	
	                        }else{
	                        	$('#gaAccounts').val('<?php echo $domainDetails['ga_account']; ?>');
	                        	$("#gaAccounts").select2("val", "<?php echo $domainDetails['ga_account']; ?>"); //set the value
	                        }
	                    },
	                    complete: function () {
	                        $('#ga_account').css('display', 'block');
	                    }
	                });
	            } else {
	                $('#ga_account').css('display', 'block');
	            }
	        }

	    } else {
	        $('#gaAccounts')
	                .html($("<option></option>")
	                        .attr("value", '0')
	                        .text('select'));
	        googleAccount = 0;
	        $('#ga_account').css('display', 'none');
	    }
	});
	
	$('input[name=google_webmaster]').click(function () {
	    var webmaster = $(this).val();
	    var webmaster = $(this).val();
	    pageHeader = $('#page_header').val();
	    pageBody = $('#page_body').val();
	    pageFooter = $('#page_footer').val();
	    if (!$('#uptime_option').hasClass('hide')) {
	        $('#uptime_option').addClass('hide');
	    }
	    if (!$('#ga_account').hasClass('hide')) {
	        $('#ga_account').addClass('hide');
	    }
	    if (webmaster == 1) {
	        connectToGoogle = $('#connectToGoogleValue').val();
	        if (connectToGoogle != 1) {
	            login_webmaster();
	        }
	    }
	});
	
	$('input[name=google_search_analytics]').click(function () {
	    var analytics = $(this).val();
	    pageHeader = $('#page_header').val();
	    pageBody = $('#page_body').val();
	    pageFooter = $('#page_footer').val();
	    if (!$('#uptime_option').hasClass('hide')) {
	        $('#uptime_option').addClass('hide');
	    }
	    if (!$('#ga_account').hasClass('hide')) {
	        $('#ga_account').addClass('hide');
	    }
	    if (analytics == 1) {
	    	console.log(324234234);
	        connectToGoogle = $('#connectToGoogleValue').val();
	        if (connectToGoogle != 1) {
	            login_webmaster();
	        }
	    }
	});			

</script>
