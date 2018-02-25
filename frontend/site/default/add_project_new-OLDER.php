<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header');
?>	
        <!-- BEGIN PAGE CONTENT -->
		  
	<div class="row">
		<div class="col-md-12">

			<div class="panel">
				<div class="panel-content">
		            <form class="wizard " data-style="sea" role="form" novalidate onsubmit="create_project()" method="post" action="javascript:void(0);">
					  
		                <fieldset>
		                  <legend>Add Domain</legend>
						  
		                  <div class="row">
						  
		                    <div class="col-lg-6">
								<div class="row form-group">
									<div class="col-lg-12">
										<label>Domain Name<span class="required">*</span></label>
										<input id="domain_name" class="form-control" type="text" required="" required data-parsley-group="block0" placeholder="Domain Name" data-validate-words="2" name="domain_name"  data-toggle="tooltip" data-placement="bottom" title="please include http:// or https://">
									</div>
								</div>
								<div class="row item form-group">
									<div class="col-lg-12">
										<div class="row form-group">
											<div class="col-md-9">
												<label class="control-label label_pane" for="monitor_website_uptime">
													<span>Would you like to Monitor website uptime?</span>
												</label>
											</div>

											<div class="col-md-3">
												<label class="switch switch-green">
													<input class="switch-input" type="checkbox" checked="" name="monitor_website_uptime" id="monitor_website_uptime">
													<span class="switch-label" data-off="Off" data-on="On"></span>
													<span class="switch-handle"></span>
												</label>
											</div>
										</div>

										<div class="row form-group">
											<div class="col-md-9">
												<label class="control-label" for="monitor_malware">
													<span>Monitor Malware</span>
												</label>
											</div>

											<div class="col-md-3">
												<label class="switch switch-green">
													<input class="switch-input" type="checkbox"  name="monitor_malware" id="monitor_malware">
													<span class="switch-label" data-off="Off" data-on="On"></span>
													<span class="switch-handle"></span>
												</label>
											</div>
										</div>

										<div class="row form-group">
											<div class="col-md-9">
												<label class="control-label" for="is_ecommerce">
													<span>Is your site ecommerce site ?</span>
												</label>
											</div>

											<div class="col-md-3">
												<label class="switch switch-green">
													<input class="switch-input" type="checkbox"  name="is_ecommerce" id="is_ecommerce">
													<span class="switch-label" data-off="Off" data-on="On"></span>
													<span class="switch-handle"></span>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>	
							
		                    <div class="col-lg-6" id="page_details">
								<div class="row form-group">
									<div class="col-lg-12">
										<label class="mycontrol-label">Page Header<span class="required">*</span></label>
										<input id="page_header" class="form-control" type="text" required="" placeholder="Page Header to Search" data-validate-words="2" name="page_header" data-toggle="tooltip" data-placement="bottom" title="Please copy and paste the actual HTML from your 'View Source' page for your page title for the best results">
									</div>
								</div>
								
								<div class="row form-group">
									<div class="col-lg-12">
										<label class="mycontrol-label">Page Body<span class="required">*</span></label>
										<input id="page_body" class="form-control" type="text" required="" placeholder="Page Body to Search" data-validate-words="2" name="page_body" data-toggle="tooltip" data-placement="bottom" title="Please copy paste actual Body text from your website">
									</div>
								</div>
								
								
								<div class="row form-group">
									<div class="col-lg-12">
										<label class="mycontrol-label">Page Footer<span class="required">*</span></label>
										<input id="page_footer" class="form-control" type="text" required="" placeholder="Page Footer to Search" data-validate-words="2" name="page_footer" data-toggle="tooltip" data-placement="bottom" title="Please copy and paste actual text from your footer">
									</div>
								</div>
								
								
								<div class="row form-group">
									<div class="col-lg-12">
										<label class="mycontrol-label">Frequency<span class="required">*</span></label>
										<select name="frequency" id="frequency" class="form-control">
											<option value="1">1 min</option>	
											<option value="5">5 min</option>	
											<option value="15">15 min</option>	
											<option value="30">30 min</option>	
											<option value="60">1 hour</option>	
											<option value="120">2 hours</option>	
											<option value="360">6 hours</option>	
											<option value="720">12 hours</option>	
											<option value="1440">24 hours</option>	
										</select>
									</div>
								</div>

								<div class="row form-group">
									<div class="col-lg-12">
										<label class="mycontrol-label">Assign subusers to domain</label>
										<select multiple="multiple" class="form-control" data-search="true" name="subusers" id="subusers" placeholder="choose subuser">
										<?php if($subusers): ?>
											<?php foreach($subusers as $subuser): ?>
												<option value="<?php echo $subuser['id']; ?>"><?php echo $subuser['first_name'].' '.$subuser['last_name']; ?></option>	
										<?php endforeach; ?>
										<?php endif; ?>		
										</select>
									</div>
								</div>

								<div class="row form-group">
									<div class="col-lg-12">
										<label class="mycontrol-label">Choose group to assign domain</label>
										<select multiple="multiple" class="form-control" data-search="true" name="groups" id="groups" placeholder="choose groups">
											<?php if($groups): ?>
											<?php foreach($groups as $group): ?>
											<option value="<?php echo $group['id']; ?>"><?php echo $group['group_name']; ?></option>
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
									<div class="row form-group">
										<div class="col-md-12">
											<label class="mycontrol-label">Admin URL<span class="required">*</span></label>
											<input id="adminURL" class="form-control" type="text" required="" placeholder="http://" data-validate-words="2" name="adminURL">
										</div>
										
										<div class="col-md-12">
											<label class="mycontrol-label">Admin Username<span class="required">*</span></label>
											<input id="adminUsername" class="form-control" type="text" required="" placeholder="Username" data-validate-words="2" name="adminUsername">
										</div>
									</div>

									<div class="row form-group">
										<div class="col-md-9">
											<label class="control-label label_pane" for="connect_to_google">
											<span>Connect to Google</span>
											</label>
										</div>
										<div class="col-md-3">
											<label class="switch switch-green">
											<input class="switch-input" type="checkbox"  id="connect_to_google" name="connect_to_google">
											<span class="switch-label" data-off="Off" data-on="On"></span>
											<span class="switch-handle"></span>
											</label>
										</div>
									</div>
									
									<div class="row form-group">
										<div class="col-md-9">
											<label class="control-label label_pane" for="monitor_malware">
											<span>Monitor Malware</span>
											</label>
										</div>
										<div class="col-md-3">
											<label class="switch switch-green">
											<input class="switch-input" type="checkbox"  name="monitor_malware" id="monitor_malware">
											<span class="switch-label" data-off="Off" data-on="On"></span>
											<span class="switch-handle"></span>
											</label>
										</div>
									</div>
									
									<div class="row form-group">
										<div class="col-md-9">
											<label class="control-label label_pane" for="crawl_error_webmaster">
											<span>Would you like to get Crawl Errors from Google Webmaster Tools?</span>
											</label>
										</div>
										<div class="col-md-3">
											<label class="switch switch-green">
											<input class="switch-input" type="checkbox" id="crawl_error_webmaster" name="crawl_error_webmaster">
											<span class="switch-label" data-off="Off" data-on="On"></span>
											<span class="switch-handle"></span>
											</label>
										</div>
									</div>
									
									<div class="row form-group">
										<div class="col-md-9">
											<label class="control-label label_pane" for="search_query_webmaster">
											<span>Would you like to track your Search Queries from Google Webmaster Tools?</span>
											</label>
										</div>
										<div class="col-md-3">
											<label class="switch switch-green">
											<input class="switch-input" type="checkbox" id="search_query_webmaster" name="search_query_webmaster">
											<span class="switch-label" data-off="Off" data-on="On"></span>
											<span class="switch-handle"></span>
											</label>
										</div>
									</div>
			                    </div>
		                    <div class="col-lg-6">
								<div style="font-size:16px;text-align:center;margin-top:50px;" id="controlwp_plugin_hint">
									Please download and activate the ControlWP plugin from <a href="<?php echo base_url(); ?>controlwp.zip" target="_blank">here</a>
								</div>	  

								<div class="col-lg-6" id="google_webmaster_details" style="display:none;">
								<input type="hidden" name="connectToGoogleValue" id="connectToGoogleValue" value="<?php echo $google_connect ?>">
									<div class="col-lg-12">
									<label>Choose your google webmaster account</label>
									<select name="gaAccounts" id="gaAccounts" class="form-control">
									    <option value="0">Select</option>
									</select>
									</div>
								</div>	
		                    </div>
							</div>
							
						</fieldset>
					  
						<fieldset>
							<legend> Monitor Keyword SERP</legend>
							<div class="row">
								<div class="col-lg-6">
									<div class="row">
										<div class="col-lg-12 form-group">
											<select class="form-control" multiple="" data-placeholder="Choose your search engine" name="engines" id="engines">
												<optgroup label="Search engine">
												  <option value="g_uk">google.co.uk</option>
												  <option value="g_us">google.com</option>
												  <option value="g_ca">google.co.ca</option>
												  <option value="g_au">google.co.au</option>
												</optgroup>
											</select>
										</div>
									
										<div class="col-lg-12 form-group">
											<textarea id="keywords" class="form-control" rows="20" placeholder="Enter your keywords, one per line, that you would like to monitor"></textarea>
										</div>
									</div>	
			                    </div>
							
								<div class="col-lg-6">
									
									<div class="row">
										<div class="item form-group col-lg-12">
											<div class="row mynomargintop">
											<label class="control-label label_pane" for="include_mobile_search">
											<span>Include Mobile Search</span>
											</label>
												<div class="myinput_pane">
													<label class="switch switch-green">
													<input class="switch-input" type="checkbox" checked="" id="include_mobile_search" name="include_mobile_search">
													<span class="switch-label" data-off="Off" data-on="On"></span>
													<span class="switch-handle"></span>
													</label>
												</div>
											</div>
											
										</div>
									</div>				
			                    </div>
							</div>
							
						</fieldset>
						
		                
						
		            </form>
				</div>
			</div>
		</div>
	</div>

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
			var connect_to_google = 'undefined' !== typeof $('input[name=connect_to_google]:checked').val() ? 1 : 0;
			var monitor_malware = 'undefined' !== typeof $('input[name=monitor_malware]:checked').val() ? 1 : 0;
			var monitor_website_uptime = 'undefined' !== typeof $('input[name=monitor_website_uptime]:checked').val() ? 1 : 0;
			var crawl_error_webmaster = 'undefined' !== typeof $('input[name=crawl_error_webmaster]:checked').val() ? 1 : 0;
			var search_query_webmaster = 'undefined' !== typeof $('input[name=search_query_webmaster]:checked').val() ? 1 : 0;
			var include_mobile_search = 'undefined' !== typeof $('input[name=include_mobile_search]:checked').val() ? 1 : 0;
			var include_local_search = 'undefined' !== typeof $('input[name=include_local_search]:checked').val() ? 1 : 0;
			var monitor_page_issues = 'undefined' !== typeof $('input[name=monitor_page_issues]:checked').val() ? 1 : 0;
			var keywords = $('#keywords').val();
			var engines = $('#engines').val();
			var towns_arr = [];
			var towns = '';
			var is_ecommerce = 0;
			
			var towns_arr = new Array();
			// $('.mytown').each(function(){
			//     if($(this).val() != ''){
			//         towns_arr.push($(this).val());
			//     }
			// })
			var engines = $('#engines').val();
			engines = engines ? engines.join(", ") : '';
			// towns = towns_arr.join(", ");
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
			if( typeof $('input[name=is_ecommerce]:checked').val() != 'undefined') {
				is_ecommerce = 1;
			}	
			if( typeof $('input[name=include_mobile_search]:checked').val() != 'undefined') {
				include_mobile_search = 1;
			}	
			if( typeof $('input[name=include_local_search]:checked').val() != 'undefined') {
				include_local_search = 1;
			}	
			if( typeof $('input[name=monitor_page_issues]:checked').val() != 'undefined') {
				monitor_page_issues = 1;
			}	
			var keywords = $('#keywords').val();

			jQuery.post("<?php echo base_url(); ?>" + "auth/auth/addDomain",{
				'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', 
				connectToGoogle: connect_to_google, 
				domain: $('#domain_name').val(), 
				ga_account: $('#gaAccounts').val(),  
				monitorMalware: monitor_malware, 
				adminURL: $('input[name=adminURL]').val(),
				adminUsername: $('input[name=adminUsername]').val(),
				monitorUptime: monitor_website_uptime,
				engines: engines, 
				keywords: keywords, 
				mobile_search: include_mobile_search, 
				local_search: include_local_search, 
				monitor: monitor_page_issues,
				towns:towns,
				webmaster: crawl_error_webmaster,
				page_header: $('#page_header').val(),
				page_body:$('#page_body').val(),
				page_footer:$('#page_footer').val(), 
				search_analytics: search_query_webmaster,
				is_ecommerce:is_ecommerce,
				frequency:$('#frequency').val(),
				subusers:$('#subusers').val(),
				groups:$('#groups').val()
			},function(data){
				if(data.type == 'success'){
			    	$('.domain-selected').val(data.domainURL);
			    	$('.domain-select').val(data.domainHost);
			    	domain_Id = data.domainId;		
			    	if (typeof domain_Id !== 'undefined') {
			    	    window.location.href = "<?php echo base_url(); ?>auth/dashboard/" + domain_Id;
			    	} else {
			    	    window.location.href = "<?php echo base_url(); ?>auth/dashboard/";
			    	}
				}
				if(data.type == 'error'){
					alert(data.msg);
					return false;	
				}
			},'json')

			$('.mytown').each(function(){
			    if($(this).val() != ''){
			        towns_arr.push($(this).val());
			    }
			})
			
			towns = towns_arr.length ? towns_arr.join(", ") : "";
			engines = null !== engines ? engines.join(", ") : "";

			var requestData = {
				'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', 
		        connectToGoogle: connect_to_google, 
		        domain: $('#domain_name').val(), 
		        ga_account: $('#gaAccounts').val(),  
		        monitorMalware: monitor_malware, 
		        adminURL: $('input[name=adminURL]').val(),
		        adminUsername: $('input[name=adminUsername]').val(),
		        engines: engines, 
		        keywords: keywords, 
		        mobile_search: include_mobile_search, 
		        local_search: include_local_search, 
		        monitor: monitor_page_issues,
		        towns:towns,
		        webmaster: crawl_error_webmaster,
		        page_header: $('#page_header').val(),
		        page_body:$('#page_body').val(),
		        page_footer:$('#page_footer').val(), 
		        search_analytics: search_query_webmaster,
		        frequency:$('#frequency').val()
			};
			
			jQuery.ajax({
		        type: "POST",
		        url: "<?php echo base_url(); ?>" + "auth/auth/addDomain",
		        dataType: 'json',
		        data: requestData,
		        success: function (data) {
		        	if( data ){
			        	$('.domain-selected').val(data.domainURL);
			            $('.domain-select').val(data.domainHost);
			            var domain_Id = data.domainId;
			        }
		        }
		    }).done(function (data) {
		        if (typeof domain_Id !== 'undefined') {
		            window.location.href = "<?php echo base_url(); ?>auth/dashboard/" + domain_Id;
		        } else {
		            window.location.href = "<?php echo base_url(); ?>auth/dashboard/";
		        }
		    });

		}

		$('.mygtbutton').click(function(){
			$('.add_town_div').append($('.repeat_town_div').html());
		})
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
				$('#google_webmaster_details').hide();
				$('#controlwp_plugin_hint').show();
			}else{
				$('#google_webmaster_details').show();
				$('#controlwp_plugin_hint').hide();
			}		
		})
		



		$('input[name=connect_to_google]').click(function () {

		    pageHeader = $('#page_header').val();
		    pageBody = $('#page_body').val();
		    pageFooter = $('#page_footer').val();
		    var google_connect = 0;
		    if( typeof $('input[name=connect_to_google]:checked').val() != 'undefined') {
		    	google_connect = 1;
		    	$('#google_webmaster_details').show();
		    	$('#controlwp_plugin_hint').hide();
		    	
		    }else{
		    	$('#google_webmaster_details').hide();
		    	$('#controlwp_plugin_hint').show();
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
		                        $.each(data, function (key, value) {
		                            $('#gaAccounts')
		                                    .append($("<option></option>")
		                                            .attr("value", value.id)
		                                            .text(value.name));
		                            googleAccount = 1;
		                        });

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
		        connectToGoogle = $('#connectToGoogleValue').val();
		        if (connectToGoogle != 1) {
		            login_webmaster();
		        }
		    }
		});			
		$(function () {
		  $('[data-toggle="tooltip"]').tooltip()
		});
</script>
